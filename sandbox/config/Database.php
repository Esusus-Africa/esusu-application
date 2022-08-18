<?php
class Database {
    //Db Parameters
    private $hostName = 'localhost';
    private $dbname = 'esusulive_sandbox';
    private $username = 'esusulive_sandbox';
    private $password = 'h^=2}AHT[9yx';
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
    
    
    public function fetchStaff($parameter,$parameter1) {

        $query = "SELECT * FROM user WHERE id = '$parameter' AND created_by = '$parameter1'";

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


    public function insertCustomerPc($query, $snum, $fname, $lname, $email, $phone_no, $gender, $dob, $addrs, $city, $state, $country, $username, $password, $community_role, $acct_number, $ledger_bal, $target_bal, $invst_bal, $loan_bal, $asset_bal, $last_with_date, $status, $otp_option, $currency, $wallet_bal, $over_draft, $card_id, $card_reg, $tpin, $gname, $merchantid, $reg_branch, $acct_status, $date_time, $acct_opening_date, $smsChecker) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$snum, $fname, $lname, $email, $phone_no, $gender, $dob, $addrs, $city, $state, $country, $username, $password, $community_role, $acct_number, $ledger_bal, $target_bal, $invst_bal, $loan_bal, $asset_bal, $last_with_date, $status, $otp_option, $currency, $wallet_bal, $over_draft, $card_id, $card_reg, $tpin, $gname, $merchantid, $reg_branch, $acct_status, $date_time, $acct_opening_date, $smsChecker]);

    }


    public function insertBranch($query, $bprefix, $bname, $bopendate, $bcountry, $currency, $branch_addrs, $branch_city, $branch_province, $branch_zipcode, $branch_landline, $branch_mobile, $branchid, $bstatus, $stamp, $created_by) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$bprefix, $bname, $bopendate, $bcountry, $currency, $branch_addrs, $branch_city, $branch_province, $branch_zipcode, $branch_landline, $branch_mobile, $branchid, $bstatus, $stamp, $created_by]);

    }


    public function insertCustomerPos($query, $fname, $lname, $phone_no, $gender, $merchantid, $date_time) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$fname, $lname, $phone_no, $gender, $merchantid, $date_time]);

    }


    public function insertWalletHistory($query, $userid, $refid, $recipient, $creditAmt, $debitAmt, $transType, $currency, $paymenttype, $details, $status, $date_time, $reg_staffid, $walbalance) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$userid, $refid, $recipient, $creditAmt, $debitAmt, $transType, $currency, $paymenttype, $details, $status, $date_time, $reg_staffid, $walbalance]);

    }


    public function insertSpecialWalletHistory($query, $userid, $refid, $recipient, $creditAmt, $debitAmt, $transType, $currency, $paymenttype, $details, $status, $date_time, $reg_staffid, $walbalance, $receiverBal) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$userid, $refid, $recipient, $creditAmt, $debitAmt, $transType, $currency, $paymenttype, $details, $status, $date_time, $reg_staffid, $walbalance, $receiverBal]);

    }


    public function insertPendingTransaction($query, $reg_staffid, $refid, $mydata, $status, $date_time) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$reg_staffid, $refid, $mydata, $status, $date_time]);

    }


    public function insertActivationCode($query2, $url, $shorturl, $status, $account) {

        $stmt = $this->pdo->prepare($query2);

        $stmt->execute([$url, $shorturl, $status, $account]);

    }


    public function insertSavings($query, $txid, $t_type, $p_type, $acctno, $transfer_to, $fn, $ln, $email, $phone, $amount, $posted_by, $remark, $date_time, $merchantid, $reg_branch, $currency, $aggr_id, $total_ledgerBal, $status, $notification, $notification2, $checksms) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$txid, $t_type, $p_type, $acctno, $transfer_to, $fn, $ln, $email, $phone, $amount, $posted_by, $remark, $date_time, $merchantid, $reg_branch, $currency, $aggr_id, $total_ledgerBal, $status, $notification, $notification2, $checksms]);

    }


    public function insertTillFundingHistory($query, $txid, $merchantid, $reg_staffid1, $reg_branch, $fullname, $reg_staffid2, $amt, $ttype, $t_type, $currency, $ledger_bal, $remark, $mystatus, $date_time) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$txid, $merchantid, $reg_staffid1, $reg_branch, $fullname, $reg_staffid2, $amt, $ttype, $t_type, $currency, $ledger_bal, $remark, $mystatus, $date_time]);

    }


    public function insertVA($query, $account_ref, $userid, $account_name, $account_number, $bank_name, $status, $date_time, $gateway_name) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$account_ref, $userid, $account_name, $account_number, $bank_name, $status, $date_time, $gateway_name]);

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


    public function updateTill($query, $balance, $comm_bal) {

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$balance, $comm_bal]);

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