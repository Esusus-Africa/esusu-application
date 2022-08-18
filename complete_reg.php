<?php 
session_start();
error_reporting(0);
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

<!--<style type="text/css">

.style1 {
  color: #FF0000;
  font-weight: bold;
}

  </style>
  -->
  
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
<body class="hold-transition login-page" style="background-color: white;">
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
   <h3 style="color: blue;"><strong><?php echo $row ['name'];?></strong></h3>
   <h4 class="panel-title"><b style="color: blue;">KYC UPDATE FORM</b></h4>
   <?php }} ?>
  </div>
     <section class="content">
    
             <div class="box-body">
          <div class="panel panel-success">
            <div class="box-body">

 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<div class="box-body">
  <!--
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue; font-size: 17px;">Upload Bulk Files Here:</label>
  <div class="col-sm-8">
           <input type="file" name="uploaded_file[]" class="btn bg-orange" multiple required>
          <span style="color:blue">Accepted file types</span> <span style="color: orange;"><b>jpg, gif, png, doc, docx, pdf</b></span>
  </div>
  </div>
  
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue; font-size: 17px;">Signed Copy of Electronic Esusu License Form:</label>
  <div class="col-sm-8">
           <input type="file" name="uploaded_file[]" class="btn bg-orange" required>
          <span style="color:blue">Accepted file types</span> <span style="color: orange;"><b>jpg, gif, png, doc, docx, pdf</b></span>
  </div>
  </div>
  -->
  
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Valid ID Card:</label>
  <div class="col-sm-5">
           <input type="file" name="uploaded_file[]" class="form-control" required>
          <span style="color:blue">Accepted file types</span> <span style="color: orange;"><b>jpg, gif, png, doc, docx, pdf</b></span>
  </div>
  <label for="" class="col-sm-3 control-label"></label>
  </div>
  
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Business Certificate (Optional):</label>
  <div class="col-sm-5">
           <input type="file" name="uploaded_file[]" class="form-control">
          <span style="color:blue">Accepted file types</span> <span style="color: orange;"><b>jpg, png, doc, docx, pdf</b></span>
  </div>
  <label for="" class="col-sm-3 control-label"></label>
  </div>
  
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue;">Utility Bill:</label>
  <div class="col-sm-5">
           <input type="file" name="uploaded_file[]" class="form-control">
          <span style="color:blue">Accepted file types</span> <span style="color: orange;"><b>jpg, gif, png, doc, docx, pdf</b></span>
  </div>
  <label for="" class="col-sm-3 control-label"></label>
  </div>

  <hr>

  <div class="form-group">
              <label for="" class="col-sm-4 control-label" style="color:blue;">BVN Number (Optional):</label>
              <div class="col-sm-5">
          <span style="color: orange;"> <b>You are required to Enter your valid BVN to qualify for any of our furture opportunities.</b> </span>
              <input name="unumber" type="text" id="unumber" onkeyup="loaddata();" class="form-control" placeholder="BVN Number Here" maxlength="11"><br>
                <div id="bvn2"></div>
              </div>
              <label for="" class="col-sm-3 control-label"></label>
              </div>

    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Address:</label>
                  <div class="col-sm-5">
                  <input name="addrs" type="text" id="autocomplete1" onFocus="geolocate()" class="form-control" placeholder="Address" required>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>
  
  <div class="form-group" align="right">
                  <label for="" class="col-sm-4 control-label" style="color:blue;"></label>
                  <div class="col-sm-5"><hr>
                  <button class="btn bg-blue ks-btn-file" type="submit" name="Import_Valid_Doc"><span class="fa fa-cloud-upload"></span> Complete Application</button>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>

</div>  

<div class="scrollable">
<?php
if(isset($_POST["Import_Valid_Doc"])){

    $instid = $_GET['a_key'];
    $staffid = $_GET['sid'];
    $bvn = mysqli_real_escape_string($link, $_POST['unumber']);
    $occupation = mysqli_real_escape_string($link, $_POST['occupation']);
    $addrs = mysqli_real_escape_string($link, $_POST['addrs']);

    foreach($_FILES['uploaded_file']['name'] as $key => $name){

        $newFilename = $name;
        move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], 'img/'.$newFilename);
        $finalfile = '../img/'.$newFilename;
    
        $insert_record = mysqli_query($link, "INSERT INTO institution_legaldoc VALUES(null,'$instid','$finalfile')");
    }   
    $update_records = mysqli_query($link, "UPDATE institution_data SET bvn = '$bvn', location = '$addrs' WHERE institution_id = '$instid'");
    $update_records = mysqli_query($link, "UPDATE user SET addr1 = '$addrs' WHERE id = '$staffid' AND created_by = '$instid'");
    echo "<script type=\"text/javascript\">
              alert(\"Application Completed Successfully!.\");
            </script>";
    echo "<script>window.location='/'; </script>";
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