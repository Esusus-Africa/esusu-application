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
<?php
function startsWith($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}
?>

              <div class="box-body">

<?php
if(isset($_POST['UpgradePlan']))
{
  $curl = curl_init();
    
  $reference = uniqid("EA-UpgradePlan-").time();
  $plan_code = mysqli_real_escape_string($link, $_POST['plan_code']);
  $paymentMethod = mysqli_real_escape_string($link, $_POST['paymentMethod']);
  $amount = mysqli_real_escape_string($link, $_POST['amount_per_months']);
  $secure_amount = base64_encode($amount);
  $redirect_url = 'https://esusu.app/institution/saassub_history.php?tid='.$_SESSION['tid'].'&&u_amt='.$secure_amount.'&&plcode='.$plan_code.'&&u_refid='.$reference.'&&mid=NDIw';
  
  $systemset = mysqli_query($link, "SELECT * FROM systemset");
  $row1 = mysqli_fetch_object($systemset);
  $publickey = $row1->public_key;
  
  $localpayment_charges = $row1->localpayment_charges;
  $capped_amount = $row1->capped_amount;
  $intlpayment_charges = $row1->intlpayment_charges;
    
  $ip = $_SERVER['REMOTE_ADDR'];
  $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
  $country = $dataArray->geoplugin_countryName;
  $country_currencycode = $dataArray->geoplugin_currencyCode;
    
  $local_rate = $amount * $localpayment_charges;
  $intl_rate = ($amount * $intlpayment_charges) + $amount;
    
  $max_cap_amount = $amount + $capped_amount;
    
  $cal_charges = ($country != "Nigeria" || $country_currencycode != "NGN" ? $intl_rate : ($country == "Nigeria" && $local_rate >= $capped_amount ? $max_cap_amount : ($local_rate + $amount)));
  $mywalletBal = $iwallet_balance - $amount;
  $date_time = date("Y-m-d h:i:s");

  //process email and aggregator commission
  $aggr_id = $fetch_inst['aggr_id'];
  $name = $fetch_inst['institution_name'];
  $instEmail = ($fetch_inst['official_email'] == "") ? "" : ",".$fetch_inst['official_email'];
  
  $search_aggr = mysqli_query($link, "SELECT * FROM aggregator WHERE aggr_id = '$aggr_id'");
  $checkAggr = mysqli_num_rows($search_aggr);
  $aggrEmail = ($checkAggr['email'] == "") ? "" : ",".$checkAggr['email'];
  $commType = $checkAggr['aggr_co_type'];
  $commRate = $checkAggr['aggr_co_rate'];
  $commValue = ($commType == "Percentage") ? (($commRate / 100) * $amount) : $commRate;
  $walletBal = $checkAggr['wallet_balance'] + $commValue;

  $emailReceiver = $fetchsys_config['email'].$instEmail.$aggrEmail;

  if($paymentMethod == "wallet"){

    if($iwallet_balance < $amount){

      echo "<div class='alert bg-orange'>Sorry! You do not have sufficient fund in your super wallet!!</div>";

    }else{

      mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mywalletBal' WHERE institution_id = '$institution_id'");
      mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$reference','Upgrade to Plan: $plan_code','','$amount','Debit','$icurrency','Charges','SMS Content: Payment for upgrading the subscription plan to : $plan_code','successful','$date_time','$iuid','$mywalletBal','')");

      $detect_subplan = mysqli_query($link, "SELECT * FROM saas_subscription_plan WHERE plan_code = '$plan_code'");
      $fetch_subplan = mysqli_fetch_array($detect_subplan);
      $staff_limit = $fetch_subplan['staff_limit'];
      $branch_limit = $fetch_subplan['branch_limit'];
      $customer_limit = $fetch_subplan['customers_limit'];
      $others = $fetch_subplan['others'];
      $parameter = (explode(',',$others));
      $countNum = count($parameter);

      for($i = 0; $i < $countNum; $i++){

        mysqli_query($link, "UPDATE member_settings SET $parameter[$i] = 'On' WHERE companyid = '$institution_id'");

      }

      $emailReceiver = $row1->email.$instEmail.$aggrEmail;
      $calc_bonus = ($couponCode == "") ? 0 : ($originalAmt - $total_amountpaid);
      $subType = "Saas Subscription Upgrade";

      $sendSMS->saasSubPmtNotifier($emailReceiver, $reference, $date_from, $expiry_date, $plan_name, $sub_plan, $institution_id, $sub_token, $total_amountpaid, $name, $calc_bonus, $subType, $iemailConfigStatus, $ifetch_emailConfig);

      mysqli_query($link, "INSERT INTO income VALUES(null,'','$reference','Plan Upgrade Fee','$u_amt','$mydate','$card_bank_details')");
      mysqli_query($link, "UPDATE saas_subscription_trans SET refid = '$reference', staff_limit = '$staff_limit', branch_limit = '$branch_limit', customer_limit = '$customer_limit' WHERE coopid_instid = '$institution_id'");
    
      //Remit Aggregator Commission
      ($aggr_id == "" || $commRate == "0") ? "" : mysqli_query($link, "UPDATE aggregator SET wallet_balance = '$walletBal' WHERE aggr_id = '$aggr_id'");
      ($aggr_id == "" || $commRate == "0") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$reference','Aggregator: $aggr_id','$commValue','','Credit','NGN','COMMISSION','Response: Esusu Africa Platform Commission of NGN$commValue on Subscription as Aggregator','successful','$currenctdate','$aggr_id','$walletBal','')");

      echo "<div class='alert bg-blue'>Upgrade Done Successfully!</div>";

    }

  }else{

    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'standard_payment'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;
      
      curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
          'amount'=>$cal_charges,
          'customer_email'=>$inst_email,
          'currency'=>$icurrency,
          'txref'=>$reference,
          'PBFPubKey'=>$publickey,
          'redirect_url'=>$redirect_url
        ]),
        CURLOPT_HTTPHEADER => [
          "content-type: application/json",
          "cache-control: no-cache"
        ],
      ));
      
      $response = curl_exec($curl);
      $err = curl_error($curl);
      
      if($err){
        // there was an error contacting the rave API
        die('Curl returned error: ' . $err);
      }
      
      $transaction = json_decode($response);
      
      if(!$transaction->data && !$transaction->data->link){
        // there was an error from the API
        print_r('API returned error: ' . $transaction->message);
      }
      
      // uncomment out this line if you want to redirect the user to the payment page
      print_r($transaction->data->message);
      
      // redirect to page so User can pay
      // uncomment this line to allow the user redirect to the payment page
      //header('Location: ' . $transaction->data->link);
      echo "<script>window.location='".$transaction->data->link."'; </script>";

  }

}
?>

