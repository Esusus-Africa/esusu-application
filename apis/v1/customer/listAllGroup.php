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

//ini_set('display_errors', true);
//error_reporting(-1);

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "GET") {

    $customer = new Customer($db);

    //FETCH ALL GROUP
    $resultsData = $customer->fetchAllGroup($registeral, $companyName);

    $resultsInfo = $db->executeCall($registeral);
        
    if($resultsData === -1) {

        $http->newNotFound('No Data Found');

    }elseif($resultsInfo === -1){

        $http->newAccessForbidden('Access Forbidden. Contact your institution for more details.');

    }else{

        $http->newCustomOK($resultsInfo, $resultsData);

    }

}
?>