<?php 
error_reporting(0); 
//ini_set('display_errors', true);
include "../config/session.php";
require_once "../config/smsAlertClass.php";
?>  
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <?php 
$call_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
$fetch_msmset = mysqli_fetch_array($call_memset);
$call = mysqli_query($link, "SELECT * FROM systemset");
if(mysqli_num_rows($call) == 0)
{
echo "<script>alert('Data Not Found1!'); </script>";
}
else
{
while($row = mysqli_fetch_assoc($call)){ 
?>

<link href="<?php echo ($bbranchid == "") ? $fetchsys_config['file_baseurl'].$row['image'] : $fetchsys_config['file_baseurl'].$fetch_msmset['logo']; ?>" rel="icon" type="dist/img">
<?php }}?>
  <?php 
	$call = mysqli_query($link, "SELECT * FROM systemset");
	$row = mysqli_fetch_assoc($call);
	?>
  <title><?php echo ($bbranchid == "") ? $row['title'] : $fetch_msmset['cname']; ?></title>
   <!-- Tell the browser to be responsive to screen width -->
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  
  <!--<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">-->
  
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  
  
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  
  
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="../plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="../plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- bootstrap slider -->
  <link rel="stylesheet" href="../plugins/bootstrap-slider/slider.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="../plugins/datepicker/datepicker3.css">
  <!-- bootstrap slider -->
  <link rel="stylesheet" href="../plugins/bootstrap-slider/slider.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  
  <link rel="stylesheet" href="../plugins/select2/select2.min.css">

  <link rel="stylesheet" href="../intl-tel-input-master/build/css/intlTelInput.css">
  
  <style type="text/css">
    /* Slideshow container */
    .slideshow-container {
      position: relative;
    }
    
    /* Slides */
    .mySlides {
      display: none;
      padding: 0px;
      text-align: center;
    }
    
    /* mynext & myprevious buttons */
    .myprev, .mynext {
      cursor: pointer;
      position: absolute;
      top: 50%;
      width: auto;
      margin-top: -30px;
      padding: 16px;
      color: #888;
      font-weight: bold;
      font-size: 20px;
      border-radius: 0 3px 3px 0;
      user-select: none;
    }
    
    /* Position the "mynext button" to the right */
    .mynext {
      position: absolute;
      right: 0;
      border-radius: 3px 0 0 3px;
    }
    
    /* On hover, add a black background color with a little bit see-through */
    .myprev:hover, .mynext:hover {
      background-color: rgba(0,0,0,0.8);
      color: white;
    }
    
    /* The dot/bullet/indicator container */
    .dot-container {
      text-align: center;
      padding: 5px;
      background: #ddd;
    }
    
    /* The dots/bullets/indicators */
    .dot {
      cursor: pointer;
      height: 15px;
      width: 15px;
      margin: 0 2px;
      background-color: #bbb;
      border-radius: 50%;
      display: inline-block;
      transition: background-color 0.6s ease;
    }
    
    /* Add a background color to the active dot/circle */
    .active, .dot:hover {
      background-color: white;
    }
    
    /* Add an italic font style to all quotes */
    q {font-style: italic;}
    
    /* Add a blue color to the author */
    .author {color: cornflowerblue;}
    </style>
  
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
  
  <style> 
        .iti { width: 100%; }
        
        .payment-title {
            width: 100%;
            text-align: center;
        }
        
        .form-container .field-container:first-of-type {
            grid-area: name;
        }
        
        .form-container .field-container:nth-of-type(2) {
            grid-area: number;
        }
        
        .form-container .field-container:nth-of-type(3) {
            grid-area: expiration;
        }
        
        .form-container .field-container:nth-of-type(4) {
            grid-area: security;
        }
        
        .field-container input {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }
        
        .field-container {
            position: relative;
        }
        
        .form-container {
            display: grid;
            grid-column-gap: 10px;
            grid-template-columns: auto auto;
            grid-template-rows: 90px 90px 90px;
            grid-template-areas: "name name""number number""expiration security";
            max-width: 400px;
            padding: 20px;
            color: #707070;
        }
        
        label {
            padding-bottom: 5px;
            font-size: 13px;
        }
        
        input {
            margin-top: 3px;
            padding: 15px;
            font-size: 16px;
            width: 100%;
            border-radius: 3px;
            border: 1px solid #dcdcdc;
        }
        
        .ccicon {
            height: 38px;
            position: absolute;
            right: 6px;
            top: calc(50% - 17px);
            width: 60px;
        }
        
        /* CREDIT CARD IMAGE STYLING */
        .preload * {
            -webkit-transition: none !important;
            -moz-transition: none !important;
            -ms-transition: none !important;
            -o-transition: none !important;
        }
        
        .container {
            width: 100%;
            max-width: 400px;
            max-height: 251px;
            height: 54vw;
            padding: 20px;
        }
        
        #ccsingle {
            position: absolute;
            right: 15px;
            top: 20px;
        }
        
        #ccsingle svg {
            width: 100px;
            max-height: 60px;
        }
        
        .creditcard svg#cardfront,
        .creditcard svg#cardback {
            width: 100%;
            -webkit-box-shadow: 1px 5px 6px 0px black;
            box-shadow: 1px 5px 6px 0px black;
            border-radius: 22px;
        }
        
        #generatecard{
            cursor: pointer;
            float: right;
            font-size: 12px;
            color: #fff;
            padding: 2px 4px;
            background-color: #909090;
            border-radius: 4px;
            cursor: pointer;
            float:right;
        }
        
        /* CHANGEABLE CARD ELEMENTS */
        .creditcard .lightcolor,
        .creditcard .darkcolor {
            -webkit-transition: fill .5s;
            transition: fill .5s;
        }
        
        .creditcard .lightblue {
            fill: #03A9F4;
        }
        
        .creditcard .lightbluedark {
            fill: #0288D1;
        }
        
        .creditcard .red {
            fill: #ef5350;
        }
        
        .creditcard .reddark {
            fill: #d32f2f;
        }
        
        .creditcard .purple {
            fill: #ab47bc;
        }
        
        .creditcard .purpledark {
            fill: #7b1fa2;
        }
        
        .creditcard .cyan {
            fill: #26c6da;
        }
        
        .creditcard .cyandark {
            fill: #0097a7;
        }
        
        .creditcard .green {
            fill: #66bb6a;
        }
        
        .creditcard .greendark {
            fill: #388e3c;
        }
        
        .creditcard .lime {
            fill: #d4e157;
        }
        
        .creditcard .limedark {
            fill: #afb42b;
        }
        
        .creditcard .yellow {
            fill: #ffeb3b;
        }
        
        .creditcard .yellowdark {
            fill: #f9a825;
        }
        
        .creditcard .orange {
            fill: #ff9800;
        }
        
        .creditcard .orangedark {
            fill: #ef6c00;
        }
        
        .creditcard .grey {
            fill: #bdbdbd;
        }
        
        .creditcard .greydark {
            fill: #616161;
        }
        
        /* FRONT OF CARD */
        #svgname {
            text-transform: uppercase;
        }
        
        #cardfront .st2 {
            fill: #FFFFFF;
        }
        
        #cardfront .st3 {
            font-family: 'Source Code Pro', monospace;
            font-weight: 600;
        }
        
        #cardfront .st4 {
            font-size: 54.7817px;
        }
        
        #cardfront .st5 {
            font-family: 'Source Code Pro', monospace;
            font-weight: 400;
        }
        
        #cardfront .st6 {
            font-size: 33.1112px;
        }
        
        #cardfront .st7 {
            opacity: 0.6;
            fill: #FFFFFF;
        }
        
        #cardfront .st8 {
            font-size: 24px;
        }
        
        #cardfront .st9 {
            font-size: 36.5498px;
        }
        
        #cardfront .st10 {
            font-family: 'Source Code Pro', monospace;
            font-weight: 300;
        }
        
        #cardfront .st11 {
            font-size: 16.1716px;
        }
        
        #cardfront .st12 {
            fill: #4C4C4C;
        }
        
        /* BACK OF CARD */
        #cardback .st0 {
            fill: none;
            stroke: #0F0F0F;
            stroke-miterlimit: 10;
        }
        
        #cardback .st2 {
            fill: #111111;
        }
        
        #cardback .st3 {
            fill: #F2F2F2;
        }
        
        #cardback .st4 {
            fill: #D8D2DB;
        }
        
        #cardback .st5 {
            fill: #C4C4C4;
        }
        
        #cardback .st6 {
            font-family: 'Source Code Pro', monospace;
            font-weight: 400;
        }
        
        #cardback .st7 {
            font-size: 27px;
        }
        
        #cardback .st8 {
            opacity: 0.6;
        }
        
        #cardback .st9 {
            fill: #FFFFFF;
        }
        
        #cardback .st10 {
            font-size: 24px;
        }
        
        #cardback .st11 {
            fill: #EAEAEA;
        }
        
        #cardback .st12 {
            font-family: 'Rock Salt', cursive;
        }
        
        #cardback .st13 {
            font-size: 37.769px;
        }
        
        /* FLIP ANIMATION */
        .container {
            perspective: 1000px;
        }
        
        .creditcard {
            width: 100%;
            max-width: 400px;
            -webkit-transform-style: preserve-3d;
            transform-style: preserve-3d;
            transition: -webkit-transform 0.6s;
            -webkit-transition: -webkit-transform 0.6s;
            transition: transform 0.6s;
            transition: transform 0.6s, -webkit-transform 0.6s;
            cursor: pointer;
        }
        
        .creditcard .front,
        .creditcard .back {
            position: absolute;
            width: 100%;
            max-width: 400px;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            -webkit-font-smoothing: antialiased;
            color: #47525d;
        }
        
        .creditcard .back {
            -webkit-transform: rotateY(180deg);
            transform: rotateY(180deg);
        }
        
        .creditcard.flipped {
            -webkit-transform: rotateY(180deg);
            transform: rotateY(180deg);
        }
    </style> 
  
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<link rel="stylesheet" href="../dist/css/style.css" />
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
  <script type="text/javascript" src="../dist/js/calendar.js"></script>
    <strong> <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css"></strong>
    
