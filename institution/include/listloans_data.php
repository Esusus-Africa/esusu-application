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

<?php echo ($add_loan == '1') ? "<a href='newloans.php?id=".$_SESSION['tid']."&&mid=".base64_encode("405")."'><button type='button' class='btn btn-flat bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-plus'></i>&nbsp;Add Loans</button></a>" : ""; ?>

<?php
}
else{
    ?>
<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 

<?php echo ($delete_loans == '1') ? "<button type='submit' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."' name='delete'><i class='fa fa-times'></i>&nbsp;Multiple Delete</button>" : ""; ?>

<a href="listloans.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("405"); ?>&&tab=tab_1"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a>

<?php
$baccount = $_SESSION['acctno'];
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT pay_date FROM loan_info WHERE branchid = '$institution_id' AND p_status = 'UNPAID' AND pay_date <= '$date_now' AND pay_date != ''") or die ("Error: " . mysqli_error($link));
$num = mysqli_num_rows($select);	
?>
	<?php echo ($view_due_loans == '1') ? '<button type="button" class="btn btn-flat bg-orange"><i class="fa fa-times"></i>&nbsp;Overdue:&nbsp;'.number_format($num,0,'.',',').'</button>' : ''; ?>

<?php    
}
?>

	<hr>
	
	
<div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="listloans.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA1&&tab=tab_1">All Loan Application</a></li>

             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="#listloans.php?id=<?php echo $_SESSION['tid']; ?>&&lide=<?php echo $_GET['lide']; ?>&&mid=NDA1&&tab=tab_2">Action</a></li>

            </ul>
             <div class="tab-content">

