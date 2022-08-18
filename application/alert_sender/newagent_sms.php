<?php
$sql = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated'") or die (mysqli_error($link));
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

$search_sys = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
$get_sys = mysqli_fetch_array($search_sys);
$sender = $get_sys['abb'];

$search_cust = mysqli_query($link, "SELECT * FROM agent_data WHERE agentid = '$agentid'") or die("Error:" . mysqli_error($link));
$get_cust = mysqli_fetch_array($search_cust);
$fname = $get_cust['fname'];
$phone = $get_cust['phone'];
$username = $get_cust['username'];
$password = $get_cust['upassword'];

$message2 = "$sender>>>ACCT. Notification | ";
$message2 .= "Hi $fname! ";
$message2 .= "Your registration as $sender Agent has been initiated successfully. ";
$message2 .= "Your Username: $username , Your Password: $password";
$debug = true;

ozekiSend($sender,$phone,$message2,$debug);
}
else{
	echo "";
}
?>