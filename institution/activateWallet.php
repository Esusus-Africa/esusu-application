<?php 
error_reporting(0); 
include "../config/session1.php";
?>  
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
		<div class="loader"></div>
<?php
$acct_owner = $_GET['uid'];
$search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$acct_owner'");
$sRowNum = mysqli_num_rows($search_mystaff);
$fetch_mystaff = mysqli_fetch_array($search_mystaff);

$search_borro = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND account = '$acct_owner'");
$bRowNum = mysqli_num_rows($search_borro);
$fetch_borro = mysqli_fetch_array($search_borro);

$userStatus = ($sRowNum == 0 && $bRowNum == 1) ? "Activated" : "Approved";

($sRowNum == 0 && $bRowNum == 1) ? mysqli_query($link, "UPDATE borrowers SET acct_status = '$userStatus' WHERE account = '$acct_owner'") or die (mysqli_error($link)) : mysqli_query($link, "UPDATE user SET comment = '$userStatus' WHERE id = '$acct_owner'") or die (mysqli_error($link));
   
echo '<meta http-equiv="refresh" content="2;url=listWallet.php.php?tid='.$_SESSION['tid'].'&&mid=NDA0">';
echo '<br>';
echo'<span class="itext" style="color: #FF0000">Account Activated Successfully!!</span>';
?>
</div>
</body>
</html>