<?php 
include "config/connect.php";
//include("application/alert_sender/sms_charges.php");
?>
<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<?php 
	$compid = (isset($_GET['id']) == true) ? $_GET['id'] : '';
    $call_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$compid'");
    $fetch_msmset = mysqli_fetch_array($call_memset);
	
	$call = mysqli_query($link, "SELECT * FROM systemset");
	if(mysqli_num_rows($call) == 0)
	{
		echo "<script>alert('Data Not Found!'); </script>";
	}
	else
	{
	while($row = mysqli_fetch_assoc($call)){
	?>
	<title><?php echo ($fetch_msmset['cname'] == '') ? $row['title'] : $fetch_msmset['cname']; ?></title>
	<?php }}?> 
	
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
	<link rel="icon" type="image/png" href="<?php echo ($fetch_msmset['cname'] == '') ? 'img/'.$row['image'] : $fetch_msmset['logo']; ?>"/>
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
	
	<style> 
        body { 
            animation: fadeInAnimation ease 3s;
            opacity: 0.1; 
            transition: opacity 3s; 
        }
        @keyframes fadeInAnimation { 
            0% { 
                opacity: 0; 
                pointer-events: none;
                transition: opacity 3s;
            }
            20% { 
                opacity: 0.2; 
                pointer-events: none;
                transition: opacity 3s;
            }
            40% { 
                opacity: 0.3; 
                pointer-events: none;
                transition: opacity 3s;
            }
            60% { 
                opacity: 0.5; 
                pointer-events: none;
                transition: opacity 3s;
            }
            80% { 
                opacity: 0.7; 
                pointer-events: none;
                transition: opacity 3s;
            }
            100% { 
                opacity: 1; 
                transition: opacity 3s;
            } 
        } 
    </style>
    
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
<body style="background-color: #666666;" onload="document.body.style.opacity='1'">
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" method="post" enctype="multipart/form-data">
					<span class="login100-form-title" style="color:blue;">
						<div class="login-logo">
			<?php 
			$call = mysqli_query($link, "SELECT * FROM systemset");
			$row = mysqli_fetch_assoc($call);
			?>
			
				
					<img src="<?php echo (($fetch_msmset['logo'] == "" || $fetch_msmset['logo'] == "img/") && $fetch_msmset['cname'] == '') ?  'img/'.$row['image'] : $fetch_msmset['logo']; ?>" alt="User Image" width="100" height="100">
   				<a href="#"><h3 style="color: <?php ($fetch_msmset['theme_color'] == '') ? '#38A1F3' : $fetch_msmset['theme_color']; ?>; font-family: Century Gothic;"><strong><?php echo ($fetch_msmset['cname'] == "") ? $row['name'] : $fetch_msmset['cname']; ?></strong></h3></a>
				
  			</div>
					</span>
					
				<div class="input-group">
					<div class="wrap-input100">
						<input class="input100" type="password" class="form-control" name="otp" required>
						<span class="focus-input100"></span>
						<span class="label-input100"><i class="fa fa-lock"></i>&nbsp;OTP Code</span>
					</div>
					
					<div class="input-group-btn">
          				<button name="submit" type="submit" class="btn"><i class="fa fa-send text-muted"></i></button>
        			</div>
        		</div>

        		<div class="help-block text-center">
    				<b style="color:black;">ENTER OTP CODE RECIEVED TO ACTIVATE YOUR ACCOUNT</b>
  				</div>
  				<div class="text-center">

  				</div>
  				<br>
  				<br>
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
function startsWith($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}

if (isset($_POST['submit']))
{
// check for valid otp
$otp = $_POST['otp'];

$check1 = mysqli_query($link, "SELECT * FROM borrowers WHERE acct_status = '$otp'")or die(mysqli_error($link));
$fetch_customer = mysqli_fetch_object($check1);
$cnum = mysqli_num_rows($check1);

$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$r = mysqli_fetch_object($query);

$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
$fetch_gateway = mysqli_fetch_object($search_gateway);
$gateway_uname = $fetch_gateway->username;
$gateway_pass = $fetch_gateway->password;
$gateway_api = $fetch_gateway->api;
		
$wallet_date_time = date("Y-m-d h:i:s");

if($cnum == 0){
	echo "<div class='alert alert-danger'>Oops!...Invalid OTP Entered</div>";
}
elseif($cnum == 1)
{
	$lname = $fetch_customer->lname;
	$account = $fetch_customer->account;
    $username = $fetch_customer->username;
    $password = $fetch_customer->password;
    $phone = $fetch_customer->phone;
	$institutionid = $fetch_customer->branchid;
	$transactionPin = $fetch_customer->tpin;
    
    $search_mmemberset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institutionid'");
    $fetch_mmemberset = mysqli_fetch_array($search_mmemberset);
    $senderid = $fetch_mmemberset['sender_id'];
	$sysabb = ($senderid == "") ? $r->abb : $senderid;

	$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institutionid'");
    $detect_memset = mysqli_fetch_array($search_memset);
    $mobileapp_link = ($detect_memset['mobileapp_link'] == "") ? "Login at https://esusu.app/$senderid" : "Download mobile app: ".$detect_memset['mobileapp_link'];
	
	$search_va = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$account'");
	$fetch_va = mysqli_fetch_array($search_va);
	$accountNumber = $fetch_va['account_number'];
    $bankName = $fetch_va['bank_name'];
    
    $sms = "$sysabb>>>Welcome $lname! Your Wallet Account No: $accountNumber, Bank: $bankName, Transaction Pin: $transactionPin. $mobileapp_link";
    
    $max_per_page = 153;
	$sms_length = strlen($sms);
	$calc_length = ceil($sms_length / $max_per_page);
	$sms_rate = $r->fax;
	
	$search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$institutionid'");
    $fetch_myinst = mysqli_fetch_array($search_insti);
    $iwallet_balance = $fetch_myinst['wallet_balance'];
    	
	$sms_charges = $calc_length * $sms_rate;
	$imywallet_balance = $iwallet_balance - $sms_charges;
    $sms_refid = "EA-smsCharges-".rand(1000000,9999999);
    $inst_type = (startsWith($institutionid,"AGT") ? 'agent' : (startsWith($institutionid,"INST") ? 'institution' : 'merchant'));
    
    include('cron/send_general_sms.php');
    
    ($senderid == "") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institutionid','$sms_refid','$phone','$sms_charges','NGN','system','SMS Content: $sms','successful','$wallet_date_time','$account','$iwallet_balance')");
    mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$institutionid','$inst_type','$sysabb','$phone','$sms','Sent',NOW())");
    ($senderid == "") ? "" : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$imywallet_balance' WHERE institution_id = '$institutionid'");
    mysqli_query($link, "UPDATE borrowers SET acct_status = 'Activated' WHERE acct_status = '$otp'") or die (mysqli_error($link));
    
    echo ($senderid == "") ? '<meta http-equiv="refresh" content="2;url=/">' : '<meta http-equiv="refresh" content="2;url=/'.$sysabb.'">';
    echo "<hr>";
    echo "<div class='alert alert-success'>Congrat! Account Activated Successfully!!</div>";

}
}
?>
				</form>

				<?php 
				 		$result= mysqli_query($link,"select * from systemset")or die(mysqli_error());
				 		while($row=mysqli_fetch_array($result))
				 		{
					?>
				<div class="login100-more" style="background-image: url('image/<?php echo ($fetch_msmset['frontpg_backgrd'] == "") ? $row['lbackg'] : $fetch_msmset['frontpg_backgrd']; ?>');" align="center">
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

</body>
</html>