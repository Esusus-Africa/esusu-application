<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

      <a href="dashboard.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 
	
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
						<option value="all">All Savings Subscription</option>
						<option value="Pending">Pending</option>
						<option value="Approved">Approved</option>
						<option value="Stop">Stop</option>

						<option disabled>Filter By Customer</option>
						<?php
            			($individual_customer_records != "1" && $branch_customer_records != "1") ? $get4 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND virtual_acctno != '' ORDER BY id") or die (mysqli_error($link)) : "";
    					($individual_customer_records === "1" && $branch_customer_records != "1") ? $get4 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND virtual_acctno != '' ORDER BY id") or die (mysqli_error($link)) : "";
    					($individual_customer_records != "1" && $branch_customer_records === "1") ? $get4 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND virtual_acctno != '' ORDER BY id") or die (mysqli_error($link)) : "";
						while($rows4 = mysqli_fetch_array($get4))
						{
						?>
						<option value="<?php echo $rows4['account']; ?>"><?php echo $rows4['virtual_acctno'].' - '.$rows4['lname'].' '.$rows4['fname'].' '.$rows4['mname']; ?></option>
						<?php } ?>
            
            			<?php
    					////TO BE ACCESSIBLE BY PEOPLE WHO HAVE ACCESS TO VIEW VENDOR
    					if($list_vendor === "1")
    					{
    					?>

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
            
						<?php
						}
						else{
						echo "";
						}
						?>

						<option disabled>Filter By Staff/Agent</option>
						<?php
						($list_employee === "1" && $list_branch_employee != "1") ? $get6 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY id") or die (mysqli_error($link)) : "";
            			($list_employee != "1" && $list_branch_employee === "1") ? $get6 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' ORDER BY id") or die (mysqli_error($link)) : "";
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
			<table id="savings_sub_data" class="table table-bordered table-striped">
				<thead>
				<tr>
					<th><input type="checkbox" id="select_all"/></th>
					<th>Action</th>
					<th>Agent Name</th>
					<th>Vendor Name</th>
					<th>Plan Name</th>
					<th>Customer Name</th>
					<th>Subscription Code</th>
					<th>Amount</th>
					<th>Duration</th>
					<th>Status</th>
					<th>Activation Date</th>
					<th>Maturity Date</th>
					<th>Expected Amount</th>
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