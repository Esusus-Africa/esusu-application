<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="setup_coupon.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-gear"></i>  Add Coupon Code</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$ratetype =  mysqli_real_escape_string($link, $_POST['ratetype']);
	$rate =  mysqli_real_escape_string($link, $_POST['rate']);
	$ccode =  mysqli_real_escape_string($link, $_POST['ccode']);
	$coupontype =  mysqli_real_escape_string($link, $_POST['coupontype']);
	$start_date =  mysqli_real_escape_string($link, $_POST['start_date']);
	$end_date =  mysqli_real_escape_string($link, $_POST['end_date']);
	$max_r =  mysqli_real_escape_string($link, $_POST['max_r']);
		
	if($coupontype == 'one_off')
	{
		$insert = mysqli_query($link, "INSERT INTO coupon_setup VALUES(null,'$ccode','$coupontype','$start_date','$end_date','$ratetype','$rate','$max_r','0',NOW())") or die ("Error: " . mysqli_error($link));
		if(!$insert)
		{
			echo "<div class='alert bg-orange'>Unable to Create Coupon.....Please try again later</div>";
		}
		else{
			echo "<div class='alert bg-blue'>New Coupon Added Successfully!</div>";
		}
	}
	else{
		$insert = mysqli_query($link, "INSERT INTO coupon_setup VALUES(null,'$ccode','$coupontype','','','$ratetype','$rate','$max_r','0',NOW())") or die ("Error: " . mysqli_error($link));
		if(!$insert)
		{
			echo "<div class='alert bg-orange'>Unable to Create Coupon.....Please try again later</div>";
		}
		else{
			echo "<div class='alert bg-blue'>New Coupon Added Successfully!</div>";
		}
	}	
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Rate Type</label>
				 <div class="col-sm-9">
                <select name="ratetype" class="form-control select2" style="width: 100%;" /required>
					<option selected="selected">--Select Rate Type--</option>
					<option value="percent_off">Percent Off</option>
					<option value="amount_off">Amount Off</option>
                </select>
              </div>
			  </div>

			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Rate</label>
                  <div class="col-sm-9">
                  <input name="rate" type="number" class="form-control" placeholder="Enter Rate Based on Selected Rate Type" required>
                  </div>
                  </div>

<?php
function random_coupon($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}
?>		
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Coupon Code</label>
                  <div class="col-sm-9">
                  <input name="ccode" type="text" class="form-control" value="<?php echo random_coupon(15); ?>" placeholder="Enter Customized Coupon Code" required>
                  </div>
                  </div>
				  
			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:blue;">Coupon Type</label>
				 <div class="col-sm-9">
                <select name="coupontype" class="form-control select2" id="coupontype" style="width: 100%;" /required>
					<option selected="selected">--Select Coupon Type--</option>
					<option value="one_off">Once-off</option>
					<option value="Repeating">Repeating</option>
                </select>
              </div>
			  </div>

			<span id='ShowValueFrank'></span>
  			<span id='ShowValueFrank'></span>
				  
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Maximum Redemption</label>
                  <div class="col-sm-9">
                  <input name="max_r" type="number" class="form-control" placeholder="Maximum Redemption for all e.g 3, 100 times" required>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-plus">&nbsp;Add</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>