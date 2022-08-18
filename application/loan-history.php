<?php include("include/header.php"); ?>
<div class="wrapper">
  <!-- Main content -->
 <section class="invoice">
      <!-- title row -->
<?php
$get_account = $_GET['uid'];
$verify_cus = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$get_account'") or die ("Error:" . mysqli_error($link));
while($get_verified = mysqli_fetch_array($verify_cus))
{ 
$verify_sys = mysqli_query($link, "SELECT * FROM systemset") or die ("Error:" . mysqli_error($link));
$get_sysv = mysqli_fetch_array($verify_sys);
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
<?php
}
?>
    </section>
  <!-- /.content -->
</div>
</body>
</html>
