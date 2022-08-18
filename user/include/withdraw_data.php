<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user"></i> Customer Withdrawal Form</h3>
            </div>
             <div class="box-body">
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
			  <?php echo '<div class="alert alert-info fade in" >
			  <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
  				<strong>Note that&nbsp;</strong> &nbsp;&nbsp;If the last time the customer withdraw is over 3 months, the system will not deduct Withdrawal Fee.<br>
				But if you are withdrawing for the first time Or you have been withdrawing day by day, withdrawal fee will be deducted from customers account automatically.
				<br><strong>Also Note that&nbsp;</strong> &nbsp;&nbsp;The withdrawal fee will be set by the Administrator of the System.
				</div>'?>
             <div class="box-body">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Account Number</label>
                  <div class="col-sm-10">
				<select name="account"  class="form-control select2" required>
<?php
if(isset($_GET['uid']))
{
	$searchin = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '".$_GET['uid']."'");
	while($get_searchin = mysqli_fetch_array($searchin))
	{
	?>
					<option value="<?php echo $_GET['uid']; ?>" selected><?php echo $_GET['uid']; ?>&nbsp; [<?php echo $get_searchin['fname']; ?>&nbsp;<?php echo $get_searchin['lname']; ?>]</option>
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
					<option value="<?php echo $get_search['account']; ?>"><?php echo $get_search['account']; ?>&nbsp; [<?php echo $get_search['fname']; ?>&nbsp;<?php echo $get_search['lname']; ?>]</option>
<?php } ?>
				</select>
				</div>
            </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Amount to Withdraw</label>
                  <div class="col-sm-10">
                  <input name="amount" type="number" class="form-control" placeholder="Enter Amount Here">
                  </div>
                  </div>

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn btn-primary btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="save" type="submit" class="btn btn-success btn-flat"><i class="fa fa-save">&nbsp;Make Withdrawal</i></button>
								&nbsp;<button name="verify" type="submit" class="btn btn-info pull-right"><i class="fa fa-filter">&nbsp;Verify Account</i></button>

              </div>
			  </div>
<?php
if(isset($_POST['save']))
{
	try{
		$account =  mysqli_real_escape_string($link, $_POST['account']);
		$amount = mysqli_real_escape_string($link, $_POST['amount']);
		$txid = 'TXID-'.rand(2000000,100000000);
		
		$google_details = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$account'");
		while($get_details = mysqli_fetch_array($google_details))
		{
			$fn = $get_details['fname'];
			$ln = $get_details['lname'];
			$em = $get_details['email'];
			$ph = $get_details['phone'];
			$bal = $get_details['balance'];
			$lwdate = $get_details['last_withdraw_date'];
			
			$sys_set = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
			$get_sys_set = mysqli_fetch_array($sys_set);
			$sys_currency = $get_sys_set['currency'];
			$sys_wfee = $get_sys_set['withdrawal_fee'];
			
			$now = time(); // or your date as well
			$your_date = strtotime($lwdate);
			$datediff = $now - $your_date;
			$total_day = round($datediff / (60 * 60 * 24));
			
			//SMS GATEWAY CHECKING
			$sql_alert = mysqli_query($link, "SELECT * FROM sms") or die (mysqli_error($link));
			$find_alert = mysqli_fetch_array($sql_alert);
			$status = $find_alert['status'];
			
			if($bal < $amount){
				echo "<div class='alert alert-danger'>Insufficient Fund!!!</div>";
			}elseif($total_day < 90 && $bal < ($amount + $sys_wfee))
			{
				echo "<div class='alert alert-danger'>Insufficient Fund!.....PLEASE NOTE THAT: ".$sys_currency.$sys_wfee." will be deducted as withdrawal fee !!</div>";
			}elseif($amount < 0){
				throw new UnexpectedValueException();
			}elseif($status == "NotActivated"){
				switch ($total_day) {
					case ($lwdate == "0000-00-00"):
						$total = number_format($bal - $amount - $sys_wfee,2,'.','');
						$today = date("Y-m-d");
						//This section will insert record for withdrawal charges and send an alert to the customer phone number.
						mysqli_query($link, "INSERT INTO transaction VALUES('','$txid','Withdraw-Charges','$account','$fn','$ln','$em','$ph','$sys_wfee',NOW())") or die (mysqli_error($link));
						mysqli_query($link, "INSERT INTO transaction VALUES('','$txid','Withdraw','$account','$fn','$ln','$em','$ph','$amount',NOW())") or die (mysqli_error($link));
						mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link));
						break;
					case ($total_day > 90):
						$total = number_format($bal - $amount,2,'.','');
						$today = date("Y-m-d");
						mysqli_query($link, "INSERT INTO transaction VALUES('','$txid','Withdraw','$account','$fn','$ln','$em','$ph','$amount',NOW())") or die (mysqli_error($link));
						mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link));
						break;
					default:
						echo "Error";
				}
			}else{
				include("alert_sender/withdraw_alert.php");
			}
			echo "<div class='alert alert-success'>Transaction Successfully!</div>";
		}
	}catch(UnexpectedValueException $ex)
	{
		echo "<div class='alert alert-danger'>Invalid Amount Entered!</div>";
	}
}
?>			  
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