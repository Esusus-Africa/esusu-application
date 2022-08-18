<?php

include("../config/connect.php");

/*function sendSms($sender, $ph, $msg, $debug=false)
{
  global $gateway_uname,$gateway_pass,$gateway_api;

  $url = 'username='.$gateway_uname;
  $url.= '&password='.$gateway_pass;
  $url.= '&sender='.urlencode($sender);
  $url.= '&recipient='.urlencode($ph);
  $url.= '&message='.urlencode($msg);

  $urltouse = $gateway_api.$url;
  //if ($debug) { echo "Request: <br>$urltouse<br><br>"; }

  //Open the URL to send the message
  $response3 = file_get_contents($urltouse);
  if ($debug) {
      //echo "Response: <br><pre>".
      //str_replace(array("<",">"),array("&lt;","&gt;"),$response3).
      //"</pre><br>"; 
  }
  return($response3);
}

function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);
}

$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
$fetch_gateway = mysqli_fetch_object($search_gateway);
$gateway_uname = $fetch_gateway->username;
$gateway_pass = $fetch_gateway->password;
$gateway_api = $fetch_gateway->api;
*/

$date_time = date("Y-m-d h:i:s");
$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
$acst_date->setTimeZone(new DateTimeZone('GMT+1'));
$correctdate = $acst_date->format('Y-m-d g:i A');

