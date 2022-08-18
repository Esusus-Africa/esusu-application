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

$sms_rate = $fetchsys_config['fax'];
$refid = "EA-smsCharges-".rand(1000000,9999999);

$search_origin = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
$fetch_origin = mysqli_fetch_array($search_origin);

$isenderid = ($fetch_origin['sender_id'] == "") ? $fetchsys_config['abb'] : $fetch_origin['sender_id'];
    
$search_cust = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$baccount'") or die("Error:" . mysqli_error($link));
$get_cust = mysqli_fetch_array($search_cust);
$fname = $get_cust['fname'];
$g_phone = $fetch_id['g_phone'];
$to = $get_cust['email'];
//$sender = $sys_abb;
$otp_code = rand(100000,999999);
    
$message = "$isenderid>>>Guarantor Consent. ";
$message .= "Dear ".$gname."! We will like you to ACCEPT or DECLINE CONSENT that ".$get_cust['lname'].' '.$get_cust['fname']."";
$message .= " is about taking loan from us. Your OTP Code: ".$otp_code.". Click here to proceed: https://esusu.app/guarantor.php";
$debug = true;
ozekiSend($isenderid,$g_phone,$message,$debug);
mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'','customer','$isenderid','$phone','$message','Sent',NOW())");
mysqli_query($link, "INSERT INTO coop_admin_guarantors VALUE(null,'$lid','$location','$g_rela','$gname','$gphone','$g_address','','$baccount','guarantor','$otp_code'");
?>