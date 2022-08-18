<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
<?php
if(isMobileDevice()){
    //Your content or code for mobile devices goes here
?>
    
    <?php echo ($delete_vendor == '1') ? "<button type='submit' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."' name='delete'><i class='fa fa-times'></i>&nbsp;Delete</button>" : ""; ?>
    <?php echo ($add_vendor == '1') ? "<a href='addvendor.php?id=".$_SESSION['tid']."&&mid=".base64_encode("901")."'><button type='button' class='btn btn-flat bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-plus'></i>&nbsp;Create Vendor</button></a>" : ""; ?>

<?php
}
else{
    ?>
    
    <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	<?php echo ($delete_vendor == '1') ? "<button type='submit' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."' name='delete'><i class='fa fa-times'></i>&nbsp;Delete</button>" : ''; ?>
	<?php echo ($add_vendor == '1') ? "<a href='addvendor.php?id=".$_SESSION['tid']."&&mid=".base64_encode("901")."'><button type='button' class='btn btn-flat bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-plus'></i>&nbsp;Create Vendor</button></a>" : ""; ?>

<?php    
}
?>
	
	
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>ID</th>
				  <th>UserType</th>
                  <th>Image</th>
				  <th>Name</th>
                  <th>Username</th>
				  <th>Email</th>
                  <th>Phone Number</th>
                  <th>Actions</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND role = 'vendor_manager'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-".$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color']."'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['userid'];
$utype = $row['utype'];
$name = $row['name'];
$image = $row['image'];
$username = $row['username'];
$email = $row['email']; 
$phone = $row['phone']; 
$branch = $row['branchid'];

//$search_branc = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$branch'");
//$fetch_branc = mysqli_fetch_array($search_branc);
?>    
                <tr>
                <td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                <td><?php echo $row['bprefix'].$id; ?></td>
				<td><?php echo ($utype == 'Registered') ? '<span class="label bg-'.$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color'].'">Registered</span>' : '<span class="label bg-orange">Unregistered</span>'; ?></td>
				<td><img src="<?php echo ($image == '' || $image == 'img/') ? $fetchsys_config['file_baseurl'].'avatar.png' : $image; ?>" width="40" height="40"></td>
				<td><?php echo $name; ?></td>
				<td><?php echo $username; ?></td>				
				<td><?php echo $email; ?></td>
				<td><?php echo $phone; ?></td>
				<td><?php echo ($utype == 'Registered' && $edit_vendor == '1') ? '<a href="view_vendor.php?id='.$id.'&&mid='.base64_encode("901").'"><button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']).'"><i class="fa fa-eye"></i>&nbsp;View</button></a>' : '<span style="color: '.(($myaltrow['alternate_color'] == "") ? 'orange' : $myaltrow['alternate_color']).';">-------</span>'; ?></td>
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
						echo "<script>window.location='listvendor.php?id=".$_SESSION['tid']."&&mid=".base64_encode("901")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$search_v = mysqli_query($link, "SELECT * FROM user WHERE userid ='$id[$i]'");
								$fetch_v = mysqli_fetch_array($search_v);
								$myv_id = $fetch_v['branchid'];
								
								$result - mysqli_query($link, "DELETE FROM vendor_reg WHERE companyid = '$myv_id'");
								$result - mysqli_query($link, "DELETE FROM member_settings WHERE companyid = '$myv_id'");
								$result = mysqli_query($link,"DELETE FROM user WHERE userid ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='listvendor.php?id=".$_SESSION['tid']."&&mid=".base64_encode("901")."'; </script>";
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