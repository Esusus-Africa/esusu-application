<?php

namespace Class\SavingsMgr;

require_once '../Interface/interface.Savings.php';
require_once 'class.Notification.php';

use Interface\MySavings\SavingsInterface as SavingsInterface;
use Class\Notification\Notifier as Notifier;

class SavingsManager extends App implements SavingsInterface {

    //function that process savings deposit
    public static function deposit($parameter, $ifetch_maintenance_model, $verify_till, $iwallet_balance, $iuid, $allow_auth, $getSMS_ProviderNum, $ismscharges, $isenderid, $icurrency, $tPin, $clientId, $isbranchid, $aggrId, $inst_name, $staffname){

        $account = $this->db->sanitizeInput($parameter['account']);
        $ptype = $this->db->sanitizeInput($parameter['ptype']);
        $amount = $this->db->sanitizeInput(preg_replace('/[^0-9.]/', '', $parameter['amount']));
        $remark = $this->db->sanitizeInput($parameter['remark']);
        $balanceToImpact = $this->db->sanitizeInput($parameter['balanceToImpact']);
        $confirmTPin = $this->db->sanitizeInput(preg_replace('/[^0-9.]/', '', $parameter['confirmTPin']));

        //Customer Data
        $get_details = $this->fetchWithOneParam('borrowers', 'account', $account);
        $fn = $get_details['fname'];
		$ln = $get_details['lname'];
		$em = $get_details['email'];
		$ph = $get_details['phone'];
		$bal = ($balanceToImpact == "ledger" ? $get_details['balance'] : ($balanceToImpact == "target" ? $get_details['target_savings_bal'] : ($balanceToImpact == "investment" ? $get_details['investment_bal'] : ($balanceToImpact == "asset" ? $get_details['asset_acquisition_bal'] : ($balanceToImpact == "loan" ? $get_details['loan_balance'] : $get_details['balance'])))));
		$balLabel = ($balanceToImpact == "ledger" ? "Ledger Savings" : ($balanceToImpact == "target" ? "Target Savings" : ($balanceToImpact == "investment" ? "Investment" : ($balanceToImpact == "asset" ? "Asset Acquisition" : "Loan"))));
		$uname = $get_details['username'];
		$total = ($balanceToImpact == "loan" || $balanceToImpact == "asset") ? ($bal - $amount) : ($bal + $amount);
		$sms_checker = $get_details['sms_checker'];
		$branch = $get_details['branchid'];
		$fullname = $ln.' '.$fn;
        $txid = uniqid($balanceToImpact).time();
        $status = ($allow_auth == "Yes") ? "Approved" : "Pending";
		$notification = ($allow_auth == "Yes") ? "1" : "0";
		$checksms = ($sms_checker == "No") ? "0" : "1";
		$transactionType = "Deposit";
        $final_date_time = date("Y-m-d h:i:s");

        //Till Configuration / Calculation
        $balance = ($verify_till === 0) ? 0 : $verify_till['balance'];
        $unsettledBal = ($verify_till === 0) ? 0 : $verify_till['unsettled_balance'];
		$commtype = ($verify_till === 0) ? "" : $verify_till['commission_type'];
		$commission = ($verify_till === 0) ? 0 : $verify_till['commission'];
		$commission_bal = ($verify_till === 0) ? 0 : $verify_till['commission_balance'];
        //Get Remain Balance After Deposit
		$remain_balance = ($verify_till === 0) ? 0 : ($balance - $amount);
		//Add to Unsettled Balance
		$totalUnsettledBal = ($verify_till === 0) ? 0 : ($unsettledBal + $amount);
        //Calculate Commission Earn By the Staff
		$cal_commission = ($commtype == "Percentage") ? (($commission / 100) * $amount) : $commission;
		//Update Default Commission Balance
		$total_commission_bal = ($commission == 0) ? $commission_bal : ($cal_commission + $commission_bal);

        //SMS charge settings
        $debitWAllet = ($getSMS_ProviderNum != 0 || ($ifetch_maintenance_model != 0 && $ismscharges == "0")) ? "No" : "Yes"; 
        $t_perc = $ifetch_maintenance_model['t_charges'];
        $mycharges = ($t_perc == "0" || $t_perc == "") ? $ismscharges : $ifetch_maintenance_model['t_charges'];
        $myiwallet_balance = $iwallet_balance - $mycharges;

        //Formatted Transaction SMS Message
        $message = Notifier::alertMsg($isenderid, 'CR', $icurrency, $amount, 0, $account, $remark, $txid, $final_date_time, $total);
		
        //Get Information from User IP Address
        $myip = $this->db->getUserIP();
        //Get Information from User Browser
        $ua = $this->db->getBrowser();
        $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
        $activities_tracked = $staffname . " make deposit to customer(" . $account . ") account with an existing balance of: " . $bal . " making: " . $total . "in total";

        if($amount <= 0){

            return -1; //Oops! Invalid Amount Entered!!</div>";
    
        }elseif($mycharges > $iwallet_balance){

            return -2; //Oh-Sorry, No sufficient fund in Wallet to complete this transaction!!

        }elseif($verify_till != 0 && $balance < $amount){

            return -3; //Oh-Sorry! No sufficient fund in your Till Account!!

        }elseif($tPin != $confirmTPin){

            return -4; //Oops!...Invalid Transaction Pin!!

        }else{

            //UPDATE INSTITUTION WALLET BALANCE
            ($ph == "" && ($t_perc == "0" || $t_perc == "")) ? "" : $this->updateWithOneParam('institution_data', 'wallet_balance', $myiwallet_balance, 'institution_id', $clientId);

            //INSERT TRANSACTION CHARGES IN WALLET HISTORY
            ($ph == "" && ($t_perc == "0" || $t_perc == "")) ? "" : $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            ($ph == "" && ($t_perc == "0" || $t_perc == "")) ? "" : $this->db->insertWalletHistory($query, $clientId, $txid, $ph, '', $mycharges, 'Debit', $icurrency, 'Charges', $message, 'successful', $final_date_time, $iuid, $myiwallet_balance);

            //UPDATE CUSTOMER BALANCE
            ($allow_auth == "Yes" && $balanceToImpact == "ledger") ? $this->updateWithOneParam('borrowers', 'balance', $total, 'account', $account) : "";
            ($allow_auth == "Yes" && $balanceToImpact == "target") ? $this->updateWithOneParam('borrowers', 'target_savings_bal', $total, 'account', $account) : "";
            ($allow_auth == "Yes" && $balanceToImpact == "investment") ? $this->updateWithOneParam('borrowers', 'investment_bal', $total, 'account', $account) : "";
            ($allow_auth == "Yes" && $balanceToImpact == "asset") ? $this->updateWithOneParam('borrowers', 'asset_acquisition_bal', $total, 'account', $account) : "";
            ($allow_auth == "Yes" && $balanceToImpact == "loan") ? $this->updateWithOneParam('borrowers', 'loan_balance', $total, 'account', $account) : "";

            //INSERT TRANSACTION RECORDS IN SAVINGS TRANSACTION HISTORY
            $queryDep = "INSERT INTO transaction (txid, t_type, p_type, acctno, transfer_to, fn, ln, email, phone, amount, posted_by, remark, date_time, branchid, sbranchid, currency, aggr_id, balance, status, sendSms, sendEmail, smsChecker, balanceToImpact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertSavings($queryDep, $txid, $transactionType, $ptype, $account, '---', $fn, $ln, $em, $ph, $amount, $iuid, $remark, $final_date_time, $clientId, $isbranchid, $icurrency, $aggrId, $total, $status, $notification, $notification, $checksms, $balanceToImpact);

            //UPDATE AND INSERT TILL RECORDS/HISTORY
            ($verify_till != 0) ? $this->updateWithThreeParam('till_account', 'balance', $remain_balance, 'unsettled_balance', $totalUnsettledBal, 'commission_balance', $total_commission_bal, 'cashier', $iuid) : "";
            ($verify_till != 0) ? $queryTillFund = "INSERT INTO fund_allocation_history (txid, companyid, manager_id, branch, teller, cashier, amount_fund, ttype, paymenttype, currency, balance, note_comment, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
            ($verify_till != 0) ? $this->db->insertTillFundingHistory($queryTillFund, $txid, $clientId, $iuid, $isbranchid, $staffname, $iuid, $amount, "Debit", $transactionType, $icurrency, $remain_balance, $remark, "successful", $final_date_time) : "";

            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $clientId, 'institution', $iuid, $myip, $yourbrowser, $activities_tracked, $isbranchid, "Success", $final_date_time);

            //SMS/Email Notification
			(($sms_checker == "No" || $mycharges == "0" || $ph == "") ? "" : ($allow_auth == "Yes" && $debitWAllet == "No" ? Notifier::sendSMS($isenderid, $ph, $message, $clientId, $txid, $iuid) : ($allow_auth == "Yes" && $debitWAllet == "Yes" && $mycharges <= $iwallet_balance ? Notifier::sendSMS($isenderid, $ph, $message, $clientId, $txid, $iuid) : "")));
            ($allow_auth == "Yes" && $em != "") ? Notifier::ledgerSavingsEmailNotifier($em, $transactionType, $txid, $fn, $ln, $ptype, $inst_name, $account, $icurrency, $amount, $total, $clientId) : "";

            return "Success";

        }

    }

    //function that process savings withdrawal
    public static function withdraw($parameter, $ifetch_maintenance_model, $verify_till, $iwallet_balance, $iuid, $allow_auth, $getSMS_ProviderNum, $ismscharges, $isenderid, $icurrency, $tPin, $clientId, $isbranchid, $aggrId, $inst_name, $staffname){

        $account = $this->db->sanitizeInput($parameter['account']);
        $ptype = $this->db->sanitizeInput($parameter['ptype']);
        $amount = $this->db->sanitizeInput(preg_replace('/[^0-9.]/', '', $parameter['amount']));
        $remark = $this->db->sanitizeInput($parameter['remark']);
        $balanceToImpact = $this->db->sanitizeInput($parameter['balanceToImpact']);
        $charges = $this->db->sanitizeInput($parameter['charges']);
        $confirmTPin = $this->db->sanitizeInput(preg_replace('/[^0-9.]/', '', $parameter['confirmTPin']));

        //Customer Data
        $get_details = $this->fetchWithOneParam('borrowers', 'account', $account);
        $fn = $get_details['fname'];
		$ln = $get_details['lname'];
		$em = $get_details['email'];
		$ph = $get_details['phone'];
		$bal = ($balanceToImpact == "ledger" ? $get_details['balance'] : ($balanceToImpact == "target" ? $get_details['target_savings_bal'] : ($balanceToImpact == "investment" ? $get_details['investment_bal'] : ($balanceToImpact == "asset" ? $get_details['asset_acquisition_bal'] : ($balanceToImpact == "loan" ? $get_details['loan_balance'] : $get_details['balance'])))));
		$balLabel = ($balanceToImpact == "ledger" ? "Ledger Savings" : ($balanceToImpact == "target" ? "Target Savings" : ($balanceToImpact == "investment" ? "Investment" : ($balanceToImpact == "asset" ? "Asset Acquisition" : "Loan"))));
		$uname = $get_details['username'];
		$sms_checker = $get_details['sms_checker'];
		$branch = $get_details['branchid'];
        $overdraft = $get_details['overdraft'];
        $acct_type = $get_details['acct_name'];
		$fullname = $ln.' '.$fn;
		$accno = $this->db->ccMasking($account);
        $txid = uniqid($balanceToImpact).time();
        $status = ($allow_auth == "Yes") ? "Approved" : "Pending";
		$notification = ($allow_auth == "Yes") ? "1" : "0";
		$checksms = ($sms_checker == "No") ? "0" : "1";
		$transactionType = "Withdraw";
		$transactionType1 = "Withdraw-Charges";

        $fetch_verification = $this->fetchWithOneParam('charge_management', 'id', $charges);
        $ctype = $fetch_verification['charges_type'];
        $cvalue = $fetch_verification['charges_value'];
        //Calculate Charges
        $percent = ($cvalue/100) * $amount;
        $final_charges = ($charges == "") ? 0 : ($ctype == "Flatrate" ? $cvalue : $percent);
        $totalamount = $amount + $final_charges;
        $final_date_time = date("Y-m-d h:i:s");
        $today = date("Y-m-d");

        $fetch_openingbal = $this->fetchWithTwoParam('account_type', 'merchant_id', $clientId, 'acct_name', $acct_type);
	    $opening_balance = ($fetch_openingbal['opening_balance'] == "") ? 0 : $fetch_openingbal['opening_balance'];

        //Till Configuration / Calculation
        $balance = ($verify_till === 0) ? 0 : $verify_till['balance'];
        $unsettledBal = ($verify_till === 0) ? 0 : $verify_till['unsettled_balance'];
		$commtype = ($verify_till === 0) ? "" : $verify_till['commission_type'];
		$commission = ($verify_till === 0) ? 0 : $verify_till['commission'];
		$commission_bal = ($verify_till === 0) ? 0 : $verify_till['commission_balance'];
        //Get Remain Balance After Withdrawal
		$remain_balance = ($verify_till === 0) ? 0 : ($balance + $totalamount);
		//Subtract from Unsettled Balance
		$totalUnsettledBal = ($verify_till === 0) ? 0 : ($unsettledBal - $amount);
        //Total Balance Left after charges
        $total = $bal - $totalamount;

        //SMS charge settings
        $debitWAllet = ($getSMS_ProviderNum != 0 || ($ifetch_maintenance_model != 0 && $ismscharges == "0")) ? "No" : "Yes"; 
        $t_perc = $ifetch_maintenance_model['t_charges'];
        $mycharges = ($t_perc == "0" || $t_perc == "") ? $ismscharges : $ifetch_maintenance_model['t_charges'];
        $myiwallet_balance = $iwallet_balance - $mycharges;

        //Formatted Transaction SMS Message
        $message = Notifier::alertMsg($isenderid, 'DR', $icurrency, $amount, $final_charges, $account, $remark, $txid, $final_date_time, $total);
		
        //Get Information from User IP Address
        $myip = $this->db->getUserIP();
        //Get Information from User Browser
        $ua = $this->db->getBrowser();
        $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
        $activities_tracked = $staffname . " make withdrawal from customer with account no: " . $account . " with an existing balance of: " . $bal . " making balance left to be: " . $total;

        if(($bal < $totalamount) && $overdraft == 'No'){

            return -1; //Insufficient Fund!;
    
        }elseif($amount <= 0){

            return -2; //Oops! Invalid Amount Entered!!</div>";
    
        }elseif($mycharges > $iwallet_balance){

            return -3; //Oh-Sorry, No sufficient fund in Wallet to complete this transaction!!

        }elseif(($opening_balance > $total) && $balanceToImpact == "ledger"){
    
            return -4; //Oh-Sorry, You cannot withdraw more than the Opening Balance!!
    
        }elseif($tPin != $confirmTPin){

            return -5; //Oops!...Invalid Transaction Pin!!

        }else{

            //UPDATE INSTITUTION WALLET BALANCE
            ($ph == "" && ($t_perc == "0" || $t_perc == "")) ? "" : $this->updateWithOneParam('institution_data', 'wallet_balance', $myiwallet_balance, 'institution_id', $clientId);

            //INSERT TRANSACTION CHARGES IN WALLET HISTORY
            ($ph == "" && ($t_perc == "0" || $t_perc == "")) ? "" : $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            ($ph == "" && ($t_perc == "0" || $t_perc == "")) ? "" : $this->db->insertWalletHistory($query, $clientId, $txid, $ph, '', $mycharges, 'Debit', $icurrency, 'Charges', $message, 'successful', $final_date_time, $iuid, $myiwallet_balance);

            //UPDATE CUSTOMER BALANCE
            ($allow_auth == "Yes" && $balanceToImpact == "ledger") ? $this->updateWithTwoParam('borrowers', 'balance', $total, 'last_withdraw_date', $today, 'account', $account) : "";
            ($allow_auth == "Yes" && $balanceToImpact == "target") ? $this->updateWithTwoParam('borrowers', 'target_savings_bal', $total, 'last_withdraw_date', $today, 'account', $account) : "";
            ($allow_auth == "Yes" && $balanceToImpact == "investment") ? $this->updateWithTwoParam('borrowers', 'investment_bal', $total, 'last_withdraw_date', $today, 'account', $account) : "";
            
            //INSERT TRANSACTION RECORDS IN SAVINGS TRANSACTION HISTORY
            $queryWith = "INSERT INTO transaction (txid, t_type, p_type, acctno, transfer_to, fn, ln, email, phone, amount, posted_by, remark, date_time, branchid, sbranchid, currency, aggr_id, balance, status, sendSms, sendEmail, smsChecker, balanceToImpact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertSavings($queryWith, $txid, $transactionType, $ptype, $account, '---', $fn, $ln, $em, $ph, $amount, $iuid, $remark, $final_date_time, $clientId, $isbranchid, $icurrency, $aggrId, $total, $status, $notification, $notification, $checksms, $balanceToImpact);
            ($final_charges == "0" || $final_charges == "") ? "" : $this->db->insertSavings($queryWith, $txid, $transactionType1, $ptype, $account, '---', $fn, $ln, $em, $ph, $final_charges, $iuid, $remark, $final_date_time, $clientId, $isbranchid, $icurrency, $aggrId, $total, $status, $notification, $notification, $checksms, $balanceToImpact);

            //LOG RECORDS TO INCOME ACCOUNT IF CHARGES IS APPLIED
            ($final_charges == "0" || $final_charges == "") ? "" : $queryInc = "INSERT INTO income (companyid, icm_id, icm_type, icm_amt, icm_date, icm_desc) VALUES(?, ?, ?, ?, ?, ?)";
            ($final_charges == "0" || $final_charges == "") ? "" : $this->db->insertIncome($queryInc, $clientId, $txid, 'Charges', $final_charges, $today, $transactionType1);

            //UPDATE AND INSERT TILL RECORDS/HISTORY
            ($verify_till != 0) ? $this->updateWithTwoParam('till_account', 'balance', $remain_balance, 'unsettled_balance', $totalUnsettledBal, 'cashier', $iuid) : "";
            ($verify_till != 0) ? $queryTillFund = "INSERT INTO fund_allocation_history (txid, companyid, manager_id, branch, teller, cashier, amount_fund, ttype, paymenttype, currency, balance, note_comment, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
            ($verify_till != 0) ? $this->db->insertTillFundingHistory($queryTillFund, $txid, $clientId, $iuid, $isbranchid, $staffname, $iuid, $totalamount, "Credit", $transactionType, $icurrency, $remain_balance, $remark, "successful", $final_date_time) : "";

            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $clientId, 'institution', $iuid, $myip, $yourbrowser, $activities_tracked, $isbranchid, "Success", $final_date_time);

            //SMS/Email Notification
			(($sms_checker == "No" || $mycharges == "0" || $ph == "") ? "" : ($allow_auth == "Yes" && $debitWAllet == "No" ? Notifier::sendSMS($isenderid, $ph, $message, $clientId, $txid, $iuid) : ($allow_auth == "Yes" && $debitWAllet == "Yes" && $mycharges <= $iwallet_balance ? Notifier::sendSMS($isenderid, $ph, $message, $clientId, $txid, $iuid) : "")));
            ($allow_auth == "Yes" && $em != "") ? Notifier::ledgerSavingsEmailNotifier($em, $transactionType, $txid, $fn, $ln, $ptype, $inst_name, $account, $icurrency, $amount, $total, $clientId) : "";

            return "Success";

        }

    }

    //function that process savings withdrawal request
    public static function withdrawalRequest($parameter, $ifetch_maintenance_model, $verify_till, $iwallet_balance, $iuid, $allow_auth, $getSMS_ProviderNum, $ismscharges, $isenderid, $icurrency, $tPin, $clientId, $isbranchid, $aggrId, $inst_name, $staffname, $inst_email){

        $account = $this->db->sanitizeInput($parameter['account']);
        $ptype = $this->db->sanitizeInput($parameter['ptype']);
        $amount = $this->db->sanitizeInput(preg_replace('/[^0-9.]/', '', $parameter['amount']));
        $remark = $this->db->sanitizeInput($parameter['remark']);
        $balanceToImpact = $this->db->sanitizeInput($parameter['balanceToImpact']);
        $charges = $this->db->sanitizeInput($parameter['charges']);
        $confirmTPin = $this->db->sanitizeInput(preg_replace('/[^0-9.]/', '', $parameter['confirmTPin']));

        //Customer Data
        $get_details = $this->fetchWithOneParam('borrowers', 'account', $account);
        $fn = $get_details['fname'];
		$ln = $get_details['lname'];
		$em = $get_details['email'];
		$ph = $get_details['phone'];
		$bal = ($balanceToImpact == "ledger" ? $get_details['balance'] : ($balanceToImpact == "target" ? $get_details['target_savings_bal'] : ($balanceToImpact == "investment" ? $get_details['investment_bal'] : ($balanceToImpact == "asset" ? $get_details['asset_acquisition_bal'] : ($balanceToImpact == "loan" ? $get_details['loan_balance'] : $get_details['balance'])))));
		$balLabel = ($balanceToImpact == "ledger" ? "Ledger Savings" : ($balanceToImpact == "target" ? "Target Savings" : ($balanceToImpact == "investment" ? "Investment" : ($balanceToImpact == "asset" ? "Asset Acquisition" : "Loan"))));
		$uname = $get_details['username'];
		$sms_checker = $get_details['sms_checker'];
		$branch = $get_details['branchid'];
        $overdraft = $get_details['overdraft'];
        $acct_type = $get_details['acct_name'];
		$fullname = $ln.' '.$fn;
		$accno = $this->db->ccMasking($account);
        $txid = uniqid($balanceToImpact).time();
        $status = "Pending";
		$notification = "0";
		$checksms = ($sms_checker == "No") ? "0" : "1";
		$transactionType = "Withdraw";
		$transactionType1 = "Withdraw-Charges";

        $fetch_verification = $this->fetchWithOneParam('charge_management', 'id', $charges);
        $ctype = $fetch_verification['charges_type'];
        $cvalue = $fetch_verification['charges_value'];
        //Calculate Charges
        $percent = ($cvalue/100) * $amount;
        $final_charges = ($charges == "") ? 0 : ($ctype == "Flatrate" ? $cvalue : $percent);
        $totalamount = $amount + $final_charges;
        $final_date_time = date("Y-m-d h:i:s");
        $today = date("Y-m-d");

        $fetch_openingbal = $this->fetchWithTwoParam('account_type', 'merchant_id', $clientId, 'acct_name', $acct_type);
	    $opening_balance = ($fetch_openingbal['opening_balance'] == "") ? 0 : $fetch_openingbal['opening_balance'];

        //Till Configuration / Calculation
        $balance = ($verify_till === 0) ? 0 : $verify_till['balance'];
        $unsettledBal = ($verify_till === 0) ? 0 : $verify_till['unsettled_balance'];
		$commtype = ($verify_till === 0) ? "" : $verify_till['commission_type'];
		$commission = ($verify_till === 0) ? 0 : $verify_till['commission'];
		$commission_bal = ($verify_till === 0) ? 0 : $verify_till['commission_balance'];
        //Get Remain Balance After Withdrawal
		$remain_balance = ($verify_till === 0) ? 0 : ($balance + $totalamount);
		//Subtract from Unsettled Balance
		$totalUnsettledBal = ($verify_till === 0) ? 0 : ($unsettledBal - $amount);
        //Total Balance Left after charges
        $total = $bal - $totalamount;

        //SMS charge settings
        $debitWAllet = ($getSMS_ProviderNum != 0 || ($ifetch_maintenance_model != 0 && $ismscharges == "0")) ? "No" : "Yes"; 
        $t_perc = $ifetch_maintenance_model['t_charges'];
        $mycharges = ($t_perc == "0" || $t_perc == "") ? $ismscharges : $ifetch_maintenance_model['t_charges'];
        $myiwallet_balance = $iwallet_balance - $mycharges;

        //Get Information from User IP Address
        $myip = $this->db->getUserIP();
        //Get Information from User Browser
        $ua = $this->db->getBrowser();
        $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
        $activities_tracked = $staffname . " make withdrawal request for customer with account no: " . $account . " with an existing balance of: " . $bal;
		
        if(($bal < $totalamount) && $overdraft == 'No'){

            return -1; //Insufficient Fund!;
    
        }elseif($amount <= 0){

            return -2; //Oops! Invalid Amount Entered!!</div>";
    
        }elseif($mycharges > $iwallet_balance){

            return -3; //Oh-Sorry, No sufficient fund in Wallet to complete this transaction!!

        }elseif(($opening_balance > $total) && $balanceToImpact == "ledger"){
    
            return -4; //Oh-Sorry, You cannot withdraw more than the Opening Balance!!
    
        }elseif($tPin != $confirmTPin){

            return -5; //Oops!...Invalid Transaction Pin!!

        }else{

            //Email Notification
            Notifier::withdrawalRequestNotifier($inst_email, $fullname, $staffname, $inst_name, $ptype, $account, $icurrency, $amount, $bal, $remark, $clientId);

            //UPDATE INSTITUTION WALLET BALANCE
            ($t_perc == "0" || $t_perc == "") ? "" : $this->updateWithOneParam('institution_data', 'wallet_balance', $myiwallet_balance, 'institution_id', $clientId);

            //INSERT TRANSACTION CHARGES IN WALLET HISTORY
            ($t_perc == "0" || $t_perc == "") ? "" : $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            ($t_perc == "0" || $t_perc == "") ? "" : $this->db->insertWalletHistory($query, $clientId, $txid, $ph, '', $mycharges, 'Debit', $icurrency, 'Charges', $message, 'successful', $final_date_time, $iuid, $myiwallet_balance);

            //INSERT TRANSACTION RECORDS IN SAVINGS TRANSACTION HISTORY
            $queryWith = "INSERT INTO transaction (txid, t_type, p_type, acctno, transfer_to, fn, ln, email, phone, amount, posted_by, remark, date_time, branchid, sbranchid, currency, aggr_id, balance, status, sendSms, sendEmail, smsChecker, balanceToImpact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertSavings($queryWith, $txid, $transactionType, $ptype, $account, '---', $fn, $ln, $em, $ph, $amount, $iuid, $remark, $final_date_time, $clientId, $isbranchid, $icurrency, $aggrId, $total, $status, $notification, $notification, $checksms, $balanceToImpact);
            ($final_charges == "0" || $final_charges == "") ? "" : $this->db->insertSavings($queryWith, $txid, $transactionType1, $ptype, $account, '---', $fn, $ln, $em, $ph, $final_charges, $iuid, $remark, $final_date_time, $clientId, $isbranchid, $icurrency, $aggrId, $total, $status, $notification, $notification, $checksms, $balanceToImpact);
            
            //INSERT INTO WITHDRAW REQUEST RECORDS/HISTORY
            $queryWRequest = "INSERT INTO ledger_withdrawal_request (txid, companyid, acct_officer, acn, ptype, amt_requested, remarks, status, balance_toimpact, currency, email, phone, sbranchid, sendsms, sendemail, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertWithdrawalRequest($queryWRequest, $txid, $clientId, $iuid, $account, $ptype, $amount, $remark, $status, $balanceToImpact, $icurrency, $em, $ph, $isbranchid, '0', '0', $final_date_time);

            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $clientId, 'institution', $iuid, $myip, $yourbrowser, $activities_tracked, $isbranchid, "Success", $final_date_time);

            return "Success";

        }

    }

    //function that process bulk deposit using CSV file
    public static function bulkDepositCSV($parameter, $ifetch_maintenance_model, $verify_till, $iwallet_balance, $iuid, $allow_auth, $getSMS_ProviderNum, $ismscharges, $isenderid, $icurrency, $clientId, $isbranchid, $aggrId, $inst_name, $staffname){
        
        $myCSVFile = $parameter->myCSVFile;
        $fileTmp = $parameter->fileTmp;

        if($myCSVFile[1] == 'csv'){
            
            $handle = fopen($fileTmp, "r");
            $fp = file($fileTmp, FILE_SKIP_EMPTY_LINES);
            $countFile = count($fp);
            $sum = 0;
            fgetcsv($handle,1000,","); // pop the headers
            while($data = fgetcsv($handle,1000,",")){

                $empty_filesop = array_filter(array_map('trim', $data));

                if(!empty($empty_filesop)){

                    $account = $this->db->sanitizeInput($data[0]);
                    $transactionType = ucwords($this->db->sanitizeInput($data[1]));
                    $amount = preg_replace('/[^0-9.]/', '', $this->db->sanitizeInput($data[2]));
                    $remark = $this->db->sanitizeInput($data[3]);
                    $final_charges =  $this->db->sanitizeInput($data[4]);
                    $ptype = $this->db->sanitizeInput($data[5]);
                    $balanceToImpact = strtolower($this->db->sanitizeInput($data[6]));

                    //Customer Data
                    $get_details = $this->fetchWithOneParam('borrowers', 'account', $account);
                    $fn = $get_details['fname'];
                    $ln = $get_details['lname'];
                    $em = $get_details['email'];
                    $ph = $get_details['phone'];
                    $bal = ($balanceToImpact == "ledger" ? $get_details['balance'] : ($balanceToImpact == "target" ? $get_details['target_savings_bal'] : ($balanceToImpact == "investment" ? $get_details['investment_bal'] : ($balanceToImpact == "asset" ? $get_details['asset_acquisition_bal'] : ($balanceToImpact == "loan" ? $get_details['loan_balance'] : $get_details['balance'])))));
                    $balLabel = ($balanceToImpact == "ledger" ? "Ledger Savings" : ($balanceToImpact == "target" ? "Target Savings" : ($balanceToImpact == "investment" ? "Investment" : ($balanceToImpact == "asset" ? "Asset Acquisition" : "Loan"))));
                    $uname = $get_details['username'];
                    $total = ($balanceToImpact == "loan" || $balanceToImpact == "asset") ? ($bal - $amount) : ($bal + $amount);
                    $sms_checker = $get_details['sms_checker'];
                    $branch = $get_details['branchid'];
                    $fullname = $ln.' '.$fn;
                    $txid = uniqid($balanceToImpact).time();
                    $status = ($allow_auth == "Yes") ? "Approved" : "Pending";
                    $notification = ($allow_auth == "Yes") ? "1" : "0";
                    $checksms = ($sms_checker == "No") ? "0" : "1";
                    $final_date_time = date("Y-m-d h:i:s");
                    $sum = $sum + $amount;

                    //Till Configuration / Calculation
                    $balance = ($verify_till === 0) ? 0 : $verify_till['balance'];
                    $unsettledBal = ($verify_till === 0) ? 0 : $verify_till['unsettled_balance'];
                    $commtype = ($verify_till === 0) ? "" : $verify_till['commission_type'];
                    $commission = ($verify_till === 0) ? 0 : $verify_till['commission'];
                    $commission_bal = ($verify_till === 0) ? 0 : $verify_till['commission_balance'];
                    //Get Remain Balance After Deposit
                    $remain_balance = ($verify_till === 0) ? 0 : ($balance - $sum);
                    //Add to Unsettled Balance
                    $totalUnsettledBal = ($verify_till === 0) ? 0 : ($unsettledBal + $sum);
                    //Calculate Commission Earn By the Staff
                    $cal_commission = ($commtype == "Percentage") ? (($commission / 100) * $sum) : $commission;
                    //Update Default Commission Balance
                    $total_commission_bal = ($commission == 0) ? $commission_bal : ($cal_commission + $commission_bal);

                    //SMS charge settings
                    $debitWAllet = ($getSMS_ProviderNum != 0 || ($ifetch_maintenance_model != 0 && $ismscharges == "0")) ? "No" : "Yes"; 
                    $t_perc = $ifetch_maintenance_model['t_charges'];
                    $mycharges = ($t_perc == "0" || $t_perc == "") ? ($ismscharges * $countFile) : ($ifetch_maintenance_model['t_charges'] * $countFile);
                    $myiwallet_balance = $iwallet_balance - $mycharges;

                    //Formatted Transaction SMS Message
                    $message = Notifier::alertMsg($isenderid, 'CR', $icurrency, $amount, 0, $account, $remark, $txid, $final_date_time, $total);

                    //Get Information from User IP Address
                    $myip = $this->db->getUserIP();
                    //Get Information from User Browser
                    $ua = $this->db->getBrowser();
                    $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
                    $activities_tracked = $staffname . " make deposit to customer(" . $account . ") account with an existing balance of: " . $bal . " making: " . $total . "in total";
                    
                    if($mycharges > $iwallet_balance){

                        return -1; //Oh-Sorry, No sufficient fund in Wallet to complete this transaction!!
            
                    }elseif($verify_till != 0 && $balance < $sum){
            
                        return -2; //Oh-Sorry! No sufficient fund in your Till Account!!
            
                    }else{

                        //UPDATE INSTITUTION WALLET BALANCE
                        ($ph == "" && ($t_perc == "0" || $t_perc == "")) ? "" : $this->updateWithOneParam('institution_data', 'wallet_balance', $myiwallet_balance, 'institution_id', $clientId);

                        //INSERT TRANSACTION CHARGES IN WALLET HISTORY
                        ($ph == "" && ($t_perc == "0" || $t_perc == "")) ? "" : $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        ($ph == "" && ($t_perc == "0" || $t_perc == "")) ? "" : $this->db->insertWalletHistory($query, $clientId, $txid, $ph, '', $mycharges, 'Debit', $icurrency, 'Charges', $message, 'successful', $final_date_time, $iuid, $myiwallet_balance);

                        //UPDATE CUSTOMER BALANCE
                        ($allow_auth == "Yes" && $balanceToImpact == "ledger") ? $this->updateWithOneParam('borrowers', 'balance', $total, 'account', $account) : "";
                        ($allow_auth == "Yes" && $balanceToImpact == "target") ? $this->updateWithOneParam('borrowers', 'target_savings_bal', $total, 'account', $account) : "";
                        ($allow_auth == "Yes" && $balanceToImpact == "investment") ? $this->updateWithOneParam('borrowers', 'investment_bal', $total, 'account', $account) : "";
                        ($allow_auth == "Yes" && $balanceToImpact == "asset") ? $this->updateWithOneParam('borrowers', 'asset_acquisition_bal', $total, 'account', $account) : "";
                        ($allow_auth == "Yes" && $balanceToImpact == "loan") ? $this->updateWithOneParam('borrowers', 'loan_balance', $total, 'account', $account) : "";

                        //INSERT TRANSACTION RECORDS IN SAVINGS TRANSACTION HISTORY
                        $queryDep = "INSERT INTO transaction (txid, t_type, p_type, acctno, transfer_to, fn, ln, email, phone, amount, posted_by, remark, date_time, branchid, sbranchid, currency, aggr_id, balance, status, sendSms, sendEmail, smsChecker, balanceToImpact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->db->insertSavings($queryDep, $txid, $transactionType, $ptype, $account, '---', $fn, $ln, $em, $ph, $amount, $iuid, $remark, $final_date_time, $clientId, $isbranchid, $icurrency, $aggrId, $total, $status, $notification, $notification, $checksms, $balanceToImpact);

                        //UPDATE AND INSERT TILL RECORDS/HISTORY
                        ($verify_till != 0) ? $this->updateWithThreeParam('till_account', 'balance', $remain_balance, 'unsettled_balance', $totalUnsettledBal, 'commission_balance', $total_commission_bal, 'cashier', $iuid) : "";
                        ($verify_till != 0) ? $queryTillFund = "INSERT INTO fund_allocation_history (txid, companyid, manager_id, branch, teller, cashier, amount_fund, ttype, paymenttype, currency, balance, note_comment, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
                        ($verify_till != 0) ? $this->db->insertTillFundingHistory($queryTillFund, $txid, $clientId, $iuid, $isbranchid, $staffname, $iuid, $amount, "Debit", $transactionType, $icurrency, $remain_balance, $remark, "successful", $final_date_time) : "";

                        //Audit Trail Log
                        $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->db->insertAuditTrail($auditTrailQuery, $clientId, 'institution', $iuid, $myip, $yourbrowser, $activities_tracked, $isbranchid, "Success", $final_date_time);
                        
                        //SMS/Email Notification
                        (($sms_checker == "No" || $mycharges == "0" || $ph == "") ? "" : ($allow_auth == "Yes" && $debitWAllet == "No" ? Notifier::sendSMS($isenderid, $ph, $message, $clientId, $txid, $iuid) : ($allow_auth == "Yes" && $debitWAllet == "Yes" && $mycharges <= $iwallet_balance ? Notifier::sendSMS($isenderid, $ph, $message, $clientId, $txid, $iuid) : "")));
                        ($allow_auth == "Yes" && $em != "") ? Notifier::ledgerSavingsEmailNotifier($em, $transactionType, $txid, $fn, $ln, $ptype, $inst_name, $account, $icurrency, $amount, $total, $clientId) : "";

                    }

                }
            
            }
            fclose($handle);
            return "Success"; //All Deposit Processed Successfully

        }else{

            return -3; //Invalid CSV File

        }

    }

    //function that process bulk withdrawal using CSV file
    public static function bulkWithdrawalCSV($parameter, $ifetch_maintenance_model, $verify_till, $iwallet_balance, $iuid, $charges, $allow_auth, $getSMS_ProviderNum, $ismscharges, $isenderid, $icurrency, $clientId, $isbranchid, $aggrId, $inst_name, $staffname){

        $myCSVFile = $parameter->myCSVFile;
        $fileTmp = $parameter->fileTmp;

        if($myCSVFile[1] == 'csv'){
            
            $handle = fopen($fileTmp, "r");
            $fp = file($fileTmp, FILE_SKIP_EMPTY_LINES);
            $countFile = count($fp);
            $sumAmt = 0;
            $sumChg = 0;
            fgetcsv($handle,1000,","); // pop the headers
            while($data = fgetcsv($handle,1000,",")){

                $empty_filesop = array_filter(array_map('trim', $data));

                if(!empty($empty_filesop)){

                    $account = $this->db->sanitizeInput($data[0]);
                    $transactionType = ucwords($this->db->sanitizeInput($data[1]));
                    $amount = preg_replace('/[^0-9.]/', '', $this->db->sanitizeInput($data[2]));
                    $remark = $this->db->sanitizeInput($data[3]);
                    $final_charges =  $this->db->sanitizeInput($data[4]);
                    $ptype = $this->db->sanitizeInput($data[5]);
                    $balanceToImpact = strtolower($this->db->sanitizeInput($data[6]));
                    $transactionType1 = "Withdraw-Charges";

                    //Customer Data
                    $get_details = $this->fetchWithOneParam('borrowers', 'account', $account);
                    $fn = $get_details['fname'];
                    $ln = $get_details['lname'];
                    $em = $get_details['email'];
                    $ph = $get_details['phone'];
                    $bal = ($balanceToImpact == "ledger" ? $get_details['balance'] : ($balanceToImpact == "target" ? $get_details['target_savings_bal'] : ($balanceToImpact == "investment" ? $get_details['investment_bal'] : ($balanceToImpact == "asset" ? $get_details['asset_acquisition_bal'] : ($balanceToImpact == "loan" ? $get_details['loan_balance'] : $get_details['balance'])))));
                    $balLabel = ($balanceToImpact == "ledger" ? "Ledger Savings" : ($balanceToImpact == "target" ? "Target Savings" : ($balanceToImpact == "investment" ? "Investment" : ($balanceToImpact == "asset" ? "Asset Acquisition" : "Loan"))));
                    $uname = $get_details['username'];
                    $sms_checker = $get_details['sms_checker'];
                    $branch = $get_details['branchid'];
                    $overdraft = $get_details['overdraft'];
                    $acct_type = $get_details['acct_name'];
                    $fullname = $ln.' '.$fn;
                    $txid = uniqid($balanceToImpact).time();
                    $status = ($allow_auth == "Yes") ? "Approved" : "Pending";
                    $notification = ($allow_auth == "Yes") ? "1" : "0";
                    $checksms = ($sms_checker == "No") ? "0" : "1";
                    $sumAmt = $sumAmt + $amount;
                    $sumChg = $sumChg + $final_charges;
                    $totalamount = $sumAmt + $sumChg;
                    $total = $amount + $final_charges;
                    $final_date_time = date("Y-m-d h:i:s");
                    $today = date("Y-m-d");
                    
                    $fetch_openingbal = $this->fetchWithTwoParam('account_type', 'merchant_id', $clientId, 'acct_name', $acct_type);
                    $opening_balance = ($fetch_openingbal['opening_balance'] == "") ? 0 : $fetch_openingbal['opening_balance'];

                    //Till Configuration / Calculation
                    $balance = ($verify_till === 0) ? 0 : $verify_till['balance'];
                    $unsettledBal = ($verify_till === 0) ? 0 : $verify_till['unsettled_balance'];
                    $commtype = ($verify_till === 0) ? "" : $verify_till['commission_type'];
                    $commission = ($verify_till === 0) ? 0 : $verify_till['commission'];
                    $commission_bal = ($verify_till === 0) ? 0 : $verify_till['commission_balance'];
                    //Get Remain Balance After Withdrawal
                    $remain_balance = ($verify_till === 0) ? 0 : ($balance + $totalamount);
                    //Subtract from Unsettled Balance
                    $totalUnsettledBal = ($verify_till === 0) ? 0 : ($unsettledBal - $sumAmt);
                    //Total Balance Left after charges
                    $mytotal = $bal - $total;

                    //SMS charge settings
                    $debitWAllet = ($getSMS_ProviderNum != 0 || ($ifetch_maintenance_model != 0 && $ismscharges == "0")) ? "No" : "Yes"; 
                    $t_perc = $ifetch_maintenance_model['t_charges'];
                    $mycharges = ($t_perc == "0" || $t_perc == "") ? ($ismscharges * $countFile) : ($ifetch_maintenance_model['t_charges'] * $countFile);
                    $myiwallet_balance = $iwallet_balance - $mycharges;

                    //Formatted Transaction SMS Message
                    $message = Notifier::alertMsg($isenderid, 'DR', $icurrency, $amount, $final_charges, $account, $remark, $txid, $final_date_time, $mytotal);

                    //Get Information from User IP Address
                    $myip = $this->db->getUserIP();
                    //Get Information from User Browser
                    $ua = $this->db->getBrowser();
                    $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
                    $activities_tracked = $staffname . " make withdrawal from customer with account no: " . $account . " with an existing balance of: " . $bal . " making balance left to be: " . $total;

                    if($mycharges > $iwallet_balance){
            
                        return -1; //Oh-Sorry, No sufficient fund in Wallet to complete this transaction!!
            
                    }else{

                        //UPDATE INSTITUTION WALLET BALANCE
                        ($ph == "" && ($t_perc == "0" || $t_perc == "" || (($bal < $total) && $overdraft == 'No') || (($opening_balance > $mytotal) && $balanceToImpact == "ledger"))) ? "" : $this->updateWithOneParam('institution_data', 'wallet_balance', $myiwallet_balance, 'institution_id', $clientId);

                        //INSERT TRANSACTION CHARGES IN WALLET HISTORY
                        ($ph == "" && ($t_perc == "0" || $t_perc == "" || (($bal < $total) && $overdraft == 'No') || (($opening_balance > $mytotal) && $balanceToImpact == "ledger"))) ? "" : $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        ($ph == "" && ($t_perc == "0" || $t_perc == "" || (($bal < $total) && $overdraft == 'No') || (($opening_balance > $mytotal) && $balanceToImpact == "ledger"))) ? "" : $this->db->insertWalletHistory($query, $clientId, $txid, $ph, '', $mycharges, 'Debit', $icurrency, 'Charges', $message, 'successful', $final_date_time, $iuid, $myiwallet_balance);

                        //UPDATE CUSTOMER BALANCE
                        ((($bal < $total) && $overdraft == 'No') || (($opening_balance > $mytotal) && $balanceToImpact == "ledger") ? "" : (($allow_auth == "Yes" && $balanceToImpact == "ledger") ? $this->updateWithTwoParam('borrowers', 'balance', $mytotal, 'last_withdraw_date', $today, 'account', $account) : ""));
                        ((($bal < $total) && $overdraft == 'No') || (($opening_balance > $mytotal) && $balanceToImpact == "ledger") ? "" : (($allow_auth == "Yes" && $balanceToImpact == "target") ? $this->updateWithTwoParam('borrowers', 'target_savings_bal', $mytotal, 'last_withdraw_date', $today, 'account', $account) : ""));
                        ((($bal < $total) && $overdraft == 'No') || (($opening_balance > $mytotal) && $balanceToImpact == "ledger") ? "" : (($allow_auth == "Yes" && $balanceToImpact == "investment") ? $this->updateWithTwoParam('borrowers', 'investment_bal', $mytotal, 'last_withdraw_date', $today, 'account', $account) : ""));
                        
                        //INSERT TRANSACTION RECORDS IN SAVINGS TRANSACTION HISTORY
                        $queryWith = "INSERT INTO transaction (txid, t_type, p_type, acctno, transfer_to, fn, ln, email, phone, amount, posted_by, remark, date_time, branchid, sbranchid, currency, aggr_id, balance, status, sendSms, sendEmail, smsChecker, balanceToImpact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        ((($bal < $total) && $overdraft == 'No') || (($opening_balance > $mytotal) && $balanceToImpact == "ledger")) ? "" : $this->db->insertSavings($queryWith, $txid, $transactionType, $ptype, $account, '---', $fn, $ln, $em, $ph, $amount, $iuid, $remark, $final_date_time, $clientId, $isbranchid, $icurrency, $aggrId, $mytotal, $status, $notification, $notification, $checksms, $balanceToImpact);
                        ($final_charges == "0" || $final_charges == "") ? "" : $this->db->insertSavings($queryWith, $txid, $transactionType1, $ptype, $account, '---', $fn, $ln, $em, $ph, $final_charges, $iuid, $remark, $final_date_time, $clientId, $isbranchid, $icurrency, $aggrId, $mytotal, $status, $notification, $notification, $checksms, $balanceToImpact);

                        //LOG RECORDS TO INCOME ACCOUNT IF CHARGES IS APPLIED
                        ($final_charges == "0" || $final_charges == "" || (($bal < $total) && $overdraft == 'No') || (($opening_balance > $mytotal) && $balanceToImpact == "ledger")) ? "" : $queryInc = "INSERT INTO income (companyid, icm_id, icm_type, icm_amt, icm_date, icm_desc) VALUES(?, ?, ?, ?, ?, ?)";
                        ($final_charges == "0" || $final_charges == "" || (($bal < $total) && $overdraft == 'No') || (($opening_balance > $mytotal) && $balanceToImpact == "ledger")) ? "" : $this->db->insertIncome($queryInc, $clientId, $txid, 'Charges', $final_charges, $today, $transactionType1);

                        //UPDATE AND INSERT TILL RECORDS/HISTORY
                        ((($bal < $total) && $overdraft == 'No') || (($opening_balance > $mytotal) && $balanceToImpact == "ledger") ? "" : (($verify_till != 0) ? $this->updateWithTwoParam('till_account', 'balance', $remain_balance, 'unsettled_balance', $totalUnsettledBal, 'cashier', $iuid) : ""));
                        ((($bal < $total) && $overdraft == 'No') || (($opening_balance > $mytotal) && $balanceToImpact == "ledger") ? "" : (($verify_till != 0) ? $queryTillFund = "INSERT INTO fund_allocation_history (txid, companyid, manager_id, branch, teller, cashier, amount_fund, ttype, paymenttype, currency, balance, note_comment, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : ""));
                        ((($bal < $total) && $overdraft == 'No') || (($opening_balance > $mytotal) && $balanceToImpact == "ledger") ? "" : (($verify_till != 0) ? $this->db->insertTillFundingHistory($queryTillFund, $txid, $clientId, $iuid, $isbranchid, $staffname, $iuid, $totalamount, "Credit", $transactionType, $icurrency, $remain_balance, $remark, "successful", $final_date_time) : ""));

                        //Audit Trail Log
                        $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->db->insertAuditTrail($auditTrailQuery, $clientId, 'institution', $iuid, $myip, $yourbrowser, $activities_tracked, $isbranchid, "Success", $final_date_time);

                        //SMS/Email Notification
                        (($sms_checker == "No" || $mycharges == "0" || $ph == "" || (($bal < $total) && $overdraft == 'No') || (($opening_balance > $mytotal) && $balanceToImpact == "ledger")) ? "" : ($allow_auth == "Yes" && $debitWAllet == "No" ? Notifier::sendSMS($isenderid, $ph, $message, $clientId, $txid, $iuid) : ($allow_auth == "Yes" && $debitWAllet == "Yes" && $mycharges <= $iwallet_balance ? Notifier::sendSMS($isenderid, $ph, $message, $clientId, $txid, $iuid) : "")));
                        ((($bal < $total) && $overdraft == 'No') || (($opening_balance > $mytotal) && $balanceToImpact == "ledger") ? "" : (($allow_auth == "Yes" && $em != "") ? Notifier::ledgerSavingsEmailNotifier($em, $transactionType, $txid, $fn, $ln, $ptype, $inst_name, $account, $icurrency, $amount, $total, $clientId) : ""));

                    }

                }

            }
            fclose($handle);
            return "Success"; //All Withdrawal Processed Successfully

        }else{

            return -2; //Invalid CSV File

        }

    }

    //Function for customer mini savings statement
    public static function miniSavingsStmt($parameter){

        $column = array('id', 'Date', 'TxID', 'Description', 'Debit', 'Credit','Balance');

        //Data table Parameter
        $searchValue = $parameter['searchValue']; //$_POST['search']['value']
        $draw = $parameter['draw']; //$_POST['draw']
        $order = $parameter['order']; //$_POST['order']
        $orderColumn = $parameter['orderColumn']; //$_POST['order']['0']['column']
        $start = $parameter['start']; //$_POST['start']
        $length = $parameter['length']; //$_POST['length']

        $startDate = ($parameter['startDate'] != "") ? $parameter['startDate'].' 00'.':00'.':00' : "2016-01-01 00:00:00";
        $endDate = ($parameter['endDate'] != "") ? $parameter['endDate'].' 24'.':00'.':00' : date("Y-m-d").' 24'.':00'.':00';
        $pmtType = $parameter['pmtType']; //All, Depsosit, Withdrawal, Withdrawal-Charges
        $filterBy = $parameter['filterBy']; //customer account number should be predetermined
        $clientId = $parameter['clientId'];

        $query = " ";

        if($startDate != "" && $endDate != "" && $pmtType != "" && $pmtType != "All"){

            $query .= "SELECT * FROM transaction 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND acctno = '$filterBy' AND branchid = '$clientId' AND (t_type = '$pmtType' OR p_type = '$pmtType')";

        }

        if($filterBy != "" && $pmtType != "" && $pmtType == "All"){

            $query .= "SELECT * FROM transaction 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$clientId' AND acctno = '$filterBy'";

        }
        

        if($searchValue != ''){

            $query .= "SELECT * FROM transaction
            WHERE txid LIKE '%'.$searchValue.'%' 
            ";

        }
        
        if(isset($order)){

            $query .= 'ORDER BY '.$column[$orderColumn].' DESC ';

        }else{

            $query .= 'ORDER BY id DESC ';

        }

        $query1 = '';

        if($length != -1){

            $query1 = 'LIMIT ' . $start . ', ' . $length;

        }

        //Query Builder for the filtering
        $statement = $this->db->pdo->prepare($query);
        $statement->execute();
        $number_filter_row = $statement->rowCount();

        $statement = $this->db->pdo->prepare($query . $query1);
        $statement->execute();
        $result = $statement->fetchAll();

        $data = array();

        foreach($result as $row){

            $sub_array = array();

            $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
            $sub_array[] = $row['date_time'];
            $sub_array[] = "<a href='#'>".$row['txid']."</a>";
            $sub_array[] = ($row['remark'] == "") ? "NILL" : $row['remark'];;
            $sub_array[] = ($row['t_type'] == "Withdraw" || $row['t_type'] == "Withdraw-Charges" || $row['t_type'] == "Transfer") ? $row['currency'].number_format($row['amount'],2,'.',',') : "---";
            $sub_array[] = ($row['t_type'] == "Deposit" || $row['t_type'] == "Transfer-Received") ? $row['currency'].number_format($row['amount'],2,'.',',') : "---";
            $sub_array[] = ($row['balance'] == "") ? "---" : $row['currency'].number_format($row['balance'],2,'.',',');
            $data[] = $sub_array;

        }

        $query2 = "SELECT * FROM transaction WHERE branchid = '$clientId' AND acctno = '$filterBy'";
        $statement2 = $this->db->pdo->prepare($query2);
        $statement2->execute();

        $output = array(
            'draw' => intval($draw),
            'recordsTotal' => $statement2->rowCount(),
            'recordsFiltered' => $number_filter_row,
            'data' => $data
        );

        return json_encode($output);

    }
    
    //Function to filter ledger transaction history
    public static function savingsHistory($parameter, $iTranRecords, $bTransRecords, $iuid, $isbranchid){

        $column = array('id', 'TxID', 'Savings Product', 'Branch', 'AcctNo', 'AcctName', 'Phone', 'Debit', 'Credit','Balance', 'Status', 'DateTime', 'PostedBy');

        //Data table Parameter
        $searchValue = $parameter['searchValue']; //$_POST['search']['value']
        $draw = $parameter['draw']; //$_POST['draw']
        $order = $parameter['order']; //$_POST['order']
        $orderColumn = $parameter['orderColumn']; //$_POST['order']['0']['column']
        $start = $parameter['start']; //$_POST['start']
        $length = $parameter['length']; //$_POST['length']

        $startDate = ($parameter['startDate'] != "") ? $parameter['startDate'].' 00'.':00'.':00' : "2016-01-01 00:00:00";
        $endDate = ($parameter['endDate'] != "") ? $parameter['endDate'].' 24'.':00'.':00' : date("Y-m-d").' 24'.':00'.':00';
        $pmtType = $parameter['pmtType'];
        $filterBy = $parameter['filterBy'];
        $clientId = $parameter['clientId'];
        $myStatus = $parameter['status'];

        $query = " ";

        if($startDate != "" && $endDate != "" && $pmtType != "" && $filterBy != "" && $pmtType != "All"){
    
            ($iTranRecords != "1" && $bTransRecords != "1") ? $query .= "SELECT * FROM transaction 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND status = '$myStatus' AND (sbranchid = '$filterBy' OR posted_by = '$filterBy' OR acctno = '$filterBy') AND branchid = '$clientId' AND (t_type = '$pmtType' OR p_type = '$pmtType')" : "";
             
            ($iTranRecords === "1" && $bTransRecords != "1") ? $query .= "SELECT * FROM transaction 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND status = '$myStatus' AND branchid = '$clientId' AND posted_by = '$iuid' AND acctno = '$filterBy' AND (t_type = '$pmtType' OR p_type = '$pmtType')" : "";
             
            ($iTranRecords != "1" && $bTransRecords === "1") ? $query .= "SELECT * FROM transaction 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND status = '$myStatus' AND branchid = '$clientId' AND sbranchid = '$isbranchid' AND acctno = '$filterBy' AND (t_type = '$pmtType' OR p_type = '$pmtType')" : "";
            
        }

        if($filterBy != "" && $pmtType != "" && $pmtType == "All"){
    
            ($iTranRecords != "1" && $bTransRecords != "1") ? $query .= "SELECT * FROM transaction 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND status = '$myStatus' AND branchid = '$clientId' AND (sbranchid = '$filterBy' OR posted_by = '$filterBy' OR acctno = '$filterBy')" : "";
             
            ($iTranRecords === "1" && $bTransRecords != "1") ? $query .= "SELECT * FROM transaction 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND status = '$myStatus' AND branchid = '$clientId' AND (posted_by = '$iuid' OR acctno = '$filterBy')" : "";
             
            ($iTranRecords != "1" && $bTransRecords === "1") ? $query .= "SELECT * FROM transaction 
             OR date_time BETWEEN '$startDate' AND '$endDate' AND status = '$myStatus' AND branchid = '$clientId' AND (sbranchid = '$isbranchid' OR acctno = '$filterBy')" : "";
            
        }
        
        if($filterBy == "" && $pmtType != "" && $pmtType != "All"){
            
            ($iTranRecords != "1" && $bTransRecords != "1") ? $query .= "SELECT * FROM transaction 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND status = '$myStatus' AND branchid = '$clientId' AND (t_type = '$pmtType' OR p_type = '$pmtType')" : "";
             
            ($iTranRecords === "1" && $bTransRecords != "1") ? $query .= "SELECT * FROM transaction 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND status = '$myStatus' AND branchid = '$clientId' AND posted_by = '$iuid' AND (t_type = '$pmtType' OR p_type = '$pmtType')" : "";
             
            ($iTranRecords != "1" && $bTransRecords === "1") ? $query .= "SELECT * FROM transaction 
             OR date_time BETWEEN '$startDate' AND '$endDate' AND status = '$myStatus' AND branchid = '$clientId' AND sbranchid = '$isbranchid' AND (t_type = '$pmtType' OR p_type = '$pmtType')" : "";
            
        }
        
        if($filterBy == "" && $pmtType != "" && $pmtType == "All"){
            
            ($iTranRecords != "1" && $bTransRecords != "1") ? $query .= "SELECT * FROM transaction 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND status = '$myStatus' AND branchid = '$clientId'" : "";
             
            ($iTranRecords === "1" && $bTransRecords != "1") ? $query .= "SELECT * FROM transaction 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND status = '$myStatus' AND branchid = '$clientId' AND posted_by = '$iuid'" : "";
             
            ($iTranRecords != "1" && $bTransRecords === "1") ? $query .= "SELECT * FROM transaction 
             OR date_time BETWEEN '$startDate' AND '$endDate' AND status = '$myStatus' AND branchid = '$clientId' AND sbranchid = '$isbranchid'" : "";
            
        }

        if($searchValue != ''){

            $query .= "SELECT * FROM transaction
            WHERE txid LIKE '%'.$searchValue.'%' 
            ";

        }
        
        if(isset($order)){

            $query .= 'ORDER BY '.$column[$orderColumn].' DESC ';

        }else{

            $query .= 'ORDER BY id DESC ';

        }

        $query1 = '';

        if($length != -1){

            $query1 = 'LIMIT ' . $start . ', ' . $length;

        }

        //Query Builder for the filtering
        $statement = $this->db->pdo->prepare($query);
        $statement->execute();
        $number_filter_row = $statement->rowCount();

        $statement = $this->db->pdo->prepare($query . $query1);
        $statement->execute();
        $result = $statement->fetchAll();

        $data = array();

        foreach($result as $row){

            $sub_array = array();
            $posted_by = $row['posted_by'];
            $acctno = $row['acctno'];
            $tbranch = $row['sbranchid'];
            $status = $row['status'];

            $selectUser = $this->fetchWithOneParam('user', 'id', $posted_by);
            $selectCustomer = $this->fetchWithOneParam('borrowers', 'account', $acctno);
            $selectBranch = $this->fetchWithOneParam('branches', 'branchid', $tbranch);

            $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
            $sub_array[] = "<a href='#'>".$row['txid']."</a>";
            $sub_array[] = ($selectCustomer['acct_type'] == "") ? "---" : $selectCustomer['acct_type'];
            $sub_array[] = "<b>".($tbranch == "") ? "Head Office" : $selectBranch['bname']."</b>";
            $sub_array[] = $row['acctno'];
            $sub_array[] = $row['ln'].' '.$row['fn'];
            $sub_array[] = $row['phone'];
            $sub_array[] = ($row['t_type'] == "Withdraw" || $row['t_type'] == "Withdraw-Charges" || $row['t_type'] == "Transfer") ? $row['currency'].number_format($row['amount'],2,'.',',') : "---";
            $sub_array[] = ($row['t_type'] == "Deposit" || $row['t_type'] == "Transfer-Received") ? $row['currency'].number_format($row['amount'],2,'.',',') : "---";
            $sub_array[] = ($row['balance'] == "") ? "---" : $row['currency'].number_format($row['balance'],2,'.',',');
            $sub_array[] = ($status == "Approved" ? "<span class='label bg-blue'>".$status."</span>" : ($status == "Pending" ? "<span class='label bg-orange'>".$status."</span>" : "<span class='label bg-red'>".$status."</span>"));
            $sub_array[] = $row['date_time'];
            $sub_array[] = $selectUser['name'].' '.$selectUser['lname'].' '.$selectUser['mname'];
            $data[] = $sub_array;

        }

        ($iTranRecords != "1" && $bTransRecords != "1") ? $query2 = "SELECT * FROM transaction WHERE branchid = '$clientId' AND status = '$myStatus'" : "";
        ($iTranRecords === "1" && $bTransRecords != "1") ? $query2 = "SELECT * FROM transaction WHERE branchid = '$clientId' AND status = '$myStatus' AND posted_by = '$iuid'" : "";
        ($iTranRecords != "1" && $bTransRecords === "1") ? $query2 = "SELECT * FROM transaction WHERE branchid = '$clientId' AND status = '$myStatus' AND sbranchid = '$isbranchid'" : "";
        $statement2 = $this->db->pdo->prepare($query2);
        $statement2->execute();

        $output = array(
            'draw' => intval($draw),
            'recordsTotal' => $statement2->rowCount(),
            'recordsFiltered' => $number_filter_row,
            'data' => $data
        );

        return json_encode($output);

    }

    //Function to filter ledger withdrawal request history
    public static function withdrawalRequestHistory($parameter, $iTranRecords, $bTransRecords, $iuid, $isbranchid){

        $column = array('id', 'TxID', 'Source', 'Branch', 'AcctNo', 'AcctName', 'Phone', 'Amount Requested', 'Current Balance', 'Status', 'PostedBy', 'DateTime');

        //Data table Parameter
        $searchValue = $parameter['searchValue']; //$_POST['search']['value']
        $draw = $parameter['draw']; //$_POST['draw']
        $order = $parameter['order']; //$_POST['order']
        $orderColumn = $parameter['orderColumn']; //$_POST['order']['0']['column']
        $start = $parameter['start']; //$_POST['start']
        $length = $parameter['length']; //$_POST['length']

        $startDate = ($parameter['startDate'] != "") ? $parameter['startDate'].' 00'.':00'.':00' : "2016-01-01 00:00:00";
        $endDate = ($parameter['endDate'] != "") ? $parameter['endDate'].' 24'.':00'.':00' : date("Y-m-d").' 24'.':00'.':00';
        $pmtType = $parameter['pmtType'];
        $filterBy = $parameter['filterBy'];
        $clientId = $parameter['clientId'];

        $query = " ";

        if($startDate != "" && $endDate != "" && $pmtType != "" && $filterBy != "" && $pmtType != "All"){
    
            ($iTranRecords != "1" && $bTransRecords != "1") ? $query .= "SELECT * FROM ledger_withdrawal_request 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (sbranchid = '$filterBy' OR acct_officer = '$filterBy' OR acn = '$filterBy') AND companyid = '$institution_id' AND ptype = '$pmtType'" : "";
             
            ($iTranRecords === "1" && $bTransRecords != "1") ? $query .= "SELECT * FROM ledger_withdrawal_request 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND acct_officer = '$iuid' AND acn = '$filterBy' AND ptype = '$pmtType'" : "";
             
            ($iTranRecords != "1" && $bTransRecords === "1") ? $query .= "SELECT * FROM ledger_withdrawal_request 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND sbranchid = '$isbranchid' AND acn = '$filterBy' AND ptype = '$pmtType'" : "";
            
        }
        
        if($filterBy != "" && $pmtType != "" && $pmtType == "All"){
            
            ($iTranRecords != "1" && $bTransRecords != "1") ? $query .= "SELECT * FROM ledger_withdrawal_request 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND (sbranchid = '$filterBy' OR acct_officer = '$filterBy' OR acn = '$filterBy')" : "";
             
            ($iTranRecords === "1" && $bTransRecords != "1") ? $query .= "SELECT * FROM ledger_withdrawal_request 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND (acct_officer = '$iuid' OR acn = '$filterBy')" : "";
             
            ($iTranRecords != "1" && $bTransRecords === "1") ? $query .= "SELECT * FROM ledger_withdrawal_request 
             OR date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND (sbranchid = '$isbranchid' OR acn = '$filterBy')" : "";
            
        }
        
        if($filterBy == "" && $pmtType != "" && $pmtType != "All"){
            
            ($iTranRecords != "1" && $bTransRecords != "1") ? $query .= "SELECT * FROM ledger_withdrawal_request 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND ptype = '$pmtType'" : "";
             
            ($iTranRecords === "1" && $bTransRecords != "1") ? $query .= "SELECT * FROM ledger_withdrawal_request 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND acct_officer = '$iuid' AND ptype = '$pmtType'" : "";
             
            ($iTranRecords != "1" && $bTransRecords === "1") ? $query .= "SELECT * FROM ledger_withdrawal_request 
             OR date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND sbranchid = '$isbranchid' AND ptype = '$pmtType'" : "";
            
        }
        
        if($filterBy == "" && $pmtType != "" && $pmtType == "All"){
            
            ($iTranRecords != "1" && $bTransRecords != "1") ? $query .= "SELECT * FROM ledger_withdrawal_request 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id'" : "";
             
            ($iTranRecords === "1" && $bTransRecords != "1") ? $query .= "SELECT * FROM ledger_withdrawal_request 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND acct_officer = '$iuid'" : "";
             
            ($iTranRecords != "1" && $bTransRecords === "1") ? $query .= "SELECT * FROM ledger_withdrawal_request 
             OR date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND sbranchid = '$isbranchid'" : "";
            
        }

        if($searchValue != ''){

            $query .= "SELECT * FROM ledger_withdrawal_request
            WHERE txid LIKE '%'.$searchValue.'%' 
            ";

        }
        
        if(isset($order)){

            $query .= 'ORDER BY '.$column[$orderColumn].' DESC ';

        }else{

            $query .= 'ORDER BY id DESC ';

        }

        $query1 = '';

        if($length != -1){

            $query1 = 'LIMIT ' . $start . ', ' . $length;

        }

        //Query Builder for the filtering
        $statement = $this->db->pdo->prepare($query);
        $statement->execute();
        $number_filter_row = $statement->rowCount();

        $statement = $this->db->pdo->prepare($query . $query1);
        $statement->execute();
        $result = $statement->fetchAll();

        $data = array();

        foreach($result as $row){

            $sub_array = array();
            $acct_officer = $row['acct_officer'];
            $acn = $row['acn'];
            $tbranch = $row['sbranchid'];
            $status = $row['status'];

            $selectUser = $this->fetchWithOneParam('user', 'id', $posted_by);
            $selectCustomer = $this->fetchWithOneParam('borrowers', 'account', $acctno);
            $selectBranch = $this->fetchWithOneParam('branches', 'branchid', $tbranch);

            $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
            $sub_array[] = $row['txid'];
            $sub_array[] = $row['balance_toimpact'].' account';
            $sub_array[] = "<b>".($tbranch == "") ? "Head Office" : $selectBranch['bname']."</b>";
            $sub_array[] = $row['acn'];
            $sub_array[] = ($selectCustomer['lname'] == "") ? "---" : $selectCustomer['lname'].' '.$selectCustomer['fname'];
            $sub_array[] = $row['phone'];
            $sub_array[] = $row['currency'].number_format($row['amt_requested'],2,'.',',');
            $sub_array[] = $row['currency'].(($row['balance_toimpact'] == "ledger") ? number_format($selectCustomer['balance'],2,'.',',') : (($row['balance_toimpact'] == "target") ? number_format($selectCustomer['target_savings_bal'],2,'.',',') : number_format($selectCustomer['investment_bal'],2,'.',',')));
            $sub_array[] = ($status == "Approved" ? "<span class='label bg-blue'>".$status."</span>" : ($status == "Pending" ? "<span class='label bg-orange'>".$status."</span>" : "<span class='label bg-red'>".$status."</span>"));
            $sub_array[] = ($acct_officer == "") ? "---" : $selectUser['name'].' '.$selectUser['lname'].' '.$selectUser['mname'];
            $sub_array[] = $row['date_time'];
            $data[] = $sub_array;

        }

        ($iTranRecords != "1" && $bTransRecords != "1") ? $query2 = "SELECT * FROM ledger_withdrawal_request WHERE companyid = '$institution_id'" : "";
        ($iTranRecords === "1" && $bTransRecords != "1") ? $query2 = "SELECT * FROM ledger_withdrawal_request WHERE companyid = '$institution_id' AND acct_officer = '$iuid'" : "";
        ($iTranRecords != "1" && $bTransRecords === "1") ? $query2 = "SELECT * FROM ledger_withdrawal_request WHERE companyid = '$institution_id' AND sbranchid = '$isbranchid'" : "";
        $statement2 = $this->db->pdo->prepare($query2);
        $statement2->execute();

        $output = array(
            'draw' => intval($draw),
            'recordsTotal' => $statement2->rowCount(),
            'recordsFiltered' => $number_filter_row,
            'data' => $data
        );

        return json_encode($output);

    }


    //Function to approved pending customer
    public static function approveSavings($parameter){

        $clientId = $parameter['clientId'];
        $selector = $parameter['selector'];
        $number = count($selector);

        if($selector == ''){

            return -1; //No record was selected;

        }elseif($number >= 1){

            for($i=0; $i<$number; $i++){

                //Get Savings Transaction details
                $get_trans = $this->fetchWithOneParam('transaction', 'id', $selector[$i]);
                $txid = $get_trans['txid'];
				$account = $get_trans['acctno'];
				$fn = $get_trans['fn'];
				$ln = $get_trans['ln'];
				$em = $get_trans['email'];
				$ph = $get_trans['phone'];
				$uname = $get_trans['fn'];
				$total = $get_trans['balance'];
				$ptype = $get_trans['t_type'];
				$amount = $get_trans['amount'];
				$balanceToImpact = $get_trans['balanceToImpact'];
				$final_date_time = date("Y-m-d h:i:s");

                $get_borrower = $this->fetchWithOneParam('borrowers', 'account', $account[$i]);
                $currentCustBal = ($balanceToImpact == "ledger" ? $get_borrower['balance'] : ($balanceToImpact == "target" ? $get_borrower['target_savings_bal'] : ($balanceToImpact == "investment" ? $get_borrower['investment_bal'] : ($balanceToImpact == "asset" ? $get_borrower['asset_acquisition_bal'] : ($balanceToImpact == "loan" ? $get_borrower['loan_balance'] : $get_borrower['balance'])))));
				$balanceColumn = ($balanceToImpact == "ledger" ? 'balance' : ($balanceToImpact == "target" ? 'target_savings_bal' : ($balanceToImpact == "investment" ? 'investment_bal' : ($balanceToImpact == "asset" ? 'asset_acquisition_bal' : ($balanceToImpact == "loan" ? 'loan_balance' : 'balance')))));
                $newbalance = ($ptype == "Deposit" && ($balanceToImpact == "loan" || $balanceToImpact == "asset") ? ($currentCustBal - $amount) : ($ptype == "Deposit" && $balanceToImpact != "loan" && $balanceToImpact != "asset" ? ($currentCustBal + $amount) : ($ptype == "Withdraw" && $currentCustBal >= $amount && $balanceToImpact != "loan" && $balanceToImpact != "asset" ? ($currentCustBal - $amount) : $currentCustBal)));
                $newbalanceStatus = ($ptype == "Deposit" && ($balanceToImpact == "loan" || $balanceToImpact == "asset") ? "Approved" : ($ptype == "Deposit" && $balanceToImpact != "loan" && $balanceToImpact != "asset" ? "Approved" : ($ptype == "Withdraw" && $currentCustBal >= $amount && $balanceToImpact != "loan" && $balanceToImpact != "asset" ? "Approved" : "Pending")));

                $this->updateWithOneParam('borrowers', $balanceColumn, $newbalance, 'account', $account[$i]);
                $this->updateManytoMany('transaction', ['balance', 'status'], [$newbalance, $newbalanceStatus], 'id', $selector[$i]);

            }
            return "Success"; //Transaction Approved Successfully

        }else{

            return -1; //No record was selected;

        }

    }


    //Function to disapproved pending customer
    public static function disapproveSavings($parameter, $iuid, $isbranchid){

        $clientId = $parameter['clientId'];
        $selector = $parameter['selector'];
        $number = count($selector);

        if($selector == ''){

            return -1; //No record was selected;

        }elseif($number >= 1){

            for($i=0; $i<$number; $i++){

                //Get Savings Transaction details
                $get_trans = $this->fetchWithOneParam('transaction', 'id', $selector[$i]);
                $postedBy = $get_trans['posted_by'];
                $ptype = $get_trans['t_type'];
				$amount = $get_trans['amount'];

                //Get till details for exist
                $fetch_role = $this->fetchWithTwoParam('till_account', 'cashier', $postedBy, 'status', 'Active');
                $balance = $fetch_role['balance'];
				$commtype = $fetch_role['commission_type'];
				$commission = $fetch_role['commission'];
				$commission_bal = $fetch_role['commission_balance'];
                $unsettledBal = $fetch_role['unsettled_balance'];
				$remain_balance = ($ptype == "Deposit") ? ($balance + $amount) : ($balance - $amount);
				$myLabelType = ($ptype == "Deposit") ? "REVERSED_".strtoupper($ptype) : "REVERSED_".strtoupper($ptype)."AL";
				$customer = $fetch_role['teller'];
				$mycurrentTime = date("Y-m-d h:i:s");
				$refid = uniqid().time();

                //Calculate Commission Earn By the Staff
				$cal_commission = ($commtype == "Percentage") ? (($commission / 100) * $amount) : $commission;
				//Update Default Commission Balance
				$total_commission_bal = ($commission == 0) ? $commission_bal : ($cal_commission - $commission_bal);
                //Add to Unsettled Balance
		        $totalUnsettledBal = (($ptype == "Deposit") ? ($unsettledBal - $amount) : (($ptype == "Withdraw") ? ($unsettledBal + $amount) : $unsettledBal));
                $tType = (($ptype == "Deposit") ? "Debit" : (($ptype == "Withdraw") ? "Credit" : "Debit"));

                ($fetch_role != 0 && ($ptype == "Deposit" || $ptype == "Withdraw")) ? $this->updateManytoMany('till_account', ['balance', 'commission_balance','unsettled_balance'], [$remain_balance, $total_commission_bal, $totalUnsettledBal], 'cashier', $postedBy) : "";
                ($fetch_role != 0) ? $queryTillFund = "INSERT INTO fund_allocation_history (txid, companyid, manager_id, branch, teller, cashier, amount_fund, ttype, paymenttype, currency, balance, note_comment, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
                ($fetch_role != 0) ? $this->db->insertTillFundingHistory($queryTillFund, $refid, $clientId, $iuid, $isbranchid, $customer, $iuid, $amount, $tType, $myLabelType, $icurrency, $remain_balance, 'Amount to '.$ptype.' was Declined', "successful", $mycurrentTime) : "";
                
                $this->updateWithOneParam('transaction', 'status', 'Disapproved', 'id', $selector[$i]);
                
            }
            return "Success"; //Transaction Disapproved Successfully

        }else{

            return -1; //No record was selected;

        }

    }


}

?>