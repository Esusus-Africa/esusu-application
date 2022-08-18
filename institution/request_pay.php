<?php 
error_reporting(0); 
include "../config/session1.php";
?>  
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
		<div class="loader"></div>
<?php
include("../config/restful_apicalls.php");

$result = array();
$tid = $_SESSION['tid'];
$id = $_GET['id'];
$auth_code = $_GET['auth'];
$perc = $_GET['perc'];

$search_key = mysqli_query($link, "SELECT * FROM authorized_card WHERE authorized_code = '$auth_code'") or die ("Error:" . mysqli_error($link));
$get_key = mysqli_fetch_object($search_key);

$lid = $get_key->lid;
$account_no = $get_key->acn;
$email = $get_key->email;
$date = date("Y-m-d");

$search_schedule = mysqli_query($link, "SELECT * FROM pay_schedule WHERE id = '$id'") or die ("Error:" . mysqli_error($link));
$get_schedule = mysqli_fetch_object($search_schedule);

$search_loaninfo = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'") or die ("Error:" . mysqli_error($link));
$get_loaninfo = mysqli_fetch_object($search_loaninfo);

$amount_to_pay = $get_schedule->payment;
$bal = $get_loaninfo->balance;
$bal2 = ($get_loaninfo->balance) - $cal_amt_deducted;
$branchid = $get_loaninfo->branchid;

$search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$branchid'");
$fetch_inst = mysqli_fetch_array($search_inst);
$subaccount_code = $fetch_inst['subaccount_code'];

$search_borrower = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$account_no'") or die ("Error:" . mysqli_error($link));
$get_borrower = mysqli_fetch_object($search_borrower);

$fname = $get_borrower->fname;
$lname = $get_borrower->lname;
$phone = $get_borrower->phone;
$em = $get_borrower->email;
$uname = $get_borrower->username;

$customer = $fname.' '.$lname;

$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_object($select1);

$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$fetch_memset = mysqli_fetch_array($search_memset);
//$sys_abb = $get_sys['abb'];
$sysabb = $fetch_memset['sender_id'];
$our_currency = $fetch_memset['currency'];

$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$institution_id' AND status = 'Activated'");
$fetch_gateway = mysqli_fetch_object($search_gateway);
$gateway_uname = $fetch_gateway->username;
$gateway_pass = $fetch_gateway->password;
$gateway_api = $fetch_gateway->api;

$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'charge_authorization'");
$fetch_restapi = mysqli_fetch_object($search_restapi);
$api_url = $fetch_restapi->api_url;

$refid = myreference(10);

$postdata =  array(
    "SECKEY"    => $row1->secret_key,
    "token"     => $auth_code,
    "currency"  => $icurrency,
    "country"   => "NG",
    "amount"    => $amount_to_pay,
    "email"     => $email,
    "firstname" => $fname,
	"lastname"  => $lname,
	"IP"        => $_SERVER['REMOTE_ADDR'],
	"narration" => "Loan Repayment",
	"txRef"     => $refid,
	"subaccounts"   => [
	    "id"    => $subaccount_code
	    ], 
	"meta"  => [
	    "metaname"  => "Loan ID",
	    "metavalue" => $lid
	    ]
    );
					
$make_call = callAPI('POST', $api_url, json_encode($postdata));
$result = json_decode($make_call, true);

//print_r($result);

if($result['status'] == "success"){
    
    $verify_balance_end = mysqli_query($link, "SELECT * FROM pay_schedule WHERE id = '$id' AND status = 'UNPAID'") or die ("Error:" . mysqli_error($link));
    $get_search4 = mysqli_fetch_array($verify_balance_end);
    $date_time = date("Y-m-d");
    $original_bal = $get_search4['balance'];

    if($original_bal == "0")
    {
        $final_bal = $bal - $amount_to_pay;
        $update = mysqli_query($link, "UPDATE loan_info SET balance = '0', p_status = 'PAID' WHERE lid = '$lid'") or die (mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO payments VALUES(null,'$tid','$lid','$refid','$account_no','$customer','$final_bal','$date_time','$amount_to_pay','paid','$institution_id')") or die (mysqli_error($link));
        $update = mysqli_query($link, "UPDATE pay_schedule SET status = 'PAID' WHERE id = '$id'") or die (mysqli_error($link));
        
        $sms = "$sysabb>>>Dear $customer! Your repayment of ".$icurrency.number_format($amount_to_pay,2,'.',',')." has been initiated successfully. ";
        $sms .= "Your Loan Balance is: ".$icurrency.number_format($final_bal,2,'.',',')." Thanks.";
        
        include('../cron/send_general_sms.php');
    	include('../cron/send_repayemail.php');
    	
        echo '<meta http-equiv="refresh" content="2;url=listpayment.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
        echo '<br>';
        echo'<span class="itext" style="color: blue;">Saving Payment.....Please Wait!</span>';
    }
    else{
        $final_bal = $bal - $amount_to_pay;
        $update = mysqli_query($link, "UPDATE loan_info SET balance = '$final_bal', p_status = 'PART-PAID' WHERE lid = '$lid'") or die (mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO payments VALUES(null,'$tid','$lid','$refid','$account_no','$customer','$final_bal','$date_time','$amount_to_pay','paid','$institution_id')") or die (mysqli_error($link));
        $update = mysqli_query($link, "UPDATE pay_schedule SET status = 'PAID' WHERE id = '$id'") or die (mysqli_error($link));
        
        $sms = "$sysabb>>>Dear $customer! Your repayment of ".$icurrency.number_format($amount_to_pay,2,'.',',')." has been initiated successfully. ";
        $sms .= "Your Loan Balance is: ".$icurrency.number_format($final_bal,2,'.',',')." Thanks.";
        
        include('../cron/send_general_sms.php');
    	include('../cron/send_repayemail.php');
        
        echo '<meta http-equiv="refresh" content="2;url=listpayment.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
        echo '<br>';
        echo'<span class="itext" style="color: blue;">Saving Payment.....Please Wait!</span>';
    }
    
}
else{
    $message = $result['message'];
    echo '<meta http-equiv="refresh" content="2;url=dueloans.php?tid='.$_SESSION['tid'].'&&mid=NDA1">';
    echo '<br>';
    echo'<span class="itext" style="color: blue;">'.$message.'</span>';
}
?>
</div>
</body>
</html>
