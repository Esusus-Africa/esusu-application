<?php
//error_reporting(0);
/**
 * Class for all NIP Bank Transfer
 * 
 * Created by AKINADE AYODEJI TIMOTHEW on 5/8/2020
 * Objective: building to scale
 */
 
//require_once "walletafrica_restfulapis_call.php";

//require_once "sterling_restfulapis_call.php";

require_once "virtualBankAccount_class.php";

class NIPBankTransfer extends GenerateBankAccount {
    
    public $providusUName, $providusPass, $amountWithNoCharges, $amountWithCharges, $currency, $narration, $tReference, $recipientAcctNo, $recipientBankCode, $accountName, $originatorName, $client, $link, $draccountname;
    
    public $walletafrica_skey, $data_to_send_server, $hashconvert, $gtbtransdetails, $gtbaccesscode, $gtbusername, $gtbpassword, $sourceAcctNo, $TxtReference, $customerRef, $firstName, $surName, $customerEmail, $phoneNumber;
    
    public $accessToken;

    private $keyval = "000000010000001000000101000001010000011100001011000011010001000100010010000100010000110100001011000001110000001000000100000010000000000100001100000000110000010100000101000010110000110100011011";
    
    private $vectorval = "0000000100000010000000110000010100000111000010110000110100010001";
    
    
    /**
     * Function to initiate transfer with ProvidusBank Endpoint
     * @group string
     */
    public function providusNIPBankTransfer($providusUName,$providusPass,$amountWithNoCharges,$currency,$narration,$tReference,$recipientAcctNo,$recipientBankCode,$accountName,$originatorName,$client){
        
        global $providusUName, $providusPass, $amountWithNoCharges, $currency, $narration, $tReference, $recipientAcctNo, $recipientBankCode, $accountName, $originatorName, $client;
        
        $param = array(
          'amount'=>$amountWithNoCharges,
          'currency'=>$currency,
          'narration'=>$narration,
          'transaction_reference'=>$tReference,
          'recipient_account_number'=>$recipientAcctNo,
          'recipient_bank_code'=>$recipientBankCode,
          'account_name'=>$accountName,
          'originator_name'=>$originatorName,
          'username'=>$providusUName,
          'password'=>$providusPass
        );

        $response = $client->NIPFundTransfer($param);
        
        $process = json_decode(json_encode($response), true);
        
        $processReturn = $process['return'];
        
        $decodeProcess = json_decode($processReturn, true);
        
        return $decodeProcess;
        
    }
    
    public function accessNIPBankTransfer(){
        
        
        
    }
    
    
    /**
     * Function to initiate transfer with Sterling Bank Endpoint
     * @group string
     */
    public function pkcs5_pad($text, $blocksize){
        
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
        
    }
    
    public function pkcs5_unpad($text){
        
        $pad = ord($text(strlen($text) - 1));
        if ($pad > strlen($text))
            return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return false;
        return substr($text, 0, -1 * $pad);
        
    }
    
    public function encryptPayLoad($plainText, $key, $iv){
        
        $padded = $this->pkcs5_pad($plainText, mcrypt_get_block_size("tripledes", "cbc"));
       // $iv = "\01\x02\x03\x05\x07\x0B\x0D\x11";
        $encText = mcrypt_encrypt("tripledes", $key, $padded, "cbc", $iv);
        return base64_encode($encText);
        
    }
    
    public function decryptText($encryptText, $key, $iv){
        
        $cipherText = base64_decode($encryptText);
      
        $iv = "\01\x02\x03\x05\x07\x0B\x0D\x11";
        $res = mcrypt_decrypt("tripledes", $key, $cipherText, "cbc", $iv);
        $resUnpadded = $this->pkcs5_unpad($res);
        return $resUnpadded;
        
    }
    
    public function cryptText($crypt, $key, $iv){
        
        //  $iv_size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        // $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        // crypting  
        $cryptText = mcrypt_encrypt(MCRYPT_3DES, $key, $crypt, MCRYPT_MODE_ECB, $iv);
        
        return base64_encode($cryptText);
        
    }
      
    public function binaryToString($binary){
        
        $binaryArray = str_split($binary, 8);
      
        $charText = "";
        foreach ($binaryArray as $key){
            
            $charText .= chr(intval($key, 2)); //casting integer value of each byte to char
        
        }
        
        return $charText;    
    
    }
    
