<?php 
$install = file_exists(__DIR__ . '/config/connect.php');

if ($install == false) {

    header("location:application/install/index.php");

}else {
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', true);
//error_reporting(-1);
include "config/connect.php";
require_once "config/smsAlertClass.php";

$cururl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$appurl = str_replace('application/install/step5.php', '', $cururl);
$orginal_path=str_replace('application','',$appurl);

        if(isset($_GET['key']) == true) 
        {
            include("decoder.php");
        }
        if(isset($_GET['activation_key']) == true)
        {
            $akey = $_GET["activation_key"];
            $resultk = mysqli_query($link, "SELECT * FROM activate_member WHERE shorturl = '$akey' AND attempt = 'No'") or die ("Error: " . mysqli_error($link));
            $rowk = mysqli_fetch_array($resultk);
            if(mysqli_num_rows($resultk) == 1)
            {
                $acn = $rowk['acn'];
                $search_boro = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
                $fetch_boro = mysqli_fetch_array($search_boro);
                $myregisterer = $fetch_boro['branchid'];
                
                $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$myregisterer'");
                $fetch_memset = mysqli_fetch_array($search_memset);
                $myregisterer_senderid = $fetch_memset['sender_id'];
                $updatekk = mysqli_query($link, "UPDATE borrowers SET acct_status = 'Activated', status='Completed' WHERE account = '$acn' AND acct_status = 'Not-Activated'") or die ("Error: " . mysqli_error($link));
                echo "<script>alert('Account Activated Successfully'); </script>";
                echo "<script>window.location='/$myregisterer_senderid'; </script>";
            }
            else{
                echo "<script>alert('Oops! Your Account have been Activated Already'); </script>";
            }
        }
        if(isset($_GET['a_key']) == true)
        {
            $a_key = $_GET["a_key"];
            $resultk2 = mysqli_query($link, "SELECT * FROM activate_member WHERE shorturl = '$a_key' AND attempt = 'No'") or die ("Error: " . mysqli_error($link));
            $rowk2 = mysqli_fetch_array($resultk2);
            if(mysqli_num_rows($resultk2) == 1)
            {
                $memid = $rowk2['acn'];
                $search_u = mysqli_query($link, "SELECT * FROM user WHERE id = '$memid'");
                $fetch_u = mysqli_fetch_array($search_u);
                $myRegisterer = $fetch_u['created_by'];
                
                $search_memset2 = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$myRegisterer'");
                $fetch_memset2 = mysqli_fetch_array($search_memset2);
                $myreg_senderid = $fetch_memset2['sender_id'];
                $updatekk = mysqli_query($link, "UPDATE user SET comment = 'Approved' WHERE id = '$memid'") or die ("Error: " . mysqli_error($link));
                $updatekk = mysqli_query($link, "UPDATE activate_member SET attempt = 'Yes' WHERE acn = '$memid'") or die ("Error: " . mysqli_error($link));
                echo "<script>alert('Account Activated Successfully'); </script>";
                echo ($myRegisterer == "") ? "<script>window.location='account/index'; </script>" : "<script>window.location='/$myreg_senderid'; </script>";
            }
            else{
                echo "<script>alert('Oops! Your Account have been Activated Already'); </script>";
            }
        }
        if(isset($_GET['deactivation_key']) == true){
            $dakey = $_GET["deactivation_key"];
            $resultk = mysqli_query($link, "SELECT * FROM activate_member WHERE shorturl = '$dakey' AND attempt = 'No'") or die ("Error: " . mysqli_error($link));
            $rowk = mysqli_fetch_array($resultk);
            if(mysqli_num_rows($resultk) == 1)
            {
                $acn = $rowk['acn'];
                $search_boro = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
                $fetch_boro = mysqli_fetch_array($search_boro);
                $myregisterer = $fetch_boro['branchid'];
                
                $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$myregisterer'");
                $fetch_memset = mysqli_fetch_array($search_memset);
                $myregisterer_senderid = $fetch_memset['sender_id'];
                $deletekk = mysqli_query($link, "DELETE FROM borrowers WHERE account = '$acn'") or die ("Error: " . mysqli_error($link));
                echo "<script>alert('Account Deactivated Successfully!'); </script>";   
                echo "<script>window.location='/$myregisterer_senderid'; </script>";
            }
            else{
                echo "<script>alert('Oops! Your Account have been Deactivated Already'); </script>";
            }
        }
        if(isset($_GET['d_key']) == true)
        {
            $a_key = $_GET["d_key"];
            $resultk2 = mysqli_query($link, "SELECT * FROM activate_member WHERE shorturl = '$a_key' AND attempt = 'No'") or die ("Error: " . mysqli_error($link));
            $rowk2 = mysqli_fetch_array($resultk2);
            if(mysqli_num_rows($resultk2) == 1)
            {
                $memid = $rowk2['acn'];
                $search_u = mysqli_query($link, "SELECT * FROM user WHERE id = '$memid'");
                $fetch_u = mysqli_fetch_array($search_u);
                $myRegisterer = $fetch_u['created_by'];
                
                $search_memset2 = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$myRegisterer'");
                $fetch_memset2 = mysqli_fetch_array($search_memset2);
                $myreg_senderid = $fetch_memset2['sender_id'];
                $updatekk = mysqli_query($link, "UPDATE user SET comment = 'Deactivated' WHERE id = '$memid'") or die ("Error: " . mysqli_error($link));
                $updatekk = mysqli_query($link, "UPDATE activate_member SET attempt = 'Yes' WHERE acn = '$memid'") or die ("Error: " . mysqli_error($link));
                echo "<script>alert('Account Deactivated Successfully'); </script>";
                echo "<script>window.location='/$myreg_senderid'; </script>";
                echo ($myRegisterer == "") ? "<script>window.location='account/index'; </script>" : "<script>window.location='/$myreg_senderid'; </script>";
            }
            else{
                echo "<script>alert('Oops! Your Account have been Deactivated Already'); </script>";
            }
        }
        if(isset($_GET['agr_key']) == true)
        {
            $agr_key = $_GET["agr_key"];
            $resultk2 = mysqli_query($link, "SELECT * FROM activate_member WHERE shorturl = '$agr_key' AND attempt = 'No'") or die ("Error: " . mysqli_error($link));
            $rowk2 = mysqli_fetch_array($resultk2);
            if(mysqli_num_rows($resultk2) == 1)
            {
                $memid = $rowk2['acn'];
                $search_u = mysqli_query($link, "SELECT * FROM user WHERE id = '$memid'");
                $fetch_u = mysqli_fetch_array($search_u);
                $myRegisterer = $fetch_u['created_by'];
                $aggrStatus = ($fetch_u['comment'] == "Pending") ? "Approved" : "Pending";
                
                $updatekk = mysqli_query($link, "UPDATE user SET comment = '$aggrStatus' WHERE id = '$memid'") or die ("Error: " . mysqli_error($link));
                $updatekk = mysqli_query($link, "UPDATE activate_member SET attempt = 'Yes' WHERE acn = '$memid'") or die ("Error: " . mysqli_error($link));
                echo "<script>alert('Account Activated Successfully'); </script>";
                echo "<script>window.location='account/index'; </script>";
            }
            else{
                echo "<script>alert('Oops! Your Account have been Activated Already'); </script>";
            }
        }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php 
    $compid = (isset($_GET['id']) == true) ? $_GET['id'] : '';
    $call_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$compid'");
    $fetch_msmset = mysqli_fetch_array($call_memset);
        
    $call = mysqli_query($link, "SELECT * FROM systemset");
    if(mysqli_num_rows($call) == 0)
    {
        echo "<script>alert('Data Not Found!'); </script>";
    }
    else
    {
    while($row = mysqli_fetch_assoc($call)){
    ?>
    <title><?php echo ($fetch_msmset['cname'] == '') ? $row['title'] : $fetch_msmset['cname']; ?></title>
    <?php }}?> 
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
<?php 
$call = mysqli_query($link, "SELECT * FROM systemset");
if(mysqli_num_rows($call) == 0)
{
echo "<script>alert('Data Not Found!'); </script>";
}
else
{
while($row = mysqli_fetch_array($call)){
?>
<!--===============================================================================================-->  
    <link rel="icon" type="image/png" href="<?php echo ($fetch_msmset['cname'] == '') ? $row['file_baseurl'].$row['image'] : $row['file_baseurl'].$fetch_msmset['logo']; ?>"/>
<!--===============================================================================================-->
<?php }} ?>
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="font/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="font/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->  
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->  
    <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="dist/css/util.css">
    <link rel="stylesheet" type="text/css" href="dist/css/main.css">
    
    
    <style type="text/css">
  
  /* ==========================================================================
   Chrome Frame prompt
   ========================================================================== */

    .chromeframe {
        margin: 0.2em 0;
        background: #ccc;
        color: #000;
        padding: 0.2em 0;
    }
    
    /* ==========================================================================
   Author's custom styles
   ========================================================================== */

    #loader-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
    }
    #loader {
        display: block;
        position: relative;
        left: 50%;
        top: 50%;
        width: 150px;
        height: 150px;
        margin: -75px 0 0 -75px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #3498db;
    
        -webkit-animation: spin 2s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
        animation: spin 2s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
    
        z-index: 1001;
    }

    #loader:before {
        content: "";
        position: absolute;
        top: 5px;
        left: 5px;
        right: 5px;
        bottom: 5px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #e74c3c;

        -webkit-animation: spin 3s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
        animation: spin 3s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
    }

    #loader:after {
        content: "";
        position: absolute;
        top: 15px;
        left: 15px;
        right: 15px;
        bottom: 15px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #f9c922;

        -webkit-animation: spin 1s linear infinite; /* Chrome, Opera 15+, Safari 5+ */
          animation: spin 1s linear infinite; /* Chrome, Firefox 16+, IE 10+, Opera */
    }

    @-webkit-keyframes spin {
        0%   { 
            -webkit-transform: rotate(0deg);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: rotate(0deg);  /* IE 9 */
            transform: rotate(0deg);  /* Firefox 16+, IE 10+, Opera */
        }
        100% {
            -webkit-transform: rotate(360deg);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: rotate(360deg);  /* IE 9 */
            transform: rotate(360deg);  /* Firefox 16+, IE 10+, Opera */
        }
    }
    @keyframes spin {
        0%   { 
            -webkit-transform: rotate(0deg);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: rotate(0deg);  /* IE 9 */
            transform: rotate(0deg);  /* Firefox 16+, IE 10+, Opera */
        }
        100% {
            -webkit-transform: rotate(360deg);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: rotate(360deg);  /* IE 9 */
            transform: rotate(360deg);  /* Firefox 16+, IE 10+, Opera */
        }
    }

    #loader-wrapper .loader-section {
        position: fixed;
        top: 0;
        width: 51%;
        height: 100%;
        background: #222222;
        z-index: 1000;
        -webkit-transform: translateX(0);  /* Chrome, Opera 15+, Safari 3.1+ */
        -ms-transform: translateX(0);  /* IE 9 */
        transform: translateX(0);  /* Firefox 16+, IE 10+, Opera */
    }

    #loader-wrapper .loader-section.section-left {
        left: 0;
    }

    #loader-wrapper .loader-section.section-right {
        right: 0;
    }

    /* Loaded */
    .loaded #loader-wrapper .loader-section.section-left {
        -webkit-transform: translateX(-100%);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: translateX(-100%);  /* IE 9 */
                transform: translateX(-100%);  /* Firefox 16+, IE 10+, Opera */

        -webkit-transition: all 0.2s 0.1s cubic-bezier(0.645, 0.045, 0.355, 1.000);  
                transition: all 0.2s 0.1s cubic-bezier(0.645, 0.045, 0.355, 1.000);
    }

    .loaded #loader-wrapper .loader-section.section-right {
        -webkit-transform: translateX(100%);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: translateX(100%);  /* IE 9 */
                transform: translateX(100%);  /* Firefox 16+, IE 10+, Opera */

        -webkit-transition: all 0.2s 0.1s cubic-bezier(0.645, 0.045, 0.355, 1.000);  
                transition: all 0.2s 0.1s cubic-bezier(0.645, 0.045, 0.355, 1.000);
    }
    
    .loaded #loader {
        opacity: 0;
        -webkit-transition: all 0.1s ease-out;  
                transition: all 0.1s ease-out;
    }
    .loaded #loader-wrapper {
        visibility: hidden;

        -webkit-transform: translateY(-100%);  /* Chrome, Opera 15+, Safari 3.1+ */
            -ms-transform: translateY(-100%);  /* IE 9 */
                transform: translateY(-100%);  /* Firefox 16+, IE 10+, Opera */

        -webkit-transition: all 0.1s 0.3s ease-out;  
                transition: all 0.1s 0.3s ease-out;
    }
    
    /* JavaScript Turned Off */
    .no-js #loader-wrapper {
        display: none;
    }
    .no-js h1 {
        color: #222222;
    }
  </style>
    
    
    <!--<style> 
        body { 
            animation: fadeInAnimation ease 3s;
            opacity: 0.1; 
            transition: opacity 3s; 
        }
        @keyframes fadeInAnimation { 
            0% { 
                opacity: 0; 
                pointer-events: none;
                transition: opacity 3s;
            }
            20% { 
                opacity: 0.2; 
                pointer-events: none;
                transition: opacity 3s;
            }
            40% { 
                opacity: 0.3; 
                pointer-events: none;
                transition: opacity 3s;
            }
            60% { 
                opacity: 0.5; 
                pointer-events: none;
                transition: opacity 3s;
            }
            80% { 
                opacity: 0.7; 
                pointer-events: none;
                transition: opacity 3s;
            }
            100% { 
                opacity: 1; 
                transition: opacity 3s;
            } 
        } 
    </style>-->
    
    
