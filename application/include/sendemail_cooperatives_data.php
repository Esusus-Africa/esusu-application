<div class="box">
        
	        <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><a href="customer?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("403"); ?>&&mid=NDAz"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a><i class="fa fa-envelope-o"></i> New Email</h3>
            </div>
             <div class="box-body">
				<form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php							
if (isset($_POST['send']))
{
	
	$email = $_POST['to'];
	$subj = $_POST['subject'];
	$msg = $_POST['msg'];
	
	switch ($email) {
		case ($email == "All"):
			$search_cust = mysqli_query($link, "SELECT * FROM cooperatives") or die("Error:" . mysqli_error($link));
			while($get_cust = mysqli_fetch_array($search_cust))
			{
				//$fname = $get_cust['fname'];
				$bulkmail = $get_cust['email']; // email id of user
				
				$query = mysqli_query($link, "SELECT email FROM systemset") or die (mysqli_error($link));
				$r = mysqli_fetch_object($query);
				//send email
				$subject = "$subj";
				$body = "$msg";
	  			$additionalheaders = "MIME-Version: 1.0" . "\r\n";
	  			$additionalheaders .= "Content-Type: text/html;charset=ISO-8859-1" . "\r\n";
	  			$additionalheaders .= "From:$r->email" . "\r\n";
	  			$additionalheaders .= "Reply-To:noreply@email.com" ."\r\n";
				if(mail($bulkmail,$subject,$body,$additionalheaders))
				{
					echo "<hr>";
					echo "<div class='alert alert-success'>Email Sent! </div>";
				}
				else{
					echo "<hr>";
					echo "<div class='alert alert-danger'>Failed to Send Email, try again later!! </div>";
				}
			}
			break;
			
		case ($email != "All"):
			$query = mysqli_query($link, "SELECT email FROM systemset") or die (mysqli_error($link));
			$r = mysqli_fetch_object($query);
			//send email
			$to = "$email";
			$subject = "$subj";
			$body = "$msg";
			$additionalheaders = "MIME-Version: 1.0" . "\r\n";
			$additionalheaders .= "Content-Type: text/html;charset=ISO-8859-1" . "\r\n";
			$additionalheaders .= "From:$r->email" . "\r\n";
			$additionalheaders .= "Reply-To:noreply@imon.com" ."\r\n";
			if(mail($to,$subject,$body,$additionalheaders))
			{
				echo "<hr>";
				echo "<div class='alert alert-success'>Email Sent!</div>";
			}
			else{
				echo "<hr>";
				echo "<div class='alert alert-danger'>Failed to Send Email, try again later!!</div>";
			}
			break;
		default:
			echo "<hr>";
			echo "<div class='alert alert-danger'>Sorry! Network Error!!</div>";
	}
}
?>				
              	<div class="box-body">
				
				<div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Select Receiver</label>
                  <div class="col-sm-8">
					<select name="to"  class="form-control select2" required style="width:100%">
					<option selected="selected">Select Receiver Here...</option>
					<optgroup label="SELECT INDIVIDUAL / ALL COOPERATIVES">
					<?php
					$get = mysqli_query($link, "SELECT * FROM cooperatives ORDER BY id") or die (mysqli_error($link));
					if(mysqli_num_rows($get)==0)
					{
					echo "<div class='alert alert-info'>No record found!</div>";
					}
					else{
					$num = mysqli_num_rows($get);
					echo "<option value='All'>All Cooperatives ($num)</option>";
					while($rows = mysqli_fetch_array($get))
					{
						
					?>
					<option value="<?php echo $rows['official_email']; ?>"><?php echo $rows['coopname']; ?></option>
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
                				<button type="reset" class="btn bg-orange"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="send" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-upload">&nbsp;Send</i></button>
              </div>
			  </div>
				</form>

	
</div>	
</div>	
</div>
</div>