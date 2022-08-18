<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat btn-warning"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 <button type="submit" class="btn btn-flat btn-danger" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>	
	<a href="print_cpayment.php" target="_blank" class="btn btn-primary btn-flat"><i class="fa fa-print"></i>&nbsp;Print Payments</a>
	<a href="excel_cpayment.php" target="_blank" class="btn btn-success btn-flat"><i class="fa fa-send"></i>&nbsp;Export Excel</a>
	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>ID</th>
				  <th>Project:</th>
				  <th>Email</th>
                  <th>Amount</th>
				  <th>Dtype</th>
                  <th>Payment Date</th>
				  <th>Payback Deadline <i>(if Lend)</i></th>
				  <th>Customer</th>
                  <th>Status</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM campaign_pay_history ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
	$id = $row['id'];
	$pid = $row['pid'];
	$c_cat = $row['c_id'];
	$dtype = $row['dtype'];
	$amount = $row['amount'];
	$pdate = $row['pdate'];
	$pstatus = $row['pstatus'];
	$search = mysqli_query($link, "SELECT * FROM causes WHERE id = '$c_cat'");
	$get_cause = mysqli_fetch_array($search);

	$search_lpay = mysqli_query($link, "SELECT * FROM campaign_lendpay_history WHERE pid = '$pid'");
	$num = mysqli_num_rows($search_lpay);
	$get_lpay = mysqli_fetch_array($search_lpay);
	$lstatus = $get_lpay['lstatus'];
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo $pid; ?></td>
				<td><?php echo $get_cause['campaign_title']; ?></td>
				<td><?php echo $row['email']; ?></td>
                <td><?php echo number_format($amount,2,".",","); ?></td>
				<td><span class="label label-info"><?php echo $dtype; ?></span></td>
				<td><?php echo date ('F d, Y', strtotime($pdate)); ?></td>
				<td><?php echo ($dtype == 'Lend' && $num == 0 ? date ('F d, Y', strtotime($row['date_to'])).' - <span class="label label-info">Not Paid</span>' : ($dtype == 'Lend' && $lstatus == 'Pending' ? date ('F d, Y', strtotime($row['date_to'])).' - <span class="label label-danger">Pending</span>' : ($dtype == 'Lend' && $lstatus == 'Paid' ? '<span class="label label-success">Paid</span>' : '<span style="color: green">---</span>'))); ?></td>
				<td><?php echo $row['name']; ?></td>
				<td><?php echo ($pstatus == "Pending") ? "<span class='label label-danger'>Cancelled</span>" : "<span class='label label-success'>Completed</span>"; ?></td>
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
						echo "<script>window.location='fundcontributed.php?id=".$_SESSION['tid']."&&mid=".base64_encode("421")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM campaign_pay_history WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='fundcontributed.php?id=".$_SESSION['tid']."&&mid=".base64_encode("421")."'; </script>";
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