<?php

class User {

    protected $db;

    public function __construct(Database $db) {

        $this->db = $db;

    }

    //FETCH ALL USER CUSTOMERS
    public function fetchAllCustomer($parameter,$companyName) {

        $query = "SELECT * FROM borrowers WHERE branchid = ? ORDER BY id DESC";

        $myOutput = $this->db->fetchAll($query, $parameter);
        
        if($myOutput >= 1){
        
            for($i = 0; $i <= $myOutput; $i++){
                    
                foreach($myOutput as $putEntry => $key){
                    
                    $staffID = $key['lofficer'];
            
                    $searchStaff = $this->db->fetchStaff($staffID,$parameter);
                    
                    $myStaffName = $searchStaff['name'].' '.$searchStaff['lname'];
                    
                    $output2[$i] = [
                        
                        "basicInfo"=> [
                            
                            "id"=> $key['id'],
                        
                            "serialNumber "=> $key['snum'],
                            
                            "firstName"=> $key['fname'],
                            
                            "lastName"=> $key['lname'],
                            
                            "middleName"=> $key['mname'],
                            
                            "email"=> $key['email'],
                            
                            "phone"=> preg_replace("/[^a-zA-Z0-9]/", "", $key['phone']),
                            
                            "gender"=> $key['gender'],
                            
                            "dateOfBirth"=> $key['dob'],
                            
                            "occupation"=> $key['occupation'],
                            
                            "employer"=> $key['employer'],
                            
                            "homeAddress"=> $key['addrs'],
                            
                            "city"=> $key['city'],
                            
                            "state"=> $key['state'],
                            
                            "country"=> $key['country'],
                            
                            "nextOfKinName"=> $key['nok'],
                            
                            "nextOfKinRelationship"=> $key['nok_rela'],
                            
                            "nextofKinPhoneNumber"=> $key['nok_phone'],
                            
                            "accountNumber"=> $key['account'],
                            
                            "userName"=> $key['username'],
                            
                            "ledgerBalance"=> $key['balance'],
                            
                            "investmentBalance"=> $key['investment_bal'],
                            
                            "walletBalance"=> $key['wallet_balance'],
                            
                            "acctountStatus"=> $key['acct_status'],
                            
                            "accountOfficer"=> $myStaffName,
                            
                            "merchantName"=> $companyName,
                            
                            "evn"=> $key['evn'],
                            
                            "bvn"=> $key['unumber'],
                            
                            "otpOption"=> $key['opt_option'],
                            
                            "allowOverdraft"=> $key['overdraft'],
                            
                            "currency"=> $key['currency'],
                            
                            "accountOpeningDate"=> $key['acct_opening_date'],
                            
                            ],
                            
                        "savingsCulture"=> [
                            
                            "countributionInterval"=> $key['s_contribution_interval'],
                        
                            "savingsAmount"=> $key['savings_amount'],
                            
                            "chargeInterval"=> $key['charge_interval'],
                            
                            "chargesAmount"=> $key['chargesAmount'],
                            
                            "disbursementInterval"=> $key['disbursement_interval'],
                            
                            "disbursementChannel"=> $key['disbursement_channel'],
                            
                            "autoDisbursementStatus"=> $key['auto_disbursement_status'],
                            
                            "autoChargeStatus"=> $key['auto_charge_status'],
                            
                            "nextChargeDate"=> $key['next_charge_date'],
                            
                            "nextDisbursementDate"=> $key['next_disbursement_date'],
                            
                            ],
                            
                        "walletAccount"=> [
                            
                            "walletReferenceId"=> $key['virtual_number'],
                            
                            "walletAccountNumber"=> $key['virtual_acctno'],
                            
                            "bankName"=> $key['bankname'],
                            
                            ]
                        
                        ];
                    $i++;
                }
                return $output2;
                
            }
            
        }else{
            
            return [
                array(
                    "responseCode"=> "01",
                    "message"=> "no data found"
                    )
                ];
            
        }

    }


