<?php
$sql = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated' AND smsuser = ''") or die (mysqli_error($link));
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

$memid = $emapData[7];
$search_cmember = mysqli_query($link, "SELECT * FROM coop_members WHERE memberid = '$memid'");
$fetch_cmember = mysqli_fetch_object($search_cmember);

$message2 = "$sender>>>Guarantor Confirmation | ";
$message2 .= "Dear $emapData[3]! ";
$message2 .= "Please confirm that you have agreed to stand as a Guarantor for $fname with this OTP:";
$message2 .= " $guarantors_otp. Click Here to Confirm: https://esusu.africa/app/guarantor.php";
$debug = true;

ozekiSend($sender,$emapData[4],$message2,$debug);
}
else{
	echo "";
}
?>