<!--===============================================================================================-->
<!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  
  <link rel="stylesheet" href="dist/css/style.css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<!--===============================================================================================-->
<script type="text/javascript">
function loaddata()
{
 var bvn=document.getElementById("unumber").value;

 if(bvn)
 {
  $.ajax({
  type: "POST",
  url: "application/verify_bvn.php",
  data: {
    my_bvn: bvn
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
      //alert(response);
   $('#bvn2').html(response);
  }
  });
 }

 else
 {
  $('#bvn2').html("<p class='label label-success'>Please Enter Correct BVN Number Here</p>");
 }
}
</script>

<script type="text/javascript">
  function veryUsername()
  {
   var verify_username=document.getElementById("vusername").value;
   var verify_email=document.getElementById("vemail").value;

   if(verify_username)
   {
    $.ajax({
    type: "POST",
    url: "verify_username.php",
    data: {
      my_username: verify_username
    },
    success: function(response) {
     // We get the element having id of display_info and put the response inside it
      //alert(response);
     $('#myusername').html(response);
    }
    });
   }
   else
   {
    $('#myusername').html("<label class='label label-danger'>Enter Unique Username</label>");
   }
  }
  
  function veryEmail()
  {
   var verify_email=document.getElementById("vemail").value;
   
   if(verify_email)
   {
    $.ajax({
    type: "POST",
    url: "verify_username.php",
    data: {
      my_email: verify_email
    },
    success: function(response) {
     // We get the element having id of display_info and put the response inside it
      //alert(response);
     $('#myvemail').html(response);
    }
    });
   }
   else
   {
    $('#myvemail').html("<label class='label label-danger'>Enter Valid Email Address</label>");
   }
  }
  
  function veryPhone()
  {
   var verify_phone=document.getElementById("vphone").value;
   
   if(verify_phone)
   {
    $.ajax({
    type: "POST",
    url: "verify_username.php",
    data: {
      my_phone: verify_phone
    },
    success: function(response) {
     // We get the element having id of display_info and put the response inside it
      //alert(response);
     $('#myvphone').html(response);
    }
    });
   }
   else
   {
    $('#myvphone').html("<label class='label label-danger'>Enter Phone Number</label>");
   }
  }
  </script>
  
  <script type="text/javascript">
  function veryBUsername()
  {
   var verify_username=document.getElementById("vbusername").value;

   if(verify_username)
   {
    $.ajax({
    type: "POST",
    url: "verify_username1.php",
    data: {
      my_username: verify_username
    },
    success: function(response) {
     // We get the element having id of display_info and put the response inside it
      //alert(response);
     $('#myusername').html(response);
    }
    });
   }
   else
   {
    $('#myusername').html("<label class='label label-danger'>Enter Unique Username</label>");
   }
  }
  
  function veryBEmail()
  {
   var verify_email=document.getElementById("vbemail").value;
   
   if(verify_email)
   {
    $.ajax({
    type: "POST",
    url: "verify_username1.php",
    data: {
      my_email: verify_email
    },
    success: function(response) {
     // We get the element having id of display_info and put the response inside it
      //alert(response);
     $('#myvemail').html(response);
    }
    });
   }
   else
   {
    $('#myvemail').html("<label class='label label-danger'>Enter Valid Email Address</label>");
   }
  }
  
  function veryBPhone()
  {
   var verify_phone=document.getElementById("vbphone").value;
   
   if(verify_phone)
   {
    $.ajax({
    type: "POST",
    url: "verify_username1.php",
    data: {
      my_phone: verify_phone
    },
    success: function(response) {
     // We get the element having id of display_info and put the response inside it
      //alert(response);
     $('#myvphone').html(response);
    }
    });
   }
   else
   {
    $('#myvphone').html("<label class='label label-danger'>Enter Phone Number</label>");
   }
  }
  </script>
