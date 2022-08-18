<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="compensation_plan.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDE2"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-newspaper-o"></i>  Edit Compensation Plan</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
  $plid = $_GET['plid'];
	$plan_level =  mysqli_real_escape_string($link, $_POST['plan_level']);
	$plan_target =  mysqli_real_escape_string($link, $_POST['plan_target']);
	$percentage =  mysqli_real_escape_string($link, $_POST['percentage']);
		
	$update = mysqli_query($link, "UPDATE compensation_plan SET plan_level='$plan_level', plan_target='$plan_target', percentage='$percentage' WHERE id = '$plid'") or die ("Error: " . mysqli_error($link));
	if(!$update)
	{
		echo "<div class='alert bg-orange'>Error.....Please try again later</div>";
	}
	else{
		echo "<div class='alert bg-blue'>Plan Updated Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$plid = $_GET['plid'];
$search_plid = mysqli_query($link, "SELECT * FROM compensation_plan WHERE id = '$plid'") or die ("ERROR: " . mysqli_error($link));
$fetch_plid = mysqli_fetch_object($search_plid);
?>
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Level</label>
                  <div class="col-sm-10">
                  <input name="plan_level" type="number" class="form-control" value="<?php echo $fetch_plid->plan_level; ?>" placeholder="Plan Level" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Target</label>
                  <div class="col-sm-10">
                  <input name="plan_target" type="number" class="form-control" value="<?php echo $fetch_plid->plan_target; ?>" placeholder="Plan Target" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Percentage</label>
                  <div class="col-sm-10">
                  <input name="percentage" type="text" class="form-control" value="<?php echo $fetch_plid->percentage; ?>" placeholder="Percentage">
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>