<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "POST") {

    $authReceived = json_decode(file_get_contents("php://input"));

    $results = $user->ePinValidation($authReceived, $mytpin);

    if($results === -1){

        $http->customOK(400, "01", "Required field must not be empty");

    }elseif($results === -2){

        $http->customOK(401, "03", "Invalid Transaction Pin");

    }elseif($results === -3){

        $http->customOK(400, "02", "A valid JSON of some fields is required");

    }else{

        $http->OK($resultsInfo, $results);

    }
    
}