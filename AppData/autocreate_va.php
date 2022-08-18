<?php

include("../config/connect.php");

//$link = mysqli_connect('localhost','esusulive_esusuapp','BoI9YR^zqs%M','esusulive_esusuapp') or die('Unable to Connect to Database');

function callAPI($method, $url, $data){
    $curl = curl_init();
 
    switch ($method){
       case "POST":
          curl_setopt($curl, CURLOPT_POST, 1);
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          break;
       case "PUT":
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
          break;
       default:
          if ($data)
             $url = sprintf("%s?%s", $url, http_build_query($data));
    }
 
    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'content-type: application/json'
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
 
    // EXECUTE:
    $result = curl_exec($curl);
    if(curl_error($curl)){
         echo 'error:' . curl_error($curl);
     }
    curl_close($curl);
    return $result;
 }
 
 function myreference($limit)
 {
     return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
 }

 // Function to check string starting 
// with given substring 
function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}

$search_vaccount = mysqli_query($link, "SELECT * FROM virtual_account WHERE status = 'pending'");
if(mysqli_num_rows($search_vaccount) == 1){
  
    $curl = curl_init();
    $curl1 = curl_init();
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
    //var_dump($auth_generate);
    $myToken = $auth_generate->responseBody->accessToken;

    $mysystem_config = mysqli_query($link, "SELECT * FROM systemset");
    $fetchsys_config = mysqli_fetch_array($mysystem_config);
    
    //GENERATE VIRTUAL ACCOUNT ON MONIFY
    while($fetch_vaccount = mysqli_fetch_array($search_vaccount)){

        $accountUserId = $fetch_vaccount['userid'];
        $accountReference = $fetch_vaccount['account_ref'];

        $searchMa = mysqli_query($link, "SELECT * FROM member_setttings WHERE companyid = '$accountUserId'");
        $fetchMa = mysqli_fetch_array($searchMa);
        $accountName = $fetchMa['cname'];
        $currencyCode = $fetchMa['currency'];

        //START INSTITUTION
        if(startsWith($accountUserId,"INST") || startsWith($accountUserId,"MER") || startsWith($accountUserId,"AGT")) {

            $check_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$accountUserId'");
            $fetch_inst = mysqli_fetch_array($check_inst);
            $customerEmail = $fetch_inst['official_email'];
            $userBvn = $fetch_inst['bvn'];

            $check_bvn = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$accountUserId'");
            $fetch_bvn = mysqli_fetch_array($check_bvn);
            $customerName = $fetch_bvn['name'];
            

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
                    //$myAccountReference = $mo_generate->responseBody->accountReference;
                    $myAccountName = $mo_generate->responseBody->accountName;
                    $myAccountNumber = $mo_generate->responseBody->accountNumber;
                    $myBankName = $mo_generate->responseBody->bankName;
                    $myStatus = $mo_generate->responseBody->status;
                    $date_time = $mo_generate->responseBody->createdOn;
                    mysqli_query($link, "UPDATE virtual_account SET account_name = '$myAccountName', account_number = '$myAccountNumber', bank_name = '$myBankName', status = '$myStatus', date_time = '$date_time' WHERE status = 'pending' AND userid = '$accountUserId'");
                }   
                
            }

        }//END INSTITUTION
        //START COOPERATIVE
        if(startsWith($accountUserId,"COOP")){

            $check_coop = mysqli_query($link, "SELECT * FROM cooperatives WHERE coopid = '$accountUserId'");
            $fetch_coop = mysqli_fetch_array($check_coop);
            $customerEmail = $fetch_coop['official_email'];

            $check_bvn = mysqli_query($link, "SELECT * FROM coop_members WHERE coopid = '$accountUserId'");
            $fetch_bvn = mysqli_fetch_array($check_bvn);
            $customerName = $fetch_bvn['fullname'];
            $userBvn = $fetch_bvn['bvn'];

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
                    //$myAccountReference = $mo_generate->responseBody->accountReference;
                    $myAccountName = $mo_generate->responseBody->accountName;
                    $myAccountNumber = $mo_generate->responseBody->accountNumber;
                    $myBankName = $mo_generate->responseBody->bankName;
                    $myStatus = $mo_generate->responseBody->status;
                    $date_time = $mo_generate->responseBody->createdOn;
                    mysqli_query($link, "UPDATE virtual_account SET account_name = '$myAccountName', account_number = '$myAccountNumber', bank_name = '$myBankName', status = '$myStatus', date_time = '$date_time' WHERE status = 'pending' AND userid = '$accountUserId'");
                }   
                
            }

        }//END COOPERATIVE
        //START CUSTOMER
        if(!(startsWith($accountUserId,"MER")) && !(startsWith($accountUserId,"INST")) && !(startsWith($accountUserId,"AGT")) && !(startsWith($accountUserId,"COOP"))){

            $check_bo = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$accountUserId'");
            $fetch_bo = mysqli_fetch_array($check_bo);
            $customerName = $fetch_bo['lname'].' '.$fetch_bo['fname'];
            $customerEmail = $fetch_bo['email'];
            $userBvn = "";

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
                    //$myAccountReference = $mo_generate->responseBody->accountReference;
                    $myAccountName = $mo_generate->responseBody->accountName;
                    $myAccountNumber = $mo_generate->responseBody->accountNumber;
                    $myBankName = $mo_generate->responseBody->bankName;
                    $myStatus = $mo_generate->responseBody->status;
                    $date_time = $mo_generate->responseBody->createdOn;
                    mysqli_query($link, "UPDATE virtual_account SET account_name = '$myAccountName', account_number = '$myAccountNumber', bank_name = '$myBankName', status = '$myStatus', date_time = '$date_time' WHERE status = 'pending' AND userid = '$accountUserId'");
                }   
                
            }

        }//END CUSTOMER

    }
    //GENERATE VIRTUAL ACCOUNT ON FLUTTERWAVE
    //include ("../config/rave_virtual_account.php");
  
}
else{
    exit(); //Forget this event ever happens
}
?>