    //FETCH CUSTOMER BY id OR account_number
    public function fetchCustomerById($parameter, $parameter1, $companyName) {

        $query = "SELECT * FROM borrowers WHERE (id = '$parameter' OR account = '$parameter') AND branchid = '$parameter1' ORDER BY id DESC";

        $myOutput = $this->db->fetchById($query, $parameter);
        
        if($myOutput >= 1){
        
            foreach($myOutput as $putEntry => $key){
                    
                    $staffID = $myOutput['lofficer'];
                    
                    $image = "https://esusu.app/".$myOutput['image'];
                    
                    $getCorrectImage = preg_match('/\s/',$image);
                    
                    $detectValidImageFormat = ($getCorrectImage >= 1) ? 'https://esusu.app/img/image-placeholder.jpg' : $image;
                    
                    $encryptedImage = ($myOutput['image'] == "" || $myOutput['image'] == "img/") ? file_get_contents('https://esusu.app/img/image-placeholder.jpg') : file_get_contents($detectValidImageFormat); 
                      
                    // Encode the image string data into base64 
                    $imageData = base64_encode($encryptedImage);  
            
                    $searchStaff = $this->db->fetchStaff($staffID,$parameter);
                    
                    $myStaffName = $searchStaff['name'].' '.$searchStaff['lname'];
                    
                    $output2 = [
                        
                        "basicInfo"=> [
                            
                            "id"=> $myOutput['id'],
                        
                            "serialNumber "=> $myOutput['snum'],
                            
                            "firstName"=> $myOutput['fname'],
                            
                            "lastName"=> $myOutput['lname'],
                            
                            "middleName"=> $myOutput['mname'],
                            
                            "email"=> $myOutput['email'],
                            
                            "phone"=> preg_replace("/[^a-zA-Z0-9]/", "", $myOutput['phone']),
                            
                            "gender"=> $myOutput['gender'],
                            
                            "dateOfBirth"=> $myOutput['dob'],
                            
                            "occupation"=> $myOutput['occupation'],
                            
                            "employer"=> $myOutput['employer'],
                            
                            "homeAddress"=> $myOutput['addrs'],
                            
                            "city"=> $myOutput['city'],
                            
                            "state"=> $myOutput['state'],
                            
                            "country"=> $myOutput['country'],
                            
                            "nextOfKinName"=> $myOutput['nok'],
                            
                            "nextOfKinRelationship"=> $myOutput['nok_rela'],
                            
                            "nextofKinPhoneNumber"=> $myOutput['nok_phone'],
                            
                            "accountNumber"=> $myOutput['account'],
                            
                            "userName"=> $myOutput['username'],
                            
                            "ledgerBalance"=> $myOutput['balance'],
                            
                            "investmentBalance"=> $myOutput['investment_bal'],
                            
                            "walletBalance"=> $myOutput['wallet_balance'],
                            
                            "acctountStatus"=> $myOutput['acct_status'],
                            
                            "accountOfficer"=> $myStaffName,
                            
                            "merchantName"=> $companyName,
                            
                            "evn"=> $myOutput['evn'],
                            
                            "bvn"=> $myOutput['unumber'],
                            
                            "otpOption"=> $myOutput['opt_option'],
                            
                            "allowOverdraft"=> $myOutput['overdraft'],
                            
                            "currency"=> $myOutput['currency'],
                            
                            "accountOpeningDate"=> $myOutput['acct_opening_date'],
                            
                            "profilePicture"=> $imageData,
                            
                            ],
                            
                        "savingsCulture"=> [
                            
                            "countributionInterval"=> $myOutput['s_contribution_interval'],
                        
                            "savingsAmount"=> $myOutput['savings_amount'],
                            
                            "chargeInterval"=> $myOutput['charge_interval'],
                            
                            "chargesAmount"=> $myOutput['chargesAmount'],
                            
                            "disbursementInterval"=> $myOutput['disbursement_interval'],
                            
                            "disbursementChannel"=> $myOutput['disbursement_channel'],
                            
                            "autoDisbursementStatus"=> $myOutput['auto_disbursement_status'],
                            
                            "autoChargeStatus"=> $myOutput['auto_charge_status'],
                            
                            "nextChargeDate"=> $myOutput['next_charge_date'],
                            
                            "nextDisbursementDate"=> $myOutput['next_disbursement_date'],
                            
                            ],
                            
                        "walletAccount"=> [
                            
                            "walletReferenceId"=> $myOutput['virtual_number'],
                            
                            "walletAccountNumber"=> $myOutput['virtual_acctno'],
                            
                            "bankName"=> $myOutput['bankname'],
                            
                            ]
                        
                        ];
                    
                }
                return $output2;
                
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

    public function checkTerminalSerial($parameter) {

        $query = "SELECT * FROM terminal_reg WHERE terminal_serial = '$parameter' AND terminal_status = 'Assigned'";

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


    //FETCH CUSTOMER VIRTUAL ACCOUNT BY ledger account_number
    public function fetchVAByAcctNo($parameter) {

        $query = "SELECT * FROM virtual_account WHERE (userid = '$parameter' OR account_number = '$parameter')";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH UNIQUE CUSTOMER/USER VIRTUAL ACCOUNT BY account_number
    public function fetchUniqueVAByAcctNo($parameter,$parameter1) {

        $query = "SELECT * FROM virtual_account WHERE account_number = '$parameter' AND companyid = '$parameter1'";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH CUSTOMER NORMAL ACCOUNT BY ledger account_number
    public function fetchCustomerByAcctId($parameter,$parameter1) {

        $query = "SELECT * FROM borrowers WHERE account = '$parameter' AND branchid = '$parameter1'";

        return $this->db->fetchById($query, $parameter);

    }
    

    //FETCH WALLET HISTORY by refid or id
    public function fetchWalletHistoryById($parameter,$parameter1) {

        $query = "SELECT * FROM wallet_history WHERE (id = '$parameter' OR refid = '$parameter') AND (userid = '$parameter1' OR recipient = '$parameter1')";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH WALLET HISTORY by userid
    public function fetchAllWalletHistory($parameter) {

        $query = "SELECT * FROM wallet_history WHERE (userid = '$parameter'OR recipient = '$parameter')";

        return $this->db->fetchById($query, $parameter);

    }


    //FETCH ALL USER BRANCH CUSTOMERS USING BRANCHID (STRING)
    public function fetchBranchCustomer($parameter, $parameter1) {

        $query = "SELECT * FROM borrowers WHERE sbranchid = ? AND branchid = '$parameter1'";

        return $this->db->fetchAll($query, $parameter);

    }


    //FETCH ALL USER CUSTOMER BY LIMIT (INT)
    public function fetchCustomerByLimit($parameter, $limit) {

        $query = "SELECT * FROM borrowers WHERE branchid = ? ORDER BY id LIMIT $limit";

        return $this->db->fetchAll($query, $parameter);

    }


    //FETCH ALL USER TRANSACTION
    public function fetchAllTrans($parameter,$companyName) {

        $query = "SELECT * FROM transaction WHERE branchid = ? ORDER BY id DESC";

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


    //FETCH ALL USER TRANSACTION BY TXID (STRING) OR ID (INT) OR AccountNumber (INT)
    public function fetchTransById($parameter, $parameter1, $companyName) {

        $query = "SELECT * FROM transaction WHERE (id = '$parameter' OR txid = '$parameter' OR acctno = '$parameter') AND branchid = '$parameter1' ORDER BY id DESC";

        //$myOutput = $this->db->fetchById($query, $parameter);
        
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


    //FETCH USER TRANSACTION BY LIMIT
    public function fetchTransByLimit($parameter, $limit) {

        $query = "SELECT * FROM transaction WHERE branchid = ? ORDER BY id LIMIT $limit";

        return $this->db->fetchAll($query, $parameter);

    }


    //FETCH ALL CUSTOMERS DEPOSIT 
    public function fetchAllDeposit($parameter) {

        $query = "SELECT * FROM transaction WHERE branchid = ? AND t_type = 'Deposit'";

        return $this->db->fetchAll($query, $parameter);

    }


    //FETCH TOTAL LEDGER BALANCE
    public function fetchAllLedgerBal($parameter) {

        $query = "SELECT SUM(balance) FROM borrowers WHERE branchid = ?";

        $result = $this->db->fetchAll($query, $parameter);
        
        $output = [
            array(
                "responseCode"=> "00",
                "total_LedgerBalance"=> ($result['SUM(balance)'] == "") ? number_format(0,2,'.',',') : number_format($result['SUM(balance)'],2,'.',',')
                )
            ];
            
        return $output;

    }


    //FETCH TOTAL LEDGER COLLECTION
    public function fetchAllLedgerSavings($parameter) {

        $query = "SELECT SUM(amount) FROM transaction WHERE branchid = ?";

        $result = $this->db->fetchAll($query, $parameter);
        
        $output = [
            array(
                "responseCode"=> "00",
                "total_LedgerSavingsCollection"=> ($result['SUM(amount)'] == "") ? number_format(0,2,'.',',') : number_format($result['SUM(amount)'],2,'.',',')
                )
            ];
            
        return $output;

    }


    //FETCH ALL CUSTOMERS DEPOSIT MADE BY A PARTICULAR STAFF USING STAFF ID
    public function fetchDepositByStaff($parameter, $parameter1) {

        $query = "SELECT * FROM transaction WHERE posted_by = ? AND branchid = '$parameter1' AND t_type = 'Deposit'";

        return $this->db->fetchAll($query, $parameter);

    }


    //FETCH ALL CUSTOMERS DEPOSIT BY LIMIT
    public function fetchDepositByLimit($parameter, $limit) {

        $query = "SELECT * FROM transaction WHERE branchid = ? AND t_type = 'Deposit' ORDER BY id LIMIT $limit";

        return $this->db->fetchAll($query, $parameter);

    }


    //FETCH INDIVIDUAL LEDGER BALANCE
    public function fetchIndivLedgerBal($parameter, $acctno) {

        $query = "SELECT balance FROM borrowers WHERE branchid = ? AND account = '$acctno'";

        $result = $this->db->fetchAll($query, $parameter);
        
        $verifyCustAcct = $this->db->verifyCustAccount($acctno,$parameter);
        
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
                "LedgerBalance"=> ($result['balance'] == "") ? number_format(0,2,'.',',') : number_format($result['balance'],2,'.',',')
                )
            ];
            
            return $output;
            
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


    //FETCH ALL CUSTOMERS WITHDRAWAL BY LIMIT
    public function fetchWithdrawByLimit($parameter, $limit) {

        $query = "SELECT * FROM transaction WHERE branchid = ? AND t_type = 'Withdraw' ORDER BY id LIMIT $limit";

        return $this->db->fetchAll($query, $parameter);

    }


    //FETCH ALL CUSTOMERS WITHDRAWAL CHARGES
    public function fetchAllWithdrawCharges($parameter) {

        $query = "SELECT * FROM transaction WHERE branchid = ? AND t_type = 'Withdraw-Charges'";

        return $this->db->fetchAll($query, $parameter);

    }


    //FETCH ALL CUSTOMERS WITHDRAWAL CHARGES BY LIMIT
    public function fetchWithdrawChargesByLimit($parameter, $limit) {

        $query = "SELECT * FROM transaction WHERE branchid = ? AND t_type = 'Withdraw-Charges' ORDER BY id LIMIT $limit";

        return $this->db->fetchAll($query, $parameter);

    }


    /** ENDPOINT URL FOR THIS SECOND SECTION (BRANCH) ARE:
     * 
     * api/branches/    : To fetch all branches created by any institution / agent / merchant
     * 
     * api/branches/id  : To fetch user branch using id (INT)
     * 
     * api/branches/branchid : To fetch user branch using branchid (STRING)  
     * 
     * api/branches/branchTrans/branchid : To fetch all transaction done by a particular branch using branchid (STRING)
     * 
     * */


    //FETCH ALL USER BRANCHES
    public function fetchAllBranch($parameter) {

        $query = "SELECT * FROM branches WHERE created_by = ?";

        return $this->db->fetchAll($query, $parameter);

    }


    //FETCH USER BRANCH BY ID (INT) or BY BRANCHID (STRING)
    public function fetchBranchById($parameter, $parameter1) {

        $query = "SELECT * FROM branches WHERE (branchid = '$parameter' OR id = '$parameter') AND created_by = '$parameter1'";

        return $this->db->fetchAll($query, $parameter);

    }


    //FETCH USER BRANCH TRANSACTION USING BRANCHID (STRING)
    public function fetchBranchTrans($parameter) {

        $query = "SELECT * FROM transaction WHERE sbranchid = ?";

        return $this->db->fetchAll($query, $parameter);

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


    //UPDATE USER WALLET BY ID (STRING)
    public function updateUserWallet($parameter, $parameter1, $parameter2) {

        $query = "UPDATE user SET transfer_balance = '$parameter' WHERE id = '$parameter1' AND created_by = '$parameter2'";

        return $this->db->updateWallet($query, $parameter);

    }


    //UPDATE BORROWER BY ACCOUNT NUMBER (INT)
    public function updateBorrower($parameter, $parameter1, $parameter2) {

        $query = "UPDATE borrowers SET balance = '$parameter' WHERE account = '$parameter1' AND branchid = '$parameter2'";

        return $this->db->updateLedgerBal($query, $parameter);

    }


    //UPDATE AGGREGATOR BY ID (STRING)
    public function updateAggr($parameter, $parameter1) {

        $query = "UPDATE aggregator SET wallet_balance = '$parameter' WHERE aggr_id = '$parameter1'";

        return $this->db->updateAggr($query, $parameter);

    }


    //UPDATE TILL ACCOUNT BY STAFF ID (STRING)
    public function updateTillAcct($parameter, $parameter1, $parameter2, $parameter3) {

        $query = "UPDATE till_account SET balance = '$parameter', commission_balance = '$parameter1' WHERE cashier = '$parameter2' AND companyid = '$parameter3'";

        return $this->db->updateTill($query, $parameter, $parameter1);

    }


    /** ENDPOINT URL FOR THIS THIRD SECTION (CUSTOMER REGISTRATION) ARE:
     * 
     * api/customer/    : To register new customer using PC / Mobile Phone with the following required field:
     * 
     * {
	*   "snum" : "ABK/001",
	*   "fname" : "Akingbade",
	*   "lname" : "Tomos",
	*   "email" : "akingbade@gm.com",
	*   "phone" : "081022222222",
	*   "gender" : "Male",
	*   "dob" : "1960-12-11",
	*   "addrs" : "No. 10 Brown lane",
	*   "city" : "Lagos",
	*   "state" : "Lagos",
	*   "country" : "Nigeria",
	*   "username" : "testing123",
	*   "password" : "test",
	*   "account" : "api12345"
    *  }
     * 
     * */

    public function insertPcCustomer($parameter, $registeral, $reg_branch, $reg_staffName, $companyName, $allow_auth) {

        if(isset($parameter->fname) && isset($parameter->lname) && isset($parameter->phone) && isset($parameter->gender) && isset($parameter->dob) && isset($parameter->username) && isset($parameter->password) && isset($parameter->account) && isset($parameter->apiKey)) {

            $snum = $parameter->snum;
            $fname = $parameter->fname;
            $lname = $parameter->lname;
            $email = $parameter->email;
            $phone_no = $parameter->phone;
            $gender = $parameter->gender;
            $dob = $parameter->dob;
            $addrs = $parameter->addrs;
            $city = $parameter->city;
            $state = $parameter->state;
            $country = $parameter->country;
            $username = $parameter->username;
            $password = $parameter->password;
            $acct_number = $parameter->account;
            $tokenId = $parameter->apiKey;
            $community_role = "Borrower";
            $ledger_bal = "0.0";
            $invst_bal = "0.0";
            $last_with_date = "0000-00-00";
            $status = "Completed";
            $otp_option = "No";
            $currency = "NGN";
            $wallet_bal = "0.0";
            $over_draft = "No";
            $card_id = "NULL";
            $card_reg = "No";
            $tpin = substr((uniqid(rand(),1)),3,4);
            $gname = "Individual";
            $acct_status = "Not-Activated";
            $date_time = date("Y-m-d h:m:s");
            $acct_opening_date = date("Y-m-d");

            $verifyCustomer = $this->db->verifyCustomer($username,$phone_no);
            
            $checkAuth = $this->accessKey($tokenId,$registeral);

            if($fname == "" || $lname == "" || $phone_no == "" || $gender == "" || $dob == "" || $username == "" || $password == "" || $acct_number == "" || $tokenId == ""){

                return [

                    "message" => "Required Field Missing",

                ];

            }elseif($verifyCustomer === 1){

                return -2;

            }elseif($checkAuth === 0){
                    
                return -3;
                    
            }else{

                return [
                    
                    "responseCode"=> "00",
                    
                    "reg_status" => "Success",

                    "message" => "Account Registered Successfully",

                    "account_details" => [

                        "account_no"=> $parameter->account,

                        "username"  => $parameter->username,

                        "password"  => $parameter->password,
                        
                        "registeredBy"=> $reg_staffName,
                        
                        "companyName"=> $companyName,

                    ]

                ];

            }

        }else{
            return -1;
        }

    }


    /** ENDPOINT URL FOR THIS BRANCH REGISTRATION ARE:
     * 
     * api/branch/    : To register new branch using PC / Mobile Phone with the following required field:
     * 
     * {
	*   "bname" : "Agege Branch - FCMB",
	*   "bcountry" : "Nigeria",
	*   "currency" : "NGN",
	*   "branch_addrs" : "No. 10 Brown lane",
	*   "branch_city" : "Lagos",
	*   "branch_province" : "Agege",
	*   "branch_zipcode" : "100234",
	*   "branch_landline" : "8999999",
	*   "branch_mobile" : "888778788",
	*   "branchid" : "BR12345"
    *  }
     * 
     * */

    public function insertMyBranch($parameter, $registeral) {

        $query = "INSERT INTO branches (bprefix, bname, bopendate, bcountry, currency, branch_addrs, branch_city, branch_province, branch_zipcode, branch_landline, branch_mobile, branchid, bstatus, stamp, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if(isset($parameter->bname) && isset($parameter->bcountry) && isset($parameter->currency) && isset($parameter->branch_addrs) && isset($parameter->branch_city) && isset($parameter->branch_province) && isset($parameter->branch_zipcode) && isset($parameter->branch_landline) && isset($parameter->branch_mobile) && isset($parameter->branchid)) {

            $bprefix = "BR";
            $bname = $parameter->bname;
            $bopendate = date("Y-m-d");
            $bcountry = $parameter->bcountry;
            $currency = $parameter->currency;
            $branch_addrs = $parameter->branch_addrs;
            $branch_city = $parameter->branch_city;
            $branch_province = $parameter->branch_province;
            $branch_zipcode = $parameter->branch_zipcode;
            $branch_landline = $parameter->branch_landline;
            $branch_mobile = $parameter->branch_mobile;
            $branchid = $parameter->branchid;
            $bstatus = "Operational";
            $stamp = "";

            $verifyBranch = $this->db->verifyBranch($branchid,$registeral);

            if($bname == "" || $bcountry == "" || $currency == "" || $branch_addrs == "" || $branch_city == "" || $branch_province == "" || $branch_zipcode == "" || $branch_landline == "" || $branch_mobile == "" || $branchid == ""){

                return -1;

            }elseif($verifyBranch === 1){

                return [

                    "message" => "Duplicte Entry is not Allowed",

                ];

            }else{

                $this->db->insertBranch($query, $bprefix, $bname, $bopendate, $bcountry, $currency, $branch_addrs, $branch_city, $branch_province, $branch_zipcode, $branch_landline, $branch_mobile, $branchid, $bstatus, $stamp, $registeral);

                return [
                    
                    "responseCode"=> "00",

                    "reg_status" => "Success",

                    "message" => "Branch Registered Successfully",

                    "details" => [

                        "branchid"=> $parameter->branchid,

                        "branch_name"  => $parameter->bname,

                        "branch_currency"  => $parameter->bcountry,

                        "branch_address" => $parameter->branch_addrs,

                        "branch_mobile" => $parameter->branch_mobile,

                        "open_date" => date("Y-m-d"),

                        "branch_status" => "Operational",

                    ]

                ];

            }

        }

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


    /** ENDPOINT URL FOR DEPOSIT ARE:
     * 
     * api/savings/deposit    : To makwe new deposit to customer ledger account with the following required field:
     * 
     * {
     *  "txid" : "apiTXID-11111111111111111",
     *  "p_type" : "Cash",
	*   "acctno" : "186277222",
    *   "amount" : "1000",
    *   "posted_by" : "MEM176363",
    *   "remark" : "direct deposit",
    *   "currency" : "NGN",
    *   "aggr_id" : "AGGR71763"
    *  }
     * 
     * */

    public function postDeposit($parameter, $registeral, $reg_branch, $reg_staffid, $reg_staffName, $companyName, $irole, $allow_auth) {

        if(isset($parameter->txid) && isset($parameter->acctno) && isset($parameter->currency) && isset($parameter->amount) && isset($parameter->p_type) && isset($parameter->apiKey)) {

            $txid = $parameter->txid; //"apiTXID-".rand(100000000000000,999999999999999);
            $p_type = $parameter->p_type;
            $acctno = $parameter->acctno;
            $amount = $parameter->amount;
            $remark = $parameter->remark;
            $currency = ($parameter->currency === "") ? "NGN" : $parameter->currency;
            $aggr_id = $parameter->aggr_id;
            $tokenId = $parameter->apiKey;

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
            
            $checkAuth = $this->accessKey($tokenId,$registeral);
            
            $verifytill = $this->fetchTillAcct($reg_staffid);
            $balance = $verifytill['balance'];
            
            $resultsInfo = $this->db->executeCall($registeral);
            
            //FETCH INSTITUTION WALLET BALANCE
            $getInst = $this->fetchInstitutionById($registeral);
            $inst_wallet = $getInst['wallet_balance'];
    
            //FETCH TRANSACTION CHARGES
            $trans_charges = $resultsInfo['t_charges'];

            if($txid === "" || $p_type === "" || $amount === "" || $acctno === "" || $tokenId == ""){

                return -3;

            }elseif($verifyCustAcct === 0){

                return -2;

            }elseif($checkAuth === 0){
                    
                return -4;
                    
            }elseif($balance < $amount && $verifytill != "0"){
                
                return -5;
                
            }elseif($inst_wallet < $trans_charges){
                
                return -6;
                
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


    /** ENDPOINT URL FOR WITHDRAWAL ARE:
     * 
     * api/savings/withdraw    : To makwe new withdrawal from customer ledger account with the following required field:
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
    *   "aggr_id" : "AGGR71763"
    *  }
     * 
     * */

    public function postWithdrawal($parameter, $registeral, $reg_branch, $reg_staffid, $reg_staffName, $companyName, $irole, $allow_auth) {

        if(isset($parameter->txid) && isset($parameter->acctno) && isset($parameter->currency) && isset($parameter->amount) && isset($parameter->p_type) && isset($parameter->wcharges) && isset($parameter->apiKey)) {

            $txid = $parameter->txid; //"apiTXID-".rand(100000000000000,999999999999999);
            $p_type = $parameter->p_type;
            $acctno = $parameter->acctno;
            $amount = $parameter->amount;
            $wcharges = ($parameter->wcharges === "") ? 0.0 : $parameter->wcharges;
            $remark = $parameter->remark;
            $currency = ($parameter->currency === "") ? "NGN" : $parameter->currency;
            $aggr_id = $parameter->aggr_id;
            $tokenId = $parameter->apiKey;

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
            
            $checkAuth = $this->accessKey($tokenId,$registeral);
            
            $verifytill = $this->fetchTillAcct($reg_staffid);
            $balance = $verifytill['balance'];
            
            $resultsInfo = $this->db->executeCall($registeral);
            
            //FETCH INSTITUTION WALLET BALANCE
            $getInst = $this->fetchInstitutionById($registeral);
            $inst_wallet = $getInst['wallet_balance'];
    
            //FETCH TRANSACTION CHARGES
            $trans_charges = $resultsInfo['t_charges'];

            if($txid === "" || $p_type === "" || $amount === "" || $acctno === "" || $tokenId === ""){

                return -3;

            }elseif($verifyCustAcct === 0){

                return -2;

            }elseif($checkAuth === 0){
                    
                return -4;
                    
            }elseif($inst_wallet < $trans_charges){
                
                return -5;
                
            }elseif($ledger_bal < ($amount + $wcharges)){
                
                return -6;
                
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


    /** ENDPOINT URL FOR LOGIN ARE:
     * 
     * api/wallet/: To create new virtual account for a particular customer ledger account with the following required field:
     * 
     * {
     *  "token" : "INST-893800",
    *  }
     * 
     * */

    public function createAuth($parameter,$registeral,$companyName,$reg_fName,$reg_lName,$reg_mName,$tillBalance,$tillCommission,$tillCommissionType,$myimage) {
        
        if(isset($parameter->token) && isset($parameter->username) && isset($parameter->password)) {
    
            $tokenId = $parameter->token;
            
            $username = $parameter->username;
            
            $password = $parameter->password;

            if($tokenId === "" || $username === "" || $password === ""){

                return -1;

            }else{
                
                $checkAuth = $this->accessKey($tokenId,$registeral);
                
                if($checkAuth === 0){
                    
                    return -3;
                    
                }
                else{
                    
                    $imagePath = "https://esusu.app/".$myimage;
                    
                    $getCorrectImage = preg_match('/\s/',$imagePath);
                    
                    $detectValidImageFormat = ($getCorrectImage >= 1) ? 'https://esusu.app/img/image-placeholder.jpg' : $imagePath;
                    
                    $encryptedImage = ($myimage == "" || $myimage == "img/") ? file_get_contents('https://esusu.app/img/image-placeholder.jpg') : file_get_contents($detectValidImageFormat); 
                      
                    // Encode the image string data into base64 
                    $imageData = base64_encode($encryptedImage); 
                    
                    return [
                    
                        "resposeCode"=> "00",
                        
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
    
    
    /** ENDPOINT URL FOR APP ACTIVATION ARE:
     * 
     * api/wallet/: To create new virtual account for a particular customer ledger account with the following required field:
     * 
     * {
     *  "token" : "INST-893800",
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
                    
                    return [
                    
                        "resposeCode"=> "00",
                        
                        "merchantID"=> $instID,
                        
                        "merchantName"=> $instName,
                        
                        "apiKey"=> $instApiKey,
                        
                        "message"=> "Activated Successfully!"
            
                    ];
                    
                }

            }

        }else{

            return -2;

        }

    }




    /** ENDPOINT URL FOR TERMINAL LOGIN ARE:
     * 
     * api/posTerminalApi/loginterminal: To login to agent terminal with the following required field:
     * 
     * {
     *  "terminalid" : "2H893800",
     *  "pin" : "123456"
    *  }
     * 
     * */

    public function loginTerminal($parameter) {

        if(isset($parameter->terminalid) && isset($parameter->pin)){

            $terminalid = $parameter->terminalid;
            $pin = $parameter->pin;

            if($terminalid === "" || $pin === ""){

                return -1;

            }else{

                $checkTerm = $this->checkTerminal($terminalid);
                $tidoperator = $checkTerm['tidoperator'];
                $institutionid = $checkTerm['merchant_id'];
                    
                $operatorInfo = $this->fetchTerminalOperator($tidoperator,$pin);

                if($checkTerm === 0 || $operatorInfo === 0){

                    return -3;

                }else{
                    
                    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
                    $reqBody = json_encode(["terminalid"=>$terminalid,"pin"=>$pin]);
                    
                    // Encode Header to Base64Url String
                    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
                    // Encode Payload to Base64Url String
                    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($reqBody));
                    // Create Signature Hash
                    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'SK-0001036572478810433-PROD-7270074D9D784C69B12C5975B9679E0A784EA481B9484EB4A981572A8DF198F7', true);
                    // Encode Signature to Base64Url String
                    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
                    // Create JWT
                    $jwt = "ESUSU-Token" . $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

                    return [
                        "mobilenumber" => $operatorInfo['phone'],
                        "address" => $operatorInfo['addr1'],
                        "agentname" => $operatorInfo['lname'].' '.$operatorInfo['name'],
                        "terminal_id" => $terminalid,
                        "token" => $jwt
                    ];

                }

            }

        }else{

            return -2;

        }

    }



     /** ENDPOINT URL FOR TERMINAL INFO ARE:
     * 
     * api/posTerminalApi/terminalinfo: To view agent terminal info with the following required field:
     * 
     * {
     *  "terminal_serial" : "2H893800"
    *  }
     * 
     * */

    public function terminalInfo($parameter) {

        if(isset($parameter->terminal_serial)){

            $terminal_serial = $parameter->terminal_serial;

            if($terminal_serial === ""){

                return -1;

            }else{

                $checkTerm = $this->checkTerminalSerial($terminal_serial);
                $tidoperator = $checkTerm['tidoperator'];
                $institutionid = $checkTerm['merchant_id'];
                    
                $operatorInfo = $this->fetchTerminalOprt($tidoperator,$institutionid);

                if($checkTerm === 0 || $operatorInfo === 0){

                    return -3;

                }else{

                    return [
                        "agentcode" => $operatorInfo['id'],
                        "mobilenumber" => "0".substr($operatorInfo['phone'], -10),
                        "address" => $operatorInfo['state'].", ".$operatorInfo['country'],
                        "agentname" => $operatorInfo['lname'].' '.$operatorInfo['name'],
                        "terminal_id" => $checkTerm['terminal_id']
                    ];

                }

            }

        }else{

            return -2;

        }

    }




    /** ENDPOINT URL FOR TERMINAL INFO ARE:
     * 
     * api/posTerminalApi/terminalinfo: To view agent terminal info with the following required field:
     * 
     * {
     *  "terminal_serial" : "2H893800"
    *  }
     * 
     * */

    public function terminalCustomInfo($parameter) {

        if(isset($parameter->terminal_serial)){

            $terminal_serial = $parameter->terminal_serial;

            if($terminal_serial === ""){

                return -1;

            }else{

                $checkTerm = $this->checkTerminalSerial($terminal_serial);
                $institutionid = $checkTerm['merchant_id'];
                
                $instInfo = $this->fetchInstitutionById($institutionid);

                $image = "https://esusu.app/".$instInfo['institution_logo'];
                    
                $getCorrectImage = preg_match('/\s/',$image);
                    
                $detectValidImageFormat = ($getCorrectImage >= 1) ? 'https://esusu.app/img/esusu.africa.png' : $image;
                    
                $encryptedImage = ($myOutput['image'] == "" || $myOutput['image'] == "img/") ? file_get_contents('https://esusu.app/img/esusu.africa.png') : file_get_contents($detectValidImageFormat); 
                      
                // Encode the image string data into base64 
                $imageData = base64_encode($encryptedImage);
                
                if($checkTerm === 0){

                    return [
                        "address" => "No 27, Araromi Street, Shomolu Lagos, Nigeria",
                        "appname" => "EsusuTerminal",
                        "phone" => "08139200693",
                        "merchantname" => "Esusu Africa",
                        "appphone" => "08139200693",
                        "logo" => $imageData,
                        "appversion" => "v1",
                        "appurl" => "www.esusu.app"
                    ];

                }else{

                    return [
                        "responsecode" => "01",
                        "address" => $instInfo['location'],
                        "appname" => "EsusuTerminal",
                        "phone" => $instInfo['official_phone'],
                        "responsemessage" => "success",
                        "merchantname" => $checkTerm['merchant_name'],
                        "appphone" => "0".substr($checkTerm['merchant_phone_no'], -10),
                        "logo" => $imageData,
                        "appversion" => "v1",
                        "appurl" => "www.esusu.app"
                    ];

                }

            }

        }else{

            return -2;

        }

    }




    /** ENDPOINT URL FOR TERMINAL AIRTME PURCHASE ARE:
     * 
     * api/posTerminalApi/airtimepurchase: To buy airtime via agent terminal with the following required field:
     * 
     * {
     *  "amount":"50",
     *  "telco":"mtn",
     *  "reference": "389439774ss389888rrr43",
     *  "mobilenumber":"07066353204",
     *  "terminalid":"2HIG0106",
     *  "pin":"123456"
    *  }
     * 
     * */

    public function airtimePurchase($parameter) {

        if(isset($parameter->amount) && isset($parameter->telco) && isset($parameter->reference) && isset($parameter->mobilenumber) && isset($parameter->terminalid) && isset($parameter->pin)){

            $amount = $parameter->amount;
            $telco = $parameter->telco;
            $reference = $parameter->reference;
            $mobilenumber = $parameter->mobilenumber;
            $terminalid = $parameter->terminalid;
            $pin = $parameter->pin;

            if($amount === "" || $telco === "" || $reference === "" || $mobilenumber === "" || $terminalid === "" || $pin === ""){

                return -1;

            }else{
                
                $curl = curl_init();
                
                $checkTerm = $this->checkTerminal($terminalid);
                $tidoperator = $checkTerm['tidoperator'];
                $institutionid = $checkTerm['merchant_id'];
                    
                $operatorInfo = $this->fetchTerminalOperator($tidoperator,$pin,$institutionid);
                $balance = $operatorInfo['transfer_balance'];

                $sysCredentials = $this->db->fetchSystemSet();
                $icurrency = $sysCredentials['currency'];
                $rubbiesSecKey = $sysCredentials['rubbiesSecKey'];
                $commission = $amount * $sysCredentials['bp_commission'];
                $topup_amount = $amount - $commission;
                $remainBalance = $balance - $topup_amount;
                $transactionDateTime = date("Y-m-d h:i:s");
                
                //4 rows [0 - 4]
                $dataToProcess = $reference."|".$amount."|".$mobilenumber."|".$telco."|".$balance;
                    
                $mytxtstatus = 'Pending';
                    
                ($topup_amount > $balance) ? "" : $queryTxt = "INSERT INTO api_txtwaitinglist (userid, refid, mydata, status, date_time) VALUE(?, ?, ?, ?, ?)";
                ($topup_amount > $balance) ? "" : $this->db->insertPendingTransaction($queryTxt, $tidoperator, $reference, $dataToProcess, $mytxtstatus, $transactionDateTime);

                if($checkTerm === 0 || $operatorInfo === 0){

                    return -3;

                }elseif($balance < $topup_amount){

                    return -4;

                }else{
                    
                    //UPDATE USER BALANCE
                    ($topup_amount > $balance) ? "" : $query = "UPDATE user SET transfer_balance = '$remainBalance' WHERE id = '$tidoperator' AND created_by = '$institutionid'";
                    ($topup_amount > $balance) ? "" : $this->db->updateUserBal($query, $remainBalance);
                    
                    $searchApi = $this->fetchApi("rubies_ctairtimepurchase");
                    $api_url = $searchApi['api_url'];

                    curl_setopt_array($curl, array(
                    CURLOPT_URL => $api_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode([
                            'reference'=>$reference,
                            'amount'=>$amount,
                            'mobilenumber'=>$mobilenumber,
                            'telco'=>$telco
                        ]),
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: ".$rubbiesSecKey,
                        "Content-Type: application/json"
                    ),
                    ));
                    
                    ($topup_amount > $balance) ? "" : $response = curl_exec($curl);
                    ($topup_amount > $balance) ? "" : $rubbies_generate = json_decode($response, true);

                    if($rubbies_generate['responsecode'] == "00"){

                        $myref = $rubbies_generate['reference'];
                        $datetime = date("Y-m-d h:i:s");

                        //UPDATE WAITING TXT
                        $txtQuery = "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$tidoperator' AND refid = '$reference' AND status = '$mytxtstatus'";
                        $this->db->updateWaitingTxt($txtQuery, $response);
                        
                        //INSERT WALLET HISTORY
                        $query2 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->db->insertWalletHistory($query2, $institutionid, $reference, $mobilenumber, '', $amount, 'Debit', $icurrency, 'Airtime - POS', 'Airtime Topup for ' . $mobilenumber . ' with refid: ' . $myref . ' via POS', 'successful', $datetime, $tidoperator, $remainBalance);
                        $this->db->insertWalletHistory($query2, $institutionid, $reference, $mobilenumber, $commission, '', 'Credit', $icurrency, 'Commission - POS', 'Airtime Topup Commission for ' . $mobilenumber . ' with refid: ' . $myref . ' via POS', 'successful', $datetime, $tidoperator, $remainBalance);
            
                        return [
                            "reference"=>$myref,
                            "responsedatetime" => $rubbies_generate['responsedatetime'],
                            "responsemessage" => $rubbies_generate['responsemessage'],
                            "cbareference" => $rubbies_generate['cbareference'],
                            "draccount" => $rubbies_generate['draccount'],
                            "craccount" => $rubbies_generate['craccount']
                            ];

                    }else{

                        $myWaitingList = $this->fetchTxtWaitingList($tidoperator,$reference,$mytxtstatus);
                        $myWaitingData = $myWaitingList['mydata'];

                        $myParameter = (explode('|',$myWaitingData));
                        $defaultBalance = $myParameter[4];

                        //UPDATE USER BALANCE
                        $uQuery = "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$tidoperator' AND created_by = '$institutionid'";
                        $this->db->updateUserBal($uQuery, $defaultBalance);

                        //UPDATE WAITING TXT
                        $txtQuery = "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$tidoperator' AND refid = '$reference' AND status = '$mytxtstatus'";
                        $this->db->updateWaitingTxt($txtQuery, $response);

                        return -5;

                    }

                }

            }

        }else{

            return -2;

        }

    }




    /** ENDPOINT URL FOR TERMINAL MOBILE DATA PURCHASE ARE:
     * 
     * api/posTerminalApi/mobiledatapurchase: To buy mobile data bundle via agent terminal with the following required field:
     * 
     * {
     *  "amount":"50",
     *  "telco":"mtn",
     *  "product":"D-MFIN-5-200MB",
     *  "reference": "389439774ss389888rrr43",
     *  "mobilenumber":"07066353204",
     *  "terminalid":"2HIG0106",
     *  "pin":"123456"
    *  }
     * 
     * */

    public function mobileDataPurchase($parameter) {

        if(isset($parameter->amount) && isset($parameter->telco) && isset($parameter->product) && isset($parameter->reference) && isset($parameter->mobilenumber) && isset($parameter->terminalid) && isset($parameter->pin)){

            $amount = $parameter->amount;
            $telco = $parameter->telco;
            $product = $parameter->product;
            $reference = $parameter->reference;
            $mobilenumber = $parameter->mobilenumber;
            $terminalid = $parameter->terminalid;
            $pin = $parameter->pin;

            if($amount === "" || $telco === "" || $product === "" || $reference === "" || $mobilenumber === "" || $terminalid === "" || $pin === ""){

                return -1;

            }else{
                
                $curl = curl_init();
                
                $checkTerm = $this->checkTerminal($terminalid);
                $tidoperator = $checkTerm['tidoperator'];
                $institutionid = $checkTerm['merchant_id'];
                    
                $operatorInfo = $this->fetchTerminalOperator($tidoperator,$pin,$institutionid);
                $balance = $operatorInfo['transfer_balance'];

                $sysCredentials = $this->db->fetchSystemSet();
                $icurrency = $sysCredentials['currency'];
                $rubbiesSecKey = $sysCredentials['rubbiesSecKey'];
                $commission = $amount * $sysCredentials['bp_commission'];
                $topup_amount = $amount - $commission;
                $remainBalance = $balance - $topup_amount;
                $transactionDateTime = date("Y-m-d h:i:s");
                
                //5 rows [0 - 5]
                $dataToProcess = $reference."|".$amount."|".$product."|".$mobilenumber."|".$telco."|".$balance;
                    
                $mytxtstatus = 'Pending';
                    
                ($topup_amount > $balance) ? "" : $queryTxt = "INSERT INTO api_txtwaitinglist (userid, refid, mydata, status, date_time) VALUE(?, ?, ?, ?, ?)";
                ($topup_amount > $balance) ? "" : $this->db->insertPendingTransaction($queryTxt, $tidoperator, $reference, $dataToProcess, $mytxtstatus, $transactionDateTime);

                if($checkTerm === 0 || $operatorInfo === 0){

                    return -3;

                }elseif($balance < $topup_amount){

                    return -4;

                }else{

                    //UPDATE USER BALANCE
                    ($topup_amount > $balance) ? "" : $query = "UPDATE user SET transfer_balance = '$remainBalance' WHERE id = '$tidoperator' AND created_by = '$institutionid'";
                    ($topup_amount > $balance) ? "" : $this->db->updateUserBal($query, $remainBalance);
                    
                    $searchApi = $this->fetchApi("rubies_ctmobiledatapurchase");
                    $api_url = $searchApi['api_url'];

                    curl_setopt_array($curl, array(
                    CURLOPT_URL => $api_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode([
                            'reference'=>$reference,
                            'amount'=>$amount,
                            'productcode'=>$product,
                            'mobilenumber'=>$mobilenumber,
                            'telco'=>$telco
                        ]),
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: ".$rubbiesSecKey,
                        "Content-Type: application/json"
                    ),
                    ));
                    
                    ($topup_amount > $balance) ? "" : $response = curl_exec($curl);
                    ($topup_amount > $balance) ? "" : $rubbies_generate = json_decode($response, true);

                    if($rubbies_generate['responsecode'] == "00"){

                        $myref = $rubbies_generate['reference'];
                        $datetime = date("Y-m-d h:i:s");

                        //UPDATE WAITING TXT
                        $txtQuery = "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$tidoperator' AND refid = '$reference' AND status = '$mytxtstatus'";
                        $this->db->updateWaitingTxt($txtQuery, $response);

                        //INSERT WALLET HISTORY
                        $query2 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->db->insertWalletHistory($query2, $institutionid, $reference, $mobilenumber, '', $amount, 'Debit', $icurrency, 'Databundle - POS', $telco . ' Databundle Purchase for ' . $product . ' with refid: ' . $myref . ' via POS', 'successful', $datetime, $tidoperator, $remainBalance);
                        $this->db->insertWalletHistory($query2, $institutionid, $reference, $mobilenumber, $commission, '', 'Credit', $icurrency, 'Commission - POS', $telco . ' Databundle Purchase Commission for ' . $product . ' with refid: ' . $myref . ' via POS', 'successful', $datetime, $tidoperator, $remainBalance);
            
                        return [
                            "reference"=>$myref,
                            "responsedatetime" => $rubbies_generate['responsedatetime'],
                            "responsemessage" => $rubbies_generate['responsemessage'],
                            "cbareference" => $rubbies_generate['cbareference'],
                            "draccount" => $rubbies_generate['draccount'],
                            "craccount" => $rubbies_generate['craccount']
                            ];

                    }else{
                        
                        $myWaitingList = $this->fetchTxtWaitingList($tidoperator,$reference,$mytxtstatus);
                        $myWaitingData = $myWaitingList['mydata'];

                        $myParameter = (explode('|',$myWaitingData));
                        $defaultBalance = $myParameter[5];

                        //UPDATE USER BALANCE
                        $uQuery = "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$tidoperator' AND created_by = '$institutionid'";
                        $this->db->updateUserBal($uQuery, $defaultBalance);

                        //UPDATE WAITING TXT
                        $txtQuery = "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$tidoperator' AND refid = '$reference' AND status = '$mytxtstatus'";
                        $this->db->updateWaitingTxt($txtQuery, $response);

                        return -5;

                    }

                }

            }

        }else{

            return -2;

        }

    }





    /** ENDPOINT URL FOR TERMINAL MOBILE DATA PRODUCT ARE:
     * 
     * api/posTerminalApi/mobiledataproduct: To list mobile data product via agent terminal with the following required field:
     * 
     * {
     *  "request":"mobiledata"
    *  }
     * 
     * */

    public function mobileDataProduct($parameter) {

        if(isset($parameter->request)){

            $request = $parameter->request;
    
            if($request === ""){

                return -1;

            }else{

                $curl = curl_init();
                
                $sysCredentials = $this->db->fetchSystemSet();
                $rubbiesSecKey = $sysCredentials['rubbiesSecKey'];
                
                $searchApi = $this->fetchApi("rubies_ctmobiledataproduct");
                $api_url = $searchApi['api_url'];

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $api_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode([
                            'request'=>$request
                        ]),
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: ".$rubbiesSecKey,
                        "Content-Type: application/json"
                    ),
                    ));
                    
                $response = curl_exec($curl);
                $rubbies_generate = json_decode($response);

                return $response;
                

            }

        }else{

            return -2;

        }

    }




    /** ENDPOINT URL FOR TERMINAL BANK LIST ARE:
     * 
     * api/posTerminalApi/banklist: To view all banklist via agent terminal with the following required field:
     * 
     * {
     *  "terminalid":"2HIG0106"
    *  }
     * 
     * */

    public function bankList($parameter) {

        if(isset($parameter->terminalid)){

            $terminalid = $parameter->terminalid;
    
            if($terminalid === ""){

                return -1;

            }else{

                $curl = curl_init();
                
                $sysCredentials = $this->db->fetchSystemSet();
                $rubbiesSecKey = $sysCredentials['rubbiesSecKey'];
                
                $searchApi = $this->fetchApi("rubies_listnipbank");
                $api_url = $searchApi['api_url'];

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $api_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode([
                            'request'=>"banklist"
                        ]),
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: ".$rubbiesSecKey,
                        "Content-Type: application/json"
                    ),
                    ));
                    
                $response = curl_exec($curl);
                $rubbies_generate = json_decode($response);
                
                return $rubbies_generate->banklist;

            }

        }else{

            return -2;

        }

    }




    /** ENDPOINT URL FOR NAME ENQUIRY ON TERMINAL ARE:
     * 
     * api/posTerminalApi/nameenquiry: To verify transfer recipient name via agent terminal with the following required field:
     * 
     * {
     *  "accountnumber":"0012226121",
     *  "bankcode":"000013"
    *  }
     * 
     * */

    public function nameEnquiry($parameter) {

        if(isset($parameter->accountnumber) && isset($parameter->bankcode)){

            $accountnumber = $parameter->accountnumber;
            $bankcode = $parameter->bankcode;
    
            if($accountnumber === "" || $bankcode === ""){

                return -1;

            }else{

                $curl = curl_init();
                
                $sysCredentials = $this->db->fetchSystemSet();
                $rubbiesSecKey = $sysCredentials['rubbiesSecKey'];
                
                $searchApi = $this->fetchApi("rubies_InterbankNameEnquiry");
                $api_url = $searchApi['api_url'];

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $api_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode([
                            'accountnumber'=>$accountnumber,
                            'bankcode'=>$bankcode
                        ]),
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: ".$rubbiesSecKey,
                        "Content-Type: application/json"
                    ),
                    ));
                    
                $response = curl_exec($curl);
                $rubbies_generate = json_decode($response, true);

                if($rubbies_generate['responsecode'] == "00"){

                    return [
                        "responsecode" => $rubbies_generate['responsecode'],
                        "accountnumber" => $rubbies_generate['accountnumber'],
                        "accountname" => $rubbies_generate['accountname'],
                        "kyc" => $rubbies_generate['kyc'],
                        "responsemessage" => "successful",
                        "sessionid" => $rubbies_generate['sessionid'],
                        "bvn" => $rubbies_generate['bvn'],
                        "bankcode" => $rubbies_generate['bankcode']
                    ];

                }else{

                    return -5;

                }

            }

        }else{

            return -2;

        }

    }





    /** ENDPOINT URL FOR TERMINAL FUND TRANSFER ARE:
     * 
     * api/posTerminalApi/fundtransfer: To make fund transfer via agent terminal with the following required field:
     * 
     * {
     *  "amount":"50",
     *  "narration": "Deposit by 037030303 ",
     *  "craccountname": "Temitope Olakunle",
     *  "bankname": "GTBANK",
     *  "craccount": "0012226121",
     *  "bankcode": "000013",
     *  "rrn": "389439774ss3898843",
     *  "mobilenumber":"07030393343",
     *  "terminalid":"2HIG0106",
     *  "pin":"123456"
    *  }
     * 
     * */

    public function fundTransfer($parameter) {

        if(isset($parameter->amount) && isset($parameter->narration) && isset($parameter->craccountname) && isset($parameter->bankname) && isset($parameter->craccount) && isset($parameter->bankcode) && isset($parameter->rrn) && isset($parameter->mobilenumber) && isset($parameter->terminalid) && isset($parameter->pin)){

            $amount = $parameter->amount;
            $narration = $parameter->narration;
            $craccountname = $parameter->craccountname;
            $bankname = $parameter->bankname;
            $craccount = $parameter->craccount;
            $bankcode = $parameter->bankcode;
            $rrn = $parameter->rrn;
            $mobilenumber = $parameter->mobilenumber;
            $terminalid = $parameter->terminalid;
            $pin = $parameter->pin;

            if($amount === "" || $narration === "" || $craccountname === "" || $bankname === "" || $craccount === "" || $bankcode === "" || $rrn === "" || $mobilenumber === "" || $terminalid === "" || $pin === ""){

                return -1;

            }else{
                
                $curl = curl_init();
                
                $checkTerm = $this->checkTerminal($terminalid);
                $tidoperator = $checkTerm['tidoperator'];
                $aggregatorid = $checkTerm['initiatedBy'];
                $institutionid = $checkTerm['merchant_id'];
                
                //Check Operator
                $operatorInfo = $this->fetchTerminalOperator($tidoperator,$pin,$institutionid);
                $balance = $operatorInfo['transfer_balance'];
                $operatorName = $operatorInfo['lname'].' '.$operatorInfo['name'];
                $operatorVA = $operatorInfo['virtual_acctno'];

                //Check Aggregator
                $operatorAggrInfo = $this->fetchTerminalOprt($aggregatorid,$institutionid);
                $aggrBalance = $operatorAggrInfo['transfer_balance'];

                $sysCredentials = $this->db->fetchSystemSet();
                $icurrency = $sysCredentials['currency'];
                $rubbiesSecKey = $sysCredentials['rubbiesSecKey'];

                $sysMaintenance = $this->fetchMaintenanceSettings($institutionid);
                $charges = ($sysMaintenance === 0) ? $sysCredentials['transfer_charges'] : $sysMaintenance['bank_transfer_charges'];
                $commission = ($sysMaintenance === 0) ? 0 : $sysMaintenance['bank_transfer_commission'];
                $amtWithCharges = $amount + $charges;
                $remainOprtBalance = $balance - $amtWithCharges;
                $remainAggrBalance = $aggrBalance + $commission;
                $transactionDateTime = date("Y-m-d h:i:s");

                //Fetch Operator Transfer Limit
                $myVA = $this->fetchVAByAcctNo($operatorVA);
                $tLimit = $myVA['transferLimitPerTrans'];
                
                //8 rows [0 - 8]
                $dataToProcess = $rrn."|".$amount."|".$narration."|".$craccountname."|".$bankname."|".$operatorName."|".$craccount."|".$bankcode."|".$balance;
                    
                $mytxtstatus = 'Pending';
                    
                ($amtWithCharges > $balance) ? "" : $queryTxt = "INSERT INTO api_txtwaitinglist (userid, refid, mydata, status, date_time) VALUE(?, ?, ?, ?, ?)";
                ($amtWithCharges > $balance) ? "" : $this->db->insertPendingTransaction($queryTxt, $tidoperator, $rrn, $dataToProcess, $mytxtstatus, $transactionDateTime);

                if($checkTerm === 0 || $operatorInfo === 0){

                    return -3;

                }elseif($amtWithCharges > $balance){

                    return -4;

                }elseif($amount > $tLimit){

                    return -6;

                }else{

                    //UPDATE USER BALANCE
                    ($amtWithCharges > $balance) ? "" : $query = "UPDATE user SET transfer_balance = '$remainOprtBalance' WHERE id = '$tidoperator' AND created_by = '$institutionid'";
                    ($amtWithCharges > $balance) ? "" : $this->db->updateUserBal($query, $remainOprtBalance);

                    $searchApi = $this->fetchApi("rubies_niptransfer");
                    $api_url = $searchApi['api_url'];

                    curl_setopt_array($curl, array(
                    CURLOPT_URL => $api_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode([
                            'reference'=>$rrn,
                            'amount'=>$amount,
                            'narration'=>$narration,
                            'craccountname'=>$craccountname,
                            'bankname'=>$bankname,
                            'draccountname'=>$operatorName,
                            'craccount'=>$craccount,
                            'bankcode'=>$bankcode
                        ]),
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: ".$rubbiesSecKey,
                        "Content-Type: application/json"
                    ),
                    ));
                    
                    ($amtWithCharges > $balance) ? "" : $response = curl_exec($curl);
                    ($amtWithCharges > $balance) ? "" : $rubbies_generate = json_decode($response, true);

                    if($rubbies_generate['responsecode'] == "00"){

                        $gatewayResponse = $rubbies_generate['nibssresponsemessage'];
                        $recipient = $craccount.', '.$craccountname.', '.$bankname;

                        //UPDATE WAITING TXT
                        $txtQuery = "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$tidoperator' AND refid = '$rrn' AND status = '$mytxtstatus'";
                        $this->db->updateWaitingTxt($txtQuery, $response);

                        //UPDATE AGGREGATOR BALANCE IF COMMISSION IS NOT EQUAL ZERO
                        ($commission === "0" || $tidoperator === $aggregatorid) ? "" : $query = "UPDATE user SET transfer_balance = '$remainAggrBalance' WHERE id = '$aggregatorid' AND created_by = '$institutionid'";
                        ($commission === "0" || $tidoperator === $aggregatorid) ? "" : $this->db->updateUserBal($query, $remainAggrBalance);

                        //INSERT WALLET HISTORY
                        $query2 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->db->insertWalletHistory($query2, $institutionid, $rrn, $recipient, '', $amount, 'Debit', $icurrency, 'BANK_TRANSFER', 'Transfer to ' . $recipient . ' via POS', 'successful', $transactionDateTime, $tidoperator, $remainOprtBalance);
                        $this->db->insertWalletHistory($query2, $institutionid, $rrn, $recipient, '', $charges, 'Debit', $icurrency, 'Charges', 'Transfer to ' . $recipient . ', Gateway Response: ' . $gatewayResponse . ' via POS', 'successful', $transactionDateTime, $tidoperator, $remainOprtBalance);
                        ($commission === "0" || $tidoperator === $aggregatorid) ? "" : $this->db->insertWalletHistory($query2, $institutionid, $rrn, $recipient, $commission, '', 'Credit', $icurrency, 'TRANSFER_COMMISSION', 'Transfer to ' . $recipient . ' via POS', 'successful', $transactionDateTime, $aggregatorid, $remainAggrBalance);

                        return [
                            "responsecode" => $rubbies_generate['responsecode'],
                            "amount" => $rubbies_generate['amount'],
                            "mobilenumber" => $mobilenumber,
                            "nibssresponsemessage" => $gatewayResponse,
                            "cbareference" => "",
                            "responsemessage" => $rubbies_generate['responsemessage'],
                            "terminalid" => $terminalid,
                            "draccount" => $rubbies_generate['draccount'],
                            "source" => "POS",
                            "sessionid" => $rubbies_generate['sessionid'],
                            "craccount" => $rubbies_generate['craccount'],
                            "rrn" => $rrn,
                            "reference" => $terminalid.$rrn,
                            "tcode" => $rubbies_generate['tcode'],
                            "nibsscode" => $rubbies_generate['nibsscode'],
                            "pin" => $pin,
                            "narration" => $terminalid . "/" . $rubbies_generate['narration'],
                            "customerid" => $terminalid,
                            "craccountname" => $rubbies_generate['craccountname'],
                            "bankname" => $rubbies_generate['bankname'],
                            "draccountname" => $rubbies_generate['draccountname'],
                            "bankcode" => $rubbies_generate['bankcode'],
                            "username" => $terminalid
                            ];

                    }else{

                        $myWaitingList = $this->fetchTxtWaitingList($tidoperator,$rrn,$mytxtstatus);
                        $myWaitingData = $myWaitingList['mydata'];

                        $myParameter = (explode('|',$myWaitingData));
                        $defaultBalance = $myParameter[8];

                        //UPDATE USER BALANCE
                        $uQuery = "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$tidoperator' AND created_by = '$institutionid'";
                        $this->db->updateUserBal($uQuery, $defaultBalance);

                        //UPDATE WAITING TXT
                        $txtQuery = "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$tidoperator' AND refid = '$rrn' AND status = '$mytxtstatus'";
                        $this->db->updateWaitingTxt($txtQuery, $response);

                        return -5;

                    }

                }

            }

        }else{

            return -2;

        }

    }




    /** ENDPOINT URL FOR TERMINAL BALANCE ENQUIRY ARE:
     * 
     * api/posTerminalApi/accountbalance: To view agent balance on terminal with the following required field:
     * 
     * {
     *  "terminalid" : "2H893800",
     *  "pin" : "123456"
    *  }
     * 
     * */

    public function accountBalance($parameter) {

        if(isset($parameter->terminalid) && isset($parameter->pin)){

            $terminalid = $parameter->terminalid;
            $pin = $parameter->pin;

            if($terminalid === "" || $pin === ""){

                return -1;

            }else{

                $checkTerm = $this->checkTerminal($terminalid);
                $tidoperator = $checkTerm['tidoperator'];
                $institutionid = $checkTerm['merchant_id'];
                
                $operatorInfo = $this->fetchTerminalOperator($tidoperator,$pin,$institutionid);

                if($checkTerm === 0 || $operatorInfo === 0){

                    return -3;

                }else{

                    $uniqueRef = sprintf( '%04x%04x%04x-%04x-%04x-%04x%04x%04x',mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),mt_rand( 0, 0xffff ),mt_rand( 0, 0x0C2f ) | 0x4000,mt_rand( 0, 0x3fff ) | 0x8000,mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B ));

                    return [
                        "customer_type" => "I",
                        "account_type" => "SAVINGS",
                        "daily_txn_sum_dr" => "0",
                        "month_txn_count_dr" => "0",
                        "overdraft_rate" => "0",
                        "overdraft" => "0.00",
                        "uniqueref" => $uniqueRef,
                        "overdraftbalancelimit" => $operatorInfo['transfer_balance'],
                        "account_status" => "ACTIVE",
                        "alw_cr" => "Y",
                        "groupcode" => "",
                        "availablebalance" => $operatorInfo['transfer_balance'],
                        "agentcode" => "",
                        "daily_txn_count_cr" => "0",
                        "minimum_balance" => "0.00",
                        "accountname" => $operatorInfo['lname'].' '.$operatorInfo['name'].' '.$operatorInfo['mname'],
                        "overdraft_expiry" => "",
                        "date_closed" => "",
                        "compbalance" => $operatorInfo['transfer_balance'],
                        "month_txn_sum_cr" => "0",
                        "ccy" => "NGN",
                        "id" => $operatorInfo['userid'],
                        "daily_txn_sum_cr" => "0.00",
                        "responsecode" => "00",
                        "month_txn_count_cr" => "0",
                        "overdraft_disabled" => "",
                        "product" => "",
                        "overdraft_start" => "",
                        "glaccountname" => "SAVINGS",
                        "month_atm_count" => "0",
                        "balance_uncleared" => "0.00",
                        "month_trf_count" => "0",
                        "date_opened" => $operatorInfo['date_time'],
                        "alw_dr" => "Y",
                        "daily_txn_count_dr" => "0",
                        "overdraft_status" => "N",
                        "glaccount" => $operatorInfo['id'],
                        "accountno" => $operatorInfo['virtual_acctno'],
                        "month_txn_sum_dr" => "0",
                        "custid" => $operatorInfo['userid'],
                    ];

                }

            }

        }else{

            return -2;

        }

    }




    /** ENDPOINT URL FOR TERMINAL BILLERS LISTING ARE:
     * 
     * api/posTerminalApi/billers: To view all billers via agent terminal with the following required field:
     * 
     * {
     *  "request":"billers"
    *  }
     * 
     * */

    public function billers($parameter) {

        if(isset($parameter->request)){

            $request = $parameter->request;
    
            if($request === ""){

                return -1;

            }else{

                $curl = curl_init();
                
                $sysCredentials = $this->db->fetchSystemSet();
                $rubbiesSecKey = $sysCredentials['rubbiesSecKey'];
                
                //$searchApi = $this->fetchApi("rubies_listnipbank");
                $api_url = "https://5igp2ofnzc.execute-api.us-west-2.amazonaws.com/prod/billers"; //$searchApi['api_url'];

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $api_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode([
                            'request'=>"billers"
                        ]),
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: eyJjdHkiOiJ0ZXh0XC9wbGFpbiIsImFsZyI6IkhTNTEyIn0.eyJwaW5jb2RlIjoiRDFJREtHNmsvUndqdjBJakphSXFqT2dPWU5kbWZ2V010MFQvaGdraW5HN2xNbGMxMFJKS0lkVWRPOEszdGd3U2FISGNqdWVrUEM0OWFDQTg3S0I4RXc9PSIsImV4cGlyeWRhdGUiOiIyMDIwLTA5LTEzVDEzOjQyOjA5LjY1NVoiLCJtb2JpbGVudW1iZXIiOiIwNzA2NjM1MzIwNCIsImFkZHJlc3MiOiJMQUdPUyBOSUdFUklBIiwiZHJhY2NvdW50IjoiMDAwMDAwMDAwOCIsInNvdXJjZSI6IlBPUyIsInZlcnNpb24iOiIxIiwiaXNzdWVkYXRlIjoiMjAyMC0wOS0xM1QwMTo0MjowOS42NTVaIiwiYWdlbnRjb2RlIjoiVE9QU1kiLCJhZ2VudG5pY2tuYW1lIjoiVGVzdCBEZXZpY2UgUDEwMCIsImFnZW50bmFtZSI6IlRPUEUgT0xBS1VOTEUiLCJ0cmFuc2ZlcmxpbWl0IjoiMTAwIiwidHJhbnNmZXJjb2RlIjoiQTA1IiwidGVybWluYWxfaWQiOiIySElHMDEwNiIsInVzZXJuYW1lIjoiQUdFTkNZUE9TIn0.7LH-i4HevqqSsgHps_6SyOt5oxwLmTuxTQiANQhL7zDQX_tyTPQqRiqxh_TswR9bEtbiL7d9tPN8VTraFDX_5w",
                        "Content-Type: application/json"
                    ),
                    ));
                    
                $response = curl_exec($curl);
                $rubbies_generate = json_decode($response);
                
                return $response;
                
            }

        }else{

            return -2;

        }

    }




