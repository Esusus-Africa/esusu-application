<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-area-chart"></i>  Report</h3>
			</div>
			

			<div class="box-body">

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
					<!-- FILTER BY ALL COLLECTION REPORT -->
					<option value="all">All Collection Report</option>

					<option disabled>Filter By Client</option>
					<?php
					$get = mysqli_query($link, "SELECT * FROM institution_data ORDER BY id DESC") or die (mysqli_error($link));
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['institution_id']; ?>"><?php echo $rows['institution_name']; ?> - [<?php echo $rows['institution_id']; ?>]</option>
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
					
					<option disabled>Filter By Client Branch</option>
					<?php
					$get5 = mysqli_query($link, "SELECT * FROM branches ORDER BY id DESC") or die (mysqli_error($link));
					while($rows5 = mysqli_fetch_array($get5))
					{
					?>
					<option value="<?php echo $rows5['branchid']; ?>"><?php echo $rows5['bname'].' ['.$rows5['branchid'].']'; ?></option>
					<?php } ?>

					<option disabled>Filter By Client Customer</option>
					<?php
					$get = mysqli_query($link, "SELECT * FROM borrowers ORDER BY id DESC") or die (mysqli_error($link));
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['account']; ?>"><?php echo $rows['account'].' - '.$rows['lname'].' '.$rows['fname'].' '.$rows['mname']; ?></option>
					<?php } ?>

					<option disabled>Filter By Client Staff / Sub-agent</option>
					<?php
					$get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY userid DESC") or die (mysqli_error($link));
					while($rows2 = mysqli_fetch_array($get2))
					{
					?>
					<option value="<?php echo $rows2['id']; ?>"><?php echo $rows2['name'].' '.$rows2['fname'].' '.$rows2['mname']; ?></option>
					<?php } ?>
				</select>
					</div>
				</div>
				</div>


				<hr>
				<div class="table-responsive">
				<table id="fetch_collectionrpt_data" class="table table-bordered table-striped">
					<thead>
					<tr>
						<th><input type="checkbox" id="select_all"/></th>
						<th>Reference ID</th>
						<th>Client Name</th>
						<th>Client Branch</th>
						<th>Client Vendor</th>
						<th>Staff/Sub-Agent</th>
						<th>Loan ID</th>
						<th>Loan Product</th>
						<th>Borrower's Name</th>
						<th>Amount Paid</th>
						<th>Loan Balance</th>
						<th>Pay Date</th>
						<th>Status</th>
					</tr>
				</thead>
				</table>
				</div>
				
			</div>



</div>	
</div>
</div>