    public function sterlingCallAPI($method, $url, $data){
        
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
       curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Content-Type: text/json",
        "AppId:9190"
       ));
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    
       // EXECUTE:
       $result = curl_exec($curl);
       
       if(curl_error($curl)){
    		echo 'error:' . curl_error($curl);
    	}
    	
       curl_close($curl);
       
       return $result;
    }
    
    
    public function sterlingNIPBankTransfer($data_to_send_server,$client){
        
        global $data_to_send_server, $client;
        
        $payload = $this->encryptPayLoad(json_encode($data_to_send_server), $this->binaryToString($this->keyval), $this->binaryToString($this->vectorval));
        
        $make_call = $this->sterlingCallAPI('POST', $client, $payload);
        
        $process = json_decode($make_call, true);
        
        return $process;
        
    }
    

    
    /**
     * Function to initiate transfer with sunTrust Bank Endpoint
     * @group string
     */
    public function sunTrustNIPBankTransfer($link,$ReqReference,$sourceAcctNo,$TxtReference,$narration,$amountWithCharges,$customerRef,$firstName,$surName,$customerEmail,$phoneNumber,$recipientAcctNo,$recipientBankCode,$onePipeSKey,$onePipeApiKey){
        
        global $link, $ReqReference, $sourceAcctNo, $TxtReference, $narration, $amountWithCharges, $customerRef, $firstName, $surName, $customerEmail, $phoneNumber, $recipientAcctNo, $recipientBankCode, $onePipeSKey, $onePipeApiKey;
        
        //GET ENDPOINT FOR SUNTRUST BANK
        $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'suntrust_nipTransfer'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
        
        $dataToEncrypt = $recipientAcctNo.$recipientBankCode;
        
        $postMyData =  array(
        	"request_ref"=> $ReqReference,
            "request_type"=> "transfer_funds",
            "auth"=>[
                 "type"=> "bank.account",
                 "secure"=> $this->encryptText($onePipeSKey,$dataToEncrypt),
                 "auth_provider"=> "SunTrust",
                 "route_mode"=> null,
                ],
                "transaction"=>[
                    "mock_mode"=> "live",
                    "transaction_ref"=> $TxtReference,
                    "transaction_desc"=> $narration,
                    "transaction_ref_parent"=> null,
                    "amount"=> ($amountWithCharges * 100),
                    "customer"=> [
                        "customer_ref"=> $customerRef,
                        "firstname"=> $firstName,
                        "surname"=> $surName,
                        "email"=> $customerEmail,
                        "mobile_no"=> $phoneNumber
                        ],
                        "meta"=> [
                            "a_key"=> "a_meta_value_1",
                            "another_key"=> "a_meta_value_2"
                            ],
                        "details"=> [
                            "destination_account"=> $recipientAcctNo,
                            "destination_bank_code"=> $recipientBankCode
                            ],
                    ]
                );
            
        $make_mycall = $this->sunTrustCallAPI('POST', $api_url, json_encode($postMyData), $onePipeApiKey, $onePipeSKey, $ReqReference);
                           
        return $make_mycall;
        
    }


    public function newSunTrustNIPBankTransfer($link,$tReference,$narration,$amountWithNoCharges,$recipientAcctNo,$recipientBankCode,$onePipeSKey,$onePipeApiKey){
        
        global $link, $tReference, $narration, $amountWithNoCharges, $recipientAcctNo, $recipientBankCode, $onePipeSKey, $onePipeApiKey;
        
        //GET ENDPOINT FOR SUNTRUST BANK
        $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'suntrust_nipTransfer'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;

        $ReqReference = date("yd").time();
        
        $postMyData =  array(
        	"request_ref"=> $ReqReference,
            "request_type"=> "disburse",
            "auth"=>[
                 "type"=> null,
                 "secure"=> null,
                 "auth_provider"=> "SunTrust",
                 "route_mode"=> null,
                ],
                "transaction"=>[
                    "mock_mode"=> "live",
                    "transaction_ref"=> $tReference,
                    "transaction_desc"=> $narration,
                    "transaction_ref_parent"=> null,
                    "amount"=> ($amountWithNoCharges * 100),
                    "customer"=> [
                        "customer_ref"=> "DemoApp_Customer007",
                        "firstname"=> "Uju",
                        "surname"=> "Usmanu",
                        "email"=> "ujuusmanu@gmail.com",
                        "mobile_no"=> "234802343132"
                        ],
                        "meta"=> [
                            "a_key"=> "a_meta_value_1",
                            "another_key"=> "a_meta_value_2"
                            ],
                        "details"=> [
                            "destination_account"=> $recipientAcctNo,
                            "destination_bank_code"=> $recipientBankCode
                            ],
                    ]
                );
            
        $make_mycall = $this->sunTrustCallAPI('POST', $api_url, json_encode($postMyData), $onePipeApiKey, $onePipeSKey, $ReqReference);
                           
        return $make_mycall;
    
    }
    
    
    public function sunTrustOTPFTConfirmation($otp,$ReqReference,$TxtReference,$onePipeSKey,$onePipeApiKey){
        
        //GET ENDPOINT FOR SUNTRUST BANK
        $search_restapi = mysqli_query($this->link, "SELECT * FROM restful_apisetup WHERE api_name = 'suntrust_otp_validation'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
        
        $postMyData =  array(
            "request_ref"=> $ReqReference,
            "request_type"=> "transfer_funds",
            "auth"=>[
                "type"=> "bank.transfer",
                "secure"=> $this->encryptText($onePipeSKey,$otp),
                "auth_provider"=> "SunTrust"
                ],
                "transaction"=>[
                  "transaction_ref"=> $TxtReference
                ]
            );
        
        $stotp_response = $this->sunTrustCallAPI('POST', $api_url, json_encode($postMyData), $onePipeApiKey, $onePipeSKey, $ReqReference);
                          
        return $stotp_response;
        
    }
    
    
    /**
     * Function to initiate transfer with Wallet Africa Endpoint
     * @group string
     */
    public function walletAfricaNIPBankTransfer($walletafrica_skey,$recipientBankCode,$recipientAcctNo,$accountName,$tReference,$amountWithNoCharges,$narration,$client){
        
        global $walletafrica_skey, $recipientBankCode, $recipientAcctNo, $accountName, $tReference, $amountWithNoCharges, $narration, $client;
        
        $param =  array(
            "SecretKey" => $walletafrica_skey,
            "BankCode" => $recipientBankCode,
            "AccountNumber" => $recipientAcctNo,
            "AccountName" => $accountName,
            "TransactionReference" => $tReference,
            "Amount" => $amountWithNoCharges,
            "Narration" => $narration
        );
                               
        $make_call = $this->waCallAPI('POST', $client, json_encode($param));
        
        $process = json_decode($make_call, true);
        
        return $make_call;
        
    }



    /**
     * Function to initiate transfer with GTBank
     * @group string
     */
    protected function hashGTBTransferParam($hashconvert,$gtbaccesscode,$gtbusername,$gtbpassword){
        global $hashconvert, $gtbaccesscode, $gtbusername, $gtbpassword;

        $data = $hashconvert.$gtbaccesscode.$gtbusername.$gtbpassword; 
        $hashed = hash('sha512', $data); 
        return $hashed;

    }

    public function GTBNIPBankTransfer($hashconvert,$gtbtransdetails,$gtbaccesscode,$gtbusername,$gtbpassword,$client){
        global $hashconvert, $gtbtransdetails, $gtbaccesscode, $gtbusername, $gtbpassword, $client;

        $hash = $this->hashGTBTransferParam($hashconvert,$gtbaccesscode,$gtbusername,$gtbpassword);

        $myclient = new SoapClient($client);

        $xmlRequest = "<soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:fil='http://tempuri.org/GAPS_Uploader/FileUploader'>
                       <soapenv:Header/>
                       <soapenv:Body>
                          <fil:SingleTransfers>
                             <!--Optional:-->
                             <fil:xmlRequest><![CDATA[<SingleTransfers> 
                                <transdetails>$gtbtransdetails</transdetails>
                                <accesscode>$gtbaccesscode</accesscode>
                                <username>$gtbusername</username>
                                <password>$gtbpassword</password>
                                <hash>$hash</hash>
                                </SingleTransfers>]]></fil:xmlRequest>
                              </fil:SingleTransfers>
                           </soapenv:Body>
                        </soapenv:Envelope>";
                    
        $convert = htmlentities($xmlRequest);

        $response = $myclient->SingleTransfers($convert);
        
        $process = json_decode(json_encode($response), true);
        
        return $process;

    }
    
    
    
    /**
     * Function to initiate NIP transfer with Rubies Digital Banking Api
     * @group string
     */
    public function rubiesListNIPBank($link,$rubbiesSecKey){
        
        global $link;
        
        $curl = curl_init();
    
        $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'rubies_listnipbank'");
        $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
        $api_url1 = $fetch_restapi1->api_url;
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url1,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>json_encode([
                    'request'=>'banklist'
                ]),
          CURLOPT_HTTPHEADER => array(
            "Authorization: ".$rubbiesSecKey,
            "Content-Type: application/json"
          ),
        ));
        
        $response = curl_exec($curl);
        $rubbies_generate = json_decode($response, true);
        
        $banklist = $rubbies_generate['banklist'];
        
        return $banklist;
        
    }
    
    public function rubiesInterBankNameEnquiry($link,$rubbiesSecKey,$recipientBankCode,$recipientAcctNo){
        
        global $link, $rubbiesSecKey, $recipientBankCode, $recipientAcctNo;
        
        $curl = curl_init();
    
        $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'rubies_InterbankNameEnquiry'");
        $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
        $api_url1 = $fetch_restapi1->api_url;
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url1,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>json_encode([
                    'accountnumber'=>$recipientAcctNo,
                    'bankcode'=>$recipientBankCode
                ]),
          CURLOPT_HTTPHEADER => array(
            "Authorization: ".$rubbiesSecKey,
            "Content-Type: application/json"
          ),
        ));
        
        $response = curl_exec($curl);
        $rubbies_generate = json_decode($response, true);
        
        return $rubbies_generate;
        
    }
    
    
    public function rubiesNIPBankTransfer($tReference,$amountWithNoCharges,$narration,$accountName,$recipientBankCode,$recipientAcctNo,$rubbiesSecKey,$link,$draccountname){
        
        global $tReference, $amountWithNoCharges, $narration, $accountName, $recipientBankCode, $recipientAcctNo, $link, $draccountname, $rubbiesSecKey;
        
        $curl = curl_init();
        
        //GET BANK NAME
        $search_bankname = mysqli_query($link, "SELECT * FROM bank_list2 WHERE bankcode = '$recipientBankCode'");
        $fetch_bankname = mysqli_fetch_array($search_bankname);
        $mybank_name = $fetch_bankname['bankname'];
        
        //GENERATE VIRTUAL ACCOUNT ON MONIFY
        $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'rubies_niptransfer'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;

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
                'reference'=>$tReference,
                'amount'=>$amountWithNoCharges,
                'narration'=>$narration,
                'craccountname'=>$accountName,
                'bankname'=>$mybank_name,
                'draccountname'=>$draccountname,
                'craccount'=>$recipientAcctNo,
                'bankcode'=>$recipientBankCode
            ]),
          CURLOPT_HTTPHEADER => array(
            "Authorization: ".$rubbiesSecKey,
            "Content-Type: application/json"
          ),
        ));
        
        $response = curl_exec($curl);
              
        return $response;
        
    }

    /**
     * Function to initiate NIP transfer with Prime Airtime Api
     * @group string
     */
    public function reAuthPAToken($link, $accessToken){
        
        global $link, $accessToken;

        $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'primeairtime_baseUrl'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url.'/api/reauth';
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer ".$accessToken            
        ],
        ));
        
        $token_response = curl_exec($curl);
        $newAuthToken = json_decode($token_response, true);

        return $newAuthToken['token'];

    }


    public function checkFTAccess($link, $accessToken){
        
        global $link, $accessToken;

        $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'primeairtime_baseUrl'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url.'/api/ft/check_access';
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer ".$accessToken
        ],
        ));
        
        $access_response = curl_exec($curl);
        $ftAccessResponse = json_decode($access_response, true);

        return $ftAccessResponse['success'];

    }


    public function primeAirtimeFT($link, $accessToken,$recipientBankCode,$recipientAcctNo,$amountWithNoCharges,$narration,$tReference){
        
        global $link, $accessToken, $recipientBankCode, $recipientAcctNo, $amountWithNoCharges, $narration, $tReference;

        $curl = curl_init();

        $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'primeairtime_baseUrl'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url.'/api/ft/transfer/'.$recipientBankCode.'/'.$recipientAcctNo;
        
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
                  'amount'=>$amountWithNoCharges,
                  'narration'=>$narration,
                  'customer_reference'=>$tReference
              ]),
            CURLOPT_HTTPHEADER => array(
              "Content-Type: application/json",
              "Authorization: Bearer ".$accessToken
            ),
          ));
          
        $response = curl_exec($curl);

        return $response;

    }

    
}

$new = new NIPBankTransfer($link);

?>