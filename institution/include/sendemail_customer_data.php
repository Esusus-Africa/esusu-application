<div class="box">
        
	        <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><a href="customer.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("403"); ?>&&mid=NDAz"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a><i class="fa fa-envelope-o"></i> New Email</h3>
            </div>
             <div class="box-body">
				<form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php							
if (isset($_POST['send']))
{
	
	$email = mysqli_real_escape_string($link, $_POST['to']);
	$subj = mysqli_real_escape_string($link, $_POST['subject']);
	$msg = mysqli_real_escape_string($link, $_POST['msg']);

	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$r = mysqli_fetch_object($query);
	$sys_abb = $r->abb;
	$sys_email = $r->email;
	$sys_currency = $r->currency;
	
	if($email == "All")
	{
		$search_cust = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id'") or die("Error:" . mysqli_error($link));
		while($get_cust = mysqli_fetch_array($search_cust))
		{
			//$fname = $get_cust['fname'];
			$bulkmail = $get_cust['email']; // email id of user
			$lname = $get_cust['lname'];

			$sendemail = mysqli_query($link, "INSERT INTO email_log VALUES(null,'$sys_abb','$sys_email','$lname','$bulkmail','$msg','customeremail','Pending',NOW())");
		}
		echo "<script>alert('SMS Sent Successfully'); </script>";

	}
	else{
		$search_cust = mysqli_query($link, "SELECT * FROM borrowers WHERE email = '$email'") or die("Error:" . mysqli_error($link));
		$get_cust = mysqli_fetch_array($search_cust);
			//$fname = $get_cust['fname'];
			$lname = $get_cust['lname'];

			$sendemail = mysqli_query($link, "INSERT INTO email_log VALUES(null,'$sys_abb','$sys_email','$lname','$email','$msg','customeremail','Pending',NOW())");
		echo "<script>alert('SMS Sent Successfully'); </script>";
	}
}
?>				
              	<div class="box-body">
				
				<div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Select Receiver</label>
                  <div class="col-sm-8">
					<select name="to"  class="form-control select2" required style="width:100%">
					<option selected="selected">Select Receiver Here...</option>
					<optgroup label="SELECT INDIVIDUAL / ALL CUSTOMERS">
					<?php
					$get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' ORDER BY id") or die (mysqli_error($link));
					if(mysqli_num_rows($get)==0)
					{
					echo "<div class='alert alert-info'>No record found!</div>";
					}
					else{
					$num = mysqli_num_rows($get);
					echo "<option value='All'>All Customers ($num)</option>";
					while($rows = mysqli_fetch_array($get))
					{
						
					?>
					<option value="<?php echo $rows['email']; ?>"><?php echo $rows['fname'].'&nbsp;'.$rows['lname']; ?></option>
					<?php } } ?>	
					</optgroup>					
					</select>
				  </div>
				</div>	
				
				<div class="form-group">
                  	<label for="" class="col-sm-4 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Subject: </label>
                  	<div class="col-sm-8">
					<input type="text" name="subject" class="form-control" placeholder="Enter Subject" /required>
           			 </div>
          			</div>
					
					
					<div class="form-group">
                  	<label for="" class="col-sm-4 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Message</label>
                  	<div class="col-sm-8">
					<textarea name="msg" id="editor1" class="form-control" rows="5" cols="80" placeholder="Enter Message Here"></textarea>
           			 </div>
          			</div>
					
				
				</div>
				
			  <div align="right">
              <div class="box-footer">
                				<button name="send" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-upload">&nbsp;Send</i></button>
              </div>
			  </div>
				</form>

	
</div>	
</div>	
</div>
</div>