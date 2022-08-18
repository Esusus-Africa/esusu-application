<?php 
include "config/connect.php";
session_start();
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
	<link rel="icon" type="image/png" href="<?php echo ($fetch_msmset['cname'] == '') ? $row['file_baseurl'].$row['image'] : $row['file_baseurl'].$fetch_msmset['logo']; ?>"/>
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
			
				
					<img src="<?php echo (($fetch_msmset['logo'] == "" || $fetch_msmset['logo'] == "img/") && $fetch_msmset['cname'] == '') ? $row['file_baseurl'].$row['image'] : $row['file_baseurl'].$fetch_msmset['logo']; ?>" alt="User Image" width="100" height="100">
   				<a href="#"><h3 style="color: <?php ($fetch_msmset['theme_color'] == '') ? '#38A1F3' : $fetch_msmset['theme_color']; ?>; font-family: Century Gothic;"><strong><?php echo ($fetch_msmset['cname'] == "") ? $row['name'] : $fetch_msmset['cname']; ?></strong></h3></a>
				
  			</div>
					</span>
					
				<div class="input-group">
					<div class="wrap-input100">
						<input class="input100" type="password" class="form-control" name="otp" inputmode="numeric" pattern="[0-9]*" required>
						<span class="focus-input100"></span>
						<span class="label-input100"><i class="fa fa-lock"></i>&nbsp;OTP Code</span>
					</div>
					
					<div class="input-group-btn">
          				<button name="submit" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-black" style="background-color: black;"><i class="fa fa-send text-muted"></i></button>
        			</div>
        		</div>

        		<div class="help-block text-center">
    				<b style="color:black;">ENTER OTP CODE RECIEVED</b>
  				</div>
  				<div class="text-center">

  				</div>
  				<br>
  				<br>
					<hr>
					<div class="text-center">
						<strong><?php echo ($fetch_msmset['copyright'] == "") ? "Copyright ".date("Y").". Powered by Esusu Africa" : $fetch_msmset['copyright']; ?></strong>
					</div>

