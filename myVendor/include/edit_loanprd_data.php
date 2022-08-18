<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="setuploanprd.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-calculator"></i> Edit Loan Product</h3>
            </div>

             <div class="box-body">


  <?php
if(isset($_POST['save']))
{
	$lprd_id = $_GET['idm'];
	$cat =  mysqli_real_escape_string($link, $_POST['cat']);
	$pname =  mysqli_real_escape_string($link, $_POST['pname']);
	$interest_rate =  mysqli_real_escape_string($link, $_POST['interest_rate']);
	$lduration =  mysqli_real_escape_string($link, $_POST['lockp']);
	$tenor =  mysqli_real_escape_string($link, $_POST['tenor']);
    $interest_type = mysqli_real_escape_string($link, $_POST['interest_type']);
	
	$insert = mysqli_query($link, "UPDATE loan_product SET category='$cat', pname='$pname', interest='$interest_rate', duration='$lduration', tenor='$tenor', interest_type='$interest_type' WHERE id = '$lprd_id'");
	if(!$insert)
	{
		echo "<div class='alert bg-orange'>Unable to Edit Loan Product.....Please try again later</div>";
	}
	else{
		echo "<div class='alert bg-blue'>Loan Product Update Successfully!</div>";
	}
}
?>
       
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
<?php
$idm = $_GET['idm'];
$lprd_search = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$idm'") or die ("Error: " . mysqli_error($link));
$get_lprd = mysqli_fetch_object($lprd_search);
?>			 
            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Category</label>
				 <div class="col-sm-9">
                <select name="cat" class="select2" style="width: 100%;">
				<option value="<?php echo $get_lprd->category; ?>" selected="selected"><?php echo $get_lprd->category; ?></option>
					<option value="Individual">Individual</option>
					<option value="Group">Group</option>
					<option value="Purchase">Purchase</option>
                </select>
              </div>
			  </div>
			  
                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Product Name</label>
                  <div class="col-sm-9">
                  <input name="pname" type="text" class="form-control" value="<?php echo $get_lprd->pname; ?>" placeholder="Product Name" required>
                  </div>
                  </div>
                  
            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Interest Type</label>
				 <div class="col-sm-9">
                <select name="interest_type" class="select2" style="width: 100%;">
				<option value="<?php echo $get_lprd->interest_type; ?>" selected="selected"><?php echo $get_lprd->interest_type; ?></option>
					<option value="Flat Rate">Flat Rate</option>
					<option value="Reducing Balance">Reducing Balance</option>
                </select>
              </div>
			  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Interest on Duration</label>
                  <div class="col-sm-9">
                  <input name="interest_rate" type="text" class="form-control" value="<?php echo $get_lprd->interest; ?>" placeholder="Interest on Duration" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Duration</label>
                  <div class="col-sm-9">
                  <input name="lockp" type="number" class="form-control" value="<?php echo $get_lprd->duration; ?>" placeholder="Enter Number of Month(s) or Week(s)" required>
                  </div>
                  </div>
                  
            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Tenor</label>
				 <div class="col-sm-9">
                <select name="tenor" class="form-control select2" style="width: 100%;">
				<option value="<?php echo $get_lprd->tenor; ?>" selected="selected"><?php echo $get_lprd->tenor; ?></option>
					<option value="Weekly">Weekly</option>
					<option value="Monthly">Monthly</option>
                </select>
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