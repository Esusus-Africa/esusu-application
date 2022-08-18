<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");

include("connect.php");

function startsWith($string, $startString) 
{
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString);
}

if($_SERVER['REQUEST_METHOD'] === "POST"){
	
	// Retrieve the request's body
	$body = @file_get_contents("php://input");
		
	// retrieve the signature sent in the reques header's.
	$signature = (isset($_SERVER['HTTP_VERIF_HASH']) ? $_SERVER['HTTP_VERIF_HASH'] : '');
	
	/* It is a good idea to log all events received. Add code *
	 * here to log the signature and body to db or file       */
	
	if (!$signature) {
	    // only a post with rave signature header gets our attention
	    exit();
	}
	
	// Store the same signature on your server as an env variable and check against what was sent in the headers
	//putenv("SECRET_HASH=ESUSU_OF_AFRICA");
	
	//$local_signature = getenv('SECRET_HASH');
	$local_signature = "ESUSU_OF_AFRICA";
	
	// confirm the event's signature
	if($signature !== $local_signature){
	  // silently forget this ever happened
	  exit();
	}
	
	http_response_code(200); // PHP 5.4 or greater
	// parse event (which is json string) as object
	// Give value to your customer but don't give any output
	// Remember that this is a call from rave's servers and 
	// Your customer is not seeing the response here at all
	$response = json_decode($body);
	
	//var_dump($response);
	
	if($response->status == 'successful') {
	    # code...
		
		$res = json_encode($response, JSON_PRETTY_PRINT);
	    $content = ['body' => $res, 'local_signature' => $local_signature];
	    $pretty = json_decode(json_encode($content), JSON_PRETTY_PRINT);
	    file_put_contents(time(), $pretty);
		
	    // TIP: you may still verify the transaction
	    // before giving value.
		$plan_id = $response->paymentPlan;
		$customer_id = $response->customer->id;
		$paymentReference = $response->id;
		$reference_no = $response->txRef;
		$flwRef = $response->flwRef;
		$totalPayable = $response->amount;
		
		$invoice_code = $response->id;
    	$currency = $response->currency;
    	$status = $response->status;
    	$card6 = $response->entity->card6;
    	$card_last4 = $response->entity->card_last4;
    	
		$date_time = date("Y-m-d H:i:s");
		
		//INVESTMENT SAVINGS
    	$searchSub2 = mysqli_query($link, "SELECT * FROM savings_subscription WHERE reference_no = '$reference_no'");
    	$fetchSub2 = mysqli_fetch_array($searchSub2);
    	$origin_planid2 = $fetchSub2['origin_planid'];
    	$balanceToImpact2 = $fetchSub2['balanceToImpact'];
    	$mySavingsStatus2 = $fetchSub2['status'];
    	$savedRefNo2 = $fetchSub2['reference_no'];
    	$acn2 = $fetchSub2['acn'];
    		
    	$seach_mobileTransLog2 = mysqli_query($link, "SELECT * FROM mobileSavings_TransactionLog WHERE refid = '$reference_no'");
    	$num_mobileTransLog2 = mysqli_num_rows($seach_mobileTransLog);
    	
    	($num_mobileTransLog2 <= 0 && $balanceToImpact2 == "ledger" && $mySavingsStatus2 == "Pending" && $reference_no === "$savedRefNo2") ? mysqli_query($link, "INSERT INTO mobileSavings_TransactionLog VALUES(null,'$acn2','$reference_no','$invoice_code','$status','$date_time')") : "";
		
		//VIRTUAL ACCOUNT
		$recipient = "From:- ".$response->entity->first_name.' '.$response->entity->last_name;
		$recipient .= ", Account Number: ".$response->entity->account_number;
		
		$search_vacct = mysqli_query($link, "SELECT * FROM virtual_account WHERE account_ref = '$reference_no'");
    	$getvnum = mysqli_num_rows($search_vacct);
    	$fetch_vacct = mysqli_fetch_array($search_vacct);
    	
    	$search_pacct = mysqli_query($link, "SELECT * FROM pool_account WHERE account_ref = '$reference_no'");
    	$getpnum = mysqli_num_rows($search_pacct);
		$fetch_pacct = mysqli_fetch_array($search_pacct);
		
		$search_tacct = mysqli_query($link, "SELECT * FROM till_virtual_account WHERE account_ref = '$reference_no'");
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
		
		$search_wvalidity = mysqli_query($link, "SELECT * FROM wallet_history WHERE refid = '$paymentReference'");
		$wunique = mysqli_num_rows($search_wvalidity);

		$search_pvalidity = mysqli_query($link, "SELECT * FROM pool_history WHERE refid = '$paymentReference'");
		$punique = mysqli_num_rows($search_pvalidity);

		$search_tvalidity = mysqli_query($link, "SELECT * FROM fund_allocation_history WHERE txid = '$paymentReference'");
		$tunique = mysqli_num_rows($search_tvalidity);
					
		//CONFIRM TRASACTION UNIQUENESS AND VALIDITY
		if($wunique == 0 && $punique == 0 && $tunique == 0 && ($getvnum == 1 || $getpnum == 1 || $gettnum == 1)){
			// update payment status and justify account balance for the institution
			($getvnum == 1 && $getpnum == 0 && $gettnum == 0 && $wunique == 0) ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$originator','$paymentReference','$recipient','$totalPayable','','Credit','$currencyCode','WEMA','','pending','$date_time','$initiator','','')") : "";
			($getvnum == 0 && $getpnum == 1 && $gettnum == 0 && $punique == 0) ? mysqli_query($link, "INSERT INTO pool_history VALUES(null,'$originator','$paymentReference','$recipient','$totalPayable','','Credit','$currencyCode','WEMA','','pendingpool','$date_time','$initiator','')") : "";
			($getvnum == 0 && $getpnum == 0 && $gettnum == 1 && $tunique == 0) ? mysqli_query($link, "INSERT INTO fund_allocation_history VALUES(null,'$paymentReference','$originator','$initiator','$branch','$recipient','$initiator','$totalPayable','Credit','WEMA','$currencyCode','','$recipient','pendingtill','$date_time')") : "";
				  
		}elseif(!startsWith($reference_no, "EA-cardAuth")){
		    
		    //INVESTMENT SAVINGS
        	$searchSub = mysqli_query($link, "SELECT * FROM savings_subscription WHERE plan_id = '$plan_id'");
        	$fetchSub = mysqli_fetch_array($searchSub);
        	$origin_planid = $fetchSub['origin_planid'];
        	$balanceToImpact = $fetchSub['balanceToImpact'];
        	$mySavingsStatus = $fetchSub['status'];
        	$savedRefNo = $fetchSub['reference_no'];
        	$acn = $fetchSub['acn'];
        		
        	$seach_mobileTransLog = mysqli_query($link, "SELECT * FROM mobileSavings_TransactionLog WHERE refid = '$reference_no'");
        	$num_mobileTransLog = mysqli_num_rows($seach_mobileTransLog);
		    
    		$search_source = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_id = '$origin_planid'");
    		$numSource = mysqli_num_rows($search_source);
    		$fetch_source = mysqli_fetch_array($search_source);
    		
    		$search_source1 = mysqli_query($link, "SELECT * FROM target_savings WHERE plan_id = '$origin_planid'");
    		$numSource1 = mysqli_num_rows($search_source1);
    		$fetch_source1 = mysqli_fetch_array($search_source1);
    		
    		//INVESTMENT PLANNER
    		$merchantid =  ($numSource == 1 && $numSource1 == 0) ? $fetch_source['merchantid_others'] : $fetch_source1['companyid'];
    		$vendorid = ($numSource == 1 && $numSource1 == 0) ? $fetch_source['branchid'] : $fetch_source1['branchid'];
    		$plan_type = ($numSource == 1 && $numSource1 == 0) ? $fetch_source['planType'] : "Savings";
    		$todays_date = date('Y-m-d h:i:s');
    		
    		//Calculate Next Payment Date
            $savings_interval = ($fetchSub['savings_interval'] == "daily" ? 'day' : ($fetchSub['savings_interval'] == "weekly" ? 'week' : ($fetchSub['savings_interval'] == "monthly" ? 'month' : 'year')));
            $next_pmt_date = ($fetchSub['savings_interval'] == "ONE-OFF") ? "None" : date('Y-m-d h:i:s', strtotime('+1 '.$savings_interval, strtotime($todays_date)));
    		
    		$search_sub = mysqli_query($link, "SELECT * FROM savings_subscription WHERE reference_no = '$reference_no'");
    		$fetch_sub = mysqli_fetch_array($search_sub);
    		
    		$search_investor = mysqli_query($link, "SELECT * FROM all_savingssub_transaction WHERE reference_no = '$reference_no'");
    		$fetch_investor = mysqli_fetch_array($search_investor);
    		
    		//INVESTOR
    		$subscription_code = $fetch_sub['subscription_code']; //$response->orderRef;
    		$sub_bal = $fetch_sub['sub_balance'] + $totalPayable;
    		$auth_code = $fetch_investor['auth_code'];
    		$card_type = $fetch_investor['card_type'];
    		$bank_name = $fetch_investor['bank_name'];
    		$country_code = $fetch_investor['country_code'];
    		$plan_code = $fetchSub['plan_code'];
    		$myagentid = $fetch_source['agentid'];
    		
    		$search_investor_data = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
    		$fetch_investor_data = mysqli_fetch_array($search_investor_data);
    		$investment_bal = ($plan_type == "Savings") ? $fetch_investor_data['target_savings_bal'] : $fetch_investor_data['investment_bal'];
    		$total_bal = $investment_bal + $totalPayable;
    		$first_name = $fetch_investor_data['fname'];
    		$last_name = $fetch_investor_data['lname'];
    		$email = $fetch_investor_data['email'];
    		$phone = $fetch_investor_data['phone'];
    		$accountOfficer = $fetch_investor_data['lofficer'];
    		$remarks = "Automate Savings with SubCode: ".$subscription_code.", PlanCode: ".$plan_code." with Invoice Code: ".$invoice_code." through Card Payment";
    		//$ledgerRef = $flwRef."|".$reference_no;
    		
    		$seach_related_trans = mysqli_query($link, "SELECT * FROM all_savingssub_transaction WHERE invoice_code = '$invoice_code'");
    		$seach_related_trans2 = mysqli_query($link, "SELECT * FROM transaction WHERE txid = '$invoice_code'");
    		if((mysqli_num_rows($seach_related_trans) <= 0 || mysqli_num_rows($seach_related_trans2) <= 0) && $mySavingsStatus == "Approved")
    		{

    			($origin_planid != "" && ($balanceToImpact == "Target" || $balanceToImpact == "investment")) ? mysqli_query($link, "INSERT INTO all_savingssub_transaction VALUE(null,'$merchantid','$acn','$invoice_code','$subscription_code','$flwRef','$currency','$totalPayable','$status','$date_time','$customer_id','$first_name','$last_name','$auth_code','$card6','$card_last4','$card_type','$bank_name','$country_code','$plan_code','$vendorid','$myagentid')") : "";
    		
    			($origin_planid != "" && $balanceToImpact == "ledger") ? mysqli_query($link, "INSERT INTO transaction VALUE(null,'$invoice_code','Deposit','Card','$acn','---','$first_name','$last_name','$email','$phone','$totalPayable','$accountOfficer','$remarks','$date_time','$merchantid','$vendorid','$currency','','$total_bal','Approved','0','1','0')") : "";
    			
    			($plan_type == "Savings" && $origin_planid != "" && ($balanceToImpact == "target" || $balanceToImpact == "ledger")) ? mysqli_query($link, "UPDATE borrowers SET target_savings_bal = '$total_bal' WHERE account = '$acn' OR virtual_acctno = '$acn'") : "";
    			($plan_type != "Savings" && $origin_planid != "" && $balanceToImpact == "investment") ? mysqli_query($link, "UPDATE borrowers SET investment_bal = '$total_bal' WHERE account = '$acn' OR virtual_acctno = '$acn'") : "";
    			
    			($origin_planid != "") ? mysqli_query($link, "UPDATE savings_subscription SET sub_balance = '$sub_bal', next_pmt_date = '$next_pmt_date' WHERE subscription_code = '$subscription_code' AND status = 'Approved' AND acn = '$acn'") : "";

    		}else{
    		    //Do nothing...
    		}
		
		}

	}
	else{
	    //Do nothing...
	}
	
}
	
?>