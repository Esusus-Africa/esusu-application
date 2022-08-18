<?php
	
    include("../config/connect.php");

	function sendSms($sender, $phone, $msg, $debug=false)
	{
	  global $gateway_uname,$gateway_pass,$gateway_api;
	
	  $url = 'username='.$gateway_uname;
	  $url.= '&password='.$gateway_pass;
	  $url.= '&sender='.urlencode($sender);
	  $url.= '&recipient='.urlencode($phone);
	  $url.= '&message='.urlencode($msg);
	
	  $urltouse =  $gateway_api.$url;
	  //if ($debug) { echo "Request: <br>$urltouse<br><br>"; }
	
	  //Open the URL to send the message
	  $response = file_get_contents($urltouse);
	  if ($debug) {
	      //echo "Response: <br><pre>".
	      //str_replace(array("<",">"),array("&lt;","&gt;"),$response).
	      //"</pre><br>"; 
	  }
	  return($response);
	}
	
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
		
		if($sms != "" && $email != ""){
			
			$debug = true;
			sendSms($sysabb,$phone,$sms,$debug);
			
			$result = array();
			// Pass the customer's authorisation code, email and amount
			$postdata =  array(
			              "From"          => $r->email_from,
			              "To"            => $email,  //borrower Email Address
			              "TemplateId"    => '15418977',
			              "TemplateModel" => [
			                "product_name"      => $r->name,
			                "name"              => $borrower_name,  //borrower's Name
			                "logo_url"          => $r->logo_url,
			                "product_url"       => $r->website,
			                "loan_id"           => $mylid,
			                "due_amount"        => $mycurr.number_format($due_amt,2,'.',','),
			                "expected_bal"      => $mycurr.number_format($current_lbal,2,'.',','),
			                "due_date"          => $due_date,
			                "support_email"     => $r->email,
			                "live_chat_url"     => $r->live_chat,
			                "company_name"      => $r->name,
			                "company_address"   => $r->address
			              ]
			            );
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			$headers = [
			  'Accept: application/json',
			  'Content-Type: application/json',
			  'X-Postmark-Server-Token: '.$r->email_token
			];
			
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			
			$request = curl_exec($ch);
			
			curl_close ($ch);
			if ($request) {
			  $result = json_decode($request, true);
			  if($result['Message'] == "OK")
			  {
			    //echo "Email Sent Successfully";
			  }else{
			    echo "Error Code: ".$result['ErrorCode'];
			  }
			}
			
			mysqli_query($link, "UPDATE pending_loan_reminder SET status = 'sent' WHERE id = '$id'");
			
		}
		if($sms == "" && $email != ""){
			
			$result = array();
			// Pass the customer's authorisation code, email and amount
			$postdata =  array(
			              "From"          => $r->email_from,
			              "To"            => $email,  //borrower Email Address
			              "TemplateId"    => '15418977',
			              "TemplateModel" => [
			                "product_name"      => $r->name,
			                "name"              => $borrower_name,  //borrower's Name
			                "logo_url"          => $r->logo_url,
			                "product_url"       => $r->website,
			                "loan_id"           => $mylid,
			                "due_amount"        => $mycurr.number_format($due_amt,2,'.',','),
			                "expected_bal"      => $mycurr.number_format($current_lbal,2,'.',','),
			                "due_date"          => $due_date,
			                "support_email"     => $r->email,
			                "live_chat_url"     => $r->live_chat,
			                "company_name"      => $r->name,
			                "company_address"   => $r->address
			              ]
			            );
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			$headers = [
			  'Accept: application/json',
			  'Content-Type: application/json',
			  'X-Postmark-Server-Token: '.$r->email_token
			];
			
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			
			$request = curl_exec($ch);
			
			curl_close ($ch);
			if ($request) {
			  $result = json_decode($request, true);
			  if($result['Message'] == "OK")
			  {
			    //echo "Email Sent Successfully";
			  }else{
			    echo "Error Code: ".$result['ErrorCode'];
			  }
			}
			
			mysqli_query($link, "UPDATE pending_loan_reminder SET status = 'sent' WHERE id = '$id'");
			
		}
		if($sms != "" && $email == ""){
			
			$debug = true;
			sendSms($sysabb,$phone,$sms,$debug);
			
			mysqli_query($link, "UPDATE pending_loan_reminder SET status = 'sent' WHERE id = '$id'");
			
		}
		if($sms == "" && $email == ""){
			
			exit(); //forget this ever happens
			
		}
		
	}
	mysqli_query($link, "DELETE FROM pending_loan_reminder WHERE status = 'sent'");
	
?>