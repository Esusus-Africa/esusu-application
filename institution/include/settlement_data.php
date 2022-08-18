<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
    	
	<hr>	

  <div class="box-body">

             <div class="box-body">

              <div class="form-group">
                <label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
                <div class="col-sm-3">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
                  <span style="color: blue;">Date format: 2018-05-01</span>
                </div>
              
                <label for="" class="col-sm-1 control-label" style="color:blue;">End Date</label>
                <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
                  <span style="color: blue;">Date format: 2018-05-24</span>
                </div>

                <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                <div class="col-sm-3">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter By...</option>
                    <option value="all">All Settlement History</option>
                    <option value="Pending">Pending</option>
                    <option value="Declined">Declined</option>
                    <option value="Settled">Settled</option>

                    <option disabled>Filter By Vendor</option>
                    <?php
                    $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND reg_type = 'vendor' ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows2 = mysqli_fetch_array($get2))
                    {
                    $vendorid = $rows2['branchid'];
                    $searchVName = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
                    $fetchVName = mysqli_fetch_array($searchVName);
                    ?>
                    <option value="<?php echo $vendorid; ?>"><?php echo $fetchVName['cname'].' - '.$rows2['virtual_acctno']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              </div>

              <hr>
              <div class="table-responsive">
              <table id="settlement_history_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Status</th>
                  <th>Reference</th>
                  <th>Vendor Name</th>
                  <th>Vendor Contact</th>
                  <th>Request Amount</th>
                  <th>Destination Channel</th>
                  <th>A/c Details</th>
                  <th>DateTime</th>
                </tr>
                </thead>
              </table>
              </div>
       
             </div>
                
						
</form>
				

              </div>


	
</div>	
</div>
</div>	
</div>