<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
			 
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 

	<?php echo ($delete_loan_record == '1') ? '<button type="submit" class="btn btn-flat bg-orange" name="delete"><i class="fa fa-times"></i>&nbsp;Multiple Delete</button>' : ''; ?>

	<?php
	$date_now = date("Y-m-d");
	$select = mysqli_query($link, "SELECT * FROM loan_info WHERE p_status = 'UNPAID' AND pay_date <= '$date_now' AND pay_date != ''") or die ("Error: " . mysqli_error($link));
	$num = mysqli_num_rows($select);	
	?>
	<?php echo ($view_due_loans == '1') ? '<button type="button" class="btn btn-flat bg-orange"><i class="fa fa-times"></i>&nbsp;Overdue:&nbsp;'.number_format($num,0,'.',',').'</button>' : ''; ?>
	
	<hr>
		
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
			<!-- FILTER BY ALL LOANS, INDIVIDUAL LOANS OR BRANCH LOANS -->
			<option value="all">All Loans</option>

			<!-- FILTER BY ALL PENDING LOANS -->
			<option value="pend">Pending Loans</option>

			<!-- FILTER BY ALL DISBURSED LOANS LOANS -->
			<option value="intrev">Loans Under-Review</option>

			<!-- FILTER BY ALL ACTIVE LOANS -->
			<option value="act">Active Loans</option>

			<!-- FILTER BY ALL PAID LOANS -->
			<option value="paid">Paid Loans</option>

			<!-- FILTER BY ALL APPROVED LOANS -->
			<option value="appr">Approved Loans</option>

			<!-- FILTER BY ALL DISAPPROVED LOANS -->
			<option value="disappr">Disapproved Loans</option>

			<!-- FILTER BY ALL DISBURSED LOANS -->
			<option value="disburs">Disbursed Loans</option>


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
		<table id="fetch_allloans_data" class="table table-bordered table-striped">
			<thead>
			<tr>
			<th><input type="checkbox" id="select_all"/></th>
			<th>Action</th>
			<th>Loan ID</th>
			<th>Client Name</th>
			<th>Client Branch</th>
			<th>Client Vendor</th>
			<th>RRR Number</th>
			<th>Account ID</th>
			<th>Contact Number</th>
			<th>Principal Amount</th>
			<th>Amount + Interest</th>
			<th>Booked By</th>
			<th>Last Reviewed By</th>
			<th>Date</th>
			<th>Status</th>
			</tr>
		</thead>
		</table>
		</div>


		<?php
				if(isset($_POST['delete'])){
					$idm = $_GET['id'];
					$id=$_POST['selector'];
					$N = count($id);
					if($id == ''){
						echo "<script>alert('$id Row Not Selected!!!'); </script>";	
						echo "<script>window.location='listloans.php?id=".$_SESSION['tid']."&&mid=NDA1&&tab=tab_1'; </script>";
					}
					else{
						for($i=0; $i < $N; $i++)
						{
							$search_loan_by_id = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id[$i]'");
							$getloan_lid = mysqli_fetch_object($search_loan_by_id);
							$get_lid = $getloan_lid->lid;
							
							$result = mysqli_query($link,"DELETE FROM loan_info WHERE id ='$id[$i]'");
							$result = mysqli_query($link,"DELETE FROM pay_schedule WHERE get_id ='$id[$i]'");
							$result = mysqli_query($link,"DELETE FROM payment_schedule WHERE lid ='$get_lid'");
							$result = mysqli_query($link,"DELETE FROM interest_calculator WHERE lid ='$get_lid'");
							$result = mysqli_query($link,"DELETE FROM attachment WHERE get_id ='$id[$i]'");
							$result = mysqli_query($link,"DELETE FROM battachment WHERE get_id ='$id[$i]'");
							//$result = mysqli_query($link,"DELETE FROM additional_fees WHERE get_id ='$id[$i]'");
							//$result = mysqli_query($link,"DELETE FROM collateral WHERE idm ='$id[$i]'");

							echo "<script>alert('Row Delete Successfully!!!'); </script>";
							echo "<script>window.location='listloans.php?id=".$_SESSION['tid']."&&mid=NDA1'; </script>";
						}
					}
					}
		?>

		</div>

</form>				

              </div>


	
</div>	
</div>
</div>	
</div>