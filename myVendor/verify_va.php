<?php 
if(isset($_POST['my_va']))
{
    
	include("../config/connect.php");

	$myva = is_numeric($_POST['my_va']) ? $_POST['my_va'] : "none";

	$search_va = mysqli_query($link, "SELECT * FROM borrowers WHERE virtual_acctno = '$myva'");
  	$fetch_va = mysqli_fetch_array($search_va);
	$num = mysqli_num_rows($search_va);
	
	$search_va1 = mysqli_query($link, "SELECT * FROM user WHERE virtual_acctno = '$myva'");
  	$fetch_va1 = mysqli_fetch_array($search_va1);
  	$num1 = mysqli_num_rows($search_va1);
	 
  	if($num == 1 && $num1 == 0){

  		echo "<br><b style='font-size:18px;'>".strtoupper($fetch_va['fname'])." ".strtoupper($fetch_va['lname'])."</b>";
 
  	}
	elseif($num == 0 && $num1 == 1){

		echo "<br><b style='font-size:18px;'>".strtoupper($fetch_va1['name'])." ".strtoupper($fetch_va1['lname'])."</b>";

	}  
	else{
  	    
  		echo "<br><b style='font-size:18px;'>Opps!..Invalid Recipient Account Entered!!</b>";
  		
  	}
  	
}
?>