<?php include "../config/session1.php"; ?>  

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
		<div class="loader"></div>

<?php
if(isset($_POST['save']))
{
	$rname =  mysqli_real_escape_string($link, $_POST['rname']);
	$mname =  mysqli_real_escape_string($link, $_POST['mname']);

	if($mname == 'All')
	{
		$update_table = "INSERT INTO my_permission (id,companyid,urole) VALUES(null, :companyid, :urole)";
		$statement = $connect->prepare($update_table);
		$statement->execute(
			array(
				':companyid' => $institution_id,
				':urole' => $rname
			)
		);
		$result = $statement->fetchAll();
		for($i=0; $i < count($_POST['pname']); $i++)
		{
			$pname = $_POST['pname'][$i];
			$query = mysqli_query($link, "UPDATE my_permission SET $pname = '1' WHERE urole = '$rname' AND companyid = '$institution_id'");
		}	
		if(isset($result))
		{
			echo '<meta http-equiv="refresh" content="2;url=access_permission.php?id='.$_SESSION['tid'].'&&mid=NDEz">';
			echo '<br>';
			echo'<span class="itext" style="color: orange">Permission Set Successfully!</span>';
		}
		else{
			echo '<meta http-equiv="refresh" content="2;url=access_permission.php?id='.$_SESSION['tid'].'&&mid=NDEz">';
			echo '<br>';
			echo'<span class="itext" style="color: orange">Error....Please try again later!</span>'.mysqli_error($link);
		}
		
	}
	else{
		$update_table = "INSERT INTO my_permission (id,companyid,urole) VALUES(null, :companyid, :urole)";
		$statement = $connect->prepare($update_table);
		$statement->execute(
			array(
				':companyid' => $institution_id,
				':urole' => $rname
			)
		);
		$result = $statement->fetchAll();
		for($i=0; $i < count($_POST['pname']); $i++)
		{
			$pname = $_POST['pname'][$i];
			$query = mysqli_query($link, "UPDATE my_permission SET $pname = '1' WHERE $pname = '' AND urole = '$rname' AND companyid = '$institution_id'");
		}
		if(isset($result))
		{
			echo '<meta http-equiv="refresh" content="2;url=access_permission.php?id='.$_SESSION['tid'].'&&mid=NDEz">';
			echo '<br>';
			echo'<span class="itext" style="color: orange">Permission Set Successfully!</span>';
		}
		else{
			echo '<meta http-equiv="refresh" content="2;url=access_permission.php?id='.$_SESSION['tid'].'&&mid=NDEz">';
			echo '<br>';
			echo'<span class="itext" style="color: orange">Error....Please try again later!</span>'.mysqli_error($link);
		}
	}
}
?>
</div>
</body>
</html>