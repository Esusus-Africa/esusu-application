<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/eUserCustomer.php";
require_once "../../models/eHttpResponse.php";
require_once "../../config/eAuth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "GET") {

    $customers = new eCustomer($db);

    $results = (isset($_GET['id'])) ? $customers->accountInfo($_GET['id'], $clientId, $companyName) : "No Result Found";

    if($results === -1){

        $newhttp->customOK(401, "01", "No Data Found");

    }else{

        $newhttp->OK($resultsInfo, $results);

    }
    
}