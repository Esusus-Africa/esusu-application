<div class="row">
		
	       
		     <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

	<a href="loanHistory.php?id=<?php echo $_SESSION['tid']; ?>&&uid=<?php echo $_GET['uid']; ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 

	<hr>


			<div class="box-body">

				<div class="form-group">
					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
					<div class="col-sm-3">
					<input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
					<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-01</span>
					</div>
					
					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">To</label>
					<div class="col-sm-3">
					<input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
					<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-24</span>
					</div>

					<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
					<div class="col-sm-3">
					<select name="filter_by" id="filterBy" class="form-control select2" style="width:100%">
					<option value="" selected="selected">Filter By...</option>
					<!-- FILTER BY ALL LOANS REPAYMENT, INDIVIDUAL LOANS REPAYMENT OR BRANCH LOANS REPAYMENT -->
					<?php echo ($all_wallet_loan_due_payment === '1' && $individual_wallet_loan_due_payment === '' && $branch_wallet_loan_due_payment === '' ? '<option value="all">All Due Payment</option>' : ($all_wallet_loan_due_payment === '' && $individual_wallet_loan_due_payment === '1' && $branch_wallet_loan_due_payment === '' ? '<option value="all1">All Due Payment</option>' : ($all_wallet_loan_due_payment === '' && $individual_wallet_loan_due_payment === '' && $branch_wallet_loan_due_payment === '1' ? '<option value="all2">All Due Payment</option>' : ''))); ?>

					<option disabled>Filter By Customer</option>
					<?php
					($individual_wallet != "1" && $branch_wallet != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND virtual_acctno != '' ORDER BY id DESC") or die (mysqli_error($link)) : "";
					($individual_wallet === "1" && $branch_wallet != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND virtual_acctno != '' ORDER BY id DESC") or die (mysqli_error($link)) : "";
					($individual_wallet != "1" && $branch_wallet === "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND virtual_acctno != '' ORDER BY id DESC") or die (mysqli_error($link)) : "";
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['virtual_acctno']; ?>"><?php echo $rows['virtual_acctno'].' - '.$rows['lname'].' '.$rows['fname'].' '.$rows['mname']; ?></option>
					<?php } ?>

					
					<option disabled>Filter By Staff / Sub-agent</option>
					<?php
					($list_employee === "1" && $list_branch_employee != "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND virtual_acctno != '' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
					($list_employee != "1" && $list_branch_employee === "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' AND virtual_acctno != '' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
					while($rows2 = mysqli_fetch_array($get2))
					{
					?>
					<option value="<?php echo $rows2['virtual_acctno']; ?>"><?php echo $rows2['virtual_acctno'].' - '.$rows2['name'].' '.$rows2['fname'].' '.$rows2['mname']; ?></option>
					<?php } ?>

					<?php
    				//TO BE ACCESSIBLE BY THE SUPER ADMIN ONLY
    				if($list_branches === "1")
    				{
    				?>
    				<option disabled>Filter By Branch</option>
    				<?php
    				$get5 = mysqli_query($link, "SELECT * FROM branches WHERE created_by = '$institution_id' ORDER BY id") or die (mysqli_error($link));
    				while($rows5 = mysqli_fetch_array($get5))
    				{
    				?>
    				<option value="<?php echo $rows5['branchid']; ?>"><?php echo $rows5['bname'].' ['.$rows5['branchid'].']'; ?></option>
    				<?php } ?>
    				<?php
    				}
    				else{
    				    //nothing
    				}
    				?>
				</select>
					</div>
				</div>
			</div>


			<hr>
            <div class="table-responsive">
			    <table id="fetch_wlDuePayment_data" class="table table-bordered table-striped">
			        <thead>
                    <tr>
					<th><input type="checkbox" id="select_all"/></th>
                    <th>Action</th>
					<th>Loan ID</th>
					<th>Branch</th>
					<th>Loan Officer</th>
					<th>Account ID</th>
					<th>Account Name</th>
					<th>Due Amount</th>
					<th>Balance Left</th>
                    <th>Schedule Due Date</th>
					<th>Date/Time</th>
                 	</tr>
                </thead>
                </table>
            </div>


</form>

					</div>
</div>	
</div>			
</div>	
</div>
