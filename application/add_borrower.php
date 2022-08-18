<?php
include "../config/session.php"; 
$comment = "Borrower";
$idm = $_GET['id'];
$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$idm' AND community_role = 'Borrower'") or die ("Error: " . mysqli_error($link));
if(mysqli_num_rows($search_customer) == 1){
	echo "<script>alert('Sorry! You cannot apply for loan twice!!...Try to settle the ongoing loan before re-applying.'); </script>";	
	echo "<script>window.location='listborrowers.php?id=".$_SESSION['tid']."&&mid=".base64_encode("403")."'; </script>";
}else{
	$update_customer = mysqli_query($link, "UPDATE borrowers SET community_role = '$comment' WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
	echo "<script>alert('Congratulation! Borrower has been added to the borrower list!!'); </script>";
	echo "<script>window.location='listborrowers.php?id=".$_SESSION['tid']."&&mid=".base64_encode("403")."'; </script>";
}
?>	