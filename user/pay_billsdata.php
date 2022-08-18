<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<?php
$acn = $_SESSION['acctno'];
$search_trans = mysqli_query($link, "SELECT * FROM wallet_history WHERE userid = '$acn' AND status = 'pending'");
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
            $remainBalance = $bwallet_balance - $topup_amount;
            
            $update_records = mysqli_query($link, "UPDATE wallet_history SET status = 'successful', wallet_balance = '$bwallet_balance' WHERE refid = '$trans_id' AND status = 'pending' AND userid = '$acn'");
            $update_records = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$remainBalance' WHERE account = '$acn'");
            
        }else{
            
            //DO NOTHING
            
        }
        
    }
    
}else{
    
    //DO NOTHING
    
}
?>    
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Purchase Databundle
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>&&mid=NDAx"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>
	
    <section class="content">
    <?php
    if($idatabundle_route == "Rubies"){ //Gateway for Rubies
      include("include/pay_rubiesdata_data.php");
    }
    elseif($idatabundle_route == "PrimeAirtime"){ //Gateway for Prime Airtime
      include("include/pay_primedata_data.php");
    }
    else{ //Gateway for Estore
      include("include/pay_billsdata_data.php");
    }
    ?>
	</section>
</div>

<?php include("include/footer.php"); ?>