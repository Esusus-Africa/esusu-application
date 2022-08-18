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

    //FETCH TRANSACTION BY ID IF ID EXIST OR ALL IF ID DOESN'T EXIST
    $resultsData = ((isset($_GET['id'])) && (isset($_GET['freq']))) ? $wallet->bankNameEnquiry($_GET['id'],$_GET['freq'],$registeral) : "Invalid Request";

    $resultsInfo = $db->executeCall($registeral);
        
    if($resultsData === -1) {

        $http->newNotAuthorized('You are not authorize to access this service at the moment');

    }elseif($resultsInfo === -1) {

        $http->newAccessForbidden('Access Forbidden. Contact your institution for more details.');

    }else {

        $http->newCustomOK($resultsInfo, $resultsData);

    }

}
?>