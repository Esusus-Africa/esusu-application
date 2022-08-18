<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="saassub_plan.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDIw"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-spine"></i>  Update Plan</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['update']))
{
  $spid = $_GET['spid'];
	$plan_code =  mysqli_real_escape_string($link, $_POST['plan_code']);
  $plan_cat =  mysqli_real_escape_string($link, $_POST['plan_cat']);
	$plan_name =  mysqli_real_escape_string($link, $_POST['plan_name']);
	$amount_per_months =  mysqli_real_escape_string($link, $_POST['amount_per_months']);
	$sms_allocated =  mysqli_real_escape_string($link, $_POST['sms_allocated']);
  $staff_limit =  mysqli_real_escape_string($link, $_POST['staff_limit']);
  $branch_limit =  mysqli_real_escape_string($link, $_POST['branch_limit']);
  $customer_limit = mysqli_real_escape_string($link, $_POST['customer_limit']);
	$expiration_grace =  mysqli_real_escape_string($link, $_POST['expiration_grace']);
  $others =  mysqli_real_escape_string($link, $_POST['others']);
  $status =  mysqli_real_escape_string($link, $_POST['status']);
		
	$update = mysqli_query($link, "UPDATE saas_subscription_plan SET plan_name = '$plan_name', amount_per_months = '$amount_per_months', expiration_grace = '$expiration_grace', status = '$status', sms_allocated = '$sms_allocated', staff_limit = '$staff_limit', branch_limit = '$branch_limit', customers_limit = '$customer_limit', others = '$others' WHERE id = '$spid'") or die ("Error: " . mysqli_error($link));
	if(!$update)
	{
		echo "<div class='alert bg-orange'>Error.....Please try again later</div>";
	}
	else{
		echo "<div class='alert bg-blue'>Plan Update Successfully!</div>";
	}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
$spid = $_GET['spid'];
$search_saasplan = mysqli_query($link, "SELECT * FROM saas_subscription_plan WHERE id = '$spid'");
$fetch_saasplan = mysqli_fetch_object($search_saasplan);
$plan_cat = $fetch_saasplan->plan_category;
?>
<?php
if($plan_cat == "Institution")
{
  ?>
             <div class="box-body">

      <input name="plan_cat" type="hidden" class="form-control" value="<?php echo $plan_cat; ?>">
			 			
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Code</label>
                  <div class="col-sm-9">
                  <input name="plan_code" type="text" class="form-control" value="<?php echo $fetch_saasplan->plan_code; ?>" readonly>
                  </div>
                  </div>
				  
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Name</label>
                  <div class="col-sm-9">
                  <input name="plan_name" type="text" class="form-control" value="<?php echo $fetch_saasplan->plan_name; ?>" placeholder="Plan Name" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-9">
                  <input name="amount_per_months" type="text" class="form-control" value="<?php echo $fetch_saasplan->amount_per_months; ?>" placeholder="Amount on monthly basis" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">SMS Allocated</label>
                  <div class="col-sm-9">
                  <input name="sms_allocated" type="text" class="form-control" value="<?php echo $fetch_saasplan->sms_allocated; ?>" placeholder="SMS Unit Allocated" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Staff/Member Limit</label>
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
                  <input name="customer_limit" type="text" class="form-control" value="<?php echo $fetch_saasplan->customers_limit; ?>" placeholder="Number of Customers Regstration Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Expiration Grace</label>
                  <div class="col-sm-9">
                  <input name="expiration_grace" type="number" class="form-control" value="<?php echo $fetch_saasplan->expiration_grace; ?>" placeholder="E.g 0, 3, 5 days" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Others</label>
                  <div class="col-sm-9">
                  <textarea name="others" id="editor1" class="form-control" rows="5" cols="80" placeholder="Enter Other Features Here"><?php echo $fetch_saasplan->others; ?></textarea>
                  </div>
                  </div>

          <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-9">
            <select name="status"  class="form-control select2" required>
                    <option value="<?php echo $fetch_saasplan->status; ?>" selected='selected'><?php echo $fetch_saasplan->status; ?></option>
                    <option value="Active">Active</option>
                    <option value="Not-Active">Not-Active</option>
            </select>
          </div>
          </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="update" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>

<?php 
}
if($plan_cat == "Cooperatives")
{
?>			  
        <input name="plan_cat" type="hidden" class="form-control" value="<?php echo $plan_cat; ?>">
            
      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Code</label>
                  <div class="col-sm-9">
                  <input name="plan_code" type="text" class="form-control" value="<?php echo $fetch_saasplan->plan_code; ?>" readonly>
                  </div>
                  </div>
          
      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Name</label>
                  <div class="col-sm-9">
                  <input name="plan_name" type="text" class="form-control" value="<?php echo $fetch_saasplan->plan_name; ?>" placeholder="Plan Name" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-9">
                  <input name="amount_per_months" type="text" class="form-control" value="<?php echo $fetch_saasplan->amount_per_months; ?>" placeholder="Amount on monthly basis" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">SMS Allocated</label>
                  <div class="col-sm-9">
                  <input name="sms_allocated" type="text" class="form-control" value="<?php echo $fetch_saasplan->sms_allocated; ?>" placeholder="SMS Unit Allocated" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Members Limit</label>
                  <div class="col-sm-9">
                  <input name="staff_limit" type="text" class="form-control" value="<?php echo $fetch_saasplan->staff_limit; ?>" placeholder="Number of Members Regstration Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Expiration Grace</label>
                  <div class="col-sm-9">
                  <input name="expiration_grace" type="number" class="form-control" value="<?php echo $fetch_saasplan->expiration_grace; ?>" placeholder="E.g 0, 3, 5 days" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Others</label>
                  <div class="col-sm-9">
                  <textarea name="others" id="editor1" class="form-control" rows="5" cols="80" placeholder="Enter Other Features Here"><?php echo $fetch_saasplan->others; ?></textarea>
                  </div>
                  </div>

          <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-9">
            <select name="status"  class="form-control select2" required>
                    <option value="<?php echo $fetch_saasplan->status; ?>" selected='selected'><?php echo $fetch_saasplan->status; ?></option>
                    <option value="Active">Active</option>
                    <option value="Not-Active">Not-Active</option>
            </select>
          </div>
          </div>
      
       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="update" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
        </div>

<?php
}
if($plan_cat == "Merchant")
{
?>

      <input name="plan_cat" type="hidden" class="form-control" value="<?php echo $plan_cat; ?>">
            
      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Code</label>
                  <div class="col-sm-9">
                  <input name="plan_code" type="text" class="form-control" value="<?php echo $fetch_saasplan->plan_code; ?>" readonly>
                  </div>
                  </div>
          
      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Name</label>
                  <div class="col-sm-9">
                  <input name="plan_name" type="text" class="form-control" value="<?php echo $fetch_saasplan->plan_name; ?>" placeholder="Plan Name" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-9">
                  <input name="amount_per_months" type="text" class="form-control" value="<?php echo $fetch_saasplan->amount_per_months; ?>" placeholder="Amount on monthly basis" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">SMS Allocated</label>
                  <div class="col-sm-9">
                  <input name="sms_allocated" type="text" class="form-control" value="<?php echo $fetch_saasplan->sms_allocated; ?>" placeholder="SMS Unit Allocated" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Expiration Grace</label>
                  <div class="col-sm-9">
                  <input name="expiration_grace" type="number" class="form-control" value="<?php echo $fetch_saasplan->expiration_grace; ?>" placeholder="E.g 0, 3, 5 days" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Others</label>
                  <div class="col-sm-9">
                  <textarea name="others" id="editor1" class="form-control" rows="5" cols="80" placeholder="Enter Other Features Here"><?php echo $fetch_saasplan->others; ?></textarea>
                  </div>
                  </div>

          <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-9">
            <select name="status"  class="form-control select2" required>
                    <option value="<?php echo $fetch_saasplan->status; ?>" selected='selected'><?php echo $fetch_saasplan->status; ?></option>
                    <option value="Active">Active</option>
                    <option value="Not-Active">Not-Active</option>
            </select>
          </div>
          </div>
      
       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="update" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
        </div>

<?php
}
if($plan_cat == "Agent")
{
?>

        <input name="plan_cat" type="hidden" class="form-control" value="<?php echo $plan_cat; ?>">
            
      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Code</label>
                  <div class="col-sm-9">
                  <input name="plan_code" type="text" class="form-control" value="<?php echo $fetch_saasplan->plan_code; ?>" readonly>
                  </div>
                  </div>
          
      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Name</label>
                  <div class="col-sm-9">
                  <input name="plan_name" type="text" class="form-control" value="<?php echo $fetch_saasplan->plan_name; ?>" placeholder="Plan Name" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-9">
                  <input name="amount_per_months" type="text" class="form-control" value="<?php echo $fetch_saasplan->amount_per_months; ?>" placeholder="Amount on monthly basis" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">SMS Allocated</label>
                  <div class="col-sm-9">
                  <input name="sms_allocated" type="text" class="form-control" value="<?php echo $fetch_saasplan->sms_allocated; ?>" placeholder="SMS Unit Allocated" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Customers Limit</label>
                  <div class="col-sm-9">
                  <input name="customer_limit" type="text" class="form-control" value="<?php echo $fetch_saasplan->customers_limit; ?>" placeholder="Number of Customers Regstration Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Expiration Grace</label>
                  <div class="col-sm-9">
                  <input name="expiration_grace" type="number" class="form-control" value="<?php echo $fetch_saasplan->expiration_grace; ?>" placeholder="E.g 0, 3, 5 days" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Others</label>
                  <div class="col-sm-9">
                  <textarea name="others" id="editor1" class="form-control" rows="5" cols="80" placeholder="Enter Other Features Here"><?php echo $fetch_saasplan->others; ?></textarea>
                  </div>
                  </div>

          <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-9">
            <select name="status"  class="form-control select2" required>
                    <option value="<?php echo $fetch_saasplan->status; ?>" selected='selected'><?php echo $fetch_saasplan->status; ?></option>
                    <option value="Active">Active</option>
                    <option value="Not-Active">Not-Active</option>
            </select>
          </div>
          </div>
      
       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="update" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
        </div>

<?php
}
if($plan_cat == "demo")
{
?>

        <input name="plan_cat" type="hidden" class="form-control" value="<?php echo $plan_cat; ?>">
            
      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Code</label>
                  <div class="col-sm-9">
                  <input name="plan_code" type="text" class="form-control" value="<?php echo $fetch_saasplan->plan_code; ?>" readonly>
                  </div>
                  </div>
          
      <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Name</label>
                  <div class="col-sm-9">
                  <input name="plan_name" type="text" class="form-control" value="<?php echo $fetch_saasplan->plan_name; ?>" placeholder="Plan Name" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-9">
                  <input name="amount_per_months" type="text" class="form-control" value="<?php echo $fetch_saasplan->amount_per_months; ?>" placeholder="Amount on monthly basis" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">SMS Allocated</label>
                  <div class="col-sm-9">
                  <input name="sms_allocated" type="text" class="form-control" value="<?php echo $fetch_saasplan->sms_allocated; ?>" placeholder="SMS Unit Allocated" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Customers Limit</label>
                  <div class="col-sm-9">
                  <input name="customer_limit" type="text" class="form-control" value="<?php echo $fetch_saasplan->customers_limit; ?>" placeholder="Number of Customers Regstration Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Others</label>
                  <div class="col-sm-9">
                  <textarea name="others" id="editor1" class="form-control" rows="5" cols="80" placeholder="Enter Other Features Here"><?php echo $fetch_saasplan->others; ?></textarea>
                  </div>
                  </div>

          <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Status</label>
                  <div class="col-sm-9">
            <select name="status"  class="form-control select2" required>
                    <option value="<?php echo $fetch_saasplan->status; ?>" selected='selected'><?php echo $fetch_saasplan->status; ?></option>
                    <option value="Active">Active</option>
                    <option value="Not-Active">Not-Active</option>
            </select>
          </div>
          </div>
      
       </div>
       
        <div align="right">
              <div class="box-footer">
                        <button name="update" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
        </div>
<?php } ?>

			 </form> 

</div>	
</div>	
</div>
</div>