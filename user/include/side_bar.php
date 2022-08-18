<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
		<?php 
			$id = $_SESSION['tid'];
			$call = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id'");
            $row = mysqli_fetch_assoc($call);
			?>
			
			<img src="<?php echo ($row['image'] == '' || $row['image'] == 'img/') ? $fetchsys_config['file_baseurl'].'image-placeholder.jpg' : $fetchsys_config['file_baseurl'].$row['image']; ?>" class="img-circle" alt="User Image" width="64" height="64">

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
		<li class="active"><a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
<?php
}
else{
	?>
		<li><a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
<?php } ?>


<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("407")))
{
?>	
	<?php echo ($iinvestment_manager == "On") ? '<li class="treeview active"><a href="#"><i class="fa fa-diamond"></i> <span> Investment </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
 	<?php echo ($iinvestment_manager == "On") ? '<li><a href="set_savingsplan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("407").'"><i class="fa fa-circle-o"></i> Browse Investment </a></li>' : ''; ?>
    <?php echo ($iinvestment_manager == "On") ? '<li><a href="my_savings_plan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("407").'"><i class="fa fa-circle-o"></i> All Investment </a></li>' : ''; ?>
    <?php echo ($iinvestment_manager == "On") ? '</ul></li>' : ''; ?>
<?php
}
else{
?>	
	<?php echo ($iinvestment_manager == "On") ? '<li><a href="#"><i class="fa fa-diamond"></i> <span> Investment </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
 	<?php echo ($iinvestment_manager == "On") ? '<li><a href="set_savingsplan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("407").'"><i class="fa fa-circle-o"></i> Browse Investment </a></li>' : ''; ?>
    <?php echo ($iinvestment_manager == "On") ? '<li><a href="my_savings_plan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("407").'"><i class="fa fa-circle-o"></i> All Investment </a></li>' : ''; ?>
    <?php echo ($iinvestment_manager == "On") ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("500")))
{
?>	
	<?php echo ($tsavings_subacct != "" && $ts_roi_type != "" && $ts_roi != "" && $iproduct_manager == "On") ? '<li class="treeview active"><a href="#"><i class="fa fa-balance-scale"></i> <span> Savings </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($tsavings_subacct != "" && $ts_roi_type != "" && $ts_roi != "" && $iproduct_manager == "On") ? '<li><a href="add_targetsavings.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("500").'&&Savings"><i class="fa fa-circle-o"></i> Set Target Savings </a></li>' : ''; ?>
    <?php echo ($tsavings_subacct != "" && $ts_roi_type != "" && $ts_roi != "" && $iproduct_manager == "On") ? '<li><a href="set_savingsplan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("500").'&&Savings"><i class="fa fa-circle-o"></i> Start Saving </a></li>' : ''; ?>
    <?php echo ($tsavings_subacct != "" && $ts_roi_type != "" && $ts_roi != "" && $iproduct_manager == "On") ? '<li><a href="my_savings_plan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("500").'&&Savings"><i class="fa fa-circle-o"></i> All Savings </a></li>' : ''; ?>
    <?php echo ($tsavings_subacct != "" && $ts_roi_type != "" && $ts_roi != "" && $iproduct_manager == "On") ? '</ul></li>' : ''; ?>
<?php
}
else{
?>	
	<?php echo ($tsavings_subacct != "" && $ts_roi_type != "" && $ts_roi != "" && $iproduct_manager == "On") ? '<li class="treeview"><a href="#"><i class="fa fa-balance-scale"></i> <span> Savings </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($tsavings_subacct != "" && $ts_roi_type != "" && $ts_roi != "" && $iproduct_manager == "On") ? '<li><a href="add_targetsavings.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("500").'&&Savings"><i class="fa fa-circle-o"></i> Set Target Savings </a></li>' : ''; ?>
    <?php echo ($tsavings_subacct != "" && $ts_roi_type != "" && $ts_roi != "" && $iproduct_manager == "On") ? '<li><a href="set_savingsplan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("500").'&&Savings"><i class="fa fa-circle-o"></i> Start Saving </a></li>' : ''; ?>
    <?php echo ($tsavings_subacct != "" && $ts_roi_type != "" && $ts_roi != "" && $iproduct_manager == "On") ? '<li><a href="my_savings_plan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("500").'&&Savings"><i class="fa fa-circle-o"></i> All Savings </a></li>' : ''; ?>
    <?php echo ($tsavings_subacct != "" && $ts_roi_type != "" && $ts_roi != "" && $iproduct_manager == "On") ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("504")))
{
?>	
	<?php echo ($iinvestment_manager == "On" && $itakafulmenu == "Enabled") ? '<li class="treeview active"><a href="#"><i class="fa fa-gg"></i> <span> Takaful </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
 	<?php echo ($iinvestment_manager == "On" && $itakafulmenu == "Enabled") ? '<li><a href="set_savingsplan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("504").'&&Takaful"><i class="fa fa-circle-o"></i> Browse Takaful </a></li>' : ''; ?>
    <?php echo ($iinvestment_manager == "On" && $itakafulmenu == "Enabled") ? '<li><a href="my_savings_plan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("504").'&&Takaful"><i class="fa fa-circle-o"></i> All Takaful </a></li>' : ''; ?>
    <?php echo ($iinvestment_manager == "On" && $itakafulmenu == "Enabled") ? '</ul></li>' : ''; ?>
<?php
}
else{
?>	
		<?php echo ($iinvestment_manager == "On" && $itakafulmenu == "Enabled") ? '<li><a href="#"><i class="fa fa-gg"></i> <span> Takaful </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
 		<?php echo ($iinvestment_manager == "On" && $itakafulmenu == "Enabled") ? '<li><a href="set_savingsplan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("504").'&&Takaful"><i class="fa fa-circle-o"></i> Browse Takaful </a></li>' : ''; ?>
    <?php echo ($iinvestment_manager == "On" && $itakafulmenu == "Enabled") ? '<li><a href="my_savings_plan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("504").'&&Takaful"><i class="fa fa-circle-o"></i> All Takaful </a></li>' : ''; ?>
    <?php echo ($iinvestment_manager == "On" && $itakafulmenu == "Enabled") ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("604")))
{
?>	
	<?php echo ($idonation_manager == "On") ? '<li class="treeview active"><a href="#"><i class="fa fa-gg-circle"></i> <span> Donation </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
 	<?php echo ($idonation_manager == "On") ? '<li><a href="set_savingsplan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("604").'&&Donation"><i class="fa fa-circle-o"></i> Browse Donation </a></li>' : ''; ?>
    <?php echo ($idonation_manager == "On") ? '<li><a href="my_savings_plan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("604").'&&Donation"><i class="fa fa-circle-o"></i> All Donation </a></li>' : ''; ?>
    <?php echo ($idonation_manager == "On") ? '</ul></li>' : ''; ?>
<?php
}
else{
?>	
	<?php echo ($idonation_manager == "On") ? '<li><a href="#"><i class="fa fa-gg-circle"></i> <span> Donation </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
 	<?php echo ($idonation_manager == "On") ? '<li><a href="set_savingsplan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("604").'&&Donation"><i class="fa fa-circle-o"></i> Browse Donation </a></li>' : ''; ?>
    <?php echo ($idonation_manager == "On") ? '<li><a href="my_savings_plan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("604").'&&Donation"><i class="fa fa-circle-o"></i> All Donation </a></li>' : ''; ?>
    <?php echo ($idonation_manager == "On") ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("404")))
{
$lid = 'LID-'.date("dmy").time();
?>	
		<?php echo ($iloan_manager == "On") ? '<li class="treeview active"><a href="#"><i class="fa fa-sliders"></i> <span> Loans </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
 		<?php echo ($iloan_manager == "On") ? '<li><a href="newloans.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("404").'&&lid='.$lid.'&&tab=tab_1"><i class="fa fa-circle-o"></i> Loan Application </a></li>' : ''; ?>
        <?php echo ($iloan_manager == "On") ? '<li><a href="listloans.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Loan History </a></li>' : ''; ?>
		<?php echo ($iloan_manager == "On") ? '<li><a href="payloans.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Make Repayment </a></li>' : ''; ?>
		<?php echo ($iloan_manager == "On") ? '<li><a href="list_payloans.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Repayment History </a></li>' : ''; ?>
        <?php echo ($iloan_manager == "On") ? '</ul></li>' : ''; ?>
<?php
}
else{
$lid = 'LID-'.date("dmY").time();
?>	
		<?php echo ($iloan_manager == "On") ? '<li class="treeview"><a href="#"><i class="fa fa-sliders"></i> <span> Loans </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
 		<?php echo ($iloan_manager == "On") ? '<li><a href="newloans.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("404").'&&lid='.$lid.'&&tab=tab_1"><i class="fa fa-circle-o"></i> Loan Application </a></li>' : ''; ?>
        <?php echo ($iloan_manager == "On") ? '<li><a href="listloans.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Loan History </a></li>' : ''; ?>
		<?php echo ($iloan_manager == "On") ? '<li><a href="payloans.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Make Repayment </a></li>' : ''; ?>
		<?php echo ($iloan_manager == "On") ? '<li><a href="list_payloans.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("404").'"><i class="fa fa-circle-o"></i> Repayment History </a></li>' : ''; ?>
        <?php echo ($iloan_manager == "On") ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("1000")))
{
?>	
	<?php echo ($iinvestment_manager == "On" && $ihealthmenu == "Enabled") ? '<li class="treeview active"><a href="#"><i class="fa fa-heartbeat"></i> <span> Health </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
 	<?php echo ($iinvestment_manager == "On" && $ihealthmenu == "Enabled") ? '<li><a href="set_savingsplan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("1000").'&&Health"><i class="fa fa-circle-o"></i> Browse HMO </a></li>' : ''; ?>
    <?php echo ($iinvestment_manager == "On" && $ihealthmenu == "Enabled") ? '<li><a href="my_savings_plan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("1000").'&&Health"><i class="fa fa-circle-o"></i> All HMO </a></li>' : ''; ?>
    <?php echo ($iinvestment_manager == "On" && $ihealthmenu == "Enabled") ? '</ul></li>' : ''; ?>
<?php
}
else{
?>	
	<?php echo ($iinvestment_manager == "On" && $ihealthmenu == "Enabled") ? '<li><a href="#"><i class="fa fa-heartbeat"></i> <span> Health </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
 	<?php echo ($iinvestment_manager == "On" && $ihealthmenu == "Enabled") ? '<li><a href="set_savingsplan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("1000").'&&Health"><i class="fa fa-circle-o"></i> Browse HMO </a></li>' : ''; ?>
    <?php echo ($iinvestment_manager == "On" && $ihealthmenu == "Enabled") ? '<li><a href="my_savings_plan.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("1000").'&&Health"><i class="fa fa-circle-o"></i> All HMO </a></li>' : ''; ?>
    <?php echo ($iinvestment_manager == "On" && $ihealthmenu == "Enabled") ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("120")))
{
?>
	<?php echo ($bgroupcontribution == "On") ? '<li class="treeview active"><a href="#"><i class="fa fa-users"></i> <span> Group </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
 	<?php echo ($bgroupcontribution == "On") ? '<li><a href="creategroup.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("120").'"><i class="fa fa-circle-o"></i> Create Group </a></li>' : ''; ?>
    <?php echo ($bgroupcontribution == "On") ? '<li><a href="#joingroup.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("120").'"><i class="fa fa-circle-o"></i> Join Group </a></li>' : ''; ?>
    <?php echo ($bgroupcontribution == "On") ? '<li><a href="#mygroup.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("120").'"><i class="fa fa-circle-o"></i> My Group </a></li>' : ''; ?>
    <?php echo ($bgroupcontribution == "On") ? '</ul></li>' : ''; ?>
<?php
}
else{
?>	
	<?php echo ($bgroupcontribution == "On") ? '<li><a href="#"><i class="fa fa-users"></i> <span> Group </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
    <?php echo ($bgroupcontribution == "On") ? '<li><a href="creategroup.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("120").'"><i class="fa fa-circle-o"></i> Create Group </a></li>' : ''; ?>
    <?php echo ($bgroupcontribution == "On") ? '<li><a href="#joingroup.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("120").'"><i class="fa fa-circle-o"></i> Join Group </a></li>' : ''; ?>
    <?php echo ($bgroupcontribution == "On") ? '<li><a href="#mygroup.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("120").'"><i class="fa fa-circle-o"></i> My Group </a></li>' : ''; ?>
    <?php echo ($bgroupcontribution == "On") ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("408")))
{
?>	
		<?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li class="treeview active"><a href="#"><i class="fa fa-briefcase"></i> <span> Wallet </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
		<?php //echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="setupQR.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i> Setup QR Code </a></li>' : ''; ?>
        <?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="wallet-towallet.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'&&tab=tab_2"><i class="fa fa-circle-o"></i> Wallet Transfer </a></li>' : ''; ?>
		<?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="transfer.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Bank Transfer </a></li>' : ''; ?>
		<?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="pay_bills.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i> Buy Airtime </a></li>' : ''; ?>
		<?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="pay_billsdata.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i> Buy Data </a></li>' : ''; ?>
		<?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="pay_bill1.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i> Pay Bills </a></li>' : ''; ?>
		<?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="sms_marketing.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i> Send SMS </a></li>' : ''; ?>
 		<?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="mywallet_history.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Wallet History </a></li>' : ''; ?>
    <?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="sms_reports.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i> SMS Reports </a></li>' : ''; ?>
    <?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '</ul></li>' : ''; ?>
<?php
}
else{
?>	
		<?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="#"><i class="fa fa-briefcase"></i> <span> Wallet </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
        <?php //echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="setupQR.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i> Setup QR Code </a></li>' : ''; ?>
 		<?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="wallet-towallet.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'&&tab=tab_2"><i class="fa fa-circle-o"></i> Wallet Transfer </a></li>' : ''; ?>
		<?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="transfer.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Bank Transfer </a></li>' : ''; ?>
		<?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="pay_bills.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i> Buy Airtime </a></li>' : ''; ?>
		<?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="pay_billsdata.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i> Buy Data </a></li>' : ''; ?>
		<?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="pay_bill1.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i> Pay Bills </a></li>' : ''; ?>
		<?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="sms_marketing.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i> Send SMS </a></li>' : ''; ?>
 		<?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="mywallet_history.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'&&tab=tab_1"><i class="fa fa-circle-o"></i> Wallet History </a></li>' : ''; ?>
 		<?php //echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="mywallet_history.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'&&tab=tab_2"><i class="fa fa-circle-o"></i> Transfer History </a></li>' : ''; ?>
    <?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '<li><a href="sms_reports.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("408").'"><i class="fa fa-circle-o"></i> SMS Reports </a></li>' : ''; ?>
    <?php echo ($iwallet_manager == "On" || $bbranchid == "") ? '</ul></li>' : ''; ?>
<?php } ?>


