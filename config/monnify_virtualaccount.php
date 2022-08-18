<?php
  $detectAcct = ($iuid == "") ? $vdefaultAcct : $idefaultAcct;
  $parameter = (explode(',',$detectAcct));
  $countNum = count($parameter);

  for($i = 0; $i < $countNum; $i++){
      
    $mydefaultbank = ($parameter[$i] == "Monnify" ? "monify" : ($parameter[$i] == "Rubies Bank" ? "rubies" : ($parameter[$i] == "Flutterwave" ? "wema" : ($parameter[$i] == "Payant" ? "sterling" : ($parameter[$i] == "Providus Bank" ? "providus" : "None")))));
  
    $search_vaccount = mysqli_query($link, "SELECT * FROM virtual_account WHERE (userid = '$iuid' OR userid = '$vuid') AND gateway_name = '$mydefaultbank' AND status = 'ACTIVE'");
  
    if(mysqli_num_rows($search_vaccount) == 0 && $mydefaultbank != "None"){
      
        $companyid = ($iuid == "") ? $vcreated_by : $institution_id;
        $accountUserId = ($iuid == "") ? $vuid : $iuid;
        $accountName = ($iuid == "" ? $vc_name : (($irole === "agent_manager" || $irole === "institution_super_admin" || $irole === "merchant_super_admin") ? $inst_name : ($businessName == "" ? $iname : $businessName)));
        $currencyCode = ($iuid == "") ? $vcurrency : $icurrency;
        $customerEmail = ($iuid == "") ? $vo_email : $myiemail_addrs;
        $customerName = ($iuid == "" ? $vc_name : (($irole === "agent_manager" || $irole === "institution_super_admin" || $irole === "merchant_super_admin") ? $inst_name : ($businessName == "" ? $iname : $businessName)));
        $userBvn = $ibvn;
        $type = ($iuid == "") ? "vendor" : "agent";
        $TxtReference = uniqid('ESFUND').time();
        $phoneNumber = ($iuid == "") ? $vo_phone : $myiphone;
        $country = "NG";
        $mydate_time = date("Y-m-d h:i:s");
        
        require_once 'virtualBankAccount_class.php';

        ($parameter[$i] == "Monnify") ? $result = $newVA->monnifyVirtualAccount($accountName,$currencyCode,$customerEmail,$customerName,$userBvn,$mo_contract_code) : "";
        ($parameter[$i] == "Rubies Bank") ? $result1 = $newVA->rubiesVirtualAccount($accountName,$rubbiesSecKey) : "";
        ($parameter[$i] == "Flutterwave") ? $result1 = $newVA->wemaVirtualAccount($accountName,$customerEmail,$TxtReference,$rave_secret_key) : "";
        ($parameter[$i] == "Payant") ? $result1 = $newVA->sterlingPayantVirtualAcct($accountName,$customerEmail,$phoneNumber,$currencyCode,$country,$payantEmail,$payantPwd,$payantOrgId) : "";
        ($parameter[$i] == "Providus Bank") ? $result1 = $newVA->providusVirtualAccount($accountName,$userBvn,$providusClientId,$providusClientSecret) : "";

        ($parameter[$i] == "Monnify" ? $myAccountReference = $result->responseBody->accountReference : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountReference = "EAVA-".time() : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountReference = $TxtReference : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myAccountReference = $result1['data']['_id'] : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : "")))));
        ($parameter[$i] == "Monnify" ? $myAccountName = $result->responseBody->accountName : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountName = $result1['virtualaccountname'] : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountName = $accountName : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myAccountName = $result1['data']['accountName'] : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountName = $result1['account_name'] : "")))));
        ($parameter[$i] == "Monnify" ? $myAccountNumber = $result->responseBody->accountNumber : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountNumber = $result1['virtualaccount'] : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountNumber = $result1['data']['account_number'] : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myAccountNumber = $result1['data']['accountNumber'] : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountNumber = $result1['account_number'] : "")))));
        ($parameter[$i] == "Monnify" ? $myBankName = $result->responseBody->bankName : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myBankName = $result1['bankname'] : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myBankName = $result1['data']['bank_name'] : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myBankName = "Sterling Bank" : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myBankName = "Providus Bank" : "")))));
        ($parameter[$i] == "Monnify" ? $myStatus = $result->responseBody->status : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $myStatus = "ACTIVE" : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myStatus = "ACTIVE" : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $myStatus = $result1['data']['status'] : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $myStatus = "ACTIVE" : "")))));
        ($parameter[$i] == "Monnify" ? $date_time = $result->responseBody->createdOn : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $date_time = date("Y-m-d h:i:s") : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $date_time = date("Y-m-d h:i:s") : "")))));
        ($parameter[$i] == "Monnify" ? $provider = "monify" : (($parameter[$i] == "Rubies Bank") && $result1['responsecode'] == "00" ? $provider = "rubies" : (($parameter[$i] == "Flutterwave") && $result1['data']['response_code'] == "02" ? $provider = "wema" : (($parameter[$i] == "Payant") && $result1['statusCode'] == "200" ? $provider = "sterling" : (($parameter[$i] == "Providus Bank") && $result1['responseCode'] == "00" ? $provider = "providus" : "")))));
        
        ($myAccountReference != "" || $myAccountName != "" || $myAccountNumber != "" || $myBankName != "" || $myStatus != "") ? mysqli_query($link, "INSERT INTO virtual_account VALUE(null,'$myAccountReference','$accountUserId','$myAccountName','$myAccountNumber','$myBankName','$myStatus','$mydate_time','$provider','$companyid','$type','Pending','$iAcctOfficer','1000000','100000','10000','5000')") : "";
        ($myAccountReference != "" || $myAccountName != "" || $myAccountNumber != "" || $myBankName != "" || $myStatus != "") ? mysqli_query($link, "UPDATE user SET virtual_number = '$myAccountReference', virtual_acctno = '$myAccountNumber', bankname = '$myBankName' WHERE id = '$accountUserId'") : "";

        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
        //echo '<meta http-equiv="refresh" content="1;url='.$link.'">';
    
    }
    elseif($mydefaultbank == "None"){
      
      echo '<div class="mySlides">';
      
      echo "[<b>No Virtual Account is available at the moment... Please check back later</b>]";
      
      echo '</div>';
      
    }
    else{
        while($fetch_vaccount = mysqli_fetch_array($search_vaccount)){
        
        //MONIFY GATEWAY STATUS
        $accountUserId = ($iuid == "") ? $vuid : $iuid;
        $mo_fund_status = $fetchsys_config['mo_status'];
        
        $my_gateway = $fetch_vaccount['gateway_name'];
        $bank_name = $fetch_vaccount['bank_name'];
        $account_number = $fetch_vaccount['account_number'];
        $account_name = $fetch_vaccount['account_name'];
        $account_reference = $fetch_vaccount['account_ref'];

        mysqli_query($link, "UPDATE user SET virtual_number = '$account_reference', virtual_acctno = '$account_number', bankname = '$bank_name' WHERE id = '$accountUserId'");
            
        echo '<div class="mySlides">';
        
        echo "<b>".strtoupper('Transfer Account Details')."</b>: <i class='fa fa-hand-o-down'></i><br>[<b>".strtoupper($bank_name)."</b> | ACCOUNT NAME: <b>".strtoupper($account_name)."</b> | ACCOUNT NO: <b>".strtoupper($account_number)."</b>]";
        
        echo '</div>';
        
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
        $search_myva = mysqli_query($link, "SELECT * FROM virtual_account WHERE (userid = '$iuid' OR userid = '$vuid') AND gateway_name = '$mydefaultbank2' AND status = 'ACTIVE'");
        while($getnumrow = mysqli_fetch_array($search_myva)){
    ?>
            <span class="dot" onclick="currentSlide(<?php echo $i; ?>)"></span>
    <?php
            $i++;
        }
    }
    ?>
    </div>