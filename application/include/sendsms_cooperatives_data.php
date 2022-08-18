<div class="box">

	         <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
<?php
$check_sms = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated' AND smsuser = ''") or die (mysqli_error($link));
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
            <h3 class="panel-title"><a href="listcooperatives?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("418"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-envelope"></i> Send SMS &nbsp;|&nbsp; <span class="label bg-blue" style="color: white;"> <b>Available Units:<b></span> <?php echo number_format($response,2,'.',','); ?> </h3>
<?php
}
else{
?>	
			 <h3 class="panel-title"><a href="listcooperatives?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("418"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-envelope"></i> Send SMS <span class="label bg-orange"> You do not have any SMS Credits available OR Your SMS Gateway is not yet Activated. Kindly goto Settings to Activate the SMS.. </span> </h3>
<?php } ?>
		   </div>
             <div class="box-body">


		<form class="form-horizontal" method="post" enctype="multipart/form-data">
	
			 <div class="box-body">
			 
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Cooperatives</label>
                  <div class="col-sm-10">
				<select name="cto"  class="form-control select2" required style="width:100%">
				<option selected="selected">Select Cooperatives...</option>
				<option value="">All Cooperatives</option>
				<option disabled>SELECT INDIVIDUAL COOPERATIVE</option>
				<?php
				$get = mysqli_query($link, "SELECT * FROM cooperatives ORDER BY id") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
				<option value="<?php echo $rows['official_phone']; ?>"><?php echo $rows['coopname']; ?></option>
				<?php } ?>				
				</select>
										</div>
										</div>	
				  
				   <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue;">Message</label>
                  	<div class="col-sm-10">
					<textarea name="message" class="form-control" rows="4" cols="5"></textarea>
           		</div>
				</div>
				</div>
				
				<div align="right">
              <div class="box-footer">
                				<button name="send_sms" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-upload">&nbsp;Send</i></button>

              </div>
			  </div>
	<?php						
	if (isset($_POST['send_sms']))
	{
        $phone = mysqli_real_escape_string($link, $_POST['cto']);
	    $sms = mysqli_real_escape_string($link, $_POST['message']);
	    
	    $system_set = mysqli_query($link, "SELECT * FROM systemset");
	    $get_sysset = mysqli_fetch_array($system_set);

	    $sys_abb = $get_sysset['abb'];

	    if($phone == ""){

	      	$search_cmember = mysqli_query($link, "SELECT * FROM cooperatives") or die("Error:" . mysqli_error($link));
	      	while($get_cmember = mysqli_fetch_array($search_cmember))
	      	{
	      		$mobile = $get_cmember['official_phone'];
	      		$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
        	    $fetch_gateway = mysqli_fetch_object($search_gateway);
        	    $gateway_uname = $fetch_gateway->username;
        	    $gateway_pass = $fetch_gateway->password;
        	    $gateway_api = $fetch_gateway->api;
	      		include("../cron/send_general_sms1.php");
	      	}
	      	echo "<script>alert('SMS Sent Successfully'); </script>";

	  	}
	  	elseif($phone != ""){
	  	    $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
    	    $fetch_gateway = mysqli_fetch_object($search_gateway);
    	    $gateway_uname = $fetch_gateway->username;
    	    $gateway_pass = $fetch_gateway->password;
    	    $gateway_api = $fetch_gateway->api;
            include("../cron/send_general_sms.php");
            echo "<script>alert('SMS Sent Successfully'); </script>";
		}
    }
	?>
			  </form>

</div>	
</div>	
</div>
</div>