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

    $results = $Pos->billPayment($authReceived);

    if($results === -1){

        $newhttp->badRequest("Required field must not be empty");

    }elseif($results === -2){

        $newhttp->badRequest("A valid JSON of some fields is required");

    }elseif($results === -3){
        
        $newhttp->accessForbidden('Account validation failed');
        
    }elseif($results === -4){
        
        $newhttp->insufficientFund('Insufficient Balance');
        
    }
    elseif($results === -5){
        
        $newhttp->badRequest("Request failed, please try again...");
        
    }else{

        $newhttp->OK($resultsInfo, $results);
        
    }

}
?>