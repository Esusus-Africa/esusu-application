<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

	<?php echo ($delete_saas_sub_transaction == 1) ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>

	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>CoopID / InstID</th>
                  <th>RefID</th>
                  <th>Unique ID <p style="font-size: 12px;" align="center"> (Subcription Token)</p></th>
                  <th>Plan Code</th>
				  <th>Amount Paid <p style="font-size: 12px;"> (Total)</p></th>
				  <th>Units Allocated <p style="font-size: 12px;"> (for SMS Sending)</p></th>
				  <th>Expired Date</th>
				  <th>Trans. Date</th>
				  <th>Status</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$select = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE status = 'Pending' ORDER BY id") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];

$system_settings = mysqli_query($link, "SELECT * FROM systemset");
$fetch_sys_settings = mysqli_fetch_object($system_settings);
?> 
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
                <td><b><?php echo $row['coopid_instid']; ?></b></td>
				<td>------------</td>
				<td><b>------------</b></td>
				<td><?php echo $row['plan_code']; ?></td>
				<td><?php echo  $fetch_sys_settings->currency.number_format($row['amount_paid'],2,'.',','); ?></td>
				<td><?php echo number_format($row['sms_allocated'],2,'.',','); ?> Units</td>
				<td><b><?php echo $row['duration_to']; ?></b></td>
				<td><?php echo $row['transaction_date']; ?></td>
				<td><?php echo "<label class='label bg-red'>".$row['status']."</label>"; ?></td>
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
						echo "<script>window.location='pending_sub.php?id=".$_SESSION['tid']."&&mid=NDIw'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM saas_subscription_trans WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='pending_sub.php?id=".$_SESSION['tid']."&&mid=NDIw'; </script>";
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