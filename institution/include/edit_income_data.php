<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="listincome.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("500"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-calculator"></i>  Edit Income</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	//$expID = "EXP".rand(200000,1000000);
	$icm_id = $_GET['idm'];
	$icm_type =  mysqli_real_escape_string($link, $_POST['icm_type']);
	$icm_amt = mysqli_real_escape_string($link, $_POST['icm_amt']);
	$icm_date =  mysqli_real_escape_string($link, $_POST['icm_date']);
	$icm_desc =  mysqli_real_escape_string($link, $_POST['icm_desc']);
	
	$insert = mysqli_query($link, "UPDATE income SET icm_type = '$icm_type', icm_amt = '$icm_amt', icm_date = '$icm_date', icm_desc = '$icm_desc' WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Unable to Edit Income.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>Income Updated Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
			  <?php echo '<div class="alert bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].' fade in" >
  				<strong>Required Fields:</strong>
				</div>'?>
             <div class="box-body">
<?php
$idm = $_GET['idm'];
$exp_search = mysqli_query($link, "SELECT * FROM income WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
$get_exp = mysqli_fetch_object($exp_search);
?>			 
                  <input name="expid" type="hidden" class="form-control" value="<?php echo $get_exp->expid; ?>">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Income Type</label>
                  <div class="col-sm-10">
				<select name="icm_type" class="form-control select2" required>
										<option value="<?php echo $get_exp->icm_type; ?>" selected='selected'><?php echo $get_exp->icm_type; ?></option>
										<?php
					$get = mysqli_query($link, "SELECT * FROM icmtype ORDER BY id") or die (mysqli_error($link));
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['incometype']; ?>"><?php echo $rows['incometype']; ?></option>
					<?php } ?>
									</select> 
					<span style="color: red;"> <a href="listincometype.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDIy" target="_blank"> Add / Edit </a></span><br>
									 </div>
                 					 </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Amount</label>
                  <div class="col-sm-10">
                  <input name="icm_amt" type="text" class="form-control" value="<?php echo $get_exp->icm_amt; ?>" placeholder="Amount" required>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date</label>
                  <div class="col-sm-10">
                  <input name="icm_date" type="date" class="form-control" value="<?php echo $get_exp->icm_date; ?>" required>
                  </div>
                  </div>
				  
			<?php echo '<div class="alert bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].' fade in" >
  				<strong>Optional Fields:</strong>
				</div>'?>
							
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Description</label>
                  <div class="col-sm-10">
				  <textarea name="icm_desc"  class="form-control" rows="4" cols="80" placeholder="Description"> <?php echo $get_exp->icm_desc; ?> </textarea>
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