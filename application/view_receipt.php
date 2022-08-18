<?php include("include/header.php"); ?>

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

