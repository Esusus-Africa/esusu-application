<?php include "../config/session1.php"; ?>  

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?php
$call = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
if(mysqli_num_rows($call) == 0)
{
echo "<script>alert('Data Not Found1!'); </script>";
}
else
{
while($row = mysqli_fetch_assoc($call)){
?>

<link href="../<?php echo $row['logo']; ?>" rel="icon" type="dist/img">
<?php }}?>

  <title><?php echo $inst_name; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <strong> <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css"></strong>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
  <!-- Datatable new code -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>

 <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
  
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
 
 <style>
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
    }
    th, td {
      padding: 5px 5px 5px 5px;
    }
 </style>
  
</head>

<?php
$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$myaltrow = mysqli_fetch_array($myaltcall);
?>
<body>
<div class="wrapper">
  <!-- Main content -->

<?php
$refid = $_GET['refid'];
$search_identity = mysqli_query($link, "SELECT * FROM verification_history WHERE transactionReference = '$refid'");
$fetch_identity = mysqli_fetch_array($search_identity);
$verification_type = $fetch_identity['verification_type'];
$mypix = $fetch_identity['picture'];
$otherImg = $fetch_identity['otherimage'];
$details = $fetch_identity['details'];
$myResponse = json_decode($details, true);
?>

  <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="alert bg-blue">
              <h5><?php echo ($verification_type != "BVN-FULL-DETAILS") ? "<span class='label bg-blue' style='font-size:18px;'>NIN VERIFICATION <i class='fa fa-info'></i></span>" : "<span class='label bg-blue' style='font-size:18px;'>BVN VERIFICATION <i class='fa fa-info'></i></span>"; ?></h5>
            </div>

            <!-- Main content -->
            <div class="invoice p-3 mb-3">

                <table style="width:100%">

                <?php
                if($verification_type != "BVN-FULL-DETAILS"){
                ?>
                    <tr>
                        <td colspan="2" align="center"><img src="<?php echo $fetchsys_config['file_baseurl'].$mypix; ?>" height="300px" width="300px"/></td>
                        <td colspan="2" align="center"><img src="<?php echo $fetchsys_config['file_baseurl'].$otherImg; ?>" height="300px" width="300px"/></td>
                    </tr>

                    <?php
                    foreach($myResponse as $key){
        
                        echo '<tr>
                                <td><b>NIN Number:</b></td>
                                <td>'.(($key['nin'] == "") ? "null" : $key['nin']).'</td>
                                <td><b>Tracking ID:</b></td>
                                <td>'.(($key['trackingId'] == "") ? "null" : $key['trackingId']).'</td>
                            </tr>
                            <tr>
                                <td><b>Central ID:</b></td>
                                <td>'.(($key['centralID'] == "") ? "null" : $key['centralID']).'</td>
                                <td><b>Document Number:</b></td>
                                <td>'.(($key['documentno'] == "") ? "null" : $key['documentno']).'</td>
                            </tr>
                            <tr>
                                <td><b>First Name:</b></td>
                                <td>'.(($key['firstname'] == "") ? "null" : $key['firstname']).'</td>
                                <td><b>Surname:</b></td>
                                <td>'.(($key['surname'] == "") ? "null" : $key['surname']).'</td>
                            </tr>
                            <tr>
                                <td><b>Middle Name:</b></td>
                                <td>'.(($key['middlename'] == "") ? "null" : $key['middlename']).'</td>
                                <td><b>Gender:</b></td>
                                <td>'.(($key['gender'] == "") ? "null" : $key['gender']).'</td>
                            </tr>
                            <tr>
                                <td><b>Telephone No:</b></td>
                                <td>'.(($key['telephoneno'] == "") ? "null" : $key['telephoneno']).'</td>
                                <td><b>Date of Birth:</b></td>
                                <td>'.(($key['birthdate'] == "") ? "null" : $key['birthdate']).'</td>
                            </tr>
                            <tr>
                                <td><b>Email Address:</b></td>
                                <td>'.(($key['email'] == "") ? "null" : $key['email']).'</td>
                                <td><b>Height:</b></td>
                                <td>'.(($key['heigth'] == "") ? "null" : $key['heigth']).'</td>
                            </tr>
                            <tr>
                                <td><b>State of Birth:</b></td>
                                <td>'.(($key['birthstate'] == "") ? "null" : $key['birthstate']).'</td>
                                <td><b>Country of Birth:</b></td>
                                <td>'.(($key['birthcountry'] == "") ? "null" : $key['birthcountry']).'</td>
                            </tr>
                            <tr>
                                <td><b>LGA:</b></td>
                                <td>'.(($key['birthlga'] == "") ? "null" : $key['birthlga']).'</td>
                                <td><b>Batch ID:</b></td>
                                <td>'.(($key['batchid'] == "") ? "null" : $key['batchid']).'</td>
                            </tr>
                            <tr>
                                <td><b>Educational Level:</b></td>
                                <td>'.(($key['educationallevel'] == "") ? "null" : $key['educationallevel']).'</td>
                                <td><b>Employment Status:</b></td>
                                <td>'.(($key['emplymentstatus'] == "") ? "null" : $key['emplymentstatus']).'</td>
                            </tr>
                            <tr>
                                <td><b>Profession:</b></td>
                                <td>'.(($key['profession'] == "") ? "null" : $key['profession']).'</td>
                                <td><b>Religion:</b></td>
                                <td>'.(($key['religion'] == "") ? "null" : $key['religion']).'</td>
                            </tr>
                            <tr>
                                <td><b>Residential Address 1:</b></td>
                                <td>'.(($key['residence_AdressLine1'] == "") ? "null" : $key['residence_AdressLine1']).'</td>
                                <td><b>Residential Address 2:</b></td>
                                <td>'.(($key['residence_AdressLine2'] == "") ? "null" : $key['residence_AdressLine2']).'</td>
                            </tr>
                            <tr>
                                <td><b>Residential Town:</b></td>
                                <td>'.(($key['residence_Town'] == "") ? "null" : $key['residence_Town']).'</td>
                                <td><b>Residential LGA:</b></td>
                                <td>'.(($key['residence_lga'] == "") ? "null" : $key['residence_lga']).'</td>
                            </tr>
                            <tr>
                                <td><b>Residential State:</b></td>
                                <td>'.(($key['residence_state'] == "") ? "null" : $key['residence_state']).'</td>
                                <td><b>Residential Status:</b></td>
                                <td>'.(($key['residencestatus'] == "") ? "null" : $key['residencestatus']).'</td>
                            </tr>
                            <tr>
                                <td><b>Origin State:</b></td>
                                <td>'.(($key['self_origin_state'] == "") ? "null" : $key['self_origin_state']).'</td>
                                <td><b>Origin LGA:</b></td>
                                <td>'.(($key['self_origin_lga'] == "") ? "null" : $key['self_origin_lga']).'</td>
                            </tr>';
                        
                    }
                    ?>

                <?php
                }elseif($verification_type == "BVN-FULL-DETAILS"){
                ?>

                    <tr>
                        <td align="center"><img src="<?php echo $fetchsys_config['file_baseurl'].$mypix; ?>" height="300px" width="300px"/></td>
                        <td align="center"><img src="<?php echo $fetchsys_config['file_baseurl'].$otherImg; ?>" height="80%" width="80%"/></td>
                    </tr>

                <?php
                }else{
                    //Do nothing
                }
                ?>
                </table>

              <!-- Table row -->
              
            <div class="box-footer">
                  
	              <button type="button" onClick="window.print();" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> pull-right" ><i class="fa fa-print"></i>Print</button>
            
            </div>

            </div>
            <!-- /.invoice -->
            
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
 
</div>
<!-- ./wrapper -->
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- Bootstrap 3.3.6 -->

</body>
</html>
