<?php

// CALL TO PREPAID CARD (VERVE-CARD) REQUEST ENDPOINT
try
      {
        $appid = "test";
    	$appkey = "test";
    	$bvn = "22144577606";
    	$moi = "National Id";
        $fname = "Ayodeji";
        $lname = "Akinade";
        $phone = "08101750845";
        $addrs = "138, Akano Ifelagba Street,";
        $state = "Ibadan";
        $email = "akinadeayodeji5@hotmail.com";

        $client = new SoapClient('http://154.113.16.142:8088/PrepaidCardServiceTest/IswPrepaidCardService.asmx?WSDL');

        $param = array(
          'appId'=>$appid,
          'appKey'=>$appkey,
          'bvn'=>$bvn,
          'MeansOfIdNumber'=>$moi,
          'firstName'=>$fname,
          'lastName'=>$lname,
          'mobileNr'=>$phone,
          'streetAddress'=>$addrs,
          'streetAddressLine2'=>$state,
          'email'=>$email
        );

        $response = $client->PostIswNewPrepaidCard($param);
        
        $process = json_decode(json_encode($response), true);
        
        $statusCode = $process['PostIswNewPrepaidCardResult']['StatusCode'];
        
        $verveMsg = $process['PostIswNewPrepaidCardResult']['JsonData'];
        
        //echo $statusCode;
        
      }
      catch( Exception $e )
      {
        // You should not be here anymore
        echo $e->getMessage();
      }
  	
  	//END OF NEW PREPARD CARD REQUEST ENDPOINT
  	
  	
  	
  	
  	
  	
//CALL TO MAIN ACCOUNT BALANCE (VERVE) ENDPOINT
try
      {
        $appid = "test";
    	$appkey = "test";
    	$cCode = "566";
    
        
        $client = new SoapClient('http://154.113.16.138:81/prepaidcardservice/IswPrepaidCardService.asmx?WSDL');

        $param = array(
          'CurrencyCode'=>$cCode,
          'appId'=>$appid,
          'appKey'=>$appkey
        );

        $response = $client->GetAccountBalance($param);
        
        $process = json_decode(json_encode($response), true);
        
        print_r($process);
        
        $acctBal = $process['GetAccountBalanceResult']['JsonData'];

        $iparr = preg_split("/[:]+/", $acctBal);
        $cardBal = $iparr[1];
        
        echo "NGN".$cardBal;
        
      }
      catch( Exception $e )
      {
        // You should not be here anymore
        echo $e->getMessage();
      }

//END OF MAIN ACCOUNT BALANCE ENDPOINT





//CALL TO PREPAID CARD (VERVE-CARD) BALANCE ENDPOINT
try
      {
        $appid = "test";
        $appkey = "test";
        $CardNo = "*************";

        $client = new SoapClient('http://154.113.16.142:8088/PrepaidCardServiceTest/IswPrepaidCardService.asmx?WSDL');

        $param = array(
          'AccountNo'=>$CardNo,
          'appId'=>$appid,
          'appKey'=>$appkey
        );

        $response = $client->GetIswPrepaidCardAccountBalance($param);
        
        $process = json_decode(json_encode($response), true);
        
        $statusCode = $process['GetIswPrepaidCardAccountBalanceResult']['StatusCode'];
        
        $availableBalance = $process['GetIswPrepaidCardAccountBalanceResult']['JsonData'];
        
        $decodeProcess = json_decode($availableBalance, true);
        
        echo $decodeProcess['availableBalance'];
        
        echo $statusCode;
      }
      catch( Exception $e )
      {
        // You should not be here anymore
        echo $e->getMessage();
      }

//END OF PREPAID CARD BALANCE ENDPOINT




//CALL TO FUND PREPAID CARD (VERVE-CARD)
try
      {
        $appid = "test";
        $appkey = "test";
        $cCode = "566";
        $email = "akinadeayodeji5@hotmail.com";
        $fname = "Ayodeji";
        $lname = "Akinade";
        $mobile_no = "+2348101750845";
        $amount = 500.00;
        $pan = "41874262511111111";
        $pref = "EA-fundVerve-2563627999";

        $client = new SoapClient('http://154.113.16.142:8088/PrepaidCardServiceTest/IswPrepaidCardService.asmx?WSDL');

        $param = array(
          'appId'=>$appid,
          'appKey'=>$appkey,
          'currencyCode'=>$cCode,
          'emailAddress'=>$email,
          'firstName'=>$fname,
          'lastName'=>$lname,
          'mobileNr'=>$mobile_no,
          'amount'=>$amount,
          'pan'=>$pan,
          'PaymentRef'=>$pref
        );

        $response = $client->PostIswCardFund($param);
        
        $process = json_decode(json_encode($response), true);

        $responseCode = $process['PostIswCardFundResult']['responseCode']; //200 OR 99
        $responseCodeGrouping = $process['PostIswCardFundResult']['responseCodeGrouping'];
        $transferCode = $process['PostIswCardFundResult']['transferCode'];
        $pin = $process['PostIswCardFundResult']['pin'];
        $cardPan = $process['PostIswCardFundResult']['cardPan'];
        $cvv = $process['PostIswCardFundResult']['cvv'];
        $expiryDate = $process['PostIswCardFundResult']['expiryDate'];
      }
      catch( Exception $e )
      {
        // You should not be here anymore
        echo $e->getMessage();
      }
      
    //END OF PREPAID CARD (VERVE-CARD) FUNDING REQUEST
    
    
    
    
    
