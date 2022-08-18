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

<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
      
<h4>
<?php 
$id = $_GET['acn'];
$select_sm = mysqli_query($link, "SELECT SUM(investment_bal) FROM borrowers WHERE account = '$id'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "0.00";
}
else{
while($row = mysqli_fetch_array($select_sm))
{
echo number_format($row['SUM(investment_bal)'],2,".",",")."</b>";
}
}
?>
</h4>
      <p>Total Recurring Savings</p>
            </div>
            <div class="icon"> 
      <i class="fa fa-hdd-o"></i> 
      </div>
            <a href="my_savings_plan.php?tid=<?php echo $_GET['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo base64_encode("405"); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>
        </div>


        <!-- ./col -->
       <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
      
      <h4>
<?php 
$id = $_GET['acn'];
$select = mysqli_query($link, "SELECT SUM(amount) FROM loan_info WHERE baccount = '$id' AND status = 'Approved'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "0.00";
}
else{
while($row = mysqli_fetch_array($select))
{
echo number_format($row['SUM(amount)'],2,".",",")."</b>";
}
}
?>
      </h4>
              <p>Loan Received</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
           <a href="listloans.php?tid=<?php echo $_GET['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo base64_encode("405"); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

       
    <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
      
      <h4>
<?php
$id = $_GET['acn'];
$select = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments WHERE remarks = 'paid' AND account_no = '$id'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "0.00";
}
else{
while($row = mysqli_fetch_array($select))
{
echo number_format($row['SUM(amount_to_pay)'],2,".",",")."</b>";
}
}
?>
      </h4>
              <p>Total Repayment</p>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
            <a href="list_payloans.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo base64_encode("490"); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>


<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
      
      <h4>
<?php
$id = $_GET['acn'];
$select = mysqli_query($link, "SELECT SUM(amount_requested) FROM mcustomer_wrequest WHERE status = 'Approved' AND account_number = '$id'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "0.00";
}
else{
while($row = mysqli_fetch_array($select))
{
echo number_format($row['SUM(amount_requested)'],2,".",",")."</b>";
}
}
?>
      </h4>
              <p>Amount Withdrawed</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
           <a href="all_wrequest.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo base64_encode("490"); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i><span style="color: blue;"> <?php echo $get_verified['fname']."'s".'&nbsp;'."Profile"; ?></span>
            <small class="pull-right"><b style="color: blue;">Last Withdrawal Date:</b> <?php echo ($get_verified['last_withdraw_date'] == '0000-00-00') ? '<span style="color: orange;">NIL</span>' : '<span style="color: orange;">'.$get_verified['last_withdraw_date'].'</span>'; ?> </small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
		<div class="box box-widget widget-user">
        <div class="col-sm-4 invoice-col">
          <div class="widget-user-header bg-blue-active" align="center">
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
          <h4><b style="color: blue;">CUSTOMER DETAILS:</b></h4>
          <address>
            <table>
            <tr>
              <td height="30px"><b style="color: blue;">Name :</b></td>
              <td height="30px"><span style="color: orange;"><?php echo $get_verified['fname'].'&nbsp;'.$get_verified['lname']; ?></span></td>
            </tr>
            <tr>
              <td height="30px"><b style="color: blue;">Address :</b></td>
              <td height="30px"><span style="color: orange;"><?php echo $get_verified['addrs1'].'&nbsp;'.$get_verified['addrs2']; ?></span></td>
            </tr>
            <tr>
              <td height="30px"><b style="color: blue;">Phone Number :</b></td>
              <td height="30px"><span style="color: orange;"><b><?php echo $get_verified['phone']; ?></span></b></td>
            </tr>
            <tr>
              <td height="30px"><b style="color: blue;">Email Address :</b></td>
              <td height="30px"><span style="color: orange;"><?php echo $get_verified['email']; ?></span></td>
            </tr>
          </table>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <h4><b style="color: blue;">ACCOUNT INFORMATION:</b></h4><br>
    <address>
          <table>
            <tr>
              <td height="30px"><b style="color: blue;">Account Number :</b></td>
              <td height="30px"><b class="label bg-orange"><?php echo $get_verified['account']; ?></b></td>
            </tr>
            <tr>
              <td height="30px"><b style="color: blue;">Ledger Balance :</b></td>
              <td height="30px"><b class="label bg-blue"><?php echo $get_sysv['currency'].number_format($get_verified['balance'],2,'.',','); ?> </b></td>
            </tr>
            <tr>
              <td height="30px"><b style="color: blue;">Opening Date :</b></td>
              <td height="30px"><b class="label bg-orange"><?php echo $get_verified['date_time']; ?></b></td>
            </tr>
          </table>
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
        <!-- /.col -->
        <div class="col-xs-12">
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
          <a href="invoice-print.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode('410'); ?>&&uid=<?php echo $get_account; ?>" target="_blank" class="btn bg-blue pull-right"><i class="fa fa-print"></i> Print</a>
        </div>
      </div>
<?php
}
?>
    </section>