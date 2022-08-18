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
						<option value="all">All Savings Transaction</option>
						<option value="pending">Pending</option>
						<option value="successful">Successful</option>

						<option disabled>Filter By Client</option>
						<?php
						$get = mysqli_query($link, "SELECT * FROM institution_data ORDER BY id DESC") or die (mysqli_error($link));
						while($rows = mysqli_fetch_array($get))
						{
						?>
						<option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_name']; ?> - [<?php echo $rows['institution_id']; ?>]</option>
						<?php } ?>

						<option disabled>Filter By Client Customer</option>
						<?php
						$get4 = mysqli_query($link, "SELECT * FROM borrowers WHERE virtual_acctno != '' ORDER BY id") or die (mysqli_error($link));
						while($rows4 = mysqli_fetch_array($get4))
						{
						?>
						<option value="<?php echo $rows4['account']; ?>"><?php echo $rows4['virtual_acctno'].' - '.$rows4['lname'].' '.$rows4['fname'].' '.$rows4['mname']; ?></option>
						<?php } ?>

						<option disabled>Filter By Client Vendor</option>
						<?php
						$get2 = mysqli_query($link, "SELECT * FROM user WHERE reg_type = 'vendor' ORDER BY id DESC") or die (mysqli_error($link));
						while($rows2 = mysqli_fetch_array($get2))
						{
						$vendorid = $rows2['branchid'];
						$searchVName = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
						$fetchVName = mysqli_fetch_array($searchVName);
						?>
						<option value="<?php echo $vendorid; ?>"><?php echo $fetchVName['cname'].' - '.$rows2['virtual_acctno']; ?></option>
						<?php } ?>

						<option disabled>Filter By Client Staff/Agent</option>
						<?php
						$get6 = mysqli_query($link, "SELECT * FROM user ORDER BY id") or die (mysqli_error($link));
						while($rows6 = mysqli_fetch_array($get6))
						{
						?>
						<option value="<?php echo $rows6['id']; ?>"><?php echo $rows6['name'].' '.$rows6['lname'].' '.$rows6['mname']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			
			</div>

			<hr>
			<div class="table-responsive">
			<table id="savings_trans_data" class="table table-bordered table-striped">
				<thead>
				<tr>
				  <th><input type="checkbox" id="select_all"/></th>
                  <th>Invoice Code</th>
				  <th>Client Name</th>
				  <th>Agent Name</th>
				  <th>Vendor Name</th>
                  <th>Customer Name</th>
                  <th>Plan Name</th>
                  <th>Subscription Code</th>
                  <th>Reference No.</th>
                  <th>Amount</th>
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
</div>