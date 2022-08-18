<?php
	error_reporting(0);
	include "config/connect.php";
	require_once "config/smsAlertClass.php";
	
	$custom_id = $_GET['id'];
	$call_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$custom_id'");
	$fetch_msmset = mysqli_fetch_array($call_memset);
?>

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
<img src="<?php echo ($fetch_msmset['logo'] == "" || $fetch_msmset['logo'] == "img/") ? 'img/electronic esusu gif.gif' : $fetch_msmset['logo']; ?>" width="100px;" height="100px;">
<?php

//BASIC INFORMATION
$fname =  mysqli_real_escape_string($link, $_POST['fname']);
$lname = mysqli_real_escape_string($link, $_POST['lname']);
$mname = mysqli_real_escape_string($link, $_POST['mname']);
$full_name = $lname.' '.$fname;
$email = mysqli_real_escape_string($link, $_POST['email']);
$phone = mysqli_real_escape_string($link, $_POST['phone']);
$gender =  mysqli_real_escape_string($link, $_POST['gender']);
$phone = mysqli_real_escape_string($link, $_POST['phone']);
$username = mysqli_real_escape_string($link, $_POST['username']);
$password = mysqli_real_escape_string($link, $_POST['password']);
$encrypt = base64_encode($password);
$dob =  mysqli_real_escape_string($link, $_POST['dob']);
//$dob =  mysqli_real_escape_string($link, $_POST['dob']);
$status = "Completed";
$wallet_date_time = date("Y-m-d h:i:s");

//Corporate Information
$bname = (!isset($_POST['bname'])) ? "" : mysqli_real_escape_string($link, $_POST['bname']);
$account_type = mysqli_real_escape_string($link, $_POST['account_type']);
$myhost = mysqli_real_escape_string($link, $_POST['id']);
$myurl = ($myhost == "") ? '' : $myhost;

$myreferral = (!isset($_POST['myreferral'])) ? "" : mysqli_real_escape_string($link, $_POST['myreferral']);

$search_systemset = mysqli_query($link, "SELECT * FROM systemset");
$fetch_systemset = mysqli_fetch_array($search_systemset);
$wprefund_bal = $fetch_systemset['wallet_prefound_amt'];

$refid = "EA-walletPrefund-".time();

$search_mmemberset = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$myhost'");
$fetch_mmemberset = mysqli_fetch_array($search_mmemberset);
$fetch_memid = ($fetch_mmemberset['companyid'] == "") ? '' : $fetch_mmemberset['companyid'];
$otp_option = $fetch_mmemberset['otp_option'];
$dedicated_ussd_prefix = $fetch_mmemberset['dedicated_ussd_prefix'];


if(isset($_POST['aggregister']))
{

	$verify_email = mysqli_query($link, "SELECT * FROM aggregator WHERE email = '$email'");
	$detect_email = mysqli_num_rows($verify_email);

	$verify_phone = mysqli_query($link, "SELECT * FROM aggregator WHERE phone = '$phone'");
	$detect_phone = mysqli_num_rows($verify_phone);

	$verify_username = mysqli_query($link, "SELECT * FROM aggregator WHERE username = '$username'");
	$detect_username = mysqli_num_rows($verify_username);
	
	$aggr_id = 'AGGR'.rand(1000,9999);

	if($detect_email == 1){
		echo '<meta http-equiv="refresh" content="5;url=/">';
		echo '<br>';
		echo "<p style='font-size:24px; color:orange;'>Sorry, Email Address has already been used.</p>";
	}
	elseif($detect_phone == 1){
		echo '<meta http-equiv="refresh" content="5;url=/">';
		echo '<br>';
		echo "<p style='font-size:24px; color:orange;'>Sorry, Phone Number has already been used.</p>";
	}
	elseif($detect_username == 1){
		echo '<meta http-equiv="refresh" content="5;url=/">';
		echo '<br>';
		echo "<p style='font-size:24px; color:orange;'>Sorry, Username has already been used.</p>";
	}
	else{
		
		$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
		$r = mysqli_fetch_object($query);
		$sysabb = $r->abb;
		$sys_email = $r->email;
		$aggr_co_type = $r->aggr_co_type;
	    $aggr_co_rate = $r->aggr_co_rate;

		$new_member = mysqli_query($link, "INSERT INTO aggregator VALUES(null,'$aggr_id','','$fname','$lname','$gender','$dob','$email','$phone','$username','$encrypt','0.0','0000','NGN','$aggr_co_type','$aggr_co_rate',NOW())") or die (mysqli_error($link));
			
		$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;
		
		$sms = "$sysabb>>>Welcome $fname! Your Aggregator ID: $aggr_id, Username: $username, Password: $password, Transaction Pin: 0000. Login here: https://esusu.app";
            
        if(!$new_member)
        {
			echo '<meta http-equiv="refresh" content="3;url=/">';
    	    echo '<br>';
    	    echo "<p style='font-size:20px; color:blue;'>Unable to register, Please try again later.</p>";
		}
		else{
			include('cron/send_aggregemail.php');
		    include('cron/send_general_sms.php');
            echo '<meta http-equiv="refresh" content="3;url=/">';
    	    echo '<br>';
    	    echo "<p style='font-size:20px; color:blue;'>Account Created Successfully! Click Ok to login as an Aggregator with your Username and Password.</p>";
		}
	}
}



