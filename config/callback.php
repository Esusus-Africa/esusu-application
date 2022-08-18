<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");

include("connect.php");

if($_SERVER['REQUEST_METHOD'] === "POST") {
	
	$input = @file_get_contents("php://input");
	
	$response = json_decode($input);
	
	//print_r($response);
	
	$curl = curl_init();
	$transactionReference = $response->transactionReference;
	$paymentReference = $response->paymentReference;
	$amountPaid = $response->amountPaid;
	$paidOn = $response->paidOn;
	$transactionHash = $response->transactionHash;
	
	$search_systemset = mysqli_query($link, "SELECT * FROM systemset");
	$fetch_systemset = mysqli_fetch_array($search_systemset);
	$clientSecret1 = $fetch_systemset['mo_secret_key'];
	$sysabb = $fetch_systemset['abb'];
	$date_time = date("Y-m-d g:i A");
	//$charges = $fetch_systemset['auth_charges'];
	
	//CALCULATE HASH (CONFIRMATION)
	$passcode1 = $clientSecret1.'|'.$paymentReference.'|'.$amountPaid.'|'.$paidOn.'|'.$transactionReference;
	$calc_hash1 = hash('sha512',$passcode1);
	
	// confirm the event's signature
	if($transactionHash !== $calc_hash1){
		// silently forget this ever happened
	  	exit();
	}
	else{
		// access post
		http_response_code(200); // PHP 5.4 or greater
		$accountReference = $response->product->reference;
		$paymentStatus = $response->paymentStatus;
		$totalPayable = $response->totalPayable;
		$bankCode = $response->accountDetails->bankCode;
		
		$search_bank = mysqli_query($link, "SELECT * FROM bank_list2 WHERE bankcode = '$bankCode'");
		$fetch_bank = mysqli_fetch_array($search_bank);
		
		$currencyCode = "";
		//$paymentMethod = $response->paymentMethod;
		//$amount_fund = $totalPayable - $charges;
		$recipient = "From:- ".$response->accountDetails->accountName;
		$recipient .= ", Account Number: ".$response->accountDetails->accountNumber;
		$recipient .= ", Bank Name: ".$fetch_bank['bankname']." | ".$response->accountDetails->bankCode;
		$date_time = date("Y-m-d H:i:s");
		
		$search_vacct = mysqli_query($link, "SELECT * FROM virtual_account WHERE account_ref = '$accountReference'");
    	$getvnum = mysqli_num_rows($search_vacct);
    	$fetch_vacct = mysqli_fetch_array($search_vacct);
    	
    	$search_pacct = mysqli_query($link, "SELECT * FROM pool_account WHERE account_ref = '$accountReference'");
    	$getpnum = mysqli_num_rows($search_pacct);
		$fetch_pacct = mysqli_fetch_array($search_pacct);
		
		$search_tacct = mysqli_query($link, "SELECT * FROM till_virtual_account WHERE account_ref = '$accountReference'");
    	$gettnum = mysqli_num_rows($search_tacct);
    	$fetch_tacct = mysqli_fetch_array($search_tacct);
    	
    	$myId = ($getvnum == 1 && $getpnum == 0 && $gettnum == 0 ? $fetch_vacct['userid'] : ($getvnum == 0 && $getpnum == 1 && $gettnum == 0 ? $fetch_pacct['userid'] : $fetch_tacct['userid']));
		
		$search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$myId'");
		$fetch_inst = mysqli_fetch_array($search_inst);
		
		$search_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$myId'");
		$user_num = mysqli_num_rows($search_user);
		$fetch_user = mysqli_fetch_array($search_user);
		$branch = $fetch_user['branchid'];
		
		$search_borrower = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$myId'");
		$borrower_num = mysqli_num_rows($search_borrower);
		$fetch_borrower = mysqli_fetch_array($search_borrower);
		
		$originator = ($user_num == 1 && $borrower_num == 0 ? $fetch_user['created_by'] : ($user_num == 0 && $borrower_num == 1 ? $fetch_borrower['branchid'] : $myId));
		
		$initiator = ($user_num == 1 && $borrower_num == 0 ? $fetch_user['id'] : ($user_num == 0 && $borrower_num == 1 ? $fetch_borrower['account'] : ''));
		
		//$status = ($getvnum == 1 && $getpnum == 0) ? "pending" : "pendingpool";
		
		//$wbalance = ($user_num == 1 && $borrower_num == 0 ? $fetch_user['transfer_balance'] : ($user_num == 0 && $borrower_num == 1 ? $fetch_borrower['wallet_balnce'] : $fetch_inst['wallet_balance']));
		
		$search_wvalidity = mysqli_query($link, "SELECT * FROM wallet_history WHERE refid = '$paymentReference'");
		$wunique = mysqli_num_rows($search_wvalidity);

		$search_pvalidity = mysqli_query($link, "SELECT * FROM pool_history WHERE refid = '$paymentReference'");
		$punique = mysqli_num_rows($search_pvalidity);

		$search_tvalidity = mysqli_query($link, "SELECT * FROM fund_allocation_history WHERE txid = '$paymentReference'");
		$tunique = mysqli_num_rows($search_tvalidity);
					
		//CONFIRM TRASACTION UNIQUENESS AND VALIDITY
		if($wunique == 0 && $punique == 0 && $tunique == 0){
			// update payment status and justify account balance for the institution
			($getvnum == 1 && $getpnum == 0 && $gettnum == 0 && $wunique == 0) ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$originator','$paymentReference','$recipient','$totalPayable','','Credit','$currencyCode','MNFY','','pending','$date_time','$initiator','','')") : "";
			($getvnum == 0 && $getpnum == 1 && $gettnum == 0 && $punique == 0) ? mysqli_query($link, "INSERT INTO pool_history VALUES(null,'$originator','$paymentReference','$recipient','$totalPayable','','Credit','$currencyCode','MNFY','','pendingpool','$date_time','$initiator','')") : "";
			($getvnum == 0 && $getpnum == 0 && $gettnum == 1 && $tunique == 0) ? mysqli_query($link, "INSERT INTO fund_allocation_history VALUES(null,'$paymentReference','$originator','$initiator','$branch','$recipient','$initiator','$totalPayable','Credit','MNFY','$currencyCode','','$recipient','pendingtill','$date_time')") : "";
				  
		}else{
			// silently forget this ever happened
			exit();
		}
	}
	
}
?>