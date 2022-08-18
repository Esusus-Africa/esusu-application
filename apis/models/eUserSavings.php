<?php

class eSavings extends User {

    // Function to check string starting 
    // with given substring 
    public function startsWith($string, $startString) 
    { 
        $len = strlen($startString); 
        return (substr($string, 0, $len) === $startString); 
    }

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
    

    //SMS NOTIFICATION
    public function instGeneralAlert($sender, $phone, $msg, $institution_id){
        
        //GET GATEWAY INFO
        $verifySMS_Provider = $this->fetchSMSGW1($institution_id);
        $verifySMS_Provider1 = $this->fetchSMSGW2("Activated");
        $fetchSMS_Provider = ($verifySMS_Provider === 0) ? $verifySMS_Provider1 : $verifySMS_Provider;
        $ozeki_password = $fetchSMS_Provider['password'];
        $ozeki_url = $fetchSMS_Provider['api'];
        $debitWallet = ($verifySMS_Provider === 0) ? "Yes" : "No";

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
        
    }

    //SAVINGS EMAIL NOTIFICATION
    public function sendEmail($email, $transactionType, $txid, $fname, $lname, $p_type, $companyName, $account, $currency, $amt, $ledger_bal, $clientId){

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
        
        // Pass the customer's authorisation code, email and amount
        $postdata =  array(
            "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $email_from : $fetch_emailConfig['email_from']),
            "To"            => $email,  //Borrower Email Address
            "TemplateId"    => '9688434',
            "TemplateModel" => [
              "ttype"             => $transactionType,
              "txid"              => $txid, //Transaction ID
              "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $brand_color : $fetch_emailConfig['brand_color']),
              "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $logo_url : $fetch_emailConfig['logo_url']),
              "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $website : $fetch_emailConfig['product_url']),
              "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
              "name"              => $fname,  //Borrower username
              "trans_date"        => $correctdate, //Transaction date
              "ptype"             => $p_type,
              "platform_name"     => $companyName, //Platform Name
              "account_number"    => $account, //Borrower Account Number
              "acct_name"         => $lname.' '.$fname, //Borrower Full Name
              "amount"            => $currency.number_format($amt,2,'.',','), //Amount Withdrawn
              "legal_balance"     => $currency.number_format($ledger_bal,2,'.',','), //Ledger Balance
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


    //New Payment for Product Subscription
    public function productPaymentNotifier($creatorEmail, $converted_date, $new_reference, $customer, $myfullname, $real_subscription_code, $plancode, $categories, $plan_name, $mybank_name, $account_number, $b_name, $plancurrency, $amountpaid){
         
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
        
        // Pass the customer's authorisation code, email and amount
        $postdata = array(
            "From"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $email_from : $fetch_emailConfig['email_from']),
            "To"        => $creatorEmail,  //Receiver Email Address
            "TemplateId"    => '17983915',
            "TemplateModel" => [
                    "trans_date"        => $converted_date,  //Date Time
                    "refid"             => $new_reference,
                    "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $brand_color : $fetch_emailConfig['brand_color']),
                    "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $logo_url : $fetch_emailConfig['logo_url']),
                    "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $website : $fetch_emailConfig['product_url']),
                    "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
                    "customer_id"       => $customer,
                    "customer_name"     => $myfullname,
                    "sub_code"          => $real_subscription_code,
                    "plan_code"         => $plancode,
                    "plan_cat"          => $categories,
                    "plan_name"         => $plan_name,
                    "payer_bank"        => $mybank_name,
                    "payer_acctno"      => $account_number,
                    "payer_acctname"    => $b_name,
                    "amount_paid"       => $plancurrency.number_format($amountpaid,2,'.',','),
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


    //Withdrawal Request Notification
    public function withdrawalRequestNotifier($email, $fullname, $iname, $inst_name, $ptype, $account, $currency, $amount, $bal, $remark, $clientId){
            
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
        
        // Pass the customer's authorisation code, email and amount
        $postdata =  array(
        "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $email_from : $fetch_emailConfig['email_from']),
        "To"            => $email,  //Borrower Email Address
        "TemplateId"    => '16171090',
        "TemplateModel" => [
            "customer_name"   => $fullname,
            "staff_name"      => $iname,
            "brand_color"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $brand_color : $fetch_emailConfig['brand_color']),
            "logo_url"        => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $logo_url : $fetch_emailConfig['logo_url']),
            "product_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $website : $fetch_emailConfig['product_url']),
            "product_name"    => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
            "name"            => $inst_name, //Platform Name
            "trans_date"      => $correctdate, //Transaction date
            "trans_type"      => $ptype,
            "accountid"       => $account,
            "amount"          => $currency.number_format($amount,2,'.',','), //Amount Withdrawn
            "available_bal"   => $currency.number_format($bal,2,'.',','), //Legal Balance (For Deposit & Savings)
            "remarks"         => $remark,
            "support_email"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $support_email : $fetch_emailConfig['support_email']),
            "live_chat_url"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $live_chat_url : $fetch_emailConfig['live_chat']),
            "company_name"    => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
            "company_address" => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $company_address : $fetch_emailConfig['company_address'])
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


    /** ENDPOINT URL TO AUTOMATE SAVINGS ARE:
     * 
     * {
     *  "ledgerAccountNo" : "9830983773",
     *  "title" : "My Ajo",
     *  "purpose" : "Birthday",
	*   "amount" : "1000",
    *   "interval" : "weekly", [daily | weekly | monthly]
    *   "duration" : "4",
    *   "paymentMethod" : "Wallet | Card",
    *   "tPin" : "1234",
    *   "clientID": "INST1228288"
    *  }
     * 
     * */
    public function startSaving($parameter, $clientId, $companyName, $aggrId, $iwallet_balance) {

        if(isset($parameter->ledgerAccountNo) && isset($parameter->title) && isset($parameter->purpose) && isset($parameter->amount) && isset($parameter->interval) && isset($parameter->duration) && isset($parameter->paymentMethod) && isset($parameter->clientID) && isset($parameter->tPin)) {

            $ledgerAccountNo = $parameter->ledgerAccountNo;
            $sTitle = $parameter->title;
            $purpose = $parameter->purpose;
            $amount = preg_replace('/[^0-9.]/', '', $parameter->amount);
            $interval = strtolower($parameter->interval);
            $duration = $parameter->duration;
            $paymentMethod = $parameter->paymentMethod;
            $tPin = $parameter->tPin;
            $instID = $parameter->clientID;
            $real_subscription_code = "uSub".date("dy").time();

            $validateAccountNo = $this->fetchCustomerByAcctId($ledgerAccountNo,$clientId);
            $sbranchid = $validateAccountNo['sbranchid'];
            $accountOfficer = $validateAccountNo['lofficer'];
            $bwallet_balance = $validateAccountNo['wallet_balance'];
            $btarget_balance = $validateAccountNo['target_savings_bal'];
            $bvirtual_acctno = $validateAccountNo['virtual_acctno'];
            $myTPin = $validateAccountNo['tpin'];
            $fname = $validateAccountNo['fname'];
            $lname = $validateAccountNo['lname'];
            $bname = $lname.' '.$fname;
            $email = $validateAccountNo['email'];
            $phone = $validateAccountNo['phone'];
            $smsChecker = $validateAccountNo['sms_checker'];
            $date_time = date("Y-m-d h:i:s");
            $new_reference = $real_subscription_code."|EA".date("dy").time();

            $searchMemSet = $this->db->fetchMemberSettings($clientId);
            $sysabb = ($searchMemSet['sender_id'] == "") ? "esusuafrica" : $searchMemSet['sender_id'];
            $currency = $searchMemSet['currency'];
            $drave_secret_key = $searchMemSet['rave_secret_key'];
            $planName = $sTitle.'_'.substr((uniqid(rand(),1)),3,4);
            $correctdate = date('m/d/Y').' '.(date('h') + 1).':'.date('i A');

            $searchSysset = $this->db->fetchSystemSet();  
            $brave_secret_key = ($drave_secret_key == "") ? $searchSysset['secret_key'] : $drave_secret_key;
            $trans_charges = $searchSysset['fax'];

            if($ledgerAccountNo === "" || $sTitle === "" || $purpose === "" || $amount === "" || $interval === "" || $duration === "" || $paymentMethod === ""){

                return -1;

            }elseif($validateAccountNo === 0){

                return -2;

            }elseif($instID != $clientId){

                return -3;

            }elseif($tPin != $myTPin){

                return -5;

            }elseif($paymentMethod == "Wallet" && $bwallet_balance < $amount){
                
                return -6;
                
            }elseif($paymentMethod == "Card"){

                $searchApi = $this->fetchApi("rave_payment_plan");
                $api_url = $searchApi['api_url'];

                $header = [
                    "Content-Type: application/json",
                    "Authorization: Bearer ".$brave_secret_key
                ];

                $sendData = array('amount'=> $amount,'name'	=> $planName,'interval'	=> $interval,'duration'	=> $duration);

                $response = $this->callAPI("POST", $api_url, json_encode($sendData), $header, 1);
                $rave_generate = json_decode($response, true);

                if($rave_generate['status'] == "success"){

                    //Get the Plan code from Flutterwave API
                    $plan_id = $rave_generate['data']['id'];
                    $plan_code = $rave_generate['data']['plan_token'];

                    //INSERT CUSTOMER RECORD
                    $myQuery = "INSERT INTO target_savings (acn, plan_id, plan_code, plan_name, purpose, amount, currency, savings_interval, duration, interestType, interestRate, txid, status, date_time, mature_date, branchid, companyid, accountOfficer, upfront_payment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $this->db->insertTargetSavings($myQuery, $ledgerAccountNo, $plan_id, $plan_code, $planName, $purpose, $amount, $currency, $interval, $duration, 'Flat', '0', '', 'Pending', $date_time, '', $sbranchid, $clientId, $accountOfficer, 'No');

                    $myQuery2 = "INSERT INTO savings_subscription (companyid, categories, plan_code, new_plancode, plan_id, origin_planid, amount, currency, savings_interval, duration, subscription_code, acn, status, date_time, vendorid, mature_date, amt_plusInterest, reference_no, next_pmt_date, sub_type, rec_type, sub_balance, withdrawal_count, withdrawTime, agentid, withdrawal_status, stop_sub, balanceToImpact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $this->db->insertSavingsSubscription($myQuery2, $clientId, $purpose, $plan_code, $plan_code, $plan_id, $plan_id, $amount, $currency, $interval, $duration, '', $ledgerAccountNo, 'Pending', $date_time, $sbranchid, '', '', '', '', 'Auto', 'Savings', '0', '0', '0', $accountOfficer, 'Yes', 'No', 'ledger');

                    return [

                        "responseCode" => "00",

                        "status" => "Success",

                        "plan_details" => [

                            "planId" => $plan_id,

                            "planCode" => $plan_code,

                            "planName" => $planName,

                            "amount" => $amount,
                            
                            "currency" => $currency,

                            "interval" => $interval,

                            "duration" => $duration,

                            "savingsPurpose" => $purpose,
                            
                            "paymentMethod" => $paymentMethod,

                            "merchantName" => $companyName

                        ]

                    ];

                }

            }elseif($paymentMethod == "Wallet" && $bwallet_balance >= $amount){

                $real_invoice_code = date("dYi").time();
                $plan_id = rand(10000000,99999999);
                $plan_code = uniqid("rpp_").$this->random_strings(7);
                $paymenttype = "Wallet Transfer";
                $real_status = "successful";
                $senderBalance = $bwallet_balance - $amount;
                $myTargetBalAfterSavings = $btarget_balance + $amount;
                $customer = $bvirtual_acctno;
                $mybank_name = "Wallet Transfer";
                $remarks = "Save with SubCode: ".$real_subscription_code.", PlanCode: ".$plan_code.", and PlanID: ".$plan_id." through ".$paymenttype;
                $account_number = $this->db->ccMasking($bvirtual_acctno);
                $b_name = $bname;
                $smsStatus = ($phone == "") ? "0" : "1";
                $notification = ($email == "") ? "0" : "1";
                $checksms = ($smsChecker == "No") ? "0" : "1";

                //Calculate Maturity Period
                $maturity_period = ($interval == "daily" ? 'day' : ($interval == "weekly" ? 'week' : ($interval == "monthly" ? 'month' : 'year')));
                $mature_date = date('Y-m-d h:i:s', strtotime('+'.$duration.' '.$maturity_period, strtotime($date_time)));

                //Calculate Next Payment Date
                $next_pmt_date = date('Y-m-d h:i:s', strtotime('+1 '.$maturity_period, strtotime($date_time)));

                //Calculate Interest
	            $total_output = $amount * $duration;

                $message = "$sysabb>>>CR";
                $message .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
                $message .= " Acc: ".$this->db->ccMasking($ledgerAccountNo)."";
                $message .= " Desc: Automated Savings - | ".$new_reference."";
                $message .= " Time: ".$correctdate."";
                $message .= " Bal: ".$currency.number_format($myTargetBalAfterSavings,2,'.',',')."";

                $max_per_page = 153;
                $sms_length = strlen($message);
                $calc_length = ceil($sms_length / $max_per_page);
                $sms_charges = $calc_length * $trans_charges;
                $mywallet_balance = $iwallet_balance - $sms_charges;

                //DEBIT CUSTOMER WALLET
                $this->updateBorrowerWallet($senderBalance, $ledgerAccountNo, $clientId);

                //INSERT WALLET HISTORY FOR CUSTOMER TARGET SAVINGS DEBIT USING WALLET
                $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertWalletHistory($query, $clientId, $new_reference, 'Deposit for SubCode: '.$real_subscription_code, '', $amount, 'Debit', $currency, 'TARGET_SAVINGS_SUB', $remarks, 'successful', $date_time, $ledgerAccountNo, $senderBalance);

                //UPDATE INSTITUTION BALANCE
                ($smsChecker == "No" || $phone == "" || $sms_charges > $iwallet_balance) ? "" : $this->updateInstitutionWallet($mywallet_balance, $clientId);

                //INSERT CUSTOMER RECORD
                $myQuery = "INSERT INTO target_savings (acn, plan_id, plan_code, plan_name, purpose, amount, currency, savings_interval, duration, interestType, interestRate, txid, status, date_time, mature_date, branchid, companyid, accountOfficer, upfront_payment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertTargetSavings($myQuery, $ledgerAccountNo, $plan_id, $plan_code, $planName, $purpose, $amount, $currency, $interval, $duration, 'Flat', '0', $real_invoice_code, 'Approved', $date_time, $mature_date, $sbranchid, $clientId, $accountOfficer, 'No');

                $myQuery2 = "INSERT INTO savings_subscription (companyid, categories, plan_code, new_plancode, plan_id, origin_planid, amount, currency, savings_interval, duration, subscription_code, acn, status, date_time, vendorid, mature_date, amt_plusInterest, reference_no, next_pmt_date, sub_type, rec_type, sub_balance, withdrawal_count, withdrawTime, agentid, withdrawal_status, stop_sub, balanceToImpact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertSavingsSubscription($myQuery2, $clientId, $purpose, $plan_code, $plan_code, $plan_id, $plan_id, $amount, $currency, $interval, $duration, $real_subscription_code, $ledgerAccountNo, 'Approved', $date_time, $sbranchid, $mature_date, $total_output, $new_reference, $next_pmt_date, 'Manual', 'Savings', $amount, '0', '0', $accountOfficer, 'Yes', 'No', 'ledger');

                $queryDep = "INSERT INTO transaction (txid, t_type, p_type, acctno, transfer_to, fn, ln, email, phone, amount, posted_by, remark, date_time, branchid, sbranchid, currency, aggr_id, balance, status, sendSms, sendEmail, smsChecker, balanceToImpact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertSavings($queryDep, $new_reference, 'Deposit', 'Wallet', $ledgerAccountNo, '---', $fname, $lname, $email, $phone, $amount, $accountOfficer, $remarks, $date_time, $clientId, $sbranchid, $currency, $aggrId, $myTargetBalAfterSavings, 'Approved', $smsStatus, $notification, $checksms, "target");

                //UPDATE CUSTOMER LEDGER BALANCE
                $this->updateBorrowerTargetBal($myTargetBalAfterSavings, $ledgerAccountNo, $clientId);

                //INSERT TRANSACTION CHARGES IN WALLET HISTORY FOR THE AGENT FOR REFERENCE PURPOSE
                $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertWalletHistory($query, $clientId, $new_reference, $phone, '', $sms_charges, 'Debit', $currency, 'Charges', $message, 'successful', $date_time, $accountOfficer, $mywallet_balance);

                //SMS Notification
                ($smsChecker == "No" || $phone == "" || $sms_charges > $iwallet_balance) ? "" : $this->instGeneralAlert($sysabb, $phone, $message, $clientId);

                //Email Notification
                ($email != "") ? $this->sendEmail($email, 'Deposit', $new_reference, $fname, $lname, 'Wallet', $companyName, $ledgerAccountNo, $currency, $amount, $myTargetBalAfterSavings, $clientId) : "";

                return [

                    "responseCode" => "00",

                    "status" => "Success",

                    "plan_details" => [

                        "planId" => $plan_id,

                        "planCode" => $plan_code,

                        "planName" => $planName,

                        "subCode" => $real_subscription_code,

                        "amount" => $amount,

                        "currency" => $currency,

                        "interval" => $interval,

                        "duration" => $duration,

                        "startDate" => $date_time,

                        "maturityDate" => $mature_date,

                        "nextPaymentDate" => $next_pmt_date,

                        "savingsPurpose" => $purpose,

                        "paymentMethod" => $paymentMethod,

                        "merchantName" => $companyName

                    ]

                ];

            }else{

                return -3;

            }

        }else{

            return -4;

        }

    }


    /** ENDPOINT URL TO ACTIVATE SAVINGS SUBSCRIPTION ARE:
     * 
     * {
     *  "referenceId" : "EA-cardAuth-1920bbtytty",
     *  "ledgerAccountNo" : "9830983773",
    *   "redirectUrl" : "https://webhook.site/9d0b00ba-9a69-44fa-a43d-a82c33c36fdc",
    *   "planId" : "10013",
    *   "clientID": "INST1228288"
    *  }
     * 
     * */
    public function activateSavingsSub($parameter, $clientId, $companyName, $companyType) {

        if(isset($parameter->referenceId) && isset($parameter->ledgerAccountNo) && isset($parameter->redirectUrl) && isset($parameter->planId) && isset($parameter->clientID)) {

            $referenceId = $parameter->referenceId;
            $ledgerAccountNo = $parameter->ledgerAccountNo;
            $redirectUrl = $parameter->redirectUrl;
            $planId = $parameter->planId;
            $instID = $parameter->clientID;
            $paymentMethod = "card";

            $validateAccountNo = $this->fetchCustomerByAcctId($ledgerAccountNo,$clientId);
            $fname = $validateAccountNo['fname'];
            $lname = $validateAccountNo['lname'];
            $customerEmail = $validateAccountNo['email'];
            $customerPhone = $validateAccountNo['phone'];
            $customerName = $lname.' '.$fname;

            $searchMemSet = $this->db->fetchMemberSettings($clientId);
            $cname = $companyName;
            $ctype = $companyType;
            $drave_secret_key = $searchMemSet['rave_secret_key'];
            $clogoName = $searchMemSet['logo'];
            $currency = $searchMemSet['currency'];
            $correctdate = date('m/d/Y').' '.(date('h') + 1).':'.date('i A');

            $searchSysset = $this->db->fetchSystemSet();  
            $brave_secret_key = ($drave_secret_key == "") ? $searchSysset['secret_key'] : $drave_secret_key;
            $authCharges = $searchSysset['auth_charges'];

            if($ledgerAccountNo === "" || $referenceId === "" || $redirectUrl === "" || $planId === "" || $instID === ""){

                return -1;

            }elseif($validateAccountNo === 0){

                return -2;

            }elseif($instID != $clientId){

                return -3;

            }else{

                $searchApi = $this->fetchApi("rave_standard_payment");
                $api_url = $searchApi['api_url'];

                $header = [
                    "Content-Type: application/json",
                    "Authorization: Bearer ".$brave_secret_key
                ];

                $sendData = [
                    'tx_ref' => $referenceId,
                    'amount' => $authCharges,
                    'currency' => $currency,
                    'redirect_url' => $redirectUrl,
                    'payment_options' => $paymentMethod,
                    "payment_plan" => $planId,
                    'customer' => [
                        'email' => $customerEmail,
                        'phonenumber' => $customerPhone,
                        'name' => $customerName
                    ],
                    'customizations' => [
                        'title' => $companyName,
                        'description' => $companyType,
                        'logo' => $fileBaseUrl.$clogoName
                    ]
                ];
                
                $response = $this->callAPI("POST", $api_url, json_encode($sendData), $header, 1);
                $rave_generate = json_decode($response, true);

                if($rave_generate['status'] == "success"){

                    $this->updateSavingsSubRef($referenceId, $planId);

                    return [

                        "responseCode" => "00",
    
                        "status" => "Success",

                        "link" => $rave_generate['data']['link']

                    ];

                }else{

                    return [

                        "responseCode" => "01",
    
                        "message" => "Request not successful, try again later",

                    ];

                }

            }

        }else{

            return -4;

        }

    }


    //FETCH CARD TRANSACTION STATUS
    public function fetchCardTransactionStatus($referenceId, $clientId, $companyName, $aggrId, $iwallet_balance) {

        $searchMemSet = $this->db->fetchMemberSettings($clientId);
        $sysabb = ($searchMemSet['sender_id'] == "") ? "esusuafrica" : $searchMemSet['sender_id'];
        $drave_secret_key = $searchMemSet['rave_secret_key'];

        $savingsSub = $this->db->fetchSavingSub($referenceId);
        $ledgerAccountNo = $savingsSub['acn'];
        $amount = $savingsSub['amount'];
        $planId = $savingsSub['plan_id'];
        $plan_code = $savingsSub['new_plancode'];
        $interval = $savingsSub['savings_interval'];
        $duration = $savingsSub['duration'];
        $real_subscription_code = "uSub".date("dy").time();
        $mySavingsStatus = $savingsSub['status'];
        $currency = $savingsSub['currency'];

        $myMobileSavingsTransLog = $this->db->fetchMobileSavingsTransLog($referenceId,$ledgerAccountNo);
        $transactionId = $myMobileSavingsTransLog['transid'];

        $validateAccountNo = $this->fetchCustomerByAcctId($ledgerAccountNo,$clientId);
        $fname = $validateAccountNo['fname'];
        $lname = $validateAccountNo['lname'];
        $customerEmail = $validateAccountNo['email'];
        $customerPhone = $validateAccountNo['phone'];
        $accountOfficer = $validateAccountNo['lofficer'];
        $real_invoice_code = date("dYi").time();
        $remarks = "Automate Deposit with SubCode: ".$real_subscription_code.", PlanCode: ".$plan_code.", and PlanID: ".$planId." through Card Payment";
        $sbranchid = $validateAccountNo['sbranchid'];
        $myTargetBalAfterSavings = $validateAccountNo['target_savings_bal'] + $amount;
        $smsChecker = $validateAccountNo['sms_checker'];
        $smsStatus = ($customerPhone == "") ? "0" : "1";
        $notification = ($customerEmail == "") ? "0" : "1";
        $checksms = ($smsChecker == "No") ? "0" : "1";
        $correctdate = date('m/d/Y').' '.(date('h') + 1).':'.date('i A');

        $searchSysset = $this->db->fetchSystemSet();
        $brave_secret_key = ($drave_secret_key == "") ? $searchSysset['secret_key'] : $drave_secret_key;
        $trans_charges = $searchSysset['fax'];
        
        if($mySavingsStatus == "Approved" || $savingsSub == ""){
            
            return -1;
            
        }else{
            
            $searchApi = $this->fetchApi("rave_verify_transaction");
            $api_url = $searchApi['api_url'].'/'.$transactionId.'/verify';
            
            $header = [
                "Content-Type: application/json",
                "Authorization: Bearer ".$brave_secret_key
            ];

            $response = $this->callAPI("GET", $api_url, false, $header, 2);
            $rave_generate = json_decode($response, true);
            $cardToken = $rave_generate['data']['card']['token'];
            
            $searchApi2 = $this->fetchApi("charge_authorization2");
            $api_url2 = $searchApi2['api_url'];
            $new_reference = uniqid("mTSv-").time();

            $data_array2 = [
                "token" => $cardToken,
                "currency" => $currency,
                "country" => "NG",
                "amount" => $amount,
                "email" => $customerEmail,
                "first_name" => $fname,
                "last_name" => $lname,
                "ip" => $_SERVER['REMOTE_ADDR'],
                "narration" => "Target Savings Payment",
                "tx_ref" => $new_reference,
                "payment_plan" => $planId,
                "preauthorize" => true
            ];

            $response2 = $this->callAPI("POST", $api_url2, json_encode($data_array2), $header, 1);
            $rave_generate2 = json_decode($response2, true);
            
            if($rave_generate2['data']['status'] == "successful"){
    
                $date_time = date("Y-m-d h:i:s");
    
                //Calculate Maturity Period
                $maturity_period = ($interval == "daily" ? 'day' : ($interval == "weekly" ? 'week' : ($interval == "monthly" ? 'month' : 'year')));
                $mature_date = date('Y-m-d h:i:s', strtotime('+'.$duration.' '.$maturity_period, strtotime($date_time)));
    
                //Calculate Next Payment Date
                $next_pmt_date = date('Y-m-d h:i:s', strtotime('+1 '.$maturity_period, strtotime($date_time)));
    
                //Calculate Interest
                $total_output = $amount * $duration;
    
                $message = "$sysabb>>>CR";
                $message .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
                $message .= " Acc: ".$this->db->ccMasking($ledgerAccountNo)."";
                $message .= " Desc: Automated Savings - | ".$new_reference."";
                $message .= " Time: ".$correctdate."";
                $message .= " Bal: ".$currency.number_format($myTargetBalAfterSavings,2,'.',',')."";
    
                $max_per_page = 153;
                $sms_length = strlen($message);
                $calc_length = ceil($sms_length / $max_per_page);
                $sms_charges = $calc_length * $trans_charges;
                $mywallet_balance = $iwallet_balance - $sms_charges;
                $myStatus = "Approved";
    
                //UPDATE INSTITUTION BALANCE
                $this->updateInstitutionWallet($mywallet_balance, $clientId);
    
                //UPDATE CUSTOMER LEDGER BALANCE
                //$this->updateBorrowerTargetBal($myTargetBalAfterSavings, $ledgerAccountNo, $clientId);
    
                //UPDATE TARGET SAVINGS RECORD
                $this->updateTargetSavingsRecords($transactionId, $myStatus, $mature_date, $planId);
    
                //UPDATE SAVINGS SUBSCRIPTION RECORD
                $this->updateSavingsSubRecords($real_subscription_code, $myStatus, $mature_date, $total_output, $next_pmt_date, $new_reference, $planId);
    
                $queryDep = "INSERT INTO transaction (txid, t_type, p_type, acctno, transfer_to, fn, ln, email, phone, amount, posted_by, remark, date_time, branchid, sbranchid, currency, aggr_id, balance, status, sendSms, sendEmail, smsChecker, balanceToImpact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertSavings($queryDep, $new_reference, 'Deposit', 'Card', $ledgerAccountNo, '---', $fname, $lname, $customerEmail, $customerPhone, $amount, $accountOfficer, $remarks, $date_time, $clientId, $sbranchid, $currency, $aggrId, $myTargetBalAfterSavings, 'Approved', $smsStatus, $notification, $checksms, 'target');
    
                //INSERT TRANSACTION CHARGES IN WALLET HISTORY FOR THE AGENT FOR REFERENCE PURPOSE
                $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertWalletHistory($query, $clientId, $new_reference, $customerPhone, '', $sms_charges, 'Debit', $currency, 'Charges', $message, 'successful', $date_time, '', $mywallet_balance);
    
                //SMS Notification
                ($smsChecker == "No" || $customerPhone == "" || $sms_charges > $iwallet_balance) ? "" : $this->instGeneralAlert($sysabb, $customerPhone, $message, $clientId);
                
                //Email Notification
                ($customerEmail != "") ? $this->sendEmail($customerEmail, 'Deposit', $new_reference, $fname, $lname, 'Card', $companyName, $ledgerAccountNo, $currency, $amount, $myTargetBalAfterSavings, $clientId) : "";
    
                return [
    
                    "responseCode" => "00",
        
                    "status" => "Success",
    
                    "data" => [
    
                        "processorResponse" => $rave_generate2['data']['processor_response'],
    
                        "referenceId" => $rave_generate2['data']['tx_ref'],
    
                        "transactionId" => $rave_generate2['data']['id'],
    
                        "amount" => $rave_generate2['data']['amount'],
    
                        "chargedAmount" => $rave_generate2['data']['charged_amount'],
    
                        "currency" => $rave_generate2['data']['currency'],
    
                        "card" => [
    
                            "issuer" => $rave_generate2['data']['card']['issuer'],
    
                            "type" => $rave_generate2['data']['card']['type'],
    
                            "expiryDate" => $rave_generate2['data']['card']['expiry'],
    
                            "token" => $cardToken
    
                        ],
    
                        "customerInfo" => [
    
                            "name" => $rave_generate2['data']['customer']['name'],
    
                            "ledgerAccountNo" => $ledgerAccountNo,
    
                            "email" => $rave_generate2['data']['customer']['email'],
    
                            "phoneNumber" => $customerPhone
    
                        ],
    
                    ]
    
                ];
    
            }else{
    
                return [
    
                    "responseCode" => "01",
    
                    "message" => "Unable to validate transaction",
    
                ];
    
            }
            
        }

    }


    //FETCH Daily / Weekly / Monthly / Yearly LEDGER HISTORY
    public function fetchSavingsHistory($account_no, $frequency, $clientId, $companyName) {

        //Daily Calculation
        $dt_min = new DateTime("last saturday"); // Edit
        $dt_min->modify('+1 day'); // Edit
        $dt_max = clone($dt_min);
        $dt_max->modify('+6 days');

        //Weekly / Monthly Calculation
        $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
        $last_day_this_month  = date('Y-m-t'); //get last day in a month

        $startDate = (($frequency == "Daily") ? date("Y-m-d").' 00'.':00'.':00' : (($frequency == "Weekly") ? $dt_min->format('Y-m-d').' 00:00:00' : (($frequency == "Monthly") ? date('Y-m-01', strtotime($first_day_this_month)).' 00:00:00' : (($frequency == "Yearly") ? date("Y-01-01").' 00:00:00' : ''))));
        $endDate = (($frequency == "Daily") ? date("Y-m-d").' 24'.':00'.':00' : (($frequency == "Weekly") ? $dt_max->format('Y-m-d').' 24:00:00' : (($frequency == "Monthly") ? date('Y-m-t', strtotime($last_day_this_month)).' 24:00:00' : (($frequency == "Yearly") ? date("Y-12-t", strtotime($startDate)).' 24:00:00' : ''))));

        //FOR CUSTOMER/BORROWER
        $customer = $this->fetchCustomerByAcctId($account_no, $clientId);

        $query = "SELECT * FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$clientId' AND acctno = '$account_no' ORDER BY id DESC";
        $output = $this->db->fetchAll($query, $clientId);
        $countOutput = count($output);

        if($customer === 0){

            return -1;
                    
        }elseif($countOutput >= 1){

            for($i = 0; $i <= $countOutput; $i++){

                foreach($output as $putEntry => $key){

                    $staffID = $key['posted_by'];
            
                    $searchStaff = $this->db->fetchStaff($staffID,$clientId);
                    
                    $myStaffName = $searchStaff['name'].' '.$searchStaff['lname'];

                    $output2[$i] = [
                        
                        "id"=> $key['id'],
                        
                        "transactionID"=> $key['txid'],
                        
                        "transactionType"=> $key['t_type'],
                        
                        "paymentType"=> $key['p_type'],
                        
                        "accountNumber"=> $key['acctno'],
                        
                        "customerFirstName"=> $key['fn'],
                        
                        "customerLastName"=> $key['ln'],
                        
                        "customerEmail"=> $key['email'],
                        
                        "customerPhone"=> preg_replace("/[^a-zA-Z0-9]/", "", $key['phone']),
                        
                        "currency"=> $key['currency'],
                        
                        "amount"=> number_format($key['amount'],2,'.',','),
                        
                        "balance"=> $key['balance'],
                        
                        "postedBy"=> $myStaffName,
                        
                        "merchantName"=> $companyName,
                        
                        "remark"=> $key['remark'],
                        
                        "dateTime"=> $key['date_time'],
                        
                        ];
                    $i++;

                }

                return [

                    "responseCode" => "00",

                    "status" => "Success",

                    "data" => $output2
                    
                ];

            }

        }else{
            
            return -1;
            
        }

    }


    /** ENDPOINT URL TO FETCH LEDGER COLLECTION WITH DATE:
     * 
     * {
     *  "startDate" : "2020-01-01",
     *  "endDate" : "2020-20-01",
     *  "ledgerAccountNo" : "9878277278",
     *  "clientID" : "INST1228288"
    *  }
     * 
     * */
    public function fetchSavingsHistoryByDate($parameter, $clientId, $companyName) {

        if(isset($parameter->startDate) && isset($parameter->endDate) && isset($parameter->ledgerAccountNo) && isset($parameter->clientID)) {

            $myStartDate = $parameter->startDate;
            $myEndDate = $parameter->endDate;
            $ledgerAccountNo = $parameter->ledgerAccountNo;
            $instID = $parameter->clientID;

            $startDate = $myStartDate.' 00:00:00'; // get start date from here
            $endDate = $myEndDate.' 24:00:00';

            //FOR CUSTOMER/BORROWER
            $customer = $this->fetchCustomerByAcctId($ledgerAccountNo, $clientId);

            $query = "SELECT * FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$clientId' AND acctno = '$ledgerAccountNo' ORDER BY id DESC";
            $output = $this->db->fetchAll($query, $clientId);
            $countOutput = count($output);
            
            if($myStartDate === "" || $myEndDate === "" || $ledgerAccountNo === "" || $instID === ""){

                return -1;

            }elseif($customer === 0){

                return -2;
                        
            }elseif($instID != $clientId){

                return -3;

            }elseif($countOutput >= 1){

                for($i = 0; $i <= $countOutput; $i++){
    
                    foreach($output as $putEntry => $key){
    
                        $staffID = $key['posted_by'];
                
                        $searchStaff = $this->db->fetchStaff($staffID,$clientId);
                        
                        $myStaffName = $searchStaff['name'].' '.$searchStaff['lname'];
    
                        $output2[$i] = [
                            
                            "id"=> $key['id'],
                            
                            "transactionID"=> $key['txid'],
                            
                            "transactionType"=> $key['t_type'],
                            
                            "paymentType"=> $key['p_type'],
                            
                            "accountNumber"=> $key['acctno'],
                            
                            "customerFirstName"=> $key['fn'],
                            
                            "customerLastName"=> $key['ln'],
                            
                            "customerEmail"=> $key['email'],
                            
                            "customerPhone"=> preg_replace("/[^a-zA-Z0-9]/", "", $key['phone']),
                            
                            "currency"=> $key['currency'],
                            
                            "amount"=> number_format($key['amount'],2,'.',','),
                            
                            "balance"=> $key['balance'],
                            
                            "postedBy"=> $myStaffName,
                            
                            "merchantName"=> $companyName,
                            
                            "remark"=> $key['remark'],
                            
                            "dateTime"=> $key['date_time'],
                            
                            ];
                        $i++;
    
                    }
                    
                    return [

                        "responseCode" => "00",
    
                        "status" => "Success",
    
                        "data" => $output2

                    ];
    
                }
    
            }else{
                
                return -1;
                
            }

        }else{

            return -4;

        }

    }


    //FETCH LAST FIVE LEDGER HISTORY
    public function fetchLastFiveSavingsHistory($account_no, $clientId, $companyName) {

        //FOR CUSTOMER/BORROWER
        $customer = $this->fetchCustomerByAcctId($account_no, $clientId);

        $query = "SELECT * FROM transaction WHERE acctno = '$account_no' AND branchid = '$clientId' ORDER BY id DESC LIMIT 5";
        $output = $this->db->fetchAll($query, $account_no);
        $countOutput = count($output);
        
        if($customer === 0){

            return -1;
                    
        }elseif($countOutput >= 1){

            for($i = 0; $i <= $countOutput; $i++){

                foreach($output as $putEntry => $key){

                    $staffID = $key['posted_by'];
            
                    $searchStaff = $this->db->fetchStaff($staffID,$clientId);
                    
                    $myStaffName = $searchStaff['name'].' '.$searchStaff['lname'];

                    $output2[$i] = [
                        
                        "id"=> $key['id'],
                        
                        "transactionID"=> $key['txid'],
                        
                        "transactionType"=> $key['t_type'],
                        
                        "paymentType"=> $key['p_type'],
                        
                        "accountNumber"=> $key['acctno'],
                        
                        "customerFirstName"=> $key['fn'],
                        
                        "customerLastName"=> $key['ln'],
                        
                        "customerEmail"=> $key['email'],
                        
                        "customerPhone"=> preg_replace("/[^a-zA-Z0-9]/", "", $key['phone']),
                        
                        "currency"=> $key['currency'],
                        
                        "amount"=> number_format($key['amount'],2,'.',','),
                        
                        "balance"=> $key['balance'],
                        
                        "postedBy"=> $myStaffName,
                        
                        "merchantName"=> $companyName,
                        
                        "remark"=> $key['remark'],
                        
                        "dateTime"=> $key['date_time'],
                        
                        ];
                    $i++;

                }

                return [

                    "responseCode" => "00",

                    "status" => "Success",

                    "data" => $output2
                    
                ];

            }

        }else{
            
            return -1;
            
        }

    }


    //FETCH Daily / Weekly / Monthly / Yearly SAVINGS SUBSCRIPTION LIST
    public function fetchMySavingsSub($account_no, $frequency, $clientId, $companyName) {

        //Daily Calculation
        $dt_min = new DateTime("last saturday"); // Edit
        $dt_min->modify('+1 day'); // Edit
        $dt_max = clone($dt_min);
        $dt_max->modify('+6 days');

        //Weekly / Monthly Calculation
        $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
        $last_day_this_month  = date('Y-m-t'); //get last day in a month

        $startDate = (($frequency == "Daily") ? date("Y-m-d").' 00'.':00'.':00' : (($frequency == "Weekly") ? $dt_min->format('Y-m-d').' 00:00:00' : (($frequency == "Monthly") ? date('Y-m-01', strtotime($first_day_this_month)).' 00:00:00' : (($frequency == "Yearly") ? date("Y-01-01").' 00:00:00' : ''))));
        $endDate = (($frequency == "Daily") ? date("Y-m-d").' 24'.':00'.':00' : (($frequency == "Weekly") ? $dt_max->format('Y-m-d').' 24:00:00' : (($frequency == "Monthly") ? date('Y-m-t', strtotime($last_day_this_month)).' 24:00:00' : (($frequency == "Yearly") ? date("Y-12-t", strtotime($startDate)).' 24:00:00' : ''))));

        //FOR CUSTOMER/BORROWER
        $customer = $this->fetchCustomerByAcctId($account_no, $clientId);

        $query = "SELECT * FROM savings_subscription WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$clientId' AND acn = '$account_no' AND status = 'Approved' ORDER BY id DESC";
        $output = $this->db->fetchAll($query, $clientId);
        $countOutput = count($output);

        if($customer === 0){

            return -1;
                    
        }elseif($countOutput >= 1){

            for($i = 0; $i <= $countOutput; $i++){

                foreach($output as $putEntry => $key){

                    $staffID = $key['agentid'];
            
                    $searchStaff = ($staffID == "") ? "" : $this->db->fetchStaff($staffID,$clientId);
                    
                    $myStaffName = ($staffID == "") ? "" : $searchStaff['name'].' '.$searchStaff['lname'];

                    $output2[$i] = [
                        
                        "id"=> $key['id'],
                        
                        "planCode"=> $key['new_plancode'],
                        
                        "planId"=> $key['plan_id'],
                        
                        "subCode"=> $key['subscription_code'],

                        "subCategory"=> $key['categories'],
                        
                        "ledgerAccountNo"=> $key['acn'],
                        
                        "amount"=> number_format($key['amount'],2,'.',','),
                        
                        "currency"=> $key['currency'],
                        
                        "savingsInterval"=> $key['savings_interval'],
                        
                        "savingsLifespam"=> (($key['savings_interval'] == "daily") ? $key['duration']." Day(s)" : (($key['savings_interval'] == "weekly") ? $key['duration']." Week(s)" : (($key['savings_interval'] == "monthly") ? $key['duration']." Month(s)" : (($key['savings_interval'] == "yearly") ? $key['duration']." Year(s)" : "One-off")))),
                        
                        "savingsProgressScore"=> number_format((($key['sub_balance'] / ($key['amount'] * $key['duration']))),2,'.','') * 100,

                        "progressType"=> "Percentage",
                        
                        "totalSaved"=> number_format($key['sub_balance'],2,'.',''),

                        "savingsGoals"=> number_format(($key['amount'] * $key['duration']),2,'.',','),

                        "startDate"=> $key['date_time'],
                        
                        "maturityDate"=> $key['mature_date'],

                        "paymentChannel"=> ($key['sub_type'] == "Auto") ? "Card" : "Wallet",

                        "balanceToImpact"=> ($key['balanceToImpact'] == "ledger" || $key['balanceToImpact'] == "Target") ? "Target" : "Investment",
                        
                        "postedBy"=> $myStaffName,
                        
                        "merchantName"=> $companyName
                        
                    ];
                    $i++;

                }

                return [
                    
                    "responseCode" => "00",

                    "status" => "Success",

                    "data" => $output2
                
                ];

            }

        }else{
            
            return -1;
            
        }

    }


    /** ENDPOINT URL TO FETCH SAVINGS SUBSCRIPTION WITH DATE:
     * 
     * {
     *  "startDate" : "2020-01-01",
     *  "endDate" : "2020-20-01",
     *  "ledgerAccountNo" : "9878277278",
     *  "clientID" : "INST1228288"
    *  }
     * 
     * */
    public function fetchMySavingsSubByDate($parameter, $clientId, $companyName) {

        if(isset($parameter->startDate) && isset($parameter->endDate) && isset($parameter->ledgerAccountNo) && isset($parameter->clientID)) {

            $myStartDate = $parameter->startDate;
            $myEndDate = $parameter->endDate;
            $ledgerAccountNo = $parameter->ledgerAccountNo;
            $instID = $parameter->clientID;

            $startDate = $myStartDate.' 00:00:00'; // get start date from here
            $endDate = $myEndDate.' 24:00:00';

            //FOR CUSTOMER/BORROWER
            $customer = $this->fetchCustomerByAcctId($ledgerAccountNo, $clientId);

            $query = "SELECT * FROM savings_subscription WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$clientId' AND acn = '$ledgerAccountNo' ORDER BY id DESC";
            $output = $this->db->fetchAll($query, $clientId);
            $countOutput = count($output);

            if($myStartDate === "" || $myEndDate === "" || $ledgerAccountNo === "" || $clientID === ""){

                return -1;

            }elseif($customer === 0){

                return -2;
                        
            }elseif($instID != $clientId){

                return -3;

            }elseif($countOutput >= 1){

                for($i = 0; $i <= $countOutput; $i++){
    
                    foreach($output as $putEntry => $key){
    
                        $staffID = $key['agentid'];
            
                        $searchStaff = ($staffID == "") ? "" : $this->db->fetchStaff($staffID,$clientId);
                        
                        $myStaffName = ($staffID == "") ? "" : $searchStaff['name'].' '.$searchStaff['lname'];

                        $output2[$i] = [
                            
                            "id"=> $key['id'],
                            
                            "planCode"=> $key['new_plancode'],
                            
                            "planId"=> $key['plan_id'],
                            
                            "subCode"=> $key['subscription_code'],

                            "subCategory"=> $key['categories'],
                            
                            "ledgerAccountNo"=> $key['acn'],
                            
                            "amount"=> number_format($key['amount'],2,'.',','),
                            
                            "currency"=> $key['currency'],
                            
                            "savingsInterval"=> $key['savings_interval'],
                            
                            "savingsLifespam"=> (($key['savings_interval'] == "daily") ? $key['duration']." Day(s)" : (($key['savings_interval'] == "weekly") ? $key['duration']." Week(s)" : (($key['savings_interval'] == "monthly") ? $key['duration']." Month(s)" : (($key['savings_interval'] == "yearly") ? $key['duration']." Year(s)" : "One-off")))),
                            
                            "savingsProgressScore"=> number_format((($key['sub_balance'] / ($key['amount'] * $key['duration']))),2,'.','') * 100,

                            "progressType"=> "Percentage",
                            
                            "totalSaved"=> number_format($key['sub_balance'],2,'.',''),

                            "savingsGoals"=> number_format(($key['amount'] * $key['duration']),2,'.',','),

                            "startDate"=> $key['date_time'],
                            
                            "maturityDate"=> $key['mature_date'],

                            "paymentChannel"=> ($key['sub_type'] == "Auto") ? "Card" : "Wallet",

                            "balanceToImpact"=> ($key['balanceToImpact'] == "ledger" || $key['balanceToImpact'] == "Target") ? "Target" : "Investment",
                            
                            "postedBy"=> $myStaffName,
                            
                            "merchantName"=> $companyName
                            
                        ];
                        $i++;
    
                    }
                    
                    return [

                        "responseCode" => "00",
    
                        "status" => "Success",
    
                        "data" => $output2
                
                    ];
    
                }
    
            }else{
                
                return -5;
                
            }

        }else{

            return -4;

        }

    }


    //CHECK MATURITY STATUS OF A SAVINGS SUBSCRIPTION
    public function checkMySavingsMaturityStatus($subCode, $clientId, $companyName) {

        $query = "SELECT * FROM savings_subscription WHERE subscription_code = '$subCode' AND companyid = '$clientId' ORDER BY id DESC";
        $output = $this->db->fetchById($query, $subCode);

        $now = time(); // or your date as well
        $maturityDate = $output['mature_date'];
        $your_date = strtotime($maturityDate);
        
        $datediff = $your_date - $now;
        $total_day = round($datediff / (60 * 60 * 24));

        if($output === 0){

            return -1;

        }elseif($total_day >= 1){

            return [

                "responseCode" => "01",
    
                "status" => "Pending",

                "message"=> "Savings not yet mature for withdrawal"

            ];

        }else{

            return [

                "responseCode" => "00",
    
                "status" => "Success",

                "data"=> [

                    "id"=> $output['id'],
                            
                    "planCode"=> $output['new_plancode'],
                            
                    "planId"=> $output['plan_id'],
                            
                    "subCode"=> $output['subscription_code'],
                    
                    "ledgerAccountNo"=> $output['acn'],

                    "currency"=> $output['currency'],

                    "totalSaved"=> $output['sub_balance'],
                    
                    "merchantName"=> $companyName

                ]

            ];

        }

    }

    
    /** ENDPOINT URL TO AUTOMATE WITHDRAWAL REQUEST ARE:
     * 
     * {
     *  "ledgerAccountNo" : "9830983773",
     *  "referenceNo" : "NUY-37JDKJY3U3YU",
     *  "source" : "ledger | target | investment"
     *  "modeOfCollection" : "Wallet | Cash | Bank",
	*   "amount" : "1000",
    *   "remark" : "testing withdrawal request",
    *   "tPin" : "1234",
    *   "clientID": "INST1228288"
    *  }
     * 
     * */
    public function makeWithdrawalRequest($parameter, $clientId, $companyName, $companyEmail, $aggrId) {

        if(isset($parameter->ledgerAccountNo) && isset($parameter->referenceNo) && isset($parameter->source) && isset($parameter->modeOfCollection) && isset($parameter->amount) && isset($parameter->remark) && isset($parameter->clientID) && isset($parameter->tPin)) {

            $ledgerAccountNo = $parameter->ledgerAccountNo;
            $referenceNo = $parameter->referenceNo;
            $source = $parameter->source;
            $modeOfCollection = $parameter->modeOfCollection;
            $amount = $parameter->amount;
            $remark = $parameter->remark;
            $tPin = $parameter->tPin;
            $clientID = $parameter->clientID;

            $validateAccountNo = $this->fetchCustomerByAcctId($ledgerAccountNo,$clientId);
            $myTPin = $validateAccountNo['tpin'];
            $accountOfficer = $validateAccountNo['lofficer'];
            $fname = $validateAccountNo['fname'];
            $lname = $validateAccountNo['lname'];
            $bname = $lname.' '.$fname;
            $email = $validateAccountNo['email'];
            $phone = $validateAccountNo['phone'];
            $sbranchid = $validateAccountNo['sbranchid'];
            $myBalanceLeft = (($source == "ledger") ? $validateAccountNo['balance'] : (($source == "target") ? $validateAccountNo['target_savings_bal'] : (($source == "investment") ? $validateAccountNo['investment_bal'] : "0")));
            $smsChecker = $validateAccountNo['sms_checker'];
            $checksms = ($smsChecker == "No") ? "0" : "1";
            $date_time = date("Y-m-d h:i:s");

            $searchStaff = ($accountOfficer == "") ? "" : $this->db->fetchStaff($accountOfficer,$clientId);
                        
            $myStaffName = ($accountOfficer == "") ? "" : $searchStaff['name'].' '.$searchStaff['lname'];
            
            $searchMemSet = $this->db->fetchMemberSettings($clientId);
            $currency = $searchMemSet['currency'];
            
            $checkDuplicacy = $this->checkWithdrawalRequestDuplicacy($referenceNo);

            if($ledgerAccountNo === "" || $referenceNo === "" || $source === "" || $modeOfCollection === "" || $amount === "" || $tPin === ""){

                return -1;

            }elseif($validateAccountNo === 0){

                return -2;
            
            }elseif($tPin != $myTPin){

                return -3;

            }elseif($clientID != $clientId){

                return -5;

            }elseif($checkDuplicacy != 0){
                
                return -6;
                
            }else{

                $myQuery = "INSERT INTO ledger_withdrawal_request (txid, companyid, acct_officer, acn, ptype, amt_requested, remarks, status, balance_toimpact, currency, email, phone, sbranchid, sendsms, sendemail, date_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertWithdrawalRequest($myQuery, $referenceNo, $clientId, $accountOfficer, $ledgerAccountNo, $modeOfCollection, $amount, $remark, "Pending", $source, $currency, $email, $phone, $sbranchid, "0", "0", $date_time);

                $queryWid = "INSERT INTO transaction (txid, t_type, p_type, acctno, transfer_to, fn, ln, email, phone, amount, posted_by, remark, date_time, branchid, sbranchid, currency, aggr_id, balance, status, sendSms, sendEmail, smsChecker, balanceToImpact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertSavings($queryWid, $referenceNo, 'Withdraw', $modeOfCollection, $ledgerAccountNo, '---', $fname, $lname, $email, $phone, $amount, $accountOfficer, $remark, $date_time, $clientId, $sbranchid, $currency, $aggrId, $myBalanceLeft, 'Pending', "0", "0", $checksms, $source);

                $this->withdrawalRequestNotifier($companyEmail, $bname, "Customer", $companyName, $modeOfCollection, $ledgerAccountNo, $currency, $amount, $myBalanceLeft, $remark, $clientId);
                
                return [

                    "responseCode" => "00",
    
                    "status" => "Success",

                    "data"=> [

                        "referenceNo" => $referenceNo,

                        "ledgerAccountNo" => $ledgerAccountNo,

                        "amount" => number_format($amount,2,'.',','),

                        "currency" => $currency,

                        "currentBalance" => $myBalanceLeft,

                        "customerEmail" => $email,

                        "customerPhone" => $phone,

                        "customerName" => $bname,

                        "accountOfficer" => ($accountOfficer == "") ? "" : $myStaffName,

                        "merchantName" => $companyName

                    ]

                ];

            }

        }else{

            return -4;

        }

    }


    /** ENDPOINT URL TO  TOPUP TARGET SAVINGS WITH WALLET ARE:
     * 
     * {
     *  "referenceNo" : "NNYEXY-7487784HHRH",
     *  "subCode" : "uSubjbjhnbsby3y378",
	*   "amount" : "1000",
    *   "balanceToImpact" : "Target | Investment",
    *   "tPin" : "1234",
    *   "clientID": "INST1228288"
    *  }
     * 
     * */
    public function topupSavingsWithWallet($parameter, $clientId, $companyName, $companyEmail, $aggrId, $iwallet_balance, $trans_charges) {

        if(isset($parameter->referenceNo) && isset($parameter->subCode) && isset($parameter->amount) && isset($parameter->balanceToImpact) && isset($parameter->clientID) && isset($parameter->tPin)) {

            $referenceNo = $parameter->referenceNo;
            $subCode = $parameter->subCode;
            $amount = $parameter->amount;
            $balanceToImpact = $parameter->balanceToImpact;
            $tPin = $parameter->tPin;
            $clientID = $parameter->clientID;
            $realRefNo = $subCode."|".$referenceNo;

            $savingsSub = $this->db->fetchSavingSub($subCode);
            $ledgerAccountNo = $savingsSub['acn'];
            $RealAmount = $savingsSub['amount'];
            $planId = $savingsSub['plan_id'];
            $plan_code = $savingsSub['new_plancode'];
            $interval = $savingsSub['savings_interval'];
            $duration = $savingsSub['duration'];
            $vendorid = $savingsSub['vendorid'];
            $agentid = $savingsSub['agentid'];
            $categories = $savingsSub['categories'];
            $subBal = $savingsSub['sub_balance'] + $amount;

            $validateAccountNo = $this->fetchCustomerByAcctId($ledgerAccountNo,$clientId);
            $myTPin = $validateAccountNo['tpin'];
            $accountOfficer = $validateAccountNo['lofficer'];
            $fname = $validateAccountNo['fname'];
            $lname = $validateAccountNo['lname'];
            $bname = $lname.' '.$fname;
            $email = $validateAccountNo['email'];
            $phone = $validateAccountNo['phone'];
            $bwallet_balance = $validateAccountNo['wallet_balance'];
            $vaNo = $validateAccountNo['virtual_acctno'];
            $vaBankName = $validateAccountNo['bankname'];
            $sbranchid = $validateAccountNo['sbranchid'];
            $myBalanceLeft = ($balanceToImpact == "Target") ? $validateAccountNo['target_savings_bal'] : $validateAccountNo['investment_bal'];
            $totalBalanceAfter = $myBalanceLeft + $amount;
            $smsChecker = $validateAccountNo['sms_checker'];
            $checksms = ($smsChecker == "No") ? "0" : "1";
            $date_time = date("Y-m-d h:i:s");
            $remark = "Savings Topup with SubCode: ".$subCode.", PlanCode: ".$plan_code." with Invoice Code: ".$realRefNo." through Wallet Payment";

            $searchStaff = ($accountOfficer == "") ? "" : $this->db->fetchStaff($accountOfficer,$clientId);
            $myStaffName = ($accountOfficer == "") ? "" : $searchStaff['name'].' '.$searchStaff['lname'];
            
            $searchMemSet = $this->db->fetchMemberSettings($clientId);
            $sysabb = ($searchMemSet['sender_id'] == "") ? "esusuafrica" : $searchMemSet['sender_id'];
            $currency = $searchMemSet['currency'];
            $correctdate = date('m/d/Y').' '.(date('h') + 1).':'.date('i A');

            $vendorEmail = ",";
            
            $checkDuplicacy = $this->fetchSavingsTransaction($realRefNo,$clientId);

            if($referenceNo === "" || $subCode === "" || $amount === "" || $balanceToImpact === "" || $tPin === ""){

                return -1;

            }elseif($validateAccountNo === 0){

                return -2;
            
            }elseif($tPin != $myTPin){

                return -3;

            }elseif($clientID != $clientId){

                return -5;

            }elseif($checkDuplicacy != 0){
                
                return -6;
                
            }elseif($amount > $bwallet_balance){

                return -7;

            }elseif($RealAmount > $amount){
                
                return -8;
                
            }else{

                $message = "$sysabb>>>CR";
                $message .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
                $message .= " Acc: ".$this->db->ccMasking($ledgerAccountNo)."";
                $message .= " Desc: Topup Savings - ".$realRefNo."";
                $message .= " Time: ".$correctdate."";
                $message .= " Bal: ".$currency.number_format($totalBalanceAfter,2,'.',',')."";

                $max_per_page = 153;
                $sms_length = strlen($message);
                $calc_length = ceil($sms_length / $max_per_page);
                $sms_charges = $calc_length * $trans_charges;
                $mywallet_balance = $iwallet_balance - $sms_charges;
                $senderBalance = $bwallet_balance - $amount;
                $creatorEmail = $email.','.$companyEmail.$vendorEmail;

                //Calculate Maturity Period
                $maturity_period = ($interval == "daily" ? 'day' : ($interval == "weekly" ? 'week' : ($interval == "monthly" ? 'month' : 'year')));
                //Calculate Next Payment Date
                $next_pmt_date = date('Y-m-d h:i:s', strtotime('+1 '.$maturity_period, strtotime($date_time)));

                //DEBIT CUSTOMER WALLET
                $this->updateBorrowerWallet($senderBalance, $ledgerAccountNo, $clientId);

                //UPDATE SAVINGS SUB RECORDS
                $this->updateMySavingsSub($subBal, $next_pmt_date, $subCode, $clientId);

                //INSERT WALLET HISTORY FOR CUSTOMER TARGET SAVINGS DEBIT USING WALLET
                $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertWalletHistory($query, $clientId, $realRefNo, 'Deposit for SubCode: '.$subCode, '', $amount, 'Debit', $currency, 'TARGET_SAVINGS_SUB', $remark, 'successful', $date_time, $ledgerAccountNo, $senderBalance);

                //UPDATE INSTITUTION BALANCE
                ($smsChecker == "No" || $phone == "" || $sms_charges > $iwallet_balance || $balanceToImpact == "Investment") ? "" : $this->updateInstitutionWallet($mywallet_balance, $clientId);

                //INSERT CUSTOMER LEDGER RECORD IF REQUIRED
                ($balanceToImpact == "Target") ? $queryDep = "INSERT INTO transaction (txid, t_type, p_type, acctno, transfer_to, fn, ln, email, phone, amount, posted_by, remark, date_time, branchid, sbranchid, currency, aggr_id, balance, status, sendSms, sendEmail, smsChecker, balanceToImpact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
                ($balanceToImpact == "Target") ? $this->db->insertSavings($queryDep, $realRefNo, 'Deposit', 'Wallet', $ledgerAccountNo, '---', $fname, $lname, $email, $phone, $amount, $accountOfficer, $remark, $date_time, $clientId, $sbranchid, $currency, $aggrId, $totalBalanceAfter, 'Approved', '1', '1', $checksms, "target") : "";

                //INSERT CUSTOMER INVESTMENT RECORDS IF REQUIRED
                ($balanceToImpact == "Investment") ? $queryInvDep = "INSERT INTO all_savingssub_transaction (merchant_id, acn, invoice_code, subscription_code, reference_no, currency, amount, status, date_time, first_name, last_name, auth_code, card_firstsix_digit, card_lastfour_digit, card_type, bank_name, next_pmt_date, vendorid, agentid) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
                ($balanceToImpact == "Investment") ? $this->insertInvestmentSavings($queryInvDep, $clientId, $ledgerAccountNo, $realRefNo, $subCode, $referenceNo, $currency, $amount, 'successful', $date_time, $fname, $lname, 'NONE', 'NONE', 'NONE', 'NONE', 'Wallet Transfer', $plan_code, $vendorid, $agentid) : "";

                //UPDATE CUSTOMER TARGET BALANCE IF REQUIRED
                ($balanceToImpact == "Target") ? $this->updateBorrowerTargetBal($totalBalanceAfter, $ledgerAccountNo, $clientId) : "";
                //UPDATE CUSTOMER INVESTMENT BALANCE IF REQUIRED
                ($balanceToImpact == "Investment") ? $this->updateBorrowerInvestmentBal($totalBalanceAfter, $ledgerAccountNo, $clientId) : "";

                //INSERT TRANSACTION CHARGES IN INSTITUTION WALLET HISTORY FOR THE AGENT FOR REFERENCE PURPOSE
                $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertWalletHistory($query, $clientId, $realRefNo, $phone, '', $sms_charges, 'Debit', $currency, 'Charges', $message, 'successful', $date_time, $accountOfficer, $mywallet_balance);

                //SMS Notification
                ($smsChecker == "No" || $phone == "" || $sms_charges > $iwallet_balance || $balanceToImpact == "Investment") ? "" : $this->instGeneralAlert($sysabb, $phone, $message, $clientId);
                
                //Email Notification
                ($email != "" && $balanceToImpact == "Target") ? $this->sendEmail($email, 'Deposit', $realRefNo, $fname, $lname, 'Wallet', $companyName, $ledgerAccountNo, $currency, $amount, $totalBalanceAfter, $clientId) : "";
                ($email != "" && $balanceToImpact == "Investment") ? $this->productPaymentNotifier($creatorEmail, $correctdate, $referenceId, $ledgerAccountNo, $bname, $subCode, $plan_code, $categories, "---", $vaBankName, $vaNo, $bname, $currency, $amount) : "";

                return [

                    "responseCode" => "00",
    
                    "status" => "Success",

                    "data"=> [

                        "referenceNo" => $referenceNo,

                        "ledgerAccountNo" => $ledgerAccountNo,

                        "amount" => number_format($amount,2,'.',','),

                        "currency" => $currency,

                        "currentBalance" => $totalBalanceAfter,

                        "customerEmail" => $email,

                        "customerPhone" => $phone,

                        "customerName" => $bname,

                        "accountOfficer" => ($accountOfficer == "") ? "" : $myStaffName,

                        "merchantName" => $companyName

                    ]

                ];

            }

        }else{

            return -4;

        }

    }



}
?>