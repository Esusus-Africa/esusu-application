<?php
include("connect.php");

include("restful_apicalls.php");

$search_mloan = mysqli_query($link, "SELECT * FROM pay_schedule WHERE direct_debit_status = 'Sent' AND status = 'UNPAID'");
while($get_mloan = mysqli_fetch_array($search_mloan))
{
    $lid = $get_mloan['lid'];
    $requestId = $get_mloan['requestid'];
    $schedule_id = $get_mloan['id'];
    $amount_to_pay = $get_mloan['payment'];
    
    $search_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'");
    $fetch_loan = mysqli_fetch_array($search_loan);
    $mandateId = $fetch_loan['mandate_id'];
    //Institution ID
    $instId = $fetch_loan['branchid'];
    //Branch ID
    $branchId = $fetch_loan['sbranchid'];
    //Vendor ID
    $vendorId = $fetch_loan['vendorid'];
    //Default Loan Balance
    $loanBalance = $fetch_loan['balance'];
    //Borrower Account ID
    $account_no = $fetch_loan['baccount'];
    //Loan Officer ID
    $agent = $fetch_loan['agent'];
    
    //CONFIRM STAFF ID
    $search_staffid = mysqli_query($link, "SELECT * FROM user WHERE id = '$agent' OR name = '$agent'");
    $fetch_staffid = mysqli_fetch_array($search_staffid);
    $lofficer = $fetch_staffid['id'];
    
    $search_borrowerinfo = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$account_no'");
    $fetch_borrowerinfo = mysqli_fetch_array($search_borrowerinfo);
    //Borrower Last Name
    $lastName = $fetch_borrowerinfo['lname'];
    //Borrower Full Name
    $customer = $fetch_borrowerinfo['lname'].' '.$fetch_borrowerinfo['fname'];
    //Borrower Phone
    $phone = $fetch_borrowerinfo['phone'];
    //Borrower Email
    $em = $fetch_borrowerinfo['email'];
    //Borrower Username
    $uname = $fetch_borrowerinfo['username'];
        	
    //Calculate New Balance (Default Loan Balance - Amount Debited by Remita)
    $final_bal = $loanBalance - $amount_to_pay;
    
    //Detect the Right Loan Status
    $newPStatus = ($final_bal <= 0) ? "PAID" : "PART-PAID";
    
    //Confirm Institution Wallet Balance
    $search_institutioninfo = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$instId'");
    $fetch_institutioninfo = mysqli_fetch_array($search_institutioninfo);
    $inst_wallet_balance = $fetch_institutioninfo['wallet_balance'];
    
    //Confirm SMS Charges
    $search_systemset = mysqli_query($link, "SELECT * FROM systemset");
    $fetch_systemset = mysqli_fetch_array($search_systemset);
    $sms_charges = $fetch_systemset['fax'];
    
    //Confirm SMS Gateway Credentials
    $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;
    
    //REMITAL CREDENTIALS
    $verify_icurrency = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instId'");
    $fetch_icurrency = mysqli_fetch_array($verify_icurrency);
    $remita_merchantid = $fetch_icurrency['remitaMerchantId'];
    $remita_apikey = $fetch_icurrency['remitaApiKey'];
    $remita_serviceid = $fetch_icurrency['remitaServiceId'];
    $api_token = $fetch_icurrency['remitaApiToken'];
    $sysabb = $fetch_icurrency['sender_id'];
    $our_currency = $fetch_icurrency['currency'];
    
    $concat_param = $mandateId.$remita_merchantid.$requestId.$remita_apikey;
    $hash = hash("sha512", $concat_param);
    
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'remita'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $url = $fetch_restapi->api_url;
        
    $api_url = $url."remita/exapp/api/v1/send/api/echannelsvc/echannel/mandate/payment/status";
    
    $postdata = array(
        'merchantId' => $remita_merchantid,
        'mandateId' => $mandateId,
        'hash'  => $hash,
        'requestId' => $requestId
        );
        
    $make_call = callAPI('POST', $api_url, json_encode($postdata));
    $output2 = trim(json_decode(json_encode($make_call), true),'jsonp ();');
    $result = json_decode($output2, true);
    
    //print_r($postdata);
    
    if($result['statuscode'] === "00" && $result['status'] === "Successful"){
        
        $refid = $result['RRR'];
        $wallet_date_time = $result['lastStatusUpdateTime'];
        $date_time = date("Y-m-d", strtotime($wallet_date_time));
        
        //Detect Duplicate Payment Entry
        $comfirm_duplicatepayment = mysqli_query($link, "SELECT * FROM payments WHERE refid = '$refid'");
        $get_duplicate = mysqli_num_rows($comfirm_duplicatepayment);
        
        if($get_duplicate === 1){
            
            //silently forget this ever happened
    	  	echo "";
        	    
        }
        else{
            
            $sms = "$sysabb>>>Dear $lastName! Your repayment of ".$our_currency.number_format($amount_to_pay,2,'.',',')." with Loan ID: $lid has been recieved. ";
            $sms .= "Your Loan Balance is: ".$our_currency.number_format($final_bal,2,'.',',')." Thanks.";
                    
            //SMS CHARGES CALCULATION
            $max_per_page = 152;
            $sms_length = strlen($sms);
            $calc_length = ceil($sms_length / $max_per_page);
                    	
            $total_sms_charges = $calc_length * $sms_charges;
            $mywallet_balance = $inst_wallet_balance - $total_sms_charges;
            
            include('../cron/send_general_sms.php');
        	include('../cron/send_repayemail.php');
        	
        	mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$instId','$refid','$phone','','$total_sms_charges','Charges','NGN','Charges','SMS Content: $sms','successful','$wallet_date_time','$account_no','$mywallet_balance','')");
            mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$instId','institution','$sysabb','$phone','$sms','Sent',NOW())");
        	mysqli_query($link, "INSERT INTO payments VALUES(null,'$lofficer','$lid','$refid','$account_no','$customer','$final_bal','$date_time','$amount_to_pay','paid','$instId','$vendorId','$branchId')") or die ("Error: " . mysqli_error($link));
        	mysqli_query($link, "UPDATE loan_info SET balance = '$final_bal', p_status = '$newPStatus' WHERE lid = '$lid'") or die ("Error: " . mysqli_error($link));
        	    
        	$search_recent_payment = mysqli_query($link, "SELECT * FROM payments WHERE lid = '$lid' AND refid = '$refid' ORDER BY id DESC") or die ("Error: " . mysqli_error($link));
            $fetch_recent_payment = mysqli_fetch_array($search_recent_payment);
            $pid = $fetch_recent_payment['id'];
                
        	mysqli_query($link, "UPDATE pay_schedule SET pid = '$pid', status = 'PAID', direct_debit_status = 'Debited' WHERE id = '$schedule_id'") or die ("Error: " . mysqli_error($link));

        }
        
    }
    
}
?>