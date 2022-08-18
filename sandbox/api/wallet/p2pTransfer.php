<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userWallet.php";
require_once "../../models/HttpResponse.php";
require_once "../../models/WalletHttpResponse.php";
require_once "../../config/auth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "POST") {

    $authReceived = json_decode(file_get_contents("php://input"));

    $results = $wallet->p2pTransfer($authReceived, $registeral, $reg_mEmail, $reg_staffName, $virtualAcctNo, $iacctType, $availableWalletBal, $reg_staffid, $mytpin);

    $resultsInfo = $db->executeCall($registeral);
    
    
    if($results === -1){

        $wallethttp->badRequest("Required field must not be empty");

    }elseif($results === -2){

        $wallethttp->badRequest("Account not found");

    }elseif($results === -3){
        
        $wallethttp->badRequest('Invalid pin format');
        
    }elseif($results === -4){
        
        $wallethttp->badRequest('Invalid Amount');
        
    }elseif($results === -5){
        
        $wallethttp->insufficientFund("Insufficient fund");
        
    }elseif($results === -6){
        
        $wallethttp->accessForbidden("Pin validation failed");
        
    }elseif($resultsInfo === -1){
        
        $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $wallethttp->accessForbidden($message);

    }else{

        $wallethttp->specialOK($resultsInfo, $results);
        
    }

}
?>