<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userCustomer.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "POST") {

    $cReceived = json_decode(file_get_contents("php://input"));
    
    $customer = new Customer($db);

    $results = $customer->createGroup($cReceived, $registeral, $mytpin);

    $resultsInfo = $db->executeCall($registeral);
    
    if($results === -1){

        $http->newBadRequest('Required field missing');

    }elseif($results === -2){

        $http->newNotAuthorized('Unauthorized Access: Invalid Transaction Pin');

    }elseif($results === -3){
        
        $http->newBadRequest("A valid JSON of some fields is required");
        
    }elseif($resultsInfo === -1){

        $http->newAccessForbidden('Access Forbidden. Contact your institution for more details.');
        
    }else{

        $http->newCustomOK($resultsInfo, $results);
        
    }

}
?>