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

$instid = $_GET['id'];

$search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$instid'");
$fetch_inst = mysqli_fetch_object($search_inst);
$official_email = $fetch_inst->official_email;
$iname = $fetch_inst->institution_name;

$search_auserid = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$instid'");
$fetch_auserid = mysqli_fetch_object($search_auserid);

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

$shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/complete_instreg.php?a_key='.$instid.'&&dkey='.$fetch_auserid->id;
	
$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));		
$r = mysqli_fetch_object($query);
$sys_abb = $r->abb;
$sys_email = $r->email;

include("../cron/send_institution_regemail.php");

echo '<meta http-equiv="refresh" content="2;url=listinstitution.php?tid='.$_SESSION['tid'].'&&mid=NDE5">';
echo '<br>';
echo'<span class="itext" style="color: blue;">Email Sent Successfully!</span>';

?>
</div>
</body>
</html>