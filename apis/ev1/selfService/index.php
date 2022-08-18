<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/eHttpResponse.php";
require_once "../../config/eAuth.php";

ini_set('display_errors', true);
error_reporting(-1);

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "POST") {

    $authReceived = json_decode(file_get_contents("php://input"));

    $results = $user->customerAuth($authReceived,$companyName,$clientId,$fileBaseUrl);

    $resultsInfo = $db->executeCall($clientId);
    
    $myUName = $authReceived->username;
    
    $myUPass = $authReceived->password;

    $myClientId = $authReceived->clientID;
    
    if($resultsInfo === -1) {

        $newhttp->notAuthorized("Access Forbidden. Contact your institution for more details.");

    }elseif($results === -1){

        $newhttp->badRequest("Required field must not be empty");

    }elseif($results === -2){

        $newhttp->badRequest("A valid JSON of some fields is required");

    }elseif($results === -3){
        
        $newhttp->notAuthorized('Invalid login credentials');
        
    }elseif($results === -4){
        
        $newhttp->notAuthorized('Access Forbidden: Account not yet activated');
        
    }elseif($myClientId != $clientId) {

        $newhttp->notFound('Invalid login credentials');

    }else{

        $newhttp->OK($resultsInfo, $results);

    }
}