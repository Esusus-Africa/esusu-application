<?php

class Customer extends User {

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

    //EMAIL NOTIFICATION
    public function sendEmail($email, $fname, $username, $password, $account, $registeral){

        $result = array();

        //FETCH SYSTEMSET FOR EMAIL DELIVERY CREDENTIALS
        $emailCredentials = $this->db->fetchSystemSet();
        $email_from = $emailCredentials['email_from'];
        $product_name = $emailCredentials['name'];
        $website = $emailCredentials['website'];
        $logo_url = $emailCredentials['logo_url'];
        $id = rand(1000000,10000000);
        $shorturl = base_convert($id,20,36);
        $shortenedurl = 'https://esusu.app/?activation_key=' . $shorturl;
        $deactivation_url = 'https://esusu.app/?deactivation_key=' . $shorturl;
        $support_email = $emailCredentials['email'];
        $live_chat_url = $emailCredentials['live_chat'];
        $sender_name = $emailCredentials['email_sender_name'];
        $company_address = $emailCredentials['address'];
        $email_token = $emailCredentials['email_token'];
        $brand_color = $emailCredentials['brand_color'];

        $correctdate = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');

        $fetch_emailConfig = $this->db->fetchEmailConfig($registeral);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
        $myAccountNumber = '---';
        
        // Pass the customer's authorisation code, email and amount
        $postdata =  array(
            "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $email_from : $fetch_emailConfig['email_from']),
            "To"            => $email,  //Customer Email Address
            "TemplateId"    => '9545527',
            "TemplateModel" => [
              "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
              "name"              => $fname,  //Customer First Name
              "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $brand_color : $fetch_emailConfig['brand_color']),
              "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $logo_url : $fetch_emailConfig['logo_url']),
              "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $website : $fetch_emailConfig['product_url']),
              "activation_url"    => $shortenedurl, //Customer Activation Link
              "username"          => $username, //Customer Username
              "password"          => $password, //Customer Password
              "ledger_acno"       => $account,
              "wallet_acno"       => $myAccountNumber,
              "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $support_email : $fetch_emailConfig['support_email']),
              "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $live_chat_url : $fetch_emailConfig['live_chat']),
              "sender_name"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $sender_name : $fetch_emailConfig['email_sender_name']),
              "deactivation_url"  => $deactivation_url,  //Customer Deactivation Link
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


    //SMS NOTIFICATION
    public function instGeneralAlert($ozeki_password, $ozeki_url, $debitWallet, $sender, $phone, $msg, $registeral, $refid, $sms_charges, $currency, $details, $reg_staffid, $mywallet_balance){
        
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
            ($debitWallet == "Yes") ? $this->updateInstitutionWallet($mywallet_balance, $registeral) : "";
            ($debitWallet == "Yes") ? $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
            ($debitWallet == "Yes") ? $this->db->insertWalletHistory($query, $registeral, $refid, $phone, '', $sms_charges, 'Debit', $currency, 'Charges', $details, 'successful', $date_time, $reg_staffid, $mywallet_balance) : "";

        }

    }


    //FETCH CUSTOMER BY id OR account_number
    public function fetchCustomerById($parameter, $parameter1, $companyName) {

        $query = "SELECT * FROM borrowers WHERE (id = '$parameter' OR account = '$parameter') AND branchid = '$parameter1' ORDER BY id DESC";

        $myOutput = $this->db->fetchById($query, $parameter);
        
        if($myOutput >= 1){
        
            foreach($myOutput as $putEntry => $key){
                    
                    $staffID = $myOutput['lofficer'];
                    
                    $image = "https://esusu.app/".$myOutput['image'];
                    
                    $getCorrectImage = preg_match('/\s/',$image);
                    
                    $detectValidImageFormat = ($getCorrectImage >= 1) ? 'https://esusu.app/img/image-placeholder.jpg' : $image;
                    
                    $encryptedImage = ($myOutput['image'] == "" || $myOutput['image'] == "img/") ? file_get_contents('https://esusu.app/img/image-placeholder.jpg') : file_get_contents($detectValidImageFormat); 
                      
                    // Encode the image string data into base64 
                    $imageData = base64_encode($encryptedImage);  
            
                    $searchStaff = $this->db->fetchStaff($staffID,$parameter);
                    
                    $myStaffName = $searchStaff['name'].' '.$searchStaff['lname'];
                    
                    $output2 = [
                        
                        "basicInfo"=> [
                            
                            "id"=> $myOutput['id'],
                        
                            "serialNumber "=> $myOutput['snum'],
                            
                            "firstName"=> $myOutput['fname'],
                            
                            "lastName"=> $myOutput['lname'],
                            
                            "middleName"=> $myOutput['mname'],
                            
                            "email"=> $myOutput['email'],
                            
                            "phone"=> preg_replace("/[^a-zA-Z0-9]/", "", $myOutput['phone']),
                            
                            "gender"=> $myOutput['gender'],
                            
                            "dateOfBirth"=> $myOutput['dob'],
                            
                            "occupation"=> $myOutput['occupation'],
                            
                            "employer"=> $myOutput['employer'],
                            
                            "homeAddress"=> $myOutput['addrs'],
                            
                            "city"=> $myOutput['city'],
                            
                            "state"=> $myOutput['state'],
                            
                            "country"=> $myOutput['country'],
                            
                            "nextOfKinName"=> $myOutput['nok'],
                            
                            "nextOfKinRelationship"=> $myOutput['nok_rela'],
                            
                            "nextofKinPhoneNumber"=> $myOutput['nok_phone'],
                            
                            "accountNumber"=> $myOutput['account'],
                            
                            "userName"=> $myOutput['username'],
                            
                            "ledgerBalance"=> $myOutput['balance'],
                            
                            "investmentBalance"=> $myOutput['investment_bal'],
                            
                            "walletBalance"=> $myOutput['wallet_balance'],
                            
                            "acctountStatus"=> $myOutput['acct_status'],
                            
                            "accountOfficer"=> $myStaffName,
                            
                            "merchantName"=> $companyName,
                            
                            "evn"=> $myOutput['evn'],
                            
                            "bvn"=> $myOutput['unumber'],
                            
                            "otpOption"=> $myOutput['opt_option'],
                            
                            "allowOverdraft"=> $myOutput['overdraft'],
                            
                            "currency"=> $myOutput['currency'],
                            
                            "accountOpeningDate"=> $myOutput['acct_opening_date'],
                            
                            "profilePicture"=> $imageData,
                            
                            ],
                            
                        "savingsCulture"=> [
                            
                            "countributionInterval"=> $myOutput['s_contribution_interval'],
                        
                            "savingsAmount"=> $myOutput['savings_amount'],
                            
                            "chargeInterval"=> $myOutput['charge_interval'],
                            
                            "chargesAmount"=> $myOutput['chargesAmount'],
                            
                            "disbursementInterval"=> $myOutput['disbursement_interval'],
                            
                            "disbursementChannel"=> $myOutput['disbursement_channel'],
                            
                            "autoDisbursementStatus"=> $myOutput['auto_disbursement_status'],
                            
                            "autoChargeStatus"=> $myOutput['auto_charge_status'],
                            
                            "nextChargeDate"=> $myOutput['next_charge_date'],
                            
                            "nextDisbursementDate"=> $myOutput['next_disbursement_date'],
                            
                            ],
                            
                        "walletAccount"=> [
                            
                            "walletReferenceId"=> $myOutput['virtual_number'],
                            
                            "walletAccountNumber"=> $myOutput['virtual_acctno'],
                            
                            "bankName"=> $myOutput['bankname'],
                            
                            ]
                        
                        ];
                    
                }
                return $output2;
                
            }else{
            
                $myoutput = [
                    array(
                        "responseCode"=> "01",
                        "message"=> "no data found"
                        )
                    ];
                
                return $myoutput;
            
        }

    }


    //FETCH ALL USER CUSTOMERS
    public function fetchAllCustomer($registeral,$companyName,$irole,$reg_staffid,$reg_branch) {
        
        $parameter = "";
        $searchRole = ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $this->db->fetchRole($irole,$parameter) : $this->db->fetchRole($irole,$registeral);
        $view_all_customers = $searchRole['view_all_customers'];
        $individual_customer_records = $searchRole['individual_customer_records'];
        $branch_customer_records = $searchRole['branch_customer_records'];

        ($view_all_customers == "1" && $individual_customer_records != "1" && $branch_customer_records != "1") ? $query = "SELECT * FROM borrowers WHERE branchid = '$registeral' ORDER BY id DESC LIMIT 700" : "";
        ($view_all_customers != "1" && $individual_customer_records == "1" && $branch_customer_records != "1") ? $query = "SELECT * FROM borrowers WHERE branchid = '$registeral' AND lofficer = '$reg_staffid' ORDER BY id DESC" : "";
        ($view_all_customers != "1" && $individual_customer_records != "1" && $branch_customer_records == "1") ? $query = "SELECT * FROM borrowers WHERE branchid = '$registeral' AND sbranchid = '$reg_branch' ORDER BY id DESC" : "";

        $myOutput = $this->db->fetchAll($query, $registeral);
        
        $countOutput = count($myOutput);
        
        if($countOutput >= 1){
        
            for($i = 0; $i <= $countOutput; $i++){
                
                foreach($myOutput as $putEntry => $key){
                    
                    $staffID = $key['lofficer'];
            
                    $searchStaff = $this->db->fetchStaff($staffID,$parameter);
                    
                    $myStaffName = $searchStaff['name'].' '.$searchStaff['lname'];
                    
                    $output2[$i] = [
                        
                        "basicInfo"=> [
                            
                            "id"=> $key['id'],
                        
                            "serialNumber "=> $key['snum'],
                            
                            "firstName"=> $key['fname'],
                            
                            "lastName"=> $key['lname'],
                            
                            "middleName"=> $key['mname'],
                            
                            "email"=> $key['email'],
                            
                            "phone"=> preg_replace("/[^a-zA-Z0-9]/", "", $key['phone']),
                            
                            "gender"=> $key['gender'],
                            
                            "dateOfBirth"=> $key['dob'],
                            
                            "occupation"=> $key['occupation'],
                            
                            "employer"=> $key['employer'],
                            
                            "homeAddress"=> $key['addrs'],
                            
                            "city"=> $key['city'],
                            
                            "state"=> $key['state'],
                            
                            "country"=> $key['country'],
                            
                            "nextOfKinName"=> $key['nok'],
                            
                            "nextOfKinRelationship"=> $key['nok_rela'],
                            
                            "nextofKinPhoneNumber"=> $key['nok_phone'],
                            
                            "accountNumber"=> $key['account'],
                            
                            "userName"=> $key['username'],
                            
                            "ledgerBalance"=> $key['balance'],
                            
                            "investmentBalance"=> $key['investment_bal'],
                            
                            "walletBalance"=> $key['wallet_balance'],
                            
                            "acctountStatus"=> $key['acct_status'],
                            
                            "accountOfficer"=> $myStaffName,
                            
                            "merchantName"=> $companyName,
                            
                            "evn"=> $key['evn'],
                            
                            "bvn"=> $key['unumber'],
                            
                            "otpOption"=> $key['opt_option'],
                            
                            "allowOverdraft"=> $key['overdraft'],
                            
                            "currency"=> $key['currency'],
                            
                            "accountOpeningDate"=> $key['acct_opening_date'],
                            
                            ],
                            
                        "savingsCulture"=> [
                            
                                "countributionInterval"=> $key['s_contribution_interval'],
                            
                                "savingsAmount"=> $key['savings_amount'],
                                
                                "chargeInterval"=> $key['charge_interval'],
                                
                                "chargesAmount"=> $key['chargesAmount'],
                                
                                "disbursementInterval"=> $key['disbursement_interval'],
                                
                                "disbursementChannel"=> $key['disbursement_channel'],
                                
                                "autoDisbursementStatus"=> $key['auto_disbursement_status'],
                                
                                "autoChargeStatus"=> $key['auto_charge_status'],
                                
                                "nextChargeDate"=> $key['next_charge_date'],
                                
                                "nextDisbursementDate"=> $key['next_disbursement_date'],
                            
                            ]
                        
                        ];
                    
                        $i++;
                    
                }
                return $output2;
                
                
            }
            
        }else{
            
            return [
                array(
                    "responseCode"=> "01",
                    "message"=> "no data found"
                    )
                ];
            
        }

    }


    //FETCH Daily / Weekly / Monthly / Yearly CUSTOMERS
    public function fetchCustomerByFreq($frequency,$registeral,$companyName,$irole,$reg_staffid,$reg_branch) {
        
        //Daily Calculation
        $dt_min = new DateTime("last saturday"); // Edit
        $dt_min->modify('+1 day'); // Edit
        $dt_max = clone($dt_min);
        $dt_max->modify('+6 days');

        //Weekly / Monthly Calculation
        $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
        $last_day_this_month  = date('Y-m-t'); //get last day in a month

        $startDate = (($frequency == "Daily") ? date("Y-m-d") : (($frequency == "Weekly") ? $dt_min->format('Y-m-d') : (($frequency == "Monthly") ? date('Y-m-01', strtotime($first_day_this_month)) : (($frequency == "Yearly") ? date("Y-01-01") : ''))));
        $endDate = (($frequency == "Daily") ? date("Y-m-d") : (($frequency == "Weekly") ? $dt_max->format('Y-m-d') : (($frequency == "Monthly") ? date('Y-m-t', strtotime($last_day_this_month)) : (($frequency == "Yearly") ? date("Y-12-t", strtotime($startDate)) : ''))));

        $parameter = "";
        $searchRole = ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $this->db->fetchRole($irole,$parameter) : $this->db->fetchRole($irole,$registeral);
        $view_all_customers = $searchRole['view_all_customers'];
        $individual_customer_records = $searchRole['individual_customer_records'];
        $branch_customer_records = $searchRole['branch_customer_records'];

        ($view_all_customers == "1" && $individual_customer_records != "1" && $branch_customer_records != "1") ? $query = "SELECT * FROM borrowers WHERE acct_opening_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' ORDER BY id DESC" : "";
        ($view_all_customers != "1" && $individual_customer_records == "1" && $branch_customer_records != "1") ? $query = "SELECT * FROM borrowers WHERE acct_opening_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND lofficer = '$reg_staffid' ORDER BY id DESC" : "";
        ($view_all_customers != "1" && $individual_customer_records != "1" && $branch_customer_records == "1") ? $query = "SELECT * FROM borrowers WHERE acct_opening_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' ORDER BY id DESC" : "";

        $myOutput = $this->db->fetchAll($query, $registeral);
        
        $countOutput = count($myOutput);
        
        if($countOutput >= 1){
        
            for($i = 0; $i <= $countOutput; $i++){
                
                foreach($myOutput as $putEntry => $key){
                    
                    $staffID = $key['lofficer'];
            
                    $searchStaff = $this->db->fetchStaff($staffID,$registeral);
                    
                    $myStaffName = $searchStaff['name'].' '.$searchStaff['lname'];
                    
                    $output2[$i] = [
                        
                        "basicInfo"=> [
                            
                            "id"=> $key['id'],
                        
                            "serialNumber "=> $key['snum'],
                            
                            "firstName"=> $key['fname'],
                            
                            "lastName"=> $key['lname'],
                            
                            "middleName"=> $key['mname'],
                            
                            "email"=> $key['email'],
                            
                            "phone"=> preg_replace("/[^a-zA-Z0-9]/", "", $key['phone']),
                            
                            "gender"=> $key['gender'],
                            
                            "dateOfBirth"=> $key['dob'],
                            
                            "occupation"=> $key['occupation'],
                            
                            "employer"=> $key['employer'],
                            
                            "homeAddress"=> $key['addrs'],
                            
                            "city"=> $key['city'],
                            
                            "state"=> $key['state'],
                            
                            "country"=> $key['country'],
                            
                            "nextOfKinName"=> $key['nok'],
                            
                            "nextOfKinRelationship"=> $key['nok_rela'],
                            
                            "nextofKinPhoneNumber"=> $key['nok_phone'],
                            
                            "accountNumber"=> $key['account'],
                            
                            "userName"=> $key['username'],
                            
                            "ledgerBalance"=> $key['balance'],
                            
                            "investmentBalance"=> $key['investment_bal'],
                            
                            "walletBalance"=> $key['wallet_balance'],
                            
                            "acctountStatus"=> $key['acct_status'],
                            
                            "accountOfficer"=> $myStaffName,
                            
                            "merchantName"=> $companyName,
                            
                            "evn"=> $key['evn'],
                            
                            "bvn"=> $key['unumber'],
                            
                            "otpOption"=> $key['opt_option'],
                            
                            "allowOverdraft"=> $key['overdraft'],
                            
                            "currency"=> $key['currency'],
                            
                            "accountOpeningDate"=> $key['acct_opening_date'],
                            
                            ],
                            
                        "savingsCulture"=> [
                            
                                "countributionInterval"=> $key['s_contribution_interval'],
                            
                                "savingsAmount"=> $key['savings_amount'],
                                
                                "chargeInterval"=> $key['charge_interval'],
                                
                                "chargesAmount"=> $key['chargesAmount'],
                                
                                "disbursementInterval"=> $key['disbursement_interval'],
                                
                                "disbursementChannel"=> $key['disbursement_channel'],
                                
                                "autoDisbursementStatus"=> $key['auto_disbursement_status'],
                                
                                "autoChargeStatus"=> $key['auto_charge_status'],
                                
                                "nextChargeDate"=> $key['next_charge_date'],
                                
                                "nextDisbursementDate"=> $key['next_disbursement_date'],
                            
                            ]
                        
                        ];
                    
                        $i++;
                    
                }
                return [

                    "responseCode"=> "00",
                    
                    "status" => "Success",

                    "userData" => $output2

                ];
                
                
            }
            
        }else{
            
            return -1; //Data not found
            
        }

    }


    /** ENDPOINT URL TO FETCH ALL CUSTOMER WITH DATE:
     * 
     * {
     *  "startDate" : "2020-01-01",
     *  "endDate" : "2020-20-01"
    *  }
     * 
     * */
    public function fetchCustomerByDate($parameter, $registeral,$companyName,$irole,$reg_staffid,$reg_branch) {
        
        if(isset($parameter->startDate) && isset($parameter->endDate)) {

            $startDate = $parameter->startDate;
            $endDate = $parameter->endDate;

            $parameter1 = "";
            $searchRole = ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $this->db->fetchRole($irole,$parameter1) : $this->db->fetchRole($irole,$registeral);
            $view_all_customers = $searchRole['view_all_customers'];
            $individual_customer_records = $searchRole['individual_customer_records'];
            $branch_customer_records = $searchRole['branch_customer_records'];

            ($view_all_customers == "1" && $individual_customer_records != "1" && $branch_customer_records != "1") ? $query = "SELECT * FROM borrowers WHERE acct_opening_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' ORDER BY id DESC" : "";
            ($view_all_customers != "1" && $individual_customer_records == "1" && $branch_customer_records != "1") ? $query = "SELECT * FROM borrowers WHERE acct_opening_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND lofficer = '$reg_staffid' ORDER BY id DESC" : "";
            ($view_all_customers != "1" && $individual_customer_records != "1" && $branch_customer_records == "1") ? $query = "SELECT * FROM borrowers WHERE acct_opening_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' ORDER BY id DESC" : "";

            $myOutput = $this->db->fetchAll($query, $registeral);
            $countOutput = count($myOutput);

            if($myStartDate === "" || $myEndDate === ""){

                return -1; //empty parameters

            }elseif($countOutput >= 1){
        
                for($i = 0; $i <= $countOutput; $i++){
                    
                    foreach($myOutput as $putEntry => $key){
                        
                        $staffID = $key['lofficer'];
                
                        $searchStaff = $this->db->fetchStaff($staffID,$registeral);
                        
                        $myStaffName = $searchStaff['name'].' '.$searchStaff['lname'];
                        
                        $output2[$i] = [
                            
                            "basicInfo"=> [
                                
                                "id"=> $key['id'],
                            
                                "serialNumber "=> $key['snum'],
                                
                                "firstName"=> $key['fname'],
                                
                                "lastName"=> $key['lname'],
                                
                                "middleName"=> $key['mname'],
                                
                                "email"=> $key['email'],
                                
                                "phone"=> preg_replace("/[^a-zA-Z0-9]/", "", $key['phone']),
                                
                                "gender"=> $key['gender'],
                                
                                "dateOfBirth"=> $key['dob'],
                                
                                "occupation"=> $key['occupation'],
                                
                                "employer"=> $key['employer'],
                                
                                "homeAddress"=> $key['addrs'],
                                
                                "city"=> $key['city'],
                                
                                "state"=> $key['state'],
                                
                                "country"=> $key['country'],
                                
                                "nextOfKinName"=> $key['nok'],
                                
                                "nextOfKinRelationship"=> $key['nok_rela'],
                                
                                "nextofKinPhoneNumber"=> $key['nok_phone'],
                                
                                "accountNumber"=> $key['account'],
                                
                                "userName"=> $key['username'],
                                
                                "ledgerBalance"=> $key['balance'],
                                
                                "investmentBalance"=> $key['investment_bal'],
                                
                                "walletBalance"=> $key['wallet_balance'],
                                
                                "acctountStatus"=> $key['acct_status'],
                                
                                "accountOfficer"=> $myStaffName,
                                
                                "merchantName"=> $companyName,
                                
                                "evn"=> $key['evn'],
                                
                                "bvn"=> $key['unumber'],
                                
                                "otpOption"=> $key['opt_option'],
                                
                                "allowOverdraft"=> $key['overdraft'],
                                
                                "currency"=> $key['currency'],
                                
                                "accountOpeningDate"=> $key['acct_opening_date'],
                                
                                ],
                                
                            "savingsCulture"=> [
                                
                                    "countributionInterval"=> $key['s_contribution_interval'],
                                
                                    "savingsAmount"=> $key['savings_amount'],
                                    
                                    "chargeInterval"=> $key['charge_interval'],
                                    
                                    "chargesAmount"=> $key['chargesAmount'],
                                    
                                    "disbursementInterval"=> $key['disbursement_interval'],
                                    
                                    "disbursementChannel"=> $key['disbursement_channel'],
                                    
                                    "autoDisbursementStatus"=> $key['auto_disbursement_status'],
                                    
                                    "autoChargeStatus"=> $key['auto_charge_status'],
                                    
                                    "nextChargeDate"=> $key['next_charge_date'],
                                    
                                    "nextDisbursementDate"=> $key['next_disbursement_date'],
                                
                                ]
                            
                            ];
                        
                            $i++;
                        
                    }
                    return [
    
                        "responseCode"=> "00",
                        
                        "status" => "Success",
    
                        "userData" => $output2
    
                    ];
                    
                    
                }
                
            }else{
            
                return -3; //Data not found
                
            }

        }else{

            return -2; //invalid Json

        }

    }



    /** ENDPOINT URL FOR CUSTOMER REGISTRATION:
    * 
     * {
	*   "accountNumber" : "99294788939",
    *  }
     * 
     * */

    public function verifyCustomerAcctNo($parameter, $registeral) {

        if(isset($parameter->accountNumber)) {

            $accountNumber = $parameter->accountNumber;

            $verifyCustomer = $this->db->verifyCustAccount($accountNumber,$registeral);

            if($accountNumber == ""){

                return -1;

            }elseif($verifyCustomer === 0){

                return [
                    array(
                        "responseCode"=> "01",
                        "message"=> "Invalid Account Number"
                        )
                    ];

            }else{
                
                $query = "SELECT * FROM borrowers WHERE account = '$accountNumber' AND branchid = '$registeral'";
                $myOutput = $this->db->fetchById($query, $accountNumber);

                foreach($myOutput as $putEntry => $key){
                    
                    $output2 = [
                        "responseCode"=> "00",
                        "message"=> "Account Verified Successfully",
                        "firtName"=> $myOutput['fname'],
                        "lastName"=> $myOutput['lname'],
                        "phoneNumber"=> $myOutput['phone'],
                        "acctCurrency"=> $myOutput['currency'],
                        "ledgerBalance"=> $myOutput['balance'],
                        ];
                        
                    return $output2;
                    
                }

            }

        }else{

            return -2;

        }

    }


    /** ENDPOINT URL FOR CUSTOMER REGISTRATION:
    * 
     * {
     *  "snum" : "ABK/001",
     *  "groupCode" : "30",
     *  "groupPosition" : "member",
    *   "picture" : base64,
	*   "fname" : "Akingbade",
	*   "lname" : "Tomos",
    *   "mname" : "Akeem",
	*   "email" : "akingbade@gm.com",
	*   "phone" : "081022222222",
	*   "gender" : "Male",
	*   "dob" : "1960-12-11",
	*   "addrs" : "No. 10 Brown lane",
	*   "city" : "Fadeyi",
	*   "state" : "Lagos",
	*   "country" : "NG",
	*   "username" : "testing123",
	*   "password" : "test",
    *   "ePin" : "xxx"
    *  }
     * 
     * */

    public function insertPcCustomer($parameter, $registeral, $reg_branch, $reg_staffName, $reg_staffid, $companyName, $allow_auth, $mytpin, $cust_reg) {

        if(isset($parameter->fname) && isset($parameter->lname) && isset($parameter->phone) && isset($parameter->gender) && isset($parameter->dob) && isset($parameter->username) && isset($parameter->ePin)) {

            $snum = $parameter->snum;
            $picture = !isset($parameter->picture) ? "" : $parameter->picture;
            $fname = $parameter->fname;
            $lname = $parameter->lname;
            $mname = $parameter->mname;
            $email = $parameter->email;
            $phone_no = $parameter->phone;
            $gender = $parameter->gender;
            $dob = $parameter->dob;
            $addrs = $parameter->addrs;
            $city = $parameter->city;
            $state = $parameter->state;
            $country = $parameter->country;
            $username = $parameter->username;
            $password = $parameter->password;
            $ePin = $parameter->ePin;
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
            $tpin = substr((uniqid(rand(),1)),3,4);
            $gname = !isset($parameter->groupCode) ? "" : $parameter->groupCode;
            $gposition = !isset($parameter->groupPosition) ? "" : $parameter->groupPosition;
            $reg_type = ($gname == "") ? "Individual" : "Group";
            $acct_status = ($allow_auth == "Yes") ? "Not-Activated" : "queue";
            $date_time = date("Y-m-d h:i:s");
            $acct_opening_date = date("Y-m-d");

            $resultsInfo = $this->db->executeCall($registeral);
            //SENDER ID
            $sysabb = ($resultsInfo['sender_id'] == "") ? "esusuafrica" : $resultsInfo['sender_id'];
            $currency = $resultsInfo['currency'];

            $searchMemSet = $this->db->fetchMemberSettings($registeral);    
            $acct_number = $searchMemSet['dedicated_ledgerAcctNo_prefix'].mt_rand(10000000,99999999);

            $searchEmailConfig = $this->db->fetchEmailSettings($registeral);
            $customDomain = ($searchEmailConfig['product_url'] == "") ? "https://esusu.app/".$sysabb : $searchEmailConfig['product_url'];
            $countSenderString = strlen($sysabb) + 1;
            $productURL = substr($customDomain, 0, -$countSenderString);
            $url = $productURL."/?acn=".$acct_number;
            $id = time();
            $shorturl = base_convert($id,20,36);
            $smsChecker = "Yes";
            $refid = uniqid("EA-custReg-").time();

            $verifyCustomer = $this->db->verifyCustomer($username,$phone_no);

            $sms = "$sysabb>>>Welcome $fname! Your Account ID: $acct_number, Username: $username, Password: $password, Transaction Pin: $tpin. Login at https://esusu.app/$sysabb";
            $details = 'SMS Content: '.$sms;

            //FETCH INSTITUTION WALLET BALANCE
            $getInst = $this->fetchInstitutionById($registeral);
            $inst_wallet = $getInst['wallet_balance'];

            //SMS CONTENT PRICE CALCULATION PER PAGE
            $max_per_page = 153;
            $sms_length = strlen($sms);
            $calc_length = ceil($sms_length / $max_per_page);
            $cust_charges = $resultsInfo['cust_mfee'];
            $sms_charges = $calc_length * $cust_charges;
            $mywallet_balance = $inst_wallet - $sms_charges;
            
            $verifySMS_Provider = $this->fetchSMSGW1($registeral);
            $verifySMS_Provider1 = $this->fetchSMSGW2("Activated");
            $fetchSMS_Provider = ($verifySMS_Provider === 0) ? $verifySMS_Provider1 : $verifySMS_Provider;
            $ozeki_password = $fetchSMS_Provider['password'];
            $ozeki_url = $fetchSMS_Provider['api'];
            $debitWallet = ($verifySMS_Provider === 0) ? "Yes" : "No";
            $sendSmsStatus = ($phone_no == "") ? "0" : "1";
            $sendEmailStatus = ($email == "") ? "0" : "1";
            //$checkAuth = $this->accessKey($tokenId,$registeral);

            if($fname == "" || $lname == "" || $mname == "" || $phone_no == "" || $gender == "" || $dob == "" || $username == "" || $ePin == ""){

                return -3;

            }elseif($ePin != $mytpin){

                return -4;

            }
            elseif($verifyCustomer === 1){

                return -2;

            }else{

                //CONVERT FROM BASE64 TO IMAGE
                $dynamicStr = md5(date("Y-m-d h:i"));
                $pixConverted = ($picture == "") ? "" : $this->db->base64Tojpeg($picture, $dynamicStr.".png");

                //INSERT CUSTOMER RECORD
                $myQuery = "INSERT INTO borrowers (snum, fname, lname, mname, email, phone, gender, dob, addrs, city, state, country, username, password, community_role, account, balance, target_savings_bal, investment_bal, loan_balance, asset_acquisition_bal, image, last_withdraw_date, status, lofficer, opt_option, currency, wallet_balance, overdraft, card_id, card_reg, tpin, reg_type, gname, gposition, branchid, sbranchid, acct_status, date_time, acct_opening_date, sms_checker, sendSMS, sendEmail) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertCustomerPc($myQuery, $snum, $fname, $lname, $mname, $email, $phone_no, $gender, $dob, $addrs, $city, $state, $country, $username, $password, $community_role, $acct_number, $ledger_bal, $target_bal, $invst_bal, $loan_bal, $asset_bal, $pixConverted, $last_with_date, $status, $reg_staffid, $otp_option, $currency, $wallet_bal, $over_draft, $card_id, $card_reg, $tpin, $reg_type, $gname, $gposition, $registeral, $reg_branch, $acct_status, $date_time, $acct_opening_date, $smsChecker, $sendSmsStatus, $sendEmailStatus);
                
                //LOG ACTIVATION RECORDS
                ($allow_auth == "Yes") ? $query2 = "INSERT INTO activate_member (url, shorturl, attempt, acn) VALUES(?, ?, ?, ?)" : "";
                ($allow_auth == "Yes") ? $this->db->insertActivationCode($query2, $url, $shorturl, 'No', $acct_number) : "";

                //SMS NOTIFCATION
                ($allow_auth == "No" ? "" : ($allow_auth == "Yes" && $debitWallet == "No" ? $this->instGeneralAlert($ozeki_password, $ozeki_url, $debitWallet, $sysabb, $phone_no, $sms, $registeral, $refid, $sms_charges, $currency, $details, $reg_staffid, $mywallet_balance) : ($allow_auth == "Yes" && $debitWallet == "Yes" && $sms_charges <= $inst_wallet ? $this->instGeneralAlert($ozeki_password, $ozeki_url, $debitWallet, $sysabb, $phone_no, $sms, $registeral, $refid, $sms_charges, $currency, $details, $reg_staffid, $mywallet_balance) : "")));

                //EMAIL NOTIFICATION
                ($allow_auth == "Yes") ? $this->sendEmail($email, $fname, $username, $password, $acct_number, $registeral) : "";

                return [
                    
                    "responseCode"=> "00",
                    
                    "reg_status" => "Success",

                    "message" => "Account Registered Successfully",

                    "account_details" => [

                        "accountNumber" => $acct_number,

                        "firstName" => $fname,

                        "lastName" => $lname,

                        "middleName" => $mname,

                        "emailAddress" => $email,

                        "phoneNumber" => $phone_no,

                        "dateOfBirth" => $dob,

                        "gender" => $gender,

                        "userName" => $parameter->username,

                        "homeAddress" => $addrs,

                        "city" => $city,

                        "state" => $state,
                        
                        "country" => $country,

                        "registeredBy" => $reg_staffName,
                        
                        "companyName" => $companyName,

                        "accountOpeningDate" => $acct_opening_date,

                        "regDateTime" => $date_time,

                    ]

                ];

            }

        }else{
            return -1;
        }

    }


    //FETCH ALL USER CUSTOMER BY LIMIT (INT)
    public function fetchCustomerByLimit($parameter, $limit) {

        $query = "SELECT * FROM borrowers WHERE branchid = ? ORDER BY id LIMIT $limit";

        return $this->db->fetchAll($query, $parameter);

    }
    

    //FETCH ALL USER BRANCH CUSTOMERS USING BRANCHID (STRING)
    public function fetchBranchCustomer($parameter, $parameter1) {

        $query = "SELECT * FROM borrowers WHERE sbranchid = ? AND branchid = '$parameter1'";

        return $this->db->fetchAll($query, $parameter);

    }


    /** ENDPOINT URL FOR CUSTOMER REGISTRATION:
    * 
     * {
     *  "groupName" : "Ifesowapo",
     *  "maximumMember" : "30",
     *  "meetingFrequency" : "weekly",
    *   "ePin" : "xxx"
    *  }
     * 
     * */

    public function createGroup($parameter, $registeral, $mytpin) {

        if(isset($parameter->groupName) && isset($parameter->maximumMember) && isset($parameter->meetingFrequency) && isset($parameter->ePin)) {

            $groupName = $parameter->groupName;
            $maximumMember = $parameter->maximumMember;
            $meetingFrequency = strtolower($parameter->meetingFrequency);
            $ePin = $parameter->ePin;
            $date_time = date('Y-m-d h:i:s');

            if($groupName == "" || $maximumMember == "" || $meetingFrequency == "" || $ePin == ""){

                return -1;

            }elseif($ePin != $mytpin){

                return -2;

            }else{

                //INSERT CUSTOMER RECORD
                $myQuery = "INSERT INTO lgroup_setup (merchant_id, gname, max_member, date_time, mfrequency) VALUES (?, ?, ?, ?, ?)";
                $this->db->insertGroup($myQuery, $registeral, $groupName, $maximumMember, $date_time, $meetingFrequency);

                return [
                    
                    "responseCode"=> "00",
                    
                    "reg_status" => "Success",

                    "message" => "Group Created Successfully",

                    "groupDetails" => [

                        "groupName" => $groupName,

                        "maxMember" => $maximumMember,

                        "meetingFrequecy" => $meetingFrequency,

                        "regDateTime" => $date_time,

                    ]

                ];

            }

        }else{

            return -3;

        }

    }


    //FETCH ALL USER CUSTOMERS
    public function fetchAllGroup($registeral, $companyName) {
        
        $query = "SELECT * FROM lgroup_setup WHERE merchant_id = '$registeral' ORDER BY id DESC";

        $myOutput = $this->db->fetchAll($query, $registeral);
        
        $countOutput = count($myOutput);
        
        if($countOutput >= 1){
        
            for($i = 0; $i <= $countOutput; $i++){
                
                foreach($myOutput as $putEntry => $key){
                    
                    $staffID = $key['lofficer'];
            
                    $output2[$i] = [
                        
                        "groupCode"=> $key['id'],
                        
                        "groupName "=> $key['gname'],
                            
                        "maxMember"=> $key['max_member'],
                            
                        "meetingFreq"=> $key['mfrequency'],
                            
                        "dateCreated"=> $key['date_time'],

                        "merchantName"=> $companyName
                        
                    ];
                    $i++;
                    
                }
                return [

                    "responseCode"=> "00",
                    
                    "reg_status" => "Success",

                    "message" => "Data Fetched Successfully",

                    "groupInfo"=> $output2

                ];
                
            }
            
        }else{
            
            return -1;
            
        }

    }


}

?>