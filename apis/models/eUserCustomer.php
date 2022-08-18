<?php

class eCustomer extends User {
    
    public function file_get_contents_curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        $err = curl_error($ch);
            
        if($err){
            return "cURL Error #:" . $err;
        }else{
            return $data;
        }
    }

    //EMAIL NOTIFICATION --> Customer Welcome Email on Mobile App
    public function sendEmail($email, $fname, $username, $password, $account, $clientId, $otpCode, $myAccountNumber){

        $result = array();

        //FETCH SYSTEMSET FOR EMAIL DELIVERY CREDENTIALS
        $emailCredentials = $this->db->fetchSystemSet();
        $email_from = $emailCredentials['email_from'];
        $product_name = $emailCredentials['name'];
        $website = $emailCredentials['website'];
        $logo_url = $emailCredentials['logo_url'];
        //$id = rand(1000000,10000000);
        //$shorturl = base_convert($id,20,36);
        //$shortenedurl = 'https://esusu.app/?activation_key=' . $shorturl;
        //$deactivation_url = 'https://esusu.app/?deactivation_key=' . $shorturl;
        $support_email = $emailCredentials['email'];
        $live_chat_url = $emailCredentials['live_chat'];
        $sender_name = $emailCredentials['email_sender_name'];
        $company_address = $emailCredentials['address'];
        $email_token = $emailCredentials['email_token'];
        $brand_color = $emailCredentials['brand_color'];

        $correctdate = date('m/d/Y').' '.(date('h') + 1).':'.date('i A');

        $fetch_emailConfig = $this->db->fetchEmailConfig($clientId);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated

        // Pass the customer's authorisation code, email and amount
        $postdata =  array(
            "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $email_from : $fetch_emailConfig['email_from']),
            "To"            => $email,  //Customer Email Address
            "TemplateId"    => '24253206',
            "TemplateModel" => [
              "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
              "name"              => $fname,  //Customer First Name
              "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $brand_color : $fetch_emailConfig['brand_color']),
              "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $logo_url : $fetch_emailConfig['logo_url']),
              "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $website : $fetch_emailConfig['product_url']),
              "activation_url"    => $otpCode, //Customer Activation Code
              "username"          => $username, //Customer Username
              "password"          => $password, //Customer Password
              "ledger_acno"       => $account,
              "wallet_acno"       => ($myAccountNumber == "") ? '---' : $myAccountNumber,
              "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $support_email : $fetch_emailConfig['support_email']),
              "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $live_chat_url : $fetch_emailConfig['live_chat']),
              "sender_name"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $sender_name : $fetch_emailConfig['email_sender_name']),
              "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
              "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $company_address : $fetch_emailConfig['company_address'])
            ]
          );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
        'X-Postmark-Server-Token: '.$email_token
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $request = curl_exec($ch);

        curl_close ($ch);
        if($request) {
            $result = json_decode($request, true);
            if($result['Message'] == "OK")
            {
                //echo "Email Sent Successfully";
            }else{
                //echo "Error Code: ".$result['ErrorCode'];
            }
        }

    }


    //Password Reset
    public function passwordReset($email, $name, $username, $password, $ipAddress){
            
        $result = array();

        //FETCH SYSTEMSET FOR EMAIL DELIVERY CREDENTIALS
        $emailCredentials = $this->db->fetchSystemSet();
        $email_from = $emailCredentials['email_from'];
        $product_name = $emailCredentials['name'];
        $website = $emailCredentials['website'];
        $logo_url = $emailCredentials['logo_url'];
        $support_email = $emailCredentials['email'];
        $live_chat_url = $emailCredentials['live_chat'];
        $sender_name = $emailCredentials['email_sender_name'];
        $company_address = $emailCredentials['address'];
        $email_token = $emailCredentials['email_token'];
        $brand_color = $emailCredentials['brand_color'];

        $correctdate = date('m/d/Y').' '.(date('h') + 1).':'.date('i A');

        $fetch_emailConfig = $this->db->fetchEmailConfig($clientId);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
        
        $postdata =  array(
        "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $email_from : $fetch_emailConfig['email_from']),
        "To"            => $email,  //Customer Email Address
        "TemplateId"    => '16144716',
        "TemplateModel" => [
            "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $brand_color : $fetch_emailConfig['brand_color']),
            "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $logo_url : $fetch_emailConfig['logo_url']),
            "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $website : $fetch_emailConfig['product_url']),
            "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
            "name"              => $name,  //Customer First Name
            "username"          => $username, //Customer Username
            "password"          => $password, //Customer Password
            "ip_address"        => $ipAddress,
            "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $support_email : $fetch_emailConfig['support_email']),
            "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $live_chat_url : $fetch_emailConfig['live_chat']),
            "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
            "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $company_address : $fetch_emailConfig['company_address'])
            ]
            );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
        'X-Postmark-Server-Token: '.$email_token
        ];
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $request = curl_exec($ch);
        
        curl_close ($ch);
        if ($request) {
            $result = json_decode($request, true);
            if($result['Message'] == "OK")
            {
                //echo "Email Sent Successfully";
            }else{
                //echo "Error Code: ".$result['ErrorCode'];
            }
        }
        
    }


    //SMS NOTIFICATION
    public function instGeneralAlert($ozeki_password, $ozeki_url, $debitWallet, $sender, $phone, $msg, $clientId, $refid, $sms_charges, $currency, $details, $reg_staffid, $mywallet_balance){
        
        $url = 'action=send-sms';
        $url.= '&api_key='.$ozeki_password;
        $url.= '&to='.urlencode($phone);
        $url.= '&from='.urlencode($sender);
        $url.= '&sms='.urlencode($msg);
      
        //Capture complete processing URL
        $urltouse = $ozeki_url.$url;
    
        //Open the URL to send the message
        $response = $this->file_get_contents_curl($urltouse);

        //Confirm the response from Gateway
        $getResponse = json_decode($response, true);
        
        $okStatus = $getResponse['code'];
        
        if($okStatus == "ok"){

            $date_time = date("Y-m-d h:i:s");

            //UPDATE INSTITUTION/AGENT WALLET AND KEEP RECORD IN WALLET HISTORY
            ($debitWallet == "Yes") ? $this->updateInstitutionWallet($mywallet_balance, $clientId) : "";
            ($debitWallet == "Yes") ? $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
            ($debitWallet == "Yes") ? $this->db->insertWalletHistory($query, $clientId, $refid, $phone, '', $sms_charges, 'Debit', $currency, 'Charges', $details, 'successful', $date_time, $reg_staffid, $mywallet_balance) : "";

        }

    }


    /** ENDPOINT URL FOR CUSTOMER REGISTRATION:
    * 
     * {
	*   "fname" : "Akingbade",
	*   "lname" : "Tomos",
	*   "email" : "akingbade@gm.com",
	*   "phone" : "081022222222",
	*   "gender" : "Male",
	*   "dob" : "1960-12-11",
	*   "username" : "testing123",
    *   "clientID": "INST1228288"
    *  }
     * 
     * */
    public function insertPcCustomer($parameter, $clientId, $companyName, $iwallet_balance) {

        if(isset($parameter->fname) && isset($parameter->lname) && isset($parameter->phone) && isset($parameter->gender) && isset($parameter->dob) && isset($parameter->username) && isset($parameter->clientID)) {

            $snum = "";
            $fname = $parameter->fname;
            $lname = $parameter->lname;
            $mname = "";
            $email = $parameter->email;
            $phone_no = $parameter->phone;
            $gender = $parameter->gender;
            $dob = $parameter->dob;
            $accountName = $lname.' '.$fname;
            $addrs = "";
            $city = "";
            $state = "";
            $country = "";
            $username = $parameter->username;
            $password = substr((uniqid(rand(),1)),4,8);
            $community_role = "Borrower";
            $ledger_bal = "0.0";
            $target_bal = "0.0";
            $invst_bal = "0.0";
            $loan_bal = "0.0";
            $asset_bal = "0.0";
            $last_with_date = "0000-00-00";
            $status = "Completed";
            $otp_option = "No";
            $wallet_bal = "0.0";
            $over_draft = "No";
            $card_id = "NULL";
            $card_reg = "No";
            $card_issurer = "NULL";
            $tpin = substr((uniqid(rand(),1)),3,4);
            $gname = "Individual";
            $acct_status = "Not-Activated";
            $date_time = date("Y-m-d h:i:s");
            $acct_opening_date = date("Y-m-d");
            $instID = $parameter->clientID;
            $userBvn = "";
            $otpCode = substr((uniqid(rand(),1)),3,6);

            $resultsInfo = $this->db->executeCall($clientId);
            //SENDER ID
            $sysabb = ($resultsInfo['sender_id'] == "") ? "esusuafrica" : $resultsInfo['sender_id'];
            $currency = $resultsInfo['currency'];

            $searchMemSet = $this->db->fetchMemberSettings($clientId);
            $vaProvider = $searchMemSet['va_provider'];
            $acct_number = $searchMemSet['dedicated_ledgerAcctNo_prefix'].mt_rand(10000000,99999999);

            $searchEmailConfig = $this->db->fetchEmailSettings($clientId);
            $customDomain = ($searchEmailConfig['product_url'] == "") ? "https://esusu.app/".$sysabb : $searchEmailConfig['product_url'];
            $countSenderString = strlen($sysabb) + 1;
            $productURL = substr($customDomain, 0, -$countSenderString);
            $url = $productURL."/?acn=".$acct_number;
            $id = time();
            $shorturl = base_convert($id,20,36);
            $smsChecker = "Yes";
            $refid = uniqid("EA-custReg-").time();

            $verifyCustomer = $this->db->verifyCustomer3($username);
            $verifyCustomer2 = $this->db->verifyCustomer4($username, $clientId);
            
            $verifySMS_Provider = $this->fetchSMSGW1($clientId);
            $verifySMS_Provider1 = $this->fetchSMSGW2("Activated");
            $fetchSMS_Provider = ($verifySMS_Provider === 0) ? $verifySMS_Provider1 : $verifySMS_Provider;
            $ozeki_password = $fetchSMS_Provider['password'];
            $ozeki_url = $fetchSMS_Provider['api'];
            $debitWallet = ($verifySMS_Provider === 0) ? "Yes" : "No";
            $sendSmsStatus = ($phone_no == "") ? "0" : "1";
            $sendEmailStatus = ($email == "") ? "0" : "1";

            $confirmUserActivation = $this->db->verifyCustomerActivation($username,$clientId);

            if($fname == "" || $lname == "" || $phone_no == "" || $gender == "" || $dob == "" || $username == "" || $instID == ""){

                return -3;

            }elseif($verifyCustomer === 1 && $verifyCustomer2 === 0){

                return -2;

            }elseif($verifyCustomer2 === 1 && $confirmUserActivation === 0){

                return -5;

            }elseif($instID != $clientId){

                return -4;

            }
            else{

                $myAccountReference = uniqid("EAVA-").time();

                //GENERATE PROVIDUS ACCOUNT NUMBER
                $generateAcct = $this->virtualAccountCreation($accountName,$userBvn,$email,$myAccountReference,$clientId,$acct_number,"");
                $myAccountName = ($generateAcct['accountName'] == "") ? "" : $generateAcct['accountName'];
                $myAccountNumber = ($generateAcct['accountNumber'] == "") ? "" : $generateAcct['accountNumber'];
                $myBankName = ($generateAcct['bankName'] == "") ? "" : $generateAcct['bankName'];
                $date_time = date("Y-m-d h:i:s");

                $sms = "$sysabb>>>Welcome $fname! Account ID: $acct_number, Wallet Account No: $myAccountNumber, Bank Name: $myBankName, Username: $username, Password: $password, Transaction Pin: $tpin, OTP: $otpCode. Login at $productURL/$sysabb";
                $details = 'SMS Content: '.$sms;

                //SMS CONTENT PRICE CALCULATION PER PAGE
                $max_per_page = 153;
                $sms_length = strlen($sms);
                $calc_length = ceil($sms_length / $max_per_page);
                $cust_charges = $resultsInfo['cust_mfee'];
                $sms_charges = $calc_length * $cust_charges;
                $mywallet_balance = $iwallet_balance - $sms_charges;

                //INSERT CUSTOMER RECORD
                $myQuery = "INSERT INTO borrowers (snum, fname, lname, mname, email, phone, gender, dob, addrs, city, state, country, username, password, community_role, account, balance, target_savings_bal, investment_bal, loan_balance, asset_acquisition_bal, last_withdraw_date, status, lofficer, opt_option, currency, wallet_balance, overdraft, card_id, card_reg, card_issurer, tpin, reg_type, branchid, sbranchid, acct_status, date_time, acct_opening_date, sms_checker, sendSMS, sendEmail, virtual_number, virtual_acctno, bankname) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertCustomerMo($myQuery, $snum, $fname, $lname, $mname, $email, $phone_no, $gender, $dob, $addrs, $city, $state, $country, $username, $password, $community_role, $acct_number, $ledger_bal, $target_bal, $invst_bal, $loan_bal, $asset_bal, $last_with_date, $status, "", $otp_option, $currency, $wallet_bal, $over_draft, $card_id, $card_reg, $card_issurer, $tpin, $gname, $clientId, "", $acct_status, $date_time, $acct_opening_date, $smsChecker, $sendSmsStatus, $sendEmailStatus, $myAccountReference, $myAccountNumber, $myBankName);

                //LOG ACTIVATION RECORDS
                $query2 = "INSERT INTO activate_member2 (url, shorturl, attempt, acn) VALUES(?, ?, ?, ?)";
                $this->db->insertActivationCode($query2, $url, $otpCode, 'No', $acct_number);

                //SMS NOTIFCATION
                ($debitWallet == "No" ? $this->instGeneralAlert($ozeki_password, $ozeki_url, $debitWallet, $sysabb, $phone_no, $sms, $clientId, $refid, $sms_charges, $currency, $details, '', $mywallet_balance) : ($debitWallet == "Yes" && $sms_charges <= $iwallet_balance ? $this->instGeneralAlert($ozeki_password, $ozeki_url, $debitWallet, $sysabb, $phone_no, $sms, $clientId, $refid, $sms_charges, $currency, $details, '', $mywallet_balance) : ""));

                //EMAIL NOTIFICATION
                ($email != "") ? $this->sendEmail($email, $fname, $username, $password, $acct_number, $clientId, $otpCode, $myAccountNumber) : "";

                return [
                    
                    "responseCode"=> "00",
                    
                    "reg_status" => "success",

                    "message" => "Account Registered Successfully",

                    "account_details" => [

                        "accountNumber" => $acct_number,

                        "firstName" => $fname,

                        "lastName" => $lname,

                        "emailAddress" => $email,

                        "phoneNumber" => $phone_no,

                        "dateOfBirth" => $dob,

                        "gender" => $gender,

                        "userName" => $parameter->username,
                        
                        "companyName" => $companyName,

                        "accountOpeningDate" => $acct_opening_date,

                        "walletAccount" => [

                            "accountRef" => $myAccountReference,
                            
                            "accountNumber" => $myAccountNumber,

                            "accountName" => $myAccountName,

                            "bankName" => $myBankName

                        ],

                        "regDateTime" => $date_time,

                    ]

                ];

            }

        }else{
            return -1;
        }

    }


    //ENDPOINT URL FOR Customer AccountInfo:
    public function accountInfo($account_no, $clientId, $companyName) {
        
        //FOR CUSTOMER/BORROWER
        $customer = $this->fetchCustomerByAcctId($account_no, $clientId);

        if($customer === 0){

            return -1;
                    
        }else{

            return [

                "resposeCode"=> "00",

                "accountID"=> $account_no,

                "serialNo"=> $customer['snum'],

                "firstName"=> $customer['fname'],

                "lastName"=> $customer['lname'],

                "emailAddress"=> $customer['email'],

                "phoneNumber"=> $customer['phone'],

                "gender"=> $customer['gender'],

                "ledgerBalance"=> $customer['balance'],

                "targetSavingsBalance"=> $customer['target_savings_bal'],

                "investmentBalnce"=> $customer['investment_bal'],

                "loanBalance"=> $customer['loan_balance'],

                "assetAcquisitionBalance"=> $customer['asset_acquisition_bal'],

                "walletBalance"=> $customer['wallet_balance'],

                "companyName"=> $companyName,

                "message"=> "Info Fetched Successfully"

            ];

        }

    }


    /** ENDPOINT URL TO ACTIVATE Customer ACCOUNT:
    * 
     * {
	*   "activationOtp" : "123456",
    *   "clientID": "INST1228288"
    *  }
     * 
     * */
    public function activateCustomerAcct($parameter, $clientId, $companyName) {
        
        if(isset($parameter->activationOtp) && isset($parameter->clientID)) {

            $otpCode = $parameter->activationOtp;
            $instID = $parameter->clientID;

            //FOR CUSTOMER/BORROWER
            $customer = $this->fetchCustomerActivationOtp($otpCode);
            $webCustomer = $this->fetchCustomerWebActivationOtp($otpCode);

            if($otpCode === "" || $instID === ""){

                return -1;

            }elseif($customer === 0 && $webCustomer === 0){

                return -2;
                        
            }elseif($instID != $clientId){

                return -3;

            }else{

                //Update Customer Status
                $this->updateBorrowerStatus("Completed", "Activated", $customer['acn'], $clientId);

                //Update Activation log status
                ($customer != 0 && $webCustomer === 0) ? $this->updateCustomerActivationLog($otpCode, $customer['acn'], "Yes") : "";
                ($customer === 0 && $webCustomer != 0) ? $this->updateCustomerWebActivationLog($otpCode, $webCustomer['acn'], "Yes") : "";

                return [

                    "resposeCode"=> "00",

                    "message"=> "Account Activated Successfully"

                ];

            }

        }else{

            return -4;

        }

    }


    /** ENDPOINT URL TO CHANGE CUSTOMER LOGIN PASSWORD:
    * 
     * {
	*   "ledgerAccountNo" : "1234567890",
    *   "oldPassword" : "123456",
    *   "newPassword" : "ags12334454",
    *   "confirmNewPassword" : "ags12334454",
    *   "clientID": "INST1228288"
    *  }
     * 
     * */
    public function changeCustomerPassword($parameter, $clientId) {
        
        if(isset($parameter->ledgerAccountNo) && isset($parameter->oldPassword) && isset($parameter->newPassword) && isset($parameter->confirmNewPassword) && isset($parameter->clientID)) {

            $ledgerAccountNo = $parameter->ledgerAccountNo;
            $oldPassword = $parameter->oldPassword;
            $newPassword = $parameter->newPassword;
            $confirmNewPassword = $parameter->confirmNewPassword;
            $instID = $parameter->clientID;

            //FOR CUSTOMER/BORROWER
            $customer = $this->fetchCustomerByAcctId($ledgerAccountNo,$clientId);
            $customerVerify = $this->fetchCustomerByAcctIdPasswd($ledgerAccountNo,$oldPassword,$clientId);

            if($ledgerAccountNo === "" || $oldPassword === "" || $newPassword === "" || $confirmNewPassword === "" || $instID === ""){

                return -1;

            }elseif($customer === 0){

                return -2;

            }elseif($customerVerify === 0){

                return -3;

            }elseif($newPassword != $confirmNewPassword){

                return -4;

            }elseif($instID != $clientId){

                return -5;

            }else{

                //Update Customer Password
                $this->updateBorrowerPasswd($newPassword, $ledgerAccountNo, $clientId);

                return [

                    "resposeCode"=> "00",

                    "message"=> "Password Changed Successfully"

                ];

            }

        }else{

            return -6;

        }

    }


    /** ENDPOINT URL TO CHANGE CUSTOMER TRANSACTION PIN:
    * 
     * {
	*   "ledgerAccountNo" : "1234567890",
    *   "oldTPin" : "0000",
    *   "newTPin" : "1234",
    *   "confirmNewTPin" : "1234",
    *   "clientID": "INST1228288"
    *  }
     * 
     * */
    public function changeCustomerTPin($parameter, $clientId) {
        
        if(isset($parameter->ledgerAccountNo) && isset($parameter->oldTPin) && isset($parameter->newTPin) && isset($parameter->confirmNewTPin) && isset($parameter->clientID)) {

            $ledgerAccountNo = $parameter->ledgerAccountNo;
            $oldTPin = $parameter->oldTPin;
            $newTPin = $parameter->newTPin;
            $confirmNewTPin = $parameter->confirmNewTPin;
            $instID = $parameter->clientID;

            //FOR CUSTOMER/BORROWER
            $customer = $this->fetchCustomerByAcctId($ledgerAccountNo,$clientId);
            $customerVerify = $this->fetchCustomerByAcctIdTPin($ledgerAccountNo,$oldTPin,$clientId);

            if($ledgerAccountNo === "" || $oldTPin === "" || $newTPin === "" || $confirmNewTPin === "" || $instID === ""){

                return -1;

            }elseif($customer === 0){

                return -2;

            }elseif($customerVerify === 0){

                return -3;

            }elseif($newTPin != $confirmNewTPin){

                return -4;

            }elseif(strlen($newTPin) > 4 || strlen($confirmNewTPin) > 4){

                return -7;

            }elseif($instID != $clientId){

                return -5;

            }else{

                //Update Customer Pin
                $this->updateBorrowerTPin($newTPin, $ledgerAccountNo, $clientId);

                return [

                    "resposeCode"=> "00",

                    "message"=> "Pin Changed Successfully"

                ];

            }

        }else{

            return -6;

        }

    }


    /** ENDPOINT URL TO RESET CUSTOMER PASSWORD:
    * 
     * {
	*   "userID" : "ledgerAcctNo | username",
    *   "clientID": "INST1228288"
    *  }
     * 
     * */
    public function resetCustomerPassword($parameter, $clientId, $iwallet_balance, $trans_charges) {
        
        if(isset($parameter->userID) && isset($parameter->clientID)) {

            $userID = $parameter->userID;
            $instID = $parameter->clientID;
            $newPassword = substr((uniqid(rand(),1)),3,6);
            $ip_address = $_SERVER['REMOTE_ADDR'];

            //FOR CUSTOMER/BORROWER
            $customer = $this->fetchCustomerByAcctId($userID,$clientId);
            $lname = $customer['lname'];
            $username = $customer['username'];
            $email = $customer['email'];
            $phone_no = $customer['phone'];

            $resultsInfo = $this->db->executeCall($clientId);
            //SENDER ID
            $sysabb = ($resultsInfo['sender_id'] == "") ? "esusuafrica" : $resultsInfo['sender_id'];
            $currency = $resultsInfo['currency'];
            $refid = uniqid("EA-smsCharges-").time();

            //CHECK SMS CONFIGURATION
            $verifySMS_Provider = $this->fetchSMSGW1($clientId);
            $verifySMS_Provider1 = $this->fetchSMSGW2("Activated");
            $fetchSMS_Provider = ($verifySMS_Provider === 0) ? $verifySMS_Provider1 : $verifySMS_Provider;
            $ozeki_password = $fetchSMS_Provider['password'];
            $ozeki_url = $fetchSMS_Provider['api'];
            $debitWallet = ($verifySMS_Provider === 0) ? "Yes" : "No";

            if($ledgerAccountNo === "" || $instID === ""){

                return -1;

            }elseif($customer === 0){

                return -2;

            }elseif($instID != $clientId){

                return -3;

            }else{

                $sms = "$sysabb>>>Dear $lname, Your new password is: $newPassword";
                $details = 'SMS Content: '.$sms;

                //SMS CONTENT PRICE CALCULATION PER PAGE
                $max_per_page = 153;
                $sms_length = strlen($sms);
                $calc_length = ceil($sms_length / $max_per_page);
                $cust_charges = $trans_charges;
                $sms_charges = $calc_length * $cust_charges;
                $mywallet_balance = $iwallet_balance - $sms_charges;

                //Update Customer Password
                $this->updateBorrowerPasswd($newPassword, $userID, $clientId);

                //Send SMS Notification
                ($debitWallet == "No" && $phone_no != '' ? $this->instGeneralAlert($ozeki_password, $ozeki_url, $debitWallet, $sysabb, $phone_no, $sms, $clientId, $refid, $sms_charges, $currency, $details, '', $mywallet_balance) : ($debitWallet == "Yes" && $sms_charges <= $iwallet_balance && $phone_no != '' ? $this->instGeneralAlert($ozeki_password, $ozeki_url, $debitWallet, $sysabb, $phone_no, $sms, $clientId, $refid, $sms_charges, $currency, $details, '', $mywallet_balance) : ""));

                //Send Email Notification
                ($email != "") ? $this->passwordReset($email, $lname, $username, $newPassword, $ip_address) : "";

                return [

                    "resposeCode"=> "00",

                    "message"=> "Password Reset Successfully."

                ];

            }

        }else{

            return -4;

        }

    }



}