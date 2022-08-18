<?php 
session_start();
error_reporting(E_ALL);
include "config/connect.php";
?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
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

<link href="img/<?php echo $row['image']; ?>" rel="icon" type="dist/img">
<?php }}?> 

<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="plugins/morris/morris.css">
  <!-- bootstrap slider -->
  <link rel="stylesheet" href="plugins/bootstrap-slider/slider.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  
  <link rel="stylesheet" href="plugins/select2/select2.min.css">
  
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<link rel="stylesheet" href="dist/css/style.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="dist/js/calendar.js"></script>
    <strong> <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css"></strong>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <style type="text/css">
<!--
.style1 {
  color: #FF0000;
  font-weight: bold;
}
-->
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
  
</head>
<body class="hold-transition login-page bg-blue">
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
?>
   <img src="img/<?php echo $row ['image'] ;?>" class="img-circle" alt="User Image" width="100" height="100">
   <h3 style="color: white;"><strong><?php echo $row ['name'];?></strong></h3>
   <h4 class="panel-title"><b style="color: white;">COOPERATIVE ADMINISTRATOR UPDATE FORM FOR APPROVAL REQUEST</b></h4>
   <?php }} ?>
  </div>
     <section class="content">
    
             <div class="box-body">
          <div class="panel panel-success">
            <div class="box-body">

 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<div class="box-body">

  <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Passport Photograph</label>
      <div class="col-sm-8">
               <input type='file' name="image" class="btn bg-orange" onChange="readURL(this);" required>
               <img id="blah"  src="avtar/user2.png" alt="Logo Here" height="100" width="100"/>
      </div>
      </div>

  <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Phone Number</label>
                  <div class="col-sm-8">
                  <input name="phone" type="text" class="form-control" placeholder="Your Personal Phone Number" required>
                  </div>
                  </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Email Address</label>
                  <div class="col-sm-8">
                  <input name="email" type="email" class="form-control" placeholder="Your Email Address" required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Meeting Frequency</label>
                  <div class="col-sm-8">
        <select name="meeting_freq"  class="form-control" required>
                    <option selected='selected'>Select Meeting Frequency&hellip;</option>
                    <option value="Weekly">Weekly</option>
                    <option value="Monthly">Monthly</option>
                    <option value="Daily">Daily</option>
        </select>
    </div>
    </div>
    
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Cooperative Registration Certificate:</label>
  <div class="col-sm-8">
           <input type="file" name="uploaded_file[]" class="btn bg-orange" required>
          <span style="color:blue">Accepted file types</span> <span style="color: orange;"><b>jpg, gif, png, doc, docx, pdf</b></span>
  </div>
  </div>
  
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Valid ID (President):</label>
  <div class="col-sm-8">
           <input type="file" name="uploaded_file[]" class="btn bg-orange" required>
          <span style="color:blue">Accepted file types</span> <span style="color: orange;"><b>jpg, gif, png, doc, docx, pdf</b></span>
  </div>
  </div>
  
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Utility Bill:</label>
  <div class="col-sm-8">
           <input type="file" name="uploaded_file[]" class="btn bg-orange">
          <span style="color:blue">Accepted file types</span> <span style="color: orange;"><b>jpg, gif, png, doc, docx, pdf</b></span>
  </div>
  </div>
  <hr>

  <div class="form-group">
              <label for="" class="col-sm-4 control-label" style="color:blue;">BVN Number (President)</label>
              <div class="col-sm-8">
          <span style="color: orange;"> <b>You need to verify your BVN here to Qualify as the Administrator of the Cooperative<i>(by displaying your Full Name as entered during the first step of registration)</i> as it will be checked when reviewing your application.</b> </span>
              <input name="unumber" type="text" id="unumber" onkeydown="loaddata();" class="form-control" placeholder="BVN Number Here" maxlength="11"><br>
         <div id="bvn2"></div>
              </div>
              </div>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-blue ks-btn-file" type="submit" name="Import_Valid_Doc"><span class="fa fa-cloud-upload"></span> Complete Application</button> 
       </div>
    </div>  

</div>  

