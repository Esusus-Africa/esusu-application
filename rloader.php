<?php 
error_reporting(0);
include "config/connect.php";
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
<img src="img/electronic esusu gif.gif" width="100px;" height="100px;">
<?php
session_start();
echo '<meta http-equiv="refresh" content="2;url=ogsaa_revenue/dashboard.php?tid='.$_SESSION['tid'].'&&un='.$_SESSION['aun'].'">';
echo '<br>';
echo'<span class="itext" style="color: #0073b7">Logging IN. Please Wait!...</span>';
?>
</div>
</body>
</html>
