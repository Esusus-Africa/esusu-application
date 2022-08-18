<?php
include("../config/connect.php");

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
$local_signature = getenv('ESUSU_OF_AFRICA');

// confirm the event's signature
if( $signature !== $local_signature ){
  // silently forget this ever happened
  exit();
}

http_response_code(200); 
// PHP 5.4 or greater
// parse event (which is json string) as object
// Give value to your customer but don't give any output
// Remember that this is a call from rave's servers and 
// Your customer is not seeing the response here at all
$response = json_decode($body, true);
if($response['status'] == 'successful'){
    # code...
    // TIP: you may still verify the transaction
        // before giving value.
    $invoice_code = $response['id'];
    $subscription_code = $response['orderRef'];
    $txref = $response['txRef'];
    $currency = $response['currency'];
    $charged_amount = $response['amount'];
    $status = $response['status'];
    $customercode = $response['customer']['id'];
    $full_name = $response['fullName'];
    $card6 = $response['entity']['card6'];
    $card_last4 = $response['entity']['card_last4'];
    $tdesc = "Recurring Savings";
    
    $search_allsub = mysqli_query($link, "SELECT * FROM all_savingssub_transaction WHERE subscription_code = '$subscription_code'");
    $fetch_allsub = mysqli_fetch_array($search_allsub);
    $merchantID = $fetch_allsub['merchant_id'];
    $acn = $fetch_allsub['acn'];
    $acctbusinessname = $fetch_allsub['first_name'];
    $cardtype = $fetch_allsub['card_type'];
    $paymenttype = $fetch_allsub['bank_name'];
    $acctcountry = $fetch_allsub['country_code'];
    $plan_code = $fetch_allsub['next_pmt_date'];
    $auth_code = $fetch_allsub['auth_code'];
    $date_time = $fetch_allsub['date_time'];
    
    $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
    $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
    $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
    $correctdate = $acst_date->format('Y-m-d g:i A');
    
    $search_borrower = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
    $fetch_borrower = mysqli_fetch_array($search_borrower);
    $binvest_bal = $fetch_borrower['investment_bal'];
    $phone = $fetch_borrower['phone'];
    $em = $fetch_borrower['email'];
    
    $total_invbal = $binvest_bal + $charged_amount;
    
    $update_records = mysqli_query($link, "UPDATE borrowers SET investment_bal = '$total_invbal' WHERE account = '$acn'");
    $insert_records = mysqli_query($link, "INSERT INTO all_savingssub_transaction VALUES(null,'$companyid','$acn','$invoice_code','$subscription_code','$txref','$currency','$charged_amount','$status',NOW(),'$customercode','$acctbusinessname','$full_name','$auth_code','$card6','$card_last4','$cardtype','$paymenttype','$acctcountry','$plan_code')");
    
    $sms = "$sysabb>>>CR";
	$sms .= " Amt: ".$currency.number_format($charged_amount,2,'.',',')."";
	$sms .= " Acc: ".$acn."";
	$sms .= " Desc: ".$tdesc." | ".$txref."";
	$sms .= " Time: ".$correctdate."";
	$sms .= " Bal: ".$currency.number_format($total_invbal,2,'.',',')."";

	include("send_general_sms.php");
	include("send_email_forrecurringsavings.php");
}
if($response['transfer']['status'] == 'SUCCESSFUL'){
    # code...
    // TIP: you may still verify the transaction
    		// before giving value.
    $refid = $response['transfer']['reference'];
    $status = $response['transfer']['status'];
    $transfer_fee = $response['transfer']['fee'];
    $amount = $response['transfer']['amount'];
    $currency = $response['transfer']['currency'];

    $search_thist = mysqli_query($link, "SELECT * FROM transfer_history WHERE reference = '$refid'");
    $fetch_thist = mysqli_fetch_array($search_thist);
    $userid = $fetch_thist['userid'];
    $date_time = $fetch_thist['date_time'];

    $search_ag = mysqli_query($link, "SELECT * FROM agent_data WHERE agentid = '$userid'");
    $search_ins = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$userid'");
    $search_ma = mysqli_query($link, "SELECT * FROM  merchant_reg WHERE merchantID = '$userid' AND role = 'Admin'");
    $search_cop = mysqli_query($link, "SELECT * FROM cooperatives WHERE coopid = '$userid'");
    $search_usr = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$userid'");


    if(mysqli_num_rows($search_ag) == 1)
    {
        $fetch_ag = mysqli_fetch_array($search_ag);
        $mywallet_balance = $fetch_ag['wallet_balance'];
        $phone = $fetch_ag['phone'];
        $em = $fetch_ag['email'];
        $uname = $fetch_ag['fname'];
        $tdesc = "Transfer";

		$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
        $correctdate = $acst_date->format('Y-m-d g:i A');

        $search_sysset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$userid'");
        $fetch_sysset = mysqli_fetch_array($search_sysset);
        $sysabb = $fetch_sysset['sender_id'];

        $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$userid' AND status = 'Activated'");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;

        $update_trecords = mysqli_query($link, "UPDATE transfer_history SET transfers_fee = '$transfer_fee', status = '$status' WHERE reference = '$refid'");

        $sms = "$sysabb>>>DR";
		$sms .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
		$sms .= " Acc: ".$userid."";
		$sms .= " Desc: ".$tdesc." | ".$refid."";
		$sms .= " Time: ".$correctdate."";
		$sms .= " Bal: ".$currency.number_format($mywallet_balance,2,'.',',')."";

		include("send_general_sms.php");
		include("send_email_fortransferstatus.php");
    }
    elseif(mysqli_num_rows($search_ins) == 1)
    {
    	$fetch_ins = mysqli_fetch_array($search_ins);
    	$mywallet_balance = $fetch_ins['wallet_balance'];
    	$phone = $fetch_ins['official_phone'];
        $em = $fetch_ins['official_email'];
        $uname = $fetch_ins['institution_name'];
        $tdesc = "Transfer";

        $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
        $correctdate = $acst_date->format('Y-m-d g:i A');

        $search_sysset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$userid'");
        $fetch_sysset = mysqli_fetch_array($search_sysset);
        $sysabb = $fetch_sysset['sender_id'];

        $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$userid' AND status = 'Activated'");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;

        $update_trecords = mysqli_query($link, "UPDATE transfer_history SET transfers_fee = '$transfer_fee', status = '$status' WHERE reference = '$refid'");

        $sms = "$sysabb>>>DR";
		$sms .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
		$sms .= " Acc: ".$userid."";
		$sms .= " Desc: ".$tdesc." | ".$refid."";
		$sms .= " Time: ".$correctdate."";
		$sms .= " Bal: ".$currency.number_format($mywallet_balance,2,'.',',')."";

		include("send_general_sms.php");
		include("send_email_fortransferstatus.php");
    }
    elseif(mysqli_num_rows($search_ma) == 1)
    {
    	$fetch_ma = mysqli_fetch_array($search_ma);
    	$mywallet_balance = $fetch_ma['wallet_balance'];
    	$phone = $fetch_ma['official_phone'];
        $em = $fetch_ma['official_email'];
        $uname = $fetch_ma['company_name'];
        $tdesc = "Transfer";

        $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
        $correctdate = $acst_date->format('Y-m-d g:i A');

        $search_sysset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$userid'");
        $fetch_sysset = mysqli_fetch_array($search_sysset);
        $sysabb = $fetch_sysset['sender_id'];

        $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$userid' AND status = 'Activated'");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;

        $update_trecords = mysqli_query($link, "UPDATE transfer_history SET transfers_fee = '$transfer_fee', status = '$status' WHERE reference = '$refid'");

        $sms = "$sysabb>>>DR";
		$sms .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
		$sms .= " Acc: ".$userid."";
		$sms .= " Desc: ".$tdesc." | ".$refid."";
		$sms .= " Time: ".$correctdate."";
		$sms .= " Bal: ".$currency.number_format($mywallet_balance,2,'.',',')."";

		include("send_general_sms.php");
		include("send_email_fortransferstatus.php");
    }
    elseif(mysqli_num_rows($search_cop) == 1)
    {
    	$fetch_cop = mysqli_fetch_array($search_cop);
    	$mywallet_balance = $fetch_cop['wallet_balance'];
    	$phone = $fetch_cop['official_phone'];
        $em = $fetch_cop['official_email'];
        $uname = $fetch_cop['coopname'];
        $tdesc = "Transfer";

        $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
        $correctdate = $acst_date->format('Y-m-d g:i A');

        $search_sysset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$userid'");
        $fetch_sysset = mysqli_fetch_array($search_sysset);
        $sysabb = $fetch_sysset['sender_id'];

        $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$userid' AND status = 'Activated'");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;

        $update_trecords = mysqli_query($link, "UPDATE transfer_history SET transfers_fee = '$transfer_fee', status = '$status' WHERE reference = '$refid'");

        $sms = "$sysabb>>>DR";
		$sms .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
		$sms .= " Acc: ".$userid."";
		$sms .= " Desc: ".$tdesc." | ".$refid."";
		$sms .= " Time: ".$correctdate."";
		$sms .= " Bal: ".$currency.number_format($mywallet_balance,2,'.',',')."";

		include("send_general_sms.php");
		include("send_email_fortransferstatus.php");
    }
    elseif(mysqli_num_rows($search_usr) == 1)
    {
    	$fetch_usr = mysqli_fetch_array($search_usr);
    	$mywallet_balance = $fetch_usr['wallet_balance'];
    	$phone = $fetch_usr['phone'];
        $em = $fetch_usr['email'];
        $uname = $fetch_usr['fname'].' '.$fetch_usr['lname'];
        $tdesc = "Transfer";
        $branchid = $fetch_usr['branchid'];

        $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
        $correctdate = $acst_date->format('Y-m-d g:i A');

        $search_sysset1 = mysqli_query($link, "SELECT * FROM systemset");
        $fetch_sysset1 = mysqli_fetch_array($search_sysset1);

        $search_sysset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$branchid'");
        $fetch_sysset = mysqli_fetch_array($search_sysset);
        $sysabb = ($fetch_sysset['sender_id'] == "") ? $fetch_sysset1['abb'] : $fetch_sysset['sender_id'];

        $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$branchid' AND status = 'Activated'");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;

        $update_trecords = mysqli_query($link, "UPDATE transfer_history SET transfers_fee = '$transfer_fee', status = '$status' WHERE reference = '$refid'");

        $sms = "$sysabb>>>DR";
		$sms .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
		$sms .= " Acc: ".$userid."";
		$sms .= " Desc: ".$tdesc." | ".$refid."";
		$sms .= " Time: ".$correctdate."";
		$sms .= " Bal: ".$currency.number_format($mywallet_balance,2,'.',',')."";

		include("send_general_sms.php");
		include("send_email_fortransferstatus.php");
    }
    else{
    	$tdesc = "Transfer";

    	$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
        $correctdate = $acst_date->format('Y-m-d g:i A');

        $search_sysset = mysqli_query($link, "SELECT * FROM systemset");
        $fetch_sysset = mysqli_fetch_array($search_sysset);
        $sysabb = $fetch_sysset['abb'];
        $phone = $fetch_sysset['mobile'];
        $em = $fetch_sysset['paypal_email'];

        $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;

    	$update_trecords = mysqli_query($link, "UPDATE transfer_history SET transfers_fee = '$transfer_fee', status = '$status' WHERE reference = '$refid'");

    	$sms = "$sysabb>>>DR";
		$sms .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
		$sms .= " Acc: ".$userid."";
		$sms .= " Desc: ".$tdesc." | ".$refid."";
		$sms .= " Time: ".$correctdate."";

		include("send_general_sms.php");
    }
}
elseif($response['transfer']['status'] == 'FAILED'){
    $refid = $response['transfer']['reference'];
    $status = $response['transfer']['status'];
    $transfer_fee = $response['transfer']['fee'];
    $amount = $response['transfer']['amount'];
    $currency = $response['transfer']['currency'];

    $search_thist = mysqli_query($link, "SELECT * FROM transfer_history WHERE reference = '$refid'");
    $fetch_thist = mysqli_fetch_array($search_thist);
    $userid = $fetch_thist['userid'];
    
    $search_ag = mysqli_query($link, "SELECT * FROM agent_data WHERE agentid = '$userid'");
    $search_ins = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$userid'");
    $search_ma = mysqli_query($link, "SELECT * FROM  merchant_reg WHERE merchantID = '$userid' AND role = 'Admin'");
    $search_cop = mysqli_query($link, "SELECT * FROM cooperatives WHERE coopid = '$userid'");
    $search_usr = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$userid'");
    
    if(mysqli_num_rows($search_ag) == 1)
    {
        $fetch_ag = mysqli_fetch_array($search_ag);
        $mywallet_balance = $fetch_ag['wallet_balance'] + $amount + $transfer_fee;
        $phone = $fetch_ag['phone'];
        $em = $fetch_ag['email'];
        $uname = $fetch_ag['fname'];
        $tdesc = "Reversal";

		$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
        $correctdate = $acst_date->format('Y-m-d g:i A');

        $search_sysset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$userid'");
        $fetch_sysset = mysqli_fetch_array($search_sysset);
        $sysabb = $fetch_sysset['sender_id'];

        $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$userid' AND status = 'Activated'");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;
        
        $update_ag = mysqli_query($link, "UPDATE agent_data SET wallet_balance = '$mywallet_balance' WHERE agentid = '$userid'");
        $update_trecords = mysqli_query($link, "UPDATE transfer_history SET status = '$status' WHERE reference = '$refid'");

        $sms = "$sysabb>>>CR";
		$sms .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
		$sms .= " Acc: ".$userid."";
		$sms .= " Desc: ".$tdesc." | ".$refid."";
		$sms .= " Time: ".$correctdate."";
		$sms .= " Bal: ".$currency.number_format($mywallet_balance,2,'.',',')."";

		include("send_general_sms.php");
		include("send_email_fortransferstatus.php");
    }
    elseif(mysqli_num_rows($search_ins) == 1)
    {
    	$fetch_ins = mysqli_fetch_array($search_ins);
    	$mywallet_balance = $fetch_ins['wallet_balance'] + $amount + $transfer_fee;
    	$phone = $fetch_ins['official_phone'];
        $em = $fetch_ins['official_email'];
        $uname = $fetch_ins['institution_name'];
        $tdesc = "Reversal";

        $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
        $correctdate = $acst_date->format('Y-m-d g:i A');

        $search_sysset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$userid'");
        $fetch_sysset = mysqli_fetch_array($search_sysset);
        $sysabb = $fetch_sysset['sender_id'];

        $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$userid' AND status = 'Activated'");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;

    	$update_ins = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mywallet_balance' WHERE institution_id = '$userid'");
        $update_trecords = mysqli_query($link, "UPDATE transfer_history SET status = '$status' WHERE reference = '$refid'");

        $sms = "$sysabb>>>CR";
		$sms .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
		$sms .= " Acc: ".$userid."";
		$sms .= " Desc: ".$tdesc." | ".$refid."";
		$sms .= " Time: ".$correctdate."";
		$sms .= " Bal: ".$currency.number_format($mywallet_balance,2,'.',',')."";

		include("send_general_sms.php");
		include("send_email_fortransferstatus.php");
    }
    elseif(mysqli_num_rows($search_ma) == 1)
    {
    	$fetch_ma = mysqli_fetch_array($search_ma);
    	$mywallet_balance = $fetch_ma['wallet_balance'] + $amount + $transfer_fee;
    	$phone = $fetch_ma['official_phone'];
        $em = $fetch_ma['official_email'];
        $uname = $fetch_ma['company_name'];
        $tdesc = "Reversal";

        $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
        $correctdate = $acst_date->format('Y-m-d g:i A');

        $search_sysset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$userid'");
        $fetch_sysset = mysqli_fetch_array($search_sysset);
        $sysabb = $fetch_sysset['sender_id'];

        $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$userid' AND status = 'Activated'");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;

    	$update_ma = mysqli_query($link, "UPDATE merchant_reg SET wallet_balance = '$mywallet_balance' WHERE merchantID = '$userid' AND role = 'Admin'");
        $update_trecords = mysqli_query($link, "UPDATE transfer_history SET status = '$status' WHERE reference = '$refid'");

        $sms = "$sysabb>>>CR";
		$sms .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
		$sms .= " Acc: ".$userid."";
		$sms .= " Desc: ".$tdesc." | ".$refid."";
		$sms .= " Time: ".$correctdate."";
		$sms .= " Bal: ".$currency.number_format($mywallet_balance,2,'.',',')."";

		include("send_general_sms.php");
		include("send_email_fortransferstatus.php");
    }
    elseif(mysqli_num_rows($search_cop) == 1)
    {
    	$fetch_cop = mysqli_fetch_array($search_cop);
    	$mywallet_balance = $fetch_cop['wallet_balance'] + $amount + $transfer_fee;
    	$phone = $fetch_cop['official_phone'];
        $em = $fetch_cop['official_email'];
        $uname = $fetch_cop['coopname'];
        $tdesc = "Reversal";

        $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
        $correctdate = $acst_date->format('Y-m-d g:i A');

        $search_sysset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$userid'");
        $fetch_sysset = mysqli_fetch_array($search_sysset);
        $sysabb = $fetch_sysset['sender_id'];

        $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$userid' AND status = 'Activated'");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;

    	$update_cop = mysqli_query($link, "UPDATE cooperatives SET wallet_balance = '$mywallet_balance' WHERE coopid = '$userid'");
        $update_trecords = mysqli_query($link, "UPDATE transfer_history SET status = '$status' WHERE reference = '$refid'");

        $sms = "$sysabb>>>CR";
		$sms .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
		$sms .= " Acc: ".$userid."";
		$sms .= " Desc: ".$tdesc." | ".$refid."";
		$sms .= " Time: ".$correctdate."";
		$sms .= " Bal: ".$currency.number_format($mywallet_balance,2,'.',',')."";

		include("send_general_sms.php");
		include("send_email_fortransferstatus.php");
    }
    elseif(mysqli_num_rows($search_usr) == 1)
    {
    	$fetch_usr = mysqli_fetch_array($search_usr);
    	$mywallet_balance = $fetch_usr['wallet_balance'] + $amount + $transfer_fee;
    	$phone = $fetch_usr['phone'];
        $em = $fetch_usr['email'];
        $uname = $fetch_usr['fname'].' '.$fetch_usr['lname'];
        $tdesc = "Reversal";
        $branchid = $fetch_usr['branchid'];

        $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
        $correctdate = $acst_date->format('Y-m-d g:i A');

        $search_sysset1 = mysqli_query($link, "SELECT * FROM systemset");
        $fetch_sysset1 = mysqli_fetch_array($search_sysset1);

        $search_sysset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$branchid'");
        $fetch_sysset = mysqli_fetch_array($search_sysset);
        $sysabb = ($fetch_sysset['sender_id'] == "") ? $fetch_sysset1['abb'] : $fetch_sysset['sender_id'];

        $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$branchid' AND status = 'Activated'");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;

    	$update_usr = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$mywallet_balance' WHERE account = '$userid'");
        $update_trecords = mysqli_query($link, "UPDATE transfer_history SET status = '$status' WHERE reference = '$refid'");

        $sms = "$sysabb>>>CR";
		$sms .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
		$sms .= " Acc: ".$userid."";
		$sms .= " Desc: ".$tdesc." | ".$refid."";
		$sms .= " Time: ".$correctdate."";
		$sms .= " Bal: ".$currency.number_format($mywallet_balance,2,'.',',')."";

		include("send_general_sms.php");
		include("send_email_fortransferstatus.php");
    }
    else{
    	$tdesc = "Reversal";

    	$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
        $correctdate = $acst_date->format('Y-m-d g:i A');

        $search_sysset = mysqli_query($link, "SELECT * FROM systemset");
        $fetch_sysset = mysqli_fetch_array($search_sysset);
        $sysabb = $fetch_sysset['abb'];
        $phone = $fetch_sysset['mobile'];
        $em = $fetch_sysset['paypal_email'];

        $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;

    	$update_trecords = mysqli_query($link, "UPDATE transfer_history SET status = '$status' WHERE reference = '$refid'");

    	$sms = "$sysabb>>>CR";
		$sms .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
		$sms .= " Acc: ".$userid."";
		$sms .= " Desc: ".$tdesc." | ".$refid."";
		$sms .= " Time: ".$correctdate."";

		include("send_general_sms.php");
    }
}
exit();
?>