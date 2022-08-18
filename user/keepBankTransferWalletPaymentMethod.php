<?php
if($plan_type == "Savings" || $plan_type == "Investment"){

    echo "";

}else{
?>
<!------------------------------------------- WALLET BANK TRANSFER METHOD ----------------------------------------------->

<hr>
<div class="slideshow-container">
    <div class="alert bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" style="font-size:16px;" align="center"><?php echo "<u><b>Method 2 (TRANSFER TO VENDOR WALLET ACCOUNT)</b></u>"; ?>: <i class='fa fa-hand-o-down'></i><br>

<?php
    $pcode = $_GET['pcode'];
    $search_plan = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$pcode'") or die ("Error: " . mysqli_error($link));
    $fetch_plan = mysqli_fetch_array($search_plan);
    $merchantid_others = $fetch_plan['merchantid_others'];
    $vendorid = $fetch_plan['branchid'];
    $planOriginator = (($sendSMS->startsWith($vendorid,"VEND") && $merchantid_others != "" && $vendorid != "") ? $vendorid : ((!$sendSMS->startsWith($vendorid,"VEND")) && $merchantid_others != "" && $vendorid != "" ? $merchantid_others : $merchantid_others));
    
    $search_vaccount = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$planOriginator' AND status = 'ACTIVE'");
    while($fetch_vaccount = mysqli_fetch_array($search_vaccount)){
    $bank_name = $fetch_vaccount['bank_name'];
    $account_number = $fetch_vaccount['account_number'];
    $account_name = $fetch_vaccount['account_name'];

    echo '<div class="mySlides">';
      
    echo "<b>".strtoupper($bank_name)."</b>=> ACCOUNT NAME: <b>".strtoupper($account_name)."</b> | ACCOUNT NO: <b>".strtoupper($account_number)."</b>";
    
    echo '</div>';

    }
?>

    <!-- Dots/bullets/indicators -->
    <div class="dot-container">
    <?php
    $i = 1;
    $search_myva = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$planOriginator' AND status = 'ACTIVE'");
    while($getnumrow = mysqli_fetch_array($search_myva)){
    ?>
    <span class="dot" onclick="currentSlide(<?php echo $i; ?>)"></span>
    <?php
    $i++;
    }
    ?>
    </div>
    
</div>

<a class="myprev" onclick="plusSlides(-1)">&#10094;</a>
<a class="mynext" onclick="plusSlides(1)">&#10095;</a>
</div>

<p style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><?php echo ($mymin_amountpaid == $mymax_amountpaid) ? "PLAN AMOUNT: <b>".$product_currency.number_format($mymin_amountpaid,2,'.',',')."</b>" : "PLAN AMOUNT RANGES FROM: <b>".$product_currency.number_format($mymin_amountpaid,2,'.',',')." - ".$product_currency.number_format($mymax_amountpaid,2,'.',',')."</b>"; ?></p>
<p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b>Please fill in the form below to notify <i style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><?php echo $account_name; ?></i> about your payment</b></p>

<form class="form-horizontal" method="post" enctype="multipart/form-data">
    
<?php

if(isset($_POST['senddetails'])){
    
    include("../config/hmo_functions.php");

    $origin_plancode = mysqli_real_escape_string($link, $_POST['plancode']);
    $amountpaid = preg_replace("/[^0-9]/", "", mysqli_real_escape_string($link, $_POST['amountpaid']));
    $account_number =  ccMasking(mysqli_real_escape_string($link, $_POST['account_number']));
    $bank_code =  mysqli_real_escape_string($link, $_POST['bank_code']);
	$b_name = mysqli_real_escape_string($link, $_POST['b_name']);
    $new_reference = "EA".date("dy").time();
    $custemail = $email2;
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);
    
    $search_bankname = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$bank_code'");
	$fetch_bankname = mysqli_fetch_array($search_bankname);
	$mybank_name = $fetch_bankname['bankname'];
	
	$bank_details = "Bank Name: ".$mybank_name;
	$bank_details .= ", Account Name: ".$b_name;
	$bank_details .= ", Account Number: ".$account_number;

    //new edition
    $plan_id = mt_rand(10000,99999);
    $new_plancode = "rpp_".myref(20);
    $plancode = $new_plancode;
    $mys_interval = mysqli_real_escape_string($link, $_POST['savings_interval']);
    $duration = mysqli_real_escape_string($link, $_POST['duration']);
    
    $search_splan1 = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$origin_plancode'");
    $fetch_splan1 = mysqli_fetch_object($search_splan1);
    $original_planid = $fetch_splan1->plan_id;
    $plan_name = $fetch_splan1->plan_name;
    $apiPlanName = $fetch_splan1->api_planname;
    $companyid = $fetch_splan1->merchantid_others;
    $plan_type = $fetch_splan1->planType;
    $vendorid = ($plan_type == "Savings") ? "" : $fetch_splan1->branchid;
    $categories = $fetch_splan1->categories;
    $divi_type = $fetch_splan1->dividend_type;
    $dividend = $fetch_splan1->dividend;
    $frequency = $fetch_splan1->frequency;
    $lock_withdrawal = $fetch_splan1->lock_withdrawal;
    $part_withdrawal = $fetch_splan1->part_withdrawal;
    $no_of_times = ($part_withdrawal == "No") ? 0 : $fetch_splan1->no_of_times;
    $todays_date = date('Y-m-d h:i:s');
    $myfullname = $myfn.' '.$myln;
    $converted_date = date('m/d/Y').' '.(date(h) + 1).':'.date('i a');
    $converted_date2 = date('Y-m-d').' '.(date(h) + 1).':'.date('i:s');
    $commtype = $fetch_splan1->commtype;
    $commvalue = $fetch_splan1->commvalue;
    $plancurrency = $fetch_splan1->currency;
    $min_planamount = $fetch_splan1->min_amount;
    $max_planamount = $fetch_splan1->max_amount;
    $upfront_payment = $fetch_splan1->upfront_payment;
    
    //Calculate Maturity Period
    $maturity_period = ($fetch_splan1->maturity_period == "weekly" ? 'week' : ($fetch_splan1->maturity_period == "monthly" ? 'month' : ($fetch_splan1->maturity_period == "yearly" ? 'year' : '')));
    $mature_date = ($fetch_splan1->maturity_period == "") ? date("Y-m-d h:i:s") : date('Y-m-d h:i:s', strtotime('+'.$frequency.' '.$maturity_period, strtotime($todays_date)));
    
    //Calculate Next Payment Date
    $savings_interval = ($mys_interval == "daily" ? 'day' : ($mys_interval == "weekly" ? 'week' : ($mys_interval == "monthly" ? 'month' : ($mys_interval == "yearly" ? 'year' : ''))));
    $next_pmt_date = ($mys_interval == "ONE-OFF") ? "None" : date('Y-m-d h:i:s', strtotime('+1 '.$savings_interval, strtotime($todays_date)));
    
    $total_output = $amountpaid * $duration;
    $calc_divi_plusTOutput = ($divi_type == "Flat Rate" && $dividend != "0" ? ($dividend + $total_output) : ($divi_type == "Flat Rate" && $dividend == "0" ? "" : ($divi_type == "Ratio" ? "Undefine" : ($divi_type == "Percentage" && $dividend != '0' ? (($dividend / 100) * $total_output) + $total_output : $dividend + $total_output))));
    $totalInterest = ($upfront_payment == "No") ? $calc_divi_plusTOutput : $total_output;

    $real_subscription_code = "uSub".date("dy").time();
    $real_invoice_code = date("yhi").time();
    $recType = (isset($_GET['Takaful']) ? 'Takaful' : (isset($_GET['Health']) ? 'Health' : (isset($_GET['Savings']) ? 'Savings' : 'Investment')));

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
    else{

        $Lga = "";
        $barea = "";
        ($withdrawal_status == "wellahealth_endpoint") ? $Notifier = wellaHealthSubNotifier($myfn,$myln,$phone2,$email2,$baddrs,$bstate,$Lga,$barea,$baddrs,$amountpaid,($mys_interval == "ONE-OFF") ? "Monthly" : ucfirst($mys_interval),$apiPlanName,$bgender,$dateofbirth) : "";
        ($withdrawal_status == "wellahealth_endpoint") ? $decodeNotifier = json_decode(json_encode($Notifier), true) : "";
        ($withdrawal_status == "wellahealth_endpoint") ? $mysub_code = $decodeNotifier['subscriptionCode'] : "";

        $real_subscription_code = ($withdrawal_status == "wellahealth_endpoint") ? $mysub_code : "uSub".date("dy").time();

        //INSERT SUBSCRIPTION RECORD, TRANSACTION RECORD AND OTHERS
        mysqli_query($link, "INSERT INTO savings_subscription VALUES(null,'$companyid','$categories','$origin_plancode','$new_plancode','$plan_id','$original_planid','$amountpaid','$plancurrency','$mys_interval','$duration','$real_subscription_code','$acctno','Pending','$todays_date','$vendorid','$mature_date','$totalInterest','$new_reference','$next_pmt_date','Manual','$recType','$amountpaid','0','$no_of_times','','$lock_withdrawal','No')");
        mysqli_query($link, "INSERT INTO all_savingssub_transaction VALUES(null,'$companyid','$acctno','$real_invoice_code','$real_subscription_code','$new_reference','$plancurrency','$amountpaid','pending','$todays_date','','$myfn','$myln','','','','','Bank Transfer','','$origin_plancode','$vendorid','')");
        mysqli_query($link, "INSERT INTO investment_notification VALUES(null,'$companyid','$vendorid','$acctno','$myfullname','$new_reference','$origin_plancode','$real_subscription_code','$plan_name','$plancurrency','$amountpaid','$bank_details','$converted_date2','Pending')");
        
        $sendSMS->productPaymentViaBankTransferNotifier($creatorEmail, $converted_date, $new_reference, $acctno, $myfullname, $real_subscription_code, $plancode, $categories, $plan_name, $mybank_name, $account_number, $b_name, $plancurrency, $amountpaid, $emailConfigStatus, $fetch_emailConfig);

        echo "<script>alert('Great! Your request has been received and will be process shortly!!'); </script>";
        echo "<script>window.location='my_savings_plan.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=".((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Health'])) ? 'MTAwMA==' : ((isset($_GET['Savings'])) ? 'NTAw' : (isset($_GET['Donation']) ? 'NjA0' : 'NDA3'))))."".((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : ''))))."'; </script>";
    
    }
    
}

?>
    
    <div class="box-body">

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
            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
            <div class="col-sm-6">
                <select name="country"  class="form-control select2" id="country" onchange="loadbank();" required>
                  <option value="" selected>Please Select Country</option>
                  <option value="NG">Nigeria</option>
                </select>
            </div>
                <label for="" class="col-sm-3 control-label"></label>
        </div>
                  
        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
            <div class="col-sm-6">
                <input name="account_number" type="text" id="account_number" onkeydown="loadaccount();" class="form-control" placeholder="Bank Account Number" required>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
        </div>
                  
        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Bank Name</label>
            <div class="col-sm-6">
                <div id="bank_list">------------</div>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
        </div>
                  
        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Name</label>
            <div class="col-sm-6">
                <div id="act_numb">------------</div>
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
            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Amount</label>
            <div class="col-sm-6">
                <input name="amountpaid" type="text" class="form-control" placeholder="Enter your desire plan amount" required/>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Plan Interval</label>
            <div class="col-sm-3">
                <select name="savings_interval" class="form-control select2" style="width: 100%;" id="savings_interval1" /required>
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
            <label class="control-label" id="mirror_interval1" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">-frequency-<label>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin</label>
            <div class="col-sm-6">
                <input name="tpin" type="password" class="form-control" placeholder="Your Transaction Pin" required/>
            </div>
            <label for="" class="col-sm-3 control-label"></label>
        </div>
        
    </div> 
    
    <div class="form-group" align="right">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
        <div class="col-sm-6">
            <button name="senddetails" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-forward">&nbsp;Submit</i></button>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>
    
</form>

<?php } ?>