<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userPos.php";
require_once "../../models/PosHttpResponse.php";
require_once "../../config/PosAuth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "POST") {

    $authReceived = json_decode(file_get_contents("php://input"));

    $results = $Pos->fundTransfer($authReceived);

    $terminalid = $authReceived->terminalid;

    $checkTerm = $user->checkTerminal($terminalid);
    $tidoperator = $checkTerm['tidoperator'];
    $institutionid = $checkTerm['merchant_id'];

    $vaGlobalLimit = $user->fetchVALimitConfiguration($tidoperator,$institutionid);
    $itransferLimitPerDay = $vaGlobalLimit['transferLimitPerDay'];
    $itransferLimitPerTrans = $vaGlobalLimit['transferLimitPerTrans'];

    $sysCredentials = $db->fetchSystemSet();
    $icurrency = $sysCredentials['currency'];

    if($results === -1){

        $newhttp->badRequest("Required field must not be empty");

    }elseif($results === -2){

        $newhttp->badRequest("A valid JSON of some fields is required");

    }elseif($results === -3){
        
        $newhttp->accessForbidden('Account validation failed');
        
    }elseif($results === -4){
        
        $newhttp->insufficientFund('Insufficient Balance');
        
    }elseif($results === -5){
        
        $customMsg = "Sorry! You cannot transact more than " . $icurrency.number_format($itransferLimitPerTrans,2,'.',',') . " at once";
        $newhttp->badRequest($customMsg);
        
    }elseif($results === -6){
        
        $customMsg = "Oops! You have reached your daily limit of " . $icurrency.number_format($itransferLimitPerDay,2,'.',',');
        $newhttp->badRequest($customMsg);
        
    }elseif($results === -7){
        
        $newhttp->badRequest("Request failed, please try again...");
        
    }else{

        $newhttp->OK($resultsInfo, $results);
        
    }

}
?>