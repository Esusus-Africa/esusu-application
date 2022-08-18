<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><u><b>Pay with Super Wallet</b></u>: <i class='fa fa-hand-o-right'></i>&nbsp;&nbsp;
            <button type="button" class="btn btn-flat bg-white" align="left">&nbsp;
                <strong class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
                    <?php 
                    $select = mysqli_query($link, "SELECT wallet_balance FROM borrowers WHERE account = '$acctno'") or die (mysqli_error($link));
                    while($row = mysqli_fetch_array($select))
                    {
                    echo "<span id='wallet_balance'>".$bbcurrency.number_format($row['wallet_balance'],2,".",",")."</span>";
                    }
                    ?>
                </strong>
            </button>
            <a href="make_payment.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo (isset($_GET['Takaful']) ? 'NTA0' : (isset($_GET['Health']) ? 'MTAwMA==' : (isset($_GET['Savings']) ? 'NTAw' : 'NDA3'))); ?>&&pcode=<?php echo $_GET['pcode']; ?><?php echo ((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ''))); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a></h3>
            </div>
             <div class="box-body">
<?php
$acn = $_GET['acn'];
$plan_code = $_GET['pcode'];
$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acctno'");
$get_customer = mysqli_fetch_object($search_customer);

    $search_splan = mysqli_query($link, "SELECT * FROM savings_subscription WHERE new_plancode = '$plan_code'");
	$fetch_splan = mysqli_fetch_object($search_splan);
    $myminamountpaid = $fetch_splan->amount;
	$product_currency = $fetch_splan->currency;
	$merchantid_others = $fetch_splan->companyid;
    $vendorid = $fetch_splan->vendorid;
    $oldpcode = $fetch_splan->plan_code;
    $mysInterval = $fetch_splan->savings_interval;

    $searchSplan = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$oldpcode'");
    $numSplan = mysqli_num_rows($searchSplan);
	$fetchSplan = mysqli_fetch_object($searchSplan);

    $searchTsplan1 = mysqli_query($link, "SELECT * FROM target_savings WHERE plan_code = '$plancode'");
    $numTsplan1 = mysqli_num_rows($searchTsplan1);
    $fetch_tsplan1 = mysqli_fetch_object($searchTplan1);

    $planType = ($numSplan == 1 && $numTsplan1 == 0) ? $fetchSplan->planType : "Savings";
