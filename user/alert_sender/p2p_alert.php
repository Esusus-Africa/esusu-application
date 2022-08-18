<?php
$sql = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$bbranchid'") or die (mysqli_error($link));
$find = mysqli_fetch_array($sql);
$ozeki_user = $find['username'];
$ozeki_password = $find['password'];
$ozeki_url = $find['api'];
$status = $find['status'];

if($status == "Activated")
{
########################################################
# Functions used to send the SMS message
########################################################

function ozekiSend($sender, $phone, $msg, $debug=false){
      global $ozeki_user,$ozeki_password,$ozeki_url;

      $url = 'username='.$ozeki_user;
      $url.= '&password='.$ozeki_password;
      $url.= '&sender='.urlencode($sender);
      $url.= '&recipient='.urlencode($phone);
      $url.= '&message='.urlencode($msg);

      $urltouse =  $ozeki_url.$url;
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
//$var = '1234123412341234';
//$var = substr_replace($var, str_repeat("X", 8), 4, 8);
//echo $var;
$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$r = mysqli_fetch_object($query);
    
$sys_abb = ($fetch_memset['sender_id'] == "") ? $r->abb : $fetch_memset['sender_id'];

$search_cust = mysqli_query($link, "SELECT * FROM wallet_history WHERE refid = '$txid'") or die("Error:" . mysqli_error($link));
$get_cust = mysqli_fetch_array($search_cust);

$amt = number_format($get_cust['amount'],2,'.',',');
$accno = ccMasking($get_cust['recipient']);
$time = $get_cust['date_time'];
$ptype = $get_cust['paymenttype'];

$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$time,new DateTimeZone(date_default_timezone_get()));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');

$message = "$sys_abb>>>CR";
$message .= " Amt: ".$bbcurrency.number_format($amt,2,'.',',')."";
$message .= " Acc: ".$accno."";
$message .= " Desc: ".$ptype." - | ".$txid."";
$message .= " Time: ".$correctdate."";
$message .= " Bal: ".$bbcurrency.number_format($receiverBalance,2,'.',',')."";
$debug = true;
ozekiSend($sys_abb,$ph,$message,$debug);
}
else{
	echo "";
}
?>