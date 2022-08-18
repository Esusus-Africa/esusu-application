<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-money"></i> Customer Withdrawal Form
            <button type="button" class="btn btn-flat bg-white" align="left">&nbsp;<b style="color: black;">Wallet Balance:</b>&nbsp;
			<strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
			<?php
			echo "<span id='wallet_balance'>".$icurrency.number_format($iassigned_walletbal,2,'.',',')."</span>";
			?>
			</strong>
			  </button>
            </h3>
            </div>
             <div class="box-body">
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">
<?php
if(isset($_POST['save']))
{
	function myotp($limit)
	{
		return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
	}
	try{
	    $my_wid = $_GET['wid'];
		$account =  mysqli_real_escape_string($link, $_POST['account']);
		$ptype = mysqli_real_escape_string($link, $_POST['ptype']);
		$amount = mysqli_real_escape_string($link, $_POST['amount']);
		$remark = mysqli_real_escape_string($link, $_POST['remark']);
		$real_txid = 'TXID-'.date("ymi").time();
		$search_txid = mysqli_query($link, "SELECT * FROM transaction WHERE txid = '$real_txid'");
		$txid = (mysqli_num_rows($search_txid) == 0) ? $real_txid : 'TXID-'.rand(1000000000,9999999999).rand(1000000000,9999999999);
		$myotp = myotp(6);
		
		$date_time = date("Y-m-d H:i:s");
		$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
        $correctdate = $acst_date->format('Y-m-d g:i A');
		
		$billing_type = $ifetch_maintenance_model['billing_type'];
		$t_perc = $ifetch_maintenance_model['t_charges'];
		
		$google_details = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$account'");
		$get_details = mysqli_fetch_array($google_details);
		$fn = $get_details['fname'];
		$ln = $get_details['lname'];
		$em = $get_details['email'];
		$ph = $get_details['phone'];
		$bal = $get_details['balance'];
		$uname = $get_details['username'];
		$lwdate = $get_details['last_withdraw_date'];
		$opt_validation = $get_details['opt_option'];
		$acct_type = $get_details['acct_name'];
		
		$search_openingbal = mysqli_query($link, "SELECT * FROM account_type WHERE merchant_id = '$institution_id' AND acct_name = '$acct_type'");
		$fetch_openingbal = mysqli_fetch_array($search_openingbal);
		$opening_balance = $fetch_openingbal['opening_balance'];

		$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
		$fetch_memset = mysqli_fetch_array($search_memset);

		$charges = mysqli_real_escape_string($link, $_POST['charges']);
		$verify_charges = mysqli_query($link, "SELECT * FROM charge_management WHERE id = '$charges'");
		$fetch_verification = mysqli_fetch_object($verify_charges);
		$ctype = $fetch_verification->charges_type;
		$cvalue = $fetch_verification->charges_value;

		//Do Percentage Calculation
		$percent = ($cvalue/100)*$amount;

		$final_charges = ($ctype == "Flatrate") ? $cvalue : $percent;
			
		if($bal < $amount && $overdraft == 'No'){
			echo "<div class='alert bg-orange'>Insufficient Fund!!!</div>";
		}elseif($bal < ($amount + $final_charges) && $overdraft == 'No')
		{
			echo "<div class='alert bg-orange'>Insufficient Fund!.....</div>";
		}elseif($amount < 0){
			throw new UnexpectedValueException();
		}elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $amount) == FALSE)
		{
			throw new UnexpectedValueException();
		}
		elseif(($opening_balance != "0" || $opening_balance != "0.0") && ($opening_balance > ($bal - ($amount + $final_charges))))
		{
			echo "<div class='alert bg-orange'>Sorry, You cannot withdraw more than the Opening Balance!!</div>";
		}
		elseif((mysqli_num_rows($isearch_maintenance_model) == 1 || $billing_type == "Hybrid") && $iassigned_walletbal < $t_perc)
		{
		    echo "<div class='alert bg-orange'>Sorry, You are unable to post this transaction due to insufficient fund in your Wallet!!</div>";
		}elseif($charges != "" && $opt_validation == "No" && (mysqli_num_rows($isearch_maintenance_model) == 1 || $billing_type == "Hybrid") && $iassigned_walletbal >= $t_perc)
		{
			$total = number_format(($bal - $amount - $final_charges),2,'.','');
			$semi_total = number_format(($bal - $amount),2,'.','');
			$today = date("Y-m-d");
			$t_type = "Withdraw";
			$t_type1 = "Withdraw-Charges";
			$income_id = "ICM".rand(000001,99999);
			$myiwallet_balance = $iassigned_walletbal - $t_perc;
            $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','self','$t_perc','$icurrency','Wallet','Description: Service Charge for Withdrawal of $amount','successful',NOW())");
			    
			($irole != "institution_super_admin" && $isubagent_wallet === "Enabled") ? mysqli_query($link, "UPDATE user SET wallet_balance = '$myiwallet_balance' WHERE id = '$iuid'") : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$institution_id'");
			
			$insert = mysqli_query($link, "INSERT INTO income VALUES(null,'$institution_id','$income_id','Charges','$final_charges','$today','$t_type1')");
				
			$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','Withdraw-Charges','$ptype','$account','----','$fn','$ln','$em','$ph','$final_charges','$iuid','Charges','$correctdate','$institution_id','$isbranchid','$icurrency','','$total','Approved','1','1','1')") or die (mysqli_error($link));
				
			$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','Withdraw','$ptype','$account','----','$fn','$ln','$em','$ph','$amount','$iuid','$remark','$correctdate','$institution_id','$isbranchid','$icurrency','','$total','Approved','1','1','1')") or die (mysqli_error($link));
				
			$update = mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link));
				
			$update = mysqli_query($link, "UPDATE ledger_withdrawal_request SET status = 'Approved' WHERE id = '$my_wid'");
				
			if(!($insert && $update))
			{
				echo "<div class='alert bg-orange'>Transaction not Successful!!</div>";
			}
			else{
				(strlen($ph) == "14" || strlen($ph) == "13" || strlen($ph) == "11") ? include("alert_sender/withdraw_alert.php") : "";
				include("email_sender/send_swithdrawal_alertemail.php");
				echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Customer Ledger Balance: <b style='color: orange;'>".$icurrency.number_format($total,2,'.',',')."</b></p></div>";
			}
		}
		elseif($charges != "" && $opt_validation == "No" && mysqli_num_rows($isearch_maintenance_model) == 0){
			$total = number_format(($bal - $amount - $final_charges),2,'.','');
			$semi_total = number_format(($bal - $amount),2,'.','');
			$today = date("Y-m-d");
			$t_type = "Withdraw";
			$t_type1 = "Withdraw-Charges";
			$income_id = "ICM".rand(000001,99999);
			$insert = mysqli_query($link, "INSERT INTO income VALUES(null,'$institution_id','$income_id','Charges','$final_charges','$today','$t_type1')");
				
			$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','Withdraw-Charges','$ptype','$account','----','$fn','$ln','$em','$ph','$final_charges','$iuid','Charges','$correctdate','$institution_id','$isbranchid','$icurrency','','$total','Approved','1','1','1')") or die (mysqli_error($link));
				
			$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','Withdraw','$ptype','$account','----','$fn','$ln','$em','$ph','$amount','$iuid','$remark','$correctdate','$institution_id','$isbranchid','$icurrency','','$total','Approved','1','1','1')") or die (mysqli_error($link));
				
			$update = mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link));
				
			$update = mysqli_query($link, "UPDATE ledger_withdrawal_request SET status = 'Approved' WHERE id = '$my_wid'");
				
			if(!($insert && $update))
			{
				echo "<div class='alert bg-orange'>Transaction not Successful!!</div>";
			}
			else{
				(strlen($ph) == "14" || strlen($ph) == "13" || strlen($ph) == "11") ? include("alert_sender/withdraw_alert.php") : "";
				include("email_sender/send_swithdrawal_alertemail.php");
				echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Customer Ledger Balance: <b style='color: orange;'>".$icurrency.number_format($total,2,'.',',')."</b></p></div>";
			}
			}
			elseif($charges != "" && $opt_validation == "Yes"){
				$total = number_format(($bal - $amount - $final_charges),2,'.','');
				$semi_total = number_format(($bal - $amount),2,'.','');
				$today = date("Y-m-d");
				$insert = mysqli_query($link, "INSERT INTO transaction_verification VALUES(null,'$txid','Withdraw-Charges','$ptype','$account','$uname','----','$fn','$ln','$em','$ph','$final_charges','$iuid','Charges','$correctdate','$institution_id','$asbranchid','$acurrency','$total','$remain_balance','$myotp')") or die (mysqli_error($link));
				
				$insert = mysqli_query($link, "INSERT INTO transaction_verification VALUES(null,'$txid','Withdraw','$ptype','$account','----','$fn','$ln','$em','$ph','$amount','$iuid','$remark','$correctdate','$institution_id','$isbranchid','$icurrency','$total','','$myotp')") or die (mysqli_error($link));

				if(!$insert)
				{
					echo "<div class='alert bg-orange'>Transaction not Successful!!</div>";
				}
				else{
					(strlen($ph) == "14" || strlen($ph) == "13" || strlen($ph) == "11") ? include("alert_sender/withdraw_otpalert1.php") : "";
					echo "<script>window.location='confirm_transaction.php?id=".$_SESSION['tid']."&&mid=NDEw'; </script>";
				}
			}
			elseif($charges == "" && $opt_validation == "No" && (mysqli_num_rows($isearch_maintenance_model) == 1 || $billing_type == "Hybrid") && $iassigned_walletbal >= $t_perc){
				$total = number_format(($bal - $amount),2,'.','');
				$today = date("Y-m-d");
				$myiwallet_balance = $iassigned_walletbal - $t_perc;
                $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','self','$t_perc','$icurrency','Wallet','Description: Service Charge for Withdrawal of $amount','successful',NOW())");
			    
				($irole != "institution_super_admin" && $isubagent_wallet === "Enabled") ? mysqli_query($link, "UPDATE user SET wallet_balance = '$myiwallet_balance' WHERE id = '$iuid'") : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$institution_id'");
				
				$insert1 = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','Withdraw','$ptype','$account','----','$fn','$ln','$em','$ph','$amount','$iuid','$remark','$correctdate','$institution_id','$isbranchid','$icurrency','','$total','Approved','1','1','1')") or die (mysqli_error($link));
				
				$update1 = mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link));
				
				$update1 = mysqli_query($link, "UPDATE ledger_withdrawal_request SET status = 'Approved' WHERE id = '$my_wid'");
				
				if(!($insert1 && $update1))
				{
					echo "<div class='alert bg-orange'>Transaction not Successful!!</div>";
				}
				else{
					//DEBIT ALERT FOR WITHDRAWAL AMOUNT ONLY SINCE THE LAST DAY THE CUSTOMER WITHDRAW IS OVER 3 MONTHS. SO WITHDRAWAL FEE IS EXCLUSIVE!
					(strlen($ph) == "14" || strlen($ph) == "13" || strlen($ph) == "11") ? include("alert_sender/withdraw_alert1.php") : "";
					include("email_sender/send_swithdrawal_alertemail.php");
					echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Customer Ledger Balance: <b style='color: orange;'>".$icurrency.number_format($total,2,'.',',')."</b></p></div>";
				}
			}
			elseif($charges == "" && $opt_validation == "No" && mysqli_num_rows($isearch_maintenance_model) == 0){
				$total = number_format(($bal - $amount),2,'.','');
				$today = date("Y-m-d");
				$myiwallet_balance = ($billing_type == "Hybrid") ? $iassigned_walletbal - $t_perc : "";
                ($billing_type == "Hybrid") ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','self','$t_perc','$icurrency','Wallet','Description: Service Charge for Withdrawal of $amount','successful',NOW())") : "";
			    
				($billing_type == "Hybrid" && $irole != "institution_super_admin" && $isubagent_wallet === "Enabled") ? mysqli_query($link, "UPDATE user SET wallet_balance = '$myiwallet_balance' WHERE id = '$iuid'") : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$institution_id'");
				
				$insert1 = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','Withdraw','$ptype','$account','----','$fn','$ln','$em','$ph','$amount','$iuid','$remark','$correctdate','$institution_id','$isbranchid','$icurrency','','$total','Approved','1','1','1')") or die (mysqli_error($link));
				
				$update1 = mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link));
				
				$update1 = mysqli_query($link, "UPDATE ledger_withdrawal_request SET status = 'Approved' WHERE id = '$my_wid'");
				
				if(!($insert1 && $update1))
				{
					echo "<div class='alert bg-orange'>Transaction not Successful!!</div>";
				}
				else{
					//DEBIT ALERT FOR WITHDRAWAL AMOUNT ONLY SINCE THE LAST DAY THE CUSTOMER WITHDRAW IS OVER 3 MONTHS. SO WITHDRAWAL FEE IS EXCLUSIVE!
					(strlen($ph) == "14" || strlen($ph) == "13" || strlen($ph) == "11") ? include("alert_sender/withdraw_alert1.php") : "";
					include("email_sender/send_swithdrawal_alertemail.php");
					echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Customer Ledger Balance: <b style='color: orange;'>".$icurrency.number_format($total,2,'.',',')."</b></p></div>";
				}
			}
			elseif($charges == "" && $opt_validation == "Yes"){
				$total = number_format(($bal - $amount),2,'.','');
				$today = date("Y-m-d");
				$insert1 = mysqli_query($link, "INSERT INTO transaction_verification VALUES(null,'$txid','Withdraw','$ptype','$account','$uname','----','$fn','$ln','$em','$ph','$amount','$iuid','$remark','$correctdate','$institution_id','$isbranchid','$icurrency','$total','','$myotp')") or die (mysqli_error($link));

				if(!$insert1)
				{
					echo "<div class='alert bg-orange'>Transaction not Successful!!</div>";
				}
				else{
					(strlen($ph) == "14" || strlen($ph) == "13" || strlen($ph) == "11") ? include("alert_sender/withdraw_otpalert1.php") : "";
					echo "<script>window.location='confirm_transaction.php?id=".$_SESSION['tid']."&&mid=NDEw'; </script>";
				}
			}
		}catch(UnexpectedValueException $ex)
		{
			echo "<div class='alert alert-danger'>Invalid Amount Entered!</div>";
		}
}
?>

