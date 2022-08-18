<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
 <?php
 if(isset($_GET['del'])){
     $del = $_GET['del'];
     mysqli_query($link, "DELETE FROM bill_payverification WHERE refid = '$del'");
 }
 ?>
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Pay Bills
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>&&mid=NDAx"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>
	
    <section class="content">
    <?php
    if($ibillpayment == "Rubies"){ //Gateway for Rubies
      include("include/pay_rubiesbpay_data.php");
    }
    elseif($ibillpayment == "PrimeAirtime"){ //Gateway for Prime Airtime
      include("include/pay_primebpay_data.php");
    }
    else{ //Gateway for Estore
      include("include/pay_bill1_data.php");
    }
    ?>
	</section>
</div>

<?php include("include/footer.php"); ?>