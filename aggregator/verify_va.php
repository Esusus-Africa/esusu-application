<?php 
if(isset($_POST['my_va']))
{
    
	include("../config/connect.php");

	$myva = is_numeric($_POST['my_va']) ? $_POST['my_va'] : "none";

	$search_va = mysqli_query($link, "SELECT * FROM virtual_account WHERE account_number = '$myva'");
	$fetch_va = mysqli_fetch_array($search_va);
	$inst_Id = $fetch_va['companyid'];
	$num = mysqli_num_rows($search_va);

	$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$inst_Id'");
    $detect_memset = mysqli_fetch_array($search_memset);
    $cname = ($inst_Id == "") ? "Esusu Africa" : $detect_memset['cname'];
	 
  	if($num == 1){

  		echo "<br><b style='font-size:18px;'>".strtoupper($fetch_va['account_name'])." (".strtoupper($cname).")</b>";
 
  	}
	else{
  	    
  		echo "<br><b style='font-size:18px;'>Opps!..Invalid Recipient Account Entered!!</b>";
  		
  	}
  	
}
?>