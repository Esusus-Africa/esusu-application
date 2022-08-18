<?php 
if(isset($_POST['my_username']))
{
	 include("config/connect.php");
	 include("config/restful_apicalls.php");
	 //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
	 
  	 $myuname = $_POST['my_username'];

  	 $search_uname = mysqli_query($link, "SELECT * FROM user WHERE username = '$myuname'");
  	//print_r($result);
  	if(mysqli_num_rows($search_uname) >= 1){
  		echo "<label style='font-size: 15px; color:red;'>Oops! Username Already Exist.</label>";
  	}
  	else{
  	    echo "<label style='font-size: 15px; color:blue;'>Username Available <img src='image/mark.jpg' width='20px' height='20px'> </label>";
  	}
}
if(isset($_POST['my_email']))
{
    include("config/connect.php");
	include("config/restful_apicalls.php");
	//$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
	 
  	$myemail = $_POST['my_email'];

  	$search_uemail = mysqli_query($link, "SELECT * FROM user WHERE email = '$myemail'");
  	//print_r($result);
  	if(mysqli_num_rows($search_uemail) >= 1){
  		echo "<label style='font-size: 15px; color:red;'>Oops! Email Already Exist.</label>";
  	}
  	else{
  	    echo "<label style='font-size: 15px; color:blue;'>Email Available <img src='image/mark.jpg' width='20px' height='20px'> </label>";
  	}
}
if(isset($_POST['my_phone']))
{
    include("config/connect.php");
	include("config/restful_apicalls.php");
	//$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
	 
  	$myphone = $_POST['my_phone'];

  	$search_uphone = mysqli_query($link, "SELECT * FROM user WHERE phone = '$myphone'");
  	//print_r($result);
  	if(mysqli_num_rows($search_uphone) >= 1){
  		echo "<label style='font-size: 15px; color:red;'>Oops! Phone Number Already Exist.</label>";
  	}
  	else{
  	    echo "<label style='font-size: 15px; color:blue;'>Phone Number is Available <img src='image/mark.jpg' width='20px' height='20px'> </label>";
  	}
}
?>