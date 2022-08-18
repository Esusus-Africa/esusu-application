<?php
include ("../config/connect.php");
$PostType = $_GET['PostType'];

if($PostType == "Institution")
{
?>

		<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Name</label>
                  <div class="col-sm-9">
                  <input name="plan_name" type="text" class="form-control" placeholder="Plan Name" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-9">
                  <input name="amount_per_months" type="text" class="form-control" placeholder="Amount on monthly basis" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">SMS Allocated</label>
                  <div class="col-sm-9">
                  <input name="sms_allocated" type="text" class="form-control" placeholder="SMS Unit Allocated" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Staff Limit</label>
                  <div class="col-sm-9">
                  <input name="staff_limit" type="text" class="form-control" placeholder="Number of Staff Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Branch Limit</label>
                  <div class="col-sm-9">
                  <input name="branch_limit" type="text" class="form-control" placeholder="Number of Branches Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Customers Limit</label>
                  <div class="col-sm-9">
                  <input name="customer_limit" type="text" class="form-control" placeholder="Number of Customers Regstration Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Expiration Grace</label>
                  <div class="col-sm-9">
                  <input name="expiration_grace" type="number" class="form-control" placeholder="E.g 0, 3, 5 days" required>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Group</label>
                  <div class="col-sm-9">
                  <input name="no_group" type="number" class="form-control" placeholder="E.g 1, 3, 5 group(s)" required>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Loan Product</label>
                  <div class="col-sm-9">
                  <input name="no_lproduct" type="number" class="form-control" placeholder="E.g 1, 3, 5 loan product(s)" required>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Saving Product</label>
                  <div class="col-sm-9">
                  <input name="no_sproduct" type="number" class="form-control" placeholder="E.g 1, 3, 5 saving product(s)" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Modules/Features</label>
                  <div class="col-sm-9">
                  <select name="others[]" class="form-control select2" style="width: 100%;" multiple="multiple" /required>
                        <option disabled>SELECT MODULES</option>
                        <?php
                        $searchModule = mysqli_query($link, "SELECT * FROM module_pricing");
                        while($fetchModule = mysqli_fetch_array($searchModule)){
                              echo '<option value="'.$fetchModule['mname'].'">'.$fetchModule['mname'].'</option>';
                        }
                        ?>
                  </select>
                  </div>
                  </div>
			<hr>

			<div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
		
<?php
}
elseif($PostType == "Cooperatives")
{
?>

			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Name</label>
                  <div class="col-sm-9">
                  <input name="plan_name" type="text" class="form-control" placeholder="Plan Name" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-9">
                  <input name="amount_per_months" type="text" class="form-control" placeholder="Amount on monthly basis" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">SMS Allocated</label>
                  <div class="col-sm-9">
                  <input name="sms_allocated" type="text" class="form-control" placeholder="SMS Unit Allocated" required>
                  </div>
                  </div>

             <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Members Limit</label>
                  <div class="col-sm-9">
                  <input name="staff_limit" type="text" class="form-control" placeholder="Number of Members Regstration Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Expiration Grace</label>
                  <div class="col-sm-9">
                  <input name="expiration_grace" type="number" class="form-control" placeholder="E.g 0, 3, 5 days" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Others</label>
                  <div class="col-sm-9">
                  <textarea name="others" id="editor1" class="form-control" rows="5" cols="80" placeholder="Enter Other Features Here"></textarea>
                  </div>
                  </div>
			<hr>

			<div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>

<?php
}
elseif($PostType == "Merchant")
{
?>

			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Name</label>
                  <div class="col-sm-9">
                  <input name="plan_name" type="text" class="form-control" placeholder="Plan Name" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-9">
                  <input name="amount_per_months" type="text" class="form-control" placeholder="Amount on monthly basis" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">SMS Allocated</label>
                  <div class="col-sm-9">
                  <input name="sms_allocated" type="text" class="form-control" placeholder="SMS Unit Allocated" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Staff Limit</label>
                  <div class="col-sm-9">
                  <input name="staff_limit" type="text" class="form-control" placeholder="Number of Staff Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Branch Limit</label>
                  <div class="col-sm-9">
                  <input name="branch_limit" type="text" class="form-control" placeholder="Number of Branches Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Customers Limit</label>
                  <div class="col-sm-9">
                  <input name="customer_limit" type="text" class="form-control" placeholder="Number of Customers Regstration Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Expiration Grace</label>
                  <div class="col-sm-9">
                  <input name="expiration_grace" type="number" class="form-control" placeholder="E.g 0, 3, 5 days" required>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Group</label>
                  <div class="col-sm-9">
                  <input name="no_group" type="number" class="form-control" placeholder="E.g 1, 3, 5 group(s)" required>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Loan Product</label>
                  <div class="col-sm-9">
                  <input name="no_lproduct" type="number" class="form-control" placeholder="E.g 1, 3, 5 loan product(s)" required>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Saving Product</label>
                  <div class="col-sm-9">
                  <input name="no_sproduct" type="number" class="form-control" placeholder="E.g 1, 3, 5 saving product(s)" required>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Investment Product</label>
                  <div class="col-sm-9">
                  <input name="no_invproduct" type="number" class="form-control" placeholder="E.g 1, 3, 5 Investment product(s)" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Modules/Features</label>
                  <div class="col-sm-9">
                  <select name="others[]" class="form-control select2" style="width: 100%;" multiple="multiple" /required>
                  <option disabled>SELECT MODULES</option>
                  <?php
                  $searchModule = mysqli_query($link, "SELECT * FROM module_pricing");
                  while($fetchModule = mysqli_fetch_array($searchModule)){
                        echo '<option value="'.$fetchModule['mname'].'">'.$fetchModule['mname'].'</option>';
                  }
                  ?>
                  </select>
                  </div>
                  </div>
			<hr>

			<div align="right">
                        <div class="box-footer">
                              <button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>
                        </div>
			</div>


<?php
}
elseif($PostType == "Agent")
{
?>

			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Name</label>
                  <div class="col-sm-9">
                  <input name="plan_name" type="text" class="form-control" placeholder="Plan Name e.g Flat Rate" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-9">
                  <input name="amount_per_months" type="text" class="form-control" placeholder="Amount on monthly basis" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">SMS Allocated</label>
                  <div class="col-sm-9">
                  <input name="sms_allocated" type="text" class="form-control" placeholder="SMS Unit Allocated" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Staff Limit</label>
                  <div class="col-sm-9">
                  <input name="staff_limit" type="text" class="form-control" placeholder="Number of Staff Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Branch Limit</label>
                  <div class="col-sm-9">
                  <input name="branch_limit" type="text" class="form-control" placeholder="Number of Branches Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Customers Limit</label>
                  <div class="col-sm-9">
                  <input name="customer_limit" type="text" class="form-control" placeholder="Number of Customers Regstration Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Expiration Grace</label>
                  <div class="col-sm-9">
                  <input name="expiration_grace" type="number" class="form-control" placeholder="E.g 0, 3, 5 days" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Modules/Features</label>
                  <div class="col-sm-9">
                  <select name="others[]" class="form-control select2" style="width: 100%;" multiple="multiple" /required>
                  <option disabled>SELECT MODULES</option>
                  <?php
                  $searchModule = mysqli_query($link, "SELECT * FROM module_pricing");
                  while($fetchModule = mysqli_fetch_array($searchModule)){
                        echo '<option value="'.$fetchModule['mname'].'">'.$fetchModule['mname'].'</option>';
                  }
                  ?>
                  </select>
                  </div>
                  </div>
			<hr>

			<div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>

<?php
}
elseif($PostType == "demo")
{
?>

			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Name</label>
                  <div class="col-sm-9">
                  <input name="plan_name" type="text" class="form-control" placeholder="Plan Name e.g Flat Rate" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-9">
                  <input name="amount_per_months" type="text" class="form-control" placeholder="Amount on monthly basis" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">SMS Allocated</label>
                  <div class="col-sm-9">
                  <input name="sms_allocated" type="text" class="form-control" placeholder="SMS Unit Allocated" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Customers Limit</label>
                  <div class="col-sm-9">
                  <input name="customer_limit" type="text" class="form-control" placeholder="Number of Customers Regstration Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Modules/Features</label>
                  <div class="col-sm-9">
                  <select name="others[]" class="form-control select2" style="width: 100%;" multiple="multiple" /required>
                  <option disabled>SELECT MODULES</option>
                  <?php
                  $searchModule = mysqli_query($link, "SELECT * FROM module_pricing");
                  while($fetchModule = mysqli_fetch_array($searchModule)){
                        echo '<option value="'.$fetchModule['mname'].'">'.$fetchModule['mname'].'</option>';
                  }
                  ?>
                  </select>
                  </div>
                  </div>
			<hr>

			<div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>

<?php } ?>