<div class="box-body">
<?php
$pcode = $_GET['pcode'];
$detect_subplan = mysqli_query($link, "SELECT * FROM saas_subscription_plan WHERE plan_code = '$pcode'");
$fetch_subplan = mysqli_fetch_array($detect_subplan);
?>

<input name="plan_code" type="hidden" class="form-control" value="<?php echo $fetch_subplan['plan_code']; ?>" id="HideValueFrank"/>

<input name="amount_per_months" type="hidden" class="form-control" value="<?php echo $fetch_subplan['amount_per_months']; ?>" id="HideValueFrank"/>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Name</label>
                  <div class="col-sm-9">
                  <span style="color: orange; font-size: 14px"><b><?php echo $fetch_subplan['plan_name'].' ('.$fetch_subplan['plan_code'].')'; ?></b></span>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount (per month)</label>
                  <div class="col-sm-9">
                  <span style="color: orange; font-size: 14px"><b><?php echo $icurrency.number_format($fetch_subplan['amount_per_months'],2,'.',','); ?></b></span>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">No. of Customers</label>
                  <div class="col-sm-9">
                 <span style="color: orange; font-size: 14px"><b><?php echo number_format($fetch_subplan['customers_limit']); ?></b></span>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">No. of Staffs</label>
                  <div class="col-sm-9">
                 <span style="color: orange; font-size: 14px"><b><?php echo number_format($fetch_subplan['staff_limit']); ?></b></span>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">No. of Branches</label>
                  <div class="col-sm-9">
                 <span style="color: orange; font-size: 14px"><b><?php echo number_format($fetch_subplan['branch_limit']); ?></b></span>
                  </div>
                  </div>

  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Other Features</label>
                  <div class="col-sm-9">
                      <?php
                        if($fetch_subplan['others'] == ""){
                            echo 'No Module Assigned!';
                        }
                        else{
                            $explodeVA = explode(",",$fetch_subplan['others']);
                            $countVA = (count($explodeVA) - 1);
                            for($i = 0; $i <= $countVA; $i++){
                                echo '<p style="color: orange; font-size: 14px"><b>'.$i.') '.ucwords(str_replace("_"," ",$explodeVA[$i])).'</b></p>';
                            }
                        }
                        ?>
                  </div>
                  </div>

<input name="dfrom" type="hidden" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly/>
<input name="dto" type="hidden" class="form-control" value="1" readonly/>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Payment Method</label>
                  <div class="col-sm-9">
                  <select name="paymentMethod" class="form-control select2" required>
                    <option value="" selected>Select Payment Method</option>
                    <option value="wallet">wallet</option>
                    <option value="card">card</option>
                  </select>
                  </div>
                  </div>


            </div>

          <div align="right">
            <div class="box-footer">
                <button name="UpgradePlan" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-mobile">&nbsp;Upgrade Now!</i></button>
            </div>
          </div>
        
         </form>

</div>  
</div>  
</div>
</div>