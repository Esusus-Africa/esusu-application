<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "GET") {

    //FETCH ONE CUSTOMER IF ID OR ACCOUNT NUMBER EXIST OR ALL IF ID DOESN'T EXIST
    $resultsData = (isset($_GET['id'])) ? $user->fetchVAByAcctNo($_GET['id']) : "Parameter is Required";

    $resultsInfo = $db->executeCall($registeral);

    if($resultsData === 0) {

        $message = "No account was found";
        $http->notFound($message);

    }elseif($resultsInfo === -1) {

        $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $http->accessForbidden($message);

    }else {

        $http->OK($resultsInfo, $resultsData);

    }

}  else if($_SERVER['REQUEST_METHOD'] === "POST") {

    $vaReceived = json_decode(file_get_contents("php://input"));

    $results = $user->createVA($vaReceived, $registeral);

    $resultsInfo = $db->executeCall($registeral);

    if($results === -1){

        $http->notFound("Account ID Not Found");

    }else if($results === -2){

        $http->duplicateEntry("Virtual Account Already Created");

    }else if($results === -3){

        $http->badRequest("A valid JSON of some fields is required");

    }else if($results === -4){

        $http->badRequest("Required field must not be empty");

    }else if($resultsInfo === -1){

        $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $http->accessForbidden($message);

    }else {

        $http->OK($resultsInfo, $results);

    }

}
?>