?>

    <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b>Fill in the form below to activate the plan with your wallet balance</b></p>

    <hr>
    <form class="form-horizontal" method="post" enctype="multipart/form-data">

    <?php
    if(isset($_POST['activatePlan'])){
        
        $id = $_GET['id'];
        $plancode = mysqli_real_escape_string($link, $_POST['plancode']);
        $amountpaid = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amountpaid']));
        $account_number =  ccMasking($bvirtual_acctno);
        $myfullname = $bname;
        $b_name = $bname;
        $tpin = mysqli_real_escape_string($link, $_POST['tpin']);
        $new_reference = "EA".date("dy").time();
        $custemail = $email2;
        $mybank_name = "Wallet Balance";

        $bank_details = "Bank Name: Wallet Balance";
        $bank_details .= ", Account Name: ".$b_name;
        $bank_details .= ", Account Number: ".$account_number;

        $search_splan1 = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$plancode'");
        $num_splan1 = mysqli_num_rows($search_splan1);
        $fetch_splan1 = mysqli_fetch_object($search_splan1);

        $search_tsplan1 = mysqli_query($link, "SELECT * FROM target_savings WHERE plan_code = '$plancode'");
        $num_tsplan1 = mysqli_num_rows($search_tsplan1);
        $fetch_tsplan1 = mysqli_fetch_object($search_tsplan1);
        
        $plan_id = ($num_splan1 == 1 && $num_tsplan1 == 0) ? $fetch_splan1->plan_id : $fetch_tsplan1->plan_id;
        $plan_name = ($num_splan1 == 1 && $num_tsplan1 == 0) ? $fetch_splan1->plan_name : $fetch_tsplan1->plan_name;
        $companyid = ($num_splan1 == 1 && $num_tsplan1 == 0) ? $fetch_splan1->merchantid_others : $fetch_tsplan1->companyid;
        $plan_type = ($num_splan1 == 1 && $num_tsplan1 == 0) ? $fetch_splan1->planType : "Savings";
        $vendorid = ($num_splan1 == 1 && $num_tsplan1 == 0) ? $fetch_splan1->branchid : $fetch_tsplan1->branchid;
        $categories = ($num_splan1 == 1 && $num_tsplan1 == 0) ? $fetch_splan1->categories : $fetch_tsplan1->purpose;
        $duration = ($num_splan1 == 1 && $num_tsplan1 == 0) ? $fetch_splan->duration : $fetch_tsplan1->duration;
        $divi_type = ($num_splan1 == 1 && $num_tsplan1 == 0) ? $fetch_splan1->dividend_type : $fetch_tsplan1->interestType;
        $dividend = ($num_splan1 == 1 && $num_tsplan1 == 0) ? $fetch_splan1->dividend : $fetch_tsplan1->interestRate;
        $frequency = ($num_splan1 == 1 && $num_tsplan1 == 0) ? $fetch_splan1->frequency : $fetch_tsplan1->savings_interval;
        $commtype = ($num_splan1 == 1 && $num_tsplan1 == 0) ? $fetch_splan1->commtype : "percentage";
        $commvalue = ($num_splan1 == 1 && $num_tsplan1 == 0) ? $fetch_splan1->commvalue : 100;
        $agentcomm = ($num_splan1 == 1 && $num_tsplan1 == 0) ? $fetch_splan1->agentcomm : 0;
        $upfront_payment = ($num_splan1 == 1 && $num_tsplan1 == 0) ? $fetch_splan1->upfront_payment : $fetch_tsplan1->upfront_payment;
        $planamount = $myminamountpaid;
        $plancurrency = $product_currency;
        $todays_date = date('Y-m-d h:i:s');
        $myfullname = $myfn.' '.$myln;
        $converted_date = date('m/d/Y').' '.(date(h) + 1).':'.date('i a');
        $converted_date2 = date('Y-m-d').' '.(date(h) + 1).':'.date('i:s');
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
        $iofficial_email = $fetch_myinst['official_email'];

        $search_merchant = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$companyid' AND (role = 'agent_manager' OR role = 'institution_super_admin' OR role = 'merchant_super_admin')");
        $fetch_merchant = mysqli_fetch_array($search_merchant);
        $merchantIuid = $fetch_merchant['id'];
        $merchantBal = $fetch_merchant['transfer_balance'] + ($merchantRevenue - $agentRevenue);

        $search_myvendor = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
        $fetch_myvend_num = mysqli_num_rows($search_myvendor);
        $fetch_myvendor = mysqli_fetch_array($search_myvendor);
        $vendemail = $fetch_myvendor['cemail'];

        $search_myuser = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$companyid' AND branchid = '$vendorid'");
        $fetch_myuser = mysqli_fetch_array($search_myuser);
        $creatorEmail = ($fetch_myvend_num == 1) ? $iofficial_email.','.$vendemail.','.$custemail : $iofficial_email.','.$fetch_myuser['email'].','.$custemail;

        $vc_name = ($fetch_myvend_num == 1) ? $fetch_myvendor['cname'] : $fetch_myinst['institution_name'];
        $vwalBalance = ($fetch_myvend_num == 1) ? $fetch_myvendor['wallet_balance'] : $merchantBal;
        $vendorBalance = (!($sendSMS->startsWith($vendorid, "VEND"))) ? ($vwalBalance + $planamount) : (($vwalBalance + $planamount) - $merchantRevenue);

        $search_agent= mysqli_query($link, "SELECT * FROM user WHERE id = '$bAcctOffice'");
        $fetch_agent = mysqli_fetch_array($search_agent);
        $agentWalletBal = $fetch_agent['transfer_balance'];

        if($tpin != $myuepin){

            echo "<script>alert('Oops!....Invalid Transaction Pin!!'); </script>";

        }
        elseif($amountpaid > $bwallet_balance){

            echo "<script>alert('Oops!....Insufficient Wallet Balance!!'); </script>";

        }
        else{

            //Sender Parameters
            $amountDebited = $amountpaid;
            $senderBalance = $bwallet_balance - $amountpaid;
            $myinvestBal = ($plan_type == "Savings") ? ($btargetsavings_bal + $amountDebited) : ($binvest_bal + $amountDebited);
            $agentWalletBalAfterCommission = $agentWalletBal + $agentRevenue;
            
            //Email Notification Service
            $sendSMS->productPaymentNotifier($creatorEmail, $converted_date, $new_reference, $bvirtual_acctno, $myfullname, $real_subscription_code, $plancode, $categories, $plan_name, $mybank_name, $account_number, $b_name, $plancurrency, $amountpaid, $emailConfigStatus, $fetch_emailConfig);
            
            //UPDATE CUSTOMER BALANCE
            ($plan_type == "Savings") ? mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance', target_savings_bal = '$myinvestBal' WHERE account = '$acctno'") : "";
            ($plan_type != "Savings") ? mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance', investment_bal = '$myinvestBal' WHERE account = '$acctno'") : "";

            //UPDATE FUND SOURCE BALANCE
            ($bAcctOfficer == "" || $agentRevenue <= 0) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$agentWalletBalAfterCommission' WHERE id = '$bAcctOfficer'");

            //UPDATE MERCHANT BALANCE
            mysqli_query($link, "UPDATE user SET transfer_balance = '$vendorBalance' WHERE id = '$merchantIuid'");
            ($sendSMS->startsWith($vendorid, "VEND")) ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $bname($bvirtual_acctno),'$amountpaid','','Credit','$plancurrency','INVESTMENT_SUB','Payment for the subscription of Product Name: $plan_name','successful','$todays_date','$merchantIuid','$vendorBalance','')");

            //INSERT SUBSCRIPTION RECORD, TRANSACTION RECORD AND OTHERS
            $insert = mysqli_query($link, "UPDATE savings_subscription SET sub_balance = '$sub_bal', next_pmt_date = '$next_pmt_date' WHERE id = '$id'") or die ("Error1: " . mysqli_error($link));
            $insert = mysqli_query($link, "INSERT INTO all_savingssub_transaction VALUES(null,'$companyid','$acctno','$real_invoice_code','$real_subscription_code','$new_reference','$plancurrency','$planamount','successful','$todays_date','','$myfn','$myln','','','','','Wallet Transfer','','$plancode','$vendorid','')") or die ("Error2: " . mysqli_error($link));
            //$update_records = mysqli_query($link, "INSERT INTO manual_investsettlement VALUES(null,'$refid','$companyid','$vendorid','$vc_name','','$plancurrency','$planamount','$acctno','$myfullname','$plancode','$real_subscription_code','$planname','$converted_date2','Pending')") or die ("Error3: " . mysqli_error($link));
            $insert = mysqli_query($link, "INSERT INTO investment_notification VALUES(null,'$companyid','$vendorid','$acctno','$myfullname','$new_reference','$plancode','$real_subscription_code','$plan_name','$plancurrency','$planamount','$bank_details','$converted_date2','Approved')") or die ("Error4: " . mysqli_error($link));

            //POST PAYMENT AND SETTLE COMMISSION FOR AGENT AND MERCHANT
            $update_records = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Sub. Payment for: $bvirtual_acctno','','$amountpaid','Debit','$plancurrency','INVESTMENT_SUB','Payment for the subscription of Product Name: $plan_name','successful','$todays_date','$acctno','$senderBalance','')") or die ("Error5: " . mysqli_error($link));
            
            //MERCHANT COMMISSION LOG
            ($merchantRevenue <= 0) ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $bname($bvirtual_acctno)','$merchantRevenue','','Credit','$plancurrency','MERCHANT_COMMISSION','Merchant Commission for the subscription of Product Name: $plan_name','successful','$todays_date','','$merchantBal','')");
            
            //VENDOR LOG FOR INVESTMENT SUB AND MERCHANT REVENUE
            ($sendSMS->startsWith($vendorid, "VEND")) ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Sub. Payment for: $bname($bvirtual_acctno)','$amountDebited','','Credit','$plancurrency','INVESTMENT_SUB','Payment for the subscription of Product Name: $plan_name. Initiated By $bname($bvirtual_acctno)','successful','$todays_date','$vendorid','$vendorBalance','$senderBalance')") : "";
            ($sendSMS->startsWith($vendorid, "VEND")) ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $bname($bvirtual_acctno)','','$merchantRevenue','Debit','$plancurrency','MERCHANT_COMMISSION','Merchant Commission for the subscription of Product Name: $plan_name','successful','$todays_date','$vendorid','$vendorBalance','')") : "";
            
            //AGENT COMMISSION HISTORY
            ($bAcctOfficer != "" && $agentRevenue >= 0) ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $bname($bvirtual_acctno)','$agentRevenue','','Credit','$plancurrency','COMMISSION','Commission for the subscription of Product Name: $plan_name','successful','$todays_date','$bAcctOfficer','$agentWalletBalAfterCommission','')") : "";

            //UPDATE VENDOR OR PLAN CREATOR BALANCE
            ($sendSMS->startsWith($vendorid, "VEND")) ? mysqli_query($link, "UPDATE vendor_reg SET wallet_balance = '$vendorBalance' WHERE companyid = '$vendorid'") : "";

            echo "<script>alert('Payment made successfully!!'); </script>";
            echo "<script>window.location='my_savings_plan.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=".((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Health'])) ? 'MTAwMA==' : ((isset($_GET['Savings'])) ? 'NTAw' : 'NDA3')))."".((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : '')))."'; </script>";

        }

    }
    ?>

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
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin</label>
        <div class="col-sm-6">
            <input name="tpin" type="password" inputmode="numeric" pattern="[0-9]*" class="form-control" placeholder="Your Transaction Pin" required/>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>
        
    <div class="form-group" align="right">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
        <div class="col-sm-6">
            <button name="activatePlan" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-forward"></i> Pay Now!</button>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

    </form>

</div>	
</div>	
</div>
</div>