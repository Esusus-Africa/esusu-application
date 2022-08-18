<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");

include("connect.php");

if($_SERVER['REQUEST_METHOD'] === "POST") {
	
	$input = @file_get_contents("php://input");
	
	$response = json_decode($input);
	
    $accountRef = $response->details->customer_ref;
    $transactionRef = $response->details->transaction_ref;
    $bankName = $response->details->provider;
	$accountNumber = $response->details->data->account_number;
    $accountFName = $response->details->customer_firstname;
    $accountLName = $response->details->customer_surname;
    $mobilePhone = $response->details->customer_mobile_no;
    $emailAddress = $response->details->customer_email;
    $transType = $response->details->data->data->transactionType;
    $amount = ($response->details->amount / 100);
    $remark = $response->details->data->data->remarks;
    $status = $response->details->status;
    $date_time = date("Y-m-d H:i:s");
	
	http_response_code(200); // PHP 5.4 or greater
    $search_systemset = mysqli_query($link, "SELECT * FROM systemset");
	$fetch_systemset = mysqli_fetch_array($search_systemset);
	$sysabb = $fetch_systemset['abb'];
		
	$search_bank = mysqli_query($link, "SELECT * FROM bank_account WHERE reference = '$accountRef'");
	$fetch_bank = mysqli_fetch_array($search_bank);
    $currencyCode = $fetch_bank['currency_code'];
    $merchantid = $fetch_bank['merchantid'];
    $branchid = $fetch_bank['branchid'];
    $staffid = $fetch_bank['staffid'];
    $customerid = $fetch_bank['customerid'];
    $balance = $fetch_bank['balance'];
    $totalBalanceLeft = ($transType == "collect" && $status == "Successful" ? ($balance + $amount) : ($transType == "disburse" && $status == "Successful" ? ($balance - $amount) : $balance));
		
	$search_wvalidity = mysqli_query($link, "SELECT * FROM bank_account_stmt WHERE transaction_ref = '$transactionRef'");
	$wunique = mysqli_num_rows($search_wvalidity);
					
	//CONFIRM TRASACTION UNIQUENESS AND VALIDITY				
	if($wunique == 0){
        // update payment status and justify account balance for the bank account
        ($wunique == 0) ? mysqli_query($link, "UPDATE bank_account SET balance = '$totalBalanceLeft' WHERE reference = '$accountRef'") : "";
		($wunique == 0) ? mysqli_query($link, "INSERT INTO bank_account_stmt VALUES(null,'$merchantid','$branchid','$staffid','$customerid','$accountRef','$transactionRef','$bankName','$accountNumber','$accountFName','$accountLName','$mobilePhone','$emailAddress','$transType','$currencyCode','$amount','$totalBalanceLeft','$remark','$status','$date_time')") : "";
		
	}else{
		// just update st
        //mysqli_query($link, "UPDATE bank_account SET balance = '$totalBalanceLeft' WHERE reference = '$accountRef'");
        mysqli_query($link, "UPDATE bank_account_stmt SET mystatus = '$status' WHERE transaction_ref = '$transactionRef'");

	}
	
}
?>