//CALL TO SPOOL OUT PREPARD CARD (VERVE-CARD) STATEMENT
    try
      {
        $PAN = "4187426257708747";
        $StartDate = "2020-01-01";
        $EndDate = "2020-02-16";
        $appid = "test";
        $appkey = "test";

        $client = new SoapClient('http://154.113.16.142:8088/PrepaidCardServiceTest/IswPrepaidCardService.asmx?WSDL');

        $param = array(
          'PAN'=>$PAN,
          'StartDate'=>$StartDate,
          'EndDate'=>$EndDate,
          'appId'=>$appid,
          'appKey'=>$appkey
        );

        $response = $client->GetIswPrepaidCardStatement($param);
        
        $process = json_decode(json_encode($response), true);

        //print_r($process);
        $StatusCode = $process['PostIswCardFundResult']['StatusCode']; //200 OR 400
        $JsonData = $process['PostIswCardFundResult']['JsonData'];
        
      }
      catch( Exception $e )
      {
        // You should not be here anymore
        echo $e->getMessage();
      }
      
    //END OF PREPAID CARD (VERVE-CARD) STATEMENT
    

    // CALL TO NIP FUND TRANSFER
    try
      {
        $username = "test";
    	$password = "test";
    	$amount = 10;
    	$currency = "NGN";
        $narration = "test transfer";
        $tReference = date("Ym").time();
        $recipient_account_number = "0002842719"; //"0056164488";
        $recipient_bank_code = "000013"; //"044";
        $account_name = "UGBO, CHARLES OMORE"; //"AYODEJI TIMOTHEW AKINADE";
        $originator_name = "UGBO, CHARLES OMORE"; //"AYODEJI TIMOTHEW AKINADE";

        $client = new SoapClient('http://154.113.16.142:9999/Payments/api?wsdl');

        $param = array(
          'amount'=>$amount,
          'currency'=>$currency,
          'narration'=>$narration,
          'transaction_reference'=>$tReference,
          'recipient_account_number'=>$recipient_account_number,
          'recipient_bank_code'=>$recipient_bank_code,
          'account_name'=>$account_name,
          'originator_name'=>$originator_name,
          'username'=>$username,
          'password'=>$password
        );

        $response = $client->NIPFundTransfer($param);
        
        $process = json_decode(json_encode($response), true);
        
        print_r($process);
        
        /*$statusCode = $process['PostIswNewPrepaidCardResult']['StatusCode'];
        
        $verveMsg = $process['PostIswNewPrepaidCardResult']['JsonData'];*/
        
        //echo $statusCode;
        
      }
      catch( Exception $e )
      {
        // You should not be here anymore
        echo $e->getMessage();
      }
  	
  	  //END OF NIP FUND TRANSFER
  	
  	
  	
  	//START OF SAMPLE REQUEST OF NIP BANK TRANSFER FOR PROVIDUS BANK ENDPOINT
  	require_once "config/nipBankTransfer_class.php";
    
    $providusUName = "test";
    $providusPass = "test";
    $amountWithNoCharges = 10;
    $currency = "NGN";
    $narration = "test transfer";
    $tReference = date("dmyi").time();
    $recipientAcctNo = "0002842719"; //"0056164488";
    $recipientBankCode = "000013"; //"044";
    $accountName = "UGBO, CHARLES OMORE"; //"AYODEJI TIMOTHEW AKINADE";
    $originatorName = "UGBO, CHARLES OMORE";
    
    $client = new SoapClient('http://154.113.16.142:9999/Payments/api?wsdl');
    
    $result = $new->providusNIPBankTransfer($providusUName,$providusPass,$amountWithNoCharges,$currency,$narration,$tReference,$recipientAcctNo,$recipientBankCode,$accountName,$originatorName,$client);
    
    print_r($result)."<br>";
    
    echo $result['transactionReference'];
    
    //END OF SAMPLE REQUEST OF NIP BANK TRANSFER FOR PROVIDUS BANK ENDPOINT
    
    
    
    //START OF SAMPLE REQUEST OF NIP BANK TRANSFER FOR WALLET AFRICA ENDPOINT
    require_once "config/nipBankTransfer_class.php";
    
    $walletafrica_skey = "17wn8ktq3dmd";
    $amountWithNoCharges = 10;
    $tReference = date("dmyi").time();
    $recipientAcctNo = "0056164488";
    $recipientBankCode = "044";
    $accountName = "AYODEJI TIMOTHEW AKINADE";

    $client = 'https://api.wallets.africa/transfer/bank/account';
    
    $result = $new->walletAfricaNIPBankTransfer($walletafrica_skey,$recipientBankCode,$recipientAcctNo,$accountName,$tReference,$amountWithNoCharges,$client);
    
    print_r($result);
    
    //END OF SAMPLE REQUEST OF NIP BANK TRANSFER FOR WALLET AFRICA ENDPOINT
    
    
    
    // CALL TO GET NIP BANK
    try
      {
        
        $api_url1 = 'http://154.113.16.142:9999/Payments/api?wsdl';

        $client = new SoapClient($api_url1);

        $response = $client->GetNIPBanks();
        
        $process = json_decode(json_encode($response), true);
        
        $processReturn = $process['return'];
        
        $process2 = json_decode($processReturn, true);
        
        $processReturn2 = $process2['banks'];
        
        $i = 0;
        
        foreach($processReturn2 as $key){
            
            echo "<option value=".$processReturn2[$i]['bankCode'].">".$processReturn2[$i]['bankName']." - ".$processReturn2[$i]['bankCode']."</option>";
            $i++;
            
        }
        
      }
      catch( Exception $e )
      {
        // You should not be here anymore
        echo $e->getMessage();
      }
  	
  	  //END OF GET NIP BANK
    
    
    
?>