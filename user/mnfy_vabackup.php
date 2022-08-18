<?php
  $search_vaccount = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$acctno' AND status = 'ACTIVE' AND gateway_name = 'monify'");
  if(mysqli_num_rows($search_vaccount) == 0){
       
      $curl = curl_init();
      $curl2 = curl_init();
      
      $accountUserId = $acctno;
      $accountReference =  "EAVA-".date('dy').time();
      $accountName = $bname;
      $currencyCode = $bbcurrency;
      $customerEmail = $email2;
      $customerName = $bname;
      $userBvn = $bbvn;
      
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
      $mo_generate = json_decode($mo_response);
          
      //var_dump($mo_generate);
          
      if(!$mo_generate->requestSuccessful == true && !$mo_generate->responseMessage == 'success'){
          
          // there was an error from the API
          print_r('API returned error: ' . $mo_generate->responseMessage);

      }
      else{
          $myAccountReference = $mo_generate->responseBody->accountReference;
          $myAccountName = $mo_generate->responseBody->accountName;
          $myAccountNumber = $mo_generate->responseBody->accountNumber;
          $myBankName = $mo_generate->responseBody->bankName;
          $myStatus = $mo_generate->responseBody->status;
          $date_time = $mo_generate->responseBody->createdOn;
          mysqli_query($link, "INSERT INTO virtual_account VALUE(null,'$myAccountReference','$accountUserId','$myAccountName','$myAccountNumber','$myBankName','$myStatus','$date_time','monify')");
          mysqli_query($link, "UPDATE borrowers SET virtual_number = '$myAccountReference', virtual_acctno = '$myAccountNumber' WHERE account = '$accountUserId'");
    	    
    	    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
          echo '<meta http-equiv="refresh" content="1;url='.$link.'">';
            
      }
    
  }
  else{
      $fetch_vaccount = mysqli_fetch_array($search_vaccount);
    
      //MONIFY GATEWAY STATUS
      $mo_fund_status = $fetchsys_config['mo_status'];
      
      $my_gateway = $fetch_vaccount['gateway_name'];
      $bank_name = $fetch_vaccount['bank_name'];
      $account_number = $fetch_vaccount['account_number'];
      $account_name = $fetch_vaccount['account_name'];
?>
    <address>
          <table>
            <tr>
              <td height="30px"><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>; font-size: 12px;">Bank Name: </b></td>
              <td height="30px"><b style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>; font-size:14px;">&nbsp;<?php echo strtoupper($bank_name) ?></b></td>
            </tr>
            <tr>
              <td height="30px"><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>; font-size: 12px;">Account Name: </b></td>
              <td height="30px"><b style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>; font-size:13px;">&nbsp;<?php echo strtoupper($account_name); ?> </b></td>
            </tr>
            <tr>
              <td height="30px"><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>; font-size: 12px;">Account No.: </b></td>
              <td height="30px"><b style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>; font-size:20px;">&nbsp;<?php echo $account_number; ?></b></td>
            </tr>
          </table>
    </address>
<?php
}
?>