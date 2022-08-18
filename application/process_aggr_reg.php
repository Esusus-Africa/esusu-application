<?php 
error_reporting(0); 
include "../config/session.php";
?>  

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
<div class="loader"></div>
<?php
if(isset($_POST['aggregister']))
{
	function random_password($limit)
	{
	    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
	}

	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));		
	$r = mysqli_fetch_object($query);
	$sysabb = $r->abb;
    $sys_email = $r->email;

	//Aggregator Records
	$merchant = mysqli_real_escape_string($link, $_POST['merchant']);
	$fname = mysqli_real_escape_string($link, $_POST['fname']);
	$lname = mysqli_real_escape_string($link, $_POST['lname']);
	$mname = mysqli_real_escape_string($link, $_POST['mname']);
	$addrs = mysqli_real_escape_string($link, $_POST['addrs']);
	$state =  mysqli_real_escape_string($link, $_POST['state']);
	$country =  mysqli_real_escape_string($link, $_POST['country']);
    $full_name = $lname.' '.$fname.' '.$mname;
	$dob = mysqli_real_escape_string($link, $_POST['dob']);
	$userBvn = mysqli_real_escape_string($link, $_POST['unumber']);

    $username = mysqli_real_escape_string($link, $_POST['username']);
	$password = random_password(10);
    $encrypt = base64_encode($password);
    
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $phone = mysqli_real_escape_string($link, $_POST['phone']);

    $gender =  mysqli_real_escape_string($link, $_POST['gender']);
	$aggr_id = "AGGR".rand(1000,9999);
	$transactionPin = substr((uniqid(rand(),1)),3,4);

	$aggr_co_type = mysqli_real_escape_string($link, $_POST['commtype']);
    $aggr_co_rate = mysqli_real_escape_string($link, $_POST['commrate']);

    $verify_email = mysqli_query($link, "SELECT * FROM aggregator WHERE email = '$email'");
	$detect_email = mysqli_num_rows($verify_email);

	$verify_phone = mysqli_query($link, "SELECT * FROM aggregator WHERE phone = '$phone'");
	$detect_phone = mysqli_num_rows($verify_phone);

	$verify_username = mysqli_query($link, "SELECT * FROM aggregator WHERE username = '$username'");
	$detect_username = mysqli_num_rows($verify_username);

	$verify_username2 = mysqli_query($link, "SELECT * FROM user WHERE username = '$username'");
	$detect_username2 = mysqli_num_rows($verify_username2);
	
	$date_time = date("Y-m-d h:i:s");

	$verify_id = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$merchant'");
	$detect_id = mysqli_fetch_array($verify_id);
	$dedicated_ussdprefix = $detect_id['dedicated_ussd_prefix'];

	require_once "../config/bvnVerification_class.php";
  
	$processBVN = $newBV->walletAfricaBVNVerifier($walletafrica_skey,$userBvn,$link);
	$ResponseCode = $processBVN['ResponseCode'];

	//BVN Details
	$bvnlname = $processBVN['LastName'];
	$bvndob = $processBVN['DateOfBirth'];
	$default_dob = date("d-M-Y", strtotime($dob));
    $bvn_picture = $processBVN['Picture'];
    $dynamicStr = md5(date("Y-m-d h:i"));
    $image_converted = $newBV->base64_to_jpeg($bvn_picture, $dynamicStr.".png");
    $imagePath = "img/".$image_converted;

    //20 array row
    $mybvn_data = $processBVN['BVN']."|".$processBVN['FirstName']."|".$processBVN['LastName']."|".$processBVN['MiddleName']."|".$processBVN['DateOfBirth']."|".$processBVN['PhoneNumber']."|".$processBVN['Email']."|".$processBVN['EnrollmentBank']."|".$processBVN['EnrollmentBranch']."|".$processBVN['Gender']."|".$processBVN['LevelOfAccount']."|".$processBVN['LgaOfOrigin']."|".$processBVN['LgaOfResidence']."|".$processBVN['MaritalStatus']."|".$processBVN['NameOnCard']."|".$processBVN['Nationality']."|".$processBVN['StateOfOrigin']."|".$processBVN['StateOfResidence']."|".$processBVN['Title']."|".$processBVN['WatchListed']."|".$image_converted;
    
    if($detect_email == 1){
		echo '<meta http-equiv="refresh" content="5;url=add_aggregator?id='.$_SESSION['tid'].'&&mid=NDE5&&tab=tab_1">';
		echo '<br>';
		echo "<p style='font-size:24px; color:orange;'>Sorry, Email Address has already been used.</p>";
	}
	elseif($detect_phone == 1){
		echo '<meta http-equiv="refresh" content="5;url=add_aggregator?id='.$_SESSION['tid'].'&&mid=NDE5&&tab=tab_1">';
		echo '<br>';
		echo "<p style='font-size:24px; color:orange;'>Sorry, Phone Number has already been used.</p>";
	}
	elseif($detect_username == 1 || $detect_username2 == 1){
		echo '<meta http-equiv="refresh" content="5;url=add_aggregator?id='.$_SESSION['tid'].'&&mid=NDE5&&tab=tab_1">';
		echo '<br>';
		echo "<p style='font-size:24px; color:orange;'>Sorry, Username has already been used.</p>";
	}
	/**elseif($ResponseCode != "200"){

		echo "<p style='font-size:24px; color:orange;'>Sorry, We're unable to verify aggregator BVN at the moment, please try again later!! </p>";
	  
	}
	elseif($bvnlname != strtoupper($lname) || $bvndob != $default_dob){

		echo '<meta http-equiv="refresh" content="10;url=add_aggregator?id='.$_SESSION['tid'].'&&mid=NDE5&&tab=tab_1">';
		echo '<br>';
		echo "<p style='font-size:24px; color:orange;'>Sorry, Aggregator Details does not match with BVN Record!!</p>";
		echo "<p style='font-size:24px; color:orange;'>BVN Record:- Last Name: ".$bvnlname." | Date of Birth: ".$bvndob."</p>";
		echo "<p style='font-size:24px; color:orange;'>Provided Info:- Last Name: ".strtoupper($lname)." | Date of Birth: ".$default_dob."</p>";

	}**/
	else{

		$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = $protocol.$_SERVER['HTTP_HOST']."/?id=".$aggr_id;
		$ide = time();
		$shorturl = base_convert($ide,20,36);
		
		$shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/?agr_key=' . $shorturl;

		foreach($_FILES['documents']['name'] as $key => $name){
        
			$newFilename = $name;
			
			if($newFilename == "")
			{
				echo "";
			}
			else{
				$newlocation = '../img/'.$newFilename;
				if(move_uploaded_file($_FILES['documents']['tmp_name'][$key], 'img/'.$newFilename))
				{
					mysqli_query($link, "INSERT INTO institution_legaldoc VALUES(null,'$aggr_id','$newlocation')") or die (mysqli_error($link));
				}
			}
		  
		}

		$rOrderID = "EA-bvnCharges-".time();

		($ResponseCode != "200") ? "" : $insert = mysqli_query($link, "INSERT INTO bvn_log VALUE(null,'','','$aggr_id','$uid','$mybvn_data','$bvn_fee','$date_time','$rOrderID')");

    	($ResponseCode != "200") ? "" : $insert = mysqli_query($link, "INSERT INTO expenses VALUES(null,'','$rOrderID','BVN Verification','$bvn_fee','$date','$full_name BVN Verification Charges as an Aggregator')");

		$insert = mysqli_query($link, "INSERT INTO activate_member VALUES(null,'$url','$shorturl','No','$aggr_id')") or die ("Error: " . mysqli_error($link));

        $new_member = mysqli_query($link, "INSERT INTO aggregator VALUES(null,'$aggr_id','','$fname','$lname','$mname','$gender','$dob','$email','$phone','$username','$encrypt','NGN','$aggr_co_type','$aggr_co_rate','$date_time','$merchant')") or die (mysqli_error($link));
		
		$new_member = mysqli_query($link, "INSERT INTO user VALUES(null,'$fname','$lname','$mname','$email','$phone','$addrs','$userBvn','','$state','','$country','Pending','$username','$encrypt','$aggr_id','$imagePath','aggregator','','Registered','','AGGR','$transactionPin','0.0','','0.0','','','','$dedicated_ussdprefix','$gender','$dob','Disallow','Disallow','Disallow','$date_time','','','','','Verified','agent','$uid','NULL','Yes','VerveCard','Yes','123456','0.0')") or die (mysqli_error($link));

		$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;
		
		$sms = "$sysabb>>>Welcome $fname! Your Aggregator ID: $aggr_id, Username: $username, Password: $password, Transaction Pin: $transactionPin. Login here: https://esusu.app";
            
        if(!$new_member)
        {
			echo '<meta http-equiv="refresh" content="3;url=add_aggregator?id='.$_SESSION['tid'].'&&mid=NDE5&&tab=tab_1">';
    	    echo '<br>';
    	    echo "<p style='font-size:20px; color:blue;'>Unable to register, Please try again later.</p>";
		}
		else{
			include('../cron/send_aggregemail.php');
		    include('../cron/send_general_sms.php');
            echo '<meta http-equiv="refresh" content="3;url=add_aggregator?id='.$_SESSION['tid'].'&&mid=NDE5&&tab=tab_1">';
    	    echo '<br>';
    	    echo "<p style='font-size:20px; color:blue;'>Account Created Successfully! An email / sms notification has been sent to the Aggregator.</p>";
		}
	}
}
?>
</div>
</body>
</html>