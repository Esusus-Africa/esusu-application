<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 <button type="submit" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>	
	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>WID</th>
                  <th>Status</th>
				  <th>Details</th>
                  <th>Total Amount</th>
				  <th>Interest Rate</th>
                  <th>Balance (Total Amount - Interest)</th>
                  <th>Date</th>
				  <th>Action</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM withdrawal_request ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$wid = $row['wid'];
$w_details = $row['w_details'];
$w_amount = $row['w_amount'];
$w_date = $row['w_date'];
$wstatus = $row['wstatus'];
$interest_rate = $row['interest_rate'];
$amt_withdrawable = $row['amt_withdrawable'];
$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
while($row1 = mysqli_fetch_array($select1))
{
$currency = $row1['currency']; 
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo $wid; ?></td>
				<td><span class="label label-<?php echo ($wstatus == 'Approved' ? 'success' : ($wstatus == 'Declined' ? 'info' : 'danger')); ?>"><?php echo $wstatus; ?></span></td>
				<td><?php echo $w_details; ?></td>
                <td><?php echo $currency.number_format($w_amount,2,".",","); ?></td>
				<td><span style="color: red;"><?php echo $interest_rate; ?>%</span></td>
				<td><span style="color: green;"><?php echo $amt_withdrawable; ?></span></td>
				<td><?php echo date ('F d, Y', strtotime($w_date)); ?></td>
				<td><a href="#myModal <?php echo $id; ?>"> <button type="button" class="btn btn-primary btn-flat" data-target="#myModal <?php echo $id; ?>" data-toggle="modal"><i class="fa fa-eye"></i></button></a></td>
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
						echo "<script>window.location='cwithdrawalrequest.php?id=".$_SESSION['tid']."&&mid=".base64_encode("421")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM withdrawal_request WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='cwithdrawalrequest.php?id=".$_SESSION['tid']."&&mid=".base64_encode("421")."'; </script>";
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