<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-money"></i> Customer Withdrawal Form
            <button type="button" class="btn btn-flat bg-white" align="left">&nbsp;<b style="color: black;">Wallet Balance:</b>&nbsp;
			<strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
			<?php
			echo "<span id='wallet_balance'>".$icurrency.number_format($iwallet_balance,2,'.',',')."</span>";

			function myotp($limit)
			{
				return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
			}

			function startsWith ($string, $startString) 
			{ 
				$len = strlen($startString); 
				return (substr($string, 0, $len) === $startString); 
			}
			?>
			</strong>
			  </button>
            </h3>
            </div>
             <div class="box-body">
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<?php
if(isset($_POST['save']))
{    
	$account = mysqli_real_escape_string($link, $_POST['account']);
	$ptype = mysqli_real_escape_string($link, $_POST['ptype']);
	$amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount']));
	$remark = mysqli_real_escape_string($link, $_POST['remark']);
	$balanceToImpact = mysqli_real_escape_string($link, $_POST['balanceToImpact']);
	$real_txid = 'TXID-'.mt_rand(10000,99999).time().uniqid();
	$search_txid = mysqli_query($link, "SELECT * FROM transaction WHERE txid = '$real_txid'");
	$txid = (mysqli_num_rows($search_txid) == 0) ? $real_txid : 'TXID-'.date("dyi").time().uniqid();
	$myotp = rand(1000, 9999).date("s");
	$charges = mysqli_real_escape_string($link, $_POST['charges']);
	$verify_charges = mysqli_query($link, "SELECT * FROM charge_management WHERE id = '$charges'");
	$fetch_verification = mysqli_fetch_object($verify_charges);
	$ctype = $fetch_verification->charges_type;
	$cvalue = $fetch_verification->charges_value;
	$currenctdate = date("Y-m-d h:i:s");

	//Do Percentage Calculation
	$percent = ($cvalue/100) * $amount;
	$final_charges = ($charges == "") ? 0 : ($ctype == "Flatrate" ? $cvalue : $percent);
	$totalamount = $amount + $final_charges;

	$google_details = mysqli_query($link, "SELECT * FROM borrowers WHERE  account = '$account'");
	$get_details = mysqli_fetch_array($google_details);
	$fn = $get_details['fname'];
	$ln = $get_details['lname'];
	$em = $get_details['email'];
	$ph = $get_details['phone'];
	$bal = ($balanceToImpact == "ledger" ? $get_details['balance'] : ($balanceToImpact == "target" ? $get_details['target_savings_bal'] : ($balanceToImpact == "investment" ? $get_details['investment_bal'] : ($balanceToImpact == "asset" ? $get_details['asset_acquisition_bal'] : ($balanceToImpact == "loan" ? $get_details['loan_balance'] : $get_details['balance'])))));
	$balLabel = ($balanceToImpact == "ledger" ? "Ledger Savings" : ($balanceToImpact == "target" ? "Target Savings" : ($balanceToImpact == "investment" ? "Investment" : ($balanceToImpact == "asset" ? "Asset Acquisition" : "Unknown"))));
	$uname = $get_details['username'];
	$lwdate = $get_details['last_withdraw_date'];
	$opt_validation = $get_details['opt_option'];
	$overdraft = $get_details['overdraft'];
	$acct_type = $get_details['acct_name'];
	$sms_checker = $get_details['sms_checker'];

	$search_openingbal = mysqli_query($link, "SELECT * FROM account_type WHERE merchant_id = '$institution_id' AND acct_name = '$acct_type'");
	$fetch_openingbal = mysqli_fetch_array($search_openingbal);
	$opening_balance = $fetch_openingbal['opening_balance'];
	
	$maintenance_row = mysqli_num_rows($isearch_maintenance_model);
	$t_perc = $ifetch_maintenance_model['t_charges'];

	$authorizePin = ($opt_validation == "No") ? $myiepin : $myotp;
	$detectAuthMode = ($opt_validation == "No") ? "pin" : "otp";

	$debitWAllet = ($getSMS_ProviderNum == 1) ? "No" : "Yes"; 
	$refid = uniqid("EA-smsCharges-").time();
	$sms_rate = $fetchsys_config['fax'];
	$mybalance = $iwallet_balance - $sms_rate;

	$message = "$isenderid>>>OTP";
    $message .= " Your One Time Password is: ".$authorizePin."";

	//Data Parser (array size = 6)
	$mydata = $txid."|".$account."|".$ptype."|".$amount."|".$remark."|".$final_charges."|".$fn."|".$ln."|".$em."|".$ph."|".$bal."|".$uname."|".$lwdate."|".$overdraft."|".$acct_type."|".$maintenance_row."|".$t_perc."|".$sms_checker."|".$balLabel."|".$balanceToImpact;
	
	if(($bal < $totalamount) && $overdraft == 'No'){

		echo "<div class='alert bg-orange'>Insufficient Fund!.....</div>";

	}
	elseif($totalamount <= 0){

		echo "<div class='alert bg-orange'>Oops! Invalid Amount Entered!!</div>";

	}
	elseif(($opening_balance != "0" || $opening_balance != "0.0") && ($opening_balance > ($bal - $totalamount)) && !(startsWith($institution_id,"AGT")))
	{

		echo "<div class='alert bg-orange'>Oh-Sorry, You cannot withdraw more than the Opening Balance!!</div>";

	}
	elseif($maintenance_row == 1 && $billing_type != "PAYGException" && $iwallet_balance < $t_perc){

		echo "<div class='alert bg-orange'>Oh-Sorry, No sufficient fund in Wallet to complete this transaction!!</div>";
		
	}
	else{
		
		mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$iuid','$authorizePin','$mydata','Pending','$currenctdate')") or die("Error: " . mysqli_error($link));
		
		($opt_validation == "No" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $ph, $message, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet) : ($debitWAllet == "Yes" && $sms_rate <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $ph, $message, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet) : "")));
		echo "<div align='center'><img src='../image/PleaseWait.gif'><p style='color: #38A1F3;'>Please wait a minute to confirm the transaction!!</p></div>";
		echo '<meta http-equiv="refresh" content="5;url=withdraw.php?id='.$_SESSION['tid'].'&&mid=NDEw&&'.$detectAuthMode.'">';
		
	}

}
?>



