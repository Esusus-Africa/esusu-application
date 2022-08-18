<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userSavings.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "GET") {

    if(isset($_GET['id']) && !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {

        //ERROR ONLY INTEGER IS ALLOWED
        $http->badRequest("Only a valid Integer is Allowed");
        die();

    }

    $savings = new Savings($db);

    //FETCH ALL TRANSACTION BY LIMIT IF ID EXIST OR NONE IF ID DOESN'T EXIST
    $resultsData = (isset($_GET['id'])) ? $savings->fetchTransByLimit($registeral,$_GET['id']) : "Paramater is Required";

    $resultsInfo = $db->executeCall($registeral);


    if($resultsData === 0) {

        $message = "No Transaction was found";
        $http->notFound($message);

    }elseif($resultsInfo === -1) {

        $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $http->accessForbidden($message);

    }else{

        $http->OK($resultsInfo, $resultsData);

    }

}
?>