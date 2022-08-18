<?php
//error_reporting(0);

include("../config/connect.php");
require_once "../config/smsAlertClass.php";

// Function to check string starting 
// with given substring 
function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}

function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);
}

$date_time = date("Y-m-d g:i A");
//$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
//$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
//$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
//$correctdate = $acst_date->format('Y-m-d g:i A');

$search_pendtransact = mysqli_query($link, "SELECT * FROM wallet_history WHERE paymenttype = 'WEMA' AND (status = 'pending' OR status = 'pendingpool' OR status = 'pendingtill')");
while($fetch_pendtransact = mysqli_fetch_array($search_pendtransact))
{
    
    $curl = curl_init();
    $recipient = $fetch_pendtransact['userid'];
    $paymentref = $fetch_pendtransact['refid'];
    $original_amount = $fetch_pendtransact['credit'];
    $initiator = $fetch_pendtransact['initiator'];
    $status = $fetch_pendtransact['status'];
    
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    $calc_charges = ($row1->wema_gatewaycharge / 100) * $original_amount;
    $charges = ($calc_charges >= $row1->wema_cappedamt) ? $row1->wema_cappedamt : $calc_charges;

    $systemset2 = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$recipient'");
    $row12 = mysqli_fetch_array($systemset2);
    $sysabb = $row12->sender_id;
    
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'wema_VAPmtVerification'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;

    curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('sessionid' => $paymentref),
        CURLOPT_HTTPHEADER => array(
          "Content-Type: application/json",
          "Authorization: Bearer ".$row1->wemaToken
        ),
      ));
      
    $response = curl_exec($curl);    
    $err = curl_error($curl);
    
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      //echo $response;
      $transaction = json_decode($response, true);
    
      //Get some values from api
      $amount_fund = $transaction['amount'];
      $paymentMethod = "ACCOUNT_TRANSFER";
      $customerName = $transaction['originatorname'];
      $paymentDescription = $transaction['narration'];
      $transactionReference = $transaction['paymentreference'];
      $currencyCode = $row12->currency;
      $wallet_date_time = date("Y-m-d h:i:s");
            
      if($transactionReference != "" && $initiator == ""){
          
          // update payment status and justify account balance for the institution
          $check_instbal = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$recipient'");
          $fetch_instbal = mysqli_fetch_array($check_instbal);
          $iwallet_bal = $fetch_instbal['wallet_balance'];
          $iphone = $fetch_instbal['official_phone'];
          $total_iwallet_bal = $iwallet_bal + $amount_fund - $charges;
		      $acct = ccMasking($recipient);
		      $today = date("Y-m-d");
		      $t_type1 = "Charges";
		      $income_id = "ICM".rand(000001,99999);
         
          $sms = "$sysabb>>>CR";
          $sms .= " Amt: ".$currencyCode.number_format($original_amount,2,'.',',')."";
          $sms .= " Charges: ".$currencyCode.number_format($charges,2,'.',',')."";
          $sms .= " Acct: ".$acct."";
          $sms .= " Desc: $paymentMethod - | ".$transactionReference."";
          $sms .= " Time: ".$date_time."";
          $sms .= " Bal: ".$currencyCode.number_format($total_iwallet_bal,2,'.',',').""; 
          
          mysqli_query($link, "INSERT INTO income VALUES(null,'','$income_id','Charges','$charges','$today','$t_type1')");
          ($status == "pending") ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$total_iwallet_bal' WHERE institution_id = '$recipient'") : "";
          ($status == "pending") ? mysqli_query($link, "UPDATE wallet_history SET paymenttype = '$paymentMethod', currency = '$currencyCode', remark = '$sms', status = 'successful', balance = '$total_iwallet_bal' WHERE refid = '$paymentref' AND status = 'pending'") : "";
          ($status == "pending") ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$recipient','$paymentref','self','','$charges','Debit','$currencyCode','$t_type1','$t_type1','successful','$wallet_date_time','','$total_iwallet_bal','')") : "";

          $sendSMS->smsWithNoCharges($sysabb, $iphone, $sms, $paymentref, $recipient);

      }
      if($transactionReference != "" && startsWith($initiator,"VEND")){
          
          // update payment status and justify account balance for the institution
          $check_vendbal = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$initiator'");
          $fetch_vendbal = mysqli_fetch_array($check_vendbal);
          $vwallet_bal = $fetch_vendbal['wallet_balance'];
          $vphone = $fetch_vendbal['cphone'];
          $total_vwallet_bal = $vwallet_bal + $amount_fund - $charges;
          $acct = ccMasking($initiator);
          $today = date("Y-m-d");
    		  $t_type1 = "Charges";
    		  $income_id = "ICM".time();
         
          $sms = "$sysabb>>>CR";
          $sms .= " Amt: ".$currencyCode.number_format($original_amount,2,'.',',')."";
          $sms .= " Charges: ".$currencyCode.number_format($charges,2,'.',',')."";
          $sms .= " Acct: ".$acct."";
          $sms .= " Desc: $paymentMethod - | ".$transactionReference."";
          $sms .= " Time: ".$date_time."";
          $sms .= " Bal: ".$currencyCode.number_format($total_vwallet_bal,2,'.',',').""; 

          mysqli_query($link, "INSERT INTO income VALUES(null,'','$income_id','Charges','$charges','$today','$t_type1')");
          ($status == "pending") ? mysqli_query($link, "UPDATE vendor_reg SET wallet_balance = '$total_vwallet_bal' WHERE companyid = '$recipient'") : "";
          ($status == "pending") ? mysqli_query($link, "UPDATE wallet_history SET paymenttype = '$paymentMethod', currency = '$currencyCode', remark = '$sms', status = 'successful', balance = '$total_vwallet_bal' WHERE refid = '$paymentref' AND status = 'pending'") : "";
          ($status == "pending") ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$recipient','$paymentref','self','','$charges','Debit','$currencyCode','$t_type1','$t_type1','successful','$wallet_date_time','$initiator','$total_vwallet_bal','')") : "";
          $sendSMS->smsWithNoCharges($sysabb, $vphone, $sms, $paymentref, $initiator);

      }
    /*if($transactionReference != "" && startsWith($recipient,"COOP")){
          
          // update payment status and justify account balance for the institution
          $check_coopbal = mysqli_query($link, "SELECT * FROM cooperatives WHERE coopid = '$recipient'");
          $fetch_coopbal = mysqli_fetch_array($check_coopbal);
          $cwallet_bal = $fetch_coopbal['wallet_balance'];
          $cphone = $fetch_coopbal['phone'];
          $total_cwallet_bal = $cwallet_bal + $amount_fund - $charges;
          $acct = ccMasking($recipient);
          $today = date("Y-m-d");
    		  $t_type1 = "Charges";
    		  $income_id = "ICM".rand(000001,99999);
         
          $sms = "$sysabb>>>CR";
          $sms .= " Amt: ".$currencyCode.number_format($original_amount,2,'.',',')."";
          $sms .= " Charges: ".$currencyCode.number_format($charges,2,'.',',')."";
          $sms .= " Acct: ".$acct."";
          $sms .= " Desc: $paymentMethod - | ".$transactionReference."";
          $sms .= " Time: ".$date_time."";
          $sms .= " Bal: ".$currencyCode.number_format($total_cwallet_bal,2,'.',',').""; 
          
          $debug = true;
          sendSms($sysabb,$cphone,$sms,$debug);
          mysqli_query($link, "INSERT INTO income VALUES(null,'','$income_id','Charges','$charges','$today','$t_type1')");
          mysqli_query($link, "UPDATE cooperatives SET wallet_balance = '$total_cwallet_bal' WHERE coopid = '$recipient'");
          mysqli_query($link, "UPDATE wallet_history SET paymenttype = '$paymentMethod', currency = '$currencyCode', card_bank_details = '$sms', status = 'successful', balance = '$cwallet_bal' WHERE refid = '$paymentref' AND status = 'pending'");
          mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$recipient','$paymentref','self','$charges','$currencyCode','$t_type1','$t_type1','successful','$wallet_date_time','','$cwallet_bal')");
          
      }*/
      if($transactionReference != "" && (startsWith($initiator,"MEM") || startsWith($initiator,"Loan=") || startsWith($initiator,"AGGR"))){
          
          // update payment status and justify account balance for the institution
          $check_userbal = mysqli_query($link, "SELECT * FROM user WHERE id = '$initiator'");
          $fetch_userbal = mysqli_fetch_array($check_userbal);
          $cwallet_bal = $fetch_userbal['transfer_balance'];
          $cphone = $fetch_userbal['phone'];
          $total_cwallet_bal = $cwallet_bal + $amount_fund - $charges;
          $acct = ccMasking($initiator);
          $originator = $fetch_userbal['created_by'];
          $today = date("Y-m-d");
    		  $t_type1 = "Charges";
    		  $income_id = "ICM".rand(000001,99999);
         
          $sms = "$sysabb>>>CR";
          $sms .= " Amt: ".$currencyCode.number_format($original_amount,2,'.',',')."";
          $sms .= " Charges: ".$currencyCode.number_format($charges,2,'.',',')."";
          $sms .= " Acct: ".$acct."";
          $sms .= " Desc: $paymentMethod - | ".$transactionReference."";
          $sms .= " Time: ".$date_time."";
          $sms .= " Bal: ".$currencyCode.number_format($total_cwallet_bal,2,'.',',').""; 
          
          mysqli_query($link, "INSERT INTO income VALUES(null,'','$income_id','Charges','$charges','$today','$t_type1')");
          ($status == "pending") ? mysqli_query($link, "UPDATE user SET transfer_balance = '$total_cwallet_bal' WHERE id = '$initiator'") : "";
          ($status == "pending") ? mysqli_query($link, "UPDATE wallet_history SET paymenttype = '$paymentMethod', currency = '$currencyCode', remark = '$sms', status = 'successful', balance = '$total_cwallet_bal' WHERE refid = '$paymentref' AND status = 'pending'") : "";
          ($status == "pending") ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$recipient','$paymentref','self','','$charges','Debit','$currencyCode','$t_type1','$t_type1','successful','$wallet_date_time','$initiator','$total_cwallet_bal','')") : "";
          
          ($status == "pendingtill") ? mysqli_query($link, "UPDATE till_virtual_account SET availableBal = '$total_cwallet_bal' WHERE userid = '$initiator'") : "";
          ($status == "pendingtill") ? mysqli_query($link, "UPDATE fund_allocation_history SET paymenttype = '$paymentMethod', currency = '$currencyCode', note_comment = '$sms', status = 'successful', balance = '$total_cwallet_bal' WHERE txid = '$paymentref' AND status = 'pendingtill'") : "";
          ($status == "pendingtill") ? mysqli_query($link, "INSERT INTO fund_allocation_history VALUES(null,'$paymentref','$recipient','$initiator','$branch','self','$initiator','$charges','Debit','$t_type1','$currencyCode','$total_cwallet_bal','$sms','successful','$wallet_date_time')") : "";
          
          ($status == "pendingpool") ? mysqli_query($link, "UPDATE pool_account SET availableBal = '$total_cwallet_bal' WHERE userid = '$initiator'") : "";
          ($status == "pendingpool") ? mysqli_query($link, "UPDATE pool_history SET paymenttype = '$paymentMethod', currency = '$currencyCode', remark = '$sms', status = 'successful', balance = '$total_cwallet_bal' WHERE refid = '$paymentref' AND status = 'pendingpool'") : "";
          ($status == "pendingpool") ? mysqli_query($link, "INSERT INTO pool_history VALUES(null,'$recipient','$paymentref','self','','$charges','Debit','$currencyCode','$t_type1','$t_type1','successful','$wallet_date_time','$initiator','$total_cwallet_bal')") : "";
          
          $sendSMS->smsWithNoCharges($sysabb, $cphone, $sms, $paymentref, $initiator);

      }
      if($transactionReference != "" && $initiator != "" && !(startsWith($initiator,"VEND")) && !(startsWith($recipient,"COOP")) && !(startsWith($initiator,"MEM")) && !(startsWith($initiator,"Loan=") && !(startsWith($initiator,"AGGR")))){
          
          // update payment status and justify account balance for the institution
          $check_bobal = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$initiator'");
          $fetch_bobal = mysqli_fetch_array($check_bobal);
          $bwallet_bal = $fetch_bobal['wallet_balance'];
          $bphone = $fetch_bobal['phone'];
          $total_bwallet_bal = $bwallet_bal + $amount_fund - $charges;
          $acct = ccMasking($initiator);
          $originator = $fetch_bobal['branchid'];
          $today = date("Y-m-d");
    		  $t_type1 = "Charges";
    		  $income_id = "ICM".rand(000001,99999);
         
          $sms = "$sysabb>>>CR";
          $sms .= " Amt: ".$currencyCode.number_format($original_amount,2,'.',',')."";
          $sms .= " Charges: ".$currencyCode.number_format($charges,2,'.',',')."";
          $sms .= " Acct: ".$acct."";
          $sms .= " Desc: $paymentMethod - | ".$transactionReference."";
          $sms .= " Time: ".$date_time."";
          $sms .= " Bal: ".$currencyCode.number_format($total_bwallet_bal,2,'.',',').""; 
          
          mysqli_query($link, "INSERT INTO income VALUES(null,'','$income_id','Charges','$charges','$today','$t_type1')");
          mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$total_bwallet_bal' WHERE account = '$initiator'");
          mysqli_query($link, "UPDATE wallet_history SET paymenttype = '$paymentMethod', currency = '$currencyCode', remark = '$sms', status = 'successful', balance = '$total_bwallet_bal' WHERE refid = '$paymentref' AND status = 'pending'");
          mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$recipient','$paymentref','self','','$charges','Debit','$currencyCode','$t_type1','$t_type1','successful','$wallet_date_time','$initiator','$total_bwallet_bal','')");
          $sendSMS->smsWithNoCharges($sysabb, $bphone, $sms, $paymentref, $initiator);

      }
      
    }
    
}



?>