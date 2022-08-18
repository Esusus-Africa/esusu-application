<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-area-chart"></i>  Report</h3>
            </div>

             <div class="box-body">
			 
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			 
			
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
                    <option value="all">All Saas Subscription Report</option>
                    <option value="Pending">Pending</option>
                    <option value="Paid">Paid</option>

					<option disabled>Filter By Usage Status</option>
                    <option value="Expired">Expired</option>
					<option value="Deactivated">Deactivated</option>
					<option value="NotActive">Not-Active</option>
					<option value="Active">Active</option>

                    <option disabled>Filter By Client</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM institution_data ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_name']; ?> - [<?php echo $rows['institution_id']; ?>]</option>
                    <?php } ?>

                    <option disabled>Filter By Saas Subscription Plan</option>
                    <?php
                    $get2 = mysqli_query($link, "SELECT * FROM saas_subscription_plan ORDER BY id DESC") or die (mysqli_error($link));
                    while($rows2 = mysqli_fetch_array($get2))
                    {
                    ?>
                    <option value="<?php echo $rows2['plan_code']; ?>"><?php echo $rows2['plan_code'].' - '.$rows2['plan_name']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              </div>

              <hr>
              <div class="table-responsive">
              <table id="saaspayment_history_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Action</th>
                  <th>Status</th>
                  <th>Client Name</th>
                  <th>Phone Number</th>
                  <th>RefID</th>
                  <th>Unique ID <p style="font-size: 12px;" align="center"> (Subcription Token)</p></th>
                  <th>Plan Code</th>
				  <th>Plan Name</th>
				  <th>Amount Paid</th>
				  <th>Expired Date</th>
				  <th>Trans. Date</th>
                </tr>
                </thead>
              </table>
              </div>
       
             </div>
			

</div>	
</div>	
</div>
</div>