<?php
$wid = $_GET['wid'];
$search_wrequest = mysqli_query($link, "SELECT * FROM ledger_withdrawal_request WHERE id = '$wid'");
$fetch_wrequest = mysqli_fetch_array($search_wrequest);
$r_acctno = $fetch_wrequest['acn'];
?>
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
                  <div class="col-sm-10">
				<select name="account"  class="form-control select2" required>
			<?php
			if(isset($_GET['uid']))
			{
				$searchin = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND account = '".$_GET['uid']."'");
				while($get_searchin = mysqli_fetch_array($searchin))
				{
				?>
					<option value="<?php echo $_GET['uid']; ?>" selected><?php echo $get_searchin['snum']; ?> <?php echo $_GET['uid']; ?>&nbsp; [<?php echo $get_searchin['fname']; ?>&nbsp;<?php echo $get_searchin['lname']; ?> : <?php echo $icurrency.number_format($get_searchin['balance'],2,'.',','); ?>]</option>
			<?php
			}
			}else{
				?>
			<?php } ?>
			<?php
			$search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND account = '$r_acctno'");
			while($get_search = mysqli_fetch_array($search))
			{
			?>
					<option value="<?php echo $get_search['account']; ?>" selected><?php echo $get_search['snum']; ?> <?php echo $get_search['account']; ?>&nbsp; [<?php echo $get_search['fname']; ?>&nbsp;<?php echo $get_search['lname']; ?> : <?php echo $icurrency.number_format($get_search['balance'],2,'.',','); ?>]</option>
			<?php } ?>
				</select>
				</div>
            </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Payment Type</label>
                  <div class="col-sm-10">
				<select name="ptype"  class="form-control select2" required>
					<option value="<?php echo $fetch_wrequest['ptype']; ?>" selected><?php echo $fetch_wrequest['ptype']; ?></option>
				</select>
				</div>
            </div>

            <?php
            if($enable_charges == 1)
            {
            ?>
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Charges</label>
                  <div class="col-sm-10">
				<select name="charges"  class="form-control select2">
					<option value="" selected>Select Charges</option>
					<?php
					$search_mycharges = mysqli_query($link, "SELECT * FROM charge_management WHERE companyid = '$institution_id'");
					while($fetch_mycharges = mysqli_fetch_object($search_mycharges))
					{
					?>
					<option value="<?php echo $fetch_mycharges->id; ?>"><?php echo $fetch_mycharges->charges_name.'('.$fetch_mycharges->charges_value.' - ['.$fetch_mycharges->charges_type.'])'; ?></option>
				<?php } ?>
				</select>
				</div>
            </div>
            <?php
        	}
        	else{
        		'';
        	}
        	?>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                  <div class="col-sm-10">
                  <input name="amount" type="number" class="form-control" value="<?php echo $fetch_wrequest['amt_requested']; ?>" placeholder="Enter Amount Here" readonly>
                  </div>
                  </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Remark</label>
                <div class="col-sm-10">
                <textarea name="remark"  class="form-control" rows="4" cols="80"><?php echo $fetch_wrequest['remarks']; ?></textarea>
                </div>
             </div>

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Make Withdrawal</i></button>
								&nbsp;<?php echo ($view_account_info == 1) ? '<button name="verify" type="submit" class="btn bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].' pull-right"><i class="fa fa-filter">&nbsp;Verify Account</i></button>' : ''; ?>

              </div>
			  </div>
			  
<?php
if(isset($_POST['verify']))
{
	$account =  mysqli_real_escape_string($link, $_POST['account']);
	echo "<script> window.location='withdraw.php?id=".$_SESSION['tid']."&&mid=".base64_encode('410')."&&uid=".$account."&&wid=".$_GET['wid']."'; </script>";
}
?>			
			<?php
			if(isset($_GET['uid']))
			{
				include("verify_customer.php");
			}else{
				echo "";
			}
			?>
			
			 </form> 


</div>	
</div>	
</div>
</div>