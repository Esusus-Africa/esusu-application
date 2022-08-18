<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-refresh"></i> Product Plan</h3>
            </div>
             <div class="box-body">
            
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
                 
            <input name="savings_plan2" type="hidden" class="form-control" id="savings_plan2" value="">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Search Product Plan</label>
                  <div class="col-sm-10">
                  <select name="savings_plan" class="select2" id="savings_plan" data-placeholder="Search By" style="width: 100%;" required>
                      <option value="" selected>---Select Plan---</option>
      				<option disabled>FILTER BY CATEGORY</option>
  	                <?php
  					$getin = mysqli_query($link, "SELECT DISTINCT categories FROM savings_plan WHERE status = 'Active'") or die (mysqli_error($link));
  					while($row = mysqli_fetch_array($getin))
  					{
  					    echo '<option value="'.$row['categories'].'">'.$row['categories'].'</option>';
  					}
  					?>
  					
  					<option disabled>FILTER BY PLAN NAME</option>
                  <?php
                    $getin = mysqli_query($link, "SELECT plan_name FROM savings_plan WHERE status = 'Active'") or die (mysqli_error($link));
  					while($row = mysqli_fetch_array($getin))
  					{
  					    echo '<option value="'.$row['plan_name'].'">'.$row['plan_name'].'</option>';
  					}
  					?>
  					
  					<option disabled>FILTER BY VENDOR</option>
                  <?php
                    $getin = mysqli_query($link, "SELECT DISTINCT branchid FROM savings_plan WHERE status = 'Active'") or die (mysqli_error($link));
  					while($row = mysqli_fetch_array($getin))
  					{
  					    $myvendorid = $row['branchid'];
  					    $search_vendo = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$myvendorid'");
  					    $fetch_vendo = mysqli_fetch_array($search_vendo);
  					    
  					    echo '<option value="'.$myvendorid.'">'.$fetch_vendo['cname'].'</option>';
  					}
  					?>
                  </select>
				  </div>
            </div>

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