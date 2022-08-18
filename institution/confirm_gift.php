<?php include "../config/session.php"; ?>  

<!DOCTYPE html>
<html>
<head>

<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid black;
  border-right: 16px solid green;
  border-bottom: 16px solid yellow;
  border-left: 16px solid green;
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
$id = $_GET['id'];
$g_tdate = date("d/m/Y");

$update = mysqli_query($link, "UPDATE referral_records SET g_status = 'confirm', g_tdate = '$g_tdate' WHERE id = '$id'") or die (mysqli_error($link));

if(!$update)
{
echo '<meta http-equiv="refresh" content="2;url=view_all_referral.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
echo '<br>';
echo'<span class="itext" style="color: #FF0000">Unable to confirm gift.....Please try again later!</span>';
}
else{
echo '<meta http-equiv="refresh" content="2;url=view_all_referral.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
echo '<br>';
echo'<span class="itext" style="color: #FF0000">Gift Confirmed.....Please Wait!</span>';
}
?>
</div>
</body>
</html>