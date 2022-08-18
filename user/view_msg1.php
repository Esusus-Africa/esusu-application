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
       Reply Tickets (Closed)
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?tid=&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDAx"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reply (Closed)</li>
      </ol>
    </section>
    <section class="content">
		<?php include("include/view_msg1_data.php"); ?>
	</section>
</div>	

<?php include("include/footer.php"); ?>