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

if($status == "Activated")
{
$sender = $sys_abb;

$search_cust = mysqli_query($link, "SELECT * FROM wallet_history WHERE refid = '$txid'") or die("Error:" . mysqli_error($link));
$get_cust = mysqli_fetch_array($search_cust);

//$amt = number_format($get_cust['amount'],2,'.',',');
$accno = ccMasking($get_cust['userid']);
$rmk = $get_cust['paymenttype'];
$time = $get_cust['date_time'];
$currency = $get_cust['currency'];

$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$time,new DateTimeZone('America/New_York'));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');

//DEBIT ALERT FOR THE AMOUNT WITHDRAWED
$message = "$sys_abb>>>DR";
$message .= " Amt: ".$currency.number_format($amt,2,'.',',')."";
$message .= " Acc: ".$accno."";
$message .= " Desc: Card-Withdrawal | ".$txid."";
$message .= " Time: ".$correctdate."";
$message .= " Bal: ".$currency.number_format($semi_total,2,'.',',')."";
$debug = true;
ozekiSend($sender,$phone,$message,$debug);

//DEBIT ALERT FOR WITHDRAWAL FEE
$message = "$sys_abb>>>DR (Withdrawal Charges)";
$message .= " Amt: ".$currency.number_format($final_charges,'.',',')."";
$message .= " Acc: ".$accno."";
$message .= " Desc: Card-Charges | ".$txid."";
$message .= " Time: ".$correctdate."";
$message .= " Bal: ".$currency.number_format($total,2,'.',',')."";
$debug = true;
ozekiSend($sender,$phone,$message,$debug);

}
else{
	echo "";
	}
?>