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

    $results = $gAccelerex->paymentReversal($authReceived);

    //Get Info
    $TransactionReference = $authReceived->TransactionReference;
    $Amount = $authReceived->Amount;
    $currencyCode = $authReceived->Currency;
    $RRN = $authReceived->RetrievalReferenceNumber;
    $Stan = $authReceived->Stan;
    $Reference = $authReceived->Reference;
    $TerminalID = $authReceived->TerminalID;
    $responsemessage = "Reversed";

    //Check indepotent
    $checkIndepotent = $user->checkTerminalDuplicateRpt($TransactionReference);

    if($results === -1){

        $newhttp->badRequest("Amount should not be empty");

    }elseif($results === -2){

        $newhttp->badRequest("Invalid Amount");

    }elseif($results === -3){
        
        $newhttp->badRequest("Transaction reference should not be empty");
        
    }elseif($results === -4){
        
        $newhttp->badRequest("Invalid transaction reference");
        
    }elseif($results === -5){
        
        $newhttp->badRequest("RRN should not be empty");
        
    }elseif($results === -6){
        
        $newhttp->badRequest("Invalid RRN");
        
    }elseif($results === -7){
        
        $newhttp->badRequest("Payment date should not be empty");
        
    }elseif($results === -8){
        
        $newhttp->notAuthorized("terminalID is empty");
        
    }elseif($results === -9){
        
        $newhttp->notAuthorized("Invalid terminalID");
        
    }elseif($results === -10){
        
        $newhttp->badRequest("Field should not be empty");
        
    }elseif($results === -11){
        
        $newhttp->badRequest("Invalid Payment date");
        
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
        $tRCount = $checkTerm['total_transaction_count'] - 1;
        $chargesType = $checkTerm['ctype']; //Flat OR Percentage

        //Standard Global Settings
        $systemDetails = $db->fetchSystemSet();
        $stampdutyBound = $companyDetails['stampduty_bound'];
        $vat_rate = $companyDetails['vat_rate'];

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
        $poolRevBal = ($poolAcct == "" ? $pBal : ($settlmentType == "auto" ? ($pBal + $Amount) : $pBal));

        //Pemding & Settled Balance with Reversal
        $pendingRBal = ($allowSettlement == "Yes" && $settlmentType == "manual") ? ($checkTerm['pending_balance'] - $Amount) : $checkTerm['pending_balance'];
        $settledRBal = ($allowSettlement == "Yes" && $settlmentType == "auto") ? ($checkTerm['settled_balance'] - $Amount) : $checkTerm['settled_balance'];

        $paymentMethod = "POS_Reversal";
        $subject = "POS_Reversal";
        $wallet_date_time = date("Y-m-d H:i:s");
        $DateTime = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');

        //check operator info
        $operatorInfo = $user->fetchTerminalOprt($tidoperator,$bbranchid);
        $reverseBal = ($allowSettlement == "Yes" ? ($settlmentType == "manual" ? $operatorInfo['transfer_balance'] : ($operatorInfo['transfer_balance'] - $Amount)) : $operatorInfo['transfer_balance']);
        $myPhone = $operatorInfo['phone'];
        $vAccountNo = $operatorInfo['virtual_acctno'];
        $oprName = $operatorInfo['name'].' '.$operatorInfo['lname'].' - '.$vAccountNo;
        $mybranchid = $operatorInfo['branchid'];
        $tidOpEmail = ",".$operatorInfo['email'];

        //Aggregator details
        $aggrid = $checkTerm['initiatedBy'];
        $aggregatorInfo = $user->fetchTerminalOprt($aggrid,$bbranchid);
        $aggrWBalBforComm = $aggregatorInfo['transfer_balance'];
        $aggrWBalAfterRev = $aggrWBalBforComm - $aggrCommission;
        $aggrPhone = $aggregatorInfo['phone'];
        $aggrEmail = ",".$aggregatorInfo['email'];

        //Merchant Details
        $memberSettings = $db->fetchMemberSettings($bbranchid);
        $sysabb = $memberSettings['sender_id'];
        $recipient = "Partner Bank";
        $bank = $memberSettings['cname'].' - '.$TerminalID;

        //Institution Info
        $instInfo = $user->fetchInstitutionById($bbranchid);
        //Email Receiver
        $emailReceiver = $instInfo['official_email'].$tidOpEmail.",pos.esusupay@gmail.com";

        $sms = "$sysabb>>>DR";
        $sms .= " Amt: ".$currencyCode.number_format($Amount,2,'.',',')."";
        $sms .= " ID: ".$user->ccMasking($vAccountNo)."";
        $sms .= " Desc: $paymentMethod - | ".$TransactionReference."";
        $sms .= " Time: ".$DateTime."";
        $sms .= " Bal: ".$currencyCode.number_format($reverseBal,2,'.',',')."";
        
        $max_per_page = 153;
        $sms_length = strlen($sms);
        $calc_length = ceil($sms_length / $max_per_page);
        
        $sms_charges = $calc_length * $systemDetails['fax'];
        $mywallet_balance = ($settlmentType == "manual" || $allowSettlement == "No" || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal < $Amount) ? $reverseBal : (($myPhone == "" || $reverseBal < $sms_charges || $sms_alert != "Yes") ? $reverseBal : ($reverseBal - $sms_charges)));

        //UPDATE OPERATOR BALANCE IF SETTLEMENT IS SET TO AUTO
        (($allowSettlement == "No" || ($allowSettlement == "Yes" && $settlmentType == "manual")) ? "" : ((($allowSettlement == "Yes" && $settlmentType == "auto") || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount)) ? $user->updateUserWallet($mywallet_balance, $tidoperator, $bbranchid) : ""));

        //LOG POS DEBIT TRANSACTION FOR REVERSAL
        (($allowSettlement == "No" || ($allowSettlement == "Yes" && $settlmentType == "manual")) ? "" : ((($allowSettlement == "Yes" && $settlmentType == "auto") || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount)) ? $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance, receiver_bal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : ""));
        (($allowSettlement == "No" || ($allowSettlement == "Yes" && $settlmentType == "manual")) ? "" : ((($allowSettlement == "Yes" && $settlmentType == "auto") || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount)) ? $db->insertSpecialWalletHistory($query, $bbranchid, $TransactionReference, 'POS with TID: '.$TerminalID, '', $Amount, 'Debit', $currencyCode, $paymentMethod, 'SMS Content: '.$sms, 'successful', $wallet_date_time, $tidoperator, $reverseBal, '') : ""));

        //UPDATE AGGREGATOR COMMISSION REVERSAL AND LOG THE TRANSACTION RECORDS
        (($aggrCommission != "0" && $settlmentType != "manual" && $aggrid != $tidoperator) || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount)) ? $user->updateUserWallet($aggrWBalAfterRev, $aggrid, $bbranchid) : "";
        (($aggrCommission != "0" && $settlmentType != "manual" && $aggrid != $tidoperator) || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount)) ? $query3 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance, receiver_bal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
        (($aggrCommission != "0" && $settlmentType != "manual" && $aggrid != $tidoperator) || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount)) ? $db->insertSpecialWalletHistory($query3, $bbranchid, $TransactionReference, 'POS with TID: '.$TerminalID, '', $aggrCommission, 'Debit', $currencyCode, 'TERMINAL_COMMISSION_REVERSAL', 'SMS Content: '.$recipient.', POS with TID: '.$TerminalID, 'successful', $wallet_date_time, $aggrid, $aggrWBalAfterRev, '') : "";

        //UPDATE POOL ACCOUNT BALANCE AND LOG THE TRANSACTION RECORDS
        ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount) ? $user->updatePoolAccount($poolRevBal, $poolAcct) : "";
        ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount) ? $query4 = "INSERT INTO pool_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
        ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount) ? $db->insertSpecialWalletHistory($query4, $bbranchid, $TransactionReference, 'POS Settlement with TID: '.$TerminalID, $Amount, '', 'Credit', $currencyCode, $paymentMethod, 'SMS Content: '.$sms, 'successful', $wallet_date_time, $tidoperator, $poolRevBal) : "";
        
        //POOL ACCOUNT EXPENSES LOG FOR ESUSU AFRICA (REVERSAL)
        ($allowSettlement == "No" ? "" : ($poolAcct != "" && $poolcomm != "0" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount ? $query6 = "INSERT INTO expenses (branchid, expid, exptype, eamt, edate, edesc) VALUES (?, ?, ?, ?, ?, ?)" : ""));
        ($allowSettlement == "No" ? "" : ($poolAcct != "" && $poolcomm != "0" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal >= $Amount ? $db->insertIncome($query6, '', $TransactionReference, 'Charges_Reversal', $poolcomm, $date, 'POOL_COMMISSION_REVERSAL') : ""));
        
        //UPDATE TERMINAL REPORT WITH REVERSED STATUS
        $user->updateTerminalRpt($pendingRBal,$reverseBal,$TransactionReference);
        //$queryRpt = "INSERT INTO terminal_report (userid, refid, terminalId, retrievalRef, channel, institutionCode, subMerchantName, trace_id, shortName, ussdReference, amount, charges, currencyCode, pending_balance, transfer_balance, initiatedBy, status, cust_phone, cust_email, date_time, branchid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        //$db->insertTerminalRpt($queryRpt, $bbranchid, $TransactionReference, $TerminalID, $RRN, $channel, $Stan, $merchantName, $MaskedPAN, $CustomerName, $Type, $Amount, $charges, $currency, $pendingBal, $reverseBal, $tidoperator, "Reversed", $customerPhone, $customerEmail, $dateTime, $branchid);
        
        //UPDATE TERMINAL SETTLED BALANCE, PENDING BALANCE AND TOTAL TRANSACTION COUNT
        $user->updateTerminalReg($pendingRBal,$settledRBal,$tRCount,$TerminalID);

        //SEND SMS NOTIFICATION
        (($settlmentType == "manual" || $allowSettlement == "No" || ($poolAcct != "" && $allowSettlement == "Yes" && $settlmentType == "auto" && $pBal < $Amount)) ? "" : (($myPhone == "" || $reverseBal < $sms_charges || $sms_alert != "Yes") ? "" : $gAccelerex->userGeneralAlert($sysabb, $myPhone, $sms, $bbranchid, $TransactionReference, $sms_charges, $currencyCode, $wallet_date_time, $tidoperator, $mywallet_balance)));

        //SEND EMAIL NOTIFICATION
        $gAccelerex->emailNotifier2($emailReceiver, $TransactionReference, $responsemessage, $paymentMethod, $recipient, $settlmentType, $oprName, $bank, $pendingRBal, $Amount, $mywallet_balance, $subject, $TerminalID, $bbranchid);

        $newhttp->OK($resultsInfo, $results);
        
    }

}
?>