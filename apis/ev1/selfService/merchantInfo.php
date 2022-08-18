<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/eUserWallet.php";
require_once "../../models/eHttpResponse.php";
require_once "../../config/eAuth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "GET") {

    $wallet = new eWallet($db);

    $results = $wallet->merchantInfo($clientId, $companyName, $companyPhone);

    if($results === -1){

        $newhttp->customOK(401, "", "No Data Found");

    }else{

        $newhttp->OK($resultsInfo, $results);

    }
    
}