<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("900")))
{
?>
    <li class="treeview active"><a href="#"><i class="fa fa-credit-card"></i> <span> Card </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">
    <li><a href="create_card.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("900"); ?>&&tab=tab_1"> <span>Create Virtual Card</span></a></li>
    <li><a href="allcard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("900"); ?>"> <span>All Virtual Card</span></a></li>
	<?php echo ($issurer == "Mastercard" && $card_id != "NULL") ? '<li><a href="cardlesswith.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("900").'"> <span>Cardless ATM Withdrawal</span></a></li>' : ''; ?>
    <?php echo ($issurer == "Mastercard" && $card_id != "NULL") ? '<li><a href="card-towallet.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("900").'&&tab=tab_1"> <span>Card-to-Wallet Transfer</span></a></li>' : ''; ?>
    <?php echo ($issurer == "Mastercard" && $card_id != "NULL") ? '<li><a href="transfer.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("900").'&&tab=tab_10"> <span>Transfer to Mastercard</span></a></li>' : ''; ?>
    <?php echo ($issurer == "VerveCard" && $card_id != "NULL") ? '<li><a href="transfer.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("900").'&&tab=tab_11"> <span>Transfer to VerveCard</span></a></li>' : ''; ?>
    <?php echo ($issurer == "VerveCard" && $card_id == "NULL") ? '<li><a href="link_verveCard.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("900").'"> <span>Link Vervecard</span></a></li>' : ''; ?>
    <?php echo ($issurer == "Mastercard" && $card_id != "NULL") ? '<li><a href="cardreports.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("900").'"> <span>Mastercard Reports</span></a></li>' : ''; ?>
    <?php echo ($issurer == "VerveCard" && $card_id != "NULL") ? '<li><a href="cardreports1.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("900").'"> <span>VerveCard Reports</span></a></li>' : ''; ?>
    </ul></li>
<?php
}
else{
	?>
	<li class="treeview"><a href="#"><i class="fa fa-credit-card"></i> <span> Card </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">
    <li><a href="create_card.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("900"); ?>&&tab=tab_1"> <span>Create Virtual Card</span></a></li>
    <li><a href="allcard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("900"); ?>"> <span>All Virtual Card</span></a></li>
	<?php echo ($issurer == "Mastercard" && $card_id != "NULL") ? '<li><a href="cardlesswith.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("900").'"> <span>Cardless ATM Withdrawal</span></a></li>' : ''; ?>
    <?php echo ($issurer == "Mastercard" && $card_id != "NULL") ? '<li><a href="card-towallet.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("900").'&&tab=tab_1"> <span>Card-to-Wallet Transfer</span></a></li>' : ''; ?>
    <?php echo ($issurer == "Mastercard" && $card_id != "NULL") ? '<li><a href="transfer.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("900").'&&tab=tab_10"> <span>Transfer to Mastercard</span></a></li>' : ''; ?>
    <?php echo ($issurer == "VerveCard" && $card_id != "NULL") ? '<li><a href="transfer.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("900").'&&tab=tab_11"> <span>Transfer to VerveCard</span></a></li>' : ''; ?>
    <?php echo ($issurer == "VerveCard" && $card_id == "NULL") ? '<li><a href="link_verveCard.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("900").'"> <span>Link Vervecard</span></a></li>' : ''; ?>
    <?php echo ($issurer == "Mastercard" && $card_id != "NULL") ? '<li><a href="cardreports.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("900").'"> <span>Mastercard Reports</span></a></li>' : ''; ?>
    <?php echo ($issurer == "VerveCard" && $card_id != "NULL") ? '<li><a href="cardreports1.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("900").'"> <span>VerveCard Reports</span></a></li>' : ''; ?>
    </ul></li>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("600")))
{
?>	
		<?php echo ($iinvestment_manager == "On" || $iproduct_manager == "On") ? '<li class="treeview active"><a href="#"><i class="fa fa-sort-amount-desc"></i> <span> Withdraw </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
 		<?php echo ($iinvestment_manager == "On" || $iproduct_manager == "On") ? '<li><a href="make_wrequest.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("600").'"><i class="fa fa-circle-o"></i> Make Withdrawal </a></li>' : ''; ?>
        <?php echo ($iinvestment_manager == "On" || $iproduct_manager == "On") ? '<li><a href="all_wrequest.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("600").'"><i class="fa fa-circle-o"></i> All Withdrawal Request </a></li>' : ''; ?>
        <?php echo ($iinvestment_manager == "On" || $iproduct_manager == "On") ? '</ul></li>' : ''; ?>
<?php
}
else{
?>	
		<?php echo ($iinvestment_manager == "On" || $iproduct_manager == "On") ? '<li><a href="#"><i class="fa fa-sort-amount-desc"></i> <span> Withdraw </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
 		<?php echo ($iinvestment_manager == "On" || $iproduct_manager == "On") ? '<li><a href="make_wrequest.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("600").'"><i class="fa fa-circle-o"></i> Make Withdrawal </a></li>' : ''; ?>
        <?php echo ($iinvestment_manager == "On" || $iproduct_manager == "On") ? '<li><a href="all_wrequest.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("600").'"><i class="fa fa-circle-o"></i> All Withdrawal Request </a></li>' : ''; ?>
        <?php echo ($iinvestment_manager == "On" || $iproduct_manager == "On") ? '</ul></li>' : ''; ?>
<?php } ?>



