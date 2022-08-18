<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";


//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "GET") {

    //FETCH BRANCH IF ID EXIST OR ALL IF ID DOESN'T EXIST
    $resultsData = (isset($_GET['id'])) ? $user->fetchBranchById($_GET['id'],$registeral) : $user->fetchAllBranch($registeral);

    $resultsInfo = $db->executeCall($registeral);


    if($resultsData === 0) {

        $message = "No Branch ";
        $message .= isset($_GET['id']) ? "with the id ".$_GET['id'] : "";
        $message .= " was found";
        $http->notFound($message);

    }elseif($resultsInfo === -1) {

        $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $http->accessForbidden($message);

    }else {

        $http->OK($resultsInfo, $resultsData);

    }

} else if($_SERVER['REQUEST_METHOD'] === "POST") {

    $branchReceived = json_decode(file_get_contents("php://input"));

    $results = $user->insertMyBranch($branchReceived, $registeral);

    $resultsInfo = $db->executeCall($registeral);

    if($results === -1){

        $http->badRequest("A valid JSON of some fields is required");

    }else if($resultsInfo === -1){

        $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $http->accessForbidden($message);

    }else{

        $http->OK($resultsInfo, $results);

    }

} else if($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $branchReceived = json_decode(file_get_contents("php://input"));

    if(!isset($branchReceived->branchid)) {

        //ID NOT PROVIDED BAD REQUEST
        $http->badRequest("Please an Id is required to make a PUT request");
        exit();

    }

    $query = "SELECT * FROM branches WHERE branchid = ? AND created_by = '$registeral'";
    $results = $db->fetchById($query, $branchReceived->branchid);

    if($results === 0) {

        //ACCOUNT NOT FOUND
        $http->notFound("Branch Not Found");

    }else if($results['created_by'] !== $registeral) {

        //NOT AUTHORIZED
        $http->notAuthorized("You are not authorized to update this customer");

    }else {

        //BRANCH CAN UPDATE THE FOLLOWING PARAMETERS
        $parameters = [
            'branchid' => $branchReceived->branchid,
            'bname' => isset($branchReceived->bname) ? $branchReceived->bname : $results['bname'],
            'bcountry' => isset($branchReceived->bcountry) ? $branchReceived->bcountry : $results['bcountry'],
            'currency' => isset($branchReceived->currency) ? $branchReceived->currency : $results['currency'],
            'branch_addrs' => isset($branchReceived->branch_addrs) ? $branchReceived->branch_addrs : $results['branch_addrs'],
            'branch_city' => isset($branchReceived->branch_city) ? $branchReceived->branch_city : $results['branch_city'],
            'branch_province' => isset($branchReceived->branch_province) ? $branchReceived->branch_province : $results['branch_province'],
            'branch_mobile' => isset($branchReceived->branch_mobile) ? $branchReceived->branch_mobile : $results['branch_mobile'],
            'bstatus' => isset($branchReceived->bstatus) ? $branchReceived->bstatus : $results['bstatus'],
        ];

        $resultsData = $user->updateBranch($parameters);

        $resultsInfo = $db->executeCall($registeral);

        if($resultsInfo === -1) {

            $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
            $message .= " to have access to our REST API";
            $http->accessForbidden($message);
    
        }else {
    
            $http->OK($resultsInfo, $resultsData);
    
        }

    }

} else if($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $idRequest = json_decode(file_get_contents("php://input"));

    if(!isset($idRequest->branchid)) {

        //ID NOT PROVIDED BAD REQUEST
        $http->badRequest("No Id was Provided");
        exit();

    }

    $query = "SELECT * FROM branches WHERE branchid = ? AND created_by = '$registeral'";
    $results = $db->fetchById($query, $idRequest->branchid);

    if($results === 0) {

        //BRANCH NOT FOUND
        $http->notFound("Branch with Id $idRequest->branchid was not found");
        exit();

    }
    if($results['created_by'] !== $registeral) {

        //NOT AUTHORIZED
        $http->notAuthorized("You are not authorized to delete this branch");

    }else{

        //USER CAN NOW DELETE
        $resultsData = $user->deleteBranch($idRequest->branchid);

        $resultsInfo = $db->executeCall($registeral);

        if($resultsInfo === -1) {

            $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
            $message .= " to have access to our REST API";
            $http->accessForbidden($message);
    
        }else {
    
            $http->OK($resultsInfo, $resultsData);
    
        }

    }

}
?>