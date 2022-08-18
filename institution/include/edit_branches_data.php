<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="listbranches.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAy"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-object-ungroup"></i>  Edit Branch</h3>
            </div>

             <div class="box-body">
			 
<?php 
$id = $_GET['idm'];
$call = mysqli_query($link, "SELECT * FROM branches WHERE id='$id'");
while($row = mysqli_fetch_assoc($call))
{
?>           
			 <form class="form-horizontal" name="f1" method="post" enctype="multipart/form-data">

             <div class="box-body">

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch Name</label>
                  <div class="col-sm-10">
                  <input name="bname" type="text" class="form-control" placeholder="Branch Name Here" value="<?php echo $row['bname']; ?>" required>
				   <span style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"> You can enter your branch name here e.g Branch #1, Branch #2 etc.</span><br>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch Open Date</label>
                  <div class="col-sm-10">
                  <input name="bopendate" type="date" class="form-control" value="<?php echo $row['bopendate']; ?>" required>
                  </div>
                  </div>
				  
			<?php echo '<div class="alert bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].' fade in" >
  				<strong>Optional Fields: Override Accounts Settings</strong>
				</div>'?>
				
			<p><span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> If you are operating in different countries, you can create a branch for each country and then override the <a href="#">account settings</a> below. This is particularly useful for setting different currencies for each branch.</span></p>
			
		<div class="form-group">
                  <div class="col-sm-4">
                  <label>
                        <input type="checkbox" name="overrideAS" onclick="enable_text(this.checked)" value="<?php echo (isset($_POST['overrideAS'])) ? 1 : 0; ?>" <?php echo (isset($_POST['overrideAS'])) ? "checked" : ""; ?> /> Override Account Settings?
                  </label>
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
                  <div class="col-sm-10">
				<select name="bcountry" class="form-control select2" disabled>
					<option value="<?php echo $row['bcountry']; ?>" selected='selected'><?php echo $row['bcountry']; ?></option>
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
										<option value="<?php echo $row['currency']; ?>" selected='selected'><?php echo $row['currency']; ?></option>
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
  
				  				  <hr>
			<?php echo '<div class="alert bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].' fade in" >
  				<strong>Other Required Fields: Branch Address</strong>
				</div>'?>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch Address</label>
                  <div class="col-sm-10">
                  <input name="branch_addrs" type="text" class="form-control" placeholder="Branch Address" value="<?php echo $row['branch_addrs']; ?>" required>
                </div>
                </div>
				
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch City</label>
                  <div class="col-sm-10">
                  <input name="branch_city" type="text" class="form-control" value="<?php echo $row['branch_city']; ?>" placeholder="Branch City" required>
                </div>
                </div>
				
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch Province</label>
                  <div class="col-sm-10">
                  <input name="branch_province" type="text" class="form-control" value="<?php echo $row['branch_province']; ?>" placeholder="Branch Province" required>
                </div>
                </div>
				
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch Zipcode</label>
                  <div class="col-sm-10">
                  <input name="branch_zipcode" type="text" class="form-control" value="<?php echo $row['branch_zipcode']; ?>" placeholder="Branch Zipcode" required>
                </div>
                </div>
				
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch Landline</label>
                  <div class="col-sm-10">
                  <input name="branch_landline" type="text" class="form-control" value="<?php echo $row['branch_landline']; ?>" placeholder="Branch Landline" required>
                </div>
                </div>
				
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch Mobile</label>
                  <div class="col-sm-10">
                  <input name="branch_mobile" type="text" class="form-control" value="<?php echo $row['branch_mobile']; ?>" placeholder="Branch Mobile" required>
                </div>
                </div>

             <div class="form-group">
						<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch Stamp</label>
						<div class="col-sm-10">
								 <a href="update_stamp.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $_GET['idm']; ?>&&mid=NDAy"><b>Click here</b></a> <span style="color: blue; font-size:15px;">to Upload / Change Stamp for the Branch</span>
								 <br><img id="blah"  src="<?php echo $fetchsys_config['file_baseurl'].$row['stamp']; ?>" alt="Branch Stamp Here" height="100" width="100"/>
						</div>
						</div>
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			 </form>
<?php } ?>			

<?php
if(isset($_POST['save']))
{
	$id =  $_GET['idm'];
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

	//this handles uploading of rentals image
	//$image2 = addslashes(file_get_contents($_FILES['image2']['tmp_name']));
	
	if($overrideAS == '0')
	{
		$system_set = mysqli_query($link, "SELECT * FROM systemset");
		$get_sysset = mysqli_fetch_array($system_set);
		//USE DEFAULT SETTING
		$sys_country = $get_sysset['bcountry'];
		$sys_currency = $get_sysset['currency'];
		
		$update = mysqli_query($link, "UPDATE branches SET bname='$bname', bopendate='$bopendate', bcountry='$sys_country', currency='$sys_currency', branch_addrs='$branch_addrs', branch_city='$branch_city', branch_province='$branch_province', branch_zipcode='$branch_zipcode', branch_landline='$branch_landline', branch_mobile='$branch_mobile' WHERE id = '$id'") or die ("Error: " . mysqli_error($link));
		if(!$update)
		{
			echo "<script>alert('Unable to Edit Branch Settings.....Please try again later'); </script>";
			echo "<script>window.location='edit_branches.php?id=".$_SESSION['tid']."&&idm=".$id."&&mid=NDAy';</script>";		}
		else{
			echo "<script>alert('Branch Settings Edited Successfully!'); </script>";
			echo "<script>window.location='edit_branches.php?id=".$_SESSION['tid']."&&idm=".$id."&&mid=NDAy';</script>";	
		}
	}
	elseif($overrideAS == '1'){
		$update = mysqli_query($link, "UPDATE branches SET bname ='$bname', bopendate ='$bopendate', bcountry ='$bcountry', currency ='$currency', branch_addrs ='$branch_addrs', branch_city ='$branch_city', branch_province ='$branch_province', branch_zipcode ='$branch_zipcode', branch_landline ='$branch_landline', branch_mobile ='$branch_mobile' WHERE id = '$id'") or die ("Error: " . mysqli_error($link));
		if(!$update)
		{
			echo "<script>alert('Unable to Edit Branch Settings.....Please try again later'); </script>";
			echo "<script>window.location='edit_branches.php?id=".$_SESSION['tid']."&&idm=".$id."&&mid=NDAy';</script>";		}
		else{
			echo "<script>alert('Branch Settings Edited Successfully!'); </script>";
			echo "<script>window.location='edit_branches.php?id=".$_SESSION['tid']."&&idm=".$id."&&mid=NDAy';</script>";	
		}
	}
}
?> 

</div>	
</div>	
</div>
</div>