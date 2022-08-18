<?php
include("../config/connect.php");

$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
$fetch_gateway = mysqli_fetch_object($search_gateway);
$gateway_uname = $fetch_gateway->username;
$gateway_pass = $fetch_gateway->password;
$gateway_api = $fetch_gateway->api;

function sendSms($sys_abb, $phone, $msg, $debug=false)
{
    global $gateway_uname,$gateway_pass,$gateway_api;

    $url = 'username='.$gateway_uname;
    $url.= '&password='.$gateway_pass;
    $url.= '&sender='.urlencode($sys_abb);
    $url.= '&recipient='.urlencode($phone);
    $url.= '&message='.urlencode($msg);

    $urltouse =  $gateway_api.$url;
    //if ($debug) { echo "Request: <br>$urltouse<br><br>"; }

    //Open the URL to send the message
    $response = file_get_contents($urltouse);
    if ($debug) {
        //echo "Response: <br><pre>".
        //str_replace(array("<",">"),array("&lt;","&gt;"),$response).
        //"</pre><br>"; 
    }
    return($response);
}

$search_pendingtxt = mysqli_query($link, "SELECT * FROM payments WHERE sendSms = '0' AND sendEmail = '0' AND (status = 'paid' OR status = 'declined')");
while($fetch_pendingtxt = mysqli_fetch_array($search_pendingtxt)){

    $id = $fetch_pendingtxt['id'];
    $refid = $fetch_pendingtxt['refid'];
    $account_no = $fetch_pendingtxt['account_no'];
    $amount_to_pay = $fetch_pendingtxt['amount_to_pay'];
    $customer = $fetch_pendingtxt['customer'];
    $lid = $fetch_pendingtxt['lid'];
    $final_bal = $fetch_pendingtxt['loan_bal'];
    $remarks = $fetch_pendingtxt['remarks'];
    $theStatus = ($remarks == "paid") ? "Approved" : "Declined";
    $channel = "Internal";
    $institution_id = $fetch_pendingtxt['branchid'];
    $checksms = $fetch_pendingtxt['smsChecker'];
    $iuid = $fetch_pendingtxt['tid'];
    $final_date_time = $fetch_pendingtxt['pay_date']." ".date("H:i:s");

    $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$final_date_time,new DateTimeZone(date_default_timezone_get()));
	$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
	$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
	$mycurrentTime = $acst_date->format('Y-m-d g:i A');

    $searchin = mysqli_query($link, "SELECT * FROM borrowers WHERE account ='$account_no'");
    $get_searchin = mysqli_fetch_array($searchin);
    $uname = $get_searchin['username'];
    $phone = $get_searchin['phone'];
    $em = $get_searchin['email'];
    $smsrefid = "EA-smsCharges-".uniqid();
    
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    $sysabb = $fetch_memset['sender_id'];
    $icurrency = $fetch_memset['currency'];

    $search_sys = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
	$get_sys = mysqli_fetch_array($search_sys);
    $sms_rate = $get_sys['fax'];

    $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$institution_id'");
    $fetch_inst = mysqli_fetch_array($search_inst);
    $inst_name = $fetch_inst['institution_name'];
    $inst_wbal = $fetch_inst['wallet_balance'];

    $sms = "$sysabb>>>Dear $customer! Your repayment of ".$icurrency.number_format($amount_to_pay,2,'.',',')." with Loan ID: $lid has been $theStatus. ";
    $sms .= "Your Loan Balance is: ".$icurrency.number_format($final_bal,2,'.',',')." Thanks.";

    $max_per_page = 153;
	$sms_length = strlen($sms);
    $calc_length = ceil($sms_length / $max_per_page);
	
	$tsms_charges = $sms_rate * $calc_length;
    $mybalance = $inst_wbal - $tsms_charges;
    $sendSMS =  ($tsms_charges > $inst_wbal || $checksms == "0" || $theStatus == "Declined") ? "0" : "1";
    $sendEmail = ($em == "") ? "-1" : "1";
    
    mysqli_query($link, "UPDATE payments SET sendSms = '$sendSMS', sendEmail = '$sendEmail' WHERE id = '$id' AND status = 'paid'");
    ($tsms_charges > $inst_wbal || $checksms == "0" || $theStatus == "Declined") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$smsrefid','$phone','','$tsms_charges','Debit','NGN','Charges','SMS Content: $sms','successful','$final_date_time','$iuid','$mybalance','')");
    ($tsms_charges > $inst_wbal || $checksms == "0" || $theStatus == "Declined") ? "" : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mybalance' WHERE institution_id = '$institution_id'");

    ($tsms_charges > $inst_wbal || $checksms == "0" || $theStatus == "Declined") ? "" : $debug = true;
    ($tsms_charges > $inst_wbal || $checksms == "0" || $theStatus == "Declined") ? "" : sendSms($sysabb,$phone,$sms,$debug);
    include("../cron/send_repayemail.php");

}
?>