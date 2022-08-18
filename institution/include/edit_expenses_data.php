<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading">
            <h3 class="panel-title"> <a href="listexpenses.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("422"); ?>&&mid=NDIy"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-newspaper-o"></i>  Add Expense</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	//$expID = "EXP".rand(200000,1000000);
	$idm = $_GET['idm'];
	$exptype =  mysqli_real_escape_string($link, $_POST['exptype']);
	$eamt = mysqli_real_escape_string($link, $_POST['eamt']);
	$edate =  mysqli_real_escape_string($link, $_POST['edate']);
	$edesc =  $_POST['edesc'];
	
	$insert = mysqli_query($link, "UPDATE expenses SET exptype = '$exptype', eamt = '$eamt', edate = '$edate', edesc = '$edesc' WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Unable to Edit Expenses.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>Expenses Edited Successfully!</div>";
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
$exp_search = mysqli_query($link, "SELECT * FROM expenses WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
$get_exp = mysqli_fetch_object($exp_search);
?>			 
                  <input name="expid" type="hidden" class="form-control" value="<?php echo $get_exp->expid; ?>">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Expense Type</label>
                  <div class="col-sm-10">
				<select name="exptype" class="form-control select2" required>
										<option value="<?php echo $get_exp->exptype; ?>" selected='selected'><?php echo $get_exp->exptype; ?></option>
										<?php
					$get = mysqli_query($link, "SELECT * FROM exptype ORDER BY id") or die (mysqli_error($link));
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['etype']; ?>"><?php echo $rows['etype']; ?></option>
					<?php } ?>
									</select> 
					<span style="color: red;"> <a href="listexptype.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDIy" target="_blank"> Add / Edit </a></span><br>
									 </div>
                 					 </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Expense Amount</label>
                  <div class="col-sm-10">
                  <input name="eamt" type="text" class="form-control" value="<?php echo $get_exp->eamt; ?>" placeholder="Expense Amount" required>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Expense Date</label>
                  <div class="col-sm-10">
                  <input name="edate" type="date" class="form-control" value="<?php echo $get_exp->edate; ?>" required>
                  </div>
                  </div>
				  
			<?php echo '<div class="alert alert-info fade in" >
  				<strong>Optional Fields:</strong>
				</div>'?>
							
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Description</label>
                  <div class="col-sm-10">
				  <textarea name="edesc"  class="form-control" rows="4" cols="80" placeholder="Description"> <?php echo $get_exp->edesc; ?> </textarea>
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