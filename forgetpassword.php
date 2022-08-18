<?php 
include "config/connect.php";
require_once "config/smsAlertClass.php";
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
	<link rel="icon" type="image/png" href="<?php echo ($fetch_msmset['cname'] == '') ? $row['file_baseurl'].$row['image'] : $fetch_msmset['logo']; ?>"/>
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
	
	<style type="text/css">
  
  /* ==========================================================================
   Chrome Frame prompt
   ========================================================================== */

    .chromeframe {
        margin: 0.2em 0;
        background: #ccc;
        color: #000;
        padding: 0.2em 0;
    }
    
    /* ==========================================================================
   Author's custom styles
   ========================================================================== */

    #loader-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
    }
    #loader {
        display: block;
        position: relative;
        left: 50%;
        top: 50%;
        width: 150px;
        height: 150px;
        margin: -75px 0 0 -75px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #3498db;
    
        -webkit-animation: spin 2s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
        animation: spin 2s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
    
        z-index: 1001;
    }

    #loader:before {
        content: "";
        position: absolute;
        top: 5px;
        left: 5px;
        right: 5px;
        bottom: 5px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #e74c3c;

        -webkit-animation: spin 3s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
        animation: spin 3s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
    }

    #loader:after {
        content: "";
        position: absolute;
        top: 15px;
        left: 15px;
        right: 15px;
        bottom: 15px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #f9c922;

        -webkit-animation: spin 1s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
          animation: spin 1s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
    }

    @-webkit-keyframes spin {
        0%   { 
            -webkit-transform: rotate(0deg);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: rotate(0deg);  /* IE 9 */
            transform: rotate(0deg);  /* Firefox 16+, IE 10+, Opera */
        }
        100% {
            -webkit-transform: rotate(360deg);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: rotate(360deg);  /* IE 9 */
            transform: rotate(360deg);  /* Firefox 16+, IE 10+, Opera */
        }
    }
    @keyframes spin {
        0%   { 
            -webkit-transform: rotate(0deg);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: rotate(0deg);  /* IE 9 */
            transform: rotate(0deg);  /* Firefox 16+, IE 10+, Opera */
        }
        100% {
            -webkit-transform: rotate(360deg);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: rotate(360deg);  /* IE 9 */
            transform: rotate(360deg);  /* Firefox 16+, IE 10+, Opera */
        }
    }

    #loader-wrapper .loader-section {
        position: fixed;
        top: 0;
        width: 51%;
        height: 100%;
        background: #222222;
        z-index: 1000;
        -webkit-transform: translateX(0);  /* Chrome, Opera 15+, Safari 3.1+ */
        -ms-transform: translateX(0);  /* IE 9 */
        transform: translateX(0);  /* Firefox 16+, IE 10+, Opera */
    }

    #loader-wrapper .loader-section.section-left {
        left: 0;
    }

    #loader-wrapper .loader-section.section-right {
        right: 0;
    }

    /* Loaded */
    .loaded #loader-wrapper .loader-section.section-left {
        -webkit-transform: translateX(-100%);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: translateX(-100%);  /* IE 9 */
                transform: translateX(-100%);  /* Firefox 16+, IE 10+, Opera */

        -webkit-transition: all 0.2s 0.1s cubic-bezier(0.645, 0.045, 0.355, 1.000);  
                transition: all 0.2s 0.1s cubic-bezier(0.645, 0.045, 0.355, 1.000);
    }

    .loaded #loader-wrapper .loader-section.section-right {
        -webkit-transform: translateX(100%);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: translateX(100%);  /* IE 9 */
                transform: translateX(100%);  /* Firefox 16+, IE 10+, Opera */

        -webkit-transition: all 0.2s 0.1s cubic-bezier(0.645, 0.045, 0.355, 1.000);  
                transition: all 0.2s 0.1s cubic-bezier(0.645, 0.045, 0.355, 1.000);
    }
    
    .loaded #loader {
        opacity: 0;
        -webkit-transition: all 0.1s ease-out;  
                transition: all 0.1s ease-out;
    }
    .loaded #loader-wrapper {
        visibility: hidden;

        -webkit-transform: translateY(-100%);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: translateY(-100%);  /* IE 9 */
                transform: translateY(-100%);  /* Firefox 16+, IE 10+, Opera */

        -webkit-transition: all 0.1s 0.3s ease-out;  
                transition: all 0.1s 0.3s ease-out;
    }
    
    /* JavaScript Turned Off */
    .no-js #loader-wrapper {
        display: none;
    }
    .no-js h1 {
        color: #222222;
    }
  </style>
	
	<!--<style> 
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
    </style>-->
    
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
    
    <div id="loader-wrapper">
			<div id="loader"></div>

			<div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>

		</div>
	
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
			
				
					<img src="<?php echo (($fetch_msmset['logo'] == "" || $fetch_msmset['logo'] == "img/") && $fetch_msmset['cname'] == '') ?  $row['file_baseurl'].$row['image'] : $row['file_baseurl'].$fetch_msmset['logo']; ?>" alt="User Image" width="100" height="100">
   				<a href="#"><h3 style="color: <?php ($fetch_msmset['theme_color'] == '') ? '#38A1F3' : $fetch_msmset['theme_color']; ?>; font-family: Century Gothic;"><strong><?php echo ($fetch_msmset['cname'] == "") ? $row['name'] : $fetch_msmset['cname']; ?></strong></h3></a>
				
  			</div>
					</span>
					
				<div class="input-group">
					<div class="wrap-input100">
						<input class="input100" type="text" class="form-control" name="username" required>
						<span class="focus-input100"></span>
						<span class="label-input100"><i class="fa fa-user"></i>&nbsp;Username</span>
					</div>
					
					<div class="input-group-btn">
          				<button name="submit" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-black" style="background-color: black;"><i class="fa fa-send text-muted"></i></button>
        			</div>
        		</div>

        		<div class="help-block text-center">
    				<b style="color:black;">ENTER YOUR USERNAME TO CHANGE YOUR PASSWORD</b>
  				</div>
  				<div class="text-center">
    				<a href="<?php echo (isset($_GET['id'])) ? '/'.$_GET['id'] : '/'; ?>" class="btn bg-<?php ($fetch_msmset['theme_color'] == "") ? '#38A1F3' : $fetch_msmset['theme_color']; ?>" style="background-color: black; color: white; font-size: 16px">Or sign in as different user</a>
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
    $mysender_id = (!isset($_GET['id'])) ? "" : $_GET['id'];
    // check for valid username
    $username = $_POST['username'];

    $search_mmemberset = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$mysender_id'");
    $fetch_mmemberset = mysqli_fetch_array($search_mmemberset);
    $institutionid = ($fetch_mmemberset['companyid'] == "") ? "" : $fetch_mmemberset['companyid'];
    $otp_option = $fetch_mmemberset['otp_option'];

    // checks if the username is in use
    $check = mysqli_query($link, "SELECT * FROM user WHERE username = '$username' AND created_by = '$institutionid'")or die(mysqli_error($link));
    $fetch_user = mysqli_fetch_object($check);
    $snum = mysqli_num_rows($check);

    $check1 = mysqli_query($link, "SELECT * FROM borrowers WHERE username = '$username' AND branchid = '$institutionid'")or die(mysqli_error($link));
    $fetch_customer = mysqli_fetch_object($check1);
    $cnum = mysqli_num_rows($check1);

    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $sysabb = ($mysender_id == "") ? $r->abb : $mysender_id;
    $wallet_date_time = date("Y-m-d H:i:s");

    $search_emailConfig = mysqli_query($link, "SELECT * FROM email_config WHERE companyid = '$institutionid'");
    $fetch_emailConfig = mysqli_fetch_array($search_emailConfig);
    $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated

    $otpcode = substr((uniqid(rand(),1)),3,6);

    if($snum == 0 && $cnum == 0){

        echo "<div class='alert alert-danger'>Oops!..Username does not exist</div>";

    }
    elseif($snum == 1 && $cnum == 0)
    {

        $name = $fetch_user->name;
        $username = $fetch_user->username;
        $email = $fetch_user->email;
        $pass = base64_encode($otpcode); //encrypted version for database entry
        $ip_address = $_SERVER['REMOTE_ADDR'];
        
        $sendSMS->passwordReset($email, $name, $username, $otpcode, $ip_address, $emailConfigStatus, $fetch_emailConfig);
        
        mysqli_query($link, "UPDATE user SET password = '$pass' WHERE username = '$username' AND created_by = '$institutionid'");

        echo ($institutionid != "") ? '<meta http-equiv="refresh" content="2;url=/'.$mysender_id.'">' : '';
        echo ($institutionid == "") ? '<meta http-equiv="refresh" content="2;url=account/index">' : '';
        echo '<hr>';
        echo "<div class='alert alert-success'>Password Reset Successfully. Please Check your Email to Login</div>";

    }
    elseif($cnum == 1 && $snum == 0)
    {

        $lname = $fetch_customer->lname;
        $username = $fetch_customer->username;
        $email = $fetch_customer->email;
        $ip_address = $_SERVER['REMOTE_ADDR'];
        
        $sendSMS->passwordReset($email, $lname, $username, $otpcode, $ip_address, $emailConfigStatus, $fetch_emailConfig);
        
        $update = mysqli_query($link, "UPDATE borrowers SET password = '$otpcode' WHERE username = '$username' AND branchid = '$institutionid'") or die (mysqli_error($link));
        
        echo ($institutionid != "") ? '<meta http-equiv="refresh" content="2;url=/'.$sysabb.'">' : '';
        echo ($institutionid == "") ? '<meta http-equiv="refresh" content="2;url=account/index">' : '';
        echo '<hr>';
        echo "<div class='alert alert-success'>Password Reset Successfully. Please Check your Email to Login</div>";

    }
}
?>
				</form>

				<?php 
				 		$result= mysqli_query($link,"select * from systemset")or die(mysqli_error());
				 		while($row=mysqli_fetch_array($result))
				 		{
					?>
				<div class="login100-more" style="background-image: url('<?php echo ($fetch_msmset['frontpg_backgrd'] == "") ? $row['file_baseurl'].$row['lbackg'] : $row['file_baseurl'].$fetch_msmset['frontpg_backgrd']; ?>');" align="center">
					<br><br><br><br>
					
   					<i> <?php //echo $row ['map'];?> </i>
   					
				</div>
				<?php }?>
			</div>
		</div>
	</div>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="vendor/jquery/jquery-1.9.1.min.js"><\/script>')</script>
<script src="vendor/jquery/main.js"></script>

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