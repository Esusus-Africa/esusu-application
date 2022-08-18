<?php include("include/header.php"); ?>

<?php
$acct_owner = $_GET['uid'];
$search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE id = '$acct_owner'");
$sRowNum = mysqli_num_rows($search_mystaff);
$fetch_mystaff = mysqli_fetch_array($search_mystaff);

$search_borro = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acct_owner'");
$bRowNum = mysqli_num_rows($search_borro);
$fetch_borro = mysqli_fetch_array($search_borro);

$searchVAWN = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$acct_owner'");
$fetchVAWN = mysqli_fetch_array($searchVAWN);

$userWalletName = $fetchVAWN['account_name'];
$transferLimitPerDayy = $fetchVAWN['transferLimitPerDay'];
$transferLimitPerTrans = $fetchVAWN['transferLimitPerTrans'];
$airtimeDataLimitPerDayy = $fetchVAWN['airtime_dataLimitPerDay'];
$airtimeDataLimitPerTranss = $fetchVAWN['airtime_dataLimitPerTrans'];
$userType = ($sRowNum == 0 && $bRowNum == 1) ? "Customer" : "User";
$userId = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['id'] : $fetch_mystaff['userid'];
$userWalletBal = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['wallet_balance'] : $fetch_mystaff['transfer_balance'];
$userDob = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['dob'] : $fetch_mystaff['dob'];
$userPhone = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['phone'] : $fetch_mystaff['phone'];
$userLastName = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['lname'] : $fetch_mystaff['lname'];
$userVerifiedBVN = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['unumber'] : $fetch_mystaff['addr2'];
$userUpgradeStatus = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['status'] : $fetch_mystaff['status'];
$institution_id = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['branchid'] : $fetch_mystaff['created_by'];
$isbranchid = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['sbranchid'] : $fetch_mystaff['branchid'];

$seach_membersttings = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$fetch_memset = mysqli_fetch_array($seach_membersttings);
$icurrency = $fetch_memset['currency'];