if(isset($_POST['aregister'])){

	$companyid = ($account_type === "agent") ? "AGT-".date("d").time() : "INST-".date("d").time();
	$acct_cat = mysqli_real_escape_string($link, $_POST['acct_cat']);
	$my_senderid = mysqli_real_escape_string($link, $_POST['my_senderid']);
    $currency_type = mysqli_real_escape_string($link, $_POST['currency_type']);
    $license_no = mysqli_real_escape_string($link, $_POST['license_no']);
    $addrs = mysqli_real_escape_string($link, $_POST['addrs']);
    $global_role = ($account_type === "agent" ? "agent_manager" : ($account_type === "agent1" ? "agency_banker" : "institution_super_admin"));
    $prefix = ($account_type === "agent") ? "AG" : "INS";
    //$referral = mysqli_real_escape_string($link, $_POST['referral']);
    
    $target_dir = "img/";
	$target_file = $target_dir.basename($_FILES["image"]["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $check = getimagesize($_FILES["image"]["tmp_name"]);

	$sourcepath = $_FILES["image"]["tmp_name"];
	$targetpath = "img/" . $_FILES["image"]["name"];
	move_uploaded_file($sourcepath,$targetpath);
			
	$location = $_FILES['image']['name'];
	
	$verify_company = mysqli_query($link, "SELECT * FROM user WHERE email = '$email' OR username = '$username' OR phone = '$phone'");
    $detect_company = mysqli_num_rows($verify_company);
	$datetime = date("Y-m-d h:i:s");

	$posPIN = substr((uniqid(rand(),1)),3,6);

	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));		
	$r = mysqli_fetch_object($query);
	$sysabb = $r->abb;
	$sys_email = $r->email;
	$sms_rate = $r->fax;
	//$defaultIdVerificationCharges = $r->defaultIdVerificationCharges;

    $lastledgerAcctNo_prefix = "00";

	$copyright = "Copyright ".date('Y').". Powered by Esusu Africa.";
    
    if($detect_company == 1){
    	echo '<meta http-equiv="refresh" content="5;url=account/index">';
    	echo '<br>';
    	echo "<p style='font-size:24px; color:orange;'>Sorry, Phone Number / Username / Email Address has already been used.</p>";
    }
    elseif($myreferral == "" && $dedicated_ussd_prefix != "0"){
        
    	$id = "MEM".time();
    	$insert = mysqli_query($link, "INSERT INTO user VALUES(null,'$fname','$lname','$mname','$email','$phone','$addrs','','','','','','Pending','$username','$encrypt','$id','','$global_role','','Registered','$companyid','$prefix','0000','0.0','','0.0','','','','','','','Allow','Allow','Allow','$datetime','','','','','Pending','agent','','NULL','No','NULL','Yes','$posPIN','0.0')") or die ("Error: " . mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO institution_data VALUES(null,'$companyid','','$acct_cat','$location','$bname','$license_no','$addrs','','','$email','$phone','','$account_type','Pending','Enable','0.0','$wprefund_bal','NULL','$wallet_date_time','','','')") or die ("Error: " . mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$companyid','$refid','self','$wprefund_bal','','Credit','NGN','Prefund_Balance','Remarks: New User Wallet Bonus','successful','$wallet_date_time','','$wprefund_bal','')") or die ("Error: " . mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO member_settings VALUE(null,'$companyid','$bname','','','$my_senderid','$currency_type','','NotActive','','','','','100000','','','','Off','Disabled','','','','','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','No','','','','','Off','Disabled','Disabled','Disabled','Off','Off','None','Off','Off','Off','Wallet Africa','Off','Off','Providus Bank','Providus Bank','Off','Off','PrimeAirtime','PrimeAirtime','PrimeAirtime','None','None','Off','$lastledgerAcctNo_prefix','Off','$copyright','No','','No','Yes','No','','Off','','Off')") or die ("Error: " . mysqli_error($link));
		$insert = mysqli_query($link, "INSERT INTO maintenance_history VALUE(null,'$companyid','2.5','','flat','7.5','1000','Activated','Hybrid','50','0','50','30','30','0','0','0.01','0.005','Flat','0','0','4','0','0')") or die ("Error: " . mysqli_error($link));
		
		$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";	
		$shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/complete_reg.php?a_key='.$companyid.'&&sid='.$id;
	
		$transactionPin = substr((uniqid(rand(),1)),3,4);

		$sms = "$r->abb>>>Welcome $fname! Unique ID: $my_senderid, Username: $username, Password: $password, Transaction Pin: $transactionPin. Login here: https://esusu.app/$my_senderid";

		if(!$insert)
		{
			echo '<meta http-equiv="refresh" content="5;url=account/index">';
			echo '<br>';
			echo '<span class="itext" style="color: orange">Unable to Register...Please Try Again Later!!</span>';
		}else{
			$sendSMS->backendGeneralAlert($sysabb, $phone, $sms, $refid, $sms_rate, $id);
			$sendSMS->frontendClientRegNotifier($email, $fname, $companyid, $shortenedurl, $username, $password);
			echo '<meta http-equiv="refresh" content="5;url=account/index">';
			echo '<br>';
			echo'<span class="itext" style="color: blue">Account Created Successfully!...Kindly check the mail / sms sent to your mobile number to proceed</span>';
		}
		
    }
    elseif($myreferral != "" && $dedicated_ussd_prefix != "0"){
        
        $id = "MEM".time();
        $insert = mysqli_query($link, "INSERT INTO user VALUES(null,'$fname','$lname','$mname','$email','$phone','$addrs','','','','','','Pending','$username','$encrypt','$id','','$global_role','','Registered','$companyid','$prefix','0000','0.0','','0.0','','','','','','','Allow','Allow','Allow','$datetime','','','','','Pending','agent','','NULL','No','NULL','Yes','$posPIN','0.0')") or die ("Error: " . mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO institution_data VALUES(null,'$companyid','','$acct_cat','$location','$bname','$license_no','$addrs','','','$email','$phone','','$account_type','Pending','Enable','0.0','$wprefund_bal','NULL','$wallet_date_time','','','')") or die ("Error: " . mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$companyid','$refid','self','$wprefund_bal','','Credit','NGN','Prefund_Balance','Remarks: New User Wallet Bonus','successful','$wallet_date_time','','$wprefund_bal','')") or die ("Error: " . mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO member_settings VALUE(null,'$companyid','$bname','','','$my_senderid','$currency_type','','NotActive','','','','','100000','','','','Off','Disabled','','','','','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','Off','No','','','','','Off','Disabled','Disabled','Disabled','Off','Off','None','Off','Off','Off','Wallet Africa','Off','Off','Providus Bank','Providus Bank','Off','Off','PrimeAirtime','PrimeAirtime','PrimeAirtime','None','None','Off','$lastledgerAcctNo_prefix','Off','$copyright','No','','No','Yes','No','','Off','','Off')") or die ("Error: " . mysqli_error($link));
    	$insert = mysqli_query($link, "INSERT INTO maintenance_history VALUE(null,'$companyid','2.5','','flat','7.5','1000','Activated','Hybrid','50','0','50','30','30','0','0','0.01','0.01','Flat','0','0','4','0','0')") or die ("Error: " . mysqli_error($link));
    	
    	//$insert_referral = mysqli_query($link, "INSERT INTO referral_records VALUES(null,'$companyid','$myreferral','$account_type',NOW())") or die ("Error: " . mysqli_error($link));

		$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";	
		$shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/complete_reg.php?a_key='.$companyid.'&&sid='.$id;
		
		$transactionPin = substr((uniqid(rand(),1)),3,4);

		$sms = "$r->abb>>>Welcome $fname! Unique ID: $companyid, Sender ID: $my_senderid, Username: $username, Password: $password, Transaction Pin: $transactionPin. Login here: https://esusu.app/$my_senderid";

		if(!$insert)
		{
			echo '<meta http-equiv="refresh" content="5;url=account/index">';
			echo '<br>';
			echo '<span class="itext" style="color: orange">Unable to Register...Please Try Again Later!!</span>';
		}else{
			$sendSMS->backendGeneralAlert($sysabb, $phone, $sms, $refid, $sms_rate, $id);
			$sendSMS->frontendClientRegNotifier($email, $fname, $companyid, $shortenedurl, $username, $password);
			echo '<meta http-equiv="refresh" content="5;url=account/index">';
			echo '<br>';
			echo'<span class="itext" style="color: blue">Account Created Successfully!...Kindly check the mail / sms sent to your mobile number to proceed</span>';
		}
		
    }
    else{
        
        $id = "MEM".time();
        $bopendate = date("Y-m-d");
        $ip = $_SERVER['REMOTE_ADDR']; // This will contain the ip of the request
        $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip), true);
        $origin_countryCode = $dataArray["geoplugin_countryCode"];
        $origin_country = mysqli_real_escape_string($link, $_POST['origin_country']);
        $origin_city = mysqli_real_escape_string($link, $_POST['origin_city']);
        $origin_province = mysqli_real_escape_string($link, $_POST['origin_province']);
        $mybranchid = 'BR'.time();
        $sysabb = $myhost;
		
		$transactionPin = substr((uniqid(rand(),1)),3,4);
		
		$sms = "$sysabb>>>Congrats! Dear $fname! Your account have been created successfully. Username: $username, Password: $password. tPIN: $transactionPin, Login to your account here: https://esusu.app/$sysabb";
        
    	$insert = mysqli_query($link, "INSERT INTO user VALUES(null,'$fname','$email','$phone','$addrs','','$origin_city','$origin_province','','$origin_countryCode','Pending','$username','$encrypt','$id','','$global_role','$mybranchid','Registered','$companyid','$prefix','0000','0.0','','0.0','','','','','','','Allow','Allow','Allow','$datetime','','','','','Pending','agent','','NULL','No','NULL','Yes','$posPIN','0.0')") or die ("Error: " . mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO branches VALUES(null,'BR','$bname','$bopendate','$origin_country','$currency_type','$addrs','$origin_city','$origin_province','NIL','$phone','$phone','$mybranchid','Operational','','$companyid')") or die ("Error: " . mysqli_error($link));
    
        if(!$insert){
            
            echo '<meta http-equiv="refresh" content="5;url=/'.$sysabb.'">';
			echo '<br>';
			echo'<span class="itext" style="color: orange">Oops!...Registration not successful, please try again later!!</span>';
            
        }else{
            
            $sendSMS->backendGeneralAlert($sysabb, $phone, $sms, $refid, $sms_rate, $id);
            echo '<meta http-equiv="refresh" content="5;url=/'.$sysabb.'">';
			echo '<br>';
			echo'<span class="itext" style="color: blue">Account Created Successfully!...Kindly check the mail / sms sent to proceed!!</span>';
            
        }
        
    }
}


if(isset($_POST['cregister'])) 
{
	$coopid = mysqli_real_escape_string($link, $_POST['coopid']);
	$coopmemberID = mysqli_real_escape_string($link, $_POST['coopmemberID']);
	$ctype =  mysqli_real_escape_string($link, $_POST['ctype']);
	$cname =  mysqli_real_escape_string($link, $_POST['cname']);
	$regno = mysqli_real_escape_string($link, $_POST['regno']);
	$addrs = mysqli_real_escape_string($link, $_POST['location']);
	$state = mysqli_real_escape_string($link, $_POST['state']);
	$country =  mysqli_real_escape_string($link, $_POST['country']);
	$coopemail =  mysqli_real_escape_string($link, $_POST['email']);
	$contact_person =  mysqli_real_escape_string($link, $_POST['contact_person']);
	$occupation =  mysqli_real_escape_string($link, $_POST['occupation']);
	$phone =  mysqli_real_escape_string($link, $_POST['phone']);
	$mobile = mysqli_real_escape_string($link, $_POST['mobile']);
	$referral = mysqli_real_escape_string($link, $_POST['referral']);

	$verify_coopem = mysqli_query($link, "SELECT * FROM cooperatives WHERE official_email = '$coopemail'");
    $detect_coopem = mysqli_num_rows($verify_coopem);
		
	$verify_coopph = mysqli_query($link, "SELECT * FROM cooperatives WHERE official_phone = '$phone'");
    $detect_coopph = mysqli_num_rows($verify_coopph);
    
    if($detect_coopem == 1){
    	echo '<meta http-equiv="refresh" content="5;url=/">';
    	echo '<br>';
    	echo "<p style='font-size:24px; color:orange;'>Sorry, Email Address has already been used1.</p>";
    }
    elseif($detect_coopph == 1){
    	echo '<meta http-equiv="refresh" content="5;url=/">';
    	echo '<br>';
    	echo "<p style='font-size:24px; color:orange;'>Sorry, Phone Number has already been used.</p>";
    }
    elseif($referral == "")
    {
    	//this handles uploading of rentals image
		$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));

		$target_dir = "../img/";
	  	$target_file = $target_dir.basename($_FILES["image"]["name"]);
	  	//$target_file_c_sign = $target_dir.basename($_FILES["c_sign"]["name"]);
	  	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	  	//$imageFileType_c_sign = pathinfo($target_file_c_sign,PATHINFO_EXTENSION);
	  	$check = getimagesize($_FILES["image"]["tmp_name"]);
	  	//$check_c_sign = getimagesize($_FILES["c_sign"]["tmp_name"]);
	  	
	  	$sourcepath = $_FILES["image"]["tmp_name"];
	  	//$sourcepath_c_sign = $_FILES["c_sign"]["tmp_name"];
	  	$targetpath = "../img/" . $_FILES["image"]["name"];
	  	//$targetpath_c_sign = "../img/" . $_FILES["c_sign"]["name"];
	  	move_uploaded_file($sourcepath,$targetpath);
	  	//move_uploaded_file($sourcepath_c_sign,$targetpath_c_sign);

	  	$location = "img/".$_FILES['image']['name'];
	  	//$loaction_c_sign = "img/".$_FILES['c_sign']['name'];
	  
	  	//$today = date("Y-m-d");
	  	$insert_coop = mysqli_query($link, "INSERT INTO cooperatives VALUES(null,'$coopid','$ctype','$cname','$location','$addrs','$state','$country','$coopemail','$phone','$mobile','$regno','Pending','Disable','0.0','0.0','NULL',NOW())") or die (mysqli_error($link));

	  	$insert = mysqli_query($link, "INSERT INTO coop_members VALUES(null,'$coopmemberID','$coopid','','$contact_person','','','$occupation','','','','','','','Admin',NOW())") or die (mysqli_error($link));

	  	echo $filename=$_FILES["document"]["tmp_name"];

		foreach($_FILES['document']['name'] as $key => $name){
		$newFilename = $name;
		move_uploaded_file($_FILES['document']['tmp_name'][$key], 'img/'.$newFilename);
		$finalfile = '../img/'.$newFilename;

		$insert_record = mysqli_query($link, "INSERT INTO cooperatives_legaldocuments VALUES(null,'$coopid','$finalfile')");
		}

		$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = $protocol . $_SERVER['HTTP_HOST']."/complete_coopreg.php?coopid=".$coopid;
		
		$shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/complete_coopreg.php?a_key='.$coopid;
		
		$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));		
		$r = mysqli_fetch_object($query);
		$sys_abb = $r->abb;
		$sys_email = $r->email;

		$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = ''");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;

		$sms = "$r->abb>>>Welcome $cname! Your Cooperative ID is $coopid. Please logon to your email to proceed. Thanks.";

		if(!$insert_coop)
		{
			echo '<meta http-equiv="refresh" content="5;url=/">';
			echo '<br>';
			echo '<span class="itext" style="color: orange">Unable to Register...Please Try Again Later!!</span>';
		}else{
			include('cron/send_general_sms.php');
			include('cron/send_coop_regemail.php');
			echo '<meta http-equiv="refresh" content="5;url=/">';
			echo '<br>';
			echo'<span class="itext" style="color: blue">Account Created Successfully!...Kindly check the mail / sms sent to your mobile number to proceed</span>';
		}  	
    }
    else{

	//this handles uploading of rentals image
	$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));

	$target_dir = "../img/";
  	$target_file = $target_dir.basename($_FILES["image"]["name"]);
  	//$target_file_c_sign = $target_dir.basename($_FILES["c_sign"]["name"]);
  	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
  	//$imageFileType_c_sign = pathinfo($target_file_c_sign,PATHINFO_EXTENSION);
  	$check = getimagesize($_FILES["image"]["tmp_name"]);
  	//$check_c_sign = getimagesize($_FILES["c_sign"]["tmp_name"]);
  	
  	$sourcepath = $_FILES["image"]["tmp_name"];
  	//$sourcepath_c_sign = $_FILES["c_sign"]["tmp_name"];
  	$targetpath = "../img/" . $_FILES["image"]["name"];
  	//$targetpath_c_sign = "../img/" . $_FILES["c_sign"]["name"];
  	move_uploaded_file($sourcepath,$targetpath);
  	//move_uploaded_file($sourcepath_c_sign,$targetpath_c_sign);

  	$location = "img/".$_FILES['image']['name'];
  	//$loaction_c_sign = "img/".$_FILES['c_sign']['name'];
  
  	//$today = date("Y-m-d");
  	$insert_coop = mysqli_query($link, "INSERT INTO cooperatives VALUES(null,'$coopid','$ctype','$cname','$location','$addrs','$state','$country','$coopemail','$phone','$mobile','$regno','Pending','Disable','0.0','0.0','NULL',NOW())") or die (mysqli_error($link));

  	$insert = mysqli_query($link, "INSERT INTO coop_members VALUES(null,'$coopmemberID','$coopid','','$contact_person','','','$occupation','','','','','','','Admin',NOW())") or die (mysqli_error($link));

  	$insert_referral = mysqli_query($link, "INSERT INTO referral_records VALUES(null,'$coopid','$referral','Cooperative',NOW())");

  	echo $filename=$_FILES["document"]["tmp_name"];

	foreach($_FILES['document']['name'] as $key => $name){
	$newFilename = $name;
	move_uploaded_file($_FILES['document']['tmp_name'][$key], 'img/'.$newFilename);
	$finalfile = '../img/'.$newFilename;

	$insert_record = mysqli_query($link, "INSERT INTO cooperatives_legaldocuments VALUES(null,'$coopid','$finalfile')");
	}

	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$url = $protocol . $_SERVER['HTTP_HOST']."/complete_coopreg.php?coopid=".$coopid;
	
	$shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/complete_coopreg.php?a_key='.$coopid;
	
	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));		
	$r = mysqli_fetch_object($query);
	$sys_abb = $r->abb;
	$sys_email = $r->email;

	$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = ''");
	$fetch_gateway = mysqli_fetch_object($search_gateway);
	$gateway_uname = $fetch_gateway->username;
	$gateway_pass = $fetch_gateway->password;
	$gateway_api = $fetch_gateway->api;

	$sms = "$r->abb>>>Welcome $cname! Your Cooperative ID is $coopid. Please logon to your email to proceed. Thanks.";

	if(!($insert_coop && $insert_referral))
	{
		echo '<meta http-equiv="refresh" content="5;url=/">';
		echo '<br>';
		echo '<span class="itext" style="color: orange">Unable to Register...Please Try Again Later!!</span>';
	}else{
		include('cron/send_general_sms.php');
		include('cron/send_coop_regemail.php');
		echo '<meta http-equiv="refresh" content="5;url=/">';
		echo '<br>';
		echo'<span class="itext" style="color: blue">Account Created Successfully!...Kindly check the mail / sms sent to your mobile number to proceed</span>';
	}  	
}
}

