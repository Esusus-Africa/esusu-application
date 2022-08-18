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
  $acn = $_SESSION['acctno'];
  $o_amt = base64_decode($_GET['o_amt']);

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
  $txref              =   $response['data']['txref'];
    $currency           =   $response['data']['currency'];
    $amount             =   $response['data']['amount'];
    $status             =   $response['data']['status'];
    $paymenttype        =   $response['data']['paymenttype'];
    $card_bank_details  =   ($paymenttype == "card") ? 'Card Token: '.$response['data']['card']['card_tokens']['embedtoken'].'<br>' : 'Account Token: '.$response['data']['account']['account_token']['token'].'<br>';
    $card_bank_details  .=  ($paymenttype == "card") ? 'Card BIN: '.$response['data']['card']['cardBIN'].'<br>' : 'Account No: '.$response['data']['account']['account_number'].'<br>';
    $card_bank_details  .=  ($paymenttype == "card") ? 'Last 4 Digit: '.$response['data']['card']['last4digits'].'<br>' : 'Bank Code: '.$response['data']['account']['account_bank'].'<br>';
    $card_bank_details  .=  ($paymenttype == "card") ? 'Card Type: '.$response['data']['card']['type'].'<br>' : 'Full Name: '.$response['data']['account']['first_name'].' '.$response['data']['account']['last_name'].'<br>';
    $card_bank_details  .=  'Account Contact Person: '.$response['data']['acctcontactperson'].'<br>';
    $card_bank_details  .=  'Account Country: '.$response['data']['acctcountry'];

  if(($chargeResponsecode == "00" || $chargeResponsecode == "0")){
    
    $confirm_wallet = mysqli_query($link, "SELECT * FROM wallet_history WHERE refid = '$txref'");
    if(mysqli_num_rows($confirm_wallet) == 1)
    {
        echo "<script>alert('Oops! You cannot confirm duplicate payment!!'); </script>";
       // echo "<div class='bg-orange'>Oops! You cannot confirm duplicate payment!!</div>";
        echo "<script>window.location='mywallet_history.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=NDA4'; </script>";
    }
    else{
        $total_bal = $bwallet_balance + $o_amt;
        
        $icm_id = "ICM".rand(100000,999999);
        
        $revenue = $amount - $o_amt;
        
        $icm_date = date("Y/m/d");

        $insert_records = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$bbranchid','$txref','self','$o_amt','$currency','$paymenttype','$card_bank_details','$status',NOW(),'$acn','$bwallet_balance')");
        $insert_records = mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','Revenue','$revenue','$icm_date','Wallet Funding Revenue')");
        $update_records = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$total_bal' WHERE account = '$acn'");
    
        echo "<script>alert('Transaction Confirmed Successfully!'); </script>";
        echo "<script>window.location='mywallet_history.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=NDA4'; </script>";
      }
  }
  else{
      $insert_records = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$acn','$txref','self','$o_amt','$currency','$paymenttype','$card_bank_details','$status',NOW(),'$acn','$bwallet_balance')");
      echo "<script>alert('Sorry, Your transaction is not valid!'); </script>";
      echo "<script>window.location='mywallet_history.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=NDA4'; </script>";
  }
}
?>
      <h1>
        My Wallet History
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Savings</li>
      </ol>
    </section>
    <section class="content">
		<?php include("include/mywallet_history_data.php"); ?>
	</section>
</div>	

<?php include("include/footer.php"); ?>

<script type="text/javascript" language="javascript">
 $(document).ready(function(){

  var dataTable = $('#fetch_wallet_transaction_data').DataTable({
   "processing" : true,
   "serverSide" : true,
   'serverMethod': 'post',
   "ajax" : {
    url:"fetch_wallet_transaction.php",
    'data': function(data){
        // Read values
        var mydfrom = $('#startDate').val();
        var mydto = $('#endDate').val();
        var myptype = $('#transType').val();

        // Append to data
        data.startDate = mydfrom;
        data.endDate = mydto;
        data.transType = myptype;
    }
   },
   dom: 'lBfrtip',
   buttons: [
    'excel','csv','pdf','copy'
   ],
   "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
  });
  
  $('#startDate').on('click', function () {
    dataTable.draw();
  });

  $('#endDate').on('click', function () {
    dataTable.draw();
  });
  
  $('#transType').change(function(){
    dataTable.draw();
  });
  
 });
 
</script>


<script type="text/javascript" language="javascript">
 $(document).ready(function(){

  var dataTable = $('#bank_transfer_transaction_data').DataTable({
   "processing" : true,
   "serverSide" : true,
   'serverMethod': 'post',
   "ajax" : {
    url:"fetch_transfer_transaction.php",
    'data': function(data){
        // Read values
        var mydfrom = $('#startDate').val();
        var mydto = $('#endDate').val();
        var myptype = $('#transType').val();

        // Append to data
        data.startDate = mydfrom;
        data.endDate = mydto;
        data.transType = myptype;
    }
   },
   dom: 'lBfrtip',
   buttons: [
    'copy', 'csv', 'excel', 'pdf', 'print'
   ],
   "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
  });
  
  $('#startDate').on('click', function () {
    dataTable.draw();
  });

  $('#endDate').on('click', function () {
    dataTable.draw();
  });
  
  $('#transType').change(function(){
    dataTable.draw();
  });
  
 });
 
</script>