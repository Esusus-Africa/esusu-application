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
	$idm = $_GET['idm'];
$ptitle =  mysqli_real_escape_string($link, $_POST['ptitle']);
$pdesc =  mysqli_real_escape_string($link, $_POST['pdesc']);
$cmsg =  mysqli_real_escape_string($link, $_POST['cmsg']);
$thandler =  mysqli_real_escape_string($link, $_POST['thandler']);
$clocation =  mysqli_real_escape_string($link, $_POST['location']);
$budget =  number_format(mysqli_real_escape_string($link, $_POST['budget']),2,'.','');
$dfrom =  mysqli_real_escape_string($link, $_POST['dfrom']);
$dto =  mysqli_real_escape_string($link, $_POST['dto']);
$campaign_fee =  mysqli_real_escape_string($link, $_POST['campaign_fee']);
$ctype =  "Donation";
$tname =  mysqli_real_escape_string($link, $_POST['tname']);
$designation =  mysqli_real_escape_string($link, $_POST['designation']);
$aboutus =  mysqli_real_escape_string($link, $_POST['aboutus']);

$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
if($image == "")
{	
	$update = mysqli_query($link, "UPDATE causes SET campaign_title='$ptitle', campaign_desc='$pdesc', budget='$budget', campaign_fee='$campaign_fee', msg_to_donor='$cmsg', twitter_handler='$thandler', location='$clocation', dfrom='$dfrom', dto='$dto', tname='$tname', designation='$designation', aboutus='$aboutus' WHERE id = '$idm'") or die (mysqli_error($link));
	
	if(!$update)
	{
	echo '<meta http-equiv="refresh" content="2;url=updatecampaign.php?tid='.$_SESSION['tid'].'&&mid=NDIx">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Unable to Edit Campaign!</span>';
	}
	else{
	echo '<meta http-equiv="refresh" content="2;url=listcampaign.php?tid='.$_SESSION['tid'].'&&mid=NDIx&&tab=tab_1">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Updating Campaign.....Please Wait!</span>';
	}
}
else{
$target_dir = "../img/";
$target_file = $target_dir.basename($_FILES["image"]["name"]);
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$check = getimagesize($_FILES["image"]["tmp_name"]);

if($check == false)
{
	echo '<meta http-equiv="refresh" content="2;url=updatecampaign.php?tid='.$_SESSION['tid'].'&&mid=NDIx">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Invalid file type</span>';
}
elseif($_FILES["image"]["size"] > 500000)
{
	echo '<meta http-equiv="refresh" content="2;url=updatecampaign.php?tid='.$_SESSION['tid'].'&&mid=NDIx">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Image must not more than 500KB!</span>';
}
elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
{
	echo '<meta http-equiv="refresh" content="2;url=updatecampaign.php?tid='.$_SESSION['tid'].'&&mid=NDIx">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Sorry, only JPG, JPEG, PNG & GIF Files are allowed.</span>';
}
else{
	$sourcepath = $_FILES["image"]["tmp_name"];
	$targetpath = "../img/" . $_FILES["image"]["name"];
	move_uploaded_file($sourcepath,$targetpath);
	
	$location = "img/".$_FILES['image']['name'];
	
	$update = mysqli_query($link, "UPDATE causes SET campaign_image='$location', campaign_title='$ptitle', campaign_desc='$pdesc', budget='$budget', campaign_fee='$campaign_fee', msg_to_donor='$cmsg', twitter_handler='$thandler', location='$clocation', dfrom='$dfrom', dto='$dto', tname='$tname', designation='$designation', aboutus='$aboutus' WHERE id = '$idm'") or die (mysqli_error($link));
	
	if(!$update)
	{
	echo '<meta http-equiv="refresh" content="2;url=updatecampaign.php?tid='.$_SESSION['tid'].'&&mid=NDIx">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Unable to Edit Campaign!</span>';
	}
	else{
	echo '<meta http-equiv="refresh" content="2;url=listcampaign.php?tid='.$_SESSION['tid'].'&&mid=NDIx&&tab=tab_1">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Updating Campaign.....Please Wait!</span>';
	}
}
}
}
?>
</div>
</body>
</html>