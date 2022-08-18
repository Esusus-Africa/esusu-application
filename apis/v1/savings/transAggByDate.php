<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userSavings.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";

//ini_set('display_errors', true);
//error_reporting(-1);

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "POST") {

    $dateReceived = json_decode(file_get_contents("php://input"));
    
    $savings = new Savings($db);

    $resultsData = $savings->fetchAggregateSavingsWithDate($dateReceived, $registeral, $irole, $reg_staffid, $reg_branch);

    $resultsInfo = $db->executeCall($registeral);
    
    if($resultsData === -1){
        
        $message = "Required field missing";
        $http->badRequest($message);
    
    }elseif($resultsData === -2){

        $message = "A valid JSON of some fields is required";
        $http->badRequest($message);

    }elseif($resultsInfo === -1){

        $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $http->accessForbidden($message);

    }else{

        //GIVE OKAY RESPONSE
        $http->OK($resultsInfo, $resultsData);

    }

}
?>