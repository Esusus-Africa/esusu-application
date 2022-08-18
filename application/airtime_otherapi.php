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
        Open API Settings
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>&&mid=NDAx"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Setup</li>
      </ol>
    </section>
	
    <section class="content">
		<?php 
    if($arole == "agent_manager" || $arole == "i_a_demo")
    {
      include("include/airtime_otherapi_data1.php"); 
    }
    else{
      include("include/airtime_otherapi_data.php"); 
    }
    ?>
	</section>
</div>

<?php include("include/footer.php"); ?>