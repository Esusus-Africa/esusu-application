<?php 
error_reporting(0); 
include "../config/session1.php"; 
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
$id = $_GET['id'];
$vendorID =  mysqli_real_escape_string($link, $_POST['vendorID']);
$companyname = mysqli_real_escape_string($link, $_POST['cname']);
$ctype = mysqli_real_escape_string($link, $_POST['ctype']);
$cdesc = mysqli_real_escape_string($link, $_POST['cdesc']);
$caddrs =  mysqli_real_escape_string($link, $_POST['caddrs']);
$official_email =  mysqli_real_escape_string($link, $_POST['cemail']);
$official_phone = mysqli_real_escape_string($link, $_POST['cphone']);
$currency = mysqli_real_escape_string($link, $_POST['currency']);
$uname = mysqli_real_escape_string($link, $_POST['cusername']);
//$rave_skey = mysqli_real_escape_string($link, $_POST['rave_skey']);
//$rave_pkey = mysqli_real_escape_string($link, $_POST['rave_pkey']);
//$rave_status = mysqli_real_escape_string($link, $_POST['rave_status']);
$password = mysqli_real_escape_string($link, $_POST['password']);
$encrypt = base64_encode($password);
$m_subaccount = mysqli_real_escape_string($link, $_POST['m_subaccount']);
//$auth_subaccount = mysqli_real_escape_string($link, $_POST['auth_subaccount']);

$account_number =  mysqli_real_escape_string($link, $_POST['acct_no']);
$bankname =  mysqli_real_escape_string($link, $_POST['bankname']);
	
//BILLING
/** $billtype = mysqli_real_escape_string($link, $_POST['billtype']);
$lbooking = mysqli_real_escape_string($link, $_POST['lbooking']);
$tcharges_type = mysqli_real_escape_string($link, $_POST['tcharges_type']);
$t_charges = mysqli_real_escape_string($link, $_POST['t_charges']);
$capped_amt = mysqli_real_escape_string($link, $_POST['capped_amt']);
**/

$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
if($image == "")
{		
	$update = mysqli_query($link,"UPDATE user SET name='$companyname',email='$official_email',phone='$official_phone',addr1='$cdesc',username='$uname',password='$encrypt' WHERE userid ='$id'")or die(mysqli_error($link)); 
	//$update = mysqli_query($link,"UPDATE maintenance_history SET loan_booking = '$lbooking', tcharges_type = '$tcharges_type', t_charges = '$t_charges', capped_amt = '$capped_amt', billing_type = '$billtype' WHERE company_id = '$vendorID'");
	$update = mysqli_query($link,"UPDATE vendor_reg SET cname='$companyname',ctype = '$ctype',cdesc='$cdesc',caddrs='$caddrs',cemail='$official_email',cphone='$official_phone',cusername='$uname',cpassword='$encrypt',currency='$currency',m_subaccount='$m_subaccount',account_number='$account_number',bankname='$bankname' WHERE companyid = '$vendorID'");
	if(!$update)
	{
	echo '<meta http-equiv="refresh" content="2;url=view_vendor.php?tid='.$id.'&&mid='.base64_encode("901").'">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Unable to update vendor records!</span>';
	}
	else{
		include("alert_sender/edit_emp_alert.php");
		echo '<meta http-equiv="refresh" content="2;url=view_vendor.php?id='.$id.'&&mid='.base64_encode("901").'">';
		echo '<br>';
		echo'<span class="itext" style="color: #FF0000">Updating Vendor.....Please Wait!</span>';
	}	
}
else{
	$target_dir = "../img/";
	$target_file = $target_dir.basename($_FILES["image"]["name"]);
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$check = getimagesize($_FILES["image"]["tmp_name"]);
	
	if($check == false)
	{
		echo '<meta http-equiv="refresh" content="2;url=view_vendor.php?tid='.$id.'&&mid='.base64_encode("901").'">';
		echo '<br>';
		echo'<span class="itext" style="color: #FF0000">Invalid file type</span>';
	}
	elseif($_FILES["image"]["size"] > 500000)
	{
		echo '<meta http-equiv="refresh" content="2;url=view_vendor.php?tid='.$id.'&&mid='.base64_encode("901").'">';
		echo '<br>';
		echo'<span class="itext" style="color: #FF0000">Image must not more than 500KB!</span>';
	}
	elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
	{
		echo '<meta http-equiv="refresh" content="2;url=view_vendor.php?tid='.$id.'&&mid='.base64_encode("901").'">';
		echo '<br>';
		echo'<span class="itext" style="color: #FF0000">Sorry, only JPG, JPEG, PNG & GIF Files are allowed.</span>';
	}
	else{
		$sourcepath = $_FILES["image"]["tmp_name"];
		$targetpath = "../img/" . $_FILES["image"]["name"];
		move_uploaded_file($sourcepath,$targetpath);

		$location = $_FILES['image']['name'];
	
		$update = mysqli_query($link,"UPDATE user SET name='$companyname',email='$official_email',phone='$official_phone',addr1='$cdesc',username='$uname',password='$encrypt',image='$location' WHERE userid ='$id'")or die(mysqli_error($link)); 
		//$update = mysqli_query($link,"UPDATE maintenance_history SET loan_booking = '$lbooking', tcharges_type = '$tcharges_type', t_charges = '$t_charges', capped_amt = '$capped_amt', billing_type = '$billtype' WHERE company_id = '$vendorID'");
		$update = mysqli_query($link,"UPDATE vendor_reg SET cname='$companyname',ctype = '$ctype',cdesc='$cdesc',caddrs='$caddrs',cemail='$official_email',cphone='$official_phone',cusername='$uname',cpassword='$encrypt',currency='$currency',logo='$location',m_subaccount='$m_subaccount',account_number='$account_number',bankname='$bankname' WHERE companyid = '$vendorID'");
		
		if(!$update)
		{
			echo '<meta http-equiv="refresh" content="2;url=view_vendor.php?tid='.$id.'&&mid='.base64_encode("901").'">';
			echo '<br>';
			echo'<span class="itext" style="color: #FF0000">Unable to update vendor records!</span>';
		}
		else{
			//include("alert_sender/edit_emp_alert.php");
			echo '<meta http-equiv="refresh" content="2;url=view_vendor.php?id='.$id.'&&mid='.base64_encode("901").'">';
			echo '<br>';
			echo'<span class="itext" style="color: #FF0000">Updating Vendor.....Please Wait!</span>';
		}
	}
}
?>
</div>
</body>
</html>