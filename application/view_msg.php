<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
<?php
if(isset($_GET['id'])){
	$id = $_GET['id'];
	$update_msg = mysqli_query($link, "UPDATE message SET mstatus = 'Opened' WHERE id = '$id'");
}
?>
    
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Reply Tickets
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"> <a href="inboxmessage.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode('406'); ?>">All Tickets</a></li>
        <li class="active">Reply</li>
      </ol>
    </section>
    <section class="content">
		<?php include("include/view_msg_data.php"); ?>
	</section>
</div>	

<?php include("include/footer.php"); ?>