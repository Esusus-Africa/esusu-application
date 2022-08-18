<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-money"></i> Customer Deposit Form</h3>
            </div>
             <div class="box-body">
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['save']))
{
	try{
		$tid = mysqli_real_escape_string($link, $_POST['postedby']);
		$ptype = mysqli_real_escape_string($link, $_POST['ptype']);
		$account =  mysqli_real_escape_string($link, $_POST['author']);
		$amount = mysqli_real_escape_string($link, $_POST['amount']);
		$remark = mysqli_real_escape_string($link, $_POST['remark']);
		$date_time = date("Y-m-d");
		$final_date_time = date ('Y-m-d h:i:s', strtotime($date_time));
		$txid = 'TXID-'.rand(2000000,100000000);

		$google_details = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$account'");
		while($get_details = mysqli_fetch_array($google_details))
		{
			$fn = $get_details['fname'];
			$ln = $get_details['lname'];
			$em = $get_details['email'];
			$ph = $get_details['phone'];
			$bal = $get_details['balance'];
			$uname = $get_details['username'];
			$total = number_format($amount + $bal,2,'.','');
			
			$search_sys = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
			$get_sys = mysqli_fetch_array($search_sys);
			$ocurrency = $get_sys['currency'];
			
			if($amount < 0){
				throw new UnexpectedValueException();
			}
			elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $amount) == FALSE)
			{
				throw new UnexpectedValueException();
			}
			else{
				$update = mysqli_query($link, "UPDATE borrowers SET balance = '$total' WHERE account = '$account'") or die (mysqli_error($link));
				$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','Deposit','$ptype','$account','----','$fn','$ln','$em','$ph','$amount','$tid','$remark','$final_date_time','$branchid','$csbranchid','$ocurrency')") or die (mysqli_error($link));
				if(!($update && $insert))
				{
					echo "<div class='alert bg-orange'>Unable to Process Transaction.....Please try again later</div>";
				}
				else{
					include("alert_sender/deposit_alert.php");
					include("email_sender/send_sdeposit_alertemail.php");
					echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Customer Ledger Balance: <b style='color: orange;'>".$ocurrency.number_format($total,2,'.',',')."</b></p></div>";
				}
			}
		}
	}catch(UnexpectedValueException $ex)
	{
		echo "<div class='alert alert-danger'>Invalid Amount Entered!</div>";
	}
}
?>
             <div class="box-body">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Account Number</label>
                  <div class="col-sm-10">
				<select name="author"  class="form-control select2" required>
<?php
if(isset($_GET['uid']))
{
	$searchin = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '".$_GET['uid']."'");
	while($get_searchin = mysqli_fetch_array($searchin))
	{
	?>
					<option value="<?php echo $_GET['uid']; ?>" selected><?php echo $_GET['uid']; ?>&nbsp; [<?php echo $get_searchin['fname']; ?>&nbsp;<?php echo $get_searchin['lname']; ?> : <?php echo $ocurrency.number_format($get_searchin['balance'],2,'.',','); ?>]</option>
<?php
}
}else{
	?>
					<option selected>Select Customer Account</option>
<?php } ?>
<?php
$search = mysqli_query($link, "SELECT * FROM borrowers");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['account']; ?>"><?php echo $get_search['account']; ?>&nbsp; [<?php echo $get_search['fname']; ?>&nbsp;<?php echo $get_search['lname']; ?> : <?php echo $ocurrency.number_format($get_search['balance'],2,'.',','); ?>]</option>
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
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Amount to Deposit</label>
                  <div class="col-sm-10">
                  <input name="amount" type="number" class="form-control" placeholder="Enter Amount Here">
                  </div>
                  </div>
                    
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Posted By</label>
                  <div class="col-sm-10">
				<select name="postedby"  class="form-control select2">
					<option selected>Select Staff</option>
<?php
$search = mysqli_query($link, "SELECT * FROM user");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['id']; ?>"><?php echo $get_search['name']; ?></option>
<?php } ?>
				</select>
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
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Deposit</i></button>
								&nbsp;<?php echo ($view_account_info == 1) ? '<button name="verify" type="submit" class="btn bg-orange pull-right"><i class="fa fa-filter">&nbsp;Verify Account</i></button>' : ''; ?>

              </div>
			  </div>		
			
			 </form> 

<?php
if(isset($_POST['verify']))
{
	$account =  mysqli_real_escape_string($link, $_POST['author']);
	echo "<script> window.location='deposit.php?id=".$_SESSION['tid']."&&mid=".base64_encode('410')."&&uid=".$account."'; </script>";
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