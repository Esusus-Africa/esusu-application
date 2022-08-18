<?php 
session_start();
error_reporting(0);
/*ini_set('display_errors', true);
error_reporting(-1);*/
//error_reporting(E_ALL);
include "config/connect.php";
require_once "config/smsAlertClass.php";
require_once "config/bvnVerification_class.php";
require_once "config/virtualBankAccount_class.php";

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

<link href="<?php echo ($fetch_msmset['cname'] == '') ? $row['file_baseurl'].$row['image'] : $row['file_baseurl'].$fetch_msmset['logo']; ?>" rel="icon" type="dist/img">

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

  <link rel="stylesheet" href="intl-tel-input-master/build/css/intlTelInput.css">
  <!--<link rel="stylesheet" href="intl-tel-input-master/build/css/demo.css">-->
  
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
        .iti { width: 100%; }
    </style>-->
	  
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
<body class="hold-transition login-page" style="background-color: <?php echo (isset($_GET['id']) == true) ? 'white' : 'white'; ?>">
    
    <div id="loader-wrapper">
			<div id="loader"></div>

			<div class="loader-section section-left"></div>
          <div class="loader-section section-right"></div>
		  </div>
		
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
   <img src="<?php echo (isset($_GET['id']) == true && $fetch_mmemberset['logo'] != "img/") ? $row['file_baseurl'].$fetch_mmemberset['logo'] : $row['file_baseurl'].$row['image']; ?>" class="img-circle" alt="User Image" width="100" height="100">
   <h3 style="color: <?php echo (isset($_GET['id']) == true) ? 'black' : 'white'; ?>;"><strong><?php echo (isset($_GET['id']) == true) ? $fetch_mmemberset['cname'] : $row ['name']; ?></strong></h3>
   <h4 class="panel-title"><b style="color: <?php echo (isset($_GET['id']) == true) ? 'black' : 'white'; ?>;"><?php echo (isset($_GET['id']) == true) ? 'ACCOUNT REGISTRATION' : 'REGISTRATION FORM'; ?></b></h4>
   <?php 
    }
    else{
    ?>
    
    <img src="<?php echo $row['file_baseurl'].$row['image']; ?>" class="img-circle" alt="User Image" width="100" height="100">
   <h3 style="color: blue;"><strong><?php echo $row['name']; ?></strong></h3>
   <h4 class="panel-title"><b style="color: blue;">REGISTRATION FORM </b></h4>
    
    <?php } } } ?>
  </div>
   	 <section class="content">
		
 			       <div class="box-body">
 					<div class="panel panel-success">
						<div class="box-body">
                    
 		         <?php
 		         if(isset($_GET['id']) == true){
 		             $mysenderID = $_GET['id'];
 		             $search_mmemberset2 = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$mysenderID'");
                 $fetch_mmemberset2 = mysqli_fetch_array($search_mmemberset2);
                 $ussdcode2 = $fetch_mmemberset2['dedicated_ussd_prefix'];
                 $my_instid = $fetch_mmemberset2['companyid'];
                 $otp_option = $fetch_mmemberset2['otp_option'];
                 $product_manager = $fetch_mmemberset2['product_manager'];
                 $iva_provider = $fetch_mmemberset2['va_provider'];
 		         ?>
 		         
 		         <form class="form-horizontal" method="post" enctype="multipart/form-data">

<div align="center">
<?php
if(isset($_POST['indiv_register'])){
        
    $preferredBank = mysqli_real_escape_string($link, $_POST['preferredBank']);
    $acct_type =  mysqli_real_escape_string($link, $_POST['acct_type']);
    $fname =  mysqli_real_escape_string($link, $_POST['fname']);
    $lname = mysqli_real_escape_string($link, $_POST['lname']);
    $mname = mysqli_real_escape_string($link, $_POST['mname']);
    $fullname = $fname.' '.$lname.' '.$mname;
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $phone = mysqli_real_escape_string($link, $_POST['phone']);
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $password = mysqli_real_escape_string($link, $_POST['password']);
    $dob =  mysqli_real_escape_string($link, $_POST['dob']);
    $gender = mysqli_real_escape_string($link, $_POST['gender']);
    $status = "Pending";
    $wallet_date_time = date("Y-m-d h:i:s");
    $userBvn = mysqli_real_escape_string($link, $_POST['bvn']);
    $refferral = mysqli_real_escape_string($link, $_POST['refferral']);

    $ip = $_SERVER['REMOTE_ADDR']; // This will contain the ip of the request
    $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip), true);
    $currencyCode = $dataArray["geoplugin_currencyCode"];
    $origin_countryCode = $dataArray["geoplugin_countryCode"];
    $origin_country = $dataArray["geoplugin_countryName"];
    $origin_city = $dataArray["geoplugin_city"];
    $origin_province = $dataArray["geoplugin_region"];

    $phoneNumber = $phone;
    $country = $origin_countryCode;
    
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $sysabb = $mysenderID;
    $sys_email = $r->email;
    $walletafrica_skey = $r->walletafrica_skey;
    $mo_contract_code = $r->mo_contract_code;
    $accountReference = "EAVA-".date("dy").time();
    $ibvn_fee = $r->bvn_fee;
    $rubbiesSecKey = $r->rubbiesSecKey;
    $rave_secret_key = $r->secret_key;
    $payantEmail = $r->payantEmail;
    $payantPwd = $r->payantPwd;
    $payantOrgId = $r->payantOrgId;
    $providusClientId = $r->providusClientId;
    $providusClientSecret = $r->providusClientSecret;
    $wemaVAPrefix = $r->wbPrefix;
    $sms_rate = $r->fax;
    $sterlinkInputKey = $r->sterlinkInputKey;
    $sterlingIv = $r->sterlingIv;

    $accountUserId = $account;
    $customerEmail = $email;
    $accountName = $fullname;
    $customerName = $accountName;

    $search_emailConfig = mysqli_query($link, "SELECT * FROM email_config WHERE companyid = '$my_instid'");
    $fetch_emailConfig = mysqli_fetch_array($search_emailConfig);
    $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated

    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$my_instid'");
    $detect_memset = mysqli_fetch_array($search_memset);
    $customDomain = ($emailConfigStatus == "Activated") ? $fetch_emailConfig['product_url'] : "https://esusu.app/$mysenderID";
    $mobileapp_link = ($detect_memset['mobileapp_link'] == "") ? "Login at ".$customDomain : "Download mobile app: ".$detect_memset['mobileapp_link'];
    $idedicated_ledgerAcctNo_prefix = $detect_memset['dedicated_ledgerAcctNo_prefix'];

    //UNIQUE CUSTOMER ACCOUNT NUMBER
    $real_acct = $idedicated_ledgerAcctNo_prefix.rand(10000000,99999999);
    $search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$real_acct'");
    $account = (mysqli_num_rows($search_customer) == 0) ? $real_acct : $idedicated_ledgerAcctNo_prefix.substr((uniqid(rand(),1)),4,8);

    $verify_email = mysqli_query($link, "SELECT * FROM borrowers WHERE email = '$email' AND branchid = '$my_instid'");
	  $detect_email = mysqli_num_rows($verify_email);

	  $verify_phone = mysqli_query($link, "SELECT * FROM borrowers WHERE phone = '$phone' AND branchid = '$my_instid'");
	  $detect_phone = mysqli_num_rows($verify_phone);

	  $verify_username = mysqli_query($link, "SELECT * FROM borrowers WHERE username = '$username'");
	  $detect_username = mysqli_num_rows($verify_username);
	
	  $verify_Uusername = mysqli_query($link, "SELECT * FROM user WHERE username = '$username'");
    $detect_Uusername = mysqli_num_rows($verify_Uusername);
  
    $ainvCod = (isset($_GET['ainv'])) ? $_GET['ainv'] : "";

    $searchinvit = mysqli_query($link, "SELECT * FROM invite WHERE invite_code = '$ainvCod' AND (status = 'Sent' OR status = 'Clicked')") or die ("Error " . mysqli_error($link));
    $numInvit = mysqli_num_rows($searchinvit);
    $fetchinvit = mysqli_fetch_array($searchinvit);
    $sUserID = ($ainvCod == "") ? $fetchinvit['userid'] : $refferral;

    $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$my_instid'");
    $fetch_myinst = mysqli_fetch_array($search_insti);
    $iwallet_balance = $fetch_myinst['wallet_balance'];

    $verifySMS_Provider = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$my_instid' AND status = 'Activated'") or die (mysqli_error($link));
    $verifySMS_Provider1 = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'") or die (mysqli_error($link));
    $getSMS_ProviderNum = mysqli_num_rows($verifySMS_Provider);
    $fetchSMS_Provider = ($getSMS_ProviderNum == 0) ? mysqli_fetch_array($verifySMS_Provider1) : mysqli_fetch_array($verifySMS_Provider);
    $ozeki_password = $fetchSMS_Provider['password'];
    $ozeki_url = $fetchSMS_Provider['api'];
    $debitWAllet = ($getSMS_ProviderNum == 1) ? "No" : "Yes"; 

    $TxtReference = uniqid('ESFUND').time();

    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/confirmAcct.php?id='.$mysenderID;
    $otpCode = substr((uniqid(rand(),1)),3,6);
    $shortenedurl1 = $protocol . $_SERVER['HTTP_HOST'] . '/?deactivation_key=' . $otpCode;

    if($detect_username == 1 || $detect_Uusername == 1){

      echo "<p style='font-size:24px; color:orange;'>Sorry, Username has already been used.</p>";
    
    }
    elseif((isset($_GET['ainv'])) && $numInvit == 0){

      echo "<p style='font-size:24px; color:orange;'>Sorry, You have already registered with the Invite link!!</p>";
    
    }
    elseif($idedicated_ledgerAcctNo_prefix == "" && $acct_type == "Individual"){

      echo "<p style='font-size:24px; color:orange;'>Sorry! The ledger account number prefix is not yet configure...Kindly contact us to so!!</p>";
    
    }
    elseif($acct_type == "Individual"){

	      $opening_date = date("Y-m-d");
	    
        //$otp = ($otp_option == "Yes" || $otp_option == "Both") ? substr((uniqid(rand(),1)),3,6) : "Not-Activated";     
        $wbalance = $iwallet_balance - $ibvn_fee;
      
        //($userBvn == "") ? "" : require_once "config/bvnVerification_class.php";
      
        ($userBvn == "") ? "" : $processBVN = $newBV->walletAfricaBVNVerifier($walletafrica_skey,$userBvn,$link);
        ($userBvn == "") ? $ResponseCode = "" : $ResponseCode = $processBVN['ResponseCode'];

        if($iwallet_balance < $ibvn_fee && $userBvn != ""){
            echo "<p style='font-size:24px; color:orange;'>Sorry! Unable to perform verification at the moment..Please try again later!!</p>";
        }
        /*elseif($ResponseCode != "200" && $userBvn != ""){
            echo "<p style='font-size:24px; color:orange;'>Sorry, We're unable to verify user BVN at the moment, please try again later!! </p>".$ResponseCode;
        }*/
        else{
            //BVN Details
            $rOrderID = "EA-bvnCharges-".time();
            ($userBvn == "") ? "" : $bvn_picture = $processBVN['Picture'];
            $dynamicStr = md5(date("Y-m-d h:i"));
            ($userBvn == "") ? "" : $image_converted = $newBV->base64_to_jpeg($bvn_picture, $dynamicStr.".png");
            $send_sms = ($debitWAllet == "No" ? "1" : ($debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? "1" : "0"));
	          $send_email = ($email == "") ? "0" : "1";
    
            //20 array row
            ($userBvn == "") ? "" : $mybvn_data = $processBVN['BVN']."|".$processBVN['FirstName']."|".$processBVN['LastName']."|".$processBVN['MiddleName']."|".$processBVN['DateOfBirth']."|".$processBVN['PhoneNumber']."|".$processBVN['Email']."|".$processBVN['EnrollmentBank']."|".$processBVN['EnrollmentBranch']."|".$processBVN['Gender']."|".$processBVN['LevelOfAccount']."|".$processBVN['LgaOfOrigin']."|".$processBVN['LgaOfResidence']."|".$processBVN['MaritalStatus']."|".$processBVN['NameOnCard']."|".$processBVN['Nationality']."|".$processBVN['StateOfOrigin']."|".$processBVN['StateOfResidence']."|".$processBVN['Title']."|".$processBVN['WatchListed']."|".$image_converted;
    
            ($preferredBank == "Monnify") ? $result = $newVA->monnifyVirtualAccount($accountName,$currencyCode,$customerEmail,$customerName,$userBvn,$mo_contract_code) : "";
            ($preferredBank == "Rubies Bank") ? $result1 = $newVA->rubiesVirtualAccount($accountName,$rubbiesSecKey) : "";
            ($preferredBank == "Flutterwave") ? $result1 = $newVA->wemaVirtualAccount($accountName,$customerEmail,$TxtReference,$rave_secret_key) : "";
            ($preferredBank == "Payant") ? $result1 = $newVA->sterlingPayantVirtualAcct($accountName,$customerEmail,$phoneNumber,$currencyCode,$country,$payantEmail,$payantPwd,$payantOrgId) : "";
            ($preferredBank == "Providus Bank") ? $result1 = $newVA->providusVirtualAccount($accountName,$userBvn,$providusClientId,$providusClientSecret) : "";
            ($preferredBank == "Wema Bank") ? $result1 = $newVA->directWemaVirtualAccount($accountName,$wemaVAPrefix) : "";
            ($preferredBank == "Sterling Bank") ? $result1 = $newVA->sterlingVirtualAccount($fname,$lname,$phoneNumber,$dob,$gender,$currencyCode,$sterlinkInputKey,$sterlingIv) : "";
            
            ($preferredBank == "Monnify" ? $myAccountReference = $result->responseBody->accountReference : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountReference = "EAVA-".time() : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountReference = $TxtReference : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myAccountReference = $result1['data']['_id'] : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : "")))))));
            ($preferredBank == "Monnify" ? $myAccountName = $result->responseBody->accountName : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountName = $result1['virtualaccountname'] : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountName = $accountName : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myAccountName = $result1['data']['accountName'] : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountName = $result1['account_name'] : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountName = $result1['account_name'] : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $myAccountName = $result1['data']['lastname'].' '.$result1['data']['firstname'] : "")))))));
            ($preferredBank == "Monnify" ? $myAccountNumber = $result->responseBody->accountNumber : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountNumber = $result1['virtualaccount'] : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountNumber = $result1['data']['account_number'] : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myAccountNumber = $result1['data']['accountNumber'] : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountNumber = $result1['account_number'] : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountNumber = $wemaVAPrefix.$result1['account_number'] : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $myAccountNumber = $result1['data']['VIRTUALACCT'] : "")))))));
            ($preferredBank == "Monnify" ? $myBankName = $result->responseBody->bankName : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myBankName = $result1['bankname'] : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myBankName = $result1['data']['bank_name'] : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myBankName = "Sterling Bank" : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myBankName = "Providus Bank" : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $myBankName = "Wema Bank" : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $myBankName = "Sterling Bank" : "")))))));
            ($preferredBank == "Monnify" ? $myStatus = $result->responseBody->status : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myStatus = "ACTIVE" : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myStatus = "ACTIVE" : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myStatus = $result1['data']['status'] : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myStatus = "ACTIVE" : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $myStatus = "ACTIVE" : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $myStatus = "ACTIVE" : "")))))));
            ($preferredBank == "Monnify" ? $date_time = $result->responseBody->createdOn : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $date_time = date("Y-m-d h:i:s") : "")))))));
            ($preferredBank == "Monnify" ? $provider = "monify" : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $provider = "rubies" : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $provider = "wema" : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $provider = "payant" : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $provider = "providus" : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $provider = "wema" : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $provider = "sterling" : "")))))));
                
            $transactionPin = substr((uniqid(rand(),1)),3,4);
            
            $VAccountDetails = ($myAccountNumber == "") ? "Your Ledger Account No: ".$account : "Your Wallet Account No: ".$myAccountNumber.", Bank: ".$myBankName;
                
            ($otp_option == "No") ? $sms = "$sysabb>>>Welcome $fname! $VAccountDetails, Transaction Pin: $transactionPin. $mobileapp_link" : "";
                
            //($otp_option == "Yes" || $otp_option == "Both") ? $sms = "$sysabb>>>Welcome $fname! Your One Time Password is $otp" : "";
                
            $max_per_page = 153;
            $sms_length = strlen($sms);
            $calc_length = ceil($sms_length / $max_per_page);
            $sms_charges = $calc_length * $sms_rate;
            $imywallet_balance = $iwallet_balance - $sms_charges;
            $sms_refid = "EA-smsCharges-".rand(1000000,9999999);
        
            //SMS NOTIFICATION
            ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $my_instid, $sms_refid, $sms_charges, '', $imywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $my_instid, $sms_refid, $sms_charges, '', $imywallet_balance, $debitWallet) : ""));
        
            //EMAIL NOTIFCATION
            $sendSMS->customerRegEmailNotifier($email, $fname, $shortenedurl, $username, $password, $account, $myAccountNumber, $otpCode, $shortenedurl1, $emailConfigStatus, $fetch_emailConfig);
            
            (isset($_GET['ainv'])) ? mysqli_query($link, "INSERT INTO borrowers VALUES(null,'','$fname','$lname','$mname','$email','$phone','$gender','$dob','','','$origin_province','$origin_city','','$origin_countryCode','','','','Borrower','$account','$username','$password','0.0','0.0','0.0','0.0','0.0','',NOW(),'0000-00-00','$status','$sUserID','','$my_instid','','$otp','','','','','','','','','','','','No','NGN','0.0','No','NULL','No','NULL','$transactionPin','Individual','','','','0.0','$opening_date','','','','$myAccountReference','$myAccountNumber','$preferredBank','$ussdcode2','','Yes','','','','','','','','','','','$send_sms','$send_email')") or die ("Error0: " . mysqli_error($link)) : $new_member = mysqli_query($link, "INSERT INTO borrowers VALUES(null,'','$fname','$lname','$mname','$email','$phone','$gender','$dob','','','$origin_province','$origin_city','',' $origin_countryCode','','','','Borrower','$account','$username','$password','0.0','0.0','0.0','0.0','0.0','',NOW(),'0000-00-00','$status','','','$my_instid','','$otp','','','','','','','','','','','','No','NGN','0.0','No','NULL','No','NULL','$transactionPin','Individual','','','','0.0','$opening_date','','','','$myAccountReference','$myAccountNumber','$preferredBank','$ussdcode2','','Yes','','','','','','','','','','','$send_sms','$send_email')") or die ("Error1: " . mysqli_error($link));
          
            ((isset($_GET['ainv'])) && $numInvit == 1) ? mysqli_query($link, "UPDATE invite SET status = 'Registered', customerid = '$account' WHERE invite_code = '$ainvCod' AND (status = 'Sent' OR status = 'Clicked')") or die ("Error2: " . mysqli_error($link)) : "";
                
            ($myAccountName != "" && $myAccountNumber != "") ? mysqli_query($link, "INSERT INTO virtual_account VALUES(null,'$myAccountReference','$account','$myAccountName','$myAccountNumber','$myBankName','$myStatus','$date_time','$provider','$my_instid','Individual','$status','$sUserID','1000000','100000','10000','5000')") or die ("Error3: " . mysqli_error($link)) : "";
            
            mysqli_query($link, "INSERT INTO ip_tracker VALUES(null,'$ip','$my_instid','$account','$accountName','$date_time')") or die ("Error3i: " . mysqli_error($link));      
          
            ($otp_option == "Both" || $otp_option == "No") ? mysqli_query($link, "INSERT INTO activate_member2 VALUES(null,'$shortenedurl','$otpCode','No','$account')") or die ("Error4: " . mysqli_error($link)) : "";
          
            ($userBvn == "" ? "" : ($ResponseCode != "200" && $ResponseCode != "" ? "" : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$wbalance' WHERE institution_id = '$my_instid'") or die ("Error5: " . mysqli_error($link))));
    
            ($userBvn == "" ? "" : ($ResponseCode != "200" && $ResponseCode != "" ? "" : mysqli_query($link, "INSERT INTO bvn_log VALUE(null,'$my_instid','','$id','','$mybvn_data','$ibvn_fee','$wallet_date_time','$rOrderID')") or die ("Error6: " . mysqli_error($link))));
          
            ($userBvn == "" ? "" : ($ResponseCode != "200" && $ResponseCode != "" ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$my_instid','$rOrderID','$userBvn','','$ibvn_fee','Debit','NGN','BVN_Charges','Response: NGN$ibvn_fee was charged for Customer BVN Verification','successful','$wallet_date_time','','$wbalance','')") or die ("Error7: " . mysqli_error($link))));
          
            ($userBvn == "" ? "" : ($ResponseCode != "200" && $ResponseCode != "" ? "" : mysqli_query($link, "INSERT INTO income VALUES(null,'','$rOrderID','BVN','$ibvn_fee','$date_time','Employee BVN Verification Charges')") or die ("Error8: " . mysqli_error($link))));
          
            //echo ($otp_option === "No") ? '<meta http-equiv="refresh" content="5;url=/'.$mysenderID.'">' : '<meta http-equiv="refresh" content="3;url=/confirm_otp.php?id='.$mysenderID.'">';
            echo '<meta http-equiv="refresh" content="5;url=/'.$mysenderID.'">';
            echo '<br>';
            //echo ($otp_option === "No") ? "<p style='font-size:20px; color:blue;'>Account Created Successfully! Kindly check the message sent to your SMS/Email to proceed.</p>" : "<p style='font-size:20px; color:blue;'>Account Created Successfully! Kindly check the OTP sent to your phone to activate your account.</p>";
            echo "<p style='font-size:20px; color:blue;'>Account Created Successfully! Kindly check the message sent to your SMS/Email to proceed.</p>";
          
        }
    }
    elseif($acct_type == "Agent"){

        $full_name = $lname.' '.$fname;
        $encrypt = base64_encode($password);
        $global_role = "tqwjr_product_marketer";
        $status = "Completed";
        $id = "MEM".time();
    
        $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
        $r = mysqli_fetch_object($query);
        $sysabb = $mysenderID;
        $sys_email = $r->email;
    
        $wbalance = $iwallet_balance - $ibvn_fee;
        
        ($userBvn == "") ? "" : $processBVN = $newBV->walletAfricaBVNVerifier($walletafrica_skey,$userBvn,$link);
        ($userBvn == "") ? "" : $ResponseCode = $processBVN['ResponseCode'];
    
        if($iwallet_balance < $ibvn_fee && $userBvn != ""){
            echo "<p style='font-size:24px; color:orange;'>Sorry! You do not have sufficient fund in your Wallet for this verification</p>";
        }
        /* elseif($ResponseCode != "200" && $userBvn != ""){
            echo "<p style='font-size:24px; color:orange;'>Sorry, We're unable to verify user BVN at the moment, please try again later!! </p>".$ResponseCode;
        }*/
        else{

            ($preferredBank == "Monnify") ? $result = $newVA->monnifyVirtualAccount($accountName,$currencyCode,$customerEmail,$customerName,$userBvn,$mo_contract_code) : "";
            ($preferredBank == "Rubies Bank") ? $result1 = $newVA->rubiesVirtualAccount($accountName,$rubbiesSecKey) : "";
            ($preferredBank == "Flutterwave") ? $result1 = $newVA->wemaVirtualAccount($accountName,$customerEmail,$TxtReference,$rave_secret_key) : "";
            ($preferredBank == "Payant") ? $result1 = $newVA->sterlingPayantVirtualAcct($accountName,$customerEmail,$phoneNumber,$currencyCode,$country,$payantEmail,$payantPwd,$payantOrgId) : "";
            ($preferredBank == "Providus Bank") ? $result1 = $newVA->providusVirtualAccount($accountName,$userBvn,$providusClientId,$providusClientSecret) : "";
            ($preferredBank == "Wema Bank") ? $result1 = $newVA->directWemaVirtualAccount($accountName,$wemaVAPrefix) : "";
            ($preferredBank == "Sterling Bank") ? $result1 = $newVA->sterlingVirtualAccount($fname,$lname,$phoneNumber,$dob,$gender,$currencyCode,$sterlinkInputKey,$sterlingIv) : "";

            ($preferredBank == "Monnify" ? $myAccountReference = $result->responseBody->accountReference : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountReference = "EAVA-".time() : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountReference = $TxtReference : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myAccountReference = $result1['data']['_id'] : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : "")))))));
            ($preferredBank == "Monnify" ? $myAccountName = $result->responseBody->accountName : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountName = $result1['virtualaccountname'] : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountName = $accountName : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myAccountName = $result1['data']['accountName'] : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountName = $result1['account_name'] : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountName = $result1['account_name'] : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $myAccountName = $result1['data']['lastname'].' '.$result1['data']['firstname'] : "")))))));
            ($preferredBank == "Monnify" ? $myAccountNumber = $result->responseBody->accountNumber : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountNumber = $result1['virtualaccount'] : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountNumber = $result1['data']['account_number'] : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myAccountNumber = $result1['data']['accountNumber'] : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountNumber = $result1['account_number'] : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountNumber = $wemaVAPrefix.$result1['account_number'] : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $myAccountNumber = $result1['data']['VIRTUALACCT'] : "")))))));
            ($preferredBank == "Monnify" ? $myBankName = $result->responseBody->bankName : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myBankName = $result1['bankname'] : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myBankName = $result1['data']['bank_name'] : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myBankName = "Sterling Bank" : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myBankName = "Providus Bank" : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $myBankName = "Wema Bank" : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $myBankName = "Sterling Bank" : "")))))));
            ($preferredBank == "Monnify" ? $myStatus = $result->responseBody->status : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myStatus = "ACTIVE" : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myStatus = "ACTIVE" : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myStatus = $result1['data']['status'] : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myStatus = "ACTIVE" : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $myStatus = "ACTIVE" : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $myStatus = "ACTIVE" : "")))))));
            ($preferredBank == "Monnify" ? $date_time = $result->responseBody->createdOn : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $date_time = date("Y-m-d h:i:s") : "")))))));
            ($preferredBank == "Monnify" ? $provider = "monify" : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $provider = "rubies" : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $provider = "wema" : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $provider = "payant" : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $provider = "providus" : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $provider = "wema" : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $provider = "sterling" : "")))))));

            $transactionPin = substr((uniqid(rand(),1)),3,4);

            $VAccountDetails = ($myAccountNumber == "") ? "" : "Your Wallet Account Number is: ".$myAccountNumber.", Bank Name: ".$myBankName.",";
          
            $sms = "$sysabb>>>Welcome $fname! $VAccountDetails Username: $username, Password: $password and Transaction Pin: $transactionPin. Login here: $customDomain";
            
            $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$my_instid'");
            $fetch_myinst = mysqli_fetch_array($search_insti);
            $iwallet_balance = $fetch_myinst['wallet_balance'];
    
            //BVN Details
            $rOrderID = "EA-bvnCharges-".time();
            $bvn_picture = $processBVN['Picture'];
            $dynamicStr = md5(date("Y-m-d h:i"));
            $image_converted = $newBV->base64_to_jpeg($bvn_picture, $dynamicStr.".png");
    
            //20 array row
            $mybvn_data = $processBVN['BVN']."|".$processBVN['FirstName']."|".$processBVN['LastName']."|".$processBVN['MiddleName']."|".$processBVN['DateOfBirth']."|".$processBVN['PhoneNumber']."|".$processBVN['Email']."|".$processBVN['EnrollmentBank']."|".$processBVN['EnrollmentBranch']."|".$processBVN['Gender']."|".$processBVN['LevelOfAccount']."|".$processBVN['LgaOfOrigin']."|".$processBVN['LgaOfResidence']."|".$processBVN['MaritalStatus']."|".$processBVN['NameOnCard']."|".$processBVN['Nationality']."|".$processBVN['StateOfOrigin']."|".$processBVN['StateOfResidence']."|".$processBVN['Title']."|".$processBVN['WatchListed']."|".$image_converted;
              
            $max_per_page = 153;
            $sms_length = strlen($sms);
            $calc_length = ceil($sms_length / $max_per_page);
            $sms_charges = $calc_length * $sms_rate;
            $imywallet_balance = $iwallet_balance - $sms_charges;
            $sms_refid = "EA-smsCharges-".rand(1000000,9999999);
          
            $search_userme = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$my_instid' AND (role = 'agent_manager' OR role = 'institution_super_admin' OR role = 'merchant_super_admin') ORDER BY id ASC");
            $fetch_userme = mysqli_fetch_array($search_userme);
            $prefix = $fetch_userme['bprefix'];
    
            $posPIN = substr((uniqid(rand(),1)),3,6);
            
            $insert = mysqli_query($link, "INSERT INTO user VALUES(null,'$fname','$lname','$mname','$email','$phone','','','$origin_city','$origin_province','','$origin_countryCode','Not-Activated','$username','$encrypt','$id','','$global_role','','Registered','$my_instid','$prefix','$transactionPin','0.0','','0.0','$myAccountReference','$myAccountNumber','$preferredBank','$ussdcode2','$gender','$dob','Disallow','Disallow','Disallow','$wallet_date_time','','','','','Pending','agent','$sUserID','NULL','No','NULL','Yes','$posPIN','0.0')") or die ("Error1: " . mysqli_error($link));
    
            ($myAccountName != "" && $myAccountNumber != "") ? mysqli_query($link, "INSERT INTO virtual_account VALUES(null,'$myAccountReference','$id','$myAccountName','$myAccountNumber','$myBankName','$myStatus','$date_time','$provider','$my_instid','agent','Pending','','1000000','100000','10000','5000')") or die ("Error2: " . mysqli_error($link)) : "";
    
            $insert = mysqli_query($link, "INSERT INTO activate_member2 VALUES(null,'$shortenedurl','$otpCode','No','$id')") or die ("Error3: " . mysqli_error($link));
            
            mysqli_query($link, "INSERT INTO ip_tracker VALUES(null,'$ip','$my_instid','$id','$accountName','$date_time')") or die ("Error3i: " . mysqli_error($link));
    
            ($userBvn == "" ? "" : ($ResponseCode != "200" && $ResponseCode != "" ? "" : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$wbalance' WHERE institution_id = '$my_instid'") or die ("Error4: " . mysqli_error($link))));
    
            ($userBvn == "" ? "" : ($ResponseCode != "200" && $ResponseCode != "" ? "" : mysqli_query($link, "INSERT INTO bvn_log VALUE(null,'$my_instid','','$id','','$mybvn_data','$ibvn_fee','$wallet_date_time','$rOrderID')") or die ("Error5: " . mysqli_error($link))));
                                    
            ($userBvn == "" ? "" : ($ResponseCode != "200" && $ResponseCode != "" ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$my_instid','$rOrderID','$userBvn','','$ibvn_fee','Debit','NGN','BVN_Charges','Response: NGN$ibvn_fee was charged for Customer BVN Verification','successful','$wallet_date_time','','$wbalance','')") or die ("Error6: " . mysqli_error($link))));
                                    
            ($userBvn == "" ? "" : ($ResponseCode != "200" && $ResponseCode != "" ? "" : mysqli_query($link, "INSERT INTO income VALUES(null,'','$rOrderID','BVN','$ibvn_fee','$date_time','Employee BVN Verification Charges')") or die ("Error7: " . mysqli_error($link))));
           
            //SMS NOTIFICATION
            ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $my_instid, $sms_refid, $sms_charges, '', $imywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $my_instid, $sms_refid, $sms_charges, '', $imywallet_balance, $debitWallet) : ""));
            //EMAIL NOTIFICATION
            $sendSMS->staffRegEmailNotifier($email, $sysabb, $fullname, $shortenedurl, $username, $password, $shortenedurl1, $emailConfigStatus, $fetch_emailConfig);
    
            echo '<meta http-equiv="refresh" content="5;url=/'.$mysenderID.'">';
            echo '<br>';
            echo "<p style='font-size:20px; color:blue;'>Account Created Successfully!...Kindly check the mail and sms sent to proceed!!</p>";     

        }

    }
    
}
?>
</div>

 		         <div class="box-body">
 		                 
                  <input type="hidden" class="form-control" name="id" value="<?php echo $mysenderID; ?>"/>

                <?php
                $explodeMyVA = explode(",",$iva_provider);
                $countMyVA = count($explodeMyVA);
                if($countMyVA == 0){
                  echo '<input type="hidden" class="form-control" name="preferredBank" value=""/>';
                }elseif($countMyVA == 1){
                ?>
                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">Preferred Bank</label>
                      <div class="col-sm-7">
                      <input name="preferredBank" type="text" class="form-control" value="<?php echo $iva_provider; ?>" readonly="readonly" /required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                      </div>
                <?php
                }else{
                ?>
                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">Preferred Bank</label>
                      <div class="col-sm-7">
                          <select name="preferredBank" class="form-control select2" /required>
                            <option value="" selected="selected">---Select Bank of your choice---</option>
                            <?php
                            $explodeVA = explode(",",$iva_provider);
                            $countVA = (count($explodeVA) - 1);
                            for($i = 0; $i <= $countVA; $i++){
                                echo '<option value="'.$explodeVA[$i].'">'.(($explodeVA[$i] == "Payant" || $explodeVA[$i] == "Monnify") ? "Sterling Bank" : ($explodeVA[$i] == "Flutterwave" ? "Wema Bank" : $explodeVA[$i])).'</option>';
                            }
                            ?>
                        </select>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>
                <?php
                }
                ?>

                  <?php
                  if($product_manager == "On"){
                  ?>
                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">Account Type</label>
                      <div class="col-sm-7">
                          <select name="acct_type" class="form-control" id="acct_type" required>
                          <?php
                          if(isset($_GET['ainv'])){

                            $invCode1 = $_GET['ainv'];
                            $searchInviteCode = mysqli_query($link, "SELECT * FROM invite WHERE invite_code = '$invCode1' AND (status = 'Sent' OR status = 'Clicked')");
                            $fetchInviteCode = mysqli_fetch_array($searchInviteCode);
                            $concat = $fetchInviteCode['mydata'];
                            $parameter = (explode('|',$concat));
                            $itype = $parameter[4];
                            $itranslateType = ($itype == "1") ? "Agent" : "Individual";

                            echo '<option value="'.$itranslateType.'" selected="selected">'.$itranslateType.'</option>';
                          
                          }
                          elseif(isset($_GET['inv'])){

                            echo '<option value="Individual" selected="selected">Individual</option>';

                          }
                          else{
                          ?>
                              <option value="" selected='selected'>Select Account Type&hellip;</option>
                              <option value="Individual">Individual</option>
                              <option value="Agent">Agent</option>
                          <?php
                          }
                          ?>
                          </select>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  <?php
                  }
                  else{
                  ?>
                  <input type="hidden" class="form-control" name="acct_type" value="Individual"/>
                  <?php
                  }
                  ?>
                        
     		         <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">First Name</label>
                      <div class="col-sm-7">
                      <input name="fname" type="text" class="form-control" placeholder="Your First Name" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                      </div>

                      <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">Middle Name</label>
                      <div class="col-sm-7">
                      <input name="mname" type="text" class="form-control" placeholder="Your Middle Name (Optional)">
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                      </div>
                      
                      <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">Last Name</label>
                      <div class="col-sm-7">
                      <input name="lname" type="text" class="form-control" placeholder="Your Last Name" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                      </div>
                    
                      <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; //id="vbemail" onkeyup="veryEmail();" ?>;">Email Address</label>
                      <div class="col-sm-7">
                      <input name="email" type="email" class="form-control" placeholder="Your Email Address" required>
                      <div id="myvemail"></div>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                      </div>
