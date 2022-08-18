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
            <i class="fa fa-globe"></i> <?php echo $get_verified['fname']."'s".'&nbsp;'."Profile"; ?>
            <small class="pull-right"><b>Last Withdrawal Date:</b> <?php echo ($get_verified['last_withdraw_date'] == '0000-00-00') ? 'NIL' : $get_verified['last_withdraw_date']; ?> </small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
		<div class="box box-widget widget-user">
        <div class="col-sm-4 invoice-col">
          <div class="widget-user-header bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>-active" align="center">
           <h4 class="widget-user-username">ACCOUNT PROFILE:<h4>
		  </div>
		  <div class="widget-user-image">
          <address>
            <img src="../<?php echo $get_verified['image'];?>" class="img-circle" width="80" height="80"/>
          </address>
		  </div>
        </div>
		</div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <h4><b>CUSTOMER DETAILS:</b></h4>
          <address>
            <b>Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <?php echo $get_verified['fname'].'&nbsp;'.$get_verified['lname']; ?><br>
            <b>Address :</b> <?php echo $get_verified['addrs1'].'&nbsp;'.$get_verified['addrs2']; ?><br>
            <b>Phone &nbsp;&nbsp;&nbsp;&nbsp;:</b> <?php echo $get_verified['phone']; ?> <br>
            <b>Email &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <?php echo $get_verified['email']; ?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <h4><b>ACCOUNT INFORMATION:</b></h4><br>
		<address>
          <b>Account Number :</b> <b class="label label-danger"><?php echo $get_verified['account']; ?></b> <br>
          <b>Account Balance &nbsp;:</b> <b class="label label-success"><?php echo $get_sysv['currency'].number_format($get_verified['balance'],2,'.',','); ?></b><br>
		  <b>Opening Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <b class="label label-info"><?php echo $get_verified['date_time']; ?></b> <br>
		</address>
		</div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
	  <hr>
	  <h3 class="page-header"><b>TRANSACTION HISTORY</b></h3>

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table id="example1" class="table table-striped">
            <thead>
            <tr>
              <th>TXID</th>
              <th>T.Type</th>
              <th>Name</th>
              <th>Date/Time</th>
              <th>Amount</th>
            </tr>
            </thead>
            <tbody>
<?php
$verify_Thistory = mysqli_query($link, "SELECT * FROM transaction WHERE acctno = '$get_account' ORDER BY id DESC") or die ("Error:" . mysqli_error($link));
while($get_Thistory = mysqli_fetch_array($verify_Thistory))
{
?>
            <tr>
              <td><?php echo $get_Thistory['txid']; ?></td>
              <td><?php echo $get_Thistory['t_type']; ?></td>
              <td><?php echo $get_Thistory['fn'].'&nbsp;'.$get_Thistory['ln']; ?></td>
              <td><?php echo $get_Thistory['date_time']; ?></td>
              <td><?php echo $get_sysv['currency'].number_format($get_Thistory['amount'],2,'.',','); ?></td>
            </tr>
<?php
}
?>
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
          <p class="lead">Customer Signature:</p>
          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
		  <?php echo ($get_verified['c_sign'] != "") ? '<img src="../'.$get_verified['c_sign'].'" alt="Signature" width="150" height="150">' : '<span class="label label-danger">Account Not Verified!</span>'; ?>
		  </p>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <p class="lead">UNPAID LOAN INFO. / EXPIRATION DATE:</p>
		  
          <div class="table-responsive">
            <table class="table">
			<?php
			$verify_loaninfo = mysqli_query($link, "SELECT SUM(payment) FROM pay_schedule WHERE tid = '$get_account' AND status = 'UNPAID'") or die ("Error:" . mysqli_error($link));
			$verify_loaninfo2 = mysqli_query($link, "SELECT * FROM loan_info WHERE p_status = 'UNPAID' OR p_status = 'PART-PAID' AND baccount = '$get_account'") or die ("Error:" . mysqli_error($link));
			$get_loaninfo2 = mysqli_fetch_array($verify_loaninfo2);
			if(mysqli_num_rows($verify_loaninfo) == 1)
			{
			$get_loaninfo = mysqli_fetch_array($verify_loaninfo);
			?>
              <tr>
                <th style="width:20%">Amount: <?php echo $get_sysv['currency'].number_format($get_loaninfo['SUM(payment)'],0,'.',','); ?></th>
                <td>Date Release: <br><?php echo $get_loaninfo2['date_release']; ?></td>
				<td>Payment Deadline: <br><?php echo $get_loaninfo2['pay_date']; ?></td>
              </tr>
		    <?php 
			}else{
				echo "<h5><div class='label label-success'>Congrat! No unpaid loan!!</div></h5>";
			}
			?>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="invoice-print.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode('410'); ?>&&uid=<?php echo $get_account; ?>" target="_blank" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> pull-right"><i class="fa fa-print"></i> Print</a>
        </div>
      </div>
<?php
}
?>
    </section>