<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar1.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-gear"></i>&nbsp;Make Software Subscription (1 of 3 Steps)
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">SMS Setup</li>
      </ol>
    </section>
	
  <section class="content">
		<?php include("include/make_saas_sub_data.php"); ?>
	</section>
</div>			

<?php include("include/footer.php"); ?>