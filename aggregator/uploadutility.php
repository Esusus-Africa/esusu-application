<?php
include("../config/session.php");

if(isset($_FILES['utility_file']['name'])){

	/* Getting file name */
	$filename = $_FILES['utility_file']['name'];

	/* Location */
	$location = "../img/".$filename;
    $newlocation = $filename;
	$imageFileType = pathinfo($location,PATHINFO_EXTENSION);
	$imageFileType = strtolower($imageFileType);

	/* Valid extensions */
	$valid_extensions = array("jpg","jpeg","png","pdf","doc");

	$response = 0;
	/* Check file extension */
	if(in_array(strtolower($imageFileType), $valid_extensions)) {
	   	/* Upload file */
	   	if(move_uploaded_file($_FILES['utility_file']['tmp_name'],$location)){
            mysqli_query($link, "INSERT INTO attachment VALUES(null,'$aggr_id','$aggr_id','$aggr_id','$newlocation','UtilityBills','Pending',NOW())");
	     	$response = $location;
	   	}
	}

	echo $response;
	exit;
}

echo 0;

?>