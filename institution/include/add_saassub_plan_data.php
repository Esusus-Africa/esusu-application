<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="saassub_plan.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDIw"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-spine"></i>  Setup Plan</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$plan_code =  mysqli_real_escape_string($link, $_POST['plan_code']);
  $pcat =  mysqli_real_escape_string($link, $_POST['pcat']);
	$plan_name =  mysqli_real_escape_string($link, $_POST['plan_name']);
	$amount_per_months =  mysqli_real_escape_string($link, $_POST['amount_per_months']);
	$sms_allocated =  mysqli_real_escape_string($link, $_POST['sms_allocated']);
  $staff_limit =  mysqli_real_escape_string($link, $_POST['staff_limit']);
  $branch_limit =  mysqli_real_escape_string($link, $_POST['branch_limit']);
  $customer_limit = mysqli_real_escape_string($link, $_POST['customer_limit']);
	$expiration_grace =  mysqli_real_escape_string($link, $_POST['expiration_grace']);
  $others =  mysqli_real_escape_string($link, $_POST['others']);
		
	$insert = mysqli_query($link, "INSERT INTO saas_subscription_plan VALUES(null,'$plan_code','$pcat','$plan_name','$amount_per_months','$expiration_grace','Active','$sms_allocated','$staff_limit','$branch_limit','$customer_limit','$others')") or die ("Error: " . mysqli_error($link));
	if(!$insert)
	{
		echo "<div class='alert bg-orange'>Error.....Please try again later</div>";
	}
	else{
		echo "<div class='alert bg-blue'>New Plan Added Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
function random_password($limit)
{
  return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}
?>
             <div class="box-body">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Code</label>
                  <div class="col-sm-9">
                  <input name="plan_code" type="text" class="form-control" value="<?php echo 'PLN_'.random_password(15); ?>" readonly>
                  </div>
                  </div>

      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Categories</label>
                  <div class="col-sm-9">
        <select name="pcat"  class="form-control select2" required style="width:100%" id="pcat">
        <option selected="selected">Select Category...</option>
        <option value="Institution">Institution Subscription</option>
        <option value="Cooperatives">Cooperatives Subscription</option>
        <option value="Merchant">Merchant Subscription</option>
        <option value="Agent">Agent Subscription</option>  
        <option value="demo">Demo Plan for Testing</option>   
        </select>
                  </div>
      </div>

      <span id='ShowValueFrank'></span>
      <span id='ShowValueFrank'></span>
      <span id='ShowValueFrank'></span>
      <span id='ShowValueFrank'></span>
      <span id='ShowValueFrank'></span>
			
			 </div>
			 
			  
			  
			 </form> 

</div>	
</div>	
</div>
</div>