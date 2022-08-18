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
if(isset($_POST['save']))
{
	
	function random_password($limit)
	{
	    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
	}

	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));		
	$r = mysqli_fetch_object($query);
	$sysabb = $r->abb;
	$sys_email = $r->email;
	$wprefund_bal = $r->wallet_prefound_amt;

    $refid = "EA-walletPrefund-".rand(1000000,9999999);

	//Agent Records
	$agentid =  mysqli_real_escape_string($link, $_POST['AgentID']);
	$agenttype = mysqli_real_escape_string($link, $_POST['agenttype']);
	$fname = mysqli_real_escape_string($link, $_POST['fname']);
	$gender = mysqli_real_escape_string($link, $_POST['gender']);
	$dob = mysqli_real_escape_string($link, $_POST['dob']);
	$bname =  mysqli_real_escape_string($link, $_POST['bname']);
	$rcnumber =  mysqli_real_escape_string($link, $_POST['rcnumber']);
	$my_senderid = mysqli_real_escape_string($link, $_POST['senderid']);
	$email = mysqli_real_escape_string($link, $_POST['email']);
	$phone = mysqli_real_escape_string($link, $_POST['phone']);
	$username = mysqli_real_escape_string($link, $_POST['username']);
	$currency_type = mysqli_real_escape_string($link, $_POST['currency_type']);

	$password = random_password(10);

	$target_dir = "../img/";
	$target_file = $target_dir.basename($_FILES["image"]["name"]);
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$check = getimagesize($_FILES["image"]["tmp_name"]);

	$sourcepath = $_FILES["image"]["tmp_name"];
	$targetpath = "../img/" . $_FILES["image"]["name"];
	move_uploaded_file($sourcepath,$targetpath);
		
	$location = "img/".$_FILES['image']['name'];

	$encrypt = base64_encode($password);
    $id = "MEM".rand(10000,99999);

	$insert_agent = mysqli_query($link, "INSERT INTO agent_data VALUES(null,'$agentid','$agenttype','$fname','$gender','$dob','$bname','$rcnumber','$occupation','','$email','$phone','$username','$password','','$location','','','Pending','agent_manager','0.0','$wprefund_bal','NULL',NOW(),'$aggr_id')") or die ("Error: " . mysqli_error($link));
    $insert_agent = mysqli_query($link, "INSERT INTO user VALUES(null,'$fname','$email','$phone','','','','','','','Pending','$username','$encrypt','$id','$location','agent_manager','','Registered','$agentid','AG','0000','0.0','','0.0')") or die (mysqli_error($link));
    $insert_agent = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$agentid','$refid','self','$wprefund_bal','NGN','system','Remarks: New User Wallet Bonus','successful',NOW())");
    $insert_agent = mysqli_query($link, "INSERT INTO maintenance_history VALUE(null,'$agentid','2.5','','flat','10','2000','Activated','Hybrid')") or die ("Error: " . mysqli_error($link));
	$insert = mysqli_query($link, "INSERT INTO member_settings VALUE(null,'$agentid','$bname','img/','','$my_senderid','$currency_type','','','','','100000','','','','No','No','No','','','','','')") or die ("Error: " . mysqli_error($link));
	
		$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = ''");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;

		$sms = "$sysabb>>>Welcome $fname! Agent ID: $my_senderid, Username: $username, Password: $password, Transaction Pin: 0000. Login here: https://esusu.app/$my_senderid";

		if(!$insert_agent)
		{
			echo '<meta http-equiv="refresh" content="5;url=add_agent.php?tid='.$_SESSION['tid'].'&&mid=NDQw&&tab=tab_1">';
			echo '<br>';
			echo '<span class="itext" style="color: orange">Error...Please Try Again Later!!<</span>';
		}
		else{
			include('../cron/send_general_sms.php');
			include('../cron/send_agent_regemail.php');
			echo '<meta http-equiv="refresh" content="5;url=add_agent.php?tid='.$_SESSION['tid'].'&&mid=NDQw&&tab=tab_1">';
			echo '<br>';
			echo'<span class="itext" style="color: blue">Accunt Created Successfully!...An email / sms notification has been sent to the agent</span>';
		}
}
?>
</div>
</body>
</html>