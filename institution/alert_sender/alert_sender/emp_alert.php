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
$refid = "EA-empRegAlert-".rand(1000000,9999999);
$mybalance = $iassigned_walletbal - $sms_rate;
$date_time = date("Y-m-d H:i:s");

if($iassigned_walletbal >= $sms_rate && $phone != "")
{
    $search_sys = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'") or die("Error:" . mysqli_error($link));
    $get_sys = mysqli_fetch_array($search_sys);
    $sender = $get_sys['sender_id'];
    
    //echo $phone;
    
    $message = "$sender>>>Welcome $name! Account created successfully. Username: $username, Password: $password. Transaction Pin: 0000, Login here: https://esusu.app/$sender";
    
    $debug = true;
    ozekiSend($sender,$phone,$message,$debug);
    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$phone','','$sms_rate','Debit','NGN','Charges','SMS Content: $message','successful','$date_time','$iuid','$mybalance','')");
    mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','institution','$sender','$phone','$message','Sent',NOW())");
    mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mybalance' WHERE institution_id = '$institution_id'");
}
else{
	echo "sms connection failed...";
}
?>