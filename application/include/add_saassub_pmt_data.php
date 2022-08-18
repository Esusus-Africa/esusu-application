<div class="box">
	      <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-dollar"></i> New Subscription Payment</h3>
            </div>
             <div class="box-body">

			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
				
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Subscription Plan</label>
                  <div class="col-sm-9">
                  <select name="saas_subplan2" class="form-control select2" id="saas_subplan2" required>
                  <option value="" selected>Select Subscription Plan</option>
<?php
$search_subplan = mysqli_query($link, "SELECT DISTINCT(plan_category) FROM saas_subscription_plan WHERE status = 'Active'");
while($fetch_saasplan = mysqli_fetch_array($search_subplan)){
?>
                   <option value="<?php echo $fetch_saasplan['plan_category']; ?>"><?php echo $fetch_saasplan['plan_category']; ?></option>
<?php } ?>
                  </select>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Categories</label>
                  <div class="col-sm-9">
                  <select name="saas_category" class="form-control select2" id="saas_category" required>
                        <option value="" selected>Select Subscription Category</option>
                        <option value="PreStarter">PreStarter</option>
                        <option value="Starter">Starter</option>
                        <option value="Standard">Standard</option>
                        <option value="Premium">Premium</option>
                  </select>
                  </div>
                  </div>

                  <span id='ShowValueFrank'></span>

			</div>
			  </form>
			  

           
</div>	
</div>
</div>	
</div>