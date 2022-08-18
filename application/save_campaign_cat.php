<?php
// Establish Connection with MYSQL
include("../config/connect.php");

$number = count($_POST["mycat_name"]);
if($number > 1)
{
	for($i=0; $i<$number; $i++)
	{
		if(trim($_POST['mycat_name'][$i]) != '')
		{
			$sql = mysqli_query($link, "INSERT INTO campaign_category VALUES(null,'".mysqli_real_escape_string($link, $_POST['mycat_name'][$i])."')");

		}
	}
	echo "Data Inserted!";
}
else{
	echo "Enter Name";
}
?>