<?php	
if (isset($_POST['submit']))
{

	$myip = getUserIP();
	$dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$myip), true);
	$latitude = $dataArray["geoplugin_latitude"];
	$longitude = $dataArray["geoplugin_longitude"];
	$date_time = date("Y-m-d h:i:s");

	$sysabb = $_GET['id'];
	// check for valid otp
	$otpCode = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['otp']));

	//Confirm Institution Id
	$search_mmemberset = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$sysabb'");
	$fetch_mmemberset = mysqli_fetch_array($search_mmemberset);
	$institutionid = $fetch_mmemberset['companyid'];

	$verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$otpCode' AND status = 'Pending'");
	$fetch_data = mysqli_fetch_array($verify_otp);
	$otpnum = mysqli_num_rows($verify_otp);
	$concat = $fetch_data['data'];
	$datetime = $fetch_data['datetime'];
	$parameter = (explode('|',$concat));
	$userType = $fetch_data['userid'];

	$ua = getBrowser();
	$yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
	$browserid = md5($yourbrowser.$token.$longitude.$latitude.uniqid());

	if($otpnum == 0){

		echo "<div class='alert alert-danger'>Oops!...Invalid OTP!!</div>";

	}
	elseif($otpnum == 1 && $userType == "customer")
	{
		
		$_SESSION['tid'] = $parameter[0];
		$_SESSION['acctno'] = $parameter[1];
		$_SESSION['bbranchid'] = $parameter[2];
		$username = $parameter[3];

		setcookie("PHPSESSID", "", time()-3600);
		setcookie("PHPSESSID", $browserid, time()+30*24*60*60);
		$mycookies = $_COOKIE['PHPSESSID'];
		
		mysqli_query($link, "UPDATE session_tracker SET ipAddress = '$myip', latitude = '$latitude', longitude = '$longitude', browserSession = '$mycookies', mybrowser = '$yourbrowser', loginStatus = 'On', LastVisitDateTime = '$date_time' WHERE username = '$username'");
		mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = 'customer' AND otp_code = '$otpCode' AND status = 'Pending'");

		echo '<meta http-equiv="refresh" content="2;url=loader2.php?tid='.$_SESSION['tid'].'&&brch='.$_SESSION['bbranchid'].'&&acn='.$_SESSION['acctno'].'">';
		echo "<hr>";
		echo "<div class='alert alert-success'>You have Successfully Login</div>";

	}
	elseif($otpnum == 1 && $userType == "institution")
	{

		$_SESSION['tid'] = $parameter[0];
		$_SESSION['instid'] = $parameter[1];
		$_SESSION['istaff'] = $parameter[2];
		$username = $parameter[3];

		setcookie("PHPSESSID", "", time()-3600);
		setcookie("PHPSESSID", $browserid, time()+30*24*60*60);
		$mycookies = $_COOKIE['PHPSESSID'];
		
		mysqli_query($link, "UPDATE session_tracker SET ipAddress = '$myip', latitude = '$latitude', longitude = '$longitude', browserSession = '$mycookies', mybrowser = '$yourbrowser', loginStatus = 'On', LastVisitDateTime = '$date_time' WHERE username = '$username'");
		mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = 'institution' AND otp_code = '$otpCode' AND status = 'Pending'");

		echo '<meta http-equiv="refresh" content="2;url=loader1.php?id='.$_SESSION['tid'].'&&instid='.$_SESSION['instid'].'&&istaff='.$_SESSION['istaff'].'">';
		echo "<hr>";
		echo "<div class='alert alert-success'>You have Successfully Login</div>";

	}
	elseif($otpnum == 1 && $userType == "vendor")
	{
		
		$_SESSION['tid'] = $parameter[0];
		$_SESSION['vendorid'] = $parameter[1];
		$_SESSION['merchantid'] = $parameter[2];
		$_SESSION['vstaff'] = $parameter[3];
		$username = $parameter[4];

		setcookie("PHPSESSID", "", time()-3600);
		setcookie("PHPSESSID", $browserid, time()+30*24*60*60);
		$mycookies = $_COOKIE['PHPSESSID'];
		
		mysqli_query($link, "UPDATE session_tracker SET ipAddress = '$myip', latitude = '$latitude', longitude = '$longitude', browserSession = '$mycookies', mybrowser = '$yourbrowser', loginStatus = 'On', LastVisitDateTime = '$date_time' WHERE username = '$username'");
		mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = 'vendor' AND otp_code = '$otpCode' AND status = 'Pending'");

		echo '<meta http-equiv="refresh" content="2;url=loader5.php?tid='.$_SESSION['tid'].'&&vend_id='.$_SESSION['vendorid'].'&&merch_id='.$_SESSION['merchantid'].'&&vstaff='.$_SESSION['vstaff'].'">';
		echo "<hr>";
		echo "<div class='alert alert-success'>You have Successfully Login</div>";

	}
	elseif($otpnum == 1 && $userType == "aggregator")
	{

		$_SESSION['tid'] = $parameter[0];
		$username = $parameter[1];

		setcookie("PHPSESSID", "", time()-3600);
		setcookie("PHPSESSID", $browserid, time()+30*24*60*60);
		$mycookies = $_COOKIE['PHPSESSID'];
		
		mysqli_query($link, "UPDATE session_tracker SET ipAddress = '$myip', latitude = '$latitude', longitude = '$longitude', browserSession = '$mycookies', mybrowser = '$yourbrowser', loginStatus = 'On', LastVisitDateTime = '$date_time' WHERE username = '$username'");
		mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = 'aggregator' AND otp_code = '$otpCode' AND status = 'Pending'");

		echo '<meta http-equiv="refresh" content="2;url=aggloader.php?tid='.$_SESSION['tid'].'">';
		echo "<hr>";
		echo "<div class='alert alert-success'>You have Successfully Login</div>";
	
	}
	elseif($otpnum == 1 && $userType == "esusuafrica")
	{
		
		$_SESSION['tid'] = $parameter[0];
		$username = $parameter[1];
		
		setcookie("PHPSESSID", "", time()-3600);
		setcookie("PHPSESSID", $browserid, time()+30*24*60*60);
		$mycookies = $_COOKIE['PHPSESSID'];
		
		mysqli_query($link, "UPDATE session_tracker SET ipAddress = '$myip', latitude = '$latitude', longitude = '$longitude', browserSession = '$mycookies', mybrowser = '$yourbrowser', loginStatus = 'On', LastVisitDateTime = '$date_time' WHERE username = '$username'");
		mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = 'esusuafrica' AND otp_code = '$otpCode' AND status = 'Pending'");

		echo '<meta http-equiv="refresh" content="2;url=loader.php?tid='.$_SESSION['tid'].'">';
		echo "<hr>";
		echo "<div class='alert alert-success'>You have Successfully Login</div>";

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

<script>
  $(".toggle-password").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
</script>

<!--===============================================================================================-->

	<script src="dist/js/main.js"></script>

<?php
function isMobileDevice(){
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

$mysenderid = $_GET['id'];
$verify_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$mysenderid'");
$fetch_memset = mysqli_fetch_object($verify_memset);
$instid = $fetch_memset->companyid;

$search_lvWidget = mysqli_query($link, "SELECT * FROM dedicated_livechat_widget WHERE companyid = '$instid'");
$fetch_lvWidget = mysqli_fetch_array($search_lvWidget);
$lvWidgetStatus = $fetch_lvWidget['status']; //Activated OR NotActivated

if(($lvWidgetStatus == "" || $lvWidgetStatus == "NotActivated") && !(isMobileDevice()))
{
?>
  
<!-- Live Chat 3 widget -->
<script type="text/javascript">
	(function(w, d, s, u) {
		w.id = 1; w.lang = ''; w.cName = ''; w.cEmail = ''; w.cMessage = ''; w.lcjUrl = u;
		var h = d.getElementsByTagName(s)[0], j = d.createElement(s);
		j.async = true; j.src = 'https://esusu.app/cs/js/jaklcpchat.js';
		h.parentNode.insertBefore(j, h);
	})(window, document, 'script', 'https://esusu.app/cs/');
</script>
<div id="jaklcp-chat-container"></div>
<!-- end Live Chat 3 widget -->

<?php
}
elseif($lvWidgetStatus == "Activated" && !(isMobileDevice())){

    echo base64_decode($fetch_lvWidget['livechat_widget']);

}else{
    //Do nothing
}
?>

<script>
    $(document).ready(function () {
        $('#iframe1').on('load', function () {
            $('#loader1').hide();
        });
    });
</script>


</body>
</html>