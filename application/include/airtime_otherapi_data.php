<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-tty"></i>  Airtime API Setup</h3>
            </div>

             <div class="box-body">
<?php
if(isset($_POST['save']))
{
	$username =  mysqli_real_escape_string($link, $_POST['username']);
		
	$insert = mysqli_query($link, "INSERT INTO airtime_api VALUES(null,'$username','Active')") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Error.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>API Added Successfully!</div>";
	}
}
if(isset($_POST['update']))
{
	$username =  mysqli_real_escape_string($link, $_POST['username']);
	$status =  mysqli_real_escape_string($link, $_POST['status']);
		
	$insert = mysqli_query($link, "UPDATE airtime_api SET username = '$username', status = '$status'") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Error.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>API Updated Successfully!</div>";
	}
}
?> 

<?php
$search_api = mysqli_query($link, "SELECT * FROM airtime_api");
if(mysqli_num_rows($search_api) == 1){
$rows = mysqli_fetch_array($search_api);
?>          
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" placeholder="Username" value="<?php echo $rows['username']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
                <div class="col-sm-10">
                <select name="status"  class="form-control select2" required style="width:100%">
					<option value="<?php echo $rows['status']; ?>" selected><?php echo $rows['status']; ?></option>
					<option value="Active">Active</option>
					<option value="Not-Active">Not-Active</option>
				</select>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="update" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Update</i></button>

              </div>
			  </div>
			  
			 </form> 
<?php
}
else{
?>
			<form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" placeholder="Username" required>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn bg-red btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="save" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			  
			 </form>

<?php } ?>


</div>	
</div>	
</div>
</div>

 
<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-envelope-o"></i>  Email Server Setup</h3>
            </div>
<?php
if(isset($_POST['update_token']))
{
	$email_token =  mysqli_real_escape_string($link, $_POST['email_token']);
	$product_url =  mysqli_real_escape_string($link, $_POST['product_url']);
	$logo_url =  mysqli_real_escape_string($link, $_POST['logo_url']);
	$support_email =  mysqli_real_escape_string($link, $_POST['support_email']);
	$live_chat =  mysqli_real_escape_string($link, $_POST['live_chat']);
	$email_from =  mysqli_real_escape_string($link, $_POST['email_from']);
	$email_sender_name =  mysqli_real_escape_string($link, $_POST['email_sender_name']);
	$company_address =  mysqli_real_escape_string($link, $_POST['company_address']);
      $brand_color = mysqli_real_escape_string($link, $_POST['brand_color']);
		
	$insert = mysqli_query($link, "UPDATE systemset SET email_token = '$email_token', website = '$product_url', email = '$support_email', live_chat = '$live_chat', email_from = '$email_from', email_sender_name = '$email_sender_name', address = '$company_address', brand_color = '$brand_color', logo_url = '$logo_url'") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Error.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>Email Server Setup Successfully!</div>";
	}
}
?>
             <div class="box-body">

<?php
$search_others = mysqli_query($link, "SELECT * FROM systemset");
if(mysqli_num_rows($search_others) == 1){
$rows_other = mysqli_fetch_array($search_others);
?>
             <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Email API Tokens</label>
                  <div class="col-sm-10">
                  <input name="email_token" type="text" class="form-control" placeholder="Email Server API Tokens" value="<?php echo $rows_other['email_token']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Product URL</label>
                  <div class="col-sm-10">
                  <input name="product_url" type="text" class="form-control" placeholder="Product URL" value="<?php echo $rows_other['website']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Logo URL</label>
                  <div class="col-sm-10">
                  <input name="logo_url" type="text" class="form-control" placeholder="Logo URL" value="<?php echo $rows_other['logo_url']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Support Email</label>
                  <div class="col-sm-10">
                  <input name="support_email" type="text" class="form-control" placeholder="Support Email" value="<?php echo $rows_other['email']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Live Chat URL</label>
                  <div class="col-sm-10">
                  <input name="live_chat" type="text" class="form-control" placeholder="Live Chat URL" value="<?php echo $rows_other['live_chat']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">From</label>
                  <div class="col-sm-10">
                  <input name="email_from" type="text" class="form-control" placeholder="Sender Email" value="<?php echo $rows_other['email_from']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Sender Name</label>
                  <div class="col-sm-10">
                  <input name="email_sender_name" type="text" class="form-control" placeholder="Sender Name" value="<?php echo $rows_other['email_sender_name']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Company Address</label>
                  <div class="col-sm-10">
                  <input name="company_address" type="text" class="form-control" placeholder="Company Address" value="<?php echo $rows_other['address']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Brand Color</label>
                  <div class="col-sm-10">
                  <select name="brand_color" class="form-control select2" required style="width:100%">
                    <option value="<?php echo $rows_other['brand_color']; ?>" selected><?php echo $rows_other['brand_color']; ?></option>
                    <option value="blue">blue</option>
                    <option value="green">green</option>
                    <option value="red">red</option>
                  </select>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="update_token" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Update</i></button>

              </div>
			  </div>
			  
			 </form>
<?php
}
else{
?>
			<form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 			
			Please set your global configuration to access this section...
			
			 </div>
			 
			  
			 </form>
<?php } ?>


			</div>	
			</div>	
			</div>