<!-- Datatable new code -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>

 <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
  
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    
<script type="text/javascript">
  function veryUsername()
  {
   var verify_username=document.getElementById("vusername").value;

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
  
  function veryEmail()
  {
   var verify_email=document.getElementById("vemail").value;
   
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
  
  function veryPhone()
  {
   var verify_phone=document.getElementById("vphone").value;
   
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

<script type="text/javascript">
  function veryBUsername()
  {
   var verify_username=document.getElementById("vbusername").value;

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
     $('#mybusername').html(response);
    }
    });
   }
   else
   {
    $('#mybusername').html("<label class='label label-danger'>Enter Unique Username</label>");
   }
  }
  
  function veryBEmail()
  {
   var verify_email=document.getElementById("vbemail").value;
   
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
     $('#myvbemail').html(response);
    }
    });
   }
   else
   {
    $('#myvbemail').html("<label class='label label-danger'>Enter Valid Email Address</label>");
   }
  }
  
  function veryBPhone()
  {
   var verify_ccode=document.getElementById("myccode").value;
   var verify_phone=document.getElementById("vbphone").value;
   
   if(verify_phone)
   {
    $.ajax({
    type: "POST",
    url: "verify_username.php",
    data: {
      my_ccode: verify_ccode,
      my_phone: verify_phone
    },
    success: function(response) {
     // We get the element having id of display_info and put the response inside it
      //alert(response);
     $('#myvbphone').html(response);
    }
    });
   }
   else
   {
    $('#myvbphone').html("<label class='label label-danger'>Enter Phone Number</label>");
   }
  }
  
  function verifyVA()
  {
   var verify_va=document.getElementById("verify_virtualacct").value;

   if(verify_va)
   {
    $.ajax({
    type: "POST",
    url: "verify_va.php",
    data: {
      my_va: verify_va,
    },
    success: function(response) {
     // We get the element having id of display_info and put the response inside it
      //alert(response);
     $('#myVA').html(response);
    }
    });
   }
   else
   {
    $('#myVA').html("<label class='label label-danger'>Enter Recipient Wallet Account Number</label>");
   }
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

<script type="text/javascript">
function loaddata2()
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
   $('#bvn3').html(response);
  }
  });
 }

 else
 {
  $('#bvn3').html("<p class='label label-success'>Please Enter Correct BVN Number Here</p>");
 }
}
</script>

