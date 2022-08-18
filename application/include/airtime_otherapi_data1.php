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

	$insert = mysqli_query($link, "UPDATE billpayment SET token = '$token', api_url = '$api_url', email = '$email', username = '$username', password = '$password', status = '$status' WHERE companyid = '$agentid'") or die ("Error: " . mysqli_error($link));
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
$search_others = mysqli_query($link, "SELECT * FROM billpayment WHERE companyid = '$agentid'");
if(mysqli_num_rows($search_others) == 1){
$rows_other = mysqli_fetch_array($search_others);
?>
             <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">API Tokens</label>
                  <div class="col-sm-10">
                  <input name="token" type="text" class="form-control" placeholder="Email Server API Tokens" value="<?php echo $rows_other['token']; ?>" required>
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
                  <input name="password" type="text" class="form-control" placeholder="Bill Payment Gateway Password" value="<?php echo $rows_other['password']; ?>" required>
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

	$insert = mysqli_query($link, "INSERT INTO billpayment VALUES(null,'$agentid','$token','$api_url','$email','$username','$password','Active')") or die ("Error: " . mysqli_error($link));
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
                  <input name="password" type="text" class="form-control" placeholder="Bill Payment Gateway Password" required>
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