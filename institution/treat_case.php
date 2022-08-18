<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
	<!-- Content Header (Page header) -->
    <section class="content-header">
<?php
$id = $_GET['id'];
$search_uintr = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id'") or die ("Error: " . mysqli_error($link));
$fetcg_uintr = mysqli_fetch_object($search_uintr);
?>
      <h1>
        Treat <?php echo $fetcg_uintr->fname; ?>'s Case
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Treat Case</li>
      </ol>
    </section>
	
	
    <section class="content">
		<?php include("include/treat_case_data.php"); ?>
	</section>
</div>	

<?php include("include/footer.php"); ?>