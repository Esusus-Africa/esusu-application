<?php
// Establish Connection with MYSQL
include("../config/connect.php");
if(isset($_POST['mobule_name2']))
{
	for($i=0; $i < count($_POST['mobule_name2']); $i++)
	{
		$mproperty = $_POST['mobule_name2'][$i];

		$query = "INSERT INTO module_pricing (id, mname, mtype) VALUES(null, :mname, :mtype)";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':mname' => $_POST['mobule_name2'][$i],
				':mtype' => $_POST['item_type2'][$i],
			)
		);
	}
	$result = $statement->fetchAll();
	if(isset($result))
	{
		echo 'ok';
	}
}
?>