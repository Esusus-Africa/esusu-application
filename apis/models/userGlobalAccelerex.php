<?php

class gAccelerex extends User {

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

    //EMAIL NOTIFICATION
    public function emailNotifier($emailReceiver, $TransactionReference, $responsemessage, $paymentMethod, $recipient, $settlmentType, $oprName, $bank, $pendingBal, $Amount, $detectStampDutyforAuto, $charges, $mywallet_balance, $subject, $TerminalID, $institutionid){

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

        $correctdate = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');

        $fetch_emailConfig = $this->db->fetchEmailConfig($institutionid);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
        
        // Pass the customer's authorisation code, email and amount
        $postdata =  array(
            "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $email_from : $fetch_emailConfig['email_from']),
            "To"            => $emailReceiver,  //Customer Email Address
            "TemplateId"    => '19608062',
            "TemplateModel" => [
              "txid"              => $TransactionReference,
              "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $brand_color : $fetch_emailConfig['brand_color']),
              "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $logo_url : $fetch_emailConfig['logo_url']),
              "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $website : $fetch_emailConfig['product_url']),
              "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
              "trans_date"        => $correctdate,
              "status"            => $responsemessage,
              "type"              => $paymentMethod,
              "acct_name"         => $recipient,
              "customer_mobile"   => "---",
              "settlement_type"   => ucwords($settlmentType),
              "operator_name"     => $oprName,
              "merchant_name"     => $bank,
              "pending_balance"   => $currencyCode.number_format($pendingBal,2,'.',','),
              "amount"            => $currencyCode.number_format($Amount,2,'.',','),
              "amount_settled"    => $currencyCode.number_format(($detectStampDutyforAuto - $charges),2,'.',','),
              "transfer_balance"  => $currencyCode.number_format($mywallet_balance,2,'.',','),
              "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $support_email : $fetch_emailConfig['support_email']),
              "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $live_chat_url : $fetch_emailConfig['live_chat']),
              "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
              "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $company_address : $fetch_emailConfig['company_address']),
              "subject"           => $subject,
              "tid"               => $TerminalID
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
    
    
    //EMAIL NOTIFICATION
    public function emailNotifier2($emailReceiver, $TransactionReference, $responsemessage, $paymentMethod, $recipient, $settlmentType, $oprName, $bank, $pendingBal, $Amount, $mywallet_balance, $subject, $TerminalID, $institutionid){

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

        $correctdate = date('m/d/Y').' '.(date(h) + 1).':'.date('i A');

        $fetch_emailConfig = $this->db->fetchEmailConfig($institutionid);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
        
        // Pass the customer's authorisation code, email and amount
        $postdata =  array(
            "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $email_from : $fetch_emailConfig['email_from']),
            "To"            => $emailReceiver,  //Customer Email Address
            "TemplateId"    => '19608062',
            "TemplateModel" => [
              "txid"              => $TransactionReference,
              "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $brand_color : $fetch_emailConfig['brand_color']),
              "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $logo_url : $fetch_emailConfig['logo_url']),
              "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $website : $fetch_emailConfig['product_url']),
              "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
              "trans_date"        => $correctdate,
              "status"            => $responsemessage,
              "type"              => $paymentMethod,
              "acct_name"         => $recipient,
              "customer_mobile"   => "---",
              "settlement_type"   => ucwords($settlmentType),
              "operator_name"     => $oprName,
              "merchant_name"     => $bank,
              "pending_balance"   => $currencyCode.number_format($pendingBal,2,'.',','),
              "amount"            => $currencyCode.number_format($Amount,2,'.',','),
              "amount_settled"    => "---",
              "transfer_balance"  => $currencyCode.number_format($mywallet_balance,2,'.',','),
              "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $support_email : $fetch_emailConfig['support_email']),
              "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $live_chat_url : $fetch_emailConfig['live_chat']),
              "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
              "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $company_address : $fetch_emailConfig['company_address']),
              "subject"           => $subject,
              "tid"               => $TerminalID
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
    public function userGeneralAlert($sysabb, $myPhone, $sms, $bbranchid, $TransactionReference, $sms_charges, $currencyCode, $wallet_date_time, $tidoperator, $mywallet_balance){
        
        $instType = ($this->startsWith($bbranchid,"MER") ? "merchant" : ($this->startsWith($bbranchid,"INST") ? "institution" : ($this->startsWith($bbranchid,"AGGR") ? "aggregator" : "agent")));

        //GET GATEWAY INFO
        $verifySMS_Provider = $this->fetchSMSGW1($bbranchid);
        $verifySMS_Provider1 = $this->fetchSMSGW2("Activated");
        $fetchSMS_Provider = ($verifySMS_Provider === 0) ? $verifySMS_Provider1 : $verifySMS_Provider;
        $ozeki_password = $fetchSMS_Provider['password'];
        $ozeki_url = $fetchSMS_Provider['api'];
        $debitWallet = ($verifySMS_Provider === 0) ? "Yes" : "No";
        
        $url = 'action=send-sms';
        $url.= '&api_key='.$ozeki_password;
        $url.= '&to='.urlencode($myPhone);
        $url.= '&from='.urlencode($sysabb);
        $url.= '&sms='.urlencode($sms);
      
        //Capture complete processing URL
        $urltouse =  $ozeki_url.$url;
      
        //Open the URL to send the message
        $response = $this->file_get_contents_curl($urltouse);

        //Confirm the response from Gateway
        $getResponse = json_decode($response, true);
        
        $okStatus = $getResponse['code'];

        if($okStatus == "ok"){

            $date_time = date("Y-m-d h:i:s");
            //Capture API Response
            mysqli_query($this->link, "INSERT INTO api_txtwaitinglist VALUE(null,'$tidoperator','$TransactionReference','$urltouse','$response','$date_time')");

            ($debitWallet == "Yes") ? $querySms = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance, receiver_bal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
            ($debitWallet == "Yes") ? $this->db->insertSpecialWalletHistory($querySms, $bbranchid, $TransactionReference, $myPhone, '', $sms_charges, 'Debit', $currencyCode, 'Charges', 'SMS Content: '.$sms, 'successful', $wallet_date_time, $tidoperator, $mywallet_balance, '') : "";
            
        }

    }

    /** ENDPOINT URL TO GET TERMINAL INFO:
     * 
     * api/globalAccelerex/terminal: Get terminal info with the following required field:
     * 
     * {
     *  "SerialNumber" : "123456",
     *  "TerminalID" : "2H893800"
    *  }
     * 
     * */
    public function getTerminalInfo($parameter) {

        if(isset($parameter->SerialNumber) && isset($parameter->TerminalID)){

            $SerialNumber = $parameter->SerialNumber;
            $TerminalID = $parameter->TerminalID;

            $checkTerm = $this->checkTerminalwithSerialNo($TerminalID, $SerialNumber);
            $tidoperator = $checkTerm['tidoperator'];
            $institutionid = $checkTerm['merchant_id'];

            if($TerminalID == ""){

                return -1; //TerminalID is empty (400)

            }elseif($SerialNumber == ""){

                return -2; //SerialNumber is empty (400)

            }elseif($checkTerm === 0){

                return -3; //Terminal not found (400)

            }else{

                $operatorInfo = $this->fetchTerminalOprt($tidoperator,$institutionid);

                return json_encode([
                    "Name" =>  $operatorInfo['lname'].' '.$operatorInfo['name'],
                    "TransactionLimit" => 1000,
                    "Fee" => $checkTerm['charges'],
                    "Address" => $operatorInfo['addr1'].", ".$operatorInfo['country'],
                    "Phone" => "0".substr($operatorInfo['phone'], -10)
                ]);

            }
    
        }else{

            return -4; //Field should not be empty (400)

        }

    }


    /** ENDPOINT URL TO RECEIVE TRANSACTION NOTIFICATION:
     * 
     * api/globalAccelerex/notification: Receive transaction notification on pos terminal with the following required field:
     * 
     * {
     *  "TransactionReference":"11111111111111389439774ss389888rrr43",
     *  "Reference":"389439774ss389888rrr43",
     *  "Amount": "50",
     *  "Type":"Purchase",
     *  "RetrievalReferenceNumber":"xxxxx",
     *  "MaskedPAN":"123456",
     *  "CardScheme":"xxxx",
     *  "CustomerName":"xxx",
     *  "StatusCode":"xxxxxxx",
     *  "StatusDescription":"xxxxxxxxxxxxxxxxxx",
     *  "Currency":"xxx",
     *  "MerchantId":"xxxx",
     *  "Stan":"xxxxx",
     *  "CardExpiry":"xxxx",
     *  "CardHash":"xxxxxxxx",
     *  "PaymentDate":"xxxxxxx",
     *  "TerminalID":"xxxx"
    *  }
     * 
     * */
    public function paymentNotification($parameter) {

        if(isset($parameter->TransactionReference) && isset($parameter->Reference) && isset($parameter->Amount) && isset($parameter->Type) && isset($parameter->RetrievalReferenceNumber) && isset($parameter->MaskedPAN) && isset($parameter->CardScheme) && isset($parameter->CustomerName) && isset($parameter->StatusCode) && isset($parameter->StatusDescription) && isset($parameter->Currency) && isset($parameter->MerchantId) && isset($parameter->Stan) && isset($parameter->CardExpiry) && isset($parameter->CardHash) && isset($parameter->PaymentDate)){

            $TransactionReference = $parameter->TransactionReference;
            $Reference = $parameter->Reference;
            $Amount = $parameter->Amount;
            $Type = $parameter->Type;
            $RetrievalReferenceNumber = $parameter->RetrievalReferenceNumber;
            $MaskedPAN = $parameter->MaskedPAN;
            $CardScheme = $parameter->CardScheme;
            $CustomerName = $parameter->CustomerName;
            $StatusCode = $parameter->StatusCode;
            $StatusDescription = $parameter->StatusDescription;
            $Currency = $parameter->Currency;
            $MerchantId = $parameter->MerchantId;
            $Stan = $parameter->Stan;
            $CardExpiry = $parameter->CardExpiry;
            $CardHash = $parameter->CardHash;
            $PaymentDate = $parameter->PaymentDate;
            $TerminalID = $parameter->TerminalID;

            $checkTerm = $this->checkTerminal($TerminalID);
            $tidoperator = $checkTerm['tidoperator'];
            $institutionid = $checkTerm['merchant_id'];

            //Check indepotent
            $checkIndepotent = $this->checkTerminalDuplicateRpt($TransactionReference);

            //check operator info
            $operatorInfo = $this->fetchTerminalOprt($tidoperator,$institutionid);

            if($TransactionReference == ""){

                return -1; //transaction reference should not be empty (400)

            }elseif($Amount == ""){

                return -2; //Amount should not be empty (400)

            }elseif($Amount <= 0){

                return -3; //Invalid Amount (400)

            }elseif($Currency == ""){

                return -4; //currency cannot be empty (400)

            }elseif($Currency != "NGN"){

                return -5; //Invalid currency (400)

            }elseif($Type == ""){

                return -6; //Transaction type cannot be empty (400)

            }elseif($Type != "Invoice" && $Type != "Purchase"){

                return -7; //Invalid transaction type (400)

            }elseif($CustomerName == ""){

                return -8; //Customer name should not be empty (400)

            }elseif($MaskedPAN == ""){

                return -9; //Masked PAN should not be empty (400)

            }elseif($PaymentDate == ""){

                return -10; //payment date should not be empty (400)

            }elseif($TerminalID == ""){

                return -11; //TerminalID should not be empty (400)

            }elseif($checkTerm === 0){

                return -12; //Invalid TerminalID (400)

            }elseif($checkIndepotent === 1){

                return json_encode([
                    "AdditionalInformation" => [
                        "Name" => "Agent",
                        "Value" => $operatorInfo['lname'].' '.$operatorInfo['name']
                    ],
                    "BillerReference" => $TransactionReference
                ]);

            }else{

                return json_encode([
                    "AdditionalInformation" => [
                        "Name" => "Agent",
                        "Value" => $operatorInfo['lname'].' '.$operatorInfo['name']
                    ],
                    "BillerReference" => $TransactionReference
                ]);

            }

        }else{

            return -13; //Field should not be empty (400)

        }

    }


    /** ENDPOINT URL TO RECEIVE TRANSACTION NOTIFICATION:
     * 
     * api/globalAccelerex/notification: Receive transaction notification on pos terminal with the following required field:
     * 
     * {
     *  "TransactionReference":"11111111111111389439774ss389888rrr43",
     *  "Amount": "50",
     *  "Currency":"xxx",
     *  "RetrievalReferenceNumber":"xxxxx",
     *  "PaymentDate":"xxx",
     *  "Stan":"xxxx",
     *  "Reference":"xxx",
     *  "TerminalID":"xxxx"
     * }
     * 
     * */
    public function paymentReversal($parameter) {
        
        if(isset($parameter->TransactionReference) && isset($parameter->Amount) && isset($parameter->Currency) && isset($parameter->RetrievalReferenceNumber) && isset($parameter->PaymentDate) && isset($parameter->Stan) && isset($parameter->Reference) && isset($parameter->TerminalID)){

            $TransactionReference = $parameter->TransactionReference;
            $Amount = $parameter->Amount;
            $Currency = $parameter->Currency;
            $RetrievalReferenceNumber = $parameter->RetrievalReferenceNumber;
            $PaymentDate = $parameter->PaymentDate;
            $Stan = $parameter->Stan;
            $Reference = $parameter->Reference;
            $TerminalID = $parameter->TerminalID;
            $today = date("Y-m-d h:i:s");
    
            //Check indepotent
            $checkIndepotent = $this->checkTerminalDuplicateRpt($TransactionReference);
            
            //Check terminal transaction with rrn
            $checkTxt = $this->checkTerminalRptWithRRN($RetrievalReferenceNumber);
    
            //check terminal validity
            $checkTerm = $this->checkTerminal($TerminalID);
    
            if($Amount == ""){
    
                return -1; //Amount should not be empty (400)
    
            }elseif($Amount <= 0){
    
                return -2; //Invalid Amount (400)
    
            }elseif($TransactionReference == ""){
    
                return -3; //Transaction reference should not be empty (400)
    
            }elseif($checkIndepotent === 0){
    
                return -4; //Invalid transaction reference (400)
    
            }elseif($RetrievalReferenceNumber == ""){
    
                return -5; //RRN should not be empty (400)
    
            }elseif($checkTxt === 0){
    
                return -6; //Invalid RRN (400)
    
            }elseif($PaymentDate == ""){
    
                return -7; //Payment date should not be empty (400)
    
            }elseif($PaymentDate != $today){
    
                return -11; //Invalid Payment date (400)
    
            }elseif($TerminalID == ""){
    
                return -8; //terminalID is empty (401)
    
            }elseif($checkTerm === 0){
    
                return -9; //Invalid terminalID (401)
    
            }else{
    
                return json_encode([
                    "status" => "True",
                    "code" => 200,
                    "message" => "Transaction logged successfully"
                ]);
    
            }
            
        }else{
            
            return -10;
            
        }

    }

}

?>