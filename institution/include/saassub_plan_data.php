<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

	<?php echo ($delete_saas_sub_plan == 1) ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
			 
	<?php echo ($setup_saas_sub_plan == 1) ? '<a href="add_saassub_plan.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("420").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-plus"></i>&nbsp;Setup Plan</button></a>' : ''; ?>
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Plan Code</th>
                  <th>Categories</th>
                  <th>Plan Name</th>
				  <th>Amount <p style="font-size: 12px;"> (per months)</p></th>
				  <th>SMS Allocated</th>
				  <th>Limitation</th>
				  <th>Expiration Grace</th>
				  <th>Status</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$select = mysqli_query($link, "SELECT * FROM saas_subscription_plan ORDER BY id DESC") or die (mysqli_error($link));
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
                <td><a href="<?php echo ($update_saas_sub_plan == 1) ? 'edit_saassub_plan.php?id='.$_SESSION['tid'].'&&spid='.$id.'&&mid=NDIw' : '#'; ?>"><b><?php echo $row['plan_code']; ?></b></a></td>
                <td><?php echo $row['plan_category']; ?></td>
				<td><?php echo $row['plan_name']; ?></td>
				<td><b><?php echo  $fetch_sys_settings->currency.number_format($row['amount_per_months'],2,'.',','); ?></b></td>
				<td><b><?php echo number_format($row['sms_allocated'],2,'.',','); ?> SMS</b></td>
				<td>Staff Limit: <p><?php echo ($row['staff_limit'] == "") ? '<b>---------</b>' : '<b>'.$row['staff_limit'].' Staff(s)/Member(s) Allowed</b>'; ?></p>
					Branch Limit: <p><?php echo ($row['branch_limit'] == "") ? '<b>---------</b>' : '<b>'.$row['branch_limit'].' Branches Allowed</b>'; ?></p>
					Customers Limit: <p><?php echo ($row['customers_limit'] == "") ? '<b>---------</b>' : '<b>'.$row['customers_limit'].' Customer(s) Allowed</b>'; ?></p>
				</td>
				<td><?php echo ($row['expiration_grace'] == '') ? '0' : $row['expiration_grace'].' Day(s)'; ?></td>
				<td><?php echo ($row['status'] == "Active") ? "<label class='label bg-blue'>".$row['status']."</label>" : "<label class='label bg-red'>".$row['status']."</label>"; ?></td>
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
						echo "<script>window.location='saassub_plan.php?id=".$_SESSION['tid']."&&mid=NDIw'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM saas_subscription_plan WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='saassub_plan.php?id=".$_SESSION['tid']."&&mid=NDIw'; </script>";
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