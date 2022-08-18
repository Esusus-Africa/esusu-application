<?php include "config/connect.php";?>

<!DOCTYPE html>
<html>
<head>

<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid blue;
  border-right: 16px solid green;
  border-bottom: 16px solid red;
  border-left: 16px solid pink;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
  margin:auto;
  
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
		<div class="loader"></div>
<?php
if(isset($_GET['refid']) == true)
{
	$acn = $_GET['acn'];
	$refid = $_GET['refid'];
	$amt = "500";
	$t_type = "Deposit";
	
	$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'") or die ("Error: " . mysqli_error($link));
	$get_buser = mysqli_fetch_object($search_user);
	//GET DATA TO POST
	$emaiL = $get_buser->email;
	$phone = $get_buser->phone;
	$fn = $get_buser->fname;
	$ln = $get_buser->lname;
	$bvn = $get_buser->unumber;
	$new_balance = $amt + $get_buser->balance;
	
	$insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$refid','$t_type','$acn','----','$fn','$ln','$emaiL','$phone','$amt',NOW(),'')") or die ("Error: " . mysqli_error($link));
	$update = mysqli_query($link, "UPDATE borrowers SET balance = '$new_balance' WHERE account = '$acn'") or die ("Error: " . mysqli_error($link));
	
	if($insert)
	{
		echo '<meta http-equiv="refresh" content="5;url=index.php">';
		echo '<br>';
		echo'<span class="itext" style="color: #FF0000">Sending Request. Please Wait!...</span>';
		echo "<script>alert('Account Created Successfully!..Your Application will be reviewed by our Staff for Authorization!!'); </script>";
	}
	else{
		echo '<meta http-equiv="refresh" content="5;url=savings.php?acn='.$acn.'">';
		echo '<br>';
		echo'<span class="itext" style="color: #FF0000">Transaction not Successful...Please try again later</span>';
	}
}
?>
</div>
</body>
</html>