if(isset($_POST['cmregister'])) 
{
	function random_password($limit)
	{
	    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
	}

	$coopmemberID = mysqli_real_escape_string($link, $_POST['coopmemberID']);
	$mrole = mysqli_real_escape_string($link, $_POST['mrole']);
	$coopid =  mysqli_real_escape_string($link, $_POST['coopid']);
	$fname = mysqli_real_escape_string($link, $_POST['fname']);
	$phone_no = mysqli_real_escape_string($link, $_POST['phone_no']);
	$memail =  mysqli_real_escape_string($link, $_POST['email']);
	$occupation =  mysqli_real_escape_string($link, $_POST['occupation']);
	$password =  random_password(10);
	$bvn =  mysqli_real_escape_string($link, $_POST['unumber']);

	$search_mfreq = mysqli_query($link, "SELECT * FROM coop_members WHERE coopid = '$coopid'");
	$fetcch_mfreq = mysqli_fetch_object($search_mfreq);
	$meeting_freq = $fetcch_mfreq->meeting_freq;
	$total_member = mysqli_num_rows($search_mfreq);

	$verify_coopmem = mysqli_query($link, "SELECT * FROM coop_members WHERE email = '$memail'");
    $detect_coopmem = mysqli_num_rows($verify_coopmem);
		
	$verify_coopmph = mysqli_query($link, "SELECT * FROM coop_members WHERE phone = '$phone_no'");
    $detect_coopmph = mysqli_num_rows($verify_coopmph);

    $verify_reglimit = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE coopid_instid = '$coopid' AND status = 'Paid' AND usage_status = 'Active'");
    $detect_reglimit = mysqli_fetch_object($verify_reglimit);
    $member_limit = $detect_reglimit->staff_limit;
    
    if($detect_coopmem == 1){
    	echo '<meta http-equiv="refresh" content="5;url=/">';
    	echo '<br>';
    	echo "<p style='font-size:24px; color:orange;'>Sorry, Email Address has already been used.</p>";
    }
    elseif($detect_coopmph == 1){
    	echo '<meta http-equiv="refresh" content="5;url=/">';
    	echo '<br>';
    	echo "<p style='font-size:24px; color:orange;'>Sorry, Phone Number has already been used.</p>";
    }
    elseif($total_member == $member_limit){
    	echo '<meta http-equiv="refresh" content="5;url=/">';
    	echo '<br>';
    	echo "<p style='font-size:24px; color:orange;'>Sorry, Please kindly consider joining other cooperatives on the list as the one you apply to has already reach it members limit.</p>";
    }
    else{	

    	$target_dir = "../img/";
    	$target_file = $target_dir.basename($_FILES["image"]["name"]);
    	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    	$check = getimagesize($_FILES["image"]["tmp_name"]);
    	$sourcepath = $_FILES["image"]["tmp_name"];
    	$targetpath = "../img/" . $_FILES["image"]["name"];

    	move_uploaded_file($sourcepath,$targetpath);

    	$location = "img/".$_FILES['image']['name'];

    	$insert = mysqli_query($link, "INSERT INTO coop_members VALUES(null,'$coopmemberID','$coopid','$location','$fname','$phone_no','$memail','$occupation','','$bvn','','','$password','$meeting_freq','$mrole',NOW())") or die (mysqli_error($link));


	    echo $filename=$_FILES["document"]["tmp_name"];

	    foreach($_FILES['document']['name'] as $key => $name){

	    $newFilename = $name;
	    move_uploaded_file($_FILES['document']['tmp_name'][$key], 'img/'.$newFilename);
	    $finalfile = '../img/'.$newFilename;

	    $insert_record = mysqli_query($link, "INSERT INTO cooperatives_legaldocuments VALUES(null,'$coopmemberID','$finalfile')");
	}

	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));		
	$r = mysqli_fetch_object($query);
	$sys_abb = $r->abb;
	$sys_email = $r->email;

	$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = ''");
	$fetch_gateway = mysqli_fetch_object($search_gateway);
	$gateway_uname = $fetch_gateway->username;
	$gateway_pass = $fetch_gateway->password;
	$gateway_api = $fetch_gateway->api;

	$sms = "$r->abb>>>Welcome $fname! Your Member ID is $coopmemberID. Logon to your email as your login details has been sent their. Thanks.";

	if(!$insert)
	{
	   echo '<meta http-equiv="refresh" content="5;url=/">';
		echo '<br>';
		echo '<span class="itext" style="color: orange">Unable to Register...Please Try Again Later!!</span>';
	}else{
		include('cron/send_coopmember_sms.php');
		include('cron/send_coopmember_regemail.php');
		echo '<meta http-equiv="refresh" content="5;url=/">';
		echo '<br>';
		echo'<span class="itext" style="color: blue">Account Created Successfully!...Kindly check the mail / sms sent to your mobile number to proceed</span>';
	}  	
}
}
?> 
</div>
</body>
</html>