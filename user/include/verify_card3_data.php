<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><u><b>Wallet Balance:</b></u>: <i class='fa fa-hand-o-right'></i>&nbsp;&nbsp;
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
            <a href="verify_card3.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo (isset($_GET['Takaful']) ? 'NTA0' : (isset($_GET['Health']) ? 'MTAwMA==' : (isset($_GET['Savings']) ? 'NTAw' : (isset($_GET['Donation']) ? 'NjA0' : 'NDA3')))); ?>&&pcode=<?php echo $_GET['pcode']; ?><?php echo ((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : '')))); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-refresh"></i>&nbsp;Refresh</button></a>
            </h3>
            </div>
             <div class="box-body">
 <?php
    $customer = $acctno;
	$acn = $_GET['acn'];
	$plan_code = $_GET['pcode'];
	$search_splan = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$plan_code'");
	$fetch_splan = mysqli_fetch_object($search_splan);
    $default_sinterval = $fetch_splan->savings_interval;
    $mymin_amountpaid = $fetch_splan->min_amount;
    $mymax_amountpaid = $fetch_splan->max_amount;
    $default_duration = $fetch_splan->duration;
    $product_currency = $fetch_splan->currency;
    $planname = $fetch_splan->plan_name;
    $plandesc = $fetch_splan->plan_desc;
    $myPMethod = $fetch_splan->pmethod;

	$reference =  "EA-cardAuth-".random_strings(10);
	
	$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$row1 = mysqli_fetch_object($select1);
    $auth_charges = $row1->auth_charges;
    $wUsername = $row1->wellahealth_clientid;
    $wPassword = $row1->wellahealth_clientsecretkey;
    $AgentCode = $row1->wellahealth_agentcode;
    
