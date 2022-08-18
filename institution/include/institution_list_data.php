<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 <button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>
	<a href="add_instpermission.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("413"); ?>"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-cogs"></i>&nbsp;Add Permission</button></a>
	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>User Name</th>
                  <th>Actions</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT DISTINCT(staff_tid), id FROM institution_module_permission") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$tid = $row['staff_tid'];
$getin = mysqli_query($link, "SELECT d_name FROM institution_user WHERE id = '$tid'") or die (mysqli_error($link));
$have = mysqli_fetch_array($getin);
$name = $have['d_name'];
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
				<td><?php echo $name; ?></td>
                <td>
				<a rel="tooltip" title="View" href="view_instperm.php?id=<?php echo $tid;?>&&mid=<?php echo base64_encode("413"); ?>"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-eye"></i></button></a>
				<a rel="tooltip" title="Update" href="edit_instperm.php?id=<?php echo $tid;?>&&mid=<?php echo base64_encode("413"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-edit"></i></button></a>
			</td>		    
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
						echo "<script>window.location='permission_list.php?id=".$_SESSION['tid']."&&mid=".base64_encode("413")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM staff_module_permission WHERE id ='$id[$i]'");
								echo "<script>alert('Staff Permission Deleted Successfully!!!'); </script>";
								echo "<script>window.location='permission_list.php?id=".$_SESSION['tid']."&&mid=".base64_encode("413")."'; </script>";
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