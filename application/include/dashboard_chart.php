<div class="row">
        
        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-header with-border">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="box-group" id="accordion">

                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        Wallet Analysis
                      </a>
                    </h4>
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
<?php
if($backend_corporate_wallet == '1')
{
?>

      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
        <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-shopping-basket"></i></span>
        <?php
        $search_iNGN = mysqli_query($link, "SELECT SUM(wallet_balance) FROM institution_data");
        $get_i = mysqli_fetch_array($search_iNGN);
        $i_wb = $get_i['SUM(wallet_balance)'];

        $search_cNGN = mysqli_query($link, "SELECT SUM(wallet_balance) FROM cooperatives");
        $get_c = mysqli_fetch_array($search_cNGN);
        $c_wb = $get_c['SUM(wallet_balance)'];

        $total_NGN = number_format(($i_wb + $c_wb),2,'.',',');
        ?>
          <div class="info-box-content">
            <span class="info-box-text"><?php echo ($backend_corporate_wallet == 1) ? '<a href="mywallet.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_3">Inst. Wallet Bal.<i class="fa fa-arrow-circle-right"></i></a></span>' : 'Inst. Wallet Bal.'; ?>
            <span class="info-box-number">
                <?php echo $total_NGN; ?>  
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
  echo '';
}
?>


<?php
if($backend_agent_wallet == '1' || $backend_individual_wallet == '1')
{
?> 

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
        <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-shopping-basket"></i></span>
        <?php
        $selectUB = mysqli_query($link, "SELECT SUM(transfer_balance) FROM user") or die (mysqli_error($link));
        $fetchUB = mysqli_fetch_array($selectUB);
        $ao_wb = $fetchUB['SUM(transfer_balance)'];
        
        $search_boNGN = mysqli_query($link, "SELECT SUM(wallet_balance) FROM borrowers");
        $get_bo = mysqli_fetch_array($search_boNGN);
        $bo_wb = $get_bo['SUM(wallet_balance)'];
        
        $totalBal = $ao_wb + $bo_wb;

        $total_NGN = number_format($totalBal,2,'.',',');
        ?>
          <div class="info-box-content">
            <span class="info-box-text"><?php echo ($backend_agent_wallet == 1 || $backend_individual_wallet == 1) ? '<a href="mywallet.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_4">Transfer WBal.<i class="fa fa-arrow-circle-right"></i></a></span>' : 'Transfer WBal.'; ?>
            <span class="info-box-number">
                <?php echo $total_NGN; ?>  
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
  echo '';
}
?>


<?php
if($list_client_subagent == '1')
{
?>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
        <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-shopping-basket"></i></span>
        <?php
        $selectSUB = mysqli_query($link, "SELECT SUM(wallet_balance) FROM user") or die (mysqli_error($link));
        $fetchSUB = mysqli_fetch_array($selectSUB);

        $total_NGN = number_format($fetchSUB['SUM(wallet_balance)'],2,'.',',');
        ?>
          <div class="info-box-content">
            <span class="info-box-text"><?php echo ($list_client_subagent == 1) ? '<a href="clientSubAgent?id='.$_SESSION['tid'].'&&mid=NDE5">Sub-wallet Bal.<i class="fa fa-arrow-circle-right"></i></a></span>' : 'Sub-wallet Bal.'; ?>
            <span class="info-box-number">
                <?php echo $total_NGN; ?>  
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
  echo '';
}
?>



<?php
if($backend_wallet_history == '1')
{
?> 

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
        <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-shopping-basket"></i></span>
        <?php
        $select = mysqli_query($link, "SELECT * FROM wallet_history") or die (mysqli_error($link));
        $number_wh = mysqli_num_rows($select);

        $total_NGN = number_format($number_wh,0,"",",");
        ?>
          <div class="info-box-content">
            <span class="info-box-text"><?php echo ($backend_wallet_history == 1) ? '<a href="mywallet.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1">Wallet Trans.<i class="fa fa-arrow-circle-right"></i></a></span>' : 'Wallet Trans.'; ?>
            <span class="info-box-number">
                <?php echo $total_NGN; ?>  
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
  echo '';
}
?>
                    </div>
                    </div>
                </div>

                <div class="panel box box-danger">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                        Loan / Repayment
                      </a>
                    </h4>
                  </div>
                  <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="box-body">

