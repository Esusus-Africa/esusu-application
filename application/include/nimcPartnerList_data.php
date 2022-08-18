<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

	<?php echo ($delete_nimc_partner == '1') ? "<button type='submit' class='btn btn-flat bg-orange' name='delete'><i class='fa fa-times'></i>&nbsp;Trash</button>" : ""; ?>
			 
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>id</th>
                  <th>Partner Name</th>
				  <th>Category</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$select = mysqli_query($link, "SELECT * FROM nimcPartner ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
?> 
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $row['id']; ?>"></td>
				<td><?php echo $row['id']; ?></td>
				<td><?php echo $row['partnerName']; ?></td>
				<td><?php echo $row['category']; ?></td>
                <td><?php echo ($edit_nimc_partner == "1") ? '<a href="updateNIMCPartners.php?id='.$_SESSION['tid'].'&&idm='.$row['id'].'&&mid='.base64_encode("411").'">Update</a>' : ''; ?></td>
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
							}
							else{
								for($i=0; $i < $N; $i++)
								{
									$result = mysqli_query($link,"DELETE FROM nimcPartner WHERE id ='$id[$i]'");
									echo "<script>alert('Record Delete Successfully!!!'); </script>";
									echo "<script>window.location='nimcPartnerList.php?id=".$_SESSION['tid']."&&mid=".base64_encode("411")."'; </script>";
								}
							}
						}
					?>		
</form>

              </div>


	
</div>	
</div>
</div>	
</section>
</div>