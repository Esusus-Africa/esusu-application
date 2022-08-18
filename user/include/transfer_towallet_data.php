<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-money"></i> Transfer Form</h3>
            </div>
             <div class="box-body">
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['save']))
{
	try{
		$amount = mysqli_real_escape_string($link, $_POST['amount']);
		$date_time = date("Y-m-d");
		$final_date_time = date ('Y-m-d h:i:s', strtotime($date_time));
		$txid = 'TXID-'.rand(2000000,100000000);

		$google_details = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acctno'");
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
			elseif($amount > $binvest_bal)
			{
				echo "<div class='alert bg-orange'>Insufficient Fund!</div>";
			}
			else{
				$new_invbal = $binvest_bal - $amount;
				$new_walbal = $get_details['wallet_balance'] + $amount;

				$update = mysqli_query($link, "UPDATE borrowers SET investment_bal = '$new_invbal', wallet_balance = '$new_walbal' WHERE account = '$acctno'") or die (mysqli_error($link));
				$insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$txid','$acctno','$amount','$bbcurrency','Self','Self Transfer from Investment Balance to Super Wallet','pending',NOW(),'$acctno','$bwallet_balance')");
				$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','Transfer','Wallet','$acctno','----','$fn','$ln','$em','$ph','$amount','Self','Self Transfer from Investment Balance to Super Wallet','$final_date_time','$bbranchid','$bsbranchid','$bbcurrency')") or die (mysqli_error($link));
				if(!($update && $insert))
				{
					echo "<div class='alert bg-orange'>Unable to Process Transfer.....Please try again later</div>";
				}
				else{
					echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Wallet Balance is: <b style='color: orange;'>".$bbcurrency.number_format($bwallet_balance,2,'.',',')."</b></p></div>";
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
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Investment Balance</label>
                  <div class="col-sm-10">
                  <input name="sbal" type="text" class="form-control" value="<?php echo number_format($binvest_bal,2,'.',','); ?>" readonly>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Wallet Balance</label>
                  <div class="col-sm-10">
                  <input name="wbal" type="text" class="form-control" value="<?php echo number_format($bwallet_balance,2,'.',','); ?>" readonly>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount to Transfer</label>
                  <div class="col-sm-10">
                  <input name="amount" type="text" class="form-control" placeholder="Enter Amount to Transfer to your wallet"/required>
                  </div>
                  </div>

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                <button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Transfer</i></button>
              </div>
			  </div>		
			
			 </form>


</div>	
</div>	
</div>
</div>