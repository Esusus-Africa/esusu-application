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

$sms_rate = $fetchsys_config['fax'] * 2;
$refid = "EA-smsCharges-".time();
$mybalance = $iassigned_walletbal - $sms_rate;
$date_time = date("Y-m-d H:i:s");

if(mysqli_num_rows($isearch_maintenance_model) == 1)
{
    //$sys_abb = $get_sys['abb'];
    $sysabb = $fetch_memset['sender_id'];
    //$sender = $sys_abb;
    
    $search_cust = mysqli_query($link, "SELECT * FROM transaction WHERE txid = '$txid'") or die("Error:" . mysqli_error($link));
    $get_cust = mysqli_fetch_array($search_cust);
    
    $amt = number_format($get_cust['amount'],2,'.',',');
    $accno = ccMasking($get_cust['acctno']);
    $phone = $get_cust['phone'];
    $rmk = $get_cust['remark'];
    $time = $get_cust['date_time'];
    
    //DEBIT ALERT FOR THE AMOUNT WITHDRAWED
    $message = "$sysabb>>>DR";
    $message .= " Amt: ".$icurrency.$amt."";
    $message .= " Acc: ".$accno."";
    $message .= " Desc: ".$remark." - ".$ln." | ".$txid."";
    $message .= " Time: ".$time."";
    $message .= " Bal: ".$icurrency.number_format($semi_total,2,'.',',')."";
    $debug = true;
    ozekiSend($sysabb,$phone,$message,$debug);
    
    //DEBIT ALERT FOR WITHDRAWAL FEE
    $message = "$sysabb>>>DR (Withdrawal Charges)";
    $message .= " Amt: ".$icurrency.number_format($final_charges,2,'.',',')."";
    $message .= " Acc: ".$accno."";
    $message .= " Desc: Charges - ".$ln." | ".$txid."";
    $message .= " Time: ".$time."";
    $message .= " Bal: ".$icurrency.number_format($total,2,'.',',')."";
    $debug = true;
    ozekiSend($sysabb,$phone,$message,$debug);
    //mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$phone','$sms_rate','NGN','system','SMS Content: $message','successful',NOW())");
    //mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','institution','$sysabb','$phone','$message','Sent',NOW())");
    //mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mybalance' WHERE institution_id = '$institution_id'");
    
}
elseif(mysqli_num_rows($isearch_maintenance_model) == 0 && $iassigned_walletbal >= $sms_rate)
{
    //$sys_abb = $get_sys['abb'];
    $sysabb = $fetch_memset['sender_id'];
    //$sender = $sys_abb;
    
    $search_cust = mysqli_query($link, "SELECT * FROM transaction WHERE txid = '$txid'") or die("Error:" . mysqli_error($link));
    $get_cust = mysqli_fetch_array($search_cust);
    
    $amt = number_format($get_cust['amount'],2,'.',',');
    $accno = ccMasking($get_cust['acctno']);
    $phone = $get_cust['phone'];
    $rmk = $get_cust['remark'];
    $time = $get_cust['date_time'];
    
    //DEBIT ALERT FOR THE AMOUNT WITHDRAWED
    $message = "$sysabb>>>DR";
    $message .= " Amt: ".$icurrency.$amt."";
    $message .= " Acc: ".$accno."";
    $message .= " Desc: ".$remark." - ".$ln." | ".$txid."";
    $message .= " Time: ".$time."";
    $message .= " Bal: ".$icurrency.number_format($semi_total,2,'.',',')."";
    $debug = true;
    ozekiSend($sysabb,$phone,$message,$debug);
    
    //DEBIT ALERT FOR WITHDRAWAL FEE
    $message = "$sysabb>>>DR (Withdrawal Charges)";
    $message .= " Amt: ".$icurrency.number_format($final_charges,2,'.',',')."";
    $message .= " Acc: ".$accno."";
    $message .= " Desc: Charges - ".$ln." | ".$txid."";
    $message .= " Time: ".$time."";
    $message .= " Bal: ".$icurrency.number_format($total,2,'.',',')."";
    $debug = true;
    ozekiSend($sysabb,$phone,$message,$debug);
    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$phone','','$sms_rate','Debit','NGN','Charges','SMS Content: $message','successful','$date_time','$iuid','$mybalance','')");
    mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','institution','$sysabb','$phone','$message','Sent',NOW())");
    mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mybalance' WHERE institution_id = '$institution_id'");
    
}
else{
    echo "";
}
?>