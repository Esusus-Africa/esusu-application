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
if(isset($_POST['submit']))
{
	$id = mysqli_real_escape_string($link, $_POST['id']);
	$tto = mysqli_real_escape_string($link, $_POST['tto']);
	$transfer = mysqli_query($link, "UPDATE borrowers SET branchid = '$id' WHERE id = '$id'");
	if($transfer)
	{
		echo '<meta http-equiv="refresh" content="5;url=customer.php?id='.$_SESSION['tid'].'&&mid=NDAz">';
		echo '<br>';
		echo '<span class="itext" style="color: blue">Customer Transferred Successfully!</span>';
	}else{
		echo '<meta http-equiv="refresh" content="5;url=index.php">';
		echo '<br>';
		echo'<span class="itext" style="color: orange">Error...Please Try Again Later!!</span>';
	}
}
?>
</div>
</body>
</html>