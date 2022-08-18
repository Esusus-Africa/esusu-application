<?php 
include "config/connect.php";
//include("application/alert_sender/sms_charges.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php 
	$call = mysqli_query($link, "SELECT * FROM systemset");
	if(mysqli_num_rows($call) == 0)
	{
		echo "<script>alert('Data Not Found!'); </script>";
	}
	else
	{
	while($row = mysqli_fetch_assoc($call)){
	?>
	<title><?php echo $row ['title']?></title>
	<?php }}?> 
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<?php 
$call = mysqli_query($link, "SELECT * FROM systemset");
if(mysqli_num_rows($call) == 0)
{
echo "<script>alert('Data Not Found!'); </script>";
}
else
{
while($row = mysqli_fetch_assoc($call)){
?>
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="img/<?php echo $row['image']; ?>"/>
<!--===============================================================================================-->
<?php }} ?> 
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="font/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="font/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="dist/css/util.css">
	<link rel="stylesheet" type="text/css" href="dist/css/main.css">
<!--===============================================================================================-->
<!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
<!--===============================================================================================-->
<script type="text/javascript">
function loaddata()
{
 var bvn=document.getElementById("unumber").value;

 if(bvn)
 {
  $.ajax({
  type: "POST",
  url: "application/verify_bvn.php",
  data: {
  	my_bvn: bvn
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
	  //alert(response);
   $('#bvn2').html(response);
  }
  });
 }

 else
 {
  $('#bvn2').html("<p class='label label-success'>Please Enter Correct BVN Number Here</p>");
 }
}
</script>
</head>
<body style="background-color: #666666;">
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" method="post" enctype="multipart/form-data">
					<span class="login100-form-title" style="color:blue;">
						<div class="login-logo">
			<?php 
			$call = mysqli_query($link, "SELECT * FROM systemset");
			if(mysqli_num_rows($call) == 0)
			{
				echo "<script>alert('Data Not Found!'); </script>";
			}
			else
			{
			while($row = mysqli_fetch_assoc($call)){
			?>
   				<img src="img/<?php echo $row ['image'] ;?>" class="img-circle" alt="User Image" width="64" height="64">
   				<a href="index.php"><h3 style="color: #0073b7;"><strong><?php echo $row ['name'];?></strong></h3></a>
  				<?php }}?>
  			</div>
					</span>
				<div class="help-block text-center">
    				<h4><b style="color:orange;">(GUARANTOR CONFIRMATION FORM)</b></h4>
  				</div>
					
				<div class="input-group">
					<div class="wrap-input100">
						<input class="input100" type="password" class="form-control" name="opt" required>
						<span class="focus-input100"></span>
						<span class="label-input100"><i class="fa fa-asterisk"></i>&nbsp;Enter the OPT Code sent to your phone here</span>
					</div>
					
					<div class="input-group-btn">
          				<button name="submit" type="submit" class="btn"><i class="fa fa-send text-muted"></i></button>
        			</div>
        		</div>

        		
  				<div class="text-center">
    				<a href="index.php" class="btn bg-blue">Or sign in as different user</a>
  				</div>
					<hr>
					<div class="text-center">
							<?php 
				 			$result= mysqli_query($link,"select * from systemset")or die(mysqli_error());
				 			while($row=mysqli_fetch_array($result))
				 			{
				 			?>
    						<i> <?php echo $row ['footer'];?> </i>
							<?php }?>
					</div>

<?php							
if (isset($_POST['submit']))
{
// check for valid email address
$opt = $_POST['otp'];

// checks if the username is in use
$check = mysqli_query($link, "SELECT * FROM coop_admin_guarantors WHERE status = '$opt'")or die(mysqli_error($link));
$snum = mysqli_num_rows($check);
if($snum == 0){
	echo "<div class='alert alert-danger'>Invalid OTP Code</div>";
}
else{
	$update = mysqli_query($link, "UPDATE coop_admin_guarantors SET status = 'Approved' WHERE status = '$otp'");
	echo "<script>alert('Great! OTP Confirmed Successfully'); </script>";
	echo "<script>window.location='index.php'; </script>";
}
}
?>
				</form>
			<?php 
				 		$result= mysqli_query($link,"select * from systemset")or die(mysqli_error());
				 		while($row=mysqli_fetch_array($result))
				 		{
					?>
				<div class="login100-more" style="background-image: url('image/<?php echo $row['lbackg']; ?>);" align="center">
					<br><br><br><br>
					
   					<i> <?php //echo $row ['map'];?> </i>
   					
				</div>
				<?php }?>
			</div>
		</div>
	</div>

	
	<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
	<script>
     $('#accounttype').change(function(){
         var PostType=$('#accounttype').val();
         $.ajax({url:"Ajax-ShowPostACType.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
 	</script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="dist/js/main.js"></script>

<!-- live chat 3 widget -->
<script type="text/javascript">
	(function(w, d, s, u) {
		w.id = 1; w.lang = ''; w.cName = ''; w.cEmail = ''; w.cMessage = ''; w.lcjUrl = u;
		var h = d.getElementsByTagName(s)[0], j = d.createElement(s);
		j.async = true; j.src = 'https://esusu.africa/cs/js/jaklcpchat.js';
		h.parentNode.insertBefore(j, h);
	})(window, document, 'script', 'https://esusu.africa/cs/');
</script>
<div id="jaklcp-chat-container"></div>
<!-- end live chat 3 widget -->		


</body>
</html>