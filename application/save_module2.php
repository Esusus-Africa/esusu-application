<?php
// Establish Connection with MYSQL
include("../config/connect.php");
if(isset($_POST['item_name']))
{
	for($i=0; $i < count($_POST['item_name']); $i++)
	{
		$mproperty = $_POST['item_name'][$i];
		$alter_table = "ALTER TABLE my_permission ADD $mproperty varchar(1) NOT NULL";
		$connect->query($alter_table);

		$query = "INSERT INTO module_property (id, mname, mproperty, mtype) VALUES(null, :mname, :mproperty, :mtype)";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':mname' => $_POST['mobule_name'][$i],
				':mproperty' => $_POST['item_name'][$i],
				':mtype' => $_POST['item_type'][$i]
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