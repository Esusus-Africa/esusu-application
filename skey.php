<?php
session_start();
error_reporting(E_ALL);
include "config/connect.php";

if(isset($_GET['mky'])== true)
{
	$mky = $_GET['mky'];
	
	$search = mysqli_query($link, "SELECT * FROM skey");
	$num = mysqli_num_rows($search);
	
	if($mky == 'Locked' && $num != 1)
	{
		
		$activate_status = mysqli_query($link, "INSERT INTO skey VALUES('','Locked')") or die ("Error: " . mysqli_error($link));
		echo "<script>alert('Site Locked Successfully!'); </script>";
		echo "<script>window.location='index.php'; </script>";
	}
	elseif($mky == 'Locked' && $num == 1)
	{
		
		$activate_status = mysqli_query($link, "UPDATE skey SET status='Locked'") or die ("Error: " . mysqli_error($link));
		echo "<script>alert('Site Locked Successfully!'); </script>";
		echo "<script>window.location='index.php'; </script>";
	}
	elseif($mky == 'Unlocked'){
		$deactivate_status = mysqli_query($link, "UPDATE skey SET status='Unlocked'") or die ("Error: " . mysqli_error($link));
		echo "<script>alert('Site Unlocked Successfully!'); </script>";
		echo "<script>window.location='index.php'; </script>";
	}
}
?>