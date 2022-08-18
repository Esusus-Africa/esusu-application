<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><a href="verify_card3.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA3&&pcode=<?php echo $_GET['pcode']; ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> <i class="fa fa-money"></i> Verify Visa / Mastercard / Verve Card</h3>
            </div>
             <div class="box-body">
 <?php
 function myreference($limit)
 {
	return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
 }

	$acn = $_GET['acn'];
	$plan_code = $_GET['pcode'];
	$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
	$get_customer = mysqli_fetch_object($search_customer);

	$search_splan = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$plan_code'");
	$fetch_splan = mysqli_fetch_object($search_splan);
	$plan_id = $fetch_splan->plan_id;
	$subacct_id = $fetch_splan->subaccount_code;
	$planamount = $fetch_splan->amount;
	//$plancurrency = $fetch_splan->currency;

	$reference =  "EA-esusuPlan-".myreference(10);
	
	$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$row1 = mysqli_fetch_object($select1);

?>           			
		           <div class="alert alert-default fade in">
   				  <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b><i>Note that you are require to make your first payment tagged to your subscription plan (as stated below) by clicking the button below for subsequent charges to be automated</i></b></p>
   				  <p style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount to Charge: <b><?php echo $bbcurrency.number_format($planamount,2,'.',','); ?></b></p>
<hr>
<form>
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <button type="button" onClick="payWithRave()" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh"></i> Make First Payment!</button>
</form>

<script>
    const API_publicKey = "<?php echo $row1->public_key; ?>";

    function payWithRave() {
        var x = getpaidSetup({
            PBFPubKey: API_publicKey,
            customer_email: "<?php echo $get_customer->email; ?>",
            amount: "<?php echo $planamount; ?>",
            customer_phone: "<?php echo $get_customer->phone; ?>",
            currency: "<?php echo $bbcurrency; ?>",
            payment_method: "both",
            txref: "<?php echo $reference; ?>",
            subaccounts: [{
            	id: "<?php echo $subacct_id; ?>",
            	transaction_charge_type: "flat_subaccount"
            }],
            payment_plan: "<?php echo $plan_id; ?>",
            meta: [{
                metaname: "Savings Plan Code",
                metavalue: "<?php echo $plan_code; ?>"
            }],
            onclose: function() {},
            callback: function(response) {
                var txref = response.tx.txRef; // collect flwRef returned and pass to a server page to complete status check.
                console.log("This is the response returned after a charge", response);
                if (response.tx.chargeResponseCode == "00" || response.tx.chargeResponseCode == "0") {
                    // redirect to a success page
                    alert('success. transaction ref is ' + txref);
                    window.location='my_savings_plan.php?tid=' + <?php echo $_SESSION['tid']; ?> + '&&acn=<?php echo $_GET['acn']; ?>' + '&&refid=' + txref + '&&pcode=<?php echo $plan_code; ?>' + '&&mid=NDA3';
                } else {
                    // redirect to a failure page.
                    alert('Sorry, transaction not successful\\nKindly Retry');
                }

                x.close(); // use this to close the modal immediately after payment.
            }
        });
    }
</script>

</div>

</div>	
</div>	
</div>
</div>