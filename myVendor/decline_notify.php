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

$search_w = mysqli_query($link, "SELECT * FROM investment_notification WHERE vendorid = '$vendorid' AND status = 'Pending' AND refid = '$refid'");
$fetch_w = mysqli_fetch_array($search_w);
$pcode = $fetch_w['plancode'];
$plancurrency = $fetch_w['plancurrency'];
$planamount = $fetch_w['planamount'];
$planname = $fetch_w['plan_name'];
$acctno = $fetch_w['customerid'];
$myfullname = $fetch_w['customer_name'];
$real_subscription_code = $fetch_w['subcode'];

$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$r = mysqli_fetch_object($query);
$sms_rate = $r->fax;

$search_investor = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acctno'");
$fetch_investor = mysqli_fetch_array($search_investor);
$phone = $fetch_investor['phone'];

$vendorBalance = $vwallet_balance - $sms_rate;

$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
$fetch_gateway = mysqli_fetch_object($search_gateway);
$gateway_uname = $fetch_gateway->username;
$gateway_pass = $fetch_gateway->password;
$gateway_api = $fetch_gateway->api;

$sysabb = $mvsenderid;

$sms = "$mvsenderid>>>Sorry, This is to notify you that your payment of $plancurrency".number_format($planamount,2,'.',',')." for $planname with Subscription code: $real_subscription_code has been Declined";

($num_sub_list == 1) ? $update_records = mysqli_query($link, "UPDATE savings_subscription SET status = 'Cancelled' WHERE vendorid = '$vendorid' AND status = 'Pending' AND reference_no = '$refid'") : "";
($vwallet_balance < $sms_rate) ? "" : $update_records = mysqli_query($link, "UPDATE vendor_reg SET wallet_balance = '$vendorBalance' WHERE companyid = '$vendorid'");
$update_records = mysqli_query($link, "UPDATE all_savingssub_transaction SET status = 'declined' WHERE vendorid = '$vendorid' AND reference_no = '$refid' AND status = 'pending'");
$update_records = mysqli_query($link, "UPDATE investment_notification SET status = 'Declined' WHERE vendorid = '$vendorid' AND status = 'Pending' AND refid = '$refid'");
($vwallet_balance < $sms_rate) ? "" : $update_records = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$vendorid','$refid','$phone','','$sms_rate','Debit','NGN','Charges','SMS Content: $sms','successful','$final_date_time','$vuid','$vendorBalance','')");

if(!$update_records){
    
    echo '<meta http-equiv="refresh" content="2;url=notification.php?tid='.$_SESSION['tid'].'&&mid=NDkw&&tab=tab_1">';
	echo '<br>';
	echo '<span class="itext" style="color: blue;">Unable to Decline Payment, Please try again later!!</span>';
    
}else{
    ($vwallet_balance < $sms_rate) ? "" : include("../cron/send_general_sms.php");
    echo '<meta http-equiv="refresh" content="2;url=notification.php?tid='.$_SESSION['tid'].'&&mid=NDkw&&tab=tab_1">';
	echo '<br>';
	echo '<span class="itext" style="color: blue;">Payment Declined Successfully!</span>';
    
}
?>