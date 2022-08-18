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
        Due Loans
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
		<li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Due</li>
      </ol>
    </section>
    <section class="content">
		<?php 
    if($arole == "agent_manager")
    {
      include("include/dueloans_data1.php");
    } 
    else{
      include("include/dueloans_data.php");
    }
    ?>
	</section>
</div>	

<?php include("include/footer.php"); ?>