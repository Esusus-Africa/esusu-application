<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat btn-warning"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 <button type="submit" class="btn btn-flat btn-danger" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>
	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
				  <th><input type="checkbox" id="select_all"/></th>
                  <th>Funder Name</th>
				  <th>Email</th>
                  <th>Company Name</th>
				  <th>Amount Contributed</th>
				  <th>DType</th>
				  <th>Payment Date</th>
				  <th>Payback Date</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM causes WHERE campaign_status = 'Approved' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$c_id = $row['id'];
$search = mysqli_query($link, "SELECT * FROM campaign_pay_history WHERE c_id = '$c_id' AND pstatus = 'Completed' ORDER BY id DESC");
while($get_funder = mysqli_fetch_array($search))
{
	$pid = $get_funder['pid'];
	$donor_email = $get_funder['email'];
	$search_borrowers = mysqli_query($link, "SELECT * FROM borrowers WHERE email = '$donor_email'");
	$get_borrowers = mysqli_fetch_array($search_borrowers);
	
	$search_lendpay = mysqli_query($link, "SELECT * FROM campaign_lendpay_history WHERE pid = '$pid'");
	$get_lendpay = mysqli_fetch_array($search_lendpay);
	$lendpay = $get_lendpay['lstatus'];
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $get_funder['id']; ?>"></td>
				<td><?php echo $get_funder['name']; ?></td>
				<td><?php echo $get_funder['email']; ?></td>
				<td><span style="color: green;"><?php echo $get_borrowers['bizname']; ?></span></td>
				<td><span style="color: green;"><b><?php echo $get_funder['amount']; ?></b></span></td>
				<td><span class="label label-info"><?php echo $get_funder['dtype']; ?></span></td>
				<td><?php echo date ('F d, Y', strtotime($get_funder['pdate'])); ?></td>
				<td><?php echo ($get_funder['dtype'] == 'Lend' && $lendpay == 'Pending' || $get_funder['dtype'] == 'Lend' && $lendpay == '' ? date ('F d, Y', strtotime($get_funder['date_to'])).'<br>'.'<span class="label label-danger">Unpaid</span>' : ($get_funder['dtype'] == 'Lend' && $lendpay == 'Paid' ? '<span class="label label-success">Paid</span>' : '<span style="color: green">---</span>')); ?></td>
			</tr>
<?php } } } ?>
             </tbody>
                </table>  
			
<?php
						if(isset($_POST['delete'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='funders_list.php?id=".$_SESSION['tid']."&&mid=".base64_encode("421")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM campaign_pay_history WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='funders_list.php?id=".$_SESSION['tid']."&&mid=".base64_encode("421")."'; </script>";
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