<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
    <form method="post">

    <div class="box-body">
                 
	            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">To</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-24</span>
                  </div>

                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
                  <div class="col-sm-3">
                 	<select name="filter_by" id="filterBy" class="form-control select2" style="width:100%">
    					<option value="" selected="selected">Filter By...</option>
    					<option value="All">All Log</option>
    				
                    	<?php
                        ////TO BE ACCESSIBLE BY THE SUPER ADMIN ONLY
                        if($list_branches == "1")
                        {
                        ?>
                        <option disabled>Filter By Branch</option>
                        <?php
                        $get5 = mysqli_query($link, "SELECT * FROM branches WHERE created_by = '$institution_id' ORDER BY id") or die (mysqli_error($link));
                        while($rows5 = mysqli_fetch_array($get5))
                        {
                        ?>
                        <option value="<?php echo $rows5['branchid']; ?>"><?php echo $rows5['bname'].' ['.$rows5['branchid'].']'; ?></option>
                        <?php } ?>
                        <?php
                        }
                        else{
                            //nothing
                        }
                        ?>
                
                        <option disabled>Filter By Staff</option>
                        <?php
                        ($list_employee == "1" && $list_branch_employee != "1") ? $get6 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY id") : "";
                        ($list_employee != "1" && $list_branch_employee == "1") ? $get6 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' ORDER BY id") : "";
                        while($rows6 = mysqli_fetch_array($get6))
                        {
                        ?>
                        <option value="<?php echo $rows6['id']; ?>"><?php echo $rows6['name'].' '.$rows6['fname'].' '.$rows6['mname']; ?></option>
                        <?php } ?>

                        <option disabled>Filter By NIMN Partners</option>
                        <?php
                        $get7 = mysqli_query($link, "SELECT * FROM nimcPartner ORDER BY id DESC") or die (mysqli_error($link));
                        while($rows7 = mysqli_fetch_array($get7))
                        {
                        ?>
                        <option value="<?php echo $rows7['id']; ?>"><?php echo $rows7['partnerName']; ?></option>
                        <?php } ?>
					</select>
                  </div>
                </div>
                
    </div>
                
                
        <hr>
            <div class="table-responsive">
			    <table id="fetch_enrolleelog_data" class="table table-bordered table-striped">
			        <thead>
                    <tr>
                      <th><input type="checkbox" id="select_all"/></th>
                      <th>Branch</th>
                      <th>Operator</th>
                      <th>NIMC Partner</th>
    				  <th>IP Address</th>
                      <th>Browser Details</th>
					  <th>Activities Tracked</th>
                      <th>dateCreated</th>
                     </tr>
                    </thead>
                </table>
            </div>

    </form>

              </div>
	
</div>	
</div>
</div>	
</div>