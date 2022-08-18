<?php
$senderid = $_GET['id'];
$myverify_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$senderid'");
$myfetch_memset = mysqli_fetch_object($myverify_memset);

$mysystem_config = mysqli_query($link, "SELECT * FROM systemset");
$fetchsys_config = mysqli_fetch_array($mysystem_config);

if($fetchsys_config['maintenance_mode'] == "OFF")
{
?>
<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" method="post" enctype="multipart/form-data" style="background-color: <?php echo ($fetch_company->login_background == "") ? 'White' : $fetch_company->login_background; ?>">
					<span class="login100-form-title" style="color:blue;">
						<div class="login-logo">
				<?php 
			$call = mysqli_query($link, "SELECT * FROM systemset");
			$roww = mysqli_fetch_assoc($call);
			?>
   				<img src="<?php echo ($fetch_company->logo == "" || $fetch_company->logo == "img/") ?  $roww['file_baseurl'].$roww['image'] : $roww['file_baseurl'].$fetch_company->logo; ?>" alt="User Image" width="100" height="100">
   				<a href="#"><h3 style="color: <?php ($fetch_company->theme_color == '') ? '#38A1F3' : $fetch_company->theme_color; ?>; font-family: Century Gothic;"><strong><?php echo ($fetch_company->cname == "") ? $roww['name'] : $fetch_company->cname; ?></strong></h3></a>

  						</div>
					</span>
					
					<div class="wrap-input100">
						<input class="input100" type="text" class="form-control" name="username" required>
						<span class="focus-input100"></span>
						<span class="label-input100"><i class="fa fa-user"></i>&nbsp;Username</span>
					</div>
					
					
					<div class="wrap-input100 validate-input" data-validate="Password is required">
						<input  name="pass" type="password" class="form-control" placeholder="Password" id="password-field" required>
						<span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
					</div>

					
					<div align="center">
		 	 			<button name="login" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-<?php ($fetch_company->theme_color == "") ? '#38A1F3' : $fetch_company->theme_color; ?> btn-flat" style="background-color: black; color: white; font-size: 16px"><i class="fa fa-send"></i>&nbsp;<b>Sign In</b></button> 
        			</div>
        			<br>
        			<div align="center"><a href="signup.php?id=<?php echo $_GET['id']; ?>"><b style="color: black;">Register</b></a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="forgetpassword.php?id=<?php echo $_GET['id']; ?>"><b style="color: black;">Forgot Password?</b></a></div>
        			<hr>
					
					<div class="text-center">
						<strong><?php echo ($myfetch_memset->copyright == "") ? "Copyright ".date("Y").". Powered by Esusu Africa" : $myfetch_memset->copyright; ?></strong>
					</div>
<?php
if(isset($_POST['login']))
{	
	$myip = getUserIP();
	$dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$myip), true);
	$latitude = $dataArray["geoplugin_latitude"];
	$longitude = $dataArray["geoplugin_longitude"];
	$newLocation = $dataArray["geoplugin_city"].", ".$dataArray["geoplugin_countryName"].".";
	$date_time = date("Y-m-d h:i:s");

	$username= mysqli_real_escape_string($link, $_POST['username']);
	$pass= mysqli_real_escape_string($link, $_POST['pass']);
	$encrypt = base64_encode($pass);
	$sid = $_GET['id'];
	$otpCode = substr((uniqid(rand(),1)),3,6);

	$verify_id = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$sid'");
	$fetch_id = mysqli_fetch_object($verify_id);
	$companyid = $fetch_id->companyid;
	$allow_login_otp = $fetch_id->allow_login_otp;

	$search_emailConfig = mysqli_query($link, "SELECT * FROM email_config WHERE companyid = '$companyid'");
    $fetch_emailConfig = mysqli_fetch_array($search_emailConfig);
    $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated

	$query2 = mysqli_query($link, "SELECT * FROM borrowers WHERE username = '$username' AND password = '$pass' AND branchid = '$companyid'") or die ("Error: " . mysqli_error($link));
	$row2 = mysqli_fetch_array($query2);
	$nor = mysqli_num_rows($query2);
	$buserid = $row2['id'];
	$acct_status = $row2['acct_status'];
	$upgradestatus = $row2['status'];
	$bemail = $row2['email'];

	$query3 = mysqli_query($link, "SELECT * FROM coop_members WHERE email = '$username' AND password = '$pass'");
	$row3 = mysqli_fetch_array($query3);
	$detect_coop = mysqli_num_rows($query3);

	$query7 = mysqli_query($link, "SELECT * FROM user WHERE username = '$username' AND password = '$encrypt' AND created_by = '$companyid' AND bprefix = 'REV'") or die ("Error: " . mysqli_error($link));
	$row7 = mysqli_fetch_array($query7);
	$detect_revenue = mysqli_num_rows($query7);
	$rev_status = $row7['comment'];
	$rev_Upgstatus = $row7['status'];
	$remail = $row7['email'];

	$query8 = mysqli_query($link, "SELECT * FROM user WHERE username = '$username' AND password = '$encrypt' AND created_by = '$companyid' AND bprefix = 'VEN'") or die ("Error: " . mysqli_error($link));
	$row8 = mysqli_fetch_array($query8);
	$detect_vendor = mysqli_num_rows($query8);
	$vuserid = $row8['id'];
	$ven_status = $row8['comment'];
	$ven_Upgstatus = $row8['status'];
	$vemail = $row8['email'];

	//ALL INSTITUTION LOGIN ACCESS
	$query4 = mysqli_query($link, "SELECT * FROM user WHERE username = '$username' AND password = '$encrypt' AND created_by = '$companyid' AND (bprefix = 'AG' OR bprefix = 'INS' OR bprefix = 'MER')") or die(mysqli_error($link));
	$row4 = mysqli_fetch_array($query4);
	$detect_inst = mysqli_num_rows($query4);
	$iuserid = $row4['id'];
	$inst_status = $row4['comment'];
	$inst_Upgstatus = $row4['status'];
	$iemail = $row4['email'];

	$ua = getBrowser();
	$yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
	$browserName = $ua['name'] . " " . $ua['version'];
	$deviceName = $ua['platform'];

	$search_loginCookies = mysqli_query($link, "SELECT * FROM session_tracker WHERE username = '$username' AND mybrowser = '$yourbrowser' AND loginStatus = 'On'") or die ("Error: " . mysqli_error($link));
    $cookiesNum = mysqli_num_rows($search_loginCookies);

	$browserid = md5($yourbrowser.$username.$longitude.$latitude.uniqid());
	$search_loginSession1 = mysqli_query($link, "SELECT * FROM session_tracker WHERE username = '$username'") or die ("Error: " . mysqli_error($link));
	$search_querySession1 = mysqli_fetch_array($search_loginSession1);
	$sessionNum1 = mysqli_num_rows($search_loginSession1);
	$sessionStatus = $search_querySession1['loginStatus'];
	$previousIpAddress = $search_querySession1['ipAddress'];
	$previousLatitide = $search_querySession1['latitude'];
	$previousLongitude = $search_querySession1['longitude'];
	$previousBroswer = $search_querySession1['mybrowser'];
	$pageHeader = "Authorize New Device";
	
	if($detect_vendor == 0 && $detect_coop == 0 && $detect_inst == 0 && $nor == 0 && $detect_revenue == 0)
	{
		echo '<hr>';
		echo '<div class="alert alert-danger">Invalid Login Details </div>';
		echo '<hr>';
	}
	elseif($inst_status == "Disapproved" || $ven_status == "Disapproved" || $rev_status == "Disapproved")
	{
		echo '<hr>';
		echo '<div class="alert alert-danger">Sorry, Your account have been disabled. Please contact us for further details.</div>';
		echo '<hr>';
	}
	elseif($inst_status == "Not-Activated" || $ven_status == "Not-Activated" || $rev_status == "Not-Activated")
	{
		echo '<hr>';
		echo '<div class="alert alert-danger">Sorry, Your account has not been activated! Kindly login to your email to activate it!!</div>';
		echo '<hr>';
	}
	elseif($inst_Upgstatus == "Suspended" || $ven_Upgstatus == "Suspended" || $rev_Upgstatus == "Suspended")
	{
		echo '<hr>';
		echo '<div class="alert alert-danger">Sorry, Your account has been Suspended! Kindly contact our support for more details!!</div>';
		echo '<hr>';
	}
	elseif($detect_coop == 0 && $detect_inst == 0 && $nor == 1 && $acct_status == "Not-Activated")
	{
		echo '<hr>';
		echo '<div class="alert alert-danger">Sorry, Your account has not been activated! Kindly login to your email to activate it!!</div>';
		echo '<hr>';
	}
	elseif($detect_coop == 0 && $detect_inst == 0 && $nor == 1 && $acct_status == "Locked")
	{
		echo '<hr>';
		echo '<div class="alert alert-danger">Sorry, Your account has been locked! Kindly contact the nearest Agent/Account Officer for any request on your account!!</div>';
		echo '<hr>';
	}
	elseif($detect_coop == 0 && $detect_inst == 0 && $nor == 1 && $upgradestatus == "Suspended")
	{
		echo '<hr>';
		echo '<div class="alert alert-danger">Sorry, Your account has been Suspended! Kindly contact our support for more details!!</div>';
		echo '<hr>';
	}
	elseif($detect_vendor == 0 && $detect_coop == 0 && $detect_inst == 0 && $nor == 1 && $acct_status == "Activated")
	{

	    echo '<hr>';
		echo ((($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? '<div class="alert alert-success">You have Successfully Login</div>' : (($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? '<div class="alert alert-info">Oops! Kindly check your email for the OTP to authorize the login access!!</div>' : '<div class="alert alert-danger">Unathorized Login Attempt</div>'));
		$_SESSION['tid'] = $row2['id'];
		$_SESSION['acctno'] = $row2['account'];
		$_SESSION['bbranchid'] = $row2['branchid'];

		$mydata = $row2['id']."|".$row2['account']."|".$row2['branchid']."|".$username."|".$sid;
		
		($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $sendSMS->loginOtpNotifier($bemail, $pageHeader, $newLocation, $myip, $browserName, $deviceName, $otpCode, $emailConfigStatus, $fetch_emailConfig) : "";
		(($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", "", time()-3600) : "";
		(($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", $browserid, time()+30*24*60*60) : "";
		(($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? $mycookies = $_COOKIE['PHPSESSID'] : "";
		
		($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? mysqli_query($link, "UPDATE session_tracker SET loginStatus = 'Off' WHERE username = '$username'") : "";
        ($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser" && $allow_login_otp == "Yes") ? mysqli_query($link, "UPDATE session_tracker SET ipAddress = '$myip', latitude = '$latitude', longitude = '$longitude', browserSession = '$mycookies', mybrowser = '$yourbrowser', loginStatus = 'On', LastVisitDateTime = '$date_time' WHERE username = '$username'") : "";
        ($sessionNum1 == 1 && ($sessionStatus == "Off" || $sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && ($allow_login_otp == "No" || $allow_login_otp == "")) ? mysqli_query($link, "UPDATE session_tracker SET ipAddress = '$myip', latitude = '$latitude', longitude = '$longitude', browserSession = '$mycookies', mybrowser = '$yourbrowser', loginStatus = 'On', LastVisitDateTime = '$date_time' WHERE username = '$username'") : "";
		($sessionNum1 == 0) ? mysqli_query($link, "INSERT INTO session_tracker VALUES(null,'$companyid','$buserid','$username','$myip','$latitude','$longitude','$mycookies','$yourbrowser','On','$date_time','$date_time')") : "";
		($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'customer','$otpCode','$mydata','Pending','$date_time')") : "";

		echo ((($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? "<script>window.location='loader2.php?tid=".$_SESSION['tid']."&&brch=".$_SESSION['bbranchid']."&&acn=".$_SESSION['acctno']."';</script>" : (($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? "<script>window.location='authorizer.php?id=".$sid."';</script>" : ""));
	
	}
	/*elseif($detect_revenue == 1 && $detect_vendor == 0 && $detect_coop == 0 && $detect_inst == 0)
	{
		//$sql = mysqli_query($link,"UPDATE user SET Signal='On' WHERE Email = '$email'") or die(mysqli_error($link));
		echo '<hr>';
		echo '<div class="alert alert-success">You have Successfully Logged in</div>';
		$_SESSION['tid'] = $row7['created_by'];
		$_SESSION['aun'] = $row7['username'];
		echo "<script>window.location='rloader.php?tid=".$_SESSION['tid']."&&un=".$_SESSION['aun']."';</script>";
	}*/
	elseif($detect_vendor == 1 && $detect_coop == 0 && $detect_inst == 0)
	{
		
		echo '<hr>';
		echo ((($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? '<div class="alert alert-success">You have Successfully Login</div>' : (($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? '<div class="alert alert-info">Oops! Kindly check your email for the OTP to authorize the login access!!</div>' : '<div class="alert alert-danger">Unathorized Login Attempt</div>'));
		$_SESSION['tid'] = $row8['id'];
		$_SESSION['vendorid'] = $row8['branchid'];
		$_SESSION['merchantid'] = $row8['created_by'];
		$_SESSION['vstaff'] = $row8['username'];

		$mydata = $row8['id']."|".$row8['branchid']."|".$row8['created_by']."|".$row8['username']."|".$username."|".$sid;
		
		($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $sendSMS->loginOtpNotifier($vemail, $pageHeader, $newLocation, $myip, $browserName, $deviceName, $otpCode, $emailConfigStatus, $fetch_emailConfig) : "";
		(($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", "", time()-3600) : "";
		(($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", $browserid, time()+30*24*60*60) : "";
		(($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? $mycookies = $_COOKIE['PHPSESSID'] : "";
		
		($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? mysqli_query($link, "UPDATE session_tracker SET loginStatus = 'Off' WHERE username = '$username'") : "";
        ($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser" && $allow_login_otp == "Yes") ? mysqli_query($link, "UPDATE session_tracker SET ipAddress = '$myip', latitude = '$latitude', longitude = '$longitude', browserSession = '$mycookies', mybrowser = '$yourbrowser', loginStatus = 'On', LastVisitDateTime = '$date_time' WHERE username = '$username'") : "";
        ($sessionNum1 == 1 && ($sessionStatus == "Off" || $sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && ($allow_login_otp == "No" || $allow_login_otp == "")) ? mysqli_query($link, "UPDATE session_tracker SET ipAddress = '$myip', latitude = '$latitude', longitude = '$longitude', browserSession = '$mycookies', mybrowser = '$yourbrowser', loginStatus = 'On', LastVisitDateTime = '$date_time' WHERE username = '$username'") : "";
		($sessionNum1 == 0) ? mysqli_query($link, "INSERT INTO session_tracker VALUES(null,'$companyid','$vuserid','$username','$myip','$latitude','$longitude','$browserid','$yourbrowser','On','$date_time','$date_time')") : "";
		($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'vendor','$otpCode','$mydata','Pending','$date_time')") : "";

		echo ((($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? "<script>window.location='loader5.php?tid=".$_SESSION['tid']."&&vend_id=".$_SESSION['vendorid']."&&merch_id=".$_SESSION['merchantid']."&&vstaff=".$_SESSION['vstaff']."';</script>" : (($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? "<script>window.location='authorizer.php?id=".$sid."';</script>" : ""));

	}
	/*elseif($detect_coop == 1 && $detect_inst == 0)
	{
		echo '<hr>';
		echo '<div class="alert alert-success">You have Successfully Logged in</div>';
		$_SESSION['tid'] = $row3['id'];
		$_SESSION['coopid'] = $row3['coopid'];
		echo "<script>window.location='loader3.php?id=".$_SESSION['tid']."&&coopid=".$_SESSION['coopid']."';</script>";		
	}*/
	elseif($detect_coop == 0 && $detect_inst == 1)
	{

		echo '<hr>';
		echo ((($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? '<div class="alert alert-success">You have Successfully Login</div>' : (($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? '<div class="alert alert-info">Oops! Kindly check your email for the OTP to authorize the login access!!</div>' : '<div class="alert alert-danger">Unathorized Login Attempt</div>'));
		$_SESSION['tid'] = $row4['id'];
		$_SESSION['instid'] = $row4['created_by'];
		$_SESSION['istaff'] = $row4['username'];
		
		$mydata = $row4['id']."|".$row4['created_by']."|".$row4['username']."|".$username."|".$sid;
		
		($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $sendSMS->loginOtpNotifier($iemail, $pageHeader, $newLocation, $myip, $browserName, $deviceName, $otpCode, $emailConfigStatus, $fetch_emailConfig) : "";
		(($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", "", time()-3600) : "";
		(($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", $browserid, time()+30*24*60*60) : "";
		(($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? $mycookies = $_COOKIE['PHPSESSID'] : "";
		
		($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? mysqli_query($link, "UPDATE session_tracker SET loginStatus = 'Off' WHERE username = '$username'") : "";
        ($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser" && $allow_login_otp == "Yes") ? mysqli_query($link, "UPDATE session_tracker SET ipAddress = '$myip', latitude = '$latitude', longitude = '$longitude', browserSession = '$mycookies', mybrowser = '$yourbrowser', loginStatus = 'On', LastVisitDateTime = '$date_time' WHERE username = '$username'") : "";
        ($sessionNum1 == 1 && ($sessionStatus == "Off" || $sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && ($allow_login_otp == "No" || $allow_login_otp == "")) ? mysqli_query($link, "UPDATE session_tracker SET ipAddress = '$myip', latitude = '$latitude', longitude = '$longitude', browserSession = '$mycookies', mybrowser = '$yourbrowser', loginStatus = 'On', LastVisitDateTime = '$date_time' WHERE username = '$username'") : "";
		($sessionNum1 == 0) ? mysqli_query($link, "INSERT INTO session_tracker VALUES(null,'$companyid','$iuserid','$username','$myip','$latitude','$longitude','$mycookies','$yourbrowser','On','$date_time','$date_time')") : "";
		($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'institution','$otpCode','$mydata','Pending','$date_time')") : "";
	
		echo ((($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? "<script>window.location='loader1.php?id=".$_SESSION['tid']."&&instid=".$_SESSION['instid']."&&istaff=".$_SESSION['istaff']."';</script>" : (($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? "<script>window.location='authorizer.php?id=".$sid."';</script>" : ""));

	}

}
?>

				</form>

				<div class="login100-more" style="background-image: url('<?php echo ($fetch_company->frontpg_backgrd == "") ? $roww['file_baseurl'].$roww['lbackg'] : $roww['file_baseurl'].$fetch_company->frontpg_backgrd; ?>')" align="left">
					<br><br><br><br>
					
   					<i> <?php //echo $row ['map'];?> </i>
   					
				</div>
			</div>
		</div>
	</div>
	
<?php
}
else{
    echo "<script>window.location='maintenance/index.php?id=".$_GET['id']."'; </script>";
}
?>