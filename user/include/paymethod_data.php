<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-exchange"></i> Payment Methods</h3>
            </div>
             <div class="box-body">
<?php
$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_object($select1);
	
$localpayment_charges = $row1->localpayment_charges;
$capped_amount = $row1->capped_amount;
$intlpayment_charges = $row1->intlpayment_charges;
?>
		           <div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> fade in">
		             <p class="lead" style="color: white;"><i>Kindly Choose the Payment Method to use for <b>loan repayment</b> Then proceed to Payment Page.</i></p>
		             <b style="font-size:18px;">NOTICE:</b> 
                    <p>(1). Note that for Local Loan Repayments with Mastercard, Visa, Bank Account, USSD: the charges is <b><?php echo ($localpayment_charges * 100).'%'; ?></b> fees subject to cap of <b><?php echo $row1->currency.number_format($capped_amount,2,'.',','); ?></b>
                    </p>
                    <p>(2). Note that for International Card Loan Repayments with Mastercard, Visa, AMEX: the charges is <b><?php echo ($intlpayment_charges * 100).'%'; ?></b>
                    </p>
					</div>
		             <hr>
					 
					 <form method="post" enctype="multipart/form-data">
						  
					<div class="row">
		                 <div class="col-sm-12">
		                   <div class="form-group mb-20">
		                     <label style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><strong>Choose Payment Method</strong></label>
		                     <select name="pmethod" class="form-control" required>
							   <option value="" selected>....</option>
		                       <!--<option value="Paypal">Paypal Payment</option>-->
							   <option value="Paystack">Visa / Mastercard / Verve Payment</option>
		                     </select>
		                   </div>
		                 </div>

					 	 <div class="col-sm-12">
					 	  <div class="form-group mb-20">					  
					 	<button type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" name="proceed"><i class="fa fa-exchange">&nbsp;<b>Proceed</b></i></button>
					 	</div>
					 	</div>
<?php
if(isset($_POST['proceed']))
{
	$pmethod = $_POST['pmethod'];
	
	if($pmethod == "")
	{
		echo "<script>alert('Sorry! You have not choosen any payment method'); </script>";
	}
	elseif($pmethod == "Paypal")
	{
		echo '<script>window.location="payloans_paypal.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("404").'" </script>';
	}
	elseif($pmethod == "Paystack")
	{
		echo '<script>window.location="pay.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("404").'" </script>';
	}
}	
?>
					 </div>
		             </form>         

</div>	
</div>	
</div>