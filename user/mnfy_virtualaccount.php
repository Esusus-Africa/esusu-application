<?php
  $detectAcct = $bdefaultAcct;
  $parameter = (explode(',',$detectAcct));
  $countNum = count($parameter);

  for($i = 0; $i < $countNum; $i++){
      
    $mydefaultbank = ($parameter[$i] == "Monnify" ? "monify" : ($parameter[$i] == "Rubies Bank" ? "rubies" : ($parameter[$i] == "Flutterwave" ? "rave" : ($parameter[$i] == "Payant" ? "payant" : ($parameter[$i] == "Providus Bank" ? "providus" : ($parameter[$i] == "Wema Bank" ? "wema" : ($parameter[$i] == "Sterling Bank" ? "sterling" : "None")))))));
  
    $search_vaccount = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$acctno' AND gateway_name = '$mydefaultbank' AND status = 'ACTIVE'");
  
    if(mysqli_num_rows($search_vaccount) == 0 && $mydefaultbank != "None"){
      
      $accountUserId = $acctno;
      $accountName = $bname;
      $currencyCode = $bbcurrency;
      $customerEmail = $email2;
      $customerName = $bname;
      $userBvn = $bbvn;
      $TxtReference = uniqid('ESFUND').time();
      $phoneNumber = $phone2;
      $country = $bcountry;
      $mydate_time = date("Y-m-d h:i:s");
      
      require_once '../config/virtualBankAccount_class.php';
      
      ($parameter[$i] == "Monnify") ? $result = $newVA->monnifyVirtualAccount($accountName,$currencyCode,$customerEmail,$customerName,$userBvn,$mo_contract_code) : "";
      ($parameter[$i] == "Rubies Bank") ? $result1 = $newVA->rubiesVirtualAccount($accountName,$rubbiesSecKey) : "";
      ($parameter[$i] == "Flutterwave") ? $result1 = $newVA->wemaVirtualAccount($accountName,$customerEmail,$TxtReference,$rave_secret_key) : "";
      ($parameter[$i] == "Payant") ? $result1 = $newVA->sterlingPayantVirtualAcct($accountName,$customerEmail,$phoneNumber,$currencyCode,$country,$payantEmail,$payantPwd,$payantOrgId) : "";
      ($parameter[$i] == "Providus Bank") ? $result1 = $newVA->providusVirtualAccount($accountName,$userBvn,$providusClientId,$providusClientSecret) : "";
      ($parameter[$i] == "Wema Bank") ? $result1 = $newVA->directWemaVirtualAccount($accountName,$wemaVAPrefix) : "";
      ($parameter[$i] == "Sterling Bank") ? $result1 = $newVA->sterlingVirtualAccount($myfn,$myln,$phoneNumber,$dateofbirth,$bgender,$currencyCode,$sterlinkInputKey,$sterlingIv) : "";

      ($parameter[$i] == "Monnify" ? $myAccountReference = $result->responseBody->accountReference : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountReference = "EAVA-".time() : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountReference = $TxtReference : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myAccountReference = $result1['data']['_id'] : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : "")))))));
      ($parameter[$i] == "Monnify" ? $myAccountName = $result->responseBody->accountName : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountName = $result1['virtualaccountname'] : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountName = $accountName : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myAccountName = $result1['data']['accountName'] : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountName = $result1['account_name'] : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountName = $result1['account_name'] : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $myAccountName = $result1['data']['lastname'].' '.$result1['data']['firstname'] : "")))))));
      ($parameter[$i] == "Monnify" ? $myAccountNumber = $result->responseBody->accountNumber : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountNumber = $result1['virtualaccount'] : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountNumber = $result1['data']['account_number'] : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myAccountNumber = $result1['data']['accountNumber'] : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountNumber = $result1['account_number'] : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountNumber = $wemaVAPrefix.$result1['account_number'] : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $myAccountNumber = $result1['data']['VIRTUALACCT'] : "")))))));
      ($parameter[$i] == "Monnify" ? $myBankName = $result->responseBody->bankName : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myBankName = $result1['bankname'] : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myBankName = $result1['data']['bank_name'] : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myBankName = "Sterling Bank" : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myBankName = "Providus Bank" : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $myBankName = "Wema Bank" : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $myBankName = "Sterling Bank" : "")))))));
      ($parameter[$i] == "Monnify" ? $myStatus = $result->responseBody->status : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myStatus = "ACTIVE" : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myStatus = "ACTIVE" : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myStatus = $result1['data']['status'] : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myStatus = "ACTIVE" : (($parameter[$i] == "Wema Bank") && $result1['responseCode'] == "00" ? $myStatus = "ACTIVE" : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $myStatus = "ACTIVE" : "")))))));
      ($parameter[$i] == "Monnify" ? $date_time = $result->responseBody->createdOn : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i]  == "Wema Bank") && $result1['responseCode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $date_time = date("Y-m-d h:i:s") : "")))))));
      ($parameter[$i] == "Monnify" ? $provider = "monify" : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $provider = "rubies" : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $provider = "rave" : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $provider = "payant" : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $provider = "providus" : (($parameter[$i] == "Sterling Bank") && $result1['response'] == "00" ? $provider = "sterling" : ""))))));

      ($myAccountReference != "" || $myAccountName != "" || $myAccountNumber != "" || $myBankName != "" || $myStatus != "") ? mysqli_query($link, "INSERT INTO virtual_account VALUE(null,'$myAccountReference','$accountUserId','$myAccountName','$myAccountNumber','$myBankName','$myStatus','$mydate_time','$provider','$bbranchid','Individual','Pending','$bAcctOfficer','1000000','100000','10000','5000')") : "";
      ($myAccountReference != "" || $myAccountName != "" || $myAccountNumber != "" || $myBankName != "" || $myStatus != "") ? mysqli_query($link, "UPDATE borrowers SET virtual_number = '$myAccountReference', virtual_acctno = '$myAccountNumber', bankname = '$myBankName' WHERE account = '$accountUserId'") : "";
    	    
      //$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
      //echo '<meta http-equiv="refresh" content="1;url='.$link.'">';
    
    }
    elseif($mydefaultbank == "None"){
        
        echo '<div class="mySlides">
              <address>
                    <table>
                      <tr>
                        <td height="30px"><b style="color: '.(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']).'; font-size: 12px;">NOTE: </b></td>
                        <td height="30px"><b style="color: '.(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']).'; font-size:14px;">&nbsp;No Virtual Account is available at the moment... Please check back later</b></td>
                      </tr>
                    </table>
              </address>
              </div>';
        
    }
    else{
        while($fetch_vaccount = mysqli_fetch_array($search_vaccount)){
      
        //MONIFY GATEWAY STATUS
        $mo_fund_status = $fetchsys_config['mo_status'];
        
        $my_gateway = $fetch_vaccount['gateway_name'];
        $bank_name = $fetch_vaccount['bank_name'];
        $account_number = $fetch_vaccount['account_number'];
        $account_name = $fetch_vaccount['account_name'];
        $account_reference = $fetch_vaccount['account_ref'];

        mysqli_query($link, "UPDATE borrowers SET virtual_number = '$account_reference', virtual_acctno = '$account_number', bankname = '$bank_name' WHERE account = '$acctno'");
?>
        <div class="mySlides">
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
        </div>
<?php
    }
  }

}
?>
<!-- Dots/bullets/indicators -->
    <div class="dot-container">
    <?php
    for($a = 0; $a < $countNum; $a++){
      $i = 1;
      $mydefaultbank2 = ($parameter[$a] == "Monnify" ? "monify" : ($parameter[$a] == "Rubies Bank" ? "rubies" : ($parameter[$a] == "Flutterwave" ? "wema" : ($parameter[$a] == "Payant" ? "sterling" : "None"))));
      $search_myva = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$acctno' AND gateway_name = '$mydefaultbank2' AND status = 'ACTIVE'");
      while($getnumrow = mysqli_fetch_array($search_myva)){
    ?>
        <span class="dot" onclick="currentSlide(<?php echo $i; ?>)"></span>
    <?php
        $i++;
      }
    }
    ?>
    </div>