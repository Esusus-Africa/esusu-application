<?php

class Wallet extends User {
    
    //Bank Transfer Alert
    public function bankTransferEmailNotifier($senderEmail, $merchantEmail, $tReference, $senderName, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $icurrency, $amountWithNoCharges, $senderBalance, $registeral){
            
        $result = array();
        
        //FETCH SYSTEMSET FOR EMAIL DELIVERY CREDENTIALS
        $emailCredentials = $this->db->fetchSystemSet();
        $email_from = $emailCredentials['email_from'];
        $product_name = $emailCredentials['name'];
        $website = $emailCredentials['website'];
        $logo_url = $emailCredentials['logo_url'];
        $support_email = $emailCredentials['email'];
        $live_chat_url = $emailCredentials['live_chat'];
        $company_address = $emailCredentials['address'];
        $email_token = $emailCredentials['email_token'];
        $brand_color = $emailCredentials['brand_color'];

        $correctdate = date('m/d/Y').' '.(date('h') + 1).':'.date('i A');

        $fetch_emailConfig = $this->db->fetchEmailConfig($registeral);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
        
        // Pass the customer's authorisation code, email and amount
        $postdata =  array(
        "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $email_from : $fetch_emailConfig['email_from']),
        "To"            => "admin@esusu.africa,".$senderEmail.",".$merchantEmail,  //User Email Address
        "TemplateId"    => '21373949',
        "TemplateModel" => [
            "txid"              => $tReference, //Transaction ID
            "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $brand_color : $fetch_emailConfig['brand_color']),
            "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $logo_url : $fetch_emailConfig['logo_url']),
            "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $website : $fetch_emailConfig['product_url']),
            "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
            "acct_name"         => $senderName, //User Full Name
            "trans_date"        => $correctdate, //Transaction date
            "platform_name"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']), //Platform Name
            "recipient_name"    => $accountName, //Recipient Name
            "recipient_acctno"  => $recipientAcctNo, //Recipient Account Number
            "recipient_bankname"=> $mybank_name, //Recipient Bank Name
            "merchant_name"     => $merchantName,
            "amount"            => $icurrency.number_format($amountWithNoCharges,2,'.',','), //Amount Transfer
            "wallet_balance"    => $icurrency.number_format($senderBalance,2,'.',','), //Remaining Balance Left
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


    //P2p_BwalletFunding - Credit
    public function walletCreditEmailNotifier($merchantEmail, $txid, $final_date_time, $inst_name, $myname, $account, $icurrency, $amount, $totalwallet_balance, $registeral){
            
        $result = array();
        
        //FETCH SYSTEMSET FOR EMAIL DELIVERY CREDENTIALS
        $emailCredentials = $this->db->fetchSystemSet();
        $email_from = $emailCredentials['email_from'];
        $product_name = $emailCredentials['name'];
        $website = $emailCredentials['website'];
        $logo_url = $emailCredentials['logo_url'];
        $support_email = $emailCredentials['email'];
        $live_chat_url = $emailCredentials['live_chat'];
        $company_address = $emailCredentials['address'];
        $email_token = $emailCredentials['email_token'];
        $brand_color = $emailCredentials['brand_color'];

        $correctdate = date('m/d/Y').' '.(date('h') + 1).':'.date('i A');

        $fetch_emailConfig = $this->db->fetchEmailConfig($registeral);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
        
        // Pass the customer's authorisation code, email and amount
        $postdata =  array(
        "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $email_from : $fetch_emailConfig['email_from']),
        "To"            => "admin@esusu.africa,".$merchantEmail,  //User Email Address
        "TemplateId"    => '10291027',
        "TemplateModel" => [
            "txid"              => $txid, //Transaction ID
            "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $brand_color : $fetch_emailConfig['brand_color']),
            "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $logo_url : $fetch_emailConfig['logo_url']),
            "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $website : $fetch_emailConfig['product_url']),
            "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
            "acct_name"         => $myname, //User Full Name
            "trans_date"        => $final_date_time, //Transaction date
            "platform_name"     => $inst_name, //Platform Name
            "account_id"        => $account, //User Account ID
            "amount"            => $icurrency.number_format($amount,2,'.',','), //Amount Transfer
            "wallet_balance"    => $icurrency.number_format($totalwallet_balance,2,'.',','), //Wallet Balance (p2p-wallet-transfer)
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


    //P2p_BwalletFunding - Debit
    public function walletDebitEmailNotifier($senderEmail, $merchantEmail, $txid, $final_date_time, $inst_name, $myname, $account, $icurrency, $amount, $totalwallet_balance, $clientId){
            
        $result = array();
        
        //FETCH SYSTEMSET FOR EMAIL DELIVERY CREDENTIALS
        $emailCredentials = $this->db->fetchSystemSet();
        $email_from = $emailCredentials['email_from'];
        $product_name = $emailCredentials['name'];
        $website = $emailCredentials['website'];
        $logo_url = $emailCredentials['logo_url'];
        $support_email = $emailCredentials['email'];
        $live_chat_url = $emailCredentials['live_chat'];
        $company_address = $emailCredentials['address'];
        $email_token = $emailCredentials['email_token'];
        $brand_color = $emailCredentials['brand_color'];

        $correctdate = date('m/d/Y').' '.(date('h') + 1).':'.date('i A');

        $fetch_emailConfig = $this->db->fetchEmailConfig($clientId);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
        
        // Pass the customer's authorisation code, email and amount
        $postdata =  array(
        "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $email_from : $fetch_emailConfig['email_from']),
        "To"            => "admin@esusu.africa,".$senderEmail.",".$merchantEmail,  //User Email Address
        "TemplateId"    => '20045888',
        "TemplateModel" => [
            "txid"              => $txid, //Transaction ID
            "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $brand_color : $fetch_emailConfig['brand_color']),
            "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $logo_url : $fetch_emailConfig['logo_url']),
            "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $website : $fetch_emailConfig['product_url']),
            "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
            "acct_name"         => $myname, //User Full Name
            "trans_date"        => $final_date_time, //Transaction date
            "platform_name"     => $inst_name, //Platform Name
            "account_id"        => $account, //User Account ID
            "amount"            => $icurrency.number_format($amount,2,'.',','), //Amount Transfer
            "wallet_balance"    => $icurrency.number_format($totalwallet_balance,2,'.',','), //Wallet Balance (p2p-wallet-transfer)
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


    //Airtime/Data Vending Alert
    public function airtimeDateEmailNotifier($senderEmail, $merchantEmail, $tReference, $senderName, $type, $phone, $operator, $merchantName, $icurrency, $amountWithNoCharges, $amountCharged, $senderBalance, $registeral){
            
        $result = array();
        
        //FETCH SYSTEMSET FOR EMAIL DELIVERY CREDENTIALS
        $emailCredentials = $this->db->fetchSystemSet();
        $email_from = $emailCredentials['email_from'];
        $product_name = $emailCredentials['name'];
        $website = $emailCredentials['website'];
        $logo_url = $emailCredentials['logo_url'];
        $support_email = $emailCredentials['email'];
        $live_chat_url = $emailCredentials['live_chat'];
        $company_address = $emailCredentials['address'];
        $email_token = $emailCredentials['email_token'];
        $brand_color = $emailCredentials['brand_color'];

        $correctdate = date('m/d/Y').' '.(date('h') + 1).':'.date('i A');

        $fetch_emailConfig = $this->db->fetchEmailConfig($registeral);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
        
        // Pass the customer's authorisation code, email and amount
        $postdata =  array(
        "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $email_from : $fetch_emailConfig['email_from']),
        "To"            => "admin@esusu.africa,".$senderEmail.",".$merchantEmail,  //User Email Address
        "TemplateId"    => '24308889',
        "TemplateModel" => [
            "type"              => $type,
            "txid"              => $tReference, //Transaction ID
            "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $brand_color : $fetch_emailConfig['brand_color']),
            "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $logo_url : $fetch_emailConfig['logo_url']),
            "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $website : $fetch_emailConfig['product_url']),
            "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
            "acct_name"         => $senderName, //User Full Name
            "trans_date"        => $correctdate, //Transaction date
            "platform_name"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']), //Platform Name
            "mobile_phone"      => $phone, //Recipient Name
            "operator"          => $operator, //Recipient Account Number
            "merchant_name"     => $merchantName,
            "amount"            => $icurrency.number_format($amountWithNoCharges,2,'.',','), //Amount Transfer
            "amount_charged"    => $icurrency.number_format($amountCharged,2,'.',','), //Amount Transfer
            "wallet_balance"    => $icurrency.number_format($senderBalance,2,'.',','), //Remaining Balance Left
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


    /** ENDPOINT URL TO GENERATE WALLET ACCOUNT IS:
     * 
     * v1/wallet/createWallet    : To generate wallet account for existing customer within the system with the following required field:
     * 
     * {
     *  "ledgerAccountNumber": "2222222222",
     *  "ePin": "xxx"
    *  }
     * 
     * */
    public function generateWalletAccount($parameter, $registeral, $reg_branch, $reg_staffName, $reg_staffid, $mytpin){

        if(isset($parameter->ledgerAccountNumber) && isset($parameter->ePin)) {

            $ledgerAccountNumber = $parameter->ledgerAccountNumber;
            $ePin = $parameter->ePin;
            $myAccountReference = uniqid("EAVA-").time();

            $searchVA = $this->fetchVAByAcctNo($ledgerAccountNumber);
            $verifyCustAcct = $this->db->verifyCustAccount($acctno,$registeral);

            if($ledgerAccountNumber == "" || $ePin == ""){

                return -1; //Missing Parameters;

            }if($searchVA != 0){

                return -2; //User already has wallet account;

            }elseif($verifyCustAcct === 0){

                return -3; //Invalid Ledger Account Number

            }elseif($ePin != $mytpin){

                return -4; //Pin not matched

            }else{

                $custDetails = $this->db->fetchCustomer($acctno, $registeral);
                $userBvn = $custDetails['unumber'];
                $accountName = $custDetails['fname'].' '.$custDetails['lname'];
                $email = $custDetails['email'];

                //GENERATE WALLET ACCOUNT
                $generateAcct = $this->virtualAccountCreation($accountName,$userBvn,$email,$myAccountReference,$registeral,$ledgerAccountNumber,$reg_staffid);
                $myAccountName = $generateAcct['accountName'];
                $myAccountNumber = $generateAcct['accountNumber'];
                $myBankName = $generateAcct['bankName'];

                return [

                    "responseCode"=> "00",
                    
                    "status" => "Success",

                    "message" => "Wallet Generated Successfully",

                    "data" => [

                        "accountRef" => $myAccountReference,
                            
                        "accountNumber" => $myAccountNumber,

                        "accountName" => $myAccountName,

                        "bankName" => $myBankName

                    ]

                ];

            }

        }else{

            return -5; //Invalid Json request

        }

    }


    //FETCH Daily / Weekly / Monthly / Yearly WALLET ACCOUNT
    public function fetchWalletAccount($frequency, $registeral, $reg_staffid, $reg_branch, $companyName, $irole) {

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

        $parameter = "";
        $searchRole = ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $this->db->fetchRole($irole,$parameter) : $this->db->fetchRole($irole,$registeral);
        $list_wallet = $searchRole['list_wallet'];
        $individual_wallet = $searchRole['individual_wallet'];
        $branch_wallet = $searchRole['branch_wallet'];
        //$branch_customer_records = $searchRole['branch_customer_records'];

        ($list_wallet == "1" && $individual_wallet != "1" && $branch_wallet != "1") ? $query = "SELECT * FROM virtual_account WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$registeral' AND status = 'ACTIVE' ORDER BY id DESC" : "";
        ($list_wallet != "1" && $individual_wallet == "1" && $branch_wallet != "1") ? $query = "SELECT * FROM virtual_account WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$registeral' AND acctOfficer = '$reg_staffid' AND status = 'ACTIVE' ORDER BY id DESC" : "";
        $output = $this->db->fetchAll($query, $registeral);
        $countOutput = count($output);

        if($countOutput >= 1){

            for($i = 0; $i <= $countOutput; $i++){

                foreach($output as $putEntry => $key){

                    $output2[$i] = [

                        "accountRef" => $key['account_ref'],

                        "accountName" => $key['account_name'],

                        "accountNumber" => $key['account_number'],

                        "bankName" => $key['bank_name'],

                        "verificationStatus" => $key['acct_status'],

                        "transferLimitPerDay" => $key['transferLimitPerDay'],

                        "transferLimitPerTrans" => $key['transferLimitPerTrans'],

                        "airtimeDataLimitPerDay" => $key['airtime_dataLimitPerDay'],

                        "airtimeDataLimitPerTrans" => $key['airtime_dataLimitPerTrans'],

                        "merchantName" => $companyName
                        
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
            
            return -1; //no data found
            
        }

    }


    /** ENDPOINT URL TO FETCH WALLET ACCOUNT WITH DATE:
     * 
     * {
     *  "startDate" : "2020-01-01",
     *  "endDate" : "2020-20-01"
    *  }
     * 
     * */
    public function fetchWalletAccountByDate($parameter, $registeral, $reg_staffid, $irole, $reg_branch, $companyName) {

        if(isset($parameter->startDate) && isset($parameter->endDate)) {

            $myStartDate = $parameter->startDate;
            $myEndDate = $parameter->endDate;

            $startDate = $myStartDate.' 00:00:00'; // get start date from here
            $endDate = $myEndDate.' 24:00:00';

            ($list_wallet == "1" && $individual_wallet != "1" && $branch_wallet != "1") ? $query = "SELECT * FROM virtual_account WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$registeral' AND status = 'ACTIVE' ORDER BY id DESC" : "";
            ($list_wallet != "1" && $individual_wallet == "1" && $branch_wallet != "1") ? $query = "SELECT * FROM virtual_account WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$registeral' AND acctOfficer = '$reg_staffid' AND status = 'ACTIVE' ORDER BY id DESC" : "";
            $output = $this->db->fetchAll($query, $registeral);
            $countOutput = count($output);

            if($myStartDate === "" || $myEndDate === ""){

                return -1; //empty field

            }elseif($countOutput >= 1){

                for($i = 0; $i <= $countOutput; $i++){
    
                    foreach($output as $putEntry => $key){

                        $output2[$i] = [

                            "accountRef" => $key['account_ref'],

                            "accountName" => $key['account_name'],

                            "accountNumber" => $key['account_number'],

                            "bankName" => $key['bank_name'],

                            "verificationStatus" => $key['acct_status'],

                            "transferLimitPerDay" => $key['transferLimitPerDay'],

                            "transferLimitPerTrans" => $key['transferLimitPerTrans'],

                            "airtimeDataLimitPerDay" => $key['airtime_dataLimitPerDay'],

                            "airtimeDataLimitPerTrans" => $key['airtime_dataLimitPerTrans'],

                            "merchantName" => $companyName
                            
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
                
                return -3; //no data found
                
            }

        }else{

            return -2; //invalid json format

        }

    }


    //FETCH Daily / Weekly / Monthly / Yearly WALLET HISTORY
    public function fetchWalletHistory($frequency, $registeral, $reg_staffid, $reg_branch, $companyName, $irole) {

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

        //$parameter = "";
        //$searchRole = ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $this->db->fetchRole($irole,$parameter) : $this->db->fetchRole($irole,$registeral);
        //$view_all_transaction = $searchRole['view_all_transaction'];
        //$individual_transaction_records = $searchRole['individual_transaction_records'];
        //$branch_transaction_records = $searchRole['branch_transaction_records'];

        ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $query = "SELECT * FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$registeral' ORDER BY id DESC" : "";
        ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin") ? $query = "SELECT * FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$registeral' AND (initiator = '$reg_staffid' OR recipient = '$reg_staffid') ORDER BY id DESC" : "";
        $output = $this->db->fetchAll($query, $registeral);
        $countOutput = count($output);

        if($countOutput >= 1){

            for($i = 0; $i <= $countOutput; $i++){

                foreach($output as $putEntry => $key){

                    $output2[$i] = [

                        "amount" => ($key['credit'] == "") ? number_format($key['debit'],2,'.',',') : number_format($key['credit'],2,'.',','),
                                    
                        "txnDate" => date("Y-m-d", strtotime($key['date_time'])),
                        
                        "paymentRef" => $key['refid'],
                                    
                        "eventDateTime" => $key['date_time'],

                        "counterpartyAccountId" => $account_no,

                        "drcr" => ($key['credit'] == "") ? "D" : "C",

                        "counterpartyBank" => $companyName,

                        "accounNname" => $customer['lname'].' '.$customer['fname'],
                        
                        "trnType" => $key['paymenttype'],

                        "narration" => $key['remark']
                        
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


    /** ENDPOINT URL TO FETCH WALLET HISTORY WITH DATE:
     * 
     * {
     *  "startDate" : "2020-01-01",
     *  "endDate" : "2020-20-01"
    *  }
     * 
     * */
    public function fetchWalletHistoryByDate($parameter, $registeral, $reg_staffid, $reg_branch, $companyName, $irole) {

        if(isset($parameter->startDate) && isset($parameter->endDate)) {

            $myStartDate = $parameter->startDate;
            $myEndDate = $parameter->endDate;

            $startDate = $myStartDate.' 00:00:00'; // get start date from here
            $endDate = $myEndDate.' 24:00:00';

            //$parameter = "";
            //$searchRole = ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $this->db->fetchRole($irole,$parameter) : $this->db->fetchRole($irole,$registeral);
            //$view_all_transaction = $searchRole['view_all_transaction'];
            //$individual_transaction_records = $searchRole['individual_transaction_records'];
            //$branch_transaction_records = $searchRole['branch_transaction_records'];

            ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $query = "SELECT * FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$registeral' ORDER BY id DESC" : "";
            ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin") ? $query = "SELECT * FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$registeral' AND (initiator = '$reg_staffid' OR recipient = '$reg_staffid') ORDER BY id DESC" : "";
            $output = $this->db->fetchAll($query, $registeral);
            $countOutput = count($output);

            if($myStartDate === "" || $myEndDate === ""){

                return -1; //empty parameters

            }elseif($countOutput >= 1){

                for($i = 0; $i <= $countOutput; $i++){
    
                    foreach($output as $putEntry => $key){

                        $output2[$i] = [

                            "amount" => ($key['credit'] == "") ? number_format($key['debit'],2,'.',',') : number_format($key['credit'],2,'.',','),
                                    
                            "txnDate" => date("Y-m-d", strtotime($key['date_time'])),
                            
                            "paymentRef" => $key['refid'],
                                        
                            "eventDateTime" => $key['date_time'],

                            "counterpartyAccountId" => $ledgerAccountNo,

                            "drcr" => ($key['credit'] == "") ? "D" : "C",

                            "counterpartyBank" => $companyName,

                            "accounNname" => $customer['lname'].' '.$customer['fname'],
                            
                            "trnType" => $key['paymenttype'],

                            "narration" => $key['remark']
                            
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
                
                return -3; //no data found
                
            }

        }else{

            return -2; //invalid json format

        }

    }


    //All Bank List Request
    public function allBankList($registeral){

        //FETCH MERCHANT CONFIGURATION FILE
        $searchMemSet = $this->db->fetchMemberSettings($registeral);
        $nip_route = $searchMemSet['nip_route'];

        //FETCH SYSTEMSET CREDENTIALS
        $systemSetCredentials = $this->db->fetchSystemSet();
        $accessToken = $systemSetCredentials['primeairtime_token'];

        if($nip_route == "PrimeAirtime"){ //PRIME AIRTIME GATEWAY

            $searchApi = $this->fetchApi("primeairtime_baseUrl");
            $api_url = $searchApi['api_url'].'/api/ft/banks';

            $header = [
                "Content-Type: application/json",
                "Authorization: Bearer ".$accessToken
            ];

            $bankListResponse = $this->callAPI("GET", $api_url, false, $header, 1);
            $bankList = json_decode($bankListResponse, true);

            if($bankList['count'] > 0){
                
                for($i = 0; $i <= $bankList['count']; $i++){

                    foreach($bankList['banks'] as $key){

                        $output2[$i] = [

                            "bankCode" => $key['sortCode'],

                            "bankName" => $key['name']

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

                return -1; //Data not found

            }

        }else{

            return -2; //You are not authorize to access this service at the moment

        }

    }


    //Name Enquiry for Bank Transfer Request
    public function bankNameEnquiry($bankCode, $accountNumber, $registeral){

        //FETCH MERCHANT CONFIGURATION FILE
        $searchMemSet = $this->db->fetchMemberSettings($registeral);
        $nip_route = $searchMemSet['nip_route'];

        //FETCH SYSTEMSET CREDENTIALS
        $systemSetCredentials = $this->db->fetchSystemSet();
        $accessToken = $systemSetCredentials['primeairtime_token'];

        if($nip_route == "PrimeAirtime"){ //PRIME AIRTIME GATEWAY

            $searchApi = $this->fetchApi("primeairtime_baseUrl");
            $api_url = $searchApi['api_url'].'/api/ft/lookup/'.$bankCode.'/'.$accountNumber;

            $header = [
                "Content-Type: application/json",
                "Authorization: Bearer ".$accessToken
            ];

            $nameEnqResponse = $this->callAPI("GET", $api_url, false, $header, 1);
            $nameEnq = json_decode($nameEnqResponse, true);
            
            return [

                "responseCode" => "00",
        
                "status" => "Success",

                "data" => [

                    "accountNumber" => $nameEnq['target_accountNumber'],

                    "accountName" => $nameEnq['target_accountName'],

                    "bankCode" => $nameEnq['target_bankCode'],

                    "bankName" => $nameEnq['target_bankName'],

                    "responseDateTime" => date("Y-m-d H:i:s")

                ]

            ];

        }else{

            return -1; //You are not authorize to access this service at the moment

        }

    }


    /** ENDPOINT URL TO MAKE FUND TRANSFER:
     * 
     * {
     *  "referenceId" : "hooli-tx-1920bbtytty",
	*   "amount" : "1000",
    *   "bankCode" : "044",
    *   "recipientAccountNumber" : "0066097011",
    *   "recipientName" : "James Micheal",
    *   "recipientBankName" : "ECOBANK",
    *   "narration" : "testing transfer",
    *   "tPin" : "1234"
    *  }
     * 
     * */
    public function fundTransfer($parameter, $registeral, $companyName, $reg_staffid, $mytpin, $reg_mEmail, $reg_fName, $availableWalletBal) {

        if(isset($parameter->referenceId) && isset($parameter->amount) && isset($parameter->bankCode) && isset($parameter->recipientAccountNumber) && isset($parameter->recipientName) && isset($parameter->recipientBankName) && isset($parameter->narration) && isset($parameter->tPin)) {

            $referenceId = $parameter->referenceId;
            $amount = preg_replace('/[^0-9.]/', '', $parameter->amount);
            $bankCode = $parameter->bankCode;
            $rAccountNumber = $parameter->recipientAccountNumber;
            $rName = $parameter->recipientName;
            $rBankName = $parameter->recipientBankName;
            $narration = $parameter->narration;
            $tPin = $parameter->tPin;
            $transactionDateTime = date("Y-m-d h:i:s");
            $todays_date = date("Y-m-d");

            //INSTITUTION DETAILS
            $fetchInstDetails = $this->db->instDetails($registeral);
            $companyEmail = $fetchInstDetails['official_email'];

            //FETCH MERCHANT CONFIGURATION FILE
            $searchMemSet = $this->db->fetchMemberSettings($registeral);
            $nip_route = $searchMemSet['nip_route'];
            $currency = $searchMemSet['currency'];

            //FETCH SYSTEMSET CREDENTIALS
            $systemSetCredentials = $this->db->fetchSystemSet();
            $accessToken = $systemSetCredentials['primeairtime_token'];

            //FETCH MERCHANT MAINTENANCE SETIINGS
            $searchChargesSettings = $this->fetchMaintenanceSettings($registeral);
            $transferCharges = $searchChargesSettings['bank_transfer_charges'];

            //Search if staff
            $searchUser = $this->fetchTerminalOprt($reg_staffid,$registeral);
            //filter correct sender info
            $amountPlusCharges = $amount + $transferCharges;

            $transferHistoryLimit = $this->fetchAggregateTransferLimitPerDay($todays_date,$reg_staffid);
            $imyDailyTransferLimit = $transferHistoryLimit['SUM(debit)'];

            $vaGlobalLimit = $this->fetchVALimitConfiguration($reg_staffid,$registeral);
            $itransferLimitPerDay = $vaGlobalLimit['transferLimitPerDay'];
            $itransferLimitPerTrans = $vaGlobalLimit['transferLimitPerTrans'];

            //7 rows [0 - 7]
            $dataToProcess = $referenceId."|".$amount."|".$narration."|".$rName."|".$rBankName."|".$rAccountNumber."|".$bankCode."|".$availableWalletBal;
            $mytxtstatus = 'Pending';

            $checkIdempotence = $this->fetchWalletHistoryByRefId($referenceId);

            if($referenceId === "" || $amount === "" || $bankCode === "" || $rAccountNumber === "" || $rName === "" || $rBankName === "" || $tPin === ""){

                return -1;

            }elseif($tPin != $mytpin){

                return -2;

            }elseif($amountPlusCharges > $availableWalletBal){

                return -3;

            }elseif($checkIdempotence != 0){

                return -4;

            }elseif($amount > $itransferLimitPerTrans){

                return -5;
     
            }elseif($imyDailyTransferLimit == $itransferLimitPerDay || (($amount + $imyDailyTransferLimit) > $itransferLimitPerDay)){
        
                return -6;
        
            }elseif($nip_route == "PrimeAirtime"){ //PRIME AIRTIME GATEWAY

                //api request log
                ($amountPlusCharges > $availableWalletBal) ? "" : $queryTxt = "INSERT INTO api_txtwaitinglist (userid, refid, mydata, status, date_time) VALUE(?, ?, ?, ?, ?)";
                ($amountPlusCharges > $availableWalletBal) ? "" : $this->db->insertPendingTransaction($queryTxt, $reg_staffid, $referenceId, $dataToProcess, $mytxtstatus, $transactionDateTime);
                
                //DEDUCT FROM SENDER BALANCE
                $senderBalance = $availableWalletBal - $amountPlusCharges;
                ($amountPlusCharges <= $availableWalletBal) ? $this->updateUserWallet($senderBalance, $reg_staffid, $registeral) : "";

                $searchApi = $this->fetchApi("primeairtime_baseUrl");
                $api_url = $searchApi['api_url'].'/api/ft/transfer/'.$bankCode.'/'.$rAccountNumber;

                $header = [
                    "Content-Type: application/json",
                    "Authorization: Bearer ".$accessToken
                ];

                $sendData = array('amount'=>$amount,'narration'=>$narration,'customer_reference'=>$referenceId);

                $fTResponse = $this->callAPI("POST", $api_url, json_encode($sendData), $header, 1);
                $fT = json_decode($fTResponse, true);

                if($fT['status'] == "201"){

                    $gatewayResponse = $fT['message'];
                    $recipient = $rAccountNumber.', '.$rName.', '.$rBankName;

                    //UPDATE WAITING TXT
                    $txtQuery = "UPDATE api_txtwaitinglist SET status = '$fTResponse' WHERE userid = '$reg_staffid' AND refid = '$referenceId' AND status = '$mytxtstatus'";
                    $this->db->updateWaitingTxt($txtQuery, $fTResponse);

                    //INSERT WALLET HISTORY
                    $query2 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $this->db->insertWalletHistory($query2, $registeral, $referenceId, $recipient, '', $amount, 'Debit', $currency, 'BANK_TRANSFER', 'Transfer to ' . $recipient . ' via API', 'successful', $transactionDateTime, $reg_staffid, $senderBalance);
                    $this->db->insertWalletHistory($query2, $registeral, $referenceId, $recipient, '', $transferCharges, 'Debit', $currency, 'Charges', 'Transfer to ' . $recipient . ', Gateway Response: ' . $gatewayResponse . ' via API', 'successful', $transactionDateTime, $reg_staffid, $senderBalance);

                    ($reg_mEmail == "") ? "" : $this->bankTransferEmailNotifier($reg_mEmail, $companyEmail, $referenceId, $reg_fName, $rName, $rAccountNumber, $rBankName, $companyName, $currency, $amount, $senderBalance, $registeral);

                    return [

                        "responseCode" => "00",
        
                        "status" => "Success",

                        "data" => [

                            "referenceId" => $referenceId,

                            "message" => "Approved or completed successfully",

                            "sessionId" => $fT['api_transactionid'],

                            "amount" => $amount,

                            "recipientAccountNumber" => $rAccountNumber,

                            "narration" => $narration,

                            "currency" => $currency,

                            "recipientAccountName" => $rName,

                            "recipientBankName" => $rBankName,

                            "bankCode" => $bankCode,

                            "responseDateTime" => date("Y-m-d H:i:s")

                        ]

                    ];

                }else{

                    $myWaitingList = $this->fetchTxtWaitingList($reg_staffid,$referenceId,$mytxtstatus);
                    $myWaitingData = $myWaitingList['mydata'];

                    $myParameter = (explode('|',$myWaitingData));
                    $defaultBalance = $myParameter[7];

                    $this->updateUserWallet($defaultBalance, $reg_staffid, $registeral);

                    //UPDATE WAITING TXT
                    $txtQuery = "UPDATE api_txtwaitinglist SET status = '$fTResponse' WHERE userid = '$reg_staffid' AND refid = '$referenceId' AND status = '$mytxtstatus'";
                    $this->db->updateWaitingTxt($txtQuery, $fTResponse);

                    return -7; //Unable to conclude transaction, try again later.

                }

            }else{

                return -8; //You are not authorize to access this service at the moment

            }

        }else{

            return -9;

        }

    }


    //Airtime Product Request
    public function airtimeProduct($phoneNumber, $registeral){

        //FETCH MERCHANT CONFIGURATION FILE
        $searchMemSet = $this->db->fetchMemberSettings($registeral);
        $airtime_route = $searchMemSet['airtime'];

        //FETCH SYSTEMSET CREDENTIALS
        $systemSetCredentials = $this->db->fetchSystemSet();
        $accessToken = $systemSetCredentials['primeairtime_token'];

        if($airtime_route == "PrimeAirtime"){ //PRIME AIRTIME GATEWAY

            $searchApi = $this->fetchApi("primeairtime_baseUrl");
            $api_url = $searchApi['api_url'].'/api/topup/info/'.preg_replace("/[^0-9]/", "", $phoneNumber);

            $header = [
                "Content-Type: application/json",
                "Authorization: Bearer ".$accessToken
            ];

            $msisdnInfoResponse = $this->callAPI("GET", $api_url, false, $header, 1);
            $msisdnInfo = json_decode($msisdnInfoResponse, true);
            
            foreach($msisdnInfo['products'] as $key){
                
                return $key['product_id'].'|'.$msisdnInfo['opts']['msisdn'].'|'.$msisdnInfo['opts']['operator'];

            }

        }else{

            return -2; //You are not authorize to access this service at the moment

        }

    }


    /** ENDPOINT URL TO BUY AIRTIME:
     * 
     * {
     *  "referenceId" : "hooli-tx-1920bbtytty",
	*   "amount" : "1000",
    *   "telco" : "MTN",
    *   "phoneNumber" : "08101750845",
    *   "tPin" : "1234"
    *  }
     * 
     * */
    public function buyAirtime($parameter, $registeral, $companyName, $reg_staffid, $mytpin, $reg_mEmail, $reg_fName, $reg_lName, $availableWalletBal){

        if(isset($parameter->referenceId) && isset($parameter->amount) && isset($parameter->telco) && isset($parameter->phoneNumber) && isset($parameter->tPin)) {

            $referenceId = $parameter->referenceId;
            $amount = preg_replace('/[^0-9.]/', '', $parameter->amount);
            $telco = $parameter->telco;
            $phoneNumber = $parameter->phoneNumber;
            $tPin = $parameter->tPin;
            $transactionDateTime = date("Y-m-d h:i:s");
            $todays_date = date("Y-m-d");
            $type = "Airtime Vending";

            //FETCH MERCHANT CONFIGURATION FILE
            $searchMemSet = $this->db->fetchMemberSettings($registeral);
            $airtime_route = $searchMemSet['airtime'];
            $currency = $searchMemSet['currency'];

            //FETCH SYSTEMSET CREDENTIALS
            $systemSetCredentials = $this->db->fetchSystemSet();
            $accessToken = $systemSetCredentials['primeairtime_token'];

            //FETCH MERCHANT MAINTENANCE SETIINGS
            $searchChargesSettings = $this->fetchMaintenanceSettings($registeral);
            $airtimeCommission = $amount * $searchChargesSettings['airtimeData_comm'];

            //Search if staff
            $searchUser = $this->fetchTerminalOprt($reg_staffid,$registeral);
            //filter correct sender info
            $amountToDebit = $amount - $airtimeCommission;

            $airtimeDataHistoryLimit = $this->fetchAggregateAirtimeDataLimitPerDay($todays_date,$reg_staffid);
            $imyDailyAirtimeData = $airtimeDataHistoryLimit['SUM(debit)'];

            $vaGlobalLimit = $this->fetchVALimitConfiguration($reg_staffid,$registeral);
            $iglobalDailyAirtime_DataLimit = $vaGlobalLimit['airtime_dataLimitPerDay'];
            $iglobal_airtimeLimitPerTrans = $vaGlobalLimit['airtime_dataLimitPerTrans'];
                
            //4 rows [0 - 4]
            $dataToProcess = $referenceId."|".$amount."|".$phoneNumber."|".$telco."|".$availableWalletBal;
            $mytxtstatus = 'Pending';

            $checkIdempotence = $this->fetchWalletHistoryByRefId($referenceId);

            if($referenceId === "" || $amount === "" || $telco === "" || $phoneNumber === "" || $tPin === ""){

                return -1;

            }elseif($tPin != $mytpin){

                return -2;

            }elseif($checkIdempotence != 0){

                return -3;

            }elseif($amount > $availableWalletBal){

                return -4;

            }elseif($amount > $iglobal_airtimeLimitPerTrans){

                return -5;
        
            }elseif($imyDailyAirtimeData == $iglobalDailyAirtime_DataLimit || (($amount + $imyDailyAirtimeData) > $iglobalDailyAirtime_DataLimit)){
        
                return -6;
        
            }elseif($airtime_route == "PrimeAirtime" && $amount <= $availableWalletBal){ //PRIME AIRTIME GATEWAY

                //api request log
                ($amount > $availableWalletBal) ? "" : $queryTxt = "INSERT INTO api_txtwaitinglist (userid, refid, mydata, status, date_time) VALUE(?, ?, ?, ?, ?)";
                ($amount > $availableWalletBal) ? "" : $this->db->insertPendingTransaction($queryTxt, $reg_staffid, $referenceId, $dataToProcess, $mytxtstatus, $transactionDateTime);
                
                //DEDUCT FROM SENDER BALANCE
                $senderBalance = $availableWalletBal - $amountToDebit;
                ($amount <= $availableWalletBal) ? $this->updateUserWallet($senderBalance, $reg_staffid, $registeral) : "";

                $searchApi = $this->fetchApi("primeairtime_baseUrl");

                $header = [
                    "Content-Type: application/json",
                    "Authorization: Bearer ".$accessToken
                ];
                
                $msisdnInfo = $this->airtimeProduct($phoneNumber, $registeral);
                $msisdnParameter = (explode('|',$msisdnInfo));
                $product_id = $msisdnParameter[0];
                $mobilePhone = $msisdnParameter[1];
                $myNetwork = $msisdnParameter[2];

                //Enpoint to purchase airtime
                $api_url = $searchApi['api_url'].'/api/topup/exec/'.$mobilePhone;

                $sendData = array('product_id'=>$product_id,'denomination'=>$amount,'send_sms'=>false,'sms_text'=>'','customer_reference'=>$referenceId);

                $aTResponse = $this->callAPI("POST", $api_url, json_encode($sendData), $header, 1);
                $aT = json_decode($aTResponse, true);

                if($aT['status'] == "201"){

                    //UPDATE WAITING TXT
                    $txtQuery = "UPDATE api_txtwaitinglist SET status = '$aTResponse' WHERE userid = '$reg_staffid' AND refid = '$referenceId' AND status = '$mytxtstatus'";
                    $this->db->updateWaitingTxt($txtQuery, $aTResponse);
 
                    //INSERT WALLET HISTORY
                    $query2 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $this->db->insertWalletHistory($query2, $registeral, $referenceId, $phoneNumber, '', $amount, 'Debit', $currency, 'Airtime - API', 'Airtime Topup for ' . $phoneNumber . ' with refid: ' . $referenceId . ' via API', 'successful', $transactionDateTime, $reg_staffid, $senderBalance);
                    $this->db->insertWalletHistory($query2, $registeral, $referenceId, $phoneNumber, $airtimeCommission, '', 'Credit', $currency, 'Commission - API', 'Airtime Topup Commission for ' . $phoneNumber . ' with refid: ' . $referenceId . ' via API', 'successful', $transactionDateTime, $reg_staffid, $senderBalance);

                    ($reg_mEmail == "") ? "" : $this->airtimeDateEmailNotifier($reg_mEmail, $companyEmail, $referenceId, $reg_fName, $type, $mobilePhone, $myNetwork, $companyName, $currency, $amount, $amountToDebit, $senderBalance, $registeral);

                    return [

                        "responseCode" => "00",
        
                        "status" => "Success",

                        "data" => [

                            "referenceId" => $referenceId,

                            "message" => "Airtime purchase is successful",

                            "sessionId" => $aT['api_transactionid'],

                            "amount" => number_format($amount,2,'.',','),

                            "amountCharged" => number_format($amountToDebit,2,'.',','),

                            "mobilePhone" => $phoneNumber,

                            "operatorName" => $myNetwork,

                            "currency" => $currency,

                            "purchasedBy" => $reg_fName.' '.$reg_lName,

                            "responseDateTime" => date("Y-m-d H:i:s")

                        ]

                    ];

                }else{

                    $myWaitingList = $this->fetchTxtWaitingList($reg_staffid,$referenceId,$mytxtstatus);
                    $myWaitingData = $myWaitingList['mydata'];

                    $myParameter = (explode('|',$myWaitingData));
                    $defaultBalance = $myParameter[4];

                    $this->updateUserWallet($defaultBalance, $reg_staffid, $registeral);

                    //UPDATE WAITING TXT
                    $txtQuery = "UPDATE api_txtwaitinglist SET status = '$aTResponse' WHERE userid = '$reg_staffid' AND refid = '$referenceId' AND status = '$mytxtstatus'";
                    $this->db->updateWaitingTxt($txtQuery, $aTResponse);

                    return -7; //Unable to conclude transaction, try again later.

                }

            }else{

                return -8; //You are not authorize to access this service at the moment

            }

        }else{

            return -9;

        }

    }


    //All Databundle Product Request
    public function allDatabundleProduct($phoneNumber, $registeral){

        //FETCH MERCHANT CONFIGURATION FILE
        $searchMemSet = $this->db->fetchMemberSettings($registeral);
        $databundle_route = $searchMemSet['databundle'];

        //FETCH SYSTEMSET CREDENTIALS
        $systemSetCredentials = $this->db->fetchSystemSet();
        $accessToken = $systemSetCredentials['primeairtime_token'];

        if($databundle_route == "PrimeAirtime"){ //PRIME AIRTIME GATEWAY

            $searchApi = $this->fetchApi("primeairtime_baseUrl");
            $api_url = $searchApi['api_url'].'/api/datatopup/info/'.preg_replace("/[^0-9]/", "", $phoneNumber);

            $header = [
                "Content-Type: application/json",
                "Authorization: Bearer ".$accessToken
            ];

            $dBResponse = $this->callAPI("GET", $api_url, false, $header, 1);
            $dBList = json_decode($dBResponse, true);
            
            if(count($dBList['products']) > 0){
                
                for($i = 0; $i <= count($dBList['products']); $i++){

                    foreach($dBList['products'] as $key){

                        $output2[$i] = [

                            "productCode" => $key['product_id'],

                            "amount" => $key['topup_value'],

                            "operator" => $dBList['opts']['operator'],

                            "description" => $dBList['opts']['operator'].' '.$key['data_amount'].' MB Data top-up service',

                            "validity" => $key['validity'],

                            "minimum" => ($key['openRangeMin'] == "") ? "0.0" : $key['openRangeMin'],

                            "maximum" => ($key['openRangeMax'] == "") ? "0.0" : $key['openRangeMax'],

                            "topupCurrency" => $key['topup_currency']

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

                return -1; //Data not found

            }

        }else{

            return -2; //You are not authorize to access this service at the moment

        }

    }


    /** ENDPOINT URL TO BUY DATABUNDLE:
     * 
     * {
     *  "referenceId" : "hooli-tx-1920bbtytty",
     *  "productCode" : "UTC-YHBBN-HB",
	*   "amount" : "1000",
    *   "telco" : "MTN",
    *   "phoneNumber" : "08101750845",
    *   "tPin" : "1234"
    *  }
     * 
     * */
    public function buyDatabundle($parameter, $registeral, $companyName, $reg_staffid, $mytpin, $reg_mEmail, $reg_fName, $reg_lName, $availableWalletBal){

        if(isset($parameter->referenceId) && isset($parameter->productCode) && isset($parameter->amount) && isset($parameter->telco) && isset($parameter->phoneNumber) && isset($parameter->tPin)) {

            $referenceId = $parameter->referenceId;
            $productCode = $parameter->productCode;
            $amount = preg_replace('/[^0-9.]/', '', $parameter->amount);
            $telco = $parameter->telco;
            $phoneNumber = $parameter->phoneNumber;
            $tPin = $parameter->tPin;
            $transactionDateTime = date("Y-m-d h:i:s");
            $todays_date = date("Y-m-d");
            $type = "Data Vending";

            //FETCH MERCHANT CONFIGURATION FILE
            $searchMemSet = $this->db->fetchMemberSettings($registeral);
            $databundle_route = $searchMemSet['databundle'];
            $currency = $searchMemSet['currency'];

            //FETCH SYSTEMSET CREDENTIALS
            $systemSetCredentials = $this->db->fetchSystemSet();
            $accessToken = $systemSetCredentials['primeairtime_token'];

            //FETCH MERCHANT MAINTENANCE SETIINGS
            $searchChargesSettings = $this->fetchMaintenanceSettings($registeral);
            $dataCommission = $amount * $searchChargesSettings['airtimeData_comm'];

            //Search if staff
            $searchUser = $this->fetchTerminalOprt($reg_staffid,$registeral);
            //filter correct sender info
            $amountToDebit = $amount - $dataCommission;

            $airtimeDataHistoryLimit = $this->fetchAggregateAirtimeDataLimitPerDay($todays_date,$reg_staffid);
            $imyDailyAirtimeData = $airtimeDataHistoryLimit['SUM(debit)'];

            $vaGlobalLimit = $this->fetchVALimitConfiguration($reg_staffid,$registeral);
            $iglobalDailyAirtime_DataLimit = $vaGlobalLimit['airtime_dataLimitPerDay'];
            $iglobal_airtimeLimitPerTrans = $vaGlobalLimit['airtime_dataLimitPerTrans'];
                
            //4 rows [0 - 4]
            $dataToProcess = $referenceId."|".$amount."|".$phoneNumber."|".$telco."|".$availableWalletBal;
            $mytxtstatus = 'Pending';

            $checkIdempotence = $this->fetchWalletHistoryByRefId($referenceId);

            if($referenceId === "" || $amount === "" || $telco === "" || $phoneNumber === "" || $tPin === ""){

                return -1;

            }elseif($tPin != $mytpin){

                return -2;

            }elseif($amount > $availableWalletBal){

                return -3;

            }elseif($checkIdempotence != 0){

                return -4;

            }elseif($amount > $iglobal_airtimeLimitPerTrans){

                return -5;
        
            }elseif($imyDailyAirtimeData == $iglobalDailyAirtime_DataLimit || (($amount + $imyDailyAirtimeData) > $iglobalDailyAirtime_DataLimit)){
        
                return -6;
        
            }elseif($databundle_route == "PrimeAirtime" && $amount <= $availableWalletBal){ //PRIME AIRTIME GATEWAY

                //api request log
                ($amount > $availableWalletBal) ? "" : $queryTxt = "INSERT INTO api_txtwaitinglist (userid, refid, mydata, status, date_time) VALUE(?, ?, ?, ?, ?)";
                ($amount > $availableWalletBal) ? "" : $this->db->insertPendingTransaction($queryTxt, $reg_staffid, $referenceId, $dataToProcess, $mytxtstatus, $transactionDateTime);
                
                //DEDUCT FROM SENDER BALANCE
                $senderBalance = $availableWalletBal - $amountToDebit;
                ($amount <= $availableWalletBal) ? $this->updateUserWallet($senderBalance, $reg_staffid, $registeral) : "";

                $searchApi = $this->fetchApi("primeairtime_baseUrl");
                $api_url = $searchApi['api_url'].'/api/datatopup/exec/'.preg_replace("/[^0-9]/", "", $phoneNumber);

                $header = [
                    "Content-Type: application/json",
                    "Authorization: Bearer ".$accessToken
                ];

                $sendData = array('product_id'=>$productCode,'denomination'=>$amount,'send_sms'=>false,'sms_text'=>'','customer_reference'=>$referenceId);

                $aTResponse = $this->callAPI("POST", $api_url, json_encode($sendData), $header, 1);
                $aT = json_decode($aTResponse, true);

                if($aT['status'] == "201"){

                    //UPDATE WAITING TXT
                    $txtQuery = "UPDATE api_txtwaitinglist SET status = '$aTResponse' WHERE userid = '$reg_staffid' AND refid = '$referenceId' AND status = '$mytxtstatus'";
                    $this->db->updateWaitingTxt($txtQuery, $aTResponse);
 
                    //INSERT WALLET HISTORY
                    $query2 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $this->db->insertWalletHistory($query2, $registeral, $referenceId, $phoneNumber, '', $amount, 'Debit', $currency, 'Databundle - API', $telco . ' Databundle Purchase for ' . $productCode . ' with refid: ' . $referenceId . ' via API', 'successful', $transactionDateTime, $reg_staffid, $senderBalance);
                    $this->db->insertWalletHistory($query2, $registeral, $referenceId, $phoneNumber, $dataCommission, '', 'Credit', $currency, 'Commission - API', $telco . ' Databundle Purchase Commission for ' . $productCode . ' with refid: ' . $referenceId . ' via API', 'successful', $transactionDateTime, $reg_staffid, $senderBalance);

                    ($reg_mEmail == "") ? "" : $this->airtimeDateEmailNotifier($reg_mEmail, $companyEmail, $referenceId, $reg_fName, $type, $phoneNumber, $telco, $companyName, $currency, $amount, $amountToDebit, $senderBalance, $registeral);

                    return [

                        "responseCode" => "00",
        
                        "status" => "Success",

                        "data" => [

                            "referenceId" => $referenceId,

                            "message" => "MobileData purchase is successful",

                            "sessionId" => $aT['api_transactionid'],

                            "amount" => number_format($amount,2,'.',','),

                            "amountCharged" => number_format($amountToDebit,2,'.',','),

                            "mobilePhone" => $phoneNumber,

                            "operatorName" => $telco,

                            "currency" => $currency,

                            "purchasedBy" => $reg_fName.' '.$reg_lName,

                            "responseDateTime" => date("Y-m-d H:i:s")

                        ]

                    ];

                }else{

                    $myWaitingList = $this->fetchTxtWaitingList($reg_staffid,$referenceId,$mytxtstatus);
                    $myWaitingData = $myWaitingList['mydata'];

                    $myParameter = (explode('|',$myWaitingData));
                    $defaultBalance = $myParameter[4];

                    $this->updateUserWallet($defaultBalance, $reg_staffid, $registeral);

                    //UPDATE WAITING TXT
                    $txtQuery = "UPDATE api_txtwaitinglist SET status = '$aTResponse' WHERE userid = '$reg_staffid' AND refid = '$referenceId' AND status = '$mytxtstatus'";
                    $this->db->updateWaitingTxt($txtQuery, $aTResponse);

                    return -7; //Unable to conclude transaction, try again later.

                }

            }else{

                return -8; //You are not authorize to access this service at the moment

            }

        }else{

            return -9;

        }

    }


    /** ENDPOINT URL FOR WALLET-TO-WALLET TRANSFER ARE:
     * 
     * api/wallet/p2pTransfer/    : To make wallet to wallet transfer within the system with the following required field:
     * 
     * {
     *  "walletAccountNumber": "2222222222",
     *  "amountToTranfer": "500",
     *  "narration": "transfer to my friend",
     *  "ePin": "1234"
     *  "apiKey": "xxx"
    *  }
     * 
     * */
    /**
    public function p2pTransfer($parameter, $registeral, $reg_mEmail, $reg_staffName, $virtualAcctNo, $iacctType, $availableWalletBal, $reg_staffid, $mytpin, $active_status) {
       
        if(isset($parameter->walletAccountNumber) && isset($parameter->amountToTranfer) && isset($parameter->narration) && isset($parameter->ePin) && isset($parameter->apiKey)) {

            $walletAccountNumber = $parameter->walletAccountNumber;
            $amountToTransfer = preg_replace('/[^0-9.]/', '', $parameter->amountToTranfer);
            $narration = $parameter->narration;
            $ePin = $parameter->ePin;
            $apiKey = $parameter->apiKey;
            $paymenttype = "p2p-transfer";
            $datetime = date("Y-m-d h:i:s");
            $refid = uniqid("EA-p2pFunding-").time();

            $sysCredentials = $this->db->fetchSystemSet();
            $email_from = $sysCredentials['email_from'];
            $product_name = $sysCredentials['name'];
            $website = $sysCredentials['website'];
            $logo_url = $sysCredentials['logo_url'];
            $support_email = $sysCredentials['email'];
            $live_chat_url = $sysCredentials['live_chat'];
            $sender_name = $sysCredentials['email_sender_name'];
            $company_address = $sysCredentials['address'];
            $email_token = $sysCredentials['email_token'];

            //CHECK VIRTUAL ACCOUNT VALIDITY
            $checkAcctValidity = $this->fetchUniqueVAByAcctNo($walletAccountNumber, $registeral);

            //USER ID OF THE RECIPIENT
            $receiverAcctId = $checkAcctValidity['userid'];

            //CHECK USER EXISTENCE
            $mycustomer = $this->fetchCustomerByAcctId($receiverAcctId, $registeral);
            $myuser = $this->fetchTerminalOprt($receiverAcctId, $registeral);

            //DETECT THE RIGHT RECEIVER (AGENT/STAFF OR CUSTOMER)
            $ph = ($mycustomer != 0 && $myuser === 0) ? $mycustomer['phone'] : $myuser['phone'];
            $em = ($mycustomer != 0 && $myuser === 0) ? $mycustomer['email'] : $myuser['email'];
            $myname = ($mycustomer != 0 && $myuser === 0) ? $checkAcctValidity['account_name'] : $checkAcctValidity['account_name'];
            $receiverVANo = ($mycustomer != 0 && $myuser === 0) ? $mycustomer['virtual_number'] : $myuser['virtual_account'];
            $receiverBalance = ($mycustomer != 0 && $myuser === 0) ? $mycustomer['wallet_balance'] : $myuser['transfer_balance'];
            $detectRightReceiver = ($mycustomer != 0 && $myuser === 0) ? "Customer" : "Institution";
            $totalwallet_balance = $receiverBalance + $amountToTransfer;

            $memberSet = $this->db->fetchMemberSettings($registeral);
            $currencyCode = $memberSet['currency'];

            $checkAuth = $this->accessKey($apiKey,$registeral);
            $todays_date = date("Y-m-d");

            $transferHistoryLimit = $this->fetchAggregateTransferLimitPerDay($todays_date,$receiverAcctId);
            $imyDailyTransferLimit = $transferHistoryLimit['SUM(debit)'];

            $vaGlobalLimit = $this->fetchVALimitConfiguration($receiverAcctId,$registeral);
            $itransferLimitPerDay = $vaGlobalLimit['transferLimitPerDay'];
            $itransferLimitPerTrans = $vaGlobalLimit['transferLimitPerTrans'];
            
            if($walletAccountNumber === "" || $amountToTransfer === "" || $narration === "" || $ePin === "" || $apiKey === ""){

                return -1; //Required field must not be empty

            }
            elseif($checkAcctValidity === 0){

                return -2; //Account not found

            }
            elseif(preg_match('/^[0-9.]+(?:\.[0-9]{0,2})?$/', $amountToTransfer) == FALSE)
            {

                return -4; //Invalid Amount

            }
            elseif($checkAuth === 0){
                    
                return -5;
                    
            }
            elseif($amountToTransfer > $availableWalletBal){

                return -6; //Insufficient fund in wallet

            }
            elseif($amountToTransfer > $itransferLimitPerTrans){

                return -7;
     
            }
            elseif($imyDailyTransferLimit == $itransferLimitPerDay || (($amountToTransfer + $imyDailyTransferLimit) > $itransferLimitPerDay)){
        
                return -8;
        
            }
            elseif($ePin != $mytpin){

                return -9; //Pin validation failed

            }
            elseif($virtualAcctNo === "$walletAccountNumber" || $reg_staffid === "$walletAccountNumber"){
      
                return -10;
                
            }
            elseif($active_status == "Suspended"){

                return -11;

            }
            else{
                
                //DEDUCT FROM SENDER BALANCE
                $senderBalance = $availableWalletBal - $amountToTransfer;
                ($iacctType === "customer") ? $this->updateBorrowerWallet($senderBalance, $reg_staffid, $registeral) : "";
                ($iacctType === "agent") ? $this->updateUserWallet($senderBalance, $reg_staffid, $registeral) : "";

                //DEDUCT FROM RECIPIENT BALANCE AND LOG TRANSACTION
                ($detectRightReceiver == "Customer") ? $this->updateBorrowerWallet($totalwallet_balance, $receiverAcctId, $registeral) : "";
                ($detectRightReceiver == "Institution") ? $this->updateUserWallet($totalwallet_balance, $receiverAcctId, $registeral) : "";

                //LOG WALLET TRANSACTION
                $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance, receiver_bal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertSpecialWalletHistory($query, $registeral, $refid, $receiverAcctId, '', $amountToTransfer, 'Debit', $currencyCode, $paymenttype, $narration, 'successful', $datetime, $reg_staffid, $senderBalance, $totalwallet_balance);

                //SEND P2P DEBIT NOTIFICATION TO SENDER
                $this->sendP2pDebit($reg_mEmail, $refid, $reg_staffName, $virtualAcctNo, $registeral, $currencyCode, $amountToTransfer, $senderBalance);

                //SEND P2P CREDIT NOTIFICATION TO RECEIVER
                $this->sendP2pCredit($em, $refid, $walletAccountNumber, $myname, $registeral, $currencyCode, $amountToTransfer, $totalwallet_balance);

                return [
    
                    "paymentReference"=> $refid,

                    "amountTransfered"=> $amountToTransfer,

                    "recipientName"=> $myname,

                    "receipientAccountNo"=> $walletAccountNumber,

                    "receipientPhone"=> $ph,

                    "currencyCode"=> $currencyCode,

                    "senderAccountName"=> $reg_staffName,

                    "walletBalance"=> $senderBalance,
        
                    "status"=> "successful",

                    "paymentDateTime"=> $datetime,
        
                ];
        
            }
        
        }

    }*/

}

?>