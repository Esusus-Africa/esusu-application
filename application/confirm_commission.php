<?php include "../config/session.php"; ?>  

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
$id = $_GET['id'];
$referral_id = $_GET['referral'];
$c_tdate = date("d/m/Y");

$update = mysqli_query($link, "UPDATE referral_records SET pstatus = 'Paid' WHERE upline_id = '$referral_id'") or die (mysqli_error($link));
$update = mysqli_query($link, "UPDATE referral_incomehistory SET status = 'Paid' WHERE id = '$id' AND status = 'Pending'");

if(!$update)
{
echo '<meta http-equiv="refresh" content="2;url=view_all_referral.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
echo '<br>';
echo'<span class="itext" style="color: #FF0000">Unable to confirm bonus.....Please try again later!</span>';
}
else{
echo '<meta http-equiv="refresh" content="2;url=view_all_referral.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
echo '<br>';
echo'<span class="itext" style="color: #FF0000">Bonus Confirmed.....Please Wait!</span>';
}
?>
</div>
</body>
</html>