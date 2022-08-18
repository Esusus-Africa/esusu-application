<div class="box">
        
	        <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><a href="listmerchants.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("490"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a><i class="fa fa-envelope-o"></i> New Email</h3>
            </div>
             <div class="box-body">
				<form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php							
if (isset($_POST['send']))
{
	
	$email = mysqli_real_escape_string($_POST['to']);
	$subj = mysqli_real_escape_string($_POST['subject']);
	$msg = mysqli_real_escape_string($link, $_POST['msg']);
	
	if($email == "All")
	{
		$search_cust = mysqli_query($link, "SELECT * FROM merchant_reg WHERE role = 'Admin'") or die("Error:" . mysqli_error($link));
		while($get_cust = mysqli_fetch_array($search_cust))
		{
			$mcname = $get_cust['company_name'];
			$bulkmail = $get_cust['official_email']; // email id of user
					
			$query = mysqli_query($link, "SELECT email FROM systemset") or die (mysqli_error($link));
			$r = mysqli_fetch_object($query);
			$sys_email = $r->email;
			$sys_abb = $r->abb;

			$sendemail = mysqli_query($link, "INSERT INTO email_log VALUES(null,'$sys_abb','$sys_email','$mcname','$bulkmail','$msg','m_email','Pending',NOW())");
		}
		echo "<script>alert('Email Sent!'); </script>";					echo "<hr>";
						echo "<div class='alert alert-success'>Email Sent! </div>";
	}
	elseif ($email != "All") {
		# code...
		$query = mysqli_query($link, "SELECT email FROM systemset") or die (mysqli_error($link));
		$r = mysqli_fetch_object($query);
		$sys_email = $r->email;
		$sys_abb = $r->abb;

		$search_cust = mysqli_query($link, "SELECT * FROM merchant_reg WHERE role = 'Admin'") or die("Error:" . mysqli_error($link));
		$get_cust = mysqli_fetch_array($search_cust);
		$mcname = $get_cust['company_name'];

		$sendemail = mysqli_query($link, "INSERT INTO email_log VALUES(null,'$sys_abb','$sys_email','$mcname','$email','$msg','m_email','Pending',NOW())");

		if($sendemail)
		{
			echo "<hr>";
			echo "<div class='alert alert-success'>Email Sent!</div>";
		}
		else{
			echo "<hr>";
			echo "<div class='alert alert-danger'>Failed to Send Email, try again later!!</div>";
		}
	}
}
?>				
              	<div class="box-body">
				
				<div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Merchant</label>
                  <div class="col-sm-8">
					<select name="to"  class="form-control select2" required style="width:100%">
					<option selected="selected">Select Merchant Here...</option>
					<optgroup label="SELECT INDIVIDUAL / ALL MERCHANT">
					<?php
					$get = mysqli_query($link, "SELECT * FROM merchant_reg WHERE role = 'Admin' ORDER BY id") or die (mysqli_error($link));
					if(mysqli_num_rows($get)==0)
					{
					echo "<div class='alert alert-info'>No record found!</div>";
					}
					else{
					$num = mysqli_num_rows($get);
					echo "<option value='All'>All Merchants ($num)</option>";
					while($rows = mysqli_fetch_array($get))
					{
						
					?>
					<option value="<?php echo $rows['official_email']; ?>"><?php echo $rows['company_name']; ?></option>
					<?php } } ?>	
					</optgroup>					
					</select>
				  </div>
				</div>	
				
				<div class="form-group">
                  	<label for="" class="col-sm-4 control-label" style="color:blue;">Subject: </label>
                  	<div class="col-sm-8">
					<input type="text" name="subject" class="form-control" placeholder="Enter Subject" /required>
           			 </div>
          			</div>
					
					
					<div class="form-group">
                  	<label for="" class="col-sm-4 control-label" style="color:blue;">Message</label>
                  	<div class="col-sm-8">
					<textarea name="msg" id="editor1" class="form-control" rows="5" cols="80" placeholder="Enter Message Here"></textarea>
           			 </div>
          			</div>
					
				
				</div>
				
			  <div align="right">
              <div class="box-footer">
                				<button name="send" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-cloud-upload">&nbsp;Send Mail!</i></button>
              </div>
			  </div>
				</form>

	
</div>	
</div>	
</div>
</div>