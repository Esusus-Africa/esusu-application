<?php 
if(isset($_POST['my_username']))
{
   include("../config/connect.php");
   include("../config/restful_apicalls.php");
   //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
   
     $myuname = $_POST['my_username'];

     $search_uname = mysqli_query($link, "SELECT * FROM borrowers WHERE username = '$myuname'");
     
     $search_muname = mysqli_query($link, "SELECT * FROM user WHERE username = '$myuname'");
    //print_r($result);
    if(mysqli_num_rows($search_uname) == 1){
      echo "<label style='font-size: 15px; color:red;'>Oops! Username Already Exist.</label>";
    }
    elseif(mysqli_num_rows($search_muname) == 1){
      echo "<label style='font-size: 15px; color:red;'>Oops! Email Already Exist.</label>";
    }
    else{
        echo "<label style='font-size: 15px; color:blue;'>Username is Available <img src='../image/mark.jpg' width='20px' height='20px'> </label>";
    }
}
if(isset($_POST['my_email']))
{
    include("../config/connect.php");
  //include("../config/restful_apicalls.php");
  //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
   
    $myemail = $_POST['my_email'];

    $search_uemail = mysqli_query($link, "SELECT * FROM borrowers WHERE email = '$myemail'");
    
    $search_muemail = mysqli_query($link, "SELECT * FROM user WHERE email = '$myemail'");
    //print_r($result);
    if(mysqli_num_rows($search_uemail) == 1){
      echo "<label style='font-size: 15px; color:red;'>Oops! Email Already Exist.</label>";
    }
    elseif(mysqli_num_rows($search_muemail) == 1){
      echo "<label style='font-size: 15px; color:red;'>Oops! Email Already Exist.</label>";
    }
    else{
        echo "<label style='font-size: 15px; color:blue;'>Email Available <img src='../image/mark.jpg' width='20px' height='20px'> </label>";
    }
}
if(isset($_POST['my_phone']))
{
    include("../config/connect.php");
  //include("../config/restful_apicalls.php");
  //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
    
    $myccode = $_POST['my_ccode'];
    $phone = $_POST['my_phone'];
    
    $myphone = $myccode.$phone;

    $search_uphone = mysqli_query($link, "SELECT * FROM borrowers WHERE phone = '$myphone'");
    
    $search_muphone = mysqli_query($link, "SELECT * FROM user WHERE phone = '$myphone'");
    //print_r($result);
    if(mysqli_num_rows($search_uphone) == 1){
        echo "<label style='font-size: 15px; color:red;'>Oops! Phone Number Already Exist.</label>";
    }
    elseif(mysqli_num_rows($search_muphone) == 1){
        echo "<label style='font-size: 15px; color:red;'>Oops! Phone Number Already Exist.</label>";
    }
    else{
        echo "<label style='font-size: 15px; color:blue;'>Phone Number is Available <img src='../image/mark.jpg' width='20px' height='20px'> </label>";
    }
}
if(isset($_POST['my_sid']))
{
    include("../config/connect.php");
  //include("../config/restful_apicalls.php");
  //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
   
    $mysid = $_POST['my_sid'];

    $search_usid = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$mysid'");
    //print_r($result);
    if(mysqli_num_rows($search_usid) >= 1){
      echo "<label style='font-size: 15px; color:red;'>Oops! Sender ID Already Exist.</label>";
    }
    else{
        echo "<label style='font-size: 15px; color:blue;'>Sender ID is Available <img src='../image/mark.jpg' width='20px' height='20px'> </label>";
    }
}
?>