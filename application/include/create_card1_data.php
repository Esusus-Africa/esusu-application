<div class="row">	
		
	 <section class="content">
		 
            <h3 class="panel-title"> <a href="list_card.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("550"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a></h3>
        
		   <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

		 <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="create_card1.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("550"); ?>&&tab=tab_1">Card Enrollment Form</a></li>
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="#create_card1.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("550"); ?>&&tab=tab_2">View Card</a></li>
              </ul>
             <div class="tab-content">
<?php
if(isset($_GET['tab']) == true)
{
	$tab = $_GET['tab'];
	if($tab == 'tab_1')
	{
	?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">
			 
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_card1.php">
             <div class="box-body">

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Agent</label>
                  <div class="col-sm-10">
				<select name="customer"  class="form-control select2" required>
						<option value='' selected='selected'>Select Agent&hellip;</option>
										<?php
$search = mysqli_query($link, "SELECT * FROM agent_data ORDER BY id DESC");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['agentid']; ?>"><?php echo ($get_search['bname'] == "") ? $get_search['fname'] : $get_search['bname']; ?></option>
					<span style="color: orange;"> <b>The Name here will be the Billing Name Automatically.</span>
<?php } ?>
				</select>
			</div>
			</div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Bank</label>
                  <div class="col-sm-10">
				<select name="bank"  class="form-control select2" required>
						<option value='' selected='selected'>Select Bank&hellip;</option>
										<?php
$search = mysqli_query($link, "SELECT DISTINCT(issuer_name) FROM atmcard_gateway_apis ORDER BY id DESC");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['issuer_name']; ?>"><?php echo $get_search['issuer_name']; ?></option>
<?php } ?>
				</select>
			</div>
			</div>
			
			<input name="api_name" type="hidden" class="form-control" value="create-virtualcards">

            <!--
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Api List</label>
                  <div class="col-sm-10">
                    <div id="listbankapi"></div>
		    </div>
		    </div>
		    -->
		    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Billing City</label>
                  <div class="col-sm-10">
                  <input name="billing_city" type="text" class="form-control" placeholder="Billing City" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Billing State</label>
                  <div class="col-sm-10">
                  <input name="billing_state" type="text" class="form-control" placeholder="Billing State" required>
                  </div>
                  </div>

			<div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Billing Address</label>
                  	<div class="col-sm-10">
					<textarea name="billing_addrs"  class="form-control" rows="4" cols="80" required></textarea>
           			 </div>
          	</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Billing Postal Code</label>
                  <div class="col-sm-10">
                  <input name="postalcode" type="text" class="form-control" placeholder="Billing Postal Code" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Billing Country</label>
                  <div class="col-sm-10">
				<select name="billing_country"  class="form-control select2" required>
										<option value='' selected='selected'>Select Billing Country&hellip;</option>
										<option value="NG">NG</option>
										<option value="US">US</option>
				</select>
			</div>
			</div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Currency</label>
                  <div class="col-sm-10">
				<select name="currency_type"  class="form-control select2" required>
										<option value='' selected='selected'>Select Currency Type&hellip;</option>
										<option value="NGN">NGN</option>
										<option value="USD">USD</option>
				</select>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-10">
                  <input name="amount" type="text" class="form-control" placeholder="Enter the amount to prefund the card with" required>
                  <span style="color: orange;"> <b>Here, you need to enter the Amount to Prefund the Card with on Card Creation.</span>
                  </div>
                  </div>
                  
<hr>
<div class="alert bg-orange">Card Security (Secure Customer Withdrawal via Card) </div>
<hr>
            
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Secure Option</label>
                  <div class="col-sm-10">
				<select name="secure_option"  class="form-control select2" required>
										<option value='' selected='selected'>Select Secure Option&hellip;</option>
										<option value="otp">OTP</option>
										<option value="pin">PIN</option>
				</select>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">PIN (Optional)</label>
                  <div class="col-sm-10">
                  <input name="pin" type="text" class="form-control" value="1111" maxlength="4" readonly>
                  <span style="color: orange;"> <b>This section is applicable only if PIN is choosen above as Customer Security Check which they can later change easily via there end after issuing the card</span>
                  </div>
                  </div>
        

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                <button name="save" type="submit" class="btn bg-blue"><i class="fa fa-spinner">&nbsp;Create Card</i></button>

              </div>
			  </div>
			  
			 </form> 
			 
              </div>

    <?php
	}
	elseif($tab == 'tab_2')
	{
	?>

		<div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">
					   
		<div class="box-body">

<?php
if(isset($_GET['aId']) == true){
	$aId = $_GET['aId'];
	$search_card = mysqli_query($link, "SELECT * FROM card_enrollment WHERE account_id = '$aId'");
	$fetch_card = mysqli_fetch_object($search_card);
?>
<div class="demo-container" align="left">
<div class='card-wrapper'></div>
<!-- CSS is included via this JavaScript file -->
<script src="../dist/card.js"></script>
<div class="form-container active">
<form name="cardw">
    <input type="text" class="myinput" value="<?php echo chunk_split($fetch_card->cardpan, 4, ' '); ?>" name="number"/readonly>
    <input type="text" class="myinput" value="<?php echo $fetch_card->name_on_card; ?>" name="name"/readonly>
    <input type="text" class="myinput" value="<?php echo date('m/Y', strtotime($fetch_card->expiration)); ?>" name="expiry"/readonly>
    <input type="text" class="myinput" value="<?php echo $fetch_card->cvv; ?>" name="cvc"/readonly>
</form>
</div>
</div>
<script>

var card = new Card({
    form: 'form',
    container: '.card-wrapper',

    placeholders: {
        number: '<?php echo chunk_split($fetch_card->cardpan, 4, ' '); ?>',
        name: '<?php echo $fetch_card->name_on_card; ?>',
        expiry: '<?php echo date('m/Y', strtotime($fetch_card->expiration)); ?>',
        cvc: '<?php echo $fetch_card->cvv; ?>'
    }
});
</script>
<?php
}
else{
	echo "<div class='alert bg-orange'>Sorry!...No Card to View!!</div>";
}
?>
		</div>
		</div>
              <!-- /.tab-pane -->
	<?php
	}
	}
	?>
  
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
			
          </div>					
			</div>
		
              </div>
	
</div>	
</div>
</div>
</section>	
</div>