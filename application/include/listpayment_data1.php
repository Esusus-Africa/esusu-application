<div class="row">
		
	       
		     <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 <?php echo ($delete_loan_repayment_records == '1') ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>' : ''; ?>
	<?php echo ($remit_cash_payment == '1') ? '<a href="newpayments.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("408").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-dollar"></i>&nbsp;New Payment</button></a>' : ''; ?>
	<?php echo ($print_loan_repayment_records == '1') ? '<a href="printpayment.php" target="_blank" class="btn bg-orange btn-flat"><i class="fa fa-print"></i>&nbsp;Print Payments</a>' : ''; ?>
	<?php echo ($export_loan_repayment_to_excel == '1') ? '<a href="excelpayment.php" target="_blank" class="btn bg-orange btn-flat"><i class="fa fa-send"></i>&nbsp;Export Excel</a>' : ''; ?>
	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Reference ID</th>
                  <th>Loan ID</th>
                  <th>Account ID</th>
				  <th>Customer Name</th>
				  <th>Loan Balance</th>
				  <th>Amount Payed</th>
                  <th>Date</th>
				  <th>Status</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM payments WHERE branchid = '$session_id'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$refid = $row['refid'];
$lid = $row['lid'];
$tid = $row['tid'];
$account_no = $row['account_no'];
$remarks = $row['remarks'];
$customer = $row['customer'];
$loan_bal = $row['loan_bal'];
$amount_payed = $row['amount_to_pay'];

$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_array($select1);
$currency = $row1['currency']; 
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo $refid; ?></td>
				<td><?php echo $lid; ?></td>
				<td><?php echo $account_no; ?></td>
				<td><?php echo $customer; ?></td>
				<td><?php echo $currency.number_format($loan_bal,2,".",","); ?></td>
				<td><?php echo $currency.number_format($amount_payed,2,".",","); ?></td>
				<td><?php echo $row['pay_date']; ?></td>
				<td><?php echo ($remarks == 'paid') ? '<span class="label bg-blue">Paid</span>' : '<span class="label bg-orange">Pending...</span>'; ?></td>    
			    </tr>
<?php } } ?>
             </tbody>
                </table>  
</form>

					</div>
</div>	
</div>			
</div>	
</div>
			
<?php
						if(isset($_POST['delete'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='listpayment.php?id=".$_SESSION['tid']."&&mid=".base64_encode("408")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM payments WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='listpayment.php?id=".$_SESSION['tid']."&&mid=".base64_encode("408")."'; </script>";
							}
							}
							}
?>			
			
			<div class="box box-info">
            <div class="box-body">
            <div class="alert bg-orange" align="center" class="style2" style="color: #FFFFFF">NUMBER OF REPAYMENT RECEIVED:&nbsp;
			<?php 
			$call3 = mysqli_query($link, "SELECT * FROM payments WHERE branchid = '$session_id'");
			$num3 = mysqli_num_rows($call3);
			?>
			<?php echo $num3; ?> 
			
			</div>
			
			 <div id="chartdiv1"></div>								
			</div>
			</div>
       
