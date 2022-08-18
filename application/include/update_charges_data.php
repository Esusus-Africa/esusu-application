<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="view_charges.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NTIw"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-asterisk"></i>  Update Charges</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$idm = mysqli_real_escape_string($link, $_GET['idm']);
	$charges_name =  mysqli_real_escape_string($link, $_POST['charges_name']);
	$ctype = mysqli_real_escape_string($link, $_POST['ctype']);
	$charges_value =  mysqli_real_escape_string($link, $_POST['charges_value']);
	
	$Update = mysqli_query($link, "UPDATE charge_management SET charges_name = '$charges_name', charges_type = '$ctype', charges_value = '$charges_value' WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
	if(!$Update)
	{
		echo "<div class='alert alert-info'>Unable to Update Charges.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>Charges Updated Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
			  <?php echo '<div class="alert bg-orange fade in" >
  				<strong>Fill up the form below to create different charges for customers</strong>
				</div>'?>
             <div class="box-body">
<?php
$idm = mysqli_real_escape_string($link, $_GET['idm']);
$search_charges = mysqli_query($link, "SELECT * FROM charge_management WHERE id = '$idm'");
$fetch_charges = mysqli_fetch_object($search_charges);
?>			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Charges Name</label>
                  <div class="col-sm-10">
                  <input name="charges_name" type="text" class="form-control" value="<?php echo $fetch_charges->charges_name; ?>" placeholder="Charges Name" required>
                  </div>
                  </div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Charges Type</label>
                  <div class="col-sm-10">
				<select name="ctype" class="form-control select2" required>
					<option value='<?php echo $fetch_charges->charges_type; ?>'><?php echo $fetch_charges->charges_type; ?></option>
					<option value="Flatrate">Flat Rate</option>
					<option value="Percentage">Percentage</option>
				</select>
				</div>
            </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Charges Value</label>
                  <div class="col-sm-10">
                  <input name="charges_value" type="text" class="form-control" value="<?php echo $fetch_charges->charges_value; ?>" placeholder="Charges Value Based on Type Selected" required>
                  </div>
                  </div> 
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Update</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>