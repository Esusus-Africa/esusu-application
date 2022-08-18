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
                    <?php echo ($irole === "agent_manager" || $irole === "institution_super_admin" || $irole === "merchant_super_admin") ? '<option value="all">All Terminal Reports</option>' : '<option value="all1">All Terminal Reports</option>'; ?>

                    <option disabled>Filter By Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Success">Successful</option>
                    <option value="Approved">Approved</option>
                    <option value="Declined">Declined</option>
                    <option value="Expired">Expired</option>
                    <option value="Dormant Account">Dormant Account</option>
                    <option value="Blacklisted Account">Blacklisted Account</option>

                    <option disabled>Filter By Channel</option>
                    <option value="POS">Pos</option>
                    <option value="USSD">USSD</option>
            
                    <option disabled>Filter By Terminal</option>
                    <?php
                   ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $get = mysqli_query($link, "SELECT * FROM terminal_reg WHERE merchant_id = '$institution_id' AND terminal_status = 'Assigned'") or die (mysqli_error($link)) : "";
                   ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin") ? $get = mysqli_query($link, "SELECT * FROM terminal_reg WHERE merchant_id = '$institution_id' AND tidoperator = '$iuid' AND terminal_status = 'Assigned'") or die (mysqli_error($link)) : "";
                    while($rows = mysqli_fetch_array($get))
                    {
                        $terminalId = $rows['terminal_id'];
                    ?>
                    <option value="<?php echo $terminalId; ?>"><?php echo $rows['terminal_id'].' - '.$rows['terminal_issurer'].' ('.$rows['channel'].')'; ?></option>
                    <?php } ?>

                    <option disabled>Filter By Staff / Sub-Agent / Operator</option>
                    <?php
                    ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $get = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND comment = 'Approved'") or die (mysqli_error($link)) : "";
                    //($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin") ? $get = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$iuid' AND comment = 'Approved'") or die (mysqli_error($link)) : "";
                    while($rows = mysqli_fetch_array($get))
                    {
                        $sID = $rows['id'];
                    ?>
                    <option value="<?php echo $sID; ?>"><?php echo $rows['virtual_acctno'].' - '.$rows['name'].' '.$rows['lname'].' '.$rows['mname']; ?></option>
                    <?php } ?>
                  </select>
                  </div>
                  
                  <input name="irole" type="hidden" id="iRole" class="form-control" value="<?php echo $irole; ?>" required>
                  
             </div>
             
            </div>
            
          <hr>
          <div class="table-responsive">
			<table id="terminal_report_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>RefID</th>
                  <th>RRN</th>
                  <th>Operator</th>
                  <th>Branch</th>
                  <th>Channel</th>
                  <th>TID</th>
                  <th>TraceID/CardPan</th>
                  <th>Amount</th>
                  <th>Charges</th>
                  <th>Amount Settled</th>
                  <th>Pending Balance</th>
                  <th>Transfer Balance</th>
                  <th>Status</th>
                  <th>Date/Time</th>
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