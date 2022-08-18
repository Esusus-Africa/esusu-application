<?php 
error_reporting(0); 
include "../config/session.php"; 
?>  
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?php 
$call = mysqli_query($link, "SELECT * FROM systemset");
if(mysqli_num_rows($call) == 0)
{
echo "<script>alert('Data Not Found1!'); </script>";
}
else
{
while($row = mysqli_fetch_assoc($call)){
?>

<link href="../img/<?php echo $row['image']; ?>" rel="icon" type="dist/img">
<?php }}?>
  <?php 
	$call = mysqli_query($link, "SELECT * FROM systemset");
	$row = mysqli_fetch_assoc($call);
	?>
  <title><?php echo $row ['title']?></title>
   <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="../plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="../plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- bootstrap slider -->
  <link rel="stylesheet" href="../plugins/bootstrap-slider/slider.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="../plugins/datepicker/datepicker3.css">
  <!-- bootstrap slider -->
  <link rel="stylesheet" href="../plugins/bootstrap-slider/slider.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  
  <link rel="stylesheet" href="../plugins/select2/select2.min.css">
  
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<link rel="stylesheet" href="../dist/css/style.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../dist/js/calendar.js"></script>
    <strong> <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css"></strong>

<?php
$idet = $_GET['id'];
$select = mysqli_query($link, "SELECT * FROM transaction WHERE id = '$idet'") or die (mysqli_error($link));
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
?>

    <div class="modal-dialog" id="printableArea">
          <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
<legend style="color: blue;"><?php if($row['t_type'] == "Withdraw"){ echo 'Withdraw Receipt | Date/Time: '.$row['date_time']; }elseif($row['t_type'] == "Withdraw-Charges"){ echo 'Withdraw-Charges | Date/Time: '.$row['date_time']; }elseif($row['t_type'] == "Deposit"){ echo 'Deposit Receipt | Date/Time: '.$row['date_time']; }elseif($row['t_type'] == "Transfer"){ echo 'Transfer Receipt | Date/Time: '.$row['date_time']; }elseif($row['t_type'] == "Transfer-Received"){ echo 'In-to-In Transfer Receipt | Date/Time: '.$row['date_time']; } ?></legend>
        </div>
        <div class="modal-body">
<?php
$search = mysqli_query($link, "SELECT * FROM systemset");
$get_searched = mysqli_fetch_array($search);
?>	
			<div align="center" style="color: orange; font-size: 18px;"><h4><strong><?php echo $get_searched['name']; ?></strong></h4></div>
			<hr>
			
			<table id="example1" class="table table-bordered table-striped">
				<tr>
				<td width="130">Transaction ID: </td>
				<th style="color: blue;"><?php echo $row['txid']; ?></th>
				</tr>
                <tr>
				<td width="130">Full Name: </td>
				<th style="color: blue;"><?php echo strtoupper($row['fn']); ?> &nbsp; <?php echo strtoupper($row['ln']); ?></th>
				</tr>
				<tr>
				<td width="150">Transaction Purpose</td>
<th style="color: blue;"><?php if($row['t_type'] == "Withdraw"){ echo 'Withdraw '.$get_searched['currency'].number_format($row['amount'],2,'.',',').'&nbsp;'.'from'.'&nbsp;'.$row['acctno']; }elseif($row['t_type'] == "Withdraw-Charges"){ echo 'Withdrawal Charges of '.$get_searched['currency'].number_format($row['amount'],2,'.',',').'&nbsp;'.'was deducted on '.$row['acctno'].' Account.'; }elseif($row['t_type'] == "Deposit"){ echo 'Deposit '.$get_searched['currency'].number_format($row['amount'],2,'.',',').'&nbsp;'.'to'.'&nbsp;'.$row['acctno']; }elseif($row['t_type'] == "Transfer"){ echo 'Make Transfer of '.$get_searched['currency'].number_format($row['amount'],2,'.',',').'&nbsp;'.'to'.'&nbsp;'.$row['transfer_to']; }elseif($row['t_type'] == "Transfer-Received"){ echo 'Received Payment of '.$get_searched['currency'].number_format($row['amount'],2,'.',',').'&nbsp;'.'from'.'&nbsp;'.$row['transfer_to']; } ?></th>
				</tr>
				<tr><td></td></tr>
				<tr>
				<td width="130">Stamp: </td>
				<th style="color: blue;"><div><?php echo ($get_searched['stamp'] == "") ? 'No Stamp Yet...' : '<img src="../image/'.$get_searched['stamp'].'" width="80" height="80"/>'; ?></div></th>
				</tr>
                <tr>
			</table>
			
			<div class="box-footer">
				<button type="button" onclick="window.print();" class="btn btn-warning pull-right" ><i class="fa fa-print"></i>Print</button>
			</div>
			
        </div>
      </div>    
    </div>
<?php } ?>

