<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 <button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>
	<?php echo ($deposit_money == '1') ? '<a href="deposit.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-plus"></i>&nbsp;Make Deposit</button></a>' : ''; ?>
	<?php echo ($withdraw_money == '1') ? '<a href="withdraw.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("410").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-plus"></i>&nbsp;Withdraw Money</button></a>' : ''; ?>
	<?php //echo ($print_transaction == '1') ? '<a href="printtransaction.php" target="_blank" class="btn bg-orange btn-flat"><i class="fa fa-print"></i>&nbsp;Print</a>' : ''; ?>
	<?php //echo ($export_transaction == '1') ? '<a href="transactionexcel.php" target="_blank" class="btn bg-orange btn-flat"><i class="fa fa-send"></i>&nbsp;Export Excel</a>' : ''; ?>	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>TxID</th>
				  <th>T_Type</th>
				  <th>AcctNo.</th>
                  <th>Transfer to/from</th>
                  <th>Phone</th>
                  <th>Amount</th>
				  <th>Date/Time</th>
				  <th>Posted By</th>
                  <th>Actions</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM transaction WHERE branchid = '$session_id' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
	$select2 = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE branchid = '$session_id' ORDER BY id DESC") or die (mysqli_error($link));
	$get_select2 = mysqli_fetch_array($select2);
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$txid = $row['txid'];
$t_type = $row['t_type'];
$acctno = $row['acctno'];
$transfer_to = $row['transfer_to'];
$ph = $row['phone'];
$amt = $row['amount'];
$dt = $row['date_time'];
$posted_by = $row['posted_by'];

$query = mysqli_query($link, "SELECT * FROM systemset");
$get_query = mysqli_fetch_array($query);
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
				<td><?php echo $row ['txid'];?></td>
				<td><?php echo $row ['t_type'];?></td>
                <td><?php echo $acctno; ?></td>
				<td align="center"><?php echo ($row['t_type'] == "Transfer") ? "<i class='fa  fa-mail-reply'></i>&nbsp;".$transfer_to : ($row['t_type'] == "Transfer-Received") ? "<i class='fa  fa-mail-forward'></i>&nbsp;".$transfer_to : $transfer_to; ?></td>
                <td><?php echo $ph; ?></td>
				<td><?php echo $get_query['currency'].number_format($amt,2,'.',','); ?></td>
				<td><?php echo $dt; ?></td>
				<td><?php echo $row['fname']; ?></td>
				<td align="center"><a href="#myModal <?php echo $id; ?>"> <button type="button" class="btn bg-blue btn-flat" data-target="#myModal<?php echo $id; ?>" data-toggle="modal"><i class="fa fa-print"></i> Receipt</button></a></td>
				</tr>
<?php 
}
?>

				<tfoot>
				    <tr>
						<td></td>
<?php
$select3 = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE branchid = '$session_id' AND t_type = 'Withdraw' ORDER BY id DESC") or die (mysqli_error($link));
while($row3 = mysqli_fetch_array($select3))
{
?>
						<td><b style="font-size: 15px;" align="right">TOTAL DEPOSIT=</b></td>
						<td><b><?php echo $get_query['currency'].number_format($row3['SUM(amount)'],2,'.',','); ?><b></td>
<?php } ?>
<?php
$select4 = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE branchid = '$session_id' AND t_type = 'Deposit' ORDER BY id DESC") or die (mysqli_error($link));
while($row4 = mysqli_fetch_array($select4))
{
?>
						<td><b style="font-size: 15px;" align="right">TOTAL DEPOSIT=</b></td>
						<td><b><?php echo $get_query['currency'].number_format($row4['SUM(amount)'],2,'.',','); ?><b></td>
<?php } ?>
						<td><b style="font-size: 15px;" align="right">OVERALL TOTAL=</b></td>
						<td><b><?php echo $get_query['currency'].number_format($get_select2['SUM(amount)'],2,'.',','); ?><b></td>
				    </tr>
				  </tfoot>
<?php } ?>
             </tbody>
                </table>  
<?php
						if(isset($_POST['delete'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='listborrowers.php?id=".$_SESSION['tid']."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$search_trans = mysqli_query($link, "SELECT * FROM transaction WHERE id ='$id[$i]'");
								while($get_trans = mysqli_fetch_object($search_trans))
								{
									$bal = $get_trans->amount;
									$acn = $get_trans->acctno;

									$search_borrower = mysqli_query($link, "SELECT * FROM borrowers WHERE account ='$acn'");
									$get_borrower = mysqli_fetch_object($search_borrower);
									$balance = $get_borrower->balance;
									$newbalance = $balance - $bal;
			
									$update = mysqli_query($link, "UPDATE borrowers SET balance = '$newbalance' WHERE account ='$acn'");
									$result = mysqli_query($link,"DELETE FROM transaction WHERE id ='$id[$i]'");
									echo "<script>alert('Row Delete Successfully!!!'); </script>";
									echo "<script>window.location='transaction.php?id=".$_SESSION['tid']."'; </script>";
								}
							}
							}
							}
?>			
</form>
				

              </div>

	
</div>	
</div>
</div>	


<!--
			<div class="box box-info">
            <div class="box-body">
            <div class="alert bg-orange" align="center" class="style2" style="color: #FFFFFF">NUMBER OF TRANSACTION:&nbsp;
			<?php 
			$call3 = mysqli_query($link, "SELECT * FROM transaction WHERE branchid = '$session_id'");
			$num3 = mysqli_num_rows($call3);
			?>
			<?php echo $num3; ?> 
			
			</div>
			
			  <div class="col-md-6">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Total Withdrawal Charges</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
    <?php
	$add=mysqli_query($link,'SELECT SUM(amount) from transaction WHERE branchid = "$session_id" AND t_type = "Withdraw-Charges"') or die ("Error: " . mysqli_error($link));
  	while($row1= mysqli_fetch_array($add))
  	{
    $mark=$row1['SUM(amount)'];
 	?>
				
	<h4><b><?php echo $get_query['currency'].number_format($mark,2,'.',','); ?></b></h4>
	<?php }?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
		  
		  <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Total Amount Transfered</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
    <?php
	$add=mysqli_query($link,'SELECT SUM(amount) from transaction WHERE branchid = "$session_id" AND t_type = "Transfer"') or die ("Error: " . mysqli_error($link));
  	while($row1= mysqli_fetch_array($add))
  	{
    $mark=$row1['SUM(amount)'];
 	?>
				
	<h4><b><?php echo $get_query['currency'].number_format($mark,2,'.',','); ?></b></h4>
	<?php }?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
 
		<div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Total Unpaid Loan</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
    <?php
	$add=mysqli_query($link,'SELECT SUM(amount_topay) from loan_info WHERE p_status = "UNPAID" AND branchid = "$session_id"') or die ("Error: " . mysqli_error($link));
  	while($row1= mysqli_fetch_array($add))
  	{
    $mark=$row1['SUM(amount_topay)'];
 	?>
				
	<h4><b><?php echo $get_query['currency'].number_format($mark,2,'.',','); ?></b></h4>
	<?php }?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
			
			<div class="col-sm-6">	
			 <div id="chartdiv2"></div>	
			</div>
			
			</div>
			</div>
-->
			
</div>