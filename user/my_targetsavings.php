<?php include("include/header.php"); ?>
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
  include ("../config/restful_apicalls.php");
  
  $response = array();
  $response2 = array();
  $systemset = mysqli_query($link, "SELECT * FROM systemset");
  $row1 = mysqli_fetch_object($systemset);
   
  $refid = $_GET['refid'];
  $pid = $_GET['pid'];
  $acn = $_GET['acn'];
  $o_amt = base64_decode($_GET['o_amt']);
  $new_reference = "EA-targetSv-".myreference(15);

  $search_splan = mysqli_query($link, "SELECT * FROM target_savings WHERE plan_id = '$pid'");
  $fetch_splan = mysqli_fetch_object($search_splan);
  $plan_id = $fetch_splan->plan_id;
  $plan_code = $fetch_splan->plan_code;
  $planamount = $fetch_splan->amount;
  $plancurrency = $fetch_splan->currency;
  $categories = $fetch_splan->categories;
  $todays_date = date('Y-m-d h:m:s');
  $duration = $fetch_splan->duration;
  $dinterval = ($fetch_splan->savings_interval == "daily" ? 'days' : ($fetch_splan->savings_interval == "weekly" ? 'week' : ($fetch_splan->savings_interval == "monthly" ? 'month' : 'year')));
  $mature_date = date('Y-m-d h:m:s', strtotime('+'.$duration.' '.$dinterval, strtotime($todays_date)));
  $total_output = $planamount * $duration;
  
  $confirm_transaction = mysqli_query($link, "SELECT * FROM income WHERE icm_id = '$refid'");
  $confirm_num = mysqli_num_rows($confirm_transaction);
  
  $select_rave = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'") or die (mysqli_error($link));
  $row_rave = mysqli_fetch_object($select_rave);
  $target_subaccount = $row_rave->tsavings_subacct;
  
  //$rave_secret_key = ($s_num == 1 && $row_rave->rave_status == "Enabled" && $vendorid == "" ? $row_rave->rave_secret_key : ($v_num == 1 && $fetch_vendors->rave_status == "Enabled" && $vendorid != "" ? $fetch_vendors->rave_secret_key : $row1->secret_key));
  $rave_secret_key = $row_rave->rave_secret_key;

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
        "firstname" => $myfn,
        "lastname" => $myln,
        "IP" => $_SERVER['REMOTE_ADDR'],
	      "narration" => "Target Savings Payment",
        "txRef" =>  $new_reference,
        "payment_plan" => $plan_id,
        "subaccounts" => [
            "id" => $target_subaccount
            ],
        "meta"  => [
    	    "metaname"  => "Plan Name",
    	    "metavalue" => $fetch_splan->plan_name
	      ]
      );
      
      $make_call2 = callAPI('POST', $api_url2, json_encode($data_array2));
      $response2 = json_decode($make_call2, true);

      if($response['status'] == 'success' && $response2['status'] == 'success' && $confirm_num == 0){
          
        $date_time = date("Y-m-d");
      
        $real_status = $response2['data']['status'];
        $real_invoice_code = $response2['data']['txid'];
        $real_subscription_code = $response2['data']['orderref'];
        $customer_code = $response2['data']['customerid'];
        $acctbusinessname = $response2['data']['acctbusinessname'];
        $acctcontactperson = $response2['data']['acctcontactperson'];
        $paymenttype = $response2['data']['paymenttype'];
        $cardBIN = $response2['data']['card']['cardBIN'];
        $last4digits = $response2['data']['card']['last4digits'];
        $cardtype = $response2['data']['card']['brand'];
        $acctcountry = $response2['data']['acctcountry'];
        
        $total_invbal = $binvest_bal + $o_amt;
            
        $date_time = date("Y-m-d");
        
        mysqli_query($link, "UPDATE borrowers SET investment_bal = '$total_invbal' WHERE account = '$acn'");
        
        mysqli_query($link, "INSERT INTO income VALUES(null,'','$txref','Authorization Charges','$amount','$date_time','Investment Tokenization charges')");
        
        mysqli_query($link, "UPDATE target_savings SET txid = '$new_reference', status = 'Active', mature_date = '$mature_date' WHERE plan_id = '$pid'");
        
        mysqli_query($link, "INSERT INTO all_savingssub_transaction VALUES(null,'','$acn','$real_invoice_code','$real_subscription_code','$new_reference','$plancurrency','$o_amt','$real_status',NOW(),'$customer_code','$acctbusinessname','$acctcontactperson','$auth_code','$cardBIN','$last4digits','$cardtype','$paymenttype','$acctcountry','$plan_code','')");
    
        echo "<script>alert('Investment Activated Successfully!'); </script>";
        echo "<script>window.location='my_targetsavings.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=NDA3'; </script>";
      
      }
      elseif($response2['status'] != 'success' && $confirm_num == 0){
          
          $mymsg = $response2['message'];
          echo "<script>alert('$mymsg'); </script>";
          
      }
      else{
          echo "<script>alert('Sorry, Your transaction is not valid!'); </script>";
      }
}
?>
      <h1>
        All Target Savings
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Savings</li>
      </ol>
    </section>
    <section class="content">
		<?php include("include/my_targetsavings_data.php"); ?>
	</section>
</div>	

<?php include("include/footer.php"); ?>