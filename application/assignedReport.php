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
echo "<script>alert('Data Not Found!'); </script>";
}
else
{
while($row = mysqli_fetch_assoc($call)){
?>

<link href="../img/<?php echo $row['image']; ?>" rel="icon" type="dist/img">
<?php }}?>
  <?php 
	$call = mysqli_query($link, "SELECT * FROM systemset");
	while($row = mysqli_fetch_assoc($call)){
	?>
  <title><?php echo $row ['title']?></title>
  <?php }?>
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

  <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="alert bg-blue">
              <h5><i class="fa fa-info"></i> Individual Terminal Reports:</h5>
            </div>
<?php
$terminalID = $_GET['termId'];
$search_term = mysqli_query($link, "SELECT * FROM terminal_reg WHERE (terminal_id = '$terminalID' OR trace_id = '$terminalID')");
$fetch_term = mysqli_fetch_array($search_term);
$assignedBy = $fetch_term['assignedBy'];
$tidOperator = $fetch_term['tidoperator'];
$merchID = $fetch_term['merchant_id'];

$userSearch = mysqli_query($link, "SELECT * FROM user WHERE id = '$assignedBy'");
$fetchSearch = mysqli_fetch_array($userSearch);

$userSearch2 = mysqli_query($link, "SELECT * FROM user WHERE id = '$tidOperator'");
$fetchSearch2 = mysqli_fetch_array($userSearch2);
?>
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
              <div class="box-body">
                <div class="col-12">
                  <label for="" class="col-sm-1 control-label"></label>
                  <h4>
                    <i class="fa fa-globe"></i> <?php echo ($fetch_term['merchant_name'] == "") ? "" : strtoupper($fetch_term['merchant_name']); ?>
                    <small class="float-right">Date: <?php echo date("d/m/Y"); ?></small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              </div>
              <!-- info row -->
              <div class="row invoice-info" align="left">
                <label for="" class="col-sm-1 control-label"></label>
                <div class="col-sm-3 invoice-col">
                  MERCHANT / AGENT INFO
                  <address>
                    <strong><?php echo $fetch_term['merchant_name']; ?> (<?php echo $fetch_term['merchant_id']; ?>).</strong><br>
                    Phone: <b><?php echo $fetch_term['merchant_phone_no']; ?></b><br>
                    Email: <b><?php echo $fetch_term['merchant_email']; ?></b><br>
                    Assigned By: <b><?php echo $fetchSearch['name'].' '.$fetchSearch['lname'].' '.$fetchSearch['mname'];; ?></b>
                  </address>
                </div>
                <!-- /.col -->
                <label for="" class="col-sm-1 control-label"></label>
                <div class="col-sm-3 invoice-col">
                  TERMINAL INFO
                  <address>
                    Issuer: <b><?php echo $fetch_term['terminal_issurer']; ?></b><br>
                    Channel: <b><?php echo $fetch_term['channel']; ?></b><br>
                    Model Code: <b><?php echo $fetch_term['terminal_model_code']; ?></b><br>
                    Date Issued: <b><?php echo date("d/m/Y", strtotime($fetch_term['dateUpdated'])); ?></b>
                  </address>
                </div>
                <!-- /.col -->
                <label for="" class="col-sm-1 control-label"></label>
                <div class="col-sm-3 invoice-col">
                  <b>Terminal ID #<?php echo $fetch_term['terminal_id']; ?></b><br>
                  <br>
                  Pending Balance: <b><?php echo number_format($fetch_term['pending_balance'],2,'.',','); ?></b><br>
                  Settled Balance: <b><?php echo number_format($fetch_term['settled_balance'],2,'.',','); ?></b><br>
                  Transfer Balance: <b><?php echo number_format($fetchSearch2['transfer_balance'],2,'.',','); ?></b><br>
                  Transaction Count: <b><?php echo number_format($fetch_term['total_transaction_count'],0,'',','); ?></b>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data">

                <div class="box-body">

                <div class="box-body">
                  
                  <div class="form-group">
                        <label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
                        <div class="col-sm-5">
                        <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
                        <span style="color: orange;">Date format: 2018-05-01</span>
                        </div>
                
                        <label for="" class="col-sm-1 control-label" style="color:blue;">To</label>
                        <div class="col-sm-5">
                        <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
                        <span style="color: orange;">Date format: 2018-05-24</span>
                        </div>

                        <input name="ttype" type="hidden" id="transType" class="form-control" value="<?php echo $terminalID; ?>">
                  </div>
                  
                </div>
                  
                <hr>

                <div class="col-12 table-responsive">
                  <table id="indiv_terminal_report_data" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th>RefID</th>
                        <th>RRN</th>
                        <th>Terminal ID</th>
                        <th>Operator</th>
                        <th>Merchant ID</th>
                        <th>Merchant Name</th>
                        <th>Channel</th>
                        <th>Amount</th>
                        <th>Charges</th>
                        <th>Amount to-Settle</th>
                        <th>Pending Balance</th>
                        <th>Transfer Balance</th>
                        <th>Status</th>
                        <th>Date/Time</th>
                      </tr>
                      </thead>
                  </table>
                </div>
                <!-- /.col -->

              </div>

              </form>

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
<!-- DataTables -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- page script --><script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>

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

<script type="text/javascript" language="javascript">
 $(document).ready(function(){

  var dataTable = $('#indiv_terminal_report_data').DataTable({
   "processing" : true,
   "serverSide" : true,
   'serverMethod': 'post',
   "ajax" : {
    url:"fetch_indivterminal_report.php",
    'data': function(data){
        // Read values
        var mydfrom = $('#startDate').val();
        var mydto = $('#endDate').val();
        var myptype = $('#transType').val();

        // Append to data
        data.startDate = mydfrom;
        data.endDate = mydto;
        data.transType = myptype;
    }
   },
   dom: 'lBfrtip',
   buttons: [
    'copy', 'csv', 'excel', 'pdf', 'print'
   ],
   "lengthMenu": [ [10, 25, 50, 100, 200, 300, 500, -1], [10, 25, 50, 100, 200, 300, 500, "All"] ]
  });
  
  $('#startDate').change(function () {
    dataTable.draw();
  });

  $('#endDate').change(function () {
    dataTable.draw();
  });
  
  $('#transType').change(function(){
    dataTable.draw();
  });
  
 });
 
</script>
</body>
</html>
