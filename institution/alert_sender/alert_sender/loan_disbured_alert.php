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
$mybalance = $iassigned_walletbal - $sms_rate;
$date_time = date("Y-m-d H:i:s");

if($iassigned_walletbal >= $sms_rate)
{
    $sender = $fetch_memset['sender_id'];
    $mycurrency = $fetch_memset['currency'];

    $search_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$LID'") or die("Error:" . mysqli_error($link));
    $get_status = mysqli_fetch_array($search_sys);
    $loan_status = $get_status['status'];
    $cus_account = $get_status['baccount'];
    
    $search_cust = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$uaccount'") or die("Error:" . mysqli_error($link));
    $get_cust = mysqli_fetch_array($search_cust);
    $fname = $get_cust['fname'];
    $phone = $get_cust['phone'];
    $to = $get_cust['email'];

    //Switch statement has to be used here for the right ALERT
    $message2 = "$sender>>>Loan Disbursed | ";
	$message2 .= "Dear ".$fname."! ";
	$message2 .= "This is to notify you that $mycurrency.number_format($loan_amount,2,'.',',') with Loan ID: ".$LID." has been disbursed to your account on ".date('Y-m-d').". Thanks.";
	$debug = true;
	ozekiSend($sender,$phone,$message2,$debug);
	mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$phone','','$sms_rate','Debit','NGN','Charges','SMS Content: $message2','successful','$date_time','$iuid','$mybalance','')");
    mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','institution','$sender','$phone','$message2','Sent',NOW())");
    mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mybalance' WHERE institution_id = '$institution_id'");

}
else{
	echo "";
}
?>