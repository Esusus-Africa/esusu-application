<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-money"></i> Verify Visa / Mastercard / Verve Card</h3>
            </div>
             <div class="box-body">

<?php
	$acn = $_GET['acn'];
	$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
	$get_customer = mysqli_fetch_object($search_customer);
	
	$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$row1 = mysqli_fetch_object($select1);
?>
            			
		           <div class="alert alert-default fade in">
   				  <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b><i>Note that you must verify your ATM Card details to proceed for us to Activate Automated Loan Repayment.</i></b></p>
   				  <p style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><b>NOTE:</b><i> Be aware that you will be charge the total amount of <b><?php echo $row1->currency.number_format($row1->auth_charges,2,'.',','); ?></b> to confirm the validity of the card.</i></p>
					</div>
		             <hr>
<?php
 function myreference($limit)
 {
	return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
 }

	$acn = $_GET['acn'];
	$reference =  "EA-auth-".myreference(10);
	$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
	$get_customer = mysqli_fetch_object($search_customer);
	
	$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$row1 = mysqli_fetch_object($select1);
?>

<form>
  	<script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <button type="button" onClick="payWithRave()" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh"></i> Verify Your Card Now to Proceed!</button>
</form>

<script>
    const API_publicKey = "<?php echo $row1->public_key; ?>";

    function payWithRave() {
        var x = getpaidSetup({
            PBFPubKey: API_publicKey,
            customer_email: "<?php echo $get_customer->email; ?>",
            amount: "<?php echo $row1->auth_charges; ?>",
            customer_phone: "<?php echo $get_customer->phone; ?>",
            currency: "<?php echo $bbcurrency; ?>",
            txref: "<?php echo $reference; ?>",
            meta: [{
                metaname: "Loan ID",
                metavalue: "<?php echo $_GET['lid']; ?>"
            }],
            onclose: function() {},
            callback: function(response) {
                var txref = response.tx.txRef; // collect flwRef returned and pass to a server page to complete status check.
                console.log("This is the response returned after a charge", response);
                if (response.tx.chargeResponseCode == "00" || response.tx.chargeResponseCode == "0") {
                    // redirect to a success page
                    alert('success. transaction ref is ' + txref);
                    window.location='newloans.php?tid=' + <?php echo $_SESSION['tid']; ?> + '&&id=<?php echo $_GET['id']; ?>' + '&&acn=<?php echo $_GET['acn']; ?>' + '&&refid=' + txref + '&&lid=<?php echo $_GET['lid']; ?>' + '&&mid=NDA0' + '&&tab=tab_0';
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