<div class="row">
		
	       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
    
<?php
if(isMobileDevice()){
    //Your content or code for mobile devices goes here
?>
    
    <?php echo ($close_customer_account == '1') ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? 'orange' : $myaltrow['alternate_color']).'" name="delete"><i class="fa fa-times"></i>&nbsp;Close Account</button>' : ''; ?>
    <?php echo ($add_customer == '1') ? "<a href='addcustomer.php?id=".$_SESSION['tid']."&&mid=".base64_encode("403")."&&tab=tab_1'><button type='button' class='btn btn-flat bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-plus'></i>&nbsp;Add Customer</button></a>" : ""; ?>

<?php
}
else{
    ?>
	<?php echo ($close_customer_account == '1') ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? 'orange' : $myaltrow['alternate_color']).'" name="delete"><i class="fa fa-times"></i>&nbsp;Close Account</button>' : ''; ?>
	<?php echo ($add_customer == '1') ? "<a href='addcustomer.php?id=".$_SESSION['tid']."&&mid=".base64_encode("403")."&&tab=tab_1'><button type='button' class='btn btn-flat bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-plus'></i>&nbsp;Add Customer</button></a>" : ""; ?>
	<?php echo ($activate_auto_charges == '1') ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? 'orange' : $myaltrow['alternate_color']).'" name="autoCharge"><i class="fa fa-check"></i>&nbsp;Activate Auto-Charge</button>' : ''; ?>
	<?php echo ($activate_auto_disburse == '1') ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']).'" name="autoDisburse"><i class="fa fa-check"></i>&nbsp;Activate Auto-Disburse</button>' : ''; ?>
	<?php echo ($disable_auto_charges == '1') ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? 'orange' : $myaltrow['alternate_color']).'" name="disableAutoCharge"><i class="fa fa-times"></i>&nbsp;Disable Auto-Charge</button>' : ''; ?>
	<?php echo ($disable_auto_disburse == '1') ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? 'blue' : $myaltrow['theme_color']).'" name="disableAutoDisburse"><i class="fa fa-times"></i>&nbsp;Disable Auto-Disburse</button>' : ''; ?>
	<?php //echo ($irole == 'agent_manager' || $irole == 'institution_super_admin' || $irole == 'merchant_super_admin') ? '<button type="submit" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? 'orange' : $myaltrow['alternate_color']).'" name="syncAcct"><i class="fa fa-times"></i>&nbsp;Update All Account</button>' : ''; ?>
<?php    
}
?>	


	<hr>

