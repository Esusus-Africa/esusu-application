<?php

class User {

    protected $db;

    protected $myitoday_record;
    
    public function __construct(Database $db) {

        $this->db = $db;

    }

    //Account Number Masking
    public function ccMasking($number, $maskingCharacter = '*') {
        return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);
    }
        
    //FETCH Access Key
    public function accessKey($parameter, $parameter1) {

        $query = "SELECT * FROM institution_data WHERE api_key = '$parameter' AND institution_id = '$parameter1'";

        return $this->db->fetchById($query, $parameter);

    }
    
    
    //FETCH Activation Key
    public function checkActivationKey($parameter) {

        $query = "SELECT * FROM institution_data WHERE activationKey = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH Terminal
    public function checkTerminal($parameter) {

        $query = "SELECT * FROM terminal_reg WHERE terminal_id = '$parameter' AND terminal_status = 'Assigned'";

        return $this->db->fetchById($query, $parameter);

    }

    public function checkTerminalwithSerialNo($parameter, $parameter1) {

        $query = "SELECT * FROM terminal_reg WHERE terminal_id = '$parameter' AND terminal_serial = '$parameter1' AND terminal_status = 'Assigned'";

        return $this->db->fetchById($query, $parameter);

    }

    public function checkTerminalSerial($parameter) {

        $query = "SELECT * FROM terminal_reg WHERE terminal_serial = '$parameter' AND terminal_status = 'Assigned'";

        return $this->db->fetchById($query, $parameter);

    }

    public function checkTerminalDuplicateRpt($parameter) {

        $query = "SELECT * FROM terminal_report WHERE refid = '$parameter' AND status = 'Approved'";

        return $this->db->fetchById($query, $parameter);

    }


    public function checkTerminalRptWithRRN($parameter) {

        $query = "SELECT * FROM terminal_report WHERE retrievalRef = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }
    
    
    public function checkWithdrawalRequestDuplicacy($parameter) {

        $query = "SELECT * FROM ledger_withdrawal_request WHERE txid = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH Terminal Operator
    public function fetchTerminalOperator($parameter,$parameter1) {

        $query = "SELECT * FROM user WHERE id = '$parameter' AND pospin = '$parameter1'";

        return $this->db->fetchById($query, $parameter);

    }
    

    public function fetchTerminalOprt($parameter,$parameter1) {

        $query = "SELECT * FROM user WHERE id = '$parameter' AND created_by = '$parameter1'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH user
    public function fetchUser($parameter) {

        $query = "SELECT * FROM user WHERE id = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }

    public function fetchUser2($parameter,$parameter1,$parameter2) {

        $query = "SELECT * FROM user WHERE username = '$parameter' AND password = '$parameter1' AND created_by = '$parameter2'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH Transaction Waiting List
    public function fetchTxtWaitingList($parameter,$parameter1,$parameter2) {

        $query = "SELECT * FROM api_txtwaitinglist WHERE userid = '$parameter' AND refid = '$parameter1' AND status = '$parameter2'";

        return $this->db->fetchById($query, $parameter);

    }

    //FETCH Terminal
    public function fetchApi($parameter) {

        $query = "SELECT * FROM restful_apisetup WHERE api_name = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH Sys Maintenance
    public function fetchMaintenanceSettings($parameter) {

        $query = "SELECT * FROM maintenance_history WHERE company_id = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH CUSTOMER VIRTUAL ACCOUNT BY account_number OR userid
    public function fetchVAByAcctNo($parameter) {

        $query = "SELECT * FROM virtual_account WHERE status = 'ACTIVE' AND (userid = '$parameter' OR account_number = '$parameter')";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH CUSTOMER POOL ACCOUNT BY account_number
    public function fetchPoolAcctByAcctNo($parameter) {

        $query = "SELECT * FROM pool_account WHERE account_number = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH CUSTOMER TILL Virtual Account BY account_number
    public function fetchTillVAByAcctNo($parameter) {

        $query = "SELECT * FROM till_virtual_account WHERE account_number = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH UNIQUE CUSTOMER/USER VIRTUAL ACCOUNT BY account_number
    public function fetchUniqueVAByAcctNo($parameter,$parameter1) {

        $query = "SELECT * FROM virtual_account WHERE account_number = '$parameter' AND companyid = '$parameter1'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH CUSTOMER NORMAL ACCOUNT BY ledger account_number
    public function fetchCustomerByAcctId($parameter,$parameter1) {

        $query = "SELECT * FROM borrowers WHERE branchid = '$parameter1' AND (account = '$parameter' OR username = '$parameter')";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH CUSTOMER NORMAL ACCOUNT BY ledger account_number
    public function fetchCustomerByAcctIdPasswd($parameter,$parameter1,$parameter2) {

        $query = "SELECT * FROM borrowers WHERE account = '$parameter' AND password = '$parameter1' AND branchid = '$parameter2'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH CUSTOMER NORMAL ACCOUNT BY ledger account_number
    public function fetchCustomerByAcctIdTPin($parameter,$parameter1,$parameter2) {

        $query = "SELECT * FROM borrowers WHERE account = '$parameter' AND tpin = '$parameter1' AND branchid = '$parameter2'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH CUSTOMER NORMAL ACCOUNT BY ledger account_number
    public function fetchCustomerActivationOtp($parameter) {

        $query = "SELECT * FROM activate_member2 WHERE shorturl = '$parameter' AND attempt = 'No'";

        return $this->db->fetchById($query, $parameter);

    }
    public function fetchCustomerWebActivationOtp($parameter) {

        $query = "SELECT * FROM activate_member WHERE shorturl = '$parameter' AND attempt = 'No'";

        return $this->db->fetchById($query, $parameter);

    }

    //FETCH CUSTOMER ACCOUNT WITH USERNAME AND PASSWORD
    public function fetchBorrowers($parameter,$parameter1,$parameter2) {

        $query = "SELECT * FROM borrowers WHERE username = '$parameter' AND password = '$parameter1' AND branchid = '$parameter2'";

        return $this->db->fetchById($query, $parameter);

    }

    public function fetchCustomerByAcctIdOnly($parameter) {

        $query = "SELECT * FROM borrowers WHERE account = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH WALLET HISTORY by refid or id AND userid or recipient
    public function fetchWalletHistoryById($parameter,$parameter1) {

        $query = "SELECT * FROM wallet_history WHERE (id = '$parameter' OR refid = '$parameter') AND (userid = '$parameter1' OR recipient = '$parameter1')";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH WALLET HISTORY by refid alone
    public function fetchWalletHistoryByRefId($parameter) {

        $query = "SELECT * FROM wallet_history WHERE refid = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }

    //FETCH WALLET HISTORY by refid alone
    public function fetchPoolHistoryByRefId($parameter) {

        $query = "SELECT * FROM pool_history WHERE refid = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }

    //FETCH WALLET HISTORY by refid alone
    public function fetchTillWHistoryByRefId($parameter) {

        $query = "SELECT * FROM fund_allocation_history WHERE txid = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }
    
    
    //FETCH TILL SAVINNG customers
    public function fetchSavingsTransaction($parameter,$parameter1) {

        $query = "SELECT * FROM transaction WHERE txid = '$parameter' AND branchid = '$parameter1'";
        
        return $this->db->fetchById($query, $parameter);

    }


    //FETCH WALLET HISTORY by userid
    public function fetchAllWalletHistory($parameter) {

        $query = "SELECT * FROM wallet_history WHERE (userid = '$parameter'OR recipient = '$parameter')";

        return $this->db->fetchById($query, $parameter);

    }


    //AIRTIME/DATA LIMIT PER DAY FOR INSTITUTION/AGENT
    public function fetchAggregateAirtimeDataLimitPerDay($parameter,$parameter1) {

        $query = "SELECT SUM(debit) FROM wallet_history WHERE date_time LIKE '$parameter%' AND initiator = '$parameter1' AND (paymenttype = 'Airtime - WEB' OR paymenttype = 'Databundle - WEB')";

        return $this->db->fetchById($query, $parameter);

    }



    //TRANSFER LIMIT PER DAY FOR INSTITUTION/AGENT
    public function fetchAggregateTransferLimitPerDay($parameter,$parameter1) {

        $query = "SELECT SUM(debit) FROM wallet_history WHERE date_time LIKE '$parameter%' AND initiator = '$parameter1' AND paymenttype = 'BANK_TRANSFER'";

        return $this->db->fetchById($query, $parameter);

    }



    //INSTITUTION STAFF / SUB-AGENT LIMIT CONFIGURATION
    public function fetchVALimitConfiguration($parameter,$parameter1) {

        $query = "SELECT * FROM virtual_account WHERE userid = '$parameter' AND companyid = '$parameter1'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH AGENT BY AGENTID (STRING)
    public function fetchAgentById($parameter) {

        $query = "SELECT * FROM agent_data WHERE agentid = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH TILL ACCOUNT BY STAFF ID (STRING)
    public function fetchTillAcct($parameter) {

        $query = "SELECT * FROM till_account WHERE cashier = '$parameter'";
        
        return $this->db->fetchById($query, $parameter);

    }


    //FETCH AGGREGATOR BY AGGREGATOR ID (STRING)
    public function fetchAggrById($parameter) {

        $query = "SELECT * FROM aggregator WHERE aggr_id = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH INSTITUTION BY INSTITUTION ID (STRING)
    public function fetchInstitutionById($parameter) {

        $query = "SELECT * FROM institution_data WHERE institution_id = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH INSTITUTION BY INSTITUTION ID (STRING)
    public function fetchMerchantById($parameter) {

        $query = "SELECT * FROM merchant_reg WHERE merchantID = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH SMS GATEWAY (STRING)
    public function fetchSMSGW1($parameter) {

        $query = "SELECT * FROM sms WHERE smsuser = '$parameter' AND status = 'Activated'";

        return $this->db->fetchById($query, $parameter);

    }
    public function fetchSMSGW2($parameter) {

        $query = "SELECT * FROM sms WHERE smsuser = '' AND status = '$parameter'";

        return $this->db->fetchById($query, $parameter);

    }


    //UPDATE AGENT WALLET BY AGENTID (STRING)
    public function updateAgentWallet($parameter, $parameter1) {

        $query = "UPDATE agent_data SET wallet_balance = '$parameter' WHERE agentid = '$parameter1'";

        return $this->db->updateWallet($query, $parameter);

    }


    //UPDATE INSTITUTION WALLET BY INSTITUTION ID (STRING)
    public function updateInstitutionWallet($parameter, $parameter1) {

        $query = "UPDATE institution_data SET wallet_balance = '$parameter' WHERE institution_id = '$parameter1'";

        return $this->db->updateWallet($query, $parameter);

    }


    //UPDATE CUSTOMER ACTIVATION LOG
    public function updateCustomerActivationLog($parameter, $parameter1, $parameter2) {

        $query = "UPDATE activate_member2 SET attempt = '$parameter2' WHERE shorturl = '$parameter' AND acn = '$parameter1'";

        return $this->db->updateWallet($query, $parameter2);

    }
    public function updateCustomerWebActivationLog($parameter, $parameter1, $parameter2) {

        $query = "UPDATE activate_member SET attempt = '$parameter2' WHERE shorturl = '$parameter' AND acn = '$parameter1'";

        return $this->db->updateWallet($query, $parameter2);

    }


    //UPDATE CUSTOMER SUB REFERENCE NO BY PLAN ID (STRING)
    public function updateSavingsSubRef($parameter, $parameter1) {

        $query = "UPDATE savings_subscription SET reference_no = '$parameter' WHERE plan_id = '$parameter1'";

        return $this->db->updateWallet($query, $parameter);

    }

    //UPDATE CUSTOMER SAVINGS SUB RECORD BY PLAN ID (STRING)
    public function updateMySavingsSub($parameter, $parameter1, $parameter2, $parameter3) {

        $query = "UPDATE savings_subscription SET sub_balance = '$parameter', next_pmt_date = '$parameter1' WHERE subscription_code = '$parameter2' AND companyid = '$parameter3'";

        return $this->db->updateMySavingsSub($query, $parameter, $parameter1);

    }

    //UPDATE CUSTOMER SAVINGS SUB RECORD BY PLAN ID (STRING)
    public function updateSavingsSubRecords($parameter, $parameter1, $parameter2, $parameter3, $parameter4, $parameter5, $parameter6) {

        $query = "UPDATE savings_subscription SET subscription_code = '$parameter', status = '$parameter1', mature_date = '$parameter2', amt_plusInterest = '$parameter3', next_pmt_date = '$parameter4', reference_no = '$parameter5' WHERE plan_id = '$parameter6'";

        return $this->db->updateSavingsSub($query, $parameter, $parameter1, $parameter2, $parameter3, $parameter4, $parameter5);

    }

    //UPDATE CUSTOMER TARGET SAVINGS RECORD BY PLAN ID (STRING)
    public function updateTargetSavingsRecords($parameter, $parameter1, $parameter2, $parameter3) {

        $query = "UPDATE target_savings SET txid = '$parameter', status = '$parameter1', mature_date = '$parameter2' WHERE plan_id = '$parameter3'";

        return $this->db->updateTargetSavings($query, $parameter, $parameter1, $parameter2);

    }

    //UPDATE MERCHANT WALLET BY MERCHANT ID (STRING)
    public function updateMerchantWallet($parameter, $parameter1) {

        $query = "UPDATE merchant_reg SET wallet_balance = '$parameter' WHERE merchantID = '$parameter1'";

        return $this->db->updateWallet($query, $parameter);

    }


    //UPDATE BORROWERS WALLET BY ACCOUNTID (STRING)
    public function updateBorrowerWallet($parameter, $parameter1, $parameter2) {

        $query = "UPDATE borrowers SET wallet_balance = '$parameter' WHERE account = '$parameter1' AND branchid = '$parameter2'";

        return $this->db->updateWallet($query, $parameter);

    }


    //UPDATE BORROWERS INVESTMENT BALANCE BY ACCOUNTID (STRING)
    public function updateBorrowerInvestmentBal($parameter, $parameter1, $parameter2) {

        $query = "UPDATE borrowers SET investment_bal = '$parameter' WHERE account = '$parameter1' AND branchid = '$parameter2'";

        return $this->db->updateWallet($query, $parameter);

    }


    //UPDATE BORROWERS TARGET BALANCE BY ACCOUNTID (STRING)
    public function updateBorrowerTargetBal($parameter, $parameter1, $parameter2) {

        $query = "UPDATE borrowers SET target_savings_bal = '$parameter' WHERE account = '$parameter1' AND branchid = '$parameter2'";

        return $this->db->updateWallet($query, $parameter);

    }


    //UPDATE USER WALLET BY ID (STRING)
    public function updateUserWallet($parameter, $parameter1, $parameter2) {

        $query = "UPDATE user SET transfer_balance = '$parameter' WHERE id = '$parameter1' AND created_by = '$parameter2'";

        return $this->db->updateWallet($query, $parameter);

    }

    //UPDATE POOL ACCOUNT
    public function updatePoolAccount($parameter, $parameter1) {

        $query = "UPDATE pool_account SET availableBal = '$parameter' WHERE account_number = '$parameter1'";

        return $this->db->updateWallet($query, $parameter);

    }


    //UPDATE TERMINAL REPORT
    public function updateTerminalRpt($parameter, $parameter1, $parameter2) {

        $query = "UPDATE terminal_report SET status = 'Reversed', pending_balance = '$parameter', transfer_balance = '$parameter1' WHERE refid = '$parameter2' AND status = 'Approved'";

        return $this->db->updateWallet($query, $parameter);

    }

    //UPDATE TERMINAL INFO
    public function updateTerminalReg($parameter, $parameter1, $parameter2, $parameter3) {

        $query = "UPDATE terminal_reg SET pending_balance = '$parameter', settled_balance = '$parameter1', total_transaction_count = '$parameter2' WHERE terminal_id = '$parameter3' AND terminal_status = 'Assigned'";

        return $this->db->updateTerminal($query, $parameter, $parameter1, $parameter2);

    }


    //UPDATE BORROWER BY ACCOUNT NUMBER (INT)
    public function updateBorrower($parameter, $parameter1, $parameter2) {

        $query = "UPDATE borrowers SET balance = '$parameter' WHERE account = '$parameter1' AND branchid = '$parameter2'";

        return $this->db->updateLedgerBal($query, $parameter);

    }


    //UPDATE BORROWER BY ACCOUNT PASSWORD (STRING)
    public function updateBorrowerPasswd($parameter, $parameter1, $parameter2) {

        $query = "UPDATE borrowers SET password = '$parameter' WHERE branchid = '$parameter2' AND (account = '$parameter1' OR username = '$parameter1')";

        return $this->db->updateLedgerBal($query, $parameter);

    }


    //UPDATE BORROWER BY ACCOUNT TRANSACTION PIN (STRING)
    public function updateBorrowerTPin($parameter, $parameter1, $parameter2) {

        $query = "UPDATE borrowers SET tpin = '$parameter' WHERE account = '$parameter1' AND branchid = '$parameter2'";

        return $this->db->updateLedgerBal($query, $parameter);

    }


    //UPDATE BORROWER BY ACCOUNT NUMBER (INT)
    public function updateBorrowerStatus($parameter, $parameter1, $parameter2, $parameter3) {

        $query = "UPDATE borrowers SET status = '$parameter', acct_status = '$parameter1' WHERE account = '$parameter2' AND branchid = '$parameter3'";

        return $this->db->updateCustomerStatus($query, $parameter, $parameter1);

    }


    //UPDATE AGGREGATOR BY ID (STRING)
    public function updateAggr($parameter, $parameter1) {

        $query = "UPDATE aggregator SET wallet_balance = '$parameter' WHERE aggr_id = '$parameter1'";

        return $this->db->updateAggr($query, $parameter);

    }


    //UPDATE TILL ACCOUNT BY STAFF ID (STRING)
    public function updateTillAcct($parameter, $parameter1, $parameter4, $parameter2, $parameter3) {

        $query = "UPDATE till_account SET balance = '$parameter', commission_balance = '$parameter1', unsettled_balance = '$parameter4' WHERE cashier = '$parameter2' AND companyid = '$parameter3'";

        return $this->db->updateTill($query, $parameter, $parameter1, $parameter4);

    }


    /** ENDPOINT URL FOR THIS CUSTOMER PROFILE UPDATE ARE:
     * 
     * api/customer/    : To update customer information using PC / Mobile Phone with the following required field:
     * 
     * {
	*   "fname" : "Akingbade",
	*   "lname" : "Tomos",
	*   "email" : "akingbade@gm.com",
	*   "phone" : "081022222222",
	*   "dob" : "1960-12-11",
	*   "addrs" : "No. 10 Brown lane",
	*   "city" : "Lagos",
	*   "state" : "Lagos",
	*   "country" : "Nigeria",
	*   "username" : "testing123",
	*   "password" : "test",
	*   "id" : 500
    *  }
     * 
     * */

    public function updatePcCustomer($parameters) {

        $query = "UPDATE borrowers SET
        
        fname = ?, lname = ?, email = ?, phone = ?, dob = ?, addrs = ?, city = ?, state = ?, country = ?, username = ?, password = ? WHERE id = ?";

        if(isset($parameters['id']) && isset($parameters['fname']) && isset($parameters['lname']) && isset($parameters['phone']) && isset($parameters['dob'])) {

            $id = $parameters['id'];
            $fname = $parameters['fname'];
            $lname = $parameters['lname'];
            $email = $parameters['email'];
            $phone = $parameters['phone'];
            $dob = $parameters['dob'];
            $addrs = $parameters['addrs'];
            $city = $parameters['city'];
            $state = $parameters['state'];
            $country = $parameters['country'];
            $username = $parameters['username'];
            $password = $parameters['password'];

            if($fname == "" || $lname == "" || $phone == "" || $dob == "" || $id == ""){

                return [
                    
                    "responseCode"=> "02",

                    "message" => "Required Field Missing",

                ];

            }
            else{
            
                $results = $this->db->updateCustomer($query, $fname, $lname, $email, $phone, $dob, $addrs, $city, $state, $country, $username, $password, $id);

                return [
                    
                    "responseCode"=> "00",

                    "update_status" => "Success",

                    "message" => "Account Update Successfully",

                ];

            }

        }
        else{
            return [
                
                "responseCode"=> "02",

                "message" => "Some field are missing",

            ];
        }

    }
    
    

    /** ENDPOINT URL FOR THIS BRANCH UPDATE ARE:
     * 
     * api/branches/    : To update branch information using PC / Mobile Phone with the following required field:
     * 
     * {
     *  "branchid" : "BR125"
	*   "bname" : "Agege Branch - FCMB",
	*   "bcountry" : "Nigeria",
	*   "currency" : "NGN",
	*   "branch_addrs" : "No. 10 Brown lane",
	*   "branch_city" : "Lagos",
	*   "branch_province" : "Agege",
	*   "branch_zipcode" : "100234",
	*   "branch_landline" : "8999999",
	*   "branch_mobile" : "888778788",
    *  }
     * 
     * */

    public function updateBranch($parameters) {

        $query = "UPDATE branches SET
        
        bname = ?, bcountry = ?, currency = ?, branch_addrs = ?, branch_city = ?, branch_province = ?, branch_mobile = ?, bstatus = ? WHERE branchid = ?";

        if(isset($parameters['branchid']) && isset($parameters['bname']) && isset($parameters['bcountry']) && isset($parameters['currency']) && isset($parameters['branch_addrs']) && isset($parameters['branch_mobile'])) {

            $branchid = $parameters['branchid'];
            $bname = $parameters['bname'];
            $bcountry = $parameters['bcountry'];
            $currency = $parameters['currency'];
            $branch_addrs = $parameters['branch_addrs'];
            $branch_city = $parameters['branch_city'];
            $branch_province = $parameters['branch_province'];
            $branch_mobile = $parameters['branch_mobile'];
            $bstatus = $parameters['bstatus'];

            if($bname == "" || $bcountry == "" || $currency == "" || $branch_mobile == "" || $branch_addrs == "" || $branchid == ""){

                return [
                    
                    "responseCode"=> "02",

                    "message" => "Required Field Missing",

                ];

            }
            else{
            
                $results = $this->db->updateBranch($query, $bname, $bcountry, $currency, $branch_addrs, $branch_city, $branch_province, $branch_mobile, $bstatus, $branchid);

                return [
                    
                    "responseCode"=> "00",

                    "update_status" => "Success",

                    "message" => "Branch Info. Update Successfully",

                ];

            }

        }
        else{
            return [
                
                "responseCode"=> "02",

                "message" => "Some field are missing",

            ];
        }

    }


    /** ENDPOINT URL TO DELETE CUSTOMER ACCOUNT:
     * 
     * api/customer/    : To delete customer account using PC / Mobile Phone with the following required field:
     * 
     * {
	*   "id" : 500
    *  }
     * 
     * */
    public function deletePcCustomer($id) {

        $query = "DELETE FROM borrowers WHERE id = ?";

        $result = $this->db->deleteById($query, $id);

        return [
            
            "responseCode"=> "00",

            "del_status" => "Success",

            "message" => "Customer with id $id was successfully deleted",

        ];

    }



    /** ENDPOINT URL TO DELETE BRANCH:
     * 
     * api/branches/    : To delete branch using PC / Mobile Phone with the following required field:
     * 
     * {
	*   "branchid" : BR500 (STRING)
    *  }
     * 
     * */

    public function deleteBranch($id) {

        $query = "DELETE FROM branches WHERE branchid = ?";

        $result = $this->db->deleteById($query, $id);

        return [
            
            "responseCode"=> "00",

            "del_status" => "Success",

            "message" => "Branch with id $id was successfully deleted",

        ];

    }


    /** ENDPOINT URL FOR VIRTUAL ACCOUNT ARE:
     * 
     * api/wallet/    : To create new virtual account for a particular customer ledger account with the following required field:
     * 
     * {
     *  "account_id" : "INST-893800",
    *  }
     * 
     * */

    public function createVA($parameter, $registeral) {
        
        $query = "INSERT INTO virtual_account (account_ref, userid, account_name, account_number, bank_name, status, date_time, gateway_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if(isset($parameter->account_id)) {
    
            $accountUserId = $parameter->account_id;
            $accountReference =  "EAVA-".$this->db->myreference(20);

            if($accountUserId === ""){

                return -4;

            }elseif($this->db->startsWith($accountUserId,"AGT") || $this->db->startsWith($accountUserId,"INST") || $this->db->startsWith($accountUserId,"MER") || $this->db->startsWith($accountUserId,"COOP")){

                $getMs = $this->db->fetchMemberSettings($accountUserId);

                if($getMs === ""){

                    return -1;

                }else{

                    $myVA = $this->fetchVAByAcctNo($accountUserId);

                    if($myVA === 0){
                        
                        $this->db->insertVA($query, $accountReference, $accountUserId, '', '', '', 'pending', '', 'monify');
                        
                        return [

                            "request_status" => "Success",
                
                            "message" => "Account Creation Request Made Successfully",
                
                            "info" => [

                                "accountReference"=> $accountReference,
                
                                "status"=> "pending",

                            ]

                        ];

                    }else{

                        return -2;

                    }

                }

            }else{

                //START OF CUSTOMER SECTION
                $getAcct = $this->fetchCustomerById($accountUserId, $registeral);

                if($getAcct === 0){

                    return -1;

                }else{

                    $myVA = $this->fetchVAByAcctNo($accountUserId);

                    if($myVA === 0){
                        
                        $this->db->insertVA($query, $accountReference, $accountUserId, '', '', '', 'pending', '', 'monify');
                        
                        return [

                            "request_status" => "Success",
                
                            "message" => "Account Creation Request Made Successfully",
                
                            "info" => [

                                "accountReference"=> $accountReference,
                
                                "status"=> "pending",

                            ]

                        ];

                    }else{

                        return -2;

                    }

                }
                //END OF CUSTOMER SECTION

            }

        }else{

            return -3;

        }

    }


    /** ENDPOINT URL FOR LOGIN:
     * 
     * {
     *  "username": "test",
     *  "password": "test",
     *  "merchantID": "INST1228288"
    *  }
     * 
     * */

    public function createAuth($parameter,$registeral,$companyName,$reg_fName,$reg_lName,$reg_mName,$tillBalance,$tillCommission,$tillCommissionType,$myimage,$reg_staffid) {
        
        if(isset($parameter->username) && isset($parameter->password) && isset($parameter->merchantID)) {
                
            $username = $parameter->username;
            
            $password = $parameter->password;

            $merchantID = $parameter->merchantID;

            if($username === "" || $password === "" || $merchantID === ""){

                return -1;

            }else{
                
                $checkAuth = $this->fetchUser2($username,base64_encode($password),$merchantID);
                
                if($checkAuth === 0){
                    
                    return -3;
                    
                }else{
                    
                    $imagePath = "https://esusu.app/".$myimage;
                    
                    $getCorrectImage = preg_match('/\s/',$imagePath);
                        
                    $detectValidImageFormat = ($getCorrectImage >= 1) ? 'https://esusu.app/img/image-placeholder.jpg' : $imagePath;
                        
                    $encryptedImage = ($myimage == "" || $myimage == "img/") ? file_get_contents('https://esusu.app/img/image-placeholder.jpg') : file_get_contents($detectValidImageFormat); 
                          
                    // Encode the image string data into base64 
                    $imageData = base64_encode($encryptedImage); 
                        
                    return [
                        
                        "resposeCode"=> "00",
                        "accountID"=>$reg_staffid,
                        "username"=> $username,
                        "firstName"=> $reg_fName,
                        "lastName"=> $reg_lName,
                        "middleName"=> $reg_mName,
                        "companyName"=> $companyName,
                        "profilePicture"=> $imageData,
                        "message"=> "Logged-in Successfully",
                        "tillAccountInfo"=> [
                            "tillBalance"=> $tillBalance,
                            "tillCommission"=> $tillCommission,
                            "tillCommissionType"=> $tillCommissionType
                        ]
                
                    ];
                    
                }
                    
            }

        }else{

            return -2;

        }

    }


    /** ENDPOINT URL FOR CUSTOMER LOGIN WITH ev1 ENDPOINT:
     * 
     * {
     *  "username": "test",
     *  "password": "test",
     *  "clientID": "INST1228288"
    *  }
     * 
     * */

    public function customerAuth($parameter,$companyName,$clientId,$fileBaseUrl) {
        
        if(isset($parameter->username) && isset($parameter->password) && isset($parameter->clientID)) {
            
            $username = $parameter->username;
            
            $password = $parameter->password;

            $clientID = $parameter->clientID;
            
            $checkAuth = $this->fetchBorrowers($username,$password,$clientID);
            $accountStatus = $checkAuth['acct_status'];
            $myimage = $checkAuth['image'];
            $accountName = $checkAuth['lname'].' '.$checkAuth['fname'];
            $userBvn = ($checkAuth['unumber'] == "") ? "" : $checkAuth['unumber'];
            $email = $checkAuth['email'];
            $acct_number = $checkAuth['account'];
            $myAccountReference = uniqid("EAVA-").time();

            if($username === "" || $password === "" || $clientID === ""){

                return -1;

            }elseif($checkAuth === 0){
                    
                return -3;
                    
            }elseif($accountStatus == "Not-Activated"){

                return -4;

            }else{

                //GENERATE PROVIDUS ACCOUNT NUMBER
                $generateAcct = $this->virtualAccountCreation($accountName,$userBvn,$email,$myAccountReference,$clientId,$acct_number,"");
                    
                $imagePath = $fileBaseUrl.$myimage;
                    
                $getCorrectImage = preg_match('/\s/',$imagePath);
                    
                $detectValidImageFormat = ($getCorrectImage >= 1) ? $fileBaseUrl.'image-placeholder.jpg' : $imagePath;
                    
                $encryptedImage = ($myimage == "" || $myimage == "img/") ? file_get_contents($fileBaseUrl.'image-placeholder.jpg') : file_get_contents($detectValidImageFormat); 
                          
                // Encode the image string data into base64 
                $imageData = base64_encode($encryptedImage); 
                        
                return [

                    "resposeCode"=> "00",

                    "accountID"=>$checkAuth['account'],

                    "username"=> $username,

                    "firstName"=> $checkAuth['fname'],

                    "lastName"=> $checkAuth['lname'],

                    "middleName"=> $checkAuth['mname'],

                    "companyName"=> $companyName,

                    "profilePicture"=> $imageData,

                    "message"=> "Logged-in Successfully",

                    "accountInfo"=> [

                        "ledgerBalance"=> number_format($checkAuth['balance'],2,'.',','),

                        "targetSavingsBalance"=> number_format($checkAuth['target_savings_bal'],2,'.',','),

                        "investmentBalnce"=> number_format($checkAuth['investment_bal'],2,'.',','),

                        "loanBalance"=> number_format($checkAuth['loan_balance'],2,'.',','),

                        "assetAcquisitionBalance"=> number_format($checkAuth['asset_acquisition_bal'],2,'.',','),

                        "walletBalance"=> number_format($checkAuth['wallet_balance'],2,'.',',')

                    ]

                ];
                
            }

        }else{

            return -2;

        }

    }
    
    
    /** ENDPOINT URL FOR APP ACTIVATION ARE:
     * 
     * {
     *  "activationKey" : "INST-893800",
    *  }
     * 
     * */

    public function appActivation($parameter) {
        
        if(isset($parameter->activationKey)) {
    
            $activationKey = $parameter->activationKey;

            if($activationKey === ""){

                return -1;

            }else{
                
                $checkAct = $this->checkActivationKey($activationKey);
                
                if($checkAct === 0){
                    
                    return -3;
                    
                }
                else{
                    
                    $instID = $checkAct['institution_id'];
                    
                    $instName = $checkAct['institution_name'];
                    
                    $instApiKey = $checkAct['api_key'];

                    $memberSet = $this->db->fetchMemberSettings($instID);

                    $myimage = $memberSet['logo'];

                    $imagePath = "https://esusu.app/".$myimage;
                    
                    $getCorrectImage = preg_match('/\s/',$imagePath);
                        
                    $detectValidImageFormat = ($getCorrectImage >= 1) ? 'https://esusu.app/img/esusu.africa.png' : $imagePath;
                        
                    $encryptedImage = ($myimage == "" || $myimage == "img/") ? file_get_contents('https://esusu.app/img/esusu.africa.png') : file_get_contents($detectValidImageFormat); 
                          
                    // Encode the image string data into base64 
                    $imageData = base64_encode($encryptedImage); 
                    
                    return [
                    
                        "resposeCode"=> "00",
                        
                        "merchantID"=> $instID,
                        
                        "merchantName"=> $instName,
                        
                        "apiKey"=> $instApiKey,

                        "companyLogo"=> $imageData,
                        
                        "message"=> "Activated Successfully!"
            
                    ];
                    
                }

            }

        }else{

            return -2;

        }

    }



    /** ENDPOINT URL FOR PIN VALIDATION:
     * 
     * {
     *  "ePin": "1234"
    *  }
     * 
     * */

    public function ePinValidation($parameter, $mytpin) {
        
        if(isset($parameter->ePin)) {
    
            $ePin = $parameter->ePin;

            if($ePin === ""){

                return -1;

            }elseif($ePin != $mytpin){

                return -2;
                    
            }else{

                return [

                    "resposeCode"=> "00",

                    "message"=> "Pin Validated Successfully"

                ];

            }

        }else{

            return -3;

        }

    }


    /** ENDPOINT URL FOR AccountInfo:
     * 
     * {
     *  "accountID": "12311114"
    *  }
     * 
     * */

    public function accountInfo($registeral, $companyName, $reg_staffid) {
        
        //FOR STAFF
        $staff = $this->fetchTerminalOprt($reg_staffid, $registeral);
        $verifytill = $this->fetchTillAcct($reg_staffid);

        //FOR CUSTOMER/BORROWER
        //$customer = $this->fetchCustomerByAcctId($reg_staffid, $registeral);

        if($staff === 0 && $customer === 0){

            return -2;
                    
        }elseif($staff != 0){

            return [
                "resposeCode"=> "00",
                "accountID"=> $reg_staffid,
                "firstName"=> $staff['name'],
                "lastName"=> $staff['lname'],
                "emailAddress"=> $staff['email'],
                "phoneNumber"=> $staff['phone'],
                "gender"=> $staff['gender'],
                "transferBalance"=> $staff['transfer_balance'],
                "tillBalance"=> $verifytill['balance'],
                "tillCommissionBalance"=> $verifytill['commission_balance'],
                "tillCommissionType"=> $verifytill['commission_type'],
                "companyName"=> $companyName,
                "message"=> "Info Fetched Successfully"
            ];
                
        }/*elseif($customer != 0 && $staff === 0){

            return [
                "resposeCode"=> "00",
                "accountID"=> $reg_staffid,
                "serialNo"=> $customer['snum'],
                "firstName"=> $customer['fname'],
                "lastName"=> $customer['lname'],
                "emailAddress"=> $customer['email'],
                "phoneNumber"=> $customer['phone'],
                "gender"=> $customer['gender'],
                "ledgerBalance"=> $customer['balance'],
                "targetSavingsBalance"=> $customer['target_savings_bal'],
                "investmentBalnce"=> $customer['investment_bal'],
                "loanBalance"=> $customer['loan_balance'],
                "assetAcquisitionBalance"=> $customer['asset_acquisition_bal'],
                "walletBalance"=> $customer['wallet_balance'],
                "companyName"=> $companyName,
                "message"=> "Info Fetched Successfully"
            ];

        }*/
        else{
            
            return -1;
            
        }

    }
    
    
    //Function to generate random string
    public function random_strings($length_of_string) 
    { 
        // String of all alphanumeric character 
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
      
        // Shufle the $str_result and returns substring 
        // of specified length 
        return substr(str_shuffle($str_result), 0, $length_of_string); 
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


    /**
     * Function to generate Virtual Account
     * @group string
     */
    public function virtualAccountCreation($accountName,$userBvn,$customerEmail,$TxtReference,$clientId,$ledgerAccountNumber,$staffid){
        
        $myStatus = "ACTIVE";
        $date_time = date("Y-m-d h:i:s");

        //FETCH SYSTEMSET FOR PROVIDUS CREDENTIALS
        $pBCredentials = $this->db->fetchSystemSet();
        $providusClientId = $pBCredentials['providusClientId'];
        $providusClientSecret = $pBCredentials['providusClientSecret'];
        $rave_secret_key = $pBCredentials['secret_key'];

        $searchMemSet = $this->db->fetchMemberSettings($clientId);
        $defaultVAAcct = $searchMemSet['defaultAcct'];
        $parameter = (explode(',',$defaultVAAcct));
        $countNum = count($parameter);

        for($i = 0; $i < $countNum; $i++){

            $mydefaultbank = ($parameter[$i] == "Providus Bank" ? "providus" : ($parameter[$i] == "Flutterwave" ? "wema" : "None"));

            $searchVA = $this->fetchVAByAcctNo($ledgerAccountNumber);
            
            if($searchVA === 0 && $mydefaultbank != "None" && $parameter[$i] == "Providus Bank"){

                $encodeAUth = $providusClientId.":".$providusClientSecret;
                $providusSignature = strtoupper(hash('sha512', $encodeAUth));
                
                //GENERATE VIRTUAL ACCOUNT ON MONIFY
                $fetch_restapi = $this->fetchApi("providus_baseUrl");
                $api_url = $fetch_restapi['api_url']."PiPCreateReservedAccountNumber";
                
                $header = [
                    "Content-Type: application/json",
                    "Client-Id: ".$providusClientId,
                    "X-Auth-Signature: ".$providusSignature
                ];
                
                $myData = array('account_name'=>$accountName,'bvn'=>$userBvn);
                
                $response = $this->callAPI("POST", $api_url, json_encode($myData), $header, 0);
                $providus_generate = json_decode($response, true);
                $myAccountName = $providus_generate['account_name'];
                $myAccountNumber = $providus_generate['account_number'];
                $myBankName = "Providus Bank";
                $provider = "providus";
                
                ($providus_generate['responseCode'] == "00") ? $myQueryVA = "INSERT INTO virtual_account (account_ref, userid, account_name, account_number, bank_name, status, date_time, gateway_name, companyid, reg_type, acct_status, acctOfficer, transferLimitPerDay, transferLimitPerTrans, airtime_dataLimitPerDay, airtime_dataLimitPerTrans) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
                ($providus_generate['responseCode'] == "00") ? $this->db->insertVA($myQueryVA, $TxtReference, $ledgerAccountNumber, $myAccountName, $myAccountNumber, $myBankName, $myStatus, $date_time, $provider, $clientId, "Individual", "Pending", $staffid, "150000", "50000", "100000", "50000") : "";

                return [

                    "accountNumber" => $myAccountNumber,

                    "accountName" => $myAccountName,

                    "bankName" => $myBankName

                ];

            }elseif($searchVA === 0 && $mydefaultbank != "None" && $parameter[$i] == "Flutterwave"){

                $amount = "0";
                //GENERATE VIRTUAL ACCOUNT ON FLUTTERWAVE
                $fetch_restapi = $this->fetchApi("rave_create_virtual_account");
                $api_url = $fetch_restapi['api_url'];
                
                $header = [
                    "Content-Type: application/json",
                    "Authorization: Bearer ".$rave_secret_key
                ];
                
                $myData = array('email'=>$customerEmail,'is_permanent'=>true,'amount'=>$amount,'narration'=>$accountName,'tx_ref'=>$TxtReference);
                  
                $response = $this->callAPI("POST", $api_url, json_encode($myData), $header, 1);
                $rave_generate = json_decode($response, true);
                $myAccountNumber = $rave_generate['data']['account_number'];
                $myBankName = $rave_generate['data']['bank_name'];
                $provider = "rave";

                ($rave_generate['data']['response_code'] == "02") ? $myQueryVA = "INSERT INTO virtual_account (account_ref, userid, account_name, account_number, bank_name, status, date_time, gateway_name, companyid, reg_type, acct_status, acctOfficer, transferLimitPerDay, transferLimitPerTrans, airtime_dataLimitPerDay, airtime_dataLimitPerTrans) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
                ($rave_generate['data']['response_code'] == "02") ? $this->db->insertVA($myQueryVA, $TxtReference, $ledgerAccountNumber, $accountName, $myAccountNumber, $myBankName, $myStatus, $date_time, $provider, $clientId, "Individual", "Pending", $staffid, "150000", "50000", "100000", "50000") : "";

                return [

                    "accountNumber" => $myAccountNumber,

                    "accountName" => $accountName,

                    "bankName" => $myBankName

                ];

            }elseif($searchVA === 0 && $mydefaultbank != "None" && $parameter[$i] == "Wema Bank"){

                $generateVANo = $wemaVAPrefix.rand(100,999).date("ds");

                $fetchVANum = $this->fetchVAByAcctNo($generateVANo);
                $realVANo = ($fetchVANum == "0") ? $generateVANo : $wemaVAPrefix.date("ds").rand(100,999);

                $myAccountNumber = $realVANo;
                $myBankName = "Wema Bank";
                $provider = "wema";

                $myQueryVA = "INSERT INTO virtual_account (account_ref, userid, account_name, account_number, bank_name, status, date_time, gateway_name, companyid, reg_type, acct_status, acctOfficer, transferLimitPerDay, transferLimitPerTrans, airtime_dataLimitPerDay, airtime_dataLimitPerTrans) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertVA($myQueryVA, $TxtReference, $ledgerAccountNumber, $accountName, $myAccountNumber, $myBankName, $myStatus, $date_time, $provider, $clientId, "Individual", "Pending", $staffid, "150000", "50000", "100000", "50000");

                return [

                    "accountNumber" => $myAccountNumber,

                    "accountName" => $accountName,

                    "bankName" => $myBankName

                ];

            }else{

                return "None";

            }

        }
    
    }



}
?>