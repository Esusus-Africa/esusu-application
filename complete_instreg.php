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
  
  <script type="text/javascript">
  function veryUsername()
  {
   var verify_username=document.getElementById("vusername").value;

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
   <h4 class="panel-title"><b style="color: white;">INSTITUTION UPDATE FORM FOR APPROVAL REQUEST</b></h4>
   <?php }} ?>
  </div>
     <section class="content">
    
             <div class="box-body">
          <div class="panel panel-success">
            <div class="box-body">

 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<div class="box-body">

  <hr>
<div <div class="bg-blue" style="font-size: 16px;">&nbsp;<b> FILL THE INFORMATIONS BELOW SO AS TO UPDATE YOUR RECORDS AS A SUPER-ADMIN OF THE INSTITUTION.</b></div>
<hr>

      <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Passport Photograph</label>
      <div class="col-sm-8">
               <input type='file' name="image" class="btn bg-orange" onChange="readURL(this);" required>
               <img id="blah"  src="avtar/user2.png" alt="Logo Here" height="100" width="100"/>
      </div>
      </div>

  <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Gender</label>
                  <div class="col-sm-8">
        <select name="gender" class="form-control" required>
                    <option value='' selected='selected'>Select Gender&hellip;</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
        </select>
    </div>
    </div>

  <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Email Address</label>
                  <div class="col-sm-8">
                  <input name="demail" type="email" class="form-control" id="vemail" onkeyup="veryEmail();" placeholder="Your Email Address" required>
                  <div id="myvemail"></div>
                  </div>
                  </div>

  <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Mobile Number</label>
                  <div class="col-sm-8">
                  <input name="mobile_no" type="text" class="form-control" placeholder="Your Personal Phone Number" required>
                  </div>
                  </div>

  <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">MOI</label>
                  <div class="col-sm-8">
        <select name="moi" class="form-control" required>
                    <option selected='selected'>Select Mode of Identification&hellip;</option>
                    <option value="National ID">National ID</option>
                    <option value="International Passport">International Passport</option>
                    <option value="Voter's Card">Voter's Card</option>
                    <option value="Driving License">Driving License</option>
        </select>
    </div>
    </div>

    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">ID Number</label>
                  <div class="col-sm-8">
                  <input name="idnumber" type="text" class="form-control" placeholder="ID Number e.g NIN, etc." required>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">BVN Number</label>
                  <div class="col-sm-8">
                    <span style="color: orange;"> <b>You need to verify your BVN here</b></span>
                  <input name="unumber" type="text" class="form-control" id="unumber" onkeydown="loaddata();" placeholder="BVN Number" maxlength="11" required>
                  <div id="bvn2"></div>
                  </div>
                  </div>

    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Username</label>
                  <div class="col-sm-8">
                  <input name="username" type="text" class="form-control" id="vusername" onkeyup="veryUsername();" placeholder="Username" required>
                  <div id="myusername"></div>
                  </div>
                  </div>

<hr>
<div class="bg-blue">&nbsp;<b> SETTLEMENT ACCOUNT DETAILS / CHARGES </b></div>
<hr>

    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">State</label>
                  <div class="col-sm-8">
                  <input name="state" type="text" class="form-control" placeholder="Enter State Where You Operate" required>
                  </div>
                  </div>

    <div class="form-group">
                      <label for="" class="col-sm-4 control-label" style="color:blue;">Country</label>
                      <div class="col-sm-8">
            <select name="country" class="form-control" id="country" onchange="loadbank();" required>
              <option value="" selected="selected">Please Select Country</option>
              <option value="NG">Nigeria</option>
              <option value="GH">Ghana</option>
              <option value="KE">Kenya</option>
              <option value="UG">Uganda </option>
              <option value="TZ">Tanzania</option>
            </select>                 
            </div>
          </div>
          
    <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Account Number</label>
                  <div class="col-sm-8">
                  <input name="account_number" type="text" id="account_number" onkeydown="loadaccount();" class="form-control" placeholder="Settlement Bank Account Number" required>
                  <div id="act_numb"></div>
                </div>
                </div>

     <div class="form-group">
                  <label for="" class="col-sm-4 control-label" style="color:blue;">Bank Code</label>
                  <div class="col-sm-8">
                    <div id="bank_list"></div>
    </div>
    </div>
    
<hr>
<div class="bg-blue">&nbsp;<b> INSTITUTION VALID DOCUMENTS </b></div>
<hr>    
    
    <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue; font-size: 14px;">Signed Copy of Electronic Esusu License Form:</label>
  <div class="col-sm-8">
           <input type="file" name="uploaded_file[]" class="btn bg-orange" required>
          <span style="color:blue">Accepted file types</span> <span style="color: orange;"><b>jpg, gif, png, doc, docx, pdf</b></span>
  </div>
  </div>
  
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue; font-size: 14px;">Valid ID Card (for Super-Admin):</label>
  <div class="col-sm-8">
           <input type="file" name="uploaded_file[]" class="btn bg-orange" required>
          <span style="color:blue">Accepted file types</span> <span style="color: orange;"><b>jpg, gif, png, doc, docx, pdf</b></span>
  </div>
  </div>
  
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue; font-size: 14px;">Business Registration / Institution Certificate:</label>
  <div class="col-sm-8">
           <input type="file" name="uploaded_file[]" class="btn bg-orange">
          <span style="color:blue">Accepted file types</span> <span style="color: orange;"><b>jpg, gif, png, doc, docx, pdf</b></span>
  </div>
  </div>
  
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: blue; font-size: 14px;">Utility Bill:</label>
  <div class="col-sm-8">
           <input type="file" name="uploaded_file[]" class="btn bg-orange">
          <span style="color:blue">Accepted file types</span> <span style="color: orange;"><b>jpg, gif, png, doc, docx, pdf</b></span>
  </div>
  </div>
  
   <div align="right">
              <div class="box-footer">
                        <button name="save" type="submit" class="btn bg-blue ks-btn-file"><i class="fa fa-cloud-upload">&nbsp;Complete Application</i></button>

              </div>
        </div>

