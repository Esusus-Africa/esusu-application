<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="setuploanprd.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-newspaper-o"></i>  Add Loan Product</h3>
            </div>

             <div class="box-body">
<?php
if(isset($_POST['save']))
{
	$cat =  mysqli_real_escape_string($link, $_POST['cat']);
	$pname =  mysqli_real_escape_string($link, $_POST['pname']);
	$interest_rate =  preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['interest_rate']));
	$lduration =  mysqli_real_escape_string($link, $_POST['lockp']);
	$tenor =  mysqli_real_escape_string($link, $_POST['tenor']);
  //$visibility = mysqli_real_escape_string($link, $_POST['visibility']);
    $interest_type = mysqli_real_escape_string($link, $_POST['interest_type']);
		
	$insert = mysqli_query($link, "INSERT INTO loan_product VALUES(null,'$vcreated_by','$subaccount_code','$cat','$pname','$interest_rate','$lduration','$tenor','No','0','$vendorid','$interest_type')") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert bg-orange'>Unable to Enter Loan Product.....Please try again later</div>";
	}
	else{
		echo "<div class='alert bg-blue'>New Loan Product Added Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
            
            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Loan Type</label>
				 <div class="col-sm-10">
                <select name="cat" class="select2" style="width: 100%;" required>
				<option value="" selected="selected">--Select Category--</option>
					<option value="Individual">Individual</option>
					<option value="Group">Group</option>
					<option value="Purchase">Purchase</option>
                </select>
              </div>
			  </div>
			 			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Product Name</label>
                  <div class="col-sm-10">
                  <input name="pname" type="text" class="form-control" placeholder="Product Name" required>
                  </div>
                  </div>
                  
            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Interest Type</label>
				 <div class="col-sm-9">
                <select name="interest_type" class="select2" style="width: 100%;" required>
				<option value="" selected="selected">--Interest Type--</option>
					<option value="Flat Rate">Flat Rate</option>
					<option value="Reducing Balance">Reducing Balance</option>
                </select>
              </div>
			  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Interest on Duration</label>
                  <div class="col-sm-10">
                  <input name="interest_rate" type="text" class="form-control" placeholder="Interest on Duration" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Duration</label>
                  <div class="col-sm-10">
                  <input name="lockp" type="number" class="form-control" placeholder="Enter Number of Month(s) or Week(s)" required>
                  </div>
                  </div>
                  
                  
            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Tenor</label>
				 <div class="col-sm-10">
                <select name="tenor" class="select2" style="width: 100%;" required>
				<option value="" selected="selected">--Select Tenor--</option>
					<option value="Weekly">Weekly</option>
					<option value="Monthly">Monthly</option>
                </select>
              </div>
			  </div>
        
        <!--
        <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Visible to Others</label>
				 <div class="col-sm-10">
                <select name="visibility" class="select2" style="width: 100%;">
				<option value="" selected="selected">--Select Visibility--</option>
					<option value="Yes">Yes</option>
					<option value="No">No</option>
                </select>
              </div>
			  </div>
			  -->
			
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