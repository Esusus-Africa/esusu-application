<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/userSavings.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";

//ini_set('display_errors', true);
//error_reporting(-1);

//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "GET") {

    $savings = new Savings($db);

    //FETCH TRANSACTION BY ID IF ID EXIST OR ALL IF ID DOESN'T EXIST
    $resultsData = ((isset($_GET['id'])) && ($_GET['id'] == "Daily") ? $savings->fetchDailyAggregateLedgerSavings($registeral,$irole,$reg_staffid,$reg_branch) : ((isset($_GET['id'])) && ($_GET['id'] == "Weekly") ? $savings->fetchWeeklyAggregateLedgerSavings($registeral,$irole,$reg_staffid,$reg_branch) : ((isset($_GET['id'])) && ($_GET['id'] == "Monthly") ? $savings->fetchMonthlyAggregateLedgerSavings($registeral,$irole,$reg_staffid,$reg_branch) : $savings->fetchYearlyAggregateLedgerSavings($registeral,$irole,$reg_staffid,$reg_branch))));

    $resultsInfo = $db->executeCall($registeral);
        
    if($resultsData === 0) {

        $message = "No Transaction was found";
        $http->notFound($message);

    }elseif($resultsInfo === -1) {

        $message = "Opps! Your Account ";
        $message .= isset($_GET['id']) ? "with id: ".$registeral : "";
        $message .= " still under review";
        $http->accessForbidden($message);

    }else {

        $http->OK($resultsInfo, $resultsData);

    }

}
?>