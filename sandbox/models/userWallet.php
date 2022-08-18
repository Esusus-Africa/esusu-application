<?php

class Wallet extends User {
    
    //p2p DEBIT NOTIFICATION
    public function sendP2pDebit($reg_mEmail, $refid, $reg_staffName, $datetime, $virtualAcctNo, $registeral, $currencyCode, $amountToTranfer, $senderBalance){

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

        $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$datetime,new DateTimeZone(date_default_timezone_get()));
        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
        $correctdate = $acst_date->format('Y-m-d g:i A');

        $getInst = $this->fetchInstitutionById($registeral);
        $merchantName = $getInst['institution_name'];
        
        // Pass the customer's authorisation code, email and amount
        $postdata =  array(
                    "From"          => $email_from,
                    "To"            => $reg_mEmail,  //User Email Address
                    "TemplateId"    => '20045888',
                    "TemplateModel" => [
                        "txid"              => $refid, //Transaction ID
                        "logo_url"          => $logo_url,
                        "product_url"       => $website,
                        "product_name"      => $product_name,
                        "acct_name"         => $reg_staffName, //User Full Name
                        "trans_date"        => $correctdate, //Transaction date
                        "platform_name"     => $product_name, //Platform Name
                        "account_id"        => $virtualAcctNo, //User Account ID
                        "merchant_name"     => $merchantName,
                        "amount"            => $currencyCode.number_format($amountToTranfer,2,'.',','), //Amount Transfer
                        "wallet_balance"    => $currencyCode.number_format($senderBalance,2,'.',','), //Wallet Balance (p2p-wallet-transfer)
                        "support_email"     => $support_email,
                        "live_chat_url"     => $live_chat_url,
                        "company_name"      => $sender_name,
                        "company_address"   => $company_address
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


    //p2p CREDIT NOTIFICATION
    public function sendP2pCredit($em, $refid, $walletAccountNumber, $datetime, $myname, $registeral, $currencyCode, $amountToTranfer, $totalwallet_balance){

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

        $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$datetime,new DateTimeZone(date_default_timezone_get()));
        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
        $correctdate = $acst_date->format('Y-m-d g:i A');

        $getInst = $this->fetchInstitutionById($registeral);
        $merchantName = $getInst['institution_name'];
        
        // Pass the customer's authorisation code, email and amount
        $postdata =  array(
                    "From"          => $email_from,
                    "To"            => $em,  //User Email Address
                    "TemplateId"    => '10291027',
                    "TemplateModel" => [
                        "txid"              => $refid, //Transaction ID
                        "product_name"      => $product_name,
                        "trans_date"        => $correctdate, //Transaction date
                        "platform_name"     => $product_name, //Platform Name
                        "acct_name"         => $myname, //User Full Name
                        "account_id"        => $walletAccountNumber, //User Account ID
                        "amount"            => $currencyCode.number_format($amountToTranfer,2,'.',','), //Amount Transfer
                        "wallet_balance"    => $currencyCode.number_format($totalwallet_balance,2,'.',','), //Wallet Balance (p2p-wallet-transfer)
                        "product_url"       => $website,
                        "logo_url"          => $logo_url,
                        "support_email"     => $support_email,
                        "live_chat_url"     => $live_chat_url,
                        "company_name"      => $sender_name,
                        "company_address"   => $company_address
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


    /** ENDPOINT URL FOR WALLET-TO-WALLET TRANSFER ARE:
     * 
     * api/wallet/p2pTransfer/    : To make wallet to wallet transfer within the system with the following required field:
     * 
     * {
     *  "walletAccountNumber": "2222222222",
     *  "amountToTranfer": "500",
     *  "narration": "transfer to my friend",
     *  "ePin": "1234"
    *  }
     * 
     * */

    public function p2pTransfer($parameter, $registeral, $reg_mEmail, $reg_staffName, $virtualAcctNo, $iacctType, $availableWalletBal, $reg_staffid) {
       
        if(isset($parameter->walletAccountNumber) && isset($parameter->amountToTranfer) && isset($parameter->narration) && isset($parameter->ePin)) {

            $walletAccountNumber = $parameter->walletAccountNumber;
            $amountToTranfer = preg_replace('/[^0-9.]/', '', $parameter->amountToTranfer);
            $narration = $parameter->narration;
            $ePin = $parameter->ePin;
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
            $totalwallet_balance = $receiverBalance + $amountToTranfer;

            $memberSet = $this->db->fetchMemberSettings($registeral);
            $currencyCode = $memberSet['currency'];
            
            if($walletAccountNumber === "" || $amountToTranfer === "" || $narration === "" || $ePin === ""){

                return -1; //Required field must not be empty

            }
            elseif($checkAcctValidity === 0){

                return -2; //Account not found

            }
            elseif(!filter_var($ePin, FILTER_VALIDATE_INT)){

                return -3; //Invalid Pin

            }
            elseif(preg_match('/^[0-9.]+(?:\.[0-9]{0,2})?$/', $amountToTranfer) == FALSE)
            {

                return -4; //Invalid Amount

            }
            elseif($amountToTranfer > $availableWalletBal){

                return -5; //Insufficient fund in wallet

            }
            elseif($ePin != $mytpin){

                return -6; //Pin validation failed

            }
            else{
                
                //DEDUCT FROM SENDER BALANCE
                $senderBalance = $availableWalletBal - $amountToTranfer;
                ($iacctType === "customer") ? $this->updateBorrowerWallet($senderBalance, $reg_staffid, $registeral) : "";
                ($iacctType === "agent") ? $this->updateUserWallet($senderBalance, $reg_staffid, $registeral) : "";

                //DEDUCT FROM RECIPIENT BALANCE AND LOG TRANSACTION
                ($detectRightReceiver == "Customer") ? $this->updateBorrowerWallet($totalwallet_balance, $receiverAcctId, $registeral) : "";
                ($detectRightReceiver == "Institution") ? $this->updateUserWallet($totalwallet_balance, $receiverAcctId, $registeral) : "";

                //LOG WALLET TRANSACTION
                $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance, receiver_bal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertWalletHistory($query, $registeral, $refid, $receiverAcctId, '', $amountToTranfer, 'Debit', $currencyCode, $paymenttype, $narration, 'successful', $datetime, $reg_staffid, $senderBalance, $totalwallet_balance);

                //SEND P2P DEBIT NOTIFICATION TO SENDER
                $this->sendP2pDebit($reg_mEmail, $refid, $reg_staffName, $datetime, $virtualAcctNo, $registeral, $currencyCode, $amountToTranfer, $senderBalance);

                //SEND P2P CREDIT NOTIFICATION TO RECEIVER
                $this->sendP2pCredit($em, $refid, $walletAccountNumber, $datetime, $myname, $registeral, $currencyCode, $amountToTranfer, $totalwallet_balance);

                return [
    
                    "paymentReference"=> $refid,

                    "amountTransfered"=> $amountToTranfer,

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

    }





}
?>