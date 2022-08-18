<?php
// Establish Connection with MYSQL
include("../config/connect.php");

$number = count($_POST["myteam_name"]);
if($number > 1)
{
	for($i=0; $i<$number; $i++)
	{
		if(trim($_POST['myteam_name'][$i]) != '')
		{
			$sql = mysqli_query($link, "INSERT INTO team_category VALUES(null,'".mysqli_real_escape_string($link, $_POST['myteam_name'][$i])."')");

		}
	}
	echo "Data Inserted!";
}
else{
	echo "Enter Category";
}
?>