<?php
if(isset($_GET['tab']) == true)
{
  $tab = $_GET['tab'];
  if($tab == 'tab_1')
  {

  ?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">

             <div class="box-body">

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
					<!-- FILTER BY ALL LOANS, INDIVIDUAL LOANS OR BRANCH LOANS -->
					<?php echo ($view_all_loans === "1" && $individual_loan_records === "" && $branch_loan_records === "" ? '<option value="all">All Loans</option>' : ($view_all_loans === "" && $individual_loan_records === "1" && $branch_loan_records === "" ? '<option value="all1">All Loans</option>' : ($view_all_loans === "" && $individual_loan_records === "" && $branch_loan_records === "1" ? '<option value="all2">All Loans</option>' : ''))); ?>

					<!-- FILTER BY ALL PENDING LOANS, INDIVIDUAL PENDING LOANS OR BRANCH PENDING LOANS -->
					<?php echo ($view_all_loans === "1" && $individual_loan_records === "" && $branch_loan_records === "" ? '<option value="pend">Pending Loans</option>' : ($view_all_loans === "" && $individual_loan_records === "1" && $branch_loan_records === "" ? '<option value="pend1">Pending Loans</option>' : ($view_all_loans === "" && $individual_loan_records === "" && $branch_loan_records === "1" ? '<option value="pend2">Pending Loans</option>' : ''))); ?>

					<!-- FILTER BY ALL DISBURSED LOANS, INDIVIDUAL DISBURSED LOANS OR BRANCH DISBURSED LOANS -->
					<?php echo ($view_all_loans === "1" && $individual_loan_records === "" && $branch_loan_records === "" && $internal_review === "1" && $idept_settings === "On" ? '<option value="intrev">Loans Under-Review</option>' : ($view_all_loans === "" && $individual_loan_records === "1" && $branch_loan_records === "" && $internal_review === "1" && $idept_settings === "On" ? '<option value="intrev1">Loans Under-Review</option>' : ($view_all_loans === "" && $individual_loan_records === "" && $branch_loan_records === "1" && $internal_review === "1" && $idept_settings === "On" ? '<option value="intrev2">Loans Under-Review</option>' : ''))); ?>

					<!-- FILTER BY ALL ACTIVE LOANS, INDIVIDUAL ACTIVE LOANS OR BRANCH ACTIVE LOANS -->
					<?php echo ($view_all_loans === "1" && $individual_loan_records === "" && $branch_loan_records === "" && $active_loan === "1" ? '<option value="act">Active Loans</option>' : ($view_all_loans === "" && $individual_loan_records === "1" && $branch_loan_records === "" && $active_loan === "1" ? '<option value="act1">Active Loans</option>' : ($view_all_loans === "" && $individual_loan_records === "" && $branch_loan_records === "1" && $active_loan === "1" ? '<option value="act2">Active Loans</option>' : ''))); ?>

					<!-- FILTER BY ALL PAID LOANS, INDIVIDUAL PAID LOANS OR BRANCH PAID LOANS -->
					<?php echo ($view_all_loans === "1" && $individual_loan_records === "" && $branch_loan_records === "" && $paid_loan === "1" ? '<option value="paid">Paid Loans</option>' : ($view_all_loans === "" && $individual_loan_records === "1" && $branch_loan_records === "" && $paid_loan === "1" ? '<option value="paid1">Paid Loans</option>' : ($view_all_loans === "" && $individual_loan_records === "" && $branch_loan_records === "1" && $paid_loan === "1" ? '<option value="paid2">Paid Loans</option>' : ''))); ?>

					<!-- FILTER BY ALL APPROVED LOANS, INDIVIDUAL APPROVED LOANS OR BRANCH APPROVED LOANS -->
					<?php echo ($view_all_loans === "1" && $individual_loan_records === "" && $branch_loan_records === "" && $approved_loan === "1" ? '<option value="appr">Approved Loans</option>' : ($view_all_loans === "" && $individual_loan_records === "1" && $branch_loan_records === "" && $approved_loan === "1" ? '<option value="appr1">Approved Loans</option>' : ($view_all_loans === "" && $individual_loan_records === "" && $branch_loan_records === "1" && $approved_loan === "1" ? '<option value="appr2">Approved Loans</option>' : ''))); ?>

					<!-- FILTER BY ALL DISAPPROVED LOANS, INDIVIDUAL DISAPPROVED LOANS OR BRANCH DISAPPROVED LOANS -->
					<?php echo ($view_all_loans === "1" && $individual_loan_records === "" && $branch_loan_records === "" && $disapproved_loan === "1" ? '<option value="disappr">Disapproved Loans</option>' : ($view_all_loans === "" && $individual_loan_records === "1" && $branch_loan_records === "" && $disapproved_loan === "1" ? '<option value="disappr1">Disapproved Loans</option>' : ($view_all_loans === "" && $individual_loan_records === "" && $branch_loan_records === "1" && $disapproved_loan === "1" ? '<option value="disappr2">Disapproved Loans</option>' : ''))); ?>

					<!-- FILTER BY ALL DISBURSED LOANS, INDIVIDUAL DISBURSED LOANS OR BRANCH DISBURSED LOANS -->
					<?php echo ($view_all_loans === "1" && $individual_loan_records === "" && $branch_loan_records === "" && $disbursed_loan === "1" && $idept_settings === "On" ? '<option value="disburs">Disbursed Loans</option>' : ($view_all_loans === "" && $individual_loan_records === "1" && $branch_loan_records === "" && $disbursed_loan === "1" && $idept_settings === "On" ? '<option value="disburs1">Disbursed Loans</option>' : ($view_all_loans === "" && $individual_loan_records === "" && $branch_loan_records === "1" && $disbursed_loan === "1" && $idept_settings === "On" ? '<option value="disburs2">Disbursed Loans</option>' : ''))); ?>
					

					<option disabled>Filter By Customer</option>
					<?php
					($individual_customer_records != "1" && $branch_customer_records != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' ORDER BY id DESC") or die (mysqli_error($link)) : "";
					($individual_customer_records === "1" && $branch_customer_records != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' ORDER BY id DESC") or die (mysqli_error($link)) : "";
					($individual_customer_records != "1" && $branch_customer_records === "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' ORDER BY id DESC") or die (mysqli_error($link)) : "";
					while($rows = mysqli_fetch_array($get))
					{
					?>
					<option value="<?php echo $rows['account']; ?>"><?php echo (($rows['snum'] == "") ? '' : $rows['snum'].' => ') . $rows['account'].' - '.$rows['lname'].' '.$rows['fname'].' '.$rows['mname']; ?></option>
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
			    <table id="fetch_allloans_data" class="table table-bordered table-striped">
			        <thead>
                    <tr>
					<th><input type="checkbox" id="select_all"/></th>
					<th>Action</th>
					<th>DateTime</th>
					<th>Loan ID</th>
					<th>Branch</th>
					<th>RRR Number</th>
					<th>Account ID</th>
					<th>Contact Number</th>
					<th>Principal Amount</th>
					<th>Amount + Interest</th>
					<th>Booked By</th>
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
        						    
									mysqli_query($link,"DELETE FROM loan_guarantor WHERE lid ='$get_lid'");
    								mysqli_query($link,"DELETE FROM pay_schedule WHERE get_id ='$id[$i]'");
    								mysqli_query($link,"DELETE FROM payment_schedule WHERE lid ='$get_lid'");
    								mysqli_query($link,"DELETE FROM interest_calculator WHERE lid ='$get_lid'");
    								mysqli_query($link,"DELETE FROM attachment WHERE get_id ='$id[$i]'");
    								mysqli_query($link,"DELETE FROM battachment WHERE get_id ='$id[$i]'");
									mysqli_query($link,"DELETE FROM loan_info WHERE id ='$id[$i]'");
    								//$result = mysqli_query($link,"DELETE FROM additional_fees WHERE get_id ='$id[$i]'");
    								//$result = mysqli_query($link,"DELETE FROM collateral WHERE idm ='$id[$i]'");
    
    								echo "<script>alert('Row Delete Successfully!!!'); </script>";
    								echo "<script>window.location='listloans.php?id=".$_SESSION['tid']."&&mid=NDA1&&tab=tab_1'; </script>";
    							}
							}
							}
?>

             
       
       
             </div>
             
             </div>

</form>
             <!-- /.tab-pane -->             
<?php
  }
  elseif($tab == 'tab_2')
  {
      $id = $_GET['lide'];
      $select_realloan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'") or die (mysqli_error($link));
      $fetch_realloan = mysqli_fetch_array($select_realloan);
      $mylid = $fetch_realloan['lid'];
      $dept_id = $fetch_realloan['dept'];
    
      $search_mydept = mysqli_query($link, "SELECT * FROM dept WHERE id = '$dept_id'");
      $fetch_mydept = mysqli_fetch_array($search_mydept);
      $dept_name = $fetch_mydept['dpt_name'];
  ?>
            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">
                
                <form class="form-horizontal" method="post"  enctype="multipart/form-data">

<?php
if(isset($_POST['update_status'])) {
    
    $Status_save = $_POST['Status'];
    $UserID = $_POST['userid'];
    $LID = $_POST['lid'];
    $mytid = $_SESSION['tid'];
    $forward_to = $_POST['forward_to'];
	$lnote = $_POST['lnote'];
	$mycurrentTime = date("Y-m-d h:i:s");
	$maintenance_row = mysqli_num_rows($isearch_maintenance_model);
	$sms_rate = $fetchsys_config['fax'];
									
	if($Status_save == "Internal-Review"){
	    
	    $treat_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$UserID'") or die ("Error: " . mysqli_error($link));
		$get_treatedloan = mysqli_fetch_array($treat_loan);
		$LID = $get_treatedloan['lid'];
		$uaccount = $get_treatedloan['baccount'];
		$lproduct = $get_treatedloan['lproduct'];
		$amount = $get_treatedloan['amount'];
		$review_date = date("Y-m-d G:i A");
											
		$search_ploan = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
		$fetch_ploan = mysqli_fetch_array($search_ploan);
		$pname = $fetch_ploan['pname'];
								
		$search_lboro = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$uaccount'");
		$fetch_lboro = mysqli_fetch_array($search_lboro);
		$bfull_name = $fetch_lboro['lname'].' '.$fetch_lboro['fname'];
								
		$look_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$mytid'");
		$get_user = mysqli_fetch_array($look_user);
		$uname = $get_user['name'];
											
		$sql = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated' AND smsuser = ''") or die (mysqli_error($link));
        $find = mysqli_fetch_array($sql);
        $gateway_uname = $find['username'];
        $gateway_pass = $find['password'];
        $gateway_api = $find['api'];

		$search_dept_details = mysqli_query($link, "SELECT * FROM dept WHERE id = '$forward_to'");
		$fetch_dept_details = mysqli_fetch_array($search_dept_details);
		$hod_email = $fetch_dept_details['hod_email'];
		$phone = $fetch_dept_details['hod_phone_no'];
		$dept_name = $fetch_dept_details['dpt_name'];
		$company_email = $inst_email;
																						
		$sysabb = $isenderid;
		$sms = "$sysabb>>>NOTIFICATION: This is to inform you that you have a pending loans to be reviewed. Login to your account here: https://esusu.app/$sysabb to view";
		
		$max_per_page = 153;
		$sms_length = strlen($sms);
		$calc_length = ceil($sms_length / $max_per_page);
		$sms_charges = $calc_length * $sms_rate;
		$mybalance = $iwallet_balance - $sms_charges;
		$refid = uniqid("EA-empRegAlert-").time();

		$debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 
		
		($billing_type == "PAYGException" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mybalance, $debitWallet) : ($debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mybalance, $debitWallet) : "")));
		
		mysqli_query($link, "INSERT INTO campaign_note VALUES(null,'$LID','$iuid','$uaccount','$Status_save','$lnote',NOW())");
		mysqli_query($link, "UPDATE loan_info SET status = '$Status_save', dept = '$forward_to', teller = '$uname' WHERE id = '$UserID'");
		
		$sendSMS->loanInternalReviewEmailNotifier($company_email, $hod_email, $dept_name, $uaccount, $bfull_name, $LID, $review_date, $iemailConfigStatus, $ifetch_emailConfig);

		echo "<script>window.location='listloans.php?id=".$_SESSION['tid']."&&mid=NDA1&&tab=tab_1'; </script>";
		
	}
	elseif($Status_save == "Approved"){
	    
	    $treat_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$UserID'") or die ("Error: " . mysqli_error($link));
		$get_treatedloan = mysqli_fetch_array($treat_loan);
		$LID = $get_treatedloan['lid'];
		$ltype = $get_treatedloan['loantype'];
		$lstatus = "NotPaid";
		$uaccount = $get_treatedloan['baccount'];
		$expire_date = $get_treatedloan['pay_date'];
		$amount = $get_treatedloan['amount'];
		$lbalance = $get_treatedloan['balance'];
											
		$search_lboro = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$uaccount'");
		$fetch_lboro = mysqli_fetch_array($search_lboro);
		$bfull_name = $fetch_lboro['lname'].' '.$fetch_lboro['fname'];
		$smschecker = $fetch_lboro['sms_checker'];
		$phone = $fetch_lboro['phone'];
		$actualLoanBal = $fetch_lboro['loan_balance'] + $lbalance;
		$actualAssetBal = $fetch_lboro['asset_acquisition_bal'] + $lbalance;
											
		$search_psche = mysqli_query($link, "SELECT sum(payment) FROM pay_schedule WHERE lid = '$LID'") or die ("Error: " . mysqli_error($link));
		$get_psche = mysqli_fetch_array($search_psche);
		$amount = $get_psche['sum(payment)'];
		$search_psche1 = mysqli_query($link, "SELECT schedule FROM pay_schedule WHERE lid = '$LID' ORDER BY id DESC") or die ("Error: " . mysqli_error($link));
	    $get_psche1 = mysqli_fetch_array($search_psche1);
		$schedule = $get_psche1['schedule'];
											
		$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
        $fetch_memset = mysqli_fetch_array($search_memset);
											
		$look_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$mytid'");
		$get_user = mysqli_fetch_array($look_user);
		$uname = $get_user['name'];
		$date = date("Y-m-d");

		$sms = "$isenderid>>>Loan Approved | ";
		$sms .= "Dear ".$fetch_lboro['fname']."! ";
		$sms .= "Your Loan Application with Loan ID: ".$LID." of ".$icurrency.number_format($amount,2,'.',',')." has been Approved on ".date('Y-m-d')." ";
		$sms .= "Kindly await disbursement to your account soon. Thanks.";

		$max_per_page = 153;
		$sms_length = strlen($sms);
		$calc_length = ceil($sms_length / $max_per_page);
		$sms_charges = $calc_length * $sms_rate;
		$mybalance = $iwallet_balance - $sms_charges;
		$refid = uniqid("EA-smsCharges-").time();

		$debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 
											
		//to update loan status in user account
		($ltype != "Purchase") ? mysqli_query($link, "UPDATE borrowers SET loan_balance = '$actualLoanBal' WHERE account = '$uaccount'") : "";
		($ltype == "Purchase") ? mysqli_query($link, "UPDATE borrowers SET asset_acquisition_bal = '$actualAssetBal' WHERE account = '$uaccount'") : "";
		($ltype == "Purchase") ? mysqli_query($link, "UPDATE outgoing_stock SET sstatus = 'Loaned' WHERE lid = '$LID'") : "";
		mysqli_query($link,"UPDATE loan_info SET status='$Status_save', teller='$uname', date_release='$date', pay_date='$schedule' WHERE id = '$UserID'") or die ("Error: " . mysqli_error($link)); 
		
		($smschecker == "No" || $billing_type == "PAYGException" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mybalance, $debitWallet) : ($debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mybalance, $debitWallet) : "")));
		
		echo "<script>window.location='listloans.php?id=".$_SESSION['tid']."&&mid=NDA1&&tab=tab_1'; </script>";
		
	}
	elseif($Status_save == "Disbursed"){
	    
	    $treat_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$UserID'") or die ("Error: " . mysqli_error($link));
		$get_treatedloan = mysqli_fetch_array($treat_loan);
		$LID = $get_treatedloan['lid'];
		$ltype = $get_treatedloan['loantype'];
		$lstatus = "NotPaid";
		$uaccount = $get_treatedloan['baccount'];
		$expire_date = $get_treatedloan['pay_date'];
		$loan_amount = $get_treatedloan['amount'];
		$lbalance = $get_treatedloan['balance'];
		
		$search_lboro = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$uaccount'");
		$fetch_lboro = mysqli_fetch_array($search_lboro);
		$bfull_name = $fetch_lboro['lname'].' '.$fetch_lboro['fname'];
		$smschecker = $fetch_lboro['sms_checker'];
		$phone = $fetch_lboro['phone'];
		$actualLoanBal = $fetch_lboro['loan_balance'] + $lbalance;
		$actualAssetBal = $fetch_lboro['asset_acquisition_bal'] + $lbalance;
											
		$search_psche = mysqli_query($link, "SELECT sum(payment) FROM pay_schedule WHERE lid = '$LID'") or die ("Error: " . mysqli_error($link));
		$get_psche = mysqli_fetch_array($search_psche);
		$amount = $get_psche['sum(payment)'];
		$search_psche1 = mysqli_query($link, "SELECT schedule FROM pay_schedule WHERE lid = '$LID' ORDER BY id DESC") or die ("Error: " . mysqli_error($link));
		$get_psche1 = mysqli_fetch_array($search_psche1);
		$schedule = $get_psche1['schedule'];
											
		$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
        $fetch_memset = mysqli_fetch_array($search_memset);
											
		$look_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$mytid'");
		$get_user = mysqli_fetch_array($look_user);
		$uname = $get_user['name'];
		$date = date("Y-m-d");

		$sms = "$isenderid>>>Loan Disbursed ";
		$sms .= "Dear ".$fetch_lboro['fname']."! ";
		$sms .= "This is to notify you that $icurrency.number_format($loan_amount,2,'.',',') with Loan ID: ".$LID." has been disbursed to your account on ".date('Y-m-d').". Thanks.";
		
		$max_per_page = 153;
		$sms_length = strlen($sms);
		$calc_length = ceil($sms_length / $max_per_page);
		$sms_charges = $calc_length * $sms_rate;
		$mybalance = $iwallet_balance - $sms_charges;
		$refid = uniqid("EA-smsCharges-").time();

		$debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 

		//to update loan status in user account
		($ltype != "Purchase") ? mysqli_query($link, "UPDATE borrowers SET loan_balance = '$actualLoanBal' WHERE account = '$uaccount'") : "";
		($ltype == "Purchase") ? mysqli_query($link, "UPDATE borrowers SET asset_acquisition_bal = '$actualAssetBal' WHERE account = '$uaccount'") : "";
		($ltype == "Purchase") ? mysqli_query($link, "UPDATE outgoing_stock SET sstatus = 'Loaned' WHERE lid = '$LID'") : "";
		mysqli_query($link,"UPDATE loan_info SET status='$Status_save', teller='$uname', date_release='$date', pay_date='$schedule' WHERE id = '$UserID'") or die ("Error: " . mysqli_error($link));
		
		($smschecker == "No" || $billing_type == "PAYGException" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mybalance, $debitWallet) : ($debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mybalance, $debitWallet) : "")));
		
		echo "<script>window.location='listloans.php?id=".$_SESSION['tid']."&&mid=NDA1&&tab=tab_1'; </script>";
		
	}
	else{
		$treat_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$UserID'") or die ("Error: " . mysqli_error($link));
		$get_treatedloan = mysqli_fetch_array($treat_loan);
		$LID = $get_treatedloan['lid'];
		$lstatus = "NotPaid";
		$uaccount = $get_treatedloan['baccount'];
		$expire_date = $get_treatedloan['pay_date'];
		$amount = $get_treatedloan['amount'];
		
		$search_lboro = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$uaccount'");
		$fetch_lboro = mysqli_fetch_array($search_lboro);
		$bfull_name = $fetch_lboro['lname'].' '.$fetch_lboro['fname'];
		$smschecker = $fetch_lboro['sms_checker'];
		$phone = $fetch_lboro['phone'];
									
		$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
        $fetch_memset = mysqli_fetch_array($search_memset);

		$sms = "$isenderid>>>Loan Disapproved | ";
		$sms .= "Dear ".$fetch_lboro['fname']."! ";
		$sms .= "Your Loan Application with Loan ID: ".$LID." of ".$icurrency.number_format($amount,2,'.',',')." has been disapproved on ".date('Y-m-d')." ";
		$sms .= "Kindly contact us for more info. Thanks";

		$max_per_page = 153;
		$sms_length = strlen($sms);
		$calc_length = ceil($sms_length / $max_per_page);
		$sms_charges = $calc_length * $sms_rate;
		$mybalance = $iwallet_balance - $sms_charges;
		$refid = uniqid("EA-smsCharges-").time();

		$debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 
        
        ($ltype == "Purchase") ? $selectOS = mysqli_query($link, "SELECT * FROM outgoing_stock WHERE lid = '$LID'") : "";
	    ($ltype == "Purchase") ? $fetchOS = mysqli_fetch_array($selectOS) : "";
	    ($ltype == "Purchase") ? $skuCode = $fetchOS['skuCode'] : "";
	    ($ltype == "Purchase") ? $OSQty = $fetchOS['qty'] : "";

	    ($ltype == "Purchase") ? $selectLS = mysqli_query($link, "SELECT * FROM loan_stock WHERE skuCode = '$skuCode'") : "";
	    ($ltype == "Purchase") ? $fetchLS = mysqli_fetch_array($selectLS) : "";
	    ($ltype == "Purchase") ? $curQty = $fetchLS['qty'] : "";
	    ($ltype == "Purchase") ? $balQty = $curQty + $OSQty : "";
		
		($ltype == "Purchase") ? mysqli_query($link, "UPDATE loan_stock SET qty = '$balQty', status = 'Available' WHERE skuCode = '$skuCode'") : "";
		mysqli_query($link,"UPDATE loan_info SET status='$Status_save' WHERE id = '$UserID'") or die ("Error: " . mysqli_error($link));
	
		($smschecker == "No" || $billing_type == "PAYGException" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mybalance, $debitWallet) : ($debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mybalance, $debitWallet) : "")));
		
		echo "<script>window.location='listloans.php?id=".$_SESSION['tid']."&&mid=NDA1&&tab=tab_1'; </script>";
		
	}			
	
}
?>

		        <div class="box-body">

				    <input type="hidden" value="<?php echo $id; ?>"  name="userid">
					<input type="hidden" value="<?php echo $mylid; ?>"  name="lid">
                  
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Status</label>
                        <div class="col-sm-10">
                            <select name="Status" class="form-control select2" style="width: 100%;" required>
								<option value="" selected> Select Status</option>
								<?php echo ($internal_review == 1 && ($idept_settings === "Off" || $idept_settings === "") ? "" : ($internal_review == 1 && $idept_settings == "On" ? '<option value="Internal-Review">Internal-Review</option>' : "")); ?>
								<?php echo ($approved_loan == 1) ? '<option value="Approved">Approve</option>' : ""; ?>
								<?php echo ($disbursed_loan == 1 && ($idept_settings == "Off" || $idept_settings === "") ? "" : ($disbursed_loan == 1 && $idept_settings == "On" ? '<option value="Disbursed">Disburse</option>' : "")); ?>
								<?php echo ($disapproved_loan == 1) ? '<option value="Disapproved">Disapprove</option>' : ""; ?>
							</select>
                        </div>
                    </div>
                    
                    <hr>
									
                    <?php
                    if($idept_settings === "Off" || $idept_settings === "")
                    {
                        echo "";
                    }else{
                    ?>
                    
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Forward to: </label>
				        <div class="col-sm-10">
				            <select name="forward_to" class="form-control select2" style="width: 100%;">
								<option value="<?php echo ($dept_id == '') ? '' : $dept_id; ?>" selected><?php echo ($dept_id == '') ? 'Select Department to forward for further review' : $dept_name; ?></option>
								<?php
								$searchDept = mysqli_query($link, "SELECT * FROM dept WHERE companyid = '$institution_id'");
								while($fetchDept = mysqli_fetch_array($searchDept))
								{
								?>
									<option value="<?php echo $fetchDept['id']; ?>"><?php echo $fetchDept['dpt_name']; ?></option>
								<?php 
								} 
							    ?>
							</select>
                        </div>
                    </div>
                    
                    <hr><hr>
            
                    <div class="form-group">
				        <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Note</label>
					    <div class="col-sm-10">
						    <textarea name="lnote" class="form-control" rows="3" cols="80" Placeholder="Enter Note" required></textarea>
						</div>
					</div>
                                    
                    <?php 
                    } 
                    ?>
                    
                    </div>
                    
                    <div align="right">
                        <div class="box-footer">
                        	<button name="update_status" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="icon-forward"> Process</i></button>
                        </div>
        			</div>
						             
    			</form>

		  <hr>
		  
		  <?php
            if($idept_settings === "No" || $idept_settings === "")
            {
                echo "";
            }else{
            ?>
		  
		    <div class="box">
	             <div class="box-body table-responsive">
		 			<table width="100%" border="1" bordercolor="<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
						<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><h4> Note:</h4> </div>
						<?php
						$search_note = mysqli_query($link, "SELECT * FROM campaign_note WHERE cpid = '$mylid' ORDER BY id DESC");
						while($get_note = mysqli_fetch_object($search_note))
						{
							$staffid = $get_note->staffid;
							$search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$staffid'");
							$get_staff = mysqli_fetch_object($search_staff);
						?>
		 			  <tr class="form-group" width="100%">
		 				<td><div class="col-sm-12" style="font-size: 13px"><b> <?php echo $get_staff->name; ?> <?php echo ($get_staff->dept == "") ? "(Admin)" : '('.$get_staff->dept.')'; ?> </b></div></td>
						<td><div class="col-sm-12" style="font-size: 12px"><i>To be Reviewed by: <b><?php echo $dept_name; ?></b> - NOTE: <?php echo $get_note->cnote; ?> <b>(<?php echo $get_note->note_date; ?>)</b></i></div></td>
					</tr>
					<?php } ?>
				</table>
				</div>
			</div>
			
			<?php
            }
            ?>
       
             </div>
             <!-- /.tab-pane --> 
             
<?php 
} 
} 
?>
</div>
</div>
</div>	
	
				

              </div>


	
</div>	
</div>
</div>	
</div>