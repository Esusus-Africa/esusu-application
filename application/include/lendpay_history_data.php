<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat btn-warning"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 <button type="submit" class="btn btn-flat btn-danger" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>	
	<a href="print_lpayment.php" target="_blank" class="btn btn-primary btn-flat"><i class="fa fa-print"></i>&nbsp;Print Payments</a>
	<a href="excel_lpayment.php" target="_blank" class="btn btn-success btn-flat"><i class="fa fa-send"></i>&nbsp;Export Excel</a>
	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>LPID</th>
				  <th>Payer</th>
				  <th>CPID</th>
				  <th>Project:</th>
				  <th>Lender Name</th>
				  <th>Lender Email</th>
                  <th>Amount</th>
                  <th>Payment Date</th>
				  <th>Payback Deadline</th>
                  <th>Status</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM campaign_lendpay_history ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
	$id = $row['id'];
	$payer_tid = $row['tid'];
	$lpay_id = $row['lpay_id'];
	$pid = $row['pid'];
	$c_id  = $row['c_id'];
	$amount = $row['amount'];
	$pdate = $row['pdate'];
	$expected_pdate = $row['expected_pdate'];
	$lstatus = $row['lstatus'];
	$disbursed_status = $row['disbursed_status'];
	$search = mysqli_query($link, "SELECT * FROM causes WHERE id = '$c_id'");
	$get_cause = mysqli_fetch_array($search);
	
	$search_payer = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$payer_tid'");
	$get_payer = mysqli_fetch_array($search_payer);

	$search_cpay = mysqli_query($link, "SELECT * FROM campaign_pay_history WHERE pid = '$pid' AND pstatus = 'Completed'");
	$num = mysqli_num_rows($search_cpay);
	$get_cpay = mysqli_fetch_array($search_cpay);
	$pstatus = $get_cpay['pstatus'];
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo $payer_tid; ?></td>
				<td><?php echo $get_payer['fname'].' '.$get_payer['lname']; ?></td>
				<td><?php echo $pid; ?></td>
				<td><?php echo $get_cause['campaign_title']; ?></td>
				<td><?php echo $row['lender_name']; ?></td>
				<td><?php echo $row['lender_email']; ?></td>
                <td><?php echo number_format($amount,2,".",","); ?></td>
				<td><?php echo date ('F d, Y', strtotime($pdate)); ?></td>
				<td><?php echo date ('F d, Y', strtotime($expected_pdate)); ?></td>
				<td><?php echo ($lstatus == 'Pending') ? '<span class="label label-danger">Pending</span>' : '<span class="label label-success">Paid</span>'; ?></td>
			    </tr>
<?php } } ?>
             </tbody>
                </table>  
			
<?php
						if(isset($_POST['delete'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='lendpay_history.php?id=".$_SESSION['tid']."&&mid=".base64_encode("421")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM campaign_lendpay_history WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='lendpay_history.php?id=".$_SESSION['tid']."&&mid=".base64_encode("421")."'; </script>";
							}
							}
							}
?>			
				
</form>
                </div>

				</div>	
				</div>
			
</div>		
       
</div>