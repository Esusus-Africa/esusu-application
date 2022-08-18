<?php
include("../config/connect.php");

try{
    
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    $verveAppId = $row1->verveAppId;
    $verveAppKey = $row1->verveAppKey;
    
    $api_name =  "display_card_bal";
    $search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = 'VerveCard'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;
    
    $cCode = "566";
    
    $client = new SoapClient($api_url);
    
    $param = array(
        'CurrencyCode'=>$cCode,
        'appId'=>$verveAppId,
        'appKey'=>$verveAppKey
    );
    
    $response = $client->GetAccountBalance($param);
            
    $process = json_decode(json_encode($response), true);
    
    $acctBal = $process['GetAccountBalanceResult']['JsonData'];
    
    $iparr = preg_split("/[:]+/", $acctBal);
    $cardBal = $iparr[1];
    
    echo "NGN".$cardBal;
    
}
catch( Exception $e ){
    
    // You should not be here anymore
    echo $e->getMessage();
    
}
?>