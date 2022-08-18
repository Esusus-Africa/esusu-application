<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 
	<?php echo ($delete_department == '1') ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>' : ''; ?>		
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Department Name</th>
                  <th>HOD Email</th>
                  <th>HOD Phone</th>
                  <th>Description</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM dept WHERE companyid = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color']."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo $row['dpt_name']; ?></td>
				<td><?php echo ($row['hod_email'] == "") ? "---" : "---"; ?></td>
				<td><?php echo ($row['hod_phone_no'] == "") ? "---" : "---"; ?></td>
				<td><?php echo $row['dpt_desc']; ?></td>
				<td><?php echo ($edit_department == '1') ? '<a href="edit_dpt.php?id='.$_SESSION['tid'].'&&idm='.$id.'&&mid=OTUw"> <button type="button" class="btn bg-'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).' btn-flat"><i class="fa fa-edit"></i> Edit</button></a>' : ''; ?></td>
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
						echo "<script>window.location='listdpt.php?id=".$_SESSION['tid']."&&mid=".base64_encode("950")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM dept WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='listdpt.php?id=".$_SESSION['tid']."&&mid=".base64_encode("950")."'; </script>";
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