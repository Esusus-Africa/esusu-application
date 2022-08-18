<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/eUserCustomer.php";
require_once "../../models/eHttpResponse.php";
require_once "../../config/eAuth.php";

//ini_set('display_errors', true);
//error_reporting(-1);

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] == "POST") {

    $customerReceived = json_decode(file_get_contents("php://input"));
    
    $customers = new eCustomer($db);

    $results = $customers->insertPcCustomer($customerReceived, $clientId, $companyName, $iwallet_balance);

    $resultsInfo = $db->executeCall($clientId);

    if($results === -1){

        $newhttp->badRequest("A valid JSON of some fields is required");

    }else if($results === -2){

        $newhttp->badRequest("Username already exist");

    }else if($results === -3){
        
        $newhttp->badRequest('Required field missing');
        
    }else if($results === -4){
        
        $newhttp->notAuthorized('Unauthorized Access: Invalid Client ID');
        
    }else if($results === -5){

        $newhttp->duplicateEntry("Username already exist and account activation is required");

    }else if($resultsInfo === -1){

        $newhttp->notAuthorized('Access Forbidden. Contact your institution for more details.');

    }else{

        //GIVE OKAY RESPONSE
        $newhttp->OK($resultsInfo, $results);
    
    }

}
?>