<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	 
	 <?php echo ($disapprove_withdrawal_request == 1) ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'" name="disapprove"><i class="fa fa-times"></i>&nbsp;Disapprove Withdrawal</button>' : ''; ?>
	
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
						<option value="all">All Withdrawal Request</option>
						<option value="Pending">Pending</option>
						<option value="Approved">Approved</option>
						<option value="Disapproved">Disapproved</option>

						<option disabled>Filter By Customer</option>
						<?php
						$get4 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND virtual_acctno != '' ORDER BY id") or die (mysqli_error($link));
						while($rows4 = mysqli_fetch_array($get4))
						{
						?>
						<option value="<?php echo $rows4['account']; ?>"><?php echo $rows4['virtual_acctno'].' - '.$rows4['lname'].' '.$rows4['fname'].' '.$rows4['mname']; ?></option>
						<?php } ?>

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

						<option disabled>Filter By Staff/Agent</option>
						<?php
						$get6 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY id") or die (mysqli_error($link));
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
			<table id="invest_wrequest_data" class="table table-bordered table-striped">
				<thead>
				<tr>
				  <th><input type="checkbox" id="select_all"/></th>
                  <th>Token</th>
				  <th>Agent Name</th>
				  <th>Vendor Name</th>
                  <th>Customer Name</th>
                  <th>Categories</th>
                  <th>Plan Name</th>
                  <th>Subscription Code</th>
                  <th>Amount Requested</th>
				  <th>Status</th>
				  <th>Date/Time</th>
				</tr>
				</thead>
			</table>
			</div>

			</div>

 
						<?php
						if(isset($_POST['disapprove'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='withdrawal_request.php?tid=".$_SESSION['tid']."&&mid=NDkw'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"UPDATE mcustomer_wrequest SET status = 'Disapproved' WHERE id ='$id[$i]'");
								echo "<script>alert('Request Disapproved Successfully!!!'); </script>";
								echo "<script>window.location='withdrawal_request.php?id=".$_SESSION['tid']."&&mid=NDkw'; </script>";
							}
							}
							}
						?>	
						
</form>
				

              </div>


	
</div>	
</div>
</div>	
</div>