<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <a href="fund_wallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a>
            
<?php
if($transfer_fund == 1)
{
  ?>
 <button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" style="color: black;">&nbsp;<b>Balance:</b>&nbsp;
<strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
<?php
echo "<span id='wallet_balance'>".$vcurrency.number_format($vwallet_balance,2,'.',',')."</span>";
?> 
</strong>
  </button>
<?php
}
else{
  echo "";
}
?>
            
            </h3>
            </div>
<?php
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    
    $localpayment_charges = $row1->localpayment_charges;
    $capped_amount = $row1->capped_amount;
    $intlpayment_charges = $row1->intlpayment_charges;
?>
             <div class="box-body">
          
			 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_walletfund.php">
<hr>
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
<b style="font-size:18px;">DIRECT TRANSFER TO WALLET:</b> 
    <p>
      You can fund your wallet directly by making transfer to your <b> ACTIVE VIRTUAL ACCOUNT NUMBER</b> stated below:
    </p>
    <hr>
<?php
  $search_vaccount = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$vendorid' AND status = 'ACTIVE'");
  if(mysqli_num_rows($search_vaccount) == 0){
    
      include ("../config/restful_apicalls.php");
    
      $curl = curl_init();
      $curl1 = curl_init();
      $curl2 = curl_init();
      
      $accountUserId = $merchantid;
      $accountReference =  "EAVA-".myreference(10);
      $accountName = $mcname;
      $currencyCode = $mcurrency;
      $customerEmail = $mo_email;
      $customerName = $mcontact_person;
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
    
    <p></p>
    Please be informed that your information is secured as you fund your <b>SUPER WALLET</b> here.
    <br>
    <b style="font-size:18px;">NOTICE:</b> 
    <p>(1). Note that for Local Payments with Mastercard, Visa, Bank Account, USSD: the charges is <?php echo ($localpayment_charges * 100).'%'; ?> fees subject to cap of <?php echo $row1->currency.number_format($capped_amount,2,'.',','); ?>
    </p>
    <p>(2). Note that for International Card Payments with Mastercard, Visa, AMEX: the charges is <?php echo ($intlpayment_charges * 100).'%'; ?>
    </p>
</div>
</hr>
    <div class="box-body">

      <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Amount</label>
            <div class="col-sm-10">
              <input name="amount" type="text" class="form-control" placeholder="Enter Amount to be Fund" required>
            </div>
      </div>
			
	</div>

        <div align="right">
          <div class="box-footer">
              <button name="FundWallet" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-mobile">&nbsp;Fund Wallet</i></button>
          </div>
        </div>
			  
			 </form>

</div>	
</div>	
</div>
</div>