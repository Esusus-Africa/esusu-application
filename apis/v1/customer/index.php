<?php
//error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userCustomer.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";

//ini_set('display_errors', true);
//error_reporting(-1);

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] == "GET") {
    
    $customers = new Customer($db);
    //FETCH ONE CUSTOMER IF ID OR ACCOUNT NUMBER EXIST OR ALL IF ID DOESN'T EXIST
    $resultsData = (isset($_GET['id'])) ? $customers->fetchCustomerById($_GET['id'],$registeral,$companyName) : $customers->fetchAllCustomer($registeral,$companyName,$irole,$reg_staffid,$reg_branch);

    $resultsInfo = $db->executeCall($registeral);


    if($resultsData === 0) {

        $message = "No customer was found";
        $http->notFound($message);

    }elseif($resultsInfo === -1) {

        $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $http->accessForbidden($message);

    }else {
        
        $http->OK($resultsInfo, $resultsData);

    }

}elseif($_SERVER['REQUEST_METHOD'] == "POST") {

    $customerReceived = json_decode(file_get_contents("php://input"));
    
    $customers = new Customer($db);

    $results = $customers->insertPcCustomer($customerReceived, $registeral, $reg_branch, $reg_staffName, $reg_staffid, $companyName, $allow_auth, $mytpin, $cust_reg);

    $resultsInfo = $db->executeCall($registeral);

    if($results === -1){

        $http->badRequest("A valid JSON of some fields is required");

    }else if($results === -2){

        $http->duplicateEntry("Opps!..Username / Phone Already Exist");

    }else if($results === -3){
        
        $http->badRequest('Required field missing');
        
    }else if($results === -4){
        
        $http->notAuthorized('Invalid Transaction Pin.');
        
    }else if($cust_reg === "Disallow"){
        
        $http->notAuthorized('Oops! Access Denied.');
        
    }else if($resultsInfo === -1){

        $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $http->accessForbidden($message);

    }else{

        //GIVE OKAY RESPONSE
        $http->OKCust($resultsInfo, $results);
    
    }
}
?>