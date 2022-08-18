<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userWallet.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";

//ini_set('display_errors', true);
//error_reporting(-1);

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "GET") {

    $wallet = new Wallet($db);

    //FETCH REQUEST WITH NO PARAMTER IN URL
    $resultsData = $wallet->allBankList($registeral);

    $resultsInfo = $db->executeCall($registeral);
        
    if($resultsData === -1) {

        $http->newNotFound('Data not found');

    }elseif($resultsData === -2) {

        $http->newNotAuthorized('You are not authorize to access this service at the moment');

    }elseif($resultsInfo === -1) {

        $http->newAccessForbidden('Access Forbidden. Contact your institution for more details.');

    }else {

        $http->newCustomOK($resultsInfo, $resultsData);

    }

}
?>