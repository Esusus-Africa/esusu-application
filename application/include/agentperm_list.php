<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	 <button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>
	<a href="add_agentperm.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("413"); ?>"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-cogs"></i>&nbsp;Add Permission</button></a>
	
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
$search_agent = mysqli_query($link, "SELECT * FROM agent_data ORDER BY id");
while($get_agent = mysqli_fetch_array($search_agent))
{
    $agentid = $get_agent['agentid'];
$select = mysqli_query($link, "SELECT * FROM staff_module_permission WHERE staff_tid = '$agentid'") or die (mysqli_error($link));
    while($row = mysqli_fetch_array($select))
    {
    $id = $row['id'];
    $tid = $row['staff_tid'];
    $getin = mysqli_query($link, "SELECT fname FROM agent_data WHERE agentid = '$tid'") or die (mysqli_error($link));
    $have = mysqli_fetch_array($getin);
    $name = $have['fname'];
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
				<td><?php echo $name; ?></td>
                <td>
				<a rel="tooltip" title="View" href="view_agentperm.php?id=<?php echo $tid;?>&&mid=<?php echo base64_encode("413"); ?>"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-eye"></i></button></a>
				<a rel="tooltip" title="Update" href="edit_agentperm.php?id=<?php echo $tid;?>&&mid=<?php echo base64_encode("413"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-edit"></i></button></a>
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
						echo "<script>window.location='agentperm.php?id=".$_SESSION['tid']."&&mid=".base64_encode("413")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM staff_module_permission WHERE id ='$id[$i]'");
								echo "<script>alert('Staff Permission Deleted Successfully!!!'); </script>";
								echo "<script>window.location='agentperm.php?id=".$_SESSION['tid']."&&mid=".base64_encode("413")."'; </script>";
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