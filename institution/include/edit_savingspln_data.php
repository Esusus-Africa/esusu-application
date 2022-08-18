<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="create_msavingsplan.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-plus"></i> Update Product Plan</h3>
            </div>

             <div class="box-body">
<?php
if(isset($_POST['save']))
{    
    $my_plid = $_GET['plid'];
    
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
	$spdesc = mysqli_real_escape_string($link, $_POST['spdesc']);
	$min_amount = mysqli_real_escape_string($link, $_POST['min_amount']);
    $max_amount = mysqli_real_escape_string($link, $_POST['max_amount']);
	$currency = mysqli_real_escape_string($link, $_POST['currency']);
    $pmethod = implode(',', $_POST['pmethod']);
	
	//Investment Interval & Duration
	$pinterval = implode(',', $_POST['pinterval']);
	$duration = mysqli_real_escape_string($link, $_POST['duration']);

    //ALLOW UPFRONT PAYMENT
    $upfront_payment = mysqli_real_escape_string($link, $_POST['upfront_payment']);
	
	//Commission Shares for Merchant and Clients
	$commtype = mysqli_real_escape_string($link, $_POST['commtype']);
    $commvalue = mysqli_real_escape_string($link, $_POST['commvalue']);
    $agentcomm = mysqli_real_escape_string($link, $_POST['agentcomm']);
    $subAcctCode = mysqli_real_escape_string($link, $_POST['subAcctCode']);
	$status = mysqli_real_escape_string($link, $_POST['status']);
    		
    $sql = mysqli_query($link, "UPDATE savings_plan SET plan_name='$spname', plan_desc='$spdesc', min_amount='$min_amount', max_amount='$max_amount', currency='$currency', dividend_type='$dividend_type', dividend='$dividend', savings_interval='$pinterval', duration='$duration', lock_withdrawal='$lock_w', maturity_period='$dinterval', frequency='$freq', part_withdrawal='$part_withdrawal', no_of_times='$nots', commtype='$commtype', commvalue='$commvalue', agentcomm='$agentcomm', pmethod = '$pmethod', subAcctCode = '$subAcctCode', status='$status', upfront_payment='$upfront_payment' WHERE id = '$my_plid'") or die("Error: " . mysqli_error($link));
    
    if($sql){
        
        echo "<script>alert('Plan Updated successfully'); </script>";
        
    } 
    else{
        
        echo "<script>alert('Error...Please try again later'); </script>";
        
    }
    
    
}
?>          
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$plid = $_GET['plid'];
$search_sinfo = mysqli_query($link, "SELECT * FROM savings_plan WHERE id = '$plid'");
$fetch_sinfo = mysqli_fetch_object($search_sinfo);
?>	 			
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Name</label>
                  <div class="col-sm-9">
                  <input name="spname" type="text" class="form-control" value="<?php echo $fetch_sinfo->plan_name; ?>" placeholder="Product Plan Name" /required>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Description</label>
                  <div class="col-sm-9">
                      <textarea name="spdesc"  class="form-control" rows="2" cols="80" placeholder="Product Description" required><?php echo $fetch_sinfo->plan_desc; ?></textarea>
                  </div>
                  </div>

		    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Minimu Amount</label>
                  <div class="col-sm-9">
                  <input name="min_amount" type="text" class="form-control" value="<?php echo $fetch_sinfo->min_amount; ?>" placeholder="Enter Minimum Plan Amount" /required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Maximum Amount</label>
                  <div class="col-sm-9">
                  <input name="max_amount" type="text" class="form-control" value="<?php echo $fetch_sinfo->max_amount; ?>" placeholder="Enter Maximum Plan Amount" /required>
                  </div>
                  </div>
                  
            <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Currency</label>
                      <div class="col-sm-9">
            <select name="currency"  class="form-control select2" required>
              <option value="<?php echo $fetch_sinfo->currency; ?>" selected><?php echo $fetch_sinfo->currency; ?></option>
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
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Payment Method(s)</label>
                  <div class="col-sm-9">
                  <select name="pmethod[]" class="form-control select2" style="width: 100%;" multiple="multiple" /required>
                      <?php
                        $explodePlan = explode(",",$fetch_sinfo->pmethod);
    
                        $countPlan = (count($explodePlan) - 1);
                        
                        for($i = 0; $i <= $countPlan; $i++){
                            
                            echo '<option value="'.$explodePlan[$i].'" selected="selected">'.$explodePlan[$i].'</option>';
                            
                        }
                      ?>
        				<option value="wallet">Wallet</option>
                        <option value="card">Card</option>
                  </select>
				  </div>
            </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Dividend</label>
                  <div class="col-sm-9">
                  <select name="dividend_type" class="form-control select2" style="width: 100%;" id="dividend_type" /required>
  				<option value="<?php echo $fetch_sinfo->dividend_type; ?>" selected="selected"><?php echo $fetch_sinfo->dividend_type; ?></option>
				<option value="Flat">Flat</option>
				<option value="Percentage">Percentage</option>
				<option value="Ratio">Ratio</option>
                  </select>
				  </div>
            </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Rate / Shares</label>
                  <div class="col-sm-9">
                  <input name="dividend" type="number" class="form-control" value="<?php echo $fetch_sinfo->dividend; ?>" placeholder="Rates / Shares in Interest" /required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Pay Interest Upfront</label>
                  <div class="col-sm-9">
                  <select name="upfront_payment" class="form-control select2" style="width: 100%;" /required>
  				<option value="<?php echo $fetch_sinfo->upfront_payment; ?>" selected="selected"><?php echo $fetch_sinfo->upfront_payment; ?></option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
                  </select>
				  </div>
            </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Interval</label>
                  <div class="col-sm-9">
                  <select name="pinterval[]" class="form-control select2" style="width: 100%;" multiple="multiple" /required>
                      <?php
                        $explodePlan = explode(",",$fetch_sinfo->savings_interval);
    
                        $countPlan = (count($explodePlan) - 1);
                        
                        for($i = 0; $i <= $countPlan; $i++){
                            
                            echo '<option value="'.$explodePlan[$i].'" selected="selected">'.$explodePlan[$i].'</option>';
                            
                        }
                      ?>
        				<option value="daily">Daily</option>
        				<option value="weekly">Weekly</option>
        				<option value="monthly">Monthly</option>
        				<option value="yearly">Yearly</option>
                  </select>
				  </div>
            </div>
            
             <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Duration</label>
                  <div class="col-sm-9">
                  <input name="duration" type="number" class="form-control" value="<?php echo $fetch_sinfo->duration; ?>" placeholder="Enter Duration" /required>
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">If <b>Plan Duration</b> is set to 5 and <b>plan intervals</b> is set to monthly, the customer would be charged 5 months and then the investment stops.</span>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Lock Withdrawal</label>
                  <div class="col-sm-9">
                  <select name="lock_withdrawal" class="form-control select2" style="width: 100%;" id="lock_withdrawal" /required>
  				<option value="<?php echo $fetch_sinfo->lock_withdrawal; ?>" selected="selected"><?php echo $fetch_sinfo->lock_withdrawal; ?></option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
                  </select>
				  </div>
            </div>
            
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Maturity Period</label>
                  <div class="col-sm-9">
                  <select name="dinterval" class="form-control select2" style="width: 100%;">
  				<option value="<?php echo $fetch_sinfo->maturity_period; ?>" selected="selected"><?php echo $fetch_sinfo->maturity_period; ?></option>
				<option value="weekly">Weekly</option>
				<option value="monthly">Monthly</option>
				<option value="annually">Yearly</option>
                  </select>
				  </div>
            </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Frequency</label>
                  <div class="col-sm-9">
                  <input name="freq" type="number" class="form-control" value="<?php echo $fetch_sinfo->frequency; ?>" placeholder="Enter Frequency based on Maturity Period e.g 1, 2, 4, 5 etc.">
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><u><b>NOTE:</b></u> If <b>frequency</b> is set to 2 and <b>Maturity Period</b> is set to annually, the customer would be able to withdraw his/her fund after 2 years.</span>
                  </div>
                  </div>
            
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Part Withdrawal</label>
                  <div class="col-sm-9">
                  <select name="part_withdrawal" class="form-control select2" style="width: 100%;" id="part_withdrawal" /required>
  				<option value="<?php echo $fetch_sinfo->part_withdrawal; ?>" selected="selected"><?php echo $fetch_sinfo->part_withdrawal; ?></option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
                  </select>
				  </div>
            </div>
            
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Number of times</label>
                  <div class="col-sm-9">
                  <input name="nots" type="number" class="form-control" value="<?php echo $fetch_sinfo->no_of_times; ?>" placeholder="Enter Number of time Allowed for withdrawal before Maturity Period Reach e.g 1, 2, 4, 5 etc.">
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><u><b>NOTE:</b></u> You can enter the number of times allowed for withdrawal before maturity period reach.</span>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Subaccount Share Type</label>
                  <div class="col-sm-9">
                  <select name="commtype" class="form-control select2" style="width: 100%;" /required>
  				<option value="<?php echo $fetch_sinfo->commtype; ?>" selected="selected"><?php echo $fetch_sinfo->commtype; ?></option>
				<option value="flat">flat</option>
				<option value="percentage">percentage</option>
                  </select>
				  </div>
            </div>
            
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Subaccount Share</label>
                  <div class="col-sm-9">
                  <input name="commvalue" type="text" class="form-control" value="<?php echo $fetch_sinfo->commvalue; ?>" placeholder="Enter Subaccount Share" /required>
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Note: Please do not enter symbol like %. The correct input are 50.6, 90, 98, 95 etc.</span>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Agent Commission(%)</label>
                  <div class="col-sm-9">
                  <input name="agentcomm" type="text" class="form-control" value="<?php echo $fetch_sinfo->agentcomm; ?>" placeholder="Enter Agent Commission" /required>
                  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Note: Please do not enter symbol like %. The correct input are 5, 2.5, 10.26 etc.</span>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Subaccount Code</label>
                  <div class="col-sm-9">
                  <input name="subAcctCode" type="text" class="form-control" value="<?php echo $fetch_sinfo->subAcctCode; ?>" placeholder="Subaccount code" /required>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Status</label>
                  <div class="col-sm-9">
                  <select name="status" class="form-control select2" style="width: 100%;" /required>
  				<option value="<?php echo $fetch_sinfo->status; ?>" selected="selected"><?php echo $fetch_sinfo->status; ?></option>
				<option value="Active">Active</option>
				<option value="Rejected">Reject</option>
                  </select>
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