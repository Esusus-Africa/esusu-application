<?php
// Establish Connection with MYSQL
include("../config/connect.php");
if(isset($_POST['item_name']))
{
	for($i=0; $i < count($_POST['item_name']); $i++)
	{
		$rname = $_POST['item_name'][$i];
		$image_name = $_FILES['image_name']['name'][$i];
		
		$sourcepath = $_FILES["image_name"]["tmp_name"][$i];
	    $targetpath = "../lend/images/home/" . $_FILES["image_name"]["name"][$i];
	    move_uploaded_file($sourcepath,$targetpath);
	    
	    
	    
	    $insert = mysqli_query($link, "INSERT INTO campaign_region VALUES(null,'$image_name','$rname')");
	}
	echo "ok";
}
?>