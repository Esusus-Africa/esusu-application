<div class="row">
		
	       
		     <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

    <?php echo ($pending_approval_disapproval_check == '1') ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']).'" name="approve"><i class="fa fa-check"></i>&nbsp;Approve</button>' : ''; ?>
    <?php echo ($pending_approval_disapproval_check == '1') ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? 'orange' : $myaltrow['alternate_color']).'" name="disapprove"><i class="fa fa-times"></i>&nbsp;Disapprove</button>' : ""; ?>
    
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
					<?php echo ($list_all_repayment === '1' && $list_individual_loan_repayment === '' && $list_branch_loan_repayment === '' ? '<option value="all">All Repayment</option>' : ($list_all_repayment === '' && $list_individual_loan_repayment === '1' && $list_branch_loan_repayment === '' ? '<option value="all1">All Repayment</option>' : ($list_all_repayment === '' && $list_individual_loan_repayment === '' && $list_branch_loan_repayment === '1' ? '<option value="all2">All Repayment</option>' : ''))); ?>

					<option disabled>Filter By Customer</option>
					<?php
					($individual_customer_records != "1" && $branch_customer_records != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link)) : "";
					($individual_customer_records === "1" && $branch_customer_records != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' ORDER BY id DESC") or die (mysqli_error($link)) : "";
					($individual_customer_records != "1" && $branch_customer_records === "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' ORDER BY id DESC") or die (mysqli_error($link)) : "";
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['account']; ?>"><?php echo $rows['account'].' - '.$rows['lname'].' '.$rows['fname'].' '.$rows['mname']; ?></option>
					<?php } ?>

					
					<option disabled>Filter By Staff / Sub-agent</option>
					<?php
					($list_employee === "1" && $list_branch_employee != "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
					($list_employee != "1" && $list_branch_employee === "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
					while($rows2 = mysqli_fetch_array($get2))
					{
					?>
					<option value="<?php echo $rows2['id']; ?>"><?php echo $rows2['name'].' '.$rows2['fname'].' '.$rows2['mname']; ?></option>
					<?php } ?>

					<?php
    				////TO BE ACCESSIBLE BY THE SUPER ADMIN ONLY
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
			    <table id="fetch_allpendingrepayment_data" class="table table-bordered table-striped">
			        <thead>
                    <tr>
					<th><input type="checkbox" id="select_all"/></th>
					<th>Reference ID</th>
					<th>Branch</th>
					<th>Loan Officer</th>
					<th>Loan ID</th>
					<th>Account ID</th>
					<th>Account Name</th>
					<th>Amount Payed</th>
					<th>Loan Balance</th>
					<th>Date</th>
					<th>Status</th>
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
			
						<?php
						if(isset($_POST['approve'])){
							$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
							if($id == ''){
								echo "<script>alert('Row Not Selected!!!'); </script>";	
								echo "<script>window.location='listpendingpayment.php?id=".$_SESSION['tid']."&&mid=".base64_encode("240")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
							    $search = mysqli_query($link, "SELECT * FROM payments WHERE id ='$id[$i]'");
							    $fetch_search = mysqli_fetch_array($search);
							    $lid = $fetch_search['lid'];
								$amount_to_pay = $fetch_search['amount_to_pay'];
								$account_no = $fetch_search['account_no'];
								$customer = $fetch_search['customer'];
								$refid = $fetch_search['refid'];
								$mycurrentTime = date("Y-m-d h:i:s");
								$staffid = $fetch_search['tid'];

								$searchin = mysqli_query($link, "SELECT * FROM borrowers WHERE account ='$account_no'");
								$get_searchin = mysqli_fetch_array($searchin);
								$loanBal = $get_searchin['loan_balance'] - $amount_to_pay;
							    
							    $search_lns = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'");
							    $fetch_lns = mysqli_fetch_array($search_lns);
							    $my_balance = $fetch_lns['balance'];
							    $final_bal = $my_balance - $amount_to_pay;
								$p_status = ($final_bal <= 0) ? "PAID" : "PART-PAID";
								
								$verify_role = mysqli_query($link, "SELECT * FROM till_account WHERE cashier = '$staffid' AND status = 'Active'");
								$numtill = mysqli_num_rows($verify_role);
								$fetch_role = mysqli_fetch_object($verify_role);
								$commissiontype = $fetch_role->commission_type;
    							$commission = ($commissiontype == "Flat") ? $fetch_role->commission : ($fetch_role->commission/100);
								$commission_bal = $fetch_role->commission_balance;

								//Calculate Commission Earn By the Staff
								$cal_commission = $commission * $amount_to_pay;
								//Update Default Commission Balance
								$total_commission_bal = ($commission == 0) ? $commission_bal : ($cal_commission + $commission_bal);

								mysqli_query($link, "UPDATE borrowers SET loan_balance = '$loanBal' WHERE account = '$account_no'");
								mysqli_query($link, "UPDATE till_account SET commission_balance = '$total_commission_bal' WHERE cashier = '$staffid'");
							    mysqli_query($link, "UPDATE loan_info SET balance = '$final_bal', p_status = '$p_status' WHERE lid = '$lid'");
								mysqli_query($link,"UPDATE payments SET remarks = 'paid' WHERE id ='$id[$i]'");
								
								echo "<script>alert('Payment Approved Successfully!!!'); </script>";
								echo "<script>window.location='listpendingpayment.php?id=".$_SESSION['tid']."&&mid=".base64_encode("240")."'; </script>";
							}
							}
						}
						?>


<?php
						if(isset($_POST['disapprove'])){
							$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
							if($id == ''){
								echo "<script>alert('Row Not Selected!!!'); </script>";	
								echo "<script>window.location='listpendingpayment.php?id=".$_SESSION['tid']."&&mid=".base64_encode("240")."'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{

								$search = mysqli_query($link, "SELECT * FROM payments WHERE id ='$id[$i]'");
							    $fetch_search = mysqli_fetch_array($search);
							    $lid = $fetch_search['lid'];
								$amount_to_pay = $fetch_search['amount_to_pay'];
								$account_no = $fetch_search['account_no'];
								$customer = $fetch_search['customer'];
								$refid = $refid = uniqid().time();
								$mycurrentTime = date("Y-m-d h:i:s");
								$staffid = $fetch_search['tid'];

								$searchin = mysqli_query($link, "SELECT * FROM borrowers WHERE account ='$account_no'");
								$get_searchin = mysqli_fetch_array($searchin);
								$loanBal = $get_searchin['loan_balance'] + $amount_to_pay;

								mysqli_query($link, "UPDATE till_account SET balance = '$loanBal' WHERE cashier = '$staffid'");
								mysqli_query($link, "INSERT INTO fund_allocation_history VALUES(null,'$refid','$institution_id','$iuid','$isbranchid','$customer','$iuid','$amount_to_pay','Credit','LOAN_REPAYMENT_REVERSAL','$icurrency','$loanBal','Loan Repayment Declined for Loan ID: $lid','successful','$mycurrentTime')");
								mysqli_query($link,"UPDATE payments SET remarks = 'declined' WHERE id ='$id[$i]'");
	
								echo "<script>alert('Payment Declined Successfully!!!'); </script>";
								echo "<script>window.location='listpendingpayment.php?id=".$_SESSION['tid']."&&mid=".base64_encode("240")."'; </script>";
							}
							}
						}
						?>
