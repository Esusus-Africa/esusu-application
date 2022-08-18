<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/activationAuth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "POST") {

    $authReceived = json_decode(file_get_contents("php://input"));

    $results = $user->appActivation($authReceived);

    //$resultsInfo = $db->executeCall($registeral);

    if($results === -1){

        $http->badRequest("Required field must not be empty");

    }elseif($results === -2){

        $http->badRequest("A valid JSON of some fields is required");

    }elseif($results === -3){
        
        $http->notAuthorized('Oops! You are not Authorized to use this facilities. Kindly contact us for more info.');
        
    }else{

        $http->OK($resultsInfo, $results);
        

    }
}