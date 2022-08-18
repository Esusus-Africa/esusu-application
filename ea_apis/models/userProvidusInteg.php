<?php

class providusVPS extends User {

    /** ENDPOINT URL TO RECEIVE TRANSACTION NOTIFICATION:
     * 
     * api/providus/settlement_notif: Receive transaction notification on each providus reserved account with the following required field:
     * 
     * {
     *  "sessionId":"11111111111111389439774ss389888rrr43",
     *  "accountNumber": "9900000012",
     *  "tranRemarks": "xxxxx",
     *  "transactionAmount": 1000,
     *  "settledAmount": 990,
     *  "feeAmount": 10,
     *  "vatAmount": 0,
     *  "currency":"xxx",
     *  "initiationTranRef":"389439774ss389888rrr43",
     *  "settlementId":"xxxx",
     *  "sourceAccountNumber":"xxxx",
     *  "sourceAccountName":"xxxx",
     *  "sourceBankName":"xxxxxx",
     *  "channelId":"1",
     *  "tranDateTime":"xxx"
    *  }
     * 
     * */
    public function settlementNotif($parameter) {

        if(isset($parameter->sessionId) && isset($parameter->accountNumber) && isset($parameter->tranRemarks) && isset($parameter->transactionAmount) && isset($parameter->settledAmount) && isset($parameter->feeAmount) && isset($parameter->vatAmount) && isset($parameter->currency) && isset($parameter->initiationTranRef) && isset($parameter->settlementId) && isset($parameter->sourceAccountNumber) && isset($parameter->sourceAccountName) && isset($parameter->sourceBankName) && isset($parameter->channelId) && isset($parameter->tranDateTime)){
            
            $sessionId = $parameter->sessionId;
            $accountNumber = $parameter->accountNumber;
            $tranRemarks = $parameter->tranRemarks;
            $transactionAmount = $parameter->transactionAmount;
            $settledAmount = $parameter->settledAmount;
            $feeAmount = $parameter->feeAmount;
            $vatAmount = $parameter->vatAmount;
            $currency = $parameter->currency;
            $initiationTranRef = $parameter->initiationTranRef;
            $settlementId = $parameter->settlementId;
            $sourceAccountNumber = $parameter->sourceAccountNumber;
            $sourceAccountName = $parameter->sourceAccountName;
            $sourceBankName = $parameter->sourceBankName;
            $channelId = $parameter->channelId;
            $tranDateTime = $parameter->tranDateTime;

            $checkIndepotent1 = $this->fetchWalletHistoryByRefId($sessionId);
            $checkIndepotent2 = $this->fetchPoolHistoryByRefId($sessionId);
            $checkIndepotent3 = $this->fetchTillWHistoryByRefId($sessionId);
            
            //CONFIRM RESERVED ACCOUNT NUMBER
            $verifyVA = $this->fetchVAByAcctNo($accountNumber);
            $verifyPA = $this->fetchPoolAcctByAcctNo($accountNumber);
            $verifyTA = $this->fetchTillVAByAcctNo($accountNumber);

            if($checkIndepotent1 >= 1 || $checkIndepotent2 >= 1 || $checkIndepotent3 >= 1){

                return -1;

            }elseif($sessionId == "" || $initiationTranRef == "" || $accountNumber == "" || $transactionAmount == "" || $currency == "" || $tranDateTime == ""){

                return -2;

            }elseif($verifyVA === 0 && $verifyPA === 0 && $verifyTA === 0){
        
                return -3;
                
            }
            else{

                return json_encode([
                    "requestSuccessful" => true,
                    "sessionId" => $sessionId,
                    "responseMessage" => "success",
                    "responseCode" => "00"
                ]);

            }
        
        }else{

            return -4;

        }

    }

}

?>