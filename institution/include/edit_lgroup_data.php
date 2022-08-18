<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="list_lgroup.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("411"); ?>&&mid=NDEx"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-newspaper-o"></i> Edit Group Settings</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$idm = $_GET['idm'];
	$gname =  mysqli_real_escape_string($link, $_POST['gname']);
	$max_member =  mysqli_real_escape_string($link, $_POST['max_member']);
		
	$insert = mysqli_query($link, "UPDATE lgroup_setup SET gname = '$gname', max_member = '$max_member' WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert bg-orange'>Unable to Update Group Setup.....Please try again later</div>";
	}
	else{
		echo "<div class='alert bg-blue'>Group Update Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$idm = $_GET['idm'];
$search_group = mysqli_query($link, "SELECT * FROM lgroup_setup WHERE id = '$idm'");
$fetch_group = mysqli_fetch_array($search_group)
?>			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Group Name</label>
                  <div class="col-sm-10">
                  <input name="gname" type="text" class="form-control" value="<?php echo $fetch_group['gname']; ?>" placeholder="Group Name" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Maximum Member</label>
                  <div class="col-sm-10">
                  <input name="max_member" type="number" class="form-control" value="<?php echo $fetch_group['max_member']; ?>" placeholder="Maximum NUmber of Member's" required>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>