</head>
<body style="background-color: #666666;">
    
        <div id="loader-wrapper">
			<div id="loader"></div>

			<div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>

		</div>
    
<?php
    /**
    function getAddress() {
        $protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
        return $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

    $url = getAddress();
    $lastSegment = basename(parse_url($url, PHP_URL_PATH));
    **/
    if(isset($_GET['id']) == true)
    {
        $id = $_GET['id'];

        $search_company = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$id'");
        if(mysqli_num_rows($search_company) == 0)
        {
            echo "<script>window.location='404.html'; </script>";
        }
        else{
            $fetch_company = mysqli_fetch_object($search_company);
            //if(!isset($_SESSION['tid']) || (trim($_SESSION['tid']) == '')) {
            include("personalize_login.php");
            //}else{
                //Do nothing
            //}
        }
    }
    else{
        echo "<script>window.location='404.html'; </script>";
        //include("general_login.php");
    }
?>



<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="vendor/jquery/jquery-1.9.1.min.js"><\/script>')</script>
<script src="vendor/jquery/main.js"></script>

    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!--===============================================================================================-->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
    <script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
    <script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
    <script src="vendor/daterangepicker/moment.min.js"></script>
    <script src="vendor/daterangepicker/daterangepicker.js"></script>
    <script>
     $('#accounttype').change(function(){
         var PostType=$('#accounttype').val();
         var PostType1=$('#myid').val();
         $.ajax({url:"Ajax-ShowPostACType.php?PostType="+PostType+"&&Referral="+PostType1,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
    </script>


    <script>
            var autocomplete1;
            var autocomplete2;
            var autocomplete3;
            function initialize() {
              autocomplete1 = new google.maps.places.Autocomplete(
                  /** @type {HTMLInputElement} */
                  (document.getElementById('autocomplete1')),
                  { types: ['geocode'] });
              autocomplete2 = new google.maps.places.Autocomplete(
                  /** @type {HTMLInputElement} */
                  (document.getElementById('autocomplete2')),
                  { types: ['geocode'] });
              autocomplete3 = new google.maps.places.Autocomplete(
                  /** @type {HTMLInputElement} */
                  (document.getElementById('autocomplete3')),
                  { types: ['geocode'] });

              google.maps.event.addListener(autocomplete, 'place_changed', function() {
              });
            }
    </script>

    <script type="text/javascript">
function loaddata()
{
 var bvn=document.getElementById("unumber").value;

 if(bvn)
 {
  $.ajax({
  type: "POST",
  url: "verify_bvn.php",
  data: {
    my_bvn: bvn
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
      //alert(response);
   $('#bvn2').html(response);
  }
  });
 }

 else
 {
  $('#bvn2').html("<p class='label label-success'>Please Enter Correct BVN Number Here</p>");
 }
}
</script>
<!--===============================================================================================-->
    <script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
    <script src="dist/js/main.js"></script>
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script>
  $(".toggle-password").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
</script>

<?php
function isMobileDevice(){
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

$mysenderid = $_GET['id'];
$verify_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$mysenderid'");
$fetch_memset = mysqli_fetch_object($verify_memset);
$instid = $fetch_memset->companyid;

$search_lvWidget = mysqli_query($link, "SELECT * FROM dedicated_livechat_widget WHERE companyid = '$instid'");
$fetch_lvWidget = mysqli_fetch_array($search_lvWidget);
$lvWidgetStatus = $fetch_lvWidget['status']; //Activated OR NotActivated

if(($lvWidgetStatus == "" || $lvWidgetStatus == "NotActivated") && !(isMobileDevice()))
{
?>
  
<!-- Live Chat 3 widget -->
<script type="text/javascript">
	(function(w, d, s, u) {
		w.id = 1; w.lang = ''; w.cName = ''; w.cEmail = ''; w.cMessage = ''; w.lcjUrl = u;
		var h = d.getElementsByTagName(s)[0], j = d.createElement(s);
		j.async = true; j.src = 'https://esusu.app/cs/js/jaklcpchat.js';
		h.parentNode.insertBefore(j, h);
	})(window, document, 'script', 'https://esusu.app/cs/');
</script>
<div id="jaklcp-chat-container"></div>
<!-- end Live Chat 3 widget -->

<?php
}
elseif($lvWidgetStatus == "Activated" && !(isMobileDevice())){

    echo base64_decode($fetch_lvWidget['livechat_widget']);

}else{
    //Do nothing
}
?>

<script>
    $(document).ready(function () {
        $('#iframe1').on('load', function () {
            $('#loader1').hide();
        });
    });
</script>
</body>
</html>