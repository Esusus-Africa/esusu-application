<?php 
$install = file_exists(__DIR__ . '/config/connect.php');

if ($install == false) {

    header("location:application/install/index.php");

}else {
session_start();
//error_reporting(E_ALL);
include "config/connect.php";
//include("application/alert_sender/sms_charges.php");

$cururl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$appurl = str_replace('application/install/step5.php', '', $cururl);
$orginal_path=str_replace('application','',$appurl);

//mail($to,$subject,$body,$additionalheaders);

if(isset($_GET['key']) == true) 
{
	include("decoder.php");
}
elseif(isset($_GET['activation_key']) == true)
        {
            $akey = $_GET["activation_key"];
            $resultk = mysqli_query($link, "SELECT * FROM activate_member WHERE shorturl='$akey' AND attempt='No'") or die ("Error: " . mysqli_error($link));
            if(mysqli_num_rows($resultk) == 1)
            {
                $updatek = mysqli_query($link, "UPDATE activate_member SET attempt='Yes' WHERE shorturl='$akey'") or die ("Error: " . mysqli_error($link));
                $rowk = mysqli_fetch_array($resultk);
                $acn = $rowk['acn'];
                $updatekk = mysqli_query($link, "UPDATE borrowers SET acct_status = 'Activated' WHERE account = '$acn'") or die ("Error: " . mysqli_error($link));
                echo "<script>alert('Account Activated Successfully'); </script>";
                echo "<script>window.location='index.php'; </script>";
            }
            else{
                echo "<script>alert('Oops! Your Account has been Activated Already'); </script>";
            }
        }
        elseif(isset($_GET['deactivation_key']) == true){
            $dakey = $_GET["deactivation_key"];
            $resultk = mysqli_query($link, "SELECT * FROM activate_member WHERE shorturl='$dakey' AND attempt='No'") or die ("Error: " . mysqli_error($link));
            if(mysqli_num_rows($resultk) == 1)
            {
                $updatek = mysqli_query($link, "UPDATE activate_member SET attempt='Yes' WHERE shorturl='$dakey'") or die ("Error: " . mysqli_error($link));
                $rowk = mysqli_fetch_array($resultk);
                $acn = $rowk['acn'];
                $deletekk = mysqli_query($link, "DELETE FROM borrowers WHERE account = '$acn'") or die ("Error: " . mysqli_error($link));
                echo "<script>alert('Account Deactivated Successfully!'); </script>";   
                echo "<script>window.location='index.php'; </script>";
            }
            else{
                echo "<script>alert('Oops! Your Account has been Deactivated Already'); </script>";
            }
        }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
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
	<title><?php echo $row ['title']?></title>
	<?php }}?> 
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="img/<?php echo $row['image']; ?>"/>
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
<style>
#frameWrap {
    position:relative;
    height: 100%;
    width: 100%;
    border: 1px solid #777777;
    background:#f0f0f0;
    box-shadow:0px 0px 10px #777777;
}

#iframe1 {
    height: 100%;
    width: 100%;
    margin:0;
    padding:0;
    border:0;
}

#loader1 {
    position:absolute;
    left:40%;
    top:35%;
    border-radius:20px;
    padding:25px;
    border:1px solid #777777;
    background:#ffffff;
    box-shadow:0px 0px 10px #777777;
}
</style>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<script language="javascript" type="text/javascript">
    //this code handles the F5/Ctrl+F5/Ctrl+R
    document.onkeydown = checkKeycode
    function checkKeycode(e) {
        var keycode;
        if (window.event)
            keycode = window.event.keyCode;
        else if (e)
            keycode = e.which;

        // Mozilla firefox
        if ($.browser.mozilla) {
            if (keycode == 116 ||(e.ctrlKey && keycode == 82)) {
                if (e.preventDefault)
                {
                    e.preventDefault();
                    e.stopPropagation();
                }
            }
        } 
        // IE
        else if ($.browser.msie) {
            if (keycode == 116 || (window.event.ctrlKey && keycode == 82)) {
                window.event.returnValue = false;
                window.event.keyCode = 0;
                window.status = "Refresh is disabled";
            }
        }
    }
    function disableF5(e) { if ((e.which || e.keyCode) == 116 || (e.which || e.keyCode) == 82) e.preventDefault(); };

$(document).ready(function(){
$(document).on("keydown", disableF5);
});
</script>

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
</head>
<body style="background-color: #666666;" id="iframe1">

	<img id="loader1" src="image/loader.gif" width="36" height="36" alt="loading gif"/>
	
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
			echo "<script>alert('Invalid ID Detected!'); </script>"; 
			echo "<script>window.location='index.php'; </script>";
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
		include("general_login.php");
	}
?>

<div align="left">
		<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog md">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" align="center">
        <h3 class="modal-title"><b style="color: blue;">REGISTRATION</b></h3>
      </div>
      <div class="modal-body">
      	<div class="box">
			       <div class="box-body">
					<div class="panel panel-success">
		             <div class="box-body">
          
					 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_reg.php">

		            <div class="wrap-input100">
						<select name="account_type" class="input100" id="accounttype" required>
								<option selected='selected'>SELECT REGISTRATION TYPES&hellip;</option>
								<option value="Customer">CUSTOMER</option>
								<option value="Agent">AGENT</option>
                    <!--<option value="Institution">INSTITUTION</option>
								<option value="Cooperative">COOPERATIVE</option>
		    <option value="Cooperative Member">COOPERATIVE MEMBER</option>
								<option value="Activate">ACTIVATE ACCOUNT (CUSTOMER)</option>
								-->
								
						</select>
						<span class="focus-input100"></span>
						<span class="label-input100"><i class="fa fa-book"></i>&nbsp;REGISTRATION TYPES</span>
					</div>

					<span id='ShowValueFrank'></span>
  					<span id='ShowValueFrank'></span>
			 
			  
					 </form> 
					</div>
				</div>
			</div>
		</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
		
		</div>

	
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
         $.ajax({url:"Ajax-ShowPostACType.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
 	</script>

 <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyAHpWqBfhPU-owmEigD_YhwyURN9h1j7eo"></script>

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

<!-- live chat 3 widget -->
<script type="text/javascript">
	(function(w, d, s, u) {
		w.id = 1; w.lang = ''; w.cName = ''; w.cEmail = ''; w.cMessage = ''; w.lcjUrl = u;
		var h = d.getElementsByTagName(s)[0], j = d.createElement(s);
		j.async = true; j.src = 'https://esusu.africa/cs/js/jaklcpchat.js';
		h.parentNode.insertBefore(j, h);
	})(window, document, 'script', 'https://esusu.africa/cs/');
</script>
<div id="jaklcp-chat-container"></div>
<!-- end live chat 3 widget -->	

<script>
    $(document).ready(function () {
        $('#iframe1').on('load', function () {
            $('#loader1').hide();
        });
    });
</script>
</body>
</html>