<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo (isset($_GET['Takaful']) ? 'Set Takaful Plan (1 of 2 Steps)' : (isset($_GET['Health']) ? 'Set Health Plan (1 of 2 Steps)' : (isset($_GET['Donation']) ? 'Set Donation Plan (1 of 2 Steps)' : (isset($_GET['Savings']) ? 'Set Savings Plan (1 of 2 Steps)' : 'Set Investment Plan (1 of 2 Steps)')))); ?>
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo (isset($_GET['Takaful']) ? 'Set Takaful Plan' : (isset($_GET['Health']) ? 'Set Health Plan' : (isset($_GET['Savings']) ? 'Set Savings Plan' : 'Set Investment Plan'))); ?></li>
      </ol>
    </section>
	
    <section class="content">
		<?php include("include/set_savingsplan_data.php"); ?> 
	</section>
</div>

<?php include("include/footer.php"); ?>