<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userGlobalAccelerex.php";
require_once "../../models/gAHttpResponse.php";
require_once "../../config/GAAuth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "POST") {

    $authReceived = json_decode(file_get_contents("php://input"));

    $results = $gAccelerex->paymentNotification($authReceived);

    //Get Info
    $TransactionReference = $authReceived->TransactionReference;
    $TerminalID = $authReceived->TerminalID;
    $RRN = $authReceived->RetrievalReferenceNumber;
    $Stan = $authReceived->Stan;
    $MaskedPAN = $authReceived->MaskedPAN;
    $CustomerName = $authReceived->CustomerName;
    $Type = $authReceived->Type;
    $Amount = $authReceived->Amount;
    $responsemessage = $authReceived->StatusDescription;
    $currencyCode = $authReceived->Currency;

    //Check indepotent
    $checkIndepotent = $user->checkTerminalDuplicateRpt($TransactionReference);

    if($results === -1){

        $newhttp->badRequest("transaction reference should not be empty");

    }elseif($results === -2){

        $newhttp->badRequest("Amount should not be empty");

    }elseif($results === -3){
        
        $newhttp->badRequest("Invalid Amount");
        
    }elseif($results === -4){
        
        $newhttp->badRequest("currency cannot be empty");
        
    }elseif($results === -5){
        
        $newhttp->badRequest("Invalid currency");
        
    }elseif($results === -6){
        
        $newhttp->badRequest("Transaction type cannot be empty");
        
    }elseif($results === -7){
        
        $newhttp->badRequest("Invalid transaction type");
        
    }elseif($results === -8){
        
        $newhttp->badRequest("Customer name should not be empty");
        
    }elseif($results === -9){
        
        $newhttp->badRequest("Masked PAN should not be empty");
        
    }elseif($results === -10){
        
        $newhttp->badRequest("payment date should not be empty");
        
    }elseif($results === -11){
        
        $newhttp->badRequest("TerminalID should not be empty");
        
    }elseif($results === -12){
        
        $newhttp->badRequest("Invalid TerminalID");
        
    }elseif($results === -13){
        
        $newhttp->badRequest("Field should not be empty");
        
    }elseif($checkIndepotent != "0"){
        
        $newhttp->OK($resultsInfo, $results);
        
    }else{

        $checkTerm = $user->checkTerminal($TerminalID);
        $tidoperator = $checkTerm['tidoperator'];
        $bbranchid = $checkTerm['merchant_id'];
        $channel = "POS";
        $merchantName = $checkTerm['merchant_name'];
        $allowSettlement = $checkTerm['allow_settlement'];
        $settlmentType = $checkTerm['settlmentType'];
        $stampduty = $checkTerm['stampduty_bound'];
        $sms_alert = $checkTerm['sms_alert'];
        $tCount = $checkTerm['total_transaction_count'] + 1;
        $chargesType = $checkTerm['ctype']; //Flat OR Percentage

        //Standard Global Settings
        $systemDetails = $db->fetchSystemSet();
        $stampdutyBound = $systemDetails['stampduty_bound'];
        $vat_rate = $systemDetails['vat_rate'];
        $detectStampDutyforAuto = ($Amount >= $stampdutyBound) ? ($Amount - $stampduty) : $Amount;
      
        //Charges Calculation
        $charges = ($chargesType == "Flat") ? $checkTerm['charges'] : number_format((($checkTerm['charges'] / 100) * $Amount),2,'.','');
        $chargesPlusVat = number_format((($charges * $vat_rate) + $charges),2,'.','');
        $aggrCommission = ($chargesType == "Flat") ? $checkTerm['commission'] : ($checkTerm['commission'] * $charges);

        //Pool Account
        $poolAcct = $checkTerm['poolAccount'];
        $searchPool = $user->fetchPoolAcctByAcctNo($poolAcct);
        $poolUserId = $searchPool['userid'];
        $poolcomm = ($poolAcct == "") ? 0 : number_format(($checkTerm['charge_comm'] * $chargesPlusVat),2,'.','');
        $pBal = $searchPool['availableBal'];
        $poolBal = ($poolAcct == "" ? $pBal : ($settlmentType == "auto" ? ($pBal - $Amount) : $pBal));

        //Pemding & Settled Balance without Reversal
        $pendingBal = ($allowSettlement == "Yes" ? (($poolAcct == "" && $settlmentType == "manual") || ($poolAcct != "" && $settlmentType == "manual" && ($pBal < $Amount || $pBal >= $Amount)) || ($poolAcct != "" && $settlmentType == "auto" && $pBal < $Amount) ? ($checkTerm['pending_balance'] + $detectStampDutyforAuto) : $checkTerm['pending_balance']) : $checkTerm['pending_balance']);
        $settledBal = ($allowSettlement == "Yes" ? (($settlmentType == "auto" && $poolAcct == "") || ($settlmentType == "auto" && $poolAcct != "" && $pBal >= $Amount) ? ($checkTerm['settled_balance'] + ($detectStampDutyforAuto - $chargesPlusVat)) : $checkTerm['settled_balance']) : $checkTerm['settled_balance']);
  
        $paymentMethod = "POS";
        $subject = "POS";
        $wallet_date_time = date("Y-m-d H:i:s");
        $DateTime = date('m/d/Y').' '.(date('h') + 1).':'.date('i A');

        //check operator info
        $operatorInfo = $user->fetchTerminalOprt($tidoperator,$bbranchid);
        $transferBal = ($allowSettlement == "Yes" ? ($settlmentType == "manual" ? $operatorInfo['transfer_balance'] : ($operatorInfo['transfer_balance'] + $detectStampDutyforAuto - $chargesPlusVat)) : ($operatorInfo['transfer_balance'] + $detectStampDutyforAuto - $chargesPlusVat));
        $myPhone = $operatorInfo['phone'];
        $vAccountNo = $operatorInfo['virtual_acctno'];
        $oprName = $operatorInfo['name'].' '.$operatorInfo['lname'].' - '.$vAccountNo;
        $mybranchid = $operatorInfo['branchid'];
        $tidOpEmail = ",".$operatorInfo['email'];

        //Aggregator details
        $aggrid = $checkTerm['initiatedBy'];
        $aggregatorInfo = $user->fetchTerminalOprt($aggrid,$bbranchid);
        $aggrWBalBforComm = $aggregatorInfo['transfer_balance'];
        $aggrWBalAfterComm = $aggrWBalBforComm + $aggrCommission;
        $aggrPhone = $aggregatorInfo['phone'];
        $aggrEmail = ",".$aggregatorInfo['email'];

        //Merchant Details
        $memberSettings = $db->fetchMemberSettings($bbranchid);
        $sysabb = $memberSettings['sender_id'];
        $recipient = $CustomerName;
        $bank = $memberSettings['cname'].' - '.$TerminalID;

        //Institution Info
        $instInfo = $user->fetchInstitutionById($bbranchid);
        //Email Receiver
        $emailReceiver = $instInfo['official_email'].$tidOpEmail.",pos.esusupay@gmail.com";

        $sms = "$sysabb>>>CR";
        $sms .= " Amt: ".$currencyCode.number_format($Amount,2,'.',',')."";
        $sms .= " Charges: ".$currencyCode.number_format($chargesPlusVat,2,'.',',')."";
        $sms .= " ID: ".$user->ccMasking($vAccountNo)."";
        $sms .= " Desc: $paymentMethod - | ".$TransactionReference."";
        $sms .= " Time: ".$DateTime."";
        $sms .= " Bal: ".$currencyCode.number_format($transferBal,2,'.',',')."";
        
        $max_per_page = 153;
        $sms_length = strlen($sms);
        $calc_length = ceil($sms_length / $max_per_page);
        
        $sms_charges = $calc_length * $systemDetails['fax'];
        $mywallet_balance = ($settlmentType == "manual" || $allowSettlement == "No" || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal < $Amount) ? $transferBal : (($myPhone == "" || $transferBal < $sms_charges || $sms_alert != "Yes") ? $transferBal : ($transferBal - $sms_charges)));

        //UPDATE OPERATOR BALANCE IF SETTLEMENT IS SET TO AUTO
        ($allowSettlement == "No" || ($allowSettlement == "Yes" && $settlmentType == "manual") ? "" : (($allowSettlement == "Yes" && $settlmentType == "auto") || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount) ? $user->updateUserWallet($mywallet_balance, $tidoperator, $bbranchid) : ""));

        //LOG POS CREDIT TRANSACTION
        ($allowSettlement == "No" || ($allowSettlement == "Yes" && $settlmentType == "manual") ? "" : (($allowSettlement == "Yes" && $settlmentType == "auto") || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount) ? $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance, receiver_bal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : ""));
        ($allowSettlement == "No" || ($allowSettlement == "Yes" && $settlmentType == "manual") ? "" : (($allowSettlement == "Yes" && $settlmentType == "auto") || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount) ? $db->insertSpecialWalletHistory($query, $bbranchid, $TransactionReference, 'POS with TID: '.$TerminalID, $Amount, '', 'Credit', $currencyCode, $paymentMethod, 'SMS Content: '.$sms, 'successful', $wallet_date_time, $tidoperator, $transferBal, '') : ""));
               
        //LOG POS STAMPDUTY (DEBIT)
        ($allowSettlement == "No" ? "" : (($allowSettlement == "Yes" && $settlmentType == "auto" && $Amount >= $stampdutyBound && $stampduty > 0) || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount && $Amount >= $stampdutyBound && $stampduty > 0) ? $query1 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance, receiver_bal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : ""));
        ($allowSettlement == "No" ? "" : (($allowSettlement == "Yes" && $settlmentType == "auto" && $Amount >= $stampdutyBound && $stampduty > 0) || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount && $Amount >= $stampdutyBound && $stampduty > 0) ? $db->insertSpecialWalletHistory($query1, $bbranchid, $TransactionReference, 'POS with TID: '.$TerminalID, '', $stampduty, 'Debit', $currencyCode, 'Stamp Duty', 'SMS Content: '.$sms, 'successful', $wallet_date_time, $tidoperator, $transferBal, '') : ""));

        //LOG POS TRANSACTION CHARGES (DEBIT)
        ($allowSettlement == "No" || ($allowSettlement == "Yes" && $settlmentType == "manual") ? "" : (($allowSettlement == "Yes" && $settlmentType == "auto") || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount) ? $query2 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance, receiver_bal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : ""));
        ($allowSettlement == "No" || ($allowSettlement == "Yes" && $settlmentType == "manual") ? "" : (($allowSettlement == "Yes" && $settlmentType == "auto") || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount) ? $db->insertSpecialWalletHistory($query1, $bbranchid, $TransactionReference, 'POS with TID: '.$TerminalID, '', $chargesPlusVat, 'Debit', $currencyCode, 'Charges', 'SMS Content: '.$recipient.', POS with TID: '.$TerminalID, 'successful', $wallet_date_time, $tidoperator, $transferBal, '') : ""));

        //UPDATE AGGREGATOR COMMISSION BALANCE AND LOG THE TRANSACTION RECORDS
        (($aggrCommission != "0" && $settlmentType != "manual" && $aggrid != $tidoperator) || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount)) ? $user->updateUserWallet($aggrWBalAfterComm, $aggrid, $bbranchid) : "";
        (($aggrCommission != "0" && $settlmentType != "manual" && $aggrid != $tidoperator) || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount)) ? $query3 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance, receiver_bal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
        (($aggrCommission != "0" && $settlmentType != "manual" && $aggrid != $tidoperator) || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount)) ? $db->insertSpecialWalletHistory($query3, $bbranchid, $TransactionReference, 'POS with TID: '.$TerminalID, $aggrCommission, '', 'Credit', $currencyCode, 'TERMINAL_COMMISSION', 'SMS Content: '.$recipient.', POS with TID: '.$TerminalID, 'successful', $wallet_date_time, $aggrid, $aggrWBalAfterComm, '') : "";

        //UPDATE POOL ACCOUNT BALANCE AND LOG THE TRANSACTION RECORDS
        ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount) ? $user->updatePoolAccount($poolBal, $poolAcct) : "";
        ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount) ? $query4 = "INSERT INTO pool_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
        ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount) ? $db->insertSpecialWalletHistory($query4, $bbranchid, $TransactionReference, 'POS Settlement with TID: '.$TerminalID, '', $Amount, 'Debit', $currencyCode, $paymentMethod, 'SMS Content: '.$sms, 'successful', $wallet_date_time, $tidoperator, $poolBal) : "";
        
        //POOL ACCOUNT REPORT FOR ESUSU AFRICA COMMISSION
        ($allowSettlement == "No" ? "" : ($poolAcct != "" && $poolcomm != "0" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount ? $query5 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance, receiver_bal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : ""));
        ($allowSettlement == "No" ? "" : ($poolAcct != "" && $poolcomm != "0" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount ? $db->insertSpecialWalletHistory($query5, $bbranchid, $TransactionReference, 'POS with TID:: '.$TerminalID, '', $poolcomm, 'Debit', $currencyCode, 'POOL_COMMISSION', 'SMS Content: '.$recipient.', POS with TID: '.$TerminalID, 'successful', $wallet_date_time, $tidoperator, $transferBal, '') : ""));
        
        //POOL ACCOUNT INCOME LOG FOR ESUSU AFRICA
        ($allowSettlement == "No" ? "" : ($poolAcct != "" && $poolcomm != "0" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount ? $query6 = "INSERT INTO income (companyid, icm_id, icm_type, icm_amt, icm_date, icm_desc) VALUES (?, ?, ?, ?, ?, ?)" : ""));
        ($allowSettlement == "No" ? "" : ($poolAcct != "" && $poolcomm != "0" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount ? $db->insertIncome($query6, '', $TransactionReference, 'Charges', $poolcomm, $date, 'POOL_COMMISSION') : ""));
        
        //LOG RECENT TRANSACTION REPORT FOR THE ASSIGNED TERMINAL
        $queryRpt = "INSERT INTO terminal_report (userid, refid, terminalId, retrievalRef, channel, institutionCode, subMerchantName, trace_id, shortName, ussdReference, amount, charges, currencyCode, pending_balance, transfer_balance, initiatedBy, status, cust_phone, cust_email, date_time, branchid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $db->insertTerminalRpt($queryRpt, $bbranchid, $TransactionReference, $TerminalID, $RRN, $channel, $Stan, $merchantName, $MaskedPAN, $CustomerName, $Type, $Amount, $charges, $currencyCode, $pendingBal, $transferBal, $tidoperator, "Approved", $myPhone, $emailReceiver, $wallet_date_time, $mybranchid);
        
        //UPDATE TERMINAL SETTLED BALANCE, PENDING BALANCE AND TOTAL TRANSACTION COUNT
        $user->updateTerminalReg($pendingBal, $settledBal, $tCount, $TerminalID);

        //SEND SMS NOTIFICATION
        (($settlmentType == "manual" || $allowSettlement == "No" || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal < $Amount)) ? "" : (($myPhone == "" || $transferBal < $sms_charges || $sms_alert != "Yes") ? "" : $gAccelerex->userGeneralAlert($sysabb, $myPhone, $sms, $bbranchid, $TransactionReference, $sms_charges, $currencyCode, $wallet_date_time, $tidoperator, $mywallet_balance)));

        //SEND EMAIL NOTIFICATION
        $gAccelerex->emailNotifier($emailReceiver, $TransactionReference, $responsemessage, $paymentMethod, $recipient, $settlmentType, $oprName, $bank, $pendingBal, $Amount, $detectStampDutyforAuto, $charges, $mywallet_balance, $subject, $TerminalID, $bbranchid);

        $newhttp->OK($resultsInfo, $results);
        
    }

}
?>