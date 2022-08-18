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
if(isset($_POST['ccat']))
{
$pcat =  mysqli_real_escape_string($link, $_POST['pcat']);
	
	$insert = mysqli_query($link, "INSERT INTO campaign_cat VALUES('','$pcat')") or die (mysqli_error($link));
	if(!$insert)
	{
	echo '<meta http-equiv="refresh" content="2;url=c_category.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode('421').'&&tab=tab_2">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Unable to Add Campaign Category</span>';
	}
	else{
	echo '<meta http-equiv="refresh" content="2;url=c_category.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode('421').'&&tab=tab_2">';
	echo '<br>';
	echo'<span class="itext" style="color: #FF0000">Creating Campaign Category.....Please Wait!</span>';
	}
}
?>
</div>
</body>
</html>