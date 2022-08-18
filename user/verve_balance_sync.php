<?php
include("../config/session.php");

try{
    
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    $verveAppId = $row1->verveAppId;
    $verveAppKey = $row1->verveAppKey;
    
    $api_name =  "display_card_bal";
    $issurer = "VerveCard";
    $search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$issurer'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;

    $client = new SoapClient($api_url);

    $param = array(
      'AccountNo'=>$card_id,
      'appId'=>$verveAppId,
      'appKey'=>$verveAppKey
    );

    $response = $client->GetIswPrepaidCardAccountBalance($param);
        
    $process = json_decode(json_encode($response), true);
        
    $statusCode = $process['GetIswPrepaidCardAccountBalanceResult']['StatusCode'];
        
    $availableBalance = $process['GetIswPrepaidCardAccountBalanceResult']['JsonData'];
        
    $decodeProcess = json_decode($availableBalance, true);
    
    $availbal = $decodeProcess['availableBalance'] / 100;
    
    echo "Card Balance: ".$bbcurrency.number_format($availbal,2,'.',',');
    
}
catch( Exception $e ){
    
    // You should not be here anymore
    echo $e->getMessage();
    
}
?>