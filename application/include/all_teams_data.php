<div class="row">	
		
	 <section class="content">
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive"> 
             <div class="box-body">
			 
			 <div class="col-md-14">
             <div class="nav-tabs-custom">
             <ul class="nav nav-tabs">

              <li <?php echo ($_GET['tab'] == 'tab_1' && $add_team == "1") ? "class='active'" : ''; ?>><a href="all_teams.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("750"); ?>&&tab=tab_1">Add New Team</a></li>
			  <li <?php echo ($_GET['tab'] == 'tab_2' && $list_teams == "1") ? "class='active'" : ''; ?>><a href="all_teams.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("750"); ?>&&tab=tab_2">All Teams</a></li>

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
            <label for="" class="col-sm-2 control-label" style="color:blue;">Team Logo</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image_name" onChange="readURL(this);" required>
       				 <img id="blah"  src="../avtar/user2.png" alt="Image Here" height="100" width="100"/>
			</div>
			</div>

            <div class="form-group">
 		        <label for="" class="col-sm-2 control-label" style="color:blue;">Team Type</label>
 		        <div class="col-sm-10">
 					<select name="team_type" class="form-control select2" required>
 						<option value="" selected='selected'>Select Team Type&hellip;</option>
 						<option value="Closed">Closed Team</option>
 						<option value="Open">Open Team</option>
 					</select>
 				</div>
 			</div>
			
			 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Team Name</label>
                  <div class="col-sm-10">
                  <input name="tname" type="text" class="form-control" placeholder="Region Name" required>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Team Category</label>
                  <div class="col-sm-10">
    				<select name="t_cat"  class="form-control select2" required>
    
    					<option selected>Select Team Category</option>
                    <?php
                    $search = mysqli_query($link, "SELECT * FROM team_category");
                    while($get_search = mysqli_fetch_array($search))
                    {
                    ?>
                        <option value="<?php echo $get_search['category']; ?>"><?php echo $get_search['category']; ?></option>
                    <?php } ?>
    				</select>
				</div>
            </div>
    
</div>

<div align="right">
<div class="box-footer">
                <button type="submit" name="submit_t" class="btn bg-blue"><i class="fa fa-save">&nbsp;Submit</i></button>

</div>
</div>

<?php
if(isset($_POST['submit_t']))
{
    $team_type = mysqli_real_escape_string($link, $_POST['team_type']);
    $t_cat = mysqli_real_escape_string($link, $_POST['t_cat']);
    $tname = mysqli_real_escape_string($link, $_POST['tname']);
	$image_name = $_FILES['image_name']['name'];
		
	$sourcepath = $_FILES["image_name"]["tmp_name"];
	$targetpath = "../lend/images/home/" . $_FILES["image_name"]["name"];
	move_uploaded_file($sourcepath,$targetpath);
	    
	$insert = mysqli_query($link, "INSERT INTO myteam VALUES(null,'$image_name','$team_type','$t_cat','$tname',NOW())");
	
    if(!$insert){
        echo "<span class='alert bg-orange'>Oops! Unable to add new team. Please try again later!!</span>";
    }
    else{
        echo "<span class='alert bg-blue'>New Team Added Successfully!</span>";
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
				  <th>Team Type</th>
                  <th>Team Category</th>
                  <th>Team Name</th>
                 </tr>
                </thead>
                <tbody> 
<?php
$select = mysqli_query($link, "SELECT * FROM myteam ORDER BY id DESC") or die (mysqli_error($link));
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
                <td><?php echo $rows['team_type']; ?></td>
                <td><?php echo $rows['t_cat']; ?></td>
				<td><img src="../lend/images/home/<?php echo $rows['timage']; ?>" width="20px" height="20px"> <?php echo $rows['tname']; ?></td>
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
					echo "<script>window.location='all_teams.php?id=".$_SESSION['tid']."&&mid=".base64_encode('750')."&&tab=tab_2'; </script>";
				}
				else{
					for($i=0; $i < $N; $i++)
					{
						$result = mysqli_query($link,"DELETE FROM myteam WHERE id ='$id[$i]'");
						echo "<script>alert('Row Delete Successfully!!!'); </script>";
						echo "<script>window.location='all_teams.php?id=".$_SESSION['tid']."&&mid=".base64_encode('750')."&&tab=tab_2'; </script>";
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