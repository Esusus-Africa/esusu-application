<?php 
session_start();
error_reporting(E_ALL);
include "config/connect.php";

function startsWith($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}
?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  
  
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

<link href="<?php echo ($fetch_msmset['cname'] == '') ? 'img/'.$row['image'] : $fetch_msmset['logo']; ?>" rel="icon" type="dist/img">
<?php }}?> 

<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
  <style> 
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
    </style>
	  
	  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <style type="text/css">

.style1 {
	color: #FF0000;
	font-weight: bold;
}
.field-icon {
  float: right;
  margin-left: -25px;
  margin-top: -25px;
  position: relative;
  z-index: 2;
}

  </style>
  
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

  function verySID()
  {
   var verify_sid=document.getElementById("vsid").value;
   
   if(verify_sid)
   {
    $.ajax({
    type: "POST",
    url: "verify_username1.php",
    data: {
      my_sid: verify_sid
    },
    success: function(response) {
     // We get the element having id of display_info and put the response inside it
      //alert(response);
     $('#myvsid').html(response);
    }
    });
   }
   else
   {
    $('#myvsid').html("<label class='label label-danger'>Enter new sms notification id</label>");
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
<body class="hold-transition login-page" style="background-color: <?php echo (isset($_GET['id']) == true) ? 'white' : 'white'; ?>" onload="document.body.style.opacity='1'">
	<br>
  <div class="login-logo">
  <?php 
$call = mysqli_query($link, "SELECT * FROM systemset");
if(mysqli_num_rows($call) == 0)
{
echo "<script>alert('Data Not Found!'); </script>";
}
else
{
while($row = mysqli_fetch_assoc($call)){
    if(isset($_GET['id'])){
    $search_mmemberset = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '".$_GET['id']."'");
    $fetch_mmemberset = mysqli_fetch_array($search_mmemberset);
?>
   <img src="<?php echo (isset($_GET['id']) == true && $fetch_mmemberset['logo'] != "img/") ? $fetch_mmemberset['logo'] : 'img/'.$row ['image']; ?>" class="img-circle" alt="User Image" width="100" height="100">
   <h3 style="color: <?php echo (isset($_GET['id']) == true) ? 'black' : 'white'; ?>;"><strong><?php echo (isset($_GET['id']) == true) ? $fetch_mmemberset['cname'] : $row ['name']; ?></strong></h3>
   <h4 class="panel-title"><b style="color: <?php echo (isset($_GET['id']) == true) ? 'black' : 'white'; ?>;"><?php echo (isset($_GET['id']) == true) ? 'ACCOUNT REGISTRATION' : 'REGISTRATION FORM'; ?></b></h4>
   <?php 
    }
    else{
    ?>
    
    <img src="img/<?php echo $row ['image']; ?>" class="img-circle" alt="User Image" width="100" height="100">
   <h3 style="color: blue;"><strong><?php echo $row ['name']; ?></strong></h3>
   <h4 class="panel-title"><b style="color: blue;">REGISTRATION FORM </b></h4>
    
    <?php } } } ?>
  </div>
   	 <section class="content">
		
 			       <div class="box-body">
 					<div class="panel panel-success">
						<div class="box-body">
 		         
 		         <form class="form-horizontal" method="post" enctype="multipart/form-data">

<div align="center">
<?php
$mysenderID = $_GET['id'];
$search_mmemberset2 = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$mysenderID'");
$fetch_mmemberset2 = mysqli_fetch_array($search_mmemberset2);
$my_instid = $fetch_mmemberset2['companyid'];
$ussdcode2 = $fetch_mmemberset2['dedicated_ussd_prefix'];

if(isset($_POST['agent_register'])){
    
    include("config/walletafrica_restfulapis_call.php");
    
    $fname =  mysqli_real_escape_string($link, $_POST['fname']);
    $lname = mysqli_real_escape_string($link, $_POST['lname']);
    $full_name = $lname.' '.$fname;
    $bname = mysqli_real_escape_string($link, $_POST['bname']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $phone = mysqli_real_escape_string($link, $_POST['phone']);
    $addrs = mysqli_real_escape_string($link, $_POST['addrs']);
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $password = mysqli_real_escape_string($link, $_POST['password']);
    $encrypt = base64_encode($password);
    $acct_cat = mysqli_real_escape_string($link, $_POST['acct_cat']);
    $global_role = "agency_banker";
    $status = "Completed";
    
    $id = "MEM".time();
    $bopendate = date("Y-m-d");
    $ip = $_SERVER['REMOTE_ADDR']; // This will contain the ip of the request
    $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip), true);
    $origin_countryCode = $dataArray["geoplugin_countryCode"];
    $origin_country = $dataArray["geoplugin_countryName"];
    $origin_city = $dataArray["geoplugin_city"];
    $origin_province = $dataArray["geoplugin_region"];
    $mybranchid = 'BR'.time();
    
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $sysabb = $mysenderID;
    $sys_email = $r->email;
    
    $verify_email = mysqli_query($link, "SELECT * FROM user WHERE email = '$email'");
	$detect_email = mysqli_num_rows($verify_email);

	$verify_phone = mysqli_query($link, "SELECT * FROM user WHERE phone = '$phone'");
	$detect_phone = mysqli_num_rows($verify_phone);

	$verify_username = mysqli_query($link, "SELECT * FROM user WHERE username = '$username'");
	$detect_username = mysqli_num_rows($verify_username);

	if($detect_email == 1){
		echo "<p style='font-size:24px; color:orange;'>Sorry, Email Address has already been used.</p>";
	}
	elseif($detect_phone == 1){
		echo "<p style='font-size:24px; color:orange;'>Sorry, Phone Number has already been used.</p>";
	}
	elseif($detect_username == 1){
		echo "<p style='font-size:24px; color:orange;'>Sorry, Username has already been used.</p>";
	}
	else{
	     
	    $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = ''");
		$fetch_gateway = mysqli_fetch_object($search_gateway);
		$gateway_uname = $fetch_gateway->username;
		$gateway_pass = $fetch_gateway->password;
		$gateway_api = $fetch_gateway->api;
		
		$transactionPin = substr((uniqid(rand(),1)),3,4);
		$prefix = "AG";
		
		$sms = "$sysabb>>>Congrats! Dear $fname! Your account have been created successfully. Username: $username, Password: $password. tPIN: $transactionPin, Login to your account here: https://esusu.app/$sysabb";
        
    	$insert = mysqli_query($link, "INSERT INTO user VALUES(null,'$fname','$email','$phone','$addrs','','$origin_city','$origin_province','','$origin_countryCode','Approved','$username','$encrypt','$id','','$global_role','$mybranchid','Registered','$my_instid','$prefix','$transactionPin','0.0','','0.0','','','$ussdcode2')") or die ("Error: " . mysqli_error($link));
      $insert = mysqli_query($link, "INSERT INTO branches VALUES(null,'BR','$bname','$bopendate','$origin_country','$origin_countryCode','$addrs','$origin_city','$origin_province','NIL','$phone','$phone','$mybranchid','Operational','','$my_instid')") or die ("Error: " . mysqli_error($link));
        
        if(!$insert){
            
            echo '<meta http-equiv="refresh" content="5;url=/'.$sysabb.'">';
			echo '<br>';
			echo'<span class="itext" style="color: orange">Oops!...Registration not successful, please try again later!!</span>';
            
        }else{
            
            include('cron/send_general_sms.php');
            include('cron/send_ag_regemail.php');
            echo '<meta http-equiv="refresh" content="5;url=/'.$sysabb.'">';
			echo '<br>';
			echo'<span class="itext" style="color: blue">Account Created Successfully!...Kindly check the mail / sms sent to proceed!!</span>';
            
        }

	}
    
}
?>
</div>

 		         <div class="box-body">
 		                 
                        <input type="hidden" class="form-control" name="id" value="<?php echo $mysenderID; ?>"/>
                        <input name="acct_cat" type="hidden" class="form-control" value="Agency Banking">
                        <input name="my_senderid" type="hidden" class="form-control" value="<?php echo $mysenderID; ?>">
                        
     		     <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">Contact Person</label>
                  <div class="col-sm-7">
                  <input name="fname" type="text" class="form-control" placeholder="Enter Contact Person's Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">Business Name</label>
                  <div class="col-sm-7">
                  <input name="bname" type="text" class="form-control" placeholder="Your Business Name" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">Email Address</label>
                  <div class="col-sm-7">
                  <input name="email" type="email" class="form-control" id="vemail" onkeyup="veryEmail();" placeholder="Your Email Address" required>
                  <div id="myvemail"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">Contact Phone</label>
                  <div class="col-sm-7">
                  <input name="phone" type="text" class="form-control" id="vphone" onkeyup="veryPhone();" placeholder="+2348182111222" required>
                  <p style="color: <?php echo ($mysenderID != "") ? 'black' : 'orange'; ?>; font-size: 16px;">International Format: <b style="color: <?php echo ($mysenderID != "") ? 'black' : 'orange'; ?>;"> e.g. +2348182111222</b>.</p>
                  <div id="myvphone"></div>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">Location</label>
                  <div class="col-sm-7">
                  <input name="addrs" type="text" class="form-control" id="autocomplete1" onFocus="geolocate()" placeholder="Location" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
                    <input name="currency_type" type="hidden" class="form-control" value="<?php echo $mycurrencytype; ?>">
                    <input name="origin_country" type="hidden" class="form-control" value="<?php echo $origincountry; ?>">
                    <input name="origin_city" type="hidden" class="form-control" value="<?php echo $origincity; ?>">
                    <input name="origin_province" type="hidden" class="form-control" value="<?php echo $originprovince; ?>">
            
                      
                      <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">Username</label>
                      <div class="col-sm-7">
                      <input name="username" type="text" class="form-control" id="vbusername" onkeyup="veryBUsername();" placeholder="Your Username" required>
                      <div id="myusername"></div>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                      </div>
    
    
    				 <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">Password</label>
                      <div class="col-sm-7">
                      <input name="password" type="password" class="form-control" placeholder="Your Password" id="password-field" required>
                      <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span> 
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                      </div>
                      
                      <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;"></label>
                      <div class="col-sm-7">
                      <span style="color: <?php echo ($mysenderID != "") ? 'black' : 'orange'; ?>;"><input class="input-checkbox100" id="ckb1" type="checkbox" name="acpt_policy" required> &nbsp; Accept our <a href="#">Terms and Conditions</a>.</span>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                      </div>
                      
                </div>
            
                    <div class="form-group" align="right">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;"></label>
                      <div class="col-sm-7">
                      <button name="agent_register" type="submit" class="btn bg-<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?> btn-flat"><i class="fa fa-cloud-upload">&nbsp;Register</i></button> 
                      <?php echo (isset($_GET['id'])) ? '<a href="/'.$_GET['id'].'"><button type="button" class="btn bg-black btn-flat"><i class="fa fa-reply-all">&nbsp;Goto Login</i></button></a>' : '<a href="/"><button type="button" class="btn bg-yellow btn-flat"><i class="fa fa-reply-all">&nbsp;Goto Login</i></button></a>'; ?>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                    </div>
                    
                </form>


 		</div>	
 		</div>
	</div>
	</section>
		
<!-- /.login-box -->
	
<!-- jQuery 2.2.3 -->
<script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
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

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>

<script>
  function myFunction() {
  var x = document.getElementById("myInput");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
} 
</script>

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

    <script>
     $('#accounttype').change(function(){
         var PostType=$('#accounttype').val();
         var PostType1=$('#myreferral').val();
         var PostType2=$('#myid').val();
         $.ajax({url:"Ajax-ShowPostACType.php?PostType="+PostType+"&&rf="+PostType1+"&&id="+PostType2,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
    </script>

</body>
</html>
