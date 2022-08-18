<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-globe"></i>  Subscription Form</h3>
            </div>

             <div class="box-body">
              
<form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_saassub1.php">

<div class="box-body">

  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Subscription Plan</label>
                  <div class="col-sm-9">
                  <select name="saas_subplan" class="form-control select2" id="saas_subplan1" required>
                  <option selected>Select Subscription Plan</option>
<?php
$search_subplan = mysqli_query($link, "SELECT DISTINCT(plan_category) FROM saas_subscription_plan WHERE status = 'Active'");
while($fetch_saasplan = mysqli_fetch_array($search_subplan)){
?>
                   <option value="<?php echo $fetch_saasplan['plan_category']; ?>"><?php echo $fetch_saasplan['plan_category']; ?></option>
<?php } ?>
                  </select>
                  </div>
                  </div>

                  <span id='ShowValueFrank'></span>
                  <span id='ShowValueFrank'></span>
                  <span id='ShowValueFrank'></span>
                  <span id='ShowValueFrank'></span>
                  <span id='ShowValueFrank'></span>


</div>
<hr>

<span style="color: blue; font-size: 18px;"><b>NOTE:</b> For More Customers / Members / Staffs to be allowed for Institution, Cooperatives and Agent, Please kindly contact our support for negotiation.</span>

 </form>

</div>	
</div>	
</div>
</div>