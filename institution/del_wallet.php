<?php include "../config/session.php"; ?>  

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
$id = $_GET['id'];
$wtype = $_GET['wtype'];
$tid = $_SESSION['tid'];
if($wtype == "credit")
{
	$select1 = mysqli_query($link, "SELECT Amount FROM mywallet WHERE id = '$id' AND wtype = '$wtype'") or die (mysqli_error($link));
	$row1 = mysqli_fetch_array($select1);
	$entered_amt = $row1['Amount'];
	
	$select2 = mysqli_query($link, "SELECT Total FROM twallet WHERE tid = '$tid'") or die (mysqli_error($link));
	$row2 = mysqli_fetch_array($select2);
	
	$Get_amt = $row2['Total'];
	$ReverseAmount = $Get_amt + $entered_amt;

	$update = mysqli_query($link, "UPDATE twallet SET Total = '$ReverseAmount' WHERE tid = '$tid'") or die (mysqli_error($link));
	$del = mysqli_query($link, "DELETE FROM mywallet WHERE id = '$id'") or die (mysqli_error($link));
	if(!($update && $del))
	{
 	   echo '<meta http-equiv="refresh" content="2;url=mywallet.php?tid='.$_SESSION['tid'].'&&mid=NDA0">';
	   echo '<br>';
	   echo'<span class="itext" style="color: #FF0000">Unable to Reverse Transaction!...Please try again later!!</span>';
	}
	else
	{
		echo '<meta http-equiv="refresh" content="2;url=mywallet.php?tid='.$_SESSION['tid'].'&&mid=NDA0">';
		echo '<br>';
		echo'<span class="itext" style="color: #FF0000">Transaction Reversed Successfully!...</span>';
	}
}
elseif($wtype == 'debit')
{
	$select1 = mysqli_query($link, "SELECT Amount FROM mywallet WHERE id = '$id' AND wtype = '$wtype'") or die (mysqli_error($link));
	$row1 = mysqli_fetch_array($select1);
	$entered_amt = $row1['Amount'];
	
	$select2 = mysqli_query($link, "SELECT Total FROM twallet WHERE tid = '$tid'") or die (mysqli_error($link));
	$row2 = mysqli_fetch_array($select2);
	
	$Get_amt = $row2['Total'];
	$ReverseAmount = $Get_amt - $entered_amt;
	
	if($ReverseAmount < 0){
		echo '<meta http-equiv="refresh" content="2;url=mywallet.php?tid='.$_SESSION['tid'].'&&mid=NDA0">';
		echo '<br>';
		echo'<span class="itext" style="color: #FF0000">Invalid Amount Entered! OR No Enough Funds to be Deduct in Wallet!!</span>';
	}
	else{
		$update = mysqli_query($link, "UPDATE twallet SET Total = '$ReverseAmount' WHERE tid = '$tid'") or die (mysqli_error($link));
		$del = mysqli_query($link, "DELETE FROM mywallet WHERE id = '$id'") or die (mysqli_error($link));
		if(!($update && $del))
		{
			echo '<meta http-equiv="refresh" content="2;url=mywallet.php?tid='.$_SESSION['tid'].'&&mid=NDA0">';
	   		echo '<br>';
	   	 	echo'<span class="itext" style="color: #FF0000">Reverse Transaction!...Please try again later!!</span>';
		}
		else
		{
			echo '<meta http-equiv="refresh" content="2;url=mywallet.php?tid='.$_SESSION['tid'].'&&mid=NDA0">';
			echo '<br>';
			echo'<span class="itext" style="color: #FF0000">Transaction Reversed Successfully!...</span>';
		}
	}
}
elseif($wtype == "transfer")
{
	$select1 = mysqli_query($link, "SELECT * FROM mywallet WHERE id = '$id' AND wtype = '$wtype'") or die (mysqli_error($link));
	$row1 = mysqli_fetch_array($select1);
	$entered_amt = $row1['Amount'];
	$transfer_to = $row1['t_to'];
	$transfer_from = $row1['tid'];
	
	$search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$transfer_to'");
	$get_staff = mysqli_fetch_array($search_staff);
	$branchid = $get_staff['branchid'];
	
	$serach_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$branchid'");
	$get_branch = mysqli_fetch_array($serach_branch);
	if($serach_branch == 1)
	{
	
	$select2 = mysqli_query($link, "SELECT Total FROM twallet WHERE tid = '$transfer_to'") or die (mysqli_error($link));
	while($row2 = mysqli_fetch_array($select2))
	{
		$Get_amt_todeductfrom = $row2['Total'];
		$DeductAmount = $Get_amt_todeductfrom - ($entered_amt * $get_branch['c_rate']);
	
		if($DeductAmount < 0){
			echo '<meta http-equiv="refresh" content="2;url=mywallet.php?tid='.$_SESSION['tid'].'&&mid=NDA0">';
			echo '<br>';
			echo'<span class="itext" style="color: #FF0000">Sorry! Part of the Fund has been used by the Staff OR No Enough Funds to be Deduct in Staff Wallet!!</span>';
		}
		else{
			$update1 = mysqli_query($link, "UPDATE twallet SET Total = '$DeductAmount' WHERE tid = '$transfer_to'") or die (mysqli_error($link));
		
			$select3 = mysqli_query($link, "SELECT Total FROM twallet WHERE tid = '$transfer_from'") or die (mysqli_error($link));
			$row3 = mysqli_fetch_array($select3);
	
			$Get_amt_tobeaddedto = $row3['Total'];
			$ReverseAmount = $Get_amt_tobeaddedto + $entered_amt;
	
			$update1 = mysqli_query($link, "UPDATE twallet SET Total = '$ReverseAmount' WHERE tid = '$transfer_from'") or die (mysqli_error($link));
			$del = mysqli_query($link, "DELETE FROM mywallet WHERE id = '$id'") or die (mysqli_error($link));
			if(!($update1 && $del))
			{
				echo '<meta http-equiv="refresh" content="2;url=mywallet.php?tid='.$_SESSION['tid'].'&&mid=NDA0">';
	   			echo '<br>';
	   	 		echo'<span class="itext" style="color: #FF0000">Unable to Reverse Transfer!...Please try again later!!</span>';
			}
			else
			{
				echo '<meta http-equiv="refresh" content="2;url=mywallet.php?tid='.$_SESSION['tid'].'&&mid=NDA0">';
				echo '<br>';
				echo'<span class="itext" style="color: #FF0000">Transfer to Staff Reversed Successfully!...</span>';
			}
		}
	}
}else{
	$select2 = mysqli_query($link, "SELECT Total FROM twallet WHERE tid = '$transfer_to'") or die (mysqli_error($link));
	while($row2 = mysqli_fetch_array($select2))
	{
		$Get_amt_todeductfrom = $row2['Total'];
		$DeductAmount = $Get_amt_todeductfrom - $entered_amt;
	
		if($DeductAmount < 0){
			echo '<meta http-equiv="refresh" content="2;url=mywallet.php?tid='.$_SESSION['tid'].'&&mid=NDA0">';
			echo '<br>';
			echo'<span class="itext" style="color: #FF0000">Sorry! Part of the Fund has been used by the Staff OR No Enough Funds to be Deduct in Staff Wallet!!</span>';
		}
		else{
			$update1 = mysqli_query($link, "UPDATE twallet SET Total = '$DeductAmount' WHERE tid = '$transfer_to'") or die (mysqli_error($link));
		
			$select3 = mysqli_query($link, "SELECT Total FROM twallet WHERE tid = '$transfer_from'") or die (mysqli_error($link));
			$row3 = mysqli_fetch_array($select3);
	
			$Get_amt_tobeaddedto = $row3['Total'];
			$ReverseAmount = $Get_amt_tobeaddedto + $entered_amt;
	
			$update1 = mysqli_query($link, "UPDATE twallet SET Total = '$ReverseAmount' WHERE tid = '$transfer_from'") or die (mysqli_error($link));
			$del = mysqli_query($link, "DELETE FROM mywallet WHERE id = '$id'") or die (mysqli_error($link));
			if(!($update1 && $del))
			{
				echo '<meta http-equiv="refresh" content="2;url=mywallet.php?tid='.$_SESSION['tid'].'&&mid=NDA0">';
	   			echo '<br>';
	   	 		echo'<span class="itext" style="color: #FF0000">Unable to Reverse Transfer!...Please try again later!!</span>';
			}
			else
			{
				echo '<meta http-equiv="refresh" content="2;url=mywallet.php?tid='.$_SESSION['tid'].'&&mid=NDA0">';
				echo '<br>';
				echo'<span class="itext" style="color: #FF0000">Transfer to Staff Reversed Successfully!...</span>';
			}
		}
	}
}
	
}
?>
</div>
</body>
</html>
