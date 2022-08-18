<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "POST") {

    $authReceived = json_decode(file_get_contents("php://input"));

    $results = $user->createAuth($authReceived,$registeral,$companyName,$reg_fName,$reg_lName,$reg_mName,$tillBalance,$tillCommission,$tillCommissionType,$myimage);

    $resultsInfo = $db->executeCall($registeral);
    
    $myUName = $authReceived->username;
    
    $myUPass = $authReceived->password;

    if($resultsInfo === -1) {

        $message = "Opps! Sorry, Client needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $http->accessForbidden($message);

    }elseif($results === -1){

        $http->badRequest("Required field must not be empty");

    }elseif($results === -2){

        $http->badRequest("A valid JSON of some fields is required");

    }elseif($results === -3){
        
        $http->notAuthorized('Oops! You are not Authorized to use this facilities. Kindly contact us for more info.');
        
    }elseif($myUName != $userid || $myUPass != $passkey) {

        $message = "Invalid login credentials";
        $http->notFound($message);

    }else{

        $http->OK($resultsInfo, $results);
        

    }
}