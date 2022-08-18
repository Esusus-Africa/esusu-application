<?php
/*header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");

$link = mysqli_connect('localhost','esusulive_esusuapp','BoI9YR^zqs%M','esusulive_esusuapp') or die('Unable to Connect to Database');

if($_SERVER['REQUEST_METHOD'] === "POST") {
	
	$input = @file_get_contents("php://input");
	
	$response = json_decode($input);
	
	$notificationType = $response->notificationType;
	
	if($notificationType === "DEBIT")
	{
	    foreach($response->lineItems as $items){
	    
    	    $refid = $items->requestId;
        	$mandateId = $items->mandateId;
        	$date_time = date("Y-m-d", strtotime($items->debitDate));
        	$setupRequestId = $items->setupRequestId;
        	//Amount Debited by Remita
        	$amount_to_pay = $items->amount;
        	
        	$search_loaninfo = mysqli_query($link, "SELECT * FROM loan_info WHERE request_id = '$setupRequestId'");
        	$fetch_loaninfo = mysqli_fetch_array($search_loaninfo);
        	//Institution ID
        	$instId = $fetch_loaninfo['branchid'];
        	//Branch ID
        	$branchId = $fetch_loaninfo['sbranchid'];
        	//Vendor ID
        	$vendorId = $fetch_loaninfo['vendorid'];
        	//Loan ID
        	$lid = $fetch_loaninfo['lid'];
        	//Default Loan Balance
        	$loanBalance = $fetch_loaninfo['balance'];
        	//Borrower Account ID
        	$account_no = $fetch_loaninfo['baccount'];
        	
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
        	
        	//Fetch Repayment Schedule
        	$search_repaymentschedule = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' AND status = 'UNPAID' ORDER BY id ASC");
        	$fetch_repaymentschedule = mysqli_fetch_array($search_repaymentschedule);
        	$schedule_id = $fetch_repaymentschedule['id'];
        	
        	//Detect Duplicate Payment Entry
        	$comfirm_duplicatepayment = mysqli_query($link, "SELECT * FROM payments WHERE refid = '$refid'");
        	$get_duplicate = mysqli_num_rows($comfirm_duplicatepayment);
        	
        	$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instId'");
            $fetch_memset = mysqli_fetch_array($search_memset);
            $sysabb = $fetch_memset['sender_id'];
            $our_currency = $fetch_memset['currency'];
        	
        	$sms = "$sysabb>>>Dear $lastName! Your repayment of ".$our_currency.number_format($amount_to_pay,2,'.',',')." with Loan ID: $lid has been recieved. ";
            $sms .= "Your Loan Balance is: ".$our_currency.number_format($final_bal,2,'.',',')." Thanks.";
            
            //SMS CHARGES CALCULATION
            $max_per_page = 153;
            $sms_length = strlen($sms);
            $calc_length = ceil($sms_length / $max_per_page);
            	
            $total_sms_charges = $calc_length * $sms_charges;
            $mywallet_balance = $inst_wallet_balance - $total_sms_charges;
            $wallet_date_time = date("Y-m-d h:i:s");
        	
        	if($get_duplicate === 1){
        	    
        	    //silently forget this ever happened
    	  	    echo "";
        	    
        	}
        	else{
        	    
        	    include('../cron/send_general_sms.php');
        		include('../cron/send_repayemail.php');
        		
        		mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$instId','$refid','$phone','$total_sms_charges','NGN','system','SMS Content: $sms','successful','$wallet_date_time')");
                mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$instId','institution','$sysabb','$phone','$sms','Sent',NOW())");
        	    mysqli_query($link, "INSERT INTO payments VALUES(null,'system','$lid','$refid','$account_no','$customer','$final_bal','$date_time','$amount_to_pay','paid','$instId','$vendorId','$branchId')") or die ("Error: " . mysqli_error($link));
        	    mysqli_query($link, "UPDATE loan_info SET balance = '$final_bal', p_status = '$newPStatus' WHERE lid = '$lid' AND request_id = '$setupRequestId'") or die ("Error: " . mysqli_error($link));
        	    
        	    $search_recent_payment = mysqli_query($link, "SELECT * FROM payments WHERE lid = '$lid' AND refid = '$refid' ORDER BY id DESC") or die ("Error: " . mysqli_error($link));
                $fetch_recent_payment = mysqli_fetch_array($search_recent_payment);
                $pid = $fetch_recent_payment['id'];
                
        	    mysqli_query($link, "UPDATE pay_schedule SET pid = '$pid', status = 'PAID' WHERE id = '$schedule_id'") or die ("Error: " . mysqli_error($link));
        	    
        	}
	    }
	}
	
}*/
?>