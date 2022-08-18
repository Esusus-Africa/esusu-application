<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar1.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

<?php
if(isset($_GET['refid']) == true){

  $refid = $_GET['refid'];
  $sub_token = $_GET['token'];

  $search_sysstemset = mysqli_query($link, "SELECT * FROM systemset");
	$r = mysqli_fetch_object($search_sysstemset);

  $searchSub = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE sub_token = '$sub_token' AND coopid_instid = '$institution_id' AND refid = '' ORDER BY id DESC");
  $fetchSub = mysqli_fetch_array($searchSub);
  $date_from = $fetchSub['duration_from'];
  $expiry_date = $fetchSub['duration_to'];
  $sub_plan = $fetchSub['plan_code'];
  $total_amountpaid = $fetchSub['amount_paid'];
  $couponCode = $fetchSub['couponCode'];
  $originalAmt = ($couponCode == "") ? "" : base64_decode($_GET['subAmt']);

  $searchPlan = mysqli_query($link, "SELECT * FROM saas_subscription_plan WHERE plan_code = '$sub_plan'");
  $fetchPlan = mysqli_fetch_array($searchPlan);
  $plan_name = $fetchPlan['plan_name'];
  $myothers = $fetchPlan['others'];
  $parameter = (explode(',',$myothers));
  $countNum = count($parameter);

  //process email and aggregator commission
  $aggr_id = $fetch_inst['aggr_id'];
  $name = $fetch_inst['institution_name'];
  $instEmail = ($fetch_inst['official_email'] == "") ? "" : ",".$fetch_inst['official_email'];
  
  $search_aggr = mysqli_query($link, "SELECT * FROM aggregator WHERE aggr_id = '$aggr_id'");
  $checkAggr = mysqli_num_rows($search_aggr);
  $aggrEmail = ($checkAggr['email'] == "") ? "" : ",".$checkAggr['email'];
  $commType = $checkAggr['aggr_co_type'];
  $commRate = $checkAggr['aggr_co_rate'];
  $commValue = ($commType == "Percentage") ? (($commRate / 100) * $total_amountpaid) : $commRate;
  $walletBal = $checkAggr['wallet_balance'] + $commValue;
  $currenctdate = date("Y-m-d h:i:s");

  $emailReceiver = $r->email.$instEmail.$aggrEmail;
  $calc_bonus = ($couponCode == "") ? 0 : ($originalAmt - $total_amountpaid);
  $subType = "Saas Subscription";

  for($i = 0; $i < $countNum; $i++){
    
    mysqli_query($link, "UPDATE member_settings SET $parameter[$i] = 'On' WHERE companyid = '$institution_id'");

  }

  $sendSMS->saasSubPmtNotifier($emailReceiver, $refid, $date_from, $expiry_date, $plan_name, $sub_plan, $institution_id, $sub_token, $total_amountpaid, $name, $calc_bonus, $subType, $iemailConfigStatus, $ifetch_emailConfig);

  $update_activesub = mysqli_query($link, "UPDATE saas_subscription_trans SET usage_status = 'Deactivated' WHERE coopid_instid = '$institution_id' AND status = 'Paid'");
  $activate_sub = mysqli_query($link, "UPDATE saas_subscription_trans SET refid = '$refid', status = 'Paid', usage_status = 'Active' WHERE sub_token = '$sub_token'");
  ($aggr_id == "" || $commRate == "0") ? "" : mysqli_query($link, "UPDATE aggregator SET wallet_balance = '$walletBal' WHERE aggr_id = '$aggr_id'");
	($aggr_id == "" || $commRate == "0") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','Aggregator: $aggr_id','$commValue','','Credit','NGN','COMMISSION','Response: Esusu Africa Platform Commission of NGN$commValue on Subscription as Aggregator','successful','$currenctdate','$aggr_id','$walletBal','')");

  echo "<script>alert('Subscription Activated Successfully'); </script>";
  echo "<script>window.location='dashboard.php?id=".$_SESSION['tid']."&&mid=NDAx'; </script>";

}
?>



<!----------------------------------------SUBSCRIPTION PLAN UPGRADE----------------------------------------------->

