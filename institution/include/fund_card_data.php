<div class="row">	
<?php
$cardId = $_GET['cardId'];
$search_card = mysqli_query($link, "SELECT * FROM card_enrollment WHERE card_id = '$cardId'");
$fetch_card = mysqli_fetch_object($search_card);

function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 7) . str_repeat($maskingCharacter, strlen($number) -12) . substr($number, -5);
}
?>		
	 <section class="content">
		 
            <h3 class="panel-title"> <a href="list_card.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("550"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a></h3>
        
		   <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

		 <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="fund_card.php?id=<?php echo $_SESSION['tid']; ?>&&cardId=<?php echo $_GET['cardId']; ?>&&mid=<?php echo base64_encode("550"); ?>&&tab=tab_1">Fund Card</a></li>
              </ul>
             <div class="tab-content">
<?php
if(isset($_GET['tab']) == true)
{
	$tab = $_GET['tab'];
	if($tab == 'tab_1')
	{
	?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">
			 
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data" name="cardw">
             
            <div class="modal-dialog" id="printableArea">
          <!-- Modal content-->
      <div class="modal-content">
          
<?php
if(isset($_POST['save']))
{
	include("../config/restful_apicalls.php");

	$result = array();
	$card_id = mysqli_real_escape_string($link, $_GET['cardId']);
	$amount = mysqli_real_escape_string($link, $_POST['amount']);
	$debit_currency = mysqli_real_escape_string($link, $_POST['debit_currency']);
	$txid = "EA-CardFunding-".mt_rand(10000,99999);
	$cvc = mysqli_real_escape_string($link, $_POST['cvc']);

	$systemset = mysqli_query($link, "SELECT * FROM systemset");
	$get_sys = mysqli_fetch_array($systemset);
	$seckey = $get_sys['secret_key'];
	
	$verify_cvc = mysqli_query($link, "SELECT * FROM card_enrollment WHERE cvv = '$cvc'");
	$bal = $fetch_card->amount_prefund;

	$search_account = mysqli_query($link, "SELECT * FROM card_enrollment WHERE card_id = '$card_id'");
	$fetch_account = mysqli_fetch_object($search_account);
	$issuer_name = $fetch_account->api_used;
	$account = $fetch_account->acctno;

	$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$account'");
	$fetch_user = mysqli_fetch_object($search_user);
	$ph = $fetch_user->phone;
	$currency = $fetch_user->currency;
	$wallet_bal = $fetch_user->wallet_balance;
	$totalwallet_balance = $wallet_bal + $amount;
	
	if(mysqli_num_rows($verify_cvc) == 0){
		echo "<div class='alert bg-orange'><i class='fa fa-times'></i> Oops! Invalid CVV Entered!!</div>";
	}
	else{

	$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE issuer_name = '$issuer_name' AND api_name = 'fund-virtualcards'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;

	// Pass the parameter here
	$postdata =  array(
		"id"	=>	$card_id,
		"amount"=>	$amount,
		"debit_currency"=>	$debit_currency,
		"secret_key"	=>	$seckey
	);

	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);
	
	//var_dump($result);

	if($result['Status'] == "success")
	{
		$postedby = "<br><b>Posted by:<br>".$name."</b>";
		$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$txid','$account','$amount','$debit_currency','card-funding','$postedby','successful',NOW(),'$iuid','$iassigned_walletbal')");

		include("alert_sender/p2p_alert.php");
		echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p></div>";
	}
	else{
		echo "<div class='alert bg-orange'><i class='fa fa-times'></i> ".$result['Message']."</div>";
	}
}
}
?>         
        <div class="modal-body">
	
			<div class="demo-container">
			    <button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" align="left" disabled>&nbsp;<b>Wallet Balance: </b>&nbsp;
                        <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
                        <?php
                        echo $fetch_card->currency_type.number_format($fetch_card->amount_prefund,2,'.',',');
                        ?> 
                        </strong>
                    </button>
                <div class='card-wrapper'></div>
				<!-- CSS is included via this JavaScript file -->
				<script src="../dist/card.js"></script>
				<span style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">
				<div class="form-container active">
					    <input type="text" class="myinput" value="<?php echo ccMasking(chunk_split($fetch_card->cardpan, 4, ' ')); ?>" name="number"/readonly>
					    <input type="text" class="myinput" value="<?php echo $fetch_card->name_on_card; ?>" name="name"/readonly>
					    <input type="text" class="myinput" name="expiry" value="<?php echo date('m/Y', strtotime($fetch_card->expiration)); ?>" /readonly>
					    <input type="password" class="myinput" placeholder="cvv" maxlength="3" name="cvc"/required>
					    <input type="amount" class="myinput" placeholder="Enter Amount e.g 5000" name="amount"/required>
					    <select name="debit_currency"  class="myinput" required>
        					<option value="" selected>Select Debit Currency </option>
        					<option value="NGN">NGN</option>
        					<option value="USD">USD</option>
        				</select>
					    
				</div>
				<div align="center">
                  <div class="box-footer">
                    <button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-spinner">&nbsp;Fund Card</i></button>
                  </div>
			    </div>
				</span>
				
			
			</div>

<script>

var card = new Card({
    form: 'form',
    container: '.card-wrapper',

    placeholders: {
        number: '<?php echo ccMasking(chunk_split($fetch_card->cardpan, 4, ' ')); ?>',
        name: '<?php echo $fetch_card->name_on_card; ?>',
        expiry: '<?php echo date('m/Y', strtotime($fetch_card->expiration)); ?>',
        cvc: '***'
    }
});
</script>
			
        </div>
      </div>    
    </div>
			  
			 </form> 
			 
              </div>
              <!-- /.tab-pane -->
	<?php
	}
	}
	?>
  
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
			
          </div>					
			</div>
		
              </div>
	
</div>	
</div>
</div>
</section>	
</div>