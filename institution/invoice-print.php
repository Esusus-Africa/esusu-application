<?php include("include/header.php"); ?>
<div class="wrapper">
  <!-- Main content -->
 <section class="invoice">
      <!-- title row -->
<?php
$get_account = $_GET['uid'];
$verify_cus = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$get_account'") or die ("Error:" . mysqli_error($link));
$get_verified = mysqli_fetch_array($verify_cus);
$bwallet_balance = $get_verified['wallet_balance'];
$balance = $get_verified['balance'];
$verify_sys = mysqli_query($link, "SELECT * FROM systemset") or die ("Error:" . mysqli_error($link));
$get_sysv = mysqli_fetch_array($verify_sys);
?>

<div class="col-lg-12 col-xs-12">

  <div class="col-xs-12" style="border-style: dotted dotted dotted dotted;">
          <h2 class="page-header">
            <i class="fa fa-globe"></i><span style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"> <?php echo $get_verified['fname']."'s".'&nbsp;'."Profile"; ?></span>
            <small class="pull-right"><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Last Withdrawal Date:</b> <?php echo ($get_verified['last_withdraw_date'] == '0000-00-00') ? '<span style="color: '.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].';">NIL</span>' : '<span style="color: '.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].';">'.$get_verified['last_withdraw_date'].'</span>'; ?> </small>
          </h2>
        </div>

        <!-- /.col -->

      <!-- info row -->
      <div class="row invoice-info" style="border-style: none inset solid inset;">
    <div class="box box-widget widget-user">
        <div class="col-sm-4 invoice-col">
          <div class="widget-user-header bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>-active" align="center">
           
      </div>
      <div class="widget-user-image">
          <address>
            <?php echo ($get_verified['image'] != "" || $get_verified['image'] != "img/") ? '<img src="../'.$get_verified['image'].'" class="img-circle" width="80" height="80">' : '<img src="../img/image-placeholder.jpg" class="img-circle" width="80" height="80">'; ?>
          </address>
      </div>
        </div>
    </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <h4><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">CUSTOMER DETAILS:</b><img src="../image/down-arrow-new.gif" width="30px" height="30px"></h4>
          <address>
            <table>
            <tr>
              <td height="30px"><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>; font-size: 12px;">Name: </b></td>
              <td height="30px"><span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>; font-size: 14px;"><b><?php echo $get_verified['fname'].'&nbsp;'.$get_verified['lname']; ?></b></span></td>
            </tr>
            <tr>
              <td height="30px"><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>; font-size: 12px;">Address: </b></td>
              <td height="30px"><span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>; font-size: 14px;"><b><?php echo ($get_verified['addrs'] == "") ? "-------" : $get_verified['addrs']; ?></b></span></td>
            </tr>
            <tr>
              <td height="30px"><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>; font-size: 12px;">Phone: </b></td>
              <td height="30px"><span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>; font-size: 14px;"><b><?php echo $get_verified['phone']; ?></b></span></td>
            </tr>
            <tr>
              <td height="30px"><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>; font-size: 12px;">Email: </b></td>
              <td height="30px"><span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>; font-size: 14px;"><b><?php echo $get_verified['email']; ?></b></span></td>
            </tr>
          </table>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <h4><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">ACCOUNT INFORMATION:</b><img src="../image/down-arrow-new.gif" width="30px" height="30px"></h4><br>
    <?php
    $search_vaccount = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$get_account' AND status = 'ACTIVE' AND gateway_name = 'monify'");
    if(mysqli_num_rows($search_vaccount) == 0){
    ?>
    
    <address>
        <table>
            <tr>
                <td height="30px"><b style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>; font-size:14px;">No Wallet Account Yet!!</b></td>
            </tr>
        </table>
    </address>
    
    <?php
    }else{
        $fetch_vaccount = mysqli_fetch_array($search_vaccount);
    
        //MONIFY GATEWAY STATUS
        $mo_fund_status = $fetchsys_config['mo_status'];
      
        $my_gateway = $fetch_vaccount['gateway_name'];
        $bank_name = $fetch_vaccount['bank_name'];
        $account_number = $fetch_vaccount['account_number'];
        $account_name = $fetch_vaccount['account_name'];
    ?>
    <address>
          <table>
            <tr>
              <td height="30px"><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>; font-size: 12px;">Bank Name: </b></td>
              <td height="30px"><b style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>; font-size:14px;">&nbsp;<?php echo strtoupper($bank_name) ?></b></td>
            </tr>
            <tr>
              <td height="30px"><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>; font-size: 12px;">Account Name: </b></td>
              <td height="30px"><b style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>; font-size:13px;">&nbsp;<?php echo strtoupper($account_name); ?> </b></td>
            </tr>
            <tr>
              <td height="30px"><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>; font-size: 12px;">Account No.: </b></td>
              <td height="30px"><b style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>; font-size:20px;">&nbsp;<?php echo $account_number; ?></b></td>
            </tr>
          </table>
    </address>
    <?php
    }
    ?>
    </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column 
        <div class="col-xs-6">
          <p class="lead">Customer Signature:</p>
          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
      <?php //echo ($get_verified['c_sign'] != "") ? '<img src="../'.$get_verified['c_sign'].'" alt="Signature" width="150" height="150">' : '<span class="label label-danger">Account Not Verified!</span>'; ?>
      </p>
        </div>
    -->
    
    <br>
    
        <!-- /.col -->
      </div>
      <!-- /.row -->
        
        <div class="row">

