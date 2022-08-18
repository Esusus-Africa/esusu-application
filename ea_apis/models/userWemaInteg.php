<?php

class wemaBankVA extends User {

    /** ENDPOINT URL TO RECEIVE TRANSACTION NOTIFICATION:
     * 
     * api/wemaBank/transNotification: Receive transaction notification on each wema bank reserved account with the following required field:
     * 
     * {
     *  "originatoraccountnumber":"string",
     *  "amount":"string",
     *  "originatorname":"string",
     *  "narration":"string",
     *  "craccountname":"string",
     *  "paymentreference":"string",
     *  "bankname":"string",
     *  "sessionid":"string",
     *  "craccount":"string",
     *  "bankcode":"string"
    *  }
     * 
     * */
    public function transNotif($parameter) {

        if(isset($parameter->originatoraccountnumber) && isset($parameter->amount) && isset($parameter->originatorname) && isset($parameter->narration) && isset($parameter->craccountname) && isset($parameter->paymentreference) && isset($parameter->bankname) && isset($parameter->sessionid) && isset($parameter->craccount) && isset($parameter->bankcode)){
            
            $originatoraccountnumber = $parameter->originatoraccountnumber;
            $amount = $parameter->amount;
            $originatorname = $parameter->originatorname;
            $narration = $parameter->narration;
            $craccountname = $parameter->craccountname;
            $paymentreference = $parameter->paymentreference;
            $bankname = $parameter->bankname;
            $sessionId = $parameter->sessionid;
            $craccount = $parameter->craccount;
            $bankcode = $parameter->bankcode;

            $recipient = "From:- ".$originatorname;
            $recipient .= ", Account Number: ".$originatoraccountnumber;
            $recipient .= ", Bank Name: ".$bankname;

            $checkIndepotent1 = $this->fetchWalletHistoryByRefId($sessionId);
            $checkIndepotent2 = $this->fetchPoolHistoryByRefId($sessionId);
            $checkIndepotent3 = $this->fetchTillWHistoryByRefId($sessionId);
            
            //CONFIRM RESERVED ACCOUNT NUMBER
            $verifyVA = $this->fetchVAByAcctNo($craccount);
            $verifyPA = $this->fetchPoolAcctByAcctNo($craccount);
            $verifyTA = $this->fetchTillVAByAcctNo($craccount);

            if($checkIndepotent1 != 0 || $checkIndepotent2 != 0 || $checkIndepotent3 != 0){

                return -1;

            }elseif($originatoraccountnumber == "" || $amount == "" || $originatorname == "" || $craccountname == "" || $paymentreference == "" || $bankname == "" || $sessionId == "" || $craccount == "" || $bankcode == ""){

                return -2;

            }elseif($verifyVA === 0 && $verifyPA === 0 && $verifyTA === 0){
        
                return -3;
                
            }
            else{

                $myId = ($verifyVA != "0" && $verifyPA == "0" && $verifyTA == "0" ? $verifyVA['userid'] : ($verifyVA == "0" && $verifyPA != "0" && $verifyTA == "0" ? $verifyPA['userid'] : $verifyTA['userid']));

                //CONFIRM RESERVED ACCOUNT OWNER
                $checkInst = $this->fetchInstitutionById($myId);
                $checkMemSet = $this->fetchMemberSettingsById($myId);
                $checkUser = $this->fetchUser($myId);
                $checkCustomer = $this->fetchCustomerByAcctIdOnly($myId);
                $originator = ($checkUser != "0" && $checkCustomer == "0" ? $checkUser['created_by'] : ($checkUser == "0" && $checkCustomer != "0" ? $checkCustomer['branchid'] : $myId));
                $initiator = ($checkUser != "0" && $checkCustomer == "0" ? $checkUser['id'] : ($checkUser == "0" && $checkCustomer != "0" ? $checkCustomer['account'] : ''));
                $branch = ($checkUser != "0" && $checkCustomer == "0" ? $checkUser['branchid'] : ($checkUser == "0" && $checkCustomer != "0" ? $checkCustomer['sbranchid'] : ""));
                $currency =  $checkMemSet['currency'];
                $tranDateTime = date("Y-m-d h:i:s");

                //LOG WALLET HISTORY
                ($verifyVA != "0" && $verifyPA == "0" && $verifyTA == "0" && $checkIndepotent1 == "0") ? $query1 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance, receiver_bal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
                ($verifyVA != "0" && $verifyPA == "0" && $verifyTA == "0" && $checkIndepotent1 == "0") ? $this->db->insertSpecialWalletHistory($query1, $originator, $sessionId, $recipient, $amount, '', 'Credit', $currency, 'WEMA', '', 'pending', $tranDateTime, $initiator, '', '') : "";

                //LOG POOL HISTORY
                ($verifyVA == "0" && $verifyPA != "0" && $verifyTA == "0" && $checkIndepotent2 == "0") ? $query2 = "INSERT INTO pool_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance, receiver_bal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
                ($verifyVA == "0" && $verifyPA != "0" && $verifyTA == "0" && $checkIndepotent2 == "0") ? $this->db->insertSpecialWalletHistory($query2, $originator, $sessionId, $recipient, $amount, '', 'Credit', $currency, 'WEMA', '', 'pendingpool', $tranDateTime, $initiator, '', '') : "";

                //LOG TILL FUNDING HISTORY
                ($verifyVA == "0" && $verifyPA == "0" && $verifyTA != "0" && $checkIndepotent3 == "0") ? $query3 = "INSERT INTO fund_allocation_history (txid, companyid, manager_id, branch, teller, cashier, amount_fund, ttype, paymenttype, currency, balance, note_comment, status, date_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
                ($verifyVA == "0" && $verifyPA == "0" && $verifyTA != "0" && $checkIndepotent3 == "0") ? $this->db->insertFundAllocationHistory($query3, $sessionId, $originator, $initiator, $branch, $recipient, $initiator, $amount, 'Credit', 'WEMA', $currency, '', $recipient, 'pendingtill', $tranDateTime) : "";

                return json_encode([
                    "transactionreference" => $sessionId, //Unique reference from the Vendor
                    "status" => "00", //00-Okay
                    "status_desc" => "success"
                ]);

            }
        
        }else{

            return -4;

        }

    }


