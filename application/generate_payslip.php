<?php include "../config/connect.php"; ?>  

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
           <div style="color:blue"><div style="font-size:21px"><div align="center"><b><?php echo '&nbsp;&nbsp;&nbsp;'. $row ['name'];?></b></div></div></div>
		   <div style="color:orange; font-size:19px;" align="center"><b><br><p>(Payslip)</p></b></div>
          <small class="pull-right"><div style="color:orange"><?php $today = date ('y:m:d'); 
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
			 
             <div class="box-body table-responsive">

<?php
$id = $_GET['id'];
$select = mysqli_query($link, "SELECT * FROM payroll WHERE id = '$id'") or die (mysqli_error($link));
$row = mysqli_fetch_object($select);
$staffid = $row->staff_id;

$get = mysqli_query($link, "SELECT * FROM user WHERE userid = '$staffid'") or die (mysqli_error($link));
$rows = mysqli_fetch_object($get);
?>				  

			<table width="100%" border="1" bordercolor="#000000">
			  <tr class="form-group" width="50%">
				<td>&nbsp;<span class="col-sm-4">Employee Name: </span><div class="col-sm-2"><b><?php echo $rows->name; ?></b></div></td>
				<td>&nbsp;<span class="col-sm-3">Payroll Date: </span><div class="col-sm-3"><b><?php echo $row->pay_date; ?></b></div></td>
			  </tr>
			  <tr class="form-group" width="50%">
				<td>&nbsp;<span class="col-sm-4">Tracking Number: </span><div class="col-sm-2"><b><?php echo $rows->id; ?></b></div></td>
				<td>&nbsp;<span class="col-sm-3">Business Name: </span><div class="col-sm-3"><b><?php echo $row->bizname; ?></b></div></td>
			  </tr>
			</table>
			<hr>
			
			<table width="100%" border="1">
			  <tr bgcolor="orange">
				<td><div align="left"><strong>&nbsp;Description</strong></div></td>
				<td><div align="right"><strong>&nbsp;Amount</strong></div></td>
				<td><div align="left"><strong>&nbsp;Description</strong></div></td>
				<td><div align="right"><strong>&nbsp;Amount</strong></div></td>
			  </tr>

			  <tr>
				<td><div align="left">&nbsp;Basic Pay</div></td>
				<td><div align="right"><?php echo $row->basic_pay; ?>&nbsp;</div></td>
				<td><div align="left">&nbsp;Pension</div></td>
				<td><div align="right"><?php echo $row->pension; ?>&nbsp;</div></td>
			  </tr>
			  <tr>
				<td><div align="left">&nbsp;Overtime</div></td>
				<td><div align="right"><?php echo $row->overtime; ?>&nbsp;</div></td>
				<td><div align="left">&nbsp;Health Insurance</div></td>
				<td><div align="right"><?php echo $row->health_insurance; ?>&nbsp;</div></td>
			  </tr>
			  <tr>
				<td><div align="left">&nbsp;Paid Leaves</div></td>
				<td><div align="right"><?php echo $row->paid_leave; ?>&nbsp;</div></td>
				<td><div align="left">&nbsp;Unpaid Leave</div></td>
				<td><div align="right"><?php echo $row->unpaid_leave; ?>&nbsp;</div></td>
			  </tr>
			  <tr>
				<td><div align="left">&nbsp;Transport Allowance</div></td>
				<td><div align="right"><?php echo $row->transport_allowance; ?>&nbsp;</div></td>
				<td><div align="left">&nbsp;Tax Deduction</div></td>
				<td><div align="right"><?php echo $row->tax_deduction; ?>&nbsp;</div></td>
			  </tr>
			  <tr>
				<td><div align="left">&nbsp;Medical Allowance</div></td>
				<td><div align="right"><?php echo $row->medical_allowance; ?>&nbsp;</div></td>
				<td><div align="left">&nbsp;Salary Loan</div></td>
				<td><div align="right"><?php echo $row->salary_loan; ?>&nbsp;</div></td>
			  </tr>
			  <tr>
				<td><div align="left">&nbsp;Bonus</div></td>
				<td><div align="right"><?php echo $row->bonus; ?>&nbsp;</div></td>
				<td><div align="left">&nbsp;Other Allowance</div></td>
				<td><div align="right"><?php echo $row->other_allowance; ?>&nbsp;</div></td>
			  </tr>
			  <tr>
				<td><div align="left">&nbsp;<b>Total Pay</b></div></td>
				<td><div align="right"><b><?php echo number_format($row->gross_amount,2,'.',','); ?></b>&nbsp;</div></td>
				<td><div align="left">&nbsp;<b>Total Deduction</b></div></td>
				<td><div align="right"><b><?php echo number_format($row->total_deduction,2,'.',','); ?></b>&nbsp;</div></td>
			  </tr>
			  <tr>
				<td><div align="left"></div></td>
				<td><div align="right"></div></td>
				<td><div align="left">&nbsp;<b>Net Pay</b></div></td>
				<td><div align="right"><b><?php echo number_format($row->paid_amount,2,'.',','); ?></b>&nbsp;</div></td>
			  </tr>
			</table>
			<hr>
			
			<table width="100%" border="1" bordercolor="#000000">
			  <tr>
				<td colspan="5" bgcolor="orange"><div align="left"><strong>Net Pay Distribution</strong></div> </td>
			  </tr>
			  <tr class="form-group">
				<td>&nbsp;<b>Payment Method:</b><p>&nbsp;<?php echo $row->payment_method; ?></p></td>
				<td>&nbsp;<b>Bank Name:</b><p>&nbsp; <?php echo $row->bank_name; ?></p></td>
				<td>&nbsp;<b>Account Number:</b><p>&nbsp; <?php echo $row->acctno; ?></p></td>
				<td>&nbsp;<b>Description:</b><p>&nbsp; <?php echo $row->adesc; ?></p></td>
				<td>&nbsp;<b>Paid Amount:</b><p>&nbsp;  <?php echo number_format($row->paid_amount,2,'.',','); ?></p></td>
			  </tr>
			</table>
			<hr>

			<table width="100%" border="1" bordercolor="#000000">
			  <tr class="form-group">
				<td>&nbsp;<b>Comments:</b><p>&nbsp;<?php echo $row->comment; ?></p></td>
			  </tr>
			</table>
			
			 </div>
			  
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
