<?php

include("../config/connect.php");

//$link = mysqli_connect('localhost','esusulive_esusuapp','BoI9YR^zqs%M','esusulive_esusuapp') or die('Unable to Connect to Database');

include("../config/walletafrica_restfulapis_call.php");

$search_systemset = mysqli_query($link, "SELECT * FROM systemset");
$fetch_systemset = mysqli_fetch_array($search_systemset);
$verveAppId = $fetch_systemset['verveAppId'];
$verveAppKey = $fetch_systemset['verveAppKey'];
$walletafrica_skey = $fetch_systemset['walletafrica_skey'];

$search_readydisburement = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE status = 'batchCardDisbursement'");
$confirm_num = mysqli_num_rows($search_readydisburement);

if($confirm_num == 0){
    
    echo "";
    
}
else{
    while($readydisburement = mysqli_fetch_array($search_readydisburement)){
        
        $id = $readydisburement['id'];
        $userid = $readydisburement['userid'];
        $otp_pinCode = $readydisburement['otp_code'];
        $data = $readydisburement['data'];
        
        $parameter = (explode('|',$data));
        
        $reference = $parameter[0]; //date("yd").time();
        $amountWithNoCharges = preg_replace("/[^0-9]/", "", $parameter[1]);
        
        $searchCust = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$userid'");
        $fetchCust = mysqli_fetch_array($searchCust);
        $pan = $fetchCust['card_id'];
        $cust_fname = $fetchCust['fname'];
        $cust_lname = $fetchCust['lname'];
        $cust_email = $fetchCust['email'];
        $currency_type = "566";
        $cust_phone = $fetchCust['phone'];
        $bvirtual_phone_no = $fetchCust['virtual_number'];
        $cust_wbal = $fetchCust['wallet_balance'];
        
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
            'currencyCode'=>$currency_type,
            'emailAddress'=>$cust_email,
            'firstName'=>$cust_fname,
            'lastName'=>$cust_lname,
            'mobileNr'=>$cust_phone,
            'amount'=>$amountWithNoCharges,
            'pan'=>$pan,
            'PaymentRef'=>$reference
        );
    
        $response = $client->PostIswCardFund($param);
            
        $process = json_decode(json_encode($response), true);
        
        //print_r($param);
        //print_r($process);
        
        $responseCode = $process['PostIswCardFundResult']['responseCode']; //90000 OR 99
        $responseCodeGrouping = $process['PostIswCardFundResult']['responseCodeGrouping'];
        
        if($responseCode === "90000"){
            
            mysqli_query($link, "UPDATE otp_confirmation SET status = 'Verified' WHERE id = '$id' AND status = 'batchCardDisbursement'");
            
        }elseif($responseCode === "99"){
            
            $txid = date("yd").time();
            //Receivers Parameters
            $receiverBalance = $cust_wbal + $amountWithNoCharges;
            
            mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$receiverBalance', card_id = '' WHERE account = '$userid'");
            mysqli_query($link, "DELETE FROM wallet_history WHERE refid = '$reference' AND paymenttype = 'Topup-Prepaid_Card'");
            mysqli_query($link, "UPDATE otp_confirmation SET status = 'Verified' WHERE id = '$id' AND status = 'batchCardDisbursement'");
            
        }
        else{
            
            echo "";
            
        }
        
    }
}

?>