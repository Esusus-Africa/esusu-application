<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title">Enrollee Info</h3>
            </div>

            <?php
            $enid = $_GET['id'];
            $seachEnrollee = mysqli_query($link, "SELECT * FROM enrollees WHERE id = '$enid'");
            $fetchEnrollee = mysqli_fetch_array($seachEnrollee);
            $defaultPID = $fetchEnrollee['nimcPartner'];
            $checkPartner = mysqli_query($link, "SELECT * FROM nimcPartner WHERE id = '$defaultPID'");
            $getPartner = mysqli_fetch_array($checkPartner);
            ?>

             <div class="box-body">

             <?php
            if(isset($_POST['updateEnrollee'])){

                $myenid = $_GET['id'];
                $transactionType = mysqli_real_escape_string($link, $_POST['transactionType']);
                $nimcPartner = mysqli_real_escape_string($link, $_POST['nimcPartner']);
                $userLname = mysqli_real_escape_string($link, $_POST['userLname']);
                $email = mysqli_real_escape_string($link, $_POST['email']);
                $associatedTrackingId = mysqli_real_escape_string($link, $_POST['associatedTrackingId']);
                $ninNo = mysqli_real_escape_string($link, $_POST['ninNo']);
                $trackingId = mysqli_real_escape_string($link, $_POST['trackingId']);
                $phoneNo = mysqli_real_escape_string($link, $_POST['phoneNo']);
                $balance = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['balance']));
                $ninSlipStatus = mysqli_real_escape_string($link, $_POST['ninSlipStatus']);
                $idCardStatus = mysqli_real_escape_string($link, $_POST['idCardStatus']);
                $remarks = mysqli_real_escape_string($link, $_POST['remarks']);
                $tpin = preg_replace('/[^0-9]/', '', mysqli_real_escape_string($link, $_POST['tpin']));
                $wallet_date_time = date("Y-m-d h:i:s");

                //Default Value
                $seachMyEnrollee = mysqli_query($link, "SELECT * FROM enrollees WHERE id = '$myenid'");
                $fetchMyEnrollee = mysqli_fetch_array($seachMyEnrollee);
                $defaultNinSlipStatus = $fetchMyEnrollee['ninSlipStatus'];
                $defaultIDCardStatus = $fetchMyEnrollee['idCardStatus'];

                //Check INST Maintenance Settings
                $smsrefid = uniqid("EA-updateEnrolmtSMS-").time();
                $maintenance_row = mysqli_num_rows($isearch_maintenance_model);
                $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes";

                $sms = "$isenderid>>>Dear $userLname, your NIN" . (($defaultNinSlipStatus == "Pending" && $ninSlipStatus == "Completed") ? "Slip has been printed out and is ready for collection." : ((($defaultNinSlipStatus == "Pending" || $defaultNinSlipStatus == "Completed") && $ninSlipStatus == "Collected") ? "Slip has been collected." : (($defaultIDCardStatus == "Pending" && $idCardStatus == "Completed") ? "ID Card is ready for collection." : ((($defaultIDCardStatus == "Pending" || $defaultIDCardStatus == "Completed") && $idCardStatus == "Collected") ? "ID Card has been collected." : (($defaultNinSlipStatus == "Pending" && $ninSlipStatus == "Completed" && $defaultIDCardStatus == "Pending" && $idCardStatus == "Completed") ? "Slip and ID Card is ready for collection." : (((($defaultNinSlipStatus == "Pending" || $defaultNinSlipStatus == "Completed") && ($defaultIDCardStatus == "Pending" || $defaultIDCardStatus == "Completed")) && $ninSlipStatus == "Collected" && $idCardStatus == "Collected") ? "Slip and ID Card has been collected." : ""))))));
                $sms .= " Thanks for your patronage.";

                $em_msg_content = "Your NIN" . (($defaultNinSlipStatus == "Pending" && $ninSlipStatus == "Completed") ? "Slip has been printed out and is ready for collection." : ((($defaultNinSlipStatus == "Pending" || $defaultNinSlipStatus == "Completed") && $ninSlipStatus == "Collected") ? "Slip has been collected." : (($defaultIDCardStatus == "Pending" && $idCardStatus == "Completed") ? "ID Card is ready for collection." : ((($defaultIDCardStatus == "Pending" || $defaultIDCardStatus == "Completed") && $idCardStatus == "Collected") ? "ID Card has been collected." : (($defaultNinSlipStatus == "Pending" && $ninSlipStatus == "Completed" && $defaultIDCardStatus == "Pending" && $idCardStatus == "Completed") ? "Slip and ID Card is ready for collection." : (((($defaultNinSlipStatus == "Pending" || $defaultNinSlipStatus == "Completed") && ($defaultIDCardStatus == "Pending" || $defaultIDCardStatus == "Completed")) && $ninSlipStatus == "Collected" && $idCardStatus == "Collected") ? "Slip and ID Card has been collected." : ""))))));
                $em_msg_content .= " Thanks for your patronage.";

                $max_per_page = 153;
                $sms_length = strlen($sms);
                $calc_length = ceil($sms_length / $max_per_page);
                $smsCharges = $ifetch_maintenance_model['smscharges'];
                $sms_charges = $calc_length * $smsCharges;
                $mybalance = $iwallet_balance - $sms_charges;

                //File
                $ninSlip_file = $_FILES['ninSlip_file']['name'];
                $ninSlip_fileTMP = $_FILES['ninSlip_file']['tmp_name'];
                $idCard_file = $_FILES['idCard_file']['name'];
                $idCard_fileTMP = $_FILES['idCard_file']['tmp_name'];

                //Get Information from User IP Address
                $myip = $sendSMS->getUserIP();
                //Get Information from User Browser
                $ua = $sendSMS->getBrowser();
                $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
                $activities_tracked = $iname . " make attempt to update enrollee details with NIN: " . $ninNo . " System Message: " . $em_msg_content;

                if($tpin != $myiepin){

                    echo "<p style='font-size:24px; color:orange;'>Oops!....Invalid Transaction Pin!!</p>";

                }else{

                    //Send SMS Notification if applicable
                    (($defaultNinSlipStatus != "Completed" && $defaultNinSlipStatus != "Collected" && $defaultIDCardStatus != "Completed" && $defaultIDCardStatus != "Collected") && ($ninSlipStatus == "Completed" || $ninSlipStatus == "Collected" || $idCardStatus == "Completed" || $idCardStatus == "Collected") && $phoneNo != "" && $iwallet_balance >= $sms_charges) ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phoneNo, $sms, $institution_id, $smsrefid, $sms_charges, $iuid, $mybalance, $debitWallet) : "";
                    //Send Email Notification if applicable
                    (($defaultNinSlipStatus != "Completed" && $defaultNinSlipStatus != "Collected" && $defaultIDCardStatus != "Completed" && $defaultIDCardStatus != "Collected") && ($ninSlipStatus == "Completed" || $ninSlipStatus == "Collected" || $idCardStatus == "Completed" || $idCardStatus == "Collected") && $email != "") ? $sendSMS->enrolleeNotifier($email, $userLname, "'UPDATE'", $transactionType, $em_msg_content, $iemailConfigStatus, $ifetch_emailConfig) : "";

                    //Upload Slip / ID Card
                    ($defaultNinSlipStatus != "Completed" || $defaultNinSlipStatus != "Collected") ? $sendSMS->uploadAttachement($ninSlip_file, $ninSlip_fileTMP, $myenid, $trackingId, "ninSlip") : "";
                    ($defaultIDCardStatus != "Completed" || $defaultIDCardStatus != "Collected") ? $sendSMS->uploadAttachement($idCard_file, $idCard_fileTMP, $myenid, $trackingId, "idCard") : "";

                    mysqli_query($link, "UPDATE enrollees SET emailAddress = '$email', associatedTrackingId = '$associatedTrackingId', ninNo = '$ninNo', balance = '$balance', remarks = '$remarks', ninSlipStatus = '$ninSlipStatus', idCardStatus = '$idCardStatus' WHERE id = '$myenid'");
                    mysqli_query($link, "INSERT INTO enrolleeLog VALUES(null,'$institution_id','$isbranchid','$iuid','$nimcPartner','$myip','$yourbrowser','$activities_tracked','$wallet_date_time')");

                    echo "<div class='alert alert-success'>Enrollee Updated Successfully!</div>";

                }

            }
            ?>

            <?php
            if(isset($_GET['edit'])){
            ?>
          
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

                <div class="form-group">
 		            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Transaction Type</label>
 		            <div class="col-sm-6">
 						<select name="transactionType" class="form-control select2" readonly>
 							<option value="<?php echo $fetchEnrollee['transactionType']; ?>" selected='selected'><?php echo $fetchEnrollee['transactionType']; ?></option>
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
                    <label for="" class="col-sm-3 control-label"></label>
 				</div>

                <div class="form-group">
 		            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">NIMC Partner</label>
 		            <div class="col-sm-6">
 						<select name="nimcPartner" class="form-control select2" readonly>
 							<option value="<?php echo $defaultPID; ?>" selected='selected'><?php echo $getPartner['partnerName'] . '(' . $getPartner['category'] . ')'; ?></option>
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
                    <label for="" class="col-sm-3 control-label"></label>
 				</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">First Name</label>
                  <div class="col-sm-6">
                  <input name="userFname" type="text" class="form-control" placeholder="First Name" value="<?php echo $fetchEnrollee['userFname']; ?>" readonly>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>
				  
		    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Last Name</label>
                  <div class="col-sm-6">
                  <input name="userLname" type="text" class="form-control" placeholder="Last Name" value="<?php echo $fetchEnrollee['userLname']; ?>" readonly>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>

			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Middle Name(Optional)</label>
                  <div class="col-sm-6">
                  <input name="userMname" type="text" class="form-control" placeholder="Middle Name" value="<?php echo $fetchEnrollee['userMname']; ?>" readonly>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mobile</label>
                <div class="col-sm-6">
                    <input name="phoneNo" type="tel" class="form-control" value="<?php echo $fetchEnrollee['phoneNo']; ?>" readonly>
                </div>
                <label for="" class="col-sm-3 control-label"></label> 
			</div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gender</label>
                <div class="col-sm-6">
                    <select name="gender" class="form-control" readonly>
                        <option value="<?php echo $fetchEnrollee['gender']; ?>" selected='selected'><?php echo $fetchEnrollee['gender']; ?></option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
			</div>


		    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Email(Optional)</label>
                  <div class="col-sm-6">
                  <input type="text" name="email" type="text" class="form-control" placeholder="Email Address" value="<?php echo $fetchEnrollee['email']; ?>">
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
				  </div>
				  
				  
		    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Date of Birth</label>
                  <div class="col-sm-6">
                  <input name="dateOfBirth" type="date" class="form-control" value="<?php echo $fetchEnrollee['dateOfBirth']; ?>" readonly>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Tracking ID</label>
                  <div class="col-sm-6">
                  <input name="trackingId" type="text" class="form-control" placeholder="Tracking ID" value="<?php echo $fetchEnrollee['trackingId']; ?>" readonly>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Associated TrackingID</label>
                  <div class="col-sm-6">
                  <input name="associatedTrackingId" type="text" class="form-control" placeholder="Associated TrackingID" value="<?php echo $fetchEnrollee['associatedTrackingId']; ?>">
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">BVN</label>
                  <div class="col-sm-6">
                  <input name="bvn" type="text" class="form-control" placeholder="BVN" value="<?php echo $fetchEnrollee['bvn']; ?>" readonly>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">NIN Number</label>
                  <div class="col-sm-6">
                  <input name="ninNo" type="text" class="form-control" placeholder="NIN Number" value="<?php echo $fetchEnrollee['ninNo']; ?>" required>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>

            <div class="form-group">
 		        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Mode of Identification</label>
 		        <div class="col-sm-6">
 					<select name="moi" class="form-control select2" readonly>
 						<option value="<?php echo $fetchEnrollee['moi']; ?>" selected='selected'><?php echo $fetchEnrollee['moi']; ?></option>
 						<option value="Old National ID">Old National ID</option>
 						<option value="International Passport">International Passport</option>
                        <option value="Voters Card">Voters Card</option>
                        <option value="Drivers License">Drivers License</option>
                        <option value="Birth Certificate">Birth Certificate</option>
                        <option value="Sworn Affidavit">Sworn Affidavit</option>
 					</select>
 				</div>
                <label for="" class="col-sm-3 control-label"></label>
 			</div>

            <div class="form-group">
 		        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Payment Type</label>
 		        <div class="col-sm-6">
 					<select name="paymentType" class="form-control select2" readonly>
 						<option value="<?php echo $fetchEnrollee['paymentType']; ?>" selected='selected'><?php echo $fetchEnrollee['paymentType']; ?></option>
 						<option value="Cash">Cash</option>
 						<option value="Bank Transfer">Bank Transfer</option>
                        <option value="Pos">Pos</option>
 					</select>
 				</div>
                <label for="" class="col-sm-3 control-label"></label>
 			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Amount to Pay</label>
                  <div class="col-sm-6">
                  <input name="amount" type="text" inputmode="numeric" pattern="[0-9.]*" class="form-control" placeholder="Amount to Pay" value="<?php echo $fetchEnrollee['amount']; ?>" readonly>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Balance to Pay</label>
                  <div class="col-sm-6">
                  <input name="balance" type="text" inputmode="numeric" pattern="[0-9.]*" class="form-control" placeholder="Balance to Pay" value="<?php echo $fetchEnrollee['balance']; ?>" required>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Upload NIN Slip</label>
                <div class="col-sm-6">
                  <input name="ninSlip_file[]" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" multiple/>
                  <hr>
				  <?php
                    $i = 0;
                    $search_file = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$enid' AND file_title = 'ninSlip'") or die ("Error: " . mysqli_error($link));
                    if(mysqli_num_rows($search_file) == 0){
                    	echo "<span style='color: ".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>No file attached!!</span>";
                    }else{
                    	while($get_file = mysqli_fetch_array($search_file)){
                    		$i++;
                    ?>
                    <a href="<?php echo $fetchsys_config['file_baseurl'].$get_file['attached_file']; ?>" target="_blank"><img src="<?php echo $fetchsys_config['file_baseurl']; ?>file_attached.png" width="64" height="64"> Attachment<?php echo $i; ?></a>
                    <?php
                    	}
                    }
                    ?>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
 		        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">NIN Slip Status</label>
 		        <div class="col-sm-6">
 					<select name="ninSlipStatus" class="form-control select2" required>
 						<option value="<?php echo $fetchEnrollee['ninSlipStatus']; ?>" selected='selected'><?php echo $fetchEnrollee['ninSlipStatus']; ?></option>
 						<option value="Completed">Completed</option>
 						<option value="Collected">Collected</option>
 					</select>
 				</div>
                <label for="" class="col-sm-3 control-label"></label>
 			</div>

             <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Upload ID Card</label>
                <div class="col-sm-6">
                  <input name="idCard_file[]" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" multiple/>
                  <hr>
				  <?php
                    $i = 0;
                    $search_file = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$enid' AND file_title = 'idCard'") or die ("Error: " . mysqli_error($link));
                    if(mysqli_num_rows($search_file) == 0){
                    	echo "<span style='color: ".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>No file attached!!</span>";
                    }else{
                    	while($get_file = mysqli_fetch_array($search_file)){
                    		$i++;
                    ?>
                    <a href="<?php echo $fetchsys_config['file_baseurl'].$get_file['attached_file']; ?>" target="_blank"><img src="<?php echo $fetchsys_config['file_baseurl']; ?>file_attached.png" width="64" height="64"> Attachment<?php echo $i; ?></a>
                    <?php
                    	}
                    }
                    ?>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
 		        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">ID Card Status</label>
 		        <div class="col-sm-6">
 					<select name="idCardStatus" class="form-control select2" required>
 						<option value="<?php echo $fetchEnrollee['idCardStatus']; ?>" selected='selected'><?php echo $fetchEnrollee['idCardStatus']; ?></option>
 						<option value="Completed">Completed</option>
 						<option value="Collected">Collected</option>
 					</select>
 				</div>
                <label for="" class="col-sm-3 control-label"></label>
 			</div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Remarks</label>
                  <div class="col-sm-6">
                  <textarea name="remarks" class="form-control" rows="2" cols="80" required><?php echo $fetchEnrollee['remarks']; ?></textarea>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Transaction Pin</label>
                  <div class="col-sm-6">
                  <input name="tpin" type="text" inputmode="numeric" pattern="[0-9]*" class="form-control" placeholder="Transaction Pin" required>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>
				  
			 </div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                	<button name="updateEnrollee" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
			</form> 

            <?php
            }else{
            ?>
    
            <form class="form-horizontal">

             <div class="box-body">

                <div class="form-group">
                        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Transaction Type</label>
                        <div class="col-sm-6">
                            <select class="form-control select2" readonly>
                                <option value="<?php echo $fetchEnrollee['transactionType']; ?>" selected='selected'><?php echo $fetchEnrollee['transactionType']; ?></option>
                            </select>
                        </div>
                        <label for="" class="col-sm-3 control-label"></label>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">NIMC Partner</label>
                        <div class="col-sm-6">
                            <select class="form-control select2" readonly>
                                <option value="<?php echo $defaultPID; ?>" selected='selected'><?php echo $getPartner['partnerName']; ?></option>
                            </select>
                        </div>
                        <label for="" class="col-sm-3 control-label"></label>
                    </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">First Name</label>
                    <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="First Name" value="<?php echo $fetchEnrollee['userFname']; ?>" readonly>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>
                    
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Last Name</label>
                    <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="Last Name" value="<?php echo $fetchEnrollee['userLname']; ?>" readonly>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Middle Name(Optional)</label>
                    <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="Middle Name" value="<?php echo $fetchEnrollee['userMname']; ?>" readonly>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mobile</label>
                    <div class="col-sm-6">
                        <input type="tel" class="form-control" value="<?php echo $fetchEnrollee['phoneNo']; ?>" readonly>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label> 
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gender</label>
                    <div class="col-sm-6">
                        <select class="form-control" readonly>
                            <option value="<?php echo $fetchEnrollee['gender']; ?>" selected='selected'><?php echo $fetchEnrollee['gender']; ?></option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>


                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Email(Optional)</label>
                    <div class="col-sm-6">
                    <input type="text" type="text" class="form-control" placeholder="Email Address" value="<?php echo $fetchEnrollee['email']; ?>" readonly>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>
                    
                    
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Date of Birth</label>
                    <div class="col-sm-6">
                    <input type="date" class="form-control" value="<?php echo $fetchEnrollee['dateOfBirth']; ?>" readonly>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>
                    
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Tracking ID</label>
                    <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="Tracking ID" value="<?php echo $fetchEnrollee['trackingId']; ?>" readonly>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Associated TrackingID</label>
                    <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="Associated TrackingID" value="<?php echo $fetchEnrollee['associatedTrackingId']; ?>" readonly>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">BVN</label>
                    <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="BVN" value="<?php echo $fetchEnrollee['bvn']; ?>" readonly>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">NIN Number</label>
                    <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="NIN Number" value="<?php echo $fetchEnrollee['ninNo']; ?>" readonly>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Mode of Identification</label>
                    <div class="col-sm-6">
                        <select class="form-control select2" readonly>
                            <option value="<?php echo $fetchEnrollee['moi']; ?>" selected='selected'><?php echo $fetchEnrollee['moi']; ?></option>
                            <option value="Old National ID">Old National ID</option>
                            <option value="International Passport">International Passport</option>
                            <option value="Voters Card">Voters Card</option>
                            <option value="Drivers License">Drivers License</option>
                            <option value="Birth Certificate">Birth Certificate</option>
                            <option value="Sworn Affidavit">Sworn Affidavit</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Payment Type</label>
                    <div class="col-sm-6">
                        <select class="form-control select2" readonly>
                            <option value="<?php echo $fetchEnrollee['paymentType']; ?>" selected='selected'><?php echo $fetchEnrollee['paymentType']; ?></option>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Pos">Pos</option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Amount to Pay</label>
                    <div class="col-sm-6">
                    <input type="text" inputmode="numeric" pattern="[0-9.]*" class="form-control" placeholder="Amount to Pay" value="<?php echo $fetchEnrollee['amount']; ?>" readonly>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Balance to Pay</label>
                    <div class="col-sm-6">
                    <input type="text" inputmode="numeric" pattern="[0-9.]*" class="form-control" placeholder="Balance to Pay" value="<?php echo $fetchEnrollee['balance']; ?>" readonly>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Upload NIN Slip</label>
                    <div class="col-sm-6">
                    <?php
                        $i = 0;
                        $search_file = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$enid' AND file_title = 'ninSlip'") or die ("Error: " . mysqli_error($link));
                        if(mysqli_num_rows($search_file) == 0){
                            echo "<span style='color: ".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>No file attached!!</span>";
                        }else{
                            while($get_file = mysqli_fetch_array($search_file)){
                                $i++;
                        ?>
                        <a href="<?php echo $fetchsys_config['file_baseurl'].$get_file['attached_file']; ?>" target="_blank"><img src="<?php echo $fetchsys_config['file_baseurl']; ?>file_attached.png" width="64" height="64"> Attachment<?php echo $i; ?></a>
                        <?php
                            }
                        }
                        ?>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">NIN Slip Status</label>
                    <div class="col-sm-6">
                        <select class="form-control select2" readonly>
                            <option value="<?php echo $fetchEnrollee['ninSlipStatus']; ?>" selected='selected'><?php echo $fetchEnrollee['ninSlipStatus']; ?></option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Upload ID Card</label>
                    <div class="col-sm-6">
                    <?php
                        $i = 0;
                        $search_file = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$enid' AND file_title = 'idCard'") or die ("Error: " . mysqli_error($link));
                        if(mysqli_num_rows($search_file) == 0){
                            echo "<span style='color: ".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>No file attached!!</span>";
                        }else{
                            while($get_file = mysqli_fetch_array($search_file)){
                                $i++;
                        ?>
                        <a href="<?php echo $fetchsys_config['file_baseurl'].$get_file['attached_file']; ?>" target="_blank"><img src="<?php echo $fetchsys_config['file_baseurl']; ?>file_attached.png" width="64" height="64"> Attachment<?php echo $i; ?></a>
                        <?php
                            }
                        }
                        ?>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">ID Card Status</label>
                    <div class="col-sm-6">
                        <select class="form-control select2" readonly>
                            <option value="<?php echo $fetchEnrollee['idCardStatus']; ?>" selected='selected'><?php echo $fetchEnrollee['idCardStatus']; ?></option>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Remarks</label>
                    <div class="col-sm-6">
                    <textarea class="form-control" rows="4" cols="80" readonly><?php echo $fetchEnrollee['remarks']; ?></textarea>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                    </div>

             </div>

            </form>

            <?php
            }
            ?>

</div>	
</div>	
</div>
</div>