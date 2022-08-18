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
          <img src="<?php echo ($row['image'] == "" || $row['image'] == "img/") ? $fetchsys_config['file_baseurl'].'image-placeholder.jpg' : $fetchsys_config['file_baseurl'].$row['image']; ?>" class="img-circle" alt="User Image" width="64" height="64">
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
        <li class="header">MAIN NAVIGATION</li>
<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("401")))
{
?>
    <li class="active"><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
<?php
}
else{
  ?>
    <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
<?php } ?>  


<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("440")))
{
?>
    <li class="treeview active"><a href="#"><i class="fa fa-male"></i> <span>Client Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">
    <li><a href="add_agent?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("440"); ?>&&tab=tab_1"><i class="fa fa-circle-o"></i> Add Client</a></li>
    <li><a href="listagents?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("440"); ?>"><i class="fa fa-circle-o"></i> List Clients</a></li>
    </ul></li>
<?php
}
else{
  ?>  
    <li class="treeview"><a href="#"><i class="fa fa-male"></i> <span>Client Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">
    <li><a href="add_agent?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("440"); ?>&&tab=tab_1"><i class="fa fa-circle-o"></i> Add Client</a></li>
    <li><a href="listagents?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("440"); ?>"><i class="fa fa-circle-o"></i> List Clients</a></li>
    </ul></li>
<?php } ?>


<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("404")))
{
?>	
		<li class="treeview active"><a href="#"><i class="fa fa-briefcase"></i> <span>Wallet Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">
    <li><a href="createWallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>&&tab=tab_1"><i class="fa fa-circle-o"></i> Create Wallet</a></li>
    <li><a href="listWallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>&&tab=tab_1"><i class="fa fa-circle-o"></i> Manage Wallet</a></li>
    <li><a href="wallet-towallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>&&tab=tab_2"><i class="fa fa-circle-o"></i> Wallet Transfer</a></li>
    <li><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>&&tab=tab_1"><i class="fa fa-circle-o"></i> Bank Transfer</a></li>
    <li><a href="pay_bills.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>"><i class="fa fa-circle-o"></i> Buy Airtime</a></li>
    <li><a href="pay_billsdata.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>"><i class="fa fa-circle-o"></i> Buy Data</a></li>
    <li><a href="pay_bill1.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>"><i class="fa fa-circle-o"></i> Pay Bills</a></li>
    <li><a href="sms_marketing?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>"><i class="fa fa-circle-o"></i> Send SMS </a></li>
    <li><a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>&&tab=tab_1"><i class="fa fa-circle-o"></i> Wallet History </a></li>
    <li><a href="sms_reports?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>"><i class="fa fa-circle-o"></i> SMS Reports </a></li>
    </ul></li>
<?php
}
else{
?>	
		<li><a href="#"><i class="fa fa-briefcase"></i> <span>Wallet Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">
    <li><a href="createWallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>&&tab=tab_1"><i class="fa fa-circle-o"></i> Create Wallet</a></li>
    <li><a href="listWallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>&&tab=tab_1"><i class="fa fa-circle-o"></i> Manage Wallet</a></li>
    <li><a href="wallet-towallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>&&tab=tab_2"><i class="fa fa-circle-o"></i> Wallet Transfer</a></li>
    <li><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>&&tab=tab_1"><i class="fa fa-circle-o"></i> Bank Transfer</a></li>
    <li><a href="pay_bills.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>"><i class="fa fa-circle-o"></i> Buy Airtime</a></li>
    <li><a href="pay_billsdata.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>"><i class="fa fa-circle-o"></i> Buy Data</a></li>
    <li><a href="pay_bill1.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>"><i class="fa fa-circle-o"></i> Pay Bills</a></li>
    <li><a href="sms_marketing?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>"><i class="fa fa-circle-o"></i> Send SMS </a></li>
    <li><a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>&&tab=tab_1"><i class="fa fa-circle-o"></i> Wallet History </a></li>
    <li><a href="sms_reports?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>"><i class="fa fa-circle-o"></i> SMS Reports </a></li>
    </ul></li>
<?php } ?>


<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("700")))
{
?>    
    <li class="treeview active"><a href="#"><i class="fa fa-binoculars"></i> <span>Pos Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">
      <li><a href="request_terminal.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("700"); ?>"><i class="fa fa-circle-o"></i> Request Terminal</a></li>
      <li><a href="pending_terminalReq.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("700"); ?>"><i class="fa fa-circle-o"></i> All Pending Request</a></li>
      <li><a href="terminal_assigned.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("700"); ?>"><i class="fa fa-circle-o"></i> All Assigned Terminal</a></li>
    </ul></li>
<?php
}
else{
?>    
    <li><a href="#"><i class="fa fa-binoculars"></i> <span>Pos Manager</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">
      <li><a href="request_terminal.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("700"); ?>"><i class="fa fa-circle-o"></i> Request Terminal</a></li>
      <li><a href="pending_terminalReq.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("700"); ?>"><i class="fa fa-circle-o"></i> All Pending Request</a></li>
      <li><a href="terminal_assigned.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("700"); ?>"><i class="fa fa-circle-o"></i> All Assigned Terminal</a></li>
    </ul></li>
<?php } ?>
    
    <li>
      <a href="../logout.php"><i class="fa fa-sign-out"></i> <span>Logout</span></a>      
    </li>
    

    </section>
    <!-- /.sidebar -->
  </aside>