<?php 
error_reporting(0); 
include "../config/session.php";
?>  
<!DOCTYPE html>
<html>
<head>

<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid blue;
  border-right: 16px solid green;
  border-bottom: 16px solid red;
  border-left: 16px solid pink;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
  margin:auto;
  
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
		<div class="loader"></div>
<?php
$title = mysqli_real_escape_string($link, $_POST['title']);
$username = mysqli_real_escape_string($link, $_POST['username']);
$password = mysqli_real_escape_string($link, $_POST['password']);
$myapi = mysqli_real_escape_string($link, $_POST['myapi']);
$status = mysqli_real_escape_string($link, $_POST['status']);

$sql = mysqli_query($link, "INSERT INTO sms VALUE(null,'$title','$username','$password','$myapi','$status','$branchid')") or die(mysqli_error($link));
if(!$sql){
echo '<meta http-equiv="refresh" content="2;url=sms.php?id='.$_SESSION['tid'].'&&mid=NDA1">';
echo '<br>';
echo'<span class="itext" style="color: #FF0000">Unable to Add SMS Settings!...please try again later</span>';
}
else{
echo '<meta http-equiv="refresh" content="2;url=sms.php?id='.$_SESSION['tid'].'&&mid=NDA1">';
echo '<br>';
echo'<span class="itext" style="color: green;">SMS Settings Configured successfully!</span>';
}
?>