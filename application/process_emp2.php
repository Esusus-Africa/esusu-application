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
$id = $_GET['id'];
$name =  mysqli_real_escape_string($link, $_POST['name']);
$lname =  mysqli_real_escape_string($link, $_POST['lname']);
$mname =  mysqli_real_escape_string($link, $_POST['mname']);
$email =  mysqli_real_escape_string($link, $_POST['email']);
$phone =  mysqli_real_escape_string($link, $_POST['phone']);
$addr1 =  mysqli_real_escape_string($link, $_POST['addr1']);
$city =  mysqli_real_escape_string($link, $_POST['city']);
$state =  mysqli_real_escape_string($link, $_POST['state']);
$zip =  mysqli_real_escape_string($link, $_POST['zip']);
$country =  mysqli_real_escape_string($link, $_POST['country']);
$username =  mysqli_real_escape_string($link, $_POST['username']);
$edit_role =  mysqli_real_escape_string($link, $_POST['urole']);
$cust_reg =  mysqli_real_escape_string($link, $_POST['cust_reg']);
$deposit =  mysqli_real_escape_string($link, $_POST['deposit']);
$withdrawal =  mysqli_real_escape_string($link, $_POST['withdrawal']);
$status = mysqli_real_escape_string($link, $_POST['status']);
$dept = mysqli_real_escape_string($link, $_POST['dept']);
$allow_auth = mysqli_real_escape_string($link, $_POST['allow_auth']);
$password =  mysqli_real_escape_string($link, $_POST['password']);
$encrypt = base64_encode($password);

/**POS WAllet
$posid = mysqli_real_escape_string($link, $_POST['wtid']);
$walletId = mysqli_real_escape_string($link, $_POST['walletId']);
$wallet_uname = mysqli_real_escape_string($link, $_POST['wallet_uname']);
$wallet_pass = mysqli_real_escape_string($link, $_POST['wallet_pass']);

$searchWallet = mysqli_query($link, "SELECT * FROM pos_wallet WHERE tid = '$posid'") or die ("Error: " . mysqli_error($link));
$fetchWalletNum = mysqli_num_rows($searchWallet);
**/

$search_user = mysqli_query($link, "SELECT * FROM user WHERE userid = '$id'") or die ("Error: " . mysqli_error($link));
$fetch_user = mysqli_fetch_array($search_user);
$utid = $fetch_user['id'];
$created_by = $fetch_user['created_by'];

