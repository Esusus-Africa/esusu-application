<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-money"></i> Customer Withdrawal Form</h3>
            </div>
             <div class="box-body">

             	<a href="view_charges.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("520"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a>
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">
<?php
if(isset($_POST['save']))
{
	try{
		$tid = mysqli_real_escape_string($link, $_POST['postedby']);
		$ptype = "Direct-Debit";
		$account =  mysqli_real_escape_string($link, $_POST['account']);
		$date_time = date("d-m-Y");
		$final_date_time = date ('Y-m-d h:i:s', strtotime($date_time));

		$maintenance_row = mysqli_num_rows($isearch_maintenance_model);
		$sms_rate = $fetchsys_config['fax'];
    	$debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 

		if($account == "All"){

			$google_details = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id'");
			while($get_details = mysqli_fetch_array($google_details))
			{
				$fn = $get_details['fname'];
				$ln = $get_details['lname'];
				$em = $get_details['email'];
				$ph = $get_details['phone'];
				$acct = $get_details['account'];
				$bal = $get_details['balance'];
				$uname = $get_details['username'];
				$lwdate = $get_details['last_withdraw_date'];
				$sms_checker = $get_details['sms_checker'];
				$txid = 'TXID-'.uniqid().time();
				
				$sys_set = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
				$get_sys_set = mysqli_fetch_array($sys_set);
				$sys_currency = $get_sys_set['currency'];

				$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
				$fetch_memset = mysqli_fetch_array($search_memset);
				
				$charges = mysqli_real_escape_string($link, $_POST['charges']);
				$verify_charges = mysqli_query($link, "SELECT * FROM charge_management WHERE id = '$charges'");
				$fetch_verification = mysqli_fetch_object($verify_charges);
				$charges_name = $fetch_verification->charges_name;
				$ctype = $fetch_verification->charges_type;
				$cvalue = $fetch_verification->charges_value;

				//Do Percentage Calculation
				$percent = ($cvalue/100)*$bal;

				$final_charges = ($ctype == "Flatrate") ? $cvalue : $percent;
				$total = number_format(($bal - $final_charges),2,'.','');

				//DEBIT ALERT FOR THE AMOUNT WITHDRAWED
				$message = "$isenderid>>>DR";
				$message .= " Amt: ".$icurrency.$final_charges."";
				$message .= " Acc: ".$acct."";
				$message .= " Desc: ".$remark." - ".$txid."";
				$message .= " Time: ".$final_date_time."";
				$message .= " Bal: ".$icurrency.$total."";

				$max_per_page = 153;
				$sms_length = strlen($sms);
				$calc_length = ceil($sms_length / $max_per_page);
				$sms_charges = $calc_length * $sms_rate;				
				$refid = "EA-smsCharges-".rand(1000000,9999999);
				$mybalance = $iwallet_balance - $sms_charges;
				$date_time = date("Y-m-d H:i:s");
				
				if($bal < $final_charges){
					echo "<div class='alert bg-orange'>Insufficient Fund!!!</div>";
				}elseif($final_charges < 0){
					throw new UnexpectedValueException();
				}elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $final_charges) == FALSE)
				{
					throw new UnexpectedValueException();
				}else{
					
					$today = date("Y-m-d");
					$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','Withdraw-Charges','$ptype','$acct','----','$fn','$ln','$em','$ph','$final_charges','$tid','$charges_name','$final_date_time','$institution_id','$isbranchid','$icurrency','')") or die (mysqli_error($link));
					
					$update = mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$acct'") or die (mysqli_error($link));
					
					($sms_checker == "No" || $billing_type == "PAYGException" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $ph, $message, $institution_id, $txid, $sms_charges, $iuid, $mybalance, $debitWallet) : ($debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $ph, $message, $institution_id, $txid, $sms_charges, $iuid, $mybalance, $debitWallet) : "")));

					$sendSMS->chargesDirectDebitEmailNotifier($em, $txid, $final_date_time, $charges_name, $uname, $ln, $fn, $account, $icurrency, $amount, $total, $iemailConfigStatus, $ifetch_emailConfig);
					
				}
			}
			echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p></div>";

		}
		else{

			$google_details = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$account'");
			while($get_details = mysqli_fetch_array($google_details))
			{
				$fn = $get_details['fname'];
				$ln = $get_details['lname'];
				$em = $get_details['email'];
				$ph = $get_details['phone'];
				$acct = $get_details['account'];
				$bal = $get_details['balance'];
				$uname = $get_details['username'];
				$lwdate = $get_details['last_withdraw_date'];
				$sms_checker = $get_details['sms_checker'];
				$txid = 'TXID-'.uniqid().time();
				
				$sys_set = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
				$get_sys_set = mysqli_fetch_array($sys_set);
				$sys_currency = $get_sys_set['currency'];

				$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
				$fetch_memset = mysqli_fetch_array($search_memset);
				
				$charges = mysqli_real_escape_string($link, $_POST['charges']);
				$verify_charges = mysqli_query($link, "SELECT * FROM charge_management WHERE id = '$charges'");
				$fetch_verification = mysqli_fetch_object($verify_charges);
				$charges_name = $fetch_verification->charges_name;
				$ctype = $fetch_verification->charges_type;
				$cvalue = $fetch_verification->charges_value;

				//Do Percentage Calculation
				$percent = ($cvalue/100)*$bal;

				$final_charges = ($ctype == "Flatrate") ? $cvalue : $percent;
				$total = number_format(($bal - $final_charges),2,'.','');

				//DEBIT ALERT FOR THE AMOUNT WITHDRAWED
				$message = "$isenderid>>>DR";
				$message .= " Amt: ".$icurrency.$final_charges."";
				$message .= " Acc: ".$acct."";
				$message .= " Desc: ".$remark." - ".$txid."";
				$message .= " Time: ".$final_date_time."";
				$message .= " Bal: ".$icurrency.$total."";

				$max_per_page = 153;
				$sms_length = strlen($sms);
				$calc_length = ceil($sms_length / $max_per_page);
				$sms_charges = $calc_length * $sms_rate;				
				$refid = "EA-smsCharges-".rand(1000000,9999999);
				$mybalance = $iwallet_balance - $sms_charges;
				$date_time = date("Y-m-d H:i:s");
				
				if($bal < $final_charges){
					echo "<div class='alert bg-orange'>Insufficient Fund!!!</div>";
				}elseif($final_charges < 0){
					throw new UnexpectedValueException();
				}elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $final_charges) == FALSE)
				{
					throw new UnexpectedValueException();
				}else{
					$today = date("Y-m-d");
					$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','Withdraw-Charges','$ptype','$account','----','$fn','$ln','$em','$ph','$final_charges','$tid','$charges_name','$final_date_time','$institution_id','$isbranchid','$icurrency','')") or die (mysqli_error($link));
					
					$update = mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link));
							
					//DEBIT ALERT FOR WITHDRAWAL AMOUNT WHEN THE CUSTOMER IS WITHDRAWING ALMOST EVERYDAY. SO WITHDRAWAL FEE IS INCLUSIVE!
					($sms_checker == "No" || $billing_type == "PAYGException" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $ph, $message, $institution_id, $txid, $sms_charges, $iuid, $mybalance, $debitWallet) : ($debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $ph, $message, $institution_id, $txid, $sms_charges, $iuid, $mybalance, $debitWallet) : "")));
					
					$sendSMS->chargesDirectDebitEmailNotifier($em, $txid, $final_date_time, $charges_name, $uname, $ln, $fn, $account, $icurrency, $amount, $total, $iemailConfigStatus, $ifetch_emailConfig);
					
					echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Customer Ledger Balance: <b style='color: orange;'>".number_format($total,2,'.',',')."</b></p></div>";
				}
			}

		}
	}catch(UnexpectedValueException $ex)
	{
		echo "<div class='alert alert-danger'>Invalid Amount Entered!</div>";
	}
}
?>			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
                  <div class="col-sm-10">
				<select name="account"  class="form-control select2" required>
					<option value="All">All Customers</option>
<?php
$search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id'");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['account']; ?>"><?php echo $get_search['account']; ?>&nbsp; [<?php echo $get_search['fname']; ?>&nbsp;<?php echo $get_search['lname']; ?> : <?php echo number_format($get_search['balance'],2,'.',','); ?>]</option>
<?php } ?>
				</select>
				</div>
            </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Charges</label>
                  <div class="col-sm-10">
				<select name="charges"  class="form-control select2" required>
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
            
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Posted By</label>
                  <div class="col-sm-10">
				<select name="postedby" class="form-control select2" required>
					<option value="" selected>Select Staff</option>
<?php
$search = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id'");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['id']; ?>"><?php echo $get_search['name'].' '.$get_search['lname']; ?></option>
<?php } ?>
				</select>
				</div>
            </div>

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Make Charges</i></button>

              </div>
			  </div>
			
			 </form> 


</div>	
</div>	
</div>
</div>