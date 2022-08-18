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

    $results = $user->accountInfo($registeral, $companyName, $reg_staffid);

    if($results === -1){

        $http->customOK(401, "01", "No Data Found");

    }elseif($results === -2){

        $http->customOK(401, "02", "Unable to Fetch Account Information");

    }else{

        $http->OK($resultsInfo, $results);

    }
    
}