<script type="text/javascript">
function loadaccount()
{
 var actnumb=document.getElementById("account_number").value;
 var bcode=document.getElementById("bank_code").value;

 if(actnumb && bcode)
 {
  $.ajax({
  type: "POST",
  url: "verify_actnumber.php",
  data: {
    my_actno: actnumb,
    my_bcode: bcode
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#act_numb').html(response);
  }
  });
 }

 else
 {
  $('#act_numb').html("<p class='label label-success'>Please Enter Correct Account Number Here</p>");
 }
}
</script>

<script type="text/javascript">
function loadCustomerDetails()
{
 var custid=document.getElementById("customerid").value;
 var bcode=document.getElementById("bcode").value;

 if(custid)
 {
  $.ajax({
  type: "POST",
  url: "verify_customerid.php",
  data: {
    my_custid: custid,
    my_bcode: bcode
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#cust_name').html(response);
  }
  });
 }

 else
 {
  $('#cust_name').html("<p class='label label-success'>Please Enter Correct Account Number/IUC Number Here</p>");
 }
}
</script>


<script type="text/javascript">
function loadMyCustomerDetails()
{
 var custid=document.getElementById("customerid").value;
 var serviceid=document.getElementById("serviceid").value;
 var productcode=document.getElementById("productcode").value;
 
 if(custid)
 {
  $.ajax({
  type: "POST",
  url: "verify_mycustomerid.php",
  data: {
    my_custid: custid,
    my_serviceid: serviceid,
    my_productcode: productcode
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#cust_name').html(response);
  }
  });
 }

 else
 {
  $('#cust_name').html("<p class='label label-success'>Please Enter Correct Account Number/IUC Number Here</p>");
 }
}
</script>


