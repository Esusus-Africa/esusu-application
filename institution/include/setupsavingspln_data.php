<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

			 <button type="submit" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>
			 
<a href="addsavingpln.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("411"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-plus"></i>&nbsp;Setup Savings Plan</button></a>
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Plan Code</th>
				  <th>Plan Name</th>
				  <th>Amount</th>
				  <th>Interval</th>
                  <th>Interest Rate</th>
				  <th>Mature Duration</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM savings_plan ORDER BY id") or die (mysqli_error($link));
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
				<td><a href="edit_savingspln.php?id=<?php echo $_SESSION['tid']; ?>&&pl_code=<?php echo $pl_code; ?>&&mid=NDEx"><?php echo $row['plan_code']; ?></a></td>
                <td><?php echo $row['plan']; ?></td>
				<td><?php echo $row['amount']; ?></td>
				<td><?php echo $row['pinterval']; ?></td>
				<td><?php echo $row['interest']; ?>% <i>per annum</i></td>
				<td><?php echo $row['effective_duration'].' months'; ?></td>
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
						echo "<script>window.location='setupsavingspln.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM savings_plan WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='setupsavingspln.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
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