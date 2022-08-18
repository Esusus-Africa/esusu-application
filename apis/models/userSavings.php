<?php

class Savings extends User {

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

    //SAVINGS EMAIL NOTIFICATION
    public function sendEmail($email, $transactionType, $txid, $fname, $lname, $p_type, $inst_name, $account, $currency, $amt, $ledger_bal, $registeral){

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


    //FETCH ALL CUSTOMERS DEPOSIT BY LIMIT
    public function fetchDepositByLimit($parameter, $limit) {

        $query = "SELECT * FROM transaction WHERE branchid = ? AND t_type = 'Deposit' ORDER BY id LIMIT $limit";

        return $this->db->fetchAll($query, $parameter);

    }

    //FETCH ALL CUSTOMERS DEPOSIT 
    public function fetchAllDeposit($parameter) {

        $query = "SELECT * FROM transaction WHERE branchid = ? AND t_type = 'Deposit'";

        return $this->db->fetchAll($query, $parameter);

    }

    //FETCH ALL CUSTOMERS DEPOSIT MADE BY A PARTICULAR STAFF USING STAFF ID
    public function fetchDepositByStaff($parameter, $parameter1) {

        $query = "SELECT * FROM transaction WHERE posted_by = ? AND branchid = '$parameter1' AND t_type = 'Deposit'";

        return $this->db->fetchAll($query, $parameter);

    }

     /** ENDPOINT URL FOR DEPOSIT ARE:
     * 
     * {
     *  "txid" : "apiTXID-11111111111111111",
     *  "p_type" : "Cash",
	*   "acctno" : "186277222",
    *   "amount" : "1000",
    *   "remark" : "direct deposit",
    *   "currency" : "NGN",
    *   "ePin" : "xxx"
    *  }
     * 
     * */
    public function postDeposit($parameter, $registeral, $reg_branch, $reg_staffid, $reg_staffName, $companyName, $irole, $allow_auth, $mytpin) {

        if(isset($parameter->txid) && isset($parameter->acctno) && isset($parameter->currency) && isset($parameter->amount) && isset($parameter->p_type) && isset($parameter->ePin)) {

            $txid = $parameter->txid; //"apiTXID-".rand(100000000000000,999999999999999);
            $p_type = $parameter->p_type;
            $acctno = $parameter->acctno;
            $amount = $parameter->amount;
            $remark = $parameter->remark;
            $currency = ($parameter->currency === "") ? "NGN" : $parameter->currency;
            $ePin = $parameter->ePin; 

            $custDetails = $this->db->fetchCustomer($acctno, $registeral);
            $fname = $custDetails['fname'];
            $lname = $custDetails['lname'];
            $email = $custDetails['email'];
            $phone = $custDetails['phone'];
            $ledger_bal = $custDetails['balance'];
            $total_ledgerBal = $ledger_bal + $amount;
            $date_time = date("Y-m-d h:m:s");
            $transfer_to = "----";
            $t_type = "Deposit";

            $verifyCustAcct = $this->db->verifyCustAccount($acctno,$registeral);
            
            //$checkAuth = $this->accessKey($tokenId,$registeral);
            
            $verifytill = $this->fetchTillAcct($reg_staffid);
            $balance = $verifytill['balance'];
            
            $resultsInfo = $this->db->executeCall($registeral);
            
            //FETCH INSTITUTION WALLET BALANCE
            $getInst = $this->fetchInstitutionById($registeral);
            $inst_wallet = $getInst['wallet_balance'];
    
            //FETCH TRANSACTION CHARGES
            $trans_charges = $resultsInfo['t_charges'];

            if($txid === "" || $p_type === "" || $amount === "" || $acctno === "" || $ePin === ""){

                return -3;

            }elseif($verifyCustAcct === 0){

                return -2;

            }elseif($balance < $amount && $verifytill != "0"){
                
                return -5;
                
            }elseif($inst_wallet < $trans_charges){
                
                return -6;
                
            }elseif($ePin != $mytpin){

                return -7;

            }
            elseif($verifytill === "0" && ($irole != "agent_manager" || $irole != "institution_super_admin" || $irole != "merchant_super_admin")){
                
                return [
                    array(
                        "responseCode"=> "01",
                        "message"=>"No Till Account Created!!"
                        )
                    ];
                
            }elseif($verifytill === "0" && ($irole === "agent_manager" || $irole === "institution_super_admin" || $irole === "merchant_super_admin")){
                
                return [
                    
                    "responseCode"=> "00",

                    "t_status" => "Success",

                    "message" => "Deposited Successfully",

                    "transaction_details" => [

                        "transaction_id"=> $txid,

                        "account"=> $parameter->acctno,

                        "amount"=> $parameter->amount,

                        "first_name"=> $fname,

                        "last_name"=> $lname,

                        "email"=> $email,

                        "phone"=> $phone,

                        "total_balance"=> $total_ledgerBal,

                        "remark"=> $remark,

                        "staffId"=> $reg_staffid,
                        
                        "staffName"=> $reg_staffName,
                        
                        "companyName"=> $companyName,

                        "transaction_date"=> $date_time,

                    ]

                ];
                
            }else{

                return [
                    
                    "responseCode"=> "00",

                    "t_status" => "Success",

                    "message" => "Deposited Successfully",

                    "transaction_details" => [

                        "transaction_id"=> $txid,

                        "account"=> $parameter->acctno,

                        "amount"=> $parameter->amount,

                        "first_name"=> $fname,

                        "last_name"=> $lname,

                        "email"=> $email,

                        "phone"=> $phone,

                        "total_balance"=> $total_ledgerBal,

                        "remark"=> $remark,

                        "staffId"=> $reg_staffid,
                        
                        "staffName"=> $reg_staffName,
                        
                        "companyName"=> $companyName,

                        "transaction_date"=> $date_time,

                    ]

                ];

            }

        }else{
            return -1;
        }

    }


    //FETCH ALL CUSTOMERS WITHDRAWAL BY LIMIT
    public function fetchWithdrawByLimit($parameter, $limit) {

        $query = "SELECT * FROM transaction WHERE branchid = ? AND t_type = 'Withdraw' ORDER BY id LIMIT $limit";

        return $this->db->fetchAll($query, $parameter);

    }


    //FETCH ALL CUSTOMERS WITHDRAWAL 
    public function fetchAllWithdraw($parameter) {

        $query = "SELECT * FROM transaction WHERE branchid = ? AND t_type = 'Withdraw'";

        return $this->db->fetchAll($query, $parameter);

    }


    //FETCH ALL CUSTOMERS WITHDRAWAL MADE BY A PARTICULAR STAFF USING STAFF ID
    public function fetchWithdrawByStaff($parameter, $parameter1) {

        $query = "SELECT * FROM transaction WHERE posted_by = ? AND branchid = '$parameter1' AND t_type = 'Withdraw'";

        return $this->db->fetchAll($query, $parameter);

    }


    //FETCH ALL CUSTOMERS WITHDRAWAL CHARGES BY LIMIT
    public function fetchWithdrawChargesByLimit($parameter, $limit) {

        $query = "SELECT * FROM transaction WHERE branchid = ? AND t_type = 'Withdraw-Charges' ORDER BY id LIMIT $limit";

        return $this->db->fetchAll($query, $parameter);

    }


    //FETCH ALL CUSTOMERS WITHDRAWAL CHARGES
    public function fetchAllWithdrawCharges($parameter) {

        $query = "SELECT * FROM transaction WHERE branchid = ? AND t_type = 'Withdraw-Charges'";

        return $this->db->fetchAll($query, $parameter);

    }
    
    
    /** ENDPOINT URL TO FETCH LEDGER COLLECTION / WITHDRAWAL / CHARGES / TRANSACTION COUNT WITH DATE INTERVAL:
     * 
     * {
     *  "startDate" : "",
     *  "endDate" : ""
    *  }
     * 
     * */
    public function fetchAggregateSavingsWithDate($parameter, $registeral, $irole, $reg_staffid, $reg_branch) {

        if(isset($parameter->startDate) && isset($parameter->endDate)) {

            $myStartDate = $parameter->startDate;
            $myEndDate = $parameter->endDate;

            $searchRole = ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $this->db->fetchRole($irole,"") : $this->db->fetchRole($irole,$registeral);
            $view_all_transaction = $searchRole['view_all_transaction'];
            $individual_transaction_records = $searchRole['individual_transaction_records'];
            $branch_transaction_records = $searchRole['branch_transaction_records'];
    
            $startDate = $myStartDate.' 00:00:00'; // get start date from here
            $endDate = $myEndDate.' 24:00:00';

            if($myStartDate === "" || $myEndDate === ""){

                return -1;

            }
            else{
                
                //yearly deposit
                ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND t_type = 'Deposit'" : "";
                ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' AND t_type = 'Deposit'" : "";
                ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' AND t_type = 'Deposit'" : "";
                $result = $this->db->fetchById($query, $registeral);
    
                //yearly withdrawal
                ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query2 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND t_type = 'Withdraw'" : "";
                ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query2 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' AND t_type = 'Withdraw'" : "";
                ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query2 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' AND t_type = 'Withdraw'" : "";
                $result2 = $this->db->fetchById($query2, $registeral);
    
                //yearly withdrawal charges
                ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query3 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND t_type = 'Withdraw-Charges'" : "";
                ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query3 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' AND t_type = 'Withdraw-Charges'" : "";
                ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query3 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' AND t_type = 'Withdraw-Charges'" : "";
                $result3 = $this->db->fetchById($query3, $registeral);
    
                //yearly transaction count
                ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query4 = "SELECT COUNT(*) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral'" : "";
                ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query4 = "SELECT COUNT(*) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid'" : "";
                ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query4 = "SELECT COUNT(*) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch'" : "";
                $result4 = $this->db->fetchById($query4, $registeral);
                
                return [
                    "responseCode"=> "00",
                    "totalDeposit"=> number_format($result['SUM(amount)'],2,'.',','),
                    "totalWithdrawal"=> number_format($result2['SUM(amount)'],2,'.',','),
                    "totalCharges"=> number_format($result3['SUM(amount)'],2,'.',','),
                    "totalTxtCount"=> number_format($result4['COUNT(*)'],0,'',',')
                ];

            }

        }
        else{

            return -2;

        }

    }


    /** ENDPOINT URL FOR WITHDRAWAL ARE:
     * 
     * {
     *  "txid" : "apiTXID-11111111111111111",
     *  "p_type" : "Cash",
	*   "acctno" : "186277222",
    *   "amount" : "1000",
    *   "wcharges" : "200",
    *   "posted_by" : "MEM176363",
    *   "remark" : "direct deposit",
    *   "currency" : "NGN",
    *   "ePin" : "xxx"
    *  }
     * 
     * */
    public function postWithdrawal($parameter, $registeral, $reg_branch, $reg_staffid, $reg_staffName, $companyName, $irole, $allow_auth, $mytpin) {

        if(isset($parameter->txid) && isset($parameter->acctno) && isset($parameter->currency) && isset($parameter->amount) && isset($parameter->p_type) && isset($parameter->wcharges) && isset($parameter->ePin)) {

            $txid = $parameter->txid; //"apiTXID-".rand(100000000000000,999999999999999);
            $p_type = $parameter->p_type;
            $acctno = $parameter->acctno;
            $amount = $parameter->amount;
            $wcharges = ($parameter->wcharges === "") ? 0.0 : $parameter->wcharges;
            $remark = $parameter->remark;
            $currency = ($parameter->currency === "") ? "NGN" : $parameter->currency;
            $ePin = $parameter->ePin;

            $custDetails = $this->db->fetchCustomer($acctno, $registeral);
            $fname = $custDetails['fname'];
            $lname = $custDetails['lname'];
            $email = $custDetails['email'];
            $phone = $custDetails['phone'];
            $ledger_bal = $custDetails['balance'];
            $date_time = date("Y-m-d h:m:s");
            $transfer_to = "----";
            $t_type = "Withdraw";
            $t_type1 = "Withdraw-Charges";
            $total_ledgerBal = $ledger_bal - ($amount + $wcharges);

            $verifyCustAcct = $this->db->verifyCustAccount($acctno,$registeral);
            
            //$checkAuth = $this->accessKey($tokenId,$registeral);
            
            $verifytill = $this->fetchTillAcct($reg_staffid);
            $balance = $verifytill['balance'];
            
            $resultsInfo = $this->db->executeCall($registeral);
            
            //FETCH INSTITUTION WALLET BALANCE
            $getInst = $this->fetchInstitutionById($registeral);
            $inst_wallet = $getInst['wallet_balance'];
    
            //FETCH TRANSACTION CHARGES
            $trans_charges = $resultsInfo['t_charges'];

            if($txid === "" || $p_type === "" || $amount === "" || $acctno === "" || $ePin === ""){

                return -3;

            }elseif($verifyCustAcct === 0){

                return -2;

            }elseif($inst_wallet < $trans_charges){
                
                return -5;
                
            }elseif($ledger_bal < ($amount + $wcharges)){
                
                return -6;
                
            }elseif($ePin != $mytpin){

                return -7;
                    
            }elseif($verifytill === "0" && ($irole != "agent_manager" || $irole != "institution_super_admin" || $irole != "merchant_super_admin")){
                
                return [
                    array(
                        "responseCode"=> "01",
                        "message"=>"No Till Account Created!!"
                        )
                    ];
                
            }elseif($verifytill === "0" && ($irole === "agent_manager" || $irole === "institution_super_admin" || $irole === "merchant_super_admin")){
                
                return [
                    
                    "responseCode"=> "00",

                    "t_status" => "Success",

                    "message" => "Withdrawn Successfully",

                    "transaction_details" => [

                        "transaction_id"=> $txid,

                        "account"=> $parameter->acctno,

                        "amount"=> $parameter->amount,

                        "charges"=> $parameter->wcharges,

                        "first_name"=> $fname,

                        "last_name"=> $lname,

                        "email"=> $email,

                        "phone"=> $phone,

                        "total_balance"=> $total_ledgerBal,

                        "remark"=> $remark,

                        "staffId"=> $reg_staffid,
                        
                        "staffName"=> $reg_staffName,
                        
                        "companyName"=> $companyName,

                        "transaction_date"=> $date_time,

                    ]

                ];
                
            }else{

                return [
                    
                    "responseCode"=> "00",

                    "t_status" => "Success",

                    "message" => "Withdrawn Successfully",

                    "transaction_details" => [

                        "transaction_id"=> $txid,

                        "account"=> $parameter->acctno,

                        "amount"=> $parameter->amount,

                        "charges"=> $parameter->wcharges,

                        "first_name"=> $fname,

                        "last_name"=> $lname,

                        "email"=> $email,

                        "phone"=> $phone,

                        "total_balance"=> $total_ledgerBal,

                        "remark"=> $remark,

                        "staffId"=> $reg_staffid,
                        
                        "staffName"=> $reg_staffName,
                        
                        "companyName"=> $companyName,

                        "transaction_date"=> $date_time,

                    ]

                ];

            }

        }else{
            return -1;
        }

    }


    //FETCH USER TRANSACTION BY LIMIT
    public function fetchTransByLimit($parameter, $limit) {

        $query = "SELECT * FROM transaction WHERE branchid = ? ORDER BY id LIMIT $limit";

        return $this->db->fetchAll($query, $parameter);

    }


    //FETCH ALL USER TRANSACTION
    public function fetchAllTrans($registeral,$companyName,$irole,$reg_staffid,$reg_branch) {
        
        $parameter = "";
        $searchRole = ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $this->db->fetchRole($irole,$parameter) : $this->db->fetchRole($irole,$registeral);
        $view_all_transaction = $searchRole['view_all_transaction'];
        $individual_transaction_records = $searchRole['individual_transaction_records'];
        $branch_transaction_records = $searchRole['branch_transaction_records'];

        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query = "SELECT * FROM transaction WHERE branchid = '$registeral' ORDER BY id DESC" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query = "SELECT * FROM transaction WHERE branchid = '$registeral' AND posted_by = '$reg_staffid' ORDER BY id DESC" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query = "SELECT * FROM transaction WHERE branchid = '$registeral' AND sbranchid = '$reg_branch' ORDER BY id DESC" : "";

        $output = $this->db->fetchAll($query, $registeral);
        
        $countOutput = count($output);

        if($countOutput >= 1){
            
            for($i = 0; $i <= $countOutput; $i++){
                    
                foreach($output as $putEntry => $key){
                    
                    $staffID = $key['posted_by'];
            
                    $searchStaff = $this->db->fetchStaff($staffID,$registeral);
                    
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
                        
                        "amount"=> $key['amount'],
                        
                        "balance"=> $key['balance'],
                        
                        "postedBy"=> $myStaffName,
                        
                        "merchantName"=> $companyName,
                        
                        "remark"=> $key['remark'],
                        
                        "dateTime"=> $key['date_time'],
                        
                        ];
                    $i++;
                }
                return $output2;
                
            }
            
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


    //FETCH Daily / Weekly / Monthly / Yearly SAVINGS TRANSACTION
    public function fetchAllTransByFreq($frequency,$registeral,$companyName,$irole,$reg_staffid,$reg_branch) {
        
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
        $view_all_transaction = $searchRole['view_all_transaction'];
        $individual_transaction_records = $searchRole['individual_transaction_records'];
        $branch_transaction_records = $searchRole['branch_transaction_records'];

        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query = "SELECT * FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' ORDER BY id DESC" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query = "SELECT * FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' ORDER BY id DESC" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query = "SELECT * FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' ORDER BY id DESC" : "";

        $output = $this->db->fetchAll($query, $registeral);
        
        $countOutput = count($output);

        if($countOutput >= 1){
            
            for($i = 0; $i <= $countOutput; $i++){
                    
                foreach($output as $putEntry => $key){
                    
                    $staffID = $key['posted_by'];
            
                    $searchStaff = $this->db->fetchStaff($staffID,$registeral);
                    
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
                        
                        "amount"=> $key['amount'],
                        
                        "balance"=> $key['balance'],
                        
                        "postedBy"=> $myStaffName,
                        
                        "merchantName"=> $companyName,
                        
                        "remark"=> $key['remark'],
                        
                        "dateTime"=> $key['date_time'],
                        
                        ];
                    $i++;
                }
                return [

                    "responseCode"=> "00",
                    
                    "status" => "Success",

                    "transDetails" => $output2

                ];
                
            }
            
        }else{
            
            return -1; //Data not found
            
        }

    }

    /** ENDPOINT URL TO FETCH ALL SAVINGS TRANSACTION WITH DATE:
     * 
     * {
     *  "startDate" : "2020-01-01",
     *  "endDate" : "2020-20-01"
    *  }
     * 
     * */
    public function fetchAllTransByDate($parameter,$registeral,$companyName,$irole,$reg_staffid,$reg_branch) {
        
        if(isset($parameter->startDate) && isset($parameter->endDate)) {

            $myStartDate = $parameter->startDate;
            $myEndDate = $parameter->endDate;

            $startDate = $myStartDate.' 00:00:00'; // get start date from here
            $endDate = $myEndDate.' 24:00:00';

            $parameter1 = "";
            $searchRole = ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $this->db->fetchRole($irole,$parameter1) : $this->db->fetchRole($irole,$registeral);
            $view_all_transaction = $searchRole['view_all_transaction'];
            $individual_transaction_records = $searchRole['individual_transaction_records'];
            $branch_transaction_records = $searchRole['branch_transaction_records'];

            ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query = "SELECT * FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' ORDER BY id DESC" : "";
            ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query = "SELECT * FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' ORDER BY id DESC" : "";
            ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query = "SELECT * FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' ORDER BY id DESC" : "";

            $output = $this->db->fetchAll($query, $registeral);
            $countOutput = count($output);

            if($myStartDate === "" || $myEndDate === ""){

                return -1; //empty parameters

            }elseif($countOutput >= 1){
            
                for($i = 0; $i <= $countOutput; $i++){
                        
                    foreach($output as $putEntry => $key){
                        
                        $staffID = $key['posted_by'];
                
                        $searchStaff = $this->db->fetchStaff($staffID,$registeral);
                        
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
                            
                            "amount"=> $key['amount'],
                            
                            "balance"=> $key['balance'],
                            
                            "postedBy"=> $myStaffName,
                            
                            "merchantName"=> $companyName,
                            
                            "remark"=> $key['remark'],
                            
                            "dateTime"=> $key['date_time'],
                            
                            ];
                        $i++;
                    }
                    return [
    
                        "responseCode"=> "00",
                        
                        "status" => "Success",
    
                        "transDetails" => $output2
    
                    ];
                    
                }
                
            }else{
                
                return -3; //Data not found
                
            }

        }else{

            return -2; //invalid Json

        }

    }


