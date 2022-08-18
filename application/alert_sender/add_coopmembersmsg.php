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

$search_sys = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
$get_sys = mysqli_fetch_array($search_sys);
$sender = $get_sys['abb'];

$message1 = "$sender>>>ACCT. Created | ";
$message1 .= "Welcome $fname! ";
$message1 .= "This is to inform you that your account is now active. ";
$message1 .= "Your Email is: $email. Password: $password";
$debug = true;

ozekiSend($sender,$phone,$message1,$debug);

$message2 = "$sender>>>Guarantor Confirmation | ";
$message2 .= "Dear $gname! ";
$message2 .= "Please confirm that you have agreed to stand as a Guarantor for $fname with this OTP:";
$message2 .= " $guarantors_otp. Click Here to Confirm: https://esusu.africa/app/guarantor.php";
$debug = true;

ozekiSend($sender,$gphone,$message2,$debug);
}
else{
	echo "";
}
?>