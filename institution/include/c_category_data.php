<div class="row">	
		
	 <section class="content">
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive"> 
             <div class="box-body">
			 
			 <div class="col-md-14">
             <div class="nav-tabs-custom">
             <ul class="nav nav-tabs">

              <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="c_category.php??id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("421"); ?>&&tab=tab_1">All Category</a></li>
			  <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="c_category.php?id=<?php echo $_GET['id']; ?>&&mid=<?php echo base64_encode("421"); ?>&&tab=tab_2">Add Category</a></li>

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
	 <button type="submit" class="btn btn-flat btn-danger" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>
	 <hr>
				<table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Project Category</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$select = mysqli_query($link, "SELECT * FROM campaign_cat ORDER BY id DESC") or die (mysqli_error($link));
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
				<td><?php echo $rows['c_category']; ?></td>
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
					echo "<script>window.location='c_category.php?id=".$_SESSION['tid']."&&mid=".base64_encode('421')."&&tab=tab_1'; </script>";
				}
				else{
					for($i=0; $i < $N; $i++)
					{
						$result = mysqli_query($link,"DELETE FROM campaign_cat WHERE id ='$id[$i]'");
						echo "<script>alert('Row Delete Successfully!!!'); </script>";
						echo "<script>window.location='c_category.php?id=".$_SESSION['tid']."&&mid=".base64_encode('421')."&&tab=tab_1'; </script>";
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
			  
				<form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_c_cat.php">
				 <div class="box-body">
					
					  <div class="form-group">
					  <label for="" class="col-sm-2 control-label" style="color:#009900">Project Category</label>
					  <div class="col-sm-10">
					  <input name="pcat" type="text" class="form-control" placeholder="Enter Campaign Category" required>
					  </div>
					  </div>
					  
				</div>
					<div align="right">
						<div class="box-footer">
							<button type="reset" class="btn btn-primary btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
							<button name="ccat" type="submit" class="btn btn-success btn-flat"><i class="fa fa-save">&nbsp;Create</i></button>

						</div>
					</div>
				</form>
			  
              </div>
	<?php
	} }
	?>
              
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
				 
			<?php
				if(isset($_POST['delete'])){
				$idm = $_GET['id'];
				$id=$_POST['selector'];
				$N = count($id);
				if($id == ''){
					echo "<script>alert('Row Not Selected!!!'); </script>";	
					echo "<script>window.location='c_category.php?id=".$_SESSION['tid']."&&mid=".base64_encode('421')."&&tab=tab_1'; </script>";
				}
				else{
					for($i=0; $i < $N; $i++)
					{
						$result = mysqli_query($link,"DELETE FROM campaign_cat WHERE id ='$id[$i]'");
						echo "<script>alert('Row Delete Successfully!!!'); </script>";
						echo "<script>window.location='c_category.php?id=".$_SESSION['tid']."&&mid=".base64_encode('421')."&&tab=tab_1'; </script>";
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
</div>