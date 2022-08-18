<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

			 <?php echo ($delete_msavings_plan == 1) ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
			 
<a href="addsavingpln.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("490"); ?>"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-plus"></i>&nbsp;Setup Esusu Scheme</button></a>
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Plan Code</th>
                  <th>Merchant ID</th>
				  <th>Plan Name</th>
				  <th>Categories</th>
				  <th>Amount</th>
				  <th>Savings Interval</th>
				  <th>Disbursement Interval</th>
                  <th>Interest/Dividend</th>
				  <th>Lock Period</th>
				  <th>Created By</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM savings_plan") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$pl_code = $row['plan_code'];
?> 
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
				<td><a href="#edit_savingspln.php?id=<?php echo $_SESSION['tid']; ?>&&pl_code=<?php echo $pl_code; ?>&&mid=NDEx"><?php echo $row['plan_code']; ?></a></td>
                <td><?php echo $row['merchantid_others']; ?></td>
                <td><?php echo $row['plan_name']; ?></td>
                <td><?php echo $row['categories']; ?></td>
				<td><?php echo $row['amount']; ?></td>
				<td><?php echo $row['savings_interval']; ?></td>
				<td><?php echo $row['disbursement_interval']; ?></td>
				<td><?php echo $row['dividend']; ?></td>
				<td><?php echo $row['lock_period'].' months'; ?></td>
				<td><?php echo ($row['created_by'] == '') ? 'self' : $row['created_by']; ?></td>
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
						echo "<script>window.location='create_msavingsplan.php?id=".$_SESSION['tid']."&&mid=NDkw'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM savings_plan WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='create_msavingsplan.php?id=".$_SESSION['tid']."&&mid=NDkw'; </script>";
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