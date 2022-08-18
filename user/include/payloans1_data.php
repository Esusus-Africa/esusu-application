<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-exchange"></i> Payment Form</h3>
            </div>
             <div class="box-body">
            			
		           <div class="alert alert-info fade in">
		             <p class="lead" style="color: #FFFFFFF;"><i>Kindly click the <b>Payment Button</b> to payback your Campaign Lend Money.</i></p>
					</div>
		             <hr>

<?php
if(isset($_GET['id']) == true){
	$id = $_GET['id'];
	$search_cpay = mysqli_query($link, "SELECT * FROM campaign_pay_history WHERE id = '$id'");
	$get_cpay = mysqli_fetch_object($search_cpay);
	$tid = $_SESSION['tid'];
	$lpay_id = "LPD-".rand(1000000,10000000);
	$pid = $get_cpay->pid;
	$c_id = $get_cpay->c_id;
	$amount = $get_cpay->amount;
	$lender_name = $get_cpay->name;
	$lender_email = $get_cpay->email;
	$expected_date = $get_cpay->date_to;
	$lstatus = "Pending";
	
	$verify_lend = mysqli_query($link, "SELECT * FROM campaign_lendpay_history WHERE pid = '$pid'");
	if(mysqli_num_rows($verify_lend) == 0){
		$insert_lpay = mysqli_query($link, "INSERT INTO campaign_lendpay_history VALUES('','$tid','$lpay_id','$pid','$c_id','$amount','$lender_name','$lender_email',NOW(),'$expected_date','$lstatus','')");
	}else{
		echo "";
	}
	$get_lpay = mysqli_fetch_object($verify_lend);
?>		             <!-- Paypal Both Onetime/Recurring Form Starts -->
		             <form id="paypal_donate_form_onetime_recurring">
		               <div class="row">
						   
		                 <div class="col-md-12">
		                   <div class="form-group mb-20">
		                     <label style="color:#009900"><strong>Payment Type</strong></label> <br>
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
		                     <label style="color:#009900"><strong>Payment Scheduling Type</strong></label>
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
		                     <label style="color:#009900"><strong>I Want to Pay for</strong></label>
		                     <select name="item_name" class="form-control">
		                       <option value="Campaign Lend Fund - <?php echo $pid; ?> - <?php echo $get_lpay->lpay_id; ?>">Campaign Lend Fund - <?php echo $pid; ?> - <?php echo $get_lpay->lpay_id; ?></option>
		                     </select>
		                   </div>
		                 </div>

		                 <div class="col-sm-12">
		                   <div class="form-group mb-20">
		                     <label style="color:#009900"><strong>Currency</strong></label>
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
		                     <label style="color:#009900"><strong>How much do you want to Pay?</strong></label>
		                     <select name="amount" class="form-control">
		                         <option value="<?php echo $get_cpay->amount; ?>" selected><?php echo $get_cpay->amount; ?></option>
								 
		                     </select>
		      
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

		               <input type="hidden" name="item_name" value="Campaign Lend Fund - <?php echo $pid; ?> - <?php echo $get_lpay->lpay_id; ?>"> <!-- updated dynamically -->
		               <input type="hidden" name="currency_code" value="USD"> <!-- updated dynamically -->
		               <input type="hidden" name="amount" value="<?php echo $get_cpay->amount; ?>"> <!-- updated dynamically -->

		               <input type="hidden" name="no_shipping" value="1">
		               <input type="hidden" name="cn" value="Comments...">
		               <input type="hidden" name="tax" value="0">
		               <input type="hidden" name="lc" value="US">
		               <input type="hidden" name="bn" value="PP-DonationsBF">
		               <input type="hidden" name="return" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/newloan/user/thankyou1.php">
		               <input type="hidden" name="cancel_return" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/newloan/user/funders_list.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA1">
		               <input type="hidden" name="notify_url" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/newloan/notify_lendpayment.php?temp_id=<?php echo $pid; ?>&&tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA1">
		               <input type="submit" name="submit">
		             </form>
            
		             <!-- Paypal Recurring Form -->
		             <form id="paypal_donate_form-recurring" class="hidden" action="https://www.paypal.com/cgi-bin/webscr" method="post">
		               <input type="hidden" name="cmd" value="_xclick-subscriptions">
		               <input type="hidden" name="business" value="<?php echo $get_settin['paypal_email']; ?>">

		               <input type="hidden" name="item_name" value="Campaign Lend Fund - <?php echo $pid; ?> - <?php echo $get_lpay->lpay_id; ?>"> <!-- updated dynamically -->
		               <input type="hidden" name="currency_code" value="USD"> <!-- updated dynamically -->
		               <input type="hidden" name="a3" value="<?php echo $get_cpay->amount; ?>"> <!-- updated dynamically -->
		               <input type="hidden" name="t3" value="D"> <!-- updated dynamically -->


		               <input type="hidden" name="p3" value="1">
		               <input type="hidden" name="rm" value="2">
		               <input type="hidden" name="src" value="1">
		               <input type="hidden" name="sra" value="1">
		               <input type="hidden" name="no_shipping" value="0">
		               <input type="hidden" name="no_note" value="1">                     
		               <input type="hidden" name="lc" value="US">
		               <input type="hidden" name="bn" value="PP-DonationsBF">
		               <input type="hidden" name="return" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/newloan/user/thankyou1.php">
		               <input type="hidden" name="cancel_return" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/newloan/user/funders_list.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA1">
		               <input type="hidden" name="notify_url" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/newloan/notify_lendpayment.php?temp_id=<?php echo $pid; ?>&&tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA1">
		               <input type="submit" name="submit">
		             </form>
		             <!-- Paypal Both Onetime/Recurring Form Ends -->
										 
		            <?php } ?>
            
		             


</div>	
</div>	
</div>
</div>