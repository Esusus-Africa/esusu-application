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
    $permide = $_GET['id'];
    $search_perm = mysqli_query($link, "SELECT * FROM my_permission WHERE id = '$permide'");
    $fetchPerm = mysqli_fetch_array($search_perm);
    ?>
      <h1>
        Update <b><?php echo ucfirst(str_replace('_', ' ', $fetchPerm['urole'])); ?></b> - Role
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Permission</li>
      </ol>
    </section>
    <section class="content">
		<?php include("include/edit_cperm_data.php"); ?>
	</section>
</div>	
</div>

<?php include("include/footer.php"); ?>