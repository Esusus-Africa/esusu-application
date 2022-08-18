<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="setuploanprd.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-newspaper-o"></i>  Add Loan Product</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
    $cat =  mysqli_real_escape_string($link, $_POST['cat']);
	$merchantid =  mysqli_real_escape_string($link, $_POST['merchantid']);
	$pname =  mysqli_real_escape_string($link, $_POST['pname']);
	$interest_rate =  preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['interest_rate']));
	$ltype =  mysqli_real_escape_string($link, $_POST['ltype']);
	$lduration =  mysqli_real_escape_string($link, $_POST['lockp']);
	$tenor =  mysqli_real_escape_string($link, $_POST['tenor']);
	
	if($cat == "Merchant"){
	    
	    $search_merchant = mysqli_query($link, "SELECT * FROM merchant_reg WHERE merchantID = '$merchantid' AND status = 'Active'");
	    $fetch_merchant = mysqli_fetch_array($search_merchant);
	    $subaccount_code = $fetch_merchant['subaccount_code'];
	    
	    $insert = mysqli_query($link, "INSERT INTO loan_product VALUES(null,'$merchantid','$subaccount_code','$ltype','$pname','$interest_rate','$lduration','$tenor')") or die ("Error: " . mysqli_error($link));
    	if(!$insert || (mysqli_num_rows($search_merchant) == 0))
    	{
    		echo "<div class='alert bg-orange'>Unable to Enter Loan Product.....Please try again later</div>";
    	}
    	else{
    		echo "<div class='alert bg-blue'>New Loan Product Added Successfully!</div>";
    	}
	    
	}
	elseif($cat == "Institution"){
		
		$search_institu = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$merchantid' AND status = 'Approved'");
	    $fetch_institu = mysqli_fetch_array($search_institu);
	    $insti_subaccount_code = $fetch_institu['subaccount_code'];
	    
    	$insert = mysqli_query($link, "INSERT INTO loan_product VALUES(null,'$merchantid','$insti_subaccount_code','$ltype','$pname','$interest_rate','$lduration','$tenor')") or die ("Error: " . mysqli_error($link));
    	if(!$insert || (mysqli_num_rows($search_institu) == 0))
    	{
    		echo "<div class='alert bg-orange'>Unable to Enter Loan Product.....Please try again later</div>";
    	}
    	else{
    		echo "<div class='alert bg-blue'>New Loan Product Added Successfully!</div>";
    	}
    }
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
                 
            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Categories</label>
				 <div class="col-sm-9">
                <select name="cat" class="select2" style="width: 100%;" required>
				<option value="" selected="selected">--Select Type--</option>
					<option value="Merchant">Merchant</option>
					<option value="Institution">Institution</option>
                </select>
              </div>
			  </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Select Merchant</label>
				 <div class="col-sm-9">
                <select name="merchantid" class="select2" style="width: 100%;" /required>
				<option value="" selected="selected">--Select Merchant--</option>
				<option disabled>--SELECT MERCHANT--</option>
					<?php
					$search_merchant = mysqli_query($link, "SELECT * FROM merchant_reg WHERE status = 'Active' ORDER BY id DESC");
					while($fetch_merchant = mysqli_fetch_object($search_merchant))
					{
					?>
					<option value="<?php echo $fetch_merchant->merchantID; ?>"><?php echo $fetch_merchant->company_name; ?></option>
				    <?php } ?>
				<option disabled>--SELECT INSTITUTION--</option>
					<?php
					$search_institutn = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved' ORDER BY id DESC");
					while($fetch_institutn = mysqli_fetch_object($search_institutn))
					{
					?>
					<option value="<?php echo $fetch_institutn->institution_id; ?>"><?php echo $fetch_institutn->institution_name; ?></option>
				    <?php } ?>
                </select>
              </div>
			  </div>
			  
			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Loan Type</label>
				 <div class="col-sm-9">
                <select name="ltype" class="select2" style="width: 100%;" required>
				<option value="" selected="selected">--Select Loan Type--</option>
					<option value="Individual">Individual</option>
					<option value="Group">Group</option>
					<option value="Purchase">Purchase</option>
                </select>
              </div>
			  </div>
			 			
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Product Name</label>
                  <div class="col-sm-9">
                  <input name="pname" type="text" class="form-control" placeholder="Product Name" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Interest on Duration</label>
                  <div class="col-sm-9">
                  <input name="interest_rate" type="text" class="form-control" placeholder="Interest Rate per Month" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Maximum Duration</label>
                  <div class="col-sm-9">
                  <input name="lockp" type="number" class="form-control" placeholder="Enter Number of Month(s) or Week(s)" required>
                  </div>
                  </div>
                  
            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Tenor</label>
				 <div class="col-sm-9">
                <select name="tenor" class="select2" style="width: 100%;" required>
				<option value="" selected="selected">--Select Tenor--</option>
					<option value="Weekly">Weekly</option>
					<option value="Monthly">Monthly</option>
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