</div>

<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-mobile"></i>  Bill Payment API Setup</h3>
            </div>
<?php
if(isset($_POST['Update_bill']))
{
	$token =  mysqli_real_escape_string($link, $_POST['token']);
  $api_url =  mysqli_real_escape_string($link, $_POST['api_url']);
	$email =  mysqli_real_escape_string($link, $_POST['email']);
	$username =  mysqli_real_escape_string($link, $_POST['username']);
  $password =  mysqli_real_escape_string($link, $_POST['password']);
	$status =  mysqli_real_escape_string($link, $_POST['status']);

	$insert = mysqli_query($link, "UPDATE billpayment SET token = '$token', api_url = '$api_url', email = '$email', username = '$username', password = '$password', status = '$status' WHERE companyid = ''") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Error.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>Setup Updated Successfully!</div>";
	}
}
?>
             <div class="box-body">

<?php
$search_others = mysqli_query($link, "SELECT * FROM billpayment WHERE companyid = ''");
if(mysqli_num_rows($search_others) == 1){
$rows_other = mysqli_fetch_array($search_others);
?>
             <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">API Tokens</label>
                  <div class="col-sm-10">
                  <input name="token" type="text" class="form-control" placeholder="API Tokens" value="<?php echo $rows_other['token']; ?>" required>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">API URL</label>
                  <div class="col-sm-10">
                  <input name="api_url" type="text" class="form-control" placeholder="API URL" value="<?php echo $rows_other['api_url']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Account Email</label>
                  <div class="col-sm-10">
                  <input name="email" type="email" class="form-control" placeholder="Email Account Connected with your Bill Payment Gateway" value="<?php echo $rows_other['email']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" placeholder="Bill Payment Gateway Username" value="<?php echo $rows_other['username']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="password" class="form-control" placeholder="Bill Payment Gateway Password" value="<?php echo $rows_other['password']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
                <div class="col-sm-10">
                <select name="status"  class="form-control select2" required style="width:100%">
					<option value="<?php echo $rows_other['status']; ?>" selected><?php echo $rows_other['status']; ?></option>
					<option value="Active">Active</option>
					<option value="NotActive">NotActive</option>
				</select>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="Update_bill" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Update</i></button>

              </div>
			  </div>
			  
			 </form> 
<?php
}
else{
?>
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['Save_bill']))
{
	$token =  mysqli_real_escape_string($link, $_POST['token']);
  $api_url =  mysqli_real_escape_string($link, $_POST['api_url']);
	$email =  mysqli_real_escape_string($link, $_POST['email']);
	$username =  mysqli_real_escape_string($link, $_POST['username']);
  $password =  mysqli_real_escape_string($link, $_POST['password']);

	$insert = mysqli_query($link, "INSERT INTO billpayment VALUES(null,'','$token','$api_url','$email','$username','$password','Active')") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Error.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>Setup Saved Successfully!</div>";
	}
}
?>
             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">API Tokens</label>
                  <div class="col-sm-10">
                  <input name="token" type="text" class="form-control" placeholder="API Tokens" required>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">API URL</label>
                  <div class="col-sm-10">
                  <input name="api_url" type="text" class="form-control" placeholder="API URL" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Account Email</label>
                  <div class="col-sm-10">
                  <input name="email" type="email" class="form-control" placeholder="Email Account Connected with your Bill Payment Gateway" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" placeholder="Bill Payment Gateway Username" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="password" type="password" class="form-control" placeholder="Bill Payment Gateway Password" required>
                  </div>
                  </div>
			
			 </div>
			 
			 <div align="right">
              <div class="box-footer">
                				<button name="Save_bill" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>

			 </form>
