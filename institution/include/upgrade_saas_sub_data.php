<div class="box">
         <div class="box-body">
      <div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-globe"></i> Upgrade Form</h3>
            </div>

             <div class="box-body">

          <div class="col-md-12">
     <div class="slideshow-container">
        <div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" style="font-size:17px;" align="center"><?php echo "<b>".strtoupper('Wallet Account Details')."</b>"; ?>: <i class='fa fa-hand-o-down'></i><br>

<?php
  $detectAcct = $idefaultAcct;
  $parameter = (explode(',',$detectAcct));
  $countNum = count($parameter);

  for($i = 0; $i < $countNum; $i++){
      
    $mydefaultbank = ($parameter[$i] == "Monnify" ? "monify" : ($parameter[$i] == "Rubies Bank" ? "rubies" : ($parameter[$i] == "Flutterwave" ? "wema" : ($parameter[$i] == "Payant" ? "sterling" : ($parameter[$i] == "Providus Bank" ? "providus" : "None")))));
  
    $search_vaccount = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$institution_id' AND gateway_name = '$mydefaultbank' AND status = 'ACTIVE'");
  
    if(mysqli_num_rows($search_vaccount) == 0 && $mydefaultbank != "None"){
      
      $accountUserId = $institution_id;
      $accountName = $inst_name;
      $currencyCode = $icurrency;
      $customerEmail = $inst_email;
      $customerName = $iname;
      $userBvn = $ibvn;
      $TxtReference = uniqid('ESFUND').time();
      $phoneNumber = $inst_phone;
      $country = "NG";
      $mydate_time = date("Y-m-d h:i:s");
      
      require_once '../config/virtualBankAccount_class.php';

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

      ($myAccountReference != "" || $myAccountName != "" || $myAccountNumber != "" || $myBankName != "" || $myStatus != "") ? mysqli_query($link, "INSERT INTO virtual_account VALUE(null,'$myAccountReference','$accountUserId','$myAccountName','$myAccountNumber','$myBankName','$myStatus','$mydate_time','$provider','','','Verified','','1000000','100000','10000','5000')") : "";
    
      $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
      echo '<meta http-equiv="refresh" content="1;url='.$link.'">';
      
    }
    elseif($mydefaultbank == "None"){
        
        echo '<div class="mySlides">';
        
        echo "[<b>No Virtual Account is available at the moment... Please check back later</b>]";
        
        echo '</div>';
        
    }
    else{
      while($fetch_vaccount = mysqli_fetch_array($search_vaccount)){
      
        //MONIFY GATEWAY STATUS
        $mo_fund_status = $fetchsys_config['mo_status'];
        
        $my_gateway = $fetch_vaccount['gateway_name'];
        $bank_name = $fetch_vaccount['bank_name'];
        $account_number = $fetch_vaccount['account_number'];
        $account_name = $fetch_vaccount['account_name'];
        
        echo '<div class="mySlides">';
        
        echo "[<b>".strtoupper($bank_name)."</b> - ACCOUNT NAME: <b>".strtoupper($account_name)."</b> - ACCOUNT NO: <b>".strtoupper($account_number)."</b>]";
        
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
      $search_myva = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$institution_id' AND gateway_name = '$mydefaultbank2' AND status = 'ACTIVE'");
      while($getnumrow = mysqli_fetch_array($search_myva)){
    ?>
        <span class="dot" onclick="currentSlide(<?php echo $i; ?>)"></span>
    <?php
        $i++;
      }
    }
    ?>
    </div>

            </div>
            
    <a class="myprev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="mynext" onclick="plusSlides(1)">&#10095;</a>
    
          </div>
        </div>
          <!-- /.col -->
              
          <form class="form-horizontal" method="post" enctype="multipart/form-data">

              <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Categories</label>
                  <div class="col-sm-9">
                        <select name="plancat" class="form-control select2" style="width:100%" id="upgrade_subplancat" required>
                              <option value="" selected="selected">--Select Plan Category--</option>
                              <option value="PreStarter">PreStarter</option>
                              <option value="Starter">Starter</option>
                              <option value="Standard">Standard</option>
                              <option value="Premium">Premium</option>
                        </select>
                  </div>
                  </div>

              <span id='ShowValueFrankPlanCat'></span>


         </form>

</div>  
</div>  
</div>
</div>