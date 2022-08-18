<?php

include("../config/connect.php");

//$link = mysqli_connect('localhost','esusulive_esusuapp','BoI9YR^zqs%M','esusulive_esusuapp') or die('Unable to Connect to Database');

include("../config/walletafrica_restfulapis_call.php");

function sendSms($sender, $ph, $msg, $debug=false)
{
  global $gateway_uname,$gateway_pass,$gateway_api;

  $url = 'username='.$gateway_uname;
  $url.= '&password='.$gateway_pass;
  $url.= '&sender='.urlencode($sender);
  $url.= '&recipient='.urlencode($ph);
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

function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);
}

$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
$fetch_gateway = mysqli_fetch_object($search_gateway);
$gateway_uname = $fetch_gateway->username;
$gateway_pass = $fetch_gateway->password;
$gateway_api = $fetch_gateway->api;

$date_time = date("Y-m-d h:i:s");
$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');

$search_systemset = mysqli_query($link, "SELECT * FROM systemset");
$fetch_systemset = mysqli_fetch_array($search_systemset);
$verveAppId = $fetch_systemset['verveAppId'];
$verveAppKey = $fetch_systemset['verveAppKey'];
$walletafrica_skey = $fetch_systemset['walletafrica_skey'];

$currenctDate = date("Y-m-d");
$disburseStatus = "NextQueue_".$currenctDate;

