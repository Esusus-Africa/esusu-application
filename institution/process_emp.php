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
$name =  mysqli_real_escape_string($link, $_POST['name']);
$email =  mysqli_real_escape_string($link, $_POST['email']);
$phone =  mysqli_real_escape_string($link, $_POST['phone']);
$addr1 =  mysqli_real_escape_string($link, $_POST['addr1']);
$dept =  mysqli_real_escape_string($link, $_POST['dept']);
$city =  mysqli_real_escape_string($link, $_POST['city']);
$state =  mysqli_real_escape_string($link, $_POST['state']);
$zip =  mysqli_real_escape_string($link, $_POST['zip']);
$country =  mysqli_real_escape_string($link, $_POST['country']);
$username =  mysqli_real_escape_string($link, $_POST['username']);
$password =  mysqli_real_escape_string($link, $_POST['password']);
$cpaswword =  mysqli_real_escape_string($link, $_POST['cpassword']);

$final_role = mysqli_real_escape_string($link, $_POST['urole']);

$target_dir = "../img/";
$target_file = $target_dir.basename($_FILES["image"]["name"]);
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$check = getimagesize($_FILES["image"]["tmp_name"]);
$branch =  mysqli_real_escape_string($link, $_POST['branch']);

$encrypt = base64_encode($password);
$id = "MEM-".rand(10000000,340000000);
$type = "INS";

$verify_staff = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id'");
$fetch_staff = mysqli_num_rows($verify_staff);

if($password != $cpaswword)
{
echo "<script>alert('The 2 Password does not match!'); </script>";
}
elseif($fetch_staff == $istaff_limit)
{
	echo "<script>alert('Sorry, You can not add more than the limit assigned to you. Kindly Contact us for higher plan!'); </script>";
}
else{
	$sourcepath = $_FILES["image"]["tmp_name"];
	$targetpath = "../img/" . $_FILES["image"]["name"];
	move_uploaded_file($sourcepath,$targetpath);
	
	$location = "img/".$_FILES['image']['name'];
	
	$insert = mysqli_query($link, "INSERT INTO user VALUES(null,'$name','$email','$phone','$addr1','','$city','$state','$zip','$country','','$username','$encrypt','$id','$location','$final_role','','Registered','$institution_id','$type','0000','0.0','$dept','0.0')") or die (mysqli_error($link));
	//$insert = mysqli_query($link, "INSERT INTO user_role VALUES(null,'$id','$frole')");
    
	if(!$insert)
	{
    	echo '<meta http-equiv="refresh" content="2;url=newemployee.php?tid='.$_SESSION['tid'].'">';
    	echo '<br>';
    	echo'<span class="itext" style="color: #FF0000">Unable to register employee!</span>';
	}
	else{
	    include("alert_sender/emp_alert.php");
    	echo '<meta http-equiv="refresh" content="2;url=listemployee.php?tid='.$_SESSION['tid'].'">';
    	echo '<br>';
    	echo'<span class="itext" style="color: #FF0000">Saving Employee.....Please Wait!</span>';
	}
}
?>
</div>
</body>
</html>