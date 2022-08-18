<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userProvidusInteg.php";
require_once "../../models/pBHttpResponse.php";
require_once "../../config/PBAuth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "POST") {

    $authReceived = json_decode(file_get_contents("php://input"));

    $results = $providusVPS->settlementNotif($authReceived);

    //Get Info
    $sessionId = $authReceived->sessionId;
    $accountNumber = $authReceived->accountNumber;
    $tranRemarks = $authReceived->tranRemarks;
    $transactionAmount = $authReceived->transactionAmount;
    $settledAmount = $authReceived->settledAmount;
    $feeAmount = $authReceived->feeAmount;
    $vatAmount = $authReceived->vatAmount;
    $currency = $authReceived->currency;
    $settlementId = $authReceived->settlementId;
    $sourceAccountNumber = $authReceived->sourceAccountNumber;
    $sourceAccountName = $authReceived->sourceAccountName;
    $sourceBankName = $authReceived->sourceBankName;
    $tranDateTime = $authReceived->tranDateTime;

    $recipient = "From:- ".$sourceAccountName;
	$recipient .= ", Account Number: ".$sourceAccountNumber;
	$recipient .= ", Bank Name: ".$sourceBankName;

    //CONFIRM RESERVED ACCOUNT NUMBER
    $verifyVA = $user->fetchVAByAcctNo($accountNumber);
    $verifyPA = $user->fetchPoolAcctByAcctNo($accountNumber);
    $verifyTA = $user->fetchTillVAByAcctNo($accountNumber);
    $myId = ($verifyVA != "0" && $verifyPA == "0" && $verifyTA == "0" ? $verifyVA['userid'] : ($verifyVA == "0" && $verifyPA != "0" && $verifyTA == "0" ? $verifyPA['userid'] : $verifyTA['userid']));

    //CONFIRM RESERVED ACCOUNT OWNER
    $checkInst = $user->fetchInstitutionById($myId);
    $checkUser = $user->fetchUser($myId);
    $checkCustomer = $user->fetchCustomerByAcctIdOnly($myId);
    $originator = ($checkUser != "0" && $checkCustomer == "0" ? $checkUser['created_by'] : ($checkUser == "0" && $checkCustomer != "0" ? $checkCustomer['branchid'] : $myId));
	$initiator = ($checkUser != "0" && $checkCustomer == "0" ? $checkUser['id'] : ($checkUser == "0" && $checkCustomer != "0" ? $checkCustomer['account'] : ''));
    $branch = ($checkUser != "0" && $checkCustomer == "0" ? $checkUser['branchid'] : ($checkUser == "0" && $checkCustomer != "0" ? $checkCustomer['sbranchid'] : ""));

    $checkIndepotent1 = $user->fetchWalletHistoryByRefId($sessionId);
    $checkIndepotent2 = $user->fetchPoolHistoryByRefId($sessionId);
    $checkIndepotent3 = $user->fetchTillWHistoryByRefId($sessionId);

    if($results === -1){

        $newhttp->duplicateRecord($sessionId,"Duplicate Transaction");

    }elseif($results === -2){

        $newhttp->systemError($sessionId,"System Failure 1, Retry");

    }elseif($results === -3){

        $newhttp->systemError($sessionId,"System Failure 2, Retry");
        
    }elseif($results === -4){
        
        $newhttp->systemError($sessionId,"System Failure 3, Retry");
        
    }else{

        //LOG WALLET HISTORY
        ($verifyVA != "0" && $verifyPA == "0" && $verifyTA == "0" && $checkIndepotent1 == "0") ? $query1 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance, receiver_bal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
        ($verifyVA != "0" && $verifyPA == "0" && $verifyTA == "0" && $checkIndepotent1 == "0") ? $db->insertSpecialWalletHistory($query1, $originator, $sessionId, $recipient, $transactionAmount, '', 'Credit', $currency, 'PROVIDUS', '', 'pending', $tranDateTime, $initiator, '', '') : "";

        //LOG POOL HISTORY
        ($verifyVA == "0" && $verifyPA != "0" && $verifyTA == "0" && $checkIndepotent2 == "0") ? $query2 = "INSERT INTO pool_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance, receiver_bal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
        ($verifyVA == "0" && $verifyPA != "0" && $verifyTA == "0" && $checkIndepotent2 == "0") ? $db->insertSpecialWalletHistory($query2, $originator, $sessionId, $recipient, $transactionAmount, '', 'Credit', $currency, 'PROVIDUS', '', 'pendingpool', $tranDateTime, $initiator, '', '') : "";

        //LOG TILL FUNDING HISTORY
        ($verifyVA == "0" && $verifyPA == "0" && $verifyTA != "0" && $checkIndepotent3 == "0") ? $query3 = "INSERT INTO fund_allocation_history (txid, companyid, manager_id, branch, teller, cashier, amount_fund, ttype, paymenttype, currency, balance, note_comment, status, date_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
        ($verifyVA == "0" && $verifyPA == "0" && $verifyTA != "0" && $checkIndepotent3 == "0") ? $db->insertFundAllocationHistory($query3, $sessionId, $originator, $initiator, $branch, $recipient, $initiator, $transactionAmount, 'Credit', 'PROVIDUS', $currency, '', $recipient, 'pendingtill', $tranDateTime) : "";

        $newhttp->OK($resultsInfo, $results);
        
    }

}
?>