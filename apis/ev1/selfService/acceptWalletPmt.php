<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/eUserWallet.php";
require_once "../../models/eHttpResponse.php";
require_once "../../config/eAuth.php";

//ini_set('display_errors', true);
//error_reporting(-1);

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] == "POST") {

    $walletReceived = json_decode(file_get_contents("php://input"));
    
    $wallet = new eWallet($db);

    $results = $wallet->acceptWalletPayment($walletReceived, $clientId, $companyName, $companyEmail);

    $resultsInfo = $db->executeCall($clientId);

    if($results === -1){

        $newhttp->badRequest('Required field missing');

    }else if($results === -2){

        $newhttp->notFound('Account not found');

    }else if($results === -3){
        
        $newhttp->duplicateEntry('Duplicate entry is not allowed');
        
    }else if($results === -4){
        
        $newhttp->insufficientFund('Insufficient fund in wallet');
        
    }else if($results === -5){
        
        $newhttp->notAuthorized('Unauthorized Access: Invalid Transaction Pin');
        
    }else if($results === -6){
        
        $newhttp->notAuthorized('Unauthorized Access: Invalid Merchant Wallet ID');
        
    }else if($results === -7){
        
        $newhttp->notAuthorized('Unauthorized Access: Invalid Client ID');
        
    }else if($results === -8){
        
        $newhttp->badRequest("A valid JSON of some fields is required");
        
    }else if($resultsInfo === -1){

        $newhttp->notAuthorized('Access Forbidden. Contact your institution for more details.');

    }else{

        //GIVE OKAY RESPONSE
        $newhttp->OK($resultsInfo, $results);
    
    }

}
?>