<?php
if(isset($_GET['view']))
{
?>

        

            <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
				  <th>Actions</th>
                  <th><?php echo ($sno_label == "") ? "S/No." : $sno_label; ?></th>
                  <th>Branch</th>
				  <th>Staff Name</th>
				  <th>Account ID</th>
                  <th>Name</th>
                  <th>Acct. Type</th>
                  <th>Reg. Type</th>
                  <th>Phone</th>
				  <th>Last Update</th>
				  <th>Opening Date</th>
                  <th>Ledger Balance</th>
                  <th>Target Savings Bal.</th>
                  <th>Investment Balance</th>
                  <th>Loan Balance</th>
                  <th>Asset Acquisition Bal.</th>
                  <th>Wallet Balance</th>
				  <th>Status</th>
                 </tr>
                </thead>
                <tbody>
<?php
$select = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND acct_status != 'Closed' ORDER BY id DESC LIMIT 1000") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert bg-blue'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$snum = $row['snum'];
$acctno = $row['account'];
$lname = $row['lname'];
$fname = $row['fname'];
$email = $row['email'];
$phone = $row['phone'];
$date_time = $row['date_time'];
$referral = $row['referral'];
$posts = $row['posts'];
$acct_status = $row['acct_status'];
$bal = $row['balance'];
$wbal = $row['wallet_balance'];
$mybranch = $row['branchid'];
$mysbranch = $row['sbranchid'];
$myofficer = $row['lofficer'];
$acct_type = $row['acct_type'];
$reg_type = $row['reg_type'];
$card_id = $row['card_id'];
$card_reg = $row['card_reg'];
$card_issuer = $row['card_issurer'];
$openingdate = $row['acct_opening_date'];
$search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$mysbranch'");
$fetch_branch = mysqli_fetch_array($search_branch);

//Card registration requirement
$dob = $row['dob'];
$c_address = $row['addrs'];
$c_city = $row['city'];
$c_state = $row['state'];
$c_zip = $row['zip'];
//$image = $row['image'];

$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');

$search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$myofficer'");
$fetch_staff = mysqli_fetch_array($search_staff);
?>    
                <tr>
				<td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>" <?php echo ($bal >= 1 || $wbal >= 1) ? "disabled" : ""; ?>></td>
				<td align="center">
				<?php
				if($acct_status == "Closed"){
					echo "---";
				}else{
				?>
				<div class="btn-group">
                      <div class="btn-group">
                        <button type="button" class="btn bg-blue btn-flat dropdown-toggle" data-toggle="dropdown">
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <?php echo ($update_customers_info == '1') ? '<li><p><a href="add_to_borrower_list.php?id='.$id.'&&mid='.base64_encode("403").'" class="btn btn-default btn-flat"><i class="fa fa-save">&nbsp;Update Information</i></a></p></li>' : ''; ?>
                          <?php echo ($view_account_info == '1') ? '<li><p><a href="invoice-print.php?id='.$_SESSION['tid'].'&&mid='.base64_encode('403').'&&uid='.$acctno.'" class="btn btn-default btn-flat"><i class="fa fa-search">&nbsp;View Account Info.</i></a></p></li>' : ''; ?>
                          <?php echo ($view_loan_history == '1') ? '<li><p><a href="loan-history.php?id='.$_SESSION['tid'].'&&mid='.base64_encode('403').'&&uid='.$acctno.'" class="btn btn-default btn-flat"><i class="fa fa-book">&nbsp;View Loan History.</i></a></p></li>' : ''; ?>
                          <?php //echo ($initiate_cardholder_registration == '1' && $card_reg == "No" && $c_address != "" && $dob != "" && $c_city != "" && $c_state != "" && $c_zip != "") ? '<li><p><a href="cardholder_reg.php?id='.$id.'&&mid='.base64_encode('403').'" class="btn btn-default btn-flat"><i class="fa fa-cc-mastercard">&nbsp;Enrol for Mastercard</i></a></p></li>' : ''; ?>
					      <?php echo ($initiate_cardholder_registration == '1' && $card_reg == "No" && $c_address != "" && $dob != "" && $c_city != "" && $c_state != "") ? '<li><p><a href="cardholder_reg1.php?id='.$id.'&&mid='.base64_encode('403').'" class="btn btn-default btn-flat"><i class="fa fa-credit-card">&nbsp;Enrol for VerveCard</i></a></p></li>' : ''; ?>
						</ul>
                      </div>
				</div>
				<?php
				}
				?>
				</td>
				<td><?php echo ($snum == "") ? "null" : $snum; ?></td>
				<td><?php echo ($mybranch != "" && $mysbranch == "") ? '<b>Head Office</b>' : '<b>'.$fetch_branch['bname'].'</b>'; ?></td>
				<td><?php echo ($myofficer == "") ? 'NIL' : $fetch_staff['name']; ?></td>
                <td><?php echo $acctno; ?></td>
				<td><?php echo $fname.'&nbsp;'.$lname; ?></td>
				<td><?php echo ($acct_type == "") ? "NIL" : $acct_type; ?></td>
				<td><?php echo $reg_type; ?></td>
				<td><?php echo $phone; ?></td>
				<td><?php echo $correctdate; ?></td>
				<td><?php echo $openingdate; ?></td>
				<td><?php echo $row['currency'].number_format($bal,2,'.',','); ?></td>
				<td><?php echo $row['currency'].number_format($row['target_savings_bal'],2,'.',','); ?></td>
				<td><?php echo $row['currency'].number_format($row['investment_bal'],2,'.',','); ?></td>
				<td><?php echo $row['currency'].number_format($row['loan_balance'],2,'.',','); ?></td>
				<td><?php echo $row['currency'].number_format($row['asset_acquisition_bal'],2,'.',','); ?></td>
				<td><?php echo $row['currency'].number_format($wbal,2,'.',','); ?></td>
				<td><?php echo ($acct_status == "Activated") ? "<span class='label bg-blue'>Active</span>" : "<span class='label bg-orange'>Not-Active</span>"; ?></td>
				</tr>
<?php } } ?>
             </tbody>
                </table>



