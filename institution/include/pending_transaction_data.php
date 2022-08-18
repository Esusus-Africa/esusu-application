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
                  <div class="col-sm-5">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date should be in this format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">End Date</label>
                  <div class="col-sm-5">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>;">Date should be in this format: 2018-05-24</span>
                  </div>
             </div>

            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Type</label>
                  <div class="col-sm-5">
                  <select name="ptype" id="pmtType" class="form-control select2" style="width:100%">
					<option value="" selected="selected">Filter By Payment Type...</option>
					<option value="All">All Transaction</option>
					<option value="Deposit">Deposit</option>
					<option value="Withdraw">Withdraw</option>
					<option value="Withdraw-Charges">Withdraw-Charges</option>
					<option value="Cash">Cash</option>
					<option value="Bank">Bank</option>
				  </select>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
                  <div class="col-sm-5">
                 <select name="filter_by" id="filterBy" class="form-control select2" style="width:100%">
    				<option value="" selected="selected">Filter By Staff, Customer, Branch...</option>

					<option disabled>Filter By Staff / Sub-agent</option>
					<?php
					($list_employee === "1" && $list_branch_employee != "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
					($list_employee != "1" && $list_branch_employee === "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
					while($rows2 = mysqli_fetch_array($get2))
					{
					?>
					<option value="<?php echo $rows2['id']; ?>"><?php echo $rows2['name'].' '.$rows2['fname'].' '.$rows2['mname']; ?></option>
					<?php } ?>
    				

				    <option disabled>Filter By Customer</option>
    				<?php
    				($individual_customer_records != "1" && $branch_customer_records != "1") ? $get4 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' ORDER BY id") or die (mysqli_error($link)) : "";
    				($individual_customer_records === "1" && $branch_customer_records != "1") ? $get4 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND (lofficer = '$iuid' OR lofficer = '$iuserid') ORDER BY id") or die (mysqli_error($link)) : "";
    				($individual_customer_records != "1" && $branch_customer_records === "1") ? $get4 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' ORDER BY id") or die (mysqli_error($link)) : "";
    				while($rows4 = mysqli_fetch_array($get4))
    				{
    				?>
    				<option value="<?php echo $rows4['account']; ?>"><?php echo $rows4['lname'].' '.$rows4['fname'].' ['.$rows4['account'].']'; ?></option>
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
			 <table id="fetch_pendingtransaction_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>TxID</th>
				  <th>Savings Product</th>
				  <th>Branch</th>
				  <th>AcctNo.</th>
				  <th>Acct. Name</th>
                  <th>Phone</th>
                  <th>Debit</th>
                  <th>Credit</th>
				  <th>Balance</th>
                  <th>Status</th>
				  <th>Date/Time</th>
				  <th>Posted By</th>
                 </tr>
                </thead>
                </table>
                </div>


                    <?php
						if(isset($_POST['approve'])){
							$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
							if($id == ''){
								echo "<script>alert('Row Not Selected!!!'); </script>";	
								echo "<script>window.location='pending_transaction.php?id=".$_SESSION['tid']."&&mid=NDEw'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$search_trans = mysqli_query($link, "SELECT * FROM transaction WHERE id ='$id[$i]'");
								$get_trans = mysqli_fetch_array($search_trans);
								$txid = $get_trans['txid'];
								$account = $get_trans['acctno'];
								$fn = $get_trans['fn'];
								$ln = $get_trans['ln'];
								$em = $get_trans['email'];
								$ph = $get_trans['phone'];
								$uname = $get_trans['fn'];
								$total = $get_trans['balance'];
								$ptype = $get_trans['t_type'];
								$amount = $get_trans['amount'];
								$balanceToImpact = $get_trans['balanceToImpact'];
								$final_date_time = date("Y-m-d h:i:s");

								$search_borrower = mysqli_query($link, "SELECT * FROM borrowers WHERE account ='$account'");
								$get_borrower = mysqli_fetch_array($search_borrower);
								$currentCustBal = ($balanceToImpact == "ledger" ? $get_borrower['balance'] : ($balanceToImpact == "target" ? $get_borrower['target_savings_bal'] : ($balanceToImpact == "investment" ? $get_borrower['investment_bal'] : ($balanceToImpact == "asset" ? $get_borrower['asset_acquisition_bal'] : ($balanceToImpact == "loan" ? $get_borrower['loan_balance'] : $get_borrower['balance'])))));
								$newbalance = ($ptype == "Deposit" && ($balanceToImpact == "loan" || $balanceToImpact == "asset") ? ($currentCustBal - $amount) : ($ptype == "Deposit" && $balanceToImpact != "loan" && $balanceToImpact != "asset" ? ($currentCustBal + $amount) : ($ptype == "Withdraw" && $currentCustBal >= $amount && $balanceToImpact != "loan" && $balanceToImpact != "asset" ? ($currentCustBal - $amount) : $currentCustBal)));
								
								mysqli_query($link, "UPDATE borrowers SET balance = '$newbalance' WHERE account = '$account'");
							    mysqli_query($link,"UPDATE transaction SET balance = '$newbalance', status = 'Approved' WHERE id ='$id[$i]' AND status = 'Pending'");
															
								echo "<script>alert('Transaction approved successfully!!!'); </script>";
							    echo "<script>window.location='pending_transaction.php?id=".$_SESSION['tid']."&&mid=NDEw'; </script>";
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
								echo "<script>window.location='pending_transaction.php?id=".$_SESSION['tid']."&&mid=NDEw'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$search_trans = mysqli_query($link, "SELECT * FROM transaction WHERE id ='$id[$i]'");
								$get_trans = mysqli_fetch_object($search_trans);
								$postedBy = $get_trans->posted_by;
								$ptype = $get_trans->t_type;
								$amount = $get_trans->amount;

								$verify_role = mysqli_query($link, "SELECT * FROM till_account WHERE cashier = '$postedBy' AND status = 'Active'");
								$roleNum = mysqli_num_rows($verify_role);
								$fetch_role = mysqli_fetch_object($verify_role);
								$balance = $fetch_role->balance;
								$commtype = $fetch_role->commission_type;
								$commission = $fetch_role->commission;
								$commission_bal = $fetch_role->commission_balance;
								$remain_balance = ($ptype == "Deposit") ? ($balance + $amount) : ($balance - $amount);
								$myLabelType = ($ptype == "Deposit") ? "REVERSED_".strtoupper($ptype) : "REVERSED_".strtoupper($ptype)."AL";
								$customer = $fetch_role->teller;
								$mycurrentTime = date("Y-m-d h:i:s");
								$refid = $refid = uniqid().time();

								//Calculate Commission Earn By the Staff
								$cal_commission = ($commtype == "Percentage") ? (($commission / 100) * $amount) : $commission;
								//Update Default Commission Balance
								$total_commission_bal = ($commission == 0) ? $commission_bal : ($cal_commission - $commission_bal);

								($roleNum == 1 && ($ptype == "Deposit" || $ptype == "Withdraw")) ? $update = mysqli_query($link, "UPDATE till_account SET balance = '$remain_balance', commission_balance = '$total_commission_bal' WHERE cashier = '$postedBy'") : "";
								mysqli_query($link, "INSERT INTO fund_allocation_history VALUES(null,'$refid','$institution_id','$iuid','$isbranchid','$customer','$iuid','$amount','Credit','$myLabelType','$icurrency','$remain_balance','Amount to $ptype was Declined','successful','$mycurrentTime')");
								$result = mysqli_query($link,"UPDATE transaction SET status = 'Disapproved' WHERE id ='$id[$i]' AND status = 'Pending'");
								
								echo "<script>alert('Transaction disapproved successfully!!!'); </script>";
							    echo "<script>window.location='pending_transaction.php?id=".$_SESSION['tid']."&&mid=NDEw'; </script>";
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