<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="setupaccttype.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("411"); ?>&&mid=NDEx"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-newspaper-o"></i>  Add Account Type</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
    $acct_type = mysqli_real_escape_string($link, $_POST['acct_type']);
	$acct_name =  mysqli_real_escape_string($link, $_POST['acct_name']);
	$interest_rate =  preg_replace("/[^0-9-.\s]/", "", mysqli_real_escape_string($link, $_POST['interest_rate']));
	$tenor = mysqli_real_escape_string($link, $_POST['tenor']);
	$opening_balance =  preg_replace("/[^0-9\s]/", "", mysqli_real_escape_string($link, $_POST['opening_balance']));
		
	$insert = mysqli_query($link, "INSERT INTO account_type VALUES(null,'$institution_id','$acct_type','$acct_name','$interest_rate','$tenor','$opening_balance')") or die ("Error: " . mysqli_error($link));
	
	if(!$insert)
	{
		echo "<div class='alert bg-orange'>Unable to Enter Account Type.....Please try again later</div>";
	}
	else{
		echo "<div class='alert bg-blue'>Account Type Added Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
                 
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Type</label>
                  <div class="col-sm-10">
                  <select name="acct_type"  class="form-control select2" required>
						<option value="" selected>Select Account Type</option>
						<option value="Regular">Regular</option>
						<option value="Fixed">Fixed</option>
					</select>
                  </div>
            </div>
                  
			
			<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Name</label>
				 <div class="col-sm-10">
                <input name="acct_name" type="text" class="form-control" placeholder="Account Name e.g. Savings Account, Current Account, Daily Contribution Account etc." required>
              </div>
			  </div>
			  
			
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Interest</label>
                  <div class="col-sm-10">
                  <input name="interest_rate" type="text" class="form-control" placeholder="Interest FORMAT: 1.0, 0.2, 0.01 etc." required>
                  </div>
                  </div>
                  
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Tenor</label>
                  <div class="col-sm-10">
                  <select name="tenor" class="form-control select2" required>
						<option value="" selected>Select Tenor</option>
						<option value="None">None</option>
						<option value="Annually">Annually</option>
						<option value="30days">30days</option>
						<option value="60days">60days</option>
						<option value="90days">90days</option>
						<option value="120days">120days</option>
						<option value="180days">180days</option>
					</select>
                  </div>
                  </div>
                  
            
			<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Min. Opening Balance</label>
				 <div class="col-sm-10">
				     <input name="opening_balance" type="number" class="form-control" placeholder="Enter Minimum Opening Balance" required>
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