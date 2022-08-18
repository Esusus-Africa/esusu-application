<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<?php

$search_doc = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$acctno'");
$fetch_docnum = mysqli_num_rows($search_doc);

/*$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acctno'");
$fetch_customer = mysqli_fetch_array($search_customer);
$my_bbvno = $fetch_customer['unumber'];*/

if($fetch_docnum >= 1){
    
    echo "";
    
}
else{
    
    echo "<script>alert('Oops!...You are to update your profile before applying for loan'); </script>";
    echo "<script>window.location='docManager.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=".base64_encode("950")."'; </script>";
    
}
?>
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Loan Application
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA0"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"> <a href="listloans.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=NDA0"> My Loan</a></li>
        <li class="active">Add</li>
      </ol>
    </section>
	
	
    <section class="content">
		<?php include("include/newloans_data.php"); ?>
	</section>
</div>	

<?php include("include/footer.php"); ?>

