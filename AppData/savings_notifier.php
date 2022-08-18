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

function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);
}

$search_pendingtxt = mysqli_query($link, "SELECT * FROM transaction WHERE status = 'Approved' AND sendSms = '0' AND sendEmail = '0'");
while($fetch_pendingtxt = mysqli_fetch_array($search_pendingtxt)){

    $id = $fetch_pendingtxt['id'];
    $txid = $fetch_pendingtxt['txid'];
	$account = $fetch_pendingtxt['acctno'];
    $fn = $fetch_pendingtxt['fn'];
	$ln = $fetch_pendingtxt['ln'];
	$em = $fetch_pendingtxt['email'];
	$ph = $fetch_pendingtxt['phone'];
    $total = $fetch_pendingtxt['balance'];
    $newbalance = $total;
    $uname = $fetch_pendingtxt['fn'];
    $ptype = $fetch_pendingtxt['t_type'];
    $amount = $fetch_pendingtxt['amount'];
    $final_date_time = $fetch_pendingtxt['date_time'];
    $institution_id = $fetch_pendingtxt['branchid'];
    $icurrency = $fetch_pendingtxt['currency'];
    $mtTType = ($ptype == "Deposit") ? "CR" : "DR";
    $checksms = $fetch_pendingtxt['smsChecker'];
    $refid = "EA-smsCharges-".uniqid();
    $iuid = $fetch_pendingtxt['posted_by'];

    $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$final_date_time,new DateTimeZone(date_default_timezone_get()));
	$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
	$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
	$correctdate = $acst_date->format('Y-m-d g:i A');
    
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    $sysabb = $fetch_memset['sender_id'];

    $search_sys = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
	$get_sys = mysqli_fetch_array($search_sys);
    $sms_rate = $get_sys['fax'];

    $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$institution_id'");
    $fetch_inst = mysqli_fetch_array($search_inst);
    $inst_name = $fetch_inst['institution_name'];
    $inst_wbal = $fetch_inst['wallet_balance'];

	$sms = "$sysabb>>>$mtTType";
	$sms .= " Amt: ".$icurrency.$amount."";
	$sms .= " Acc: ".ccMasking($account)."";
	$sms .= " Desc: $ptype - ".$txid."";
	$sms .= " Time: ".$correctdate."";
    $sms .= " Bal: ".$icurrency.number_format($newbalance,2,'.',',')."";

    $max_per_page = 153;
	$sms_length = strlen($sms);
    $calc_length = ceil($sms_length / $max_per_page);
	
	$tsms_charges = $sms_rate * $calc_length;
    $mybalance = $inst_wbal - $tsms_charges;
    $sendSMS =  ($tsms_charges > $inst_wbal || $checksms == "0") ? "0" : "1";
    $sendEmail = ($em == "") ? "-1" : "1";
    
    mysqli_query($link, "UPDATE transaction SET sendSms = '$sendSMS', sendEmail = '$sendEmail' WHERE id = '$id' AND status = 'Approved'");
    ($tsms_charges > $inst_wbal || $checksms == "0") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$ph','','$tsms_charges','Debit','NGN','Charges','SMS Content: $sms','successful','$final_date_time','$iuid','$mybalance','')");
    ($tsms_charges > $inst_wbal || $checksms == "0") ? "" : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mybalance' WHERE institution_id = '$institution_id'");

    ($tsms_charges > $inst_wbal || $checksms == "0") ? "" : $debug = true;
    ($tsms_charges > $inst_wbal || $checksms == "0") ? "" : sendSms($sysabb,$ph,$sms,$debug);
    include("../cron/send_ledgerSavings_emailAlert.php");

}
?>