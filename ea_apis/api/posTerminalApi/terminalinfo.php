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

    $results = $Pos->terminalInfo($authReceived);

    if($results === -1){

        $newhttp->badRequest("The following fields (terminal_serial) are required.");

    }elseif($results === -2){

        $newhttp->badRequest("A valid JSON of terminal_serials fields is required");

    }elseif($results === -3){
        
        $newhttp->accessForbidden('Unknown Terminal serial');
        
    }else{

        $newhttp->OK($resultsInfo, $results);
        

    }

}
?>