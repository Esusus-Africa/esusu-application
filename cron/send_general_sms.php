<?php
//include("../config/connect.php");
function sendSms($sender, $phone, $msg, $debug=false)
{
  global $gateway_uname,$gateway_pass,$gateway_api;

  $url = 'username='.$gateway_uname;
  $url.= '&password='.$gateway_pass;
  $url.= '&sender='.urlencode($sender);
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

$debug = true;
sendSms($sysabb,$phone,$sms,$debug);

?>