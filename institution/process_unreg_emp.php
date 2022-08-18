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
if(isset($_POST['emp']))
{
$name =  mysqli_real_escape_string($link, $_POST['name']);
$email =  mysqli_real_escape_string($link, $_POST['email']);
$phone =  mysqli_real_escape_string($link, $_POST['phone']);
$addr1 =  mysqli_real_escape_string($link, $_POST['addr1']);
$addr2 =  mysqli_real_escape_string($link, $_POST['addr2']);
$city =  mysqli_real_escape_string($link, $_POST['city']);
$state =  mysqli_real_escape_string($link, $_POST['state']);
$zip =  mysqli_real_escape_string($link, $_POST['zip']);
$country =  mysqli_real_escape_string($link, $_POST['country']);
$branch =  mysqli_real_escape_string($link, $_POST['branch']);
$id = "UNMEM".rand(10000000,340000000);
	
	$insert = mysqli_query($link, "INSERT INTO user VALUES(null,'$name','$email','$phone','$addr1','$addr2','$city','$state','$zip','$country','','','','$id','','','$branch','Unregistered','$agentid','AG')") or die (mysqli_error($link));
	if(!$insert)
	{
	echo '<meta http-equiv="refresh" content="2;url=newpayroll.php?tid='.$_SESSION['tid'].'&&mid=NDIz">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Unable to register employee!</span>';
	}
	else{
	echo '<meta http-equiv="refresh" content="2;url=newpayroll.php?tid='.$_SESSION['tid'].'&&mid=NDIz">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Saving Staff Records.....Please Wait!</span>';
	}
}
?>
</div>
</body>
</html>