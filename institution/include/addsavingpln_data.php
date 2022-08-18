<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="create_msavingsplan.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-plus"></i> Create Product Plan</h3>
            </div>

             <div class="box-body">
<?php
if(isset($_POST['save']))
{

    $plan_type = mysqli_real_escape_string($link, $_POST['ptype']);
	$plan_category = mysqli_real_escape_string($link, $_POST['plan_category']);
	$dividend_type =  mysqli_real_escape_string($link, $_POST['dividend_type']);
    $dividend =  mysqli_real_escape_string($link, $_POST['dividend']);
    	
	//Lock Period Option
	$lock_w = mysqli_real_escape_string($link, $_POST['lock_withdrawal']);
	$dinterval = ($lock_w === "Yes") ? mysqli_real_escape_string($link, $_POST['dinterval']) : "";
	$freq = ($lock_w === "Yes") ? mysqli_real_escape_string($link, $_POST['freq']) : "";
	
	//Part Withdrawal
	$part_withdrawal = mysqli_real_escape_string($link, $_POST['part_withdrawal']);
	$nots = ($part_withdrawal === "Yes") ? mysqli_real_escape_string($link, $_POST['nots']) : "";
	
	$spname = mysqli_real_escape_string($link, $_POST['spname']);
	$api_spname = mysqli_real_escape_string($link, $_POST['api_spname']);
	$spdesc = mysqli_real_escape_string($link, $_POST['spdesc']);
    $min_amount = mysqli_real_escape_string($link, $_POST['min_amount']);
    $max_amount = mysqli_real_escape_string($link, $_POST['max_amount']);
	$currency = mysqli_real_escape_string($link, $_POST['currency']);
    $pmethod = implode(',', $_POST['pmethod']);
	
	//Investment Interval & Duration
    $pinterval = implode(',', $_POST['pinterval']);
	$duration = mysqli_real_escape_string($link, $_POST['duration']);
	
	//Commission Shares for Merchant and Clients
	$commtype = mysqli_real_escape_string($link, $_POST['commtype']);
    $commvalue = mysqli_real_escape_string($link, $_POST['commvalue']);
    $agentcomm = mysqli_real_escape_string($link, $_POST['agentcomm']);
    $subAcctCode = mysqli_real_escape_string($link, $_POST['subAcctCode']);

    //ALLOW UPFRONT PAYMENT
    $upfront_payment = mysqli_real_escape_string($link, $_POST['upfront_payment']);

	$systemset = mysqli_query($link, "SELECT * FROM systemset");
	$row1 = mysqli_fetch_object($systemset);
      
    //Get the Plan code generated by the system
    $plan_id = mt_rand(10000,99999);
	$plan_code = "rpp_".random_strings(20);
    $date_time = date("Y-m-d h:m:s");
	
	$insert = mysqli_query($link, "INSERT INTO savings_plan VALUES(null,'$institution_id','NIL','$plan_id','$plan_code','$spname','$api_spname','$spdesc','$plan_category','$min_amount','$max_amount','$currency','$dividend_type','$dividend','$pinterval','$duration','$lock_w','$dinterval','$freq','$part_withdrawal','$nots','$date_time','','$commtype','$commvalue','Active','$plan_type','$agentcomm','$pmethod','$subAcctCode','$upfront_payment')") or die ("Error: " . mysqli_error($link));

	echo "<script>alert('New Plan Added Successfully!'); </script>";

}
?>  
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

             <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Product Type</label>
                  <div class="col-sm-10">
        	<select name="ptype" class="form-control select2" required>
                    <option value="" selected='selected'>Select Product Type&hellip;</option>
                    <option value="Savings">Savings</option>
                    <option value="Investment">Investment</option>
                    <option value="Takaful">Takaful</option>
                    <option value="Donation">Donation</option>
                    <option value="Health">Health</option>
        	</select>
		    </div>
		    </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Name</label>
                  <div class="col-sm-10">
                  <input name="spname" type="text" class="form-control" placeholder="Product Plan Name" /required>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">API Plan Name (Optional)</label>
                  <div class="col-sm-10">
                  <input name="api_spname" type="text" class="form-control" placeholder="API Plan Name">
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Description</label>
                  <div class="col-sm-10">
                      <textarea name="spdesc"  class="form-control" rows="2" cols="80" placeholder="Product Plan Description" required></textarea>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Categories</label>
                  <div class="col-sm-10">
        	<select name="plan_category"  class="form-control select2" id="plan_category" required>
                    <option value="" selected='selected'>Select Category&hellip;</option>
                    <option value="Insurance">Insurance</option>
                    <option value="Pension">Pension</option>
                    <option value="Real Estate">Real Estate</option>
                    <option value="Halal Investment">Halal Investment</option>
                    <option value="Health Insurance">Health Insurance</option>
                    <option value="Islamic Insurance (Takaful)">Islamic Insurance (Takaful)</option>
                    <option value="Halal Donation">Halal Donation</option>
                    <option value="Target Savings">Target Savings</option>
                    <option value="Other">Other</option>
        	</select>
		    </div>
		    </div>

		    <span id='ShowValueFrank'></span>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Minimum Amount</label>
                  <div class="col-sm-10">
                  <input name="min_amount" type="text" class="form-control" placeholder="Enter Minimum Plan Amount" /required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Maximum Amount</label>
                  <div class="col-sm-10">
                  <input name="max_amount" type="text" class="form-control" placeholder="Enter Maximum Plan Amount" /required>
                  </div>
                  </div>
                  
            <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Currency</label>
                      <div class="col-sm-10">
            <select name="currency"  class="form-control select2" required>
              <option value="" selected>Please Select Currency</option>
              <option value="NGN">NGN</option>
              <option value="USD">USD</option>
              <option value="GBP">GBP</option>
              <option value="EUR">EUR</option>
              <option value="GHS">GHS</option>
              <option value="KES">KES</option>
              <option value="UGX">UGX</option>
              <option value="TZS">TZS</option>
            </select>                 
            </div>
    		</div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Payment Method(s)</label>
                  <div class="col-sm-10">
                  <select name="pmethod[]" class="form-control select2" multiple="multiple" style="width: 100%;" /required>
                    <option value="" selected="selected">---Choose Payment Method---</option>
                    <option value="wallet">Wallet</option>
                    <option value="card">Card</option>
                  </select>
				  </div>
            </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Dividend</label>
                  <div class="col-sm-10">
                  <select name="dividend_type" class="select2" style="width: 100%;" id="dividend_type" /required>
  				<option value="" selected="selected">---Select Dividend / Interest---</option>
				<option value="Flat Rate">Flat Rate</option>
				<option value="Percentage">Percentage</option>
				<option value="Ratio">Ratio</option>
                  </select>
				  </div>
            </div>

            <span id='ShowValueFrank1'></span>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Pay Interest Upfront</label>
                  <div class="col-sm-10">
                  <select name="upfront_payment" class="form-control select2" style="width: 100%;" /required>
  				<option value="" selected="selected">---Select if upfront payment of Interest is allowed---</option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
                  </select>
				  </div>
            </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Interval</label>
                  <div class="col-sm-10">
                  <select name="pinterval[]" class="form-control select2" style="width: 100%;" multiple="multiple" id="pinterval" /required>
  				<option value="" selected="selected">---Choose Interval for the Plan---</option>
				<option value="daily">Daily</option>
				<option value="weekly">Weekly</option>
				<option value="monthly">Monthly</option>
				<option value="yearly">Yearly</option>
				<option value="ONE-OFF">ONE-OFF</option>
                  </select>
			</div>
            </div>
            
            <span id='ShowValueFrank6'></span>
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Lock Withdrawal</label>
                  <div class="col-sm-10">
                  <select name="lock_withdrawal" class="form-control select2" style="width: 100%;" id="lock_withdrawal" /required>
  				<option value="" selected="selected">---Select Option---</option>
				<option value="Yes">Yes (Temporary Lock)</option>
				<option value="No">No</option>
				<option value="Lock">Yes (Permanent Lock)</option>
                  </select>
				  </div>
            </div>

            <span id='ShowValueFrank4'></span>
            <span id='ShowValueFrank4'></span>
            
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Part Withdrawal</label>
                  <div class="col-sm-10">
                  <select name="part_withdrawal" class="form-control select2" style="width: 100%;" id="part_withdrawal" /required>
  				<option value="" selected="selected">---Select Option---</option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
                  </select>
				  </div>
            </div>

            <span id='ShowValueFrank5'></span>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Subaccount Share Type</label>
                  <div class="col-sm-10">
                  <select name="commtype" class="form-control select2" style="width: 100%;" /required>
  				<option value="" selected="selected">---Subaccount Share Type---</option>
				<option value="flat">flat</option>
				<option value="percentage">percentage</option>
                  </select>
				  </div>
            </div>
            
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Subaccount Share</label>
                  <div class="col-sm-10">
                  <input name="commvalue" type="text" class="form-control" placeholder="Enter Subaccount Share" /required>
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><u><b>NOTE:</b></u> Please do not enter symbol like <b>%</b>. The correct input are 50.6, 90, 98, 95 etc.</span>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Agent Commission(%)</label>
                  <div class="col-sm-10">
                  <input name="agentcomm" type="text" class="form-control" placeholder="Enter Agent Commission" /required>
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Note: Please do not enter symbol like %. The correct input are 5, 2.5, 10.26 etc.</span>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Subaccount Code</label>
                  <div class="col-sm-10">
                  <input name="subAcctCode" type="text" class="form-control" placeholder="Subaccount code" /required>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
              
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>