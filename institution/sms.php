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
        <i class="fa fa-gear"></i> Dedicated SMS Gateway Settings
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">SMS Setup</li>
      </ol>
	
<div class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
<?php 
$smscall = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$institution_id'");
$smscallrow = mysqli_fetch_array($smscall);
$smsstatus = $smscallrow['status'];
if($smsstatus == "Activated")
{
?>
<p align="left"><strong>Status: </strong><span class="label bg-blue">Activated</span></p>
<?php 
}
else{
?>
<p align="left"><strong>Status: </strong><span class="label bg-orange">NotActivated</span></p>
<?php
}
?>
		  </p>
        </div>
    </section>
	
  <section class="content">
		<?php include("include/sms_data.php"); ?>
	</section>
</div>			

<?php include("include/footer.php"); ?>