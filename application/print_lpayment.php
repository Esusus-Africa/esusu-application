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
</head>
<body onLoad="window.print();">
<div class="wrapper">
  <!-- Main content -->
  <br>
  <?php 
		 $result= mysqli_query($link,"select * from systemset")or die(mysqli_error());
		 while($row=mysqli_fetch_array($result))
		 {
		 ?>
		   <div align="center"><img src="../img/<?php echo $row['image'];?>" width="80" height="80" class="user-image" alt="User Image">
		 <?php }?>
		 </div>
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
		 <?php 
		 $sql = "SELECT * FROM systemset";
		 $result = mysqli_query($link,$sql);
		 while ($row=mysqli_fetch_array($result))
		{
?>
            <div style="color:#009900"><div style="font-size:21px"><div align="center"><b><?php echo '&nbsp;&nbsp;&nbsp;'. $row ['name'];?></b></div></div></div>
 		   <div style="color:#009900; font-size:19px;" align="center"><b><br><p>(Contribution Lend Received from Customer)</p></b></div>
           <small class="pull-right"><div style="color:#009900"><?php $today = date ('y:m:d'); 
 		  								  $new = date ('l, F, d, Y', strtotime($today));	
 										      echo $new;?></div>
		</small>
        </h2>
		<?php  
		}
		?>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    
    <!-- /.row -->

    <!-- Table row -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
            <div class="box-header">
              <!--<h3 class="box-title">Payment table</h3>-->

            </div>
		              <div class="box-body table-responsive">

			 <table class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>LPID</th>
				  <th>Payer</th>
				  <th>CPID</th>
				  <th>Project:</th>
				  <th>Lender Name</th>
				  <th>Lender Email</th>
                  <th>Amount</th>
                  <th>Payment Date</th>
				  <th>Payback Deadline</th>
                  <th>Status</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM campaign_lendpay_history ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
	$id = $row['id'];
	$payer_tid = $row['tid'];
	$lpay_id = $row['lpay_id'];
	$pid = $row['pid'];
	$c_id  = $row['c_id'];
	$amount = $row['amount'];
	$pdate = $row['pdate'];
	$expected_pdate = $row['expected_pdate'];
	$lstatus = $row['lstatus'];
	$disbursed_status = $row['disbursed_status'];
	$search = mysqli_query($link, "SELECT * FROM causes WHERE id = '$c_id'");
	$get_cause = mysqli_fetch_array($search);
	
	$search_payer = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$payer_tid'");
	$get_payer = mysqli_fetch_array($search_payer);

	$search_cpay = mysqli_query($link, "SELECT * FROM campaign_pay_history WHERE pid = '$pid' AND pstatus = 'Completed'");
	$num = mysqli_num_rows($search_cpay);
	$get_cpay = mysqli_fetch_array($search_cpay);
	$pstatus = $get_cpay['pstatus'];
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo $payer_tid; ?></td>
				<td><?php echo $get_payer['fname'].' '.$get_payer['lname']; ?></td>
				<td><?php echo $pid; ?></td>
				<td><?php echo $get_cause['campaign_title']; ?></td>
				<td><?php echo $row['lender_name']; ?></td>
				<td><?php echo $row['lender_email']; ?></td>
                <td><?php echo number_format($amount,2,".",","); ?></td>
				<td><?php echo date ('F d, Y', strtotime($pdate)); ?></td>
				<td><?php echo date ('F d, Y', strtotime($expected_pdate)); ?></td>
				<td><?php echo ($lstatus == 'Pending') ? '<span class="label label-danger">Pending</span>' : '<span class="label label-success">Paid</span>'; ?></td>
			    </tr>
<?php } } ?>
             </tbody>
                </table>  
				
				</div>
				
              <div class="box-footer">
	 <button type="button" onClick="window.print();" class="btn btn-warning pull-right" ><i class="fa fa-print"></i>Print</button>

            <!-- /.box-body -->
          </div>
      <!-- /.box -->

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
</body>
</html>
