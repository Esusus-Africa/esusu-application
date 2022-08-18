<?php
	
	$link = mysqli_connect('esusuapp-backend.mysql.database.azure.com','esusuafrica@esusuapp-backend','MyEABackend2019','EAbackendDb') or die('Unable to Connect to Database');
	
	$search_system = mysqli_query($link, "SELECT * FROM systemset");
	$r = mysqli_fetch_object($search_system);

	$search_pendingreminder = mysqli_query($link, "SELECT * FROM pending_loan_reminder WHERE status = 'pending'");
	while($fetch_pending = mysqli_fetch_array($search_pendingreminder)){
		
		$id = $fetch_pending['id'];
		$sms = $fetch_pending['sms_details'];
		$due_date = $fetch_pending['due_date'];
		$myacct = $fetch_pending['acct_no'];
		$mylid = $fetch_pending['loan_id'];
		$borrower_name = $fetch_pending['borrower_name'];
		$due_amt = $fetch_pending['due_amt'];
		$phone = $fetch_pending['phone'];
		$email = $fetch_pending['email'];
		$currency = $fetch_pending['currency'];
		$current_lbal = $fetch_pending['current_lbal'];
		$sysabb = $fetch_pending['sender_id'];
		
		$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
        $fetch_gateway = mysqli_fetch_object($search_gateway);
        $gateway_uname = $fetch_gateway->username;
        $gateway_pass = $fetch_gateway->password;
        $gateway_api = $fetch_gateway->api;
		
		if($sms_content != "" && $email != ""){
			
			include("https://esusu.app/cron/send_general_sms.php");
			
            include("https://esusu.app/cron/send_loanreminder_email.php");
			
			mysqli_query($link, "UPDATE pending_loan_reminder SET status = 'sent' WHERE id = '$id'");
			
		}
		if($sms_content == "" && $email != ""){
			
			include("https://esusu.app/cron/send_loanreminder_email.php");
			
			mysqli_query($link, "UPDATE pending_loan_reminder SET status = 'sent' WHERE id = '$id'");
			
		}
		if($sms_content != "" && $email == ""){
			
			include("https://esusu.app/cron/send_general_sms.php");
			
			mysqli_query($link, "UPDATE pending_loan_reminder SET status = 'sent' WHERE id = '$id'");
			
		}
		if($sms_content == "" && $email == ""){
			
			exit(); //forget this ever happens
			
		}
		
	}
	mysqli_query($link, "DELETE FROM pending_loan_reminder WHERE status = 'sent'");
	
?>