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
		$rbon = mysqli_real_escape_string($link, $_POST['rbon']);
		$wbal = mysqli_real_escape_string($link, $_POST['wbal']);
		
		if($amount < 0){
			throw new UnexpectedValueException();
		}
		elseif(preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $amount) == FALSE)
		{
			throw new UnexpectedValueException();
		}
		elseif($amount > $rbon)
		{
			echo "<div class='alert bg-orange'>Insufficient Fund!</div>";
		}
		else{
			$new_wbal = $amount + $wbal;

			$update = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$new_wbal' WHERE institution_id = '$institution_id'") or die (mysqli_error($link));
			if(!$update)
			{
				echo "<div class='alert bg-orange'>Unable to Process Transfer.....Please try again later</div>";
			}
			else{
					echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Wallet Balance is: <b style='color: orange;'>".$icurrency.number_format($iwallet_balance,2,'.',',')."</b></p></div>";
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
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Referral Balance</label>
                  <div class="col-sm-10">
                  <input name="rbon" type="text" class="form-control" value="<?php echo number_format($ireferral_bonus,2,'.',','); ?>" readonly>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Wallet Balance</label>
                  <div class="col-sm-10">
                  <input name="wbal" type="text" class="form-control" value="<?php echo number_format($iwallet_balance,2,'.',','); ?>" readonly>
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