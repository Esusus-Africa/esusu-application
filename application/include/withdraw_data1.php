<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <?php
            $fetch_role = mysqli_fetch_object($verify_role);
			$balance = $fetch_role->balance;
			?>
            <h3 class="panel-title"><i class="fa fa-money"></i> Customer Withdrawal Form</h3>
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
		$account =  mysqli_real_escape_string($link, $_POST['account']);
		$ptype = mysqli_real_escape_string($link, $_POST['ptype']);
		$amount = mysqli_real_escape_string($link, $_POST['amount']);
		$remark = mysqli_real_escape_string($link, $_POST['remark']);
		$txid = 'TXID-'.rand(2000000,100000000);
		$myotp = myotp(6);
		
		$google_details = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$account'");
		while($get_details = mysqli_fetch_array($google_details))
		{
			$fn = $get_details['fname'];
			$ln = $get_details['lname'];
			$em = $get_details['email'];
			$ph = $get_details['phone'];
			$bal = $get_details['balance'];
			$uname = $get_details['username'];
			$lwdate = $get_details['last_withdraw_date'];
			$opt_validation = $get_details['opt_option'];

			$sys_set = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
			$get_sys_set = mysqli_fetch_array($sys_set);
			$sys_currency = $get_sys_set['currency'];
			//$sys_wfee = $get_sys_set['withdrawal_fee'];
			$sys_abb = $get_sys_set['abb'];

			//Get Remain Balance After Deposit
			$remain_balance = $balance + $amount;

			$charges = mysqli_real_escape_string($link, $_POST['charges']);
			$verify_charges = mysqli_query($link, "SELECT * FROM charge_management WHERE id = '$charges'");
			$fetch_verification = mysqli_fetch_object($verify_charges);
			$ctype = $fetch_verification->charges_type;
			$cvalue = $fetch_verification->charges_value;

			//Do Percentage Calculation
			$percent = ($cvalue/100)*$amount;

			$final_charges = ($ctype == "Flatrate") ? $cvalue : $percent;
			
			if($bal < $amount){
				echo "<div class='alert bg-orange'>Insufficient Fund!!!</div>";
			}elseif($bal < ($amount + $final_charges))
			{
				echo "<div class='alert bg-orange'>Insufficient Fund!.....</div>";
			}elseif($amount < 0){
				throw new UnexpectedValueException();
			}elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $amount) == FALSE)
			{
				throw new UnexpectedValueException();
			}elseif($charges != "" && $opt_validation == "No"){
				$total = number_format(($bal - $amount - $final_charges),2,'.','');
				$semi_total = number_format(($bal - $amount),2,'.','');
				$today = date("Y-m-d");
				$t_type = "Withdraw";
				$t_type1 = "Withdraw-Charges";
				$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','$t_type1','$ptype','$account','----','$fn','$ln','$em','$ph','$final_charges','$session_id','Charges',NOW(),'$branchid','$csbranchid','$sys_currency')") or die (mysqli_error($link));
				
				$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','$t_type','$ptype','$account','----','$fn','$ln','$em','$ph','$amount','$session_id','$remark',NOW(),'$branchid','$csbranchid','$sys_currency')") or die (mysqli_error($link));
				
				$update = mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link));
				
				$update = mysqli_query($link, "UPDATE till_account SET balance = '$remain_balance' WHERE cashier = '$session_id'");

				if(!($insert && $update))
				{
					echo "<div class='alert bg-orange'>Transaction not Successful!!</div>";
				}
				else{
					include("alert_sender/withdraw_alert.php");
					include("email_sender/send_swithdrawal_alertemail.php");
					echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Customer Ledger Balance: <b style='color: orange;'>".$acurrency.number_format($total,2,'.',',')."</b></p></div>";
				}
			}
			elseif($charges != "" && $opt_validation == "Yes"){
				$total = number_format(($bal - $amount - $final_charges),2,'.','');
				$semi_total = number_format(($bal - $amount),2,'.','');
				$today = date("Y-m-d");
				$insert = mysqli_query($link, "INSERT INTO transaction_verification VALUES(null,'$txid','Withdraw-Charges','$ptype','$account','$uname','----','$fn','$ln','$em','$ph','$final_charges','$session_id','Charges',NOW(),'$branchid','$csbranchid','$sys_currency','$total','$remain_balance','$myotp')") or die (mysqli_error($link));
				
				$insert = mysqli_query($link, "INSERT INTO transaction_verification VALUES(null,'$txid','Withdraw','$ptype','$account','$uname','----','$fn','$ln','$em','$ph','$amount','$session_id','$remark',NOW(),'$branchid','$csbranchid','$sys_currency','$total','$remain_balance','$myotp')") or die (mysqli_error($link));

				if(!$insert)
				{
					echo "<div class='alert bg-orange'>Transaction not Successful!!</div>";
				}
				else{
					include("alert_sender/withdraw_otpalert1.php");
					echo "<script>window.location='confirm_transaction.php?id=".$_SESSION['tid']."&&mid=NDEw'; </script>";
				}
			}
			elseif($charges == "" && $opt_validation == "No"){
				$total = number_format(($bal - $amount),2,'.','');
				$today = date("Y-m-d");
				$t_type = "Withdraw";
				$t_type1 = "";
				$insert1 = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','$t_type','$ptype','$account','----','$fn','$ln','$em','$ph','$amount','$session_id','$remark',NOW(),'$branchid','$csbranchid','$sys_currency')") or die (mysqli_error($link));
				
				$update1 = mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link));
				
				$update = mysqli_query($link, "UPDATE till_account SET balance = '$remain_balance' WHERE cashier = '$session_id'");

				if(!($insert1 && $update1))
				{
					echo "<div class='alert bg-orange'>Transaction not Successful!!</div>";
				}
				else{
					//DEBIT ALERT FOR WITHDRAWAL AMOUNT ONLY SINCE THE LAST DAY THE CUSTOMER WITHDRAW IS OVER 3 MONTHS. SO WITHDRAWAL FEE IS EXCLUSIVE!
					include("alert_sender/withdraw_alert1.php");
					include("email_sender/send_swithdrawal_alertemail.php");
					echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Customer Ledger Balance: <b style='color: orange;'>".$acurrency.number_format($total,2,'.',',')."</b></p></div>";
				}
			}
			elseif($charges == "" && $opt_validation == "Yes"){
				$total = number_format(($bal - $amount),2,'.','');
				$today = date("Y-m-d");
				$insert1 = mysqli_query($link, "INSERT INTO transaction_verification VALUES(null,'$txid','Withdraw','$ptype','$account','$uname','----','$fn','$ln','$em','$ph','$amount','$session_id','$remark',NOW(),'$branchid','$csbranchid','$sys_currency','$total','$remain_balance','$myotp')") or die (mysqli_error($link));

				if(!$insert1)
				{
					echo "<div class='alert bg-orange'>Transaction not Successful!!</div>";
				}
				else{
					include("alert_sender/withdraw_otpalert1.php");
					echo "<script>window.location='confirm_transaction.php?id=".$_SESSION['tid']."&&mid=NDEw'; </script>";
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
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Account Number</label>
                  <div class="col-sm-10">
				<select name="account"  class="form-control select2" required>
<?php
if(isset($_GET['uid']))
{
	$searchin = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '".$_GET['uid']."'");
	while($get_searchin = mysqli_fetch_array($searchin))
	{
	?>
					<option value="<?php echo $_GET['uid']; ?>" selected><?php echo $_GET['uid']; ?>&nbsp; [<?php echo $get_searchin['fname']; ?>&nbsp;<?php echo $get_searchin['lname']; ?> : <?php echo $sys_currency.number_format($get_searchin['balance'],2,'.',','); ?>]</option>
<?php
}
}else{
	?>
					<option selected>Select Customer Account to Withdraw from</option>
<?php } ?>
<?php
$search = mysqli_query($link, "SELECT * FROM borrowers");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['account']; ?>"><?php echo $get_search['account']; ?>&nbsp; [<?php echo $get_search['fname']; ?>&nbsp;<?php echo $get_search['lname']; ?> : <?php echo $sys_currency.number_format($get_search['balance'],2,'.',','); ?>]</option>
<?php } ?>
				</select>
				</div>
            </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Payment Type</label>
                  <div class="col-sm-10">
				<select name="ptype"  class="form-control select2" required>
					<option selected>Select Payment Type</option>
					<option value="Cash">Cash</option>
					<option value="Bank">Bank</option>
				</select>
				</div>
            </div>

            <?php
            if($enable_charges == 1)
            {
            ?>
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Charges</label>
                  <div class="col-sm-10">
				<select name="charges"  class="form-control select2" required>
					<option selected>Select Charges</option>
					<?php
					$search_mycharges = mysqli_query($link, "SELECT * FROM charge_management WHERE companyid = '$agentid'");
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
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-10">
                  <input name="amount" type="text" class="form-control" placeholder="Enter Amount Here">
                  </div>
                  </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Remark</label>
                <div class="col-sm-10">
                <textarea name="remark"  class="form-control" rows="4" cols="80" ></textarea>
                </div>
             </div>

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Make Withdrawal</i></button>
								&nbsp;<?php echo ($view_account_info == 1) ? '<button name="verify" type="submit" class="btn bg-orange pull-right"><i class="fa fa-filter">&nbsp;Verify Account</i></button>' : ''; ?>

              </div>
			  </div>
			  
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
			
			 </form> 


</div>	
</div>	
</div>
</div>