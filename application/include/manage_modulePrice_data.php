<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>
			 
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr align="center">
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Module Name</th>
				  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM module_pricing ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
?> 
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
				<td><b><?php echo $row['mname']; ?></b></td>
				<td><a href="edit_module.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $id; ?>&&mid=NDEx" class="btn bg-blue">Update</a></td>
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
						echo "<script>window.location='manage_modulePrice.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM module_pricing WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='manage_modulePrice.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
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