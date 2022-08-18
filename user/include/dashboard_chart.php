<?php
$get_account = $_GET['tid'];
$get_actno = $_GET['acn'];
$verify_cus = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$get_account'") or die ("Error:" . mysqli_error($link));
$get_verified = mysqli_fetch_array($verify_cus);
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
            <img src="<?php echo ($get_verified['image'] != '') ? $fetchsys_config['file_baseurl'].$get_verified['image'] : $fetchsys_config['file_baseurl'].'image-placeholder.jpg'; ?>" class="img-circle" width="80" height="80"/>
          </address>
      </div>
        </div>
    </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <h4><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">CUSTOMER DETAILS:</b><img src="<?php echo $fetchsys_config['file_baseurl']; ?>down-arrow-new.gif" width="30px" height="30px"></h4>
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
        <div class="slideshow-container">
          <h4><b style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">ACCOUNT INFORMATION:</b><img src="<?php echo $fetchsys_config['file_baseurl']; ?>down-arrow-new.gif" width="30px" height="30px"></h4><br>

    <?php
    //echo $walletafrica_status;
    if($mo_virtualacct_status == "Active" && ($walletafrica_status == "NotActive" || $walletafrica_status == "Active")){
        
        include("mnfy_virtualaccount.php");
        
    }
    elseif($mo_virtualacct_status == "NotActive" && $walletafrica_status == "Active"){
        
        include("../config/walletafrica_restfulapis_call.php");
        include("walletafrica_virtulaccount.php");
        
    }
    ?>
    
    <a class="myprev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="mynext" onclick="plusSlides(1)">&#10095;</a>
    </div>
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
if($iinvestment_manager === "On" && $bbranchid != "")
{
?>          
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-handshake"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Investment</span>
                <span class="info-box-number">
                  <?php 
                    $select_sm = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acctno'") or die (mysqli_error($link));
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
                    $select_sm = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acctno'") or die (mysqli_error($link));
                    $row = mysqli_fetch_array($select_sm);
                    echo number_format($row['target_savings_bal'],2,".",",")."</b>";
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
                    $select = mysqli_query($link, "SELECT SUM(amount) FROM loan_info WHERE baccount = '$acctno' AND status = 'Approved'") or die (mysqli_error($link));
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
                    $select = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments WHERE remarks = 'paid' AND account_no = '$acctno'") or die (mysqli_error($link));
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
                    $select = mysqli_query($link, "SELECT SUM(amount) FROM transfer_history WHERE userid = '$acctno' AND status = 'SUCCESSFUL'") or die (mysqli_error($link));
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
if($iwallet_manager === "On" || $bbranchid === "")
{
?>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> elevation-1"><i class="fas fa-shopping-basket"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Wallet Balance</span>
                <span class="info-box-number">
                  <?php 
                    echo "<span id='wallet_balance'>".$bbcurrency.number_format($bwallet_balance,2,".",",")."</span>";
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
if($bbranchid != "" && $isavings_account === "On"){
?>
      
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> elevation-1"><i class="fas fa-money"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Ledger Balance</span>
                <span class="info-box-number">
                  <?php echo $bbcurrency.number_format($balance,2,".",","); ?>
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
                    $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE acctno  = '$acctno' AND (t_type = 'Withdraw' OR t_type = 'Withdraw-Charges')") or die (mysqli_error($link));
                    if(mysqli_num_rows($select)==0)
                    {
                        echo "0.00";
                    }
                    else{
                        while($row = mysqli_fetch_array($select))
                        {
                            echo "-".$bbcurrency.number_format($row['SUM(amount)'],2,".",",")."</b>";
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
                    $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Deposit' AND acctno  = '$acctno'") or die (mysqli_error($link));
                    if(mysqli_num_rows($select)==0)
                    {
                    echo "0.00";
                    }
                    else{
                    while($row = mysqli_fetch_array($select))
                    {
                    echo $bbcurrency.number_format($row['SUM(amount)'],2,".",",")."</b>";
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
                    $selecte = mysqli_query($link, "SELECT * FROM transaction WHERE acctno = '$acctno'") or die (mysqli_error($link));
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
    

</div>