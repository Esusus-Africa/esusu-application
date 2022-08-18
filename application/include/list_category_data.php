<div class="row">	
		
	 <section class="content">
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive"> 
             <div class="box-body">
			 
			 <div class="col-md-14">
             <div class="nav-tabs-custom">
             <ul class="nav nav-tabs">

              <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="list_category.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("750"); ?>&&tab=tab_1">All Campaigns Category</a></li>
			  <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="list_category.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("750"); ?>&&tab=tab_2">All Teams Category</a></li>

              </ul>
             <div class="tab-content">
<?php
if(isset($_GET['tab']) == true)
{
	$tab = $_GET['tab'];
	if($tab == 'tab_1')
	{
	?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">
<form method="post">
	 <?php echo ($delete_category == "1") ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>' : ''; ?>
	 
	 <?php echo ($add_category == "1") ? '<a href="add_category?id='.$_SESSION['tid'].'&&mid=NzUw"><button type="button" class="btn bg-blue"><i class="fa fa-plus"></i>&nbsp;Add Category</button></a>' : ''; ?>
	 <hr>
				<table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Campaign Category</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$select = mysqli_query($link, "SELECT * FROM campaign_category ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
}
else{
while($rows = mysqli_fetch_array($select))
{
$id = $rows['id'];
?>   
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $rows['id']; ?>"></td>
				<td><?php echo $rows['category']; ?></td>
                <td><?php echo ($edit_category == "1") ? '<a href="edit_ccategory.php?id='.$_SESSION['tid'].'&&idm='.$id.'&&mid=NzUw"> <button type="button" class="btn bg-blue btn-flat"><i class="fa fa-edit"></i> Edit</button></a>' : ''; ?></td>
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
					echo "<script>window.location='list_category.php?id=".$_SESSION['tid']."&&mid=".base64_encode('750')."&&tab=tab_1'; </script>";
				}
				else{
					for($i=0; $i < $N; $i++)
					{
						$result = mysqli_query($link,"DELETE FROM campaign_category WHERE id ='$id[$i]'");
						echo "<script>alert('Row Delete Successfully!!!'); </script>";
						echo "<script>window.location='list_category.php?id=".$_SESSION['tid']."&&mid=".base64_encode('750')."&&tab=tab_1'; </script>";
					}
				}
				}
				?>
					
				
</form>				
				
              </div>
              <!-- /.tab-pane -->
	<?php
	}
	elseif($tab == 'tab_2')
	{
	?>
              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">
			  
				<form method="post">
	 <?php echo ($delete_category == "1") ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>' : ''; ?>
	 
	 <?php echo ($add_category == "1") ? '<a href="add_category?id='.$_SESSION['tid'].'&&mid=NzUw"><button type="button" class="btn bg-blue"><i class="fa fa-plus"></i>&nbsp;Add Category</button></a>' : ''; ?>
	 <hr>
				<table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Team Category</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$select = mysqli_query($link, "SELECT * FROM team_category ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
}
else{
while($rows = mysqli_fetch_array($select))
{
$id = $rows['id'];
?>   
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $rows['id']; ?>"></td>
				<td><?php echo $rows['category']; ?></td>
                <td><?php echo ($edit_category == "1") ? '<a href="edit_tcategory.php?id='.$_SESSION['tid'].'&&idm='.$id.'&&mid=NzUw"> <button type="button" class="btn bg-blue btn-flat"><i class="fa fa-edit"></i> Edit</button></a>' : ''; ?></td>
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
					echo "<script>window.location='list_category.php?id=".$_SESSION['tid']."&&mid=".base64_encode('750')."&&tab=tab_2'; </script>";
				}
				else{
					for($i=0; $i < $N; $i++)
					{
						$result = mysqli_query($link,"DELETE FROM team_category WHERE id ='$id[$i]'");
						echo "<script>alert('Row Delete Successfully!!!'); </script>";
						echo "<script>window.location='list_category.php?id=".$_SESSION['tid']."&&mid=".base64_encode('750')."&&tab=tab_2'; </script>";
					}
				}
				}
				?>
					
				
</form>
			  
              </div>
	<?php
	} }
	?>
              
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
					
				
</form>
				</div>
				

              </div>
			 

	
</div>	
</div>
</div>	
</div>