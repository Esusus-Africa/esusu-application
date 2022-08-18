<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/eUserSavings.php";
require_once "../../models/eHttpResponse.php";
require_once "../../config/eAuth.php";

//ini_set('display_errors', true);
//error_reporting(-1);

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "GET") {

    $savings = new eSavings($db);

    //FETCH TRANSACTION BY ID IF ID EXIST OR ALL IF ID DOESN'T EXIST
    $resultsData = ((isset($_GET['id'])) && ($_GET['id'] != "")) ? $savings->checkMySavingsMaturityStatus($_GET['id'],$clientId,$companyName) : "Invalid Request";

    $resultsInfo = $db->executeCall($clientId);
        
    if($resultsData === 0) {

        $newhttp->notFound('No Transaction was found');

    }elseif($resultsData === -1) {

        $newhttp->notFound('No records found');

    }elseif($resultsInfo === -1) {

        $newhttp->notAuthorized('Access Forbidden. Contact your institution for more details.');

    }else {

        $newhttp->OK($resultsInfo, $resultsData);

    }

}
?>