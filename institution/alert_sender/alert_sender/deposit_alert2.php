<?php
$sql = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'") or die (mysqli_error($link));
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
$sms_rate = $fetchsys_config['fax'] * 2;
$refid = "EA-smsCharges-".rand(1000000,9999999);
$mybalance = $iassigned_walletbal - $sms_rate;
$date_time = date("Y-m-d H:i:s");

if(mysqli_num_rows($isearch_maintenance_model) == 1)
{
    $sys_abb = $fetch_memset['sender_id'];
    
    $search_cust = mysqli_query($link, "SELECT * FROM transaction WHERE txid = '$txid'") or die("Error:" . mysqli_error($link));
    $get_cust = mysqli_fetch_array($search_cust);
    
    $amt = number_format($get_cust['amount'],2,'.',',');
    $accno = ccMasking($get_cust['acctno']);
    $time = $get_cust['date_time'];
    
    $search_cust2 = mysqli_query($link, "SELECT * FROM transaction WHERE txid = '$txid2'") or die("Error:" . mysqli_error($link));
    $get_cust2 = mysqli_fetch_array($search_cust2);
    
    $amt2 = number_format($get_cust['amount'],2,'.',',');
    $accno2 = ccMasking($get_cust['acctno']);
    $time2 = $get_cust['date_time'];
    
    $search_bal = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$account'") or die("Error:" . mysqli_error($link));
    $get_bal = mysqli_fetch_array($search_bal);
    $bal = $get_bal['balance'];
    
    $search_bal2 = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$referral'") or die("Error:" . mysqli_error($link));
    $get_bal2 = mysqli_fetch_array($search_bal2);
    $bal2 = $get_bal['balance'];
    
    $message = "$sys_abb>>>CR";
    $message .= " Amt: ".$icurrency.$amt."";
    $message .= " Acc: ".$accno."";
    $message .= " Desc: Deposit - | ".$txid."";
    $message .= " Time: ".$time."";
    $message .= " Bal: ".$icurrency.$bal."";
    $debug = true;
    ozekiSend($sys_abb,$ph,$message,$debug);
    
    $message = "$sys_abb>>>TR";
    $message .= " Amt: ".$icurrency.$amt2."";
    $message .= " Acc: ".$accno2."";
    $message .= " Desc: Deposit - | ".$txid2."";
    $message .= " Time: ".$time2."";
    $message .= " Bal: ".$icurrency.$bal2."";
    $debug = true;
    ozekiSend($sys_abb,$my_ph,$message,$debug);
    //mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$ph','$sms_rate','NGN','system','SMS Content: $message','successful',NOW())");
    //mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','institution','$sysabb','$ph','$message','Sent',NOW())");
    //mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mybalance' WHERE institution_id = '$institution_id'");
    
}
elseif(mysqli_num_rows($isearch_maintenance_model) == 0 && $iassigned_walletbal >= $sms_rate)
{
    $sys_abb = $fetch_memset['sender_id'];
    
    $search_cust = mysqli_query($link, "SELECT * FROM transaction WHERE txid = '$txid'") or die("Error:" . mysqli_error($link));
    $get_cust = mysqli_fetch_array($search_cust);
    
    $amt = number_format($get_cust['amount'],2,'.',',');
    $accno = ccMasking($get_cust['acctno']);
    $time = $get_cust['date_time'];
    
    $search_cust2 = mysqli_query($link, "SELECT * FROM transaction WHERE txid = '$txid2'") or die("Error:" . mysqli_error($link));
    $get_cust2 = mysqli_fetch_array($search_cust2);
    
    $amt2 = number_format($get_cust['amount'],2,'.',',');
    $accno2 = ccMasking($get_cust['acctno']);
    $time2 = $get_cust['date_time'];
    
    $search_bal = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$account'") or die("Error:" . mysqli_error($link));
    $get_bal = mysqli_fetch_array($search_bal);
    $bal = $get_bal['balance'];
    
    $search_bal2 = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$referral'") or die("Error:" . mysqli_error($link));
    $get_bal2 = mysqli_fetch_array($search_bal2);
    $bal2 = $get_bal['balance'];
    
    $message = "$sys_abb>>>CR";
    $message .= " Amt: ".$icurrency.$amt."";
    $message .= " Acc: ".$accno."";
    $message .= " Desc: Deposit - | ".$txid."";
    $message .= " Time: ".$time."";
    $message .= " Bal: ".$icurrency.$bal."";
    $debug = true;
    ozekiSend($sys_abb,$ph,$message,$debug);
    
    $message = "$sys_abb>>>TR";
    $message .= " Amt: ".$icurrency.$amt2."";
    $message .= " Acc: ".$accno2."";
    $message .= " Desc: Deposit - | ".$txid2."";
    $message .= " Time: ".$time2."";
    $message .= " Bal: ".$icurrency.$bal2."";
    $debug = true;
    ozekiSend($sys_abb,$my_ph,$message,$debug);
    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$ph','','$sms_rate','Debit','NGN','Charges','SMS Content: $message','successful','$date_time','$iuid','$mybalance','')");
    mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','institution','$sysabb','$ph','$message','Sent',NOW())");
    mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mybalance' WHERE institution_id = '$institution_id'");
    
}
else{
	echo "";
}
?>