<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				
				<form class="login100-form validate-form" method="post" enctype="multipart/form-data">
					<span class="login100-form-title" style="color:blue;">
						<div class="login-logo">
			<?php 
			$call555 = mysqli_query($link, "SELECT * FROM systemset");
			$row555 = mysqli_fetch_array($call555);
			?>
   				<img src="<?php echo $row555['file_baseurl'].$row555['image'] ;?>" alt="User Image" width="100" height="100">
   				<a href="index"><h3 style="color: #38A1F3; font-family: Century Gothic;"><strong><?php echo $row555['name'];?></strong></h3></a>
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
          				<a href="../signup.php<?php echo (isset($_GET['rf'])) ? '?rf='.$_GET['rf'] : ''; ?>"><button type="button" class="btn bg-orange btn-flat" style="font-size: 16px"><i class="fa fa-plus"></i>&nbsp; <b>Sign Up</b></button></a> &nbsp; &nbsp; &nbsp; &nbsp;
		 	 			<button name="login" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-blue btn-flat" style="font-size: 16px"><i class="fa fa-send"></i>&nbsp;<b>Sign In</b></button>
        			</div>
        			<!--<br>
        			<div align="center"><a href="forgetpassword.php"><b>Forgot Password?</b></a></div>-->
        			<hr>
					
					<div class="text-center">
    					<i> <?php echo $row555['footer'];?> </i>
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
	$otpCode = substr((uniqid(rand(),1)),3,6);
			
	$query = mysqli_query($link, "SELECT * FROM user WHERE '$username' IN(email, username) AND password = '$encrypt' AND created_by = ''") or die(mysqli_error($link));
	$numberOfRows = mysqli_num_rows($query);	
	$row = mysqli_fetch_array($query);
	$detect_branch = $row['branchid'];
	$staff = $row['role'];
	$userStatus = $row['comment'];
	$email = $row['email'];

	$query2 = mysqli_query($link, "SELECT * FROM borrowers WHERE username = '$username' AND password = '$pass'") or die ("Error: " . mysqli_error($link));
	$nor = mysqli_num_rows($query2);
	$row2 = mysqli_fetch_array($query2);
	$bcompanyid = $row2['branchid'];
	$acct_status = $row2['acct_status'];
	$upgradestatus = $row2['status'];
	$bemail = $row2['email'];
	
	$query5 = mysqli_query($link, "SELECT * FROM aggregator WHERE username = '$username' AND password = '$encrypt'") or die ("Error: " . mysqli_error($link));
    $nor5 = mysqli_num_rows($query5);
    $row5 = mysqli_fetch_array($query5);
    $agcompanyid = $row5['merchantid'];
	$agemail = $row5['email'];
    
    $query6 = mysqli_query($link, "SELECT * FROM user WHERE username = '$username' AND password = '$encrypt' AND role = 'aggregator' AND created_by = '$agcompanyid'") or die ("Error: " . mysqli_error($link));
    $row6 = mysqli_fetch_array($query6);
    $nor6 = mysqli_num_rows($query6);
    $aggrStatus = $row6['comment'];
    $aguserid = $row6['id'];

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

	$memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bcompanyid'");
    $fetchMemset = mysqli_fetch_array($memset);
    $sid = $fetchMemset['sender_id'];
	$allow_login_otp = $fetchMemset['allow_login_otp'];

	$search_emailConfig = mysqli_query($link, "SELECT * FROM email_config WHERE companyid = '$bcompanyid'");
    $fetch_emailConfig = mysqli_fetch_array($search_emailConfig);
    $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
	
	if($numberOfRows == 0 && $nor == 0 && $nor5 == 0)
	{
		echo '<hr>';
		echo '<div class="alert alert-danger">Invalid Login Details</div>';
		echo '<hr>';
	} 
	elseif(($numberOfRows == 1 || $nor == 1 || $nor5 == 1) && ($acct_status == "Not-Activated" || $userStatus == "Not-Activated" || $aggrStatus == "Not-Activated"))
	{
		echo '<hr>';
		echo '<div class="alert alert-danger">Sorry, Your account has not been activated! Kindly login to your email to activate it!!</div>';
		echo '<hr>';
	}
	elseif(($numberOfRows == 1 || $nor == 1 || $nor5 == 1) && ($acct_status == "Deactivated" || $userStatus == "Deactivated" || $aggrStatus == "Deactivated"))
	{
		echo '<hr>';
		echo '<div class="alert alert-danger">Opps!, Your account has been Deactivated! Contact our support for further inquiry!!</div>';
		echo '<hr>';
	}
	elseif($numberOfRows == 1 && $nor == 0 && $nor5 == 0 && $userStatus == "Pending")
	{
		echo '<hr>';
		echo '<div class="alert alert-danger">Opps! Your account still under review. Contact our support for further inquiry!!</div>';
		echo '<hr>';
	}
	elseif($numberOfRows == 0 && $nor == 1 && $nor5 == 0 && $acct_status == "Locked")
	{
		echo '<hr>';
		echo '<div class="alert alert-danger">Sorry, Your account has been locked! Kindly contact the nearest Agent/Account Officer for further inquiry!!</div>';
		echo '<hr>';
	}
	elseif(($numberOfRows == 1 || $nor == 1 || $nor5 == 1) && ($upgradestatus == "Suspended" || $userStatus == "Suspended" || $aggrStatus == "Suspended"))
	{
		echo '<hr>';
		echo '<div class="alert alert-danger">Sorry, Your account has been Suspended! Contact our support for further inquiry!!</div>';
		echo '<hr>';
	}
	elseif($numberOfRows == 0 && $nor == 1 && $nor5 == 0 && $acct_status == "Activated")
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
		($sessionNum1 == 0) ? mysqli_query($link, "INSERT INTO session_tracker VALUES(null,'$bcompanyid','$buserid','$username','$myip','$latitude','$longitude','$mycookies','$yourbrowser','On','$date_time','$date_time')") : "";
		($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'customer','$otpCode','$mydata','Pending','$date_time')") : "";

		echo ((($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? "<script>window.location='../loader2.php?tid=".$_SESSION['tid']."&&brch=".$_SESSION['bbranchid']."&&acn=".$_SESSION['acctno']."';</script>" : (($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? "<script>window.location='../authorizer.php?id=".$sid."';</script>" : ""));
	
	}
	elseif($numberOfRows == 1 && $nor == 0 && $nor5 == 0 && $userStatus == "Approved")
	{
		
		echo '<hr>';
    	echo ((($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No" || $allow_login_otp == "") ? '<div class="alert alert-success">You have Successfully Login</div>' : (($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? '<div class="alert alert-info">Oops! Kindly check your email for the OTP to authorize the login access!!</div>' : '<div class="alert alert-danger">Unathorized Login Attempt</div>'));
    	$_SESSION['tid'] = $row['id'];

		$mydata = $row['id']."|".$username;
		
		($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $sendSMS->loginOtpNotifier($email, $pageHeader, $newLocation, $myip, $browserName, $deviceName, $otpCode, $emailConfigStatus, $fetch_emailConfig) : "";
		(($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No" || $allow_login_otp == "") ? setcookie("PHPSESSID", "", time()-3600) : "";
		(($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No" || $allow_login_otp == "") ? setcookie("PHPSESSID", $browserid, time()+30*24*60*60) : "";
		(($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No" || $allow_login_otp == "") ? $mycookies = $_COOKIE['PHPSESSID'] : "";
		
		($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? mysqli_query($link, "UPDATE session_tracker SET loginStatus = 'Off' WHERE username = '$username'") : "";
        ($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser" && $allow_login_otp == "Yes") ? mysqli_query($link, "UPDATE session_tracker SET ipAddress = '$myip', latitude = '$latitude', longitude = '$longitude', browserSession = '$mycookies', mybrowser = '$yourbrowser', loginStatus = 'On', LastVisitDateTime = '$date_time' WHERE username = '$username'") : "";
        ($sessionNum1 == 1 && ($sessionStatus == "Off" || $sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && ($allow_login_otp == "No" || $allow_login_otp == "")) ? mysqli_query($link, "UPDATE session_tracker SET ipAddress = '$myip', latitude = '$latitude', longitude = '$longitude', browserSession = '$mycookies', mybrowser = '$yourbrowser', loginStatus = 'On', LastVisitDateTime = '$date_time' WHERE username = '$username'") : "";
		($sessionNum1 == 0) ? mysqli_query($link, "INSERT INTO session_tracker VALUES(null,'','$userid','$username','$myip','$latitude','$longitude','$mycookies','$yourbrowser','On','$date_time','$date_time')") : "";
		($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'esusuafrica','$otpCode','$mydata','Pending','$date_time')") : "";

		echo ((($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No" || $allow_login_otp == "") ? "<script>window.location='../loader.php?tid=".$_SESSION['tid']."';</script>" : (($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? "<script>window.location='../authorizer.php';</script>" : ""));
	
	}
	elseif($numberOfRows == 0 && $nor == 0 && $nor5 == 1 && $aggrStatus == "Approved")
	{

		echo '<hr>';
    	echo ((($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? '<div class="alert alert-success">You have Successfully Login</div>' : (($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? '<div class="alert alert-info">Oops! Kindly check your email for the OTP to authorize the login access!!</div>' : '<div class="alert alert-danger">Unathorized Login Attempt</div>'));
    	$_SESSION['tid'] = $row5['aggr_id'];

        $mydata = $row5['aggr_id']."|".$username;
        
		($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $sendSMS->loginOtpNotifier($agemail, $pageHeader, $newLocation, $myip, $browserName, $deviceName, $otpCode, $emailConfigStatus, $fetch_emailConfig) : "";
        (($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", "", time()-3600) : "";
        (($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", $browserid, time()+30*24*60*60) : "";
        (($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? $mycookies = $_COOKIE['PHPSESSID'] : "";
        
		($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? mysqli_query($link, "UPDATE session_tracker SET loginStatus = 'Off' WHERE username = '$username'") : "";
        ($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser" && $allow_login_otp == "Yes") ? mysqli_query($link, "UPDATE session_tracker SET ipAddress = '$myip', latitude = '$latitude', longitude = '$longitude', browserSession = '$mycookies', mybrowser = '$yourbrowser', loginStatus = 'On', LastVisitDateTime = '$date_time' WHERE username = '$username'") : "";
        ($sessionNum1 == 1 && ($sessionStatus == "Off" || $sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && ($allow_login_otp == "No" || $allow_login_otp == "")) ? mysqli_query($link, "UPDATE session_tracker SET ipAddress = '$myip', latitude = '$latitude', longitude = '$longitude', browserSession = '$mycookies', mybrowser = '$yourbrowser', loginStatus = 'On', LastVisitDateTime = '$date_time' WHERE username = '$username'") : "";
		($sessionNum1 == 0) ? mysqli_query($link, "INSERT INTO session_tracker VALUES(null,'$agcompanyid','$aguserid','$username','$myip','$latitude','$longitude','$mycookies','$yourbrowser','On','$date_time','$date_time')") : "";
		($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'aggregator','$otpCode','$mydata','Pending','$date_time')") : "";

		echo ((($sessionNum1 == 1 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? "<script>window.location='../aggloader.php?tid=".$_SESSION['tid']."';</script>" : (($sessionNum1 == 1 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? "<script>window.location='../authorizer.php';</script>" : ""));

	}

}
?>

				</form>

				<div class="login100-more" style="background-image: url('<?php echo $row555['file_baseurl'].$row555['lbackg']; ?>')" align="left">
					<br><br><br><br>
				</div>

			</div>
		</div>
	</div>