    /** ENDPOINT URL FOR CUSTOMER ACCOUNT LOOKUP:
     * 
     * api/wemaBank/accountLookup: Get account lookup of wema bank reserved account with the following required field:
     * 
     * {
     *  "accountnumber":"string"
    *  }
     * 
     * */
    public function accountLookup($parameter) {

        if(isset($parameter->accountnumber)){
            
            $accountnumber = $parameter->accountnumber;
           
            //CONFIRM RESERVED ACCOUNT NUMBER
            $verifyVA = $this->fetchVAByAcctNo($accountnumber);
            $verifyPA = $this->fetchPoolAcctByAcctNo($accountnumber);
            $verifyTA = $this->fetchTillVAByAcctNo($accountnumber);

            if($accountnumber == ""){

                return -1;

            }elseif($verifyVA === 0 && $verifyPA === 0 && $verifyTA === 0){
        
                return json_encode([
                    "accountname" => "",
                    "status" => "07",//00-Okay, 07-Invalid Account
                    "status_desc" => "Invalid Account"
                ]);

            }else{

                return json_encode([
                    "accountname" => (($verifyVA != 0 && $verifyPA === 0 && $verifyTA === 0) ? $verifyVA['account_name'] : (($verifyVA === 0 && $verifyPA != 0 && $verifyTA === 0) ? $verifyPA['account_name'] : (($verifyVA === 0 && $verifyPA === 0 && $verifyTA != 0) ? $verifyTA['account_name'] : ""))),
                    "status" => "00",//00-Okay, 07-Invalid Account
                    "status_desc" => "Successful"
                ]);

            }
        
        }else{

            return -2;

        }

    }

}

?>