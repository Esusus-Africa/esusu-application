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


<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
      
<h4>
<?php 
$id = $_GET['uid'];
$select_sm = mysqli_query($link, "SELECT SUM(investment_bal) FROM borrowers WHERE account = '$id'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "0.00";
}
else{
while($row = mysqli_fetch_array($select_sm))
{
echo $get_sysv['currency'].number_format($row['SUM(investment_bal)'],2,".",",")."</b>";
}
}
?>
</h4>
      <p>Total Recurring Savings</p>
            </div>
            <div class="icon"> 
      <i class="fa fa-calculator"></i> 
      </div>
 </div>
        </div>


        <!-- ./col -->
       <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
      
      <h4>
<?php 
$id = $_GET['uid'];
$select = mysqli_query($link, "SELECT SUM(amount) FROM loan_info WHERE baccount = '$id' AND status = 'Approved'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "0.00";
}
else{
while($row = mysqli_fetch_array($select))
{
echo $get_sysv['currency'].number_format($row['SUM(amount)'],2,".",",")."</b>";
}
}
?>
      </h4>
              <p>Loan Received</p>
            </div>
            <div class="icon">
              <i class="fa fa-cloud-download"></i>
            </div>
          </div>
        </div>

       
    <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
      
      <h4>
<?php
$id = $_GET['uid'];
$select = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments WHERE remarks = 'paid' AND account_no = '$id'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "0.00";
}
else{
while($row = mysqli_fetch_array($select))
{
echo $get_sysv['currency'].number_format($row['SUM(amount_to_pay)'],2,".",",")."</b>";
}
}
?>
      </h4>
              <p>Total Repayment</p>
            </div>
            <div class="icon">
              <i class="fa fa-calculator"></i>
            </div>
          </div>
        </div>


<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
      
      <h4>
<?php
$id = $_GET['uid'];
$select = mysqli_query($link, "SELECT SUM(amount_requested) FROM mcustomer_wrequest WHERE status = 'Approved' AND account_number = '$id'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "0.00";
}
else{
while($row = mysqli_fetch_array($select))
{
echo $get_sysv['currency'].number_format($row['SUM(amount_requested)'],2,".",",")."</b>";
}
}
?>
      </h4>
              <span>Total Withdrawal</span><br>
              <span style="font-size: 10px;"><b>(For Recurring Savings)</b></span>
            </div>
            <div class="icon">
              <i class="fa fa-cloud-download"></i>
            </div>
          </div>
        </div>

<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
      
      <h4>
<?php 
$id = $_GET['uid'];
$select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Withdraw' AND acctno  = '$id'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "0.00";
}
else{
while($row = mysqli_fetch_array($select))
{
echo $get_sysv['currency'].number_format($row['SUM(amount)'],2,".",",")."</b>";
}
}
?>
      </h4>
              <span>Total Withdrawal</span><br>
              <span style="font-size: 10px;"><b>(Normal Savings)</b></span>
            </div>
            <div class="icon">
              <i class="fa fa-cloud-download"></i>
            </div>
          </div>
        </div>
        
<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
      
      <h4>
<?php 
$id = $_GET['uid'];
$select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Deposit' AND acctno  = '$id'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "0.00";
}
else{
while($row = mysqli_fetch_array($select))
{
echo $get_sysv['currency'].number_format($row['SUM(amount)'],2,".",",")."</b>";
}
}
?>
      </h4>
              <span>Total Deposit</span><br>
              <span style="font-size: 10px;"><b>(Normal Savings)</b></span>
            </div>
            <div class="icon">
              <i class="fa fa-calculator"></i>
            </div>
          </div>
        </div>
        
<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
      
      <h4>
<?php 
$id = $_GET['uid'];
$selecte = mysqli_query($link, "SELECT * FROM transaction WHERE acctno = '$id'") or die (mysqli_error($link));
$nume = mysqli_num_rows($selecte);
echo $nume;
?>
      </h4>
              <span>Total Transaction</span><br>
              <span style="font-size: 10px;"><b>(Normal Savings)</b></span>
            </div>
            <div class="icon">
              <i class="fa fa-database"></i>
            </div>
          </div>
        </div>
        
<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
      
      <h4>
          <?php echo $get_sysv['currency'].number_format($get_verified['balance'],2,'.',','); ?>
      </h4>
              <span>Ledger Balance</span><br>
              <span style="font-size: 10px;"><b>(Normal Savings)</b></span>
            </div>
            <div class="icon">
              <i class="fa fa-database"></i>
            </div>
          </div>
        </div>


        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> <?php echo $get_verified['fname']."'s".'&nbsp;'."Profile"; ?>
            <small class="pull-right"><b>Last Withdrawal Date:</b> <?php echo $get_verified['last_withdraw_date']; ?> </small>
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
              <td height="30px"><span style="color: orange;"><?php echo $get_verified['addrs']; ?></span></td>
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
$verify_Thistory = mysqli_query($link, "SELECT * FROM transaction WHERE acctno = '$get_account'") or die ("Error:" . mysqli_error($link));
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
          <p class="lead">UNPAID LOAN INFORMATION / EXPIRATION DATE:</p>

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
<?php
}
?>
    </section>
  <!-- /.content -->
</div>
</body>
</html>
