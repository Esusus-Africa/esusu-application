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
    				($individual_customer_records != "1" && $branch_customer_records != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND status = 'queue' ORDER BY id") or die (mysqli_error($link)) : "";
    				($individual_customer_records === "1" && $branch_customer_records != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND status = 'queue' ORDER BY id") or die (mysqli_error($link)) : "";
    				($individual_customer_records != "1" && $branch_customer_records === "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND status = 'queue' ORDER BY id") or die (mysqli_error($link)) : "";
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
                        if($list_branches == "1")
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
                
                        <option disabled>Filter By Staff</option>
                        <?php
                        ($list_employee == "1" && $list_branch_employee != "1") ? $get6 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' ORDER BY id") : "";
                        ($list_employee != "1" && $list_branch_employee == "1") ? $get6 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' ORDER BY id") : "";
                        while($rows6 = mysqli_fetch_array($get6))
                        {
                        ?>
                        <option value="<?php echo $rows6['id']; ?>"><?php echo $rows6['name'].' '.$rows6['fname'].' '.$rows6['mname']; ?></option>
                        <?php } ?>
					</select>
                  </div>
                </div>
                
                </div>
                
                
        <hr>
            <div class="table-responsive">
			    <table id="fetch_allpendingcustomer_data" class="table table-bordered table-striped">
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
                      <th>Wallet Balance</th>
    				  <th>Status</th>
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
								echo "<script>window.location='pending_customer.php?id=".$_SESSION['tid']."&&mid=MjQw'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$searchCust = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id[$i]'");
								$fetchCust = mysqli_fetch_array($searchCust);
								$myAccountNumber = ($fetchCust['virtual_acctno'] == "") ? "----" : $fetchCust['virtual_acctno'];
								$account = $fetchCust['account'];
								$fname = $fetchCust['name'];
								$username = $fetchCust['username'];
								$password = $fetchCust['password'];

								/*$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
								$r = mysqli_fetch_object($query);
								$sms_rate = $r->fax;
								$sys_email = $r->email;
								$seckey = $r->secret_key;

								$refid = "EA-custReg-".time();
								$cust_charges = ($ifetch_maintenance_model['cust_mfee'] == "") ? $sms_rate : $ifetch_maintenance_model['cust_mfee'];
								$myiwallet_balance = $iwallet_balance - $cust_charges;
								$wallet_date_time = date("Y-m-d h:i:s");

								//END CUSTOMER IDENTITY VERIFICATION
								$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
								$fetch_memset = mysqli_fetch_array($search_memset);
								$sysabb = $fetch_memset['sender_id'];
								$mobileapp_link = ($fetch_memset['mobileapp_link'] == "") ? "Login at https://esusu.app/$sysabb" : "Download mobile app: ".$fetch_memset['mobileapp_link'];
								//$dedicated_ussd_prefix = $fetch_memset['dedicated_ussd_prefix'];

								$transactionPin = substr((uniqid(rand(),1)),3,4);
								
								$sms = "$sysabb>>>Welcome $fname! Your Account ID: $account, Username: $username, Password: $password, Transaction Pin: $transactionPin. $mobileapp_link";
								
								$max_per_page = 153;
								$sms_length = strlen($sms);
								$calc_length = ceil($sms_length / $max_per_page);

								$maintenance_row = mysqli_num_rows($isearch_maintenance_model);
								$debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 							
								$sms_charges = $calc_length * $cust_charges;
								$mywallet_balance = $iwallet_balance - $sms_charges;
								$refid = "EA-smsCharges-".time();

								$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
								$url = $protocol . $_SERVER['HTTP_HOST']."/?acn=".$account;
								$id = rand(1000000,10000000);
								$shorturl = base_convert($id,20,36);

								$shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/?activation_key=' . $shorturl;
								$shortenedurl1 = $protocol . $_SERVER['HTTP_HOST'] . '/?deactivation_key=' . $shorturl;

								//SMS NOTIFICATION
								($billing_type == "PAYGException" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mywallet_balance, $debitWallet) : ($allow_auth == "Yes" && $debitWAllet == "Yes" && $sms_rate <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mywallet_balance, $debitWallet) : "")));
								//EMAIL NOTIFICATION
								($allow_auth == "Yes") ? $sendSMS->customerRegEmailNotifier($email, $fname, $shortenedurl, $username, $password, $account, $myAccountNumber, $shortenedurl1, $iemailConfigStatus, $ifetch_emailConfig) : "";
								
								$insert = mysqli_query($link, "INSERT INTO activate_member VALUES(null,'$url','$shorturl','No','$account')") or die ("Error4: " . mysqli_error($link));*/
								
								$result = mysqli_query($link,"UPDATE borrowers SET acct_status = 'Not-Activated', status = 'Pending' WHERE id ='$id[$i]'");
																
								echo "<script>alert('Registration Approved Successfully!!!'); </script>";
								echo "<script>window.location='pending_customer.php?id=".$_SESSION['tid']."&&mid=MjQw'; </script>";
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
								echo "<script>window.location='pending_customer.php?id=".$_SESSION['tid']."&&mid=MjQw'; </script>";
							}
							else{
							for($i=0; $i < $N; $i++)
							{
								$searchCust = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id[$i]'");
								$fetchCust = mysqli_fetch_array($searchCust);
								$fname = $fetchCust['name'];

								$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
								$r = mysqli_fetch_object($query);
								$sms_rate = $r->fax;
								$wallet_date_time = date("Y-m-d h:i:s");

								//END CUSTOMER IDENTITY VERIFICATION
								$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
								$fetch_memset = mysqli_fetch_array($search_memset);
								$sysabb = $fetch_memset['sender_id'];
									
								$sms = "$sysabb>>>Dear $fname! Sorry to inform you that your account has been disapproved as you did not meetup with our requirement";
									
								$max_per_page = 153;
								$sms_length = strlen($sms);
								$calc_length = ceil($sms_length / $max_per_page);
								
								$maintenance_row = mysqli_num_rows($isearch_maintenance_model);
								$debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes";
								$sms_charges = $calc_length * $sms_rate;
								$mywallet_balance = $iassigned_walletbal - $sms_charges;
								$refid = "EA-smsCharges-".time();

								//SMS NOTIFICATION
								($billing_type == "PAYGException" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mywallet_balance, $debitWallet) : ($allow_auth == "Yes" && $debitWAllet == "Yes" && $sms_rate <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mywallet_balance, $debitWallet) : "")));

								$result = mysqli_query($link,"UPDATE borrowers SET acct_status = 'Disapprove', status='Disapprove' WHERE id ='$id[$i]'");
								
								echo "<script>alert('Registration Disapproved Successfully!!!'); </script>";
								echo "<script>window.location='pending_customer.php?id=".$_SESSION['tid']."&&mid=MjQw'; </script>";
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