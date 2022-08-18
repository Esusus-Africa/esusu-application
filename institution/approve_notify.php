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
$refid = $_GET['refid'];
$final_date_time = date ('Y-m-d h:i:s');

$search_w = mysqli_query($link, "SELECT * FROM investment_notification WHERE merchantid = '$institution_id' AND vendorid = '' AND status = 'Pending' AND refid = '$refid'");
$fetch_w = mysqli_fetch_array($search_w);
$pcode = $fetch_w['plancode'];
$plancurrency = $fetch_w['plancurrency'];
$planamount = $fetch_w['planamount'];
$planname = $fetch_w['plan_name'];
$acctno = $fetch_w['customerid'];
$myfullname = $fetch_w['customer_name'];
$real_subscription_code = $fetch_w['subcode'];
$converted_date2 = date('Y-m-d').' '.(date(h) + 1).':'.date('i:s');

$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$r = mysqli_fetch_object($query);
$sms_rate = $r->fax;

$search_investor = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acctno'");
$fetch_investor = mysqli_fetch_array($search_investor);
$phone = $fetch_investor['phone'];
$investment_bal = $fetch_investor['investment_balance'] + $planamount;

$sub_list = mysqli_query($link, "SELECT * FROM savings_subscription WHERE vendorid = '$vendorid' AND subscription_code = '$real_subscription_code'");
$fetch_sub_list = mysqli_fetch_array($sub_list);
$sub_bal = $fetch_sub_list['sub_balance'] + $planamount;

$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
$fetch_gateway = mysqli_fetch_object($search_gateway);
$gateway_uname = $fetch_gateway->username;
$gateway_pass = $fetch_gateway->password;
$gateway_api = $fetch_gateway->api;

$merchantBalance = $iwallet_balance - $sms_rate;

$sysabb = $isenderid;

$sms = "$isenderid>>>Dear Investor, This is to notify you that your payment of $plancurrency".number_format($planamount,2,'.',',')." for $planname with Subscription code: $real_subscription_code has been Approved";

$update_records = mysqli_query($link, "UPDATE savings_subscription SET sub_balance = '$sub_bal', status = 'Approved' WHERE branchid = '$institution_id' AND status = 'Pending' AND subscription_code = '$real_subscription_code'");

($iwallet_balance < $sms_rate) ? "" : $update_records = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$merchantBalance' WHERE institution_id = '$institution_id'");

$update_records = mysqli_query($link, "UPDATE borrowers SET investment_balance = '$investment_bal' WHERE account = '$acctno'");

$update_records = mysqli_query($link, "UPDATE all_savingssub_transaction SET status = 'successful' WHERE merchant_id = '$institution_id' AND vendorid = '' AND reference_no = '$refid' AND status = 'pending'");

//$update_records = mysqli_query($link, "INSERT INTO manual_investsettlement VALUES(null,'$institution_id','','$inst_name','$acctno','$myfullname','$refid','$pcode','$real_subscription_code','$planname','$plancurrency','$planamount','$converted_date2','Pending')");

$update_records = mysqli_query($link, "UPDATE investment_notification SET status = 'Approved' WHERE merchantid = '$institution_id' AND vendorid = '' AND status = 'Pending' AND refid = '$refid'");

($iwallet_balance < $sms_rate) ? "" : $update_records = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$phone','','$sms_rate','Debit','NGN','Charges','SMS Content: $sms','successful','$final_date_time','$iuid','$merchantBalance','')");

if(!$update_records){
    
    echo '<meta http-equiv="refresh" content="2;url=notification.php?tid='.$_SESSION['tid'].'&&mid=NDkw&&tab=tab_1">';
	echo '<br>';
	echo '<span class="itext" style="color: blue;">Unable to Approved Payment, Please try again later!!</span>';
    
}else{
	
    ($iwallet_balance < $sms_rate) ? "" : include("../cron/send_general_sms.php");
    echo '<meta http-equiv="refresh" content="2;url=notification.php?tid='.$_SESSION['tid'].'&&mid=NDkw&&tab=tab_1">';
	echo '<br>';
	echo '<span class="itext" style="color: blue;">Payment Approved Successfully!</span>';
    
}
?>