?>

   	<p style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><?php echo ($mymin_amountpaid == $mymax_amountpaid) ? "PLAN AMOUNT: <b>".$product_currency.number_format($mymin_amountpaid,2,'.',',')."</b>" : "PLAN AMOUNT RANGES FROM: <b>".$product_currency.number_format($mymin_amountpaid,2,'.',',')." - ".$product_currency.number_format($mymax_amountpaid,2,'.',',')."</b>"; ?></p>
    <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b>Fill in the form below to activate the plan with your wallet balance</b></p>

    <hr>
    <form class="form-horizontal" method="post" enctype="multipart/form-data">

    <?php
    if(isset($_POST['activatePlan'])){
        
        include("../config/restful_apicalls.php");
        require_once("../config/hmo_functions.php");

        $response = array();
        $origin_plancode = mysqli_real_escape_string($link, $_POST['plancode']);
        $amountpaid = preg_replace("/[^0-9]/", "", mysqli_real_escape_string($link, $_POST['amountpaid']));
        $mys_interval = mysqli_real_escape_string($link, $_POST['savings_interval']);
        $duration = mysqli_real_escape_string($link, $_POST['duration']);
        $pmethod = mysqli_real_escape_string($link, $_POST['pmethod']);
        $tpin = mysqli_real_escape_string($link, $_POST['tpin']);
        
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
        $myfullname = $myfn.' '.$myln;
        $converted_date = date('m/d/Y').' '.(date('h') + 1).':'.date('i a');
        $converted_date2 = date('Y-m-d').' '.(date('h') + 1).':'.date('i:s');
        $commtype = $fetch_splan1->commtype;
        $commvalue = $fetch_splan1->commvalue;
        $plancurrency = $fetch_splan1->currency;
        $min_planamount = $fetch_splan1->min_amount;
        $max_planamount = $fetch_splan1->max_amount;
        $agentcomm = $fetch_splan1->agentcomm;
        $upfront_payment = $fetch_splan1->upfront_payment;

        //CALC PERC
        $calc_percent = $amountpaid - (($commvalue/100) * $amountpaid);

        //MERCHANT CUT
        $merchantRevenue = ($commtype == "percentage") ? $calc_percent : $commvalue;
        $agentRevenue = ($agentcomm/100) * $merchantRevenue;

        //Calculate Maturity Period
        $maturity_period = ($fetch_splan1->maturity_period == "weekly" ? 'week' : ($fetch_splan1->maturity_period == "monthly" ? 'month' : ($fetch_splan1->maturity_period == "daily" ? 'day' : 'year')));
        $mature_date = ($fetch_splan1->maturity_period == "") ? date("Y-m-d h:i:s") : date('Y-m-d h:i:s', strtotime('+'.$frequency.' '.$maturity_period, strtotime($todays_date)));
        
        //Calculate Next Payment Date
        $savings_interval = ($mys_interval == "daily" ? 'day' : ($mys_interval == "weekly" ? 'week' : ($mys_interval == "monthly" ? 'month' : ($mys_interval == "yearly" ? 'year' : ''))));
        $next_pmt_date = ($mys_interval == "ONE-OFF") ? "None" : date('Y-m-d h:i:s', strtotime('+1 '.$savings_interval, strtotime($todays_date)));
        
        $total_output = $amountpaid * $duration;
        $calc_divi_plusTOutput = ($divi_type == "Flat Rate" && $dividend != "0" ? ($dividend + $total_output) : ($divi_type == "Flat Rate" && $dividend == "0" ? "" : ($divi_type == "Ratio" ? "Undefine" : ($divi_type == "Percentage" && $dividend != '0' ? (($dividend / 100) * $total_output) + $total_output : $dividend + $total_output))));
        $totalInterest = ($upfront_payment == "No") ? $calc_divi_plusTOutput : $total_output;
        //$real_subscription_code = "uSub".date("dy").time();
        $real_invoice_code = date("yhi").time();
        $recType = (isset($_GET['Takaful']) ? 'Takaful' : (isset($_GET['Health']) ? 'Health' : (isset($_GET['Donation']) ? 'Donation' : (isset($_GET['Savings']) ? 'Savings' : 'Investment'))));
        $balanceToImpact = (isset($_GET['Takaful']) ? 'Investment' : (isset($_GET['Health']) ? 'Investment' : (isset($_GET['Donation']) ? 'Investment' : (isset($_GET['Savings']) ? 'Target' : 'Ledger'))));
 
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
        $withdrawal_status = $fetch_myvendor['api_notification'];

        $search_myuser = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$companyid' AND branchid = '$vendorid'");
        $fetch_myuser = mysqli_fetch_array($search_myuser);
        $creatorEmail = ($fetch_myvend_num == 1) ? $iofficial_email.','.$vendemail.','.$email2 : $iofficial_email.','.$fetch_myuser['email'].','.$email2;

        $vc_name = ($fetch_myvend_num == 1) ? $fetch_myvendor['cname'] : $fetch_myinst['institution_name'];
        $vwalBalance = ($fetch_myvend_num == 1) ? $fetch_myvendor['wallet_balance'] : $merchantBal;
        $vendorBalance = ($vendorid == "" || !($sendSMS->startsWith($vendorid, "VEND"))) ? ($vwalBalance + $amountpaid) : (($vwalBalance + $amountpaid) - $merchantRevenue);

        $search_agent= mysqli_query($link, "SELECT * FROM user WHERE id = '$bAcctOfficer'");
        $fetch_agent = mysqli_fetch_array($search_agent);
        $agentWalletBal = $fetch_agent['transfer_balance'];
        $extractInterest = $calc_divi_plusTOutput - $total_output;

        if($tpin != $myuepin){

            echo "<script>alert('Oops!....Invalid Transaction Pin!!'); </script>";

        }
        elseif($min_planamount > $amountpaid){

            echo "<script>alert('Sorry!....You can not subscribe below the amount set!!'); </script>";

        }
        elseif($amountpaid > $max_planamount){

            echo "<script>alert('Sorry!....You can not subscribe above the amount set!!'); </script>";

        }
        elseif($pmethod == "card"){

            $rave_secret_key = ($fetch_icurrency->rave_status == "Enabled") ? $fetch_icurrency->rave_secret_key : $row1->secret_key;
    
            $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'paymentplans'");
            $fetch_restapi = mysqli_fetch_object($search_restapi);
            $api_url = $fetch_restapi->api_url;

            // Pass the plan's name, interval and amount
            $postdata =  array(
                'amount'=> $amountpaid,
                'name'	=> $plan_name."_".date("yds").mt_rand(1000,9999),
                'interval'	=> $mys_interval,
                'duration'	=> $duration,
                'seckey'	=> $rave_secret_key
            );
            
            $make_call = callAPI('POST', $api_url, json_encode($postdata));
            $response = json_decode($make_call, true);

            if($response['status'] == "success"){
        
                //Get the Plan code from Rave
                $plan_id = $response['data']['id'];
                $new_plancode = $response['data']['plan_token'];
    
                mysqli_query($link, "INSERT INTO savings_subscription VALUES(null,'$companyid','$categories','$origin_plancode','$new_plancode','$plan_id','$original_planid','$amountpaid','$plancurrency','$mys_interval','$duration','','$acctno','Pending','$todays_date','$vendorid','','','','','Auto','$recType','$amountpaid','0','$no_of_times','$bAcctOfficer','$lock_withdrawal','No','$balanceToImpact')");
    
                echo "<script>alert('Great! One more step to complete the Plan Activation!!'); </script>";
                echo "<script>window.location='tokenize_card.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=".((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Health'])) ? 'MTAwMA==' : ((isset($_GET['Savings'])) ? 'NTAw' : (isset($_GET['Donation']) ? 'NjA0' : 'NDA3'))))."".((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : ''))))."&&pcode=".$new_plancode."'; </script>";
    
            }
            else{
            
                $message = $response['message'];
                echo "<script>alert('$message \\nPlease try another one'); </script>";
    
            }
            
        }
        elseif($extractInterest > $vendorBalance && ($sendSMS->startsWith($vendorid, "VEND"))){

            echo "<script>alert('Opps!...Transaction not successful. Contact your Institution for more details'); </script>";
      
        }
        elseif($pmethod == "wallet" && $bwallet_balance >= $amountpaid){

            $account_number = ccMasking($bvirtual_acctno);
            $b_name = $bname;
            $new_reference = "EA".date("dy").time();
            $custemail = $email2;
            $mybank_name = "Wallet Balance";
            $bank_details = "Bank Name: Wallet Balance";
            $bank_details .= ", Account Name: ".$b_name;
            $bank_details .= ", Account Number: ".$account_number;
            $plan_id = mt_rand(10000,99999);
            $new_plancode = "rpp_".random_strings(20);
            $plancode = $new_plancode;
            
            //Sender Parameters
            $realMerchantBalance = ($upfront_payment == "No") ? $vendorBalance : ($vendorBalance - $extractInterest);
            $amountDebited = $amountpaid;
            $senderBalance = $bwallet_balance - $amountpaid;
            $myinvestBal = ($plan_type == "Savings") ? ($btargetsavings_bal + $amountDebited) : ($binvest_bal + $amountDebited);
            $agentWalletBalAfterCommission = $agentWalletBal + $agentRevenue;

            $Lga = "";
            $barea = "";
            ($withdrawal_status == "wellahealth_endpoint") ? $Notifier = wellaHealthSubNotifier($myfn,$myln,substr($phone2, -13),$email2,$baddrs,$bstate,$Lga,$barea,$baddrs,$amountpaid,($mys_interval == "ONE-OFF") ? "Monthly" : ucfirst($mys_interval),$apiPlanName,$bgender,$dateofbirth) : "";
            ($withdrawal_status == "wellahealth_endpoint") ? $decodeNotifier = json_decode(json_encode($Notifier), true) : "";
            ($withdrawal_status == "wellahealth_endpoint") ? $mysub_code = $decodeNotifier['subscriptionCode'] : "";

            $real_subscription_code = ($withdrawal_status == "wellahealth_endpoint") ? $mysub_code : "uSub".date("dy").time();

            //Email Notification Service
            $sendSMS->productPaymentNotifier($creatorEmail, $converted_date, $new_reference, $customer, $myfullname, $real_subscription_code, $plancode, $categories, $plan_name, $mybank_name, $account_number, $b_name, $plancurrency, $amountpaid, $emailConfigStatus, $fetch_emailConfig);

            //UPDATE CUSTOMER BALANCE
            $totalSenderBalance = $senderBalance + (($calc_divi_plusTOutput == "Undefine") ? 0 : $extractInterest);
            ($plan_type == "Savings") ? mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance', target_savings_bal = '$myinvestBal' WHERE account = '$acctno'") : "";
            ($plan_type != "Savings") ? mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance', investment_bal = '$myinvestBal' WHERE account = '$acctno'") : "";
            ($upfront_payment == "No") ? "" : mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$totalSenderBalance' WHERE account = '$acctno'");
            mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Subscription Upfront Interest','','$extractInterest','Debit','$plancurrency','INVESTMENT_SUB','Payment for the subscription of Product Name: $plan_name','successful','$todays_date','$acctno','$senderBalance','')");
            ($upfront_payment == "Yes") ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Subscription Upfront Interest','$extractInterest','','Credit','$plancurrency','SUB_INTEREST','Interest for the subscription of Product Name: $plan_name','successful','$todays_date','$acctno','$totalSenderBalance','')") : "";
            ($upfront_payment == "No") ? "" : $sendSMS->walletCreditEmailNotifier($email2, $real_invoice_code, $todays_date, $binst_name, $bname, $bvirtual_acctno, $bbcurrency, $extractInterest, $totalSenderBalance, $emailConfigStatus, $fetch_emailConfig);

            //UPDATE FUND SOURCE BALANCE
            ($bAcctOfficer == "" || $agentRevenue <= 0) ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$agentWalletBalAfterCommission' WHERE id = '$bAcctOfficer'");

            //UPDATE MERCHANT BALANCE
            mysqli_query($link, "UPDATE user SET transfer_balance = '$realMerchantBalance' WHERE id = '$merchantIuid'");
            (!$sendSMS->startsWith($vendorid, "VEND")) ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $bname($bvirtual_acctno),'$amountpaid','','Credit','$plancurrency','INVESTMENT_SUB','Payment for the subscription of Product Name: $plan_name','successful','$todays_date','$merchantIuid','$vendorBalance','')") : "";
            ($upfront_payment == "Yes" && !($sendSMS->startsWith($vendorid, "VEND"))) ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Subscription Upfront Interest','','$extractInterest','Debit','$plancurrency','SUB_INTEREST','Interest for the subscription of Product Name: $plan_name','successful','$todays_date','$merchantIuid','$realMerchantBalance','')") : "";

            //INSERT SUBSCRIPTION RECORD, TRANSACTION RECORD AND OTHERS
            $insert = mysqli_query($link, "INSERT INTO savings_subscription VALUES(null,'$companyid','$categories','$origin_plancode','$new_plancode','$plan_id','$original_planid','$amountpaid','$plancurrency','$mys_interval','$duration','$real_subscription_code','$acctno','Approved','$todays_date','$vendorid','$mature_date','$totalInterest','$new_reference','$next_pmt_date','Manual','$recType','$amountpaid','0','$no_of_times','','$lock_withdrawal','No','$balanceToImpact')");
            $insert = mysqli_query($link, "INSERT INTO all_savingssub_transaction VALUES(null,'$companyid','$acctno','$real_invoice_code','$real_subscription_code','$new_reference','$plancurrency','$amountpaid','successful','$todays_date','','$myfn','$myln','NONE','NONE','NONE','NONE','Wallet Transfer','$bcountry','$origin_plancode','$vendorid','')");
            $insert = mysqli_query($link, "INSERT INTO investment_notification VALUES(null,'$companyid','$vendorid','$acctno','$myfullname','$new_reference','$origin_plancode','$real_subscription_code','$plan_name','$plancurrency','$amountpaid','$bank_details','$converted_date2','Approved')");

            //POST PAYMENT AND SETTLE COMMISSION FOR AGENT AND MERCHANT
            ($merchantRevenue > 0) ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $bname($bvirtual_acctno)','$merchantRevenue','','Credit','$plancurrency','MERCHANT_COMMISSION','Merchant Commission for the subscription of Product Name: $plan_name','successful','$todays_date','','$merchantBal','')") : "";
            
            //VENDOR LOG FOR INVESTMENT SUB AND MERCHANT REVENUE
            ($sendSMS->startsWith($vendorid, "VEND")) ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $bname($bvirtual_acctno)','$amountDebited','','Credit','$plancurrency','INVESTMENT_SUB','Payment for the subscription of Product Name: $plan_name. Initiated By $bname($bvirtual_acctno)','successful','$todays_date','$vendorid','$vendorBalance','')") or die ("Error6: " . mysqli_error($link)) : "";
            ($sendSMS->startsWith($vendorid, "VEND")) ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $bname($bvirtual_acctno)','','$merchantRevenue','Debit','$plancurrency','MERCHANT_COMMISSION','Merchant Commission for the subscription of Product Name: $plan_name','successful','$todays_date','$vendorid','$vendorBalance','')") or die ("Error7: " . mysqli_error($link)) : "";
            
            //AGENT COMMISSION HISTORY
            ($bAcctOfficer != "" && $agentRevenue >= 0) ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Initiated By $bname($bvirtual_acctno)','$agentRevenue','','Credit','$plancurrency','COMMISSION','Commission for the subscription of Product Name: $plan_name','successful','$todays_date','$bAcctOfficer','$agentWalletBalAfterCommission','')") or die ("Error8ii: " . mysqli_error($link)) : "";

            //UPDATE VENDOR
            ($sendSMS->startsWith($vendorid, "VEND")) ? mysqli_query($link, "UPDATE vendor_reg SET wallet_balance = '$realMerchantBalance' WHERE companyid = '$vendorid'") or die ("Error10: " . mysqli_error($link)) : "";
            ($upfront_payment == "Yes" && ($sendSMS->startsWith($vendorid, "VEND"))) ? mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$companyid','$new_reference','Subscription Upfront Interest','','$extractInterest','Debit','$plancurrency','SUB_INTEREST','Interest for the subscription of Product Name: $plan_name','successful','$todays_date','$vendorid','$realMerchantBalance','')") or die ("Error8iii: " . mysqli_error($link)) : "";

            echo "<script>alert('Product Activated Successfully!!'); </script>";
            echo "<script>window.location='my_savings_plan.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=".((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Health'])) ? 'MTAwMA==' : ((isset($_GET['Savings'])) ? 'NTAw' : (isset($_GET['Donation']) ? 'NjA0' : 'NDA3'))))."".((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : ''))))."'; </script>";

        }
        else{

		    echo "<script>alert('Sorry! You have insufficient fund in your wallet to proceed!!'); </script>";

	    }

    }
    ?>

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
                $explodePlan = explode(",",$default_sinterval);
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
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Payment Method</label>
        <div class="col-sm-6">
            <select name="pmethod" class="form-control select2" style="width: 100%;" /required>
                <option value="" selected="selected">---Select Payment Method---</option>
                <?php
                $parameterMethod = explode(",",$myPMethod);
                $countNum = (count($parameterMethod) - 1);
                for($i = 0; $i <= $countNum; $i++){
                    echo '<option value="'.$parameterMethod[$i].'">'.$parameterMethod[$i].'</option>';
                }
                ?>
            </select>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

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


</div>	
</div>	
</div>
</div>