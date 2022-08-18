<div class="row">	
		
	 <section class="content">
		         
		   <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

		 <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <?php echo ($add_enrollee == 1) ? '<li "'.(($_GET['tab'] == "tab_1") ? "class='active'" : "").'"><a href="addEnrollee.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("711").'&&tab=tab_1">Add New Enrollee</a></li>' : ''; ?>
                <?php echo ($add_bulk_enrollees == 1) ? '<li "'.(($_GET['tab'] == "tab_1") ? "class='active'" : "").'"><a href="addEnrollee.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("711").'&&tab=tab_2">Add Bulk Enrollee</a></li>' : ''; ?>
            </ul>
            <div class="tab-content">
<?php
if(isset($_GET['tab']) == true)
{
	$tab = $_GET['tab'];
	if($tab == 'tab_1' && $add_enrollee == 1)
	{
	?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">
			 
 <?php
if(isset($_POST['save']))
{
    $transactionType = mysqli_real_escape_string($link, $_POST['transactionType']);
    $nimcPartner = mysqli_real_escape_string($link, $_POST['nimcPartner']);
    $userFname = mysqli_real_escape_string($link, $_POST['userFname']);
    $userLname = mysqli_real_escape_string($link, $_POST['userLname']);
    $userMname = mysqli_real_escape_string($link, $_POST['userMname']);
    $phoneNo = mysqli_real_escape_string($link, $_POST['phoneNo']);
    $gender = mysqli_real_escape_string($link, $_POST['gender']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $dateOfBirth = mysqli_real_escape_string($link, $_POST['dateOfBirth']);
    $trackingId = mysqli_real_escape_string($link, $_POST['trackingId']);
    $bvn = mysqli_real_escape_string($link, $_POST['bvn']);
    $ninNo = mysqli_real_escape_string($link, $_POST['ninNo']);
    $moi = mysqli_real_escape_string($link, $_POST['moi']);
    $paymentType = mysqli_real_escape_string($link, $_POST['paymentType']);
    $amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount']));
    $balance = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['balance']));
    $tpin = preg_replace('/[^0-9]/', '', mysqli_real_escape_string($link, $_POST['tpin']));

    $maintenance_row = mysqli_num_rows($isearch_maintenance_model);
    $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes";

    $verify_enrolleeUniqueness = mysqli_query($link, "SELECT * FROM enrollees WHERE trackingId = '$trackingId' AND companyid = '$institution_id'");
    $v_enrolleeUniqueness = mysqli_num_rows($verify_enrolleeUniqueness);

    $getEnrolleeNo = mysqli_query($link, "SELECT * FROM enrollees WHERE companyid = '$institution_id'");
    $fetchEnrolleeNo = mysqli_num_rows($getEnrolleeNo);

    $refid = uniqid("EA-newEnrolment-").time();
    $cust_charges = ($ifetch_maintenance_model['cust_mfee'] == "") ? 0 : $ifetch_maintenance_model['cust_mfee'];
    $myiwallet_balance = $iwallet_balance - $cust_charges;
    $wallet_date_time = date("Y-m-d h:i:s");

    $smsrefid = uniqid("EA-newEnrolmtSMS-").time();
    $sms = "$isenderid>>>Dear $userLname, your NIN Enrolment has been submitted for processing with Tracking ID: ".$trackingId." ";
    $sms .= "You will be notify via Email/Phone once available for collection. Thanks.";

    $em_msg_content = "Your NIN Enrolment has been submitted for processing with Tracking ID: ".$trackingId." ";
    $em_msg_content .= "You will be notify via Email/Phone once available for collection. Thanks.";

    $max_per_page = 153;
    $sms_length = strlen($sms);
    $calc_length = ceil($sms_length / $max_per_page);
    $smsCharges = $ifetch_maintenance_model['smscharges'];
    $sms_charges = $calc_length * $smsCharges;
    $mybalance = $myiwallet_balance - $sms_charges;

    //Get Information from User IP Address
    $myip = $sendSMS->getUserIP();
    //Get Information from User Browser
    $ua = $sendSMS->getBrowser();
    $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
    $activities_tracked = $iname . " make attempt to add new enrollee with Tracking ID: ".$trackingId;

    if($v_enrolleeUniqueness == 1){

        echo "<p style='font-size:24px; color:orange;'>Sorry, Tracking ID has already been used.</p>";

    }elseif($tpin != $myiepin){

        echo "<p style='font-size:24px; color:orange;'>Oops!....Invalid Transaction Pin!!</p>";

    }elseif($fetchEnrolleeNo == $icustomer_limit){

        echo "<script>alert('Sorry, You can not add more than the limit assigned to you. Kindly Contact us for higher plan!'); </script>";
    
    }elseif($iwallet_balance < $cust_charges && (mysqli_num_rows($isearch_maintenance_model) == 0 || (mysqli_num_rows($isearch_maintenance_model) == 1 && $billing_type != "PAYGException"))){
        
        echo "<script>alert('Sorry, You are unable to add more enrollee due to insufficient fund in institution Wallet!!'); </script>";
    
    }else{

        ($cust_charges != 0) ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','self','','$cust_charges','Debit','$icurrency','Charges','Description: Service Charge for new Enrolment input for $trackingId','successful','$wallet_date_time','$iuid','$myiwallet_balance','')") : "";
		mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$institution_id'");

        mysqli_query($link, "INSERT INTO enrollees VALUES(null,'$institution_id','$isbranchid','$iuid','$userFname','$userLname','$userMname','$gender','$dateOfBirth','$bvn','$moi','$ninNo','$emailAddress','$phoneNo','$transactionType','$amount','$balance','$paymentType','$nimcPartner','$trackingId','','','Pending','Pending','$wallet_date_time','$wallet_date_time')") or die ("Error: " .mysqli_error($link));
        mysqli_query($link, "INSERT INTO enrolleeLog VALUES(null,'$institution_id','$isbranchid','$iuid','$nimcPartner','$myip','$yourbrowser','$activities_tracked','$wallet_date_time')");

        //SMS NOTIFICATION
        ($billing_type == "PAYGException" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phoneNo, $sms, $institution_id, $smsrefid, $sms_charges, $iuid, $mybalance, $debitWallet) : ($debitWAllet == "Yes" && $sms_charges <= $myiwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phoneNo, $sms, $institution_id, $smsrefid, $sms_charges, $iuid, $mybalance, $debitWallet) : "")));
        //EMAIL NOTIFICATION
        ($email != "") ? $sendSMS->enrolleeNotifier($email, $userLname, "NEW", $transactionType, $em_msg_content, $iemailConfigStatus, $ifetch_emailConfig) : "";
            
        echo "<div class='alert alert-success'>New Enrollee Added Successfully!</div>";
        
    }
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">
                 
                <div class="form-group">
 		            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Transaction Type</label>
 		            <div class="col-sm-10">
 						<select name="transactionType" class="form-control select2" required>
 							<option value="" selected='selected'>Select Type</option>
 							<option value="NIN_ENROLLMENT">NIN Enrollment</option>
 							<option value="NIN_REPRINT">NIN Reprint</option>
                            <option value="NIN_MODIFICATION">NIN Modification</option>
                            <option value="NATIONAL_ID_CARD">National ID Card</option>
                            <option value="NIN_CLEARANCE">NIN Clearance</option>
                            <option value="NIN_VALIDATION">NIN Validation</option>
                            <option value="NIN_RECAPTURE">NIN Recapture</option>
                            <option value="NIN_ENROLLMENT_FASTRACK">NIN Enrollment Fastrack</option>
                            <option value="NIN_DEPT_REPAYMENT">NIN Debt Repayment</option>
                            <option value="BVN_ENROLLMENT">BVN Enrollment</option>
 						</select>
 				    </div>
 				</div>

                <div class="form-group">
 		            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">NIMC Partner</label>
 		            <div class="col-sm-10">
 						<select name="nimcPartner" class="form-control select2" required>
 							<option value="" selected='selected'>Select Partner</option>
                            <?php
                            $searchPartners = mysqli_query($link, "SELECT * FROM nimcPartner");
                            while($fetchPartners = mysqli_fetch_array($searchPartners)){
                            ?>
 							    <option value="<?php echo $fetchPartners['id']; ?>"><?php echo $fetchPartners['partnerName'] . '(' . $fetchPartners['category'] . ')'; ?></option>
 							<?php
                            }
                            ?>
 						</select>
 				    </div>
 				</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">First Name</label>
                  <div class="col-sm-10">
                  <input name="userFname" type="text" class="form-control" placeholder="First Name" required>
                  </div>
                  </div>
				  
		    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Last Name</label>
                  <div class="col-sm-10">
                  <input name="userLname" type="text" class="form-control" placeholder="Last Name" required>
                  </div>
                  </div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Middle Name(Optional)</label>
                  <div class="col-sm-10">
                  <input name="userMname" type="text" class="form-control" placeholder="Middle Name">
                  </div>
                  </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mobile</label>
                <div class="col-sm-4">
                    <input name="phoneNo" type="tel" class="form-control" required>
                </div><?php echo (isMobileDevice()) ? "<br>" : ""; ?>
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gender</label>
                <div class="col-sm-4">
                    <select name="gender" class="form-control" required>
                        <option value="" selected='selected'>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>  
			</div>


		    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Email(Optional)</label>
                  <div class="col-sm-10">
                  <input type="text" name="email" type="text" class="form-control" placeholder="Email Address">
                  </div>
				  </div>
				  
				  
		    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Date of Birth</label>
                  <div class="col-sm-10">
                  <input name="dateOfBirth" type="date" class="form-control" required>
                  </div>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Tracking ID</label>
                  <div class="col-sm-10">
                  <input name="trackingId" type="text" class="form-control" placeholder="Tracking ID" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">BVN</label>
                  <div class="col-sm-10">
                  <input name="bvn" type="text" class="form-control" placeholder="BVN" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">NIN Number(Optional)</label>
                  <div class="col-sm-10">
                  <input name="ninNo" type="text" class="form-control" placeholder="NIN Number">
                  </div>
                  </div>

            <div class="form-group">
 		        <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Mode of Identification</label>
 		        <div class="col-sm-10">
 					<select name="moi" class="form-control select2" required>
 						<option value="" selected='selected'>Select Mode of Identification</option>
 						<option value="Old National ID">Old National ID</option>
 						<option value="International Passport">International Passport</option>
                        <option value="Voters Card">Voters Card</option>
                        <option value="Drivers License">Drivers License</option>
                        <option value="Birth Certificate">Birth Certificate</option>
                        <option value="Sworn Affidavit">Sworn Affidavit</option>
 					</select>
 				</div>
 			</div>

            <div class="form-group">
 		        <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Payment Type</label>
 		        <div class="col-sm-10">
 					<select name="paymentType" class="form-control select2" required>
 						<option value="" selected='selected'>Select Payment Type</option>
 						<option value="Cash">Cash</option>
 						<option value="Bank Transfer">Bank Transfer</option>
                        <option value="Pos">Pos</option>
 					</select>
 				</div>
 			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Amount to Pay</label>
                  <div class="col-sm-10">
                  <input name="amount" type="text" inputmode="numeric" pattern="[0-9.]*" class="form-control" placeholder="Amount to Pay" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Balance to Pay</label>
                  <div class="col-sm-10">
                  <input name="balance" type="text" inputmode="numeric" pattern="[0-9.]*" class="form-control" placeholder="Balance to Pay" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Transaction Pin</label>
                  <div class="col-sm-10">
                  <input name="tpin" type="text" inputmode="numeric" pattern="[0-9]*" class="form-control" placeholder="Transaction Pin" required>
                  </div>
                  </div>

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-save"> Submit</i></button>
              </div>
			  </div>
			  
			 </form> 
			 
              </div>
              <!-- /.tab-pane -->
	<?php
	}
	elseif($tab == 'tab_2' && $add_bulk_enrollees == 1)
	{
	?>
              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">
				  
				<form class="form-horizontal" method="post" enctype="multipart/form-data">
					
                <div class="box-body">

<?php
if(isset($_POST["Import"])){
    
    $tpin = preg_replace('/[^0-9]/', '', mysqli_real_escape_string($link, $_POST['tpin']));

    $maintenance_row = mysqli_num_rows($isearch_maintenance_model);
    $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes";

    $getEnrolleeNo = mysqli_query($link, "SELECT * FROM enrollees WHERE companyid = '$institution_id'");
    $fetchEnrolleeNo = mysqli_num_rows($getEnrolleeNo);
	
	if($tpin != $myiepin){

        echo "<p style='font-size:24px; color:orange;'>Oops!....Invalid Transaction Pin!!</p>";

    }elseif($fetchEnrolleeNo == $icustomer_limit){

        echo "<script>alert('Sorry, You can not add more than the limit assigned to you. Kindly Contact us for higher plan!'); </script>";
    
    }elseif($_FILES['file']['name']){
        
        $filename = explode('.', $_FILES['file']['name']);
        
        if($filename[1] == 'csv'){
            
            $handle = fopen($_FILES['file']['tmp_name'], "r");
            $fp = file($_FILES['file']['tmp_name'], FILE_SKIP_EMPTY_LINES);
            $countFile = count($fp);
            fgetcsv($handle,1000,","); // pop the headers
            while($data = fgetcsv($handle,1000,",")){
                
                $empty_filesop = array_filter(array_map('trim', $data));
                
                if(!empty($empty_filesop)){

                    $transactionType = strtoupper(mysqli_real_escape_string($link, $data[0]));
                    $nimcPartner = mysqli_real_escape_string($link, $data[1]);
                    $userFname = strtoupper(mysqli_real_escape_string($link, $data[2]));
                    $userLname = strtoupper(mysqli_real_escape_string($link, $data[3]));
                    $userMname = strtoupper(mysqli_real_escape_string($link, $data[4]));
                    $phoneNo = (strpos(mysqli_real_escape_string($link, $data[5]), '+234') === 0 ? str_replace(' ', '', mysqli_real_escape_string($link, $data[5])) : (strpos(mysqli_real_escape_string($link, $data[5]), '234') === 0 ? "+".str_replace(' ', '', mysqli_real_escape_string($link, $data[5])) : (strpos(mysqli_real_escape_string($link, $data[5]), '0') === 0 ?  "+234".str_replace('0', '', mysqli_real_escape_string($link, $data[5])) : ($data[5] == "" ? "" : "+234".str_replace(' ', '', mysqli_real_escape_string($link, $data[5]))))));
                    $gender = (($data[6] == "F") ? "Female" : (($data[6] == "M") ? "Male" : ucwords(mysqli_real_escape_string($link, $data[6]))));
                    $email = mysqli_real_escape_string($link, $data[7]);
                    $dateOfBirth = ($data[7] == "") ? "" : reformatDate(mysqli_real_escape_string($link, $data[8]));
                    $trackingId = mysqli_real_escape_string($link, $data[9]);
                    $bvn = mysqli_real_escape_string($link, $data[10]);
                    $ninNo = mysqli_real_escape_string($$link, $data[11]);
                    $moi = mysqli_real_escape_string($link, $data[12]);
                    $paymentType = mysqli_real_escape_string($link, $data[13]);
                    $amount = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $data[14]));
                    $balance = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $data[15]));
                    $status = ($data[16] == "") ? "Pending" : ucwords(mysqli_real_escape_string($link, $data[16]));
                    $status1 = ($data[17] == "") ? "Pending" : ucwords(mysqli_real_escape_string($link, $data[17]));
                    $dateCreated = ($data[18] == "") ? date("Y-m-d h:i:s") : date("Y-m-d h:i:s", strtotime((mysqli_real_escape_string($link, $data[18]))));

                    $verify_enrolleeUniqueness = mysqli_query($link, "SELECT * FROM enrollees WHERE trackingId = '$trackingId' AND companyid = '$institution_id'");
                    $v_enrolleeUniqueness = mysqli_num_rows($verify_enrolleeUniqueness);

                    $refid = uniqid("EA-newEnrolment-").time();
                    $cust_charges = ($ifetch_maintenance_model['cust_mfee'] == "") ? 0 : $ifetch_maintenance_model['cust_mfee'];
                    $myiwallet_balance = $iwallet_balance - ($cust_charges * $countFile);
                    $wallet_date_time = date("Y-m-d h:i:s");

                    $em_msg_content = "Your NIN Enrolment has been submitted for processing with Tracking ID: ".$trackingId." ";
                    $em_msg_content .= "You will be notify via Email/Phone once available for collection. Thanks.";

                    //Get Information from User IP Address
                    $myip = $sendSMS->getUserIP();
                    //Get Information from User Browser
                    $ua = $sendSMS->getBrowser();
                    $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
                    $activities_tracked = $iname . " make attempt to add new enrollee with Tracking ID: ".$trackingId;
                    
                    if($v_enrolleeUniqueness == 1){

                        echo "<p style='font-size:24px; color:orange;'>Sorry, Tracking ID has already been used.</p>";

                    }elseif($iwallet_balance < ($cust_charges * $countFile) && (mysqli_num_rows($isearch_maintenance_model) == 0 || (mysqli_num_rows($isearch_maintenance_model) == 1 && $billing_type != "PAYGException"))){
                        
                        echo "<script>alert('Sorry, You are unable to add more enrollee due to insufficient fund in institution Wallet!!'); </script>";
                    
                    }else{

                        ($cust_charges != 0) ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','self','','$cust_charges','Debit','$icurrency','Charges','Description: Service Charge for new Enrolment input for $trackingId','successful','$wallet_date_time','$iuid','$myiwallet_balance','')") : "";
		                mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$institution_id'");
                        
						$sql = "INSERT INTO enrollees(id,companyid,branchid,staffid,userFname,userLname,userMname,gender,dateOfBirth,bvn,moi,ninNo,emailAddress,phoneNo,transactionType,amount,balance,paymentType,nimcPartner,trackingId,associatedTrackingId,remarks,ninSlipStatus,idCardStatus,createdAt,updatedAt) VALUES(null,'$institution_id','$isbranchid','$iuid','$userFname','$userLname','$userMname','$gender','$dateOfBirth','$bvn','$moi','$ninNo','$emailAddress','$phoneNo','$transactionType','$amount','$balance','$paymentType','$nimcPartner','$trackingId','','','$status','$status1','$dateCreated','$wallet_date_time')";
                        $result = mysqli_query($link,$sql);

                        $sql2 = "INSERT INTO enrolleeLog(id,companyid,branchid,staffid,nimcPartner,ip_addrs,browser_details,activities_tracked,createdAt) VALUES(null,'$institution_id','$isbranchid','$iuid','$nimcPartner','$myip','$yourbrowser','$activities_tracked','$wallet_date_time')";
                        $result2 = mysqli_query($link,$sql2);

                        if(!($result && $result2))
            			{
            				echo "<script type=\"text/javascript\">
            					alert(\"Invalid File:Please Upload CSV File.\");
            				    </script>".mysqli_error($link);
            			}

                        //EMAIL NOTIFICATION
                        ($email != "") ? $sendSMS->enrolleeNotifier($email, $userLname, "NEW", $transactionType, $em_msg_content, $iemailConfigStatus, $ifetch_emailConfig) : "";
            			
                    }
                }
            }
            fclose($handle);
            echo "<script type=\"text/javascript\">
						alert(\"Enrollee Information has been successfully Imported.\");
					</script>";
            
        }
        
    }
    
}
?>
    
	<div class="form-group">
           <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Import Enrollees:</label>
	<div class="col-sm-10">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="file" accept=".csv" required>
	</div>
	</div>
	
	<div class="form-group">
           <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">NOTE:</label>
	<div class="col-sm-10">
        <span style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">(1)</span> <i>Kindly download the <a href="../sample/enrollees_sample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the details once.</i>
	</div>
	</div>
	
    <div align="right">
       <div class="box-footer">
	     <button class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> ks-btn-file" type="submit" name="Import"><span class="fa fa-cloud-upload"></span> Import Customer Details</button> 
       </div>
    </div>  

</div>	

</form>

	<?php
	}
}
?>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
			
          </div>					
			</div>
		
              </div>
	
</div>	
</div>
</div>
</section>	
</div>