<script type="text/javascript">
function fetchbanklist()
{
 var actnumb=document.getElementById("accountNo").value;
 var bcode=document.getElementById("bankCode").value;

 if(actnumb && bcode)
 {
  $.ajax({
  type: "POST",
  url: "verify_actnumber2.php",
  data: {
    my_actno: actnumb,
    my_bcode: bcode
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#act_numb').html(response);
  }
  });
 }

 else
 {
  $('#act_numb').html("<p class='label label-success'>Please Enter Correct Account Number Here</p>");
 }
}

function fetchsterlingbanklist()
{
 var actnumb=document.getElementById("accountNo").value;
 var bcode=document.getElementById("sterlingBankCode").value;

 if(actnumb && bcode)
 {
  $.ajax({
  type: "POST",
  url: "verify_actnumber3.php",
  data: {
    my_actno: actnumb,
    my_bcode: bcode
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#act_numb').html(response);
  }
  });
 }

 else
 {
  $('#act_numb').html("<p class='label label-success'>Please Enter Correct Account Number Here</p>");
 }
}

function fetchrubiesbanklist()
{
 var actnumb=document.getElementById("accountNo").value;
 var bcode=document.getElementById("rubiesBankCode").value;

 if(actnumb && bcode)
 {
  $.ajax({
  type: "POST",
  url: "verify_actnumber4.php",
  data: {
    my_actno: actnumb,
    my_bcode: bcode
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#act_numb').html(response);
  }
  });
 }

 else
 {
  $('#act_numb').html("<p class='label label-success'>Please Enter Correct Account Number Here</p>");
 }
}
</script>

<script type="text/javascript">
function loadbranchcode()
{
 var bank_id=document.getElementById("bank_id").value;

 if(bank_id)
 {
  $.ajax({
  type: "POST",
  url: "verify_bankid.php",
  data: {
    my_bcode: bank_id
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#branch_code').html(response);
  }
  });
 }

 else
 {
  $('#branch_code').html("<p class='label label-success'>No Bank Selected!</p>");
 }
}
</script>

<script type="text/javascript">
function loadbank()
{
 var country=document.getElementById("country").value;

 if(country)
 {
  $.ajax({
  type: "POST",
  url: "list_bank.php",
  data: {
    my_country: country
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#bank_list').html(response);
  }
  });
 }

 else
 {
  $('#bank_list').html("<p class='label label-success'>Please Select Country</p>");
 }
}
</script>

<script type="text/javascript">
function loadbank1()
{
 var country=document.getElementById("country").value;

 if(country)
 {
  $.ajax({
  type: "POST",
  url: "list_bank1.php",
  data: {
    my_country: country
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#bank_list1').html(response);
  }
  });
 }

 else
 {
  $('#bank_list1').html("<p class='label label-success'>Please Select Country</p>");
 }
}
</script>

