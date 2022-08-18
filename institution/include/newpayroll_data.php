<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="listpayroll.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDIz"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-paypal"></i>  Add Payroll</h3>
            </div>

             <div class="box-body">
          
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 
                  <input name="branchid" type="hidden" class="form-control" value="">
			
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Select Registered Staff</label>
                  <div class="col-sm-9">
				<select name="reg_staff" class="form-control select2" required>
										<option selected='selected'>Select Registered Staff&hellip;</option>
										<?php
										$id = $_GET['id'];
					$get = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY userid DESC") or die (mysqli_error($link));
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['userid']; ?>"><?php echo $rows['name']; ?></option>
					<?php } ?>
									</select> 
									 </div>
                 					 </div>
				</div>

			<div align="right">
              <div class="box-footer">
                	<button name="add_payroll" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-plus">&nbsp;Add Payroll</i></button>
              </div>
			</div>
<?php
if(isset($_POST['add_payroll']))
{
	$staffid = mysqli_real_escape_string($link, $_POST['reg_staff']);
	echo "<script> window.location='add_payroll.php?id=".$_SESSION['tid']."&&staff_id=".$staffid."&&mid=NDIz'; </script>";
}
?>
			
			</form> 
			<hr>
		
		<form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
				  
			<?php echo '<div class="alert bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].' fade in" >
  				<strong>Below, you can add unregistered staff that are not using the software as a means of logging in for proper accountability in payroll system.</strong>
				</div>'; ?>
							
				<div class="form-group">
                  <label class="col-sm-3" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Select Unregistered Staff</label>
                </div>

<?php
$unreg_user = mysqli_query($link, "SELECT * FROM user WHERE utype = 'Unregistered' AND created_by = '$institution_id' ORDER BY id DESC") or die ("Error: " . mysqli_error($link));
if(mysqli_num_rows($unreg_user) == 0)
{
	echo "<span style='color: ".$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].";'> No Information Added Yet! </span>";
}else{
while($get_user = mysqli_fetch_object($unreg_user))
{
?>				
				<div class="form-group">
                  <div class="col-sm-3">
				  <input type="radio" name="ureg_name" value="<?php echo $get_user->userid; ?>"> <?php echo $get_user->name; ?><br>
				  <span style="color: red;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="edit_unreg_user.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $get_user->userid; ?>&&mid=NDIz"> Edit </a>&nbsp;&nbsp;<a href="delete_unreg_user.php?id=<?php echo $_SESSION['tid']; ?>&&idm=<?php echo $get_user->userid; ?>&&mid=NDIz"> Delete </a></span>
                  </div>
                </div>
<?php } } ?>								  
			 </div>
			 
<?php
$unreg_user = mysqli_query($link, "SELECT * FROM user WHERE utype = 'Unregistered' AND created_by = '$institution_id' ORDER BY id DESC") or die ("Error: " . mysqli_error($link));
if(mysqli_num_rows($unreg_user) == 0)
{
	echo "";
}else{
?>	
			 <div align="right">
              <div class="box-footer">
                	<button name="add_unreg_payroll" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-plus">&nbsp;Add Payroll</i></button>
              </div>
			 </div>
<?php } ?>
			 
			  <hr>
			  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> <a href="unreg_user.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDIz"> <b>Add other unregistered staff in Payroll</b> </a> </span>
<?php
if(isset($_POST['add_unreg_payroll']))
{
	$staffid = mysqli_real_escape_string($link, $_POST['ureg_name']);
	echo "<script> window.location='add_payroll.php?id=".$_SESSION['tid']."&&staff_id=".$staffid."&&mid=NDIz'; </script>";
}
?>			  
			 </form> 

</div>	
</div>	
</div>
</div>