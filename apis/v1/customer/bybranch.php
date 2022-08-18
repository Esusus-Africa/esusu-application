<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userCustomer.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "GET") {
    
    $customers = new Customer($db);
    
    //FETCH BRANCH CUSTOMER IF ID EXIST OR NONE IF ID DOESN'T EXIST
    $resultsData = $customers->fetchBranchCustomer($reg_branch,$registeral);

    $resultsInfo = $db->executeCall($registeral);

    if($resultsData === 0) {

        $message = "No customer was found";
        $http->notFound($message);

    }elseif($resultsInfo === -1) {

        $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $http->accessForbidden($message);

    }else {

        $http->OK($resultsInfo, $resultsData);

    }

}
?>