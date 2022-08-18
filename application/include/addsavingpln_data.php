<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="create_msavingsplan.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDkw"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-plus"></i> Add Esusu Plan</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	include ("../config/restful_apicalls.php");

	$merchantid =  mysqli_real_escape_string($link, $_POST['merchantid']);
	$plan_category = mysqli_real_escape_string($link, $_POST['plan_category']);
	$dividend_type =  mysqli_real_escape_string($link, $_POST['dividend_type']);
	$dividend =  mysqli_real_escape_string($link, $_POST['dividend']);
	$dinterval = mysqli_real_escape_string($link, $_POST['dinterval']);
	$lockp = mysqli_real_escape_string($link, $_POST['lockp']);
	
	$spname =  mysqli_real_escape_string($link, $_POST['spname']);
	$amount =  mysqli_real_escape_string($link, $_POST['amount']);
	$pinterval =  mysqli_real_escape_string($link, $_POST['pinterval']);
	$duration = mysqli_real_escape_string($link, $_POST['duration']);
	$currency = mysqli_real_escape_string($link, $_POST['currency']);

	$systemset = mysqli_query($link, "SELECT * FROM systemset");
	$row1 = mysqli_fetch_object($systemset);

	$search_mymerchant = mysqli_query($link, "SELECT * FROM merchant_reg WHERE merchantID = '$merchantid'");
	$fetch_mymerchant = mysqli_fetch_object($search_mymerchant);
	$subaccount_code = $fetch_mymerchant->subaccount_code;

	$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'paymentplans'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;

	// Pass the plan's name, interval and amount
	$postdata =  array(
		'amount'=> $amount,
		'name'	=> $spname,
		'interval'	=> $pinterval,
		'duration'	=> $duration,
		'seckey'	=> $row1->secret_key
	);
	
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$response = json_decode($make_call, true);

	if($response['status'] == "success"){
	  
		//Get the Plan code from Paystack API
			$plan_id = $response['data']['id'];
			$plan_code = $response['data']['plan_token'];
			
			$insert = mysqli_query($link, "INSERT INTO savings_plan VALUES(null,'$merchantid','$subaccount_code','$plan_id','$plan_code','$spname','$plan_category','$amount','$currency','$dividend_type','$dividend','$pinterval','$duration','$dinterval','$lockp',NOW(),'','','','Active','Investment')") or die ("Error: " . mysqli_error($link));

			echo "<script>alert('New Esusu Plan Added Successfully!'); </script>";

		//Perform necessary action
	}else{
	  
		$message = $response['message'];
		echo "<script>alert('$message \\nPlease try another one'); </script>";

	}

}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Select Merchant</label>
				 <div class="col-sm-9">
                <select name="merchantid" class="select2" style="width: 100%;" /required>
				<option selected="selected">--Select Merchant--</option>
					<?php
					$search_merchant = mysqli_query($link, "SELECT * FROM merchant_reg ORDER BY id DESC");
					while($fetch_merchant = mysqli_fetch_object($search_merchant))
					{
					?>
					<option value="<?php echo $fetch_merchant->merchantID; ?>"><?php echo $fetch_merchant->company_name; ?></option>
				<?php } ?>
                </select>
              </div>
			  </div>
			 			
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Name</label>
                  <div class="col-sm-9">
                  <input name="spname" type="text" class="form-control" placeholder="Savings Plan Name" /required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Categories</label>
                  <div class="col-sm-9">
        	<select name="plan_category"  class="form-control select2" id="plan_category" required>
                    <option selected='selected'>Select Category&hellip;</option>
                    <option value="Insurance">Insurance</option>
                    <option value="Pension">Pension</option>
                    <option value="Real Estate">Real Estate</option>
                    <option value="Halal Investment">Halal Investment</option>
                    <option value="Islamic Insurance (Takaful)">Islamic Insurance (Takaful)</option>
                    <option value="Other">Other</option>
        	</select>
		    </div>
		    </div>

		    <span id='ShowValueFrank'></span>

		    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Recurring Amount</label>
                  <div class="col-sm-9">
                  <input name="amount" type="text" class="form-control" placeholder="Enter Recurring Amount to be Saving" /required>
                  </div>
                  </div>

            <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Currency</label>
                      <div class="col-sm-9">
            <select name="currency"  class="form-control select2" required>
              <option selected>Please Select Currency</option>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="GBP">GBP</option>
              <option value="EUR">EUR</option>
              <option value="AUD">AUD</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
            </select>                 
            </div>
    		</div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Dividend</label>
                  <div class="col-sm-9">
                  <select name="dividend_type" class="select2" style="width: 100%;" id="dividend_type" /required>
  				<option selected="selected">---Select Dividend / Interest---</option>
				<option value="Flat Rate">Flat Rate</option>
				<option value="Percentage">Base on Percentage</option>
                  </select>
				  </div>
            </div>

            <span id='ShowValueFrank1'></span>
				  
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Savings Interval</label>
                  <div class="col-sm-9">
                  <select name="pinterval" class="select2" style="width: 100%;" /required>
  				<option selected="selected">---Choose Interval for Savings---</option>
				<option value="daily">Daily</option>
				<option value="weekly">Weekly</option>
				<option value="monthly">Monthly</option>
				<option value="yearly">Yearly</option>
				<option value="quarterly">Quarterly</option>
				<option value="bi-anually">Bi-anually</option>
                  </select>
				  </div>
            </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Disbursement Period</label>
                  <div class="col-sm-9">
                  <select name="dinterval" class="select2" style="width: 100%;" /required>
  				<option selected="selected">---Choose Disbursement Period---</option>
				<option value="weekly">Weekly</option>
				<option value="monthly">Monthly</option>
				<option value="annually">Annually</option>
                  </select>
				  </div>
            </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Lock Period (Monthly Basis)</label>
                  <div class="col-sm-9">
                  <input name="lockp" type="text" class="form-control" placeholder="Lock Period before withdrawal " /required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Duration</label>
                  <div class="col-sm-9">
                  <input name="duration" type="text" class="form-control" placeholder="Enter Duration" /required>
                  <span style="color: orange;">Note that, if set to 5 and savings intervals is set to monthly the customer would be charged 5 months, and then the subscription stops.</span>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn bg-orange"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>