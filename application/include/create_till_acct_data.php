<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="view_teller.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEw"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-desktop"></i>  Add Till</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$till_name =  mysqli_real_escape_string($link, $_POST['till_name']);
	$cashier = mysqli_real_escape_string($link, $_POST['cashier']);
	$office =  mysqli_real_escape_string($link, $_POST['office']);
	$till_desc =  mysqli_real_escape_string($link, $_POST['till_desc']);
	$status =  mysqli_real_escape_string($link, $_POST['status']);
	$percentage = mysqli_real_escape_string($link, $_POST['percentage']);
	
	$insert = mysqli_query($link, "INSERT INTO till_account VALUES(null,'','$office','$till_name','$cashier','$percentage','0.0','$till_desc','0','$status',NOW())") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Unable to Add Till Account.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>Till Account Added Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
			  <?php echo '<div class="alert bg-orange fade in" >
  				<strong>Fill up the form below to create Till Account for your Field Officers / Teller / Cashier</strong>
				</div>'?>
             <div class="box-body">
			 
                  <input name="branchid" type="hidden" class="form-control" value="">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Till Name</label>
                  <div class="col-sm-10">
                  <input name="till_name" type="text" class="form-control" placeholder="Till Name" required>
                  </div>
                  </div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Cashier Name</label>
                  <div class="col-sm-10">
				<select name="cashier" class="form-control select2" required>
										<option value="" selected='selected'>Select Cashier&hellip;</option>
										<?php
					$get = mysqli_query($link, "SELECT * FROM user WHERE role != 'super_admin' AND role != 'agent_manager' AND role != 'institution_super_admin' ORDER BY userid") or die (mysqli_error($link));
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['id']; ?>"><?php echo $rows['name']; ?></option>
					<?php } ?>
									</select>
									 </div>
                 					 </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Office</label>
                  <div class="col-sm-10">
				<select name="office" class="form-control select2" required>
										<option value="" selected='selected'>Select Office/Branch&hellip;</option>
										<option value=''>Head Office</option>
										<?php
					$get_office = mysqli_query($link, "SELECT * FROM branches ORDER BY id") or die (mysqli_error($link));
					while($office_rows = mysqli_fetch_array($get_office))
					{
					?>
					<option value="<?php echo $rows['branchid']; ?>"><?php echo $office_rows['bname']; ?></option>
					<?php } ?>
									</select>
									 </div>
                 					 </div>
                 					 
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Commision (%)</label>
                  <div class="col-sm-10">
                  <input name="percentage" type="text" class="form-control" placeholder="Deposit Commission like: 0, 2.2, 5, 10.... without putting % sign" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Description</label>
                  <div class="col-sm-10">
				  <textarea name="till_desc"  class="form-control" rows="4" cols="80" placeholder="Description"> </textarea>
                </div>
                </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-10">
				<select name="status" class="form-control select2" required>
										<option value="" selected='selected'>Select Status&hellip;</option>
										<option value='Active'>Active</option>
										<option value='NotActive'>NotActive</option>
									</select>
									 </div>
                 					 </div>				  
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>