<?php

require_once 'class.EnvCoder.php';

use DotEnvironment\DotEnv as MyDotEnv;

(new MyDotEnv(__DIR__ . '/.env'))->load();

class Database {
    
    //Db Parameters
    private $appDev;
    private $dbDNS;
    private $username;
    private $password;
    protected $pdo;

    //Start Connection using constructor
    public function __construct(){

        $this->pdo = null;
        $this->appDev = getenv('APP_ENV');
        $this->dbDNS = getenv('DATABASE_DNS');
        $this->username = getenv('DATABASE_USER');
        $this->password = getenv('DATABASE_PASSWORD');
        
        try{

            $this->pdo = new PDO($this->dbDNS, $this->username, $this->password);

            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch(PDOException $e){

            echo "Error : " . $e->getMessage;

        }

    }

    //Function to fetch all sql data
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

    //Function to Fetch Global System Setting
    public function fetchSystemSet() {

        $query = "SELECT * FROM systemset";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetch();

    }

    //Function for email configuration
    public function fetchEmailConfig($parameter) {

        $query = "SELECT * FROM email_config WHERE companyid = '$parameter'";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$parameter]);

        return $stmt->fetch();

    }

    //Function to startwith
    public function startsWith($string, $startString) 
    { 

        $len = strlen($startString); 
        return (substr($string, 0, $len) === $startString);

    }

    //Function to sanitize input string
    public function sanitizeInput($string){

        $filteredString = filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        return $filteredString;

    }

    //Global function to call any api method: POST, GET and PUT
    public function callAPI($method, $url, $data, $header, $hoststatus){

        $curl = curl_init();
     
        switch ($method){

           case "POST":
              curl_setopt($curl, CURLOPT_POST, 1);
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
              break;
           case "PUT":
              curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
              break;
           default:
              if ($data)
                 $url = sprintf("%s?%s", $url, http_build_query($data));
                 
        }
     
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $hoststatus);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $hoststatus);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      
        // EXECUTE:
        $result = curl_exec($curl);

        if(curl_error($curl)){

            return 'error:' . curl_error($curl);

        }

        curl_close($curl);

        return $result;

    }

    //Function to generate reference number
    public function myreference($limit)
    {

        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);

    }
    public function random_strings($length_of_string) 
    { 
        // String of all alphanumeric character 
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
    
        // Shufle the $str_result and returns substring 
        // of specified length 
        return substr(str_shuffle($str_result), 0, $length_of_string); 
    }

    //Function to mask account number
    public function ccMasking($number, $maskingCharacter = '*') 
    {

        return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);

    }

    //Function to mask card pan number
    public function panNumberMasking($number, $maskingCharacter = '*'){

        return substr($number, 0, 6) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -4);

    }

    //Function to detect mobile device
    public function isMobileDevice(){

        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    
    }

    public function convertDateTime($DateTime){
    
        $userTimezone = new \DateTimeZone('GMT');
        $gmtTimezone = new \DateTimeZone(date_default_timezone_get());
        $myDateTime = new \DateTime(date('Y-m-d G:i:s', strtotime($DateTime)), $gmtTimezone);
        $offset = $userTimezone->getOffset($myDateTime);
        $myInterval = DateInterval::createFromDateString((string)$offset . 'seconds');
        $myDateTime->add($myInterval);
        $result = $myDateTime->format('Y-m-d h:i A');

        return $result;

    }

    public function reformatDate($date, $from_format = 'd/m/Y', $to_format = 'Y-m-d') {

        $date_aux = date_create_from_format($from_format, $date);
        return date_format($date_aux,$to_format);

    }

    //Function to fetch member settings and maintance history configuring
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

    //Function to get browser information
    public function getBrowser() {

        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";
      
        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {

          $platform = 'linux';

        }elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {

          $platform = 'mac';

        }elseif (preg_match('/windows|win32/i', $u_agent)) {

          $platform = 'windows';

        }
      
        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){

          $bname = 'Internet Explorer';
          $ub = "MSIE";

        }elseif(preg_match('/Firefox/i',$u_agent)){

          $bname = 'Mozilla Firefox';
          $ub = "Firefox";

        }elseif(preg_match('/OPR/i',$u_agent)){

          $bname = 'Opera';
          $ub = "Opera";

        }elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){

          $bname = 'Google Chrome';
          $ub = "Chrome";

        }elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){

          $bname = 'Apple Safari';
          $ub = "Safari";

        }elseif(preg_match('/Netscape/i',$u_agent)){

          $bname = 'Netscape';
          $ub = "Netscape";

        }elseif(preg_match('/Edge/i',$u_agent)){

          $bname = 'Edge';
          $ub = "Edge";

        }elseif(preg_match('/Trident/i',$u_agent)){

          $bname = 'Internet Explorer';
          $ub = "MSIE";

        }
      
        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

        if (!preg_match_all($pattern, $u_agent, $matches)) {

          // we have no matching number just continue

        }

        // see how many we have
        $i = count($matches['browser']);

        if($i != 1) {

          //we will have two since we are not using 'other' argument yet
          //see if version is before or after the name
          if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){

              $version= $matches['version'][0];

          }else {

              $version= $matches['version'][1];

          }

        }else{

          $version= $matches['version'][0];

        }
      
        // check if we have a number
        if ($version==null || $version=="") {$version="?";}
      
        return array(
          'userAgent' => $u_agent,
          'name'      => $bname,
          'version'   => $version,
          'platform'  => $platform,
          'pattern'    => $pattern
        );

    }
    
    //Function to get user ip address
    public function getUserIP() {

        $ipaddress = '';

        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;

    }

    //Functions to check internet connectivity
    public function internetConnectionStatus($domain){

        //Checking internet connection status with predefined function
        $file = @fsockopen($domain, 80, $errno, $errstr);//@fsockopen is used to connect to a socket
        return ($file);

    }

    /**
     * This section spell out some global functions
     * that handles PDO INSERTION sql query,
     * such as :- 
     * @insertSavings {Deposit, Withdrawal, Withdrawal_Charges},
     * @insertWithdrawalRequest
     * @insertTillFundingHistory
     * @insertWalletHistory
     * @insertSpecialWalletHistory
     * @insertIncome
     * @insertCustomerPc
     * @insertActivationCode
     * @insertBranch
     * @insertApiTransactionLog
     * @insertSessionTracker
     * @insertOTPConfirmation
     * @insertAuditTrail
     * @insertRole
     * @insertPermission
     * @insertAttachment
     * @insertPaySchedule
     * @insertLoanRepayment
     */
    public function insertSavings($query, $txid, $t_type, $p_type, $acctno, $transfer_to, $fn, $ln, $email, $phone, $amount, $posted_by, $remark, $date_time, $merchantid, $reg_branch, $currency, $aggr_id, $total_ledgerBal, $status, $notification, $notification2, $checksms, $balanceToImpact) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$txid, $t_type, $p_type, $acctno, $transfer_to, $fn, $ln, $email, $phone, $amount, $posted_by, $remark, $date_time, $merchantid, $reg_branch, $currency, $aggr_id, $total_ledgerBal, $status, $notification, $notification2, $checksms, $balanceToImpact]);

    }

    public function insertWithdrawalRequest($query, $refid, $bbranchid, $bAcctOfficer, $acctno, $p_type, $amount, $remark, $status, $source, $currency, $email, $phone, $bsbranchid, $sendSMS, $sendEmail, $date_time) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$refid, $bbranchid, $bAcctOfficer, $acctno, $p_type, $amount, $remark, $status, $source, $currency, $email, $phone, $bsbranchid, $sendSMS, $sendEmail, $date_time]);

    }

    public function insertTillFundingHistory($query, $txid, $merchantid, $reg_staffid1, $reg_branch, $fullname, $reg_staffid2, $amt, $ttype, $t_type, $currency, $ledger_bal, $remark, $mystatus, $date_time) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$txid, $merchantid, $reg_staffid1, $reg_branch, $fullname, $reg_staffid2, $amt, $ttype, $t_type, $currency, $ledger_bal, $remark, $mystatus, $date_time]);

    }

    public function insertWalletHistory($query, $userid, $refid, $recipient, $creditAmt, $debitAmt, $transType, $currency, $paymenttype, $details, $status, $date_time, $reg_staffid, $walbalance) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userid, $refid, $recipient, $creditAmt, $debitAmt, $transType, $currency, $paymenttype, $details, $status, $date_time, $reg_staffid, $walbalance]);

    }

    public function insertSpecialWalletHistory($query, $userid, $refid, $recipient, $creditAmt, $debitAmt, $transType, $currency, $paymenttype, $details, $status, $date_time, $reg_staffid, $walbalance, $receiverBal) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userid, $refid, $recipient, $creditAmt, $debitAmt, $transType, $currency, $paymenttype, $details, $status, $date_time, $reg_staffid, $walbalance, $receiverBal]);

    }

    public function insertIncome($query, $companyid, $icm_id, $icm_type, $icm_amt, $icm_date, $icm_desc) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$companyid, $icm_id, $icm_type, $icm_amt, $icm_date, $icm_desc]);

    }

    public function insertCustomerPc($query, $snum, $fname, $lname, $mname, $email, $phone_no, $gender, $dob, $occupation, $addrs, $city, $state, $country, $nok, $nok_rela, $nok_phone, $community_role, $account, $username, $password, $balance, $target_savings_bal, $investment_bal, $loan_balance, $asset_acquisition_bal, $image, $date_time, $last_withdraw_date, $status, $lofficer, $c_sign, $branchid, $sbranchid, $acct_status, $s_contribution_interval, $savings_amount, $charge_interval, $chargesAmount, $disbursement_interval, $disbursement_channel, $auto_disbursement_status, $auto_charge_status, $next_charge_date, $next_disbursement_date, $recipient_id, $otp_option, $currency, $wallet_balance, $overdraft, $card_id, $card_reg, $card_issurer, $tpin, $reg_type, $gname, $gposition, $acct_type, $expected_fixed_balance, $acct_opening_date, $unumber, $verve_expiry_date, $employer, $dedicated_ussd_prefix, $evn, $sms_checker, $ws_interval, $ave_savings_amt, $ws_duration, $ws_frequency, $mmaidenName, $moi, $lga, $otherInfo, $nok_addrs, $name_of_trustee, $sendSmsStatus, $sendEmailStatus) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$snum, $fname, $lname, $mname, $email, $phone_no, $gender, $dob, $occupation, $addrs, $city, $state, $country, $nok, $nok_rela, $nok_phone, $community_role, $account, $username, $password, $balance, $target_savings_bal, $investment_bal, $loan_balance, $asset_acquisition_bal, $image, $date_time, $last_withdraw_date, $status, $lofficer, $c_sign, $branchid, $sbranchid, $acct_status, $s_contribution_interval, $savings_amount, $charge_interval, $chargesAmount, $disbursement_interval, $disbursement_channel, $auto_disbursement_status, $auto_charge_status, $next_charge_date, $next_disbursement_date, $recipient_id, $otp_option, $currency, $wallet_balance, $overdraft, $card_id, $card_reg, $card_issurer, $tpin, $reg_type, $gname, $gposition, $acct_type, $expected_fixed_balance, $acct_opening_date, $unumber, $verve_expiry_date, $employer, $dedicated_ussd_prefix, $evn, $sms_checker, $ws_interval, $ave_savings_amt, $ws_duration, $ws_frequency, $mmaidenName, $moi, $lga, $otherInfo, $nok_addrs, $name_of_trustee, $sendSmsStatus, $sendEmailStatus]);

    }

    public function insertActivationCode($query2, $url, $shorturl, $status, $account) {

        $stmt = $this->pdo->prepare($query2);
        $stmt->execute([$url, $shorturl, $status, $account]);

    }

    public function insertBranch($query, $bprefix, $bname, $bopendate, $bcountry, $currency, $branch_addrs, $branch_city, $branch_province, $branch_zipcode, $branch_landline, $branch_mobile, $branchid, $bstatus, $stamp, $created_by) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$bprefix, $bname, $bopendate, $bcountry, $currency, $branch_addrs, $branch_city, $branch_province, $branch_zipcode, $branch_landline, $branch_mobile, $branchid, $bstatus, $stamp, $created_by]);

    }

    public function insertApiTransactionLog($query, $reg_staffid, $refid, $mydata, $status, $date_time) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$reg_staffid, $refid, $mydata, $status, $date_time]);

    }

    public function insertSessionTracker($query, $companyid, $userid, $username, $ipAddress, $latitude, $longitude, $browserSession, $mybrowser, $loginStatus, $FirstVisitDateTime, $LastVisitDateTime) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$companyid, $userid, $username, $ipAddress, $latitude, $longitude, $browserSession, $mybrowser, $loginStatus, $FirstVisitDateTime, $LastVisitDateTime]);

    }

    public function insertOTPConfirmation($query, $userid, $otp_code, $data, $status, $datetime) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$userid, $otp_code, $data, $status, $datetime]);

    }

    public function insertAuditTrail($query, $companyid, $company_cat, $username, $ip_addrs, $browser_details, $activities_tracked, $branchid, $status, $date_time) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$companyid, $company_cat, $username, $ip_addrs, $browser_details, $activities_tracked, $branchid, $status, $date_time]);

    }

    public function insertRole($query, $companyid, $role_name) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$companyid, $role_name]);

    }

    public function insertPermission($query, $companyid, $urole) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$companyid, $urole]);

    }

    public function insertAttachment($query, $get_id, $borrowerid, $tid, $attached_file, $file_title, $fstatus, $date_time) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$get_id, $borrowerid, $tid, $attached_file, $file_title, $fstatus, $date_time]);

    }

    public function insertBankBeneficiary($query, $companyid, $recipient_id, $full_name, $acct_no, $bank_code, $bank_name, $date_time, $staffid, $sbranchid) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$companyid, $recipient_id, $full_name, $acct_no, $bank_code, $bank_name, $date_time, $staffid, $sbranchid]);

    }

    public function insertPaySchedule($query, $lid, $loanid, $account_no, $pid, $pay_date, $final_bal, $amount_to_pay, $status, $companyid, $vendorid, $sbranchid, $lofficer, $direct_debit_status, $requestid, $dueStatus) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$lid, $loanid, $account_no, $pid, $pay_date, $final_bal, $amount_to_pay, $status, $companyid, $vendorid, $sbranchid, $lofficer, $direct_debit_status, $requestid, $dueStatus]);

    }

    public function insertLoanRepayment($query, $tid, $lid, $refid, $account_no, $customer, $loan_bal, $pay_date, $amount_to_pay, $remarks, $branchid, $vendorid, $sbranchid, $sendSms, $sendEmail, $smsChecker) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$tid, $lid, $refid, $account_no, $customer, $loan_bal, $pay_date, $amount_to_pay, $remarks, $branchid, $vendorid, $sbranchid, $sendSms, $sendEmail, $smsChecker]);

    }


    /**
     * This section spell out some global functions
     * that handles PDO UPDATE sql query,
     * such as :- 
     * @updateOneParam
     * @updateTwoParam
     * @updateThreeParam
     */
    public function updateOneParam($query, $param1) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$param1]);

    }

    public function updateTwoParam($query, $param1, $param2) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$param1, $param2]);

    }

    public function updateThreeParam($query, $param1, $param2, $param3) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$param1, $param2, $param3]);

    }


    /**
     * This section spell out some global functions
     * that handles PDO SELECT sql query,
     * such as :- 
     * @fetchByOne
     * @fetchByTwo
     * fetchByTwoAll
     * @fetchByThree
     * @fetchByFour
     * @fetchByFive
     * @fetchBySeven
     * 
     */
    public function fetchByOne($query, $param1) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$param1]);
        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return $stmt->fetch();
        }

    }

    public function fetchByTwo($query, $param1, $param2) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$param1, $param2]);
        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return $stmt->fetch();
        }

    }
    public function fetchByTwoAll($query, $param1, $param2) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$param1, $param2]);
        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return $stmt->fetchAll();
        }

    }

    public function fetchByThree($query, $param1, $param2, $param3) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$param1, $param2, $param3]);
        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return $stmt->fetch();
        }

    }
    public function fetchByThreeAll($query, $param1, $param2, $param3) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$param1, $param2, $param3]);
        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return $stmt->fetchAll();
        }

    }

    public function fetchByFour($query, $param1, $param2, $param3, $param4) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$param1, $param2, $param3, $param4]);
        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return $stmt->fetch();
        }

    }

    public function fetchByFive($query, $param1, $param2, $param3, $param4, $param5) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$param1, $param2, $param3, $param4, $param5]);
        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return $stmt->fetch();
        }

    }

    public function fetchBySeven($query, $param1, $param2, $param3, $param4, $param5, $param6, $param7) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$param1, $param2, $param3, $param4, $param5, $param6, $param7]);
        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return $stmt->fetch();
        }

    }

    public function fetchByEight($query, $param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8) {

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8]);
        $rowCount = $stmt->rowCount();

        if($rowCount <= 0) {
            return 0;
        }
        else{
            return $stmt->fetch();
        }

    }



}

?>