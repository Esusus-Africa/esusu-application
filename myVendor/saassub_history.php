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

  $search_refferal = mysqli_query($link, "SELECT * FROM referral_records WHERE downline_id = '$vendorid' AND pstatus = 'NotPaid'");
  if(mysqli_num_rows($search_refferal) == 1)
  {
    $fetch_referral = mysqli_fetch_array($search_refferal);
    $upline_id = $fetch_referral['upline_id'];
    $accttype = $fetch_referral['accttype'];

    $search_perc = mysqli_query($link, "SELECT * FROM compensation_plan ORDER BY id");
    $fetch_perc = mysqli_fetch_array($search_perc);
    $perc = ($fetch_perc['percentage'])/100;

    $amount_topay = $total_amountpaid * $perc;

    $search_agt = mysqli_query($link, "SELECT * FROM vendor_reg WHERE status = 'Approved' AND companyid = '$upline_id'");
    $fetch_agt = mysqli_fetch_array($search_agt);
    $mo_name = $fetch_agt['cname'];

    $insert = mysqli_query($link, "INSERT INTO referral_incomehistory VALUES(null,'$upline_id','$mo_name','$amount_topay','Pending',NOW())");

     $update_activesub = mysqli_query($link, "UPDATE saas_subscription_trans SET usage_status = 'Deactivated' WHERE coopid_instid = '$vendorid' AND status = 'Paid'");

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
?>

<?php
if(isset($_GET['u_refid']) == true){
    
  include ("../config/restful_apicalls.php");
  
  $systemset = mysqli_query($link, "SELECT * FROM systemset");
  $row1 = mysqli_fetch_object($systemset);
  
  $u_refid = $_GET['u_refid'];
  $plcode = $_GET['plcode'];
  $u_amt = base64_decode($_GET['u_amt']);
  $icm_id = "ICM".rand(100000,999999);
  $mydate = date("Y-m-d");
  
  $detect_subplan = mysqli_query($link, "SELECT * FROM saas_subscription_plan WHERE plan_code = '$plcode'");
  $fetch_subplan = mysqli_fetch_array($detect_subplan);
  $staff_limit = $fetch_subplan['staff_limit'];
  $branch_limit = $fetch_subplan['branch_limit'];
  $customer_limit = $fetch_subplan['customers_limit'];

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
  $card_bank_details  .=  'Reference ID: '.$u_refid;
  
  if(($chargeResponsecode == "00" || $chargeResponsecode == "0")){
      
      $insert_records = mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','Plan Upgrade Fee','$u_amt','$mydate','$card_bank_details')");
      $update_records = mysqli_query($link, "UPDATE saas_subscription_trans SET refid = '$u_refid', staff_limit = '$staff_limit', branch_limit = '$branch_limit', customer_limit = '$customer_limit' WHERE coopid_instid = '$vendorid'");
    
      echo "<script>alert('Upgrade Done Successfully!'); </script>";
      
  }else{
      
      echo "<script>alert('Oops! Invalid Transaction!!'); </script>";
      
  }

}
?>

<?php
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
    $search_refferal = mysqli_query($link, "SELECT * FROM referral_records WHERE downline_id = '$vendorid' AND pstatus = 'NotPaid'");
    if(mysqli_num_rows($search_refferal) == 1)
    {
      $fetch_referral = mysqli_fetch_array($search_refferal);
      $upline_id = $fetch_referral['upline_id'];
      $accttype = $fetch_referral['accttype'];
  
      $search_perc = mysqli_query($link, "SELECT * FROM compensation_plan ORDER BY id");
      $fetch_perc = mysqli_fetch_array($search_perc);
      $perc = ($fetch_perc['percentage'])/100;
  
      $amount_topay = $total_amountpaid * $perc;
  
      $search_agt = mysqli_query($link, "SELECT * FROM vendor_reg WHERE status = 'Approved' AND companyid = '$upline_id'");
      $fetch_agt = mysqli_fetch_array($search_agt);
      $mo_name = $fetch_agt['cname'];
  
      $insert = mysqli_query($link, "INSERT INTO referral_incomehistory VALUES(null,'$upline_id','$mo_name','$amount_topay','Pending',NOW())");
  
       $update_activesub = mysqli_query($link, "UPDATE saas_subscription_trans SET usage_status = 'Deactivated' WHERE coopid_instid = '$vendorid' AND status = 'Paid'");
  
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