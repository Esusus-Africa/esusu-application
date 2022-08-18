<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><a href="finalize_subpay.php?id=<?php echo $_SESSION['tid']; ?>&&token=<?php echo $_GET['token']; ?>&&mid=NDIw"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a> <i class="fa fa-cc-visa"></i>  Make Payment</h3>
            </div>

             <div class="box-body">
<?php
include ("../config/restful_apicalls.php");

$reference =  "EA-SaasSub-".random_strings(10);

$token = $_GET['token'];
$subAmt = $_GET['subAmt'];
$search_withtoken = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE sub_token = '$token'");
$fetch_withtoken = mysqli_fetch_object($search_withtoken);
$originalAmt = ($fetch_withtoken['couponCode'] == "") ? "" : $_GET['subAmt'];

$search_systemset = mysqli_query($link, "SELECT * FROM systemset");
$row1 = mysqli_fetch_object($search_systemset);

$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'billpayment'");
$fetch_restapi = mysqli_fetch_object($search_restapi);
$api_url = $fetch_restapi->api_url;

$data_array = array(
  "secret_key"    => $row1->secret_key,
  "service"     => "rates_convert",
  "service_method"  => "post",
  "service_version" => "v1",
  "service_channel" => "transactions",
  "service_channel_group" => "merchants",
  "service_payload" => [
    "FromCurrency"   => $icurrency,
    "ToCurrency"  => "NGN",
    "Amount"   => $fetch_withtoken->amount_paid
  ]
);

$make_call = callAPI('POST', $api_url, json_encode($data_array));
$response = json_decode($make_call, true);

$original_value = ($response['data']['Status'] == "success") ? $response['data']['ToAmount'] : '';
?> 

<?php
if($fetchsys_config['mo_status'] == "Active")
{
?>
<form>

<div class="box-body">

<span style="font-size: 18px;">Payment Option 1: </span> <span style="font-size: 18px;">Online Card Payment - </span>
<script type="text/javascript" src="https://sandbox.sdk.monnify.com/plugin/monnify.js"></script>
<button type="button" onclick="payWithMonnify()" class="btn bg-blue"><i class="fa fa-send"></i> <b> Make Payment </b></button>

</div>

</form>

<script type="text/javascript">
    function payWithMonnify() {
        MonnifySDK.initialize({
            amount: <?php echo $original_value; ?>,
            currency: "<?php echo $icurrency; ?>",
            reference: "<?php echo $reference; ?>",
            customerFullName: "<?php echo $inst_name; ?>",
            customerEmail: "<?php echo $inst_email; ?>",
            customerMobileNumber: "<?php echo $inst_phone; ?>",
            apiKey: "<?php echo $row1->mo_api_key; ?>",
            contractCode: "<?php echo $row1->mo_contract_code; ?>",
            paymentDescription: "Esusu Africa Monthly Saas Subscription Payment",
            isTestMode: false,
            onComplete: function(response){
                //Implement what happens when transaction is completed.
                //console.log(response);
               if(response.paymentStatus == "PAID"){
                   alert('Transaction Complete Successfully');
                   window.location='saassub_history.php?id=<?php echo $_SESSION['tid']; ?>&&token=<?php echo $token; ?>&&subAmt=<?php echo $originalAmt; ?>&&mo_refid=<?php echo $reference; ?>&&mid=NDIw';
               }else if(response.paymentStatus == "FAIL"){
                   alert('Transaction Failed');
                   window.location='make_saas_sub.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDIw';
               }
            },
            onClose: function(){}
        });
    }
</script>

<?php
}
else{
?>

<form>

<div class="box-body">

<span style="font-size: 18px;">Payment Option 1: </span> <span style="font-size: 18px;">Online Card Payment - </span>
<script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
   <button type="button" onClick="payWithRave()" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-send"></i> <b> Make Payment </b></button>

</div>

</form>

<script>
    const API_publicKey = "<?php echo $row1->public_key; ?>";

    function payWithRave() {
        var x = getpaidSetup({
            PBFPubKey: API_publicKey,
            customer_email: "<?php echo $inst_email; ?>",
            amount: "<?php echo $original_value; ?>",
            customer_phone: "<?php echo $inst_phone; ?>",
            currency: "<?php echo $icurrency; ?>",
            txref: "<?php echo $reference; ?>",
            meta: [{
                companyname: "<?php echo $iname; ?>",
                instid: "<?php echo $institution_id; ?>"
            }],
            onclose: function() {},
            callback: function(response) {
                var txref = response.tx.txRef; // collect flwRef returned and pass to a server page to complete status check.
                if (response.tx.chargeResponseCode == "00" || response.tx.chargeResponseCode == "0") {
                    // redirect to a success page
                    alert('success. transaction ref is ' + txref);
                    window.location='saassub_history.php?id=<?php echo $_SESSION['tid']; ?>&&token=<?php echo $token; ?>&&subAmt=<?php echo $originalAmt; ?>&&mid=NDIw' + '&&refid=' + txref;
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


</div>	
</div>	
</div>
</div>