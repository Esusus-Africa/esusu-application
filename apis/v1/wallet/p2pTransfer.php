<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userWallet.php";
require_once "../../models/WalletHttpResponse.php";
require_once "../../config/auth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "POST") {

    $authReceived = json_decode(file_get_contents("php://input"));

    $results = $wallet->p2pTransfer($authReceived, $registeral, $reg_mEmail, $reg_staffName, $virtualAcctNo, $iacctType, $availableWalletBal, $reg_staffid, $mytpin, $active_status);

    $resultsInfo = $db->executeCall($registeral);

    $vaGlobalLimit = $user->fetchVALimitConfiguration($reg_staffid,$registeral);
    $itransferLimitPerDay = $vaGlobalLimit['transferLimitPerDay'];
    $itransferLimitPerTrans = $vaGlobalLimit['transferLimitPerTrans'];

    $sysCredentials = $db->fetchSystemSet();
    $icurrency = $sysCredentials['currency'];
    
    if($results === -1){

        $wallethttp->badRequest("Required field must not be empty");

    }elseif($results === -2){

        $wallethttp->badRequest("Account not found");

    }elseif($results === -3){
        
        $wallethttp->badRequest('Invalid pin format');
        
    }elseif($results === -4){
        
        $wallethttp->badRequest('Invalid Amount');
        
    }elseif($results === -5){
        
        $wallethttp->notAuthorized('You are not Authorized to use this facilities.');
        
    }elseif($results === -6){
        
        $wallethttp->insufficientFund("Insufficient fund");
        
    }elseif($results === -7){
        
        $customMsg = "You cannot transact more than " . $icurrency.number_format($itransferLimitPerTrans,2,'.',',') . " at once";
        $newhttp->badRequest($customMsg);
        
    }elseif($results === -8){
        
        $customMsg = "You have reached your daily limit of " . $icurrency.number_format($itransferLimitPerDay,2,'.',',');
        $newhttp->badRequest($customMsg);
        
    }elseif($results === -9){
        
        $wallethttp->accessForbidden("Pin validation failed");
        
    }elseif($results === -10){
        
        $wallethttp->badRequest("Request failed");
        
    }elseif($results === -11){
        
        $wallethttp->accessForbidden("Account has been Suspended");
        
    }elseif($resultsInfo === -1){
        
        $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $wallethttp->accessForbidden($message);

    }else{

        $wallethttp->specialOK($resultsInfo, $results);
        
    }

}
?>