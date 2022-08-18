<?php
// Establish Connection with MYSQL
include("../config/connect.php");

$number = count($_POST["name"]);
if($number > 1)
{
	for($i=0; $i<$number; $i++)
	{
		if(trim($_POST['name'][$i]) != '')
		{
			$sql = mysqli_query($link, "INSERT INTO installed_module VALUES(null,'".mysqli_real_escape_string($link, $_POST['name'][$i])."')");

		}
	}
	echo "Data Inserted!";
}
else{
	echo "Enter Name";
}
?>