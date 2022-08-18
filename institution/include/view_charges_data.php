<div class="row">
    
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> 
	<?php echo ($delete_charges == '1') ? '<button type="submit" class="btn btn-flat bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].'" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>' : ''; ?>
	<?php echo ($direct_charges == '1') ? '<a href="direct_charges.php?id='.$_SESSION['tid'].'&&mid=NTIw"><button type="button" class="btn btn-flat bg-'.$myaltrow['theme_color'].' == "" ? blue : '.$myaltrow['theme_color'].'"><i class="fa fa-asterisk"></i>&nbsp;Apply Direct Charges</button></a>' : ''; ?>		
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>CompanyID</th>
                  <th>Charges Name</th>
				  <th>Charges Type</th>
                  <th>Value / Amount</th>
                  <th>Date/Time</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody>
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM charge_management WHERE companyid = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link));
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
                <td><?php echo ($row['companyid'] == "") ? '-------' : $row['companyid']; ?></td>
				<td><?php echo $row['charges_name']; ?></td>
				<td><?php echo $row['charges_type']; ?></td>
				<td><?php echo ($row['charges_type'] == "Flatrate") ? $row['charges_value'] : $row['charges_value'].'%'; ?></td>
				<td><?php echo $row['date_time']; ?></td>
				<td align="center">
				<div class="btn-group">
                    <div class="btn-group">
                        <button type="button" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat dropdown-toggle" data-toggle="dropdown">
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                        	<?php echo ($edit_charges == '1') ? '<li><p><a href="update_charges.php?id='.$_SESSION['tid'].'&&idm='.$id.'&&mid=NTIw" class="btn btn-default btn-flat"><i class="fa fa-edit"></i> Update Charges</a></p></li>' : ''; ?>
                        </ul>
                      </div>
                </div>
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
						echo "<script>window.location='view_charges.php?id=".$_SESSION['tid']."&&mid=".base64_encode("520")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM charge_management WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='view_charges.php?id=".$_SESSION['tid']."&&mid=".base64_encode("520")."'; </script>";
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