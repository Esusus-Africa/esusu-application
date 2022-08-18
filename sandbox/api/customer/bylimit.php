<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";


//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "GET") {

    if(isset($_GET['id']) && !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {

        //ERROR ONLY INTEGER IS ALLOWED
        $http->badRequest("Only a valid Integer is Allowed");
        die();

    }
    //FETCH CUSTOMERS BY LIMIT IF ID EXIST OR NONE IF ID DOESN'T EXIST
    $resultsData = (isset($_GET['id'])) ? $user->fetchCustomerByLimit($registeral,$_GET['id']) : "Parameter is Required";

    $resultsInfo = $db->executeCall($registeral);


    if($resultsData === 0) {

        $message = "No Customer was found";
        $http->notFound($message);

    }elseif($resultsInfo === -1) {

        $message = "Opps! Your Account ";
        $message .= isset($_GET['id']) ? "with id: ".$registeral : "";
        $message .= " still under review";
        $http->accessForbidden($message);

    }else {

        $http->OK($resultsInfo, $resultsData);

    }

}
?>