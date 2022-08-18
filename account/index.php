<?php 
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', true);
//error_reporting(-1);
include "../config/connect.php";
require_once "../config/smsAlertClass.php";
?>
<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php 
    $call = mysqli_query($link, "SELECT * FROM systemset");
    if(mysqli_num_rows($call) == 0)
    {
        echo "<script>alert('Data Not Found!'); </script>";
    }
    else
    {
    while($row = mysqli_fetch_assoc($call)){
    ?>
    <title><?php echo $row['title']; ?></title>
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
    <link rel="icon" type="image/png" href="<?php echo $row['file_baseurl'].$row['image']; ?>"/>
<!--===============================================================================================-->
<?php }} ?>
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../font/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../font/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/animate/animate.css">
<!--===============================================================================================-->  
    <link rel="stylesheet" type="text/css" href="../vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
<!--===============================================================================================-->  
    <link rel="stylesheet" type="text/css" href="../vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="../dist/css/util.css">
    <link rel="stylesheet" type="text/css" href="../dist/css/main.css">
    
<!--===============================================================================================-->
<!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
  
  <link rel="stylesheet" href="../dist/css/style.css" />

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
  url: "../application/verify_bvn.php",
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
    url: "../verify_username.php",
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
    url: "../verify_username.php",
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
    url: "../verify_username.php",
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
    url: "../verify_username1.php",
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
    url: "../verify_username1.php",
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
    url: "../verify_username1.php",
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
  include("../general_login.php");
?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../vendor/jquery/jquery-1.9.1.min.js"><\/script>')</script>
<script src="vendor/jquery/main.js"></script>

    <script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!--===============================================================================================-->
    <script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
    <script src="../vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
    <script src="../vendor/bootstrap/js/popper.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
    <script src="../vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
    <script src="../vendor/daterangepicker/moment.min.js"></script>
    <script src="../vendor/daterangepicker/daterangepicker.js"></script>
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
  url: "../verify_bvn.php",
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
    <script src="../vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
    <script src="../dist/js/main.js"></script>
    
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

<script>
    $(document).ready(function () {
        $('#iframe1').on('load', function () {
            $('#loader1').hide();
        });
    });
</script>
</body>
</html>