<?php
if($view_all_loans == '1' || $access_backend_loan_tab == '1')
{
?>

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-book"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_loans == '1' || $access_backend_loan_tab == '1') ? '<a href="listloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'">Loan Application<i class="fa fa-arrow-circle-right"></i></a>' : 'Loan Application'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT * FROM loan_info WHERE status = 'Approved' OR status = 'Disbursed'") or die (mysqli_error($link));
                $rowNum = mysqli_num_rows($select);
                echo number_format($rowNum,0,"",",")."</b>";
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
  echo '';
}
?>


<?php
if($view_all_loans == '1' || $access_backend_loan_tab == '1')
{
?>

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-american-sign-language-interpreting"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_loans == '1' || $access_backend_loan_tab == '1') ? '<a href="listloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'">Loans Released<i class="fa fa-arrow-circle-right"></i></a>' : 'Loans Released'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT SUM(amount) FROM loan_info WHERE status = 'Approved' OR status = 'Disbursed'") or die (mysqli_error($link));
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

<?php
}
else{
  echo '';
}
?>


<?php
if($view_due_loans == '1')
{
?>

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-exclamation-triangle"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_due_loans == '1') ? '<a href="dueloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'">Loan Due Amount<i class="fa fa-arrow-circle-right"></i></a>' : 'Loan Due Amount'; ?></span>
                <span class="info-box-number">
                <?php 
                $date_now = date("Y-m-d");
                $select = mysqli_query($link, "SELECT SUM(payment) FROM pay_schedule WHERE status = 'UNPAID' AND schedule <= '$date_now'") or die ("Error: " . mysqli_error($link));
                if(mysqli_num_rows($select)==0)
                {
                echo "0.00";
                }
                else{
                while($row = mysqli_fetch_array($select))
                {
                echo number_format($row['SUM(payment)'],2,".",",")."</b>";
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
  echo '';
}
?>


<?php
if($list_all_repayment == '1')
{
?>

      <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-calculator"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($list_all_repayment == '1') ? '<a href="listpayment?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'">Loan Repayment<i class="fa fa-arrow-circle-right"></i></a>' : 'Loan Repayment'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments WHERE remarks = 'paid'") or die (mysqli_error($link));
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
  echo '';
}
?>

                    </div>
                  </div>
                </div>
                
                <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                        Saas Subscription / Income & Expenses
                      </a>
                    </h4>
                  </div>
                  <div id="collapseThree" class="panel-collapse collapse">
                    <div class="box-body">

<?php
if($saas_subscription_history == '1')
{
?>

      <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-refresh"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($saas_subscription_history == '1') ? '<a href="paid_sub?id='.$_SESSION['tid'].'&&mid='.base64_encode("420").'">Saas Sub. Count<i class="fa fa-arrow-circle-right"></i></a>' : 'Saas Sub. Count'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE status = 'Paid'") or die (mysqli_error($link));
                $num = mysqli_num_rows($select);
                echo number_format($num,0,'',',');
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
  echo '';
}
?>


<?php
if($saas_subscription_history == '1')
{
?>

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-desktop"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($saas_subscription_history == '1') ? '<a href="paid_sub?id='.$_SESSION['tid'].'&&mid='.base64_encode("420").'">Total Saas Sub.<i class="fa fa-arrow-circle-right"></i></a>' : 'Total Saas Sub.'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT SUM(amount_paid) FROM saas_subscription_trans WHERE status = 'Paid'") or die (mysqli_error($link));
                if(mysqli_num_rows($select)==0)
                {
                echo "0.00";
                }
                else{
                while($row = mysqli_fetch_array($select))
                {
                echo number_format($row['SUM(amount_paid)'],2,".",",")."</b>";
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
  echo '';
}
?>


<?php
if($view_expenses == '1')
{
?>

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-mail-reply-all"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_expenses == '1') ? '<a href="listexpenses?id='.$_SESSION['tid'].'&&mid='.base64_encode("500").'">Total Expenses<i class="fa fa-arrow-circle-right"></i></a>' : 'Total Expenses'; ?></span>
                <span class="info-box-number">
                <?php 
                $date_now = date("Y-m-d");
                $select = mysqli_query($link, "SELECT SUM(eamt) FROM expenses") or die ("Error: " . mysqli_error($link));
                if(mysqli_num_rows($select) == 0)
                {
                  echo '0.00';
                }
                else{
                while($row = mysqli_fetch_array($select))
                {
                echo number_format($row['SUM(eamt)'],2,".",",")."</b>";
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
  echo '';
}
?>


<?php
if($view_income == '1')
{
?>

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-recycle"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_income == '1') ? '<a href="listincome?id='.$_SESSION['tid'].'&&mid='.base64_encode("500").'">Total Income<i class="fa fa-arrow-circle-right"></i></a>' : 'Total Income'; ?></span>
                <span class="info-box-number">
                <?php 
                $date_now = date("Y-m-d");
                $select = mysqli_query($link, "SELECT SUM(icm_amt) FROM income") or die ("Error: " . mysqli_error($link));
                if(mysqli_num_rows($select) == 0)
                {
                  echo '0.00';
                }
                else{
                while($row = mysqli_fetch_array($select))
                {
                echo number_format($row['SUM(icm_amt)'],2,".",",")."</b>";
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
  echo '';
}
?>

                    </div>
                    </div>
                    </div>
                    
                    
                    <div class="panel box box-danger">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                        Client / Aggregator
                      </a>
                    </h4>
                  </div>
                  <div id="collapseFour" class="panel-collapse collapse">
                    <div class="box-body">
                    
<?php
if($access_client_tab == '1' || $list_client == '1')
{
?>

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-street-view"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($list_client == '1') ? '<a href="listinstitution?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'">Total Agents<i class="fa fa-arrow-circle-right"></i></a>' : 'Total Agents'; ?></span>
                <span class="info-box-number">
                <?php 
                $selecte = mysqli_query($link, "SELECT * FROM institution_data WHERE account_type = 'agent'") or die (mysqli_error($link));
                $nume = mysqli_num_rows($selecte);
                echo number_format($nume,0,'',',');
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
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-institution"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($list_client == '1') ? '<a href="listinstitution?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'">Total Institution<i class="fa fa-arrow-circle-right"></i></a>' : 'Total Institution'; ?></span>
                <span class="info-box-number">
                <?php 
                $selecte = mysqli_query($link, "SELECT * FROM institution_data WHERE account_type = 'institution'") or die (mysqli_error($link));
                $nume = mysqli_num_rows($selecte);
                echo number_format($nume,0,'',',');
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
  echo '';
}
?>


<?php
if($list_aggregators == '1')
{
?>

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($list_aggregators == '1') ? '<a href="listaggregators?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'">Total Aggregator<i class="fa fa-arrow-circle-right"></i></a>' : 'Total Aggregator'; ?></span>
                <span class="info-box-number">
                <?php 
                $selecte = mysqli_query($link, "SELECT * FROM aggregator") or die (mysqli_error($link));
                $nume = mysqli_num_rows($selecte);
                echo number_format($nume,0,'',',');
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
  echo '';
}
?>


<?php
if($list_client_subagent == '1')
{
?>

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-user"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($list_client_subagent == '1') ? '<a href="clientSubAgent?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'">Total Sub-Agent<i class="fa fa-arrow-circle-right"></i></a>' : 'Total Sub-Agent'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT * FROM user WHERE id != '$tid' AND (role != 'super-admin' OR role != 'agent_manager' OR role != 'institution_super_admin' OR role != 'merchant_super_admin')") or die (mysqli_error($link));
                $num = mysqli_num_rows($select);
                echo number_format($num,0,'',',');
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
  echo '';
}
?>

                        </div>
                    </div>
                    </div>
                    
                    
                    <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                        Ledger Savings Reports
                      </a>
                    </h4>
                  </div>
                  <div id="collapseFive" class="panel-collapse collapse">
                    <div class="box-body">

<?php
if($view_all_transaction == '1')
{
?>    

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-plus"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_transaction == '1') ? '<a href="transaction?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'">Total Deposit<i class="fa fa-arrow-circle-right"></i></a>' : 'Total Deposit'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Deposit'") or die (mysqli_error($link));
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

<?php
}
else{
  echo '';
}
?>


<?php
if($view_all_transaction == '1')
{
?>    

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-minus"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_transaction == '1') ? '<a href="transaction?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'">Total Withdrawn<i class="fa fa-arrow-circle-right"></i></a>' : 'Total Withdrawn'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE t_type = 'Withdraw'") or die (mysqli_error($link));
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

<?php
}
else{
  echo '';
}
?>


<?php
if($view_all_transaction == '1')
{
?>    

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-map-marker"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_transaction == '1') ? '<a href="transaction?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'">Balance Left<i class="fa fa-arrow-circle-right"></i></a>' : 'Balance Left'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT SUM(balance) FROM borrowers") or die (mysqli_error($link));
                $num = mysqli_num_rows($select);
                if($num == 0)
                {
                echo "0.00";
                }
                else{
                    $row = mysqli_fetch_array($select);
                    $balance = number_format($row['SUM(balance)'],2,'.',',');
                    echo $balance."</b>";
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
  echo '';
}
?>


<?php
if($view_all_transaction == '1')
{
?>    

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-database"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_transaction == '1') ? '<a href="transaction?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'">Total Transaction<i class="fa fa-arrow-circle-right"></i></a>' : 'Total Transaction'; ?></span>
                <span class="info-box-number">
                <?php 
                $selecte = mysqli_query($link, "SELECT * FROM transaction") or die (mysqli_error($link));
                $nume = mysqli_num_rows($selecte);
                echo number_format($nume,0,'',',');
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
  echo '';
}
?>

        
                        </div>
                    </div>
                    </div>
                    
                    
                    <div class="panel box box-danger">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
                        Helpdesk / Customer / Investment
                      </a>
                    </h4>
                  </div>
                  <div id="collapseSix" class="panel-collapse collapse">
                    <div class="box-body">
                        
<?php
if($view_all_tickets == '1')
{
?>    

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-commenting"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_tickets == '1') ? '<a href="inboxmessage?id='.$_SESSION['tid'].'&&mid='.base64_encode("406").'">Helpdesk Message<i class="fa fa-arrow-circle-right"></i></a>' : 'Helpdesk Message'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT * FROM message") or die (mysqli_error($link));
                $num = mysqli_num_rows($select);
                echo number_format($num,0,'',',');
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
  echo '';
}
?>


<?php
if($view_all_customers == '1')
{
?>    

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_all_customers == '1') ? '<a href="customer?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'">All Customers<i class="fa fa-arrow-circle-right"></i></a>' : 'All Customers'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT * FROM borrowers") or die (mysqli_error($link));
                $num = mysqli_num_rows($select);
                echo number_format($num,0,'',',');
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
  echo '';
}
?>


<?php
if($view_investment_transaction == '1')
{
?>    

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-handshake"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($view_investment_transaction == '1') ? '<a href="msavings_trans?id='.$_SESSION['tid'].'&&mid='.base64_encode("490").'">Amount Invested<i class="fa fa-arrow-circle-right"></i></a>' : 'Amount Invested'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT SUM(amount) FROM all_savingssub_transaction WHERE status = 'successful'") or die (mysqli_error($link));
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

<?php
}
else{
  echo '';
}
?>


<?php
if($investment_withdrawal_request == '1')
{
?>    

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-money"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($investment_withdrawal_request == '1') ? '<a href="withdrawal_request?id='.$_SESSION['tid'].'&&mid='.base64_encode("490").'">Investment Withrawn<i class="fa fa-arrow-circle-right"></i></a>' : 'Investment Withrawn'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT SUM(amount_requested) FROM mcustomer_wrequest WHERE status = 'Approved'") or die (mysqli_error($link));
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
  echo '';
}
?>

                        </div>
                    </div>
                    </div>


                    <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">
                        Terminal / POS Report
                      </a>
                    </h4>
                  </div>
                  <div id="collapseSeven" class="panel-collapse collapse">
                    <div class="box-body">

<?php
if($backend_all_terminal == '1')
{
?>    

    <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-terminal"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($backend_all_terminal == '1') ? '<a href="allterminal?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'">Total Terminal<i class="fa fa-arrow-circle-right"></i></a>' : 'Total Terminal'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT * FROM terminal_reg") or die (mysqli_error($link));
                $num = mysqli_num_rows($select);
                echo number_format($num,0,'',',');
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
              <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-terminal"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($backend_all_terminal == '1') ? '<a href="allterminal?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'">Available Terminal<i class="fa fa-arrow-circle-right"></i></a>' : 'Available Terminal'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_status = 'Available'") or die (mysqli_error($link));
                $num = mysqli_num_rows($select);
                echo number_format($num,0,'',',');
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
              <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-search"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($backend_all_terminal == '1') ? '<a href="allterminal_report?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'">Daily POS Trans.<i class="fa fa-arrow-circle-right"></i></a>' : 'Daily POS Trans.'; ?></span>
                <span class="info-box-number">
                <?php 
                $today_record = date("Y-m-d");
                $select = mysqli_query($link, "SELECT SUM(amount), currencyCode FROM terminal_report WHERE date_time LIKE '$today_record%' AND (status = 'Success' OR status = 'Approved')") or die (mysqli_error($link));
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
              <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-rocket"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($backend_all_terminal == '1') ? '<a href="allterminal_report?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'">Total POS Trans.<i class="fa fa-arrow-circle-right"></i></a>' : 'Total POS Trans.'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT SUM(amount), currencyCode FROM terminal_report WHERE (status = 'Success' OR status = 'Approved')") or die (mysqli_error($link));
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
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-map-pin"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($backend_all_terminal == '1') ? '<a href="allterminal_report?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'">Daily POS TCount<i class="fa fa-arrow-circle-right"></i></a>' : 'Daily POS TCount'; ?></span>
                <span class="info-box-number">
                <?php 
                $today_record = date("Y-m-d");
                $select = mysqli_query($link, "SELECT * FROM terminal_report WHERE date_time LIKE '$today_record%' AND (status = 'Success' OR status = 'Approved')") or die (mysqli_error($link));
                $numee = mysqli_num_rows($select);
                
                echo number_format($numee,0,"",",")."</b>";
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
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-area-chart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?php echo ($backend_all_terminal == '1') ? '<a href="allterminal_report?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'">Total POS TCount<i class="fa fa-arrow-circle-right"></i></a>' : 'Total POS TCount'; ?></span>
                <span class="info-box-number">
                <?php 
                $select = mysqli_query($link, "SELECT * FROM terminal_report WHERE (status = 'Success' OR status = 'Approved')") or die (mysqli_error($link));
                $nume = mysqli_num_rows($select);
                
                echo number_format($nume,0,"",",")."</b>";
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
  echo '';
}
?>



<?php
if($backend_pending_terminal_settlement == '1')
{
?>   

      <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-question"></i></span>

              <div class="info-box-content">
              <span class="info-box-text"><?php echo ($backend_pending_terminal_settlement == '1') ? '<a href="term_pendSettlement?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'">Pending POS Settlement<i class="fa fa-arrow-circle-right"></i></a>' : 'Pending POS Settlement'; ?></span>
                <span class="info-box-number">
                <?php 
                ($backend_pending_terminal_settlement == "1") ? $checkd1 = mysqli_query($link, "SELECT SUM(pending_balance) FROM terminal_reg WHERE terminal_status = 'Assigned'") or die ("Error:" . mysqli_error($link)) : "";  
                $number_checkd1 = mysqli_fetch_array($checkd1);
                echo number_format($number_checkd1['SUM(pending_balance)'],2,".",",")."</b>";
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
              <span class="info-box-icon bg-blue elevation-1"><i class="fas fa-pie-chart"></i></span>

              <div class="info-box-content">
              <span class="info-box-text"><?php echo ($backend_pending_terminal_settlement == '1') ? '<a href="term_pendSettlement?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'">Total POS Settlement<i class="fa fa-arrow-circle-right"></i></a>' : 'Total POS Settlement'; ?></span>
                <span class="info-box-number">
                <?php 
                ($backend_pending_terminal_settlement == "1") ? $checkd1 = mysqli_query($link, "SELECT SUM(settled_balance) FROM terminal_reg WHERE terminal_status = 'Assigned'") or die ("Error:" . mysqli_error($link)) : "";  
                $number_checkd1 = mysqli_fetch_array($checkd1);
                echo number_format($number_checkd1['SUM(settled_balance)'],2,".",",")."</b>";
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
  echo '';
}
?>

                    </div>
                    </div>
                    </div>
                    
                    
                    </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>



   <section class="content">
      <div class="row">
    </div>
    
    <!--  Event codes starts here-->
     
          <div class="box box-info">
<?php
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$view_all_loans = $get_check['view_all_loans'];
$borrowers_reports = $get_check['borrowers_reports'];
if($view_all_loans == '1' || $borrowers_reports == '1')
{
?>  

    <div class="box-body">

       <div class="col-md-6">
         <div id="chartContainer" style="height: 370px; width: 100%;"></div>
         <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
         <!--<div id="chartdiv"></div>-->
       </div>
      
      
      <div class="col-md-6">
          <div id="chartContainer2" style="height: 370px; width: 100%;"></div>
        <!--<div id="chartdiv7"></div>-->
      </div>

<?php
}
else{
  echo '';
}
?>  
      </div>
    
</div>
</div>