<?php } ?>


			</div>	
			</div>	
			</div>
</div>


<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-mobile"></i>  Payvice Access Point</h3>
            </div>
<?php
if(isset($_POST['Update_PV']))
{
	$pv_walletid =  mysqli_real_escape_string($link, $_POST['pv_walletid']);
    $pv_username =  mysqli_real_escape_string($link, $_POST['pv_username']);
	$pv_tpin =  mysqli_real_escape_string($link, $_POST['pv_tpin']);
	$pv_password =  mysqli_real_escape_string($link, $_POST['pv_password']);

	$insert = mysqli_query($link, "UPDATE payvice_accesspoint SET pv_walletid = '$pv_walletid', pv_username = '$pv_username', pv_tpin = '$pv_tpin', pv_password = '$pv_password'") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Error.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>Setup Updated Successfully!</div>";
	}
}
?>
             <div class="box-body">

<?php
$search_vothers = mysqli_query($link, "SELECT * FROM payvice_accesspoint");
if(mysqli_num_rows($search_vothers) == 1){
$rows_vother = mysqli_fetch_array($search_vothers);
?>
             <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Wallet ID</label>
                  <div class="col-sm-10">
                  <input name="pv_walletid" type="text" class="form-control" placeholder="Wallet ID" value="<?php echo $rows_vother['pv_walletid']; ?>" required>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="pv_username" type="text" class="form-control" placeholder="Payvice Username" value="<?php echo $rows_vother['pv_username']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transaction Pin</label>
                  <div class="col-sm-10">
                  <input name="pv_tpin" type="number" pattern="[0-9]{4}" maxlength="4" class="form-control" placeholder="Transaction Pin" value="<?php echo $rows_vother['pv_tpin']; ?>" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="pv_password" type="password" class="form-control" placeholder="Payvice Password" value="<?php echo $rows_vother['pv_password']; ?>" required>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="Update_PV" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Update</i></button>

              </div>
			  </div>
			  
			 </form> 
<?php
}
else{
?>
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
if(isset($_POST['Save_PV']))
{
    $pv_walletid =  mysqli_real_escape_string($link, $_POST['pv_walletid']);
    $pv_username =  mysqli_real_escape_string($link, $_POST['pv_username']);
	$pv_tpin =  mysqli_real_escape_string($link, $_POST['pv_tpin']);
	$pv_password =  mysqli_real_escape_string($link, $_POST['pv_password']);

	$insert = mysqli_query($link, "INSERT INTO payvice_accesspoint VALUES(null,'$pv_walletid','$pv_username','$pv_tpin','$pv_password')") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Error.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>Setup Saved Successfully!</div>";
	}
}
?>
             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Wallet ID</label>
                  <div class="col-sm-10">
                  <input name="pv_walletid" type="text" class="form-control" placeholder="Wallet ID" required>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-10">
                  <input name="pv_username" type="text" class="form-control" placeholder="Payvice Username" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Transaction Pin</label>
                  <div class="col-sm-10">
                  <input name="pv_tpin" type="number" pattern="[0-9]{4}" maxlength="4" class="form-control" placeholder="Transaction Pin" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Password</label>
                  <div class="col-sm-10">
                  <input name="pv_password" type="password" class="form-control" placeholder="Payvice Password" required>
                  </div>
                  </div>
			
			 </div>
			 
			 <div align="right">
              <div class="box-footer">
                				<button name="Save_PV" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>

			 </form>
<?php } ?>


			</div>	
			</div>	
			</div>
</div>