<script type="text/javascript">
function loadpB()
{
 var pbid=document.getElementById("product_id").value;
 var category=document.getElementById("cat").value;


 if(pbid)
 {
  $.ajax({
  type: "POST",
  url: "verify_plist.php",
  data: {
    my_pbid: pbid,
    my_cat: category
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#get_product').html(response);
  }
  });
 }

 else
 {
  $('#get_product').html("<p class='label label-success'>Please Enter Correct PRODUCT ID Here</p>");
 }
}
</script>

<script type="text/javascript">
function load_billcat()
{
 var myservice=document.getElementById("bill_cat").value;
 //var myplans=document.getElementById("plan_list").value;
 //var myscard=document.getElementById("my_scard").value;
 
 if(myservice)
 {
  $.ajax({
  type: "POST",
  url: "validate_billsproduct.php",
  data: {
    service_name: myservice
  },
  success: function(response) {
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#product_list').html(response);
  }
  });
 }
 
 else
 {
  $('#product_list').html("<p class='label bg-orange'>Waiting...</p>");
 }
 
}
</script>

<script type="text/javascript">
function load_airtime()
{
 var my_aphone=document.getElementById("my_phone_no").value;
 //var myplans=document.getElementById("plan_list").value;
 //var myscard=document.getElementById("my_scard").value;
 
 if(my_aphone)
 {
  $.ajax({
  type: "POST",
  url: "validate_airtimelist.php",
  data: {
    myphone: my_aphone
  },
  success: function(response) {
      //getProgress();
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#airtime_list').html(response);
  }
  });
 }
 
 else
 {
  $('#airtime_list').html("<p class='label bg-orange'>Loading...</p>");
 }
 
}
</script>

<script type="text/javascript">
//Start the long running process
    $.ajax({
        url: 'long_process.php',
        success: function(data) {
        }
    });
    //Start receiving progress
    function getProgress(){
        $.ajax({
            url: 'progress.php',
            success: function(data) {
                $("#progress").html(data);
                if(data<10){
                    getProgress();
                }
            }
        });
    }
</script>

<script type="text/javascript">
function load_databundle()
{
 var myaphone=document.getElementById("myphone_no").value;
 //var myplans=document.getElementById("plan_list").value;
 //var myscard=document.getElementById("my_scard").value;
 
 if(myaphone)
 {
  $.ajax({
  type: "POST",
  url: "validate_databundle.php",
  data: {
    a_myphone: myaphone
  },
  success: function(response) {
      //getProgress();
   // We get the element having id of display_info and put the response inside it
    //alert(response);
   $('#databundle_list').html(response);
  }
  });
  
 }
 
 else
 {
  $('#databundle_list').html("<p class='label bg-orange'>Data Loading...</p>");
 }
 
}
</script>

<script language="javascript" type="text/javascript">
	$().ready(function () {
		$('.modal.printable').on('shown.bs.modal', function() {
			$('modal-dialog', this).addClass('focused');
			$('body').addClass('modalprinter');
			
		if($(this).hasClass('autoprint')) {
			window.print();
		}
		}).on('hidden.bs.modal', function() {
			$('modal-dialog', this).removeClass('focused');
			$('body').removeClass('modalprinter');
		});
	});
</script>

<script language="javaScript">
<!-- 	
function enable_text(status)
{
//alert(status);
status=!status;	
document.f1.bcountry.disabled = status;
document.f1.currency.disabled = status;
}
//  End -->
</script>

<script type="text/javascript"> 
jQuery(document).ready(function(){
  jQuery(function() {
        jQuery(this).bind("contextmenu", function(event) {
            event.mypreventDefault();
        });
    });
});
</script>
<?php 
	$call = mysqli_query($link, "SELECT * FROM systemset");
	$row = mysqli_fetch_array($call);
	
	$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
  $myaltrow = mysqli_fetch_array($myaltcall);
	?>
	
<style>
      .frame {
       height: 250px;
      width: 669px;
      border-color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> <?php echo ($myaltrow['theme_color'] == '') ? '#1c87c9' : $myaltrow['theme_color']; ?>;
      border-image: none;
      border-radius: 0 0 0 0;
      border-style: solid;
      border-width: 10px;
      }
    </style>

</head>
<!--    onload="document.body.style.opacity='1'"    -->   
<body class="hold-transition skin-<?php echo ($myaltrow['theme_color'] == '') ? $row['theme_color'] : $myaltrow['theme_color']; ?> sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed";>
    
    <div id="loader-wrapper">
			<div id="loader"></div>

			<div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>

		</div>