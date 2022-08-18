<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">

	<?php echo ($disapprove_withdrawal_request == 1) ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'" name="disapprove"><i class="fa fa-times"></i>&nbsp;Disapprove Request</button>' : ''; ?>

	<?php echo ($approve_withdrawal_request == 1) ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'" name="approve"><i class="fa fa-check"></i>&nbsp;Approve Request</button>' : ''; ?>
	
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
					<option value="Cash">Cash</option>
					<option value="Bank">Bank</option>
					<option value="Wallet">Wallet</option>
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
			 <table id="fetch_ledgerWRequest_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>TxID</th>
				  <th>Source</th>
				  <th>Branch</th>
				  <th>AcctNo.</th>
				  <th>Acct. Name</th>
                  <th>Phone</th>
                  <th>Amount Requested</th>
				  <th>Balance</th>
				  <th>Status</th>
				  <th>Posted By</th>
				  <th>Date/Time</th>
                 </tr>
                </thead>
                </table>
                </div>

						
						<?php
						if(isset($_POST['disapprove'])){
							$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
							if($id == ''){
								echo "<script>alert('Row Not Selected!!!'); </script>";	
								echo "<script>window.location='allLedger_wrequest.php?tid=".$_SESSION['tid']."&&mid=NDEw'; </script>";
							}
							else{

								for($i=0; $i < $N; $i++)
								{
									$searchReq = mysqli_query($link, "SELECT * FROM ledger_withdrawal_request WHERE id ='$id[$i]'");
									$fetchReq = mysqli_fetch_array($searchReq);
									$mytxid = $fetchReq['txid'];

									mysqli_query($link,"UPDATE transaction SET status = 'Disapproved' WHERE txid ='$mytxid' AND status = 'Pending'");
									mysqli_query($link,"UPDATE ledger_withdrawal_request SET status = 'Disapproved' WHERE id ='$id[$i]'");

									echo "<script>alert('Request Disapproved Successfully!!!'); </script>";
									echo "<script>window.location='allLedger_wrequest.php?tid=".$_SESSION['tid']."&&mid=NDEw'; </script>";
								}

							}
						}
						?>


						<?php
						if(isset($_POST['approve'])){

							$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
							if($id == ''){
								echo "<script>alert('Row Not Selected!!!'); </script>";	
								echo "<script>window.location='allLedger_wrequest.php?tid=".$_SESSION['tid']."&&mid=NDEw'; </script>";
							}
							else{

								for($i=0; $i < $N; $i++)
								{
									$searchReq = mysqli_query($link, "SELECT * FROM ledger_withdrawal_request WHERE id ='$id[$i]'");
									$fetchReq = mysqli_fetch_array($searchReq);
									$postedBy = $fetchReq['acct_officer'];
									$branch = $fetchReq['sbranchid'];
									$txid = $fetchReq['txid'];
									$account = $fetchReq['acn'];
									$income_id = "ICM".rand(000001,99999);
									$balanceToImpact = $fetchReq['balance_toimpact'];
									$amountReqPlusCharges = $fetchReq['amt_requested'];
									$totalamount = $amountReqPlusCharges;
									$ptype = $fetchReq['ptype'];
									$date_time = date("Y-m-d h:i:s");

									$searchTrans = mysqli_query($link, "SELECT * FROM transaction WHERE txid = '$txid' AND t_type = 'Withdraw-Charges'");
									$fetchTrans = mysqli_fetch_array($searchTrans);
									$final_charges = $fetchTrans['amount'];
									$amountReqMinusCharges = $amountReqPlusCharges - $final_charges;
									$newTransferBal = $itransfer_balance - $amountReqMinusCharges;
									($ptype == "Wallet" && $itransfer_balance >= $amountReqMinusCharges) ? mysqli_query($link, "UPDATE user SET transfer_balance = '$newTransferBal' WHERE id = '$iuid'") : "";

									$searchMyCust = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$account'");
									$fetchMyCust = mysqli_fetch_array($searchMyCust);
									$currentWalletBal = $fetchMyCust['wallet_balance'];
									$newWalletBal = $currentWalletBal - $amountReqMinusCharges;
									$currentBal = ($balanceToImpact == "ledger" ? $fetchMyCust['balance'] : ($balanceToImpact == "target" ? $fetchMyCust['target_savings_bal'] : $fetchMyCust['investment_bal']));
									$total = $currentBal - $amountReqPlusCharges;
									$fullname = $fetchMyCust['lname'].' '.$fetchMyCust['fname'];
									
									//UPDATE TILL BALANCE IF APPLICABLE
									$verify_role = mysqli_query($link, "SELECT * FROM till_account WHERE cashier = '$postedBy' AND status = 'Active'");
    								if(mysqli_num_rows($verify_role) == 1){

										$fetch_role = mysqli_fetch_array($verify_role);
										$balance = $fetch_role['balance'];
										$remain_balance = $balance + $totalamount;

										$update = mysqli_query($link, "UPDATE till_account SET balance = '$remain_balance' WHERE cashier = '$postedBy'");
										mysqli_query($link, "INSERT INTO fund_allocation_history VALUES(null,'$txid','$institution_id','$postedBy','$branch','$fullname','$postedBy','$amountReqPlusCharges','Debit','Withdraw','$icurrency','$remain_balance','withdraw contribution','successful','$date_time')");

									}

									//UPDATE CUSTOMER BALANCE
									($balanceToImpact == "ledger") ? mysqli_query($link, "UPDATE borrowers SET balance = '$total', last_withdraw_date = '$today' WHERE account = '$account'") : "";
									($balanceToImpact == "target") ? mysqli_query($link, "UPDATE borrowers SET target_savings_bal = '$total', last_withdraw_date = '$today' WHERE account = '$account'") : "";
									($balanceToImpact == "investment") ? mysqli_query($link, "UPDATE borrowers SET investment_bal = '$total', last_withdraw_date = '$today' WHERE account = '$account'") : "";
									($ptype == "Wallet" && $itransfer_balance >= $amountReqMinusCharges) ? mysqli_query($link, "UPDATE borrowers SET balance = '$newWalletBal' WHERE account = '$account'") : "";
									($ptype == "Wallet" && $itransfer_balance >= $amountReqMinusCharges) ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$institution_id','$txid','WALLET PAYOUT','','$amountReqMinusCharges','Debit','$icurrency','PAYOUT','Wallet Payout for: $account','successful','$date_time','$account','$newWalletBal','')") : "";

									//UPDATE TRANSACTION RECORD AND REQUEST LOG
									($ptype == "Wallet" && $itransfer_balance < $amountReqMinusCharges) ? "" : mysqli_query($link,"UPDATE transaction SET status = 'Approved' WHERE txid ='$txid' AND status = 'Pending'");
									($ptype == "Wallet" && $itransfer_balance < $amountReqMinusCharges) ? "" : mysqli_query($link,"UPDATE ledger_withdrawal_request SET status = 'Approved' WHERE id ='$id[$i]'");

									echo ($ptype == "Wallet" && $itransfer_balance < $amountReqMinusCharges) ? "<script>alert('No sufficient fund in your transfer wallet!!!'); </script>" : "<script>alert('Request Approved Successfully!!!'); </script>";
									echo "<script>window.location='allLedger_wrequest.php?tid=".$_SESSION['tid']."&&mid=NDEw'; </script>";
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