<div class="scrollable">
<?php
if(isset($_POST["Import_Valid_Doc"])){

    function random_password($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    $coopid = $_GET['a_key'];
    $phone_no = mysqli_real_escape_string($link, $_POST['phone']);
    $memail =  mysqli_real_escape_string($link, $_POST['email']);
    $password =  random_password(10); 
    $bvn = mysqli_real_escape_string($link, $_POST['unumber']);
    $meeting_freq = mysqli_real_escape_string($link, $_POST['meeting_freq']);

    $search_coop = mysqli_query($link, "SELECT * FROM coop_members WHERE coopid = '$coopid'");
    $fetch_coop = mysqli_fetch_array($search_coop);
    $coopmemberID = $fetch_coop['memberid'];
    $fname = $fetch_coop['fullname'];

    //FOR GUARANTOR
    $guarantors_otp = random_password(8);

    $grelationship = mysqli_real_escape_string($link, $_POST['grelationship']);
    $gname = mysqli_real_escape_string($link, $_POST['gname']);
    $gphone = mysqli_real_escape_string($link, $_POST['gphone']);
    $gaddrs = mysqli_real_escape_string($link, $_POST['gaddrs']);

    $target_dir = "img/";
    $target_file = $target_dir.basename($_FILES["image"]["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    $sourcepath = $_FILES["image"]["tmp_name"];
    $targetpath = "img/" . $_FILES["image"]["name"];

    $target_file_gimage = $target_dir.basename($_FILES["gimage"]["name"]);
    $target_file_gpimage = $target_dir.basename($_FILES["gpimage"]["name"]);
    $imageFileType_gimage = pathinfo($target_file_gimage,PATHINFO_EXTENSION);
    $imageFileType_gpimage = pathinfo($target_file_gpimage,PATHINFO_EXTENSION);
    $check_gimage = getimagesize($_FILES["gimage"]["tmp_name"]);
    $check_gpimage = getimagesize($_FILES["gpimage"]["tmp_name"]);

    $sourcepath_gimage = $_FILES["gimage"]["tmp_name"];
    $sourcepath_gpimage = $_FILES["gpimage"]["tmp_name"];
    
    $targetpath_gimage = "../img/" . $_FILES["gimage"]["name"];
    $targetpath_gpimage = "../img/" . $_FILES["gpimage"]["name"];

    move_uploaded_file($sourcepath,$targetpath);

    move_uploaded_file($sourcepath_gimage,$targetpath_gimage);

    move_uploaded_file($sourcepath_gpimage,$targetpath_gpimage);

    $passport = "img/".$_FILES['image']['name'];

    $loaction_gimage = "img/".$_FILES['gimage']['name'];
    
    $loaction_gpimage = "img/".$_FILES['gpimage']['name'];

    echo $filename=$_FILES["file"]["tmp_name"];

    foreach ($_FILES['uploaded_file']['name'] as $key => $name){

    $newFilename = $name;
    move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], 'img/'.$newFilename);
    $finalfile = '../img/'.$newFilename;

    $insert_record = mysqli_query($link, "INSERT INTO cooperatives_legaldocuments VALUES(null,'$coopid','$finalfile')");
    $update_records = mysqli_query($link, "UPDATE coop_members SET member_image = '$passport', phone = '$phone_no', email = '$memail', bvn = '$bvn', password = '$password', meeting_freq = '$meeting_freq' WHERE coopid = '$coopid'");
    
   } 
   /**
    $insert = mysqli_query($link, "INSERT INTO coop_admin_guarantors VALUES(null,'$coopid','$loaction_gimage','$grelationship','$gname','$gphone','$gaddrs','$loaction_gpimage','$coopmemberID','Admin','$guarantors_otp')");

    $query = mysqli_query($link, "SELECT abb, email FROM systemset") or die (mysqli_error($link));    
    $r = mysqli_fetch_object($query);
    $sys_abb = $r->abb;

    $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = ''");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;

    $sms1 = "$r->abb>>>Guarantor Confirmation. Please confirm that you have agreed to stand as a Guarantor for $fname with this OTP: $guarantors_otp. Click Here to Confirm: https://esusu.africa/app/guarantor.php";

    include('cron/send_coopmember_guarantor_sms.php');
    **/

   echo "<script type=\"text/javascript\">
              alert(\"Application Completed Successfully!... You will be notify once your application is approved.\");
            </script>";
    echo "<script>window.location='index.php'; </script>";
}  
?> 
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
<!-- jQuery 3 FOR SLIDER -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="plugins/select2/select2.full.min.js"></script>
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS 1.0.1 -->
<script src="plugins/chartjs/Chart.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1');
    //bootstrap WYSIHTML5 - text editor
    $(".textarea").wysihtml5();
  });
</script>

<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>

<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

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
</body>
</html>