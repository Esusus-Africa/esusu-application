<?php

class eWallet extends User {

    //Bank Transfer Alert
    public function bankTransferEmailNotifier($senderEmail, $merchantEmail, $tReference, $senderName, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $icurrency, $amountWithNoCharges, $senderBalance, $clientId){
            
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
    public function walletCreditEmailNotifier($merchantEmail, $txid, $final_date_time, $inst_name, $myname, $account, $icurrency, $amount, $totalwallet_balance, $clientId){
            
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
    public function airtimeDateEmailNotifier($senderEmail, $merchantEmail, $tReference, $senderName, $type, $phone, $operator, $merchantName, $icurrency, $amountWithNoCharges, $amountCharged, $senderBalance, $clientId){
            
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


    //All Bank List Request
    public function allBankList($clientId){

        //FETCH MERCHANT CONFIGURATION FILE
        $searchMemSet = $this->db->fetchMemberSettings($clientId);
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
    public function bankNameEnquiry($bankCode, $accountNumber, $clientId){

        //FETCH MERCHANT CONFIGURATION FILE
        $searchMemSet = $this->db->fetchMemberSettings($clientId);
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
     *  "senderAccountNumber" : "9830983773",
	*   "amount" : "1000",
    *   "bankCode" : "044",
    *   "recipientAccountNumber" : "0066097011",
    *   "recipientName" : "James Micheal",
    *   "recipientBankName" : "ECOBANK",
    *   "narration" : "testing transfer",
    *   "tPin" : "1234",
    *   "clientID": "INST1228288"
    *  }
     * 
     * */
    public function fundTransfer($parameter, $clientId, $companyName, $companyEmail) {

        if(isset($parameter->referenceId) && isset($parameter->senderAccountNumber) && isset($parameter->amount) && isset($parameter->bankCode) && isset($parameter->recipientAccountNumber) && isset($parameter->recipientName) && isset($parameter->recipientBankName) && isset($parameter->narration) && isset($parameter->tPin) && isset($parameter->clientID)) {

            $referenceId = $parameter->referenceId;
            $senderAccountNumber = $parameter->senderAccountNumber;
            $amount = $parameter->amount;
            $bankCode = $parameter->bankCode;
            $rAccountNumber = $parameter->recipientAccountNumber;
            $rName = $parameter->recipientName;
            $rBankName = $parameter->recipientBankName;
            $narration = $parameter->narration;
            $tPin = $parameter->tPin;
            $instID = $parameter->clientID;
            $transactionDateTime = date("Y-m-d h:i:s");
            $todays_date = date("Y-m-d");

            //FETCH MERCHANT CONFIGURATION FILE
            $searchMemSet = $this->db->fetchMemberSettings($clientId);
            $nip_route = $searchMemSet['nip_route'];
            $currency = $searchMemSet['currency'];

            //FETCH SYSTEMSET CREDENTIALS
            $systemSetCredentials = $this->db->fetchSystemSet();
            $accessToken = $systemSetCredentials['primeairtime_token'];

            //FETCH MERCHANT MAINTENANCE SETIINGS
            $searchChargesSettings = $this->fetchMaintenanceSettings($clientId);
            $transferCharges = $searchChargesSettings['bank_transfer_charges'];

            //lookup account validity
            $searchVA = $this->fetchVAByAcctNo($senderAccountNumber);
            $accountID = ($searchVA['userid'] == "") ? $senderAccountNumber : $searchVA['userid'];

            //Search if borrowers
            $searchCustomer = $this->fetchCustomerByAcctId($accountID,$clientId);
            //Search if staff
            $searchUser = $this->fetchTerminalOprt($accountID,$clientId);
            //filter correct sender info
            $myTPin = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['tpin'] : $searchUser['tpin'];
            $mywalletBal = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['wallet_balance'] : $searchUser['transfer_balance'];
            $myAcctID = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['account'] : $searchUser['id'];
            $senderEmail = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['email'] : $searchUser['email'];
            $senderName = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['lname'].' '.$searchCustomer['fname'] : $searchUser['name'].' '.$searchUser['fname'];
            $detectRightSender = ($searchCustomer != 0 && $searchUser === 0) ? "Customer" : "Agent";
            $amountPlusCharges = $amount + $transferCharges;

            $transferHistoryLimit = $this->fetchAggregateTransferLimitPerDay($todays_date,$myAcctID);
            $imyDailyTransferLimit = $transferHistoryLimit['SUM(debit)'];

            $vaGlobalLimit = $this->fetchVALimitConfiguration($myAcctID,$clientId);
            $itransferLimitPerDay = $vaGlobalLimit['transferLimitPerDay'];
            $itransferLimitPerTrans = $vaGlobalLimit['transferLimitPerTrans'];

            //7 rows [0 - 7]
            $dataToProcess = $referenceId."|".$amount."|".$narration."|".$rName."|".$rBankName."|".$rAccountNumber."|".$bankCode."|".$mywalletBal;
            $mytxtstatus = 'Pending';

            $checkIdempotence = $this->fetchWalletHistoryByRefId($referenceId);

            if($referenceId === "" || $senderAccountNumber === "" || $amount === "" || $bankCode === "" || $rAccountNumber === "" || $rName === "" || $rBankName === "" || $tPin === "" || $instID === ""){

                return -1;

            }elseif($searchCustomer === 0 && $searchUser === 0){

                return -2;

            }elseif($tPin != $myTPin){

                return -3;

            }elseif($instID != $clientId){

                return -4;

            }elseif($amountPlusCharges > $mywalletBal){

                return -5;

            }elseif($checkIdempotence != 0){

                return -9;

            }elseif($amount > $itransferLimitPerTrans){

                return -10;
     
            }elseif($imyDailyTransferLimit == $itransferLimitPerDay || (($amount + $imyDailyTransferLimit) > $itransferLimitPerDay)){
        
                return -11;
        
            }elseif($nip_route == "PrimeAirtime"){ //PRIME AIRTIME GATEWAY

                //api request log
                ($amountPlusCharges > $mywalletBal) ? "" : $queryTxt = "INSERT INTO api_txtwaitinglist (userid, refid, mydata, status, date_time) VALUE(?, ?, ?, ?, ?)";
                ($amountPlusCharges > $mywalletBal) ? "" : $this->db->insertPendingTransaction($queryTxt, $myAcctID, $referenceId, $dataToProcess, $mytxtstatus, $transactionDateTime);
                
                //DEDUCT FROM SENDER BALANCE
                $senderBalance = $mywalletBal - $amountPlusCharges;
                ($detectRightSender === "Customer" && $amountPlusCharges <= $mywalletBal) ? $this->updateBorrowerWallet($senderBalance, $myAcctID, $clientId) : "";
                ($detectRightSender === "Agent" && $amountPlusCharges <= $mywalletBal) ? $this->updateUserWallet($senderBalance, $myAcctID, $clientId) : "";

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
                    $txtQuery = "UPDATE api_txtwaitinglist SET status = '$fTResponse' WHERE userid = '$myAcctID' AND refid = '$referenceId' AND status = '$mytxtstatus'";
                    $this->db->updateWaitingTxt($txtQuery, $fTResponse);

                    //INSERT WALLET HISTORY
                    $query2 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $this->db->insertWalletHistory($query2, $clientId, $referenceId, $recipient, '', $amount, 'Debit', $currency, 'BANK_TRANSFER', 'Transfer to ' . $recipient . ' via API', 'successful', $transactionDateTime, $myAcctID, $senderBalance);
                    $this->db->insertWalletHistory($query2, $clientId, $referenceId, $recipient, '', $transferCharges, 'Debit', $currency, 'Charges', 'Transfer to ' . $recipient . ', Gateway Response: ' . $gatewayResponse . ' via API', 'successful', $transactionDateTime, $myAcctID, $senderBalance);

                    ($senderEmail == "") ? "" : $this->bankTransferEmailNotifier($senderEmail, $companyEmail, $referenceId, $senderName, $rName, $rAccountNumber, $rBankName, $companyName, $currency, $amount, $senderBalance, $clientId);

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

                    $myWaitingList = $this->fetchTxtWaitingList($myAcctID,$referenceId,$mytxtstatus);
                    $myWaitingData = $myWaitingList['mydata'];

                    $myParameter = (explode('|',$myWaitingData));
                    $defaultBalance = $myParameter[7];

                    ($detectRightSender === "Customer") ? $this->updateBorrowerWallet($defaultBalance, $myAcctID, $clientId) : "";
                    ($detectRightSender === "Agent") ? $this->updateUserWallet($defaultBalance, $myAcctID, $clientId) : "";

                    //UPDATE WAITING TXT
                    $txtQuery = "UPDATE api_txtwaitinglist SET status = '$fTResponse' WHERE userid = '$myAcctID' AND refid = '$referenceId' AND status = '$mytxtstatus'";
                    $this->db->updateWaitingTxt($txtQuery, $fTResponse);

                    return -6; //Unable to conclude transaction, try again later.

                }

            }else{

                return -7; //You are not authorize to access this service at the moment

            }

        }else{

            return -8;

        }

    }
    
    
    
    //Airtime Product Request
    public function airtimeProduct($phoneNumber, $clientId){

        //FETCH MERCHANT CONFIGURATION FILE
        $searchMemSet = $this->db->fetchMemberSettings($clientId);
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
     *  "senderAccountNumber" : "9830983773",
	*   "amount" : "1000",
    *   "telco" : "MTN",
    *   "phoneNumber" : "08101750845",
    *   "tPin" : "1234",
    *   "clientID": "INST1228288"
    *  }
     * 
     * */
    public function buyAirtime($parameter, $clientId, $companyName, $companyEmail){

        if(isset($parameter->referenceId) && isset($parameter->senderAccountNumber) && isset($parameter->amount) && isset($parameter->telco) && isset($parameter->phoneNumber) && isset($parameter->tPin) && isset($parameter->clientID)) {

            $referenceId = $parameter->referenceId;
            $senderAccountNumber = $parameter->senderAccountNumber;
            $amount = $parameter->amount;
            $telco = $parameter->telco;
            $phoneNumber = $parameter->phoneNumber;
            $tPin = $parameter->tPin;
            $instID = $parameter->clientID;
            $transactionDateTime = date("Y-m-d h:i:s");
            $todays_date = date("Y-m-d");
            $type = "Airtime Vending";

            //FETCH MERCHANT CONFIGURATION FILE
            $searchMemSet = $this->db->fetchMemberSettings($clientId);
            $airtime_route = $searchMemSet['airtime'];
            $currency = $searchMemSet['currency'];

            //FETCH SYSTEMSET CREDENTIALS
            $systemSetCredentials = $this->db->fetchSystemSet();
            $accessToken = $systemSetCredentials['primeairtime_token'];

            //FETCH MERCHANT MAINTENANCE SETIINGS
            $searchChargesSettings = $this->fetchMaintenanceSettings($clientId);
            $airtimeCommission = $amount * $searchChargesSettings['airtimeData_comm'];

            //lookup account validity
            $searchVA = $this->fetchVAByAcctNo($senderAccountNumber);
            $accountID = ($searchVA['userid'] == "") ? $senderAccountNumber : $searchVA['userid'];

            //Search if borrowers
            $searchCustomer = $this->fetchCustomerByAcctId($accountID,$clientId);
            //Search if staff
            $searchUser = $this->fetchTerminalOprt($accountID,$clientId);
            //filter correct sender info
            $myTPin = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['tpin'] : $searchUser['tpin'];
            $mywalletBal = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['wallet_balance'] : $searchUser['transfer_balance'];
            $myAcctID = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['account'] : $searchUser['id'];
            $senderEmail = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['email'] : $searchUser['email'];
            $senderName = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['lname'].' '.$searchCustomer['fname'] : $searchUser['name'].' '.$searchUser['fname'];
            $detectRightSender = ($searchCustomer != 0 && $searchUser === 0) ? "Customer" : "Agent";
            $amountToDebit = $amount - $airtimeCommission;

            $airtimeDataHistoryLimit = $this->fetchAggregateAirtimeDataLimitPerDay($todays_date,$myAcctID);
            $imyDailyAirtimeData = $airtimeDataHistoryLimit['SUM(debit)'];

            $vaGlobalLimit = $this->fetchVALimitConfiguration($myAcctID,$clientId);
            $iglobalDailyAirtime_DataLimit = $vaGlobalLimit['airtime_dataLimitPerDay'];
            $iglobal_airtimeLimitPerTrans = $vaGlobalLimit['airtime_dataLimitPerTrans'];
                
            //4 rows [0 - 4]
            $dataToProcess = $referenceId."|".$amount."|".$phoneNumber."|".$telco."|".$mywalletBal;
            $mytxtstatus = 'Pending';

            $checkIdempotence = $this->fetchWalletHistoryByRefId($referenceId);

            if($referenceId === "" || $senderAccountNumber === "" || $amount === "" || $telco === "" || $phoneNumber === "" || $tPin === "" || $instID === ""){

                return -1;

            }elseif($searchCustomer === 0 && $searchUser === 0){

                return -2;

            }elseif($tPin != $myTPin){

                return -3;

            }elseif($instID != $clientId){

                return -4;

            }elseif($amount > $mywalletBal){

                return -5;

            }elseif($checkIdempotence != 0){

                return -9;

            }elseif($amount > $iglobal_airtimeLimitPerTrans){

                return -10;
        
            }elseif($imyDailyAirtimeData == $iglobalDailyAirtime_DataLimit || (($amount + $imyDailyAirtimeData) > $iglobalDailyAirtime_DataLimit)){
        
                return -11;
        
            }elseif($airtime_route == "PrimeAirtime" && $amount <= $mywalletBal){ //PRIME AIRTIME GATEWAY

                //api request log
                ($amount > $mywalletBal) ? "" : $queryTxt = "INSERT INTO api_txtwaitinglist (userid, refid, mydata, status, date_time) VALUE(?, ?, ?, ?, ?)";
                ($amount > $mywalletBal) ? "" : $this->db->insertPendingTransaction($queryTxt, $myAcctID, $referenceId, $dataToProcess, $mytxtstatus, $transactionDateTime);
                
                //DEDUCT FROM SENDER BALANCE
                $senderBalance = $mywalletBal - $amountToDebit;
                ($detectRightSender === "Customer" && $amount <= $mywalletBal) ? $this->updateBorrowerWallet($senderBalance, $myAcctID, $clientId) : "";
                ($detectRightSender === "Agent" && $amount <= $mywalletBal) ? $this->updateUserWallet($senderBalance, $myAcctID, $clientId) : "";

                $searchApi = $this->fetchApi("primeairtime_baseUrl");

                $header = [
                    "Content-Type: application/json",
                    "Authorization: Bearer ".$accessToken
                ];
                
                $msisdnInfo = $this->airtimeProduct($phoneNumber, $clientId);
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
                    $txtQuery = "UPDATE api_txtwaitinglist SET status = '$aTResponse' WHERE userid = '$myAcctID' AND refid = '$referenceId' AND status = '$mytxtstatus'";
                    $this->db->updateWaitingTxt($txtQuery, $aTResponse);
 
                    //INSERT WALLET HISTORY
                    $query2 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $this->db->insertWalletHistory($query2, $clientId, $referenceId, $phoneNumber, '', $amount, 'Debit', $currency, 'Airtime - API', 'Airtime Topup for ' . $phoneNumber . ' with refid: ' . $referenceId . ' via API', 'successful', $transactionDateTime, $myAcctID, $senderBalance);
                    $this->db->insertWalletHistory($query2, $clientId, $referenceId, $phoneNumber, $airtimeCommission, '', 'Credit', $currency, 'Commission - API', 'Airtime Topup Commission for ' . $phoneNumber . ' with refid: ' . $referenceId . ' via API', 'successful', $transactionDateTime, $myAcctID, $senderBalance);

                    ($senderEmail == "") ? "" : $this->airtimeDateEmailNotifier($senderEmail, $companyEmail, $referenceId, $senderName, $type, $mobilePhone, $myNetwork, $companyName, $currency, $amount, $amountToDebit, $senderBalance, $clientId);

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

                            "purchasedBy" => $senderName,

                            "responseDateTime" => date("Y-m-d H:i:s")

                        ]

                    ];

                }else{

                    $myWaitingList = $this->fetchTxtWaitingList($myAcctID,$referenceId,$mytxtstatus);
                    $myWaitingData = $myWaitingList['mydata'];

                    $myParameter = (explode('|',$myWaitingData));
                    $defaultBalance = $myParameter[4];

                    ($detectRightSender === "Customer") ? $this->updateBorrowerWallet($defaultBalance, $myAcctID, $clientId) : "";
                    ($detectRightSender === "Agent") ? $this->updateUserWallet($defaultBalance, $myAcctID, $clientId) : "";

                    //UPDATE WAITING TXT
                    $txtQuery = "UPDATE api_txtwaitinglist SET status = '$aTResponse' WHERE userid = '$myAcctID' AND refid = '$referenceId' AND status = '$mytxtstatus'";
                    $this->db->updateWaitingTxt($txtQuery, $aTResponse);

                    return -6; //Unable to conclude transaction, try again later.

                }

            }else{

                return -7; //You are not authorize to access this service at the moment

            }

        }else{

            return -8;

        }

    }


