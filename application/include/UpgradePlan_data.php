<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="paid_sub.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDIw"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-spine"></i>  Upgrade Subscription</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['update']))
{
  $subtoken = $_GET['subtoken'];
  $plan_code =  mysqli_real_escape_string($link, $_POST['plan_code']);
  $amount_paid =  mysqli_real_escape_string($link, $_POST['amount_paid']);
  $staff_limit =  mysqli_real_escape_string($link, $_POST['staff_limit']);
  $branch_limit =  mysqli_real_escape_string($link, $_POST['branch_limit']);
  $customer_limit = mysqli_real_escape_string($link, $_POST['customer_limit']);
  $no_group =  mysqli_real_escape_string($link, $_POST['no_group']);
  $no_lproduct =  mysqli_real_escape_string($link, $_POST['no_lproduct']);
  $no_sproduct =  mysqli_real_escape_string($link, $_POST['no_sproduct']);
  $no_invproduct =  mysqli_real_escape_string($link, $_POST['no_invproduct']);

  $update = mysqli_query($link, "UPDATE saas_subscription_trans SET amount_paid = '$amount_paid', staff_limit = '$staff_limit', branch_limit = '$branch_limit', customer_limit = '$customer_limit', no_group = '$no_group', no_lproduct = '$no_lproduct', branch_limit = '$branch_limit', customers_limit = '$customer_limit', no_sproduct = '$no_sproduct', no_invproduct = '$no_invproduct' WHERE sub_token = '$subtoken'") or die ("Error: " . mysqli_error($link));
  
  if(!$update)
  {
		echo "<div class='alert bg-orange'>Error.....Please try again later</div>";
  }
  else{
		echo "<div class='alert bg-blue'>Subscription Upgraded Successfully!</div>";
  }
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
$subtoken = $_GET['subtoken'];
$search_saasplan = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE sub_token = '$subtoken'");
$fetch_saasplan = mysqli_fetch_object($search_saasplan);
$pcode = $fetch_saasplan->plan_code;

$searchSaasPlan = mysqli_query($link, "SELECT * FROM saas_subscription_plan WHERE plan_code = '$pcode'") or die (mysqli_error($link));
$fetchSaasPlan = mysqli_fetch_array($searchSaasPlan);
?>


             <div class="box-body">

            <input name="sub_token" type="hidden" class="form-control" value="<?php echo $subtoken; ?>">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Code</label>
                  <div class="col-sm-9">
                  <input name="plan_code" type="text" class="form-control" value="<?php echo $pcode; ?>" readonly>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Name</label>
                  <div class="col-sm-9">
                  <input name="plan_name" type="text" class="form-control" value="<?php echo $fetchSaasPlan['plan_name']; ?>" placeholder="Plan Name" readonly>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-9">
                  <input name="amount_paid" type="text" class="form-control" value="<?php echo $fetch_saasplan->amount_paid; ?>" placeholder="Amount Paid" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Staff/Sub-Agent Limit</label>
                  <div class="col-sm-9">
                  <input name="staff_limit" type="text" class="form-control" value="<?php echo $fetch_saasplan->staff_limit; ?>" placeholder="Number of Staff Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Branch Limit</label>
                  <div class="col-sm-9">
                  <input name="branch_limit" type="text" class="form-control" value="<?php echo $fetch_saasplan->branch_limit; ?>" placeholder="Number of Branches Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Customers Limit</label>
                  <div class="col-sm-9">
                  <input name="customer_limit" type="text" class="form-control" value="<?php echo $fetch_saasplan->customer_limit; ?>" placeholder="Number of Customers Regstration Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Groups</label>
                  <div class="col-sm-9">
                  <input name="no_group" type="text" class="form-control" value="<?php echo $fetch_saasplan->no_group; ?>" placeholder="Number of Groups Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Loan Product</label>
                  <div class="col-sm-9">
                  <input name="no_lproduct" type="text" class="form-control" value="<?php echo $fetch_saasplan->no_lproduct; ?>" placeholder="Number of Loan Product Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Savings Product</label>
                  <div class="col-sm-9">
                  <input name="no_sproduct" type="text" class="form-control" value="<?php echo $fetch_saasplan->no_sproduct; ?>" placeholder="Number of Savings Product Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Investment Product</label>
                  <div class="col-sm-9">
                  <input name="no_invproduct" type="text" class="form-control" value="<?php echo $fetch_saasplan->no_invproduct; ?>" placeholder="Number of Investment Product Allowed" required>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="update" type="submit" class="btn bg-blue"><i class="fa fa-sort-up">&nbsp;Upgrade</i></button>
              </div>
			  </div>

			 </form> 

</div>	
</div>	
</div>
</div>