<?php
if(isset($_GET['u_refid']) == true){
    
  include ("../config/restful_apicalls.php");

  $systemset = mysqli_query($link, "SELECT * FROM systemset");
  $row1 = mysqli_fetch_object($systemset);
  
  $refid = $_GET['u_refid'];
  $plcode = $_GET['plcode'];
  $u_amt = base64_decode($_GET['u_amt']);
  $icm_id = "ICM".rand(100000,999999);
  $sub_token = $_GET['token'];
  $mydate = date("Y-m-d");

  $searchSub = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE sub_token = '$sub_token' AND coopid_instid = '$institution_id' AND refid = '' ORDER BY id DESC");
  $fetchSub = mysqli_fetch_array($searchSub);
  $date_from = $fetchSub['duration_from'];
  $expiry_date = $fetchSub['duration_to'];
  $sub_plan = $fetchSub['plan_code'];
  $total_amountpaid = $fetchSub['amount_paid'];
  $couponCode = $fetchSub['couponCode'];
  $originalAmt = ($couponCode == "") ? "" : base64_decode($_GET['subAmt']);
  
  $detect_subplan = mysqli_query($link, "SELECT * FROM saas_subscription_plan WHERE plan_code = '$plcode'");
  $fetch_subplan = mysqli_fetch_array($detect_subplan);
  $staff_limit = $fetch_subplan['staff_limit'];
  $branch_limit = $fetch_subplan['branch_limit'];
  $customer_limit = $fetch_subplan['customers_limit'];
  $plan_name = $fetch_subplan['plan_name'];
  $myothers = $fetch_subplan['others'];
  $parameter = (explode(',',$myothers));
  $countNum = count($parameter);

  //process email and aggregator commission
  $aggr_id = $fetch_inst['aggr_id'];
  $name = $fetch_inst['institution_name'];
  $instEmail = ($fetch_inst['official_email'] == "") ? "" : ",".$fetch_inst['official_email'];
  
  $search_aggr = mysqli_query($link, "SELECT * FROM aggregator WHERE aggr_id = '$aggr_id'");
  $checkAggr = mysqli_num_rows($search_aggr);
  $aggrEmail = ($checkAggr['email'] == "") ? "" : ",".$checkAggr['email'];
  $commType = $checkAggr['aggr_co_type'];
  $commRate = $checkAggr['aggr_co_rate'];
  $commValue = ($commType == "Percentage") ? (($commRate / 100) * $total_amountpaid) : $commRate;
  $walletBal = $checkAggr['wallet_balance'] + $commValue;
  $currenctdate = date("Y-m-d h:i:s");

  $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'verify_transaction'");
  $fetch_restapi = mysqli_fetch_object($search_restapi);
  $api_url = $fetch_restapi->api_url;

  $data_array = array(
    "txref"   =>  $refid,
    "SECKEY"  =>  $row1->secret_key
  );

  $make_call = callAPI('POST', $api_url, json_encode($data_array));
  $response = json_decode($make_call, true);
  
  $chargeResponsecode = $response['data']['chargecode'];
  $paymenttype        =   $response['data']['paymenttype'];
  $card_bank_details  =   ($paymenttype == "card") ? 'Card Token: '.$response['data']['card']['card_tokens']['embedtoken'].'<br>' : 'Account Token: '.$response['data']['account']['account_token']['token'].'<br>';
  $card_bank_details  .=  ($paymenttype == "card") ? 'Card BIN: '.$response['data']['card']['cardBIN'].'<br>' : 'Account No: '.$response['data']['account']['account_number'].'<br>';
  $card_bank_details  .=  ($paymenttype == "card") ? 'Last 4 Digit: '.$response['data']['card']['last4digits'].'<br>' : 'Bank Code: '.$response['data']['account']['account_bank'].'<br>';
  $card_bank_details  .=  ($paymenttype == "card") ? 'Card Type: '.$response['data']['card']['type'].'<br>' : 'Full Name: '.$response['data']['account']['first_name'].' '.$response['data']['account']['last_name'].'<br>';
  $card_bank_details  .=  'Account Contact Person: '.$response['data']['acctcontactperson'].'<br>';
  $card_bank_details  .=  'Account Country: '.$response['data']['acctcountry'];
  $card_bank_details  .=  'Reference ID: '.$refid;
  
  if(($chargeResponsecode == "00" || $chargeResponsecode == "0")){

    $emailReceiver = $row1->email.$instEmail.$aggrEmail;
    $calc_bonus = ($couponCode == "") ? 0 : ($originalAmt - $total_amountpaid);
    $subType = "Saas Subscription Upgrade";

    for($i = 0; $i < $countNum; $i++){
    
      mysqli_query($link, "UPDATE member_settings SET $parameter[$i] = 'On' WHERE companyid = '$institution_id'");
  
    }

    $sendSMS->saasSubPmtNotifier($emailReceiver, $refid, $date_from, $expiry_date, $plan_name, $sub_plan, $institution_id, $sub_token, $total_amountpaid, $name, $calc_bonus, $subType, $iemailConfigStatus, $ifetch_emailConfig);
      
    $insert_records = mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','Plan Upgrade Fee','$u_amt','$mydate','$card_bank_details')");
    $update_activesub = mysqli_query($link, "UPDATE saas_subscription_trans SET usage_status = 'Deactivated' WHERE coopid_instid = '$institution_id' AND status = 'Paid'");
    $update_records = mysqli_query($link, "UPDATE saas_subscription_trans SET refid = '$refid', staff_limit = '$staff_limit', branch_limit = '$branch_limit', customer_limit = '$customer_limit' WHERE coopid_instid = '$institution_id'");
    ($aggr_id == "" || $commRate == "0") ? "" : mysqli_query($link, "UPDATE aggregator SET wallet_balance = '$walletBal' WHERE aggr_id = '$aggr_id'");
	  ($aggr_id == "" || $commRate == "0") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','Aggregator: $aggr_id','$commValue','','Credit','NGN','COMMISSION','Response: Esusu Africa Platform Commission of NGN$commValue on Subscription as Aggregator','successful','$currenctdate','$aggr_id','$walletBal','')");

    echo "<script>alert('Upgrade Done Successfully!'); </script>";
    echo "<script>window.location='dashboard.php?id=".$_SESSION['tid']."&&mid=NDAx'; </script>";
      
  }else{
      
      echo "<script>alert('Oops! Invalid Transaction!!'); </script>";
      
  }

}
?>

