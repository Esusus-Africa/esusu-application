<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/plain');
header("Access-Control-Allow-Methods: POST");

include("connect.php");

$sql = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = ''") or die (mysqli_error($link));
$find = mysqli_fetch_array($sql);
$gateway_uname = $find['username'];
$gateway_pass = $find['password'];
$gateway_api = $find['api'];

function sendSms($sender, $phone, $msg, $debug=false)
{
  global $gateway_uname,$gateway_pass,$gateway_api;

  $url = 'username='.$gateway_uname;
  $url.= '&password='.$gateway_pass;
  $url.= '&sender='.urlencode($sender);
  $url.= '&recipient='.urlencode($phone);
  $url.= '&message='.urlencode($msg);

  $urltouse =  $gateway_api.$url;
  //if ($debug) { echo "Request: <br>$urltouse<br><br>"; }

  //Open the URL to send the message
  $response1 = file_get_contents($urltouse);
  if ($debug) {
      //echo "Response: <br><pre>".
      //str_replace(array("<",">"),array("&lt;","&gt;"),$response1).
      //"</pre><br>"; 
  }
  return($response1);
}

function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);
}

if($_SERVER['REQUEST_METHOD'] === "POST") {
	
	$postdata = @file_get_contents("php://input");
	
	include("cgate_webhookClass.php");
	
	$encryptFile = $new->testingEncrypt($postdata);
        
    $decryptFile = $new->testingDecrypt();
        
    $decoding = json_decode($decryptFile, true);
    
    $responseCode = $decoding['responseCode'];
    
    $reference = $decoding['reference'];
        
    $TransactionID = $decoding['TransactionID'];
    
    $tId = $decoding['terminalId'];
    
    $responsemessage = $decoding['responsemessage'];
    
    $TraceID = $decoding['TraceID'];
    
    $verifyTrans = mysqli_query($link, "SELECT * FROM terminal_report WHERE trace_id = '$TraceID' AND ussdReference = '$reference' AND refid = '$TransactionID' AND status = 'Pending'");
    $numTrans = mysqli_num_rows($verifyTrans);

    $verifyTID = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_id = '$tId'");
    $numTID = mysqli_num_rows($verifyTID);
    
    if($responseCode == "00" && $numTrans == 1){
        
        $retrievalReference = $decoding['retrievalReference'];
        
        $shortName = $decoding['shortName'];
        
        $customer_mobile = $decoding['customer_mobile'];
        
        $amount = $decoding['amount'];
        
        $fetchTrans = mysqli_fetch_array($verifyTrans);
            
        //Customer Details
        $custEmail = ($fetchTrans['cust_email'] == "") ? "" : ",".$fetchTrans['cust_email'];
        $currencyCode = $fetchTrans['currencyCode'];
        $charges = $fetchTrans['charges'];
        $SubMerchantName = $fetchTrans['subMerchantName'];
            
        //Initiator 
        $bbranchid = $fetchTrans['userid'];
        $systemset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
        $row1 = mysqli_fetch_object($systemset);
        $sysabb = $row1->sender_id;
        $ihalalpay_module = $row1->halalpay_module;
            
        $instSet = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'");
        $fetchSet = mysqli_fetch_array($instSet);
        $iassigned_walletbal = $fetchSet['wallet_balance'];
            
        $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
        $r = mysqli_fetch_object($query);
        $stampduty = $r->stampduty_fee;
        $stampdutyBound = $r->stampduty_bound;
            
        //Terminal Details
        $traceId = $fetchTrans['trace_id'];
        $searchTerminal = mysqli_query($link, "SELECT * FROM terminal_reg WHERE trace_id = '$traceId' AND terminal_status = 'Assigned'");
        $fetchTerminal = mysqli_fetch_array($searchTerminal);
        $allowSettlement = $fetchTerminal['allow_settlement'];
        $settlmentType = $fetchTerminal['settlmentType'];
        $detectStampDutyforAuto = ($amount >= $stampdutyBound) ? ($amount - $stampduty) : $amount;
        $pendingBal = ($settlmentType == "manual") ? $fetchTerminal['pending_balance'] : ($fetchTerminal['pending_balance'] - $detectStampDutyforAuto);
        $settledBal = ($settlmentType == "manual") ? $fetchTerminal['settled_balance'] : ($fetchTerminal['settled_balance'] + ($detectStampDutyforAuto - $charges));
        $tCount = $fetchTerminal['total_transaction_count'] + 1;
        $sms_alert = $fetchTerminal['sms_alert'];
        $ctype = $fetchTerminal['ctype'];
        $myCommission = ($ctype == "Percentage") ? (($fetchTerminal['commission'] / 100) * $charges) : $fetchTerminal['commission'];
        $terminalId = $fetchTerminal['terminal_id'];

        $initiatedBy = $fetchTrans['initiatedBy'];
        $searchUser = mysqli_query($link, "SELECT * FROM user WHERE id = '$initiatedBy' AND created_by = '$bbranchid'");
        $fetchUser = mysqli_fetch_array($searchUser);
        $tBal = $fetchUser['transfer_balance'];
        $transferBal = ($settlmentType == "manual") ? ($tBal - $charges) : ($tBal + ($amount - $charges));
        $myPhone = $fetchUser['phone'];
        $operatorVA = $fetchUser['virtual_acctno'];
        $oprName = $fetchUser['name'].' '.$fetchUser['lname'].' - '.$operatorVA;
        
        //Email Receiver
        $emailReceiver = $fetchUser['email'].$custEmail.",pos.esusupay@gmail.com";
        
        $type = "USSD";
        $subject = ($ihalalpay_module == "On") ? "HalalPAY Cardless Withdrawal" : "esusuPAY Cardless Withdrawal";
        $recipient = "Transfer From: ".$shortName;
        $wallet_date_time = date("Y-m-d H:i:s");
        $DateTime = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');
            
        $sms = "$sysabb>>>CR";
        $sms .= " Amt: ".$currencyCode.number_format($amount,2,'.',',')."";
        $sms .= " Charges: ".$currencyCode.number_format($charges,2,'.',',')."";
        $sms .= " ID: ".ccMasking($operatorVA)."";
        $sms .= " Desc: $type - | ".$TransactionID."";
        $sms .= " Time: ".$DateTime."";
        $sms .= " Bal: ".$currencyCode.number_format($transferBal,2,'.',',')."";
            
        $max_per_page = 153;
        $sms_length = strlen($sms);
        $calc_length = ceil($sms_length / $max_per_page);
                
        $sms_charges = $calc_length * $r->fax;
        $mywallet_balance = ($settlmentType == "manual" || $allowSettlement == "No"  ? $transferBal : (($myPhone == "" || $transferBal < $sms_charges || $sms_alert != "Yes") ? $transferBal : ($transferBal - $sms_charges)));
            
        ($settlmentType == "manual" ? "" : (($myPhone == "" || $iassigned_walletbal < $sms_charges) && $sms_alert != "Yes") ? "" : $debug = true);
        ($settlmentType == "manual" ? "" : (($myPhone == "" || $iassigned_walletbal < $sms_charges) && $sms_alert != "Yes") ? "" : sendSms($sysabb,$myPhone,$sms,$debug));
        mysqli_query($link, "UPDATE user SET transfer_balance = '$transferBal' WHERE id = '$initiatedBy' AND created_by = '$bbranchid'");

        mysqli_query($link, "UPDATE terminal_report SET pending_balance = '$pendingBal', transfer_balance = '$transferBal', retrievalRef = '$retrievalReference', shortName = '$shortName', status = '$responsemessage' WHERE trace_id = '$TraceID' AND ussdReference = '$reference' AND refid = '$TransactionID' AND status = 'Pending'");
        mysqli_query($link, "UPDATE terminal_reg SET pending_balance = '$pendingBal', settled_balance = '$settledBal', total_transaction_count = '$tCount' WHERE trace_id = '$TraceID' AND terminal_status = 'Assigned'");
        ($settlmentType == "manual") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','$recipient','$amount','','Credit','$currencyCode','$type','SMS Content: $sms','successful','$wallet_date_time','$initiatedBy','$transferBal','')");
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','self','','$charges','Debit','$currencyCode','Charges','SMS Content: $recipient','successful','$wallet_date_time','$initiatedBy','$transferBal','')");
        //($settlmentType == "manual" ? "" : (($myPhone == "" || $iassigned_walletbal < $sms_charges) && $sms_alert != "Yes") ? "" : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mywallet_balance' WHERE institution_id = '$bbranchid'"));
        ($settlmentType == "manual" ? "" : ($myPhone == "" || $transferBal < $sms_charges || $sms_alert != "Yes") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','$myPhone','','$sms_charges','Debit','$currencyCode','Charges','SMS Content: $sms','successful','$wallet_date_time''$initiatedBy','$mywallet_balance','')"));
        
        $searchAdmin = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$bbranchid' AND (role = 'agent_manager' || role = 'institution_super_admin' || role = 'merchant_super_admin')");
        $fetchAdmin = mysqli_fetch_array($searchAdmin);
        $adminVA = $fetchAdmin['virtual_acctno'];
        $adminId = $fetchAdmin['id'];
        $adminBalance = ($myCommission == "0") ? $fetchAdmin['transfer_balance'] : ($fetchAdmin['transfer_balance'] + $myCommission);
        
         mysqli_query($link, "UPDATE user SET transfer_balance = '$adminBalance' WHERE virtual_acctno = '$adminVA' AND created_by = '$bbranchid'");
        ($myCommission == "0") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','$adminId','$myCommission','','Credit','$currencyCode','TRANSFER_COMMISSION','SMS Content: TRANSFER_COMMISSION','successful','$wallet_date_time','$initiatedBy','$transferBal','$adminBalance')");

        include("cgateEmailNotifier.php");
        
    }
    elseif($responseCode == "25" && $numTrans == 1){ //EXPIRED CODE
        
        $retrievalRef = $decoding['retrievalReference'];
    
        $sName = $decoding['shortName'];

        $amt = $decoding['amount'];

        $searchTerminal = mysqli_query($link, "SELECT * FROM terminal_reg WHERE trace_id = '$TraceID' AND terminal_status = 'Assigned'");
        $fetchTerminal = mysqli_fetch_array($searchTerminal);
        $pBal = $fetchTerminal['pending_balance'] - $amt;
        
        //Update Current Status;
        mysqli_query($link, "UPDATE terminal_report SET status = 'Expired' WHERE trace_id = '$TraceID' AND ussdReference = '$reference' AND refid = '$TransactionID'");
        mysqli_query($link, "UPDATE terminal_reg SET pending_balance = '$pBal' WHERE trace_id = '$TraceID' AND terminal_status = 'Assigned'");
        
    }
    elseif($responseCode == "09" && $numTrans == 1){ //PENDING STATUS
        
        //Update Current Status;
        mysqli_query($link, "UPDATE terminal_report SET status = '$responsemessage' WHERE ussdReference = '$reference' AND refid = '$TransactionID'");
        
    }
    elseif(($responseCode == "02" || $responseCode == "78") && $numTrans == 1){ //Dormant Account(02) - Blacklisted Account(78)
        
        $retrievalRef = $decoding['retrievalReference'];
    
        $sName = $decoding['shortName'];

        $amt = $decoding['amount'];

        $searchTerminal = mysqli_query($link, "SELECT * FROM terminal_reg WHERE trace_id = '$TraceID' AND terminal_status = 'Assigned'");
        $fetchTerminal = mysqli_fetch_array($searchTerminal);
        $pBal = $fetchTerminal['pending_balance'] - $amt;
        
        //Update Current Status;
        mysqli_query($link, "UPDATE terminal_report SET status = '$responsemessage' WHERE trace_id = '$TraceID' AND ussdReference = '$reference' AND refid = '$TransactionID'");
        mysqli_query($link, "UPDATE terminal_reg SET pending_balance = '$pBal' WHERE trace_id = '$TraceID' AND terminal_status = 'Assigned'");
        
    }
	
}
else{
    //do nothing
}

?>