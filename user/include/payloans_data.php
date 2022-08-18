<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-paypal"></i> Paypal Payment Form</h3>
            </div>
             <div class="box-body">
            			
		           <div class="alert bg-blue fade in">
		             <p class="lead" style="color: orange;"><i>Kindly Validate your Payment by clicking on <b>"Validate Pay First"</b> Then proceed to use the form below the button to pay your Outstanding Loan Payment.</i></p>
					</div>
		             <hr>

<?php
if(isset($_GET['lid']) == true){
	$acn = $_GET['acn'];
	$search_pay = mysqli_query($link, "SELECT * FROM payments WHERE remarks = 'pending' AND account_no = '$acn'");
	$get_pay = mysqli_fetch_object($search_pay);
	
	$search_id = mysqli_query($link, "SELECT * FROM pay_schedule WHERE tid = '$acn'");
	$get_id = mysqli_fetch_object($search_id);
?>		             <!-- Paypal Both Onetime/Recurring Form Starts -->
		             <form id="paypal_donate_form_onetime_recurring">
		               <div class="row">
						   
						 <div class="col-sm-12">
			   			<div class="form-group mb-20">
			                     <label style="color:blue;">Loan ID</label>
			                     <input name="acte" type="text" class="form-control" value="<?php echo $get_pay->lid; ?>">
			                     </div>
			                     </div>
					
		 <input name="customer" type="hidden" id="customer" class="form-control" value="<?php echo $get_pay->fname.'&nbsp;'.$get_pay->lname; ?>">
		 <input name="account" type="hidden" id="account" class="form-control" value="<?php echo $get_pay->account; ?>">
		 <input name="loan" type="hidden" id="loan" class="form-control" value="<?php echo $get_pay->balance; ?>">
						  
   			 			 	<div class="col-sm-12">
			   			  <div class="form-group mb-20">
			                   <label style="color:blue;">Payment Date</label>
			                 <div class="input-group date">
			                     <div class="input-group-addon">
			                       <i class="fa fa-calendar"></i>
			                     </div>
			                     <input type="date" class="form-control pull-right" name="pay_date" value="<?php echo $get_pay->pay_date; ?>">
			                   </div>
			                 </div>
			   			  </div>

		                 <div class="col-md-12">
		                   <div class="form-group mb-20">
		                     <label style="color:blue;"><strong>Payment Type</strong></label> <br>
		                     <label class="radio-inline">
		                       <input type="radio" checked="" value="one_time" name="payment_type"> 
		                       One Time
		                     </label>
		                     <label class="radio-inline">
		                       <input type="radio" value="recurring" name="payment_type"> 
		                       Recurring
		                     </label>
		                   </div>
		                 </div>

		                 <div class="col-sm-12" id="donation_type_choice">
		                   <div class="form-group mb-20">
		                     <label style="color:blue;"><strong>Payment Scheduling Type</strong></label>
		                     <div class="radio mt-5">
		                       <label class="radio-inline">
		                         <input type="radio" value="D" name="t3" checked="">
		                         Daily</label>
		                       <label class="radio-inline">
		                         <input type="radio" value="W" name="t3">
		                         Weekly</label>
		                       <label class="radio-inline">
		                         <input type="radio" value="M" name="t3">
		                         Monthly</label>
		                       <label class="radio-inline">
		                         <input type="radio" value="Y" name="t3">
		                         Yearly</label>
		                     </div>
		                   </div>
		                 </div>

		                 <div class="col-sm-12">
		                   <div class="form-group mb-20">
		                     <label style="color:blue;"><strong>I Want to Pay for</strong></label>
		                     <select name="item_name" class="form-control">
		                       <option value="Loan Outstanding">Loan Outstanding</option>
		                     </select>
		                   </div>
		                 </div>

		                 <div class="col-sm-12">
		                   <div class="form-group mb-20">
		                     <label style="color:blue;"><strong>Currency</strong></label>
		                     <select name="currency_code" class="form-control">
		                       <option value="">Select Currency</option>
		                       <option value="USD" selected="selected">USD - U.S. Dollars</option>
		                       <option value="AUD">AUD - Australian Dollars</option>
		                       <option value="BRL">BRL - Brazilian Reais</option>
		                       <option value="GBP">GBP - British Pounds</option>
		                       <option value="HKD">HKD - Hong Kong Dollars</option>
		                       <option value="HUF">HUF - Hungarian Forints</option>
		                       <option value="INR">INR - Indian Rupee</option>
		                       <option value="ILS">ILS - Israeli New Shekels</option>
		                       <option value="JPY">JPY - Japanese Yen</option>
		                       <option value="MYR">MYR - Malaysian Ringgit</option>
		                       <option value="MXN">MXN - Mexican Pesos</option>
		                       <option value="TWD">TWD - New Taiwan Dollars</option>
		                       <option value="NZD">NZD - New Zealand Dollars</option>
		                       <option value="NOK">NOK - Norwegian Kroner</option>
		                       <option value="PHP">PHP - Philippine Pesos</option>
		                       <option value="PLN">PLN - Polish Zlotys</option>
		                       <option value="RUB">RUB - Russian Rubles</option>
		                       <option value="SGD">SGD - Singapore Dollars</option>
		                       <option value="SEK">SEK - Swedish Kronor</option>
		                       <option value="CHF">CHF - Swiss Francs</option>
		                       <option value="THB">THB - Thai Baht</option>
		                       <option value="TRY">TRY - Turkish Liras</option>
		                     </select>
		                   </div>
		                 </div>

		                 <div class="col-sm-12">
		                   <div class="form-group mb-20">
		                     <label style="color:blue;"><strong>How much do you want to Pay?</strong>  <span style="color: orange;"> Here is the Amount to Pay Now: <b><?php echo number_format($get_pay->amount_to_pay,2,'.',','); ?> </b></span> | Balance:<span style="color: orange;">  <b><?php echo number_format($get_pay->loan_bal,2,'.',','); ?></b></span></label>
		                     <select name="amount" class="form-control">
		                         <option value="<?php echo $get_pay->amount_to_pay; ?>" selected><?php echo $get_pay->amount_to_pay; ?></option>
								 <option value="20">20</option>
		                         <option value="50">50</option>
		                         <option value="100">100</option>
		                         <option value="200">200</option>
		                         <option value="500">500</option>
		                         <option value="other">Other Amount</option>
		                     </select>
		                     <div id="custom_other_amount">
		                       <label style="color:blue"><strong>Custom Amount:</strong></label>
		                     </div>
		                   </div>
		                 </div>

		                 <div class="col-sm-12">
		                   <div class="form-group mb-20">
		                     <input type="image" src="../img/payment-button.png" name="submit" alt="PayPal - The safer, easier way to pay online!" width="150" height="40">
		                   </div>
		                 </div>
		               </div>
		             </form>
					 
		             <!-- Script for Donation Form Custom Amount -->
		             <script type="text/javascript">
		               $(document).ready(function(e) {
		                 var $donation_form = $("#paypal_donate_form_onetime_recurring");
		                 //toggle custom amount
		                 var $custom_other_amount = $donation_form.find("#custom_other_amount");
		                 $custom_other_amount.hide();
		                 $donation_form.find("select[name='amount']").change(function() {
		                     var $this = $(this);
		                     if ($this.val() == 'other') {
		                       $custom_other_amount.show().append('<div class="input-group"><span class="input-group-addon">$</span> <input id="input_other_amount" type="text" name="amount" class="form-control" value="100"/></div>');
		                     }
		                     else{
		                       $custom_other_amount.children( ".input-group" ).remove();
		                       $custom_other_amount.hide();
		                     }
		                 });

		                 //toggle donation_type_choice
		                 var $donation_type_choice = $donation_form.find("#donation_type_choice");
		                 $donation_type_choice.hide();
		                 $("input[name='payment_type']").change(function() {
		                     if (this.value == 'recurring') {
		                         $donation_type_choice.show();
		                     }
		                     else {
		                         $donation_type_choice.hide();
		                     }
		                 });


		                 // submit form on click
		                 $donation_form.on('submit', function(e){
		                         $( "#paypal_donate_form-onetime" ).submit();
		                     var item_name = $donation_form.find("select[name='item_name'] option:selected").val();
		                     var currency_code = $donation_form.find("select[name='currency_code'] option:selected").val();
		                     var amount = $donation_form.find("select[name='amount'] option:selected").val();
		                     var t3 = $donation_form.find("input[name='t3']:checked").val();

		                     if ( amount == 'other') {
		                       amount = $donation_form.find("#input_other_amount").val();
		                     }

		                     // submit proper form now
		                     if ( $("input[name='payment_type']:checked", $donation_form).val() == 'recurring' ) {
		                         var recurring_form = $('#paypal_donate_form-recurring');

		                         recurring_form.find("input[name='item_name']").val(item_name);
		                         recurring_form.find("input[name='currency_code']").val(currency_code);
		                         recurring_form.find("input[name='a3']").val(amount);
		                         recurring_form.find("input[name='t3']").val(t3);

		                         recurring_form.find("input[type='submit']").trigger('click');

		                     } else if ( $("input[name='payment_type']:checked", $donation_form).val() == 'one_time' ) {
		                         var onetime_form = $('#paypal_donate_form-onetime');

		                         onetime_form.find("input[name='item_name']").val(item_name);
		                         onetime_form.find("input[name='currency_code']").val(currency_code);
		                         onetime_form.find("input[name='amount']").val(amount);

		                         onetime_form.find("input[type='submit']").trigger('click');
								 
		                     }
		                     return false;
		                 });

		               });
		             </script>

<?php
$system_settin = mysqli_query($link, "SELECT * FROM systemset");
$get_settin = mysqli_fetch_array($system_settin);
?>

		             <!-- Paypal Onetime Form -->
		             <form id="paypal_donate_form-onetime" class="hidden" action="https://www.paypal.com/cgi-bin/webscr" method="post">
		               <input type="hidden" name="cmd" value="_donations">
		               <input type="hidden" name="business" value="<?php echo $get_settin['paypal_email']; ?>">

		               <input type="hidden" name="item_name" value="Loan Outstanding"> <!-- updated dynamically -->
		               <input type="hidden" name="currency_code" value="USD"> <!-- updated dynamically -->
		               <input type="hidden" name="amount" value="<?php echo $get_pay->amount_to_pay; ?>"> <!-- updated dynamically -->

		               <input type="hidden" name="no_shipping" value="1">
		               <input type="hidden" name="cn" value="Comments...">
		               <input type="hidden" name="tax" value="0">
		               <input type="hidden" name="lc" value="US">
		               <input type="hidden" name="bn" value="PP-DonationsBF">
		               <input type="hidden" name="return" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/loan/user/thankyou.php">
	               	   <input type="hidden" name="cancel_return" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/loan/user/list_payloans.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA0">
	                   <input type="hidden" name="notify_url" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/loan/notifypayment.php?temp_id=<?php echo $get_id['id']; ?>&&payid=<?php echo $get_pay['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&bal=<?php echo $get_pay['loan_bal']; ?>&&prefid=<?php echo "P".rand(100000000000000, 999999999999999); ?>">
		               <input type="submit" name="submit">
		             </form>
            
		             <!-- Paypal Recurring Form -->
		             <form id="paypal_donate_form-recurring" class="hidden" action="https://www.paypal.com/cgi-bin/webscr" method="post">
		               <input type="hidden" name="cmd" value="_xclick-subscriptions">
		               <input type="hidden" name="business" value="<?php echo $get_settin['paypal_email']; ?>">

		               <input type="hidden" name="item_name" value="Loan Outstanding"> <!-- updated dynamically -->
		               <input type="hidden" name="currency_code" value="USD"> <!-- updated dynamically -->
		               <input type="hidden" name="a3" value="<?php echo $get_pay->amount_to_pay; ?>"> <!-- updated dynamically -->
		               <input type="hidden" name="t3" value="D"> <!-- updated dynamically -->


		               <input type="hidden" name="p3" value="1">
		               <input type="hidden" name="rm" value="2">
		               <input type="hidden" name="src" value="1">
		               <input type="hidden" name="sra" value="1">
		               <input type="hidden" name="no_shipping" value="0">
		               <input type="hidden" name="no_note" value="1">                     
		               <input type="hidden" name="lc" value="US">
		               <input type="hidden" name="bn" value="PP-DonationsBF">
		               <input type="hidden" name="return" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/loan/user/thankyou.php">
		               <input type="hidden" name="cancel_return" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/loan/user/list_payloans.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA0">
		               <input type="hidden" name="notify_url" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/loan/notifypayment.php?temp_id=<?php echo $get_id['id']; ?>&&payid=<?php echo $get_pay['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&bal=<?php echo $get_pay['loan_bal']; ?>&&prefid=<?php echo "P".rand(100000000000000, 999999999999999); ?>">
		               <input type="submit" name="submit">
		             </form>
		             <!-- Paypal Both Onetime/Recurring Form Ends -->
										 
<?php
}else{
?>
					<!-- Paypal Both Onetime/Recurring Form Starts -->
							             <form id="paypal_donate_form_onetime_recurring" method="post" enctype="multipart/form-data">
							               <div class="row">
						   
											 <div class="col-sm-12">
								   			<div class="form-group mb-20">
								                     <label style="color:blue;">Loan ID</label>
								                     <input name="acte" type="text" class="form-control" placeholder="Loan ID" required>
								                     </div>
								                     </div>
					
						                <?php
										$acn = $_GET['acn'];
						   				$get = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'") or die (mysqli_error($link));
						   				$rows = mysqli_fetch_array($get);
										$sql = mysqli_query($link, "select * from pay_schedule where status = 'UNPAID' AND tid='$acn'");
										$row = mysqli_fetch_array($sql)
						   				?>
							 <input name="customer" type="hidden" id="customer" class="form-control" value="<?php echo $rows['fname'].'&nbsp;'.$rows['lname']; ?>">
							 <input name="account" type="hidden" id="account" class="form-control" value="<?php echo $rows['account']; ?>">
							 <input name="loan" type="hidden" id="loan" class="form-control" value="<?php echo $row['balance']; ?>">
							 <input name="amount_topay" type="hidden" id="amount_topay" class="form-control" value="<?php echo $row['payment']; ?>">
						  
					   			 			 	<div class="col-sm-12">
								   			  <div class="form-group mb-20">
								                   <label style="color:blue;">Payment Date</label>
								                 <div class="input-group date">
								                     <div class="input-group-addon">
								                       <i class="fa fa-calendar"></i>
								                     </div>
								                     <input type="date" class="form-control pull-right" name="pay_date">
								                   </div>
								                 </div>
								   			  </div>
											  
	 <div class="col-sm-12">
	  <div class="form-group mb-20">					  
	<button type="submit" class="btn bg-orange" name="val_submit"><i class="fa fa-search">&nbsp;<b>Validate Pay First</b></i></button>
	</div>
	</div>
<?php
if(isset($_POST['val_submit'])){
	$tid = $_SESSION['tid'];
	$lid = $_POST['acte'];
	$account = $_POST['account'];
	$customer = $_POST['customer'];
	$loan_bal = $_POST['loan'];
	$pay_date = $_POST['pay_date'];
	$amount_topay = $_POST['amount_topay'];
	$remark = "pending";
	
	$verify_loanid = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' AND status = 'UNPAID'");
	if(mysqli_num_rows($verify_loanid) == 0){
		echo "<script>alert('Invalid Loan ID'); </script>";
	}else{
	    $fetch_loanide = mysqli_fetch_array($verify_loanid);
	    $myvendorid = $fetch_loanide['vendorid'];
		$insert = mysqli_query($link, "INSERT INTO payments VALUES('','Self','$lid','','$account','$customer','$loan_bal','$pay_date','$amount_topay','$remark','$bbranchid','$myvendorid')");
		if(!$insert){
			echo "<script>alert('Unable to Verify Payment'); </script>";
		}else{
			echo "<script>alert('Payment Verify Successfully!...Click to Proceed!!'); </script>";
			echo "<script>window.location='payloans_paypal.php?tid=".$_SESSION['tid']."&&acn=".$account."&&lid=".$lid."&&mid=".base64_encode("404")."'; </script>";
		}
	}
}
?>
							               </div>
							             </form>
										 
		            <?php } ?>
            
		             


</div>	
</div>	
</div>
</div>