<?php 
error_reporting(0); 
include "../config/session.php";
?>  

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
		<div class="loader"></div>
<?php
	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));		
	$r = mysqli_fetch_object($query);
	$sys_abb = $r->abb;
	$sys_email = $r->email;
	$wprefund_bal = $r->wallet_prefound_amt;

    $refid = "EA-walletPrefund-".rand(100000000,999999999);
	//$subaccount_charges = $r->subaccount_charges;

	//Merchant Records
	$merchantID =  mysqli_real_escape_string($link, $_POST['merchantID']);
	$companyname = mysqli_real_escape_string($link, $_POST['companyname']);
	$license_no = mysqli_real_escape_string($link, $_POST['license_no']);
	$company_sector = mysqli_real_escape_string($link, $_POST['company_sector']);
	$state =  mysqli_real_escape_string($link, $_POST['state']);
	$country =  mysqli_real_escape_string($link, $_POST['country']);
	$official_email = mysqli_real_escape_string($link, $_POST['official_email']);
	$official_phone = mysqli_real_escape_string($link, $_POST['official_phone']);
	$id = "MEM".rand(100000,999999);
	$my_senderid = mysqli_real_escape_string($link, $_POST['senderid']);
	$currency_type = mysqli_real_escape_string($link, $_POST['currency']);
	//SETTLEMENT ACCOUNT DETAILS / DIVIDEND
	//$account_number = mysqli_real_escape_string($link, $_POST['account_number']);
	//$bank_code = mysqli_real_escape_string($link, $_POST['bank_code']);
	//$bank_name = mysqli_real_escape_string($link, $_POST['bank_name']);
	//$perc_charges = mysqli_real_escape_string($link, $_POST['perc_charges']);
	//$settlement_schedule = mysqli_real_escape_string($link, $_POST['settlement_schedule']);

	//Contact Person
	$contact_person = mysqli_real_escape_string($link, $_POST['contact_person']);
	$phone = mysqli_real_escape_string($link, $_POST['phone']);
	$cemail = mysqli_real_escape_string($link, $_POST['cemail']);
	$uname = mysqli_real_escape_string($link, $_POST['uname']);
	$password = random_password(10);
	$encrypt = base64_encode($password);
	
	$target_dir = "../img/";
	$target_file = $target_dir.basename($_FILES["image"]["name"]);
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$check = getimagesize($_FILES["image"]["tmp_name"]);

	$verify_email = mysqli_query($link, "SELECT * FROM merchant_reg WHERE official_email = '$official_email'");
	$detect_email = mysqli_num_rows($verify_email);

	$verify_phone = mysqli_query($link, "SELECT * FROM merchant_reg WHERE official_phone = '$official_phone'");
	$detect_phone = mysqli_num_rows($verify_phone);

	$verify_username = mysqli_query($link, "SELECT * FROM merchant_reg WHERE username = '$uname'");
	$detect_username = mysqli_num_rows($verify_username);
	
	$verify_id = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$my_senderid'");
	$detect_id= mysqli_num_rows($verify_id);
	
		
	if($detect_email == 1){
		echo '<meta http-equiv="refresh" content="5;url=add_merchant.php?tid='.$_SESSION['tid'].'&&mid=NDkw&&tab=tab_1">';
		echo '<br>';
		echo "<p style='font-size:24px; color:orange;'>Sorry, Email Address has already been used.</p>";
	}
	elseif($detect_phone == 1){
		echo '<meta http-equiv="refresh" content="5;url=add_merchant.php?tid='.$_SESSION['tid'].'&&mid=NDkw&&tab=tab_1">';
		echo '<br>';
		echo "<p style='font-size:24px; color:orange;'>Sorry, Phone Number has already been used.</p>";
	}
	elseif($detect_username == 1){
		echo '<meta http-equiv="refresh" content="5;url=add_merchant.php?tid='.$_SESSION['tid'].'&&mid=NDkw&&tab=tab_1">';
		echo '<br>';
		echo "<p style='font-size:24px; color:orange;'>Sorry, Username has already been used.</p>";
	}
	elseif($detect_id == 1){
		echo '<meta http-equiv="refresh" content="5;url=add_merchant.php?tid='.$_SESSION['tid'].'&&mid=NDkw&&tab=tab_1">';
		echo '<br>';
		echo "<p style='font-size:24px; color:orange;'>Sorry, Sender ID has already been used.</p>";
	}
	else{

		$sourcepath = $_FILES["image"]["tmp_name"];
		$targetpath = "../img/" . $_FILES["image"]["name"];
		move_uploaded_file($sourcepath,$targetpath);
			
		$location = "img/".$_FILES['image']['name'];

		echo $filename=$_FILES["file"]["tmp_name"];
		foreach ($_FILES['documents']['name'] as $key => $name){

			$newFilename = $name;
			$newlocation = '../img/'.$newFilename;
			if(move_uploaded_file($_FILES['documents']['tmp_name'][$key], '../img/'.$newFilename))
			{
				mysqli_query($link, "INSERT INTO merchant_legaldoc VALUES(null,'$merchantID','$newlocation')");
			}
			
		}

		//$subaccount_code = $result['data']['subaccount_id'];
		//$bank_name = $result['data']['bank_name'];

		//$insert_records = mysqli_query($link, "INSERT INTO merchant_legaldoc VALUES(null,'$merchantID','$newlocation')");
    	$insert_records = mysqli_query($link, "INSERT INTO merchant_reg VALUES(null,'$merchantID','NIL','$location','$companyname','$license_no','$company_sector','$state','$country','$official_email','$official_phone','NIL','NIL','NIL','NIL','','$contact_person','$phone','$cemail','$uname','$password','Admin','NotActive','0.0','0.0','NULL',NOW(),'0000')") or die ("Error: " . mysqli_error($link));
    	$insert_records = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$merchantID','$refid','self','$wprefund_bal','NGN','system','Remarks: New User Wallet Bonus','successful',NOW())") or die ("Error: " . mysqli_error($link));
        $insert_records = mysqli_query($link, "INSERT INTO user VALUES(null,'$contact_person','$cemail','$phone','','','','','','','Pending','$uname','$encrypt','$id','','merchant_super_admin','','Registered','$merchantID','MER','0000','0.0','','0.0')") or die ("Error: " . mysqli_error($link));
        $insert_records = mysqli_query($link, "INSERT INTO member_settings VALUE(null,'$merchantID','$companyname','$location','','$my_senderid','$currency_type','','','','','100000')") or die ("Error: " . mysqli_error($link));
        
		/*$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = ''");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;

		$sms = "$r->abb>>>Welcome $companyname! Your Merchant ID is: $merchantID, Transaction Pin is: 0000. Logon to your email for your username and password.";
			
		include('../cron/send_inst_sms.php');
		include('../cron/send_merchant_regemail.php');*/

		if(!$insert_records)
		{
			echo '<meta http-equiv="refresh" content="5;url=add_merchant.php?tid='.$_SESSION['tid'].'&&mid=NDkw&&tab=tab_1">';
			echo '<br>';
			echo '<span class="itext" style="color: orange">Error...Please Try Again Later!!</span>';

		}
		else{
			echo '<meta http-equiv="refresh" content="5;url=listmerchants.php?tid='.$_SESSION['tid'].'&&mid=NDkw">';
			echo '<br>';
			echo'<span class="itext" style="color: blue">Accunt Created Successfully!...An email / sms notification has been sent to the merchant</span>';

		}
	}
?>
</div>
</body>
</html>