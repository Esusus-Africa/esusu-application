<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-globe"></i>  Subscription Form</h3>
            </div>

             <div class="box-body">
              
<form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_saassub.php">

<div class="box-body">

  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Subscription Plan</label>
                  <div class="col-sm-9">
                  <select name="saas_subplan" class="form-control select2" id="saas_subplan" required>
                  <option selected>Select Subscription Plan</option>
<?php
$search_subplan = mysqli_query($link, "SELECT DISTINCT(plan_category) FROM saas_subscription_plan WHERE plan_category = 'Vendor' AND status = 'Active'");
while($fetch_saasplan = mysqli_fetch_array($search_subplan)){
?>
                   <option value="<?php echo $fetch_saasplan['plan_category']; ?>"><?php echo $fetch_saasplan['plan_category']; ?></option>
<?php } ?>
                  </select>
                  </div>
                  </div>

                  <span id='ShowValueFrank'></span>
                  <span id='ShowValueFrank'></span>
                  <span id='ShowValueFrank'></span>
                  <span id='ShowValueFrank'></span>
                  <span id='ShowValueFrank'></span>


</div>
<hr>

 </form>

</div>	
</div>	
</div>
</div>

<div class="box box-info">
      <div class="box-body">
    <b style="font-size:18px;">DIRECT TRANSFER TO WALLET:</b> 
    <p>
      You can fund your wallet directly by making transfer to your <b> ACTIVE VIRTUAL ACCOUNT NUMBER</b> stated below with <b>N50 Stamp Duty</b>:
    </p>
    <hr>
<?php
  $search_vaccount = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$vendorid' AND status = 'ACTIVE'");
  if(mysqli_num_rows($search_vaccount) == 0){
    
      include ("../config/restful_apicalls.php");
    
      $curl = curl_init();
      $curl1 = curl_init();
      $curl2 = curl_init();
      
      $accountUserId = $vendorid;
      $accountReference =  "EAVA-".myreference(10);
      $accountName = $vc_name;
      $currencyCode = $vcurrency;
      $customerEmail = $vo_email;
      $customerName = $vc_name;
      $userBvn = "";
      
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
      require_once("../config/mo_virtual_account.php");
      
      //GENERATE VIRTUAL ACCOUNT ON FLUTTERWAVE
      //include ("../config/rave_virtual_account.php");
    
  }
  else{
    while($fetch_vaccount = mysqli_fetch_array($search_vaccount)){
    
      //MONIFY GATEWAY STATUS
      $mo_fund_status = $fetchsys_config['mo_status'];
      
      $my_gateway = $fetch_vaccount['gateway_name'];
      $bank_name = $fetch_vaccount['bank_name'];
      $account_number = $fetch_vaccount['account_number'];
      $account_name = $fetch_vaccount['account_name'];
      
      echo "<p><b>Bank Name:</b> ".strtoupper($bank_name)." ".(($mo_fund_status == 'NotActive' && $my_gateway == 'rave') ? '<span class="btn bg-black"><b>(ACTIVE)</b></span>' : '<span class="btn bg-black"><b>(ACTIVE)</b></span>')."</p>";
      echo "<p><b>Account Name:</b> ".strtoupper($account_name)."</p>";
      echo "<p><b>Account Number:</b> ".strtoupper($account_number)."</p><hr>";
      
    }
  }
?>
<h5><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Note: Kindly indicate your <span style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Vendor ID & Plan Code </span> as your subscription plan will be activated upon payment confirmation via bank transfer option.</b></h4>
</div>
</div>