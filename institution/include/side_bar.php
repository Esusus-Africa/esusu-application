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
          <p><?php echo $row ['username'] ;?></p>
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
		<li class="active"><a href="audit_trail.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("650"); ?>"><i class="fa fa-book"></i> <span>Audit Trail</span></a></li>
<?php
}
else{
	?>
		<li><a href="audit_trail.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("650"); ?>"><i class="fa fa-book"></i> <span>Audit Trail</span></a></li>
<?php } ?>


<?php
if($ibranch_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("402")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_branch_tab = $get_check['access_branch_tab'];
  $add_branches = $get_check['add_branches'];
  $list_branches  = $get_check['list_branches'];
?>
    <?php echo ($access_branch_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-object-ungroup"></i> <span>Branch Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_branches == 1) ? '<li><a href="newbranches.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("402").'"><i class="fa fa-circle-o"></i> Add Branches</a></li>' : ''; ?>
    <?php echo ($list_branches == 1) ? '<li><a href="listbranches.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("402").'"><i class="fa fa-circle-o"></i> List Branches</a></li>' : ''; ?>
    <?php echo ($access_branch_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_branch_tab = $get_check['access_branch_tab'];
  $add_branches = $get_check['add_branches'];
  $list_branches  = $get_check['list_branches'];
  ?>  
    <?php echo ($access_branch_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-object-ungroup"></i> <span>Branch Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_branches == 1) ? '<li><a href="newbranches.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("402").'"><i class="fa fa-circle-o"></i> Add Branches</a></li>' : ''; ?>
    <?php echo ($list_branches == 1) ? '<li><a href="listbranches.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("402").'"><i class="fa fa-circle-o"></i> List Branches</a></li>' : ''; ?>
    <?php echo ($access_branch_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>

<?php
}
else{
    echo "";
}
?>


<?php
if($idept_settings === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("950")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $department_manager_tab = $get_check['department_manager_tab'];
  $add_department = $get_check['add_department'];
  $list_department  = $get_check['list_department'];
?>
    <?php echo ($department_manager_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-object-group"></i> <span>Department Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_department == 1) ? '<li><a href="newdpt.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("950").'"><i class="fa fa-circle-o"></i> Add Department</a></li>' : ''; ?>
    <?php echo ($list_department == 1) ? '<li><a href="listdpt.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("950").'"><i class="fa fa-circle-o"></i> List Departments</a></li>' : ''; ?>
    <?php echo ($department_manager_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $department_manager_tab = $get_check['department_manager_tab'];
  $add_department = $get_check['add_department'];
  $list_department  = $get_check['list_department'];
  ?>  
    <?php echo ($department_manager_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-object-group"></i> <span>Department Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_department == 1) ? '<li><a href="newdpt.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("950").'"><i class="fa fa-circle-o"></i> Add Department</a></li>' : ''; ?>
    <?php echo ($list_department == 1) ? '<li><a href="listdpt.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("950").'"><i class="fa fa-circle-o"></i> List Departments</a></li>' : ''; ?>
    <?php echo ($department_manager_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>

<?php
}
else{
    echo "";
}
?>



<?php
if($ipermission_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("413")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_mpermision_tab = $get_check['access_mpermision_tab'];
  $create_role = $get_check['create_role'];
  $set_permission_level  = $get_check['set_permission_level'];
  $view_all_roles  = $get_check['view_all_roles'];
?>    
    <?php echo ($access_mpermision_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-cogs"></i> <span>Permission Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($create_role == 1) ? '<li><a href="add_role.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("413").'"><i class="fa fa-circle-o"></i>Create Role(s)</a></li>' : ''; ?>
    <?php echo ($set_permission_level == 1) ? '<li><a href="access_permission.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("413").'"><i class="fa fa-circle-o"></i>Set Permission Level</a></li>' : ''; ?>
    <?php echo ($set_permission_level == 1) ? '<li><a href="permission_list.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("413").'"><i class="fa fa-circle-o"></i>View All Permission</a></li>' : ''; ?>
    <?php echo ($view_all_roles == 1) ? '<li><a href="view_roles.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("413").'"><i class="fa fa-circle-o"></i>View All Role</a></li>' : ''; ?>
    <?php echo ($access_mpermision_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_mpermision_tab = $get_check['access_mpermision_tab'];
  $create_role = $get_check['create_role'];
  $set_permission_level  = $get_check['set_permission_level'];
  $view_all_roles  = $get_check['view_all_roles'];
?>    
    <?php echo ($access_mpermision_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-cogs"></i> <span>Permission Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($create_role == 1) ? '<li><a href="add_role.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("413").'"><i class="fa fa-circle-o"></i>Create Role(s)</a></li>' : ''; ?>
    <?php echo ($set_permission_level == 1) ? '<li><a href="access_permission.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("413").'"><i class="fa fa-circle-o"></i>Set Permission Level</a></li>' : ''; ?>
    <?php echo ($set_permission_level == 1) ? '<li><a href="permission_list.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("413").'"><i class="fa fa-circle-o"></i>View All Permission</a></li>' : ''; ?>
    <?php echo ($view_all_roles == 1) ? '<li><a href="view_roles.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("413").'"><i class="fa fa-circle-o"></i>View All Role</a></li>' : ''; ?>
    <?php echo ($access_mpermision_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>

<?php
}
else{
    echo "";
}
?>





<?php
if($icustomer_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("403")))
{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_customer_tab = $get_check['access_customer_tab'];
$add_customer = $get_check['add_customer'];
$link_account = $get_check['link_account'];
$view_all_customers = $get_check['view_all_customers'];
$individual_customer_records = $get_check['individual_customer_records'];
$branch_customer_records = $get_check['branch_customer_records'];
?>    
    <?php echo ($access_customer_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-users"></i> <span>Customers Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_customer == 1) ? '<li><a href="addcustomer.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("403").'&&tab=tab_1"><i class="fa fa-circle-o"></i> New Customer</a></li>' : ''; ?>
    <?php echo ($link_account == 1) ? '<li><a href="linkaccount.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("403").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Link Account</a></li>' : ''; ?>
    <?php echo ($view_all_customers == 1 || $individual_customer_records == 1 || $branch_customer_records == 1) ? '<li><a href="customer.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("403").'"><i class="fa fa-circle-o"></i> All Customer</a></li>' : ''; ?>
    <?php echo ($access_customer_tab == 1) ? '</ul></li>' : ''; ?> 
<?php
}
else{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_customer_tab = $get_check['access_customer_tab'];
$add_customer = $get_check['add_customer'];
$link_account = $get_check['link_account'];
$view_all_customers = $get_check['view_all_customers'];
$individual_customer_records = $get_check['individual_customer_records'];
$branch_customer_records = $get_check['branch_customer_records'];
?>    
    <?php echo ($access_customer_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-users"></i> <span>Customers Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_customer == 1) ? '<li><a href="addcustomer.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("403").'&&tab=tab_1"><i class="fa fa-circle-o"></i> New Customer</a></li>' : ''; ?>
    <?php echo ($link_account == 1) ? '<li><a href="linkaccount.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("403").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Link Account</a></li>' : ''; ?>
    <?php echo ($view_all_customers == 1 || $individual_customer_records == 1 || $branch_customer_records == 1) ? '<li><a href="customer.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("403").'"><i class="fa fa-circle-o"></i> All Customer</a></li>' : ''; ?>
    <?php echo ($access_customer_tab == 1) ? '</ul></li>' : ''; ?> 
<?php } ?>

<?php
}
else{
    echo "";
}
?>



<?php
if($iaccount_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("922")))
{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$account_manager_tab = $get_check['account_manager_tab'];
$open_bank_account = $get_check['open_bank_account'];
$manage_bank_account = $get_check['manage_bank_account'];
$manage_individual_bank_account = $get_check['manage_individual_bank_account'];
$manage_branch_bank_account = $get_check['manage_branch_bank_account'];
$view_bank_internal_account_statement = $get_check['view_bank_internal_account_statement'];
?>    
    <?php echo ($account_manager_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-bank"></i> <span>Account Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($open_bank_account == 1) ? '<li><a href="openAccount.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("922").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Open Bank Account</a></li>' : ''; ?>
    <?php echo ($manage_bank_account == 1 || $manage_individual_bank_account == 1 || $manage_branch_bank_account == 1) ? '<li><a href="manageAccount.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("922").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Manage Bank Account</a></li>' : ''; ?>
    <?php echo ($view_bank_internal_account_statement == 1) ? '<li><a href="view_acctStm.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("922").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Internal Account Statement</a></li>' : ''; ?>
    <?php echo ($account_manager_tab == 1) ? '</ul></li>' : ''; ?> 
<?php
}
else{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$account_manager_tab = $get_check['account_manager_tab'];
$open_bank_account = $get_check['open_bank_account'];
$manage_bank_account = $get_check['manage_bank_account'];
$manage_individual_bank_account = $get_check['manage_individual_bank_account'];
$manage_branch_bank_account = $get_check['manage_branch_bank_account'];
$view_bank_internal_account_statement = $get_check['view_bank_internal_account_statement'];
?>    
    <?php echo ($account_manager_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-bank"></i> <span>Account Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($open_bank_account == 1) ? '<li><a href="openAccount.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("922").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Open Bank Account</a></li>' : ''; ?>
    <?php echo ($manage_bank_account == 1 || $manage_individual_bank_account == 1 || $manage_branch_bank_account == 1) ? '<li><a href="manageAccount.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("922").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Manage Bank Account</a></li>' : ''; ?>
    <?php echo ($view_bank_internal_account_statement == 1) ? '<li><a href="view_acctStm.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("922").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Internal Account Statement</a></li>' : ''; ?>
    <?php echo ($account_manager_tab == 1) ? '</ul></li>' : ''; ?> 
<?php } ?>

<?php
}
else{
    echo "";
}
?>



<?php
if($ipending_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("240")))
{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$pending_manager_tab = $get_check['pending_manager_tab'];
$view_pending_customer = $get_check['view_pending_customer'];
$view_pending_savings_transaction = $get_check['view_pending_savings_transaction'];
$view_pending_loan_repayment = $get_check['view_pending_loan_repayment'];
$pending_approval_disapproval_check = $get_check['pending_approval_disapproval_check'];
?>    
    <?php echo ($pending_manager_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-exclamation"></i> <span>Pending Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($view_pending_customer == 1) ? '<li><a href="pending_customer.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("240").'"><i class="fa fa-circle-o"></i> Customer Registration</a></li>' : ''; ?>
    <?php echo ($view_pending_savings_transaction == 1) ? '<li><a href="pending_transaction.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("240").'"><i class="fa fa-circle-o"></i> Savings Transaction</a></li>' : ''; ?>
    <?php echo ($view_pending_loan_repayment == 1) ? '<li><a href="pending_repayment.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("240").'"><i class="fa fa-circle-o"></i> Loan Repayment</a></li>' : ''; ?>
    <?php echo ($pending_manager_tab == 1) ? '</ul></li>' : ''; ?> 
<?php
}
else{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$pending_manager_tab = $get_check['pending_manager_tab'];
$view_pending_customer = $get_check['view_pending_customer'];
$view_pending_savings_transaction = $get_check['view_pending_savings_transaction'];
$view_pending_loan_repayment = $get_check['view_pending_loan_repayment'];
$pending_approval_disapproval_check = $get_check['pending_approval_disapproval_check'];
?>    
    <?php echo ($pending_manager_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-exclamation"></i> <span>Pending Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($view_pending_customer == 1) ? '<li><a href="pending_customer.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("240").'"><i class="fa fa-circle-o"></i> Customer Registration</a></li>' : ''; ?>
    <?php echo ($view_pending_savings_transaction == 1) ? '<li><a href="pending_transaction.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("240").'"><i class="fa fa-circle-o"></i> Savings Transaction</a></li>' : ''; ?>
    <?php echo ($view_pending_loan_repayment == 1) ? '<li><a href="pending_repayment.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("240").'"><i class="fa fa-circle-o"></i> Loan Repayment</a></li>' : ''; ?>
    <?php echo ($pending_manager_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>

<?php
}
else{
    echo "";
}
?>



<?php
if($iinvite_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("911")))
{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$send_invite = $get_check['send_invite'];
$invite_status = $get_check['invite_status'];
?>    
    <?php echo ($send_invite == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-sitemap"></i> <span>Invite Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($send_invite == 1) ? '<li><a href="sendinvite.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("911").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Send Invite</a></li>' : ''; ?>
    <?php echo ($invite_status == 1) ? '<li><a href="invitereport.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("911").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Invite Report</a></li>' : ''; ?>
    <?php echo ($send_invite == 1) ? '</ul></li>' : ''; ?> 
<?php
}
else{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$send_invite = $get_check['send_invite'];
$invite_status = $get_check['invite_status'];
?>    
    <?php echo ($send_invite == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-sitemap"></i> <span>Invite Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($send_invite == 1) ? '<li><a href="sendinvite.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("911").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Send Invite</a></li>' : ''; ?>
    <?php echo ($invite_status == 1) ? '<li><a href="invitereport.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("911").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Invite Report</a></li>' : ''; ?>
    <?php echo ($send_invite == 1) ? '</ul></li>' : ''; ?> 
<?php } ?>

<?php
}
else{
    echo "";
}
?>



<?php
if($icard_issuance_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("550")))
{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$create_card = $get_check['create_card'];
$list_card = $get_check['list_card'];
$link_verve_card = $get_check['link_verve_card'];
?>    
    <?php echo ($create_card == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-credit-card"></i> <span>Card Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($create_card == 1) ? '<li><a href="create_card?id='.$_SESSION['tid'].'&&mid='.base64_encode("550").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Issue / Load Card</a></li>' : ''; ?>
    <?php echo ($link_verve_card == '1') ? '<li><a href="link_verveCard?id='.$_SESSION['tid'].'&&mid='.base64_encode("550").'"><i class="fa fa-circle-o"></i> Link Vervecard(Prepaid)</a></li>' : ''; ?>
    <?php echo ($link_verve_card == '1') ? '<li><a href="link_verveCard2?id='.$_SESSION['tid'].'&&mid='.base64_encode("550").'"><i class="fa fa-circle-o"></i> Link Vervecard(Debit)</a></li>' : ''; ?>
    <?php echo ($create_card == '1') ? '<li><a href="create_card?id='.$_SESSION['tid'].'&&mid='.base64_encode("550").'&&tab=tab_5"><i class="fa fa-circle-o"></i> Vervecard Report</a></li>' : ''; ?>
    <?php echo ($list_card == 1) ? '<li><a href="list_card?id='.$_SESSION['tid'].'&&mid='.base64_encode("550").'&&tab=tab_1"><i class="fa fa-circle-o"></i> All Cardholders</a></li>' : ''; ?>
    <?php echo ($create_card == 1) ? '</ul></li>' : ''; ?>  
<?php
}
else{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$create_card = $get_check['create_card'];
$list_card = $get_check['list_card'];
$link_verve_card = $get_check['link_verve_card'];
?>    
    <?php echo ($create_card == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-credit-card"></i> <span>Card Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($create_card == 1) ? '<li><a href="create_card?id='.$_SESSION['tid'].'&&mid='.base64_encode("550").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Issue / Load Card</a></li>' : ''; ?>
    <?php echo ($link_verve_card == '1') ? '<li><a href="link_verveCard?id='.$_SESSION['tid'].'&&mid='.base64_encode("550").'"><i class="fa fa-circle-o"></i> Link Vervecard(Prepaid)</a></li>' : ''; ?>
    <?php echo ($link_verve_card == '1') ? '<li><a href="link_verveCard2?id='.$_SESSION['tid'].'&&mid='.base64_encode("550").'"><i class="fa fa-circle-o"></i> Link Vervecard(Debit)</a></li>' : ''; ?>
    <?php echo ($create_card == '1') ? '<li><a href="create_card?id='.$_SESSION['tid'].'&&mid='.base64_encode("550").'&&tab=tab_5"><i class="fa fa-circle-o"></i> Vervecard Report</a></li>' : ''; ?>
    <?php echo ($list_card == 1) ? '<li><a href="list_card?id='.$_SESSION['tid'].'&&mid='.base64_encode("550").'&&tab=tab_1"><i class="fa fa-circle-o"></i> All Cardholders</a></li>' : ''; ?>
    <?php echo ($create_card == 1) ? '</ul></li>' : ''; ?>   
<?php } ?>

<?php
}
else{
    echo "";
}
?>





<?php
if($iproduct_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("1000")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $product_manager_tab = $get_check['product_manager_tab'];
    $list_all_product = $get_check['list_all_product'];
    $all_product_subscription = $get_check['all_product_subscription'];
    $individual_product_subscription = $get_module['individual_product_subscription'];
    $all_product_transaction == $get_check['all_product_transaction'];
    $individual_product_transaction = $get_module['individual_product_transaction'];
    $product_withdrawal_request = $get_check['product_withdrawal_request'];
?>	
		<?php echo ($product_manager_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-gift"></i> <span>Product Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
        <?php echo ($list_all_product == 1) ? '<li><a href="allproduct.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("1000").'"><i class="fa fa-circle-o"></i> Browse Product</a></li>' : ''; ?>
        <?php echo ($all_product_subscription == 1 || $individual_product_subscription == 1) ? '<li><a href="allproduct_sub.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("1000").'"><i class="fa fa-circle-o"></i> Product Subscription</a></li>' : ''; ?>
        <?php echo ($all_product_transaction == 1 || $individual_product_transaction == 1) ? '<li><a href="allproduct_trans.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("1000").'"><i class="fa fa-circle-o"></i> Product Transaction</a></li>' : ''; ?>
        <?php echo ($product_withdrawal_request == 1) ? '<li><a href="make_prdwrq.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("1000").'"><i class="fa fa-circle-o"></i> Make Withdrawal</a></li>' : ''; ?>
        <?php echo ($product_withdrawal_request == 1) ? '<li><a href="pwithdrawal_req.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("1000").'"><i class="fa fa-circle-o"></i> All Request</a></li>' : ''; ?>
        <?php echo ($product_manager_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $product_manager_tab = $get_check['product_manager_tab'];
    $list_all_product = $get_check['list_all_product'];
    $all_product_subscription = $get_check['all_product_subscription'];
    $individual_product_subscription = $get_module['individual_product_subscription'];
    $all_product_transaction == $get_check['all_product_transaction'];
    $individual_product_transaction = $get_module['individual_product_transaction'];
    $product_withdrawal_request = $get_check['product_withdrawal_request'];
?>	
		<?php echo ($product_manager_tab == 1) ? '<li><a href="#"><i class="fa fa-gift"></i> <span>Product Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
        <?php echo ($list_all_product == 1) ? '<li><a href="allproduct.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("1000").'"><i class="fa fa-circle-o"></i> Browse Product</a></li>' : ''; ?>
        <?php echo ($all_product_subscription == 1 || $individual_product_subscription == 1) ? '<li><a href="allproduct_sub.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("1000").'"><i class="fa fa-circle-o"></i> Product Subscription</a></li>' : ''; ?>
        <?php echo ($all_product_transaction == 1 || $individual_product_transaction == 1) ? '<li><a href="allproduct_trans.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("1000").'"><i class="fa fa-circle-o"></i> Product Transaction</a></li>' : ''; ?>
        <?php echo ($product_withdrawal_request == 1) ? '<li><a href="make_prdwrq.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("1000").'"><i class="fa fa-circle-o"></i> Make Withdrawal</a></li>' : ''; ?>
        <?php echo ($product_withdrawal_request == 1) ? '<li><a href="pwithdrawal_req.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("1000").'"><i class="fa fa-circle-o"></i> All Request</a></li>' : ''; ?>
        <?php echo ($product_manager_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>


<?php 
}
else{
    echo "";
}
?>




<?php
if($iinvestment_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("490")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_investment_tab = $get_check['access_investment_tab'];
    $view_investment_subscription = $get_check['view_investment_subscription'];
    $view_investment_transaction = $get_check['view_investment_transaction'];
    $disapprove_withdrawal_request = $get_check['disapprove_withdrawal_request'];
    $approve_withdrawal_request = $get_check['approve_withdrawal_request'];

?>
    <?php echo ($access_investment_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-calculator"></i> <span>Investment Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($view_investment_transaction == 1) ? '<li><a href="notification.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Notification <span class="badge badge-orange right">: '. $myinum_pendinginvestment .' :</span></a></li>' : ''; ?>
    <?php echo ($view_investment_subscription == 1) ? '<li><a href="view_msaving_sub.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Investment Subscription</a></li>' : ''; ?>
    <?php echo ($view_investment_transaction == 1) ? '<li><a href="msavings_trans.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Investment Transaction</a></li>' : ''; ?>
    <?php echo ($disapprove_withdrawal_request == 1 || $approve_withdrawal_request == 1) ? '<li><a href="withdrawal_request.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Withdrawal Request</a></li>' : ''; ?>
    <?php echo ($access_investment_tab == 1) ? '<li><a href="settlement.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Settlement History</a></li>' : ''; ?>
    <?php echo ($access_investment_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_investment_tab = $get_check['access_investment_tab'];
    $view_investment_subscription = $get_check['view_investment_subscription'];
    $view_investment_transaction = $get_check['view_investment_transaction'];
    $disapprove_withdrawal_request = $get_check['disapprove_withdrawal_request'];
    $approve_withdrawal_request = $get_check['approve_withdrawal_request'];
  ?>  
    <?php echo ($access_investment_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-calculator"></i> <span>Investment Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($view_investment_transaction == 1) ? '<li><a href="notification.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Notification <span class="badge badge-orange right">: '. $myinum_pendinginvestment .' :</span></a></li>' : ''; ?>
    <?php echo ($view_investment_subscription == 1) ? '<li><a href="view_msaving_sub.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Investment Subscription</a></li>' : ''; ?>
    <?php echo ($view_investment_transaction == 1) ? '<li><a href="msavings_trans.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Investment Transaction</a></li>' : ''; ?>
    <?php echo ($disapprove_withdrawal_request == 1 || $approve_withdrawal_request == 1) ? '<li><a href="withdrawal_request.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Withdrawal Request</a></li>' : ''; ?>
    <?php echo ($access_investment_tab == 1) ? '<li><a href="settlement.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("490").'"><i class="fa fa-circle-o"></i> Settlement History</a></li>' : ''; ?>
    <?php echo ($access_investment_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>

<?php
}
else{
    echo "";
}
?>




<?php
if($isubagent_manager === "On" || $istaff_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("409")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_employee_tab = $get_check['access_employee_tab'];
    $add_employee = $get_check['add_employee'];
    $list_employee  = $get_check['list_employee'];
?>    
    <?php echo ($access_employee_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-user"></i> <span>'.(($istaff_manager === "On") ? "Staff Manager" : "Sub-Agent Manager").'</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_employee == 1) ? '<li><a href="newemployee.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("409").'"><i class="fa fa-circle-o"></i>'.(($istaff_manager === "On") ? "Create Staff" : "Create Sub-Agent").'</a></li>' : ''; ?>
    <?php echo ($list_employee == 1) ? '<li><a href="listemployee.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("409").'"><i class="fa fa-circle-o"></i>'.(($istaff_manager === "On") ? "List All Staff" : "List All Sub-Agent").'</a></li>' : ''; ?>
    <?php echo ($access_employee_tab == 1) ? '</ul></li>' : ''; ?> 
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_employee_tab = $get_check['access_employee_tab'];
    $add_employee = $get_check['add_employee'];
    $list_employee  = $get_check['list_employee'];
?>    
    <?php echo ($access_employee_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-user"></i> <span>'.(($istaff_manager === "On") ? "Staff Manager" : "Sub-Agent Manager").'</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_employee == 1) ? '<li><a href="newemployee.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("409").'"><i class="fa fa-circle-o"></i>'.(($istaff_manager === "On") ? "Create Staff" : "Create Sub-Agent").'</a></li>' : ''; ?>
    <?php echo ($list_employee == 1) ? '<li><a href="listemployee.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("409").'"><i class="fa fa-circle-o"></i>'.(($istaff_manager === "On") ? "List All Staff" : "List All Sub-Agent").'</a></li>' : ''; ?>
    <?php echo ($access_employee_tab == 1) ? '</ul></li>' : ''; ?> 
<?php } ?>

<?php
}
else{
    echo "";
}
?>



<?php
if($ivendor_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("901")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_vendor_tab = $get_check['access_vendor_tab'];
    $add_vendor = $get_check['add_vendor'];
    $list_vendor  = $get_check['list_vendor'];
?>    
    <?php echo ($access_vendor_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-gift"></i> <span>Vendor Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_vendor == 1) ? '<li><a href="addvendor.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("901").'"><i class="fa fa-circle-o"></i>Creat Vendor</a></li>' : ''; ?>
    <?php echo ($list_vendor == 1) ? '<li><a href="listvendor.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("901").'"><i class="fa fa-circle-o"></i>List All Vendor</a></li>' : ''; ?>
    <?php echo ($access_vendor_tab == 1) ? '</ul></li>' : ''; ?> 
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_vendor_tab = $get_check['access_vendor_tab'];
    $add_vendor = $get_check['add_vendor'];
    $list_vendor  = $get_check['list_vendor'];
?>    
    <?php echo ($access_vendor_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-gift"></i> <span>Vendor Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_vendor == 1) ? '<li><a href="addvendor.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("901").'"><i class="fa fa-circle-o"></i>Creat Vendor</a></li>' : ''; ?>
    <?php echo ($list_vendor == 1) ? '<li><a href="listvendor.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("901").'"><i class="fa fa-circle-o"></i>List All Vendor</a></li>' : ''; ?>
    <?php echo ($access_vendor_tab == 1) ? '</ul></li>' : ''; ?> 
<?php } ?>

<?php
}
else{
    echo "";
}
?>



<?php
if($ienrolment_manager === "On")//New update added
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("711")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $enrolment_tab = $get_check['enrolment_tab'];
    $add_enrollee = $get_check['add_enrollee'];
    $add_bulk_enrollees = $get_check['add_bulk_enrollees'];
    $all_enrollee_list = $get_check['all_enrollee_list'];
    $individual_enrollee_list = $get_check['individual_enrollee_list'];
    $branch_enrollee_list = $get_check['branch_enrollee_list'];
    $view_all_enrolment_log = $get_check['view_all_enrolment_log'];
    $view_individual_enrolment_log = $get_check['view_individual_enrolment_log'];
    $view_branch_enrolment_log = $get_check['view_branch_enrolment_log'];
?>	
		<?php echo ($enrolment_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-users"></i> <span>Enrollment Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
        <?php echo ($add_enrollee == 1) ? '<li><a href="addEnrollee.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("711").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Add Enrollee</a></li>' : ''; ?>
        <?php echo ($add_bulk_enrollees == 1) ? '<li><a href="addEnrollee.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("711").'&&tab=tab_2"><i class="fa fa-circle-o"></i> Add Bulk Enrollees</a></li>' : ''; ?>
        <?php echo ($all_enrollee_list == 1 || $individual_enrollee_list == 1 || $branch_enrollee_list == 1) ? '<li><a href="enrolleeList.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("711").'"><i class="fa fa-circle-o"></i> All Enrollees</a></li>' : ''; ?>
        <?php echo ($view_all_enrolment_log == 1 || $view_individual_enrolment_log == 1 || $view_branch_enrolment_log == 1) ? '<li><a href="enrolmentLog.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("711").'"><i class="fa fa-circle-o"></i> Activities Log</a></li>' : ''; ?>
        <?php echo ($enrolment_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $enrolment_tab = $get_check['enrolment_tab'];
    $add_enrollee = $get_check['add_enrollee'];
    $add_bulk_enrollees = $get_check['add_bulk_enrollees'];
    $all_enrollee_list = $get_check['all_enrollee_list'];
    $individual_enrollee_list = $get_check['individual_enrollee_list'];
    $branch_enrollee_list = $get_check['branch_enrollee_list'];
    $view_all_enrolment_log = $get_check['view_all_enrolment_log'];
    $view_individual_enrolment_log = $get_check['view_individual_enrolment_log'];
    $view_branch_enrolment_log = $get_check['view_branch_enrolment_log'];
?>	
		<?php echo ($enrolment_tab == 1) ? '<li><a href="#"><i class="fa fa-users"></i> <span>Enrollment Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
        <?php echo ($add_enrollee == 1) ? '<li><a href="addEnrollee.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("711").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Add Enrollee</a></li>' : ''; ?>
        <?php echo ($add_bulk_enrollees == 1) ? '<li><a href="addEnrollee.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("711").'&&tab=tab_2"><i class="fa fa-circle-o"></i> Add Bulk Enrollees</a></li>' : ''; ?>
        <?php echo ($all_enrollee_list == 1 || $individual_enrollee_list == 1 || $branch_enrollee_list == 1) ? '<li><a href="enrolleeList.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("711").'"><i class="fa fa-circle-o"></i> All Enrollees</a></li>' : ''; ?>
        <?php echo ($view_all_enrolment_log == 1 || $view_individual_enrolment_log == 1 || $view_branch_enrolment_log == 1) ? '<li><a href="enrolmentLog.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("711").'"><i class="fa fa-circle-o"></i> Activities Log</a></li>' : ''; ?>
        <?php echo ($enrolment_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>

<?php 
}
else{
    echo "";
}
?>



<?php
if($iverification_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("511")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $identity_verification_tab = $get_check['identity_verification_tab'];
    $verify_identity = $get_check['verify_identity'];
    $all_verification_history = $get_check['all_verification_history'];
    $individual_verification_history = $get_check['individual_verification_history'];
    $branch_verification_history = $get_check['branch_verification_history'];
?>	
		<?php echo ($identity_verification_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-search"></i> <span>Verification Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
        <?php echo ($verify_identity == 1 && $iverification_manager == "On") ? '<li><a href="identifyVerification.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("511").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Verify Identity</a></li>' : ''; ?>
        <?php echo ($all_verification_history == 1 || $individual_verification_history == 1 || $branch_verification_history == 1) ? '<li><a href="verificationHistory.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("511").'"><i class="fa fa-circle-o"></i> Verification History</a></li>' : ''; ?>
        <?php echo ($identity_verification_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $identity_verification_tab = $get_check['identity_verification_tab'];
    $verify_identity = $get_check['verify_identity'];
    $all_verification_history = $get_check['all_verification_history'];
    $individual_verification_history = $get_check['individual_verification_history'];
    $branch_verification_history = $get_check['branch_verification_history'];
?>	
		<?php echo ($identity_verification_tab == 1) ? '<li><a href="#"><i class="fa fa-search"></i> <span>Verification Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
        <?php echo ($verify_identity == 1 && $iverification_manager == "On") ? '<li><a href="identifyVerification.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("511").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Verify Identity</a></li>' : ''; ?>
        <?php echo ($all_verification_history == 1 || $individual_verification_history == 1 || $branch_verification_history == 1) ? '<li><a href="verificationHistory.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("511").'"><i class="fa fa-circle-o"></i> Verification History</a></li>' : ''; ?>
        <?php echo ($identity_verification_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>

<?php 
}
else{
    echo "";
}
?>



<?php
if($iwallet_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("404")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_wallet_tab = $get_check['access_wallet_tab'];
    $sms_marketing = $get_check['sms_marketing'];
    $sms_report = $get_check['sms_report'];
    $transfer_fund == $get_check['transfer_fund'];
    $recharge_airtime_or_data = $get_check['recharge_airtime_or_data'];
    $view_pool_account_history = $get_check['view_pool_account_history'];
    $my_wallet_loan_history = $get_check['my_wallet_loan_history'];
    //WALLET CREATION
    $create_wallet = $get_check['create_wallet'];
    $list_wallet = $get_check['list_wallet'];
    $individual_wallet = $get_check['individual_wallet'];
    $branch_wallet = $get_check['branch_wallet'];
    $create_individual_wallet_only = $get_check['create_individual_wallet_only'];
    $create_agent_wallet_only = $get_check['create_agent_wallet_only'];
    $create_corporate_wallet_only = $get_check['create_corporate_wallet_only'];
    $withdraw_from_wallet = $get_check['withdraw_from_wallet'];
?>	
		<?php echo ($access_wallet_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-briefcase"></i> <span>Wallet Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
        <?php echo (($create_wallet == 1 || $create_individual_wallet_only == 1 || $create_agent_wallet_only == 1 || $create_corporate_wallet_only == 1) && $iwallet_creation == "On") ? '<li><a href="createWallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Create Wallet</a></li>' : ''; ?>
        <?php echo (($list_wallet == 1 || $individual_wallet == 1 || $branch_wallet == 1) && $iwallet_creation == "On") ? '<li><a href="listWallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Manage Wallet</a></li>' : ''; ?>
        <?php echo ($fund_wallet == 1) ? '<li><a href="wallet-towallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Wallet Transfer (Customer)</a></li>' : ''; ?>
        <?php echo ($fund_wallet == 1) ? '<li><a href="wallet-towallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_2"><i class="fa fa-circle-o"></i> Wallet Transfer (Others)</a></li>' : ''; ?>
        <?php echo ($withdraw_from_wallet == 1) ? '<li><a href="withdraw_fromWallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Withdraw from Wallet</a></li>' : ''; ?>
        <?php echo ($transfer_fund == 1) ? '<li><a href="transfer_fund.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Bank Transfer (Single)</a></li>' : ''; ?>
        <?php echo ($transfer_fund == 1) ? '<li><a href="transfer_fund.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_7"><i class="fa fa-circle-o"></i> Bank Transfer (Bulk)</a></li>' : ''; ?>
        <?php echo ($recharge_airtime_or_data == 1) ? '<li><a href="pay_bills.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Buy Airtime</a></li>' : ''; ?>
        <?php echo ($recharge_airtime_or_data == 1) ? '<li><a href="pay_billsdata.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Buy Data</a></li>' : ''; ?>
        <?php echo ($recharge_airtime_or_data == 1) ? '<li><a href="pay_bill1.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Pay Bills</a></li>' : ''; ?>
        <?php echo ($sms_marketing == 1) ? '<li><a href="sms_marketing?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Send SMS </a></li>' : ''; ?>
        <?php echo ($access_wallet_tab == 1) ? '<li><a href="mywallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Wallet History </a></li>' : ''; ?>
        <?php echo ($view_pool_account_history == 1 && $ipool_account == "On") ? '<li><a href="poolAcct_history?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Pool Account History</a></li>' : ''; ?>
        <?php echo ($view_pool_account_history == 1 && $ipool_account == "On") ? '<li><a href="term_pendSettlement.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Pending Settlement <span class="badge badge-orange right">: '. $myinum_pendingTerminalSettlement .' :</span></a></li>' : ''; ?>
        <?php //echo ($transfer_fund == 1) ? '<li><a href="mywallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_2"><i class="fa fa-circle-o"></i> Transfer History </a></li>' : ''; ?>
        <?php echo ($my_wallet_loan_history == '1' && $irole != 'agent_manager' && $irole != 'institution_super_admin' && $irole != 'merchant_super_admin') ? '<li><a href="loanHistory?id='.$_SESSION['tid'].'&&uid='.$iuid.'" target="_blank"><i class="fa fa-circle-o"></i> My Loan Account</a></li>' : ''; ?>
        <?php echo ($iussd_prefixStatus == "Active" && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? '<li><a href="mywallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_3"><i class="fa fa-circle-o"></i> USSD Session History </a></li>' : ''; ?>
        <?php echo ($sms_report == 1) ? '<li><a href="sms_reports?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> SMS Reports </a></li>' : ''; ?>
        <?php echo ($access_wallet_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_wallet_tab = $get_check['access_wallet_tab'];
    $sms_marketing = $get_check['sms_marketing'];
    $sms_report = $get_check['sms_report'];
    $transfer_fund == $get_check['transfer_fund'];
    $recharge_airtime_or_data = $get_check['recharge_airtime_or_data'];
    $view_pool_account_history = $get_check['view_pool_account_history'];
    $pending_terminal_settlement = $get_check['pending_terminal_settlement'];
    $my_wallet_loan_history = $get_check['my_wallet_loan_history'];
    //WALLET CREATION
    $create_wallet = $get_check['create_wallet'];
    $list_wallet = $get_check['list_wallet'];
    $individual_wallet = $get_check['individual_wallet'];
    $branch_wallet = $get_check['branch_wallet'];
    $create_individual_wallet_only = $get_check['create_individual_wallet_only'];
    $create_agent_wallet_only = $get_check['create_agent_wallet_only'];
    $create_corporate_wallet_only = $get_check['create_corporate_wallet_only'];
    $withdraw_from_wallet = $get_check['withdraw_from_wallet'];
?>	
		<?php echo ($access_wallet_tab == 1) ? '<li><a href="#"><i class="fa fa-briefcase"></i> <span>Wallet Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
        <?php echo (($create_wallet == 1 || $create_individual_wallet_only == 1 || $create_agent_wallet_only == 1 || $create_corporate_wallet_only == 1) && $iwallet_creation == "On") ? '<li><a href="createWallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Create Wallet</a></li>' : ''; ?>
        <?php echo (($list_wallet == 1 || $individual_wallet == 1 || $branch_wallet == 1) && $iwallet_creation == "On") ? '<li><a href="listWallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Manage Wallet</a></li>' : ''; ?>
        <?php echo ($fund_wallet == 1) ? '<li><a href="wallet-towallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Wallet Transfer (Customer)</a></li>' : ''; ?>
        <?php echo ($fund_wallet == 1) ? '<li><a href="wallet-towallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_2"><i class="fa fa-circle-o"></i> Wallet Transfer (Others)</a></li>' : ''; ?>
        <?php echo ($withdraw_from_wallet == 1) ? '<li><a href="withdraw_fromWallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Withdraw from Wallet</a></li>' : ''; ?>
        <?php echo ($transfer_fund == 1) ? '<li><a href="transfer_fund.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Bank Transfer (Single)</a></li>' : ''; ?>
        <?php echo ($transfer_fund == 1) ? '<li><a href="transfer_fund.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_7"><i class="fa fa-circle-o"></i> Bank Transfer (Bulk)</a></li>' : ''; ?>
        <?php echo ($recharge_airtime_or_data == 1) ? '<li><a href="pay_bills.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Buy Airtime</a></li>' : ''; ?>
        <?php echo ($recharge_airtime_or_data == 1) ? '<li><a href="pay_billsdata.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Buy Data</a></li>' : ''; ?>
        <?php echo ($recharge_airtime_or_data == 1) ? '<li><a href="pay_bill1.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Pay Bills</a></li>' : ''; ?>
        <?php echo ($sms_marketing == 1) ? '<li><a href="sms_marketing?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Send SMS </a></li>' : ''; ?>
        <?php echo ($access_wallet_tab == 1) ? '<li><a href="mywallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Wallet History </a></li>' : ''; ?>
        <?php echo ($view_pool_account_history == 1 && $ipool_account == "On") ? '<li><a href="poolAcct_history?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Pool Account History</a></li>' : ''; ?>
        <?php echo ($pending_terminal_settlement == 1 && $ipool_account == "On") ? '<li><a href="term_pendSettlement.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Pending Settlement <span class="badge badge-orange right">: '. $myinum_pendingTerminalSettlement .' :</span></a></li>' : ''; ?>
        <?php //echo ($transfer_fund == 1) ? '<li><a href="mywallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_2"><i class="fa fa-circle-o"></i> Transfer History </a></li>' : ''; ?>
        <?php echo ($my_wallet_loan_history == '1' && $irole != 'agent_manager' && $irole != 'institution_super_admin' && $irole != 'merchant_super_admin') ? '<li><a href="loanHistory?id='.$_SESSION['tid'].'&&uid='.$iuid.'&&act='.$ivirtual_acctno.'" target="_blank"><i class="fa fa-circle-o"></i> My Loan Account</a></li>' : ''; ?>
        <?php echo ($iussd_prefixStatus == "Active" && ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin")) ? '<li><a href="mywallet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_3"><i class="fa fa-circle-o"></i> USSD Session History </a></li>' : ''; ?>
        <?php echo ($sms_report == 1) ? '<li><a href="sms_reports?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> SMS Reports </a></li>' : ''; ?>
        <?php echo ($access_wallet_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>

<?php 
}
else{
    echo "";
}
?>



<?php
if($ibvn_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("944")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $bvn_tab = $get_check['bvn_tab'];
    $verify_bvn = $get_check['verify_bvn'];
    $view_all_bvn_verification = $get_check['view_all_bvn_verification'];
    $view_individual_bvn_verification = $get_check['view_individual_bvn_verification'];
    $view_branch_bvn_verification = $get_check['view_branch_bvn_verification'];
?>
    <?php echo ($bvn_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-legal"></i> <span>BVN Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($verify_bvn == 1) ? '<li><a href="bvnValidation.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("944").'&&tab=tab_1"><i class="fa fa-circle-o"></i>Verify BVN</a></li>' : ''; ?>
    <?php echo (($view_all_bvn_verification == 1 || $view_individual_bvn_verification == 1 || $view_branch_bvn_verification == 1)) ? '<li><a href="bvnValidation.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("944").'&&tab=tab_2"><i class="fa fa-circle-o"></i>View BVN Report</a></li>' : ''; ?>
    <?php echo ($bvn_tab == 1) ? '</ul></li>' : ''; ?> 

<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $bvn_tab = $get_check['bvn_tab'];
    $verify_bvn = $get_check['verify_bvn'];
    $view_all_bvn_verification = $get_check['view_all_bvn_verification'];
    $view_individual_bvn_verification = $get_check['view_individual_bvn_verification'];
    $view_branch_bvn_verification = $get_check['view_branch_bvn_verification'];
	?>
	<?php echo ($bvn_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-legal"></i> <span>BVN Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($verify_bvn == 1) ? '<li><a href="bvnValidation.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("944").'&&tab=tab_1"><i class="fa fa-circle-o"></i>Verify BVN</a></li>' : ''; ?>
    <?php echo (($view_all_bvn_verification == 1 || $view_individual_bvn_verification == 1 || $view_branch_bvn_verification == 1)) ? '<li><a href="bvnValidation.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("944").'&&tab=tab_2"><i class="fa fa-circle-o"></i>View BVN Report</a></li>' : ''; ?>
    <?php echo ($bvn_tab == 1) ? '</ul></li>' : ''; ?>

<?php } ?>

<?php
}
else{
    echo "";
}
?>



<?php
if($ipos_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("700")))
{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$pos_tab = $get_check['pos_tab'];
$request_terminal = $get_check['request_terminal'];
$pending_terminal_request = $get_check['pending_terminal_request'];
$all_terminal_assigned = $get_check['all_terminal_assigned'];
$terminal_request_log = $get_check['terminal_request_log'];
$terminal_report = $get_check['terminal_report'];
$esusuPAY_cardless_withdrawal = $get_check['esusuPAY_cardless_withdrawal'];
?>    
    <?php echo ($pos_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-binoculars"></i> <span>Pos Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($esusuPAY_cardless_withdrawal == 1) ? '<li><a href="ussd_cardless.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> '.(($ihalalpay_module === "On") ? "HalalPAY" : "esusuPAY").'</a></li>' : ''; ?>
    <?php echo ($request_terminal == 1) ? '<li><a href="request_terminal.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Request Terminal</a></li>' : ''; ?>
    <?php echo ($pending_terminal_request == 1) ? '<li><a href="pending_terminalReq.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> All Pending Request</a></li>' : ''; ?>
    <?php echo ($all_terminal_assigned == 1) ? '<li><a href="terminal_assigned.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> All Assigned Terminal</a></li>' : ''; ?>
    <?php echo ($terminal_request_log == 1) ? '<li><a href="terminal_reqLog.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Request Log</a></li>' : ''; ?>
    <?php echo ($terminal_report == 1) ? '<li><a href="terminal_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Terminal Report</a></li>' : ''; ?>
    <?php echo ($pos_tab == 1) ? '</ul></li>' : ''; ?> 
<?php
}
else{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$pos_tab = $get_check['pos_tab'];
$request_terminal = $get_check['request_terminal'];
$pending_terminal_request = $get_check['pending_terminal_request'];
$all_terminal_assigned = $get_check['all_terminal_assigned'];
$terminal_request_log = $get_check['terminal_request_log'];
$terminal_report = $get_check['terminal_report'];
$esusuPAY_cardless_withdrawal = $get_check['esusuPAY_cardless_withdrawal'];
?>    
    <?php echo ($pos_tab == 1) ? '<li><a href="#"><i class="fa fa-binoculars"></i> <span>Pos Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($esusuPAY_cardless_withdrawal == 1) ? '<li><a href="ussd_cardless.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> '.(($ihalalpay_module === "On") ? "HalalPAY" : "esusuPAY").'</a></li>' : ''; ?>
    <?php echo ($request_terminal == 1) ? '<li><a href="request_terminal.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Request Terminal</a></li>' : ''; ?>
    <?php echo ($pending_terminal_request == 1) ? '<li><a href="pending_terminalReq.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> All Pending Request</a></li>' : ''; ?>
    <?php echo ($all_terminal_assigned == 1) ? '<li><a href="terminal_assigned.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> All Assigned Terminal</a></li>' : ''; ?>
    <?php echo ($terminal_request_log == 1) ? '<li><a href="terminal_reqLog.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Request Log</a></li>' : ''; ?>
    <?php echo ($terminal_report == 1) ? '<li><a href="terminal_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("700").'"><i class="fa fa-circle-o"></i> Terminal Report</a></li>' : ''; ?>
    <?php echo ($pos_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>

<?php
}
else{
    echo "";
}
?>




<?php
if($iloan_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("405")))
{
$check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_loan_tab = $get_check['access_loan_tab'];
$add_loan = $get_check['add_individual_loan'];
$add_group_loan = $get_check['add_group_loan'];
$add_purchase_loan = $get_check['add_purchase_loan'];
$upload_bulk_loan = $get_check['upload_bulk_loan'];
$view_all_loans = $get_check['view_all_loans'];
$view_due_loans = $get_check['view_due_loans'];
$individual_loan_records = $get_check['individual_loan_records'];
$branch_loan_records = $get_check['branch_loan_records'];
?>  
    <?php echo ($access_loan_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-balance-scale"></i> <span>Loan Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_loan == 1) ? '<li><a href="newloans.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Individual Loans</a></li>' : ''; ?>
    <?php echo ($upload_bulk_loan == 1) ? '<li><a href="bulkloans.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Upload Bulk Loans</a></li>' : ''; ?>
    <?php echo ($add_group_loan == 1) ? '<li><a href="grouploans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Group Loans</a></li>' : ''; ?>
    <?php echo ($add_purchase_loan == 1) ? '<li><a href="purchaseloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Purchased Loans</a></li>' : ''; ?>
    <?php echo (($view_all_loans == 1 || $individual_loan_records == 1 || $branch_loan_records == 1)) ? '<li><a href="listloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'&&tab=tab_1"><i class="fa fa-circle-o"></i> View All Loans</a></li>' : ''; ?>
    <?php echo (($view_all_loans == 1 || $individual_loan_records == 1 || $branch_loan_records == 1) && $iremitaMerchantId != "" && $iremitaApiKey != "" && $iremitaServiceId != "" && $iremitaApiToken != "") ? '<li><a href="allmandate?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> All Mandate <span class="badge badge-orange right" id="mandate_status">: '.number_format($iactivatedDD,0,'',',').' :</span></a></li>' : ''; ?>
    <?php echo ($view_due_loans == 1) ? '<li><a href="dueloans.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Due Loans</a></li>' : ''; ?>
    <?php echo ($access_loan_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_loan_tab = $get_check['access_loan_tab'];
    $add_loan = $get_check['add_individual_loan'];
    $add_group_loan = $get_check['add_group_loan'];
    $add_purchase_loan = $get_check['add_purchase_loan'];
    $upload_bulk_loan = $get_check['upload_bulk_loan'];
    $view_all_loans = $get_check['view_all_loans'];
    $view_due_loans = $get_check['view_due_loans'];
    $individual_loan_records = $get_check['individual_loan_records'];
    $branch_loan_records = $get_check['branch_loan_records'];
?>  
    <?php echo ($access_loan_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-balance-scale"></i> <span>Loan Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_loan == 1) ? '<li><a href="newloans.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Individual Loans</a></li>' : ''; ?>
    <?php echo ($upload_bulk_loan == 1) ? '<li><a href="bulkloans.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Upload Bulk Loans</a></li>' : ''; ?>
    <?php echo ($add_group_loan == 1) ? '<li><a href="grouploans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Group Loans</a></li>' : ''; ?>
    <?php echo ($add_purchase_loan == 1) ? '<li><a href="purchaseloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Purchased Loans</a></li>' : ''; ?>
    <?php echo (($view_all_loans == 1 || $individual_loan_records == 1 || $branch_loan_records == 1)) ? '<li><a href="listloans?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'&&tab=tab_1"><i class="fa fa-circle-o"></i> View All Loans</a></li>' : ''; ?>
    <?php echo (($view_all_loans == 1 || $individual_loan_records == 1 || $branch_loan_records == 1) && $iremitaMerchantId != "" && $iremitaApiKey != "" && $iremitaServiceId != "" && $iremitaApiToken != "") ? '<li><a href="allmandate?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> All Mandate <span class="badge badge-orange right" id="mandate_status">: '.number_format($iactivatedDD,0,'',',').' :</span></a></li>' : ''; ?>
    <?php echo ($view_due_loans == 1) ? '<li><a href="dueloans.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("405").'"><i class="fa fa-circle-o"></i> Due Loans</a></li>' : ''; ?>
    <?php echo ($access_loan_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>

  

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("408")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_loanrepayment_tab = $get_check['access_loanrepayment_tab'];
$remit_cash_payment = $get_check['remit_cash_payment'];
$remit_bulk_cash_payment = $get_check['remit_bulk_cash_payment'];
$list_all_repayment = $get_check['list_all_repayment'];
$list_individual_loan_repayment = $get_check['list_individual_loan_repayment'];
$list_branch_loan_repayment = $get_check['list_branch_loan_repayment'];
?>  
    <?php echo ($access_loanrepayment_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-dollar"></i> <span>Loan Repayments</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($remit_cash_payment == 1) ? '<li><a href="newpayments.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i>Add Payment</a></li>' : ''; ?>
    <?php echo ($remit_bulk_cash_payment == 1) ? '<li><a href="bulkrepmts.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i>Add Bulk Payment</a></li>' : ''; ?>
    <?php echo (($list_all_repayment == 1 || $list_individual_loan_repayment == 1 || $list_branch_loan_repayment == 1)) ? '<li><a href="listpayment.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i>List Repayments</a></li>' : ''; ?>
    <?php echo ($access_loanrepayment_tab == 1) ? '</ul></li>' : ''; ?> 
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_loanrepayment_tab = $get_check['access_loanrepayment_tab'];
$remit_cash_payment = $get_check['remit_cash_payment'];
$remit_bulk_cash_payment = $get_check['remit_bulk_cash_payment'];
$list_all_repayment = $get_check['list_all_repayment'];
$list_individual_loan_repayment = $get_check['list_individual_loan_repayment'];
$list_branch_loan_repayment = $get_check['list_branch_loan_repayment'];
?>  
    <?php echo ($access_loanrepayment_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-dollar"></i> <span>Loan Repayments</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($remit_cash_payment == 1) ? '<li><a href="newpayments.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i>Add Payment</a></li>' : ''; ?>
    <?php echo ($remit_bulk_cash_payment == 1) ? '<li><a href="bulkrepmts.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i>Add Bulk Payment</a></li>' : ''; ?>
    <?php echo (($list_all_repayment == 1 || $list_individual_loan_repayment == 1 || $list_branch_loan_repayment == 1)) ? '<li><a href="listpayment.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i>List Repayments</a></li>' : ''; ?>
    <?php echo ($access_loanrepayment_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>

<?php
}
else{
    echo "";
}
?>




<?php
if($iteller_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("510")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_till_tab = $get_check['access_till_tab'];
    $create_till_account = $get_check['create_till_account'];
    $list_teller  = $get_check['list_teller'];
    $fund_allocation_history  = $get_check['fund_allocation_history'];
    $till_internal_transfer  = $get_check['till_internal_transfer'];
?>    
    <?php echo ($access_till_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-desktop"></i> <span>Teller Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($create_till_account == 1) ? '<li><a href="create_till_acct.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("510").'"><i class="fa fa-circle-o"></i>Create Till Account</a></li>' : ''; ?>
    <?php echo ($till_internal_transfer == 1) ? '<li><a href="internalTransfer.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("510").'"><i class="fa fa-circle-o"></i>Internal Transfer</a></li>' : ''; ?>
    <?php echo ($list_teller == 1) ? '<li><a href="view_teller.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("510").'"><i class="fa fa-circle-o"></i>View All Teller</a></li>' : ''; ?>
    <?php echo ($fund_allocation_history == 1) ? '<li><a href="view_fund_history.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("510").'&&tab=tab_1"><i class="fa fa-circle-o"></i>Till Fund History</a></li>' : ''; ?>
    <?php echo ($access_till_tab == 1) ? '</ul></li>' : ''; ?> 
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_till_tab = $get_check['access_till_tab'];
    $create_till_account = $get_check['create_till_account'];
    $list_teller = $get_check['list_teller'];
    $fund_allocation_history = $get_check['fund_allocation_history'];
    $till_internal_transfer  = $get_check['till_internal_transfer'];
?>    
    <?php echo ($access_till_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-desktop"></i> <span>Teller Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($create_till_account == 1) ? '<li><a href="create_till_acct.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("510").'"><i class="fa fa-circle-o"></i>Create Till Account</a></li>' : ''; ?>
    <?php echo ($till_internal_transfer == 1) ? '<li><a href="internalTransfer.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("510").'"><i class="fa fa-circle-o"></i>Internal Transfer</a></li>' : ''; ?>
    <?php echo ($list_teller == 1) ? '<li><a href="view_teller.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("510").'"><i class="fa fa-circle-o"></i>View All Teller</a></li>' : ''; ?>
    <?php echo ($fund_allocation_history == 1) ? '<li><a href="view_fund_history.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("510").'&&tab=tab_1"><i class="fa fa-circle-o"></i>Till Fund History</a></li>' : ''; ?>
    <?php echo ($access_till_tab == 1) ? '</ul></li>' : ''; ?>  
<?php } ?>

<?php
}
else{
    echo "";
}
?>




<?php
if($icharges_manager === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("520")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $charges_tab = $get_check['charges_tab'];
    $add_charges = $get_check['add_charges'];
    $view_all_charges = $get_check['view_all_charges'];
?>    
    <?php echo ($charges_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-asterisk"></i> <span>Charges Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_charges == 1) ? '<li><a href="add_charges.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("520").'"><i class="fa fa-circle-o"></i>Add Charges</a></li>' : ''; ?>
    <?php echo ($view_all_charges == 1) ? '<li><a href="view_charges.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("520").'"><i class="fa fa-circle-o"></i>View All Charges</a></li>' : ''; ?>
    <?php echo ($charges_tab == 1) ? '</ul></li>' : ''; ?> 
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $charges_tab = $get_check['charges_tab'];
    $add_charges = $get_check['add_charges'];
    $view_all_charges = $get_check['view_all_charges'];
?>    
    <?php echo ($charges_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-asterisk"></i> <span>Charges Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_charges == 1) ? '<li><a href="add_charges.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("520").'"><i class="fa fa-circle-o"></i>Add Charges</a></li>' : ''; ?>
    <?php echo ($view_all_charges == 1) ? '<li><a href="view_charges.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("520").'"><i class="fa fa-circle-o"></i>View All Charges</a></li>' : ''; ?>
    <?php echo ($charges_tab == 1) ? '</ul></li>' : ''; ?> 
<?php } ?>

<?php
}
else{
    echo "";
}
?>




<?php
if($isavings_account === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("410")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_savings_tab = $get_check['access_savings_tab'];
$deposit_money = $get_check['deposit_money'];
$withdraw_money = $get_check['withdraw_money'];
$bulk_savings_upload = $get_check['bulk_savings_upload'];
$view_all_transaction = $get_check['view_all_transaction'];
$request_ledger_withdrawal = $get_check['request_ledger_withdrawal'];
$all_ledger_withdrawal_request = $get_check['all_ledger_withdrawal_request'];
$individual_transaction_records = $get_check['individual_transaction_records'];
$branch_transaction_records = $get_check['branch_transaction_records'];
?>      
    <?php echo ($access_savings_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-money"></i> <span>Savings Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($deposit_money == 1) ? '<li><a href="deposit.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><i class="fa fa-circle-o"></i>Deposit Money</a></li>' : ''; ?>
    <?php echo ($withdraw_money == 1) ? '<li><a href="withdraw.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><i class="fa fa-circle-o"></i>Withdraw Money</a></li>' : ''; ?>
    <?php echo ($bulk_savings_upload == 1) ? '<li><a href="bulksavings.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><i class="fa fa-circle-o"></i>Bulk Savings</a></li>' : ''; ?>
    <?php echo ($view_all_transaction == 1 || $individual_transaction_records == 1 || $branch_transaction_records == 1) ? '<li><a href="transaction.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><i class="fa fa-circle-o"></i>All Transaction</a></li>' : ''; ?>
    <?php echo ($request_ledger_withdrawal == 1) ? '<li><a href="make_wrequest.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><i class="fa fa-circle-o"></i>Withdrawal Request</a></li>' : ''; ?>
    <?php echo ($all_ledger_withdrawal_request == 1) ? '<li><a href="allLedger_wrequest.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><i class="fa fa-circle-o"></i>All Request</a></li>' : ''; ?>
    <?php echo ($access_savings_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_savings_tab = $get_check['access_savings_tab'];
$deposit_money = $get_check['deposit_money'];
$withdraw_money = $get_check['withdraw_money'];
$bulk_savings_upload = $get_check['bulk_savings_upload'];
$view_all_transaction = $get_check['view_all_transaction'];
$request_ledger_withdrawal = $get_check['request_ledger_withdrawal'];
$all_ledger_withdrawal_request = $get_check['all_ledger_withdrawal_request'];
$individual_transaction_records = $get_check['individual_transaction_records'];
$branch_transaction_records = $get_check['branch_transaction_records'];
?>      
    <?php echo ($access_savings_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-money"></i> <span>Savings Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($deposit_money == 1) ? '<li><a href="deposit.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><i class="fa fa-circle-o"></i>Deposit Money</a></li>' : ''; ?>
    <?php echo ($withdraw_money == 1) ? '<li><a href="withdraw.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><i class="fa fa-circle-o"></i>Withdraw Money</a></li>' : ''; ?>
    <?php echo ($bulk_savings_upload == 1) ? '<li><a href="bulksavings.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><i class="fa fa-circle-o"></i>Bulk Savings</a></li>' : ''; ?>
    <?php echo ($view_all_transaction == 1 || $individual_transaction_records == 1 || $branch_transaction_records == 1) ? '<li><a href="transaction.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><i class="fa fa-circle-o"></i>All Transaction</a></li>' : ''; ?>
    <?php echo ($request_ledger_withdrawal == 1) ? '<li><a href="make_wrequest.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><i class="fa fa-circle-o"></i>Withdrawal Request</a></li>' : ''; ?>
    <?php echo ($all_ledger_withdrawal_request == 1) ? '<li><a href="allLedger_wrequest.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><i class="fa fa-circle-o"></i>All Request</a></li>' : ''; ?>
    <?php echo ($access_savings_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>

<?php
}
else{
    echo "";
}
?>


<?php
if($ireports_module === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("425")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_report_tab = $get_check['access_report_tab'];
$borrowers_reports = $get_check['borrowers_reports'];
$collection_reports = $get_check['collection_reports'];
$loan_reports = $get_check['loan_reports'];
$subscription_reports = $get_check['subscription_reports'];
$id_verification_report = $get_check['id_verification_report'];
$enrollees_report = $get_check['enrollees_report'];
?>    
    <?php echo ($access_report_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-area-chart"></i> <span>Reports</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($borrowers_reports == 1 && $iloan_manager == "On") ? '<li><a href="borrower_reports.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Borrower Report</a></li>' : ''; ?>
    <?php echo ($collection_reports == 1 && $iloan_manager == "On") ? '<li><a href="collection_reports.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Loan Repayment Report</a></li>' : ''; ?>
    <?php echo ($loan_reports == 1 && $iloan_manager == "On") ? '<li><a href="loanCollectionSheet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'&&tab=tab_1"><i class="fa fa-circle-o"></i> General Loan Report </a></li>' : ''; ?>
    <?php echo ($view_all_transaction == 1 && $isavings_account == "On") ? '<li><a href="savings_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Normal Savings (R)</a></li>' : ''; ?>
    <?php echo ($id_verification_report == 1 && $ienrolment_manager == "On") ? '<li><a href="idVerification_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> ID Verification Report</a></li>' : ''; ?>
    <?php echo ($enrollees_report == 1 && $ienrolment_manager == "On") ? '<li><a href="enrollees_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Enrollees Report</a></li>' : ''; ?>
    <?php echo (($subscription_reports == 1 && mysqli_num_rows($isearch_maintenance_model) == 0) || ($subscription_reports == 1 && mysqli_num_rows($isearch_maintenance_model) == 1 && $model == "Hybrid")) ? '<li><a href="subscription_reports.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Subscription Report</a></li>' : ''; ?>
    <?php echo ($access_report_tab == 1 && $iinvestment_manager == "On") ? '<li><a href="rsavings_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Investment Report</a></li>' : ''; ?>
    <?php echo ($access_report_tab == 1) ? '<li><a href="financial_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Financial Report</a></li>' : ''; ?>
    <?php echo ($access_report_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_report_tab = $get_check['access_report_tab'];
$borrowers_reports = $get_check['borrowers_reports'];
$collection_reports = $get_check['collection_reports'];
$loan_reports = $get_check['loan_reports'];
$subscription_reports = $get_check['subscription_reports'];
$id_verification_report = $get_check['id_verification_report'];
$enrollees_report = $get_check['enrollees_report'];
?>    
    <?php echo ($access_report_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-area-chart"></i> <span>Reports</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($borrowers_reports == 1 && $iloan_manager == "On") ? '<li><a href="borrower_reports.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Borrower Report</a></li>' : ''; ?>
    <?php echo ($collection_reports == 1 && $iloan_manager == "On") ? '<li><a href="collection_reports.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Loan Repayment Report</a></li>' : ''; ?>
    <?php echo ($loan_reports == 1 && $iloan_manager == "On") ? '<li><a href="loanCollectionSheet.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'&&tab=tab_1"><i class="fa fa-circle-o"></i> General Loan Report </a></li>' : ''; ?>
    <?php echo ($view_all_transaction == 1 && $isavings_account == "On") ? '<li><a href="savings_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Normal Savings (R)</a></li>' : ''; ?>
    <?php echo ($id_verification_report == 1 && $ienrolment_manager == "On") ? '<li><a href="idVerification_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> ID Verification Report</a></li>' : ''; ?>
    <?php echo ($enrollees_report == 1 && $ienrolment_manager == "On") ? '<li><a href="enrollees_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Enrollees Report</a></li>' : ''; ?>
    <?php echo (($subscription_reports == 1 && mysqli_num_rows($isearch_maintenance_model) == 0) || ($subscription_reports == 1 && mysqli_num_rows($isearch_maintenance_model) == 1 && $model == "Hybrid")) ? '<li><a href="subscription_reports.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Subscription Report</a></li>' : ''; ?>
    <?php echo ($access_report_tab == 1 && $iinvestment_manager == "On") ? '<li><a href="rsavings_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Investment Report</a></li>' : ''; ?>
    <?php echo ($access_report_tab == 1) ? '<li><a href="financial_report.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("425").'"><i class="fa fa-circle-o"></i> Financial Report</a></li>' : ''; ?>
    <?php echo ($access_report_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>

<?php
}
else{
    echo "";
}
?>



<?php
/**
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("415")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_notice_tab = $get_check['access_notice_tab'];
?>  
  <?php echo ($access_notice_tab == 1) ? '<li class="active"><a href="newsboard.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("415").'"><i class="fa fa-briefcase"></i> <span>Manage Notice Board</span></a></li>' : ''; ?>
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
  $get_check = mysqli_fetch_array($check);
  $access_notice_tab = $get_check['access_notice_tab'];
  ?>
  <?php echo ($access_notice_tab == 1) ? '<li><a href="newsboard.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("415").'"><i class="fa fa-briefcase"></i> <span>Manage Notice Board</span></a></li>' : ''; ?>
<?php } */ ?>


<?php
/**
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("406")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_helpdesk_tab = $get_check['access_helpdesk_tab'];
    $view_all_tickets = $get_check['view_all_tickets'];
    $close_tickets  = $get_check['close_tickets'];
?>    
    <?php echo ($access_helpdesk_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-envelope-o"></i> <span>Helpdesk Center</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($view_all_tickets == 1) ? '<li><a href="inboxmessage.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("406").'"><i class="fa fa-circle-o"></i>All Tickes</a></li>' : ''; ?>
    <?php echo ($close_tickets == 1) ? '<li><a href="outboxmessage.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("406").'"><i class="fa fa-circle-o"></i>Closed Tickets</a></li>' : ''; ?>
    <?php echo ($access_helpdesk_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_helpdesk_tab = $get_check['access_helpdesk_tab'];
    $view_all_tickets = $get_check['view_all_tickets'];
    $close_tickets  = $get_check['close_tickets'];
?>    
    <?php echo ($access_helpdesk_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-envelope-o"></i> <span>Helpdesk Center</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($view_all_tickets == 1) ? '<li><a href="inboxmessage.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("406").'"><i class="fa fa-circle-o"></i>All Tickes</a></li>' : ''; ?>
    <?php echo ($close_tickets == 1) ? '<li><a href="outboxmessage.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("406").'"><i class="fa fa-circle-o"></i>Closed Tickets</a></li>' : ''; ?>
    <?php echo ($access_helpdesk_tab == 1) ? '</ul></li>' : ''; ?>
<?php } */ ?>




<?php
if($ipayroll_module === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("423")))
{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_payroll_tab = $get_check['access_payroll_tab'];
    $add_payroll = $get_check['add_payroll'];
    $view_payroll  = $get_check['view_payroll'];
?>
    <?php echo ($access_payroll_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-paypal"></i> <span>Payroll</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_payroll == 1) ? '<li><a href="newpayroll.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("423").'"><i class="fa fa-circle-o"></i> Add Payroll</a></li>' : ''; ?>
    <?php echo ($view_payroll == 1) ? '<li><a href="listpayroll.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("423").'"><i class="fa fa-circle-o"></i> View Payroll</a></li>' : ''; ?>
    <?php echo ($access_payroll_tab == 1) ? '</ul></li>' : ''; ?>
<?php
}
else{
    $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $access_payroll_tab = $get_check['access_payroll_tab'];
    $add_payroll = $get_check['add_payroll'];
    $view_payroll  = $get_check['view_payroll'];
?>
    <?php echo ($access_payroll_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-paypal"></i> <span>Payroll</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($add_payroll == 1) ? '<li><a href="newpayroll.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("423").'"><i class="fa fa-circle-o"></i> Add Payroll</a></li>' : ''; ?>
    <?php echo ($view_payroll == 1) ? '<li><a href="listpayroll.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("423").'"><i class="fa fa-circle-o"></i> View Payroll</a></li>' : ''; ?>
    <?php echo ($access_payroll_tab == 1) ? '</ul></li>' : ''; ?>
<?php } ?>

<?php
}
else{
    echo "";
}
?>




<?php
if($iincome_module === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("500")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
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
   $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
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
}
else{
    echo "";
}
?>




<?php
if($iexpenses_module === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("422")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
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
   $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
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
}
else{
    echo "";
}
?>




<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("420")))
{
?>
    <?php echo (($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin" || $irole == "i_a_demo") && (mysqli_num_rows($isearch_maintenance_model) == 0 || $model == "Hybrid")) ? '<li class="treeview active"><a href="#"><i class="fa fa-globe"></i> <span>Manage Subscription</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo (($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin" || $irole == "i_a_demo") && (mysqli_num_rows($isearch_maintenance_model) == 0 || $model == "Hybrid")) ? '<li><a href="make_saas_sub.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> Make Subscription</a></li>' : ''; ?>
    <?php echo (($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin" || $irole == "i_a_demo") && (mysqli_num_rows($isearch_maintenance_model) == 0 || $model == "Hybrid")) ? '<li><a href="upgrade_saas_sub.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> Upgrade Plan</a></li>' : ''; ?>
    <?php echo (($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin" || $irole == "i_a_demo") && (mysqli_num_rows($isearch_maintenance_model) == 0 || $model == "Hybrid")) ? '<li><a href="saassub_history.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> My Subscription History</a></li>' : ''; ?>
    <?php echo (($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin" || $irole == "i_a_demo") && (mysqli_num_rows($isearch_maintenance_model) == 0 || $model == "Hybrid")) ? '</ul></li>' : ''; ?>
<?php
}
else{
  ?>  
    <?php echo (($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin" || $irole == "i_a_demo") && (mysqli_num_rows($isearch_maintenance_model) == 0 || $model == "Hybrid")) ? '<li class="treeview"><a href="#"><i class="fa fa-globe"></i> <span>Manage Subscription</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo (($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin" || $irole == "i_a_demo") && (mysqli_num_rows($isearch_maintenance_model) == 0 || $model == "Hybrid")) ? '<li><a href="make_saas_sub.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> Make Subscription</a></li>' : ''; ?>
    <?php echo (($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin" || $irole == "i_a_demo") && (mysqli_num_rows($isearch_maintenance_model) == 0 || $model == "Hybrid")) ? '<li><a href="upgrade_saas_sub.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> Upgrade Plan</a></li>' : ''; ?>
    <?php echo (($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin" || $irole == "i_a_demo") && (mysqli_num_rows($isearch_maintenance_model) == 0 || $model == "Hybrid")) ? '<li><a href="saassub_history.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><i class="fa fa-circle-o"></i> My Subscription History</a></li>' : ''; ?>
    <?php echo (($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin" || $irole == "i_a_demo") && (mysqli_num_rows($isearch_maintenance_model) == 0 || $model == "Hybrid")) ? '</ul></li>' : ''; ?>
<?php } ?>




<?php
if($igeneral_settings === "On")
{
?>

<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("411")))
{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_settings_tab = $get_check['access_settings_tab'];
$setup_loan_products = $get_check['setup_loan_products'];
$setup_group_loans = $get_check['setup_group_loans'];
$setup_stock = $get_check['setup_stock'];
$setup_account_type = $get_check['setup_account_type'];
$setup_sms_gateway = $get_check['sms_gateway_settings'];
?>    
    <?php echo ($access_settings_tab == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-gear"></i> <span>General Settings</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($setup_sms_gateway == 1 && $idedicated_sms_gateway == "On") ? '<li><a href="sms?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>SMS Gateway Settings</a></li>' : ''; ?>
    <?php echo ($setup_group_loans == 1 && $iloan_manager == "On") ? '<li><a href="list_lgroup?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Setup Group for Loans</a></li>' : ''; ?>
    <?php echo ($setup_loan_products == 1 && $iloan_manager == "On") ? '<li><a href="setuploanprd.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Setup Loan Products</a></li>' : ''; ?>
    <?php echo ($setup_stock == 1 && $iloan_manager == "On") ? '<li><a href="setupstock.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Setup Stock</a></li>' : ''; ?>
    <?php echo ($setup_account_type == 1 && $isavings_account == "On") ? '<li><a href="setupaccttype?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Ledger Savings Setup</a></li>' : ''; ?>
    <?php echo (($irole == "institution_super_admin" || $irole == "merchant_super_admin") && ($iinvestment_manager == "On" || $iproduct_manager == "On")) ? '<li><a href="create_msavingsplan.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Product Setup</a></li>' : ''; ?>
    <?php echo (($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") && ($isavings_account == "On" || $iloan_manager == "On")) ? '<li><a href="collectionSheet.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Collection Sheet</a></li>' : ''; ?>
    <?php echo ($access_settings_tab == 1) ? '</ul></li>' : ''; ?> 
<?php
}
else{
  $check = mysqli_query($link, "SELECT * FROM my_permission WHERE urole = '$irole'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$access_settings_tab = $get_check['access_settings_tab'];
$setup_loan_products = $get_check['setup_loan_products'];
$setup_group_loans = $get_check['setup_group_loans'];
$setup_stock = $get_check['setup_stock'];
$setup_account_type = $get_check['setup_account_type'];
$setup_sms_gateway = $get_check['sms_gateway_settings'];
?>    
    <?php echo ($access_settings_tab == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-gear"></i> <span>General Settings</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($setup_sms_gateway == 1 && $idedicated_sms_gateway == "On") ? '<li><a href="sms?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>SMS Gateway Settings</a></li>' : ''; ?>
    <?php echo ($setup_group_loans == 1 && $iloan_manager == "On") ? '<li><a href="list_lgroup?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Setup Group for Loans</a></li>' : ''; ?>
    <?php echo ($setup_loan_products == 1 && $iloan_manager == "On") ? '<li><a href="setuploanprd.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Setup Loan Products</a></li>' : ''; ?>
    <?php echo ($setup_stock == 1 && $iloan_manager == "On") ? '<li><a href="setupstock.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Setup Stock</a></li>' : ''; ?>
    <?php echo ($setup_account_type == 1 && $isavings_account == "On") ? '<li><a href="setupaccttype?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Ledger Savings Setup</a></li>' : ''; ?>
    <?php echo (($irole == "institution_super_admin" || $irole == "merchant_super_admin") && ($iinvestment_manager == "On" || $iproduct_manager == "On")) ? '<li><a href="create_msavingsplan.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Product Setup</a></li>' : ''; ?>
    <?php echo (($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") && ($isavings_account == "On" || $iloan_manager == "On")) ? '<li><a href="collectionSheet.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><i class="fa fa-circle-o"></i>Collection Sheet</a></li>' : ''; ?>
    <?php echo ($access_settings_tab == 1) ? '</ul></li>' : ''; ?> 
<?php } ?>

<?php
}
else{
    echo "";
}
?>
    
    <li>
          <a href="../logout.php?id=<?php echo $institution_id; ?>">
            <i class="fa fa-sign-out"></i> <span>Logout</span>
          </a>
        </li>

    </section>
    <!-- /.sidebar -->
  </aside>