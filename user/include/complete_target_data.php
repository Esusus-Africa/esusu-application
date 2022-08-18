<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><u><b>Method 1 (Using Debit Card to Automate):</b></u>
                <a href="complete_target.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA3&&pid=<?php echo $_GET['pid']; ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> <i class="fa fa-money"></i> Verify Visa / Mastercard / Verve Card
            </h3>
            </div>
             <div class="box-body">
 <?php
    function myreference($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

	$acn = $_GET['acn'];
	$plan_id = $_GET['pid'];
	$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acctno'");
	$get_customer = mysqli_fetch_object($search_customer);

	$search_splan = mysqli_query($link, "SELECT * FROM target_savings WHERE plan_id = '$plan_id'");
	$fetch_splan = mysqli_fetch_object($search_splan);
	$planamount = $fetch_splan->amount;
	$plancurrency = $fetch_splan->currency;

	$reference =  "EA-cardAuth-".myreference(10);
	
	$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$row1 = mysqli_fetch_object($select1);
	$auth_charges = $row1->auth_charges;
	$mysubacct_id = $row1->auth_subaccount;
	
	$select_rave = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'") or die (mysqli_error($link));
	$row_rave = mysqli_fetch_object($select_rave);
    $rave_public_key = $row_rave->rave_public_key;
?>           			

<div class="alert alert-default fade in">

    <p style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">PLAN AMOUNT: <b><?php echo $product_currency.number_format($planamount,2,'.',','); ?></b></p>
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
            currency: "<?php echo $plancurrency; ?>",
            txref: "<?php echo $reference; ?>",
            callback_url: "https://esusu.app/user/my_targetsavings.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&refid=<?php echo $reference; ?>&&pid=<?php echo $plan_id; ?>&&o_amt=<?php echo base64_encode($planamount); ?>&&mid=NDA3",
            subaccounts: [{
            	id: "<?php echo $mysubacct_id; ?>"
            }],
            onclose: function() {},
            callback: function(response) {
                var txref = response.tx.txRef; // collect flwRef returned and pass to a server page to complete status check.
                console.log("This is the response returned after a charge", response);
                if (response.tx.chargeResponseCode == "00" || response.tx.chargeResponseCode == "0") {
                    // redirect to a success page
                    alert('success. transaction ref is ' + txref);
                    window.location='my_targetsavings.php?tid=' + <?php echo $_SESSION['tid']; ?> + '&&acn=<?php echo $_GET['acn']; ?>' + '&&refid=' + txref + '&&pid=<?php echo $plan_id; ?>' + '&&o_amt=<?php echo base64_encode($planamount); ?>' + '&&mid=NDA3';
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

<div class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" style="font-size:16px;" align="left"><?php echo "<u><b>Method 2 (Tranfer to Wallet Account)</b></u>"; ?>: <i class='fa fa-hand-o-right'></i>&nbsp;&nbsp;

<?php
$search_vaccount = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$bbranchid' AND status = 'ACTIVE' AND gateway_name = 'monify'");
$fetch_vaccount = mysqli_fetch_array($search_vaccount);
$bank_name = $fetch_vaccount['bank_name'];
$account_number = $fetch_vaccount['account_number'];
$account_name = $fetch_vaccount['account_name'];
  
echo "<b>".strtoupper($bank_name)."</b>=> ACCOUNT NAME: <b>".strtoupper($account_name)."</b> | ACCOUNT NO: <b>".strtoupper($account_number)."</b>";
?>

</div>

<p style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">PLAN AMOUNT: <b><?php echo $product_currency.number_format($planamount,2,'.',','); ?></b></p>
<p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b>Please fill in the form below to notify <i style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><?php echo $account_name; ?></i> about your payment</b></p>

<form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php

if(isset($_POST['senddetails'])){

    function ccMasking($number, $maskingCharacter = '*') {
        return substr($number, 0, 0) . str_repeat($maskingCharacter, strlen($number) - 4) . substr($number, -4);
    }

    $plancode = mysqli_real_escape_string($link, $_POST['plancode']);
    $amountpaid = preg_replace("/[^0-9]/", "", mysqli_real_escape_string($link, $_POST['amountpaid']));
    $account_number =  ccMasking(mysqli_real_escape_string($link, $_POST['account_number']));
    $bank_code =  mysqli_real_escape_string($link, $_POST['bank_code']);
	$b_name = mysqli_real_escape_string($link, $_POST['b_name']);
    $new_reference = "EA".date("dy").time();
    
    $search_bankname = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$bank_code'");
	$fetch_bankname = mysqli_fetch_array($search_bankname);
	$mybank_name = $fetch_bankname['bankname'];
	
	$bank_details = "Bank Name: ".$mybank_name;
	$bank_details .= ", Account Name: ".$b_name;
    $bank_details .= ", Account Number: ".$account_number;
    
    $search_splan = mysqli_query($link, "SELECT * FROM target_savings WHERE plan_id = '$plancode'");
    $fetch_splan = mysqli_fetch_object($search_splan);
    $plan_id = $fetch_splan->plan_id;
    $plan_code = $fetch_splan->plan_code;
    $plan_name = $fetch_splan->plan_name;
    $planamount = $fetch_splan->amount;
    $plancurrency = $fetch_splan->currency;
    $categories = $fetch_splan->categories;
    $todays_date = date('Y-m-d h:i:s');
    $duration = $fetch_splan->duration;
    $dinterval = ($fetch_splan->savings_interval == "daily" ? 'days' : ($fetch_splan->savings_interval == "weekly" ? 'week' : ($fetch_splan->savings_interval == "monthly" ? 'month' : 'year')));
    $mature_date = date('Y-m-d h:i:s', strtotime('+'.$duration.' '.$dinterval, strtotime($todays_date)));
    $total_output = $planamount * $duration;
    $target_subaccount = $row1->target_subaccount;
    $myfullname = $myfn.' '.$myln;
    $converted_date = date('m/d/Y').' '.(date(h) + 1).':'.date('i a');
    $real_subscription_code = "uSub".date("dy").time();
    $real_invoice_code = date("yhi").time();

    $search_provider = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'");
    $fetch_provider = mysqli_fetch_array($search_provider);
    $creatorEmail = $fetch_provider['official_email'];

    $insert = mysqli_query($link, "UPDATE target_savings SET txid = '$new_reference', mature_date = '$mature_date' WHERE plan_id = '$plan_id'");
    
    $insert = mysqli_query($link, "INSERT INTO all_savingssub_transaction VALUES(null,'$bbranchid','$acctno','$real_invoice_code','$real_subscription_code','$new_reference','$plancurrency','$amountpaid','pending','$todays_date','','$myfn','$myln','','','','','Bank Transfer','','$plan_code','')");

    $insert = mysqli_query($link, "INSERT INTO investment_notification VALUES(null,'$bbranchid','','$acctno','$myfullname','$new_reference','$plancode','$real_subscription_code','$plan_name','$plancurrency','$planamount','$bank_details','$converted_date','Pending')");

    if(!$insert){
        
        echo "<script>alert('Opps!...Unable to complete transaction, please try again later!!'); </script>";
        
    }else{
        
        include("../cron/send_investmentalert.php");
        echo "<script>alert('Great! Your request has been received and will be process shortly!!'); </script>";
        echo "<script>window.location='my_targetsavings.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=NDA3'; </script>";
        
    }

}

?>

<div class="box-body">
        
        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
            <div class="col-sm-6">
                <select name="country"  class="form-control select2" id="country" onchange="loadbank();" required>
                  <option value="" selected>Please Select Country</option>
                  <option value="NG">Nigeria</option>
                </select>
            </div>
                <label for="" class="col-sm-3 control-label"></label>
        </div>
                  
        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
            <div class="col-sm-6">
                <input name="account_number" type="text" id="account_number" onkeydown="loadaccount();" class="form-control" placeholder="Bank Account Number" required>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
        </div>
                  
        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Bank Name</label>
            <div class="col-sm-6">
                <div id="bank_list">------------</div>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
        </div>
                  
        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Name</label>
            <div class="col-sm-6">
                <div id="act_numb">------------</div>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
        </div>
        
        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Code</label>
            <div class="col-sm-6">
                <input name="plancode" type="text" class="form-control" value="<?php echo $_GET['pid']; ?>" readonly/>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
        </div>
        
        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount Paid</label>
            <div class="col-sm-6">
                <input name="amountpaid" type="text" class="form-control" value="<?php echo number_format($planamount,0,'.',','); ?>" readonly/>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
        </div>
        
    </div>
    
    <div class="form-group" align="right">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
        <div class="col-sm-6">
            <button name="senddetails" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-forward">&nbsp;Submit</i></button>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

</form>


</div>	
</div>	
</div>
</div>