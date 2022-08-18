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

    $fTReceived = json_decode(file_get_contents("php://input"));
    
    $wallet = new eWallet($db);

    $results = $wallet->fundTransfer($fTReceived, $clientId, $companyName, $companyEmail);

    $resultsInfo = $db->executeCall($clientId);

    $senderAccountNumber = $fTReceived->senderAccountNumber;

    //lookup account validity
    $searchVA = $user->fetchVAByAcctNo($senderAccountNumber);
    $accountID = ($searchVA['userid'] == "") ? $senderAccountNumber : $searchVA['userid'];

    //Search if borrowers
    $searchCustomer = $user->fetchCustomerByAcctId($accountID,$clientId);
    //Search if staff
    $searchUser = $user->fetchTerminalOprt($accountID,$clientId);
    //filter correct sender info
    $myAcctID = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['account'] : $searchUser['id'];

    $vaGlobalLimit = $user->fetchVALimitConfiguration($myAcctID,$clientId);
    $itransferLimitPerDay = $vaGlobalLimit['transferLimitPerDay'];
    $itransferLimitPerTrans = $vaGlobalLimit['transferLimitPerTrans'];
    $currency = $resultsInfo['currency'];

    if($results === -1){

        $newhttp->badRequest('Required field missing');

    }else if($results === -2){

        $newhttp->notFound("Invalid sender account number");

    }else if($results === -3){
        
        $newhttp->notAuthorized('Unauthorized Access: Invalid Transaction Pin');
        
    }else if($results === -4){
        
        $newhttp->notAuthorized('Unauthorized Access: Invalid Client ID');
        
    }else if($results === -5){
        
        $newhttp->insufficientFund('Insufficient fund in wallet');
        
    }else if($results === -6){
        
        $newhttp->badRequest('Unable to conclude transaction, try again later');
        
    }else if($results === -7){
        
        $newhttp->notAuthorized('You are not authorize to access this service at the moment');
        
    }else if($results === -8){
        
        $newhttp->badRequest("A valid JSON of some fields is required");
        
    }else if($results === -9){
        
        $newhttp->duplicateEntry("Duplicate transaction is not allowed");
        
    }else if($results === -10){
        
        $customMsg = "Sorry! You cannot transact more than " . $currency.number_format($itransferLimitPerTrans,2,'.',',') . " at once";
        $newhttp->badRequest($customMsg);
        
    }else if($results === -11){
        
        $customMsg = "Oops! You have reached your daily limit of " . $currency.number_format($itransferLimitPerDay,2,'.',',');
        $newhttp->badRequest($customMsg);
        
    }else if($resultsInfo === -1){

        $newhttp->notAuthorized('Access Forbidden. Contact your institution for more details.');

    }else{

        //GIVE OKAY RESPONSE
        $newhttp->OK($resultsInfo, $results);
    
    }

}
?>