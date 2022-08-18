<div class="box">

	         <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
			<?php
			$check_sms = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated'") or die (mysqli_error($link));
			if(mysqli_num_rows($check_sms) == 1)
			{
			$get_sms = mysqli_fetch_array($check_sms);
			$ozeki_user = $get_sms['username'];
			$ozeki_password = $get_sms['password'];
			$ozeki_url = $get_sms['api'];

			$url = 'username='.$ozeki_user;
			$url.= '&password='.$ozeki_password;
			$url.= '&balance='.'true&';

			$urltouse = $ozeki_url.$url;
			$response = file_get_contents($urltouse);
			?>
			            <h3 class="panel-title"><a href="listemployee.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("409"); ?>&&mid=NDAz"><button type="button" class="btn btn-flat btn-warning"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-envelope"></i> Send SMS &nbsp;|&nbsp; <span class="label label-success" style="color: #FFFFFF;"> <b>Available Units:<b></span> <?php echo number_format($response,2,'.',','); ?> </h3>
			<?php
			}
			else{
			?>	
						 <h3 class="panel-title"><a href="customer.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("403"); ?>&&mid=NDAz"><button type="button" class="btn btn-flat btn-warning"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-envelope"></i> Send SMS <span class="label label-danger"> You do not have any SMS Credits available OR Your SMS Gateway is not yet Activated. Kindly goto Settings to Activate the SMS. </span> </h3>
			<?php } ?>
            </div>
             <div class="box-body">
		<form class="form-horizontal" method="post" enctype="multipart/form-data">
		 <?php echo '<div class="alert alert-info fade in" >
			  <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
  				<strong>Note that&nbsp;</strong> &nbsp;&nbsp;Some Fields are Rquired.
				</div>'?>
			 <div class="box-body">
			 
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:#009900">Select Employee</label>
                  <div class="col-sm-10">
				<select name="to"  class="form-control select2" required style="width:100%">
				<option selected="selected">Select Employee Here...</option>
				<option value="All">All Employee</option>
				<option disabled>SELECT INDIVIDUAL EMPLOYEE</option>
				<?php
				$tid = $_SESSION['tid'];
				$get = mysqli_query($link, "SELECT * FROM user WHERE id != '$tid'") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['phone']; ?>"><?php echo $rows['name']; ?></option>
				<?php } ?>				
				</select>
										</div>
										</div>	
			
			   <div class="form-group">
                	<label for="" class="col-sm-2 control-label" style="color:#009900">Message Contents</label>
                	<div class="col-sm-10">
				<textarea name="message" class="form-control" rows="4" cols="5"></textarea>
         		</div>
			</div>
			</div>
				
				<div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn btn-primary btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="send" type="submit" class="btn btn-success btn-flat"><i class="fa fa-save">&nbsp;Send</i></button>

              </div>
			  </div>
<?php							
if (isset($_POST['send_sms']))
{
	$sql = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated'") or die (mysqli_error($link));
	$find = mysqli_fetch_array($sql);
	$ozeki_user = $find['username'];
	$ozeki_password = $find['password'];
	$ozeki_url = $find['api'];
	$status = $find['status'];

	if($status == "Activated")
	{
	########################################################
	# Functions used to send the SMS message
	########################################################

	function ozekiSend($sender, $phone, $msg, $debug=false){
		  global $ozeki_user,$ozeki_password,$ozeki_url;

		  $url = 'username='.$ozeki_user;
		  $url.= '&password='.$ozeki_password;
		  $url.= '&sender='.urlencode($sender);
		  $url.= '&recipient='.urlencode($phone);
		  $url.= '&message='.urlencode($msg);

		  $urltouse =  $ozeki_url.$url;
		  //if ($debug) { echo "Request: <br>$urltouse<br><br>"; }

		  //Open the URL to send the message
		  $response = file_get_contents($urltouse);
		  if ($debug) {
			   //echo "Response: <br><pre>".
			   //str_replace(array("<",">"),array("&lt;","&gt;"),$response).
			   //"</pre><br>"; 
			   }

		  return($response);
	}
	
	$cto = mysqli_real_escape_string($link, $_POST['to']);
	$msg = mysqli_real_escape_string($link, $_POST['message']);
	
	switch ($cto) {
		case ($cto == "All"):
			$search_cust = mysqli_query($link, "SELECT * FROM user") or die("Error:" . mysqli_error($link));
			while($get_cust = mysqli_fetch_array($search_cust))
			{
				$system_set = mysqli_query($link, "SELECT * FROM systemset");
				$get_sysset = mysqli_fetch_array($system_set);
				$name = $get_cust['name'];
				$phone = $get_cust['phone'];
				$sender = $get_sysset['abb'];

				$message .= "Dear ".$name."! ";
				$message .= "".$msg."";
				$debug = true;
				ozekiSend($sender,$phone,$message,$debug);
			}
			echo "<script>alert('Sending Message...Done!');</script>";
			break;
		case ($cto != "All"):
			$search_cust = mysqli_query($link, "SELECT * FROM user WHERE phone = '$cto'") or die("Error:" . mysqli_error($link));
			while($get_cust = mysqli_fetch_array($search_cust))
			{
				$system_set = mysqli_query($link, "SELECT * FROM systemset");
				$get_sysset = mysqli_fetch_array($system_set);
				$name = $get_cust['name'];
				$sender = $get_sysset['abb'];

				$message .= "Dear ".$name."! ";
				$message .= "".$msg."";
				$debug = true;
				ozekiSend($sender,$cto,$message,$debug);
			}
			echo "<script>alert('Sending Message...Done!');</script>";
			break;
		default:
			echo "<script>alert('Unable to deliver message.....Please try again later!');</script>";
	}
	}else{
		echo "<script>alert('Sorry! SMS Gateway Not Activated Yet!!');</script>";
	}
}
?>
			  </form>


</div>	
</div>	
</div>
</div>