<?php
/**
if(isset($_GET['mo_refid']) == true){
  
  $curl = curl_init();
  
  $mo_refid = $_GET['mo_refid'];
  $sub_token = $_GET['token'];
  
  $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'mo_payment_status'");
  $fetch_restapi = mysqli_fetch_object($search_restapi);
  $api_url = $fetch_restapi->api_url;
  
  curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url.'?paymentReference='.$mo_refid,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => false,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Authorization: Basic TUtfUFJPRF9HSFpZR1o0U0hIOjJRTjJRVFBUU0hUS0hUVVFHWFVZVk1DNVFaVVNRR0RV"
      ],
    ));
    
  $response = curl_exec($curl);
  $err = curl_error($curl);
  
  if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      //echo $response;
      $transaction = json_decode($response);
      
  if($transaction->responseBody->paymentStatus == "PAID")
  {
    $search_refferal = mysqli_query($link, "SELECT * FROM referral_records WHERE downline_id = '$instid' AND pstatus = 'NotPaid'");
    if(mysqli_num_rows($search_refferal) == 1)
    {
      $fetch_referral = mysqli_fetch_array($search_refferal);
      $upline_id = $fetch_referral['upline_id'];
      $accttype = $fetch_referral['accttype'];
  
      $search_perc = mysqli_query($link, "SELECT * FROM compensation_plan ORDER BY id");
      $fetch_perc = mysqli_fetch_array($search_perc);
      $perc = ($fetch_perc['percentage'])/100;
  
      $amount_topay = $total_amountpaid * $perc;
  
      $search_agt = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved' AND institution_id = '$upline_id'");
      $fetch_agt = mysqli_fetch_array($search_agt);
      $iname = $fetch_agt['institution_name'];
  
      $insert = mysqli_query($link, "INSERT INTO referral_incomehistory VALUES(null,'$upline_id','$iname','$amount_topay','Pending',NOW())");
  
       $update_activesub = mysqli_query($link, "UPDATE saas_subscription_trans SET usage_status = 'Deactivated' WHERE coopid_instid = '$institution_id' AND status = 'Paid'");
  
      $activate_sub = mysqli_query($link, "UPDATE saas_subscription_trans SET refid = '$refid', status = 'Paid', usage_status = 'Active' WHERE sub_token = '$sub_token'");
      echo "<script>alert('Subscription Activated Successfully'); </script>";
      echo "<script>window.location='dashboard.php?id=".$_SESSION['tid']."&&mid=NDAx'; </script>";
    }
    else{
      $activate_sub = mysqli_query($link, "UPDATE saas_subscription_trans SET refid = '$refid', status = 'Paid', usage_status = 'Active' WHERE sub_token = '$sub_token'");
      echo "<script>alert('Subscription Activated Successfully'); </script>";
      echo "<script>window.location='dashboard.php?id=".$_SESSION['tid']."&&mid=NDAx'; </script>";
    }
  }
  else{
      echo "<script>alert('Oops! Invalid Transaction!!'); </script>";
  }

}
}
 */
?>
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        My Subscription History
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
		<li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">History</li>
      </ol>
    </section>
    <section class="content">
		<?php include("include/saassub_history_data.php"); ?>
	</section>
</div>	

<?php include("include/footer.php"); ?>