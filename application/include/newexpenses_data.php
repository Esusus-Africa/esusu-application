<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="listexpenses.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDIy"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-newspaper-o"></i>  Add Expense</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$expID = "EXP".rand(200000,1000000);
	$exptype =  mysqli_real_escape_string($link, $_POST['exptype']);
	$eamt = mysqli_real_escape_string($link, $_POST['eamt']);
	$edate =  mysqli_real_escape_string($link, $_POST['edate']);
	$edesc =  $_POST['edesc'];
	
	foreach ($_FILES['uploaded_file']['name'] as $key => $name){
 
		$newFilename = time() . "_" . $name;
		move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], '../img/'.$newFilename);
		$location = $newFilename;
		
		$insert = mysqli_query($link, "INSERT INTO expense_document VALUES(null,'$expID','$location')") or die ("Error: " . mysqli_error($link));
	}
	$insert = mysqli_query($link, "INSERT INTO expenses VALUES(null,'','$expID','$exptype','$eamt','$edate','$edesc')") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert alert-info'>Unable to Add Expenses.....Please try again later</div>";
	}
	else{
		echo "<div class='alert alert-success'>New Expenses Added Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
			  <?php echo '<div class="alert bg-orange fade in" >
  				<strong>Required Fields:</strong>
				</div>'?>
             <div class="box-body">
			 
                  <input name="branchid" type="hidden" class="form-control" value="">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Expense Type</label>
                  <div class="col-sm-10">
				<select name="exptype" class="form-control select2" required>
										<option selected='selected'>Select Expense Type&hellip;</option>
										<?php
					$get = mysqli_query($link, "SELECT * FROM exptype ORDER BY id") or die (mysqli_error($link));
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['etype']; ?>"><?php echo $rows['etype']; ?></option>
					<?php } ?>
									</select> 
					<?php echo ($view_expense_type == 1) ? '<span style="color: orange;"> <a href="listexptype.php?id='.$_SESSION['tid'].'&&mid=NDIy" target="_blank"> Add / Edit </a></span><br>' : ''; ?>
									 </div>
                 					 </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Expense Amount</label>
                  <div class="col-sm-10">
                  <input name="eamt" type="text" class="form-control" placeholder="Expense Amount" required>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Expense Date</label>
                  <div class="col-sm-10">
                  <input name="edate" type="date" class="form-control" required>
                  </div>
                  </div>
				  
				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Invoice/Receipt:</label>
                  <div class="col-sm-10">
					Accepted file types <span style="color:orange">jpg, gif, png, xls, xlsx, csv, doc, docx, pdf</span>
			 		<input name="uploaded_file[]" type="file" class="btn bg-blue" multiple required>
			 		<span style="color: orange;"> You can select up to 20 files, Please click Browse button and then <b>Ctrl</b> button on your keyboard to select multiple files.</span>
                  </div>
                </div>
				  
			<?php echo '<div class="alert bg-orange fade in" >
  				<strong>Optional Fields:</strong>
				</div>'; ?>
							
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">Description</label>
                  <div class="col-sm-10">
				  <textarea name="edesc"  class="form-control" rows="4" cols="80" placeholder="Description"> </textarea>
                </div>
                </div>
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                <button type="reset" class="btn bg-orange"><i class="fa fa-times">&nbsp;Reset</i></button>
                <button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>