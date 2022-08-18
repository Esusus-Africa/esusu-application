<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");

include("connect.php");
require_once "smsAlertClass.php";

function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);
}

if($_SERVER['REQUEST_METHOD'] === "POST"){
    
    // Retrieve the request's body
	$input = @file_get_contents("php://input");
	
	$response = json_decode($input);
	
    $TransactionID = $response->TransactionReference;
    $terminalId = $response->TerminalID;
    $responseCode = $response->StatusCode; //00
    $pan = $response->CustomerName;
    $RRN = $response->RetrievalReferenceNumber;
    $STAN = $response->STAN;
    $authCode = $response->TransactionID;
    $responsemessage = ($responseCode == "00") ? "Approved" : "Declined"; //Approved
    $amount = $response->Amount;
    $fee = $response->Fee;
    $rrn = $response->TransactionID;
    $transactionTime = $response->PaymentDate;
    $Type = $response->Type;
    $currencyCode = $response->Currency;
    
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $vat_rate = $r->vat_rate;
    /**
    $stampduty = $r->stampduty_fee;
    $stampdutyBound = $r->stampduty_bound;
    */

    if($Type == "Purchase"){
	
        $verifyTrans = mysqli_query($link, "SELECT * FROM terminal_report WHERE retrievalRef = '$RRN'");
        $numTrans = mysqli_num_rows($verifyTrans);

        $verifyTID = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_id = '$terminalId'");
        $numTID = mysqli_num_rows($verifyTID);

        $ip = $_SERVER['REMOTE_ADDR']; // This will contain the ip of the request
        $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip), true);
        $origin_city = $dataArray["geoplugin_city"];
        $origin_country = $dataArray["geoplugin_countryName"];
        
        $searchTerminal = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_id = '$terminalId' AND terminal_status = 'Assigned'");
        $fetchTerminal = mysqli_fetch_array($searchTerminal);
        $SubMerchantName = $fetchTerminal['merchant_name'];
        $merchantAddress = $origin_city.", ".$origin_country;
        $allowSettlement = $fetchTerminal['allow_settlement'];
        $settlmentType = $fetchTerminal['settlmentType'];
        $tCount = $fetchTerminal['total_transaction_count'] + 1;
        $sms_alert = $fetchTerminal['sms_alert'];
        $bbranchid = $fetchTerminal['merchant_id'];
        $detectStampDutyforAuto = $amount;  //Update on amount settled error on email receipt
        $chargesType = $fetchTerminal['ctype']; //Flat OR Percentage
        $charges = ($chargesType == "Flat") ? $fetchTerminal['charges'] : number_format((($fetchTerminal['charges'] / 100) * $amount),2,'.','');
        $chargesPlusVat = number_format((($charges * $vat_rate) + $charges),2,'.','');
        $aggrCommission = ($chargesType == "Flat") ? $fetchTerminal['commission'] : ($fetchTerminal['commission'] * $charges);
        
        //Pool Account
        $poolAcct = $fetchTerminal['poolAccount'];
        $searchPool = mysqli_query($link, "SELECT * FROM pool_account WHERE account_number = '$poolAcct'");
        $fetchPool = mysqli_fetch_array($searchPool);
        $poolUserId = $fetchPool['userid'];
        $poolcomm = ($poolAcct == "") ? 0 : number_format(($fetchTerminal['charge_comm'] * $chargesPlusVat),2,'.','');
        $pBal = $fetchPool['availableBal'];
        $poolBal = ($poolAcct == "" ? $pBal : ($settlmentType == "auto" ? ($pBal - $amount) : $pBal));
        $poolRevBal = ($poolAcct == "" ? $pBal : ($settlmentType == "auto" ? ($pBal + $amount) : $pBal));
        
        //Without Reversal
        $pendingBal = ($allowSettlement == "Yes" ? (($poolAcct == "" && $settlmentType == "manual") || ($poolAcct != "" && $settlmentType == "manual" && ($pBal < $amount || $pBal >= $amount)) || ($poolAcct != "" && $settlmentType == "auto" && $pBal < $amount) ? ($fetchTerminal['pending_balance'] + $amount) : $fetchTerminal['pending_balance']) : $fetchTerminal['pending_balance']);
        $settledBal = ($allowSettlement == "Yes" ? (($settlmentType == "auto" && $poolAcct == "") || ($settlmentType == "auto" && $poolAcct != "" && $pBal >= $amount) ? ($fetchTerminal['settled_balance'] + ($amount - $chargesPlusVat)) : $fetchTerminal['settled_balance']) : $fetchTerminal['settled_balance']);
        $paymentMethod = "POS";
        $subject = "POS";
        $wallet_date_time = date("Y-m-d H:i:s");
        $DateTime = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');

        $tidoperator = $fetchTerminal['tidoperator'];
        $searchUser = mysqli_query($link, "SELECT * FROM user WHERE id = '$tidoperator'");
        $fetchUser = mysqli_fetch_array($searchUser);
        $transferBal = ($allowSettlement == "Yes" ? ($settlmentType == "manual" ? $fetchUser['transfer_balance'] : ($fetchUser['transfer_balance'] + $amount - $chargesPlusVat)) : $fetchUser['transfer_balance']);
        $myPhone = $fetchUser['phone'];
        $vAccountNo = $fetchUser['virtual_acctno'];
        $oprName = $fetchUser['name'].' '.$fetchUser['lname'].' - '.$vAccountNo;
        $mybranchid = $fetchUser['branchid'];
        $tidOpEmail = ",".$fetchUser['email'];

        $aggrid = $fetchTerminal['initiatedBy'];
        $searchAggr = mysqli_query($link, "SELECT * FROM user WHERE id = '$aggrid'");
        $fetchAggr = mysqli_fetch_array($searchAggr);
        $aggrWBalBforComm = $fetchAggr['transfer_balance'];
        $aggrWBalAfterComm = $aggrWBalBforComm + $aggrCommission;
        $aggrWBalAfterRev = $aggrWBalBforComm - $aggrCommission;
        $aggrPhone = $fetchAggr['phone'];
        $aggrEmail = ",".$fetchAggr['email'];

        $systemset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
        $row1 = mysqli_fetch_object($systemset);
        $sysabb = $row1->sender_id;
        $recipient = $SubMerchantName;
        $bank = $row1->cname.' - '.$terminalId;

        $search_emailConfig = mysqli_query($link, "SELECT * FROM email_config WHERE companyid = '$bbranchid'");
        $fetch_emailConfig = mysqli_fetch_array($search_emailConfig);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
        
        $instSet = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'");
        $fetchSet = mysqli_fetch_array($instSet);
        //Email Receiver
        $emailReceiver = $fetchSet['official_email'].$tidOpEmail.",pos.esusupay@gmail.com";
                
        $sms = "$sysabb>>>CR";
        $sms .= " Amt: ".$currencyCode.number_format($amount,2,'.',',')."";
        $sms .= " Charges: ".$currencyCode.number_format($chargesPlusVat,2,'.',',')."";
        $sms .= " ID: ".ccMasking($vAccountNo)."";
        $sms .= " Desc: $paymentMethod - | ".$RRN."";
        $sms .= " Time: ".$DateTime."";
        $sms .= " Bal: ".$currencyCode.number_format($transferBal,2,'.',',')."";
                
        $max_per_page = 153;
        $sms_length = strlen($sms);
        $calc_length = ceil($sms_length / $max_per_page);
                    
        $sms_charges = $calc_length * $r->fax;
        $mywallet_balance = ($settlmentType == "manual" || $allowSettlement == "No" || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal < $amount) ? $transferBal : (($myPhone == "" || $transferBal < $sms_charges || $sms_alert != "Yes") ? $transferBal : ($transferBal - $sms_charges)));
        
        if($responseCode == "00" && $numTrans == 0 && $numTID == 1){
            
            http_response_code(200); //PHP 5.4 or greater

            $date = date("Y-m-d");
            
            //Sms Notification for Automatic Settlement Only
            ($settlmentType == "manual" || $allowSettlement == "No" || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal < $amount) ? "" : (($myPhone == "" || $transferBal < $sms_charges || $sms_alert != "Yes") ? "" : $sendSMS->smsWithNoCharges($sysabb, $myPhone, $sms, $TransactionID, $tidoperator)));
            ($settlmentType == "manual" || $allowSettlement == "No" || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal < $amount) ? "" : (($myPhone == "" || $transferBal < $sms_charges || $sms_alert != "Yes") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','$myPhone','','$sms_charges','Debit','$currencyCode','Charges','SMS Content: $sms','successful','$wallet_date_time''$tidoperator','$mywallet_balance','')")));
            
            //Email Notification
            $sendSMS->posEmailNotifier($emailReceiver, $TransactionID, $DateTime, $responsemessage, $paymentMethod, $bank, $settlmentType, $oprName, $SubMerchantName, $currencyCode, $pendingBal, $amount, $detectStampDutyforAuto, $charges, $mywallet_balance, $subject, $terminalId, $emailConfigStatus, $fetch_emailConfig);

            //Operator Balance Update After or without Settlement minus charges
            ($allowSettlement == "No" || ($allowSettlement == "Yes" && $settlmentType == "manual") ? "" : (($allowSettlement == "Yes" && $settlmentType == "auto") || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $amount) ? mysqli_query($link, "UPDATE user SET transfer_balance = '$mywallet_balance' WHERE id = '$tidoperator'") : ""));
            ($allowSettlement == "No" || ($allowSettlement == "Yes" && $settlmentType == "manual") ? "" : (($allowSettlement == "Yes" && $settlmentType == "auto") || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $amount) ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','POS with TID: $terminalId','$amount','','Credit','$currencyCode','$paymentMethod','SMS Content: $sms','successful','$wallet_date_time','$tidoperator','$transferBal','')") : ""));
            //($allowSettlement == "No" ? "" : ($allowSettlement == "Yes" && $settlmentType == "auto" && $amount >= $stampdutyBound ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','POS with TID: $terminalId','','$stampduty','Debit','$currencyCode','Stamp Duty','SMS Content: $sms','successful','$wallet_date_time','$tidoperator','$transferBal','')") : ""));
            ($allowSettlement == "No" || ($allowSettlement == "Yes" && $settlmentType == "manual") ? "" : (($allowSettlement == "Yes" && $settlmentType == "auto") || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $amount) ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','POS with TID: $terminalId','','$chargesPlusVat','Debit','$currencyCode','Charges','SMS Content: $recipient, POS with TID: $terminalId','successful','$wallet_date_time','$tidoperator','$transferBal','')") : ""));
            
            //Aggregator Commission Balance Update
            (($aggrCommission != "0" && $settlmentType != "manual" && $aggrid != $tidoperator) || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $amount)) ? mysqli_query($link, "UPDATE user SET transfer_balance = '$aggrWBalAfterComm' WHERE id = '$aggrid'") : "";
            (($aggrCommission != "0" && $settlmentType != "manual" && $aggrid != $tidoperator) || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $amount)) ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','POS with TID: $terminalId','$aggrCommission','','Credit','$currencyCode','TERMINAL_COMMISSION','SMS Content: $recipient, POS with TID: $terminalId','successful','$wallet_date_time','$aggrid','$aggrWBalAfterComm','')") : "";

            //Pool Account History
            ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $amount) ? mysqli_query($link, "UPDATE pool_account SET availableBal = '$poolBal' WHERE account_number = '$poolAcct'") : "";
            ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $amount) ? mysqli_query($link, "INSERT INTO pool_history VALUE(null,'$bbranchid','$TransactionID','POS Settlement with TID: $terminalId','','$amount','Debit','$currencyCode','$paymentMethod','SMS Content: $sms','successful','$wallet_date_time','$tidoperator','$poolBal')") : "";
            //Pool Report for wallet history
            ($allowSettlement == "No" ? "" : ($poolAcct != "" && $poolcomm != "0" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $amount ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$TransactionID','POS with TID: $terminalId','','$poolcomm','Debit','$currencyCode','POOL_COMMISSION','SMS Content: $recipient, POS with TID: $terminalId','successful','$wallet_date_time','$tidoperator','$transferBal','')") : ""));
            ($allowSettlement == "No" ? "" : ($poolAcct != "" && $poolcomm != "0" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $amount ? mysqli_query($link, "INSERT INTO income VALUE(null,'','$TransactionID','Charges','$poolcomm','$date','POOL_COMMISSION')") : ""));

            //Log Recent Report for the Assigned Terminal
            mysqli_query($link, "INSERT INTO terminal_report VALUES(null,'$bbranchid','$TransactionID','$terminalId','$RRN','$paymentMethod','$authCode','$SubMerchantName','$pan','$merchantAddress','$STAN','$amount','$chargesPlusVat','$currencyCode','$pendingBal','$transferBal','$tidoperator','$responsemessage','$myPhone','$emailReceiver','$wallet_date_time','$mybranchid')");
            //Update Terminal settled balance and total transaction count
            mysqli_query($link, "UPDATE terminal_reg SET pending_balance = '$pendingBal', settled_balance = '$settledBal', total_transaction_count = '$tCount' WHERE terminal_id = '$terminalId' AND terminal_status = 'Assigned'");        
            
            $arr = array("reponseCode"=> $responseCode,"auditID"=> $TransactionID,"responseMessage"=> "Transaction Done Successfully");
            
            echo json_encode($arr);
            
        }
        elseif($responseCode != "00" && $numTrans == 0 && $numTID == 1){ //22
            
            http_response_code(400); // PHP 5.4 or greater

            //Log Recent Report for the Assigned Terminal
            mysqli_query($link, "INSERT INTO terminal_report VALUES(null,'$bbranchid','$TransactionID','$terminalId','$RRN','$paymentMethod','$authCode','$SubMerchantName','$pan','$merchantAddress','$STAN','$amount','$chargesPlusVat','$currencyCode','$pendingBal','$transferBal','$tidoperator','$responsemessage','$myPhone','$emailReceiver','$wallet_date_time')");
            
            $arr = array("reponseCode"=> $responseCode,"auditID"=> $RRN,"responseMessage"=> "Transaction Declined");
            
            echo json_encode($arr);
            
        }
        elseif($responseCode == "00" && $numTrans == 1 && $numTID == 1){
            
            http_response_code(400); // PHP 5.4 or greater

            $arr = array("reponseCode"=> 400,"auditID"=> $RRN,"responseMessage"=> "Request Already Sent");
            
            echo json_encode($arr);
            
        }
        else{
        
            exit();
            
        }

    }
    else{

        exit();

    }
    
}
?>