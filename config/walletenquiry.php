<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");

include("connect.php");

if($_SERVER['REQUEST_METHOD'] === "POST"){
    
    // Retrieve the request's body
	$input = @file_get_contents("php://input");
	
    $response = json_decode($input);

    $accountNo = $response->account;

    //User Verification
    $search_cbalance = mysqli_query($link, "SELECT * FROM borrowers WHERE virtual_acctno = '$accountNo'");
    $fetch_cbalance = mysqli_fetch_array($search_cbalance);
    $mycnum = mysqli_num_rows($search_cbalance);
    $myccum_fullname = $fetch_cbalance['lname'].' '.$fetch_cbalance['fname'].' '.$fetch_cbalance['mname'];
    $myccum_balance = $fetch_cbalance['wallet_balance'];
        
    $search_myibalance = mysqli_query($link, "SELECT * FROM user WHERE virtual_acctno = '$accountNo'");
    $fetch_myibalance = mysqli_fetch_array($search_myibalance);
    $myinum = mysqli_num_rows($search_myibalance);
    $myi_name = $fetch_myibalance['lname'].' '.$fetch_myibalance['name'].' '.$fetch_myibalance['mname'];
    $myi_balance = $fetch_myibalance['transfer_balance'];
        
    //Detect Right User
    $myname = ($mycnum == 1 && $myinum == 0) ? $myccum_fullname : $myi_name;
    $userBalance = ($mycnum == 1 && $myinum == 0) ? $myccum_balance : $myi_balance;
    $detectRightUser = ($mycnum == 1 && $myinum == 0) ? "Customer" : "Institution";

    if($mycnum == 0 && $myinum == 0){

        $arr = array(
            "status"=>"notfound",
            "statuscode"=>"96"
        );
        
        echo json_encode($arr);

    }
    else{

        $arr = array(
            "status"=>"success",
            "statuscode"=>"00",
            "balance"=>$userBalance,
            "wallet"=>$accountNo,
            "walletname"=>$myname
        );
    
        echo json_encode($arr);

    }

}
?>