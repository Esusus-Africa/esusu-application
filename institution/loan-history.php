<?php include("include/header.php"); ?>
<div class="wrapper">
  <!-- Main content -->
 <section class="invoice">
      <!-- title row -->
<?php
$get_account = $_GET['uid'];
$verify_cus = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$get_account'") or die ("Error:" . mysqli_error($link));
$get_verified = mysqli_fetch_array($verify_cus);
?>
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> <?php echo strtoupper($get_verified['fname']).'&nbsp;'.strtoupper($get_verified['lname']); ?>
          </h2>
        </div>
      </div>
	  <div align="center"><h3 class="page-header"><b>LOAN PAYMENT HISTORY</b></h3></div>
      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table id="example1" class="table table-striped">
            <thead>
            <tr>
              <th>Reference ID</th>
              <th>Loan ID</th>
              <th>Loan Balance</th>
              <th>Amount Payed</th>
              <th>Date</th>
              <th>Status</th>
            </tr>
            </thead>
            <tbody>
<?php
$verify_Thistory = mysqli_query($link, "SELECT * FROM payments WHERE account_no = '$get_account' AND remarks = 'paid'") or die ("Error:" . mysqli_error($link));
while($get_Thistory = mysqli_fetch_array($verify_Thistory))
{
$id = $get_Thistory['id'];
$refid = $get_Thistory['refid'];
$lid = $get_Thistory['lid'];
$tid = $get_Thistory['tid'];
$account_no = $get_Thistory['account_no'];
$remarks = $get_Thistory['remarks'];
$loan_bal = $get_Thistory['loan_bal'];
$amount_payed = $get_Thistory['amount_to_pay'];

$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_array($select1);
$currency = $row1['currency']; 
?>
            <tr>
                <td><?php echo $refid; ?></td>
                <td><?php echo $lid; ?></td>
                <td><?php echo $currency.number_format($loan_bal,2,".",","); ?></td>
                <td><?php echo $currency.number_format($amount_payed,2,".",","); ?></td>
                <td><?php echo $get_Thistory['pay_date']; ?></td>
                <td><?php echo ($remarks == 'paid') ? '<span class="label label-success">Paid</span>' : '<span class="label label-danger">Pending...</span>'; ?></td>    
            </tr>
<?php } ?>
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <!-- /.row -->

    </section>
  <!-- /.content -->
</div>
</body>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../vendor/jquery/jquery-1.9.1.min.js"><\/script>')</script>
<script src="../vendor/jquery/main.js"></script>

<!-- DataTables -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>

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
</html>