    /** ENDPOINT URL FOR TERMINAL BILLERS VERIFICATION ARE:
     * 
     * api/posTerminalApi/billerverification: To verify billers via agent terminal with the following required field:
     * 
     * {
     *  "billercode":"EKO_ELECT_PREPAID",
     *  "billercustomerid":"0101150353221"
    *  }
     * 
     * */

    public function billerVerification($parameter) {

        if(isset($parameter->billercode) && isset($parameter->billercustomerid)){

            $billercode = $parameter->billercode;
            $billercustomerid = $parameter->billercustomerid;
    
            if($billercode === "" || $billercustomerid === ""){

                return -1;

            }else{

                $curl = curl_init();
                
                $sysCredentials = $this->db->fetchSystemSet();
                $rubbiesSecKey = $sysCredentials['rubbiesSecKey'];
                
                $searchApi = $this->fetchApi("rubies_billerverification");
                $api_url = $searchApi['api_url'];

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $api_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode([
                            'billercode'=>$billercode,
                            'billercustomerid'=>$billercustomerid
                        ]),
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: ".$rubbiesSecKey,
                        "Content-Type: application/json"
                    ),
                    ));
                    
                $response = curl_exec($curl);
                $rubbies_generate = json_decode($response);

                if($rubbies_generate['responsecode'] == "00"){

                    return [
                        "responsecode" => $rubbies_generate['responsecode'],
                        "responsemessage" => $rubbies_generate['responsemessage'],
                        "name" => $rubbies_generate['name'],
                        "customername" => $rubbies_generate['customername']
                    ];

                }else{

                    return -5;

                }
                
            }

        }else{

            return -2;

        }

    }





    /** ENDPOINT URL FOR TERMINAL BILL PAYMENT ARE:
     * 
     * api/posTerminalApi/billpayment: To make bill payment via agent terminal with the following required field:
     * 
     * {
     *  "reference": "974977794972de43j804433",
     *  "billercustomerid": "0101150353221",
     *  "productcode": "PREPAID",
     *  "amount": "1000",
     *  "mobilenumber": "07066353204",
     *  "name": "TEMITOPE OLAKUNLE",
     *  "billercode": "EKO_ELECT_PREPAID",
     *  "terminalid":"2HIG0106",
     *  "pin":"123456"
    *  }
     * 
     * */

    public function billPayment($parameter) {

        if(isset($parameter->reference) && isset($parameter->billercustomerid) && isset($parameter->productcode) && isset($parameter->amount) && isset($parameter->mobilenumber) && isset($parameter->name) && isset($parameter->billercode) && isset($parameter->terminalid) && isset($parameter->pin)){

            $reference = $parameter->reference;
            $billercustomerid = $parameter->billercustomerid;
            $productcode = $parameter->productcode;
            $amount = $parameter->amount;
            $mobilenumber = $parameter->mobilenumber;
            $name = $parameter->name;
            $billercode = $parameter->billercode;            
            $terminalid = $parameter->terminalid;
            $pin = $parameter->pin;

            if($reference === "" || $billercustomerid === "" || $productcode === "" || $amount === "" || $mobilenumber === "" || $name === "" || $billercode === "" || $terminalid === "" || $pin === ""){

                return -1;

            }else{
                
                $curl = curl_init();
                
                $checkTerm = $this->checkTerminal($terminalid);
                $tidoperator = $checkTerm['tidoperator'];
                $institutionid = $checkTerm['merchant_id'];
                
                //Check Operator
                $operatorInfo = $this->fetchTerminalOperator($tidoperator,$pin,$institutionid);
                $balance = $operatorInfo['transfer_balance'];

                $sysCredentials = $this->db->fetchSystemSet();
                $icurrency = $sysCredentials['currency'];
                $rubbiesSecKey = $sysCredentials['rubbiesSecKey'];
                $commission = $amount * $sysCredentials['bp_commission'];
                $topup_amount = $amount - $commission;
                $remainBalance = $balance - $topup_amount;
                $transactionDateTime = date("Y-m-d h:i:s");
                
                //7 rows [0 - 7]
                $dataToProcess = $reference."|".$billercustomerid."|".$productcode."|".$amount."|".$mobilenumber."|".$name."|".$billercode."|".$balance;
                    
                $mytxtstatus = 'Pending';
                    
                ($topup_amount > $balance) ? "" : $queryTxt = "INSERT INTO api_txtwaitinglist (userid, refid, mydata, status, date_time) VALUE(?, ?, ?, ?, ?)";
                ($topup_amount > $balance) ? "" : $this->db->insertPendingTransaction($queryTxt, $tidoperator, $rrn, $dataToProcess, $mytxtstatus, $transactionDateTime);

                if($checkTerm === 0 || $operatorInfo === 0){

                    return -3;

                }elseif($balance < $topup_amount){

                    return -4;

                }else{
                    
                    //UPDATE USER BALANCE
                    ($topup_amount > $balance) ? "" : $query = "UPDATE user SET transfer_balance = '$remainBalance' WHERE id = '$tidoperator' AND created_by = '$institutionid'";
                    ($topup_amount > $balance) ? "" : $this->db->updateUserBal($query, $remainBalance);
                    
                    $searchApi = $this->fetchApi("rubies_billerpurchase");
                    $api_url = $searchApi['api_url'];

                    curl_setopt_array($curl, array(
                    CURLOPT_URL => $api_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode([
                            'reference'=>$reference,
                            'billercustomerid'=>$billercustomerid,
                            'productcode'=>$productcode,
                            'amount'=>$amount,
                            'mobilenumber'=>$mobilenumber,
                            'name'=>$name,
                            'billercode'=>$billercode
                        ]),
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: ".$rubbiesSecKey,
                        "Content-Type: application/json"
                    ),
                    ));
                    
                    ($topup_amount > $balance) ? "" : $response = curl_exec($curl);
                    ($topup_amount > $balance) ? "" : $rubbies_generate = json_decode($response, true);

                    if($rubbies_generate['responsecode'] == "00"){

                        $transactionDateTime = date("Y-m-d h:i:s");

                        //UPDATE WAITING TXT
                        $txtQuery = "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$tidoperator' AND refid = '$reference' AND status = '$mytxtstatus'";
                        $this->db->updateWaitingTxt($txtQuery, $response);

                        //INSERT WALLET HISTORY
                        $query2 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $this->db->insertWalletHistory($query2, $institutionid, $reference, $billercustomerid, '', $amount, 'Debit', $icurrency, 'Billpayment - POS', "$response", 'successful', $transactionDateTime, $tidoperator, $remainBalance);
                        $this->db->insertWalletHistory($query2, $institutionid, $reference, $billercustomerid, $commission, '', 'Credit', $icurrency, 'Billpayment - POS', "$response", 'successful', $transactionDateTime, $tidoperator, $remainBalance);
            
                        return $response;

                    }else{
                        
                        $myWaitingList = $this->fetchTxtWaitingList($tidoperator,$reference,$mytxtstatus);
                        $myWaitingData = $myWaitingList['mydata'];

                        $myParameter = (explode('|',$myWaitingData));
                        $defaultBalance = $myParameter[7];

                        //UPDATE USER BALANCE
                        $uQuery = "UPDATE user SET transfer_balance = '$defaultBalance' WHERE id = '$tidoperator' AND created_by = '$institutionid'";
                        $this->db->updateUserBal($uQuery, $defaultBalance);

                        //UPDATE WAITING TXT
                        $txtQuery = "UPDATE api_txtwaitinglist SET status = '$response' WHERE userid = '$tidoperator' AND refid = '$reference' AND status = '$mytxtstatus'";
                        $this->db->updateWaitingTxt($txtQuery, $response);

                        return -5;

                    }

                }

            }

        }else{

            return -2;

        }

    }





    /** ENDPOINT URL FOR TERMINAL TRANSACTION LIST ARE:
     * 
     * api/posTerminalApi/transactionlist: To list all transaction via agent terminal with the following required field:
     * 
     * {
     *  "terminalid":"2HIG0106"
    *  }
     * 
     * */

    public function transactionList($parameter) {

        if(isset($parameter->terminalid)){

            $terminalid = $parameter->terminalid;
    
            if($terminalid === ""){

                return -1;

            }else{

                $checkTerm = $this->checkTerminal($terminalid);
                $tidoperator = $checkTerm['tidoperator'];
                $aggregator = $checkTerm['initiatedBy'];
                $institutionid = $checkTerm['merchant_id'];
                
                //Check Operator
                $operatorInfo = $this->fetchTerminalOprt($tidoperator,$institutionid);

                //Check Aggr
                $aggrInfo = $this->fetchTerminalOprt($aggregator,$institutionid);

                if($checkTerm === 0 || $operatorInfo === 0){

                    return -3;

                }else{

                    $query = "SELECT * FROM wallet_history WHERE initiator = ? AND (paymenttype = 'POS' OR paymenttype = 'BANK_TRANSFER' OR paymenttype = 'Airtime - POS' OR paymenttype = 'Databundle - POS' OR paymenttype = 'Billpayment - POS') ORDER BY id DESC LIMIT 5";

                    $output = $this->db->fetchAll($query, $tidoperator);
                    
                    if($output >= 1){
                        
                        for($i = 0; $i <= $output; $i++){
                                
                            foreach($output as $putEntry => $key){
                                
                                $output2[$i] = [
                                    "amount" => ($key['credit'] == "") ? $key['debit'] : $key['credit'],
                                    "counterpartyservice" => ($key['paymenttype'] == "POS" ? "PURCHASE" : ($key['paymenttype'] == "BANK_TRANSFER" ? "BANK TRANSFER" : ($key['paymenttype'] == "Airtime - POS" ? "AIRTIME" : $key['transaction_type']))),
                                    "txndate" => date("Y-m-d", strtotime($key['date_time'])),
                                    "counterpartybankcode" => "",
                                    "contractref" => $key['refid'],
                                    "paymentref" => $terminalid.'-'.$key['refid'],
                                    "eventdate" => $key['date_time'],
                                    "counterpartyaccount" => $operatorInfo['virtual_acctno'],
                                    "drcr" => ($key['credit'] == "") ? "D" : "C",
                                    "counterpartybank" => $checkTerm['merchant_name'],
                                    "counterpartychannel" => ($key['paymenttype'] == "POS" ? "POS" : ($key['paymenttype'] == "BANK_TRANSFER" ? "NIP" : ($key['paymenttype'] == "Airtime - POS" ? "AIRTIME" : "INTERNAL"))),
                                    "accountname" => $operatorInfo['lname'].' '.$operatorInfo['name'].' '.$operatorInfo['mname'],
                                    "trntype" => $key['paymenttype'],
                                    "narration" => $key['remark'],
                                    "counterparty" => $aggrInfo['lname'].' '.$aggrInfo['name'],
                                    "id" => $key['id'],
                                    "account" => $key['initiator']
                                ];

                                $i++;
                            }
                            return $output2;
                            
                        }
                        
                    }else{
                        
                        return -5;
                        
                    }

                }

            }

        }else{

            return -2;

        }

    }

}
?>