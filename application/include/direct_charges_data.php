<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-money"></i> Charges Form</h3>
            </div>
             <div class="box-body">

             	<a href="view_charges.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("520"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 
            
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
		$txid = 'TXID-'.rand(2000000,100000000);
		
		if($account == "All"){

			$google_details = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = ''");
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
			
			$sys_set = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
			$get_sys_set = mysqli_fetch_array($sys_set);
			$sys_currency = $get_sys_set['currency'];
			//$sys_wfee = $get_sys_set['withdrawal_fee'];
			$sys_abb = $get_sys_set['abb'];
			
			$charges = mysqli_real_escape_string($link, $_POST['charges']);
			$verify_charges = mysqli_query($link, "SELECT * FROM charge_management WHERE id = '$charges'");
			$fetch_verification = mysqli_fetch_object($verify_charges);
			$charges_name = $fetch_verification->charges_name;
			$ctype = $fetch_verification->charges_type;
			$cvalue = $fetch_verification->charges_value;

			//Do Percentage Calculation
			$percent = ($cvalue/100)*$bal;

			$final_charges = ($ctype == "Flatrate") ? $cvalue : $percent;
			
			if($bal < $final_charges){
				echo "<div class='alert bg-orange'>Insufficient Fund!!!</div>";
			}elseif($final_charges < 0){
				throw new UnexpectedValueException();
			}elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $final_charges) == FALSE)
			{
				throw new UnexpectedValueException();
			}else{
				$total = number_format(($bal - $final_charges),2,'.','');
				$today = date("Y-m-d");
				$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','Withdraw-Charges','$ptype','$acct','----','$fn','$ln','$em','$ph','$final_charges','$tid','$charges_name','$final_date_time','','$csbranchid')") or die (mysqli_error($link));
				
				$update = mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$acct'") or die (mysqli_error($link));
						
				include("alert_sender/charge_alert.php");
				//include("email_sender/send_directdebit_alertemail.php");
			
			}
		}
		echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p></div>";

		}else{

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
			
			$sys_set = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
			$get_sys_set = mysqli_fetch_array($sys_set);
			$sys_currency = $get_sys_set['currency'];
			//$sys_wfee = $get_sys_set['withdrawal_fee'];
			$sys_abb = $get_sys_set['abb'];
			
			$charges = mysqli_real_escape_string($link, $_POST['charges']);
			$verify_charges = mysqli_query($link, "SELECT * FROM charge_management WHERE id = '$charges'");
			$fetch_verification = mysqli_fetch_object($verify_charges);
			$charges_name = $fetch_verification->charges_name;
			$ctype = $fetch_verification->charges_type;
			$cvalue = $fetch_verification->charges_value;

			//Do Percentage Calculation
			$percent = ($cvalue/100)*$bal;

			$final_charges = ($ctype == "Flatrate") ? $cvalue : $percent;
			
			if($bal < $final_charges){
				echo "<div class='alert bg-orange'>Insufficient Fund!!!</div>";
			}elseif($final_charges < 0){
				throw new UnexpectedValueException();
			}elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $final_charges) == FALSE)
			{
				throw new UnexpectedValueException();
			}else{
				$total = number_format(($bal - $final_charges),2,'.','');
				$today = date("Y-m-d");
				$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','Withdraw-Charges','$ptype','$account','----','$fn','$ln','$em','$ph','$final_charges','$tid','$charges_name','$final_date_time','','$csbranchid')") or die (mysqli_error($link));
				
				$update = mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link));
						
				if(!($insert && $update))
				{
					echo "<div class='alert bg-orange'>Transaction not Successful!!</div>";
				}
				else{
					//DEBIT ALERT FOR WITHDRAWAL AMOUNT WHEN THE CUSTOMER IS WITHDRAWING ALMOST EVERYDAY. SO WITHDRAWAL FEE IS INCLUSIVE!
					include("alert_sender/charge_alert.php");
					include("email_sender/send_directdebit_alertemail.php");
					echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Customer Ledger Balance: <b style='color: orange;'>".number_format($total,2,'.',',')."</b></p></div>";
				}
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
					<option value="All">All Customers</option>
<?php
$search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = ''");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['account']; ?>"><?php echo $get_search['account']; ?>&nbsp; [<?php echo $get_search['fname']; ?>&nbsp;<?php echo $get_search['lname']; ?> : <?php echo number_format($get_search['balance'],2,'.',','); ?>]</option>
<?php } ?>
				</select>
				</div>
            </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Charges</label>
                  <div class="col-sm-10">
				<select name="charges"  class="form-control select2" required>
					<option selected>Select Charges</option>
					<?php
					$search_mycharges = mysqli_query($link, "SELECT * FROM charge_management WHERE companyid = ''");
					while($fetch_mycharges = mysqli_fetch_object($search_mycharges))
					{
					?>
					<option value="<?php echo $fetch_mycharges->id; ?>"><?php echo $fetch_mycharges->charges_name.'('.$fetch_mycharges->charges_value.' - ['.$fetch_mycharges->charges_type.'])'; ?></option>
				<?php } ?>
				</select>
				</div>
            </div>
            
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Posted By</label>
                  <div class="col-sm-10">
				<select name="postedby"  class="form-control select2">
					<option selected>Select Staff</option>
<?php
$search = mysqli_query($link, "SELECT * FROM user WHERE created_by = ''");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['id']; ?>"><?php echo $get_search['name']; ?></option>
<?php } ?>
				</select>
				</div>
            </div>

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Make Charges</i></button>

              </div>
			  </div>
			
			 </form> 


</div>	
</div>	
</div>
</div>