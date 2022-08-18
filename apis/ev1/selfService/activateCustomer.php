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

    $customerActCodeReceived = json_decode(file_get_contents("php://input"));
    
    $customers = new eCustomer($db);

    $results = $customers->activateCustomerAcct($customerActCodeReceived, $clientId, $companyName);

    if($results === -1){

        $newhttp->notFound('Required field missing');

    }else if($results === -2){

        $newhttp->notAuthorized('Invalid Activation Code');

    }else if($results === -3){

        $newhttp->notAuthorized('Unauthorized Access: Invalid Client ID');

    }else if($results === -4){

        $newhttp->badRequest("A valid JSON of some fields is required");

    }else{

        $newhttp->OK($resultsInfo, $results);

    }
    
}