<?php
function isMobileDevice(){
  return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
?>
                      <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; //id="vbphone" onkeyup="veryBPhone();" ?>;">Mobile Number</label>
                      <div class="col-sm-3">
                      <input name="phone" type="tel" class="form-control" id="phone" required>
                      <div id="myvphone"></div>
                      </div><?php echo (isMobileDevice()) ? "<br>" : ""; ?>
                      <label for="" class="col-sm-1 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">Gender</label>
                      <div class="col-sm-3">
                            <select name="gender" class="form-control" required>
                                        <option value="" selected='selected'>Select Gender&hellip;</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                            </select>
                        </div>  
                    </div>
                      
<?php
//MINIMUM DATE
$min_date = new DateTime(date("Y-m-d"));
$min_date->sub(new DateInterval('P60Y'));
$mymin_date = $min_date->format('Y-m-d');

//MAXIMUM DATE
$max_date = new DateTime(date("Y-m-d"));
$max_date->sub(new DateInterval('P18Y'));
$mymax_date = $max_date->format('Y-m-d');
?>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">Date of Birth</label>
                  <div class="col-sm-7">
                  <input name="dob" type="date" class="form-control" placeholder="Date Format: mm/dd/yyyy" id="txtDate" min="<?php echo $mymin_date; ?>" max="<?php echo $mymax_date; ?>" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                </div>

                <input name="bvn" type="hidden" class="form-control" value="" placeholder="BVN Number">

                <!--<div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">BVN Number</label>
                      <div class="col-sm-7">
                          <input name="bvn" type="text" class="form-control" placeholder="BVN Number">
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>
                -->
                      
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

                    <?php
                    if(isset($_GET['ainv'])){

                      $ainvCode = $_GET['ainv'];
                      $searchinvite = mysqli_query($link, "SELECT * FROM invite WHERE invite_code = '$ainvCode' AND (status = 'Sent' OR status = 'Clicked')");
                      $numInvite = mysqli_num_rows($searchinvite);
                      $fetchinvite = mysqli_fetch_array($searchinvite);
                      $staffUserID = $fetchinvite['userid'];

                      ($numInvite == 1) ? mysqli_query($link, "UPDATE invite SET status = 'Clicked' WHERE invite_code = '$ainvCode' AND userid = '$staffUserID' AND status = 'Sent'") : "";
                      
                      $searchUser = mysqli_query($link, "SELECT * FROM user WHERE id = '$staffUserID'");
                      $fetchUser = mysqli_fetch_array($searchUser);
                      $staffName = ($numInvite == 0) ? "" : $fetchUser['name'].' '.$fetchUser['lname'].' '.$fetchUser['mname'];
                    ?>

                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">Referrer</label>
                      <div class="col-sm-7">
                      <input name="staffName" type="text" class="form-control" value="<?php echo $staffName; ?>" placeholder="Your Referral" readonly="readonly" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                      </div>

                    <?php
                    }
                    else{
                      //Do Nothing
                    }
                    ?>


                    <?php
                    if(isset($_GET['inv'])){
                      $invCode = $_GET['inv'];
                      $searchUser = mysqli_query($link, "SELECT * FROM user WHERE id = '$invCode'");
                      $fetchUser = mysqli_fetch_array($searchUser);
                      $staffName = $fetchUser['name'].' '.$fetchUser['lname'].' '.$fetchUser['mname'];
                    ?>

                    <input name="refferral" type="hidden" class="form-control" value="<?php echo $_GET['inv']; ?>">

                    <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?>;">Referrer</label>
                      <div class="col-sm-7">
                      <input name="staffName" type="text" class="form-control" value="<?php echo $staffName; ?>" placeholder="Your Referral" readonly="readonly" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                    </div>
                    
                    <?php
                    }
                    else{
                      //Do Nothing
                    }
                    ?>
                      
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
                      <?php echo (isset($_GET['id'])) ? '<a href="/'.$_GET['id'].'"><button type="button" class="btn bg-black btn-flat"><i class="fa fa-reply-all">&nbsp;Go Back</i></button></a>' : '<a href="/"><button type="button" class="btn bg-yellow btn-flat"><i class="fa fa-reply-all">&nbsp;Go Back</i></button></a>'; ?>
                      <button name="indiv_register" type="submit" class="btn bg-<?php echo ($mysenderID != "") ? 'black' : 'blue'; ?> btn-flat"><i class="fa fa-cloud-upload">&nbsp;Register</i></button> 
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                      </div>
                      
                     <div class="form-group" align="right">
                      <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                      <div class="col-sm-7">
                        
                    </div>
                    <label for="" class="col-sm-2 control-label"></label>
                    </div>
                    
                </form>
                
                <?php
 		         }
 		         else{
 		         ?>
 		         
 		         <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_reg.php<?php echo (isset($_GET['id']) == true) ? '?id='.$_GET['id'] : ''; ?>">

 		             <div class="box-body">
 		         
              <input type="hidden" class="form-control" name="myreferral" id="myreferral" value="<?php echo (isset($_GET['rf']) == true) ? $_GET['rf'] : ''; ?>"/>
 		            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Registration Type</label>
                  <div class="col-sm-7">
                    <select name="account_type" class="form-control" id="accounttype" required>
                                <option value="" selected='selected'>SELECT TYPE&hellip;</option>
                                <!--<option value="Customer">Individual Account</option>-->
                                <option value="Corporate">Corporate Account</option>
                                <!--<option value="Aggregator">Aggregator Account</option>-->
                                <!--<option value="Activate">Activate Account</option>-->
                                <!--<option value="Cooperative">Group</option>-->
                    </select>
                </div>
                <label for="" class="col-sm-2 control-label"></label>
                </div>

                <span id='ShowValueFrank'></span>
                <span id='ShowValueFrank'></span>
				
 					 </div>
            
         <div class="form-group" align="right">
                  <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                  <div class="col-sm-7">
                    <?php echo (isset($_GET['id'])) ? '<a href="/'.$_GET['id'].'"><button type="button" class="btn bg-black btn-flat"><i class="fa fa-reply-all">&nbsp;Goto Login</i></button></a>' : '<a href="account/index"><button type="button" class="btn bg-yellow btn-flat"><i class="fa fa-reply-all">&nbsp;Goto Login</i></button></a>'; ?>
                </div>
                <label for="" class="col-sm-2 control-label"></label>
                </div>
			  
 					 </form> 
 					 
 			<?php } ?>


 		</div>	
 		</div>
	</div>
	</section>
		
