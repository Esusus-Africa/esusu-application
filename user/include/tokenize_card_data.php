<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title">
            <a href="tokenize_card.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo (isset($_GET['Takaful']) ? 'NTA0' : (isset($_GET['Health']) ? 'MTAwMA==' : (isset($_GET['Savings']) ? 'NTAw' : (isset($_GET['Donation']) ? 'NjA0' : 'NDA3')))); ?>&&pcode=<?php echo $_GET['pcode']; ?><?php echo ((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : '')))); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a>
            </h3>
            </div>
             <div class="box-body">
 <?php
	$acn = $_GET['acn'];
    $reference =  "EA-cardAuth-".random_strings(10);
	$new_plancode = $_GET['pcode'];
	$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acctno'");
    $get_customer = mysqli_fetch_object($search_customer);
    
    $searchSub = mysqli_query($link, "SELECT * FROM savings_subscription WHERE new_plancode = '$new_plancode' AND status = 'Pending'");
    $fetchSub = mysqli_fetch_array($searchSub);
    $origin_plancode = $fetchSub['plan_code'];
    $amountpaid = $fetchSub['amount'];

	$search_splan = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$origin_plancode'");
	$num_splan = mysqli_num_rows($search_splan);
    $fetch_splan = mysqli_fetch_array($search_splan);

    $search_splan1 = mysqli_query($link, "SELECT * FROM target_savings WHERE plan_code = '$origin_plancode'");
    $num_splan1 = mysqli_num_rows($search_splan1);
	$fetch_splan1 = mysqli_fetch_array($search_splan1);

    //DETECT THE RIGHT PLAN
	$product_currency = ($num_splan == 1 && $num_splan1 == 0) ? $fetch_splan['currency'] : $fetch_splan1['currency'];
	
	$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$row1 = mysqli_fetch_object($select1);
    $auth_charges = $row1->auth_charges;
    
    $select_rave = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'") or die (mysqli_error($link));
	$row_rave = mysqli_fetch_object($select_rave);
    $rave_public_key = ($row_rave->rave_status == "Enabled") ? $row_rave->rave_public_key : $row1->public_key;
    $mysubacct_id = ($row_rave->cardtokenization_subacct == "") ? $row1->auth_subaccount : $row_rave->cardtokenization_subacct; //($sendSMS->startsWith($myVendID, "VEND")) ? $fetch_splan->subAcctCode : $row_rave->tsavings_subacct;
    
?>

<div class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" style="font-size:16px;" align="left"><?php echo "<u><b>Method 3 (Using Debit Card to Automate)</b></u>"; ?></div>

<div class="alert alert-default fade in">
    
    <p style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">PLAN AMOUNT: <b><?php echo $product_currency.number_format($amountpaid,2,'.',','); ?></b></p>
   	<p style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">AUTHORIZATION FEE: <b><?php echo $product_currency.number_format($auth_charges,2,'.',','); ?></b></p>
    <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b>NOTE: </b><i>After your card have been tokenized by our system, your first payment tagged to your subscription plan (as stated above) will be debited automatically</i></p>
    
<hr>
<form>
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <button type="button" onClick="payWithRave()" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh"></i> Activate Plan!</button>
</form>

<script>
    const API_publicKey = "<?php echo $rave_public_key; ?>";

    function payWithRave() {
        var x = getpaidSetup({
            PBFPubKey: API_publicKey,
            customer_email: "<?php echo $get_customer->email; ?>",
            amount: "<?php echo $auth_charges; ?>",
            customer_phone: "<?php echo $get_customer->phone; ?>",
            currency: "<?php echo $product_currency; ?>",
            txref: "<?php echo $reference; ?>",
            subaccounts: [
              {
                id: "<?php echo $mysubacct_id; ?>",
                transaction_split_ratio:"10"
              }
            ],
            onclose: function() {},
            callback: function(response) {
                var txref = response.tx.txRef; // collect flwRef returned and pass to a server page to complete status check.
                console.log("This is the response returned after a charge", response);
                if (response.tx.chargeResponseCode == "00" || response.tx.chargeResponseCode == "0") {
                    // redirect to a success page
                    alert('success. transaction ref is ' + txref);
                    window.location='my_savings_plan.php?tid=' + <?php echo $_SESSION['tid']; ?> + '&&acn=<?php echo $_SESSION['acctno']; ?>' + '&&refid=' + txref + '&&pcode=<?php echo $new_plancode; ?>' + '&&mid=<?php echo ((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Health'])) ? 'MTAwMA==' : ((isset($_GET['Savings'])) ? 'NTAw' : (isset($_GET['Donation']) ? 'NjA0' : 'NDA3')))); ?><?php echo ((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : 'Investment')))); ?>';
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