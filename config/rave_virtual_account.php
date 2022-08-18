<?php
	$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'rave_create_virtual_account'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;
    
    curl_setopt_array($curl1, array(
      CURLOPT_URL => $api_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode([
        'email'=>$customerEmail,
        'seckey'=>$fetchsys_config['secret_key'],
        'is_permanent'=>true,
        'narration'=>$accountName
      ]),
      CURLOPT_HTTPHEADER => [
        "content-type: application/json",
        "cache-control: no-cache"
      ],
    ));	
    
    $rave_response = curl_exec($curl1);
    $err = curl_error($curl1);
    
    if($err){
      // there was an error contacting the rave API
      die('Curl returned error: ' . $err);
    }
    
    $rave_generate = json_decode($rave_response);
    
    //var_dump($rave_generate);
    
    if(!$rave_generate->status == 'success' && !$rave_generate->message == 'BANKTRANSFERS-ACCOUNTNUMBER-CREATED'){
      // there was an error from the API
      print_r('API returned error: ' . $rave_generate->message);
      
    }else{
        
        $myRaveAccountReference = $rave_generate->data->flw_reference;
        $myRaveAccountNumber = $rave_generate->data->accountnumber;
        $myRaveBankName = $rave_generate->data->bankname;
        $myRaveStatus = "ACTIVE";
        $Ravedate_time = $rave_generate->data->created_on;
            
        mysqli_query($link, "INSERT INTO virtual_account VALUE(null,'$myRaveAccountReference','$accountUserId','$accountName','$myRaveAccountNumber','$myRaveBankName','$myRaveStatus','$Ravedate_time','rave')");
        
    }
?>