<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userSterlingInteg.php";
require_once "../../models/sBHttpResponse.php";
require_once "../../config/SBAuth.php";

// ini_set('display_errors', true);
// error_reporting(-1);

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "POST") {

    $authReceived = json_decode(file_get_contents("php://input"));

    $results = $sterlingBank->transNotif($authReceived, $inputKey, $iv);

    if($results === -1){

        $newhttp->duplicateRecord("Duplicate Transaction");

    }elseif($results === -2){

        $newhttp->badRequest("Required field must not be empty","03");

    }elseif($results === -3){

        $newhttp->badRequest("Invalid Account","07");
        
    }elseif($results === -4){
        
        $newhttp->badRequest("Invalid Json","02");
        
    }else{

        $newhttp->OK($results);
        
    }

}
?>