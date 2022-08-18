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

$agentid = $_GET['id'];

$search_agent = mysqli_query($link, "SELECT * FROM agent_data WHERE agentid = '$agentid'");
$fetch_agent = mysqli_fetch_object($search_agent);
$email = $fetch_agent->email;
$fname = $fetch_agent->fname;
$username = $fetch_agent->username;
$password = $fetch_agent->upassword;

$search_auserid = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$agentid'");
$fetch_auserid = mysqli_fetch_object($search_auserid);

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

$shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/complete_reg.php?a_key='.$agentid.'&&sid='.$fetch_auserid->id;
	
$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));		
$r = mysqli_fetch_object($query);
$sys_abb = $r->abb;
$sys_email = $r->email;

include("../cron/send_agent_regemail.php");

echo '<meta http-equiv="refresh" content="2;url=listagents.php?tid='.$_SESSION['tid'].'&&mid=NDQw">';
echo '<br>';
echo'<span class="itext" style="color: blue;">Email Sent Successfully!</span>';

?>
</div>
</body>
</html>