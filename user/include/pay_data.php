<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-money"></i> Visa / Mastercard / Verve Payment</h3>
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
		             <p><i>Kindly Validate your Payment by clicking on <b>"Validate"</b> Then proceed to use the form below the button to pay your Outstanding Loan Payment.</i></p>
					<b style="font-size:18px;">NOTICE:</b> 
                    <p>(1). Note that for Local Loan Repayments with Mastercard, Visa, Bank Account, USSD: the charges is <b><?php echo ($localpayment_charges * 100).'%'; ?></b> fees subject to cap of <b><?php echo $row1->currency.number_format($capped_amount,2,'.',','); ?></b>
                    </p>
                    <p>(2). Note that for International Card Loan Repayments with Mastercard, Visa, AMEX: the charges is <b><?php echo ($intlpayment_charges * 100).'%'; ?></b>
                    </p>
					</div>
		             <hr>

<?php
if(isset($_GET['lid']) == true){
	function myreference($limit)
	 {
		return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
	 }
	$acn = $_GET['acn'];
	$lid = $_GET['lid'];
	$reference =  "EA-loanRepay-".myreference(10);
	$search_pay = mysqli_query($link, "SELECT * FROM payments WHERE remarks = 'pending' AND account_no = '$acn'");
	$get_pay = mysqli_fetch_object($search_pay);
	
	$search_id = mysqli_query($link, "SELECT * FROM pay_schedule WHERE tid = '$acn'");
	$get_id = mysqli_fetch_object($search_id);
	
	$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
	$get_customer = mysqli_fetch_object($search_customer);
	
	$localpayment_charges = $row1->localpayment_charges;
    $capped_amount = $row1->capped_amount;
    $intlpayment_charges = $row1->intlpayment_charges;
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
    $country = $dataArray->geoplugin_countryName;
    $country_currencycode = $dataArray->geoplugin_currencyCode;
    
    $local_rate = $get_pay->amount_to_pay * $localpayment_charges;
    $intl_rate = ($get_pay->amount_to_pay * $intlpayment_charges) + $get_pay->amount_to_pay;
    
    $max_cap_amount = $get_pay->amount_to_pay + $capped_amount;
    
    $cal_charges = ($country != "Nigeria" || $country_currencycode != "NGN" ? $intl_rate : ($country == "Nigeria" && $local_rate >= $capped_amount ? $max_cap_amount : ($local_rate + $get_pay->amount_to_pay)));
?>		           
<?php
if($bbranchid != ""){
?>
<form>
  	<script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <button type="button" onClick="payWithRave()" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh"></i> Proceed to Pay Now!</button>
</form>

<script>
    const API_publicKey = "<?php echo $row1->public_key; ?>";

    function payWithRave() {
        var x = getpaidSetup({
            PBFPubKey: API_publicKey,
            customer_email: "<?php echo $get_customer->email; ?>",
            amount: "<?php echo $cal_charges; ?>",
            customer_phone: "<?php echo $get_customer->phone; ?>",
            currency: "<?php echo $bbcurrency; ?>",
            payment_method: "both",
            txref: "<?php echo $reference; ?>",
            subaccounts: [{
               id:  "<?php echo $subaccount_code; ?>"
            }],
            callback_url: "https://esusu.app/user/list_payloans.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $acnt_id; ?>'&&refid=<?php echo $reference; ?>&&olp_id=<?php echo $_GET['olp_id']; ?>&&pid=<?php echo $_GET['pid']; ?>&&o_amt=<?php echo base64_encode($get_pay->amount_to_pay); ?>&&n_amt=<?php echo base64_encode($cal_charges); ?>&&lid=<?php echo $_GET['lid']; ?>&&mid=NDA0",
            meta: [{
                metaname: "Loan ID",
                metavalue: "<?php echo $get_pay->lid; ?>"
            }],
            onclose: function() {},
            callback: function(response) {
                var txref = response.tx.txRef; // collect flwRef returned and pass to a server page to complete status check.
                console.log("This is the response returned after a charge", response);
                if (response.tx.chargeResponseCode == "00" || response.tx.chargeResponseCode == "0") {
                    // redirect to a success page
                    alert('success. transaction ref is ' + txref);
                    window.location='list_payloans.php?id=' + <?php echo $_SESSION['tid']; ?> + '&&acn=<?php echo $acnt_id; ?>' + '&&refid=' + txref + '&&olp_id=<?php echo $_GET['olp_id']; ?>' + '&&pid=<?php echo $_GET['pid']; ?>' + '&&o_amt=<?php echo base64_encode($get_pay->amount_to_pay); ?>' + '&&n_amt=<?php echo base64_encode($cal_charges); ?>' + '&&lid=<?php echo $_GET['lid']; ?>' + '&&mid=NDA0';
                } else {
                    // redirect to a failure page.
                    alert('Sorry, transaction not successful\\nKindly Retry');
                }

                x.close(); // use this to close the modal immediately after payment.
            }
        });
    }
</script> 

<?php
}
else{
?>

<form>
  	<script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <button type="button" onClick="payWithRave()" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh"></i> Proceed to Pay Now!</button>
</form>

<script>
    const API_publicKey = "<?php echo $row1->public_key; ?>";

    function payWithRave() {
        var x = getpaidSetup({
            PBFPubKey: API_publicKey,
            customer_email: "<?php echo $get_customer->email; ?>",
            amount: "<?php echo $cal_charges; ?>",
            customer_phone: "<?php echo $get_customer->phone; ?>",
            currency: "<?php echo $bbcurrency; ?>",
            payment_method: "both",
            txref: "<?php echo $reference; ?>",
            callback_url: "https://esusu.app/user/list_payloans.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $acnt_id; ?>'&&refid=<?php echo $reference; ?>&&olp_id=<?php echo $_GET['olp_id']; ?>&&pid=<?php echo $_GET['pid']; ?>&&o_amt=<?php echo base64_encode($get_pay->amount_to_pay); ?>&&n_amt=<?php echo base64_encode($cal_charges); ?>&&lid=<?php echo $_GET['lid']; ?>&&mid=NDA0",
            meta: [{
                metaname: "Loan ID",
                metavalue: "<?php echo $get_pay->lid; ?>"
            }],
            onclose: function() {},
            callback: function(response) {
                var txref = response.tx.txRef; // collect flwRef returned and pass to a server page to complete status check.
                console.log("This is the response returned after a charge", response);
                if (response.tx.chargeResponseCode == "00" || response.tx.chargeResponseCode == "0") {
                    // redirect to a success page
                    alert('success. transaction ref is ' + txref);
                    window.location='list_payloans.php?id=' + <?php echo $_SESSION['tid']; ?> + '&&acn=<?php echo $acnt_id; ?>' + '&&refid=' + txref + '&&olp_id=<?php echo $_GET['olp_id']; ?>' + '&&pid=<?php echo $_GET['pid']; ?>' + '&&o_amt=<?php echo base64_encode($get_pay->amount_to_pay); ?>' + '&&n_amt=<?php echo base64_encode($cal_charges); ?>' + '&&lid=<?php echo $_GET['lid']; ?>' + '&&mid=NDA0';
                } else {
                    // redirect to a failure page.
                    alert('Sorry, transaction not successful\\nKindly Retry');
                }

                x.close(); // use this to close the modal immediately after payment.
            }
        });
    }
</script> 

<?php } ?>
										 
<?php
}else{
?>
					<!-- Paypal Both Onetime/Recurring Form Starts -->
							             <form id="paypal_donate_form_onetime_recurring" method="post" enctype="multipart/form-data">
							               <div class="row">
						   
											 <div class="col-sm-12">
								   			<div class="form-group mb-20">
								                     <label style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Loan ID</label>
								                     <select class="select2" name="acte" style="width: 100%;" required>
                                        				<option value="" selected="selected">--Select Loan to Pay--</option>
                                                         <?php
                                        				$get = mysqli_query($link, "SELECT * FROM loan_info WHERE p_status != 'PAID' AND status != 'Pending' AND baccount = '$acctno'") or die (mysqli_error($link));
                                        				while($rows = mysqli_fetch_array($get))
                                        				{
                                        				    $get_b = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acctno' ORDER BY id") or die (mysqli_error($link));
                                        				    $fetch_getb = mysqli_fetch_array($get_b);
                                        				    $myb_name = $fetch_getb['lname'].' '.$fetch_getb['fname'];
                                        				    $ltype = $fetch_getb['loantype'];
                                        				echo '<option value="'.$rows['lid'].'">'.$acctno.' - '.$myb_name.' [Loan ID: '.$rows['lid'].']</option>';
                                        				}
                                        				?>
                                                    </select>
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
								                   <label style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Payment Date</label>
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
	<button type="submit" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" name="val_submit"><i class="fa fa-search">&nbsp;<b>Validate</b></i></button>
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
		$track_lpay = mysqli_fetch_array($verify_loanid);
		$olp_id = $track_lpay['id'];
		$vendorid = $track_lpay['vendorid'];
		$insert = mysqli_query($link, "INSERT INTO payments VALUES(null,'Self','$lid','','$account','$customer','$loan_bal','$pay_date','$amount_topay','$remark','$bbranchid','$vendorid','$bsbranchid')");
		if(!$insert){
			echo "<script>alert('Unable to Verify Payment'); </script>";
		}else{
			$verify_payid = mysqli_query($link, "SELECT * FROM payments ORDER BY id DESC");
			$trackpid = mysqli_fetch_array($verify_payid);
			$pid = $trackpid['id'];
			echo "<script>alert('Payment Verify Successfully!...Click to Proceed!!'); </script>";
			echo "<script>window.location='pay.php?tid=".$_SESSION['tid']."&&acn=".$account."&&lid=".$lid."&&olp_id=".$olp_id."&&pid=".$pid."&&mid=".base64_encode("404")."'; </script>";
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