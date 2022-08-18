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
            <a href="verify_card3.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("1000"); ?>&&pcode=<?php echo $_GET['pcode']; ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a>
            </h3>
            </div>
             <div class="box-body">

<hr>
<div class="slideshow-container">
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

<a class="myprev" onclick="plusSlides(-1)">&#10094;</a>
<a class="mynext" onclick="plusSlides(1)">&#10095;</a>
    
</div>
</hr>
  

 <?php
    function myreference($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

	$plan_code = $_GET['pcode'];

	$search_splan = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$plan_code'");
    $fetch_splan = mysqli_fetch_object($search_splan);
    $plan_id = $fetch_splan->plan_id;
    $mymin_amountpaid = $fetch_splan->min_amount;
    $mymax_amountpaid = $fetch_splan->max_amount;
    $plan_type = $fetch_splan->planType;
    $savings_interval = $fetch_splan->savings_interval;
    $default_duration = $fetch_splan->duration;
	$product_currency = $fetch_splan->currency;
	$merchantid_others = $fetch_splan->merchantid_others;
    $vendorid = $fetch_splan->branchid;
    $my_commtype = $fetch_splan->commtype;
    $my_commvalue = $fetch_splan->commvalue;
    $my_agentcomm = $fetch_splan->agentcomm;
    $planname = $fetch_splan->plan_name;
    $plandesc = $fetch_splan->plan_desc;
    $my_calc_percent = $mymin_amountpaid - (($my_commvalue/100) * $mymin_amountpaid);
    $my_calc_percent2 = $mymax_amountpaid - (($my_commvalue/100) * $mymax_amountpaid);
    $myPMethod = $fetch_splan->pmethod;
    $parameterMethod = (explode(',',$myPMethod));
    $countNum = count($parameterMethod);

    $select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$row1 = mysqli_fetch_object($select1);
    $wUsername = $row1->wellahealth_clientid;
    $wPassword = $row1->wellahealth_clientsecretkey;
    $AgentCode = $row1->wellahealth_agentcode;

    //MERCHANT CUT
    $my_merchantRevenue = ($my_commtype == "percentage") ? $my_calc_percent : $my_commvalue;
    $my_merchantRevenue2 = ($my_commtype == "percentage") ? $my_calc_percent2 : $my_commvalue;
    $my_agentRevenue = ($my_agentcomm/100) * $my_merchantRevenue;
    $my_agentRevenue2 = ($my_agentcomm/100) * $my_merchantRevenue2;
?>

<?php
for($i = 0; $i < $countNum; $i++){

    if($parameterMethod[$i] == "wallet"){
?>

    <p style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">PLAN AMOUNT RANGES FROM: <b><?php echo $product_currency.number_format($mymin_amountpaid,2,'.',','); ?> - <?php echo $product_currency.number_format($mymax_amountpaid,2,'.',','); ?></b></p>
    <p style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">AGENT COMMISSION: <b><?php echo $product_currency.number_format($my_agentRevenue,2,'.',','); ?> - <?php echo $product_currency.number_format($my_agentRevenue2,2,'.',','); ?></b></p>
    <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b>Complete the activation with your transaction Pin</b></p>

    <hr>
    <form class="form-horizontal" method="post" enctype="multipart/form-data">

    <?php
    if(isset($_POST['activatePlan'])){

        include("../config/hmo_functions.php");
        
        $fund_source = mysqli_real_escape_string($link, $_POST['fund_source']);
        $otpCode = ($fund_source == "1") ? "" : mysqli_real_escape_string($link, $_POST['otpCode']);
        $customer = mysqli_real_escape_string($link, $_POST['customer']);
        $search_cust = mysqli_query($link, "SELECT * FROM borrowers WHERE virtual_acctno = '$customer'");
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
        $origin_plancode = mysqli_real_escape_string($link, $_POST['plancode']);
        $amountpaid = preg_replace("/[^0-9]/", "", mysqli_real_escape_string($link, $_POST['amountpaid']));
        $account_number =  ($fund_source == "0") ? ccMasking($fetch_cust['virtual_acctno']) : ccMasking($ivirtual_acctno);
        $b_name = $iname;
        $tpin = mysqli_real_escape_string($link, $_POST['tpin']);
        $new_reference = "EA".date("dy").time();
        $mybank_name = "Wallet Balance";

        $bank_details = "Bank Name: Wallet Balance";
        $bank_details .= ", Account Name: ".$b_name;
        $bank_details .= ", Account Number: ".$account_number;

        //new edition
        $plan_id = mt_rand(10000,99999);
	    $plancode = "rpp_".myreference(20); //NEW PLAN CODE
        $mys_interval = mysqli_real_escape_string($link, $_POST['savings_interval']);
        $duration = mysqli_real_escape_string($link, $_POST['duration']);

        $search_splan1 = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$origin_plancode'");
        $fetch_splan1 = mysqli_fetch_object($search_splan1);
        $original_planid = $fetch_splan1->plan_id;
        $plan_name = $fetch_splan1->plan_name;
        $apiPlanName = $fetch_splan1->api_planname;
        $companyid = $fetch_splan1->merchantid_others;
        $plan_type = $fetch_splan1->planType;
        $vendorid = $fetch_splan1->branchid;
        $categories = $fetch_splan1->categories;
        $divi_type = $fetch_splan1->dividend_type;
        $dividend = $fetch_splan1->dividend;
        $frequency = $fetch_splan1->frequency;
        $lock_withdrawal = $fetch_splan1->lock_withdrawal;
        $part_withdrawal = $fetch_splan1->part_withdrawal;
        $no_of_times = ($part_withdrawal == "No") ? 0 : $fetch_splan1->no_of_times;
        $todays_date = date('Y-m-d h:i:s');
        $converted_date = date('m/d/Y').' '.(date('h') + 1).':'.date('i a');
        $converted_date2 = date('Y-m-d').' '.(date('h') + 1).':'.date('i:s');
        $commtype = $fetch_splan1->commtype;
        $commvalue = $fetch_splan1->commvalue;
        $plancurrency = $fetch_splan1->currency;
        $min_planamount = $fetch_splan1->min_amount;
        $max_planamount = $fetch_splan1->max_amount;
        $agentcomm = $fetch_splan1->agentcomm;
        $calc_percent = $amountpaid - (($commvalue/100) * $amountpaid);

        //MERCHANT CUT
        $merchantRevenue = ($commtype == "percentage") ? $calc_percent : $commvalue;
        $agentRevenue = ($agentcomm/100) * $merchantRevenue;
        
        //Calculate Maturity Period
        $maturity_period = ($fetch_splan1->maturity_period == "weekly" ? 'week' : ($fetch_splan1->maturity_period == "monthly" ? 'month' : ($fetch_splan1->maturity_period == "daily" ? 'day' : 'year')));
        $mature_date = ($fetch_splan1->maturity_period == "") ? date('Y-m-d h:i:s') : date('Y-m-d h:i:s', strtotime('+'.$frequency.' '.$maturity_period, strtotime($todays_date)));

        //Calculate Next Payment Date
        $savings_interval = ($mys_interval == "daily" ? 'day' : ($mys_interval == "weekly" ? 'week' : ($mys_interval == "monthly" ? 'month' : ($mys_interval == "yearly" ? 'year' : ''))));
        $next_pmt_date = ($mys_interval == "ONE-OFF") ? "None" : date('Y-m-d h:i:s', strtotime('+1 '.$savings_interval, strtotime($todays_date)));
        
        $total_output = $amountpaid * $duration;
        $calc_divi_plusTOutput = ($divi_type == "Flat Rate" && $dividend != "0" ? ($dividend + $total_output) : ($divi_type == "Flat Rate" && $dividend == "0" ? "" : ($divi_type == "Ratio" ? "Undefine" : ($divi_type == "Percentage" && $dividend != '0' ? (($dividend / 100) * $total_output) + $total_output : $dividend + $total_output))));
        //$real_subscription_code = "uSub".date("dy").time();
        $real_invoice_code = date("yhi").time();
        //$recType = ($plan_type != "Investment") ? "Takaful" : $plan_type;

        $search_myinst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$companyid'");
        $fetch_myinst = mysqli_fetch_array($search_myinst);
        $iofficial_email = $fetch_myinst['official_email'];

        $search_myvendor = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
        $fetch_myvend_num = mysqli_num_rows($search_myvendor);
        $fetch_myvendor = mysqli_fetch_array($search_myvendor);
        $vendemail = $fetch_myvendor['cemail'];
        $withdrawal_status = $fetch_myvendor['api_notification'];

        $search_myuser = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$companyid' AND branchid = '$vendorid'");
        $fetch_myuser = mysqli_fetch_array($search_myuser);
        $creatorEmail = ($fetch_myvend_num == 1) ? $iofficial_email.','.$vendemail.','.$custemail : $iofficial_email.','.$fetch_myuser['email'].','.$custemail;

        $vc_name = ($fetch_myvend_num == 1) ? $fetch_myvendor['cname'] : $fetch_myinst['institution_name'];
        $vwalBalance = ($fetch_myvend_num == 1) ? $fetch_myvendor['wallet_balance'] : $fetch_myinst['wallet_balance'];
        $vendorBalance = ($vendorid == "") ? ($vwalBalance + $amountpaid) : (($vwalBalance + $amountpaid) - $merchantRevenue);

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
            echo "<div class='alert bg-orange'>Opps!...Invalid Transaction Pin!!</div>";

        }
        elseif($amountpaid > $source){

            //DELETE OTP RECORDS
            ($fund_source == "1") ? "" : mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$otpCode' AND status = 'Pending'");
            echo "<div class='alert bg-orange'>Oops!....Insufficient Balance!!</div>";

        }
        elseif($min_planamount > $amountpaid){

            //DELETE OTP RECORDS
            ($fund_source == "1") ? "" : mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$otpCode' AND status = 'Pending'");
            echo "<div class='alert bg-orange'>Sorry!....You can not subscribe below the amount set!!</div>";

        }
        elseif($amountpaid > $max_planamount){

            //DELETE OTP RECORDS
            ($fund_source == "1") ? "" : mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$otpCode' AND status = 'Pending'");
            echo "<div class='alert bg-orange'>Sorry!....You can not subscribe above the amount set!!</div>";

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
            $amountDebited = ($irole != "agent_manager" || $irole != "institution_super_admin" || $irole != "merchant_super_admin") ? ($amountpaid - $agentRevenue) : $amountpaid;
            $senderBalance = $source - $amountDebited;
            $senderBalBforCommission = $source - $amountpaid;
            $myinvestBal = $binvest_bal + $amountpaid;

            $Lga = "";
            $barea = "";
            ($withdrawal_status == "wellahealth_endpoint") ? $Notifier = wellaHealthSubNotifier($myfn,$myln,substr($phone2, -13),$custemail,$baddrs,$bstate,$Lga,$barea,$baddrs,$amountpaid,($mys_interval == "ONE-OFF") ? "Monthly" : ucfirst($mys_interval),$apiPlanName,$bgender,$dateofbirth) : "";
            ($withdrawal_status == "wellahealth_endpoint") ? $decodeNotifier = json_decode(json_encode($Notifier), true) : "";
            ($withdrawal_status == "wellahealth_endpoint") ? $mysub_code = $decodeNotifier['subscriptionCode'] : "";

            $real_subscription_code = ($withdrawal_status == "wellahealth_endpoint") ? $mysub_code : "uSub".date("dy").time();
            
            //Email Notification Service
            $sendSMS->productPaymentNotifier($creatorEmail, $converted_date, $new_reference, $customer, $myfullname, $real_subscription_code, $plancode, $categories, $plan_name, $mybank_name, $account_number, $b_name, $plancurrency, $amountpaid, $iemailConfigStatus, $ifetch_emailConfig);

            //UPDATE CUSTOMER BALANCE
            $update_records = mysqli_query($link, "UPDATE borrowers SET investment_bal = '$myinvestBal' WHERE (account = '$customer' OR virtual_acctno = '$customer')");
            
            //UPDATE FUND SOURCE BALANCE
            $totalAgentBalanceAfterCommission = $itransfer_balance + $agentRevenue;
            ($fund_source == "0") ? mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalBforCommission' WHERE (account = '$customer' OR virtual_acctno = '$customer')") : "";
            ($fund_source == "0") ? mysqli_query($link, "UPDATE user SET transfer_balance = '$totalAgentBalanceAfterCommission' WHERE id = '$iuid'") : "";
            ($fund_source == "1") ? mysqli_query($link, "UPDATE user SET transfer_balance = '$senderBalance' WHERE id = '$iuid'") : "";

            //UPDATE MERCHANT BALANCE
            $update_records = mysqli_query($link, "UPDATE user SET transfer_balance = '$merchantBal' WHERE id = '$merchantIuid'");

            //INSERT SUBSCRIPTION RECORD, TRANSACTION RECORD AND OTHERS
            $insert = mysqli_query($link, "INSERT INTO savings_subscription VALUES(null,'$companyid','$categories','$origin_plancode','$plancode','$plan_id','$original_planid','$amountpaid','$plancurrency','$mys_interval','$duration','$real_subscription_code','$customer','Approved','$todays_date','$vendorid','$mature_date','$calc_divi_plusTOutput','$new_reference','$next_pmt_date','Manual','$plan_type','$amountpaid','0','$no_of_times','$iuid','$lock_withdrawal','No','Investment')");
            $insert = mysqli_query($link, "INSERT INTO all_savingssub_transaction VALUES(null,'$companyid','$customer','$real_invoice_code','$real_subscription_code','$new_reference','$plancurrency','$amountpaid','successful','$todays_date','','$myfn','$myln','','','','','Wallet Transfer','','$origin_plancode','$vendorid','$iuid')");
            //$update_records = mysqli_query($link, "INSERT INTO manual_investsettlement VALUES(null,'$companyid','$vendorid','$vc_name','$customer','$myfullname','$refid','$origin_plancode','$real_subscription_code','$planname','$plancurrency','$amountpaid','$converted_date2','Pending')");
            $insert = mysqli_query($link, "INSERT INTO investment_notification VALUES(null,'$companyid','$vendorid','$customer','$myfullname','$new_reference','$origin_plancode','$real_subscription_code','$plan_name','$plancurrency','$amountpaid','$bank_details','$converted_date2','Approved')");

            //POST PAYMENT AND SETTLE COMMISSION FOR AGENT AND MERCHANT
            ($vendorid == "") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $iname($ivirtual_acctno)','$merchantRevenue','','Credit','$plancurrency','MERCHANT_COMMISSION','Merchant Commission for the subscription of Product Name: $plan_name','successful','$todays_date','','$merchantBal','')") or die (mysqli_error($link));
            ($vendorid == "") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $iname($ivirtual_acctno)','','$merchantRevenue','Debit','$plancurrency','MERCHANT_COMMISSION','Merchant Commission for the subscription of Product Name: $plan_name','successful','$todays_date','$vendorid','$vendorBalance','')") or die (mysqli_error($link));
            $update_records = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Subscription for: $customer','','$amountpaid','Debit','$plancurrency','INVESTMENT_SUB','Payment for the subscription of Product Name: $plan_name','successful','$todays_date','$iuid','$senderBalBforCommission','')") or die (mysqli_error($link));
            $update_records = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $iname($ivirtual_acctno)','','$amountpaid','Debit','$plancurrency','INVESTMENT_SUB','Payment for the subscription of Product Name: $plan_name','successful','$todays_date','$acctID','$senderBalBforCommission','')") or die (mysqli_error($link));
            $update_records = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $iname($ivirtual_acctno)','$amountDebited','','Credit','$plancurrency','INVESTMENT_SUB','Payment for the subscription of Product Name: $plan_name. Initiated By $iname($ivirtual_acctno)','successful','$todays_date','$vendorid','$vendorBalance','')") or die (mysqli_error($link));
            ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin" && $fund_source == "1") ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','self','$agentRevenue','','Credit','$plancurrency','COMMISSION','Commission for the subscription of Product Name: $plan_name','successful','$todays_date','$iuid','$senderBalance','')") or die (mysqli_error($link)) : "";
            ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin" && $fund_source == "0") ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','self','$agentRevenue','','Credit','$plancurrency','COMMISSION','Commission for the subscription of Product Name: $plan_name','successful','$todays_date','$iuid','$totalAgentBalanceAfterCommission','')") or die (mysqli_error($link)) : "";
            
            //UPDATE VENDOR OR PLAN CREATOR BALANCE
            ($vendorid == "") ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$vendorBalance' WHERE institution_id = '$companyid'") : $update_records = mysqli_query($link, "UPDATE vendor_reg SET wallet_balance = '$vendorBalance' WHERE companyid = '$vendorid'");

            //DELETE OTP RECORDS
            ($fund_source == "1") ? "" : mysqli_query($link, "DELETE FROM otp_confirmation WHERE userid = '$iuid' AND otp_code = '$otpCode' AND status = 'Pending'");

            echo "<script>alert('Congrat! Investment Product Activated Successfully!!'); </script>";
            echo "<script>window.location='allproduct_sub.php?id=".$_SESSION['tid']."&&mid=".base64_encode("1000")."'; </script>";

        }

    }
    ?>

    <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Customer</label>
        <div class="col-sm-6">
        <!--<select name="customer"  class="form-control select2" required>
            <option value="" selected>Select Customer</option>
            <?php
            //($individual_customer_records != "1" && $branch_customer_records != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' ORDER BY id") or die (mysqli_error($link)) : "";
    		//($individual_customer_records === "1" && $branch_customer_records != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' ORDER BY id") or die (mysqli_error($link)) : "";
            //($individual_customer_records != "1" && $branch_customer_records === "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' ORDER BY id") or die (mysqli_error($link)) : "";
            //while($fetch_get = mysqli_fetch_array($get))
            //{
            ?>
                <option value="<?php //echo $fetch_get['account']; ?>"><?php //echo $fetch_get['virtual_acctno'].' - '.$fetch_get['name'].' '.$fetch_get['lname'].' '.$fetch_get['mname']; ?></option>
            <?php
            //}
            ?>
        </select>-->

        <input name="customer" type="text" class="form-control" id="verify_virtualacct" onkeyup="verifyVA();" placeholder="Enter Recipient Wallet Account Number" required>
        <div id="myVA"></div>

        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

    <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Code</label>
        <div class="col-sm-6">
            <input name="plancode" type="text" class="form-control" value="<?php echo $_GET['pcode']; ?>" readonly/>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

    <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Name</label>
        <div class="col-sm-6">
            <input name="planname" type="text" class="form-control" value="<?php echo $planname; ?>" readonly/>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

    <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Description</label>
        <div class="col-sm-6">
            <textarea name="plandesc"  class="form-control" rows="4" cols="80" readonly><?php echo $plandesc; ?></textarea>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

    <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Amount</label>
        <div class="col-sm-6">
            <input name="amountpaid" type="text" class="form-control" placeholder="Enter your desire plan amount" required/>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

    <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Interval</label>
        <div class="col-sm-3">
            <select name="savings_interval" class="form-control select2" style="width: 100%;" id="savings_interval" /required>
                <option value="" selected="selected">---Choose Interval for the Plan---</option>
                <?php
                $explodePlan = explode(",",$savings_interval);
                $countPlan = (count($explodePlan) - 1);
                for($i = 0; $i <= $countPlan; $i++){
                    echo '<option value="'.$explodePlan[$i].'">'.$explodePlan[$i].'</option>';
                }
                ?>
            </select>
        </div>
        <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Duration</label>
        <div class="col-sm-1">
            <input name="duration" type="text" class="form-control" value="<?php echo $default_duration; ?>" placeholder="Enter Duration" required/>
        </div>
        <label class="control-label" id="mirror_interval" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">-frequency-<label>
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
            <button name="activatePlan" type="submit" onclick="var e=this;setTimeout(function(){e.disabled=true;},0);return true;" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-refresh"></i> Activate Plan!</button>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

    </form>

<?php
    }
    else{
        echo "<hr><b>NOTE:</b> <i>Card payment method is not available at the moment!!</i>";
    }
}
?>


</div>	
</div>	
</div>
</div>