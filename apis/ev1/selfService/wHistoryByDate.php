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

    $wHistoryReceived = json_decode(file_get_contents("php://input"));
    
    $walletHistory = new eWallet($db);

    $results = $walletHistory->fetchWalletHistoryByDate($wHistoryReceived, $clientId, $companyName);

    $resultsInfo = $db->executeCall($clientId);

    if($results === -4){

        $newhttp->badRequest("A valid JSON of some fields is required");

    }else if($results === -1){

        $newhttp->badRequest('Required field missing');

    }else if($results === -2){
        
        $newhttp->notFound('Invalid account');
        
    }else if($results === -3){
        
        $newhttp->notAuthorized('Unauthorized Access: Invalid Client ID');
        
    }else if($results === -5){
        
        $newhttp->notFound('No Records was Found');
        
    }else if($resultsInfo === -1){

        $newhttp->notAuthorized('Access Forbidden. Contact your institution for more details.');

    }else{

        //GIVE OKAY RESPONSE
        $newhttp->OK($resultsInfo, $results);
    
    }

}
?>