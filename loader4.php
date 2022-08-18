<?php 
error_reporting(0);
session_start();
include "config/connect.php";

$merch_id = $_GET['merch_id'];
$call_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$merch_id'");
$fetch_msmset = mysqli_fetch_array($call_memset);
?>

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
<img src="<?php echo ($fetch_msmset['logo'] == "" || $fetch_msmset['logo'] == "img/") ? 'img/electronic esusu gif.gif' : $fetch_msmset['logo']; ?>" width="100px;" height="100px;">
<?php
echo '<meta http-equiv="refresh" content="2;url=merchant/dashboard.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode('401').'">';
echo '<br>';
echo'<span class="itext" style="color: #0073b7">Logging In. Please Wait!...</span>';
?>
</div>
</body>
</html>
