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

//$var = '1234123412341234';
//$var = substr_replace($var, str_repeat("X", 8), 4, 8);
//echo $var;

$sms_rate = $fetchsys_config['fax'];
$refid = "EA-smsCharges-".time();
$mybalance = $iassigned_walletbal - $sms_rate;
//$mybalance = $iwallet_balance - ($sms_rate + $amount);
$date_time = date("Y-m-d H:i:s");

if($iassigned_walletbal >= $sms_rate)
{
    $sysabb = $fetch_memset['sender_id'];
    
    $search_cust = mysqli_query($link, "SELECT * FROM wallet_history WHERE refid = '$txid'") or die("Error:" . mysqli_error($link));
    $get_cust = mysqli_fetch_array($search_cust);
    
    $amt = number_format($get_cust['amount'],2,'.',',');
    $accno = ccMasking($get_cust['userid']);
    $time = $get_cust['date_time'];
    $ptype = $get_cust['paymenttype'];
    
    $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$time,new DateTimeZone(date_default_timezone_get()));
    $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
    $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
    $correctdate = $acst_date->format('Y-m-d g:i A');
    
    $message = "$sysabb>>>CR";
    $message .= " Amt: ".$icurrency.$amt."";
    $message .= " Acc: ".$accno."";
    $message .= " Desc: ".$ptype." - | ".$txid."";
    $message .= " Time: ".$correctdate."";
    $message .= " Bal: ".$icurrency.number_format($totalwallet_balance,2,'.',',')."";
    $debug = true;
    ozekiSend($sysabb,$ph,$message,$debug);
    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$ph','','$sms_rate','Debit','NGN','Charges','SMS Content: $message','successful','$date_time,'$iuid','$mybalance','')");
    mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','institution','$sysabb','$ph','$message','Sent',NOW())");
    mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mybalance' WHERE institution_id = '$institution_id'");
}
else{
	echo "";
}
?>