<?php include "../config/session.php"; ?>  

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php 
$call = mysqli_query($link, "SELECT * FROM systemset");
if(mysqli_num_rows($call) == 0)
{
echo "<script>alert('Data Not Found4!'); </script>";
}
else
{
while($row = mysqli_fetch_assoc($call)){
?>

<link href="../img/<?php echo $row['image']; ?>" rel="icon" type="dist/img">
<?php }}?>
  <?php 
  $call = mysqli_query($link, "SELECT * FROM systemset");
  $row = mysqli_fetch_assoc($call);
  ?>
  <title><?php echo $row ['title']; ?></title>
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
  
</head>

<body>
<div class="wrapper">
  <!-- Main content -->

<?php
$acctOwnerID = $_GET['uid'];
$search_bvn = mysqli_query($link, "SELECT * FROM bvn_log WHERE accountID = '$acctOwnerID'");
$num_bvn = mysqli_num_rows($search_bvn);
$fetch_bvn = mysqli_fetch_array($search_bvn);
$acctOfficerID = $fetch_bvn['acctOfficer'];

$search_myibalance = mysqli_query($link, "SELECT * FROM user WHERE id = '$acctOwnerID'");
$fetch_myibalance = mysqli_fetch_array($search_myibalance);
$ph = $fetch_myibalance['phone'];
$em = $fetch_myibalance['email'];
$myname = $fetch_myibalance['name'].' '.$fetch_myibalance['lname'].' '.$fetch_myibalance['mname'];
$myi_state = $fetch_myibalance['state'];
$myi_country = $fetch_myibalance['country'];
$mysc = $myi_state.' / '.$myi_country;
$mydob = $fetch_myibalance['dob'];
$mygender = $fetch_myibalance['gender'];
$mybvn = $fetch_myibalance['addr2'];
$myvano = $fetch_myibalance['virtual_acctno'];
$userUpgradeStatus = $fetch_myibalance['status'];

$concat = $fetch_bvn['mydata'];
$parameter = (explode('|',$concat));
$bankcode = $parameter[7];
//Fetch Bank Name
$search_bankname = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$bankcode'");
$fetch_bankname = mysqli_fetch_array($search_bankname);
$mybank_name = $fetch_bankname['bankname'];

