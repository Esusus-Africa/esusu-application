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

//ini_set('display_errors', true);
//error_reporting(-1);

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "GET") {

    $wallet = new eWallet($db);

    //FETCH TRANSACTION BY ID IF ID EXIST OR ALL IF ID DOESN'T EXIST
    $resultsData = ((isset($_GET['id']))) ? $wallet->allDatabundleProduct($_GET['id'],$clientId) : "Invalid Request";

    $resultsInfo = $db->executeCall($clientId);
        
    if($resultsData === -1) {

        $newhttp->notFound('Data not found');

    }elseif($resultsData === -2) {

        $newhttp->notAuthorized('You are not authorize to access this service at the moment');

    }elseif($resultsInfo === -1) {

        $newhttp->notAuthorized('Access Forbidden. Contact your institution for more details.');

    }else {

        $newhttp->OK($resultsInfo, $resultsData);

    }

}
?>