<!-- /.login-box -->


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="vendor/jquery/jquery-1.9.1.min.js"><\/script>')</script>
<script src="vendor/jquery/main.js"></script>
	
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

<script src="intl-tel-input-master/build/js/intlTelInput.js"></script>
  <script>
        var countryData = window.intlTelInputGlobals.getCountryData(),
        input = document.querySelector("#phone");       
        var iti = window.intlTelInput(input, {
          // allowDropdown: false,
          // autoHideDialCode: false,
          // autoPlaceholder: "off",
          // dropdownContainer: document.body,
          // excludeCountries: ["us"],
          // formatOnDisplay: false,
          geoIpLookup: function(callback) {
            $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
              var countryCode = (resp && resp.country) ? resp.country : "";
              callback(countryCode);
            });
          },

         //hiddenInput: "full",
        initialCountry: "ng",
          // localizedCountries: { 'de': 'Deutschland' },
         //nationalMode: false,
          // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
          // placeholderNumberType: "MOBILE",
          // preferredCountries: ['cn', 'jp'],
        //separateDialCode: true,
        utilsScript: "intl-tel-input-master/build/js/utils.js",
        });

        input.addEventListener("countrychange", function(e) {
          // do something with iti.getSelectedCountryData()
         //var countryData = iti.getSelectedCountryData().dialCode + document.getElementById("phone").value;
         input.value = '';
         input.value = '+' + iti.getSelectedCountryData().dialCode + document.getElementById("phone").value;
        });     

        function myFunction() {
          phonefull = document.getElementById("phone").value;
          if(phonefull == ""){
            input.value = '+' + iti.getSelectedCountryData().dialCode;
          }else{
            input.value = document.getElementById("phone").value;
          }
        }
        
        document.addEventListener("DOMContentLoaded", function() {
          myFunction();
        });
        
  </script>
  
<?php

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

</body>
</html>