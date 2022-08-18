<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

	<?php echo ($update_compensation_plan == 1) ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
			 
	<?php echo ($set_referral_plan == 1) ? '<a href="add_compensation_plan.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("416").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-plus"></i>&nbsp;Setup Compensation Plan</button></a>' : ''; ?>
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Plan Level</th>
				  <th>Percentage</th>
				  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM compensation_plan ORDER BY id") or die (mysqli_error($link));
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
                <td>Level <?php echo $row['plan_level']; ?></td>
				<td><?php echo $row['percentage']; ?>%</td>
				<td><?php echo ($update_compensation_plan == 1) ? '<a href="edit_compensation_plan.php?id='.$_SESSION['tid'].'&&plid='.$id.'&&mid=NDE2">Edit</a>' : '-----'; ?></td>
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
						echo "<script>window.location='setuploanprd.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM compensation_plan WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='compensation_plan.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
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