<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("507")))
{
?>
		<?php echo ($iinvestment_manager == "On" || $bbranchid == "") ? '<li class="active"><a href="all_savingstrans.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("507").'"><i class="fa fa-line-chart"></i> <span>Report</span></a></li>' : ''; ?>
<?php
}
else{
	?>
		<?php echo ($iinvestment_manager == "On" || $bbranchid == "") ? '<li><a href="all_savingstrans.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("507").'"><i class="fa fa-line-chart"></i> <span>Report</span></a></li>' : ''; ?>
<?php } ?>


<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("403")))
{
?>
		<?php echo ($isavings_account == "On") ? '<li class="active"><a href="transaction.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("403").'"><i class="fa fa-database"></i> <span>Ledger</span></a></li>' : ''; ?>
<?php
}
else{
	?>
		<?php echo ($isavings_account == "On") ? '<li><a href="transaction.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("403").'"><i class="fa fa-database"></i> <span>Ledger</span></a></li>' : ''; ?>
<?php } ?>


<?php
if(isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("950")))
{
?>
		<li class="active"><a href="docManager.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("950"); ?>&&tab=tab_1"><i class="fa fa-newspaper-o"></i> <span> Profile</span></a></li>
<?php
}
else{
	?>
		<li><a href="docManager.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("950"); ?>&&tab=tab_1"><i class="fa fa-newspaper-o"></i> <span> Profile</span></a></li>
<?php } ?>

		
		<li>
          <a href="../logout.php<?php echo ($bbranchid == '') ? '' : '?id='.$bbranchid; ?>">
            <i class="fa fa-sign-out"></i> <span>Logout</span>
          </a>
        </li>
		

    </section>
    <!-- /.sidebar -->
  </aside>