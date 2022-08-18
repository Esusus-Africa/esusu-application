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

$search_pendtransact = mysqli_query($link, "SELECT * FROM pool_history WHERE status = 'pendingpool' AND paymenttype = 'MNFY'");
while($fetch_pendtransact = mysqli_fetch_array($search_pendtransact))
{
    
    $recipient = $fetch_pendtransact['userid'];
    $paymentref = $fetch_pendtransact['refid'];
    $paymentref1 = urlencode($fetch_pendtransact['refid']);
    $original_amount = $fetch_pendtransact['credit'];
    $initiator = $fetch_pendtransact['initiator'];
    
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    $sysabb = $row1->abb;
    $calc_charges = ($row1->mo_gatewaycharge / 100) * $original_amount;
    $charges = ($calc_charges >= $row1->mo_cappedamt) ? $row1->mo_cappedamt : $calc_charges;
    
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'mo_payment_status'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url.'?transactionReference='.$paymentref1;
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => false,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Authorization: Basic TUtfUFJPRF9HSFpZR1o0U0hIOjJRTjJRVFBUU0hUS0hUVVFHWFVZVk1DNVFaVVNRR0RV"
      ],
    ));
    
    $status_response = curl_exec($curl);
    $err = curl_error($curl);
    
    //curl_close($curl);
    
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      //echo $response;
      $transaction = json_decode($status_response);
      
      //var_dump($transaction);
      
      //Get some values from api
      $amount_fund = $transaction->responseBody->amount;
      $paymentMethod = $transaction->responseBody->paymentMethod;
      $customerName = $transaction->responseBody->customerName;
      $paymentDescription = $transaction->responseBody->paymentDescription;
      $transactionReference = $transaction->responseBody->transactionReference;
      $currencyCode = $transaction->responseBody->currencyCode;
      $wallet_date_time = date("Y-m-d h:i:s");
      
      //echo $transaction->responseBody->paymentStatus;
      // update payment status and justify account balance for the institution
      $check_userbal = mysqli_query($link, "SELECT * FROM user WHERE id = '$initiator'");
      $fetch_userbal = mysqli_fetch_array($check_userbal);
      $cphone = $fetch_userbal['phone'];
      $originator = $fetch_userbal['created_by'];
          
      $searchBal = mysqli_query($link, "SELECT * FROM pool_account WHERE userid = '$initiator'");
      $fetchBal = mysqli_fetch_array($searchBal);
      $cwallet_bal = $fetchBal['availableBal'];
      $virtual_acctno = $fetchBal['account_number'];
      $total_cwallet_bal = $cwallet_bal + $amount_fund - $charges;
      $acct = ccMasking($virtual_acctno);
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
      mysqli_query($link, "UPDATE pool_account SET availableBal = '$total_cwallet_bal' WHERE userid = '$initiator'");
      mysqli_query($link, "UPDATE pool_history SET paymenttype = '$paymentMethod', currency = '$currencyCode', remark = '$sms', status = 'successful', balance = '$total_cwallet_bal' WHERE refid = '$paymentref' AND status = 'pendingpool'");
      mysqli_query($link, "INSERT INTO pool_history VALUES(null,'$recipient','$paymentref','self','','$charges','Debit','$currencyCode','$t_type1','$t_type1','successful','$wallet_date_time','$initiator','$total_cwallet_bal')");
      $sendSMS->smsWithNoCharges($sysabb, $cphone, $sms, $paymentref, $initiator);

    }
      
}

?>