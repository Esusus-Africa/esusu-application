<?php 
error_reporting(0); 
include "../config/session.php";
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

$uid = $_GET['uid'];
$search_c = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$uid'");
$mycnum = mysqli_num_rows($search_c);
  
$search_myi = mysqli_query($link, "SELECT * FROM user WHERE id = '$uid'");
$myinum = mysqli_num_rows($search_myi);

$todays_date = date('Y-m-d');
$next_termination_date = date('Y-m-d', strtotime('+35 day', strtotime($todays_date)));

($mycnum == 1 && $myinum == 0) ? $insert = mysqli_query($link,"UPDATE borrowers SET acct_status = 'Closed', last_withdraw_date = '$next_termination_date' WHERE account ='$uid'") : "";
($mycnum == 0 && $myinum == 1) ? $insert = mysqli_query($link,"UPDATE user SET comment = 'Closed' WHERE id ='$uid'") : "";
    
if(!$insert)
{
   	echo '<meta http-equiv="refresh" content="2;url=listWallet.php?tid='.$_SESSION['tid'].'">';
   	echo '<br>';
   	echo'<span class="itext" style="color: #FF0000">Unable to close wallet!</span>';
}
else{
   	echo '<meta http-equiv="refresh" content="2;url=listWallet.php?tid='.$_SESSION['tid'].'">';
   	echo '<br>';
   	echo'<span class="itext" style="color: #FF0000">Wallet Closed Successfully!</span>';
}
?>
</div>
</body>
</html>