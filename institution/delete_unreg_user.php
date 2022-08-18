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
$idm =  $_GET['idm'];
$delete = mysqli_query($link, "DELETE FROM user WHERE userid = '$idm'") or die (mysqli_error($link));
if(!$delete)
{
echo '<meta http-equiv="refresh" content="2;url=newpayroll.php?tid='.$_SESSION['tid'].'&&mid=NDIz">';
echo '<br>';
echo'<span class="itext" style="color: #FF0000">Unable to Delete employee records!</span>';
}
else{
echo '<meta http-equiv="refresh" content="2;url=newpayroll.php?tid='.$_SESSION['tid'].'&&mid=NDIz">';
echo '<br>';
echo'<span class="itext" style="color: #FF0000">Staff Records Delete Successfully!</span>';
}
?>
</div>
</body>
</html>