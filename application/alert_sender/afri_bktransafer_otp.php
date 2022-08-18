<?php
$sql = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated' AND smsuser = ''") or die (mysqli_error($link));
$find = mysqli_fetch_array($sql);
$ozeki_user = $find['username'];
$ozeki_password = $find['password'];
$ozeki_url = $find['api'];
$status = $find['status'];

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

//$sys_abb = $get_sys['abb'];
$sysabb = $fetch_memset['abb'];
//$sender = $sys_abb;

$search_cust = mysqli_query($link, "SELECT * FROM africa_pmt_confirmation WHERE refid = '$reference'") or die("Error:" . mysqli_error($link));
$get_cust = mysqli_fetch_array($search_cust);

$otp_code = $get_cust['otp_code'];
$phone = $get_cust['phone'];
$amount = number_format($get_cust['amount'],2,'.',',');

$message = "$sysabb>>>DO NOT DISCLOSE YOUR OTP TO ANYONE.";
$message .= " Your One Time Password to Transfer ".$fetch_memset['currency'].$amount." is ".$otp_code.". Kindly ignore if you are unaware. Thanks.";
$debug = true;
ozekiSend($sysabb,$phone,$message,$debug);
?>