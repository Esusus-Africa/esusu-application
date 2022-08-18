<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userGlobalAccelerex.php";
require_once "../../models/gAHttpResponse.php";
require_once "../../config/GAAuth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "POST") {

    $authReceived = json_decode(file_get_contents("php://input"));

    $results = $gAccelerex->getTerminalInfo($authReceived);

    if($results === -1){

        $newhttp->badRequest("TerminalID is empty");

    }elseif($results === -2){

        $newhttp->badRequest("SerialNumber is empty");

    }elseif($results === -3){
        
        $newhttp->badRequest("Terminal not found");
        
    }elseif($results === -4){
        
        $newhttp->badRequest("Field should not be empty");
        
    }else{

        $newhttp->OK($resultsInfo, $results);
        
    }

}
?>