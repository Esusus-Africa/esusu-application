<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST");

include("connect.php");

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
  $response3 = file_get_contents($urltouse);
  if ($debug) {
      //echo "Response: <br><pre>".
      //str_replace(array("<",">"),array("&lt;","&gt;"),$response3).
      //"</pre><br>"; 
  }
  return($response3);
}

if($_SERVER['REQUEST_METHOD'] === "POST"){
    
    // Retrieve the request's body
	$input = @file_get_contents("php://input");
	
	$response = json_decode($input);
	
	//print_r($response);
    
	$PAN_Masked = $response->PAN_Masked;
	$Account_PAN = $response->Account_PAN;
	$Merchant_Narrative = $response->Merchant_Narrative;
	$DateTime = $response->DateTime;
	$STAN = $response->STAN;
	$RRN = $response->RRN;
	$TrxnAmount = $response->TrxnAmount;
	$CurrCode = $response->CurrCode;
	$tType = "Debit";
	
	$systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    $verveAppId = $row1->verveAppId;
    $verveAppKey = $row1->verveAppKey;
    $sms_rate = $row1->fax;
    $refid = "EA-smsCharges-".time();
    
    $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;
        
    $api_name = "display_card_bal";
    $issurer = "VerveCard";
    $search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$issurer'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;
	
	$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE card_id = '$Account_PAN' AND card_reg = 'Yes'");
	$fetch_customernum = mysqli_num_rows($search_customer);
	$fetchcust = mysqli_fetch_array($search_customer);
	
	$search_agtbal = mysqli_query($link, "SELECT * FROM user WHERE card_id = '$Account_PAN' AND card_reg = 'Yes'");
	$fetch_agtnum = mysqli_num_rows($search_agtbal);
	$fetch_agtbal = mysqli_fetch_array($search_agtbal);
	
	$account = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetchcust['account'] : $fetch_agtbal['id'];
	$phone = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetchcust['phone'] : $fetch_agtbal['phone'];
	$email = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetchcust['email'] : $fetch_agtbal['email'];
	$fname = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetchcust['fname'] : $fetch_agtbal['name'];
	$lname = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetchcust['lname'] : $fetch_agtbal['lname'];
	$branchid = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetchcust['branchid'] : $fetch_agtbal['created_by'];
	$sbranchid = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetchcust['sbranchid'] : $fetch_agtbal['branchid'];
	$bank = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetchcust['card_issurer'] : $fetch_agtbal['card_issurer'];
	$vAccount = ($fetch_customernum == 1 && $fetch_agtnum == 0) ? $fetchcust['virtual_acctno'] : $fetch_agtbal['virtual_acctno']; 	
    	   
	$memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$branchid'");
	$fetch_memset = mysqli_fetch_array($memset);
	$isenderid = $fetch_memset['sender_id'];
	$icurrency = $fetch_memset['currency'];
    
    $client = new SoapClient($api_url);
    
    $param = array(
      'AccountNo'=>$Account_PAN,
      'appId'=>$verveAppId,
      'appKey'=>$verveAppKey
    );
    
    $response2 = $client->GetIswPrepaidCardAccountBalance($param);
            
    $process = json_decode(json_encode($response2), true);
            
    $statusCode = $process['GetIswPrepaidCardAccountBalanceResult']['StatusCode'];
            
    $availableBalance = $process['GetIswPrepaidCardAccountBalanceResult']['JsonData'];
            
    $decodeProcess = json_decode($availableBalance, true);
        
    $availbal = $decodeProcess['availableBalance'] / 100;
        
    $sms = "$isenderid>>>Dr. Pan Number ".$PAN_Masked." has been debited with $CurrCode".number_format($TrxnAmount,2,'.',',').". Reference ID: ".$RRN.". Card Balance: $CurrCode".number_format($availbal,2,'.',',').". ";
    $sms .= "Date ".$DateTime."";
        
    $searchCompany = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$branchid'");
	$fetchCompany = mysqli_fetch_array($searchCompany);
	    
	$max_per_page = 153;
    $sms_length = strlen($sms);
    $calc_length = ceil($sms_length / $max_per_page);
                	
    $sms_charges = $calc_length * $sms_rate;
	$walletBal = $fetchCompany['wallet_balance'];
	    
	$remainingCompanyBal = $walletBal - $sms_charges;
	$final_date_time = date("Y-m-d h:i:s");
	    
	($walletBal >= $sms_charges) ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$remainingCompanyBal' WHERE institution_id = '$branchid'") : "";
	mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$branchid','$RRN','$PAN_Masked','','$TrxnAmount','Debit','$icurrency','Card_Withdrawal','$sms','successful','$final_date_time','$account','$availbal','')") or die ("Error: " . mysqli_error($link));
	($walletBal >= $sms_charges) ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$branchid','$RRN','$PAN_Masked','','$sms_charges','Debit','$icurrency','Charges','$sms','successful','$final_date_time','','$remainingCompanyBal','')") or die ("Error: " . mysqli_error($link)) : "";
    mysqli_query($link, "INSERT INTO cardTransaction VALUES(null,'$account','$fname','$lname','$email','$phone','$Account_PAN','$PAN_Masked','$Merchant_Narrative','$DateTime','$STAN','$RRN','$TrxnAmount','$availbal','$CurrCode','$tType','$branchid','$sbranchid','$account')");
        
    $debug = true;
    sendSms($isenderid,$phone,$sms,$debug);
    include("cardEmailNotifier.php");
	   
}
?>