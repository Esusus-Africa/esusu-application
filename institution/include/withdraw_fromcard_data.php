<div class="box">
<?php
$cardId = $_GET['cardId'];
$search_card = mysqli_query($link, "SELECT * FROM card_enrollment WHERE card_id = '$cardId'");
$fetch_card = mysqli_fetch_object($search_card);

function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 7) . str_repeat($maskingCharacter, strlen($number) -12) . substr($number, -5);
}
?>
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-money"></i> Make Withdrawal via Card 
            </h3>
            </div>
             <div class="box-body">
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data" name="cardw">


<div class="modal-dialog" id="printableArea">
          <!-- Modal content-->
      <div class="modal-content">
          <?php
if(isset($_POST['save']))
{
	include("../config/restful_apicalls.php");

	$result = array();
	$cvc = mysqli_real_escape_string($link, $_POST['cvc']);
	$amt = mysqli_real_escape_string($link, $_POST['amount']);
	$pin = mysqli_real_escape_string($link, $_POST['pin']);
	$token = mysqli_real_escape_string($link, $_POST['token']);
	$txid = "EA-cardWithdrawal-".mt_rand(100000,999999);
	$acctno = $fetch_card->acctno;
	$card_currency = $fetch_card->currency_type;
	$phone = $fetch_card->billing_phone;

	$verify_cvc = mysqli_query($link, "SELECT * FROM card_enrollment WHERE cvv = '$cvc'");
	$bal = $fetch_card->amount_prefund;

	$charges = mysqli_real_escape_string($link, $_POST['charges']);
	$verify_charges = mysqli_query($link, "SELECT * FROM charge_management WHERE id = '$charges'");
	$fetch_verification = mysqli_fetch_object($verify_charges);
	$ctype = $fetch_verification->charges_type;
	$cvalue = $fetch_verification->charges_value;

	$postedby = "<br><b>Posted by:<br>".$name."</b>";

	//Do Percentage Calculation
	$percent = ($cvalue/100)*$amt;

	$final_charges = ($ctype == "Flatrate") ? $cvalue : $percent;

	if(mysqli_num_rows($verify_cvc) == 0){
		echo "<div class='alert bg-orange'><i class='fa fa-times'></i> Oops! Invalid CVV Entered!!</div>";
	}elseif($bal < $amt){
		echo "<div class='alert bg-orange'><i class='fa fa-times'></i> Insufficient Fund in Customer Wallet!!!</div>";
	}elseif($bal < ($amt + $final_charges))
	{
		echo "<div class='alert bg-orange'><i class='fa fa-times'></i> Insufficient Fund in Customer Wallet!!!...</div>";
	}elseif($charges != "" && $pin != "" && $token == ""){
		
		$systemset = mysqli_query($link, "SELECT * FROM systemset");
		$get_sys = mysqli_fetch_array($systemset);
		$sys_abb = $get_sys['abb'];
		$seckey = $get_sys['secret_key'];

		$search_account = mysqli_query($link, "SELECT * FROM card_enrollment WHERE pin = '$pin'");
		$fetch_account = mysqli_fetch_object($search_account);
		$issuer_name = $fetch_account->api_used;
		$account = $fetch_account->acctno;

		if(mysqli_num_rows($search_account) == 1){
			$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE issuer_name = '$issuer_name' AND api_name = 'withdraw_fromcard'");
			$fetch_restapi = mysqli_fetch_object($search_restapi);
			$api_url = $fetch_restapi->api_url;

			// Pass the parameter here
			$postdata =  array(
				"id"	=>	$cardId,
				"amount"=>	$amt,
				"secret_key"	=>	$seckey
			);

			$make_call = callAPI('POST', $api_url, json_encode($postdata));
			$result = json_decode($make_call, true);

			if($result['Status'] == 'success')
			{
				$total = number_format(($bal - $amt - $final_charges),2,'.','');
				$semi_total = number_format(($bal - $amt),2,'.','');
				$mycurrentTime = date("Y-m-d h:i:s");
				$today = date("Y-m-d");
				$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$txid','$acctno','','$final_charges','Debit','$card_currency','Card-WCharges','$postedby','successful','$mycurrentTime','$iuid','$total','')") or die (mysqli_error($link));
						
				$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$txid','$acctno','','$amt','Debit','$card_currency','Card-Withdrawal','$postedby','successful','$mycurrentTime','$iuid','$total','')") or die (mysqli_error($link));
						
				$update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link));

				$update = mysqli_query($link, "UPDATE card_enrollment SET amount_prefund = '$total' WHERE acctno = '$account'") or die (mysqli_error($link));

				include("alert_sender/card_withdraw_alert.php");
				echo "<div class='alert bg-blue'><i class='fa fa-mark'></i> Transaction successful!!...</div>";
			}
			else{
				echo "<div class='alert bg-orange'><i class='fa fa-times'></i> Oops! ".$result['Message']."</div>";
			}
		}else{
			echo "<div class='alert bg-orange'><i class='fa fa-times'></i> Oops! Invalid PIN Entered!!</div>";
		}
	}elseif($charges != "" && $pin == "" && $token != ""){
		$systemset = mysqli_query($link, "SELECT * FROM systemset");
		$get_sys = mysqli_fetch_array($systemset);
		$sys_abb = $get_sys['abb'];
		$seckey = $get_sys['secret_key'];

		$search_account = mysqli_query($link, "SELECT * FROM card_enrollment WHERE pin = '$token'");
		$fetch_account = mysqli_fetch_object($search_account);
		$issuer_name = $fetch_account->api_used;
		$account = $fetch_account->acctno;

		if(mysqli_num_rows($search_account) == 1){
			$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE issuer_name = '$issuer_name' AND api_name = 'withdraw_fromcard'");
			$fetch_restapi = mysqli_fetch_object($search_restapi);
			$api_url = $fetch_restapi->api_url;

			// Pass the parameter here
			$postdata =  array(
				"id"	=>	$cardId,
				"amount"=>	$amt,
				"secret_key"	=>	$seckey
			);

			$make_call = callAPI('POST', $api_url, json_encode($postdata));
			$result = json_decode($make_call, true);

			if($result['Status'] == 'success')
			{
				$total = number_format(($bal - $amt - $final_charges),2,'.','');
				$semi_total = number_format(($bal - $amt),2,'.','');
				$today = date("Y-m-d");
				$mycurrentTime = date("Y-m-d h:i:s");
				$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$txid','$acctno','','$final_charges','Debit','$card_currency','Card-WCharges','$postedby','successful','$mycurrentTime','$iuid','$total')") or die (mysqli_error($link));
						
				$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$txid','$acctno','','$amt','Debit','$card_currency','Card-Withdrawal','$postedby','successful','$mycurrentTime','$iuid','$total')") or die (mysqli_error($link));
						
				$update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link));

				$update = mysqli_query($link, "UPDATE card_enrollment SET amount_prefund = '$total' WHERE acctno = '$account'") or die (mysqli_error($link));

				include("alert_sender/card_withdraw_alert.php");
				echo "<div class='alert bg-blue'><i class='fa fa-mark'></i> Transaction successful!!...</div>";
			}
			else{
				echo "<div class='alert bg-orange'><i class='fa fa-times'></i> Oops! ".$result['Message']."</div>";
			}
		}else{
			echo "<div class='alert bg-orange'><i class='fa fa-times'></i> Oops! Invalid PIN Entered!!</div>";
		}
	}elseif($charges == "" && $pin != "" && $token == ""){
		$systemset = mysqli_query($link, "SELECT * FROM systemset");
		$get_sys = mysqli_fetch_array($systemset);
		$sys_abb = $get_sys['abb'];
		$seckey = $get_sys['secret_key'];

		$search_account = mysqli_query($link, "SELECT * FROM card_enrollment WHERE pin = '$pin'");
		$fetch_account = mysqli_fetch_object($search_account);
		$issuer_name = $fetch_account->api_used;
		$account = $fetch_account->acctno;

		if(mysqli_num_rows($search_account) == 1){
		$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE issuer_name = '$issuer_name' AND api_name = 'withdraw_fromcard'");
		$fetch_restapi = mysqli_fetch_object($search_restapi);
		$api_url = $fetch_restapi->api_url;

		// Pass the parameter here
		$postdata =  array(
			"id"	=>	$cardId,
			"amount"=>	$amt,
			"secret_key"	=>	$seckey
		);

		$make_call = callAPI('POST', $api_url, json_encode($postdata));
		$result = json_decode($make_call, true);

		if($result['Status'] == 'success')
		{
			$semi_total = number_format(($bal - $amt),2,'.','');
			$today = date("Y-m-d");						
			$mycurrentTime = date("Y-m-d h:i:s");
			$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$txid','$acctno','','$amt','Debit','$card_currency','Card-Withdrawal','$postedby','successful','$mycurrentTime','$iuid','$semi_total','')") or die (mysqli_error($link));
						
			$update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$semi_total', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link));

				$update = mysqli_query($link, "UPDATE card_enrollment SET amount_prefund = '$semi_total' WHERE acctno = '$account'") or die (mysqli_error($link));

				include("alert_sender/card_withdraw_alert1.php");
				echo "<div class='alert bg-blue'><i class='fa fa-mark'></i> Transaction successful!!...</div>";
			}
			else{
				echo "<div class='alert bg-orange'><i class='fa fa-times'></i> Oops! ".$result['Message']."</div>";
			}
		}else{
			echo "<div class='alert bg-orange'><i class='fa fa-times'></i> Oops! Invalid PIN Entered!!</div>";
		}
	}elseif($charges == "" && $pin == "" && $token != ""){
		$systemset = mysqli_query($link, "SELECT * FROM systemset");
		$get_sys = mysqli_fetch_array($systemset);
		$sys_abb = $get_sys['abb'];
		$seckey = $get_sys['secret_key'];

		$search_account = mysqli_query($link, "SELECT * FROM card_enrollment WHERE pin = '$token'");
		$fetch_account = mysqli_fetch_object($search_account);
		$issuer_name = $fetch_account->api_used;
		$account = $fetch_account->acctno;

		if(mysqli_num_rows($search_account) == 1){
		$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE issuer_name = '$issuer_name' AND api_name = 'withdraw_fromcard'");
		$fetch_restapi = mysqli_fetch_object($search_restapi);
		$api_url = $fetch_restapi->api_url;

		// Pass the parameter here
		$postdata =  array(
			"id"	=>	$cardId,
			"amount"=>	$amt,
			"secret_key"	=>	$seckey
		);

		$make_call = callAPI('POST', $api_url, json_encode($postdata));
		$result = json_decode($make_call, true);

		if($result['Status'] == 'success')
		{
			$semi_total = number_format(($bal - $amt),2,'.','');
			$today = date("Y-m-d");						
			$mycurrentTime = date("Y-m-d h:i:s");
			$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$txid','$acctno','','$amt','Debit','$card_currency','Card-Withdrawal','$postedby','successful','$mycurrentTime','$iuid','$semi_total','')") or die (mysqli_error($link));
						
			$update = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$semi_total', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link));

				$update = mysqli_query($link, "UPDATE card_enrollment SET amount_prefund = '$semi_total' WHERE acctno = '$account'") or die (mysqli_error($link));

				include("alert_sender/card_withdraw_alert1.php");
				echo "<div class='alert bg-blue'><i class='fa fa-mark'></i> Transaction successful!!...</div>";
			}
			else{
				echo "<div class='alert bg-orange'><i class='fa fa-times'></i> Oops! ".$result['Message']."</div>";
			}
		}else{
			echo "<div class='alert bg-orange'><i class='fa fa-times'></i> Oops! Invalid PIN Entered!!</div>";
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
					    <select name="charges"  class="myinput" required>
        					<option selected>Select Charges</option>
        					<?php
        					$search_mycharges = mysqli_query($link, "SELECT * FROM charge_management WHERE companyid = '' ORDER BY id DESC");
        					while($fetch_mycharges = mysqli_fetch_object($search_mycharges))
        					{
        					?>
        					<option value="<?php echo $fetch_mycharges->id; ?>"><?php echo $fetch_mycharges->charges_name.'('.$fetch_mycharges->charges_value.' - ['.$fetch_mycharges->charges_type.'])'; ?></option>
            				<?php } ?>
        				</select>
					    <?php
					    if($fetch_card->secure_option == "pin"){
					    ?>
					    <input type="password" class="myinput" placeholder="Enter your 4 digit PIN to confirm" maxlength="4" name="pin"/required>
					    <?php
					    }
					    elseif($fetch_card->secure_option == "otp"){
					    ?>
					    <input type="text" class="myinput" placeholder="Enter Token to confirm transaction" name="token"/required>
					    <?php
					        $phone = $fetch_card->billing_phone;
					        $accountno = $fetch_card->acctno;
					        $otp = mt_rand(100000,999999);
					        $wtype = "card-withdrawal";
					        
					        $system_set = mysqli_query($link, "SELECT * FROM systemset");
                            $get_sys = mysqli_fetch_array($system_set);
                            $sysabb = $get_sys['abb'];
                            
                            $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
                        	$fetch_gateway = mysqli_fetch_object($search_gateway);
                        	$gateway_uname = $fetch_gateway->username;
                        	$gateway_pass = $fetch_gateway->password;
                        	$gateway_api = $fetch_gateway->api;

					        $insert = mysqli_query($link, "UPDATE card_enrollment SET pin = '$otp' WHERE acctno = '$accountno'");
					        
					        $sms = "$sysabb>>>DO NOT DISCLOSE YOUR OTP TO ANYONE.";
                            $sms .= " Your One Time Password to Withdraw via Card is ".$otp.". Kindly ignore if you are unaware. Thanks.";
					        include("../cron/send_general_sms.php");
					        echo "<span style='font-size:11px;'><i>otp has been sent to customer phone number...</i></span>";
					    }
					    ?>
					    
				</div>
				<div align="center">
                  <div class="box-footer">
                    <button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Make Withdrawal</i></button>
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
</div>	
</div>
</div>