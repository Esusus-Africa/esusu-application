<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userWallet.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "POST") {

    $walletReceived = json_decode(file_get_contents("php://input"));
    
    $wallet = new Wallet($db);

    $results = $wallet->fetchWalletHistoryByDate($walletReceived, $registeral, $reg_staffid, $reg_branch, $companyName, $irole);

    $resultsInfo = $db->executeCall($registeral);
    
    if($results === -1){

        $http->newBadRequest('Required field missing');

    }elseif($results === -2){

        $http->newBadRequest("A valid JSON of some fields is required");

    }elseif($results === -3){
        
        $http->newNotFound('No Data Found');
        
    }elseif($resultsInfo === -1){

        $http->newAccessForbidden('Access Forbidden. Contact your institution for more details.');
    }else{

        $http->newCustomOK($resultsInfo, $results);
        
    }

}
?>