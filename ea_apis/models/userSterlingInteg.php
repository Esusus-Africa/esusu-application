<?php

require_once('class.aesEncryption.php');

class sterlingBankVA extends User {

    /** ENDPOINT URL TO RECEIVE TRANSACTION NOTIFICATION:
     * 
     * api/sterling/transNotification: Receive transaction notification on each sterling bank reserved account with the following required field:
     * 
     * {
     *  "Data":"encryptedmessage"
    *  }
    *
    *   After Decryption, you should have below response:
    *   {
     *  "transferType":1,
     *  "senderAccountNumber":"08032094353",
     *  "amount":150.00,
     *  "availableBalance":12528.03,
     *  "senderName":"John Kings",
     *  "narration":"TestTnx005",
     *  "beneficiaryAccountName":"test",
     *  "beneficiaryAccountNumber":"test",
     *  "paymentReference":"test",
     *  "senderBankCode":"000001",
     *  "destinationBankCode":"000001",
     *  "IsInternal":false,
     *  "transactionDate":"2021-12-22T10:16:02.303",
     *  "clientId":45,
     *  "clientwebhookUrl":"your webhook"
    *  }
    *
    *  The expected response with status code 200 and message: 
    *   {
     *  "status":true,
     *  "responseCode":"00",
     *  "responseMessage":"request received"
     *  }
     * 
     * */
    public function transNotif($parameter, $inputKey, $iv) {

        if(isset($parameter->Data)){
            
            $encryptedMsg = $parameter->Data;
            $data_to_send_server = json_encode(["Data"=>$encryptedMsg]);
            $blockSize = 128;
            $aes = new AESEncryption($data_to_send_server, $inputKey, $iv, $blockSize);
            $hextobin = base64_encode(pack('H*',$encryptedMsg));
            $aes->setData($hextobin);
            $descriptResponse = $aes->decrypt();
            $decodeResponse = json_decode($descriptResponse, true);
            
            $transferType = $decodeResponse['transferType'];
            $originatoraccountnumber = $decodeResponse['senderAccountNumber'];
            $amount = $decodeResponse['amount'];
            $availableBalance = $decodeResponse['availableBalance'];
            $originatorname = $decodeResponse['senderName'];
            $narration = $decodeResponse['narration'];
            $craccountname = $decodeResponse['beneficiaryAccountName'];
            $craccount = $decodeResponse['beneficiaryAccountNumber'];
            $paymentreference = $decodeResponse['paymentReference'];
            $senderBankCode = $decodeResponse['senderBankCode'];
            $destinationBankCode = $decodeResponse['destinationBankCode'];

            $checkBankList = $this->checkBankList($senderBankCode);
            $bankname = $checkBankList['bankname'];

            $recipient = "From:- ".$originatorname;
            $recipient .= ", Account Number: ".$originatoraccountnumber;
            $recipient .= ", Bank Name: ".$bankname;

            $checkIndepotent1 = $this->fetchWalletHistoryByRefId($paymentreference);
            $checkIndepotent2 = $this->fetchPoolHistoryByRefId($paymentreference);
            $checkIndepotent3 = $this->fetchTillWHistoryByRefId($paymentreference);
            
            //CONFIRM RESERVED ACCOUNT NUMBER
            $verifyVA = $this->fetchVAByAcctNo($craccount);
            $verifyPA = $this->fetchPoolAcctByAcctNo($craccount);
            $verifyTA = $this->fetchTillVAByAcctNo($craccount);

            if($checkIndepotent1 != 0 || $checkIndepotent2 != 0 || $checkIndepotent3 != 0){

                return -1;

            }elseif($encryptedMsg == ""){

                return -2;

            }elseif($verifyVA === 0 && $verifyPA === 0 && $verifyTA === 0){
        
                return -3;
                
            }
            else{

                $responseMsg = json_encode(["status" => true,"responseCode" => "00","responseMessage" => "request received"]);
                $aesResponse = new AESEncryption($responseMsg, $inputKey, $iv, $blockSize);
                $encryptDataResponse = base64_decode($aesResponse->encrypt());
                $binToHexReponse = bin2hex($encryptDataResponse);
                $dataResponsePasser = "$binToHexReponse";
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
                ($verifyVAgit != "0" && $verifyPA == "0" && $verifyTA == "0" && $checkIndepotent1 == "0") ? $query1 = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance, receiver_bal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
                ($verifyVA != "0" && $verifyPA == "0" && $verifyTA == "0" && $checkIndepotent1 == "0") ? $this->db->insertSpecialWalletHistory($query1, $originator, $paymentreference, $recipient, $amount, '', 'Credit', $currency, 'STERLING', '', 'pending', $tranDateTime, $initiator, '', '') : "";

                //LOG POOL HISTORY
                ($verifyVA == "0" && $verifyPA != "0" && $verifyTA == "0" && $checkIndepotent2 == "0") ? $query2 = "INSERT INTO pool_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance, receiver_bal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
                ($verifyVA == "0" && $verifyPA != "0" && $verifyTA == "0" && $checkIndepotent2 == "0") ? $this->db->insertSpecialWalletHistory($query2, $originator, $paymentreference, $recipient, $amount, '', 'Credit', $currency, 'STERLING', '', 'pendingpool', $tranDateTime, $initiator, '', '') : "";

                //LOG TILL FUNDING HISTORY
                ($verifyVA == "0" && $verifyPA == "0" && $verifyTA != "0" && $checkIndepotent3 == "0") ? $query3 = "INSERT INTO fund_allocation_history (txid, companyid, manager_id, branch, teller, cashier, amount_fund, ttype, paymenttype, currency, balance, note_comment, status, date_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
                ($verifyVA == "0" && $verifyPA == "0" && $verifyTA != "0" && $checkIndepotent3 == "0") ? $this->db->insertFundAllocationHistory($query3, $paymentreference, $originator, $initiator, $branch, $recipient, $initiator, $amount, 'Credit', 'STERLING', $currency, '', $recipient, 'pendingtill', $tranDateTime) : "";

                return "$dataResponsePasser";

            }
        
        }else{

            return -4;

        }

    }


}

?>