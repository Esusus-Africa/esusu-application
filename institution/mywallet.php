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
    $currectDateTime = date("Y-m-d h:i:s");

  if(($chargeResponsecode == "00" || $chargeResponsecode == "0")){

    $confirm_wallet = mysqli_query($link, "SELECT * FROM wallet_history WHERE refid = '$txref'");
    if(mysqli_num_rows($confirm_wallet) == 1)
    {
        echo "<script>alert('Oops! You cannot confirm duplicate payment!!'); </script>";
        echo "<script>window.location='mywallet.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
    }
    else{
        $total_bal = $itransfer_balance + $o_amt;

        $insert_records = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$txref','self','$o_amt','','Credit','$currency','$paymenttype','$card_bank_details','$status','$currectDateTime','$iuid','$total_bal','')");
        $update_records = mysqli_query($link, "UPDATE user SET transfer_balance = '$total_bal' WHERE id = '$iuid'");
    
        echo "<script>alert('Transaction Confirmed Successfully!'); </script>";
        echo "<script>window.location='mywallet.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
    }
  }
  else{
      //$insert_records = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$txref','self','$o_amt','','$currency','$paymenttype','$card_bank_details','$status','$currectDateTime','$iuid','','')");
      echo "<script>alert('Sorry, Your transaction is not valid!'); </script>";
      echo "<script>window.location='mywallet.php?id=".$_SESSION['tid']."&&mid=NDA0'; </script>";
  }
}
?>


<?php
$search_trans = mysqli_query($link, "SELECT * FROM wallet_history WHERE userid = '$institution_id' AND initiator = '$iuid' AND status = 'pending'");
if(mysqli_num_rows($search_trans) == 1) {
    
    while($get_trans = mysqli_fetch_object($search_trans)) {
        
        //LOOP THROUGH ALL TRANSACTION ON PENDING TO CHECK WITH THE SIGNAL ON E-STORE API AND UPDATE IF NECESSARY
        //CALL FOR E-STORE API TO VERIFY TRANSACTION STATUS
        $jsonData=array();
    	$search_billconfig = mysqli_query($link, "SELECT * FROM billpayment");
    	$fetch_billconfig = mysqli_fetch_object($search_billconfig);
    	
    	$url = 'https://estoresms.com/api_query'; //API Url (Do not change)
    	//$url = '$fetch_billconfig->api_url'; //API Url (Do not change)
        $token=$fetch_billconfig->token; //Replace with your API token
        $email=$fetch_billconfig->email;  //Replace with your account email address
        $username=$fetch_billconfig->username;  // Replace with your account username
        
        //Initiate cURL.
        $ch = curl_init($url);
        
        $jsonData=array();
        $jsonData['username']=$username;
        
        //Generate Hash
        $jsonData['hash']=hash('sha512',$token.$email.$username);
        
        //QUERY USING TRANSACTION ID
        $jsonData['transaction_id']=$get_trans->refid;
        
        //OR QUERY USING YOUR REF
        //$jsonData['ref']='myRef';
        
        //Encode the array into JSON.
        $jsonDataEncoded = json_encode($jsonData);
        
        //Send as a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);
        
        //Attach encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        
        //Set the content type as application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        
        //Allow parsing response to string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        //Execute the request
        $response=curl_exec($ch);
        
        $jsonData = json_decode($response, true);
        
        curl_close ($ch);
        
        if($jsonData['status'] == "Approved") {
            
            //UPDATE WALLET HISTORY STATUS ACCORDING TO E-STORE API RESPONSE
            $trans_id = $jsonData['transaction_id'];
            $commission = $jsonData['topup_amount'] * $fetchsys_config['bp_commission'];
            $topup_amount = $jsonData['topup_amount'] - $commission;
            $remainBalance = $cwallet_balance - $topup_amount;
            
            $update_records = mysqli_query($link, "UPDATE wallet_history SET status = 'successful' WHERE refid = '$trans_id' AND status = 'pending' AND userid = '$institution_id'");
            $update_records = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$remainBalance' WHERE institution_id = '$institution_id'");
            
        }else{
            
            //DO NOTHING
            
        }
        
    }
    
}else{
    
    //DO NOTHING
    
}
?>
      <h1>
       My Super Wallet
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">List</li>
      </ol>
    </section>
    <section class="content">
		<?php include("include/mywallet_data.php"); ?>
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
    'copy', 'csv', 'excel', 'pdf', 'print'
   ],
   "lengthMenu": [ [10, 25, 50, 100, 200, 300, 500, -1], [10, 25, 50, 100, 200, 300, 500, "All"] ]
  });
  
  $('#startDate').change(function() {
    dataTable.draw();
  });

  $('#endDate').change(function() {
    dataTable.draw();
  });
  
  $('#transType').change(function(){
    dataTable.draw();
  });
  
 });
 
</script>



<script type="text/javascript" language="javascript">
 $(document).ready(function(){

  var dataTable = $('#fetch_ussd_transaction_data').DataTable({
   "processing" : true,
   "serverSide" : true,
   'serverMethod': 'post',
   "ajax" : {
    url:"fetch_ussd_transaction.php",
    'data': function(data){
        // Read values
        var mdfrom = $('#myStartDate').val();
        var mdto = $('#myEndDate').val();

        // Append to data
        data.myStartDate = mdfrom;
        data.myEndDate = mdto;
    }
   },
   dom: 'lBfrtip',
   buttons: [
    'copy', 'csv', 'excel', 'pdf', 'print'
   ],
   "lengthMenu": [ [10, 25, 50, 100, 200, 300, 500, -1], [10, 25, 50, 100, 200, 300, 500, "All"] ]
  });

  $('#myStartDate').change(function() {
    dataTable.draw();
  });

  $('#myEndDate').change(function() {
    dataTable.draw();
  });
  
 });
 
</script>