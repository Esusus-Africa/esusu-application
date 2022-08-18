<?php
function wellaHealthSubNotifier($FirstName,$LastName,$Phone,$Email,$Location,$StateOfResidence,$Lga,$Area,$StreetName,$Amount,$PaymentPlan,$Product,$Gender,$DateOFBirth){
    global $link, $wUsername, $wPassword, $AgentCode;

    $curl = curl_init();

    //WELLAHEALTH SUBSCRIPTION NOTIFICATION
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'wellahealth_endpoint'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url.'subscriptions';

    $encodeBasicAuth = $wUsername.':'.$wPassword;

    $myBasicToken = base64_encode($encodeBasicAuth);
       
    curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'FirstName'=>$FirstName,
            'LastName'=>$LastName,
            'Phone'=>$Phone,
            'Email'=>$Email,
            'Location'=>$Location,
            'StateOfResidence'=>$StateOfResidence,
            'Lga'=>$Lga,
            'Area'=>$Area,
            'StreetName'=>$StreetName,
            'AcquisitionChannel'=>"Agent",
            'AgentCode'=>$AgentCode,
            'Amount'=>$Amount,
            'PaymentPlan'=>$PaymentPlan,
            'Product'=>$Product,
            'Gender'=>$Gender,
            'DateOFBirth'=>$DateOFBirth,
            'BeneficiaryList'=>[]
        ]),
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Basic ".$myBasicToken
        ),
      ));
    
    $wella_response = curl_exec($curl);
     
    $wella_generate = json_decode($wella_response);

    return $wella_generate;
}
?>