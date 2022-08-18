<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userWallet.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";

//ini_set('display_errors', true);
//error_reporting(-1);

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] == "POST") {

    $fTReceived = json_decode(file_get_contents("php://input"));
    
    $wallet = new Wallet($db);

    $results = $wallet->fundTransfer($fTReceived, $registeral, $companyName, $reg_staffid, $mytpin, $reg_mEmail, $reg_fName, $availableWalletBal);

    $resultsInfo = $db->executeCall($registeral);

    //Wallet Configuration
    $vaGlobalLimit = $user->fetchVALimitConfiguration($reg_staffid,$registeral);
    $itransferLimitPerDay = $vaGlobalLimit['transferLimitPerDay'];
    $itransferLimitPerTrans = $vaGlobalLimit['transferLimitPerTrans'];
    $currency = $resultsInfo['currency'];

    if($results === -1){

        $http->newBadRequest('Required field missing');

    }else if($results === -2){
        
        $http->newNotAuthorized('Unauthorized Access: Invalid Transaction Pin');
        
    }else if($results === -3){
        
        $http->newInsufficientFund('Insufficient fund in wallet');
        
    }else if($results === -4){
        
        $http->newDuplicateEntry("Duplicate transaction is not allowed");
        
    }else if($results === -5){
        
        $customMsg = "Sorry! You cannot transact more than " . $currency.number_format($itransferLimitPerTrans,2,'.',',') . " at once";
        $http->newBadRequest($customMsg);
        
    }else if($results === -6){
        
        $customMsg = "Oops! You have reached your daily limit of " . $currency.number_format($itransferLimitPerDay,2,'.',',');
        $http->newBadRequest($customMsg);
        
    }else if($results === -7){
        
        $http->newBadRequest('Unable to conclude transaction, try again later');
        
    }else if($results === -8){
        
        $http->newNotAuthorized('You are not authorize to access this service at the moment');
        
    }else if($results === -9){
        
        $http->newBadRequest("A valid JSON of some fields is required");
        
    }else if($resultsInfo === -1){

        $http->newAccessForbidden('Access Forbidden. Contact your institution for more details.');

    }else{

        //GIVE OKAY RESPONSE
        $http->newCustomOK($resultsInfo, $results);
    
    }

}
?>