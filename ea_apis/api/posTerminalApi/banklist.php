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

    $results = $Pos->bankList($authReceived);

    if($results === -1){

        $newhttp->badRequest("Request field must not be empty");

    }elseif($results === -2){

        $newhttp->badRequest("A valid JSON of request field is required");

    }else{

        $newhttp->specialOK($resultsInfo, $results);
        
    }

}
?>