<?php
if($iinvestment_manager === "On")
{
?>          
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-handshake"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Investment</span>
                <span class="info-box-number">
                  <?php 
                    $select_sm = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$get_account'") or die (mysqli_error($link));
                    $row = mysqli_fetch_array($select_sm);
                    echo number_format($row['investment_bal'],2,".",",")."</b>";
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
<?php
}
else{
?>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-balance-scale"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Savings</span>
                <span class="info-box-number">
                  <?php 
                    $select_sm = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$get_account'") or die (mysqli_error($link));
                    $row = mysqli_fetch_array($select_sm);
                    echo number_format($row['investment_bal'],2,".",",")."</b>";
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>


<?php
}
?>

        
<?php
if($iloan_manager === "On"){
?>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-american-sign-language-interpreting"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Loan Received</span>
                <span class="info-box-number">
                  <?php 
                    $select = mysqli_query($link, "SELECT SUM(amount) FROM loan_info WHERE baccount = '$get_account' AND status = 'Approved'") or die (mysqli_error($link));
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
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-calculator"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Repayment</span>
                <span class="info-box-number">
                  <?php
                    $select = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments WHERE remarks = 'paid' AND account_no = '$get_account'") or die (mysqli_error($link));
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
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
<?php
}
else{
?>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-forward"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Transfer</span>
                <span class="info-box-number">
                  <?php 
                    $select = mysqli_query($link, "SELECT SUM(amount) FROM transfer_history WHERE userid = '$get_account' AND status = 'SUCCESSFUL'") or die (mysqli_error($link));
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
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-mobile"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total SMS</span>
                <span class="info-box-number">
                  <?php 
                    echo "<span id='smsunit_balance'>".number_format(($bwallet_balance/$fetchsys_config['fax']),2,'.',',')." unit(s)</span>";
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->


<?
}
?>

<?php
if($iwallet_manager === "On")
{
?>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-shopping-basket"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Wallet Balance</span>
                <span class="info-box-number">
                  <?php 
                    echo "<span id='wallet_balance'>".$icurrency.number_format($bwallet_balance,2,".",",")."</span>";
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
<?php
}
else{
    echo "";
}
?>
        
<?php
if($isavings_account === "On"){
?>
      
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-money"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Ledger Balance</span>
                <span class="info-box-number">
                  <?php echo $icurrency.number_format($balance,2,".",","); ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-minus"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Debit</span>
                <span class="info-box-number">
                  <?php 
                    $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE acctno  = '$get_account' AND (t_type = 'Withdraw' OR t_type = 'Withdraw-Charges')") or die (mysqli_error($link));
                    if(mysqli_num_rows($select)==0)
                    {
                        echo "0.00";
                    }
                    else{
                        while($row = mysqli_fetch_array($select))
                        {
                            echo "-".$icurrency.number_format($row['SUM(amount)'],2,".",",")."</b>";
                        }
                    }
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-plus"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Credit</span>
                <span class="info-box-number">
                  <?php 
                    $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Deposit' AND acctno  = '$get_account'") or die (mysqli_error($link));
                    if(mysqli_num_rows($select)==0)
                    {
                    echo "0.00";
                    }
                    else{
                    while($row = mysqli_fetch_array($select))
                    {
                    echo $icurrency.number_format($row['SUM(amount)'],2,".",",")."</b>";
                    }
                    }
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-server"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Ledger Transaction</span>
                <span class="info-box-number">
                  <?php 
                    $selecte = mysqli_query($link, "SELECT * FROM transaction WHERE acctno = '$get_account'") or die (mysqli_error($link));
                    $nume = mysqli_num_rows($selecte);
                    echo $nume;
                    ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

<?php
}
else{
    //Nothing
}
?>
        
      </div>

    
</div>



<!-- Main content -->
<div class="row">
    
    <hr>
	  <h3 class="page-header"><b>TRANSACTION HISTORY</b></h3>

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th><input type="checkbox" id="select_all"/></th>
                <th>Date</th>
                <th>TxID</th>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
            </tr>
            </thead>
            <tbody>
<?php
$verify_Thistory = mysqli_query($link, "SELECT * FROM transaction WHERE acctno = '$get_account'") or die ("Error:" . mysqli_error($link));
while($get_Thistory = mysqli_fetch_array($verify_Thistory))
{
?>
            <tr>
                <td><input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='<?php echo $get_Thistory['id']; ?>'></td>
                <td><?php echo date("d/m/Y g:i A", strtotime($get_Thistory['date_time'])); ?></td>
                <td><?php echo "<a href='view_receipt.php?id=".$get_Thistory['id']."' target='_blank'>".$get_Thistory['txid']."</a>"; ?></td>
                <td><?php echo ($get_Thistory['remark'] == "") ? "NILL" : $get_Thistory['remark']; ?></td>
                <td><?php echo ($get_Thistory['t_type'] == "Withdraw" || $get_Thistory['t_type'] == "Withdraw-Charges" || $get_Thistory['t_type'] == "Transfer") ? $get_Thistory['currency'].number_format($get_Thistory['amount'],2,'.',',') : "---"; ?></td>
                <td><?php echo ($get_Thistory['t_type'] == "Deposit" || $get_Thistory['t_type'] == "Transfer-Received") ? $get_Thistory['currency'].number_format($get_Thistory['amount'],2,'.',',') : "---"; ?></td>
                <td><?php echo ($get_Thistory['balance'] == "") ? "---" : $get_Thistory['currency'].number_format($get_Thistory['balance'],2,'.',','); ?></td>
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

</div>
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

<script>
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
</html>
