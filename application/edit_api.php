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
if(isset($_POST['submit'])){
  $gateway_name = mysqli_real_escape_string($link, $_POST['gateway_name']);
  $username = mysqli_real_escape_string($link, $_POST['username']);
  $password = mysqli_real_escape_string($link, $_POST['password']);
  $api = mysqli_real_escape_string($link, $_POST['api']);
  $status = mysqli_real_escape_string($link, $_POST['status']);

  if($arole == "agent_manager" || $arole = "i_a_demo")
  {
  $sql = mysqli_query($link, "UPDATE sms SET sms_gateway = '$gateway_name', username = '$username', password = '$password', api = '$api', status = '$status' WHERE smsuser = '$session_id'") or die(mysqli_error($link));
  if(!$sql){
  echo '<meta http-equiv="refresh" content="2;url=sms.php?id='.$_SESSION['tid'].'&&mid=NDEx">';
  echo '<br>';
  echo'<span class="itext" style="color: #FF0000">Unable to Update SMS Settings!...please try again later</span>';
  }
  else{
  echo '<meta http-equiv="refresh" content="2;url=sms.php?id='.$_SESSION['tid'].'&&mid=NDEx">';
  echo '<br>';
  echo'<span class="itext" style="color: green;">SMS Settings Update successfully!</span>';
  }
}
}

if(isset($_POST['submit2'])){
     $gateway_name = mysqli_real_escape_string($link, $_POST['gateway_name']);
     $username = mysqli_real_escape_string($link, $_POST['username']);
     $password = mysqli_real_escape_string($link, $_POST['password']);  
     $api = mysqli_real_escape_string($link, $_POST['api']);
     $status = mysqli_real_escape_string($link, $_POST['status']);
     
   $sql = mysqli_query($link, "UPDATE sms SET sms_gateway = '$gateway_name', username = '$username', password = '$password', api = '$api', status = '$status' WHERE smsuser = ''") or die(mysqli_error($link));
  if(!$sql){
  echo '<meta http-equiv="refresh" content="2;url=sms.php?id='.$_SESSION['tid'].'&&mid=NDEx">';
  echo '<br>';
  echo'<span class="itext" style="color: #FF0000">Unable to Update SMS Settings!...please try again later</span>';
  }
  else{
  echo '<meta http-equiv="refresh" content="2;url=sms.php?id='.$_SESSION['tid'].'&&mid=NDEx">';
  echo '<br>';
  echo'<span class="itext" style="color: green;">SMS Settings Update successfully!</span>';
  }
}

if(isset($_POST['save'])){
  $gateway_name = mysqli_real_escape_string($link, $_POST['gateway_name']);
  $username = mysqli_real_escape_string($link, $_POST['username']);
  $password = mysqli_real_escape_string($link, $_POST['password']);
  $api = mysqli_real_escape_string($link, $_POST['api']);
  $status = mysqli_real_escape_string($link, $_POST['status']);

  if($arole == "agent_manager" || $arole = "i_a_demo")
  {
  $sql = mysqli_query($link, "INSERT INTO sms VALUES(null,'$gateway_name','$username','$password','$api','$status','$session_id')") or die(mysqli_error($link));
  if(!$sql){
  echo '<meta http-equiv="refresh" content="2;url=sms.php?id='.$_SESSION['tid'].'&&mid=NDEx">';
  echo '<br>';
  echo'<span class="itext" style="color: #FF0000">Error!...please try again later</span>';
  }
  else{
  echo '<meta http-equiv="refresh" content="2;url=sms.php?id='.$_SESSION['tid'].'&&mid=NDEx">';
  echo '<br>';
  echo'<span class="itext" style="color: green;">SMS Settings Added successfully!</span>';
  }
}
}

if(isset($_POST['save2'])){
  $gateway_name = mysqli_real_escape_string($link, $_POST['gateway_name']);
  $username = mysqli_real_escape_string($link, $_POST['username']);
  $password = mysqli_real_escape_string($link, $_POST['password']);
  $api = mysqli_real_escape_string($link, $_POST['api']);
  $status = mysqli_real_escape_string($link, $_POST['status']);
  $sql = mysqli_query($link, "INSERT INTO sms VALUES(null,'$gateway_name','$username','$password','$api','$status','')") or die(mysqli_error($link));
  if(!$sql){
  echo '<meta http-equiv="refresh" content="2;url=sms.php?id='.$_SESSION['tid'].'&&mid=NDEx">';
  echo '<br>';
  echo'<span class="itext" style="color: #FF0000">Error!...please try again later</span>';
  }
  else{
  echo '<meta http-equiv="refresh" content="2;url=sms.php?id='.$_SESSION['tid'].'&&mid=NDEx">';
  echo '<br>';
  echo'<span class="itext" style="color: green;">SMS Settings Added successfully!</span>';
  }
}
?>