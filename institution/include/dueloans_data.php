<div class="row">       
		    <section class="content">  
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form method="post">
	<a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><button type="button" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> 

    <?php echo ($send_debit_instruction == '1' && $iremitaMerchantId != "" && $iremitaApiKey != "" && $iremitaServiceId != "" && $iremitaApiToken != "") ? "<button type='submit' class='btn btn-flat bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."' name='send_debit'><i class='fa fa-check'></i>&nbsp;Send Debit Instruction</button>" : ""; ?>

    <?php echo ($cancel_debit_instruction == '1' && $iremitaMerchantId != "" && $iremitaApiKey != "" && $iremitaServiceId != "" && $iremitaApiToken != "") ? "<button type='submit' class='btn btn-flat bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."' name='cancel_debit'><i class='fa fa-times'></i>&nbsp;Cancel Debit Instruction</button>" : ""; ?>

<?php
$date_now = date("Y-m-d");
$select = mysqli_query($link, "SELECT * FROM pay_schedule WHERE status = 'UNPAID' AND schedule <= '$date_now' AND branchid = '$institution_id'") or die ("Error: " . mysqli_error($link));
$num = mysqli_num_rows($select);	
?>
	<?php echo ($view_due_loans == 1) ? '<button type="button" class="btn btn-flat bg-'.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'"><i class="fa fa-times"></i>&nbsp;Overdue:&nbsp;'.number_format($num,0,'.',',').'</button>' : ''; ?>
	
    <?php echo ($view_due_loans == 1) ? '<button type="submit" name="reminder" class="btn btn-flat bg-'.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'"><i class="fa fa-bell"></i>&nbsp;Loan Reminder&nbsp;'.'</button>' : ''; ?>

    <a href="dueloans.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("405"); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a>

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
            <!-- FILTER BY ALL LOANS, INDIVIDUAL LOANS OR BRANCH LOANS -->
            <?php echo ($view_due_loans === '1' && $individual_due_loans === '' && $branch_due_loans === '' ? '<option value="all">All Due Loans</option>' : ($view_due_loans === '' && $individual_due_loans === '1' && $branch_due_loans === '' ? '<option value="all1">All Due Loans</option>' : ($view_due_loans === '' && $individual_due_loans === '' && $branch_due_loans === '1' ? '<option value="all2">All Due Loans</option>' : ''))); ?>


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
        <table id="fetch_dueloans_data" class="table table-bordered table-striped">
            <thead>
            <tr>
            <th><input type="checkbox" id="select_all"/></th>
            <th>Action</th>
            <th>Loan ID</th>
            <th>Branch</th>
            <th>RRR Number</th>
			<th>Account ID</th>
            <th>Account Name</th>
            <th>Contact Number</th>
            <th>Principal Amount</th>
            <th>Amount to Pay</th>
            <th>Loan Balance</th>
            <th>DD Status</th>
            <th>Approve By</th>
            <th>Repayment Date</th>
            </tr>
        </thead>
        </table>
        </div>


                
                        <?php
						if(isset($_POST['send_debit'])){
						    $idm = $_GET['id'];
							$id=$_POST['selector'];
							$N = count($id);
							
    						if($id == ''){
    						    echo "<script>alert('Row Not Selected!!!'); </script>";	
    						    echo "<script>window.location='dueloans.php?id=".$_SESSION['tid']."&&mid=NDA1'; </script>";
    						}
							else{
							for($i=0; $i < $N; $i++)
							{
							    //Initiate a new cURL session
                                $ch = curl_init();
    
							    //REMITAL CREDENTIALS
                                $remita_merchantid = $fetch_icurrency->remitaMerchantId;
                                $remita_apikey = $fetch_icurrency->remitaApiKey;
                                $remita_serviceid = $fetch_icurrency->remitaServiceId;
                                $api_token = $fetch_icurrency->remitaApiToken;
                                $requestid = date("dmY").time();

								$search_loan_by_id = mysqli_query($link, "SELECT * FROM pay_schedule WHERE id = '$id[$i]'");
    							$getloan_lid = mysqli_fetch_object($search_loan_by_id);
    						    $get_lid = $getloan_lid->lid;
    						    $totalAmount = number_format($getloan_lid->payment,2,'.','');
    						    
    						    $search_ldetails = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$get_lid'");
    						    $fetch_ldetails = mysqli_fetch_array($search_ldetails);
    						    $mandate_id = $fetch_ldetails['mandate_id'];
    						    //$request_id = $fetch_ldetails['request_id'];
    						    $funcing_acct = $fetch_ldetails['funcing_acct'];
    						    $funding_bankcode = $fetch_ldetails['funding_bankcode'];
    						    
    						    $concat_param = $remita_merchantid.$remita_serviceid.$requestid.$totalAmount.$remita_apikey;
                                $hash = hash("sha512", $concat_param);
                                
                                $search_restapi2 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'remita'");
                                $fetch_restapi2 = mysqli_fetch_object($search_restapi2);
                                $url = $fetch_restapi2->api_url;
                                
                                $api_url = $url."remita/exapp/api/v1/send/api/echannelsvc/echannel/mandate/payment/send";

                                $postdata = array(
                                    "merchantId" => $remita_merchantid,
                                    "serviceTypeId" => $remita_serviceid,
                                    "requestId" => $requestid,
                                    "hash"  => $hash,
                                    "totalAmount"    => $totalAmount,
                                    "mandateId" => $mandate_id,
                                    "fundingAccount"  => $funcing_acct,
                                    "fundingBankCode" => ($funding_bankcode === "221") ? "039" : $funding_bankcode
                                );
                                
                                //print_r($postdata);
                                
                                curl_setopt($ch, CURLOPT_URL, $api_url);
        
                                //Set the CURLOPT_RETURNTRANSFER option ton true
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                                
                                //set the CURLOPT_POST option to true for POST request
                                curl_setopt($ch, CURLOPT_POST, TRUE);
                                                
                                //Set the request data as Array using json_encoded function
                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
                                                
                                //Set custom headers for Content-Type header
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                                                    
                                //Execute cURL request with all previous settings
                                $response2 = curl_exec($ch);
                                $output2 = trim(json_decode(json_encode($response2), true),'jsonp ();');
                                
                                $result = json_decode($output2, true);
                                
                                //print_r($output2);
                                
                                if($result['statuscode'] === "01" || $result['statuscode'] === "069"){
                                    
                                    $remitaRRR = $result['RRR'];
                                    $transactionRef = $result['transactionRef'];
                                    $newrequestid = $result['requestId'];

                                    mysqli_query($link, "UPDATE loan_info SET remita_rrr = '$remitaRRR', trans_ref = '$transactionRef' WHERE lid = '$get_lid'");
                                    mysqli_query($link, "UPDATE pay_schedule SET direct_debit_status = 'Sent', requestid = '$newrequestid' WHERE id = '$id[$i]'");
                                    
                                    echo "<script>alert('Debit Instruction Sent Successfully!!!'); </script>";
								    echo "<script>window.location='dueloans.php?id=".$_SESSION['tid']."&&mid=NDA1'; </script>";
                                    
                                }
                                else{
                                    $mstatus = $result['status'];
                                    echo "<script>alert('$mstatus'); </script>";
								    echo "<script>window.location='dueloans.php?id=".$_SESSION['tid']."&&mid=NDA1'; </script>";
                                    
                                }
							}
							}
							}
							if(isset($_POST['cancel_debit'])){
							    
							    $idm = $_GET['id'];
    							$id=$_POST['selector'];
    							$N = count($id);
    							
        						if($id == ''){
        						    echo "<script>alert('Row Not Selected!!!'); </script>";	
        						    echo "<script>window.location='listloans.php?id=".$_SESSION['tid']."&&tab=tab_1'; </script>";
        						}
    							else{
    							for($i=0; $i < $N; $i++)
    							{
    							    //Initiate a new cURL session
                                    $ch = curl_init();
                                
    							    //REMITAL CREDENTIALS
                                    $remita_merchantid = $fetch_icurrency->remitaMerchantId;
                                    $remita_apikey = $fetch_icurrency->remitaApiKey;
                                    
                                    $search_loan_by_id = mysqli_query($link, "SELECT * FROM pay_schedule WHERE id = '$id[$i]'");
        							$getloan_lid = mysqli_fetch_object($search_loan_by_id);
        						    $get_lid = $getloan_lid->lid;
        						    $request_id = $getloan_lid->requestid;
        						    
        						    $search_ldetails = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$get_lid'");
        						    $fetch_ldetails = mysqli_fetch_array($search_ldetails);
        						    $mandate_id = $fetch_ldetails['mandate_id'];
        						    $trans_ref = $fetch_ldetails['trans_ref'];
        						    $direct_debit_status = $fetch_ldetails['direct_debit_status'];
        						    
        						    $concat_param = $trans_ref.$remita_merchantid.$request_id.$remita_apikey;
                                    $hash = hash("sha512", $concat_param);
                                    
                                    $search_restapi2 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'remita'");
                                    $fetch_restapi2 = mysqli_fetch_object($search_restapi2);
                                    $url = $fetch_restapi2->api_url;
                                    
                                    $api_url = $url."remita/exapp/api/v1/send/api/echannelsvc/echannel/mandate/payment/stop";
    
                                    $postdata = array(
                                        "merchantId" => $remita_merchantid,
                                        "mandateId" => $mandate_id,
                                        "hash"  => $hash,
                                        "requestId" => $request_id,
                                        "transactionRef" => $trans_ref
                                    );
                                    
                                    curl_setopt($ch, CURLOPT_URL, $api_url);
        
                                    //Set the CURLOPT_RETURNTRANSFER option ton true
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                                    
                                    //set the CURLOPT_POST option to true for POST request
                                    curl_setopt($ch, CURLOPT_POST, TRUE);
                                                    
                                    //Set the request data as Array using json_encoded function
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
                                                    
                                    //Set custom headers for Content-Type header
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                                                        
                                    //Execute cURL request with all previous settings
                                    $response2 = curl_exec($ch);
                                    $output2 = trim(json_decode(json_encode($response2), true),'jsonp ();');
                                    
                                    $result = json_decode($output2, true);
                                    
                                    if($result['statuscode'] === "00" && $direct_debit_status === "Sent"){
                                        
                                        mysqli_query($link, "UPDATE pay_schedule SET direct_debit_status = 'Sent' WHERE id = '$id[$i]'");
                                        //mysqli_query($link, "UPDATE loan_info SET direct_debit_status = 'Stop' WHERE lid = '$get_lid' AND direct_debit_status = 'Sent'");
                                        echo "<script>alert('Debit instruction cancelled successfully!'); </script>";
								        echo "<script>window.location='dueloans.php?id=".$_SESSION['tid']."&&mid=NDA1'; </script>";
                                        
                                    }
                                    else{
                                        
                                        $mstatus = $result['status'];
                                        echo "<script>alert('$mstatus'); </script>";
								        echo "<script>window.location='dueloans.php?id=".$_SESSION['tid']."&&mid=NDA1'; </script>";
                                        
                                    }
    							    
    							}
							    
							}
						}
                        ?>



                        <?php
						if(isset($_POST['reminder'])){
                                                        
                            $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
                            $fetch_memset = mysqli_fetch_array($search_memset);
                            //$sys_abb = $get_sys['abb'];
                            $sysabb = $fetch_memset['sender_id'];
                            $mycurrentTime = date("Y-m-d h:i:s");
                            $date_now = date("Y-m-d");
						    //GET PAYMENT SCHEDULE DETAILS
							$search_ps = mysqli_query($link,"SELECT DISTINCT lid, tid FROM pay_schedule WHERE status = 'UNPAID' AND schedule <= '$date_now' AND branchid = '$institution_id' ORDER BY id DESC");
                            $get_rowno = mysqli_num_rows($search_ps);
                            while($fetch_ps = mysqli_fetch_array($search_ps))
                            {
                                $mylid = $fetch_ps['lid'];
                                $myacct = $fetch_ps['tid'];
                                
                                $search_ps2 = mysqli_query($link, "SELECT SUM(payment), schedule FROM pay_schedule WHERE tid = '$myacct' AND status = 'UNPAID' ORDER BY schedule DESC");
                                $fetch_ps2 = mysqli_fetch_array($search_ps2);
                                
                                $due_amt = $fetch_ps2['SUM(payment)'];
                                $due_date = $fetch_ps2['schedule'];
                                
                                //CALCULATE THE EXPECTED BALANCE AFTER PAYING THE DUE AMOUNT ABOVE
                                $search_lbal = mysqli_query($link, "SELECT * FROM loan_info WHERE baccount = '$myacct' AND status = 'Approved'");
                                $fetch_lbal = mysqli_fetch_array($search_lbal);
                                $current_lbal = $fetch_lbal['balance'];
                                
                                $search_myborrower = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$myacct'");
                                $fetch_myborrower = mysqli_fetch_array($search_myborrower);
                                $borrower_name = $fetch_myborrower['lname'].' '.$fetch_myborrower['fname'];
                                $phone = $fetch_myborrower['phone'];
                                $email = $fetch_myborrower['email'];
                                $mycurr = $fetch_myborrower['currency'];
                                
                                $sms = "Dear $borrower_name, ";
                                $sms .= "This is a reminder on your Loan Repayment with Loan ID: $mylid of ";
                                $sms .= $mycurr.number_format($due_amt,2,'.',',')." as outstanding with the balance of ";
                                $sms .= $mycurr.number_format($current_lbal,2,'.',',')." on due date of $due_date. Thanks.";
                                
                                $max_per_page = 152;
                            	$sms_length = strlen($sms);
                            	$calc_length = ceil($sms_length / $max_per_page);
                            	
                            	$sms_rate = $fetchsys_config['fax'];
                            	$sms_charges = $calc_length * $sms_rate;
                            	$mywallet_balance = $iwallet_balance - ($sms_charges * $get_rowno);
                            	$refid = "EA-smsCharges-".date("dY").time();
                                
                                //($iwallet_balance >= $sms_charges) ? include("../cron/send_general_sms.php") : "";
                                //include("../cron/send_loanreminder_email.php");
                                $sms_content = ($iwallet_balance >= ($sms_charges * $get_rowno)) ? $sms : "";
                                mysqli_query($link, "INSERT INTO pending_loan_reminder VALUE(null,'$myacct','$mylid','$borrower_name','$due_amt','$current_lbal','$sms_content','$phone','$email','$mycurr','$due_date','$sysabb','pending')");
                                ($iwallet_balance >= ($sms_charges * $get_rowno)) ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$phone','','$sms_charges','Debit','$mycurr','Charges','SMS Content: $sms','successful','$mycurrentTime','$iuid','$mywallet_balance','')") : "";
                                ($iwallet_balance >= ($sms_charges * $get_rowno)) ? mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','institution','$sysabb','$phone','$sms','Sent',NOW())") : "";
                                ($iwallet_balance >= ($sms_charges * $get_rowno)) ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mywallet_balance' WHERE institution_id = '$institution_id'") : "";

								//($email != "" && $iwallet_balance >= $sms_charges ? "<div class='alert alert-success'>Loan Reminder Sent Successfully!</div>" : ($email != "" && $iwallet_balance <= $sms_charges ? "<div class='alert alert-success'>Loan Reminder Sent Successfully but with NO SMS due to insufficient balance in your wallet!</div>" : "<div class='alert bg-orange'>Oops!....Network Error, Please try again later</div>"));
                                echo "<script>alert('Loan Reminder Sent Successfully!'); </script>";
							    echo "<script>window.location='missedpayment.php?id=".$_SESSION['tid']."&&mid=NDA3'; </script>";
                            }
                            exit();
						}
						?>

</form>				

              </div>


	
</div>	
</div>
</div>	
</div>