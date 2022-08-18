<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="manage_modulePrice.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i> Back</button></a> <i class="fa fa-code"></i>  Update Module Pricing</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$idm = mysqli_real_escape_string($link, $_GET['idm']);
	$mname = mysqli_real_escape_string($link, $_POST['mname']);
	
	$update = mysqli_query($link, "UPDATE module_pricing SET mname = '$mname' WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
	if(!$update)
	{
		echo "<div class='alert bg-orange'>Unable to Update Module.....Please try again later</div>";
	}
	else{
		echo "<div class='alert bg-blue'>Module Updated Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$idm = mysqli_real_escape_string($link, $_GET['idm']);
$search_module = mysqli_query($link, "SELECT * FROM module_pricing WHERE id = '$idm'");
$fetch_module = mysqli_fetch_object($search_module);
?>
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Module Name</label>
                  <div class="col-sm-9">
                  <input name="mname" type="text" class="form-control" value="<?php echo $fetch_module->mname; ?>" readonly>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-edit">&nbsp;Update</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>