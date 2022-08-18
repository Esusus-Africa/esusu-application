<?php
class Database {
    //Db Parameters
    private $hostName = 'localhost';
    private $dbname = 'esusulive_esusuapp';
    private $username = 'esusulive_esusuapp';
    private $password = 'BoI9YR^zqs%M';
    private $pdo;

    //Start Connection
    public function __construct(){
        $this->pdo = null;
        
        try{
            $this->pdo = new PDO("mysql:host=$this->hostName;dbname=$this->dbname;",

            $this->username, $this->password);

            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            echo "Error : " . $e->getMessage;
        }
    }


    public function fetchAll($query, $parameter) {
        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter]);

        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return $stmt->fetchAll();
        }
    }


    public function fetchById($query, $parameter) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter]);

        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return $stmt->fetch();
        }
    }


    public function fetchSmsCredentials() {

        $query = "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute();

        return $stmt->fetch();

    }


    public function fetchSystemSet() {

        $query = "SELECT * FROM systemset";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute();

        return $stmt->fetch();

    }


    public function checkAuthKey($parameter) {

        $query = "SELECT * FROM institution_data WHERE api_key = '$parameter'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter]);

        return $stmt->fetch();

    }


    public function instDetails($parameter) {

        $query = "SELECT * FROM institution_data WHERE institution_id = '$parameter'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter]);

        return $stmt->fetch();

    }


    public function fetchEmailConfig($parameter) {

        $query = "SELECT * FROM email_config WHERE companyid = '$parameter'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter]);

        return $stmt->fetch();

    }
    
    
    public function fetchStaff($parameter,$parameter1) {

        $query = "SELECT * FROM user WHERE id = '$parameter' AND created_by = '$parameter1'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter1]);

        return $stmt->fetch();

    }
    
    
    public function fetchRole($parameter,$parameter1) {

        $query = "SELECT * FROM my_permission WHERE urole = '$parameter' AND companyid = '$parameter1'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter1]);

        return $stmt->fetch();

    }


    public function fetchCustomer($parameter,$parameter1) {

        $query = "SELECT * FROM borrowers WHERE (account = '$parameter' OR phone = '$parameter') AND branchid = '$parameter1'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter1]);

        return $stmt->fetch();

    }
    
    
    public function borrowerAuth($parameter,$parameter1) {

        $query = "SELECT * FROM borrowers WHERE username = '$parameter' AND password = '$parameter1'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter1]);

        return $stmt->fetch();

    }
    
    
    
    public function userAuth($parameter,$parameter1) {

        $query = "SELECT * FROM user WHERE username = '$parameter' AND password = '$parameter1'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter1]);

        return $stmt->fetch();

    }


    public function userVerifier($parameter,$parameter1) {

        $query = "SELECT * FROM user WHERE created_by = '$parameter' AND (id = '$parameter1' OR userid = '$parameter1')";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter1]);

        return $stmt->fetch();

    }
    
    
    public function accessKey($parameter,$parameter1) {

        $query = "SELECT * FROM institution_data WHERE institution_id = '$parameter' AND api_key = '$parameter1'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter1]);

        return $stmt->fetch();

    }



    public function fetchMemberSettings($parameter) {

        $query = "SELECT * FROM member_settings WHERE companyid = '$parameter'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter]);

        return $stmt->fetch();

    }


    public function fetchSavingSub($parameter) {

        $query = "SELECT * FROM savings_subscription WHERE (reference_no = '$parameter' OR subscription_code = '$parameter')";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter]);

        return $stmt->fetch();

    }


    public function fetchMobileSavingsTransLog($parameter,$parameter1) {

        $query = "SELECT * FROM mobileSavings_TransactionLog WHERE refid = '$parameter' AND acctid = '$parameter1'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter1]);

        return $stmt->fetch();

    }


    public function fetchEmailSettings($parameter) {

        $query = "SELECT * FROM email_config WHERE companyid = '$parameter'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter]);

        return $stmt->fetch();

    }
    
    
    public function fetchBankList($parameter) {

        $query = "SELECT * FROM bank_list4 WHERE bankcode = '$parameter'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter]);

        return $stmt->fetch();

    }



    public function fetchVA($parameter) {

        $query = "SELECT * FROM virtual_account WHERE userid = '$parameter'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter]);

        return $stmt->fetch();

    }



    public function sendSms($sender, $phone, $msg, $debug=false)
    {
        global $gateway_uname,$gateway_pass,$gateway_api;

        $url = 'username='.$gateway_uname;
        $url.= '&password='.$gateway_pass;
        $url.= '&sender='.urlencode($sender);
        $url.= '&recipient='.urlencode($phone);
        $url.= '&message='.urlencode($msg);

        $urltouse =  $gateway_api.$url;
        //if ($debug) { echo "Request: <br>$urltouse<br><br>"; }

        //Open the URL to send the message
        $response = file_get_contents($urltouse);
        if ($debug) {
            //echo "Response: <br><pre>".
            //str_replace(array("<",">"),array("&lt;","&gt;"),$response).
            //"</pre><br>"; 
        }
        return($response);
    }


    /**
    * Function to convert base64 to jpeg
    * @group string 
    */
    public function base64Tojpeg($bvn_picture, $dynamicStr) {
    
        $ifp = fopen( '../../../img/'.$dynamicStr, "wb" );
        fwrite( $ifp, base64_decode( $bvn_picture) );
        fclose( $ifp );
        return( $dynamicStr );
        
    }


    public function verifyCustomer($parameter,$parameter2) {

        $query = "SELECT * FROM borrowers WHERE (username = '$parameter' OR phone = '$parameter2')";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter2]);

        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return 1;
        }

    }


    public function verifyCustomer2($parameter,$parameter2, $parameter3) {

        $query = "SELECT * FROM borrowers WHERE branchid = '$parameter3' AND (username = '$parameter' OR phone = '$parameter2')";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter2,$parameter3]);

        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return 1;
        }

    }


    public function verifyCustomer3($parameter) {

        $query = "SELECT * FROM borrowers WHERE username = '$parameter'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter]);

        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return 1;
        }

    }


    public function verifyCustomer4($parameter, $parameter2) {

        $query = "SELECT * FROM borrowers WHERE username = '$parameter' AND branchid = '$parameter2'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter2]);

        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return 1;
        }

    }


    public function verifyCustomerActivation($parameter,$parameter2) {

        $query = "SELECT * FROM borrowers WHERE acct_status = 'Not-Activated' AND username = '$parameter' AND branchid = '$parameter2'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter2]);

        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return 1;
        }

    }
    
    
    public function verifyCustomerActivation2($parameter,$parameter2) {

        $query = "SELECT * FROM activate_member2 WHERE acct_status = 'Not-Activated' AND (username = '$parameter' OR phone = '$parameter2')";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter2]);

        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return 1;
        }

    }


    public function verifyCustAccount($parameter,$parameter1) {

        $query = "SELECT * FROM borrowers WHERE account = '$parameter' AND branchid = '$parameter1'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter1]);

        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return 1;
        }

    }



    public function verifyCompanyAuth($parameter,$parameter1,$parameter2) {

        $query = "SELECT * FROM user WHERE username = '$parameter' AND password = '$parameter1' AND created_by = '$parameter2'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter1,$parameter2]);

        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return 1;
        }

    }



    public function verifyCustomerAuth($parameter,$parameter1,$parameter2) {

        $query = "SELECT * FROM borrowers WHERE username = '$parameter' AND password = '$parameter1' AND branchid = '$parameter2'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter1,$parameter2]);

        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return 1;
        }

    }



    public function verifyBranch($parameter,$parameter1) {

        $query = "SELECT * FROM branches WHERE branchid = '$parameter' AND created_by = '$parameter1'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter,$parameter1]);

        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return 1;
        }

    }


    public function executeCall($merchantid) {

        $query = "SELECT 
        member_settings.cname, member_settings.sender_id, member_settings.currency, maintenance_history.cust_mfee, maintenance_history.tcharges_type, maintenance_history.t_charges, maintenance_history.capped_amt, maintenance_history.status
        FROM member_settings 
        LEFT JOIN maintenance_history ON member_settings.companyid = maintenance_history.company_id WHERE member_settings.companyid = '$merchantid' AND maintenance_history.company_id = '$merchantid'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$merchantid]);

        $results = $stmt->fetch();


        //EXECUTE CODE WITH RESPECT TO PLANS
        if ($results['status'] === "Activated") {

            //GRANT ACCESS

            $stmt = $this->pdo->prepare($query);

            $stmt->execute([$merchantid]);

            return $results;

        }else{
            
            return -1;

        }
    }


    public function insertAgentPc($query, $agenttype, $name, $gender, $bname, $address, $email, $phone_no, $username, $password, $date_time) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$agenttype, $name, $gender, $bname, $address, $email, $phone_no, $username, $password, $date_time]);

    }



    public function insertAgentPos($query, $name, $gender, $bname, $email, $phone_no, $date_time) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$name, $gender, $bname, $email, $phone_no, $date_time]);

    }


    public function insertGroup($query, $companyid, $gName, $maxMember, $date_time, $mFrequency) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$companyid, $gName, $maxMember, $date_time, $mFrequency]);

    }


    public function insertCustomerPc($query, $snum, $fname, $lname, $mname, $email, $phone_no, $gender, $dob, $addrs, $city, $state, $country, $username, $password, $community_role, $acct_number, $ledger_bal, $target_bal, $invst_bal, $loan_bal, $asset_bal, $decoded_picture, $last_with_date, $status, $lofficer, $otp_option, $currency, $wallet_bal, $over_draft, $card_id, $card_reg, $tpin, $regType, $gname, $gposition, $merchantid, $reg_branch, $acct_status, $date_time, $acct_opening_date, $smsChecker, $sendSmsStatus, $sendEmailStatus) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$snum, $fname, $lname, $mname, $email, $phone_no, $gender, $dob, $addrs, $city, $state, $country, $username, $password, $community_role, $acct_number, $ledger_bal, $target_bal, $invst_bal, $loan_bal, $asset_bal, $decoded_picture, $last_with_date, $status, $lofficer, $otp_option, $currency, $wallet_bal, $over_draft, $card_id, $card_reg, $tpin, $regType, $gname, $gposition, $merchantid, $reg_branch, $acct_status, $date_time, $acct_opening_date, $smsChecker, $sendSmsStatus, $sendEmailStatus]);

    }


    public function insertCustomerMo($query, $snum, $fname, $lname, $mname, $email, $phone_no, $gender, $dob, $addrs, $city, $state, $country, $username, $password, $community_role, $acct_number, $ledger_bal, $target_bal, $invst_bal, $loan_bal, $asset_bal, $last_with_date, $status, $lofficer, $otp_option, $currency, $wallet_bal, $over_draft, $card_id, $card_reg, $card_issurer, $tpin, $gname, $merchantid, $reg_branch, $acct_status, $date_time, $acct_opening_date, $smsChecker, $sendSmsStatus, $sendEmailStatus, $virtualNo, $virtualAcctNo, $bankName) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$snum, $fname, $lname, $mname, $email, $phone_no, $gender, $dob, $addrs, $city, $state, $country, $username, $password, $community_role, $acct_number, $ledger_bal, $target_bal, $invst_bal, $loan_bal, $asset_bal, $last_with_date, $status, $lofficer, $otp_option, $currency, $wallet_bal, $over_draft, $card_id, $card_reg, $card_issurer, $tpin, $gname, $merchantid, $reg_branch, $acct_status, $date_time, $acct_opening_date, $smsChecker, $sendSmsStatus, $sendEmailStatus, $virtualNo, $virtualAcctNo, $bankName]);

    }


    public function insertBranch($query, $bprefix, $bname, $bopendate, $bcountry, $currency, $branch_addrs, $branch_city, $branch_province, $branch_zipcode, $branch_landline, $branch_mobile, $branchid, $bstatus, $stamp, $created_by) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$bprefix, $bname, $bopendate, $bcountry, $currency, $branch_addrs, $branch_city, $branch_province, $branch_zipcode, $branch_landline, $branch_mobile, $branchid, $bstatus, $stamp, $created_by]);

    }


    public function insertCustomerPos($query, $fname, $lname, $phone_no, $gender, $merchantid, $date_time) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$fname, $lname, $phone_no, $gender, $merchantid, $date_time]);

    }


    public function insertIncome($query, $companyid, $icm_id, $icm_type, $icm_amt, $icm_date, $icm_desc) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$companyid, $icm_id, $icm_type, $icm_amt, $icm_date, $icm_desc]);

    }


    public function insertWalletHistory($query, $userid, $refid, $recipient, $creditAmt, $debitAmt, $transType, $currency, $paymenttype, $details, $status, $date_time, $reg_staffid, $walbalance) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$userid, $refid, $recipient, $creditAmt, $debitAmt, $transType, $currency, $paymenttype, $details, $status, $date_time, $reg_staffid, $walbalance]);

    }


    public function insertSpecialWalletHistory($query, $userid, $refid, $recipient, $creditAmt, $debitAmt, $transType, $currency, $paymenttype, $details, $status, $date_time, $reg_staffid, $walbalance, $receiverBal) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$userid, $refid, $recipient, $creditAmt, $debitAmt, $transType, $currency, $paymenttype, $details, $status, $date_time, $reg_staffid, $walbalance, $receiverBal]);

    }


    public function insertFundAllocationHistory($query, $refid, $userid, $reg_staffid, $branch, $recipient, $initiator, $amtPaid, $transType, $paymenttype, $currency, $walbalance, $details, $status, $date_time) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$refid, $userid, $reg_staffid, $branch, $recipient, $initiator, $amtPaid, $transType, $paymenttype, $currency, $walbalance, $details, $status, $date_time]);

    }


    public function insertPendingTransaction($query, $reg_staffid, $refid, $mydata, $status, $date_time) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$reg_staffid, $refid, $mydata, $status, $date_time]);

    }


    public function insertActivationCode($query2, $url, $shorturl, $status, $account) {

        $stmt = $this->pdo->prepare($query2);

        $stmt->execute([$url, $shorturl, $status, $account]);

    }


    public function insertSavings($query, $txid, $t_type, $p_type, $acctno, $transfer_to, $fn, $ln, $email, $phone, $amount, $posted_by, $remark, $date_time, $merchantid, $reg_branch, $currency, $aggr_id, $total_ledgerBal, $status, $notification, $notification2, $checksms, $balanceToImpact) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$txid, $t_type, $p_type, $acctno, $transfer_to, $fn, $ln, $email, $phone, $amount, $posted_by, $remark, $date_time, $merchantid, $reg_branch, $currency, $aggr_id, $total_ledgerBal, $status, $notification, $notification2, $checksms, $balanceToImpact]);

    }


    public function insertWithdrawalRequest($query, $refid, $bbranchid, $bAcctOfficer, $acctno, $p_type, $amount, $remark, $status, $source, $currency, $email, $phone, $bsbranchid, $sendSMS, $sendEmail, $date_time) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$refid, $bbranchid, $bAcctOfficer, $acctno, $p_type, $amount, $remark, $status, $source, $currency, $email, $phone, $bsbranchid, $sendSMS, $sendEmail, $date_time]);

    }


    public function insertTerminalRpt($query, $merchantid, $refid, $terminalid, $rrn, $channel, $stan, $merchantName, $MaskedPAN, $custName, $transType, $amount, $charges, $currency, $pendingBal, $transferBal, $initiatedBy, $status, $customerPhone, $customerEmail, $dateTime, $branchid) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$merchantid, $refid, $terminalid, $rrn, $channel, $stan, $merchantName, $MaskedPAN, $custName, $transType, $amount, $charges, $currency, $pendingBal, $transferBal, $initiatedBy, $status, $customerPhone, $customerEmail, $dateTime, $branchid]);

    }


    public function insertTillFundingHistory($query, $txid, $merchantid, $reg_staffid1, $reg_branch, $fullname, $reg_staffid2, $amt, $ttype, $t_type, $currency, $ledger_bal, $remark, $mystatus, $date_time) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$txid, $merchantid, $reg_staffid1, $reg_branch, $fullname, $reg_staffid2, $amt, $ttype, $t_type, $currency, $ledger_bal, $remark, $mystatus, $date_time]);

    }



    public function insertVA($query, $account_ref, $userid, $account_name, $account_number, $bank_name, $status, $date_time, $gateway_name, $bbranchid, $regType, $acctStatus, $bAcctOfficer, $transferLimitPerDay, $transferLimitPerTrans, $airtimeDataLimitPerDay, $airtimeDataLimitPerTrans) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$account_ref, $userid, $account_name, $account_number, $bank_name, $status, $date_time, $gateway_name, $bbranchid, $regType, $acctStatus, $bAcctOfficer, $transferLimitPerDay, $transferLimitPerTrans, $airtimeDataLimitPerDay, $airtimeDataLimitPerTrans]);

    }


    public function insertTargetSavings($query, $acn, $plan_id, $plan_code, $sname, $purpose, $amount, $currency, $interval, $duration, $interestType, $interestRate, $txid, $savingStatus, $date_time, $maturity_date, $bsbranchid, $bbranchid, $bAcctOfficer, $iupfront_payment) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$acn, $plan_id, $plan_code, $sname, $purpose, $amount, $currency, $interval, $duration, $interestType, $interestRate, $txid, $savingStatus, $date_time, $maturity_date, $bsbranchid, $bbranchid, $bAcctOfficer, $iupfront_payment]);

    }


    public function insertSavingsSubscription($query, $bbranchid, $purpose, $plan_code, $new_plancode, $plan_id, $origin_planid, $amount, $currency, $interval, $duration, $sub_code, $acn, $savingStatus, $date_time, $bsbranchid, $maturity_date, $amt_plusInterest, $txid, $next_pmt_date, $sub_type, $rec_type, $sub_balance, $wcount, $wtime, $bAcctOfficer, $wstatus, $stop_sub, $balanceToImpact) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$bbranchid, $purpose, $plan_code, $new_plancode, $plan_id, $origin_planid, $amount, $currency, $interval, $duration, $sub_code, $acn, $savingStatus, $date_time, $bsbranchid, $maturity_date, $amt_plusInterest, $txid, $next_pmt_date, $sub_type, $rec_type, $sub_balance, $wcount, $wtime, $bAcctOfficer, $wstatus, $stop_sub, $balanceToImpact]);

    }


    public function insertInvestmentSavings($query, $bbranchid, $acn, $txid, $sub_code, $refid, $currency, $amount, $status, $date_time, $fname, $lname, $authCode, $cardF6digit, $cardL4digit, $cardType, $bankName, $plan_code, $vendorid, $agentid) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$bbranchid, $acn, $txid, $sub_code, $refid, $currency, $amount, $status, $date_time, $fname, $lname, $authCode, $cardF6digit, $cardL4digit, $cardType, $bankName, $plan_code, $vendorid, $agentid]);

    }


    public function updateAgent($query, $agenttype, $name, $gender, $bname, $address, $email, $phone_no, $username, $password, $date_time, $id) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$agenttype, $name, $gender, $bname, $address, $email, $phone_no, $username, $password, $date_time, $id]);

    }



    public function updateCustomer($query, $fname, $lname, $email, $phone, $dob, $addrs, $city, $state, $country, $username, $password, $id) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$fname, $lname, $email, $phone, $dob, $addrs, $city, $state, $country, $username, $password, $id]);

    }


    public function updateBranch($query, $bname, $bcountry, $currency, $branch_addrs, $branch_city, $branch_province, $branch_mobile, $bstatus, $branchid) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$bname, $bcountry, $currency, $branch_addrs, $branch_city, $branch_province, $branch_mobile, $bstatus, $branchid]);

    }


    public function updateWallet($query, $wallet_bal) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$wallet_bal]);

    }

    public function updateCustomerStatus($query, $status, $acctStatus) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$status,$acctStatus]);

    }

    public function updateSavingsSub($query, $subCode, $status, $matureDate, $totalOutput, $nextPmtDate, $refid) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$subCode,$status,$matureDate,$totalOutput,$nextPmtDate,$refid]);

    }

    public function updateMySavingsSub($query, $sub_balance, $next_pmt_date) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$sub_balance, $next_pmt_date]);

    }

    public function updateTargetSavings($query, $txid, $status, $matureDate) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$txid,$status,$matureDate]);

    }

    public function updateLedgerBal($query, $balance) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$balance]);

    }

    public function updateUserBal($query, $balance) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$balance]);

    }


    public function updateWaitingTxt($query, $status) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$status]);

    }


    public function updateAggr($query, $balance) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$balance]);

    }


    public function updateTill($query, $balance, $comm_bal, $unsettledBal) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$balance, $comm_bal, $unsettledBal]);

    }


    public function updateTerminal($query, $pendingBal, $settledBal, $tcount) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$pendingBal, $settledBal, $tcountl]);

    }


    public function deleteById($query, $id) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$id]);

    }


    public function startsWith($string, $startString) 
    { 
        $len = strlen($startString); 
        return (substr($string, 0, $len) === $startString); 
    }


    public function myreference($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
    
    public function ccMasking($number, $maskingCharacter = '*') 
    {
        return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);
    }

}
?>