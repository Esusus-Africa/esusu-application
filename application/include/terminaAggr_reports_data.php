<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title">Aggregate Terminal Report</h3>
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
                    <option value="all">All Aggregate Terminal Report</option>

                    <option disabled>Filter By Channel</option>
                    <option value="USSD">USSD</option>
                    <option value="POS">POS</option>

                    <option disabled>Filter By Terminal</option>
                    <?php
                    $get = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_status = 'Assigned'") or die (mysqli_error($link));
                    while($rows = mysqli_fetch_array($get))
                    {
                        $terminalId = $rows['terminal_id'];
                    ?>
                    <option value="<?php echo $terminalId; ?>"><?php echo $rows['terminal_id'].' - '.$rows['terminal_model_code'].' ('.$rows['channel'].')'; ?></option>
                    <?php } ?>

                    <option disabled>Filter By Institution</option>
                    <?php
                    $get2 = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved' ORDER BY id DESC") or die ("Error: " . mysqli_error($link));
    				while($rows2 = mysqli_fetch_array($get2))
    				{
    				?>
    				<option value="<?php echo $rows2['institution_id']; ?>"><?php echo $rows2['institution_name'].' - '.$rows2['institution_id']; ?></option>
                    <?php } ?>
                    
                    <option disabled>Filter By Operator</option>
                    <?php
                    $get2 = mysqli_query($link, "SELECT * FROM user WHERE virtual_acctno != '' AND reg_type = 'agent' ORDER BY userid DESC") or die ("Error: " . mysqli_error($link));
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
			    <table id="fetch_terminalAggr_data" class="table table-bordered table-striped">
			        <thead>
                    <tr>
                      <th><input type="checkbox" id="select_all"/></th>
                      <th>Client Name</th>
    				  <th>Total Trans. Count</th>
                      <th>Total Trans. Volume</th>
                      <th>Total Amount Declined</th>
                      <th>Total Amount Approved</th>
                      <th>Total Charges</th>
                      <th>Total Stampduty</th>
                      <th>Total Pending Balance</th>
                      <th>Total Settled Amount</th>
                      <th>Total Transfer Balance</th>
                     </tr>
                    </thead>
                </table>
            </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>