    //FETCH ALL USER TRANSACTION
    /**
     * public function fetchAllTransByStaff($parameter,$parameter1,$companyName) {

        $query = "SELECT * FROM transaction WHERE branchid = ? AND posted_by = '$parameter1' ORDER BY id DESC";

        $output = $this->db->fetchAll($query, $parameter);
        
        if($output >= 1){
            
            for($i = 0; $i <= $output; $i++){
                    
                foreach($output as $putEntry => $key){
                    
                    $staffID = $key['posted_by'];
            
                    $searchStaff = $this->db->fetchStaff($staffID,$parameter);
                    
                    $myStaffName = $searchStaff['name'].' '.$searchStaff['lname'];
                    
                    $output2[$i] = [
                        
                        "id"=> $key['id'],
                        
                        "transactionID "=> $key['txid'],
                        
                        "transactionType"=> $key['t_type'],
                        
                        "paymentType"=> $key['p_type'],
                        
                        "accountNumber"=> $key['acctno'],
                        
                        "customerFirstName"=> $key['fn'],
                        
                        "customerLastName"=> $key['ln'],
                        
                        "customerEmail"=> $key['email'],
                        
                        "customerPhone"=> preg_replace("/[^a-zA-Z0-9]/", "", $key['phone']),
                        
                        "currency"=> $key['currency'],
                        
                        "amount"=> $key['amount'],
                        
                        "balance"=> $key['balance'],
                        
                        "postedBy"=> $myStaffName,
                        
                        "merchantName"=> $companyName,
                        
                        "remark"=> $key['remark'],
                        
                        "dateTime"=> $key['date_time'],
                        
                        ];
                    $i++;
                }
                return $output2;
                
            }
            
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
    */


