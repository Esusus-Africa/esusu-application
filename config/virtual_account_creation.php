<?php
function providusVirtualAccount($accountReference,$accountName,$currencyCode,$customerEmail,$customerName,$userBvn){
    global $link, $mo_contract_code;

    $curl = curl_init();
    $curl2 = curl_init();
      
    $api_url2 = "https://api.monnify.com/api/v1/auth/login";
    
    curl_setopt_array($curl2, array(
        CURLOPT_URL => $api_url2,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(
          "Authorization: Basic TUtfUFJPRF9HSFpZR1o0U0hIOjJRTjJRVFBUU0hUS0hUVVFHWFVZVk1DNVFaVVNRR0RV"
        ),
    ));
      
    $auth_response = curl_exec($curl2);
    $autherr = curl_error($curl2);
      
    curl_close($curl2);
      
    if ($autherr) {
          
        echo "cURL Error #:" . $autherr;
        
    }
    
    $auth_generate = json_decode($auth_response);
    
    $myToken = $auth_generate->responseBody->accessToken;
      
    //GENERATE VIRTUAL ACCOUNT ON MONIFY
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'mo_create_virtual_account'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;
    
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
            'accountReference'=>$accountReference,
            'accountName'=>$accountName,
            'currencyCode'=>$currencyCode,
            'contractCode'=>$mo_contract_code,
            'customerEmail'=>$customerEmail,
            'customerName'=>$customerName,
            'customerBvn'=>$userBvn
        ]),
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$myToken
        ),
      ));
    
      $mo_response = curl_exec($curl);
      $mo_generate = json_decode($mo_response);
          
      if(!$mo_generate->requestSuccessful == true && !$mo_generate->responseMessage == 'success'){
          
          // there was an error from the API
          print_r('API returned error: ' . $mo_generate->responseMessage);

      }
      else{

        return $mo_generate;

      }

}
?>