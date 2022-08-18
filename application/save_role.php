<?php
// Establish Connection with MYSQL
include("../config/connect.php");

$number = count($_POST["role"]);
if($number > 1)
{
	for($i=0; $i<$number; $i++)
	{
		if(trim($_POST['role'][$i]) != '')
		{
			$sql = mysqli_query($link, "INSERT INTO global_role VALUES(null,'','".mysqli_real_escape_string($link, $_POST['role'][$i])."')");

		}
	}
	echo "Role Inserted!";
}
else{
	echo "Enter Role";
}
?>