    //All Databundle Product Request
    public function allDatabundleProduct($phoneNumber, $clientId){

        //FETCH MERCHANT CONFIGURATION FILE
        $searchMemSet = $this->db->fetchMemberSettings($clientId);
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
     *  "senderAccountNumber" : "9830983773",
     *  "productCode" : "UTC-YHBBN-HB",
	*   "amount" : "1000",
    *   "telco" : "MTN",
    *   "phoneNumber" : "08101750845",
    *   "tPin" : "1234",
    *   "clientID": "INST1228288"
    *  }
     * 
     * */
    public function buyDatabundle($parameter, $clientId, $companyName, $companyEmail){

        if(isset($parameter->referenceId) && isset($parameter->senderAccountNumber) && isset($parameter->productCode) && isset($parameter->amount) && isset($parameter->telco) && isset($parameter->phoneNumber) && isset($parameter->tPin) && isset($parameter->clientID)) {

            $referenceId = $parameter->referenceId;
            $senderAccountNumber = $parameter->senderAccountNumber;
            $productCode = $parameter->productCode;
            $amount = $parameter->amount;
            $telco = $parameter->telco;
            $phoneNumber = $parameter->phoneNumber;
            $tPin = $parameter->tPin;
            $instID = $parameter->clientID;
            $transactionDateTime = date("Y-m-d h:i:s");
            $todays_date = date("Y-m-d");
            $type = "Data Vending";

            //FETCH MERCHANT CONFIGURATION FILE
            $searchMemSet = $this->db->fetchMemberSettings($clientId);
            $databundle_route = $searchMemSet['databundle'];
            $currency = $searchMemSet['currency'];

            //FETCH SYSTEMSET CREDENTIALS
            $systemSetCredentials = $this->db->fetchSystemSet();
            $accessToken = $systemSetCredentials['primeairtime_token'];

            //FETCH MERCHANT MAINTENANCE SETIINGS
            $searchChargesSettings = $this->fetchMaintenanceSettings($clientId);
            $dataCommission = $amount * $searchChargesSettings['airtimeData_comm'];

            //lookup account validity
            $searchVA = $this->fetchVAByAcctNo($senderAccountNumber);
            $accountID = ($searchVA['userid'] == "") ? $senderAccountNumber : $searchVA['userid'];

            //Search if borrowers
            $searchCustomer = $this->fetchCustomerByAcctId($accountID,$clientId);
            //Search if staff
            $searchUser = $this->fetchTerminalOprt($accountID,$clientId);
            //filter correct sender info
            $myTPin = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['tpin'] : $searchUser['tpin'];
            $mywalletBal = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['wallet_balance'] : $searchUser['transfer_balance'];
            $myAcctID = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['account'] : $searchUser['id'];
            $senderEmail = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['email'] : $searchUser['email'];
            $senderName = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['lname'].' '.$searchCustomer['fname'] : $searchUser['name'].' '.$searchUser['fname'];
            $detectRightSender = ($searchCustomer != 0 && $searchUser === 0) ? "Customer" : "Agent";
            $amountToDebit = $amount - $dataCommission;

            $airtimeDataHistoryLimit = $this->fetchAggregateAirtimeDataLimitPerDay($todays_date,$myAcctID);
            $imyDailyAirtimeData = $airtimeDataHistoryLimit['SUM(debit)'];

            $vaGlobalLimit = $this->fetchVALimitConfiguration($myAcctID,$clientId);
            $iglobalDailyAirtime_DataLimit = $vaGlobalLimit['airtime_dataLimitPerDay'];
            $iglobal_airtimeLimitPerTrans = $vaGlobalLimit['airtime_dataLimitPerTrans'];
                
            //4 rows [0 - 4]
            $dataToProcess = $referenceId."|".$amount."|".$phoneNumber."|".$telco."|".$mywalletBal;
            $mytxtstatus = 'Pending';

            $checkIdempotence = $this->fetchWalletHistoryByRefId($referenceId);

            if($referenceId === "" || $senderAccountNumber === "" || $amount === "" || $telco === "" || $phoneNumber === "" || $tPin === "" || $instID === ""){

                return -1;

            }elseif($searchCustomer === 0 && $searchUser === 0){

                return -2;

            }elseif($tPin != $myTPin){

                return -3;

            }elseif($instID != $clientId){

                return -4;

            }elseif($amount > $mywalletBal){

                return -5;

            }elseif($checkIdempotence != 0){

                return -9;

            }elseif($amount > $iglobal_airtimeLimitPerTrans){

                return -10;
        
            }elseif($imyDailyAirtimeData == $iglobalDailyAirtime_DataLimit || (($amount + $imyDailyAirtimeData) > $iglobalDailyAirtime_DataLimit)){
        
                return -11;
        
            }elseif($databundle_route == "PrimeAirtime" && $amount <= $mywalletBal){ //PRIME AIRTIME GATEWAY

                //api request log
                ($amount > $mywalletBal) ? "" : $queryTxt = "INSERT INTO api_txtwaitinglist (userid, refid, mydata, status, date_time) VALUE(?, ?, ?, ?, ?)";
                ($amount > $mywalletBal) ? "" : $this->db->insertPendingTransaction($queryTxt, $myAcctID, $referenceId, $dataToProcess, $mytxtstatus, $transactionDateTime);
                
                //DEDUCT FROM SENDER BALANCE
                $senderBalance = $mywalletBal - $amountToDebit;
                ($detectRightSender === "Customer" && $amount <= $mywalletBal) ? $this->updateBorrowerWallet($senderBalance, $myAcctID, $clientId) : "";
                ($detectRightSender === "Agent" && $amount <= $mywalletBal) ? $this->updateUserWallet($senderBalance, $myAcctID, $clientId) : "";

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
                    $txtQuery = "UPDATE api_txtwaitinglist SET status = '$aTResponse' WHERE userid = '$myAcctID' AND refid = '$referenceId' AND status = '$mytxtstatus'";
                    $this->db->updateWaitingTxt($txtQuery, $aTResponse);
 
                    //INSERT WALLET HISTORY
                    $query2 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $this->db->insertWalletHistory($query2, $clientId, $referenceId, $phoneNumber, '', $amount, 'Debit', $currency, 'Databundle - API', $telco . ' Databundle Purchase for ' . $productCode . ' with refid: ' . $referenceId . ' via API', 'successful', $transactionDateTime, $myAcctID, $senderBalance);
                    $this->db->insertWalletHistory($query2, $clientId, $referenceId, $phoneNumber, $dataCommission, '', 'Credit', $currency, 'Commission - API', $telco . ' Databundle Purchase Commission for ' . $productCode . ' with refid: ' . $referenceId . ' via API', 'successful', $transactionDateTime, $myAcctID, $senderBalance);

                    ($senderEmail == "") ? "" : $this->airtimeDateEmailNotifier($senderEmail, $companyEmail, $referenceId, $senderName, $type, $phoneNumber, $telco, $companyName, $currency, $amount, $amountToDebit, $senderBalance, $clientId);

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

                            "purchasedBy" => $senderName,

                            "responseDateTime" => date("Y-m-d H:i:s")

                        ]

                    ];

                }else{

                    $myWaitingList = $this->fetchTxtWaitingList($myAcctID,$referenceId,$mytxtstatus);
                    $myWaitingData = $myWaitingList['mydata'];

                    $myParameter = (explode('|',$myWaitingData));
                    $defaultBalance = $myParameter[4];

                    ($detectRightSender === "Customer") ? $this->updateBorrowerWallet($defaultBalance, $myAcctID, $clientId) : "";
                    ($detectRightSender === "Agent") ? $this->updateUserWallet($defaultBalance, $myAcctID, $clientId) : "";

                    //UPDATE WAITING TXT
                    $txtQuery = "UPDATE api_txtwaitinglist SET status = '$aTResponse' WHERE userid = '$myAcctID' AND refid = '$referenceId' AND status = '$mytxtstatus'";
                    $this->db->updateWaitingTxt($txtQuery, $aTResponse);

                    return -6; //Unable to conclude transaction, try again later.

                }

            }else{

                return -7; //You are not authorize to access this service at the moment

            }

        }else{

            return -8;

        }

    }


    //FETCH Daily / Weekly / Monthly / Yearly WALLET HISTORY
    public function fetchWalletHistory($account_no, $frequency, $clientId, $companyName) {

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

        $query = "SELECT * FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$clientId' AND initiator = '$account_no' ORDER BY id DESC";
        $output = $this->db->fetchAll($query, $clientId);
        $countOutput = count($output);

        if($customer === 0){

            return -1;
                    
        }elseif($countOutput >= 1){

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
     *  "endDate" : "2020-20-01",
     *  "ledgerAccountNo" : "9878277278",
     *  "clientID" : "INST1228288"
    *  }
     * 
     * */
    public function fetchWalletHistoryByDate($parameter, $clientId, $companyName) {

        if(isset($parameter->startDate) && isset($parameter->endDate) && isset($parameter->ledgerAccountNo) && isset($parameter->clientID)) {

            $myStartDate = $parameter->startDate;
            $myEndDate = $parameter->endDate;
            $ledgerAccountNo = $parameter->ledgerAccountNo;
            $instID = $parameter->clientID;

            $startDate = $myStartDate.' 00:00:00'; // get start date from here
            $endDate = $myEndDate.' 24:00:00';

            //FOR CUSTOMER/BORROWER
            $customer = $this->fetchCustomerByAcctId($ledgerAccountNo, $clientId);

            $query = "SELECT * FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$clientId' AND initiator = '$ledgerAccountNo' ORDER BY id DESC";
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
                
                return -5;
                
            }

        }else{

            return -4;

        }

    }


    //FETCH ALL CUSTOMER WALLET ACCOUNT
    public function fetchCustomerWalletAcct($account_no, $clientId, $companyName) {

        //FOR CUSTOMER/BORROWER
        $customer = $this->fetchCustomerByAcctId($account_no, $clientId);

        $query = "SELECT * FROM virtual_account WHERE userid = '$account_no' AND companyid = '$clientId' AND status = 'ACTIVE' ORDER BY id DESC";
        $output = $this->db->fetchAll($query, $clientId);
        $countOutput = count($output);

        if($customer === 0){

            return -1;
                    
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
            
            return -1;
            
        }

    }


    //ENDPOINT URL FOR Merchant Info - JS:
    public function merchantInfo($clientId, $companyName, $companyPhone) {
        
        //FOR Merchant Info
        $memberSet = $this->db->executeCall($clientId);

        if($memberSet === 0){

            return -1; //invalid 
                    
        }else{

            $myimage = $memberSet['logo'];

            $imagePath = $fileBaseUrl.$myimage;
                    
            $getCorrectImage = preg_match('/\s/',$imagePath);
                        
            $detectValidImageFormat = ($getCorrectImage >= 1) ? 'https://esusu.app/img/esusu.africa.png' : $imagePath;
                        
            $encryptedImage = ($myimage == "" || $myimage == "img/") ? file_get_contents($fileBaseUrl.'esusu.africa.png') : file_get_contents($detectValidImageFormat); 
                          
            // Encode the image string data into base64 
            $imageData = base64_encode($encryptedImage); 

            return [

                "resposeCode"=> "00",

                "merchantID"=> $clientId,

                "merchantName"=> $companyName,

                "officialContact"=> $companyPhone,

                "merchantLogo"=> $imageData,

                "merchantWalletID"=> $memberSet['merchantWalletID'],

                "message"=> "Info Fetched Successfully"

            ];

        }

    }


    /** ENDPOINT URL TO ACCEPT PAYMENT TO MERCHANT WALLET:
     * 
     * {
     *  "referenceId" : "test-ehehh233hsh33h",
     *  "walletAccountNumber" : "1234567890",
     *  "amountToPay" : "1000",
     *  "transactionPin" : "1234",
     *  "merchantWalletID" : "INST-00000000",
     *  "clientID" : "INST1228288"
    *  }
     * 
     * */
    public function acceptWalletPayment($parameter, $clientId, $companyName, $companyEmail) {

        if(isset($parameter->referenceId) && isset($parameter->walletAccountNumber) && isset($parameter->amountToPay) && isset($parameter->transactionPin) && isset($parameter->merchantWalletID) && isset($parameter->clientID)) {

            $referenceId = $parameter->referenceId;
            $walletAccountNumber = $parameter->walletAccountNumber;
            $amountToPay = preg_replace('/[^0-9.]/', '', $parameter->amountToPay);
            $transactionPin = $parameter->transactionPin;
            $merchantWalletID = $parameter->merchantWalletID;
            $instID = $parameter->clientID;
            $ptype = "p2p-transfer";
            $transactionDateTime = date("Y-m-d h:i:s");

            //lookup account validity
            $searchVA = $this->fetchVAByAcctNo($walletAccountNumber);
            $accountID = $searchVA['userid'];

            //Search if borrowers
            $searchCustomer = $this->fetchCustomerByAcctId($accountID,$clientId);
            //Search if staff
            $searchUser = $this->fetchTerminalOprt($accountID,$clientId);
            //filter correct sender info
            $myTPin = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['tpin'] : $searchUser['tpin'];
            $mywalletBal = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['wallet_balance'] : $searchUser['transfer_balance'];
            $myAcctID = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['account'] : $searchUser['id'];
            $senderEmail = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['email'] : $searchUser['email'];
            $senderName = ($searchCustomer != 0 && $searchUser === 0) ? $searchCustomer['lname'].' '.$searchCustomer['fname'] : $searchUser['name'].' '.$searchUser['fname'];
            $detectRightSender = ($searchCustomer != 0 && $searchUser === 0) ? "Customer" : "Agent";

            $checkIdempotence = $this->fetchWalletHistoryByRefId($referenceId);

            $memberSet = $this->db->executeCall($clientId);
            $currency = $memberSet['currency'];
            $walletPaymentType = $memberSet['walletPaymentType'];
            $walletPaymentCharges = $memberSet['walletPaymentCharges'];
            $walletPaymentChargesCapped = $memberSet['walletPaymentChargesCapped'];
            $calcPercentage = (($walletPaymentCharges * $amountToPay) >= $walletPaymentChargesCapped) ? $walletPaymentChargesCapped : ($walletPaymentCharges * $amountToPay);
            $paymentCharges = ($walletPaymentType == "Flat") ? $walletPaymentCharges : $calcPercentage;
            $amountToDebit = $amountToPay + $paymentCharges;

            //Search Merchant Wallet ID Validity
            $searchMerchant = $this->fetchTerminalOprt($merchantWalletID,$clientId);
            $merchantWalletBalance = $searchMerchant['transfer_balance'];

            if($referenceId === "" || $walletAccountNumber === "" || $amountToPay === "" || $transactionPin === "" || $merchantWalletID === "" || $clientID === ""){

                return -1; //Missing field required

            }elseif($searchVA === 0){

                return -2; //account not found
                        
            }elseif($checkIdempotence != 0){

                return -3; //duplicate transactio is not allowed

            }elseif($mywalletBal < $amountToDebit){

                return -4; //insufficient balance in wallet

            }elseif($transactionPin != $myTPin){

                return -5; //invalid transaction pin
                        
            }elseif($searchMerchant === 0){

                return -6; //invalid merchant information

            }elseif($instID != $clientId){

                return -7; //invalid client id

            }else{
                
                //Deduct Payee Wallet
                $senderBalance = $mywalletBal - $amountToDebit;
                ($detectRightSender === "Customer" && $amountToDebit <= $mywalletBal) ? $this->updateBorrowerWallet($senderBalance, $myAcctID, $clientId) : "";
                ($detectRightSender === "Agent" && $amountToDebit <= $mywalletBal) ? $this->updateUserWallet($senderBalance, $myAcctID, $clientId) : "";
                
                //Cedit Merchant Wallet
                $receiverBalance = $merchantWalletBalance + $amountToPay;
                $this->updateUserWallet($receiverBalance, $merchantWalletID, $clientId);

                //INSERT WALLET HISTORY
                $query2 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertSpecialWalletHistory($query2, $clientId, $referenceId, $myAcctID, '', $amountToPay, 'Debit', $currency, $ptype, 'Accept Wallet Payment from: ' . $walletAccountNumber . ' with refid: ' . $referenceId . ' via API', 'successful', $transactionDateTime, $myAcctID, $senderBalance, $receiverBalance);
                $this->db->insertSpecialWalletHistory($query2, $clientId, $referenceId, 'Payment Charge for: '.$walletAccountNumber, $paymentCharges, '', 'Debit', $currency, 'Wallet Payment Charges from: ' . $walletAccountNumber . ' with refid: ' . $referenceId . ' via API', 'successful', $transactionDateTime, $myAcctID, $senderBalance, $receiverBalance);

                $this->walletCreditEmailNotifier($companyEmail, $referenceId, $transactionDateTime, $companyName, $senderName, $walletAccountNumber, $currency, $amountToPay, $receiverBalance, $clientId);
                $this->walletDebitEmailNotifier($senderEmail, $companyEmail, $referenceId, $transactionDateTime, $companyName, $senderName, $walletAccountNumber, $currency, $amountToDebit, $senderBalance, $clientId);

                return [

                    "responseCode" => "00",

                    "status" => "Success",

                    "data" => [

                        "walletAcctNo" => $walletAccountNumber,

                        "walletAcctName" => $senderName,

                        "amountPaid" => $amountToPay,

                        "charges" => $paymentCharges,

                        "amountCharged" => $amountToDebit,

                        "paymentMethod" => "Wallet",

                        "merchantName" => $companyName,

                        "responseDateTime" => $transactionDateTime

                    ]

                ];

            }

        }else{

            return -8; //invalid json is not allowed

        }

    }


}

?>