$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
if($image == "")
{		
	$branch =  mysqli_real_escape_string($link, $_POST['branch']);
	
	$update = mysqli_query($link,"UPDATE user SET name='$name',lname='$lname',mname='$mname',email='$email',phone='$phone',addr1='$addr1',city='$city',state='$state',zip='$zip',country='$country',username='$username',password='$encrypt', branchid='$branch', role = '$edit_role', cust_reg='$cust_reg', deposit='$deposit', withdrawal='$withdrawal', comment = '$status', dept = '$dept', allow_auth = '$allow_auth' WHERE userid ='$id'") or die("Error: " . mysqli_error($link)); 
	//($pos_wallet_settings != '1' || $walletId == "" ? "" : (($fetchWalletNum == 1 ) ? mysqli_query($link,"UPDATE pos_wallet SET walletid = '$walletId', username = '$wallet_uname', password = '$wallet_pass' WHERE tid = '$posid'") or die ("Error: " . mysqli_error($link)) : mysqli_query($link,"INSERT INTO pos_wallet VALUES(null,'$created_by','$utid','$wallet_uname','$wallet_pass','$walletId',NOW())") or die ("Error: " . mysqli_error($link))));
	
	if(!$update)
	{
	echo '<meta http-equiv="refresh" content="2;url=view_emp.php?id='.$utid.'&&mid='.base64_encode("419").'">';
	echo '<br>';
	echo'<span class="itext" style="color: orange">Unable to update employee records!</span>';
	}
	else{
		//include("alert_sender/edit_emp_alert.php");
		echo '<meta http-equiv="refresh" content="2;url=view_emp.php?id='.$utid.'&&mid='.base64_encode("419").'">';
		echo '<br>';
		echo'<span class="itext" style="color: blue">Updating Employee.....Please Wait!</span>';
	}	
}
else{
	$target_dir = "../img/";
	$target_file = $target_dir.basename($_FILES["image"]["name"]);
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$check = getimagesize($_FILES["image"]["tmp_name"]);
	
	if($check == false)
	{
		echo '<meta http-equiv="refresh" content="2;url=view_emp.php?id='.$utid.'&&mid='.base64_encode("419").'">';
		echo '<br>';
		echo'<span class="itext" style="color: orange">Invalid file type</span>';
	}
	elseif($_FILES["image"]["size"] > 500000)
	{
		echo '<meta http-equiv="refresh" content="2;url=view_emp.php?id='.$utid.'&&mid='.base64_encode("419").'">';
		echo '<br>';
		echo'<span class="itext" style="color: orange">Image must not more than 500KB!</span>';
	}
	elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
	{
		echo '<meta http-equiv="refresh" content="2;url=view_emp.php?id='.$utid.'&&mid='.base64_encode("419").'">';
		echo '<br>';
		echo'<span class="itext" style="color: orange">Sorry, only JPG, JPEG, PNG & GIF Files are allowed.</span>';
	}
	else{
		$sourcepath = $_FILES["image"]["tmp_name"];
		$targetpath = "../img/" . $_FILES["image"]["name"];
		move_uploaded_file($sourcepath,$targetpath);

		$location = $_FILES['image']['name'];
		$branch =  mysqli_real_escape_string($link, $_POST['branch']);

		$search_user = mysqli_query($link, "SELECT * FROM user WHERE userid = '$id'") or die (mysqli_error($link));
		$fetch_user = mysqli_fetch_array($search_user);
		$utid = $fetch_user['id'];
	
		//$update1 = mysqli_query($link,"UPDATE twallet SET branchid = '$branchid' WHERE tid = '$utid'") or die (mysqli_error($link));
		$update = mysqli_query($link,"UPDATE user SET name='$name',lname='$lname',mname='$mname',email='$email',phone='$phone',addr1='$addr1',city='$city',state='$state',zip='$zip',country='$country',comment='$comment',username='$username',password='$encrypt',image='$location', branchid='$branch', role = '$edit_role', cust_reg='$cust_reg', deposit='$deposit', withdrawal='$withdrawal', comment = '$status', dept = '$dept', allow_auth = '$allow_auth' WHERE userid ='$id'") or die("Error: " . mysqli_error($link)); 
		//($pos_wallet_settings != '1' || $walletId == "" ? "" : (($fetchWalletNum == 1 ) ? mysqli_query($link,"UPDATE pos_wallet SET walletid = '$walletId', username = '$wallet_uname', password = '$wallet_pass' WHERE tid = '$posid'") or die ("Error: " . mysqli_error($link)) : mysqli_query($link,"INSERT INTO pos_wallet VALUES(null,'$created_by','$utid','$wallet_uname','$wallet_pass','$walletId',NOW())") or die ("Error: " . mysqli_error($link))));
		
		if(!$update)
		{
			echo '<meta http-equiv="refresh" content="2;url=view_emp.php?id='.$utid.'&&mid='.base64_encode("419").'">';
			echo '<br>';
			echo'<span class="itext" style="color: orange">Unable to update employee records!</span>';
		}
		else{
			//include("alert_sender/edit_emp_alert.php");
			echo '<meta http-equiv="refresh" content="2;url=view_emp.php?id='.$utid.'&&mid='.base64_encode("419").'">';
			echo '<br>';
			echo'<span class="itext" style="color: blue">Updating Employee.....Please Wait!</span>';
		}
	}
}
?>
</div>
</body>
</html>