$searchVA = mysqli_query($link, "SELECT account_name FROM virtual_account WHERE userid = '$acctOwnerID'");
$fetchVA = mysqli_fetch_array($searchVA);
?>

  <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="alert bg-<?php echo ($num_bvn == 1) ? 'blue' : 'orange'; ?>">
              <h5><?php echo ($userUpgradeStatus == "Verified") ? "<span class='label bg-blue' style='font-size:18px;'>Account Verified <i class='fa fa-check'></i></span>" : "<span class='label bg-orange' style='font-size:18px;'>Verification still ".$userUpgradeStatus." <i class='fa fa-info'></i></span>"; ?></h5>
            </div>

            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fa fa-globe"></i>
                    <small class="float-right">Date: <?php echo date('d/m/Y').' '.(date(h) + 1).':'.date('i A');; ?></small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->

              <div class="row invoice-info" style="border-style: none inset solid inset;">
                <div class="box box-widget widget-user">
                    <div class="col-sm-4 invoice-col">
                    <div class="widget-user-header bg-blue-active" align="center">
                    <b style="font-size:20px;"><?php echo $fetchVA['account_name']; ?></b>
                    </div>
                    <div class="widget-user-image">
                    <address>
                        <img src="<?php echo ($num_bvn == 1 && $parameter[20] != '') ? $fetchsys_config['file_baseurl'].$parameter[20] : $fetchsys_config['file_baseurl'].'image-placeholder.jpg'; ?>" class="img-circle" width="150" height="150"/>
                    </address>
                    </div>
                    </div>
                </div>


                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                <h4><b style="color: blue;">BASIC INFO:</b><img src="../image/down-arrow-new.gif" width="30px" height="30px"></h4>
                <address>
                    <table>
                    <tr>
                    <td height="30px"><b style="color: blue; font-size: 12px;">Name: </b></td>
                    <td height="30px"><span style="color: orange; font-size: 14px;"><b><?php echo ($num_bvn == 1) ? $parameter[1].' '.$parameter[2].' '.$parameter[3] : $myname; ?></b></span></td>
                    </tr>
                    <tr>
                    <td height="30px"><b style="color: blue; font-size: 12px;">State / Nationality: </b></td>
                    <td height="30px"><span style="color: orange; font-size: 14px;"><b><?php echo ($num_bvn == 1) ? $parameter[16].' / '.$parameter[15] : $mysc; ?></b></span></td>
                    </tr>
                    <tr>
                    <td height="30px"><b style="color: blue; font-size: 12px;">Phone: </b></td>
                    <td height="30px"><span style="color: orange; font-size: 14px;"><b><?php echo ($num_bvn == 1) ? $parameter[5] : $ph; ?></b></span></td>
                    </tr>
                    <tr>
                    <td height="30px"><b style="color: blue; font-size: 12px;">Email: </b></td>
                    <td height="30px"><span style="color: orange; font-size: 14px;"><b><?php echo ($num_bvn == 1) ? $parameter[6] : $em; ?></b></span></td>
                    </tr>
                    <tr>
                    <td height="30px"><b style="color: blue; font-size: 12px;">Date of Birth: </b></td>
                    <td height="30px"><span style="color: orange; font-size: 14px;"><b><?php echo ($num_bvn == 1) ? $parameter[4] : $mydob; ?></b></span></td>
                    </tr>
                    <tr>
                    <td height="30px"><b style="color: blue; font-size: 12px;">Gender: </b></td>
                    <td height="30px"><span style="color: orange; font-size: 14px;"><b><?php echo ($num_bvn == 1) ? $parameter[9] : $mygender; ?></b></span></td>
                    </tr>
                    <tr>
                    <td height="30px"><b style="color: blue; font-size: 12px;">BVN: </b></td>
                    <td height="30px"><span style="color: orange; font-size: 14px;"><b><?php echo ($num_bvn == 1) ? $parameter[0] : $mybvn; ?></b></span></td>
                    </tr>
                </table>
                </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                <h4><b style="color: blue;">OTHER DETAILS:</b><img src="../image/down-arrow-new.gif" width="30px" height="30px"></h4>
                <address>
                    <table>
                    <tr>
                    <td height="30px"><b style="color: blue; font-size: 12px;">Enrollment Bank: </b></td>
                    <td height="30px"><span style="color: orange; font-size: 14px;"><b><?php echo ($num_bvn == 1) ? $mybank_name.' - '.$parameter[7] : "null"; ?></b></span></td>
                    </tr>
                    <tr>
                    <td height="30px"><b style="color: blue; font-size: 12px;">Enrollment Branch: </b></td>
                    <td height="30px"><span style="color: orange; font-size: 14px;"><b><?php echo ($num_bvn == 1) ? $parameter[8] : "null"; ?></b></span></td>
                    </tr>
                    <tr>
                    <td height="30px"><b style="color: blue; font-size: 12px;">Level Of Account: </b></td>
                    <td height="30px"><span style="color: orange; font-size: 14px;"><b><?php echo ($num_bvn == 1) ? $parameter[10] : "null"; ?></b></span></td>
                    </tr>
                    <tr>
                    <td height="30px"><b style="color: blue; font-size: 12px;">Marital Status: </b></td>
                    <td height="30px"><span style="color: orange; font-size: 14px;"><b><?php echo ($num_bvn == 1) ? $parameter[13] :"null"; ?></b></span></td>
                    </tr>
                    <tr>
                    <td height="30px"><b style="color: blue; font-size: 12px;">WatchListed: </b></td>
                    <td height="30px"><span style="color: orange; font-size: 14px;"><b><?php echo ($num_bvn == 1) ? $parameter[19] : "null"; ?></b></span></td>
                    </tr>
                    <tr>
                    <td height="30px"><b style="color: blue; font-size: 12px;">Origin LGA / State of Residence: </b></td>
                    <td height="30px"><span style="color: orange; font-size: 14px;"><b><?php echo ($num_bvn == 1) ? $parameter[11].' / '.$parameter[17] : "null"; ?></b></span></td>
                    </tr>
                    <tr>
                    <td height="30px"><b style="color: blue; font-size: 12px;">Name on Card: </b></td>
                    <td height="30px"><span style="color: orange; font-size: 14px;"><b><?php echo ($num_bvn == 1) ? $parameter[14] : "null"; ?></b></span></td>
                    </tr>
                </table>
                </address>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              
            <div class="box-footer">

                <?php
                    $i = 0;
                    $search_file = mysqli_query($link, "SELECT * FROM institution_legaldoc WHERE instid = '$acctOwnerID'") or die ("Error: " . mysqli_error($link));
                    if(mysqli_num_rows($search_file) == 0){
                    	echo "<span style='color: orange'>No file attached!!</span>";
                    }else{
                    	while($get_file = mysqli_fetch_array($search_file)){
                    		$i++;
                    ?>
                    <a href="../img/<?php echo $get_file['document']; ?>" target="_blank"><img src="../img/file_attached.png" width="64" height="64"> Attachment<?php echo $i; ?></a>
                    <?php
                    	}
                    }
                ?>

	            <button type="button" onClick="window.print();" class="btn bg-orange pull-right" ><i class="fa fa-print"></i>Print</button>;
            
            </div>

        <div>
            
        </div>

        <!-- /.row -->

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

<!-- FastClick -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<!-- FastClick -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<!-- FastClick -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<!-- FastClick -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<!-- FastClick -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<!-- FastClick -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<!-- FastClick -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<!-- FastClick -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>

</body>
</html>