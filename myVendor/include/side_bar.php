   <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
    <?php 
      $id = $_SESSION['tid'];
      $ucall = mysqli_query($link, "SELECT * FROM user WHERE id = '$id'");
      $urow = mysqli_fetch_assoc($call);
      ?>
          <img src="<?php echo ($urow['image'] == '' || $urow['image'] == 'img/') ? $fetchsys_config['file_baseurl'].'avatar.png' : $fetchsys_config['file_baseurl'].$urow['image']; ?>" class="img-circle" alt="User Image" width="64" height="64">
        </div>
        <div class="pull-left info">
          <p><?php echo $row['username'] ;?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>

        </div>
      </div>
      <!-- search form -->
      
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
<ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("401")))
{
?>
		<li class="active"><a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
<?php
}
else{
	?>
		<li><a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
<?php } ?>


<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("650")))
{
?>
		<!--<li class="active"><a href="audit_trail.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("650"); ?>"><i class="fa fa-book"></i> <span>Audit Trail</span></a></li>-->
<?php
}
else{
	?>
		<!--<li><a href="audit_trail.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("650"); ?>"><i class="fa fa-book"></i> <span>Audit Trail</span></a></li>-->
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("490")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
?>
    <?php echo ($v_ctype != "Onlending Firm") ? '<li class="treeview active"><a href="#"><i class="fa fa-female"></i> <span>Product Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($v_ctype != "Onlending Firm") ? '<li><a href="notification.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Notification <span class="badge badge-orange right">: '. $myvnum_pendinginvestment .' :</span></a></li>' : ''; ?>
    <?php echo ($v_ctype != "Onlending Firm") ? '<li><a href="create_msavingsplan.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Product Setup</a></li>' : ''; ?>
    <?php echo ($v_ctype != "Onlending Firm") ? '<li><a href="view_msaving_sub.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Product Subscription</a></li>' : ''; ?>
    <?php echo ($v_ctype != "Onlending Firm") ? '<li><a href="msavings_trans.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Product Transaction</a></li>' : ''; ?>
    <?php echo ($v_ctype != "Onlending Firm") ? '<li><a href="make_prdwrq.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Request Withdrawal</a></li>' : ''; ?>
    <?php echo ($v_ctype != "Onlending Firm") ? '<li><a href="settlement.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Settlement History</a></li>' : ''; ?>
    <?php echo ($v_ctype != "Onlending Firm") ? '<li><a href="withdrawal_request.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Withdrawal Request</a></li>' : ''; ?>
    <?php echo ($v_ctype != "Onlending Firm") ? '</ul></li>' : ''; ?>
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_referral_tab = $get_check['access_referral_tab'];
    $confirm_bonus = $get_check['confirm_bonus'];
    $view_bonus_transaction = $get_check['view_bonus_transaction'];
  ?>  
    <?php echo ($v_ctype != "Onlending Firm") ? '<li class="treeview"><a href="#"><i class="fa fa-female"></i> <span>Product Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($v_ctype != "Onlending Firm") ? '<li><a href="notification.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Notification <span class="badge badge-orange right">: '. $myvnum_pendinginvestment .' :</span></a></li>' : ''; ?>
    <?php echo ($v_ctype != "Onlending Firm") ? '<li><a href="create_msavingsplan.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Product Setup</a></li>' : ''; ?>
    <?php echo ($v_ctype != "Onlending Firm") ? '<li><a href="view_msaving_sub.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Product Subscription</a></li>' : ''; ?>
    <?php echo ($v_ctype != "Onlending Firm") ? '<li><a href="msavings_trans.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Product Transaction</a></li>' : ''; ?>
    <?php echo ($v_ctype != "Onlending Firm") ? '<li><a href="make_prdwrq.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Request Withdrawal</a></li>' : ''; ?>
    <?php echo ($v_ctype != "Onlending Firm") ? '<li><a href="settlement.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Settlement History</a></li>' : ''; ?>
    <?php echo ($v_ctype != "Onlending Firm") ? '<li><a href="withdrawal_request.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Withdrawal Request</a></li>' : ''; ?>
    <?php echo ($v_ctype != "Onlending Firm") ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("404")))
{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error".mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_wallet_tab = $get_check['access_wallet_tab'];
?>  
  <?php echo ($access_wallet_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-briefcase"></i> <span>Wallet Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
  <?php echo ($transfer_fund == 1) ? '<li><a href="p_to_p.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Wallet Transfer</a></li>' : ''; ?>
  <?php echo ($transfer_fund == 1) ? '<li><a href="transfer_fund.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Bank Transfer</a></li>' : ''; ?>
  <?php echo ($recharge_airtime_or_data == 1) ? '<li><a href="pay_bills.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Buy Airtime</a></li>' : ''; ?>
  <?php echo ($recharge_airtime_or_data == 1) ? '<li><a href="pay_billsdata.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Buy Data</a></li>' : ''; ?>
  <?php echo ($recharge_airtime_or_data == 1) ? '<li><a href="pay_bill1.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Pay Bills</a></li>' : ''; ?>
  <?php echo ($sms_marketing == 1) ? '<li><a href="sms_marketing?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Send SMS </a></li>' : ''; ?>
  <?php echo ($access_wallet_tab == 1) ? '<li><a href="mywallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Wallet History </a></li>' : ''; ?>
  <?php //echo ($transfer_fund == 1) ? '<li><a href="mywallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_2"><i class="fa fa-circle-o"></i> Transfer History </a></li>' : ''; ?>
  <?php echo ($sms_report == 1) ? '<li><a href="sms_reports?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> SMS Reports </a></li>' : ''; ?>
  <?php echo ($access_wallet_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error".mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_wallet_tab = $get_check['access_wallet_tab'];
  ?>
  <?php echo ($access_wallet_tab == 1) ? '<li><a href="#"><i class="fa fa-briefcase"></i> <span>Wallet Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
  <?php echo ($transfer_fund == 1) ? '<li><a href="p_to_p.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Wallet Transfer</a></li>' : ''; ?>
  <?php echo ($transfer_fund == 1) ? '<li><a href="transfer_fund.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Bank Transfer</a></li>' : ''; ?>
  <?php echo ($recharge_airtime_or_data == 1) ? '<li><a href="pay_bills.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Buy Airtime</a></li>' : ''; ?>
  <?php echo ($recharge_airtime_or_data == 1) ? '<li><a href="pay_billsdata.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Buy Data</a></li>' : ''; ?>
  <?php echo ($recharge_airtime_or_data == 1) ? '<li><a href="pay_bill1.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Pay Bills</a></li>' : ''; ?>
  <?php echo ($sms_marketing == 1) ? '<li><a href="sms_marketing?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Send SMS </a></li>' : ''; ?>
  <?php echo ($access_wallet_tab == 1) ? '<li><a href="mywallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Wallet History </a></li>' : ''; ?>
  <?php //echo ($transfer_fund == 1) ? '<li><a href="mywallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_2"><i class="fa fa-circle-o"></i> Transfer History </a></li>' : ''; ?>
  <?php echo ($sms_report == 1) ? '<li><a href="sms_reports?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> SMS Reports </a></li>' : ''; ?>
  <?php echo ($access_wallet_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("900")))
{
?>
		<?php echo ($v_ctype == "Onlending Firm") ? '<li class="active"><a href="bvnValidation.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("900").'&&tab=tab_1"><i class="fa fa-legal"></i> <span>BVN Manager</span></a></li>' : ''; ?>
<?php
}
else{
	?>
		<?php echo ($v_ctype == "Onlending Firm") ? '<li><a href="bvnValidation.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("900").'&&tab=tab_1"><i class="fa fa-legal"></i> <span>BVN Manager</span></a></li>' : ''; ?>
<?php } ?>


<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("405")))
{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_loan_tab = $get_check['access_loan_tab'];
$view_all_loans = $get_check['view_all_loans'];
$view_due_loans = $get_check['view_due_loans'];
$active_loan = $get_check['active_loan'];
$paid_loan = $get_check['paid_loan'];
$approved_loan = $get_check['approved_loan'];
$disapproved_loan = $get_check['disapproved_loan'];
?>  
    <?php echo ($access_loan_tab == 1 && $v_ctype == "Onlending Firm") ? '<li class="treeview active"><a href="#"><i class="fa fa-balance-scale"></i> <span>Loans Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($vrole == "vendor_manager" && $v_ctype == "Onlending Firm") ? '<li><a href="setuploanprd.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Setup Loan Products</a></li>' : ''; ?>
    <?php echo ($view_all_loans == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="pendingloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Pending Loans</a></li>' : ''; ?>
    <?php echo ($active_loan == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="activeloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Active Loans</a></li>' : ''; ?>
    <?php echo ($paid_loan == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="paidloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Paid Loans</a></li>' : ''; ?>
    <?php echo ($approved_loan == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="apprloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Loans Approved</a></li>' : ''; ?>
    <?php echo ($disapproved_loan == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="disapprloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Loans Disapproved</a></li>' : ''; ?>
    <?php echo ($view_all_loans == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="listloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> View All Loans</a></li>' : ''; ?>
    <?php echo ($view_due_loans == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="dueloans.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Due Loans</a></li>' : ''; ?>
    <?php echo ($access_loan_tab == 1 && $v_ctype == "Onlending Firm") ? '</ul></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_loan_tab = $get_check['access_loan_tab'];
$view_all_loans = $get_check['view_all_loans'];
$view_due_loans = $get_check['view_due_loans'];
$active_loan = $get_check['active_loan'];
$paid_loan = $get_check['paid_loan'];
$approved_loan = $get_check['approved_loan'];
$disapproved_loan = $get_check['disapproved_loan'];
?>  
    <?php echo ($access_loan_tab == 1 && $v_ctype == "Onlending Firm") ? '<li class="treeview"><a href="#"><i class="fa fa-balance-scale"></i> <span>Loans Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($vrole == "vendor_manager" && $v_ctype == "Onlending Firm") ? '<li><a href="setuploanprd.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Setup Loan Products</a></li>' : ''; ?>
    <?php echo ($view_all_loans == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="pendingloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Pending Loans</a></li>' : ''; ?>
    <?php echo ($active_loan == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="activeloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Active Loans</a></li>' : ''; ?>
    <?php echo ($paid_loan == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="paidloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Paid Loans</a></li>' : ''; ?>
    <?php echo ($approved_loan == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="apprloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Loans Approved</a></li>' : ''; ?>
    <?php echo ($disapproved_loan == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="disapprloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Loans Disapproved</a></li>' : ''; ?>
    <?php echo ($view_all_loans == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="listloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> View All Loans</a></li>' : ''; ?>
    <?php echo ($view_due_loans == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="dueloans.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Due Loans</a></li>' : ''; ?>
    <?php echo ($access_loan_tab == 1 && $v_ctype == "Onlending Firm") ? '</ul></li>' : ''; ?>
<?php } ?>


<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("407")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_missedpayment_tab = $get_check['access_missedpayment_tab'];
?>    
    <?php echo ($access_missedpayment_tab == 1 && $v_ctype == "Onlending Firm") ? '<li class="active"><a href="missedpayment.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("407").'"><i class="fa fa-dollar"></i> <span>Request Due Payment</span></a></li>' : ''; ?> 
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_missedpayment_tab = $get_check['access_missedpayment_tab'];
?>    
    <?php echo ($access_missedpayment_tab == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="missedpayment.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("407").'"><i class="fa fa-dollar"></i> <span>Request Due Payment</span></a></li>' : ''; ?> 
<?php } ?>
  

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("408")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_loanrepayment_tab = $get_check['access_loanrepayment_tab'];
$remit_cash_payment = $get_check['remit_cash_payment'];
$list_all_repayment = $get_check['list_all_repayment'];
?>  
    <?php echo ($access_loanrepayment_tab == 1 && $v_ctype == "Onlending Firm") ? '<li class="treeview active"><a href="#"><i class="fa fa-dollar"></i> <span>Loan Repayments</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php //echo ($remit_cash_payment == 1) ? '<li><a href="newpayments.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i>Add Payment</a></li>' : ''; ?>
    <?php //echo ($remit_cash_payment == 1) ? '<li><a href="bulkrepmts.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i>Add Bulk Payment</a></li>' : ''; ?>
    <?php echo ($list_all_repayment == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="listpayment.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i>List Repayments</a></li>' : ''; ?>
    <?php echo ($access_loanrepayment_tab == 1 && $v_ctype == "Onlending Firm") ? '</ul></li>' : ''; ?> 
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_loanrepayment_tab = $get_check['access_loanrepayment_tab'];
$remit_cash_payment = $get_check['remit_cash_payment'];
$list_all_repayment = $get_check['list_all_repayment'];
?>  
    <?php echo ($access_loanrepayment_tab == 1 && $v_ctype == "Onlending Firm") ? '<li class="treeview"><a href="#"><i class="fa fa-dollar"></i> <span>Loan Repayments</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php //echo ($remit_cash_payment == 1) ? '<li><a href="newpayments.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i>Add Payment</a></li>' : ''; ?>
    <?php //echo ($remit_cash_payment == 1) ? '<li><a href="bulkrepmts.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i>Add Bulk Payment</a></li>' : ''; ?>
    <?php echo ($list_all_repayment == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="listpayment.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i>List Repayments</a></li>' : ''; ?>
    <?php echo ($access_loanrepayment_tab == 1 && $v_ctype == "Onlending Firm") ? '</ul></li>' : ''; ?>
<?php } ?>


<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("425")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_report_tab = $get_check['access_report_tab'];
    $borrowers_reports = $get_check['borrowers_reports'];
    $collection_reports = $get_check['collection_reports'];
    $loan_reports = $get_check['loan_reports'];
    $subscription_reports = $get_check['subscription_reports'];
  ?>
  <?php echo ($access_report_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-area-chart"></i> <span>Reports</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
  <?php //echo ($borrowers_reports == 1) ? '<li><a href="borrower_reports.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Borrower Report</a></li>' : ''; ?>
  <?php echo ($collection_reports == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="collection_reports.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Loan Collection Report</a></li>' : ''; ?>
  <?php //echo ($loan_reports == 1) ? '<li><a href="loan_reports.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Loan Report </a></li>' : ''; ?>
  <?php echo ($subscription_reports == 1 && mysqli_num_rows($vsearch_maintenance_model) == 0) ? '<li><a href="subscription_reports.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Subscription Report</a></li>' : ''; ?>
  <?php echo ($access_report_tab == 1 && $v_ctype != "Onlending Firm") ? '<li><a href="rsavings_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Product Report</a></li>' : ''; ?>
  <?php echo ($access_report_tab == 1) ? '<li><a href="financial_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Financial Report</a></li>' : ''; ?>
  <?php echo ($access_report_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_report_tab = $get_check['access_report_tab'];
    $borrowers_reports = $get_check['borrowers_reports'];
    $collection_reports = $get_check['collection_reports'];
    $loan_reports = $get_check['loan_reports'];
    $subscription_reports = $get_check['subscription_reports'];
  ?>
  <?php echo ($access_report_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-area-chart"></i> <span>Reports</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
  <?php //echo ($borrowers_reports == 1) ? '<li><a href="borrower_reports.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Borrower Report</a></li>' : ''; ?>
  <?php echo ($collection_reports == 1 && $v_ctype == "Onlending Firm") ? '<li><a href="collection_reports.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Loan Collection Report</a></li>' : ''; ?>
  <?php //echo ($loan_reports == 1) ? '<li><a href="loan_reports.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Loan Report </a></li>' : ''; ?>
  <?php echo ($subscription_reports == 1 && mysqli_num_rows($vsearch_maintenance_model) == 0) ? '<li><a href="subscription_reports.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Subscription Report</a></li>' : ''; ?>
  <?php echo ($access_report_tab == 1 && $v_ctype != "Onlending Firm") ? '<li><a href="rsavings_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Product Report</a></li>' : ''; ?>
  <?php echo ($access_report_tab == 1) ? '<li><a href="financial_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Financial Report</a></li>' : ''; ?>
  <?php echo ($access_report_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>


<?php
/*if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("415")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_notice_tab = $get_check['access_notice_tab'];*/
?>  
  <?php //echo ($access_notice_tab == 1) ? '<li class="active"><a href="newsboard.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("415").'"><i class="fa fa-briefcase"></i> <span>Manage Notice Board</span></a></li>' : ''; ?>
<?php
/*}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_notice_tab = $get_check['access_notice_tab'];*/
  ?>
  <?php //echo ($access_notice_tab == 1) ? '<li><a href="newsboard.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("415").'"><i class="fa fa-briefcase"></i> <span>Manage Notice Board</span></a></li>' : ''; ?>
<?php //} ?>


<?php
/*if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("406")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_helpdesk_tab = $get_check['access_helpdesk_tab'];
    $view_all_tickets = $get_check['view_all_tickets'];
    $close_tickets  = $get_check['close_tickets'];*/
?>    
    <?php //echo ($access_helpdesk_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-envelope-o"></i> <span>Helpdesk Center</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php //echo ($view_all_tickets == 1) ? '<li><a href="inboxmessage.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("406").'"><i class="fa fa-circle-o"></i>All Tickes</a></li>' : ''; ?>
    <?php //echo ($close_tickets == 1) ? '<li><a href="outboxmessage.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("406").'"><i class="fa fa-circle-o"></i>Closed Tickets</a></li>' : ''; ?>
    <?php //echo ($access_helpdesk_tab == 1) ? '</ul></li>' : ''; ?>
<?php
/*}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_helpdesk_tab = $get_check['access_helpdesk_tab'];
    $view_all_tickets = $get_check['view_all_tickets'];
    $close_tickets  = $get_check['close_tickets'];*/
?>    
    <?php //echo ($access_helpdesk_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-envelope-o"></i> <span>Helpdesk Center</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php //echo ($view_all_tickets == 1) ? '<li><a href="inboxmessage.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("406").'"><i class="fa fa-circle-o"></i>All Tickes</a></li>' : ''; ?>
    <?php //echo ($close_tickets == 1) ? '<li><a href="outboxmessage.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("406").'"><i class="fa fa-circle-o"></i>Closed Tickets</a></li>' : ''; ?>
    <?php //echo ($access_helpdesk_tab == 1) ? '</ul></li>' : ''; ?>
<?php //} ?>


<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("500")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$income_tab = $get_check['income_tab'];
$add_income = $get_check['add_income'];
$view_income = $get_check['view_income'];
?>
    <?php echo ($income_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-calculator"></i> <span>Income</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_income == 1) ? '<li><a href="newincome.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("500").'"><i class="fa fa-circle-o"></i> Add Income</a></li>' : ''; ?>
    <?php echo ($view_income == 1) ? '<li><a href="listincome.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("500").'"><i class="fa fa-circle-o"></i> View Income</a></li>' : ''; ?>
    <?php echo ($income_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
   $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$income_tab = $get_check['income_tab'];
$add_income = $get_check['add_income'];
$view_income = $get_check['view_income'];
?>
    <?php echo ($income_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-calculator"></i> <span>Income</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_income == 1) ? '<li><a href="newincome.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("500").'"><i class="fa fa-circle-o"></i> Add Income</a></li>' : ''; ?>
    <?php echo ($view_income == 1) ? '<li><a href="listincome.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("500").'"><i class="fa fa-circle-o"></i> View Income</a></li>' : ''; ?>
    <?php echo ($income_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>


<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("422")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_expense_tab = $get_check['access_expense_tab'];
$add_expenses = $get_check['add_expenses'];
$view_expenses = $get_check['view_expenses'];
?>
    <?php echo ($access_expense_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-newspaper-o"></i> <span>Expenses</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_expenses == 1) ? '<li><a href="newexpenses.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("422").'"><i class="fa fa-circle-o"></i> Add Expenses</a></li>' : ''; ?>
    <?php echo ($view_expenses == 1) ? '<li><a href="listexpenses.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("422").'"><i class="fa fa-circle-o"></i> View Expenses</a></li>' : ''; ?>
    <?php echo ($access_expense_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
   $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$vrole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_expense_tab = $get_check['access_expense_tab'];
$add_expenses = $get_check['add_expenses'];
$view_expenses = $get_check['view_expenses'];
?>
    <?php echo ($access_expense_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-newspaper-o"></i> <span>Expenses</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_expenses == 1) ? '<li><a href="newexpenses.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("422").'"><i class="fa fa-circle-o"></i> Add Expenses</a></li>' : ''; ?>
    <?php echo ($view_expenses == 1) ? '<li><a href="listexpenses.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("422").'"><i class="fa fa-circle-o"></i> View Expenses</a></li>' : ''; ?>
    <?php echo ($access_expense_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>


<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("420")))
{
  $user2 = $_GET['tid'];
?>
    <?php echo ($vrole == "vendor_manager" && mysqli_num_rows($vsearch_maintenance_model) == 0) ? '<li class="treeview active"><a href="#"><i class="fa fa-globe"></i> <span>Manage Subscription</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($vrole == "vendor_manager" && mysqli_num_rows($vsearch_maintenance_model) == 0) ? '<li><a href="make_saas_sub.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> Make Subscription</a></li>' : ''; ?>
    <?php //echo ($vrole == "vendor_manager" && mysqli_num_rows($vsearch_maintenance_model) == 0) ? '<li><a href="upgrade_saas_sub.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> Upgrade Plan</a></li>' : ''; ?>
    <?php echo ($vrole == "vendor_manager" && mysqli_num_rows($vsearch_maintenance_model) == 0) ? '<li><a href="saassub_history.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> My Subscription History</a></li>' : ''; ?>
    <?php echo ($vrole == "vendor_manager" && mysqli_num_rows($vsearch_maintenance_model) == 0) ? '</ul></li>' : ''; ?>
<?php
}
else{
  ?>  
    <?php echo ($vrole == "vendor_manager" && mysqli_num_rows($vsearch_maintenance_model) == 0) ? '<li class="treeview"><a href="#"><i class="fa fa-globe"></i> <span>Manage Subscription</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($vrole == "vendor_manager" && mysqli_num_rows($vsearch_maintenance_model) == 0) ? '<li><a href="make_saas_sub.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> Make Subscription</a></li>' : ''; ?>
    <?php //echo ($vrole == "vendor_manager" && mysqli_num_rows($vsearch_maintenance_model) == 0) ? '<li><a href="upgrade_saas_sub.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> Upgrade Plan</a></li>' : ''; ?>
    <?php echo ($vrole == "vendor_manager" && mysqli_num_rows($vsearch_maintenance_model) == 0) ? '<li><a href="saassub_history.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> My Subscription History</a></li>' : ''; ?>
    <?php echo ($vrole == "vendor_manager" && mysqli_num_rows($vsearch_maintenance_model) == 0) ? '</ul></li>' : ''; ?>
<?php } ?>
    
    <li>
          <a href="../logout.php?id=<?php echo $vcreated_by; ?>">
            <i class="fa fa-sign-out"></i> <span>Logout</span>
          </a>
        </li>
    
    </section>
    <!-- /.sidebar -->
  </aside>