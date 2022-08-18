<?php
$sql = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated' AND smsuser = ''") or die (mysqli_error($link));
$find = mysqli_fetch_array($sql);
$ozeki_user = $find['username'];
$ozeki_password = $find['password'];
$ozeki_url = $find['api'];
$status = $find['status'];

//$message = "$isenderid>>>Guarantor Consent. ";
//$message .= "Dear ".$gname."! We will like you to ACCEPT or DECLINE CONSENT that ".$get_cust['lname'].' '.$get_cust['fname']."";
//$message .= " is about taking loan from us. Your OTP Code: ".$otp_code.". Click here to proceed: https://esusu.app/guarantor.php";

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
$mybalance = $iassigned_walletbal - $sms_rate;
$date_time = date("Y-m-d H:i:s");

if($iassigned_walletbal >= $sms_rate)
{    
    $search_cust = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$baccount'") or die("Error:" . mysqli_error($link));
    $get_cust = mysqli_fetch_array($search_cust);
    $fname = $get_cust['fname'];
    $phone = $get_cust['phone'];
    $to = $get_cust['email'];
    //$sender = $sys_abb;
    $otp_code = rand(100000,999999);
    
    $message = "$isenderid>>>LOAN APPLICATION ";
    $message .= "Dear ".$fname."! Your loan application with Loan ID: ".$lid." of ".$icurrency.number_format($amount,2,'.',',')." has been initiated on ".date('d/m/Y')."";
    $message .= " Kindly await our response upon review. Thanks.";
    $debug = true;
    ozekiSend($isenderid,$phone,$message,$debug);
    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$phone','','$sms_rate','Debit','NGN','Charges','SMS Content: $message','successful','$date_time','$iuid','$mybalance','')");
    mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','institution','$isenderid','$phone','$message','Sent',NOW())");
    mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mybalance' WHERE institution_id = '$institution_id'");
}
else{
	echo "";
}
?>