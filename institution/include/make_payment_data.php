<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><u><b>Transfer Wallet:</b></u><i class='fa fa-hand-o-right'></i> 
            <button type="button" class="btn btn-flat bg-white" align="left">&nbsp;
                <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
                    <?php 
                        echo $icurrency.number_format($itransfer_balance,2,".",",");
                    ?>
                </strong>
            </button>
            <a href="make_payment.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=MTAwMA==&&pcode=<?php echo $_GET['pcode']; ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a></h3>
            </div>
             <div class="box-body">

<hr>
<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" style="font-size:17px;" align="center">
    <p>
        <?php
        //echo $walletafrica_status;
        if($mo_virtualacct_status == "Active" && ($walletafrica_status == "NotActive" || $walletafrica_status == "Active")){
            
            include("../config/monnify_virtualaccount.php");
            
        }
        elseif($mo_virtualacct_status == "NotActive" && $walletafrica_status == "Active"){
            
            include("../config/walletafrica_restfulapis_call.php");
            include("walletafrica_virtulaccount.php");
            
        }
        ?>
    </p>
</div>
</hr>


<?php
    $customer = $_GET['acn'];
    $plan_code = $_GET['pcode'];
    $search_splan = mysqli_query($link, "SELECT * FROM savings_subscription WHERE new_plancode = '$plan_code'");
	$fetch_splan = mysqli_fetch_object($search_splan);
    $myminamountpaid = $fetch_splan->amount;
	$product_currency = $fetch_splan->currency;
	$merchantid_others = $fetch_splan->companyid;
    $vendorid = $fetch_splan->vendorid;
    $oldpcode = $fetch_splan->plan_code;
    $mysInterval = $fetch_splan->savings_interval;
