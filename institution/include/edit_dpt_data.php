<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="listdpt.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("950"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-calculator"></i>  Edit Income</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	//$expID = "EXP".rand(200000,1000000);
	$id = $_GET['idm'];
	$dpt_name =  mysqli_real_escape_string($link, $_POST['dpt_name']);
	$hod_email = mysqli_real_escape_string($link, $_POST['hod_email']);
	$hod_phone_no = mysqli_real_escape_string($link, $_POST['hod_phone_no']);
	$dpt_desc = mysqli_real_escape_string($link, $_POST['dpt_desc']);
	
	$insert = mysqli_query($link, "UPDATE dept SET dpt_name = '$dpt_name', hod_email = '$hod_email', hod_phone_no = '$hod_phone_no', dpt_desc = '$dpt_desc' WHERE id = '$id'") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert bg-orange'>Unable to Edit Department.....Please try again later</div>";
	}
	else{
		echo "<div class='alert bg-blue'>Department Updated Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">
<?php
$idm = $_GET['idm'];
$exp_search = mysqli_query($link, "SELECT * FROM dept WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
$get_exp = mysqli_fetch_object($exp_search);
?>			 
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Department Name</label>
                  <div class="col-sm-10">
                  <input name="dpt_name" type="text" class="form-control" value="<?php echo $get_exp->dpt_name; ?>" placeholder="Enter Department Name" required>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">HOD Email</label>
                  <div class="col-sm-10">
                  <input name="hod_email" type="email" class="form-control" value="<?php echo $get_exp->hod_email; ?>" placeholder="Enter HOD Email" required>
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">HOD Phone (Optional)</label>
                  <div class="col-sm-10">
                  <input name="hod_phone_no" type="text" class="form-control" value="<?php echo $get_exp->hod_phone_no; ?>" placeholder="Enter HOD Phone Number for SMS Notification">
                  <span style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b>NOTE:</b> if you left this unfilled, No SMS Notification will be sent</span>
                  </div>
                  </div>
							
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Description</label>
                  <div class="col-sm-10">
				  <textarea name="dpt_desc"  class="form-control" rows="4" cols="80" placeholder="Describe Department Activities Here"><?php echo $get_exp->dpt_desc; ?></textarea>
                </div>
                </div>
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
            	<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?> btn-flat"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>