$searchDocFile = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$acct_owner'");
$numDocFile = mysqli_fetch_array($searchDocFile);
$fetchDocFile = mysqli_fetch_array($searchDocFile);
?>

    <div class="modal-dialog modal-lg">
          <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <legend style="color: blue;"><b>Upgrade <?php echo $userWalletName; ?> Wallet: <?php echo ($userUpgradeStatus == "Verified") ? "<span class='label bg-blue'>Verified <i class='fa fa-check'></i></span>" : "<span class='label bg-orange'>".$userUpgradeStatus." <i class='fa fa-info'></i></span>"; ?></b></legend>
        </div>
        <div class="modal-body">

        <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['upgrade'])){

    $mybvn = mysqli_real_escape_string($link, $_POST['cust_bvn']);
    $status = mysqli_real_escape_string($link, $_POST['status']);
    $comment = mysqli_real_escape_string($link, $_POST['comment']);
    $transferLimitPerDay = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['transferLimitPerDay']));
    $perTransTransferLimit = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['transferLimitPerTrans']));
    $airtimeDataLimitPerDay = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['airtimeDataLimitPerDay']));
    $airtimeDataLimitPerTrans = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['airtimeDataLimitPerTrans']));
    $date_time = date("Y-m-d H:i:s");

    if($status != "Verified" && $comment == ""){

        echo "<div class='alert bg-orange'>Opps!..You need to input reasons for the status selected!!</div>";

    }
    else{

        foreach ($_FILES['uploaded_file']['name'] as $key => $name){
        
            $newFilename = $name;
            
            if($newFilename == "")
            {
                echo "";
            }
            else{
                $newlocation = $newFilename;
                if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], '../img/'.$newFilename))
                {
                    mysqli_query($link, "INSERT INTO attachment VALUES(null,'$userId','$acct_owner','$acct_owner','$newlocation',NOW())") or die (mysqli_error($link));
                }
            }
            
        }
    
        ($status == "Declined" && $numDocFile != 0) ? unlink($fetchsys_config['file_baseurl'].$fetchDocFile['attached_file']) : "";
        ($status == "Declined" && $numDocFile != 0) ? mysqli_query($link, "DELETE FROM attachment WHERE borrowerid = '$acct_owner'") : "";
        ($comment == "") ? "" : mysqli_query($link, "INSERT INTO wallet_case VALUES(null,'$institution_id','$isbranchid','$uid','$acct_owner','$status','$comment','$date_time')");
        
        ($userVerifiedBVN == "") ? "" : mysqli_query($link, "UPDATE virtual_account SET acct_status = '$status', transferLimitPerDay = '$transferLimitPerDay', transferLimitPerTrans = '$perTransTransferLimit', airtime_dataLimitPerDay = '$airtimeDataLimitPerDay', airtime_dataLimitPerTrans = '$airtimeDataLimitPerTrans' WHERE userid = '$acct_owner'");
        ($userVerifiedBVN != "" && $userType == "User") ? mysqli_query($link, "UPDATE user SET addr2 = '$mybvn', status = '$status' WHERE id = '$acct_owner'") : "";
        ($userVerifiedBVN != "" && $userType == "Customer") ? mysqli_query($link, "UPDATE borrowers SET unumber = '$mybvn', status = '$status' WHERE account = '$acct_owner'") : "";
        
        echo "<div class='alert bg-blue'>Account $status Successfully</div>";
        echo '<meta http-equiv="refresh" content="5;url=upgradeWallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&uid='.$acct_owner.'">';

    }

}
?>

            <div class="box-body">

            <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Upload Documents:</label>
            <div class="col-sm-6">
                <input name="uploaded_file[]" type="file" class="btn bg-orange" multiple/>
                <span style="color: orange;">Document Accepted: <b>GOVERNMENT ISSUED ID CARD, INTL. PASSPORT, UTILITY BILLS, BANK ACCOUNT STATEMENT</b></span>
                <hr>
                    <?php
                    $acct_owner = $_GET['uid'];
                    $i = 0;
                    $search_file = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$acct_owner'") or die ("Error: " . mysqli_error($link));
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
                <hr>
            </div>
            <label for="" class="col-sm-2 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-4 control-label" style="color:blue;">BVN: <?php echo ($userVerifiedBVN != "" && strlen($userVerifiedBVN) == "11") ? '<span style="color: blue">[<b>Verified</b> <i class="fa fa-check"></i>]</span>' : '<span style="color: orange">[<b>Not-Verified</b> <i class="fa fa-times"></i>]</span>'; ?></label>
                <div class="col-sm-6">
                    <input name="cust_bvn" type="text" class="form-control" value="<?php echo $userVerifiedBVN; ?>" placeholder="BVN Number Here" maxlength="11">
                    <span style="color: orange;"> BVN Verification cost <b style="color: blue;"><?php echo $icurrency.number_format($bvn_fee,2,'.',','); ?></b>. </span>
                    <br>
                    <div class="scrollable">
                    <?php
                    if(isset($_POST['verifyBVN'])){
                        
                        $userBvn = mysqli_real_escape_string($link, $_POST['cust_bvn']);
                        
                        if($userWalletBal < $bvn_fee){
            
                            echo "<br><span class='bg-orange'>Sorry! No sufficient fund in $userType Wallet for this verification</span>";
                            
                        }  
                        elseif(strlen($userBvn) != 11 && $userBvn != ""){
                            
                            echo "<br><span>BVN Number not Valid....</span>";
                            
                        }
                        elseif($ibvn_route == "Wallet Africa"){
                            
                                require_once "../config/bvnVerification_class.php";
            
                                $processBVN = $newBV->walletAfricaBVNVerifier($walletafrica_skey,$userBvn,$link);
                                $ResponseCode = $processBVN['ResponseCode'];
                            
                                if($ResponseCode == "200"){
                                
                                    $icm_id = "ICM".time();
                                    $exp_id = "EXP".time();
                                    $myOtp = substr((uniqid(rand(),1)),3,6);
                                    $rOrderID = "EA-bvnCharges-".time();
                                
                                    $date_time = date("Y-m-d");
                                    $wallet_date_time = date("Y-m-d H:i:s");
                                    
                                    $wbalance = $userWalletBal - $bvn_fee;
                                    //substr()
                                    //BVN Details
                                    $bvn_fname = $processBVN['FirstName'];
                                    $bvn_lname = $processBVN['LastName'];
                                    $bvn_mname = $processBVN['MiddleName'];
                                    $bvn_dob = $processBVN['DateOfBirth'];
                                    $bvn_phone = "+234".substr($processBVN['PhoneNumber'],-10);
                                    $correct_bvnPhone = $processBVN['PhoneNumber'];
                                    $bvn_email = $processBVN['Email'];
                                    $bvn_picture = $processBVN['Picture'];
                                    $dynamicStr = md5(date("Y-m-d h:i"));
                                    $image_converted = $newBV->base64_to_jpeg($bvn_picture, $dynamicStr.".png");
                                    $default_dob = date("d-M-Y", strtotime($userDob));

                                    //20 array row
                                    $mybvn_data = $processBVN['BVN']."|".$processBVN['FirstName']."|".$processBVN['LastName']."|".$processBVN['MiddleName']."|".$processBVN['DateOfBirth']."|".$processBVN['PhoneNumber']."|".$processBVN['Email']."|".$processBVN['EnrollmentBank']."|".$processBVN['EnrollmentBranch']."|".$processBVN['Gender']."|".$processBVN['LevelOfAccount']."|".$processBVN['LgaOfOrigin']."|".$processBVN['LgaOfResidence']."|".$processBVN['MaritalStatus']."|".$processBVN['NameOnCard']."|".$processBVN['Nationality']."|".$processBVN['StateOfOrigin']."|".$processBVN['StateOfResidence']."|".$processBVN['Title']."|".$processBVN['WatchListed']."|".$image_converted;
                                    
                                    $search_bvnverify = mysqli_query($link, "SELECT * FROM bvn_log WHERE accountID = '$acct_owner'");
                                    $fetch_bvnverify = mysqli_fetch_array($search_bvnverify);
                                    $bvn_nos = mysqli_num_rows($search_bvnverify);
                                    $concat = $fetch_bvnverify['mydata'];
                                    $parameter = (explode('|',$concat));
                                    $old_picture = $parameter[20];
                                    
                                    //include("alert_sender/bvn_otp.php");
                                    ($userType == "Customer") ? $update_wallet = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$wbalance' WHERE account = '$acct_owner'") : $update_wallet = mysqli_query($link, "UPDATE user SET transfer_balance = '$wbalance' WHERE id = '$acct_owner'");
                                    
                                    ($bvn_nos == 1 && $old_picture != "") ? unlink($fetchsys_config['file_baseurl'].$old_picture) : "";
                                    
                                    ($bvn_nos == 1) ? $update_bvn = mysqli_query($link, "UPDATE bvn_log SET mydata = '$mybvn_data' WHERE accountID = '$acct_owner'") : $insert_bvn = mysqli_query($link, "INSERT INTO bvn_log VALUE(null,'$institution_id','$isbranchid','$acct_owner','$uid','$mybvn_data','$bvn_fee','$wallet_date_time','$rOrderID')");
                                    
                                    $insert_income = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$rOrderID','$userBvn','','$bvn_fee','Debit','$icurrency','BVN_Charges','Response: $icurrency.$bvn_fee was charged for Customer BVN Verification','successful','$wallet_date_time','$uid','$wbalance','')");
                                    
                                    $insert_income = mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','BVN','$bvn_fee','$date_time','$userType BVN Verification Charges')");
                                    
                                    ($userType == "Customer" && $bvn_lname == $userLastName && ($bvn_dob == $default_dob || $bvn_phone == $userPhone || $userPhone == $correct_bvnPhone)) ? mysqli_query($link, "UPDATE borrowers SET unumber = '$userBvn', status = 'UnderReview' WHERE account = '$acct_owner'") : "";
                                    ($userType == "User" && $bvn_lname == $userLastName && ($bvn_dob == $default_dob || $bvn_phone == $userPhone || $userPhone == $correct_bvnPhone)) ? mysqli_query($link, "UPDATE user SET addr2 = '$userBvn', status = 'UnderReview' WHERE id = '$acct_owner'") : "";
                                    
                                    ($bvn_lname == $userLastName && ($bvn_dob == $default_dob || $bvn_phone == $userPhone || $userPhone == $correct_bvnPhone)) ? mysqli_query($link, "UPDATE virtual_account SET acct_status = 'UnderReview' WHERE userid = '$acct_owner'") : "";

                                    echo ($bvn_lname == $userLastName && ($bvn_dob == $default_dob || $bvn_phone == $userPhone || $userPhone == $correct_bvnPhone)) ? '<p style="color: blue;"><b>Data Verified Successfully!</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Verification Not Successful! Please try with correct BVN that Match your Information on our System</b> <i class="fa fa-times"></i></p>';
                                    
                                    echo '<meta http-equiv="refresh" content="10;url=upgradeWallet.php?id='.$_SESSION['tid'].'&&mid=NDA0&&uid='.$acct_owner.'">';

                            }
                            else{
                                echo "<br><span class='bg-orange'>Oops! Network Error, please try again later </span>";
                            }
                            
                        }
                        else{
                            
                            //empty
                            echo "Sorry! Service not available at the moment, please try again later!!";
                            
                        }
                        
                    }
                    ?>
                    </div>
                </div>
                <label for="" class="col-sm-2 control-label"><button type="submit" class="btn bg-blue btn-flat" name="verifyBVN"><i class="fa fa-eye">&nbsp;Verify</i></button></label>
            </div>
            

            <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Transfer Limit <i>(per Day)</i>:</label>
            <div class="col-sm-6">
            <input name="transferLimitPerDay" type="text" class="form-control" value="<?php echo $transferLimitPerDayy; ?>" placeholder="Transfer Limit per Day" required>
            </div>
            <label for="" class="col-sm-2 control-label"></label>
            </div>

            <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Transfer Limit <i>(per Trans.)</i>:</label>
            <div class="col-sm-6">
            <input name="transferLimitPerTrans" type="text" class="form-control" value="<?php echo $transferLimitPerTrans; ?>" placeholder="Transfer Limit per Transaction" required>
            </div>
            <label for="" class="col-sm-2 control-label"></label>
            </div>


            <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Airtime/Data Limit <i>(per Day)</i>:</label>
            <div class="col-sm-6">
            <input name="airtimeDataLimitPerDay" type="text" class="form-control" value="<?php echo $airtimeDataLimitPerDayy; ?>" placeholder="Airtime/Data Limit per Day" required>
            </div>
            <label for="" class="col-sm-2 control-label"></label>
            </div>

            <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Airtime/Data Limit <i>(per Trans.)</i>:</label>
            <div class="col-sm-6">
            <input name="airtimeDataLimitPerTrans" type="text" class="form-control" value="<?php echo $airtimeDataLimitPerTranss; ?>" placeholder="Airtime/Data Limit per Transaction" required>
            </div>
            <label for="" class="col-sm-2 control-label"></label>
            </div>


            <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Status:</label>
            <div class="col-sm-6">
                <select name="status" class="form-control select2" style="width:100%" required>
    				<option value="<?php echo $userUpgradeStatus; ?>" selected="selected"><?php echo $userUpgradeStatus; ?></option>
                    <option value="Declined">Decline Verification</option>
                    <option value="Verified">Verify Account</option>
                    <option value="Suspended">Suspend Account</option>
                </select>
            </div>
            <label for="" class="col-sm-2 control-label"></label>
            </div>

            <div class="form-group">
            <label for="" class="col-sm-4 control-label" style="color:blue;">Comment:</label>
            <div class="col-sm-6">
                <textarea name="comment"  class="form-control" rows="2" cols="80"></textarea>
            </div>
            <label for="" class="col-sm-2 control-label"></label>
            </div>
				  
			 </div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-4 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                	<button name="upgrade" type="submit" class="btn bg-blue"><i class="fa fa-link">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-2 control-label"></label>
            </div>

			 </form> 

        </div>
        <div style="font-size:10px;"><?php include("include/footer.php"); ?></div>
      </div>   
      
    </div>


