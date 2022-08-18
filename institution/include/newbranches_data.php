<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="listbranches.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("402"); ?>&&mid=NDAz"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-object-ungroup"></i>  New Branch</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$branchid =  mysqli_real_escape_string($link, $_POST['branchid']);
	$bname =  mysqli_real_escape_string($link, $_POST['bname']);
	$bopendate = mysqli_real_escape_string($link, $_POST['bopendate']);
	$overrideAS = (isset($_POST['overrideAS'])) ? 1 : 0;
	$bcountry =  mysqli_real_escape_string($link, $_POST['bcountry']);
	$currency =  $_POST['currency'];
	$branch_addrs = mysqli_real_escape_string($link, $_POST['branch_addrs']);
	$branch_city =  mysqli_real_escape_string($link, $_POST['branch_city']);
	$branch_province =  mysqli_real_escape_string($link, $_POST['branch_province']);
	$branch_zipcode = mysqli_real_escape_string($link, $_POST['branch_zipcode']);
	$branch_landline = mysqli_real_escape_string($link, $_POST['branch_landline']);
	$branch_mobile = mysqli_real_escape_string($link, $_POST['branch_mobile']);
	
	$date_time = date("Y-m-d H:i:s");
	$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
    $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
    $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
    $correctdate = $acst_date->format('Y-m-d g:i A');
	
	if($overrideAS == '0')
	{
		$system_set = mysqli_query($link, "SELECT * FROM systemset");
		$get_sysset = mysqli_fetch_array($system_set);
		//USE DEFAULT SETTING
		$sys_country = $get_sysset['bcountry'];
		$sys_currency = $get_sysset['currency'];
		/**
		   $activities_details = $ausername." Added new branches (".$bname.") on"
		   $today_date = date("Y-m-d");
		   $search_audit = mysqli_query($link, "SELECT * FROM audit_trail WHERE date_time LIKE '$today_date%' AND username = '$ausername' AND companyid = '$institution_id'");
		   if(mysqli_num_rows($search_audit) == 1)
		   {
		     $update_query = mysqli_query($link, "UPDATE audit_trail SET activities_tracked = '' WHERE username = ")
		 }
		**/
		//
		$insert = mysqli_query($link, "INSERT INTO branches VALUES(null,'BR','$bname','$bopendate','$sys_country','$sys_currency','$branch_addrs','$branch_city','$branch_province','$branch_zipcode','$branch_landline','$branch_mobile','$branchid','Operational','','$institution_id')") or die ("Error: " . mysqli_error($link));
		if(!$insert)
		{
			echo "<div class='alert alert-info'>Unable to Register New Branch.....Please try again later</div>";
		}
		else{
			echo "<div class='alert alert-success'>New Branch Added Successfully!</div>";
		}
	}
	elseif($overrideAS == '1'){
		$insert = mysqli_query($link, "INSERT INTO branches VALUES(null,'BR','$bname','$bopendate','$bcountry','$currency','$branch_addrs','$branch_city','$branch_province','$branch_zipcode','$branch_landline','$branch_mobile','$branchid','Operational','','$institution_id')") or die ("Error: " . mysqli_error($link));
		if(!$insert)
		{
			echo "<div class='alert alert-info'>Unable to Register New Branch.....Please try again later</div>";
		}
		else{
			echo "<div class='alert alert-success'>New Branch Added Successfully!</div>";
		}
	}
}
?>           
			 <form class="form-horizontal" name="f1" method="post" enctype="multipart/form-data">
			  <?php echo '<div class="alert bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].' fade in" >
  				<strong>Required Fields:</strong>
				</div>'; ?>
             <div class="box-body">
<?php
$branchid = 'BR'.time();
?>
                  <input name="branchid" type="hidden" class="form-control" value="<?php echo $branchid; ?>">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch Name</label>
                  <div class="col-sm-10">
                  <input name="bname" type="text" class="form-control" placeholder="Branch Name Here" required>
				   <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> You can enter your branch name here e.g Branch #1, Branch #2 etc.</span><br>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch Open Date</label>
                  <div class="col-sm-10">
                  <input name="bopendate" type="date" class="form-control" required>
                  </div>
                  </div>
				  
			<?php echo '<div class="alert bg-blue fade in" >
  				<strong>Optional Fields: Override Accounts Settings</strong>
				</div>'; ?>
				
			<p><span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> If you are operating in different countries, you can create a branch for each country and then override the <a href="#">account settings</a> below. This is particularly useful for setting different currencies for each branch.</span></p>
			
		<div class="form-group">
                  <div class="col-sm-4">
                  <label>
                        <input type="checkbox" name="overrideAS" onclick="enable_text(this.checked)" value="<?php echo (isset($_POST['overrideAS'])) ? 1 : 0; ?>"> Override Account Settings?
                  </label>
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
                  <div class="col-sm-10">
				<select name="bcountry" class="form-control select2" disabled>
										<option selected='selected'>Select a country&hellip;</option>
										<?php
					$get = mysqli_query($link, "SELECT * FROM bcountries ORDER BY id") or die (mysqli_error($link));
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['name']; ?>"><?php echo $rows['name']; ?></option>
					<?php } ?>
									</select>                 
									 </div>
                 					 </div>
									 
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Currency</label>
                  <div class="col-sm-10">
				<select name="currency"  class="form-control" disabled>
										<option selected='selected'>Select Currency&hellip;</option>
										<option value="NGN">NGN - &#x20A6;</option>
										<option value="NGN">NGN</option>
										<option value="USD">USD</option>
										<option value="EUR">EUR</option>
										<option value="GBP">GBP</option>
										<option value="UGX">UGX</option>
										<option value="TZS">TZS</option>
										<option value="GHS">GHS</option>
										<option value="KES">KES</option>
										<option value="ZAR">ZAR</option>
									</select>                 
									 </div>
                 					 </div>
                 					 
				  				  <br>
			<?php echo '<div class="alert bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].' fade in" >
  				<strong>Other Required Fields: Branch Address</strong>
				</div>'; ?>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch Address</label>
                  <div class="col-sm-10">
                  <input name="branch_addrs" type="text" class="form-control" placeholder="Branch Address" required>
                </div>
                </div>
				
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch City</label>
                  <div class="col-sm-10">
                  <input name="branch_city" type="text" class="form-control" placeholder="Branch City" required>
                </div>
                </div>
				
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch Province</label>
                  <div class="col-sm-10">
                  <input name="branch_province" type="text" class="form-control" placeholder="Branch Province" required>
                </div>
                </div>
				
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch Zipcode</label>
                  <div class="col-sm-10">
                  <input name="branch_zipcode" type="text" class="form-control" placeholder="Branch Zipcode" required>
                </div>
                </div>
				
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch Landline</label>
                  <div class="col-sm-10">
                  <input name="branch_landline" type="text" class="form-control" placeholder="Branch Landline" required>
                </div>
                </div>
				
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch Mobile</label>
                  <div class="col-sm-10">
                  <input name="branch_mobile" type="text" class="form-control" placeholder="Branch Mobile" required>
                </div>
                </div>
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>