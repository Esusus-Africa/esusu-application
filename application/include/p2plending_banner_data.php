<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

			 <?php echo ($delete_p2pbanners == 1) ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>
			 
<?php echo ($add_p2pbanner == 1) ? '<a href="add_newbanner.php?id='.$_SESSION['tid'].'&&mid=NDEx"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-plus"></i>&nbsp;Add New Banner</button></a>' : ''; ?>
	<hr>		
			  
			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Banner</th>
                  <th>Caption</th>
				  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM lending_banner ORDER BY id DESC") or die (mysqli_error($link));
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
                <td><img src="../lend/images/home/<?php echo $row['banner_image']; ?>" height="150" width="350"></td>
                <td><?php echo $row['caption']; ?></td>
				<td><a href="edit_ldbanner.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $id; ?>&&mid=NDEx"> <button type="button" class="btn bg-blue btn-flat"><i class="fa fa-edit"></i> Edit</button></a></td>
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
						echo "<script>window.location='p2plending_banner.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"DELETE FROM lending_banner WHERE id ='$id[$i]'");
								echo "<script>alert('Row Delete Successfully!!!'); </script>";
								echo "<script>window.location='p2plending_banner.php?id=".$_SESSION['tid']."&&mid=NDEx'; </script>";
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