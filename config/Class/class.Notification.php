<?php

namespace Class\Notification;

class Notifier extends App {

    //Dynamic curl function for sms
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

    //Functions for formated ledger savings alert
    public static function alertMsg($senderid, $alertType, $currency, $amount, $final_charges, $accno, $remark, $txid, $currentdate, $balanceLeft){

        $message = "$senderid>>>$alertType";
		$message .= " Amt: ".$currency.number_format($amount,2,'.',',')."";
        $message .= ($final_charges === '0') ? "" : " Charges: ".$currency.number_format($final_charges,2,'.',',')."";
		$message .= " Acc: ".$this->ccMasking($accno)."";
		$message .= " Desc: ".substr($remark,0,20)." - ".$txid."";
		$message .= " Time: ".$this->convertDateTime($currentdate)."";
		$message .= " Bal: ".$currency.number_format($balanceLeft,2,'.',',')."";

        return $message;

    }

    //Function for formated customer reg alert
    public static function custAlertMsg($senderid, $fname, $accountDetail, $loginDetails, $transactionPin, $mobileapp_link){

        $message = "$senderid>>>Welcome $fname!";
        $message .= " $accountDetail, $loginDetails, Transaction Pin: $transactionPin. $mobileapp_link";

        return $message;

    }

    //Function for formated loan repayment alert
    public static function loanRepayAlertMsg($senderid, $customer, $icurrency, $amount_to_pay, $lid, $final_bal){

        $message = "$sysabb>>>Dear $customer! Your repayment of ".$icurrency.number_format($amount_to_pay,2,'.',',')." with Loan ID: $lid has been initiated successfully. ";
        $message .= "Your Loan Balance is: ".$icurrency.number_format($final_bal,2,'.',',')." Thanks.";

        return $message;

    }
    

    /**
     * THIS SECTION CONTAIN 
     * SMS NOTIFICATION SCRIPT
     * 
     * Written By: AKINADE AYODEJI T.
     * For: Scalability purpose
     */

    //Trigger sms charges
    public static function sendSMS($sender, $phone, $msg, $institution_id, $refid, $iuid){
        
        $getSMS_ProviderNum = $this->fetchWithTwoParam('sms', 'smsuser', $institution_id, 'status', 'Activated');
        $getSMS_ProviderNum1 = $this->fetchWithTwoParam('sms', 'smsuser', '', 'status', 'Activated');
        $ozeki_password = ($getSMS_ProviderNum === 0) ? $getSMS_ProviderNum1['password'] : $getSMS_ProviderNum['password'];
        $ozeki_url = ($getSMS_ProviderNum === 0) ? $getSMS_ProviderNum1['api'] : $getSMS_ProviderNum['api'];

        $instType = ($this->db->startsWith($institution_id,"MER") ? "merchant" : ($this->db->startsWith($institution_id,"INST") ? "institution" : "agent"));

        $url = 'action=send-sms';
        $url.= '&api_key='.$ozeki_password;
        $url.= '&to='.urlencode($phone);
        $url.= '&from='.urlencode($sender);
        $url.= '&sms='.urlencode($msg);
      
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
            $queryTxt = "INSERT INTO api_txtwaitinglist (userid, refid, mydata, status, date_time) VALUE(?, ?, ?, ?, ?)";
            $this->db->insertApiTransactionLog($queryTxt, $iuid, $refid, $urltouse, $response, $date_time);

        }

    }



    /**
     * THIS SECTION CONTAIN DIFFERENT
     * EMAIL NOTIFICATION SCRIPT
     * 
     * Written By: AKINADE AYODEJI T.
     * For: Scalability purpose
     */

    //Ledger Savings Alert
    public static function ledgerSavingsEmailNotifier($email, $transactionType, $txid, $fname, $lname, $p_type, $inst_name, $account, $currency, $amt, $ledger_bal, $registeral){
    
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

        $fetch_emailConfig = $this->db->fetchEmailConfig($registeral);
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
              "platform_name"     => $inst_name, //Platform Name
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

    //Withdrawal Request Notification
    public static function withdrawalRequestNotifier($email, $fullname, $iname, $inst_name, $ptype, $account, $currency, $amount, $bal, $remark, $registeral){
            
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

        $fetch_emailConfig = $this->db->fetchEmailConfig($registeral);
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
            "available_bal"   => $currency.number_format($bal,2,'.',','), //Available Balance
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

    //Login OTP Notifier
    public static function loginOtpNotifier($creatorEmail, $pageHeader, $newLocation, $ipAddress, $browserName, $deviceName, $activationCode, $registeral){
        
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

        $fetch_emailConfig = $this->db->fetchEmailConfig($registeral);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
        
        // Pass otp code
        $postdata = array(
            "From"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $email_from : $fetch_emailConfig['email_from']),
            "To"        => $creatorEmail,  //Receiver Email Address
            "TemplateId"    => '23799812',
            "TemplateModel" => [
                    "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
                    "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $brand_color : $fetch_emailConfig['brand_color']),
                    "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $logo_url : $fetch_emailConfig['logo_url']),
                    "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $website : $fetch_emailConfig['product_url']),
                    "page_header"       => $pageHeader,
                    "new_location"      => $newLocation,
                    "ip_address"        => $ipAddress,
                    "browser_name"      => $browserName,
                    "device_name"       => $deviceName,
                    "activation_code"   => $activationCode,
                    "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $live_chat_url : $fetch_emailConfig['live_chat']),
                    "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
                    "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $company_address : $fetch_emailConfig['company_address']),
                    "date_time"         => $correctdate  //Date Time              
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

    //Customer Welcome Email
    public static function customerRegEmailNotifier($email, $fname, $shortenedurl, $username, $password, $account, $myAccountNumber, $shortenedurl1){
        
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

        $fetch_emailConfig = $this->db->fetchEmailConfig($registeral);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
        
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
            "deactivation_url"  => $shortenedurl1,  //Customer Deactivation Link
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

    //Loan Repayment Alert
    public static function loanRepaymentEmailNotifier($em, $refid, $uname, $mycurrentTime, $theStatus, $channel, $account_no, $customer, $phone, $lid, $icurrency, $amount_to_pay, $final_bal){
        
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

        $fetch_emailConfig = $this->db->fetchEmailConfig($registeral);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
        
        // Pass the customer's authorisation code, email and amount
        $postdata =  array(
        "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $email_from : $fetch_emailConfig['email_from']),
        "To"            => $em,  //Borrower Email Address
        "TemplateId"    => '13352617',
        "TemplateModel" => [
            "refid"             => $refid, //Transaction ID
            "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $brand_color : $fetch_emailConfig['brand_color']),
            "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $logo_url : $fetch_emailConfig['logo_url']),
            "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $website : $fetch_emailConfig['product_url']),
            "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']),
            "name"              => $uname,  //Borrower username
            "trans_date"        => $mycurrentTime, //Transaction date
            "status"            => $theStatus,
            "channel"           => $channel,
            "account_number"    => $account_no, //Borrower Account Number
            "acct_name"         => $customer, //Borrower Full Name
            "phone"             => $phone,
            "platform_name"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $product_name : $fetch_emailConfig['product_name']), //Platform Name
            "loan_id"			=> $lid,
            "amount"            => $icurrency.number_format($amount_to_pay,2,'.',','), //Amount Paid
            "loan_balance"     	=> $icurrency.number_format($final_bal,2,'.',','), //Loan Balance
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


}

?>