<?php

include("../config/connect.php");

//$link = mysqli_connect('localhost','esusulive_esusuapp','BoI9YR^zqs%M','esusulive_esusuapp') or die('Unable to Connect to Database');

function sendSms($sender, $ph, $msg, $debug=false)
{
  global $gateway_uname,$gateway_pass,$gateway_api;

  $url = 'username='.$gateway_uname;
  $url.= '&password='.$gateway_pass;
  $url.= '&sender='.urlencode($sender);
  $url.= '&recipient='.urlencode($ph);
  $url.= '&message='.urlencode($msg);

  $urltouse =  $gateway_api.$url;
  //if ($debug) { echo "Request: <br>$urltouse<br><br>"; }

  //Open the URL to send the message
  $response3 = file_get_contents($urltouse);
  if ($debug) {
      //echo "Response: <br><pre>".
      //str_replace(array("<",">"),array("&lt;","&gt;"),$response3).
      //"</pre><br>"; 
  }
  return($response3);
}

function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);
}

$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
$fetch_gateway = mysqli_fetch_object($search_gateway);
$gateway_uname = $fetch_gateway->username;
$gateway_pass = $fetch_gateway->password;
$gateway_api = $fetch_gateway->api;

$date_time = date("Y-m-d h:i:s");
$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');

$currenctDate = date("Y-m-d");
$chargeStatus = "NextQueue_".$currenctDate;

$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE auto_charge_status = 'Active' AND next_charge_date = '$currenctDate'");
while($fetch_customer = mysqli_fetch_array($search_customer)){
    
    $amount = $fetch_customer['chargesAmount'];
    
    $charge_interval = ($fetch_customer['charge_interval'] == "weekly" ? "week" : ($fetch_customer['charge_interval'] == "monthly" ? "month" : "year"));
    
    $calc_nextCharge_date = date('Y-m-d', strtotime('+1 '.$charge_interval, strtotime($currenctDate)));
    
    $nextChargeStatus = "NextQueue_".$calc_nextCharge_date;
    
    $institution_id = $fetch_customer['branchid'];
    
    $account = $fetch_customer['account'];
    
    $ledger_balance = $fetch_customer['balance'] - $amount;
    
    $icurrency = $fetch_customer['currency'];
    
    $fn = $fetch_customer['fname'];
	
	$ln = $fetch_customer['lname'];
	
	$em = $fetch_customer['email'];
	
	$ph = $fetch_customer['phone'];
    
    $txid = date("yd").time();
    
    $icm_id = "ICM".rand(100000,999999);
    
    $icm_date = date("Y-m-d");
    
    $message = "$sysabb>>>DR (Withdrawal Charges)";
    $message .= " Amt: ".$icurrency.$amount."";
    $message .= " Acc: ".ccMasking($account)."";
    $message .= " Desc: Charges - ".$txid."";
    $message .= " Time: ".$correctdate."";
    $message .= " Bal: ".$icurrency.number_format($ledger_balance,2,'.',',')."";
        
    mysqli_query($link, "UPDATE borrowers SET balance = '$ledger_balance', next_charge_date = '$calc_nextCharge_date' WHERE account = '$account' AND branchid = '$institution_id'");
    
    mysqli_query($link, "INSERT INTO income VALUES(null,'$institution_id','$icm_id','Charges','$amount','$icm_date','Withdraw-Charges')");
    
    mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','Withdraw-Charges','Cash','$account','----','$fn','$ln','$em','$ph','$amount','','Auto Charge','$correctdate','$institution_id','','$icurrency','','$ledger_balance')") or die (mysqli_error($link));
    
}
?>