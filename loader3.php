<?php 
error_reporting(0);
session_start();
include "config/connect.php";
?>

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
<img src="img/electronic esusu gif.gif" width="100px;" height="100px;">
<?php
echo '<meta http-equiv="refresh" content="2;url=cooperative/dashboard.php?id='.$_SESSION['tid'].'&&mid='.base64_encode('401').'">';
echo '<br>';
echo'<span class="itext" style="color: #0073b7">Logging In. Please Wait!...</span>';
?>
</div>
</body>
</html>
