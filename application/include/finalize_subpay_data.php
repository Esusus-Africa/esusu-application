<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-cc-visa"></i>  Make Payment</h3>
            </div>

             <div class="box-body">
              
<form class="form-horizontal" method="post" enctype="multipart/form-data">

<div class="box-body">

<hr>
 <span style="color: green; font-size: 18px">Kindly click the button below to proceed for the payment with the uses of your ATM Card, Bank Account Number, Bank Shortcode OR with the use of your Online Banking if you are using <span style="color: blue;"><b>GTBank</b></span>
<hr>

<div>
   <button type="button" onclick="payWithPaystack()" class="btn bg-blue"><i class="fa fa-send"></i> <b> Make Payment </b></button>

<?php
$token = $_GET['token'];
$search_withtoken = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE sub_token = '$token'");
$fetch_withtoken = mysqli_fetch_object($search_withtoken);

$search_systemset = mysqli_query($link, "SELECT * FROM systemset");
$row1 = mysqli_fetch_object($search_systemset);
?>
</div>

</div>

 </form>

 <form >
   <script src="https://js.paystack.co/v1/inline.js"></script>
 </form>

<script>
  function payWithPaystack(){
    var handler = PaystackPop.setup({
      key: '<?php echo $row1->public_key; ?>',
      email: '<?php echo $aemail; ?>',
      amount: <?php echo $fetch_withtoken->amount_paid * 100; ?>,
      ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
      companyname: '<?php echo $aname; ?>',
      coopid: '<?php echo $agentid; ?>',
      // label: "Optional string that replaces customer email"
      metadata: {
         custom_fields: [
            {
                display_name: "Mobile Number",
                variable_name: "mobile_number",
                value: "<?php echo $aphone; ?>"
            }
         ]
      },
      callback: function(response){
          alert('success. transaction ref is ' + response.reference);
          window.location='saassub_history.php?id=<?php echo $_SESSION['tid']; ?>&&token=<?php echo $token; ?>&&mid=NDIw' + '&&refid=' + response.reference;
      },
      onClose: function(){
          alert('window closed');
      }
    });
    handler.openIframe();
  }
</script>

</div>	
</div>	
</div>
</div>