$searchQuery = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE status = 'batchSavings'");
while($fetchQuery = mysqli_fetch_array($searchQuery)){
    
    $id = $fetchQuery['id'];
    $concat = $fetchQuery['data'];
    $datetime = $fetchQuery['datetime'];
    $parameter = (explode('|',$concat));
    
    $txid = $parameter[0];
    $account = $parameter[1];
    $transactionType = $parameter[2];
    $amountWithoutCharges = $parameter[3];
    $remark = $parameter[4];
    $final_charges = $parameter[5];
    $amount = $parameter[6];
    $maintenance_row = $parameter[7];
    $t_perc = $parameter[8];
    $sysabb = $parameter[9];
    $institution_id = $parameter[10];
    $iuid = $fetchQuery['userid'];
    $ptype = $parameter[11];
    $icurrency = $parameter[12];
    $balanceToImpact = $parameter[13];
    
    $searchInst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$institution_id'");
    $fetchInst = mysqli_fetch_array($searchInst);
    $iassigned_walletbal = $fetchInst['wallet_balance'];
    $inst_name = $fetchInst['institution_name'];
    
    $searchUser = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$account'");
    $checkUser = mysqli_num_rows($searchUser);
    $fetchUser = mysqli_fetch_array($searchUser);
    $bbalance = ($balanceToImpact == "ledger" ? $fetchUser['balance'] : ($balanceToImpact == "target" ? $fetchUser['target_savings_bal'] : ($balanceToImpact == "investment" ? $fetchUser['investment_bal'] : ($balanceToImpact == "asset" ? $fetchUser['asset_acquisition_bal'] : 0))));
	$balLabel = ($balanceToImpact == "ledger" ? "Ledger Savings" : ($balanceToImpact == "target" ? "Target Savings" : ($balanceToImpact == "investment" ? "Investment" : ($balanceToImpact == "asset" ? "Asset Acquisition" : "Unknown"))));
    $fn = $fetchUser['fname'];
    $ln = $fetchUser['lname'];
    $em = $fetchUser['email'];
    $ph = $fetchUser['phone'];
    $uname = $fetchUser['username'];
    $tbbalance = $bbalance + $amountWithoutCharges;
    $smsNotification = $fetchUser['receive_sms']; //Yes or No
    $overdraft = $fetchUser['overdraft'];
    $smsChecker = ($smsNotification == "Yes") ? 1 : 0;
    $fullname = $ln.' '.$fn;

    //GLOBAL SETTINGS
    $mysystem_config = mysqli_query($link, "SELECT * FROM systemset");
    $fetchsys_config = mysqli_fetch_array($mysystem_config);
    $sms_rate = $fetchsys_config['fax'];
    
    $searchStaff = mysqli_query($link, "SELECT * FROM user WHERE id = '$iuid'");
    $fetchStaff = mysqli_fetch_array($searchStaff);
    $isbranchid = $fetchStaff['branchid'];
    $allow_auth = $fetchStaff['allow_auth'];
    $status = ($allow_auth == "Yes") ? "Approved" : "Pending";

    $verify_role = mysqli_query($link, "SELECT * FROM till_account WHERE cashier = '$iuid' AND status = 'Active'");
    $rowRole = mysqli_num_rows($verify_role);
    $fetch_role = mysqli_fetch_object($verify_role);
	$balance = $fetch_role->balance;
	$commtype = $fetch_role->commission_type;
	$commission = $fetch_role->commission;
    $commission_bal = $fetch_role->commission_balance;
    
    $refid = "EA-smsCharges-".rand(1000000,9999999);
    
    if($transactionType == "Deposit" && $maintenance_row == 1 && $checkUser == 1){
        
        //$myiwallet_balance = ($smsNotification == "No" || $iassigned_walletbal < $sms_rate) ? ($iassigned_walletbal - $t_perc) : ($iassigned_walletbal - $t_perc - $sms_rate);
        $myiwallet_balance = $iassigned_walletbal - $t_perc;
        //Get Remain Balance After Deposit
        $remain_balance = $balance - $amountWithoutCharges;
        //Calculate Commission Earn By the Staff
        $cal_commission = ($commtype == "Percentage") ? (($commission / 100) * $amountWithoutCharges) : $commission;
        //Update Default Commission Balance
        $total_commission_bal = ($commission == 0) ? $commission_bal : ($cal_commission + $commission_bal);

        $total = $bbalance + $amountWithoutCharges;

        /*$message = "$sysabb>>>CR";
        $message .= " Amt: ".$icurrency.$amountWithoutCharges."";
        $message .= " Acc: ".ccMasking($account)."";
        $message .= " Desc: Direct Deposit - | ".$txid."";
        $message .= " Time: ".$correctdate."";
        $message .= " Bal: ".$icurrency.number_format($tbbalance,2,'.',',')."";*/

        //($smsNotification == "No" || $iassigned_walletbal < $sms_rate) ? "" : $debug = true;
        //($smsNotification == "No" || $iassigned_walletbal < $sms_rate) ? "" : sendSms($sysabb,$ph,$message,$debug);
			
		($t_perc > 0 && $iassigned_walletbal >= $t_perc) ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','self','','$t_perc','Debit','$icurrency','Charges','Description: Service Charge for Deposit of $amountWithoutCharges','successful','$date_time','$iuid','$myiwallet_balance','')") : "";

		($t_perc > 0 && $iassigned_walletbal >= $t_perc) ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$institution_id'") : "";
				
        ($t_perc > 0 && $iassigned_walletbal >= $t_perc && $allow_auth == "Yes" && $balanceToImpact == "ledger") ? mysqli_query($link, "UPDATE borrowers SET balance = '$total' WHERE account = '$account'") or die (mysqli_error($link)) : "";
        
        ($t_perc > 0 && $iassigned_walletbal >= $t_perc && $allow_auth == "Yes" && $balanceToImpact == "target") ? mysqli_query($link, "UPDATE borrowers SET target_savings_bal = '$total' WHERE account = '$account'") or die (mysqli_error($link)) : "";

        ($t_perc > 0 && $iassigned_walletbal >= $t_perc && $allow_auth == "Yes" && $balanceToImpact == "investment") ? mysqli_query($link, "UPDATE borrowers SET investment_bal = '$total' WHERE account = '$account'") or die (mysqli_error($link)) : "";

        ($t_perc > 0 && $iassigned_walletbal >= $t_perc && $allow_auth == "Yes" && $balanceToImpact == "asset") ? mysqli_query($link, "UPDATE borrowers SET asset_acquisition_bal = '$total' WHERE account = '$account'") or die (mysqli_error($link)) : "";
		
		($t_perc > 0 && $iassigned_walletbal >= $t_perc) ? mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','$transactionType','$ptype','$account','----','$fn','$ln','$em','$ph','$amountWithoutCharges','$iuid','$remark','$correctdate','$institution_id','$isbranchid','$icurrency','','$total','$status','0','1','$smsChecker')") or die ("Error: " . mysqli_error($link)) : "";
				
	    ($rowRole == 1 && $t_perc > 0 && $iassigned_walletbal >= $t_perc) ? mysqli_query($link, "UPDATE till_account SET balance = '$remain_balance', commission_balance = '$total_commission_bal' WHERE cashier = '$iuid'") : "";

        ($rowRole == 1 && $t_perc > 0 && $iassigned_walletbal >= $t_perc) ? mysqli_query($link, "INSERT INTO fund_allocation_history VALUES(null,'$txid','$institution_id','$iuid','$isbranchid','$fullname','$iuid','$amountWithoutCharges','Debit','$transactionType','$icurrency','$remain_balance','$remark','successful','$date_time')") : "";
        
        ($t_perc > 0 && $iassigned_walletbal >= $t_perc && $allow_auth == "Yes") ? include("../cron/send_ledgerSavings_emailAlert.php") : "";

		($t_perc > 0 && $iassigned_walletbal >= $t_perc) ? mysqli_query($link, "DELETE FROM otp_confirmation WHERE id = '$id' AND status = 'batchSavings'") : "";
        
    }
    if($transactionType == "Deposit" && $maintenance_row == 0 && $checkUser == 1){

        //$myiwallet_balance = ($smsNotification == "No" || $iassigned_walletbal < $sms_rate) ? ($iassigned_walletbal - $t_perc) : ($iassigned_walletbal - $t_perc - $sms_rate);
        
        $remain_balance = $balance - $amountWithoutCharges;
        //Calculate Commission Earn By the Staff
        $cal_commission = ($commtype == "Percentage") ? (($commission / 100) * $amountWithoutCharges) : $commission;
        //Update Default Commission Balance
        $total_commission_bal = ($commission == 0) ? $commission_bal : ($cal_commission + $commission_bal);

        $total = $bbalance + $amountWithoutCharges;

        /*$message = "$sysabb>>>CR";
        $message .= " Amt: ".$icurrency.$amountWithoutCharges."";
        $message .= " Acc: ".ccMasking($account)."";
        $message .= " Desc: Direct Deposit - | ".$txid."";
        $message .= " Time: ".$correctdate."";
        $message .= " Bal: ".$icurrency.number_format($tbbalance,2,'.',',')."";

        ($smsNotification == "No" || $iassigned_walletbal < $sms_rate) ? "" : $debug = true;
        ($smsNotification == "No" || $iassigned_walletbal < $sms_rate) ? "" : sendSms($sysabb,$ph,$message,$debug);*/

        //($smsNotification == "No" || $iassigned_walletbal < $sms_rate) ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$ph','$sms_rate','NGN','Charges','SMS Content: $message','successful','$date_time','$iuid','$iassigned_walletbal')");
        //($smsNotification == "No" || $iassigned_walletbal < $sms_rate) ? "" : mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','institution','$sysabb','$ph','$message','Sent',NOW())");

        ($allow_auth == "Yes" && $balanceToImpact == "ledger") ? mysqli_query($link, "UPDATE borrowers SET balance = '$total' WHERE account = '$account'") or die (mysqli_error($link)) : "";
        
        ($allow_auth == "Yes" && $balanceToImpact == "target") ? mysqli_query($link, "UPDATE borrowers SET target_savings_bal = '$total' WHERE account = '$account'") or die (mysqli_error($link)) : "";

        ($allow_auth == "Yes" && $balanceToImpact == "investment") ? mysqli_query($link, "UPDATE borrowers SET investment_bal = '$total' WHERE account = '$account'") or die (mysqli_error($link)) : "";

        ($allow_auth == "Yes" && $balanceToImpact == "asset") ? mysqli_query($link, "UPDATE borrowers SET asset_acquisition_bal = '$total' WHERE account = '$account'") or die (mysqli_error($link)) : "";
				
		mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','$transactionType','$ptype','$account','----','$fn','$ln','$em','$ph','$amount','$iuid','$remark','$correctdate','$institution_id','$isbranchid','$icurrency','','$total','$status','0','1','$smsChecker')") or die (mysqli_error($link));
				
		($rowRole == 1) ? mysqli_query($link, "UPDATE till_account SET balance = '$remain_balance', commission_balance = '$total_commission_bal' WHERE cashier = '$iuid'") : "";

        ($rowRole == 1) ? mysqli_query($link, "INSERT INTO fund_allocation_history VALUES(null,'$txid','$institution_id','$iuid','$isbranchid','$fullname','$iuid','$amountWithoutCharges','Debit','$transactionType','$icurrency','$remain_balance','$remark','successful','$date_time')") : "";

        ($allow_auth == "Yes") ? include("../cron/send_ledgerSavings_emailAlert.php") : "";

		mysqli_query($link, "DELETE FROM otp_confirmation WHERE id = '$id' AND status = 'batchSavings'");

    }
    if($transactionType == "Withdraw" && $final_charges != "0" && $checkUser == 1){

        //$myiwallet_bal = ($smsNotification == "No" || $iassigned_walletbal < $sms_rate) ? $iassigned_walletbal : ($iassigned_walletbal - $sms_rate);
        $myiwallet_bal = $iassigned_walletbal;
        $myiwallet_balance = ($maintenance_row == 1 && $iassigned_walletbal >= $t_perc) ? ($myiwallet_bal - $t_perc) : $myiwallet_bal;
    
        //Get Remain Balance After Deposit
		$remain_balance = $bbalance - $amount;
		$tillremain_balance = $balance + $amount;
		//Total Charges Calculated
		$totalCharges = $bbalance - $amountWithoutCharges - $final_charges;
		//Semi Charges Calculated
        $semiCharges = $bbalance - $amountWithoutCharges;
        
        $total = number_format($totalCharges,2,'.','');
		$semi_total = number_format($semiCharges,2,'.','');
		$today = date("Y-m-d");
		$t_type = "Withdraw";
		$t_type1 = "Withdraw-Charges";
        $income_id = "ICM".rand(000001,99999);

        //DEBIT ALERT FOR THE AMOUNT WITHDRAWED
        /*$message = "$sysabb>>>DR";
        $message .= " Amt: ".$icurrency.number_format($amountWithoutCharges,2,'.',',')."";
        $message .= " Charges: ".$icurrency.number_format($final_charges,2,'.',',')."";
        $message .= " Acc: ".ccMasking($account)."";
        $message .= " Desc: ".$remark." - ".$txid."";
        $message .= " Time: ".$correctdate."";
        $message .= " Bal: ".$icurrency.number_format($remain_balance,2,'.',',')."";

        ($maintenance_row == 0 && $smsNotification == "Yes" && $iassigned_walletbal >= $sms_rate && ($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount))) ? $debug = true : "";
        ($maintenance_row == 0 && $smsNotification == "Yes" && $iassigned_walletbal >= $sms_rate && ($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount))) ? sendSms($sysabb,$ph,$message,$debug) : "";*/
        
        //($maintenance_row == 0 && $smsNotification == "Yes" && $iassigned_walletbal >= $sms_rate && ($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount))) ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$ph','$sms_rate','NGN','Charges','SMS Content: $message','successful','$date_time','$iuid','$iassigned_walletbal')") : "";
        //($maintenance_row == 0 && $smsNotification == "Yes" && $iassigned_walletbal >= $sms_rate && ($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount))) ? mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','institution','$sysabb','$ph','$message','Sent',NOW())") : "";

        ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc && ($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount))) ? $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','self','','$t_perc','Debit','$icurrency','Charges','Description: Service Charge for Withdrawal of $amountWithoutCharges','successful','$date_time','$iuid','$myiwallet_balance','')") : "";
			    
		($maintenance_row == "1" && $iassigned_walletbal >= $t_perc && ($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount))) ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$institution_id'") : "";
        

        (($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc)) && $allow_auth == "Yes" && $balanceToImpact == "ledger") ? $update = mysqli_query($link, "UPDATE borrowers SET balance = '$remain_balance', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link)) : "";

        (($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc)) && $allow_auth == "Yes" && $balanceToImpact == "target") ? $update = mysqli_query($link, "UPDATE borrowers SET target_savings_bal = '$remain_balance', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link)) : "";

        (($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc)) && $allow_auth == "Yes" && $balanceToImpact == "investment") ? $update = mysqli_query($link, "UPDATE borrowers SET investment_bal = '$remain_balance', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link)) : "";

        (($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc)) && $allow_auth == "Yes" && $balanceToImpact == "asset") ? $update = mysqli_query($link, "UPDATE borrowers SET asset_acquisition_bal = '$remain_balance', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link)) : "";


        (($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc))) ? $insert = mysqli_query($link, "INSERT INTO income VALUES(null,'$institution_id','$income_id','Charges','$final_charges','$today','$t_type1')") : "";
				
		(($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc))) ? $insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','$t_type1','$ptype','$account','----','$fn','$ln','$em','$ph','$final_charges','$iuid','Charges','$correctdate','$institution_id','$isbranchid','$icurrency','','$total','$status','0','1','$smsChecker')") or die (mysqli_error($link)) : "";
		
		(($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc))) ? $insert = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','$transactionType','$ptype','$account','----','$fn','$ln','$em','$ph','$amountWithoutCharges','$iuid','$remark','$correctdate','$institution_id','$isbranchid','$icurrency','','$total','$status','0','1','$smsChecker')") or die (mysqli_error($link)) : "";
				
        ($rowRole == 1 && (($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc)))) ? $update = mysqli_query($link, "UPDATE till_account SET balance = '$tillremain_balance' WHERE cashier = '$iuid'") : "";
        
        ($rowRole == 1 && (($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc)))) ? mysqli_query($link, "INSERT INTO fund_allocation_history VALUES(null,'$txid','$institution_id','$iuid','$isbranchid','$fullname','$iuid','$amountWithoutCharges','Debit','$transactionType','$icurrency','$tillremain_balance','$remark','successful','$date_time')") : "";

        (($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc)) && $allow_auth == "Yes") ? include("../cron/send_ledgerSavings_emailAlert.php") : "";

		(($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc))) ? mysqli_query($link, "DELETE FROM otp_confirmation WHERE id = '$id' AND status = 'batchSavings'") : "";

    }
    if($transactionType == "Withdraw" && $final_charges == "0" && $checkUser == 1){
        
        $remain_balance = $bbalance - $amount;
		$tillremain_balance = $balance + $amount;

        //$myiwallet_bal = ($smsNotification == "No" || $iassigned_walletbal < $sms_rate) ? $iassigned_walletbal : ($iassigned_walletbal - $sms_rate);
        $myiwallet_bal = $iassigned_walletbal;
        $myiwallet_balance = ($maintenance_row == 1 && $iassigned_walletbal >= $t_perc) ? ($myiwallet_bal - $t_perc) : $myiwallet_bal;
        
        //Semi Charges Calculated
        $semiCharges = $bbalance - $amountWithoutCharges;

        $total = number_format($semiCharges,2,'.','');
        $today = date("Y-m-d");

        //DEBIT ALERT FOR THE AMOUNT WITHDRAWED
        /*$message = "$sysabb>>>DR";
        $message .= " Amt: ".$icurrency.number_format($amountWithoutCharges,2,'.',',')."";
        $message .= " Acc: ".ccMasking($account)."";
        $message .= " Desc: ".$remark." - ".$txid."";
        $message .= " Time: ".$correctdate."";
        $message .= " Bal: ".$icurrency.number_format($remain_balance,2,'.',',')."";

        ($maintenance_row == 0 && $smsNotification == "Yes" && $iassigned_walletbal >= $sms_rate && ($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount))) ? $debug = true : "";
        ($maintenance_row == 0 && $smsNotification == "Yes" && $iassigned_walletbal >= $sms_rate && ($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount))) ? sendSms($sysabb,$ph,$message,$debug) : "";
        
        ($maintenance_row == 0 && $smsNotification == "Yes" && $iassigned_walletbal >= $sms_rate && ($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount))) ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$ph','$sms_rate','NGN','Charges','SMS Content: $message','successful','$date_time','$iuid','$iassigned_walletbal')") : "";
        ($maintenance_row == 0 && $smsNotification == "Yes" && $iassigned_walletbal >= $sms_rate && ($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount))) ? mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','institution','$sysabb','$ph','$message','Sent',NOW())") : "";*/
        
        ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc && ($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount))) ? $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','self','','$t_perc','Debit','$icurrency','Charges','Description: Service Charge for Withdrawal of $amountWithoutCharges','successful','$date_time','$iuid','$myiwallet_balance','')") : "";
		
		($maintenance_row == "1" && $iassigned_walletbal >= $t_perc && ($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount))) ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$institution_id'") : "";
		
		(($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc))) ? $insert1 = mysqli_query($link, "INSERT INTO transaction VALUES(null,'$txid','$transactionType','$ptype','$account','----','$fn','$ln','$em','$ph','$amountWithoutCharges','$iuid','$remark','$correctdate','$institution_id','$isbranchid','$icurrency','','$total','$status','0','1','$smsChecker')") or die (mysqli_error($link)) : "";
        
        
        (($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc)) && $allow_auth == "Yes" && $balanceToImpact == "ledger") ? $update = mysqli_query($link, "UPDATE borrowers SET balance = '$remain_balance', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link)) : "";

        (($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc)) && $allow_auth == "Yes" && $balanceToImpact == "target") ? $update = mysqli_query($link, "UPDATE borrowers SET target_savings_bal = '$remain_balance', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link)) : "";

        (($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc)) && $allow_auth == "Yes" && $balanceToImpact == "investment") ? $update = mysqli_query($link, "UPDATE borrowers SET investment_bal = '$remain_balance', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link)) : "";

        (($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc)) && $allow_auth == "Yes" && $balanceToImpact == "asset") ? $update = mysqli_query($link, "UPDATE borrowers SET asset_acquisition_bal = '$remain_balance', last_withdraw_date = '$today' WHERE account = '$account'") or die (mysqli_error($link)) : "";
        
        
        ($rowRole == 1 && (($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc)))) ? $update = mysqli_query($link, "UPDATE till_account SET balance = '$tillremain_balance' WHERE cashier = '$iuid'") : "";
        
        ($rowRole == 1 && (($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc)))) ? mysqli_query($link, "INSERT INTO fund_allocation_history VALUES(null,'$txid','$institution_id','$iuid','$isbranchid','$fullname','$iuid','$amountWithoutCharges','Debit','$transactionType','$icurrency','$tillremain_balance','$remark','successful','$date_time')") : "";

        (($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc)) && $allow_auth == "Yes") ? include("../cron/send_ledgerSavings_emailAlert.php") : "";

		(($overdraft == "Yes" || ($overdraft == "No" && $bbalance >= $amount)) && ($maintenance_row == "0" || ($maintenance_row == "1" && $iassigned_walletbal >= $t_perc))) ? mysqli_query($link, "DELETE FROM otp_confirmation WHERE id = '$id' AND status = 'batchSavings'") : "";

    }
    else{
        //Do nothing;
    }
    
}

?>