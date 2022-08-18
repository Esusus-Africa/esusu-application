<?php
$sql = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated'") or die ("Error: " . mysqli_error($link));
$find = mysqli_fetch_array($sql);
$ozeki_user = $find['username'];
$ozeki_password = $find['password'];
$ozeki_url = $find['api'];
$status = $find['status'];

$date = date("Y-m-d",time());

$to = "admin@critechglobal.com";
$subject = "Loan Software Usage Alert - $date";
$body = "\n Be informed that Your Loan Software is in use by below company link.";
$body .= "\n The Tracked URL is: $orginal_path";
$body .= "\n Current Usage Time: $date";
$body .= "\n";
$body .= "\n If domain not recognized, please click the link below to block:.";
$body .= "\n $orginal_path/skey.php?mky=Locked";
$body .= "\n";
$body .= "\n Else, click the link below to unblock:.";
$body .= "\n $orginal_path/skey.php?mky=Unlocked";
$additionalheaders = "From:alerts@critechglobal.com\r\n";
$additionalheaders .= "Reply-To:noreply@critechglobal.com \r\n";
$additionalheaders .= "MIME-Version: 1.0";
$additionalheaders .= "Content-Type: text/html\r\n";

########################################################
# MONTH END DETECTOR
########################################################

$day = date("d");
$month = date("m");
$year = date("Y");
$datein = cal_days_in_month(CAL_GREGORIAN, $month, $year);
if($day == $datein && $status == "Activated")
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

function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);
}
//$var = '1234123412341234';
//$var = substr_replace($var, str_repeat("X", 8), 4, 8);
//echo $var;
$search_sys = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
$get_sys = mysqli_fetch_array($search_sys);
$currency = $get_sys['currency'];
$address = $get_sys['address'];
$amount = number_format($get_sys['sms_charges'],2,'.',',');
$sender = $get_sys['abb'];

$search_cust = mysqli_query($link, "SELECT * FROM transaction") or die("Error:" . mysqli_error($link));
while($get_cust = mysqli_fetch_array($search_cust))
{
$accno = ccMasking($get_cust['acctno']);
$phone = $get_cust['phone'];

$i = 0;
$search_bow = mysqli_query($link, "SELECT * FROM borrowers WHERE balance > 0") or die("Error:" . mysqli_error($link));
while($get_bow = mysqli_fetch_array($search_bal))
{
$account = $get_bow['account'];
foreach($account as $s)
{
$bal = $get_bow['balance'];
$balance = $bal - $amount;
mysqli_query($link, "UPDATE borrowers SET balance = '$balance' WHERE account = '$s'") or die("Error:" . mysqli_error($link));
$i++;

$message = "$sender>>>SMS NOTIFICATION";
$message .= " Amt: ".$currency.$amount."";
$message .= " Acc: ".$accno."";
$message .= " Desc: SMS MONTHLY CHARGES";
$message .= " Time: ".date('D, M jS Y', strtotime('d',time()))."";
$message .= " Bal: ".$currency.$balance."";
$debug = true;
ozekiSend($sender,$phone,$message,$debug);
}
}
$i++;
}
}
else{
	//empty action
}
?>