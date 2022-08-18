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
if(isset($_GET['refid']) == true)
{
  include ("../config/restful_apicalls.php");

  $systemset = mysqli_query($link, "SELECT * FROM systemset");
  $row1 = mysqli_fetch_object($systemset);
   
  $refid = $_GET['refid'];
  $plan_code = $_GET['pcode'];
  $acn = $_GET['acn'];

  $search_splan = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$plan_code'");
  $fetch_splan = mysqli_fetch_object($search_splan);
  $plan_id = $fetch_splan->plan_id;
  $companyid = $fetch_splan->merchantid_others;
  $subacct_id = $fetch_splan->subaccount_code;
  $planamount = $fetch_splan->amount;
  $plancurrency = $fetch_splan->currency;
  $categories = $fetch_splan->categories;

  $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'verify_transaction'");
  $fetch_restapi = mysqli_fetch_object($search_restapi);
  $api_url = $fetch_restapi->api_url;

  $data_array = array(
    "txref"   =>  $refid,
    "SECKEY"  =>  $row1->secret_key
  );

  $make_call = callAPI('POST', $api_url, json_encode($data_array));
  $response = json_decode($make_call, true);

  if($response['status'] == 'success'){

    $invoice_code       =   $response['data']['txid'];
    $subscription_code  =   $response['data']['orderref'];
    $txref              =   $response['data']['txref'];
    $currency           =   $response['data']['currency'];
    $amount             =   $response['data']['amount'];
    $status             =   $response['data']['status'];
    $customer_code      =   $response['data']['customerid'];
    $acctbusinessname   =   $response['data']['acctbusinessname'];
    $acctcontactperson  =   $response['data']['acctcontactperson'];
    $auth_code          =   $response['data']['card']['card_tokens']['embedtoken'];
    $paymenttype        =   $response['data']['paymenttype'];
    $cardBIN            =   $response['data']['card']['cardBIN'];
    $last4digits        =   $response['data']['card']['last4digits'];
    $cardtype           =   $response['data']['card']['type'];
    $acctcountry        =   $response['data']['acctcountry'];

    $insert_records = mysqli_query($link, "INSERT INTO savings_subscription VALUES(null,'$companyid','$categories','$plan_code','$subscription_code','$acn','Active',NOW())");

    $total_invbal = $binvest_bal + $amount;

    $update_records = mysqli_query($link, "UPDATE borrowers SET investment_bal = '$total_invbal' WHERE account = '$acn'");
    
    $insert_records = mysqli_query($link, "INSERT INTO all_savingssub_transaction VALUES(null,'$companyid','$acn','$invoice_code','$subscription_code','$txref','$currency','$amount','$status',NOW(),'$customer_code','$acctbusinessname','$acctcontactperson','$auth_code','$cardBIN','$last4digits','$cardtype','$paymenttype','$acctcountry','$plan_code')");

    echo "<script>alert('Transaction Confirmed Successfully!'); </script>";
  }
  else{
      echo "<script>alert('Sorry, Your transaction is not valid!'); </script>";
  }
}
?>

      <h1>
        Set Esusu Scheme (2 of 2 Steps)
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Set Savings Plan</li>
      </ol>
    </section>
	
    <section class="content">
		<?php include("include/finalize_savingssub_data.php"); ?>
	</section>
</div>

<?php include("include/footer.php"); ?>