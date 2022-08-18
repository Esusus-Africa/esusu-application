<?php

class Pos extends User {

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



    /** ENDPOINT URL FOR TERMINAL CUSTOM INFO ARE:
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
                $todays_date = date("Y-m-d");

                $airtimeDataHistoryLimit = $this->fetchAggregateAirtimeDataLimitPerDay($todays_date,$tidoperator);
                $imyDailyAirtimeData = $airtimeDataHistoryLimit['SUM(debit)'];

                $vaGlobalLimit = $this->fetchVALimitConfiguration($tidoperator,$institutionid);
                $iglobalDailyAirtime_DataLimit = $vaGlobalLimit['airtime_dataLimitPerDay'];
                $iglobal_airtimeLimitPerTrans = $vaGlobalLimit['airtime_dataLimitPerTrans'];
                
                //4 rows [0 - 4]
                $dataToProcess = $reference."|".$amount."|".$mobilenumber."|".$telco."|".$balance;
                    
                $mytxtstatus = 'Pending';
                    
                ($topup_amount > $balance) ? "" : $queryTxt = "INSERT INTO api_txtwaitinglist (userid, refid, mydata, status, date_time) VALUE(?, ?, ?, ?, ?)";
                ($topup_amount > $balance) ? "" : $this->db->insertPendingTransaction($queryTxt, $tidoperator, $reference, $dataToProcess, $mytxtstatus, $transactionDateTime);

                if($checkTerm === 0 || $operatorInfo === 0){

                    return -3;

                }elseif($balance < $topup_amount){

                    return -4;

                }elseif($topup_amount > $iglobal_airtimeLimitPerTrans){

                    return -5;
            
                }elseif($imyDailyAirtimeData == $iglobalDailyAirtime_DataLimit || (($topup_amount + $imyDailyAirtimeData) > $iglobalDailyAirtime_DataLimit)){
            
                    return -6;
            
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

                        return -7;

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
                $todays_date = date("Y-m-d");

                $airtimeDataHistoryLimit = $this->fetchAggregateAirtimeDataLimitPerDay($todays_date,$tidoperator);
                $imyDailyAirtimeData = $airtimeDataHistoryLimit['SUM(debit)'];

                $vaGlobalLimit = $this->fetchVALimitConfiguration($tidoperator,$institutionid);
                $iglobalDailyAirtime_DataLimit = $vaGlobalLimit['airtime_dataLimitPerDay'];
                $iglobal_airtimeLimitPerTrans = $vaGlobalLimit['airtime_dataLimitPerTrans'];
                
                //5 rows [0 - 5]
                $dataToProcess = $reference."|".$amount."|".$product."|".$mobilenumber."|".$telco."|".$balance;
                    
                $mytxtstatus = 'Pending';
                    
                ($topup_amount > $balance) ? "" : $queryTxt = "INSERT INTO api_txtwaitinglist (userid, refid, mydata, status, date_time) VALUE(?, ?, ?, ?, ?)";
                ($topup_amount > $balance) ? "" : $this->db->insertPendingTransaction($queryTxt, $tidoperator, $reference, $dataToProcess, $mytxtstatus, $transactionDateTime);

                if($checkTerm === 0 || $operatorInfo === 0){

                    return -3;

                }elseif($balance < $topup_amount){

                    return -4;

                }elseif($topup_amount > $iglobal_airtimeLimitPerTrans){

                    return -5;
            
                }elseif($imyDailyAirtimeData == $iglobalDailyAirtime_DataLimit || (($topup_amount + $imyDailyAirtimeData) > $iglobalDailyAirtime_DataLimit)){
            
                    return -6;
            
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

                        return -7;

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
                $PubKey = $sysCredentials['public_key'];

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
                    
                    $verifyBank = $this->db->fetchBankList($bankcode);
                    $oldBankCode = $verifyBank['oldbankCode'];
                
                    $searchApi = $this->fetchApi("resolveaccount");
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
                                'recipientaccount'=>$accountnumber,
                                'destbankcode'=>$oldBankCode,
                                'PBFPubKey'=>$PubKey
                            ]),
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json"
                        ),
                        ));
                        
                    $response = curl_exec($curl);
                    $walletafrica_generate = json_decode($response, true);
                    
                    if($walletafrica_generate['data']['data']['responsecode'] == "00"){
                        
                        return [
                            "responsecode" => "00",
                            "accountnumber" => $accountnumber,
                            "accountname" => $walletafrica_generate['data']['data']['accountname'],
                            "kyc" => "2",
                            "responsemessage" => "successful",
                            "sessionid" => date("mY").time(),
                            "bvn" => "",
                            "bankcode" => $bankcode
                        ];
                        
                    }
                    else{
                        
                        return -5;
                        
                    }

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
                $walletAfricaSecKey = $sysCredentials['walletafrica_skey'];
                $walletAfricaPubKey = $sysCredentials['walletafrica_pkey'];

                $sysMaintenance = $this->fetchMaintenanceSettings($institutionid);
                $charges = ($sysMaintenance === 0) ? $sysCredentials['transfer_charges'] : $sysMaintenance['bank_transfer_charges'];
                $commission = ($sysMaintenance === 0) ? 0 : $sysMaintenance['bank_transfer_commission'];
                $amtWithCharges = $amount + $charges;
                $remainOprtBalance = $balance - $amtWithCharges;
                $remainAggrBalance = $aggrBalance + $commission;
                $transactionDateTime = date("Y-m-d h:i:s");
                $todays_date = date("Y-m-d");

                $myMemberSettings = $this->db->fetchMemberSettings($institutionid);
                $nip_route = $myMemberSettings['nip_route'];
                
                $verifyBank = $this->db->fetchBankList($bankcode);
                $oldBankCode = $verifyBank['oldbankCode'];

                $transferHistoryLimit = $this->fetchAggregateTransferLimitPerDay($todays_date,$tidoperator);
                $imyDailyTransferLimit = $transferHistoryLimit['SUM(debit)'];

                $vaGlobalLimit = $this->fetchVALimitConfiguration($tidoperator,$institutionid);
                $itransferLimitPerDay = $vaGlobalLimit['transferLimitPerDay'];
                $itransferLimitPerTrans = $vaGlobalLimit['transferLimitPerTrans'];
                
                //8 rows [0 - 8]
                $dataToProcess = $rrn."|".$amount."|".$narration."|".$craccountname."|".$bankname."|".$operatorName."|".$craccount."|".$bankcode."|".$balance."|".$oldBankCode;
                    
                $mytxtstatus = 'Pending';
                    
                ($amtWithCharges > $balance) ? "" : $queryTxt = "INSERT INTO api_txtwaitinglist (userid, refid, mydata, status, date_time) VALUE(?, ?, ?, ?, ?)";
                ($amtWithCharges > $balance) ? "" : $this->db->insertPendingTransaction($queryTxt, $tidoperator, $rrn, $dataToProcess, $mytxtstatus, $transactionDateTime);

                if($checkTerm === 0 || $operatorInfo === 0){

                    return -3;

                }elseif($amtWithCharges > $balance){

                    return -4;

                }elseif($amount > $itransferLimitPerTrans){

                    return -5;
         
                }elseif($imyDailyTransferLimit == $itransferLimitPerDay || (($amount + $imyDailyTransferLimit) > $itransferLimitPerDay)){
            
                    return -6;
            
                }elseif($nip_route == "RubiesBank"){

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

                        return -7;

                    }

                }elseif($nip_route == "Wallet Africa"){

                    //UPDATE USER BALANCE
                    ($amtWithCharges > $balance) ? "" : $query = "UPDATE user SET transfer_balance = '$remainOprtBalance' WHERE id = '$tidoperator' AND created_by = '$institutionid'";
                    ($amtWithCharges > $balance) ? "" : $this->db->updateUserBal($query, $remainOprtBalance);

                    $searchApi = $this->fetchApi("walletafrica_transfer");
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
                            'SecretKey'=> $walletAfricaSecKey,
                            'bankcode'=>$oldBankCode,
                            'AccountNumber'=>$craccount,
                            'AccountName'=>$craccountname,
                            'TransactionReference'=>$rrn,
                            'Amount'=>$amount,
                            'Narration'=>$narration                            
                        ]),
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                            "Authorization: Bearer ".$walletAfricaPubKey
                        ),
                      ));
                      
                    $response = curl_exec($curl);
              
                    ($amtWithCharges > $balance) ? "" : $response = curl_exec($curl);
                    ($amtWithCharges > $balance) ? "" : $walletafrica_generate = json_decode($response, true);

                    if($walletafrica_generate['ResponseCode'] == "100" || $walletafrica_generate['ResponseCode'] == "200" || $response['ResponseCode'] == "100" || $response['ResponseCode'] == "200"){

                        $gatewayResponse = $walletafrica_generate['Message'];
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
                            "responsecode" => "00",
                            "amount" => $amount,
                            "mobilenumber" => $mobilenumber,
                            "nibssresponsemessage" => $gatewayResponse,
                            "cbareference" => "",
                            "responsemessage" => "Success",
                            "terminalid" => $terminalid,
                            "draccount" => "",
                            "source" => "POS",
                            "sessionid" => $rrn.time(),
                            "craccount" => $craccount,
                            "rrn" => $rrn,
                            "reference" => $terminalid.$rrn,
                            "tcode" => "F04",
                            "nibsscode" => "00",
                            "pin" => $pin,
                            "narration" => $terminalid . "/" . $narration,
                            "customerid" => $terminalid,
                            "craccountname" => $craccountname,
                            "bankname" => $bankname,
                            "draccountname" => $operatorName,
                            "bankcode" => $bankcode,
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

                        return -7;

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