    //FETCH ALL TILL ACCOUNT HISTORY
    public function fetchAllTillHistory($registeral,$companyName,$irole,$reg_staffid,$reg_branch) {
        
        $parameter = "";
        $searchRole = ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $this->db->fetchRole($irole,$parameter) : $this->db->fetchRole($irole,$registeral);
        $view_all_transaction = $searchRole['view_all_transaction'];
        $individual_transaction_records = $searchRole['individual_transaction_records'];
        $branch_transaction_records = $searchRole['branch_transaction_records'];

        //($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query = "SELECT fund_allocation_history.id, fund_allocation_history.txid, fund_allocation_history.cashier, fund_allocation_history.manager_id, fund_allocation_history.ttype, fund_allocation_history.paymenttype, fund_allocation_history.currency, fund_allocation_history.amount_fund, fund_allocation_history.balance, fund_allocation_history.note_comment, fund_allocation_history.date_time, transaction.acctno, transaction.fn, transaction.ln, transaction.email, transaction.phone FROM fund_allocation_history FULL JOIN transaction ON fund_allocation_history.companyid = transaction.branchid WHERE fund_allocation_history.companyid = '$registeral' AND transaction.branchid = '$registeral'" : "";
        //($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query = "SELECT fund_allocation_history.id, fund_allocation_history.txid, fund_allocation_history.cashier, fund_allocation_history.manager_id, fund_allocation_history.ttype, fund_allocation_history.paymenttype, fund_allocation_history.currency, fund_allocation_history.amount_fund, fund_allocation_history.balance, fund_allocation_history.note_comment, fund_allocation_history.date_time, transaction.acctno, transaction.fn, transaction.ln, transaction.email, transaction.phone FROM fund_allocation_history FULL JOIN transaction ON fund_allocation_history.companyid = transaction.branchid WHERE fund_allocation_history.companyid = '$registeral' AND transaction.branchid = '$registeral' AND fund_allocation_history.cashier = '$reg_staffid'" : "";
        //($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query = "SELECT fund_allocation_history.id, fund_allocation_history.txid, fund_allocation_history.cashier, fund_allocation_history.manager_id, fund_allocation_history.ttype, fund_allocation_history.paymenttype, fund_allocation_history.currency, fund_allocation_history.amount_fund, fund_allocation_history.balance, fund_allocation_history.note_comment, fund_allocation_history.date_time, transaction.acctno, transaction.fn, transaction.ln, transaction.email, transaction.phone FROM fund_allocation_history FULL JOIN transaction ON fund_allocation_history.companyid = transaction.branchid WHERE fund_allocation_history.companyid = '$registeral' AND transaction.branchid = '$registeral' AND fund_allocation_history.branch = '$reg_branch'" : "";
        
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query = "SELECT * FROM fund_allocation_history WHERE companyid = '$registeral' ORDER BY id DESC" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query = "SELECT * FROM fund_allocation_history WHERE companyid = '$registeral' AND cashier = '$reg_staffid' ORDER BY id DESC" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query = "SELECT * FROM fund_allocation_history WHERE companyid = '$registeral' AND branch = '$reg_branch' ORDER BY id DESC" : "";
        
        $output = $this->db->fetchAll($query, $registeral);
        $countOutput = sizeof($output);

        if($countOutput >= 1){
            
            for($i = 0; $i <= $countOutput; $i++){
                
                foreach($output as $putEntry => $key){
                    
                    $staffID = ($key['paymenttype'] == "MANUAL_FUNDING") ? $key['manager_id'] : $key['cashier'];

                    $searchStaff = $this->db->fetchStaff($staffID,$registeral);
                    $myStaffName = $searchStaff['name'].' '.$searchStaff['lname'];

                    //$searchCustomer = $this->fetchSavingsTransaction($key['txid'],$registeral);
                    //$acctNo = $searchCustomer['acctno'];
                    //print_r($searchCustomer);
                    
                    $output2[$i] = [
                        
                        "id"=> $key['id'],
                        
                        "transactionID"=> $key['txid'],
                        
                        "transactionType"=> $key['ttype'],
                        
                        "paymentType"=> $key['paymenttype'],
                        
                        //"accountNumber"=> $key['acctno'],
                        
                        //"customerFirstName"=> $key['fn'],
                        
                        //"customerLastName"=> $key['ln'],
                        
                        //"customerEmail"=> $key['email'],
                        
                        //"customerPhone"=> preg_replace("/[^a-zA-Z0-9]/", "", $key['phone']),
                        
                        "currency"=> $key['currency'],
                        
                        "amount"=> $key['amount_fund'],
                        
                        "balance"=> $key['balance'],
                        
                        "postedBy"=> $myStaffName,
                        
                        "merchantName"=> $companyName,
                        
                        "remark"=> $key['note_comment'],
                        
                        "dateTime"=> $key['date_time'],
                        
                        ];
                    $i++;
                }
                return $output2;
                
            }
            
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


    //FETCH STAFF, CUSTOMER, BRANCH TOTAL LEDGER COLLECTION
    public function fetchIndivLedgerSavings($parameter, $parameter1) {

        $query = "SELECT SUM(amount) FROM transaction WHERE branchid = ? AND (acctno = '$parameter1' OR sbranchid = '$parameter1' OR posted_by = '$parameter1')";

        $result = $this->db->fetchAll($query, $parameter);
        
        $verifyCustAcct = $this->db->verifyCustAccount($parameter1,$parameter);

        $verifyStaff = $this->db->userVerifier($parameter,$parameter1);

        $verifyBranch = $this->fetchBranchById($parameter1, $parameter);
        
        echo $verifyCustAcct;
        
        if($verifyCustAcct === 0 && $verifyStaff == "" && $verifyBranch === 0){
            
            $output = [
                array(
                    "responseCode"=> "01",
                    "message"=> "Invalid ID Entered!"
                    )
                ];
            
            return $output;
            
        }
        else{
            
            $output = [
            array(
                "responseCode"=> "00",
                "total_LedgerSavingsCollection"=> ($result['SUM(amount)'] == "") ? number_format(0,2,'.',',') : number_format($result['SUM(amount)'],2,'.',',')
                )
            ];
            
            return $output;
            
        }

    }


    //FETCH TOTAL LEDGER COLLECTION (DEPOSIT)
    public function fetchAllLedgerSavings($parameter) {

        $query = "SELECT SUM(amount) FROM transaction WHERE branchid = '$parameter' AND t_type = 'Deposit'";

        $result = $this->db->fetchById($query, $parameter);
        
        $output = [
            array(
                "responseCode"=> "00",
                "totalAmount"=> number_format($result['SUM(amount)'],2,'.',',')
                )
            ];
            
        return $output;

    }
    
    
    //FETCH TOTAL LEDGER COLLECTION (WITHDRAWAL)
    public function fetchAllLedgerWithdrawal($parameter) {

        $query = "SELECT SUM(amount) FROM transaction WHERE branchid = '$parameter' AND t_type = 'Withdraw'";
        $result = $this->db->fetchById($query, $parameter);

        $query2 = "SELECT SUM(amount) FROM transaction WHERE branchid = '$parameter' AND t_type = 'Withdraw-Charges'";
        $result2 = $this->db->fetchById($query2, $parameter);
        
        $output = [
            array(
                "responseCode"=> "00",
                "totalAmount"=> number_format($result['SUM(amount)'],2,'.',','),
                "totalCharges"=> number_format($result2['SUM(amount)'],2,'.',',')
                )
            ];
            
        return $output;

    }


    //FETCH INDIVIDUAL LEDGER BALANCE
    public function fetchIndivLedgerBal($parameter, $parameter1) {

        $query = "SELECT balance FROM borrowers WHERE branchid = '$parameter' AND account = '$parameter1'";

        $result = $this->db->fetchById($query, $parameter);
        
        $verifyCustAcct = $this->db->verifyCustAccount($parameter1,$parameter);
        
        if($verifyCustAcct === 0){
            
            $output = [
                array(
                    "responseCode"=> "01",
                    "message"=> "Invalid A/c Number"
                    )
                ];
            
            return $output;
            
        }
        else{
            
            $output = [
            array(
                "responseCode"=> "00",
                "LedgerBalance"=> number_format($result['balance'],2,'.',',')
                )
            ];
            
            return $output;
            
        }

    }


    //FETCH TOTAL LEDGER BALANCE
    public function fetchAllLedgerBal($parameter) {

        $query = "SELECT SUM(balance) FROM borrowers WHERE branchid = '$parameter'";

        $result = $this->db->fetchById($query, $parameter);
        
        $output = [
            array(
                "responseCode"=> "00",
                "total_LedgerBalance"=> number_format(($result['SUM(balance)']),2,'.',',')
                )
            ];
            
        return $output;

    }

    //FETCH ALL USER TRANSACTION BY TXID (STRING) OR ID (INT) OR AccountNumber (INT)
    public function fetchTransById($parameter, $parameter1, $companyName) {

        $query = "SELECT * FROM transaction WHERE (id = '$parameter' OR txid = '$parameter' OR acctno = '$parameter') AND branchid = '$parameter1' ORDER BY id DESC";
        
        $output = $this->db->fetchAll($query, $parameter);
        
        if($output >= 1){
        
            for($i = 0; $i <= $output; $i++){
                    
                foreach($output as $putEntry => $key){
                    
                    $staffID = $key['posted_by'];
            
                    $searchStaff = $this->db->fetchStaff($staffID,$parameter);
                    
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
                        
                        "amount"=> $key['amount'],
                        
                        "balance"=> $key['balance'],
                        
                        "postedBy"=> $myStaffName,
                        
                        "merchantName"=> $companyName,
                        
                        "remark"=> $key['remark'],
                        
                        "dateTime"=> $key['date_time'],
                        
                        ];
                    $i++;
                }
                return $output2;
                
            }
            
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


    //FETCH DAILY LEDGER COLLECTION / WITHDRAWAL / CHARGES / TRANSACTION COUNT
    public function fetchDailyAggregateLedgerSavings($registeral,$irole,$reg_staffid,$reg_branch) {

        $parameter = "";
        $searchRole = ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $this->db->fetchRole($irole,$parameter) : $this->db->fetchRole($irole,$registeral);
        $view_all_transaction = $searchRole['view_all_transaction'];
        $individual_transaction_records = $searchRole['individual_transaction_records'];
        $branch_transaction_records = $searchRole['branch_transaction_records'];

        $startDate = date("Y-m-d").' 00'.':00'.':00';
        $endDate = date("Y-m-d").' 24'.':00'.':00';

        //daily deposit
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND t_type = 'Deposit'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' AND t_type = 'Deposit'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' AND t_type = 'Deposit'" : "";
        $result = $this->db->fetchById($query, $registeral);

        //daily withdrawal
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query2 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND t_type = 'Withdraw'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query2 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' AND t_type = 'Withdraw'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query2 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' AND t_type = 'Withdraw'" : "";
        $result2 = $this->db->fetchById($query2, $registeral);

        //daily withdrawal charges
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query3 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND t_type = 'Withdraw-Charges'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query3 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' AND t_type = 'Withdraw-Charges'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query3 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' AND t_type = 'Withdraw-Charges'" : "";
        $result3 = $this->db->fetchById($query3, $registeral);

        //daily transaction count
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query4 = "SELECT COUNT(*) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query4 = "SELECT COUNT(*) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query4 = "SELECT COUNT(*) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch'" : "";
        $result4 = $this->db->fetchById($query4, $registeral);
        
        $output = [
            array(
                "responseCode"=> "00",
                "totalDeposit"=> number_format($result['SUM(amount)'],2,'.',','),
                "totalWithdrawal"=> number_format($result2['SUM(amount)'],2,'.',','),
                "totalCharges"=> number_format($result3['SUM(amount)'],2,'.',','),
                "totalTxtCount"=> number_format($result4['COUNT(*)'],0,'',',')
                )
            ];
            
        return $output;

    }


    //FETCH WEEKLY LEDGER COLLECTION / WITHDRAWAL / CHARGES / TRANSACTION COUNT
    public function fetchWeeklyAggregateLedgerSavings($registeral,$irole,$reg_staffid,$reg_branch) {

        $parameter = "";
        $searchRole = ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $this->db->fetchRole($irole,$parameter) : $this->db->fetchRole($irole,$registeral);
        $view_all_transaction = $searchRole['view_all_transaction'];
        $individual_transaction_records = $searchRole['individual_transaction_records'];
        $branch_transaction_records = $searchRole['branch_transaction_records'];

        $dt_min = new DateTime("last saturday"); // Edit
        $dt_min->modify('+1 day'); // Edit
        $dt_max = clone($dt_min);
        $dt_max->modify('+6 days');
        
        $startDate = $dt_min->format('Y-m-d').' 00:00:00';
        $endDate = $dt_max->format('Y-m-d').' 24:00:00';

        //weekly deposit
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND t_type = 'Deposit'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' AND t_type = 'Deposit'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' AND t_type = 'Deposit'" : "";
        $result = $this->db->fetchById($query, $registeral);

        //weekly withdrawal
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query2 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND t_type = 'Withdraw'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query2 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' AND t_type = 'Withdraw'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query2 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' AND t_type = 'Withdraw'" : "";
        $result2 = $this->db->fetchById($query2, $registeral);

        //weekly withdrawal charges
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query3 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND t_type = 'Withdraw-Charges'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query3 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' AND t_type = 'Withdraw-Charges'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query3 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' AND t_type = 'Withdraw-Charges'" : "";
        $result3 = $this->db->fetchById($query3, $registeral);

        //weekly transaction count
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query4 = "SELECT COUNT(*) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query4 = "SELECT COUNT(*) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query4 = "SELECT COUNT(*) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch'" : "";
        $result4 = $this->db->fetchById($query4, $registeral);
        
        $output = [
            array(
                "responseCode"=> "00",
                "totalDeposit"=> number_format($result['SUM(amount)'],2,'.',','),
                "totalWithdrawal"=> number_format($result2['SUM(amount)'],2,'.',','),
                "totalCharges"=> number_format($result3['SUM(amount)'],2,'.',','),
                "totalTxtCount"=> number_format($result4['COUNT(*)'],0,'',',')
                )
            ];
            
        return $output;

    }


    //FETCH MONTHLY LEDGER COLLECTION / WITHDRAWAL / CHARGES / TRANSACTION COUNT
    public function fetchMonthlyAggregateLedgerSavings($registeral,$irole,$reg_staffid,$reg_branch) {

        $parameter = "";
        $searchRole = ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $this->db->fetchRole($irole,$parameter) : $this->db->fetchRole($irole,$registeral);
        $view_all_transaction = $searchRole['view_all_transaction'];
        $individual_transaction_records = $searchRole['individual_transaction_records'];
        $branch_transaction_records = $searchRole['branch_transaction_records'];

        $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
        $last_day_this_month  = date('Y-m-t'); //get last day in a month

        $startDate = date('Y-m-01', strtotime($first_day_this_month)).' 00:00:00';
        $endDate = date('Y-m-t', strtotime($last_day_this_month)).' 24:00:00';

        //monthly deposit
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND t_type = 'Deposit'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' AND t_type = 'Deposit'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' AND t_type = 'Deposit'" : "";
        $result = $this->db->fetchById($query, $registeral);

        //monthly withdrawal
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query2 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND t_type = 'Withdraw'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query2 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' AND t_type = 'Withdraw'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query2 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' AND t_type = 'Withdraw'" : "";
        $result2 = $this->db->fetchById($query2, $registeral);

        //monthly withdrawal charges
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query3 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND t_type = 'Withdraw-Charges'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query3 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' AND t_type = 'Withdraw-Charges'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query3 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' AND t_type = 'Withdraw-Charges'" : "";
        $result3 = $this->db->fetchById($query3, $registeral);

        //monthly transaction count
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query4 = "SELECT COUNT(*) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query4 = "SELECT COUNT(*) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query4 = "SELECT COUNT(*) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch'" : "";
        $result4 = $this->db->fetchById($query4, $registeral);
        
        $output = [
            array(
                "responseCode"=> "00",
                "totalDeposit"=> number_format($result['SUM(amount)'],2,'.',','),
                "totalWithdrawal"=> number_format($result2['SUM(amount)'],2,'.',','),
                "totalCharges"=> number_format($result3['SUM(amount)'],2,'.',','),
                "totalTxtCount"=> number_format($result4['COUNT(*)'],0,'',',')
                )
            ];
            
        return $output;

    }

    
    //FETCH YEARLY LEDGER COLLECTION / WITHDRAWAL / CHARGES / TRANSACTION COUNT
    public function fetchYearlyAggregateLedgerSavings($registeral,$irole,$reg_staffid,$reg_branch) {

        $parameter = "";
        $searchRole = ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $this->db->fetchRole($irole,$parameter) : $this->db->fetchRole($irole,$registeral);
        $view_all_transaction = $searchRole['view_all_transaction'];
        $individual_transaction_records = $searchRole['individual_transaction_records'];
        $branch_transaction_records = $searchRole['branch_transaction_records'];

        $startDate = date("Y-01-01").' 00:00:00'; // get start date from here
        $endDate = date("Y-12-t", strtotime($startDate)).' 24:00:00';

        //yearly deposit
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND t_type = 'Deposit'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' AND t_type = 'Deposit'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' AND t_type = 'Deposit'" : "";
        $result = $this->db->fetchById($query, $registeral);

        //yearly withdrawal
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query2 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND t_type = 'Withdraw'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query2 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' AND t_type = 'Withdraw'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query2 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' AND t_type = 'Withdraw'" : "";
        $result2 = $this->db->fetchById($query2, $registeral);

        //yearly withdrawal charges
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query3 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND t_type = 'Withdraw-Charges'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query3 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid' AND t_type = 'Withdraw-Charges'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query3 = "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch' AND t_type = 'Withdraw-Charges'" : "";
        $result3 = $this->db->fetchById($query3, $registeral);

        //yearly transaction count
        ($view_all_transaction == "1" && $individual_transaction_records == "" && $branch_transaction_records == "") ? $query4 = "SELECT COUNT(*) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "1" && $branch_transaction_records == "") ? $query4 = "SELECT COUNT(*) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND posted_by = '$reg_staffid'" : "";
        ($view_all_transaction == "" && $individual_transaction_records == "" && $branch_transaction_records == "1") ? $query4 = "SELECT COUNT(*) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$registeral' AND sbranchid = '$reg_branch'" : "";
        $result4 = $this->db->fetchById($query4, $registeral);
        
        $output = [
            array(
                "responseCode"=> "00",
                "totalDeposit"=> number_format($result['SUM(amount)'],2,'.',','),
                "totalWithdrawal"=> number_format($result2['SUM(amount)'],2,'.',','),
                "totalCharges"=> number_format($result3['SUM(amount)'],2,'.',','),
                "totalTxtCount"=> number_format($result4['COUNT(*)'],0,'',',')
                )
            ];
            
        return $output;

    }


    
}

?>