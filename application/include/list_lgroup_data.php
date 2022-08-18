<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
    
    <?php echo ($delete_group == 1) ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
			 
<?php echo ($add_group == 1) ? '<a href="setup_lgroup.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("411").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-plus"></i>&nbsp;Setup Group</button></a>' : ''; ?>
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Merchant ID</th>
                  <th>Group Name</th>
                  <th>Maxinum Number of Group Member's</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM lgroup_setup ORDER BY id DESC") or die (mysqli_error($link));
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
				<td><?php echo ($row['merchant_id'] == "") ? "Self" : $row['merchant_id']; ?></td>
                <td><?php echo ($update_group == "1") ? '<a href="edit_lgroup.php?id='.$_SESSION['tid'].'&&idm='.$id.'&&mid=NDEx">'.$row['gname'].'</a>' : $row['gname']; ?></td>
				<td><?php echo $row['max_member']; ?></td>
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
						echo "<script>window.location='list_lgroup.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM lgroup_setup WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='list_lgroup.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
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