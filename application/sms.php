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
        <i class="fa fa-gear"></i>&nbsp;SMS Settings
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">SMS Setup</li>
      </ol>
  
<div class="alert bg-blue">
          <p class="fa fa-hand-o-right">&nbsp;&nbsp;Update SMS Gateway Settings Here</p><br>
<?php
if($arole == "agent_manager" || $arole == "i_a_demo")
{
?>
      <?php 
$call = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$session_id'");
if(mysqli_num_rows($call) == 0)
{
echo "<p align='left'><strong>Status: </strong><span class='label bg-orange'><b>Not Regsistered!</b> Contact our Support to Setup your SMS</span></p>";
}
else
{
while($row = mysqli_fetch_assoc($call)){
$status = $row['status'];
if($status == "NotActivated")
{
?>
<p align="left"><strong>Status:&nbsp;</strong><span class="label bg-orange"><?php echo $row['status']; ?></span></p>
<?php 
}
elseif($status == "Activated")
{
?>
<p align="left"><strong>Status:&nbsp;</strong><span class="label bg-orange"><?php echo $row['status']; ?></span></p>
<?php
}
}
}
?>
<?php
}
else{
?>

<?php 
$call = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = ''");
if(mysqli_num_rows($call) == 0)
{
echo "<p align='left'><strong>Status: </strong><span class='label bg-orange'><b>Not Regsistered!</b> Contact our Support to Setup your SMS</span></p>";
}
else
{
while($row = mysqli_fetch_assoc($call)){
$status = $row['status'];
if($status == "NotActivated")
{
?>
<p align="left"><strong>Status:&nbsp;</strong><span class="label bg-orange"><?php echo $row['status']; ?></span></p>
<?php 
}
elseif($status == "Activated")
{
?>
<p align="left"><strong>Status:&nbsp;</strong><span class="label bg-orange"><?php echo $row['status']; ?></span></p>
<?php
}
}
}
?>
<?php } ?>
      </p>
        </div>
    </section>
  
  <section class="content">
  <?php 
    if($arole == "agent_manager" || $arole == "i_a_demo")
    {
      include("include/sms_data1.php");
    }else{
      include("include/sms_data.php");
    }

    ?>
  </section>
</div>      

<?php include("include/footer.php"); ?>