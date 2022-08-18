<?php
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
        'contractCode'=>$fetchsys_config['mo_contract_code'],
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
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        
      echo "cURL Error #:" . $err;
      
    } else {
        
        $mo_generate = json_decode($mo_response);
        
        //var_dump($mo_generate);
        
        if(!$mo_generate->requestSuccessful == true && !$mo_generate->responseMessage == 'success'){
          // there was an error from the API
          print_r('API returned error: ' . $mo_generate->responseMessage);
          
        }else{
            $myAccountReference = $mo_generate->responseBody->accountReference;
            $myAccountName = $mo_generate->responseBody->accountName;
            $myAccountNumber = $mo_generate->responseBody->accountNumber;
            $myBankName = $mo_generate->responseBody->bankName;
            $myStatus = $mo_generate->responseBody->status;
            $date_time = $mo_generate->responseBody->createdOn;
            ($myAccountReference != "" && $myAccountName != "" && $myAccountNumber != "" && $myBankName != "" && $myStatus != "") ? mysqli_query($link, "INSERT INTO virtual_account VALUE(null,'$myAccountReference','$accountUserId','$myAccountName','$myAccountNumber','$myBankName','$myStatus','$date_time','monify','$vcreated_by','agent','1000000','100000','10000','5000')") : "";
        }   
        
    }
?>