<?php
// Establish Connection with MYSQL
include("../config/connect.php");
if(isset($_POST['item_name1']))
{
	for($i=0; $i < count($_POST['item_name1']); $i++)
	{
		$mproperty = $_POST['item_name1'][$i];
		$alter_table = "ALTER TABLE my_permission2 ADD $mproperty varchar(1) NOT NULL";
		$connect->query($alter_table);
		
		$update_table = "UPDATE my_permission2 SET $mproperty = '1' WHERE urole = 'super_admin'";
		$connect->query($update_table);

		$query = "INSERT INTO module_property (id, mname, mproperty, mtype) VALUES(null, :mname, :mproperty, :mtype)";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':mname' => $_POST['mobule_name1'][$i],
                ':mproperty' => $_POST['item_name1'][$i],
                ':mtype' => $_POST['item_type1'][$i]
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