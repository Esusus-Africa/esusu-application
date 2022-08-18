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
        Update Loan Application
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA0"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"> <a href="listloans.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA0"> My Loan</a></li>
        <li class="active">Update</li>
      </ol>
    </section>
	
	
    <section class="content">
		<?php include("include/editloan_app_data.php"); ?>
	</section>
</div>	

<?php include("include/footer.php"); ?>

