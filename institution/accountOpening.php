<?php
include("include/header.php");
require_once "../config/virtualBankAccount_class.php";
?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Open Bank Account (2 of 3 Steps)
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Bank</li>
      </ol>
    </section>
	
    <section class="content">
		<?php include("include/accountOpening_data.php"); ?>
	</section>
</div>

<?php include("include/footer.php"); ?>