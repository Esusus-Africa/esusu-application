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
        Withdraw Money
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"> <a href="transaction.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("410"); ?>">Transaction</a></li>
        <li class="active">Withdraw</li>
      </ol>
    </section>
	
    <section class="content">
		<?php 
    $verify_role = mysqli_query($link, "SELECT * FROM till_account WHERE cashier = '$session_id'");
    if(mysqli_num_rows($verify_role) == 1){
      include("include/withdraw_data1.php");
    } 
    else{
      include("include/withdraw_data.php");
    }
    ?>
	</section>
</div>

<?php include("include/footer.php"); ?>