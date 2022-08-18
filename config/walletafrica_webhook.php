<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");

include("connect.php");

$sql = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = ''") or die (mysqli_error($link));
$find = mysqli_fetch_array($sql);
$gateway_uname = $find['username'];
$gateway_pass = $find['password'];
$gateway_api = $find['api'];

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
  $response1 = file_get_contents($urltouse);
  if ($debug) {
      //echo "Response: <br><pre>".
      //str_replace(array("<",">"),array("&lt;","&gt;"),$response1).
      //"</pre><br>"; 
  }
  return($response1);
}

function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);
}

if($_SERVER['REQUEST_METHOD'] === "POST") {
	
	$input = @file_get_contents("php://input");
	
	$response = json_decode($input, true);
	
	$referenceId = "EA-ref".date("mY").time();
	$transactionDate = $response['DatePaid'];
	$bankAccountName = $response['AccountName'];
	$bankAccountNo = $response['AccountNumber'];
	$amountDeposited = $response['Amount'];
	$uniquePhoneNo = $response['phonenumber'];
	$webhooktype = $response['TransactionType'];
	
	//Verify the right customer to credit
	$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE virtual_number = '$uniquePhoneNo'");
	$fetch_customer = mysqli_fetch_array($search_customer);
	$fetch_cust_number = mysqli_num_rows($search_customer);
	
	//Verify the right client to credit
	$search_client = mysqli_query($link, "SELECT * FROM user WHERE virtual_number = '$uniquePhoneNo'");
	$fetch_client = mysqli_fetch_array($search_client);
	$fetch_client_number = mysqli_num_rows($search_client);
	
	$systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    $sysabb = $row1->abb;
    $walletafrica_skey = $row1->walletafrica_skey;
    $calc_charges = ($row1->wa_gatewaycharge / 100) * $amountDeposited;
    $charges = ($calc_charges >= $row1->wa_cappedamt) ? $row1->wa_cappedamt : $calc_charges;
	
	if($fetch_cust_number == 1 && $webhooktype == "credit"){
	    
	    //http_response_code(200); // PHP 5.4 or greater
	    $currencyCode = $fetch_customer['currency'];
	    $phoneNo = $fetch_customer['phone'];
	    $accountId = $fetch_customer['account'];
	    $institutionId = $fetch_customer['branchid'];
	    $walletBanalnce = $fetch_customer['wallet_balance'];
	    $bbranchid = $fetch_customer['branchid'];
		$acct = ccMasking($accountId);
		$today = date("Y-m-d");
		$t_type1 = "Stamp Duty";
		$income_id = "ICM".time();
		$paymentMethod = "ACCOUNT_TRANSFER";
		$wallet_date_time = date("Y-m-d H:i:s");
		$recipient = "From:- ".$bankAccountName;
		$recipient .= ", Account Number: ".$bankAccountNo;
		
		$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_debit'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
		
		$curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>"{\r\n  \"transactionReference\": \"$referenceId\",\r\n  \"amount\": $charges,\r\n  \"phoneNumber\": \"$uniquePhoneNo\",\r\n  \"secretKey\": \"$walletafrica_skey\"\r\n}",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer btx9cyh7332r"
          ),
        ));
        
        $response = curl_exec($curl);
        
        $transaction = json_decode($response, true);
        
        if($transaction['Response']['ResponseCode'] == "200"){
            
            $walletBalance = $transaction['Data']['CustomerWalletBalance'];
            
            $sms = "$sysabb>>>CR";
            $sms .= " Amt: ".$currencyCode.number_format($amountDeposited,2,'.',',')."";
            $sms .= " Charges: ".$currencyCode.number_format($charges,2,'.',',')."";
            $sms .= " Acct: ".$acct."";
            $sms .= " Desc: $paymentMethod - | ".$referenceId."";
            $sms .= " Time: ".$transactionDate."";
            $sms .= " Bal: ".$currencyCode.number_format($walletBalance,2,'.',',').""; 
              
            $debug = true;
            sendSms($sysabb,$phoneNo,$sms,$debug);
            mysqli_query($link, "INSERT INTO income VALUES(null,'','$income_id','Charges','$charges','$today','$t_type1')");
    	    mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$walletBalance' WHERE virtual_number = '$uniquePhoneNo'");
    	    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$referenceId','$recipient','$amountDeposited','$currencyCode','$paymentMethod','SMS Content: $sms','successful','$wallet_date_time','$accountId','$walletBanalnce')");
    	    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$referenceId','self','$charges','$currencyCode','$t_type1','SMS Content: $sms','successful','$wallet_date_time','$accountId','$walletBanalnce')");

        }
        else{
            echo "";
        }
	    
	}
	if($fetch_client_number == 1 && $webhooktype == "credit"){
	    
	    http_response_code(200); // PHP 5.4 or greater
	    $currencyCode = $fetch_customer['currency'];
	    $phoneNo = $fetch_client['phone'];
	    $accountId = $fetch_client['id'];
	    $institutionId = $fetch_client['created_by'];
	    $walletBanalnce = $fetch_client['transfer_balance'];
		$acct = ccMasking($accountId);
		$today = date("Y-m-d");
		$t_type1 = "Stamp Duty";
		$income_id = "ICM".time();
		$paymentMethod = "ACCOUNT_TRANSFER";
		$wallet_date_time = date("Y-m-d H:i:s");
		$recipient = "From:- ".$bankAccountName;
		$recipient .= ", Account Number: ".$bankAccountNo;
		
		$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_debit'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
		
		$curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>"{\r\n  \"transactionReference\": \"$referenceId\",\r\n  \"amount\": $charges,\r\n  \"phoneNumber\": \"$uniquePhoneNo\",\r\n  \"secretKey\": \"$walletafrica_skey\"\r\n}",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer btx9cyh7332r"
          ),
        ));
        
        $response = curl_exec($curl);
        
        $transaction = json_decode($response, true);
        
        if($transaction['Response']['ResponseCode'] == "200"){
            
            $walletBalance = $transaction['Data']['CustomerWalletBalance'];
            
            $sms = "$sysabb>>>CR";
            $sms .= " Amt: ".$currencyCode.number_format($amountDeposited,2,'.',',')."";
            $sms .= " Charges: ".$currencyCode.number_format($charges,2,'.',',')."";
            $sms .= " Acct: ".$acct."";
            $sms .= " Desc: $paymentMethod - | ".$referenceId."";
            $sms .= " Time: ".$transactionDate."";
            $sms .= " Bal: ".$currencyCode.number_format($walletBalance,2,'.',',').""; 
              
            $debug = true;
            sendSms($sysabb,$phoneNo,$sms,$debug);
            mysqli_query($link, "INSERT INTO income VALUES(null,'','$income_id','Charges','$charges','$today','$t_type1')");
    	    mysqli_query($link, "UPDATE user SET transfer_balance = '$walletBalance' WHERE virtual_number = '$uniquePhoneNo'");
    	    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institutionId','$referenceId','$recipient','$amountDeposited','$currencyCode','$paymentMethod','SMS Content: $sms','successful','$wallet_date_time''$accountId','$walletBanalnce')");
    	    mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institutionId','$referenceId','self','$charges','$currencyCode','$t_type1','SMS Content: $sms','successful','$wallet_date_time''$accountId','$walletBanalnce')");

        }
        else{
            echo "";
        }
	    
	}
	else{
	    
	    exit(); //Forget this ever happens
	    
	}
	
}
?>