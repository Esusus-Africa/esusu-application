<?php 
session_start(); 
include("config/connect.php");

$myid = (isset($_GET['id']) == true) ? $_GET['id'] : '';
$call_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$myid'");
$fetch_msmset = mysqli_fetch_array($call_memset);

$mysystem_config = mysqli_query($link, "SELECT * FROM systemset");
$fetchsys_config = mysqli_fetch_array($mysystem_config);

$mycookies = $_COOKIE['PHPSESSID'];
$myuserid = $_SESSION['tid'];
$date_time = date("Y-m-d h:i:s");

mysqli_query($link, "UPDATE session_tracker SET loginStatus = 'Off', LastVisitDateTime = '$date_time' WHERE userid = '$myuserid' AND browserSession = '$mycookies'");
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
<img src="<?php echo ($fetch_msmset['logo'] == "" || $fetch_msmset['logo'] == "img/") ? $fetchsys_config['file_baseurl'].'electronic esusu gif.gif' : $fetchsys_config['file_baseurl'].$fetch_msmset['logo']; ?>" width="100px;" height="100px;">
<?php

session_destroy();

$id = (isset($_GET['id'])) ? $_GET['id'] : '';

$search_sid = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$id'");
if(mysqli_num_rows($search_sid) == 0)
{
    echo '<meta http-equiv="refresh" content="2;url=account/index">';
    echo '<br>';
    echo'<span class="itext" style="color: #0073b7">Logging Out. Please Wait!...</span>';
}
else{
    $fetch_sid = mysqli_fetch_object($search_sid);
    $senderid = $fetch_sid->sender_id;
    echo '<meta http-equiv="refresh" content="2;url=/'.$senderid.'">';
    echo '<br>';
    echo'<span class="itext" style="color: #0073b7">Logging Out. Please Wait!...</span>';
}

?>
</div>
</body>
</html>
