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
if(isset($_POST['cbutton']))
{
$author =  mysqli_real_escape_string($link, $_POST['author']);
$ptitle =  mysqli_real_escape_string($link, $_POST['ptitle']);
$pdesc =  mysqli_real_escape_string($link, $_POST['pdesc']);
$cmsg =  mysqli_real_escape_string($link, $_POST['cmsg']);
$c_cat = mysqli_real_escape_string($link, $_POST['c_cat']);
$thandler =  mysqli_real_escape_string($link, $_POST['thandler']);
$clocation =  mysqli_real_escape_string($link, $_POST['location']);
$budget =  number_format(mysqli_real_escape_string($link, $_POST['budget']),2,'.','');
$dfrom =  mysqli_real_escape_string($link, $_POST['dfrom']);
$dto =  mysqli_real_escape_string($link, $_POST['dto']);
$ctype =  "Lend";
$cpid = "#".rand(1000000,9999999);

$target_dir = "../img/";
$target_file = $target_dir.basename($_FILES["image"]["name"]);
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$check = getimagesize($_FILES["image"]["tmp_name"]);

if($check == false)
{
	echo '<meta http-equiv="refresh" content="2;url=add_campaign.php?tid='.$id.'&&mid='.base64_encode("750").'">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Invalid file type</span>';
}
elseif($_FILES["image"]["size"] > 500000)
{
	echo '<meta http-equiv="refresh" content="2;url=add_campaign.php?tid='.$id.'&&mid='.base64_encode("750").'">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Image must not more than 500KB!</span>';
}
elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
{
	echo '<meta http-equiv="refresh" content="2;url=add_campaign.php?tid='.$id.'&&mid='.base64_encode("750").'">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Sorry, only JPG, JPEG, PNG & GIF Files are allowed.</span>';
}
else{
	$sourcepath = $_FILES["image"]["tmp_name"];
	$targetpath = "../img/" . $_FILES["image"]["name"];
	move_uploaded_file($sourcepath,$targetpath);
	
	$location = "img/".$_FILES['image']['name'];
	
	foreach($_FILES['uploaded_file']['name'] as $key => $name){

    $newFilename = $name;
    move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], 'img/'.$newFilename);
    $finalfile = '../img/'.$newFilename;

    $insert_record = mysqli_query($link, "INSERT INTO campaign_documents VALUES(null,'$cpid','$finalfile')");
    
    }
	
	$insert = mysqli_query($link, "INSERT INTO campaign VALUES(null,'$author','$location','$ptitle','$pdesc','0.00','$budget','0','$ctype','$c_cat','$cmsg','$thandler','$clocation','$dfrom','$dto','Pending','$cpid','')") or die (mysqli_error($link));
	
	if(!$insert)
	{
	echo '<meta http-equiv="refresh" content="2;url=add_campaign.php?tid='.$_SESSION['tid'].'&&mid=NzUw">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Unable to Create Campaign!</span>';
	}
	else{
	echo '<meta http-equiv="refresh" content="2;url=campaign_list.php?tid='.$_SESSION['tid'].'&&mid=NzUw&&tab=tab_1">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Campaign Created Successfully!.....<p>Kindly urge the Customer to wait patiently for the Approval and Welcome Email once Reviewed!!!</span>';
	}
}
}
?>
</div>
</body>
</html>