</div>  

<div class="scrollable">
<?php
if(isset($_POST["save"]))
{
    function random_password($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    $result = array();
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));   
    $r = mysqli_fetch_object($query);
    $sys_abb = $r->abb;
    $sys_email = $r->email;
    $subaccount_charges = $r->subaccount_charges;
    
    $instid = $_GET['a_key'];
    $dirid = $_GET['dkey'];
    $d_type = mysqli_real_escape_string($link, $_POST['directorateType']);
    $gender = mysqli_real_escape_string($link, $_POST['gender']);
    $demail =  mysqli_real_escape_string($link, $_POST['demail']);
    $mobile_no = mysqli_real_escape_string($link, $_POST['mobile_no']);
    $moi = mysqli_real_escape_string($link, $_POST['moi']);
    $idnumber = mysqli_real_escape_string($link, $_POST['idnumber']);
    $bvn = mysqli_real_escape_string($link, $_POST['unumber']);
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $password =  random_password(10); 

    //SETTLEMENT ACCOUNT DETAILS / DIVIDEND
    $state =  mysqli_real_escape_string($link, $_POST['state']);
    $country =  mysqli_real_escape_string($link, $_POST['country']);
    $account_number = mysqli_real_escape_string($link, $_POST['account_number']);
    $bank_code = mysqli_real_escape_string($link, $_POST['bank_code']);
    //$bank_name = mysqli_real_escape_string($link, $_POST['bank_name']);
    $perc_charges = mysqli_real_escape_string($link, $_POST['perc_charges']);
    //$settlement_schedule = mysqli_real_escape_string($link, $_POST['settlement_schedule']);

    $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$instid'");
    $fetch_inst = mysqli_fetch_array($search_inst);
    $iname = $fetch_inst['institution_name'];
    $official_email = $fetch_inst['official_email'];
    $official_phone = $fetch_inst['official_phone'];
    $itype = $fetch_inst['itype'];

    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'subaccount'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;

    $search_contact = mysqli_query($link, "SELECT * FROM institution_user WHERE institution_id = '$instid'");
    $fetch_contact = mysqli_fetch_object($search_contact);
    $contact_person = $fetch_contact->d_name;

    // Pass the subaccount name, settlement bank account, account number and percentage charges
    $postdata =  array(
      "account_bank"            => $bank_code,
      "account_number"          => $account_number,
      "business_name"           => $iname,
      "business_email"          => $official_email,
      "business_contact"        => $contact_person,
      "business_contact_mobile" => $mobile_no,
      "business_mobile"         => $official_phone,
      "meta" => [
        "instsitution_id"   => $instid
      ],
      "seckey"                  => $r->secret_key,
      "split_type"              => "percentage",
      "split_value"             => $subaccount_charges
      );
          
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $headers = [
      'Content-Type: application/json'
      ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $request = curl_exec($ch);
    if(curl_error($ch)){
      echo 'error:' . curl_error($ch);
    }
    curl_close($ch);
          
    if ($request) {
      $result = json_decode($request, true);

    if($result['status'] == "success"){

        $subaccount_code = $result['data']['subaccount_id'];

        $target_dir = "img/";
        $target_file = $target_dir.basename($_FILES["image"]["name"]);
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        $sourcepath = $_FILES["image"]["tmp_name"];
        $targetpath = "img/" . $_FILES["image"]["name"];

        move_uploaded_file($sourcepath,$targetpath);

        $passport = "img/".$_FILES['image']['name'];

        $encrypt = base64_encode($password);
        
        echo $filename=$_FILES["file"]["tmp_name"];

        foreach($_FILES['uploaded_file']['name'] as $key => $name){
    
        $newFilename = $name;
        move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], 'img/'.$newFilename);
        $finalfile = '../img/'.$newFilename;

        $update_records = mysqli_query($link, "INSERT INTO institution_legaldoc VALUES(null,'$instid','$finalfile')");
        $update_records = mysqli_query($link, "UPDATE institution_user SET d_image = '$passport', d_type = '$d_type', gender = '$gender', email = '$demail', mobile_no = '$mobile_no', moi = '$moi', id_number = '$idnumber', username = '$username', password = '$password', bvn = '$bvn' WHERE institution_id = '$instid'");

        $update_records = mysqli_query($link, "UPDATE user SET email = '$demail', phone = '$mobile_no', username = '$username', password = '$encrypt' WHERE id = '$dirid'");

        $update_inst = mysqli_query($link, "UPDATE institution_data SET subaccount_code = '$subaccount_code', state = '$state', country = '$country' WHERE institution_id = '$instid'");

        if($update_records)
        {
            echo "<script type=\"text/javascript\">
              alert(\"Application Completed Successfully!... You will be notify once your application is approved.\");
            </script>";
            echo "<script>window.location='index.php'; </script>";
        }
        else{
            echo "<script type=\"text/javascript\">
              alert(\"Error!... Please try again later.\");
            </script>";
        }
    }
    }else{
        
        echo $result['message'];
        
    }
    }
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

<script>
     $('#directorate_type').change(function(){
         var PostType=$('#directorate_type').val();
         $.ajax({url:"Ajax-ShowDirectorate.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
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