<?php
if (isset($_POST['confirm']))
{    
    $myotp = mysqli_real_escape_string($link, $_POST['otp']);
    
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
	$fetch_memset = mysqli_fetch_array($search_memset);
	
	$search_sys = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
	$get_sys = mysqli_fetch_array($search_sys);
				    
	$verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$myotp' AND userid = '$iuid' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
						    
	if($otpnum == 0){
						        
	    echo "<div class='alert bg-orange'>Oops!...Invalid Transaction Pin!!</div>";
						        
	}else{

		$concat = $fetch_data['data'];
        $datetime = $fetch_data['datetime'];
        $parameter = (explode('|',$concat));
				
        $txid = $parameter[0];
		$account = $parameter[1];
		$ptype = $parameter[2];
        $amount = $parameter[3];
		$remark = $parameter[4];
		$date_time = date("Y-m-d h:i:s");
		$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
		$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
		$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
		$correctdate = $acst_date->format('Y-m-d g:i A');
		$final_charges = $parameter[5];
		$fn = $parameter[6];
		$ln = $parameter[7];
		$em = $parameter[8];
		$ph = $parameter[9];
		$bal = $parameter[10];
		$uname = $parameter[11];
		$lwdate = $parameter[12];
		$overdraft = $parameter[13];
		$acct_type = $parameter[14];
		$maintenance_row = $parameter[15];
		$t_perc = $parameter[16];
		$sms_checker = $parameter[17];
		$balLabel = $parameter[18];
		$balanceToImpact = $parameter[19];

		//Total Charges Calculated
		$totalCharges = $bal - $amount - $final_charges;

		//Semi Charges Calculated
		$semiCharges = $bal - $amount;

		$status = ($allow_auth == "Yes") ? "Approved" : "Pending";

		$notification = ($allow_auth == "Yes") ? "1" : "0";

		$checksms = ($sms_checker == "No") ? "0" : "1";

		$debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 
		$refid = uniqid("EA-smsCharges-").time();
		$sms_rate = $fetchsys_config['fax'];
		$mybalance = $iwallet_balance - $sms_rate;
		$accno = ccMasking($account);

		//DEBIT ALERT FOR THE AMOUNT WITHDRAWED
		$message1 = "$isenderid>>>DR";
		$message1 .= " Amt: ".$icurrency.number_format($amount,2,'.',',')."";
		$message1 .= " Acc: ".$accno."";
		$message1 .= " Desc: ".substr($remark,0,20)." - ".$txid."";
		$message1 .= " Time: ".$correctdate."";
		$message1 .= " Bal: ".$icurrency.number_format($semiCharges,2,'.',',')."";

		//DEBIT ALERT FOR WITHDRAWAL CHARGES
		$message2 = "$isenderid>>>DR";
		$message2 .= " Amt: ".$icurrency.number_format($final_charges,2,'.',',')."";
		$message2 .= " Acc: ".$accno."";
		$message2 .= " Desc: Withdrawal Charges - ".$txid."";
		$message2 .= " Time: ".$correctdate."";
		$message2 .= " Bal: ".$icurrency.number_format($totalCharges,2,'.',',')."";

		if($final_charges != 0 && $final_charges != "")
		{
			$total = number_format($totalCharges,2,'.','');
			$semi_total = number_format($semiCharges,2,'.','');
			$today = date("Y-m-d");
			$transactionType = "Withdraw";
			$transactionType1 = "Withdraw-Charges";
			$income_id = "ICM".rand(000001,99999);
			$myiwallet_balance = $iwallet_balance - $t_perc;

            ($maintenance_row == "1" && $billing_type != "PAYGException" && $t_perc != "0") ? $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','self','','$t_perc','Debit','$icurrency','Charges','Description: Service Charge for Withdrawal of $amount','successful','$date_time','$iuid','$myiwallet_balance','')") : "";
			    
			($maintenance_row == "1" && $billing_type != "PAYGException" && $t_perc != "0" ? (($irole != "agent_manager" || $irole != 'institution_super_admin' || $irole != "merchant_super_admin") && $isubagent_wallet === "Enabled" ? mysqli_query($link, "UPDATE user SET wallet_balance = '$myiwallet_balance' WHERE id = '$iuid'") : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$institution_id'")) : "");
				
			$insert = mysqli_query($link, "INSERT INTO income VALUES(null,'$institution_id','$income_id','Charges','$final_charges','$today','$transactionType1')");
				
			$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','$transactionType1','$ptype','$account','----','$fn','$ln','$em','$ph','$final_charges','$iuid','Charges','$correctdate','$institution_id','$isbranchid','$icurrency','','$total','$status','$notification','$notification','$checksms','$balanceToImpact')") or die (mysqli_error($link));
				
			$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','$transactionType','$ptype','$account','----','$fn','$ln','$em','$ph','$amount','$iuid','$remark','$correctdate','$institution_id','$isbranchid','$icurrency','','$total','$status','$notification','$notification','$checksms','$balanceToImpact')") or die (mysqli_error($link));
				
			($allow_auth == "Yes" && $balanceToImpact == "ledger") ? $update = mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die ("Error: " . mysqli_error($link)) : "";

			($allow_auth == "Yes" && $balanceToImpact == "target") ? $update = mysqli_query($link, "UPDATE borrowers SET target_savings_bal = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die ("Error: " . mysqli_error($link)) : "";

			($allow_auth == "Yes" && $balanceToImpact == "investment") ? $update = mysqli_query($link, "UPDATE borrowers SET investment_bal = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die ("Error: " . mysqli_error($link)) : "";

			//($allow_auth == "Yes" && $balanceToImpact == "asset") ? $update = mysqli_query($link, "UPDATE borrowers SET asset_acquisition_bal = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die ("Error: " . mysqli_error($link)) : "";
			
			(($sms_checker == "No" || $billing_type == "PAYGException") ? "" : (($allow_auth == "Yes" && $debitWAllet == "No") ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $ph, $message1, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet) : (($allow_auth == "Yes" && $debitWAllet == "Yes" && $sms_rate <= $iwallet_balance) ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $ph, $message1, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet) : "")));
			//Withdrawal Charges Alert Function
			(($sms_checker == "No" || $billing_type == "PAYGException") ? "" : (($allow_auth == "Yes" && $debitWAllet == "No") ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $ph, $message2, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet) : (($allow_auth == "Yes" && $debitWAllet == "Yes" && $sms_rate <= $iwallet_balance) ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $ph, $message2, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet) : "")));
			($allow_auth == "Yes") ? $sendSMS->ledgerSavingsEmailNotifier($em, $transactionType, $txid, $uname, $correctdate, $ptype, $inst_name, $account, $ln, $fn, $icurrency, $amount, $total, $iemailConfigStatus, $ifetch_emailConfig) : "";
			
			mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$myotp' AND status = 'Pending'");

			echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Customer Ledger Balance: <b style='color: orange;'>".$icurrency.number_format($total,2,'.',',')."</b></p></div>";
			echo '<meta http-equiv="refresh" content="5;url=withdraw.php?id='.$_SESSION['tid'].'&&mid=NDEw">';

		}
		else{

			$transactionType = "Withdraw";
			$total = number_format($semiCharges,2,'.','');
			$today = date("Y-m-d");
			$myiwallet_balance = $iwallet_balance - $t_perc;

			($maintenance_row == "1" && $billing_type != "PAYGException" && $t_perc != "0") ? $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','self','','$t_perc','Debit','$icurrency','Charges','Description: Service Charge for Withdrawal of $amount','successful','$date_time','$iuid','$myiwallet_balance','')") : "";
			    
			($maintenance_row == "1" && $billing_type != "PAYGException" && $t_perc != "0" ? (($irole != "agent_manager" || $irole != 'institution_super_admin' || $irole != "merchant_super_admin") && $isubagent_wallet === "Enabled" ? mysqli_query($link, "UPDATE user SET wallet_balance = '$myiwallet_balance' WHERE id = '$iuid'") : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$institution_id'")) : "");
			
			$insert1 = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','$transactionType','$ptype','$account','----','$fn','$ln','$em','$ph','$amount','$iuid','$remark','$correctdate','$institution_id','$isbranchid','$icurrency','','$total','$status','$notification','$notification','$checksms','$balanceToImpact')") or die (mysqli_error($link));
			
			($allow_auth == "Yes" && $balanceToImpact == "ledger") ? $update = mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die ("Error: " . mysqli_error($link)) : "";

			($allow_auth == "Yes" && $balanceToImpact == "target") ? $update = mysqli_query($link, "UPDATE borrowers SET target_savings_bal = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die ("Error: " . mysqli_error($link)) : "";

			($allow_auth == "Yes" && $balanceToImpact == "investment") ? $update = mysqli_query($link, "UPDATE borrowers SET investment_bal = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die ("Error: " . mysqli_error($link)) : "";

			//($allow_auth == "Yes" && $balanceToImpact == "asset") ? $update = mysqli_query($link, "UPDATE borrowers SET asset_acquisition_bal = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die ("Error: " . mysqli_error($link)) : "";
			
			(($sms_checker == "No" || $billing_type == "PAYGException") ? "" : (($allow_auth == "Yes" && $debitWAllet == "No") ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $ph, $message1, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet) : (($allow_auth == "Yes" && $debitWAllet == "Yes" && $sms_rate <= $iwallet_balance) ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $ph, $message1, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet) : "")));
			($allow_auth == "Yes") ? $sendSMS->ledgerSavingsEmailNotifier($em, $transactionType, $txid, $uname, $correctdate, $ptype, $inst_name, $account, $ln, $fn, $icurrency, $amount, $total, $iemailConfigStatus, $ifetch_emailConfig) : "";
			
			mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$myotp' AND status = 'Pending'");

			echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Customer Ledger Balance: <b style='color: orange;'>".$icurrency.number_format($total,2,'.',',')."</b></p></div>";
			echo '<meta http-equiv="refresh" content="5;url=withdraw.php?id='.$_SESSION['tid'].'&&mid=NDEw">';

		}
	
	}
}
?>



<?php
if(!isset($_GET['pin']) && !isset($_GET['otp']))
{
?>
			<div class="box-body">

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
                  <div class="col-sm-10">
				<select name="account"  class="form-control select2" id="account_topost_to" required>
				<?php
				if(isset($_GET['uid']))
				{
					$searchin = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed' AND account = '".$_GET['uid']."'");
					while($get_searchin = mysqli_fetch_array($searchin))
					{
					?>
						<option value="<?php echo $_GET['uid']; ?>" selected><?php echo $get_searchin['snum']; ?> <?php echo $_GET['uid']; ?> - [<?php echo $get_searchin['lname'].' '.$get_searchin['fname'].' '.$get_searchin['mname']; ?>]</option>
					<?php
					}
				}else{
					?>
						<option value="" selected>Select Customer Account to Withdraw from</option>
				<?php } ?>
				<?php
				($individual_customer_records != "1" && $branch_customer_records != "1") ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed' ORDER BY id") or die (mysqli_error($link)) : "";
				($individual_customer_records == "1" && $branch_customer_records != "1") ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed' AND lofficer = '$iuid' ORDER BY id") or die (mysqli_error($link)) : "";
				($individual_customer_records != "1" && $branch_customer_records == "1") ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed' AND sbranchid = '$isbranchid' ORDER BY id") or die (mysqli_error($link)) : "";
				while($get_search = mysqli_fetch_array($search))
				{
				?>
						<option value="<?php echo $get_search['account']; ?>"><?php echo $get_search['snum']; ?> <?php echo $get_search['account']; ?> - [<?php echo $get_search['lname'].' '.$get_search['fname'].' '.$get_search['mname']; ?>]</option>
				<?php } ?>
				</select>
				</div>
            </div>

			<span id='ShowValueFrank'></span>
            <span id='ShowValueFrank'></span>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Payment Type</label>
                  <div class="col-sm-10">
				<select name="ptype"  class="form-control select2" required>
					<option value="" selected>Select Payment Type</option>
					<option value="Cash">Cash</option>
					<option value="Bank">Bank</option>
					<option value="Wallet">Wallet</option>
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
                  <input name="amount" type="number" class="form-control" placeholder="Enter Amount Here">
                  </div>
                  </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Remark</label>
                <div class="col-sm-10">
                <textarea name="remark"  class="form-control" rows="4" cols="80" ></textarea>
                </div>
             </div>

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Make Withdrawal</i></button>
								&nbsp;<?php echo ($view_account_info == 1) ? '<button name="verify" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].' pull-right"><i class="fa fa-filter">&nbsp;Verify Account</i></button>' : ''; ?>

              </div>
			  </div>
<?php
}
else{
    include("otp_confirmation.php");
}
?>	

			</form> 
			  
<?php
if(isset($_POST['verify']))
{
	$account =  mysqli_real_escape_string($link, $_POST['account']);
	echo "<script> window.location='withdraw.php?id=".$_SESSION['tid']."&&mid=".base64_encode('410')."&&uid=".$account."'; </script>";
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

</div>	
</div>	
</div>
</div>