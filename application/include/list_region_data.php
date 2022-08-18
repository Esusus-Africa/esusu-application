<div class="row">	
		
	 <section class="content">
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive"> 
             <div class="box-body">
			 
			 <div class="col-md-14">
             <div class="nav-tabs-custom">
             <ul class="nav nav-tabs">

              <li <?php echo ($_GET['tab'] == 'tab_1' && $add_region == "1") ? "class='active'" : ''; ?>><a href="add_region.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("750"); ?>&&tab=tab_1">Add New Region</a></li>
			  <li <?php echo ($_GET['tab'] == 'tab_2' && $add_region == "1") ? "class='active'" : ''; ?>><a href="add_region.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("750"); ?>&&tab=tab_2">All Regions</a></li>

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

<form class="form-horizontal" method="post" enctype="multipart/form-data">

 <div class="box-body">
     
<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:blue;">Region Icon</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image_name" onChange="readURL(this);">
       				 <img id="blah"  src="../avtar/user2.png" alt="Image Here" height="100" width="100"/>
			</div>
			</div>
			
			 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Region Name</label>
                  <div class="col-sm-10">
                  <input name="rname" type="text" class="form-control" placeholder="Region Name" required>
                  </div>
                  </div>
    
</div>

<div align="right">
<div class="box-footer">
                <button type="submit" name="submit_r" class="btn bg-blue"><i class="fa fa-save">&nbsp;Submit</i></button>

</div>
</div>

<?php
if(isset($_POST['submit_r']))
{
    $rname = $_POST['rname'];
	$image_name = $_FILES['image_name']['name'];
		
	$sourcepath = $_FILES["image_name"]["tmp_name"];
	$targetpath = "../lend/images/home/" . $_FILES["image_name"]["name"];
	move_uploaded_file($sourcepath,$targetpath);
	    
	$insert = mysqli_query($link, "INSERT INTO campaign_region VALUES(null,'$image_name','$rname')");
	
    if(!$insert){
        echo "<span class='alert bg-orange'>Oops! Unable to add region. Please try again later!!</span>";
    }
    else{
        echo "<span class='alert bg-blue'>Region Added Successfully!</span>";
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
	 <?php echo ($delete_region == "1") ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Delete</button>' : ''; ?>
	 	 <hr>
				<table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Region Name</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$select = mysqli_query($link, "SELECT * FROM campaign_region ORDER BY id DESC") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-orange'>No data found yet!.....Check back later!!</div>";
}
else{
while($rows = mysqli_fetch_array($select))
{
$id = $rows['id'];
?>   
                <tr>
				<td><input id="optionsCheckbox" class="checkbox" name="selector[]" type="checkbox" value="<?php echo $rows['id']; ?>"></td>
				<td><img src="../lend/images/home/<?php echo $rows['rimage']; ?>" width="20px" height="20px"> <?php echo $rows['rname']; ?></td>
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
					echo "<script>window.location='list_region.php?id=".$_SESSION['tid']."&&mid=".base64_encode('750')."&&tab=tab_2'; </script>";
				}
				else{
					for($i=0; $i < $N; $i++)
					{
						$result = mysqli_query($link,"DELETE FROM campaign_region WHERE id ='$id[$i]'");
						echo "<script>alert('Row Delete Successfully!!!'); </script>";
						echo "<script>window.location='list_region.php?id=".$_SESSION['tid']."&&mid=".base64_encode('750')."&&tab=tab_2'; </script>";
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