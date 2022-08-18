<?php

namespace Class\Customer;

require_once '../Interface/interface.Customer.php';
require_once 'class.Notification.php';

use Interface\Customer\CustomerInterface as CustomerInterface;
use Class\Notification\Notifier as Notifier;

class CustomerManager extends App implements CustomerInterface {

    //Function to add new customer
    public static function addCustomer($parameter, $getSMS_ProviderNum, $ifetch_maintenance_model, $iwallet_balance, $ismscharges, $isenderid, $mobile_applink, $icustomer_limit, $idedicated_ledgerAcctNo_prefix, $allow_auth, $icurrency, $staffname, $isavings_account, $iloan_manager){

        //Account Intro
        $clientId = $this->db->sanitizeInput($parameter['clientId']);
        $acct_type = ($isavings_account === "On") ? $this->db->sanitizeInput($parameter['acct_type']) : ""; 
        $reg_type = $this->db->sanitizeInput($parameter['reg_type']);
        $gname = ($isavings_account === "On" || $iloan_manager === "On") ? $this->db->sanitizeInput($parameter['gname']) : "";
        $g_position = ($isavings_account === "On" || $iloan_manager === "On") ? $this->db->sanitizeInput($parameter['g_position']) : "";

        //Basic Information
        $snum = $this->db->sanitizeInput($parameter['snum']);
        $fname = $this->db->sanitizeInput($parameter['fname']);
        $lname = $this->db->sanitizeInput($parameter['lname']);
        $mname = $this->db->sanitizeInput($parameter['mname']);
        $email = $this->db->sanitizeInput($parameter['email']);
        $phone = $this->db->sanitizeInput($parameter['phone']);
        $gender = $this->db->sanitizeInput($parameter['gender']);
        $dob = $this->db->sanitizeInput($parameter['dob']);
        $occupation = $this->db->sanitizeInput($parameter['occupation']);
        $addrs = $this->db->sanitizeInput($parameter['addrs']);
        $city = $this->db->sanitizeInput($parameter['city']);
        $state = $this->db->sanitizeInput($parameter['state']);
        $country = $this->db->sanitizeInput($parameter['country']);
        $nok = $this->db->sanitizeInput($parameter['nok']);
        $nok_rela = $this->db->sanitizeInput($parameter['nok_rela']);
        $nok_phone = $this->db->sanitizeInput($parameter['nok_phone']);

        //Account Settings
        $smsChecker = $this->db->sanitizeInput($parameter['smsChecker']);
        $account = $this->db->sanitizeInput($parameter['account']);
        $lofficer = $this->db->sanitizeInput($parameter['lofficer']);
        $sbranchid = $this->db->sanitizeInput($parameter['sbranchid']);
        $otp = $this->db->sanitizeInput($parameter['otp']);
        $overdraft = $this->db->sanitizeInput($parameter['overdraft']);
        $username = $this->db->sanitizeInput($parameter['username']);
        $password = $this->db->sanitizeInput($parameter['password']);

        //Savings Settings
        $s_interval = $this->db->sanitizeInput($parameter['s_interval']);
        $s_amount = $this->db->sanitizeInput($parameter['s_amount']);
        $c_interval = $this->db->sanitizeInput($parameter['c_interval']);
        $chargesAmount = $this->db->sanitizeInput($parameter['chargesAmount']);
        $d_interval = $this->db->sanitizeInput($parameter['d_interval']);
        $d_channel = $this->db->sanitizeInput($parameter['d_channel']);
        $account_number = ($d_channel == "Bank" && $isavings_account == "On") ? $this->db->sanitizeInput($parameter['account_number']) : "";
        $bank_code = ($d_channel == "Bank" && $isavings_account == "On") ? $this->db->sanitizeInput($parameter['bank_code']) : "";
        $beneficiary_name = ($d_channel == "Bank" && $isavings_account == "On") ? $this->db->sanitizeInput($parameter['beneficiary_name']) : "";

        //File Upload Parameter
        $sourcepath = $this->db->sanitizeInput($parameter['sourcepath']);
        $targetpath = $this->db->sanitizeInput($parameter['targetpath']);
        $location = $this->db->sanitizeInput($parameter['location']);
        $uploaded_file = $this->db->sanitizeInput($parameter['uploaded_file']);
        $uploaded_fileTMP = $this->db->sanitizeInput($parameter['uploaded_fileTMP']);

        //Other Info
        $refid = uniqid("EA-custReg-").time();
        $transactionPin = substr((uniqid(rand(),1)),3,4);
        $myAccountNumber = "----";
        $accountDetail = "Your Account ID: ".$account;
        $loginDetails = "Username: $username, Password: $password";

        //Detect Number of customer
        $custLimit = $this->fetchWithTwoParamNotAll('borrowers', 'branchid', $clientId, 'acct_status', 'Closed');
        $countCustomer = count($custLimit);

        //Check Customer email and username if exist
        $checkEmail = $this->fetchWithTwoParamNot('borrowers', 'email', $email, 'acct_status', 'Closed');
        $checkUsername = $this->fetchWithTwoParamNot('borrowers', 'username', $username, 'acct_status', 'Closed');
        //Check User email and username if exist
        $checkUserEmail = $this->fetchWithOneParam('user', 'email', $email);
        $checkUserUsername = $this->fetchWithOneParam('user', 'username', $username);

        //SMS charge settings
        $debitWAllet = ($getSMS_ProviderNum != 0 || ($ifetch_maintenance_model != 0 && $ismscharges == "0")) ? "No" : "Yes"; 
        $t_perc = $ifetch_maintenance_model['cust_mfee'];
        $mycharges = ($t_perc == "0" || $t_perc == "") ? 0 : $ifetch_maintenance_model['cust_mfee'];

        $fetch_emailConfig = $this->db->fetchEmailConfig($clientId);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
        $customDomain = ($emailConfigStatus == "Activated") ? $fetch_emailConfig['product_url'] : "https://esusu.app/$isenderid";
        $mobileapp_link = ($mobile_applink == "") ? "Login at ".$customDomain : "Download mobile app: ".$mobile_applink;
        
        //Sms message
        $sms = Notifier::custAlertMsg($isenderid, $fname, $accountDetail, $loginDetails, $transactionPin, $mobileapp_link);

        $max_per_page = 153;
        $sms_length = strlen($sms);
        $calc_length = ceil($sms_length / $max_per_page);
        $sms_charges = $calc_length * $ismscharges;
        $totalCharges = $mycharges + $sms_charges;
        $myiwallet_balance = $iwallet_balance - $totalCharges;
        $wallet_date_time = date("Y-m-d h:i:s");

        //loan group setup
        $fetch_vg = $this->fetchWithOneParam('lgroup_setup', 'id', $gname);
        $max_member = $fetch_vg['max_member'];
        //Get number of members
        $memGroupNum = $this->fetchWithTwoParamAll('borrowers', 'reg_type', $reg_type, 'gname', $gname);
        $countGroupMem = count($memGroupNum);

        //Check duplicate serial number
        $verify_serialno = $this->fetchWithTwoParam('borrowers', 'branchid', $clientId, 'snum', $snum);

        $send_sms = ($phone == "" || $allow_auth == "No" || $ismscharges <= 0) ? "0" : "1";
        $send_email = ($email == "" || $allow_auth == "No") ? "0" : "1";
        $file_title = "Others";
        $status = ($allow_auth == "Yes") ? "Completed" : "queue";
        $acct_status = ($allow_auth == "Yes") ? "Not-Activated" : "queue";

        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST']."/?acn=".$account;
        $id = rand(1000000,10000000);
        $shorturl = base_convert($id,20,36);

        //Get Information from User IP Address
        $myip = $this->db->getUserIP();
        //Get Information from User Browser
        $ua = $this->db->getBrowser();
        $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
        $activities_tracked = $staffname . " make attempt to add new customer account with ledger account no: ".$account;

        if($verify_serialno != 0){

            return -1; //Serial Number Already Used

        }elseif($checkEmail != 0 || $checkUserEmail != 0){

            return -2; //Email Address has already been used

        }elseif($checkUsername != 0 || $checkUserUsername != 0){

            return -3; //Username has already been used

        }elseif($reg_type == "Group" && $max_member == "$countGroupMem"){

            return -4; //Maximum limit reached for group

        }elseif($countCustomer == "$icustomer_limit"){

            return -5; //You are out of space for customers registration

        }elseif($iwallet_balance < $mycharges && $mycharges != '0'){

            return -6; //You have insufficient fund in wallet to add customers

        }elseif($idedicated_ledgerAcctNo_prefix == ""){

            return -7; //The ledger account number prefix is not yet configure

        }else{

            ($location == "") ? "" : $this->uploadImage($sourcepath, $targetpath);
            $opening_date = date("Y-m-d");

            //Add Bank Beneficiary
            ($d_channel == "Bank" && $isavings_account == "On") ? $this->generateBankRecipient($account_number, $bank_code, $beneficiary_name, $clientId, $lofficer, $sbranchid) : "";

            //Upload Attachement if need be
            $this->uploadAttachement($uploaded_file, $uploaded_fileTMP, '', $account, $file_title);

            //Insert Customer Records
            $queryCustInsertn = "INSERT INTO borrowers(snum, fname, lname, mname, email, phone, gender, dob, occupation, addrs, city, state, country, nok, nok_rela, nok_phone, community_role, account, username, password, balance, target_savings_bal, investment_bal, loan_balance, asset_acquisition_bal, image, date_time, last_withdraw_date, status, lofficer, c_sign, branchid, sbranchid, acct_status, s_contribution_interval, savings_amount, charge_interval, chargesAmount, disbursement_interval, disbursement_channel, auto_disbursement_status, auto_charge_status, next_charge_date, next_disbursement_date, recipient_id, otp_option, currency, wallet_balance, overdraft, card_id, card_reg, card_issurer, tpin, reg_type, gname, gposition, acct_type, expected_fixed_balance, acct_opening_date, unumber, verve_expiry_date, employer, dedicated_ussd_prefix, evn, sms_checker, ws_interval, ave_savings_amt, ws_duration, ws_frequency, mmaidenName, moi, lga, otherInfo, nok_addrs, name_of_trustee, sendSMS, sendEmail) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
            $this->db->insertCustomerPc($queryCustInsertn, $snum, $fname, $lname, $mname, $email, $phone, $gender, $dob, $occupation, $addrs, $city, $state, $country, $nok, $nok_rela, $nok_phone, 'Borrower', $account, $username, $password, '0.0', '0.0', '0.0', '0.0', '0.0', $location, $wallet_date_time, '0000-00-00', $status, $lofficer, '', $clientId, $sbranchid, $acct_status, $s_interval, $s_amount, $c_interval, $chargesAmount, $d_interval, $d_channel, 'NotActive','NotActive','','', '', $otp, $icurrency, '0.0', $overdraft, 'NULL','No','NULL', $transactionPin, $reg_type, $gname, $g_position, $acct_type, '0.0', $opening_date, '', '', '', '', '', $smsChecker, '', '', '', '', '', '', '', '', '', '', $send_sms, $send_email);

            //Inert Activation Code
            $shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/?activation_key=' . $shorturl;
	        $shortenedurl1 = $protocol . $_SERVER['HTTP_HOST'] . '/?deactivation_key=' . $shorturl;
            $queryActivationCode = "INSERT INTO activate_member(url, shorturl, attempt, acn) VALUES(?, ?, ?, ?)";
            $this->db->insertActivationCode($queryActivationCode, $url, $shorturl, 'No', $account);

            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $clientId, 'institution', $lofficer, $myip, $yourbrowser, $activities_tracked, $sbranchid, "Success", $wallet_date_time);

            //Sms Notification
	        (($ismscharges <= 0 || $phone == "") ? "" : ($allow_auth == "Yes" && $debitWAllet == "No" ? Notifier::sendSMS($isenderid, $phone, $sms, $clientId, $refid, $lofficer) : ($allow_auth == "Yes" && $debitWAllet == "Yes" && $mycharges <= $iwallet_balance ? Notifier::sendSMS($isenderid, $phone, $sms, $clientId, $refid, $lofficer) : "")));
            
            //Email Notification
            ($allow_auth == "Yes") ? Notifier::customerRegEmailNotifier($email, $fname, $shortenedurl, $username, $password, $account, $myAccountNumber, $shortenedurl1) : "";

            return "Success";

        }

    }

    //Function to upload bulk customer registration
    public static function bulkCustomerRegCSV($parameter, $getSMS_ProviderNum, $ifetch_maintenance_model, $iwallet_balance, $ismscharges, $isenderid, $mobile_applink, $icustomer_limit, $idedicated_ledgerAcctNo_prefix, $allow_auth, $clientId, $staffname){

        $myCSVFile = $this->db->sanitizeInput($parameter['myCSVFile']);
        $fileTmp = $this->db->sanitizeInput($parameter['fileTmp']);
        $lofficer = $this->db->sanitizeInput($parameter['lofficer']);
        $sbranchid = $this->db->sanitizeInput($parameter['sbranchid']);

        $fetch_emailConfig = $this->db->fetchEmailConfig($clientId);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
        $customDomain = ($emailConfigStatus == "Activated") ? $fetch_emailConfig['product_url'] : "https://esusu.app/$isenderid";
        $mobileapp_link = ($mobile_applink == "") ? "Login at ".$customDomain : "Download mobile app: ".$mobile_applink;

        //Get Information from User IP Address
        $myip = $this->db->getUserIP();
        //Get Information from User Browser
        $ua = $this->db->getBrowser();
        $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];

        if($idedicated_ledgerAcctNo_prefix == ""){

            return -1; //The ledger account number prefix is not yet configure

        }elseif($myCSVFile[1] == 'csv'){

            $handle = fopen($fileTmp, "r");
            $fp = file($fileTmp, FILE_SKIP_EMPTY_LINES);
            $countFile = count($fp);
            $activities_tracked = $staffname . " make attempt to upload " . $countFile . "batch customer account";
            $sumAmt = 0;
            $sumChg = 0;
            fgetcsv($handle,1000,","); // pop the headers
            while($data = fgetcsv($handle,1000,",")){
                
                $empty_filesop = array_filter(array_map('trim', $data));

                if(!empty($empty_filesop)){

                    $snum = $this->db->sanitizeInput($data[0]);
                    $fname = ucwords($this->db->sanitizeInput($data[1]));
                    $lname = ucwords($this->db->sanitizeInput($data[2]));
                    $mname = ucwords($this->db->sanitizeInput($data[3]));
                    $email = str_replace(' ', '', $this->db->sanitizeInput($data[4]));
                    $phone = (strpos($this->db->sanitizeInput($data[5]), '+234') === 0 ? str_replace(' ', '', $this->db->sanitizeInput($data[5])) : (strpos($this->db->sanitizeInput($data[5]), '234') === 0 ?  "+".str_replace(' ', '', $this->db->sanitizeInput($data[5])) : ($data[5] == "" ? "" : "+234".str_replace(' ', '', $this->db->sanitizeInput($data[5])))));
                    $gender = ucwords($this->db->sanitizeInput($data[6]));
                    $dob = ($data[7] == "") ? "" : $this->db->reformatDate($this->db->sanitizeInput($data[7]));
                    $occupation = $this->db->sanitizeInput($data[8]);
                    $address = $this->db->sanitizeInput($data[9]);
                    $city = ucwords($this->db->sanitizeInput($data[10]));
                    $state = ucwords($this->db->sanitizeInput($data[11]));
                    $country = strtoupper($this->db->sanitizeInput($data[12]));
                    $next_of_kin = $this->db->sanitizeInput($data[13]);
                    $next_of_kin_rela = $this->db->sanitizeInput($data[14]);
                    $next_of_kin_phone = $this->db->sanitizeInput($data[15]);
                    $myusername = $this->db->sanitizeInput($data[16]);
					$username = ($myusername == "") ? $fname : $myusername;
                    $password = substr((uniqid(rand(),1)),3,6);
                    $currency = str_replace(' ', '', strtoupper($this->db->sanitizeInput($data[17])));
					$account = $idedicated_ledgerAcctNo_prefix.substr((uniqid(rand(),1)),4,8);
                    $s_contribution_interval = ucwords($this->db->sanitizeInput($data[18]));
                    $savings_amount = $this->db->sanitizeInput($data[19]);
                    $charge_interval = ucwords($this->db->sanitizeInput($data[20]));
                    $chargesAmount = $this->db->sanitizeInput($data[21]);
                    $disbursement_interval = ucwords($this->db->sanitizeInput($data[22]));
                    $disbursement_channel = $this->db->sanitizeInput($data[23]);
					$savingsProductCode = $this->db->sanitizeInput($data[24]);
					$groupCode = $this->db->sanitizeInput($data[25]);
					$savingsBalance = $this->db->sanitizeInput($data[26]);
					$targetSavingsBal = $this->db->sanitizeInput($data[27]);
					$investementBal = $this->db->sanitizeInput($data[28]);
					$loanBal = $this->db->sanitizeInput($data[29]);
					$assetAcquisitionBal = $this->db->sanitizeInput($data[30]);
					$branchCode = $this->db->sanitizeInput($data[31]);
					$status = ($allow_auth == "Yes") ? "Completed" : "queue";
                    $acct_status = ($allow_auth == "Yes") ? "Not-Activated" : "queue";
					$myAccountNumber = "----";
                    $opening_date = date("Y-m-d");
                    $wallet_date_time = date("Y-m-d H:i:s");
                    $transactionPin = substr((uniqid(rand(),1)),3,4);

                    //loan group setup
                    $fetch_vg = $this->fetchWithOneParam('lgroup_setup', 'id', $groupCode);
                    $max_member = $fetch_vg['max_member'];
                    $gname = ($fetch_vg['gname'] == "") ? "" : $fetch_vg['gname'];
                    $g_position = ($fetch_vg['gname'] == "") ? "" : "member";

                    //Account type
                    $fetch_acctType = $this->fetchWithOneParam('account_type', 'id', $savingsProductCode);
                    $acct_type = ($fetch_acctType['acct_name'] == "") ? ucwords($savingsProductCode) : $fetch_acctType['acct_name'];
					$reg_type = ($fetch_acctType['gname'] == "") ? "Individual" : "Group";
                    
                    $refid = "EA-custReg-".time().uniqid();
                    //SMS charge settings
                    $debitWAllet = ($getSMS_ProviderNum != 0 || ($ifetch_maintenance_model != 0 && $ismscharges == "0")) ? "No" : "Yes"; 
                    $t_perc = $ifetch_maintenance_model['cust_mfee'];
                    $indivCharges = ($t_perc == "0" || $t_perc == "") ? 0 : $t_perc;
                    $mycharges = ($t_perc == "0" || $t_perc == "") ? 0 : ($t_perc * $countFile);
                    $myiwallet_balance = $iwallet_balance - $mycharges;

                    if($mycharges > $iwallet_balance){

                        return -2; //Insufficient fund in wallet to add bulk customer

                    }else{

                        //debit institution wallet for bulk customer registration
                        ($indivCharges == "0") ? "" : $this->updateWithOneParam('institution_data', 'wallet_balance', $myiwallet_balance, 'institution_id', $clientId);
                        
                        //Insert Customer Records
                        $queryCustInsertn = "INSERT INTO borrowers(snum, fname, lname, mname, email, phone, gender, dob, occupation, addrs, city, state, country, nok, nok_rela, nok_phone, community_role, account, username, password, balance, target_savings_bal, investment_bal, loan_balance, asset_acquisition_bal, image, date_time, last_withdraw_date, status, lofficer, c_sign, branchid, sbranchid, acct_status, s_contribution_interval, savings_amount, charge_interval, chargesAmount, disbursement_interval, disbursement_channel, auto_disbursement_status, auto_charge_status, next_charge_date, next_disbursement_date, recipient_id, otp_option, currency, wallet_balance, overdraft, card_id, card_reg, card_issurer, tpin, reg_type, gname, gposition, acct_type, expected_fixed_balance, acct_opening_date, unumber, verve_expiry_date, employer, dedicated_ussd_prefix, evn, sms_checker, ws_interval, ave_savings_amt, ws_duration, ws_frequency, mmaidenName, moi, lga, otherInfo, nok_addrs, name_of_trustee, sendSMS, sendEmail) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
                        $this->db->insertCustomerPc($queryCustInsertn, $snum, $fname, $lname, $mname, $email, $phone, $gender, $dob, $occupation, $address, $city, $state, $country, $next_of_kin, $next_of_kin_rela, $next_of_kin_phone, 'Borrower', $account, $username, $password, '0.0', '0.0', '0.0', '0.0', '0.0', '', $wallet_date_time, '0000-00-00', $status, $lofficer, '', $clientId, $sbranchid, $acct_status, $s_contribution_interval, $savings_amount, $charge_interval, $chargesAmount, $disbursement_interval, $disbursement_channel, 'NotActive','NotActive','','', '', 'No', $currency, '0.0', 'No', 'NULL','No','NULL', $transactionPin, $reg_type, $gname, $g_position, $acct_type, '0.0', $opening_date, '', '', '', '', '', 'Yes', '', '', '', '', '', '', '', '', '', '', '0', '0');

                        //Insert Wallet history
                        $query = "INSERT INTO wallet_history(userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        ($indivCharges == "0") ? "" : $this->db->insertWalletHistory($query, $clientId, $refid, $phone, '', $indivCharges, 'Debit', $currency, 'Charges', 'Bulk Customer Regisration - '.$account, 'successful', $wallet_date_time, $lofficer, $myiwallet_balance);
        
                    }

                }

            }
            fclose($handle);
            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $clientId, 'institution', $lofficer, $myip, $yourbrowser, $activities_tracked, $sbranchid, "Success", $wallet_date_time);
            return "Success"; //Bulk Customers Added Successfully

        }else{

            return -3; //Invalid CSV File

        }

    }

    //Function to link new customer account with existing one
    public static function linkCustomerAcct($parameter, $getSMS_ProviderNum, $ifetch_maintenance_model, $iwallet_balance, $ismscharges, $isenderid, $mobile_applink, $icustomer_limit, $idedicated_ledgerAcctNo_prefix, $allow_auth, $clientId, $staffname){

        $defaultAcct = $this->db->sanitizeInput($parameter['defaultAcct']);
        $fetch_cust = $this->fetchWithTwoParam('borrowers', 'branchid', $clientId, 'account', $defaultAcct);
        $snum = $fetch_cust['snum'];
        $fname = $fetch_cust['fname'];
        $lname = $fetch_cust['lname'];
        $mname = $fetch_cust['mname'];
        $email = $fetch_cust['email'];
        $phone = $fetch_cust['phone'];
        $gender = $fetch_cust['gender'];
        $dob = $fetch_cust['dob'];
        $occupation = $fetch_cust['occupation'];
        $addrs = $fetch_cust['addrs'];
        $city = $fetch_cust['city'];
        $state = $fetch_cust['state'];
        $zip = $fetch_cust['zip'];
        $country = $fetch_cust['country'];
        $nok = $fetch_cust['nok'];
        $nok_rela = $fetch_cust['nok_rela'];
        $nok_phone = $fetch_cust['nok_phone'];
        $account = $this->db->sanitizeInput($parameter['account']);
        $username = $this->db->sanitizeInput($parameter['username']);
        $password = substr((uniqid(rand(),1)),4,6);
        $location = $fetch_cust['image'];
        $date_time = date("Y-m-d h:i:s");
        $last_withdraw_date = "0000-00-00";
        $status = $fetch_cust['status'];
        $lofficer = $fetch_cust['lofficer'];
        $c_sign = $fetch_cust['c_sign'];
        $s_contribution_interval = $fetch_cust['s_contribution_interval'];
        $savings_amount = $fetch_cust['savings_amount'];
        $charge_interval = $fetch_cust['charge_interval'];
        $chargesAmount = $fetch_cust['chargesAmount'];
        $disbursement_interval = $fetch_cust['disbursement_interval'];
        $disbursement_channel = $fetch_cust['disbursement_channel'];
        $auto_disbursement_status = $fetch_cust['auto_disbursement_status'];
        $auto_charge_status = $fetch_cust['auto_charge_status'];
        $next_charges_date = $fetch_cust['next_charges_date'];
        $next_disbursement_date = $fetch_cust['next_disbursement_date'];
        $recipient_id = $fetch_cust['recipient_id'];
        $opt_option = $fetch_cust['opt_option'];
        $currency = $fetch_cust['currency'];
        $overdraft = "No";
        $transactionPin = substr((uniqid(rand(),1)),3,4);
        $reg_type = "Individual";
        $gname = "";
        $gposition = "";
        $acct_type = $this->db->sanitizeInput($parameter['acct_type']);
        $acct_opening_date = date("Y-m-d");
        $unumber = $fetch_cust['unumber'];
        $verve_expiry_date = "";
        $employer = $fetch_cust['employer'];
        $virtual_number = "";
        $virtual_acctno = "";
        $bankname = "";
        $dedicated_ussd_prefix = $fetch_cust['dedicated_ussd_prefix'];
        $evnNumber = ($_POST['evnNumber'] == "") ? $fetch_cust['evn'] : $this->db->sanitizeInput($parameter['evnNumber']);

        //Other Info
        $refid = uniqid("EA-custReg-").time();
        $myAccountNumber = "----";
        $accountDetail = "Your Account ID: ".$account;
        $loginDetails = "Username: $username, Password: $password";

        //Detect Number of customer
        $custLimit = $this->fetchWithTwoParamNotAll('borrowers', 'branchid', $clientId, 'acct_status', 'Closed');
        $countCustomer = count($custLimit);

        //Check Customer username if exist
        $checkUsername = $this->fetchWithTwoParamNot('borrowers', 'username', $username, 'acct_status', 'Closed');
        //Check User username if exist
        $checkUserUsername = $this->fetchWithOneParam('user', 'username', $username);

        //SMS charge settings
        $debitWAllet = ($getSMS_ProviderNum != 0 || ($ifetch_maintenance_model != 0 && $ismscharges == "0")) ? "No" : "Yes"; 
        $t_perc = $ifetch_maintenance_model['cust_mfee'];
        $mycharges = ($t_perc == "0" || $t_perc == "") ? 0 : $ifetch_maintenance_model['cust_mfee'];

        $fetch_emailConfig = $this->db->fetchEmailConfig($clientId);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
        $customDomain = ($emailConfigStatus == "Activated") ? $fetch_emailConfig['product_url'] : "https://esusu.app/$isenderid";
        $mobileapp_link = ($mobile_applink == "") ? "Login at ".$customDomain : "Download mobile app: ".$mobile_applink;
        
        //Sms message
        $sms = Notifier::custAlertMsg($isenderid, $fname, $accountDetail, $loginDetails, $transactionPin, $mobileapp_link);

        $max_per_page = 153;
        $sms_length = strlen($sms);
        $calc_length = ceil($sms_length / $max_per_page);
        $sms_charges = $calc_length * $ismscharges;
        $totalCharges = $mycharges + $sms_charges;
        $myiwallet_balance = $iwallet_balance - $totalCharges;
        $wallet_date_time = date("Y-m-d h:i:s");

        $send_sms = ($phone == "" || $allow_auth == "No" || $ismscharges <= 0) ? "0" : "1";
        $send_email = ($email == "" || $allow_auth == "No") ? "0" : "1";
        $file_title = "Others";
        $status = ($allow_auth == "Yes") ? "Completed" : "queue";
        $acct_status = ($allow_auth == "Yes") ? "Not-Activated" : "queue";

        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST']."/?acn=".$account;
        $id = rand(1000000,10000000);
        $shorturl = base_convert($id,20,36);

        //Get Information from User IP Address
        $myip = $this->db->getUserIP();
        //Get Information from User Browser
        $ua = $this->db->getBrowser();
        $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
        $activities_tracked = $staffname . " make attempt to add new customer account with ledger account no: ".$account;

        if($checkUsername != 0 || $checkUserUsername != 0){

            return -1; //Username has already been used

        }elseif($countCustomer == "$icustomer_limit"){

            return -2; //You are out of space for customers registration

        }elseif($iwallet_balance < $mycharges && $mycharges != '0'){

            return -3; //You have insufficient fund in wallet to add customers

        }elseif($idedicated_ledgerAcctNo_prefix == ""){

            return -4; //The ledger account number prefix is not yet configure

        }else{

            $opening_date = date("Y-m-d");

            //Insert Customer Records
            $queryCustInsertn = "INSERT INTO borrowers(snum, fname, lname, mname, email, phone, gender, dob, occupation, addrs, city, state, country, nok, nok_rela, nok_phone, community_role, account, username, password, balance, target_savings_bal, investment_bal, loan_balance, asset_acquisition_bal, image, date_time, last_withdraw_date, status, lofficer, c_sign, branchid, sbranchid, acct_status, s_contribution_interval, savings_amount, charge_interval, chargesAmount, disbursement_interval, disbursement_channel, auto_disbursement_status, auto_charge_status, next_charge_date, next_disbursement_date, recipient_id, otp_option, currency, wallet_balance, overdraft, card_id, card_reg, card_issurer, tpin, reg_type, gname, gposition, acct_type, expected_fixed_balance, acct_opening_date, unumber, verve_expiry_date, employer, dedicated_ussd_prefix, evn, sms_checker, ws_interval, ave_savings_amt, ws_duration, ws_frequency, mmaidenName, moi, lga, otherInfo, nok_addrs, name_of_trustee, sendSMS, sendEmail) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
            $this->db->insertCustomerPc($queryCustInsertn, $snum, $fname, $lname, $mname, $email, $phone, $gender, $dob, $occupation, $addrs, $city, $state, $country, $nok, $nok_rela, $nok_phone, 'Borrower', $account, $username, $password, '0.0', '0.0', '0.0', '0.0', '0.0', $location, $wallet_date_time, '0000-00-00', $status, $lofficer, '', $clientId, $sbranchid, $acct_status, $s_contribution_interval, $savings_amount, $charge_interval, $chargesAmount, $disbursement_interval, $disbursement_channel, 'NotActive','NotActive','','', '', $opt_option, $currency, '0.0', $overdraft, 'NULL','No','NULL', $transactionPin, $reg_type, $gname, $g_position, $acct_type, '0.0', $opening_date, '', '', '', '', '', $smsChecker, '', '', '', '', '', '', '', '', '', '', $send_sms, $send_email);

            //Inert Activation Code
            $shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/?activation_key=' . $shorturl;
	        $shortenedurl1 = $protocol . $_SERVER['HTTP_HOST'] . '/?deactivation_key=' . $shorturl;
            $queryActivationCode = "INSERT INTO activate_member(url, shorturl, attempt, acn) VALUES(?, ?, ?, ?)";
            $this->db->insertActivationCode($queryActivationCode, $url, $shorturl, 'No', $account);

            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $clientId, 'institution', $lofficer, $myip, $yourbrowser, $activities_tracked, $sbranchid, "Success", $wallet_date_time);

            //Sms Notification
	        (($ismscharges <= 0 || $phone == "") ? "" : ($allow_auth == "Yes" && $debitWAllet == "No" ? Notifier::sendSMS($isenderid, $phone, $sms, $clientId, $refid, $lofficer) : ($allow_auth == "Yes" && $debitWAllet == "Yes" && $mycharges <= $iwallet_balance ? Notifier::sendSMS($isenderid, $phone, $sms, $clientId, $refid, $lofficer) : "")));
            
            //Email Notification
            ($allow_auth == "Yes") ? Notifier::customerRegEmailNotifier($email, $fname, $shortenedurl, $username, $password, $account, $myAccountNumber, $shortenedurl1) : "";

            return "Success";

        }

    }

    //Function to fetch all customer records
    public static function viewAllCustomers($parameter, $iCustRecords, $bCustRecords, $updateCustinfo, $viewAcctInfo, $viewLoanHistory, $iuid, $isbranchid){

        $column = array('id', 'action', 'sNo', 'branch', 'staffName', 'accountId', 'name', 'acctType', 'regType', 'phone', 'Last Update', 'Opening Date', 'ledgerBalance', 'targetSavingsBal', 'investmentBalance', 'loanBalance', 'assetAcquisitionBal', 'walletBalance', 'status');

        //Data table Parameter
        $searchValue = $parameter['searchValue']; //$_POST['search']['value']
        $draw = $parameter['draw']; //$_POST['draw']
        $order = $parameter['order']; //$_POST['order']
        $orderColumn = $parameter['orderColumn']; //$_POST['order']['0']['column']
        $start = $parameter['start']; //$_POST['start']
        $length = $parameter['length']; //$_POST['length']

        $startDate = ($parameter['startDate'] != "") ? $parameter['startDate'] : "0000-00-00";
        $endDate = ($parameter['endDate'] != "") ? $parameter['endDate'] : date("Y-m-d");
        $byCustomer = $parameter['byCustomer'];
        $filterBy = $parameter['filterBy'];
        $clientId = $parameter['clientId'];

        $query = " ";

        if($startDate != "" && $endDate != "" && $byCustomer == "" && $filterBy != ""){
    
            ($iCustRecords != "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE (acct_opening_date BETWEEN '$startDate' AND '$endDate'  AND branchid = '$clientId' AND acct_status != 'Closed' AND (sbranchid = '$filterBy' OR lofficer = '$filterBy' OR status = '$filterBy'))
             " : "";
             
            ($iCustRecords === "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE (acct_opening_date BETWEEN '$startDate' AND '$endDate'  AND branchid = '$clientId' AND lofficer = '$iuid' AND acct_status != 'Closed')
             " : "";
             
            ($iCustRecords === "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE (acct_opening_date BETWEEN '$startDate' AND '$endDate'  AND branchid = '$clientId' AND sbranchid = '$isbranchid' AND acct_status != 'Closed')
             " : "";
            
        }
        
        if($startDate == "" && $endDate == "" && $filterBy == "" && $byCustomer != ""){
            
            ($iCustRecords != "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE account =  '$byCustomer' AND branchid = '$clientId' AND acct_status != 'Closed'
             " : "";
             
            ($iCustRecords === "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE account =  '$byCustomer' AND branchid = '$clientId' AND lofficer = '$iuid' AND acct_status != 'Closed'
             " : "";
             
            ($iCustRecords != "1" && $bCustRecords === "1") ? $query .= "SELECT * FROM borrowers 
            WHERE account =  '$byCustomer' AND branchid = '$clientId' AND sbranchid = '$isbranchid' AND acct_status != 'Closed'
             " : "";
            
        }
        
        
        if($startDate == "" && $endDate == "" && $filterBy != "All" && $filterBy != "" && $byCustomer == ""){
            
            ($iCustRecords != "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE branchid = '$clientId' AND acct_status != 'Closed' AND (sbranchid = '$filterBy' OR lofficer = '$filterBy' OR status = '$filterBy')
             " : "";
             
            ($iCustRecords === "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE branchid = '$clientId' AND lofficer = '$iuid' AND acct_status != 'Closed'
             " : "";
             
            ($iCustRecords != "1" && $bCustRecords === "1") ? $query .= "SELECT * FROM borrowers 
            WHERE branchid = '$clientId' AND sbranchid = '$isbranchid' AND acct_status != 'Closed'
             " : "";
            
        }
        
        
        if($startDate == "" && $endDate == "" && $filterBy == "All" && $byCustomer == ""){
            
            ($iCustRecords != "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE branchid = '$clientId' AND acct_status != 'Closed'
             " : "";
             
            ($iCustRecords === "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE branchid = '$clientId' AND lofficer = '$iuid' AND acct_status != 'Closed'
             " : "";
             
            ($iCustRecords != "1" && $bCustRecords === "1") ? $query .= "SELECT * FROM borrowers 
            WHERE branchid = '$clientId' AND sbranchid = '$isbranchid' AND acct_status != 'Closed'
             " : "";
            
        }
        
        
        if($startDate != "" && $endDate != "" && $filterBy == "" && $byCustomer == ""){
            
            ($iCustRecords != "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE acct_opening_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$clientId' AND acct_status != 'Closed'
             " : "";
             
            ($iCustRecords === "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE acct_opening_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$clientId' AND lofficer = '$iuid' AND acct_status != 'Closed'
             " : "";
             
            ($iCustRecords === "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE acct_opening_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$clientId' AND sbranchid = '$isbranchid' AND acct_status != 'Closed'
             " : "";
            
        }

        if($searchValue != ''){

            $query .= "SELECT * FROM borrowers
            WHERE id LIKE '%'.$searchValue.'%' 
            OR account LIKE '%'.$searchValue.'%' 
            OR snum LIKE '%'.$searchValue.'%' 
            OR status LIKE '%'.$searchValue.'%' 
            OR phone LIKE '%'.$searchValue.'%' 
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
            $mysbranch = $row['sbranchid'];
            $myofficer = $row['lofficer'];
            $acct_status = $row['acct_status'];

            $selectBranch = $this->fetchWithOneParam('branches', 'branchid', $mysbranch);
            $selectUser = $this->fetchWithOneParam('user', 'id', $myofficer);

            $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."' ".(($bal >= 1 || $wbal >= 1) ? 'disabled' : '').">";
            $sub_array[] = ($acct_status == "Closed") ? "---" : "<div class='btn-group'>
                                <div class='btn-group'>
                                <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                                    <span class='caret'></span>
                                </button>
                                <ul class='dropdown-menu'>
                                ".(($updateCustinfo == '1') ? "<li><p><a href='add_to_borrower_list.php?id=".$row['id']."&&mid=".base64_encode('403')."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-save'>&nbsp;Update Account</i></a></p></li>" : '')."
                                ".(($viewAcctInfo == '1') ? "<li><p><a href='invoice-print.php?id=".$_SESSION['tid']."&&mid=".base64_encode('403')."&&uid=".$acctno."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-search'>&nbsp;View Account</i></a></p></li>" : '')."
                                ".(($viewLoanHistory == '1') ? "<li><p><a href='loan-history.php?id=".$_SESSION['tid']."&&mid=".base64_encode('403')."&&uid=".$acctno."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-book'>&nbsp;View Loan</i></a></p></li>" : '')."
                                "/**.(($initiate_cardholder_registration == '1' && $row['card_reg'] == 'No' && $row['addrs'] != '' && $row['dob'] != '' && $row['city'] != '' && $row['state'] != '') ? "<li><p><a href='cardholder_reg1.php?id=".$row['id']."&&mid=".base64_encode('403')."' class='btn btn-default btn-flat'><i class='fa fa-credit-card'>&nbsp;Enrol for VerveCard</i></a></p></li>" : ''). */"
                                </ul>
                                </div>
                            </div>";
            $sub_array[] = ($row['snum'] == "") ? "null" : $row['snum'];
            $sub_array[] = ($mysbranch == "") ? '<b>Head Office</b>' : '<b>'.$selectBranch['bname'].'</b>';
            $sub_array[] = ($myofficer == "") ? 'NIL' : $selectUser['name'].' '.$selectUser['fname'];
            $sub_array[] = $row['account'];
            $sub_array[] = $row['fname'].' '.$row['lname'];
            $sub_array[] = ($row['acct_type'] == "") ? "NIL" : $row['acct_type'];
            $sub_array[] = $row['reg_type'];
            $sub_array[] = $row['phone'];
            $sub_array[] = $this->db->convertDateTime($row['date_time']);
            $sub_array[] = $row['acct_opening_date'];
            $sub_array[] = $row['currency'].number_format($row['balance'],2,'.',',');
            $sub_array[] = $row['currency'].number_format($row['target_savings_bal'],2,'.',',');
            $sub_array[] = $row['currency'].number_format($row['investment_bal'],2,'.',',');
            $sub_array[] = $row['currency'].number_format($row['loan_balance'],2,'.',',');
            $sub_array[] = $row['currency'].number_format($row['asset_acquisition_bal'],2,'.',',');
            $sub_array[] = $row['currency'].number_format($row['wallet_balance'],2,'.',',');
            $sub_array[] = ($acct_status == "Activated" ? "<span class='label bg-blue'>Active</span>" : ($acct_status == "Closed" ? "<span class='label bg-red'>Closed</span>" : "<span class='label bg-orange'>Not-Active</span>"));
            $data[] = $sub_array;

        }

        ($iCustRecords != "1" && $bCustRecords != "1") ? $query2 = "SELECT * FROM borrowers WHERE branchid = '$clientId'" : "";
        ($iCustRecords === "1" && $bCustRecords != "1") ? $query2 = "SELECT * FROM borrowers WHERE branchid = '$clientId' AND lofficer = '$iuid'" : "";
        ($iCustRecords != "1" && $bCustRecords === "1") ? $query2 = "SELECT * FROM borrowers WHERE branchid = '$clientId' AND sbranchid = '$isbranchid'" : "";
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

    //Function to update customer basic information
    public static function updateCustomersInfo($parameter, $clientId, $isavings_account, $iloan_manager){

        $id = $this->db->sanitizeInput($parameter['id']);
        $acct_type = ($isavings_account === "On") ? $this->db->sanitizeInput($parameter['acct_type']) : "Regular Account"; 
        $reg_type = $this->db->sanitizeInput($parameter['reg_type']);
        $gname = ($isavings_account === "On" || $iloan_manager === "On") ? $this->db->sanitizeInput($parameter['gname']) : "";
        $g_position = ($isavings_account === "On" || $iloan_manager === "On") ? $this->db->sanitizeInput($parameter['g_position']) : "";

        //Basic Information
        $snum = $this->db->sanitizeInput($parameter['snum']);
        $fname = $this->db->sanitizeInput($parameter['fname']);
        $lname = $this->db->sanitizeInput($parameter['lname']);
        $mname = $this->db->sanitizeInput($parameter['mname']);
        $email = $this->db->sanitizeInput($parameter['email']);
        $phone = $this->db->sanitizeInput($parameter['phone']);
        $gender = $this->db->sanitizeInput($parameter['gender']);
        $dob = $this->db->sanitizeInput($parameter['dob']);
        $occupation = $this->db->sanitizeInput($parameter['occupation']);
        $addrs = $this->db->sanitizeInput($parameter['addrs']);
        $city = $this->db->sanitizeInput($parameter['city']);
        $state = $this->db->sanitizeInput($parameter['state']);
        $country = $this->db->sanitizeInput($parameter['country']);
        $nok = $this->db->sanitizeInput($parameter['nok']);
        $nok_rela = $this->db->sanitizeInput($parameter['nok_rela']);
        $nok_phone = $this->db->sanitizeInput($parameter['nok_phone']);
        $sourcepath = $this->db->sanitizeInput($parameter['sourcepath']);
        $targetpath = $this->db->sanitizeInput($parameter['targetpath']);
        $location = $this->db->sanitizeInput($parameter['location']);

        //Account Settings
        $smsChecker = $this->db->sanitizeInput($parameter['smsChecker']);
        $lofficer = $this->db->sanitizeInput($parameter['lofficer']);
        $sbranchid = $this->db->sanitizeInput($parameter['sbranchid']);
        $otp = $this->db->sanitizeInput($parameter['otp']);
        $overdraft = $this->db->sanitizeInput($parameter['overdraft']);
        $username = $this->db->sanitizeInput($parameter['username']);

        //Savings Settings
        $s_interval = $this->db->sanitizeInput($parameter['s_interval']);
        $s_amount = $this->db->sanitizeInput($parameter['s_amount']);
        $c_interval = $this->db->sanitizeInput($parameter['c_interval']);
        $chargesAmount = $this->db->sanitizeInput($parameter['chargesAmount']);
        $d_interval = $this->db->sanitizeInput($parameter['d_interval']);
        $d_channel = $this->db->sanitizeInput($parameter['d_channel']);
        $account_number = ($d_channel == "Bank" && $isavings_account == "On") ? $this->db->sanitizeInput($parameter['account_number']) : "";
        $bank_code = ($d_channel == "Bank" && $isavings_account == "On") ? $this->db->sanitizeInput($parameter['bank_code']) : "";
        $beneficiary_name = ($d_channel == "Bank" && $isavings_account == "On") ? $this->db->sanitizeInput($parameter['beneficiary_name']) : "";

        //verify new serial number existence if applicable
        $verify_serialno = $this->fetchWithThreeParamNot('borrowers', 'branchid', $clientId, 'snum', $snum, 'id', $id);

        //Check Customer email and username if exist
        $checkEmail = $this->fetchWithTwoParamNot('borrowers', 'email', $email, 'id', $id);
        $checkUsername = $this->fetchWithTwoParamNot('borrowers', 'username', $username, 'id', $id);
        //Check User email and username if exist
        $checkUserEmail = $this->fetchWithOneParam('user', 'email', $email);
        $checkUserUsername = $this->fetchWithOneParam('user', 'username', $username);

        //loan group setup
        $fetch_vg = $this->fetchWithOneParam('lgroup_setup', 'id', $gname);
        $max_member = $fetch_vg['max_member'];
        //Get number of members
        $memGroupNum = $this->fetchWithThreeParamNotAll('borrowers', 'reg_type', $reg_type, 'gname', $gname, 'id', $id);
        $countGroupMem = count($memGroupNum);

        if($verify_serialno != 0){

            return -1; //Serial number already used

        }elseif($checkEmail != 0 || $checkUserEmail != 0){

            return -2; //Email Address has already been used

        }elseif($checkUsername != 0 || $checkUserUsername != 0){

            return -3; //Username has already been used

        }elseif($reg_type == "Group" && $max_member == "$countGroupMem"){

            return -4; //Maximum limit reached for group

        }else{

            //Upload new image
            ($location == "") ? "" : $this->uploadImage($sourcepath, $targetpath);

            //Add Bank Beneficiary
            ($d_channel == "Bank" && $isavings_account == "On") ? $this->generateBankRecipient($account_number, $bank_code, $beneficiary_name, $clientId, $lofficer, $sbranchid) : "";

            //Update customer information
            ($location != "") ? $this->updateManytoMany('borrowers', ['snum', 'fname', 'lname', 'mname', 'email', 'phone', 'gender', 'dob', 'occupation', 'addrs', 'city', 'state', 'zip', 'country', 'lofficer', 'account', 'sbranchid', 'nok', 'nok_rela', 'nok_phone', 'image', 'acct_status', 'opt_option', 'username', 'overdraft', 'reg_type', 'acct_type', 's_contribution_interval', 'savings_amount', 'charge_interval', 'chargesAmount', 'disbursement_interval', 'disbursement_channel', 'acct_opening_date', 'sms_checker'], [$snum, $fname, $lname, $mname, $email, $phone, $gender, $dob, $occupation, $addrs, $city, $state, $zip, $country, $lofficer, $account, $branchid, $nok, $nok_rela, $nok_phone, $location, $acct_status, $otp, $username, $overdraft, $reg_type, $acct_type, $s_interval, $s_amount, $c_interval, $chargesAmount, $d_interval, $d_channel, $acct_opening_date, $smsChecker], 'id', $id) : "";
            ($location == "") ? $this->updateManytoMany('borrowers', ['snum', 'fname', 'lname', 'mname', 'email', 'phone', 'gender', 'dob', 'occupation', 'addrs', 'city', 'state', 'zip', 'country', 'lofficer', 'account', 'sbranchid', 'nok', 'nok_rela', 'nok_phone', 'acct_status', 'opt_option', 'username', 'overdraft', 'reg_type', 'acct_type', 's_contribution_interval', 'savings_amount', 'charge_interval', 'chargesAmount', 'disbursement_interval', 'disbursement_channel', 'acct_opening_date', 'sms_checker'], [$snum, $fname, $lname, $mname, $email, $phone, $gender, $dob, $occupation, $addrs, $city, $state, $zip, $country, $lofficer, $account, $branchid, $nok, $nok_rela, $nok_phone, $acct_status, $otp, $username, $overdraft, $reg_type, $acct_type, $s_interval, $s_amount, $c_interval, $chargesAmount, $d_interval, $d_channel, $acct_opening_date, $smsChecker], 'id', $id) : "";

            return "Success";

        }

    }

    //Function to close customer account
    public static function closeCustomerAccount($parameter){

        $clientId = $parameter['clientId'];
        $selector = $parameter['selector'];
        $number = count($selector);

        if($selector == ''){

            return -1; //No record was selected;

        }else{

            for($i=0; $i<$number; $i++){

                $todays_date = date('Y-m-d');
                $next_termination_date = date('Y-m-d', strtotime('+35 day', strtotime($todays_date)));

                $this->updateManytoMany('borrowers', ['acct_status', 'last_withdraw_date'], ['Closed', $next_termination_date], 'id', $selector[$i]);

            }
            return "Success"; //Account Closed Successfully

        }

    }

    //Function to activate autocharge
    public static function activateAutoCharge($parameter){

        $clientId = $parameter['clientId'];
        $selector = $parameter['selector'];
        $number = count($selector);

        if($selector == ''){

            return -1; //No record was selected;

        }else{

            for($i=0; $i<$number; $i++){

                $fetchBorrower = $this->fetchWithOneParam('borrowers', 'id', $selector[$i]);
                $chargeInterval = ($fetchBorrower['charge_interval'] == "weekly" ? "week" : ($fetchBorrower['charge_interval'] == "monthly" ? "month" : "year"));

                $todays_date = date('Y-m-d');
				$next_charge_date = date('Y-m-d', strtotime('+1 '.$chargeInterval, strtotime($todays_date)));

                $this->updateManytoMany('borrowers', ['auto_charge_status', 'next_charge_date'], ['Active', $next_charge_date], 'id', $selector[$i]);

            }
            return "Success"; //Auto-charge Activated Successfully

        }

    }

    //Function to activate auto - disbursement
    public static function activateAutoDisbursement($parameter){

        $clientId = $parameter['clientId'];
        $selector = $parameter['selector'];
        $number = count($selector);

        if($selector == ''){

            return -1; //No record was selected;

        }else{

            for($i=0; $i<$number; $i++){

                $fetchBorrower = $this->fetchWithOneParam('borrowers', 'id', $selector[$i]);
                $disburseInterval = ($fetchBorrower['disbursement_interval'] == "weekly" ? "week" : ($fetchBorrower['disbursement_interval'] == "monthly" ? "month" : "year"));

                $todays_date = date('Y-m-d');
				$next_disbursement_date = date('Y-m-d', strtotime('+1 '.$disburseInterval, strtotime($todays_date)));

                $this->updateManytoMany('borrowers', ['auto_disbursement_status', 'next_disbursement_date'], ['Active', $next_disbursement_date], 'id', $selector[$i]);

            }
            return "Success"; //Auto-disbursement Activated Successfully

        }

    }

    //Function to  disable auto-charge
    public static function disableAutoCharge($parameter){

        $clientId = $parameter['clientId'];
        $selector = $parameter['selector'];
        $number = count($selector);

        if($selector == ''){

            return -1; //No record was selected;

        }else{

            for($i=0; $i<$number; $i++){

                $this->updateWithOneParam('borrowers', 'auto_charge_status', 'NotActive', 'id', $selector[$i]);

            }
            return "Success"; //Auto-charge Deactivated Successfully

        }

    }

    //Function to disable auto-disbursement
    public static function disableAutoDisbursement($parameter){

        $clientId = $parameter['clientId'];
        $selector = $parameter['selector'];
        $number = count($selector);

        if($selector == ''){

            return -1; //No record was selected;

        }else{

            for($i=0; $i<$number; $i++){

                $this->updateWithOneParam('borrowers', 'auto_disbursement_status', 'NotActive', 'id', $selector[$i]);

            }
            return "Success"; //Auto-disbursement Deactivated Successfully

        }

    }

    //Function to fetch all pending customer records
    public static function viewAllPendingCustomers($parameter, $iCustRecords, $bCustRecords, $updateCustinfo, $iuid, $isbranchid){

        $column = array('id', 'action', 'sNo', 'branch', 'staffName', 'accountId', 'name', 'acctType', 'regType', 'phone', 'Last Update', 'Opening Date', 'status');

        //Data table Parameter
        $searchValue = $parameter['searchValue']; //$_POST['search']['value']
        $draw = $parameter['draw']; //$_POST['draw']
        $order = $parameter['order']; //$_POST['order']
        $orderColumn = $parameter['orderColumn']; //$_POST['order']['0']['column']
        $start = $parameter['start']; //$_POST['start']
        $length = $parameter['length']; //$_POST['length']

        $startDate = ($parameter['startDate'] != "") ? $parameter['startDate'] : "0000-00-00";
        $endDate = ($parameter['endDate'] != "") ? $parameter['endDate'] : date("Y-m-d");
        $byCustomer = $parameter['byCustomer'];
        $filterBy = $parameter['filterBy'];
        $clientId = $parameter['clientId'];

        $query = " ";

        if($startDate != "" && $endDate != "" && $byCustomer == "" && $filterBy != ""){
    
            ($iCustRecords != "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE (acct_opening_date BETWEEN '$startDate' AND '$endDate'  AND branchid = '$clientId' AND status = 'queue' AND (sbranchid = '$filterBy' OR lofficer = '$filterBy'))
             " : "";
             
            ($iCustRecords === "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE (acct_opening_date BETWEEN '$startDate' AND '$endDate'  AND branchid = '$clientId' AND lofficer = '$iuid' AND status = 'queue')
             " : "";
             
            ($iCustRecords === "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE (acct_opening_date BETWEEN '$startDate' AND '$endDate'  AND branchid = '$clientId' AND sbranchid = '$isbranchid' AND status = 'queue')
             " : "";
            
        }
        
        if($startDate == "" && $endDate == "" && $filterBy == "" && $byCustomer != ""){
            
            ($iCustRecords != "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE account =  '$byCustomer' AND branchid = '$clientId' AND status = 'queue'
             " : "";
             
            ($iCustRecords === "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE account =  '$byCustomer' AND branchid = '$clientId' AND lofficer = '$iuid' AND status = 'queue'
             " : "";
             
            ($iCustRecords != "1" && $bCustRecords === "1") ? $query .= "SELECT * FROM borrowers 
            WHERE account =  '$byCustomer' AND branchid = '$clientId' AND sbranchid = '$isbranchid' AND status = 'queue'
             " : "";
            
        }
        
        
        if($startDate == "" && $endDate == "" && $filterBy != "All" && $filterBy != "" && $byCustomer == ""){
            
            ($iCustRecords != "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE branchid = '$clientId' AND status = 'queue' AND (sbranchid = '$filterBy' OR lofficer = '$filterBy')
             " : "";
             
            ($iCustRecords === "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE branchid = '$clientId' AND lofficer = '$iuid' AND status = 'queue'
             " : "";
             
            ($iCustRecords != "1" && $bCustRecords === "1") ? $query .= "SELECT * FROM borrowers 
            WHERE branchid = '$clientId' AND sbranchid = '$isbranchid' AND status = 'queue'
             " : "";
            
        }
        
        
        if($startDate == "" && $endDate == "" && $filterBy == "All" && $byCustomer == ""){
            
            ($iCustRecords != "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE branchid = '$clientId' AND status = 'queue'
             " : "";
             
            ($iCustRecords === "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE branchid = '$clientId' AND lofficer = '$iuid' AND status = 'queue'
             " : "";
             
            ($iCustRecords != "1" && $bCustRecords === "1") ? $query .= "SELECT * FROM borrowers 
            WHERE branchid = '$clientId' AND sbranchid = '$isbranchid' AND status = 'queue'
             " : "";
            
        }
        
        
        if($startDate != "" && $endDate != "" && $filterBy == "" && $byCustomer == ""){
            
            ($iCustRecords != "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE acct_opening_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$clientId' AND status = 'queue'
             " : "";
             
            ($iCustRecords === "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE acct_opening_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$clientId' AND lofficer = '$iuid' AND status = 'queue'
             " : "";
             
            ($iCustRecords === "1" && $bCustRecords != "1") ? $query .= "SELECT * FROM borrowers 
            WHERE acct_opening_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$clientId' AND sbranchid = '$isbranchid' AND status = 'queue'
             " : "";
            
        }

        if($searchValue != ''){

            $query .= "SELECT * FROM borrowers
            WHERE id LIKE '%'.$searchValue.'%' 
            OR account LIKE '%'.$searchValue.'%' 
            OR snum LIKE '%'.$searchValue.'%' 
            OR status LIKE '%'.$searchValue.'%' 
            OR phone LIKE '%'.$searchValue.'%' 
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
            $mysbranch = $row['sbranchid'];
            $myofficer = $row['lofficer'];
            $acct_status = $row['acct_status'];

            $selectBranch = $this->fetchWithOneParam('branches', 'branchid', $mysbranch);
            $selectUser = $this->fetchWithOneParam('user', 'id', $myofficer);

            $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."' ".(($bal >= 1 || $wbal >= 1) ? 'disabled' : '').">";
            $sub_array[] = ($acct_status == "Closed") ? "---" : "<div class='btn-group'>
                                <div class='btn-group'>
                                <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                                    <span class='caret'></span>
                                </button>
                                <ul class='dropdown-menu'>
                                ".(($updateCustinfo == '1') ? "<li><p><a href='add_to_borrower_list.php?id=".$row['id']."&&mid=".base64_encode('403')."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-save'>&nbsp;Update Account</i></a></p></li>" : '')."
                                </ul>
                                </div>
                            </div>";
            $sub_array[] = ($row['snum'] == "") ? "null" : $row['snum'];
            $sub_array[] = ($mysbranch == "") ? '<b>Head Office</b>' : '<b>'.$selectBranch['bname'].'</b>';
            $sub_array[] = ($myofficer == "") ? 'NIL' : $selectUser['name'].' '.$selectUser['fname'];
            $sub_array[] = $row['account'];
            $sub_array[] = $row['fname'].' '.$row['lname'];
            $sub_array[] = ($row['acct_type'] == "") ? "NIL" : $row['acct_type'];
            $sub_array[] = $row['reg_type'];
            $sub_array[] = $row['phone'];
            $sub_array[] = $this->db->convertDateTime($row['date_time']);
            $sub_array[] = $row['acct_opening_date'];
            $sub_array[] = ($acct_status == "Activated" ? "<span class='label bg-blue'>Active</span>" : ($acct_status == "Closed" ? "<span class='label bg-red'>Closed</span>" : "<span class='label bg-orange'>Pending</span>"));
            $data[] = $sub_array;

        }

        ($iCustRecords != "1" && $bCustRecords != "1") ? $query2 = "SELECT * FROM borrowers WHERE branchid = '$clientId' AND status = 'queue'" : "";
        ($iCustRecords === "1" && $bCustRecords != "1") ? $query2 = "SELECT * FROM borrowers WHERE branchid = '$clientId' AND lofficer = '$iuid' AND status = 'queue'" : "";
        ($iCustRecords != "1" && $bCustRecords === "1") ? $query2 = "SELECT * FROM borrowers WHERE branchid = '$clientId' AND sbranchid = '$isbranchid' AND status = 'queue'" : "";
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
    public static function approveCustomer($parameter){

        $clientId = $parameter['clientId'];
        $selector = $parameter['selector'];
        $number = count($selector);

        if($selector == ''){

            return -1; //No record was selected;

        }elseif($number >= 1){

            for($i=0; $i<$number; $i++){

                $fetchCust = $this->fetchWithOneParam('borrowers', 'id', $selector[$i]);
                $email = $fetchCust['email'];
                $myAccountNumber = ($fetchCust['virtual_acctno'] == "") ? "----" : $fetchCust['virtual_acctno'];
				$account = $fetchCust['account'];
				$fname = $fetchCust['name'];
				$username = $fetchCust['username'];
				$password = $fetchCust['password'];

                $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $url = $protocol . $_SERVER['HTTP_HOST']."/?acn=".$account;
                $id = rand(1000000,10000000);
                $shorturl = base_convert($id,20,36);

                //Insert Activation Code
                $shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/?activation_key=' . $shorturl;
                $shortenedurl1 = $protocol . $_SERVER['HTTP_HOST'] . '/?deactivation_key=' . $shorturl;
                $queryActivationCode = "INSERT INTO activate_member(url, shorturl, attempt, acn) VALUES(?, ?, ?, ?)";
                $this->db->insertActivationCode($queryActivationCode, $url, $shorturl, 'No', $account);

                ($email == "") ? "" : Notifier::customerRegEmailNotifier($email, $fname, $shortenedurl, $username, $password, $account, $myAccountNumber, $shortenedurl1);
                $this->updateManytoMany('borrowers', ['acct_status', 'status'], ['Not-Activated', 'Pending'], 'id', $selector[$i]);

            }
            return "Success"; //Registration Approved Successfully

        }else{

            return -1; //No record was selected;

        }

    }


    //Function to disapproved pending customer
    public static function disapproveCustomer($parameter){

        $clientId = $parameter['clientId'];
        $selector = $parameter['selector'];
        $number = count($selector);

        if($selector == ''){

            return -1; //No record was selected;

        }elseif($number >= 1){

            for($i=0; $i<$number; $i++){

                $this->updateManytoMany('borrowers', ['acct_status', 'status'], ['Not-Activated', 'Pending'], 'id', $selector[$i]);

            }
            return "Success"; //Registration Disapproved Successfully

        }else{

            return -1; //No record was selected;

        }

    }


}

?>