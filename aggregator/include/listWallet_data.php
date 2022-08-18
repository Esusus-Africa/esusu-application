<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title">All Wallet</h3>
            </div>



             <div class="box-body">

			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 
			<div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
				  <span style="color: orange;">Date format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">To</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
				  <span style="color: orange;">Date format: 2018-05-24</span>
                  </div>

                  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter By</label>
                  <div class="col-sm-3">
                 <select name="filter_by" id="filterBy" class="form-control select2" style="width:100%">
    				<option value="" selected="selected">Filter By...</option>
                    <option value="all1">All Agent Wallet</option>
                    
                    <option disabled>Filter By Agent Wallet User</option>
                    <?php
                    $get2 = mysqli_query($link, "SELECT * FROM user WHERE virtual_acctno != '' AND acctOfficer = '$aggrid' AND reg_type = 'agent' ORDER BY userid DESC") or die ("Error: " . mysqli_error($link));
    				while($rows2 = mysqli_fetch_array($get2))
    				{
    				?>
    				<option value="<?php echo $rows2['id']; ?>"><?php echo $rows2['virtual_acctno'].' - '.$rows2['name'].' '.$rows2['fname'].' '.$rows2['mname']; ?></option>
                    <?php } ?>
				</select>
                  </div>
                </div>
            
            
            <hr>
            <div class="table-responsive">
			    <table id="fetch_allwallet_data" class="table table-bordered table-striped">
			        <thead>
                    <tr>
                      <th><input type="checkbox" id="select_all"/></th>
                      <th>Actions</th>
                      <th>Acct. Type</th>
                      <th>Acct. Officer</th>
    				  <th>Acct. Number</th>
                      <th>Acct. Name</th>
                      <th>Bank Name</th>
                      <th>Phone Number</th>
                      <th>Email Address</th>
                      <th>Wallet Balance</th>
                      <th>Opening Date</th>
    				  <th>Status</th>
                     </tr>
                    </thead>
                </table>
            </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>