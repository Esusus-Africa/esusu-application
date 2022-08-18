<?php
$akey = $_GET["activation_key"];
$resultk = mysqli_query($link, "SELECT * FROM activate_member WHERE shorturl='$akey' AND attempt='No'") or die ("Error: " . mysqli_error($link));
if(mysqli_num_rows($resultk) == 1)
{
	$updatek = mysqli_query($link, "UPDATE activate_member SET attempt='Yes' WHERE shorturl='$akey'") or die ("Error: " . mysqli_error($link));
	$rowk = mysqli_fetch_array($resultk);
	//$real_url = $rowk['url'];
	$updatekk = mysqli_query($link, "UPDATE borrowers SET acct_status = 'Activated' WHERE account = '$acn'") or die ("Error: " . mysqli_error($link));
	$acn = $rowk['acn'];
	echo "<script>alert('Account Activated Successfully!'); </script>";	
	//header("location:".$real_url);	
}
else{
		echo "<script>alert('Oops! Your Account has been Activated Already'); </script>";
	}
}
?>