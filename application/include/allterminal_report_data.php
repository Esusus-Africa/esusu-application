<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-link"></i> Terminal Report</h3>
            </div>

             <div class="box-body">
           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
            
            <div class="box-body">
            
            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:blue;">From</label>
                  <div class="col-sm-3">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01" required>
				  <span style="color: orange;">Date format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:blue;">To</label>
                  <div class="col-sm-3">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24" required>
				  <span style="color: orange;">Date format: 2018-05-24</span>
                  </div>
                  
                  <label for="" class="col-sm-1 control-label" style="color:blue;">Filter</label>
                  <div class="col-sm-3">
                  <select name="ttype" id="transType" class="form-control select2" style="width:100%" required>
                    <option value="" selected="selected">Filter Report...</option>
                    <option value="all">All Terminal Reports</option>

                    <option disabled>Filter By Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Success">Successful</option>
                    <option value="Approved">Approved</option>
                    <option value="Declined">Declined</option>
                    <option value="Expired">Expired</option>
                    <option value="Reversed">Reversed</option>
                    <option value="Dormant Account">Dormant Account</option>
                    <option value="Blacklisted Account">Blacklisted Account</option>

                    <option disabled>Filter By Channel</option>
                    <option value="Pos">Pos</option>
                    <option value="USSD">USSD</option>
                    
                    <option disabled>Filter By Merchant</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved'") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                        $merchantId = $rows['institution_id'];
                    ?>
                    <option value="<?php echo $merchantId; ?>"><?php echo $rows['institution_name']; ?></option>
                    <?php } ?>

                    <option disabled>Filter By Terminal</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_status = 'Assigned'") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                        $terminalId = $rows['terminal_id'];
                    ?>
                    <option value="<?php echo $terminalId; ?>"><?php echo $rows['terminal_id'].' - '.$rows['terminal_model_code'].' ('.$rows['channel'].')'; ?></option>
                    <?php } ?>
                    
                    <option disabled>Filter By Staff</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM user WHERE virtual_acctno != '' AND comment = 'Approved'") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['id']; ?>"><?php echo $rows['virtual_acctno'].' - '.$rows['name'].' '.$rows['lname']; ?></option>
                    <?php } ?>
                  </select>
                  </div>
             </div>
             
            </div>
            
          <hr>
          <div class="table-responsive">
			<table id="terminal_report_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Date/Time</th>
                  <th>RefID</th>
                  <th>RRN</th>
                  <th>Terminal ID</th>
                  <th>Operator</th>
                  <th>Merchant Name</th>
                  <th>Channel</th>
                  <th>Amount</th>
                  <th>Charges</th>
                  <th>Amount to-Settled</th>
                  <th>Pending Balance</th>
                  <th>Transfer Balance</th>
                  <th>Status</th>
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