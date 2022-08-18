<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userCustomer.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";

//ini_set('display_errors', true);
//error_reporting(-1);

if($_SERVER['REQUEST_METHOD'] == "POST") {

    $customerReceived = json_decode(file_get_contents("php://input"));
    
    $customers = new Customer($db);

    $results = $customers->verifyCustomerAcctNo($customerReceived, $registeral);

    $resultsInfo = $db->executeCall($registeral);

    if($results === -1){

        $http->badRequest('Account Number must not be empty');

    }else if($results === -2){

        $http->badRequest("A valid JSON of some fields is required");

    }else if($resultsInfo === -1){

        $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $http->accessForbidden($message);

    }else{

        //GIVE OKAY RESPONSE
        $http->OK($resultsInfo, $results);
    
    }
}
?>