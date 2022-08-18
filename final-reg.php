<?php 
include "config/connect.php";
include("application/alert_sender/sms_charges.php");
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
</head>
<body style="background-color: #666666;">
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form" method="post" enctype="multipart/form-data">
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
   				<a href="index.php"><h3 style="color: red;"><strong><?php echo $row ['name'];?></strong></h3></a>
  				<?php }}?>
  			</div>
					</span>
					 <div style="color: blue;" align="center"><b>PAY REGISTRATION / YEARLY PREMIUM FEE TO ACTIVATE YOUR ACCOUNT</b></div>
					 <hr>

				<?php
				$search_member = mysqli_query($link, "SELECT * FROM systemset");
				$fetch_member = mysqli_fetch_object($search_member);
				?>
					
				<div class="input-group">
					<div class="wrap-input100">
						<input class="input100" type="text" class="form-control" name="reg_fee" value="<?php echo number_format(($fetch_member->reg_fee+$fetch_member->yearly_renewer_amt),2,'.',','); ?>" readonly>
						<span class="focus-input100"></span>
						<span class="label-input100"></span>
					</div>
					
					<div class="input-group-btn">
						<button type="button" onclick="payWithPaystack()" class="btn bg-blue"><i class="fa fa-send"></i> <b>Pay</b></button>
        			</div>
<?php							
	//basic deposit amount to kickstart savings 
	$ekey = $_GET['activation_key'];
	 
	$search_active = mysqli_query($link, "SELECT * FROM activate_member WHERE shorturl ='$ekey' AND attempt='No'") or die ("Error: " . mysqli_error($link));
	$get_bactive = mysqli_fetch_array($search_active);
	$acn = $get_bactive['acn'];
	
	$search_member = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'") or die ("Error: " . mysqli_error($link));
	$get_member = mysqli_fetch_object($search_member);
	
	$system_set = mysqli_query($link, "SELECT * FROM systemset") or die ("Error: " . mysqli_error($link));
	$row1 = mysqli_fetch_object($system_set);
?>

        		</div>

        		<div class="help-block">
    				<br>
    				<span style="color:blue;">Please be informed that you are required to make a registration fee of <b>NGN<?php echo number_format($fetch_member->reg_fee,2,'.',','); ?></b> and Yearly Premium Fee of <b>NGN<?php echo number_format($fetch_member->yearly_renewer_amt,2,'.',','); ?></b>.</span>
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

				</form>

<form >
  <script src="https://js.paystack.co/v1/inline.js"></script>
</form>

<script>
  function payWithPaystack(){
    var handler = PaystackPop.setup({
      key: '<?php echo $row1->public_key; ?>',
      email: '<?php echo $get_member->email; ?>',
      amount: <?php echo ($row1->reg_fee+$row1->yearly_renewer_amt) * 100; ?>,
	  //currency: '<?php //echo $get_systemset->currency; ?>',
      //ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
      firstname: '<?php echo $get_member->fname; ?>',
      lastname: '<?php echo $get_member->lname; ?>',
      // label: "Optional string that replaces customer email"
      metadata: {
         custom_fields: [
            {
                display_name: "Mobile Number",
                variable_name: "mobile_number",
                value: "<?php echo $get_member->phone; ?>",
            }
         ]
      },
      callback: function(response){
		  alert('success. transaction ref is ' + response.reference);
		  window.location='index.php?activation_key=<?php echo $ekey; ?>&&did=<?php echo $acn; ?>' + '&&refid=' + response.reference;
      },
      onClose: function(){
          alert('window closed');
      }
    });
    handler.openIframe();
  }
</script>
				<div class="login100-more" style="background-image: url('image/imgpsh_fullsize-3.jpg');" align="center">
					<br><br><br><br>
					<?php 
				 		$result= mysqli_query($link,"select * from systemset")or die(mysqli_error());
				 		while($row=mysqli_fetch_array($result))
				 		{
					?>
   					<i> <?php //echo $row ['map'];?> </i>
   					<?php }?>
				</div>
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
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="dist/js/main.js"></script>
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://js.paystack.co/v1/paystack.js"></script>	


</body>
</html>