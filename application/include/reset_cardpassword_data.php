<div class="box">
<?php
$cardId = $_GET['cardId'];
$search_card = mysqli_query($link, "SELECT * FROM card_enrollment WHERE card_id = '$cardId'");
$fetch_card = mysqli_fetch_object($search_card);

function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 7) . str_repeat($maskingCharacter, strlen($number) -12) . substr($number, -5);
}
?>
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-money"></i> Reset Card PIN
            </h3>
            </div>
             <div class="box-body">
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data" name="cardw">


<div class="modal-dialog" id="printableArea">
          <!-- Modal content-->
      <div class="modal-content">
                        <?php
					    if(isset($_POST['save']))
					    {
					    	$phone = $fetch_card->billing_phone;
					        $accountno = $fetch_card->acctno;
					        $otp = mt_rand(1000,9999);
					        
					        $system_set = mysqli_query($link, "SELECT * FROM systemset");
                            $get_sys = mysqli_fetch_array($system_set);
                            $sysabb = $get_sys['abb'];
                            
                            $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
                        	$fetch_gateway = mysqli_fetch_object($search_gateway);
                        	$gateway_uname = $fetch_gateway->username;
                        	$gateway_pass = $fetch_gateway->password;
                        	$gateway_api = $fetch_gateway->api;

					        $insert = mysqli_query($link, "UPDATE card_enrollment SET pin = '$otp' WHERE acctno = '$accountno'");
					        
					        $sms = "$sysabb>>>DO NOT DISCLOSE YOUR PIN TO ANYONE.";
                            $sms .= " Your Newly Generated PIN is ".$otp.". Thanks.";
					        include("../cron/send_general_sms.php");
					        echo "<span style='font-size:13px; color:blue'><i>New PIN has been sent to customer's registered phone number...</i></span>";
					    }
					    ?>
        <div class="modal-body">
	
			<div class="demo-container">
			    <button type="button" class="btn btn-flat bg-orange" align="left" disabled>&nbsp;<b>Wallet Balance: </b>&nbsp;
                        <strong class="alert bg-blue">
                        <?php
                        echo $fetch_card->currency_type.number_format($fetch_card->amount_prefund,2,'.',',');
                        ?> 
                        </strong>
                    </button>
                <div class='card-wrapper'></div>
				<!-- CSS is included via this JavaScript file -->
				<script src="../dist/card.js"></script>
				<span style="color: blue;">
				<div class="form-container active">
					    <input type="text" class="myinput" value="<?php echo ccMasking(chunk_split($fetch_card->cardpan, 4, ' ')); ?>" name="number"/readonly>
					    <input type="text" class="myinput" value="<?php echo $fetch_card->name_on_card; ?>" name="name"/readonly>
					    <input type="text" class="myinput" name="expiry" value="<?php echo date('m/Y', strtotime($fetch_card->expiration)); ?>" /readonly>
					    <input type="password" class="myinput" placeholder="cvv" maxlength="3" name="cvc"/required>
					    <select name="secure_option"  class="myinput" required>
        					<option value="<?php echo $fetch_card->secure_option; ?>" selected><?php echo $fetch_card->secure_option; ?></option>
        					<option value="pin">pin</option>
        					<option value="otp">otp</option>
        				</select>
				</div>
				<div align="center">
                  <div class="box-footer">
                    <button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Reset PIN</i></button>
                  </div>
			    </div>
				</span>
				
			
			</div>

<script>

var card = new Card({
    form: 'form',
    container: '.card-wrapper',

    placeholders: {
        number: '<?php echo ccMasking(chunk_split($fetch_card->cardpan, 4, ' ')); ?>',
        name: '<?php echo $fetch_card->name_on_card; ?>',
        expiry: '<?php echo date('m/Y', strtotime($fetch_card->expiration)); ?>',
        cvc: '***'
    }
});
</script>
			
        </div>
      </div>    
    </div>
			
			 </form> 


</div>	
</div>	
</div>
</div>