<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userWemaInteg.php";
require_once "../../models/wBHttpResponse.php";
require_once "../../config/WBAuth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "POST") {

    $authReceived = json_decode(file_get_contents("php://input"));

    $results = $wemaBank->accountLookup($authReceived);

    //Get Info
    $sessionId = "";

    if($results === -1){

        $newhttp->badRequest($sessionId,"Required field must not be empty","03");

    }elseif($results === -2){

        $newhttp->badRequest($sessionId,"Invalid Json","02");

    }else{

        $newhttp->OK($results);

    }

}
?>