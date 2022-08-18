<?php
include("include/header.php");
require_once("../config/restful_apicalls.php");
require_once("../config/hmo_functions.php");
?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    
	<!-- Content Header (Page header) -->
    <section class="content-header">
<?php
if(isset($_GET['refid']))
{

  $response = array();
  $response2 = array();
  $systemset = mysqli_query($link, "SELECT * FROM systemset");
  $row1 = mysqli_fetch_object($systemset);
  $mysubacct_id = $row1->auth_subaccount;
  $targetSubacct_id = $row1->target_subaccount;
  $wUsername = $row1->wellahealth_clientid;
  $wPassword = $row1->wellahealth_clientsecretkey;
  $AgentCode = $row1->wellahealth_agentcode;
   
  $refid = $_GET['refid'];
  $new_plancode = $_GET['pcode'];
  $acctno = $_GET['acn'];
  $new_reference = "EA".date("dy").time();
  $planType = (isset($_GET['Takaful']) ? "Takaful" : (isset($_GET['Health']) ? "Health" : (isset($_GET['Donation']) ? "Donation" : (isset($_GET['Investment']) ? "Investment" : "Savings"))));

  $searchSub = mysqli_query($link, "SELECT * FROM savings_subscription WHERE new_plancode = '$new_plancode' AND status = 'Pending'");
  $fetchSub = mysqli_fetch_array($searchSub);
  $origin_plancode = $fetchSub['plan_code'];
  $planamount = $fetchSub['amount'];
  $mys_interval = $fetchSub['savings_interval'];
  $plancode = $origin_plancode;
  $amountpaid = $planamount;
  $plan_id = $fetchSub['plan_id'];

  $search_splan = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$origin_plancode'");
  $num_splan = mysqli_num_rows($search_splan);
  $fetch_splan = mysqli_fetch_object($search_splan);

  $search_splan1 = mysqli_query($link, "SELECT * FROM target_savings WHERE plan_code = '$origin_plancode'");
  $num_splan1 = mysqli_num_rows($search_splan1);
  $fetch_splan1 = mysqli_fetch_object($search_splan1);

  $plan_name = ($num_splan == 1 && $num_splan1 == 0) ? $fetch_splan->plan_name : $fetch_splan1->plan_name;
  $companyid = ($num_splan == 1 && $num_splan1 == 0) ? $fetch_splan->merchantid_others : $fetch_splan1->companyid;
  $plancurrency = ($num_splan == 1 && $num_splan1 == 0) ? $fetch_splan->currency : $fetch_splan1->currency;
  $categories = ($num_splan == 1 && $num_splan1 == 0) ? $fetch_splan->categories : $fetch_splan1->purpose;
  $duration = ($num_splan == 1 && $num_splan1 == 0) ? $fetch_splan->duration : $fetch_splan1->duration;
  $divi_type = ($num_splan == 1 && $num_splan1 == 0) ? $fetch_splan->dividend_type : $fetch_splan1->interestType;
  $dividend = ($num_splan == 1 && $num_splan1 == 0) ? $fetch_splan->dividend : $fetch_splan1->interestRate;
  $maturity_period = ($num_splan == 1 && $num_splan1 == 0) ? $fetch_splan->maturity_period : $fetch_splan1->savings_interval;
  $frequency = ($num_splan == 1 && $num_splan1 == 0) ? $fetch_splan->frequency : $fetch_splan1->duration;
  $vendorid = ($num_splan == 1 && $num_splan1 == 0) ? $fetch_splan->branchid : "";
  $apiPlanName = ($num_splan == 1 && $num_splan1 == 0) ? $fetch_splan->api_planname : "";
  $upfront_payment = ($num_splan == 1 && $num_splan1 == 0) ? $fetch_splan->upfront_payment : $fetch_splan1->upfront_payment;
  $todays_date = date('Y-m-d h:i:s');
  $converted_date = date('m/d/Y').' '.(date('h') + 1).':'.date('i a');
  $customer = $bvirtual_acctno;
  $myfullname = $myfn.' '.$myln;

  //Calculate Maturity Period
  $mymaturity_period = ($maturity_period == "weekly" ? 'week' : ($maturity_period == "monthly" ? 'month' : (($maturity_period == "yearly" || $maturity_period == "annually") ? 'year' : 'day')));
  $mature_date = ($maturity_period == "") ? date('Y-m-d h:i:s') : date('Y-m-d h:i:s', strtotime('+'.$frequency.' '.$mymaturity_period, strtotime($todays_date)));
  
  //Calculate Next Payment Date
  $savings_interval = ($mys_interval == "daily" ? 'day' : ($mys_interval == "weekly" ? 'week' : ($mys_interval == "monthly" ? 'month' : ($mys_interval == "yearly" ? 'year' : ''))));
  $next_pmt_date = ($mys_interval == "ONE-OFF") ? "None" : date('Y-m-d h:i:s', strtotime('+1 '.$savings_interval, strtotime($todays_date)));
  
  //Calculate Total Output Plus Dividend
  $total_output = $planamount * $duration;
  $calc_divi_plusTOutput = ($divi_type == "Flat Rate" && $dividend != "0" ? ($dividend + $total_output) : ($divi_type == "Flat Rate" && $dividend == "0" ? "" : ($divi_type == "Ratio" ? "Undefine" : ($divi_type == "Percentage" && $dividend != '0' ? (($dividend / 100) * $total_output) + $total_output : $dividend + $total_output))));
  $totalInterest = ($upfront_payment == "No") ? $calc_divi_plusTOutput : $total_output;

  //MERCHANT COMMISSION SETTINGS
  $merchant_ratio_type = $fetch_splan->commtype;
  $remaining_ratio = ($merchant_ratio_type == 'flat') ? ($planamount - $fetch_splan->commvalue) : (($fetch_splan->commvalue / 100) * 10);
  $merchant_ratio = ($merchant_ratio_type == 'flat') ? $fetch_splan->commvalue : (10 - $remaining_ratio);
  
  $search_myinst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$companyid'");
  $fetch_myinst = mysqli_fetch_array($search_myinst);
  $iofficial_email = $fetch_myinst['official_email'];

  $search_merchant = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$companyid' AND (role = 'agent_manager' OR role = 'institution_super_admin' OR role = 'merchant_super_admin')");
  $fetch_merchant = mysqli_fetch_array($search_merchant);
  $merchantIuid = $fetch_merchant['id'];
  $merchantBal = $fetch_merchant['transfer_balance'];
  
  $search_vendors = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
  $fetch_myvend_num = mysqli_num_rows($search_vendors);
  $fetch_vendors = mysqli_fetch_object($search_vendors);
  $withdrawal_status = $fetch_vendors->api_notification;
  $vendemail = $fetch_vendors->cemail;
 
  $select_rave = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$companyid'") or die (mysqli_error($link));
  $row_rave = mysqli_fetch_object($select_rave);
  $vsubaccount = ($sendSMS->startsWith($vendorid, "VEND") ? $fetch_splan->subAcctCode : ((!($sendSMS->startsWith($vendorid, "VEND"))) && $row_rave->tsavings_subacct != ""  ? $row_rave->tsavings_subacct : $targetSubacct_id));
  $rave_secret_key = ($row_rave->rave_status == "Enabled") ? $row_rave->rave_secret_key : $row1->secret_key;
  
  $search_myuser = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$companyid' AND branchid = '$vendorid'");
  $fetch_myuser = mysqli_fetch_array($search_myuser);
  $creatorEmail = ($fetch_myvend_num == 1) ? $iofficial_email.','.$vendemail.','.$email2 : $iofficial_email.','.$fetch_myuser['email'].','.$email2;

  $confirm_transaction = mysqli_query($link, "SELECT * FROM income WHERE icm_id = '$refid'");
  $confirm_num = mysqli_num_rows($confirm_transaction);

  $vendorBalance = ($fetch_myvend_num == 1) ? $fetch_vendors->wallet_balance : $merchantBal;
  $extractInterest = $calc_divi_plusTOutput - $total_output;
  $realMerchantBalance = ($upfront_payment == "No") ? $vendorBalance : ($vendorBalance - $extractInterest);

  $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'verify_transaction'");
  $fetch_restapi = mysqli_fetch_object($search_restapi);
  $api_url = $fetch_restapi->api_url;

  $data_array = array(
    "txref"   =>  $refid,
    "SECKEY"  =>  $rave_secret_key
  );

  $make_call = callAPI('POST', $api_url, json_encode($data_array));
  $response = json_decode($make_call, true);

    $txref = $response['data']['txref'];
    $amount = $response['data']['amount'];
    $date_time = date("Y-m-d");

    mysqli_query($link, "INSERT INTO income VALUES(null,'','$txref','Authorization Charges','$amount','$date_time','Product Tokenization charges')");
    
    if($extractInterest > $vendorBalance && $upfront_payment == "Yes"){

      echo "<script>alert('Opps!...Transaction not successful. Contact your Institution for more details'); </script>";
      echo "<script>window.location='my_savings_plan.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=".((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Health'])) ? 'MTAwMA==' : ((isset($_GET['Savings'])) ? 'NTAw' : (isset($_GET['Donation']) ? 'NjA0' : 'NDA3'))))."".((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : 'Investment'))))."'; </script>";

    }else{

      foreach($response['data']['card']['card_tokens'] as $key)
      
      $auth_code = $key['embedtoken'];
      
      $search_restapi2 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'charge_authorization'");
      $fetch_restapi2 = mysqli_fetch_object($search_restapi2);
      $api_url2 = $fetch_restapi2->api_url;
      
      $data_array2 = array(
        "SECKEY"  =>  $rave_secret_key,
        "token" => $auth_code,
        "currency" => $plancurrency,
        "amount" => $planamount,
        "email" => $email2,
        "first_name" => $myfn,
        "last_name" => $myln,
        "IP" => $_SERVER['REMOTE_ADDR'],
	    "narration" => "Payment for ".$fetch_splan->plan_name." / ".$bname." / ".$bvirtual_acctno,
        "txRef" =>  $new_reference,
        "payment_plan" => $plan_id,
        "subaccounts"=>array([
            "id"=> $vsubaccount,
            "transaction_split_ratio" => $remaining_ratio,
            "transaction_charge_type" => $merchant_ratio_type
            ],
            [
            "id"=> $mysubacct_id,
            "transaction_split_ratio" => $merchant_ratio,
            "transaction_charge_type" => $merchant_ratio_type
            ]),
        "meta"  => [
    	    "metaname"  => "Plan Name",
    	    "metavalue" => $fetch_splan->plan_name
	    ]
      );
      
      $make_call2 = callAPI('POST', $api_url2, json_encode($data_array2));
      $response2 = json_decode($make_call2, true);
      
      if($response['status'] == 'success' && $response2['data']['status'] == 'successful' && $confirm_num == 0){
          
          $real_status = $response2['data']['status'];
          $real_invoice_code = $response2['data']['id'];
          $customer_code = $response2['data']['customerId'];
          $paymenttype = $response2['data']['paymentType'];
          $mybank_name = "Card Payment";
          $account_number = ccMasking($bvirtual_acctno);
          $b_name = $bname;

          $Lga = "";
          $barea = "";
          
          ($withdrawal_status == "wellahealth_endpoint") ? $Notifier = wellaHealthSubNotifier($myfn,$myln,substr($phone2, -13),$email2,$baddrs,$bstate,$Lga,$barea,$baddrs,$planamount,($mys_interval == "ONE-OFF") ? "Monthly" : ucfirst($mys_interval),$apiPlanName,$bgender,$dateofbirth) : "";
          ($withdrawal_status == "wellahealth_endpoint") ? $decodeNotifier = json_decode(json_encode($Notifier), true) : "";
          ($withdrawal_status == "wellahealth_endpoint") ? $mysub_code = $decodeNotifier['subscriptionCode'] : "";
          
          $real_subscription_code = ($withdrawal_status == "wellahealth_endpoint") ? $mysub_code : "uSub".date("dy").time();
          
          //Email Notification Service
          $sendSMS->productPaymentNotifier($creatorEmail, $converted_date, $new_reference, $customer, $myfullname, $real_subscription_code, $plancode, $categories, $plan_name, $mybank_name, $account_number, $b_name, $plancurrency, $amountpaid, $emailConfigStatus, $fetch_emailConfig);
           
          mysqli_query($link, "UPDATE savings_subscription SET subscription_code = '$real_subscription_code', status = 'Approved', mature_date = '$mature_date', amt_plusInterest = '$calc_divi_plusTOutput', reference_no = '$new_reference', next_pmt_date = '$next_pmt_date' WHERE new_plancode = '$new_plancode' AND status = 'Pending'");

          $total_invbal = ($planType == "Savings") ? ($btargetsavings_bal + $planamount) : ($binvest_bal + $planamount);

          $actualInterest = ($calc_divi_plusTOutput == "Undefine") ? 0 : $extractInterest;
          $totalSenderBalance = $bwallet_balance + $actualInterest;
          ($planType != "Savings") ? mysqli_query($link, "UPDATE borrowers SET investment_bal = '$total_invbal' WHERE account = '$acctno'") or die ("Error1: " . mysqli_error($link)) : "";
          ($planType == "Savings") ? mysqli_query($link, "UPDATE borrowers SET target_savings_bal = '$total_invbal' WHERE account = '$acctno'") or die ("Error2: " . mysqli_error($link)) : "";
          ($planType == "Savings" && $num_splan1 == 1) ? mysqli_query($link, "UPDATE target_savings SET txid = '$real_invoice_code', status = 'Approved', mature_date = '$mature_date' WHERE plan_code = '$origin_plancode' AND status = 'Pending'") or die ("Error3: " . mysqli_error($link)) : "";
          ($upfront_payment == "Yes") ? mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$totalSenderBalance' WHERE account = '$acctno'") or die ("Error4: " . mysqli_error($link)) : "";
          ($upfront_payment == "Yes") ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$real_invoice_code','Subscription Upfront Interest','$extractInterest','','Credit','$plancurrency','SUB_INTEREST','Interest for the subscription of Product Name: $plan_name','successful','$todays_date','$acctno','$totalSenderBalance','')") or die ("Error8: " . mysqli_error($link)) : "";
          ($upfront_payment == "Yes") ? $sendSMS->walletCreditEmailNotifier($email2, $real_invoice_code, $todays_date, $binst_name, $bname, $bvirtual_acctno, $bbcurrency, $extractInterest, $totalSenderBalance, $emailConfigStatus, $fetch_emailConfig) : "";

          //UPDATE MERCHANT BALANCE
          ($upfront_payment == "Yes" && !($sendSMS->startsWith($vendorid, "VEND"))) ? mysqli_query($link, "UPDATE user SET transfer_balance = '$realMerchantBalance' WHERE id = '$merchantIuid'") : "";
          ($upfront_payment == "Yes" && !($sendSMS->startsWith($vendorid, "VEND"))) ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Subscription Upfront Interest','','$extractInterest','Debit','$plancurrency','SUB_INTEREST','Interest for the subscription of Product Name: $plan_name','successful','$todays_date','$merchantIuid','$realMerchantBalance','')") or die ("Error8: " . mysqli_error($link)) : "";

          //UPDATE MERCHANT BALANCE
          ($upfront_payment == "Yes" && ($sendSMS->startsWith($vendorid, "VEND"))) ? mysqli_query($link, "UPDATE vendor_reg SET wallet_balance = '$realMerchantBalance' WHERE companyid = '$vendorid'") : "";
          ($upfront_payment == "Yes" && ($sendSMS->startsWith($vendorid, "VEND"))) ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Subscription Upfront Interest','','$extractInterest','Debit','$plancurrency','SUB_INTEREST','Interest for the subscription of Product Name: $plan_name','successful','$todays_date','$vendorid','$realMerchantBalance','')") or die ("Error8: " . mysqli_error($link)) : "";

          mysqli_query($link, "INSERT INTO all_savingssub_transaction VALUES(null,'$companyid','$acctno','$real_invoice_code','$real_subscription_code','$new_reference','$plancurrency','$planamount','$real_status','$todays_date','$customer_code','$myfn','$myln','$auth_code','NONE','NONE','NONE','$paymenttype','NG','$origin_plancode','$vendorid','$bAcctOfficer')");
         
          echo "<script>alert('Plan Activated Successfully!'); </script>";
          echo "<script>window.location='my_savings_plan.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=".((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Health'])) ? 'MTAwMA==' : ((isset($_GET['Savings'])) ? 'NTAw' : (isset($_GET['Donation']) ? 'NjA0' : 'NDA3'))))."".((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : 'Investment'))))."'; </script>";

      }
      elseif($response2['status'] != 'success' && $confirm_num == 0){
          
          $mymsg = $response2['message'];
          echo $mymsg;

      }
      else{
          
          echo "<script>alert('Sorry, Your transaction is not valid!'); </script>";
          
      }

    }

}
?> 
      <h1>
        <?php echo ((isset($_GET['Takaful'])) ? 'Takaful' : ((isset($_GET['Health'])) ? 'Health' : ((isset($_GET['Savings'])) ? 'Savings' : ((isset($_GET['Donation'])) ? 'Donation' : 'Investment')))); ?> Subscription
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Plan</li>
      </ol>
    </section>
    <section class="content">
		<?php include("include/my_savings_plan_data.php"); ?>
	</section>
</div>	

<?php include("include/footer.php"); ?>