<?php
}
else{
?>

             <div class="box-body">
                 
	           <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">From</label>
                  <div class="col-sm-5">
                  <input name="dfrom" type="date" id="startDate" class="form-control" placeholder="To Date: 2018-05-01">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-01</span>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">To</label>
                  <div class="col-sm-5">
                  <input name="dto" type="date" id="endDate" class="form-control" placeholder="To Date: 2018-05-24">
				  <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Date format: 2018-05-24</span>
                  </div>
             </div>

            <div class="form-group">
                  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Customer</label>
                  <div class="col-sm-5">
                  <select name="customer" id="byCustomer" class="form-control select2" style="width:100%">
					 <option value="" selected>Filter By Customer</option>
    				<?php
    				($individual_customer_records != "1" && $branch_customer_records != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed' ORDER BY id") or die (mysqli_error($link)) : "";
    				($individual_customer_records === "1" && $branch_customer_records != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND acct_status != 'Closed' ORDER BY id") or die (mysqli_error($link)) : "";
    				($individual_customer_records != "1" && $branch_customer_records === "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND acct_status != 'Closed' ORDER BY id") or die (mysqli_error($link)) : "";
    				while($rows = mysqli_fetch_array($get))
    				{
    				?>
    				<option value="<?php echo $rows['account']; ?>"><?php echo $rows['snum'].' - '.$rows['lname'].' '.$rows['fname'].' ['.$rows['account'].'] | '.$rows['phone']; ?></option>
    				<?php } ?>
				  </select>
                  </div>
				  
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
                  <div class="col-sm-5">
                 <select name="filter_by" id="filterBy" class="form-control select2" style="width:100%">
    				<option value="" selected="selected">Filter By...</option>
    				<option value="All">All Customer</option>
    				<?php
    				////TO BE ACCESSIBLE BY THE SUPER ADMIN ONLY
    				if($individual_transaction_records != "1" && $branch_transaction_records != "1")
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
				    
    				<?php
    				//TO BE ACCESSIBLE BY THE SUPER ADMIN ONLY
    				if($individual_customer_records != "1" && $branch_customer_records != "1")
    				{
    				?>
    				
    				<option disabled>Filter By Staff</option>
    				<?php
    				$get6 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY id") or die (mysqli_error($link));
    				while($rows6 = mysqli_fetch_array($get6))
    				{
    				?>
    				<option value="<?php echo $rows6['id']; ?>"><?php echo $rows6['name'].' '.$rows6['fname'].' '.$rows6['mname']; ?></option>
    				<?php } ?>
    				
    				<?php
    				}
    				//TO BE ACCESSIBLE BY THE BRANCH ONLY
    				elseif($individual_customer_records != "1" && $branch_customer_records === "1")
    				{
    				?>
    				
    				<option disabled>Filter By Staff</option>
    				<?php
    				$get6 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' ORDER BY id") or die (mysqli_error($link));
    				while($rows6 = mysqli_fetch_array($get6))
    				{
    				?>
    				<option value="<?php echo $rows6['id']; ?>"><?php echo $rows6['name']; ?></option>
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
			    <table id="fetch_allcustomer_data" class="table table-bordered table-striped">
			        <thead>
                    <tr>
                      <th><input type="checkbox" id="select_all"/></th>
					  <th>Actions</th>
                      <th><?php echo ($sno_label == "") ? "S/No." : $sno_label; ?></th>
                      <th>Branch</th>
                      <th>Staff Name</th>
    				  <th>Account ID</th>
                      <th>Name</th>
                      <th>Acct. Type</th>
                      <th>Reg. Type</th>
                      <th>Phone</th>
					  <th>Last Update</th>
					  <th>Opening Date</th>
                      <th>Ledger Balance</th>
                      <th>Target Savings Bal.</th>
                      <th>Investment Balance</th>
                      <th>Loan Balance</th>
                      <th>Asset Acquisition Bal.</th>
                      <th>Wallet Balance</th>
    				  <th>Status</th>
                     </tr>
                    </thead>
                </table>
            </div>
		
			 


<?php
}
?>
			   
						<?php
						if(isset($_POST['delete'])){
						$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='customer.php?id=".$_SESSION['tid']."&&mid=NDAz'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$todays_date = date('Y-m-d');
								$next_termination_date = date('Y-m-d', strtotime('+35 day', strtotime($todays_date)));

								$result = mysqli_query($link,"UPDATE borrowers SET acct_status = 'Closed', last_withdraw_date = '$next_termination_date' WHERE id ='$id[$i]'");
								
								echo "<script>alert('Account Closed Successfully!!!'); </script>";
								echo "<script>window.location='customer.php?id=".$_SESSION['tid']."&&mid=NDAz'; </script>";
							}
							}
							}
						?>	

						<?php
						if(isset($_POST['autoCharge'])){
							$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
							if($id == ''){
								echo "<script>alert('Row Not Selected!!!'); </script>";	
								echo "<script>window.location='customer.php?id=".$_SESSION['tid']."&&mid=NDAz'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$searchBorrower = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id[$i]'");
								$fetchBorrower = mysqli_fetch_array($searchBorrower);
								$chargeInterval = ($fetchBorrower['charge_interval'] == "weekly" ? "week" : ($fetchBorrower['charge_interval'] == "monthly" ? "month" : "year"));

								$todays_date = date('Y-m-d');
								$next_charge_date = date('Y-m-d', strtotime('+1 '.$chargeInterval, strtotime($todays_date)));
								//$charge_status = "NextQueue_".$next_charge_date;

								$result = mysqli_query($link,"UPDATE borrowers SET auto_charge_status = 'Active', next_charge_date = '$next_charge_date' WHERE id ='$id[$i]'");
								
								echo "<script>alert('Auto-Charge Activated Successfully!!!'); </script>";
								echo "<script>window.location='customer.php?id=".$_SESSION['tid']."&&mid=NDAz'; </script>";
							}
							}
							}
						?>

						<?php
						if(isset($_POST['autoDisburse'])){
							$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
							if($id == ''){
								echo "<script>alert('Row Not Selected!!!'); </script>";	
								echo "<script>window.location='customer.php?id=".$_SESSION['tid']."&&mid=NDAz'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$searchBorrower = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id[$i]'");
								$fetchBorrower = mysqli_fetch_array($searchBorrower);
								$disburseInterval = ($fetchBorrower['disbursement_interval'] == "weekly" ? "week" : ($fetchBorrower['disbursement_interval'] == "monthly" ? "month" : "year"));
								
								$todays_date = date('Y-m-d');
								$next_disbursement_date = date('Y-m-d', strtotime('+1 '.$disburseInterval, strtotime($todays_date)));
								//$disburse_status = "NextQueue_".$next_disbursement_date;

								$result = mysqli_query($link,"UPDATE borrowers SET auto_disbursement_status = 'Active', next_disbursement_date = '$next_disbursement_date' WHERE id ='$id[$i]'");
								
								echo "<script>alert('Auto-Disbursement Activated Successfully!!!'); </script>";
								echo "<script>window.location='customer.php?id=".$_SESSION['tid']."&&mid=NDAz'; </script>";
							}
							}
							}
						?>

<?php
						if(isset($_POST['disableAutoCharge'])){
							$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
							if($id == ''){
								echo "<script>alert('Row Not Selected!!!'); </script>";	
								echo "<script>window.location='customer.php?id=".$_SESSION['tid']."&&mid=NDAz'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"UPDATE borrowers SET auto_charge_status = 'NotActive' WHERE id ='$id[$i]'");
								
								echo "<script>alert('Auto-Charge Deactivated Successfully!!!'); </script>";
								echo "<script>window.location='customer.php?id=".$_SESSION['tid']."&&mid=NDAz'; </script>";
							}
							}
							}
						?>


						<?php
						if(isset($_POST['disableAutoDisburse'])){
							$idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
							if($id == ''){
								echo "<script>alert('Row Not Selected!!!'); </script>";	
								echo "<script>window.location='customer.php?id=".$_SESSION['tid']."&&mid=NDAz'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$result = mysqli_query($link,"UPDATE borrowers SET auto_disbursement_status = 'NotActive' WHERE id ='$id[$i]'");
								
								echo "<script>alert('Auto-Disbursement Deactivated Successfully!!!'); </script>";
								echo "<script>window.location='customer.php?id=".$_SESSION['tid']."&&mid=NDAz'; </script>";
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