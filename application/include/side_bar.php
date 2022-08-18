<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
    <?php 
      $id = $_SESSION['tid'];
      $call = mysqli_query($link, "SELECT * FROM user WHERE id = '$id'");
      $row = mysqli_fetch_assoc($call);
      ?>
          <img src="<?php echo $fetchsys_config['file_baseurl'].$row['image']; ?>" class="img-circle" alt="User Image" width="64" height="64">
        </div>
        <div class="pull-left info">
          <p><?php echo $row['username']; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->

      <ul class="sidebar-menu">

            <div align="center">
                <div id="google_translate_element"></div>

                <script type="text/javascript">
                function googleTranslateElementInit() {
                new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                }
                </script>
                
                <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
                
                </script>
            </div>

        <li class="header">MAIN NAVIGATION</li>
<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("401")))
{
?>
    <li class="active"><a href="dashboard?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
<?php
}
else{
  ?>
    <li><a href="dashboard?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
<?php } ?>  


<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("402")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_backend_branch_tab = $get_check['access_backend_branch_tab'];
  $add_backend_branches = $get_check['add_backend_branches'];
  $list_backend_branches  = $get_check['list_backend_branches'];
?>
    <?php echo ($access_backend_branch_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-object-ungroup"></i> <span>Branch Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_backend_branches == 1) ? '<li><a href="newbranches?id='.$_SESSION['tid'].'&&mid='.base64_encode("402").'"><i class="fa fa-circle-o"></i> Add Branches</a></li>' : ''; ?>
    <?php echo ($list_backend_branches == 1) ? '<li><a href="listbranches?id='.$_SESSION['tid'].'&&mid='.base64_encode("402").'"><i class="fa fa-circle-o"></i> List Branches</a></li>' : ''; ?>
    <?php echo ($access_backend_branch_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_backend_branch_tab = $get_check['access_backend_branch_tab'];
  $add_backend_branches = $get_check['add_backend_branches'];
  $list_backend_branches  = $get_check['list_backend_branches'];
  ?>  
    <?php echo ($access_backend_branch_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-object-ungroup"></i> <span>Branch Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_backend_branches == 1) ? '<li><a href="newbranches?id='.$_SESSION['tid'].'&&mid='.base64_encode("402").'"><i class="fa fa-circle-o"></i> Add Branches</a></li>' : ''; ?>
    <?php echo ($list_backend_branches == 1) ? '<li><a href="listbranches?id='.$_SESSION['tid'].'&&mid='.base64_encode("402").'"><i class="fa fa-circle-o"></i> List Branches</a></li>' : ''; ?>
    <?php echo ($access_backend_branch_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>


<?php
/**
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("418")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_cooperative_tab = $get_check['access_cooperative_tab'];
  $add_cooperatives = $get_check['add_cooperatives'];
  $add_coop_members  = $get_check['add_coop_members'];
  $list_cooperatives  = $get_check['list_cooperatives'];
?>
    <?php echo ($access_cooperative_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-object-group"></i> <span>Cooperative Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_cooperatives == 1) ? '<li><a href="add_cooperative?id='.$_SESSION['tid'].'&&mid='.base64_encode("418").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Add Cooperative</a></li>' : ''; ?>
    <?php echo ($add_coop_members == 1) ? '<li><a href="add_coop_member?id='.$_SESSION['tid'].'&&mid='.base64_encode("418").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Add Coop. Member</a></li>' : ''; ?>
    <?php echo ($list_cooperatives == 1) ? '<li><a href="listcooperative?id='.$_SESSION['tid'].'&&mid='.base64_encode("418").'"><i class="fa fa-circle-o"></i> List Cooperative</a></li>' : ''; ?>
    <?php echo ($access_cooperative_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_cooperative_tab = $get_check['access_cooperative_tab'];
  $add_cooperatives = $get_check['add_cooperatives'];
  $add_coop_members  = $get_check['add_coop_members'];
  $list_cooperatives  = $get_check['list_cooperatives'];
  ?>  
    <?php echo ($access_cooperative_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-object-group"></i> <span>Cooperative Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_cooperatives == 1) ? '<li><a href="add_cooperative?id='.$_SESSION['tid'].'&&mid='.base64_encode("418").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Add Cooperative</a></li>' : ''; ?>
    <?php echo ($add_coop_members == 1) ? '<li><a href="add_coop_member?id='.$_SESSION['tid'].'&&mid='.base64_encode("418").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Add Coop. Member</a></li>' : ''; ?>
    <?php echo ($list_cooperatives == 1) ? '<li><a href="listcooperative?id='.$_SESSION['tid'].'&&mid='.base64_encode("418").'"><i class="fa fa-circle-o"></i> List Cooperative</a></li>' : ''; ?>
    <?php echo ($access_cooperative_tab == 1) ? '</ul></li>' : ''; ?>
<?php } */?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("409")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_backend_employee_tab = $get_check['access_backend_employee_tab'];
    $add_backend_employee = $get_check['add_backend_employee'];
    $list_backend_employee  = $get_check['list_backend_employee'];
?>    
    <?php echo ($access_backend_employee_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-user"></i> <span>Employee Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_backend_employee == 1) ? '<li><a href="newemployee?id='.$_SESSION['tid'].'&&mid='.base64_encode("409").'"><i class="fa fa-circle-o"></i>Creat Employee</a></li>' : ''; ?>
    <?php echo ($list_backend_employee == 1) ? '<li><a href="listemployee?id='.$_SESSION['tid'].'&&mid='.base64_encode("409").'"><i class="fa fa-circle-o"></i>List Employee</a></li>' : ''; ?>
    <?php echo ($access_backend_employee_tab == 1) ? '</ul></li>' : ''; ?> 
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_backend_employee_tab = $get_check['access_backend_employee_tab'];
    $add_backend_employee = $get_check['add_backend_employee'];
    $list_backend_employee  = $get_check['list_backend_employee'];
?>    
    <?php echo ($access_backend_employee_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-user"></i> <span>Employee Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_backend_employee == 1) ? '<li><a href="newemployee?id='.$_SESSION['tid'].'&&mid='.base64_encode("409").'"><i class="fa fa-circle-o"></i>Creat Employee</a></li>' : ''; ?>
    <?php echo ($list_backend_employee == 1) ? '<li><a href="listemployee?id='.$_SESSION['tid'].'&&mid='.base64_encode("409").'"><i class="fa fa-circle-o"></i>List Employee</a></li>' : ''; ?>
    <?php echo ($access_backend_employee_tab == 1) ? '</ul></li>' : ''; ?>  
<?php } ?>




<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("419")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_client_tab = $get_check['access_client_tab'];
  $add_client = $get_check['add_client'];
  $add_aggregator = $get_check['add_aggregator'];
  $list_client  = $get_check['list_client'];
  $view_client_branch  = $get_check['view_client_branch'];
  $list_teller = $get_check['list_teller'];
  $list_client_subagent = $get_check['list_client_subagent'];
  $list_aggregators = $get_check['list_aggregators'];
  $link_vervecard = $get_check['link_vervecard'];
  $link_debitcard = $get_check['link_debitcard'];
  $issue_or_load_card = $get_check['issue_or_load_card'];
  $list_cardholder = $get_check['list_cardholder'];
  $view_all_transaction = $get_check['view_all_transaction'];
  $view_all_charges = $get_check['view_all_charges'];
  $view_all_roles = $get_check['view_all_roles'];
  $view_all_customers = $get_check['view_all_customers'];
  $create_client_pool_account = $get_check['create_client_pool_account'];
  $view_all_pool_account = $get_check['view_all_pool_account'];
  $view_pool_account_history = $get_check['view_pool_account_history'];
  $client_verification_history = $get_check['client_verification_history'];
  $view_client_enrollees_list = $get_check['view_client_enrollees_list'];
?>
    <?php echo ($access_client_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-institution"></i> <span>Client Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_client == 1) ? '<li><a href="add_institution?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Add Client</a></li>' : ''; ?>
    <?php echo ($add_aggregator == 1) ? '<li><a href="add_aggregator?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Add Aggregator</a></li>' : ''; ?>
    <?php echo ($list_client == 1) ? '<li><a href="listinstitution?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> List Client</a></li>' : ''; ?>
    <?php echo ($view_client_branch == 1) ? '<li><a href="clientbranches?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> All Client Branches</a></li>' : ''; ?>
    <?php echo ($view_all_customers == 1) ? '<li><a href="customer?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> All Client Customer</a></li>' : ''; ?>
    <?php echo ($list_teller == 1) ? '<li><a href="view_teller?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> View All Teller</a></li>' : ''; ?>
    <?php echo ($list_client_subagent == 1) ? '<li><a href="clientSubAgent?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> All Client Sub-Agent</a></li>' : ''; ?>
    <?php echo ($list_aggregators == 1) ? '<li><a href="listaggregators?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> List Aggregators</a></li>' : ''; ?>
    <?php echo ($link_vervecard == '1') ? '<li><a href="link_verveCard?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> Link Vervecard(Prepaid)</a></li>' : ''; ?>
    <?php echo ($link_vervecard == '1') ? '<li><a href="link_verveCard2?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> Link Vervecard(Debit)</a></li>' : ''; ?>
    <?php echo ($issue_or_load_card == 1) ? '<li><a href="create_card?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Issue / Load Card</a></li>' : ''; ?>
    <?php echo ($list_cardholder == 1) ? '<li><a href="list_card?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'&&tab=tab_1"><i class="fa fa-circle-o"></i> All Cardholders</a></li>' : ''; ?>
    <?php echo ($view_all_transaction == 1) ? '<li><a href="transaction?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> Client Transaction</a></li>' : ''; ?>
    <?php echo ($view_all_charges == 1) ? '<li><a href="view_charges?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> View All Charges</a></li>' : ''; ?>
    <?php echo ($view_all_roles == 1) ? '<li><a href="permission_list?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> View All Role</a></li>' : ''; ?>
    <?php echo ($create_client_pool_account == 1) ? '<li><a href="create_poolAcct?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Create Pool Account</a></li>' : ''; ?>
    <?php echo ($view_all_pool_account == 1) ? '<li><a href="all_poolAcct?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'&&tab=tab_1"><i class="fa fa-circle-o"></i> All Pool Account</a></li>' : ''; ?>
    <?php echo ($view_pool_account_history == 1) ? '<li><a href="poolAcct_history?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Pool Account History</a></li>' : ''; ?>
    <?php echo ($client_verification_history == 1) ? '<li><a href="verificationHistory?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> Verification History</a></li>' : ''; ?>
    <?php echo ($view_client_enrollees_list == 1) ? '<li><a href="enrolleeList?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> All Client Enrollees</a></li>' : ''; ?>
    <?php echo ($access_client_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_client_tab = $get_check['access_client_tab'];
  $add_client = $get_check['add_client'];
  $add_aggregator = $get_check['add_aggregator'];
  $list_client  = $get_check['list_client'];
  $view_client_branch  = $get_check['view_client_branch'];
  $list_teller = $get_check['list_teller'];
  $list_client_subagent = $get_check['list_client_subagent'];
  $list_aggregators = $get_check['list_aggregators'];
  $link_vervecard = $get_check['link_vervecard'];
  $issue_or_load_card = $get_check['issue_or_load_card'];
  $list_cardholder = $get_check['list_cardholder'];
  $view_all_transaction = $get_check['view_all_transaction'];
  $view_all_charges = $get_check['view_all_charges'];
  $view_all_roles = $get_check['view_all_roles'];
  $view_all_customers = $get_check['view_all_customers'];
  $create_client_pool_account = $get_check['create_client_pool_account'];
  $view_all_pool_account = $get_check['view_all_pool_account'];
  $view_pool_account_history = $get_check['view_pool_account_history'];
  $client_verification_history = $get_check['client_verification_history'];
  $view_client_enrollees_list = $get_check['view_client_enrollees_list'];
  ?>  
    <?php echo ($access_client_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-institution"></i> <span>Client Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_client == 1) ? '<li><a href="add_institution?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Add Client</a></li>' : ''; ?>
    <?php echo ($add_aggregator == 1) ? '<li><a href="add_aggregator?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Add Aggregator</a></li>' : ''; ?>
    <?php echo ($list_client == 1) ? '<li><a href="listinstitution?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> List Client</a></li>' : ''; ?>
    <?php echo ($view_client_branch == 1) ? '<li><a href="clientbranches?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> All Client Branches</a></li>' : ''; ?>
    <?php echo ($view_all_customers == 1) ? '<li><a href="customer?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> All Client Customer</a></li>' : ''; ?>
    <?php echo ($list_teller == 1) ? '<li><a href="view_teller?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> View All Teller</a></li>' : ''; ?>
    <?php echo ($list_client_subagent == 1) ? '<li><a href="clientSubAgent?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> All Client Sub-Agent</a></li>' : ''; ?>
    <?php echo ($list_aggregators == 1) ? '<li><a href="listaggregators?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> List Aggregators</a></li>' : ''; ?>
    <?php echo ($link_vervecard == '1') ? '<li><a href="link_verveCard?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> Link Vervecard(Prepaid)</a></li>' : ''; ?>
    <?php echo ($link_vervecard == '1') ? '<li><a href="link_verveCard2?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> Link Vervecard(Debit)</a></li>' : ''; ?>
    <?php echo ($issue_or_load_card == 1) ? '<li><a href="create_card?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Issue / Load Card</a></li>' : ''; ?>
    <?php echo ($list_cardholder == 1) ? '<li><a href="list_card?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'&&tab=tab_1"><i class="fa fa-circle-o"></i> All Cardholders</a></li>' : ''; ?>
    <?php echo ($view_all_transaction == 1) ? '<li><a href="transaction?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> Client Transaction</a></li>' : ''; ?>
    <?php echo ($view_all_charges == 1) ? '<li><a href="view_charges?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> View All Charges</a></li>' : ''; ?>
    <?php echo ($view_all_roles == 1) ? '<li><a href="permission_list?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> View All Role</a></li>' : ''; ?>
    <?php echo ($create_client_pool_account == 1) ? '<li><a href="create_poolAcct?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Create Pool Account</a></li>' : ''; ?>
    <?php echo ($view_all_pool_account == 1) ? '<li><a href="all_poolAcct?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'&&tab=tab_1"><i class="fa fa-circle-o"></i> All Pool Account</a></li>' : ''; ?>
    <?php echo ($view_pool_account_history == 1) ? '<li><a href="poolAcct_history?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Pool Account History</a></li>' : ''; ?>
    <?php echo ($client_verification_history == 1) ? '<li><a href="verificationHistory?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> Verification History</a></li>' : ''; ?>
    <?php echo ($view_client_enrollees_list == 1) ? '<li><a href="enrolleeList?id='.$_SESSION['tid'].'&&mid='.base64_encode("419").'"><i class="fa fa-circle-o"></i> All Client Enrollees</a></li>' : ''; ?>
    <?php echo ($access_client_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("404")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $backend_access_wallet_tab = $get_check['backend_access_wallet_tab'];
    $backend_sms_marketing = $get_check['backend_sms_marketing'];
    $backend_sms_report = $get_check['backend_sms_report'];
    $backend_transfer_fund == $get_check['backend_transfer_fund'];
    $backend_recharge_airtime_or_data = $get_check['backend_recharge_airtime_or_data'];
    //WALLET CREATION
    $backend_list_wallet = $get_check['backend_list_wallet'];
    $backend_individual_wallet = $get_check['backend_individual_wallet'];
    $backend_branch_wallet = $get_check['backend_branch_wallet'];
?>	
    <?php echo ($backend_access_wallet_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-briefcase"></i> <span>Wallet Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($backend_list_wallet == 1 || $backend_individual_wallet == 1 || $backend_branch_wallet == 1) ? '<li><a href="listWallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Manage Wallet</a></li>' : ''; ?>
        <?php echo ($backend_transfer_fund == 1) ? '<li><a href="fund_wallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Fund Transfer Wallet</a></li>' : ''; ?>
        <?php echo ($backend_transfer_fund == 1) ? '<li><a href="fund_wallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_3"><i class="fa fa-circle-o"></i> Fund Coperate Wallet</a></li>' : ''; ?>
        <?php echo ($backend_transfer_fund == 1) ? '<li><a href="fund_wallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_6"><i class="fa fa-circle-o"></i> Fund Vendor Wallet</a></li>' : ''; ?>
        <?php echo ($backend_transfer_fund == 1) ? '<li><a href="transfer_fund.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Bank Transfer (Single)</a></li>' : ''; ?>
        <?php echo ($backend_transfer_fund == 1) ? '<li><a href="transfer_fund.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_7"><i class="fa fa-circle-o"></i> Bank Transfer (Bulk)</a></li>' : ''; ?>
        <?php echo ($backend_recharge_airtime_or_data == 1) ? '<li><a href="pay_bills.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Buy Airtime/Data</a></li>' : ''; ?>
        <?php echo ($backend_recharge_airtime_or_data == 1) ? '<li><a href="pay_bill1.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Pay Bills</a></li>' : ''; ?>
        <?php echo ($backend_sms_marketing == 1) ? '<li><a href="sms_marketing?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Send SMS </a></li>' : ''; ?>
        <?php echo ($backend_access_wallet_tab == 1) ? '<li><a href="mywallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Wallet History </a></li>' : ''; ?>
        <?php echo ($backend_access_wallet_tab == 1) ? '<li><a href="mywallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_4"><i class="fa fa-circle-o"></i> USSD Session History </a></li>' : ''; ?>
        <?php echo ($backend_sms_report == 1) ? '<li><a href="sms_reports?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> SMS Reports </a></li>' : ''; ?>
        <?php echo ($backend_access_wallet_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $backend_access_wallet_tab = $get_check['backend_access_wallet_tab'];
    $backend_sms_marketing = $get_check['backend_sms_marketing'];
    $backend_sms_report = $get_check['backend_sms_report'];
    $backend_transfer_fund == $get_check['backend_transfer_fund'];
    $backend_recharge_airtime_or_data = $get_check['backend_recharge_airtime_or_data'];
    //WALLET CREATION
    $backend_list_wallet = $get_check['backend_list_wallet'];
    $backend_individual_wallet = $get_check['backend_individual_wallet'];
    $backend_branch_wallet = $get_check['backend_branch_wallet'];
?>	
		<?php echo ($backend_access_wallet_tab == 1) ? '<li><a href="#"><i class="fa fa-briefcase"></i> <span>Wallet Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($backend_list_wallet == 1 || $backend_individual_wallet == 1 || $backend_branch_wallet == 1) ? '<li><a href="listWallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Manage Wallet</a></li>' : ''; ?>
        <?php echo ($backend_transfer_fund == 1) ? '<li><a href="fund_wallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Fund Transfer Wallet</a></li>' : ''; ?>
        <?php echo ($backend_transfer_fund == 1) ? '<li><a href="fund_wallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_3"><i class="fa fa-circle-o"></i> Fund Coperate Wallet</a></li>' : ''; ?>
        <?php echo ($backend_transfer_fund == 1) ? '<li><a href="fund_wallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_6"><i class="fa fa-circle-o"></i> Fund Vendor Wallet</a></li>' : ''; ?>
        <?php echo ($backend_transfer_fund == 1) ? '<li><a href="transfer_fund.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Bank Transfer (Single)</a></li>' : ''; ?>
        <?php echo ($backend_transfer_fund == 1) ? '<li><a href="transfer_fund.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_7"><i class="fa fa-circle-o"></i> Bank Transfer (Bulk)</a></li>' : ''; ?>
        <?php echo ($backend_recharge_airtime_or_data == 1) ? '<li><a href="pay_bills.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Buy Airtime/Data</a></li>' : ''; ?>
        <?php echo ($backend_recharge_airtime_or_data == 1) ? '<li><a href="pay_bill1.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Pay Bills</a></li>' : ''; ?>
        <?php echo ($backend_sms_marketing == 1) ? '<li><a href="sms_marketing?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Send SMS </a></li>' : ''; ?>
        <?php echo ($backend_access_wallet_tab == 1) ? '<li><a href="mywallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Wallet History </a></li>' : ''; ?>
        <?php echo ($backend_access_wallet_tab == 1) ? '<li><a href="mywallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_4"><i class="fa fa-circle-o"></i> USSD Session History </a></li>' : ''; ?>
        <?php echo ($backend_sms_report == 1) ? '<li><a href="sms_reports?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> SMS Reports </a></li>' : ''; ?>
        <?php echo ($backend_access_wallet_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("490")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_investment_tab = $get_check['access_investment_tab'];
    $view_investment_subscription = $get_check['view_investment_subscription'];
    $view_investment_transaction = $get_check['view_investment_transaction'];
    $investment_withdrawal_request = $get_check['investment_withdrawal_request'];
    $investment_settlement_to_vendor = $get_check['investment_settlement_to_vendor'];
?>
    <?php echo ($access_investment_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-calculator"></i> <span>Investment Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($view_investment_transaction == 1) ? '<li><a href="notification.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Notification <span class="badge badge-orange right">: '. $myinum_pendinginvestment .' :</span></a></li>' : ''; ?>
    <?php echo ($view_investment_subscription == 1) ? '<li><a href="view_msaving_sub.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Investment Subscription</a></li>' : ''; ?>
    <?php echo ($view_investment_transaction == 1) ? '<li><a href="msavings_trans.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Investment Transaction</a></li>' : ''; ?>
    <?php echo ($disapprove_withdrawal_request == 1) ? '<li><a href="withdrawal_request.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Withdrawal Request</a></li>' : ''; ?>
    <?php echo ($investment_settlement_to_vendor == 1) ? '<li><a href="settlement.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Settlement History</a></li>' : ''; ?>
    <?php echo ($access_investment_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_investment_tab = $get_check['access_investment_tab'];
    $view_investment_subscription = $get_check['view_investment_subscription'];
    $view_investment_transaction = $get_check['view_investment_transaction'];
    $disapprove_withdrawal_request = $get_check['disapprove_withdrawal_request'];
    $investment_settlement_to_vendor = $get_check['investment_settlement_to_vendor'];
  ?>  
    <?php echo ($access_investment_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-calculator"></i> <span>Investment Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($view_investment_transaction == 1) ? '<li><a href="notification.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Notification <span class="badge badge-orange right">: '. $myinum_pendinginvestment .' :</span></a></li>' : ''; ?>
    <?php echo ($view_investment_subscription == 1) ? '<li><a href="view_msaving_sub.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Investment Subscription</a></li>' : ''; ?>
    <?php echo ($view_investment_transaction == 1) ? '<li><a href="msavings_trans.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Investment Transaction</a></li>' : ''; ?>
    <?php echo ($disapprove_withdrawal_request == 1) ? '<li><a href="withdrawal_request.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Withdrawal Request</a></li>' : ''; ?>
    <?php echo ($investment_settlement_to_vendor == 1) ? '<li><a href="settlement.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Settlement History</a></li>' : ''; ?>
    <?php echo ($access_investment_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("922")))
{
$check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$backend_account_manager_tab = $get_check['backend_account_manager_tab'];
$backend_manage_bank_account = $get_check['backend_manage_bank_account'];
$backend_add_bank_gateway = $get_check['backend_add_bank_gateway'];
$backend_list_bank_gateway = $get_check['backend_list_bank_gateway'];
?>    
    <?php echo ($backend_account_manager_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-credit-card"></i> <span>Account Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($backend_add_bank_gateway == 1) ? '<li><a href="addBankGateway?id='.$_SESSION['tid'].'&&mid='.base64_encode("922").'"><i class="fa fa-circle-o"></i> Add Bank Account</a></li>' : ''; ?>
    <?php echo ($backend_list_bank_gateway == 1) ? '<li><a href="listBank?id='.$_SESSION['tid'].'&&mid='.base64_encode("922").'"><i class="fa fa-circle-o"></i> List Bank</a></li>' : ''; ?>
    <?php echo ($backend_manage_bank_account == 1) ? '<li><a href="#manageBank?id='.$_SESSION['tid'].'&&mid='.base64_encode("922").'"><i class="fa fa-circle-o"></i> Manage Account</a></li>' : ''; ?>
    <?php echo ($backend_account_manager_tab == 1) ? '</ul></li>' : ''; ?> 
<?php
}
else{
$check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$backend_account_manager_tab = $get_check['backend_account_manager_tab'];
$backend_manage_bank_account = $get_check['backend_manage_bank_account'];
$backend_add_bank_gateway = $get_check['backend_add_bank_gateway'];
$backend_list_bank_gateway = $get_check['backend_list_bank_gateway'];
?>    
    <?php echo ($backend_account_manager_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-credit-card"></i> <span>Account Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($backend_add_bank_gateway == 1) ? '<li><a href="addBankGateway?id='.$_SESSION['tid'].'&&mid='.base64_encode("922").'"><i class="fa fa-circle-o"></i> Add Bank Account</a></li>' : ''; ?>
    <?php echo ($backend_list_bank_gateway == 1) ? '<li><a href="listBank?id='.$_SESSION['tid'].'&&mid='.base64_encode("922").'"><i class="fa fa-circle-o"></i> List Bank</a></li>' : ''; ?>
    <?php echo ($backend_manage_bank_account == 1) ? '<li><a href="#manageBank?id='.$_SESSION['tid'].'&&mid='.base64_encode("922").'"><i class="fa fa-circle-o"></i> Manage Account</a></li>' : ''; ?>
    <?php echo ($backend_account_manager_tab == 1) ? '</ul></li>' : ''; ?>  
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("700")))
{
$check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$backend_pos_tab = $get_check['backend_pos_tab'];
$backend_add_terminal = $get_check['backend_add_terminal'];
$backend_configure_terminal = $get_check['backend_configure_terminal'];
$backend_assign_terminal = $get_check['backend_assign_terminal'];
$backend_all_terminal = $get_check['backend_all_terminal'];
$backend_all_terminal_request = $get_check['backend_all_terminal_request'];
$backend_all_terminal_report = $get_check['backend_all_terminal_report'];
$backend_terminal_withdrawn_request = $get_check['backend_terminal_withdrawn_request'];
$backend_add_ussd_bank = $get_check['backend_add_ussd_bank'];
$backend_all_ussd_bank = $get_check['backend_all_ussd_bank'];
$backend_pending_terminal_settlement = $get_check['backend_pending_terminal_settlement'];

?>    
    <?php echo ($backend_pos_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-binoculars"></i> <span>Pos Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($backend_add_ussd_bank == 1) ? '<li><a href="add_ussdBank.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Add USSD Bank</a></li>' : ''; ?>
    <?php echo ($backend_add_terminal == 1) ? '<li><a href="add_terminal.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Add Single Terminal</a></li>' : ''; ?>
    <?php echo ($backend_configure_terminal == 1) ? '<li><a href="configure_terminal.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Configure Terminal</a></li>' : ''; ?>
    <?php echo ($backend_add_terminal == 1) ? '<li><a href="add_bulkterminal.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Upload Bulk Terminals</a></li>' : ''; ?>
    <?php echo ($backend_assign_terminal == 1) ? '<li><a href="assTerminal.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Assign Terminal</a></li>' : ''; ?>
    <?php echo ($backend_all_terminal == 1) ? '<li><a href="allterminal.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> All Terminals</a></li>' : ''; ?>
    <?php echo ($backend_pending_terminal_settlement == 1) ? '<li><a href="term_pendSettlement.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Pending Settlement <span class="badge badge-orange right">: '. $myinum_pendingTerminalSettlement .' :</span></a></li>' : ''; ?>
    <?php echo ($backend_all_ussd_bank == 1) ? '<li><a href="all_ussdBank.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> All USSD Bank</a></li>' : ''; ?>
    <?php echo ($backend_all_terminal_request == 1) ? '<li><a href="all_terminalReq.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> All Pending Request</a></li>' : ''; ?>
    <?php echo ($backend_all_terminal_report == 1) ? '<li><a href="allterminal_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Terminal Report</a></li>' : ''; ?>
    <?php echo ($backend_terminal_withdrawn_request == 1) ? '<li><a href="withdrawn_request.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Defaulted Request</a></li>' : ''; ?>
    <?php echo ($backend_pos_tab == 1) ? '</ul></li>' : ''; ?> 
<?php
}
else{
$check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$backend_pos_tab = $get_check['backend_pos_tab'];
$backend_add_terminal = $get_check['backend_add_terminal'];
$backend_configure_terminal = $get_check['backend_configure_terminal'];
$backend_assign_terminal = $get_check['backend_assign_terminal'];
$backend_all_terminal = $get_check['backend_all_terminal'];
$backend_all_terminal_request = $get_check['backend_all_terminal_request'];
$backend_all_terminal_report = $get_check['backend_all_terminal_report'];
$backend_terminal_withdrawn_request = $get_check['backend_terminal_withdrawn_request'];
$backend_add_ussd_bank = $get_check['backend_add_ussd_bank'];
$backend_all_ussd_bank = $get_check['backend_all_ussd_bank'];
$backend_pending_terminal_settlement = $get_check['backend_pending_terminal_settlement'];
?>    
    <?php echo ($backend_pos_tab == 1) ? '<li><a href="#"><i class="fa fa-binoculars"></i> <span>Pos Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($backend_add_ussd_bank == 1) ? '<li><a href="add_ussdBank.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Add USSD Bank</a></li>' : ''; ?>
    <?php echo ($backend_add_terminal == 1) ? '<li><a href="add_terminal.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Add Single Terminal</a></li>' : ''; ?>
    <?php echo ($backend_configure_terminal == 1) ? '<li><a href="configure_terminal.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Configure Terminal</a></li>' : ''; ?>
    <?php echo ($backend_add_terminal == 1) ? '<li><a href="add_bulkterminal.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Upload Bulk Terminals</a></li>' : ''; ?>
    <?php echo ($backend_assign_terminal == 1) ? '<li><a href="assTerminal.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Assign Terminal</a></li>' : ''; ?>
    <?php echo ($backend_all_terminal == 1) ? '<li><a href="allterminal.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> All Terminals</a></li>' : ''; ?>
    <?php echo ($backend_pending_terminal_settlement == 1) ? '<li><a href="term_pendSettlement.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Pending Settlement <span class="badge badge-orange right">: '. $myinum_pendingTerminalSettlement .' :</span></a></li>' : ''; ?>
    <?php echo ($backend_all_ussd_bank == 1) ? '<li><a href="all_ussdBank.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> All USSD Bank</a></li>' : ''; ?>
    <?php echo ($backend_all_terminal_request == 1) ? '<li><a href="all_terminalReq.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> All Pending Request</a></li>' : ''; ?>
    <?php echo ($backend_all_terminal_report == 1) ? '<li><a href="allterminal_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Terminal Report</a></li>' : ''; ?>
    <?php echo ($backend_terminal_withdrawn_request == 1) ? '<li><a href="withdrawn_request.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Defaulted Request</a></li>' : ''; ?>
    <?php echo ($backend_pos_tab == 1) ? '</ul></li>' : ''; ?>   
<?php 
}
?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("420")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_saas_subscription_tab = $get_check['access_saas_subscription_tab'];
  $setup_saas_sub_plan  = $get_check['setup_saas_sub_plan'];
  $add_saas_sub_payment = $get_check['add_saas_sub_payment'];
  $view_saas_sub_plan  = $get_check['view_saas_sub_plan'];
  $saas_subscription_history  = $get_check['saas_subscription_history'];
?>
    <?php echo ($access_saas_subscription_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-globe"></i> <span>Subscription Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($setup_saas_sub_plan == 1) ? '<li><a href="add_saassub_plan?id='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> Setup Sub. Plans</a></li>' : ''; ?>
    <?php echo ($add_saas_sub_payment == 1) ? '<li><a href="add_saassub_pmt?id='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> Add Sub. Payment</a></li>' : ''; ?>
    <?php echo ($view_saas_sub_plan == 1) ? '<li><a href="saassub_plan?id='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> Subscription Plan</a></li>' : ''; ?>
    <?php echo ($saas_subscription_history == 1) ? '<li><a href="paid_sub?id='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> Subscription History</a></li>' : ''; ?>
    <?php echo ($access_saas_subscription_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_saas_subscription_tab = $get_check['access_saas_subscription_tab'];
  $setup_saas_sub_plan  = $get_check['setup_saas_sub_plan'];
  $add_saas_sub_payment = $get_check['add_saas_sub_payment'];
  $view_saas_sub_plan  = $get_check['view_saas_sub_plan'];
  $saas_subscription_history  = $get_check['saas_subscription_history'];
  ?>  
    <?php echo ($access_saas_subscription_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-globe"></i> <span>Subscription Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($setup_saas_sub_plan == 1) ? '<li><a href="add_saassub_plan?id='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> Setup Sub. Plans</a></li>' : ''; ?>
    <?php echo ($add_saas_sub_payment == 1) ? '<li><a href="add_saassub_pmt?id='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> Add Sub. Payment</a></li>' : ''; ?>
    <?php echo ($view_saas_sub_plan == 1) ? '<li><a href="saassub_plan?id='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> Subscription Plan</a></li>' : ''; ?>
    <?php echo ($saas_subscription_history == 1) ? '<li><a href="paid_sub?id='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> Subscription History</a></li>' : ''; ?>
    <?php echo ($access_saas_subscription_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>


<?php
/**
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("750")))
{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$campaign_manager_tab = $get_check['campaign_manager_tab'];
$add_category = $get_check['add_category'];
$list_category = $get_check['list_category'];
$add_region = $get_check['add_region'];
$add_campaign = $get_check['add_campaign'];
$campaign_list = $get_check['campaign_list'];
$lender_list = $get_check['lender_list'];
$add_team = $get_check['add_team'];
$list_teams = $get_check['list_teams'];
$donation_history = $get_check['donation_history'];
$lending_history = $get_check['lending_history'];
$progress_report = $get_check['progress_report'];
?>


    <?php echo ($campaign_manager_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-balance-scale"></i> <span>Campaign Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_category == 1) ? '<li><a href="add_category?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'"><i class="fa fa-circle-o"></i> Add Category</a></li>' : ''; ?>
    <?php echo ($list_category == 1) ? '<li><a href="list_category?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'&&tab=tab_1"><i class="fa fa-circle-o"></i> List Category</a></li>' : ''; ?>
    <?php echo ($add_region == 1) ? '<li><a href="add_region?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Add Region</a></li>' : ''; ?>
    <?php echo ($add_campaign == 1) ? '<li><a href="add_campaign?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Add Campaign</a></li>' : ''; ?>
    <?php echo ($campaign_list == 1) ? '<li><a href="campaign_list?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Campaign List</a></li>' : ''; ?>
    <?php echo ($lender_list == 1) ? '<li><a href="lender_list?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'"><i class="fa fa-circle-o"></i> Lender List</a></li>' : ''; ?>
    <?php echo ($lending_history == 1) ? '<li><a href="lend_history?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'"><i class="fa fa-circle-o"></i> Lend History</a></li>' : ''; ?>
    <?php echo ($list_teams == 1) ? '<li><a href="all_teams?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'&&tab=tab_2"><i class="fa fa-circle-o"></i> All Teams</a></li>' : ''; ?>
    <?php echo ($progress_report == 1) ? '<li><a href="cprogress_report?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'"><i class="fa fa-circle-o"></i> Progress Report</a></li>' : ''; ?>
    <?php echo ($campaign_manager_tab == 1) ? '</ul></li>' : ''; ?> 


<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $campaign_manager_tab = $get_check['campaign_manager_tab'];
    $add_category = $get_check['add_category'];
    $list_category = $get_check['list_category'];
    $add_region = $get_check['add_region'];
    $add_campaign = $get_check['add_campaign'];
    $campaign_list = $get_check['campaign_list'];
    $lender_list = $get_check['lender_list'];
    $add_team = $get_check['add_team'];
    $list_teams = $get_check['list_teams'];
    $donation_history = $get_check['donation_history'];
    $lending_history = $get_check['lending_history'];
    $progress_report = $get_check['progress_report'];
?>


    <?php echo ($campaign_manager_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-balance-scale"></i> <span>Campaign Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_category == 1) ? '<li><a href="add_category?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'"><i class="fa fa-circle-o"></i> Add Category</a></li>' : ''; ?>
    <?php echo ($list_category == 1) ? '<li><a href="list_category?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'&&tab=tab_1"><i class="fa fa-circle-o"></i> List Category</a></li>' : ''; ?>
    <?php echo ($add_region == 1) ? '<li><a href="add_region?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Add Region</a></li>' : ''; ?>
    <?php echo ($add_campaign == 1) ? '<li><a href="add_campaign?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Add Campaign</a></li>' : ''; ?>
    <?php echo ($campaign_list == 1) ? '<li><a href="campaign_list?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Campaign List</a></li>' : ''; ?>
    <?php echo ($lender_list == 1) ? '<li><a href="lender_list?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'"><i class="fa fa-circle-o"></i> Lender List</a></li>' : ''; ?>
    <?php echo ($lending_history == 1) ? '<li><a href="lend_history?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'"><i class="fa fa-circle-o"></i> Lend History</a></li>' : ''; ?>
    <?php echo ($list_teams == 1) ? '<li><a href="all_teams?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'&&tab=tab_2"><i class="fa fa-circle-o"></i> All Teams</a></li>' : ''; ?>
    <?php echo ($progress_report == 1) ? '<li><a href="cprogress_report?id='.$_SESSION['tid'].'&&mid='.base64_encode("750").'"><i class="fa fa-circle-o"></i> Progress Report</a></li>' : ''; ?>
    <?php echo ($campaign_manager_tab == 1) ? '</ul></li>' : ''; ?>


<?php } */ ?>


  
<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("405")))
{
$check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_backend_loan_tab = $get_check['access_backend_loan_tab'];
$view_all_loans = $get_check['view_all_loans'];
$view_all_loan_product = $get_check['view_all_loan_product'];
$list_all_repayment = $get_check['list_all_repayment'];
?>  
    <?php echo ($access_backend_loan_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-balance-scale"></i> <span>Loans Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($view_all_loan_product == 1) ? '<li><a href="add_mloan_p?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> All Loan Product</a></li>' : ''; ?>
    <?php echo ($view_all_loans == 1) ? '<li><a href="listloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> View All Loans</a></li>' : ''; ?>
    <?php echo ($view_due_loans == 1) ? '<li><a href="dueloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Due Loans</a></li>' : ''; ?>
    <?php echo ($list_all_repayment == 1) ? '<li><a href="listpayment?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i>All Loan Repayments</a></li>' : ''; ?>
    <?php echo ($access_backend_loan_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_backend_loan_tab = $get_check['access_backend_loan_tab'];
  $view_all_loans = $get_check['view_all_loans'];
  $view_all_loan_product = $get_check['view_all_loan_product'];
  $list_all_repayment = $get_check['list_all_repayment'];
?>  
    <?php echo ($access_backend_loan_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-balance-scale"></i> <span>Loans Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($view_all_loan_product == 1) ? '<li><a href="add_mloan_p?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> All Loan Product</a></li>' : ''; ?>
    <?php echo ($view_all_loans == 1) ? '<li><a href="listloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> View All Loans</a></li>' : ''; ?>
    <?php echo ($view_due_loans == 1) ? '<li><a href="dueloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Due Loans</a></li>' : ''; ?>
    <?php echo ($list_all_repayment == 1) ? '<li><a href="listpayment?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i>All Loan Repayments</a></li>' : ''; ?>
    <?php echo ($access_backend_loan_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
/**
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("416")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_referral_tab = $get_check['access_referral_tab'];
$set_referral_plan = $get_check['set_referral_plan'];
$view_compensation_plan = $get_check['view_compensation_plan'];
$confirm_bonus = $get_check['confirm_bonus'];
$view_bonus_transaction = $get_check['view_bonus_transaction'];
?>    
    <?php echo ($access_referral_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-link"></i> <span>Referral Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($set_referral_plan == 1) ? '<li><a href="add_compensation_plan?id='.$_SESSION['tid'].'&&mid='.base64_encode("416").'"><i class="fa fa-circle-o"></i> Set Referral Plan</a></li>' : ''; ?>
    <?php echo ($view_compensation_plan == 1) ? '<li><a href="compensation_plan?id='.$_SESSION['tid'].'&&mid='.base64_encode("416").'"><i class="fa fa-circle-o"></i> Compensation Plan</a></li>' : ''; ?>
    <?php echo ($confirm_bonus == 1) ? '<li><a href="view_all_referral?id='.$_SESSION['tid'].'&&mid='.base64_encode("416").'"><i class="fa fa-circle-o"></i> View All Referral</a></li>' : ''; ?>
    <?php echo ($view_bonus_transaction == 1) ? '<li><a href="bonus_transaction?id='.$_SESSION['tid'].'&&mid='.base64_encode("416").'"><i class="fa fa-circle-o"></i> Commission History</a></li>' : ''; ?>
    <?php echo ($access_referral_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_referral_tab = $get_check['access_referral_tab'];
$set_referral_plan = $get_check['set_referral_plan'];
$view_compensation_plan = $get_check['view_compensation_plan'];
$confirm_bonus = $get_check['confirm_bonus'];
$view_bonus_transaction = $get_check['view_bonus_transaction'];
?>    
    <?php echo ($access_referral_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-link"></i> <span>Referral Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($set_referral_plan == 1) ? '<li><a href="add_compensation_plan?id='.$_SESSION['tid'].'&&mid='.base64_encode("416").'"><i class="fa fa-circle-o"></i> Set Referral Plan</a></li>' : ''; ?>
    <?php echo ($view_compensation_plan == 1) ? '<li><a href="compensation_plan?id='.$_SESSION['tid'].'&&mid='.base64_encode("416").'"><i class="fa fa-circle-o"></i> Compensation Plan</a></li>' : ''; ?>
    <?php echo ($confirm_bonus == 1) ? '<li><a href="view_all_referral?id='.$_SESSION['tid'].'&&mid='.base64_encode("416").'"><i class="fa fa-circle-o"></i> View All Referral</a></li>' : ''; ?>
    <?php echo ($view_bonus_transaction == 1) ? '<li><a href="bonus_transaction?id='.$_SESSION['tid'].'&&mid='.base64_encode("416").'"><i class="fa fa-circle-o"></i> Commission History</a></li>' : ''; ?>
    <?php echo ($access_referral_tab == 1) ? '</ul></li>' : ''; ?>
<?php } */ ?>  



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("413")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_backend_permision_tab = $get_check['access_backend_permision_tab'];
  $create_backend_role = $get_check['create_backend_role'];
  $set_permission_level  = $get_check['set_permission_level'];
  $view_all_backend_roles  = $get_check['view_all_backend_roles'];
?>
    <?php echo ($access_backend_permision_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-cogs"></i> <span>Permission Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($create_backend_role == 1) ? '<li><a href="add_role?id='.$_SESSION['tid'].'&&mid='.base64_encode("413").'"><i class="fa fa-circle-o"></i>Create Role</a></li>' : ''; ?>
    <?php echo ($set_permission_level == 1) ? '<li><a href="access_permission?id='.$_SESSION['tid'].'&&mid='.base64_encode("413").'"><i class="fa fa-circle-o"></i>Set Permission Level</a></li>' : ''; ?>
    <?php echo ($view_all_backend_roles == 1) ? '<li><a href="mypermission_list?id='.$_SESSION['tid'].'&&mid='.base64_encode("413").'"><i class="fa fa-circle-o"></i>All Permission</a></li>' : ''; ?>
    <?php echo ($access_backend_permision_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_backend_permision_tab = $get_check['access_backend_permision_tab'];
  $create_backend_role = $get_check['create_backend_role'];
  $set_permission_level  = $get_check['set_permission_level'];
  $view_all_backend_roles  = $get_check['view_all_backend_roles'];
?>    
    <?php echo ($access_backend_permision_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-cogs"></i> <span>Permission Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($create_backend_role == 1) ? '<li><a href="add_role?id='.$_SESSION['tid'].'&&mid='.base64_encode("413").'"><i class="fa fa-circle-o"></i>Create Role</a></li>' : ''; ?>
    <?php echo ($set_permission_level == 1) ? '<li><a href="access_permission?id='.$_SESSION['tid'].'&&mid='.base64_encode("413").'"><i class="fa fa-circle-o"></i>Set Permission Level</a></li>' : ''; ?>
    <?php echo ($view_all_backend_roles == 1) ? '<li><a href="mypermission_list?id='.$_SESSION['tid'].'&&mid='.base64_encode("413").'"><i class="fa fa-circle-o"></i>All Permission</a></li>' : ''; ?>
    <?php echo ($access_backend_permision_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("406")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_helpdesk_tab = $get_check['access_helpdesk_tab'];
    $view_all_tickets = $get_check['view_all_tickets'];
    $close_tickets  = $get_check['close_tickets'];
?>    
    <?php echo ($access_helpdesk_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-envelope-o"></i> <span>Helpdesk Center</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($view_all_tickets == 1) ? '<li><a href="inboxmessage?id='.$_SESSION['tid'].'&&mid='.base64_encode("406").'"><i class="fa fa-circle-o"></i>All Tickes</a></li>' : ''; ?>
    <?php echo ($close_tickets == 1) ? '<li><a href="outboxmessage?id='.$_SESSION['tid'].'&&mid='.base64_encode("406").'"><i class="fa fa-circle-o"></i>Closed Tickets</a></li>' : ''; ?>
    <?php echo ($access_helpdesk_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_helpdesk_tab = $get_check['access_helpdesk_tab'];
    $view_all_tickets = $get_check['view_all_tickets'];
    $close_tickets  = $get_check['close_tickets'];
?>    
    <?php echo ($access_helpdesk_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-envelope-o"></i> <span>Helpdesk Center</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($view_all_tickets == 1) ? '<li><a href="inboxmessage?id='.$_SESSION['tid'].'&&mid='.base64_encode("406").'"><i class="fa fa-circle-o"></i>All Tickes</a></li>' : ''; ?>
    <?php echo ($close_tickets == 1) ? '<li><a href="outboxmessage?id='.$_SESSION['tid'].'&&mid='.base64_encode("406").'"><i class="fa fa-circle-o"></i>Closed Tickets</a></li>' : ''; ?>
    <?php echo ($access_helpdesk_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>
    
  


<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("500")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $income_and_expenses_tab = $get_check['income_and_expenses_tab'];
  $add_income = $get_check['add_income'];
  $view_income = $get_check['view_income'];
  $add_expenses = $get_check['add_expenses'];
  $view_expenses = $get_check['view_expenses'];
?>
    <?php echo ($income_and_expenses_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-calculator"></i> <span>Income & Expenses</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_income == 1) ? '<li><a href="newincome?id='.$_SESSION['tid'].'&&mid='.base64_encode("500").'"><i class="fa fa-circle-o"></i> Add Income</a></li>' : ''; ?>
    <?php echo ($add_expenses == 1) ? '<li><a href="newexpenses?id='.$_SESSION['tid'].'&&mid='.base64_encode("500").'"><i class="fa fa-circle-o"></i> Add Expenses</a></li>' : ''; ?>
    <?php echo ($view_income == 1) ? '<li><a href="listincome?id='.$_SESSION['tid'].'&&mid='.base64_encode("500").'"><i class="fa fa-circle-o"></i> View Income</a></li>' : ''; ?>
    <?php echo ($view_expenses == 1) ? '<li><a href="listexpenses?id='.$_SESSION['tid'].'&&mid='.base64_encode("500").'"><i class="fa fa-circle-o"></i> View Expenses</a></li>' : ''; ?>
    <?php echo ($income_and_expenses_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $income_and_expenses_tab = $get_check['income_and_expenses_tab'];
  $add_income = $get_check['add_income'];
  $view_income = $get_check['view_income'];
  $add_expenses = $get_check['add_expenses'];
  $view_expenses = $get_check['view_expenses'];
?>
    <?php echo ($income_and_expenses_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-calculator"></i> <span>Income & Expenses</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_income == 1) ? '<li><a href="newincome?id='.$_SESSION['tid'].'&&mid='.base64_encode("500").'"><i class="fa fa-circle-o"></i> Add Income</a></li>' : ''; ?>
    <?php echo ($add_expenses == 1) ? '<li><a href="newexpenses?id='.$_SESSION['tid'].'&&mid='.base64_encode("500").'"><i class="fa fa-circle-o"></i> Add Expenses</a></li>' : ''; ?>
    <?php echo ($view_income == 1) ? '<li><a href="listincome?id='.$_SESSION['tid'].'&&mid='.base64_encode("500").'"><i class="fa fa-circle-o"></i> View Income</a></li>' : ''; ?>
    <?php echo ($view_expenses == 1) ? '<li><a href="listexpenses?id='.$_SESSION['tid'].'&&mid='.base64_encode("500").'"><i class="fa fa-circle-o"></i> View Expenses</a></li>' : ''; ?>
    <?php echo ($income_and_expenses_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("423")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_payroll_tab = $get_check['access_payroll_tab'];
    $add_payroll = $get_check['add_payroll'];
    $view_payroll  = $get_check['view_payroll'];
?>
    <?php echo ($access_payroll_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-paypal"></i> <span>Payroll Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_payroll == 1) ? '<li><a href="newpayroll?id='.$_SESSION['tid'].'&&mid='.base64_encode("423").'"><i class="fa fa-circle-o"></i> Add Payroll</a></li>' : ''; ?>
    <?php echo ($view_payroll == 1) ? '<li><a href="listpayroll?id='.$_SESSION['tid'].'&&mid='.base64_encode("423").'"><i class="fa fa-circle-o"></i> View Payroll</a></li>' : ''; ?>
    <?php echo ($access_payroll_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_payroll_tab = $get_check['access_payroll_tab'];
    $add_payroll = $get_check['add_payroll'];
    $view_payroll  = $get_check['view_payroll'];
?>
    <?php echo ($access_payroll_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-paypal"></i> <span>Payroll Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_payroll == 1) ? '<li><a href="newpayroll?id='.$_SESSION['tid'].'&&mid='.base64_encode("423").'"><i class="fa fa-circle-o"></i> Add Payroll</a></li>' : ''; ?>
    <?php echo ($view_payroll == 1) ? '<li><a href="listpayroll?id='.$_SESSION['tid'].'&&mid='.base64_encode("423").'"><i class="fa fa-circle-o"></i> View Payroll</a></li>' : ''; ?>
    <?php echo ($access_payroll_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("425")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_report_tab = $get_check['access_report_tab'];
  $loan_borrowers_reports = $get_check['loan_borrowers_reports'];
  $loan_collection_reports = $get_check['loan_collection_reports'];
  $loan_collector_reports = $get_check['loan_collector_reports'];
  $savings_collection_reports = $get_check['savings_collection_reports'];
  $subscription_reports = $get_check['subscription_reports'];
  $terminal_aggregate_reports = $get_check['terminal_aggregate_reports'];
  $id_verification_report = $get_check['id_verification_report'];
  $enrollees_report = $get_check['enrollees_report'];
  $financial_reports = $get_check['financial_reports'];
?>    
    <?php echo ($access_report_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-area-chart"></i> <span>Reports</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($loan_borrowers_reports == 1) ? '<li><a href="borrower_reports?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Loan Borrowers Reports</a></li>' : ''; ?>
    <?php echo ($loan_collection_reports == 1) ? '<li><a href="collection_reports?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Loan Collection Reports</a></li>' : ''; ?>
    <?php echo ($loan_collector_reports == 1) ? '<li><a href="collector_reports?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Loan Collector Reports</a></li>' : ''; ?>
    <?php echo ($subscription_reports == 1) ? '<li><a href="subscription_reports?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Subscription Reports</a></li>' : ''; ?>
    <?php echo ($savings_collection_reports == 1) ? '<li><a href="savings_report?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Savings Collection Reports</a></li>' : ''; ?>
    <?php echo ($terminal_aggregate_reports == 1) ? '<li><a href="terminaAggr_reports?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Terminal Aggregate Rpts</a></li>' : ''; ?>
    <?php echo ($id_verification_report == 1) ? '<li><a href="idVerification_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> ID Verification Report</a></li>' : ''; ?>
    <?php echo ($enrollees_report == 1) ? '<li><a href="enrollees_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Enrollees Report</a></li>' : ''; ?>
    <?php echo ($financial_reports == 1) ? '<li><a href="financial_report?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Financial Reports</a></li>' : ''; ?>
    <?php echo ($access_report_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_report_tab = $get_check['access_report_tab'];
  $loan_borrowers_reports = $get_check['loan_borrowers_reports'];
  $loan_collection_reports = $get_check['loan_collection_reports'];
  $loan_collector_reports = $get_check['loan_collector_reports'];
  $savings_collection_reports = $get_check['savings_collection_reports'];
  $subscription_reports = $get_check['subscription_reports'];
  $terminal_aggregate_reports = $get_check['terminal_aggregate_reports'];
  $id_verification_report = $get_check['id_verification_report'];
  $enrollees_report = $get_check['enrollees_report'];
  $financial_reports = $get_check['financial_reports'];
?>
    <?php echo ($access_report_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-area-chart"></i> <span>Reports</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($loan_borrowers_reports == 1) ? '<li><a href="borrower_reports?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Loan Borrowers Reports</a></li>' : ''; ?>
    <?php echo ($loan_collection_reports == 1) ? '<li><a href="collection_reports?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Loan Collection Reports</a></li>' : ''; ?>
    <?php echo ($loan_collector_reports == 1) ? '<li><a href="collector_reports?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Loan Collector Reports</a></li>' : ''; ?>
    <?php echo ($subscription_reports == 1) ? '<li><a href="subscription_reports?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Subscription Reports</a></li>' : ''; ?>
    <?php echo ($savings_collection_reports == 1) ? '<li><a href="savings_report?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Savings Collection Reports</a></li>' : ''; ?>
    <?php echo ($terminal_aggregate_reports == 1) ? '<li><a href="terminaAggr_reports?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Terminal Aggregate Rpts</a></li>' : ''; ?>
    <?php echo ($id_verification_report == 1) ? '<li><a href="idVerification_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> ID Verification Report</a></li>' : ''; ?>
    <?php echo ($enrollees_report == 1) ? '<li><a href="enrollees_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Enrollees Report</a></li>' : ''; ?>
    <?php echo ($financial_reports == 1) ? '<li><a href="financial_report?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Financial Reports</a></li>' : ''; ?>
    <?php echo ($access_report_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("411")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_settings_tab = $get_check['access_settings_tab'];
  $company_setup = $get_check['company_setup'];
  $sms_gateway_settings = $get_check['sms_gateway_settings'];
  $add_system_module = $get_check['add_system_module'];
  $setup_client_module_properties = $get_check['setup_client_module_properties'];
  $setup_backend_module_properties = $get_check['setup_backend_module_properties'];
  $airtime_other_apis = $get_check['airtime_other_apis'];
  $backup_database = $get_check['backup_database'];
  $restful_api_settings = $get_check['restful_api_settings'];
  $setup_coupon = $get_check['setup_coupon'];
  $add_client_module_pricing = $get_check['add_client_module_pricing'];
  $manage_client_module_pricing = $get_check['manage_client_module_pricing'];
  $add_nimc_partners = $get_check['add_nimc_partners'];
  $add_bulk_nimc_partners = $get_check['add_bulk_nimc_partners'];
  $nimc_partner_list = $get_check['nimc_partner_list'];
  //$p2pbanner_setup = $get_check['p2pbanner_setup'];
?>    
    <?php echo ($access_settings_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-gear"></i> <span>General Settings</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($company_setup == 1) ? '<li><a href="system_set?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'&&tab=tab_1"><i class="fa fa-circle-o"></i>Company Setup</a></li>' : ''; ?>
    <?php //echo ($company_setup == 1) ? '<li><a href="p2plend_set?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>P2p-Lending Setup</a></li>' : ''; ?>
    <?php //echo ($p2pbanner_setup == 1) ? '<li><a href="p2plending_banner?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>P2p-Lending Banner</a></li>' : ''; ?>
    <?php echo ($setup_coupon == 1) ? '<li><a href="setup_coupon?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Setup Coupon Code</a></li>' : ''; ?>
    <?php echo ($sms_gateway_settings == 1) ? '<li><a href="sms?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'&&tab=tab_1"><i class="fa fa-circle-o"></i>SMS Gateway Settings</a></li>' : ''; ?>
    <?php echo ($add_system_module == 1) ? '<li><a href="add_sysmodule?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Add System Module</a></li>' : ''; ?>
    <?php echo ($add_client_module_pricing == 1) ? '<li><a href="configure_modulePrince?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Configure Saas Sub Module</a></li>' : ''; ?>
    <?php echo ($manage_client_module_pricing == 1) ? '<li><a href="manage_modulePrice?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Manage Saas Sub Module</a></li>' : ''; ?>
    <?php echo ($setup_client_module_properties == 1) ? '<li><a href="add_moduleproperty?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Configure Client Module</a></li>' : ''; ?>
    <?php echo ($setup_backend_module_properties == 1) ? '<li><a href="add_backendmproperty?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Configure Backend Module</a></li>' : ''; ?>
    <?php echo ($airtime_other_apis == 1) ? '<li><a href="airtime_otherapi?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Open API Settings</a></li>' : ''; ?>
    <?php echo ($restful_api_settings == 1) ? '<li><a href="restful_api_settings?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>RESTful API Settings</a></li>' : ''; ?>
    <?php echo ($restful_api_settings == 1) ? '<li><a href="card_issuerapis?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Card Issuer APIs</a></li>' : ''; ?>
    <?php echo ($add_nimc_partners == 1) ? '<li><a href="addNIMCPartners.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Add NIMC Partner</a></li>' : ''; ?>
    <?php echo ($add_bulk_nimc_partners == 1) ? '<li><a href="addNIMCPartners.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'&&bulk"><i class="fa fa-circle-o"></i>Add Bulk NIMC Partner</a></li>' : ''; ?>
    <?php echo ($nimc_partner_list == 1) ? '<li><a href="nimcPartnerList.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>All NIMC Partners</a></li>' : ''; ?>
    <?php echo ($backup_database == 1) ? '<li><a href="backupdatabase?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Backup Database</a></li>' : ''; ?>
    <?php echo ($access_settings_tab == 1) ? '</ul></li>' : ''; ?> 
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission2 WHERE urole = '$urole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_settings_tab = $get_check['access_settings_tab'];
  $company_setup = $get_check['company_setup'];
  $sms_gateway_settings = $get_check['sms_gateway_settings'];
  $add_system_module = $get_check['add_system_module'];
  $setup_client_module_properties = $get_check['setup_client_module_properties'];
  $setup_backend_module_properties = $get_check['setup_backend_module_properties'];
  $airtime_other_apis = $get_check['airtime_other_apis'];
  $backup_database = $get_check['backup_database'];
  $restful_api_settings = $get_check['restful_api_settings'];
  $setup_coupon = $get_check['setup_coupon'];
  $add_client_module_pricing = $get_check['add_client_module_pricing'];
  $manage_client_module_pricing = $get_check['manage_client_module_pricing'];
  $add_nimc_partners = $get_check['add_nimc_partners'];
  $add_bulk_nimc_partners = $get_check['add_bulk_nimc_partners'];
  $nimc_partner_list = $get_check['nimc_partner_list'];
  //$p2pbanner_setup = $get_check['p2pbanner_setup'];
?>    
    <?php echo ($access_settings_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-gear"></i> <span>General Settings</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($company_setup == 1) ? '<li><a href="system_set?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'&&tab=tab_1"><i class="fa fa-circle-o"></i>Company Setup</a></li>' : ''; ?>
    <?php //echo ($company_setup == 1) ? '<li><a href="p2plend_set?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>P2p-Lending Setup</a></li>' : ''; ?>
    <?php //echo ($p2pbanner_setup == 1) ? '<li><a href="p2plending_banner?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>P2p-Lending Banner</a></li>' : ''; ?>
    <?php echo ($setup_coupon == 1) ? '<li><a href="setup_coupon?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Setup Coupon Code</a></li>' : ''; ?>
    <?php echo ($sms_gateway_settings == 1) ? '<li><a href="sms?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'&&tab=tab_1"><i class="fa fa-circle-o"></i>SMS Gateway Settings</a></li>' : ''; ?>
    <?php echo ($add_system_module == 1) ? '<li><a href="add_sysmodule?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Add System Module</a></li>' : ''; ?>
    <?php echo ($add_client_module_pricing == 1) ? '<li><a href="configure_modulePrince?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Configure Saas Sub Module</a></li>' : ''; ?>
    <?php echo ($manage_client_module_pricing == 1) ? '<li><a href="manage_modulePrice?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Manage Saas Sub Module</a></li>' : ''; ?>
    <?php echo ($setup_client_module_properties == 1) ? '<li><a href="add_moduleproperty?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Configure Client Module</a></li>' : ''; ?>
    <?php echo ($setup_backend_module_properties == 1) ? '<li><a href="add_backendmproperty?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Configure Backend Module</a></li>' : ''; ?>
    <?php echo ($airtime_other_apis == 1) ? '<li><a href="airtime_otherapi?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Open API Settings</a></li>' : ''; ?>
    <?php echo ($restful_api_settings == 1) ? '<li><a href="restful_api_settings?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>RESTful API Settings</a></li>' : ''; ?>
    <?php echo ($restful_api_settings == 1) ? '<li><a href="card_issuerapis?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Card Issuer APIs</a></li>' : ''; ?>
    <?php echo ($add_nimc_partners == 1) ? '<li><a href="addNIMCPartners.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Add NIMC Partner</a></li>' : ''; ?>
    <?php echo ($add_bulk_nimc_partners == 1) ? '<li><a href="addNIMCPartners.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'&&bulk"><i class="fa fa-circle-o"></i>Add Bulk NIMC Partner</a></li>' : ''; ?>
    <?php echo ($nimc_partner_list == 1) ? '<li><a href="nimcPartnerList.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>All NIMC Partners</a></li>' : ''; ?>
    <?php echo ($backup_database == 1) ? '<li><a href="backupdatabase?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Backup Database</a></li>' : ''; ?>
    <?php echo ($access_settings_tab == 1) ? '</ul></li>' : ''; ?>  
<?php } ?>
    
    <li>
        <a href="../logout.php"><i class="fa fa-sign-out"></i> <span>Logout</span></a>
    </li>
    

    </section>
    <!-- /.sidebar -->
  </aside>