$search_customer1 = mysqli_query($link, "SELECT * FROM borrowers WHERE auto_disbursement_status = 'Active' AND next_disbursement_date = '$currenctDate'");
while($fetch_customer = mysqli_fetch_array($search_customer)){
    
    $disbursement_interval = ($fetch_customer['disbursement_interval'] == "weekly" ? "week" : ($fetch_customer['disbursement_interval'] == "monthly" ? "month" : "year"));
        
    $calc_nextDisburse_date = date('Y-m-d', strtotime('+1 '.$disbursement_interval, strtotime($currenctDate)));
    
    $nextDisburseStatus = "NextQueue_".$calc_nextDisburse_date;
        
    $institution_id = $fetch_customer['branchid'];
        
    $account = $fetch_customer['account'];
    
    $uname = $fetch_customer['username'];
        
    $amount = $fetch_customer['balance'];
    
    $bwalletBal = $fetch_customer['wallet_balance'];
    
    $totalWalletBal = $bwalletBal + $amount;
    
    $disbursementChannel = $fetch_customer['disbursement_channel'];
    
    $bank_id = $fetch_customer['bank_id'];
    
    $icurrency_type = "566";
    
    $icurrency = $fetch_customer['currency'];
    
    $fn = $fetch_customer['fname'];
	
	$ln = $fetch_customer['lname'];
	
	$em = $fetch_customer['email'];
	
	$ph = $fetch_customer['phone'];
    
    $txid = date("yd").time();
    
    $searchInst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$institution_id'");
    $fetchInst = mysqli_fetch_array($searchInst);
    $inst_name = $fetchInst['institution_name'];
    $instWallet = $fetchInst['wallet_balance'];
    $imywallet_balanc = $instWallet - $amount;
    $wallet_date_time = date("Y-m-d h:i:s");
    
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
	$fetch_memset = mysqli_fetch_array($search_memset);
	$sysabb = $fetch_memset['sender_id'];
    
    $message = "$sysabb>>>DR";
    $message .= " Amt: ".$icurrency.$amount."";
    $message .= " Acc: ".ccMasking($account)."";
    $message .= " Desc: Withdrawal - ".$txid."";
    $message .= " Time: ".$correctdate."";
    $message .= " Bal: ".$icurrency."0.0";
    
    if(($instWallet >= $amount) && $disbursementChannel == 'Prepaid_card'){
        
        //Fetch Gateway
        $issurer = "VerveCard";
        $api_name = "card_load";
        $search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$issurer'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
            	
        $client = new SoapClient($api_url);
        
        $param = array(
            'appId'=>$verveAppId,
            'appKey'=>$verveAppKey,
            'currencyCode'=>$icurrency_type,
            'emailAddress'=>$fetch_customer['email'],
            'firstName'=>$fetch_customer['fname'],
            'lastName'=>$fetch_customer['lname'],
            'mobileNr'=>$ph,
            'amount'=>$amount,
            'pan'=>$fetch_customer['card_id'],
            'PaymentRef'=>$txid
        );
    
        $response = $client->PostIswCardFund($param);
            
        $process = json_decode(json_encode($response), true);
        
        $ptype = "Card";
        
        ($responseCode == "90000") ? $debug = true : "";
        
        ($responseCode == "90000") ? sendSms($sysabb,$ph,$message,$debug) : "";
        
        ($responseCode == "90000") ? mysqli_query($link, "UPDATE borrowers SET balance = '0.0', next_disbursement_date = '$calc_nextDisburse_date' WHERE account = '$account' AND branchid = '$institution_id'") : "";
        
        ($responseCode == "90000") ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','$ph','','$amount','Debit','$icurrency','Charges','SMS Content: Automatic disbursement to Card from Ledger balance','successful','$wallet_date_time','','$imywallet_balanc','')") : "";
        
        ($responseCode == "90000") ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$imywallet_balanc' WHERE institution_id = '$institution_id'") : "";
        
        ($responseCode == "90000") ? mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','Withdraw','$ptype','$account','----','$fn','$ln','$em','$ph','$amount','','Auto Disbursement','$correctdate','$institution_id','','$icurrency','','0.0')") or die (mysqli_error($link)) : "";
        
        ($responseCode == "90000") ? include("send_sdeposit_alertemail.php") : "";
        
    }
    if(($instWallet >= $amount) && $disbursementChannel == 'Wallet'){
        
        $ptype = "Wallet";
        
        $debug = true;
        
        sendSms($sysabb,$ph,$message,$debug);
        
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','$ph','','$amount','Debit','$icurrency','Charges','SMS Content: Automatic disbursement to Customer Wallet from Ledger balance','successful','$wallet_date_time','','$imywallet_balanc','$totalWalletBal')");
        
        mysqli_query($link, "UPDATE borrowers SET balance = '0.0', next_disbursement_date = '$calc_nextDisburse_date' WHERE account = '$account' AND branchid = '$institution_id'");
        
        mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$imywallet_balanc' WHERE institution_id = '$institution_id'");
        
        mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','Withdraw','$ptype','$account','----','$fn','$ln','$em','$ph','$amount','','Auto Disbursement','$correctdate','$institution_id','','$icurrency','','0.0')") or die (mysqli_error($link));
        
        include("send_sdeposit_alertemail.php");
        
    }
    if(($instWallet >= $amount) && $disbursementChannel == 'Bank'){
        
        $searchBank = mysqli_query($link, "SELECT * FROM transfer_recipient WHERE companyid = '$institution_id' AND recipient_id = '$bank_id'");
        $fetchBank = mysqli_fetch_array($searchBank);
        $bank_code = $fetchBank['bank_code'];
        $accountNum = $fetchBank['acct_no'];
        $b_name = $fetchBank['full_name'];
        $mybank_name = $fetchBank['bank_name'];
        
        $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_transfer'");
        $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
        $api_url1 = $fetch_restapi1->api_url;
            
        $postdata1 =  array(
            "SecretKey" => $walletafrica_skey,
            "BankCode" => $bank_code,
            "AccountNumber" => $accountNum,
            "AccountName" => $b_name,
            "TransactionReference" => $txid,
            "Amount" => $amount
        );
                               
        $make_call1 = callAPI('POST', $api_url1, json_encode($postdata1));
        $result1 = json_decode($make_call1, true);
        
        $tramsferid = substr((uniqid(rand(),1)),3,6);
        $gatewayResponse = $result1['Message'];
        $transactionDateTime = date("Y-m-d H:i:s");
        $ptype = "Bank";

        $recipient = $accountNum.', '.$b_name.', '.$mybank_name;
        
        ($result1['ResponseCode'] == "100") ? $debug = true : "";
        
        ($result1['ResponseCode'] == "100") ? sendSms($sysabb,$ph,$message,$debug) : "";

        ($result1['ResponseCode'] == "100") ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','$recipient','','$amount','Debit','$icurrency','BANK_TRANSFER','Transfer to $recipient','successful','$transactionDateTime','','$imywallet_balanc','')") : "";
        ($result1['ResponseCode'] == "100") ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','$recipient','','$calcCharges','Debit','$icurrency','Charges','Transfer to $recipient','successful','$transactionDateTime','','$imywallet_balanc','')") : "";
                
        ($result1['ResponseCode'] == "100") ? mysqli_query($link, "UPDATE borrowers SET balance = '0.0', next_disbursement_date = '$calc_nextDisburse_date' WHERE account = '$account' AND branchid = '$institution_id'") : "";
        
        ($result1['ResponseCode'] == "100") ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$imywallet_balanc' WHERE institution_id = '$institution_id'") : "";
        
        ($result1['ResponseCode'] == "100") ? mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','Withdraw','$ptype','$account','----','$fn','$ln','$em','$ph','$amount','','Auto Disbursement','$correctdate','$institution_id','','$icurrency','','0.0')") or die (mysqli_error($link)) : "";
        
        ($result1['ResponseCode'] == "100") ? include("send_sdeposit_alertemail.php") : "";
        
    }
    

}

?>