?>

    <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b>Complete the activation with your transaction Pin</b></p>

    <hr>
    <form class="form-horizontal" method="post" enctype="multipart/form-data">

    <?php
    if(isset($_POST['activatePlan'])){
       
        $fund_source = mysqli_real_escape_string($link, $_POST['fund_source']);
        $otpCode = ($fund_source == "1") ? "" : mysqli_real_escape_string($link, $_POST['otpCode']);
        $search_cust = mysqli_query($link, "SELECT * FROM borrowers WHERE (account = '$customer' OR virtual_acctno = '$customer')");
        $fetch_cust = mysqli_fetch_array($search_cust);
        $binvest_bal = $fetch_cust['investment_bal'];
        $myfn = $fetch_cust['fname'];
        $myln = $fetch_cust['lname'];
        $mymn = $fetch_cust['mname'];
        $phone2 = $fetch_cust['phone'];
        $myfullname = $myfn.' '.$myln.' '.$mymn;
        $custemail = $fetch_cust['email'];
        $baddrs = $fetch_cust['addrs'];
        $bstate = $fetch_cust['state'];
        $bgender = $fetch_cust['gender'];
        $dateofbirth = $fetch_cust['dob'];
        $acctID = $fetch_cust['account'];
        $bbvn = $fetch_cust['unumber'];
        $lga = $fetch_cust['lga'];
        $moi = $fetch_cust['moi'];
        $mmaidenName = $fetch_cust['mmaidenName'];
        $otherInfo = $fetch_cust['otherInfo'];
        $bnok = $fetch_cust['nok'];
        $bnok_rela = $fetch_cust['nok_rela'];
        $nok_addrs = $fetch_cust['nok_addrs'];
        $boccupation = $fetch_cust['occupation'];
        $bemployer = $fetch_cust['employer'];

        $source = ($fund_source == "0") ? $fetch_cust['wallet_balance'] : $itransfer_balance;
        $id = $_GET['id'];
        $plancode = mysqli_real_escape_string($link, $_POST['plancode']);
        $amountpaid = preg_replace("/[^0-9]/", "", mysqli_real_escape_string($link, $_POST['amountpaid']));
        $account_number =  ccMasking($ivirtual_acctno);
        $b_name = $iname;
        $tpin = mysqli_real_escape_string($link, $_POST['tpin']);
        $new_reference = "EA".date("dy").time();
        $mybank_name = "Wallet Balance";

        $bank_details = "Bank Name: Wallet Balance";
        $bank_details .= ", Account Name: ".$b_name;
        $bank_details .= ", Account Number: ".$account_number;

        $search_splan1 = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$plancode'");
        $fetch_splan1 = mysqli_fetch_object($search_splan1);
        $plan_id = $fetch_splan1->plan_id;
        $plan_name = $fetch_splan1->plan_name;
        $companyid = $fetch_splan1->merchantid_others;
        $plan_type = $fetch_splan1->planType;
        $vendorid = $fetch_splan1->branchid;
        $planamount = $amountpaid;
        $plancurrency = $fetch_splan1->currency;
        $categories = $fetch_splan1->categories;
        $duration = $fetch_splan1->duration;
        $divi_type = $fetch_splan1->dividend_type;
        $dividend = $fetch_splan1->dividend;
        $frequency = $fetch_splan1->frequency;
        $todays_date = date('Y-m-d h:i:s');
        $myfullname = $myfn.' '.$myln;
        $converted_date = date('m/d/Y').' '.(date(h) + 1).':'.date('i a');
        $converted_date2 = date('Y-m-d').' '.(date(h) + 1).':'.date('i:s');
        $commtype = $fetch_splan1->commtype;
        $commvalue = $fetch_splan1->commvalue;
        $agentcomm = $fetch_splan1->agentcomm;
        $calc_percent = $planamount - (($commvalue/100) * $planamount);

        //MERCHANT CUT
        $merchantRevenue = ($commtype == "percentage") ? $calc_percent : $commvalue;
        $agentRevenue = ($agentcomm/100) * $merchantRevenue;
        
        //Calculate Maturity Period
        $maturity_period = ($fetch_splan1->maturity_period == "weekly" ? 'week' : ($fetch_splan1->maturity_period == "monthly" ? 'month' : ($fetch_splan1->maturity_period == "daily" ? 'day' : 'year')));
        $mature_date = date('Y-m-d h:i:s', strtotime('+'.$frequency.' '.$maturity_period, strtotime($todays_date)));
        
        //Calculate Next Payment Date
        $savings_interval = ($mysInterval == "daily" ? 'day' : ($mysInterval == "weekly" ? 'week' : ($mysInterval == "monthly" ? 'month' : 'year')));
        $next_pmt_date = date('Y-m-d h:i:s', strtotime('+1 '.$savings_interval, strtotime($todays_date)));
        
        $search_sub = mysqli_query($link, "SELECT * FROM savings_subscription WHERE id = '$id'");
        $fetch_sub = mysqli_fetch_array($search_sub);
        $real_subscription_code = $fetch_sub['subscription_code'];
        $real_invoice_code = date("yhi").time();
        $sub_bal = $fetch_sub['sub_balance'] + $planamount;

        $search_myinst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$companyid'");
        $fetch_myinst = mysqli_fetch_array($search_myinst);

        $search_myvendor = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
        $fetch_myvend_num = mysqli_num_rows($search_myvendor);
        $fetch_myvendor = mysqli_fetch_array($search_myvendor);
        $vendemail = $fetch_myvendor['cemail'];

        $search_myuser = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$companyid' AND branchid = '$vendorid'");
        $fetch_myuser = mysqli_fetch_array($search_myuser);
        $creatorEmail = ($fetch_myvend_num == 1) ? $iofficial_email.','.$vendemail.','.$custemail : $iofficial_email.','.$fetch_myuser['email'].','.$custemail;

        $vc_name = ($fetch_myvend_num == 1) ? $fetch_myvendor['cname'] : $fetch_myinst['institution_name'];
        $vwalBalance = ($fetch_myvend_num == 1) ? $fetch_myvendor['wallet_balance'] : $fetch_myinst['wallet_balance'];
        $vendorBalance = ($vendorid == "") ? ($vwalBalance + $planamount) : (($vwalBalance + $planamount) - $merchantRevenue);

        $search_merchant = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$companyid' AND (role = 'agent_manager' OR role = 'institution_super_admin' OR role = 'merchant_super_admin')");
        $fetch_merchant = mysqli_fetch_array($search_merchant);
        $merchantIuid = $fetch_merchant['id'];
        $merchantBal = $fetch_merchant['transfer_balance'] + ($merchantRevenue - $agentRevenue);
        $customerBal = $fetch_cust['wallet_balance'];

        $verify_otp = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '$otpCode' AND userid = '$iuid' AND status = 'Pending'");
		$fetch_data = mysqli_fetch_array($verify_otp);
		$otpnum = mysqli_num_rows($verify_otp);
		
		$ValidID = $fetch_kycRequirement['ValidID']; //Required or Optional
        $UtilityBills = $fetch_kycRequirement['UtilityBills']; //Required or Optional
        $mySignature = $fetch_kycRequirement['mySignature']; //Required or Optional
        $myVerifiedBvn = $fetch_kycRequirement['bvn']; //Required or Optional
        $myBiodata = $fetch_kycRequirement['biodata']; //Required or Optional
        $myNok = $fetch_kycRequirement['nok']; //Required or Optional
        $myOccupation = $fetch_kycRequirement['occupation']; //Required or Optional
        
        //Customer KYC Verification Doc.
        $search_ValidID = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$acctID' AND file_title = 'ValidID'");
        $fetch_ValidID = mysqli_num_rows($search_ValidID);
        
        $search_Utility = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$acctID' AND file_title = 'UtilityBills'");
        $fetch_Utility = mysqli_num_rows($search_Utility);
        
        $search_Signature = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$acctID' AND file_title = 'Signature'");
        $fetch_Signature = mysqli_num_rows($search_Signature);
        //End of Customer KYC Verification Doc.

        if($fund_source == "0" && $otpnum == 0){

            //DELETE OTP RECORDS
            mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$otpCode' AND status = 'Pending'");
            echo "<div class='alert bg-orange'>Opps!...Invalid OTP Entered!!</div>";

        }
        elseif($tpin != $myiepin){

            //DELETE OTP RECORDS
            ($fund_source == "1") ? "" : mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$otpCode' AND status = 'Pending'");
            echo "<div class='alert bg-orange'>Oops!....Invalid Transaction Pin!!</div>";

        }
        elseif($amountpaid > $source){

            //DELETE OTP RECORDS
            ($fund_source == "1") ? "" : mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$otpCode' AND status = 'Pending'");
            echo "<div class='alert bg-orange'>Oops!....Insufficient Balance!!</div>";

        }
        elseif(($fetch_ValidID == 0 && $ValidID == "Required") || ($fetch_Utility == 0 && $UtilityBills == "Required") || ($fetch_Signature == 0 && $mySignature == "Required") || ($bbvn == "" && $myVerifiedBvn == "Required")){
        
            echo "<div class='alert bg-orange'>Sorry! Customer kyc document/bvn needs to be updated...</div>";

        }elseif($myBiodata == "Required" && ($mymn == "" || $baddrs == "" || $dateofbirth == "" || $bgender == "" || $bstate == "" || $lga == "" || $moi == "" || $mmaidenName == "" || $otherInfo == "")){
            
            echo "<div class='alert bg-orange'>Sorry! Customer biodata needs to be updated...</div>";

        }elseif($myNok == "Required" && ($bnok == "" || $bnok_rela == "" || $nok_addrs == "")){
            
            echo "<div class='alert bg-orange'>Sorry! Customer next of kin information needs to be updated...</div>";

        }elseif($myOccupation == "Required" && ($boccupation == "" || $bemployer == "")){
            
            echo "<div class='alert bg-orange'>Sorry! Customer needs to update occupation/employer details...</div>";

        }
        else{

            //Sender Parameters
            $amountDebited = ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin") ? ($amountpaid - $agentRevenue) : $amountpaid;
            $senderBalance = $source - $amountDebited;
            $senderBalBforCommission = $source - $amountpaid;
            $myinvestBal = $binvest_bal + $amountpaid;
            
            //Email Notification Service
            $sendSMS->productPaymentNotifier($creatorEmail, $converted_date, $new_reference, $customer, $myfullname, $real_subscription_code, $plancode, $categories, $plan_name, $mybank_name, $account_number, $b_name, $plancurrency, $amountpaid, $iemailConfigStatus, $ifetch_emailConfig);
            
            //UPDATE CUSTOMER BALANCE
            $update_records = mysqli_query($link, "UPDATE borrowers SET investment_bal = '$myinvestBal' WHERE (account = '$customer' OR virtual_acctno = '$customer')");
            
            //UPDATE FUND SOURCE BALANCE
            $totalAgentBalanceAfterCommission = $itransfer_balance + $agentRevenue;
            ($fund_source == "0") ? $update_records = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalBforCommission' WHERE (account = '$customer' OR virtual_acctno = '$customer')") : "";
            ($fund_source == "0") ? $update_records = mysqli_query($link, "UPDATE user SET transfer_balance = '$totalAgentBalanceAfterCommission' WHERE id = '$iuid'") : "";
            ($fund_source == "1") ? $update_records = mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$iuid'") : "";

            //UPDATE MERCHANT BALANCE
            $update_records = mysqli_query($link, "UPDATE user SET transfer_balance = '$merchantBal' WHERE id = '$merchantIuid'");

            //INSERT SUBSCRIPTION RECORD, TRANSACTION RECORD AND OTHERS
            $insert = mysqli_query($link, "UPDATE savings_subscription SET sub_balance = '$sub_bal', next_pmt_date = '$next_pmt_date' WHERE id = '$id'");
            $insert = mysqli_query($link, "INSERT INTO all_savingssub_transaction VALUES(null,'$companyid','$customer','$real_invoice_code','$real_subscription_code','$new_reference','$plancurrency','$planamount','successful','$todays_date','','$myfn','$myln','','','','','Wallet Transfer','','$plancode','$vendorid','$iuid')");
            //$update_records = mysqli_query($link, "INSERT INTO manual_investsettlement VALUES(null,'$companyid','$vendorid','$vc_name','$customer','$myfullname','$refid','$plancode','$real_subscription_code','$planname','$plancurrency','$planamount','$converted_date2','Pending')");
            $insert = mysqli_query($link, "INSERT INTO investment_notification VALUES(null,'$companyid','$vendorid','$customer','$myfullname','$new_reference','$plancode','$real_subscription_code','$plan_name','$plancurrency','$planamount','$bank_details','$converted_date2','Approved')");

            //POST PAYMENT AND SETTLE COMMISSION FOR AGENT AND MERCHANT
            ($vendorid == "") ? "" : $update_records = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $iname($ivirtual_acctno)','$merchantRevenue','','Credit','$plancurrency','MERCHANT_COMMISSION','Merchant Commission for the subscription of Product Name: $plan_name','successful','$todays_date','','$merchantBal','')") or die (mysqli_error($link));
            ($vendorid == "") ? "" : $update_records = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $iname($ivirtual_acctno)','','$merchantRevenue','Debit','$plancurrency','MERCHANT_COMMISSION','Merchant Commission for the subscription of Product Name: $plan_name','successful','$todays_date','$vendorid','$vendorBalance','')") or die (mysqli_error($link));
            $update_records = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Sub Payment for: $customer','','$amountpaid','Debit','$plancurrency','INVESTMENT_SUB','Payment for the subscription of Product Name: $plan_name','successful','$todays_date','$iuid','$senderBalBforCommission','')") or die (mysqli_error($link));
            $update_records = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $iname($ivirtual_acctno)','','$amountpaid','Debit','$plancurrency','INVESTMENT_SUB','Payment for the subscription of Product Name: $plan_name','successful','$todays_date','$acctID','$senderBalBforCommission','')") or die (mysqli_error($link));
            $update_records = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $iname($ivirtual_acctno)','$amountDebited','','Credit','$plancurrency','INVESTMENT_SUB','Payment for the subscription of Product Name: $plan_name. Initiated By $iname($ivirtual_acctno)','successful','$todays_date','$vendorid','$vendorBalance','')") or die (mysqli_error($link));
            ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin" && $fund_source == "1") ? $update_records = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','self','$agentRevenue','','Credit','$plancurrency','COMMISSION','Commission for the subscription of Product Name: $plan_name','successful','$todays_date','$iuid','$senderBalance','')") or die (mysqli_error($link)) : "";
            ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin" && $fund_source == "0") ? $update_records = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','self','$agentRevenue','','Credit','$plancurrency','COMMISSION','Commission for the subscription of Product Name: $plan_name','successful','$todays_date','$iuid','$totalAgentBalanceAfterCommission','')") or die (mysqli_error($link)) : "";

            //UPDATE VENDOR OR PLAN CREATOR BALANCE
            ($vendorid == "") ? $update_records = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$vendorBalance' WHERE institution_id = '$companyid'") : $update_records = mysqli_query($link, "UPDATE vendor_reg SET wallet_balance = '$vendorBalance' WHERE companyid = '$vendorid'");

            //DELETE OTP RECORDS
            ($fund_source == "1") ? "" : mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$otpCode' AND status = 'Pending'");

            echo "<script>alert('Payment made Successfully!!'); </script>";
            echo "<script>window.location='allproduct_sub.php?id=".$_SESSION['tid']."&&mid=".base64_encode("1000")."'; </script>";

        }

    }
    ?>
    
    <input name="customer" type="hidden" class="form-control" id="verify_virtualacct" value="<?php echo $_GET['acn']; ?>" readonly>

    <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Code</label>
        <div class="col-sm-6">
            <input name="plancode" type="text" class="form-control" value="<?php echo $oldpcode; ?>" readonly/>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

    <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount Paid</label>
        <div class="col-sm-6">
            <input name="amountpaid" type="text" class="form-control" value="<?php echo number_format($myminamountpaid,0,'.',','); ?>" readonly/>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

    <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Fund Source</label>
        <div class="col-sm-6">
            <select name="fund_source" class="form-control select2" style="width: 100%;" id="fund_source" /required>
                <option value="" selected="selected">---Choose Funding Source---</option>
                <option value="0">Customer Wallet</option>
                <option value="1">My Wallet</option>
            </select>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

    <span id='ShowValueFrank'></span>
    <span id='ShowValueFrank'></span>

    <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin</label>
        <div class="col-sm-6">
            <input name="tpin" type="password" class="form-control" placeholder="Your Transaction Pin" required/>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>
        
    <div class="form-group" align="right">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
        <div class="col-sm-6">
            <button name="activatePlan" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-forward"></i> Pay Now!</button>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

    </form>


</div>	
</div>	
</div>
</div>