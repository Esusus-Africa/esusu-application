<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<?php
$search_trans = mysqli_query($link, "SELECT * FROM wallet_history WHERE userid = '$institution_id' AND status = 'pending'  AND initiator = '$iuid'");
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
            $remainBalance = $itransfer_balance - $topup_amount;
            
            $update_records = mysqli_query($link, "UPDATE wallet_history SET status = 'successful' WHERE refid = '$trans_id' AND status = 'pending' AND userid = '$institution_id' AND initiator = '$iuid'");
            $update_records = mysqli_query($link, "UPDATE user SET transfer_balance = '$remainBalance' WHERE id = '$iuid'");
            
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
       Purchase Airtime
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>&&mid=NDAx"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>
	
    <section class="content">
    <?php
    if($iairtime_route == "Rubies"){ //Gateway for Rubies
      include("include/pay_rubiesairtime_data.php");
    }
    elseif($iairtime_route == "PrimeAirtime"){ //Gateway for Prime Airtime
      include("include/pay_primeairtime_data.php");
    }
    else{ //Gateway for Estore
      include("include/pay_bills_data.php");
    }
    ?>
	</section>
</div>

<?php include("include/footer.php"); ?>