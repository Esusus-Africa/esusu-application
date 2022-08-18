<?php 
error_reporting(0);
session_start();
include "config/connect.php";

$mysystem_config = mysqli_query($link, "SELECT * FROM systemset");
$fetchsys_config = mysqli_fetch_array($mysystem_config);
?>

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
<img src="<?php echo $fetchsys_config['file_baseurl']; ?>electronic esusu gif.gif" width="100px;" height="100px;">
<?php
echo '<meta http-equiv="refresh" content="2;url=application/dashboard?tid='.$_SESSION['tid'].'">';
echo '<br>';
echo'<span class="itext" style="color: #0073b7">Logging In. Please Wait!...</span>';
?>
</div>
</body>
</html>
