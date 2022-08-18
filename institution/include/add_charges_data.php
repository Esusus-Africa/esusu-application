<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="view_charges.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NTIw"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-asterisk"></i>  Add Charges</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$charges_name =  mysqli_real_escape_string($link, $_POST['charges_name']);
	$ctype = mysqli_real_escape_string($link, $_POST['ctype']);
	$charges_value =  mysqli_real_escape_string($link, $_POST['charges_value']);
	
	$insert = mysqli_query($link, "INSERT INTO charge_management VALUES(null,'$institution_id','$charges_name','$ctype','$charges_value',NOW())") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Unable to Add Charges.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>Charges Added Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
			  <?php echo '<div class="alert bg-'.$myaltrow['alternate_color'].' == "" ? orange : '.$myaltrow['alternate_color'].' fade in" >
  				<strong>Fill up the form below to create different charges for customers</strong>
				</div>'?>
             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Charges Name</label>
                  <div class="col-sm-10">
                  <input name="charges_name" type="text" class="form-control" placeholder="Charges Name" required>
                  </div>
                  </div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Charges Type</label>
                  <div class="col-sm-10">
				<select name="ctype" class="form-control select2" required>
					<option selected='selected'>Select Charges Type&hellip;</option>
					<option value="Flatrate">Flat Rate</option>
					<option value="Percentage">Percentage</option>
				</select>
				</div>
            </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Charges Value</label>
                  <div class="col-sm-10">
                  <input name="charges_value" type="text" class="form-control" placeholder="Charges Value Based on Type Selected" required>
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