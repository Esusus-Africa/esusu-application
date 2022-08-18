<?php

/**
 * Class for all Virtual/Real Bank Account Creation
 * 
 * Created by AKINADE AYODEJI TIMOTHEW on 7/8/2020
 * Objective: building to scale
 */
 
//require_once "connect.php";
error_reporting(E_ERROR | E_PARSE);
include('phpseclib/Crypt/RSA.php');
include('phpseclib/Crypt/AES.php');
require_once('phpseclib/Crypt/Rijndael.php');
require_once('phpseclib/Math/BigInteger.php');
require_once('phpseclib/Crypt/Random.php');
require_once('phpseclib/Crypt/Hash.php');
require_once('bvnVerification_class.php');
require_once('Class/class.aesEncryption.php');
 
class GenerateBankAccount extends verifyBVN {
     
    public $onePipeSKey, $accountUserId, $accountName, $currencyCode, $customerEmail, $customerName, $userBvn, $mo_contract_code, $phoneNumber, $onePipeApiKey, $walletafrica_skey;
     
    public $otp, $ReqReference, $TxtReference, $title, $firstName, $surName, $middleName, $dob, $gender, $addressLine, $city, $state, $country;
     
    public $accessBank_apimSubKey, $accessBank_auditId, $accessBank_appId, $link, $docName, $fullDocPath, $accounttier, $profession, $maritalStatus, $religion;
    
    public $rubbiesSecKey, $amount, $amountcontrol, $daysactive, $minutesactive, $rubbiesCallbackUrl, $trackingReference, $kudaClientID, $rave_secret_key, $payantEmail, $payantPwd, $payantOrgId;

    public $providusClientId, $providusClientSecret, $wemaVAPrefix, $companyid, $regType, $accountOfficer;

    public $lastName, $inputKey, $iv;
     

    public function __construct($link) {
        
        $this->link = $link;
        $this->accountUserId = $accountUserId;
        $this->accountName = $accountName;
        $this->currencyCode = $currencyCode;
        $this->customerName = $customerName;
        $this->mo_contract_code = $mo_contract_code;
        $this->onePipeSKey = $onePipeSKey;
        $this->walletafrica_skey = $walletafrica_skey;
        $this->onePipeApiKey = $onePipeApiKey;
        $this->ReqReference = $ReqReference;
        $this->TxtReference = $TxtReference;
        $this->userBvn = $userBvn;
        $this->title = $title;
        $this->firstName = $firstName;
        $this->surName = $surName;
        $this->middleName = $middleName;
        $this->dob = $dob;
        $this->phoneNumber = $phoneNumber;
        $this->customerEmail = $customerEmail;
        $this->gender = $gender;
        $this->addressLine = $addressLine;
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
        $this->otp = $otp;
        $this->accessBank_apimSubKey = $accessBank_apimSubKey;
        $this->accessBank_auditId = $accessBank_auditId;
        $this->accessBank_appId = $accessBank_appId;
        $this->docName = $docName;
        $this->fullDocPath = $fullDocPath;
        $this->accounttier = $accounttier;
        $this->profession = $profession;
        $this->maritalStatus = $maritalStatus;
        $this->religion = $religion;
        $this->rubbiesSecKey = $rubbiesSecKey;
        $this->amount = $amount;
        $this->amountcontrol = $amountcontrol;
        $this->daysactive = $daysactive;
        $this->minutesactive = $minutesactive;
        $this->rubbiesCallbackUrl = $rubbiesCallbackUrl;
        $this->trackingReference = $trackingReference;
        $this->kudaClientID = $kudaClientID;
        $this->rave_secret_key = $rave_secret_key;
        $this->payantEmail = $payantEmail;
        $this->payantPwd = $payantPwd;
        $this->payantOrgId = $payantOrgId;
        $this->providusClientId = $providusClientId;
        $this->providusClientSecret = $providusClientSecret;
        $this->wemaVAPrefix = $wemaVAPrefix;
        $this->companyid = $companyid;
        $this->regType = $regType;
        $this->accountOfficer = $accountOfficer;
        $this->lastName = $lastName;

    }
     
     /**
     * Function to generate / verify Monnify Virtual Account Creation
     * @group string
     */
    public function monnifyVirtualAccount($accountName,$currencyCode,$customerEmail,$customerName,$userBvn,$mo_contract_code){
        
        //global $link, $accountUserId, $accountName, $currencyCode, $customerEmail, $customerName, $userBvn, $mo_contract_code;
        
        $curl = curl_init();
        
        $curl2 = curl_init();

        $accountReference = "EAVA-".date("dy").time();
      
        $api_url2 = "https://api.monnify.com/api/v1/auth/login";
    
        curl_setopt_array($curl2, array(
            CURLOPT_URL => $api_url2,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
              "Authorization: Basic TUtfUFJPRF9HSFpZR1o0U0hIOjJRTjJRVFBUU0hUS0hUVVFHWFVZVk1DNVFaVVNRR0RV"
            ),
            
        ));
              
        $auth_response = curl_exec($curl2);
        $autherr = curl_error($curl2);
              
        curl_close($curl2);
              
        if ($autherr) {
                  
            return "cURL Error #:" . $autherr;
                
        }
              
        $auth_generate = json_decode($auth_response);
              
        $myToken = $auth_generate->responseBody->accessToken;
              
        //GENERATE VIRTUAL ACCOUNT ON MONIFY
        $search_restapi = mysqli_query($this->link, "SELECT * FROM restful_apisetup WHERE api_name = 'mo_create_virtual_account'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
               
        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'accountReference'=>$accountReference,
                'accountName'=>$accountName,
                'currencyCode'=>$currencyCode,
                'contractCode'=>$mo_contract_code,
                'customerEmail'=>$customerEmail,
                'bvn'=>$userBvn,
                'customerName'=>$customerName,
                'getAllAvailableBanks'=> false,
                'preferredBanks'=> ['035']  //WEMA BANK (035) | STERLING BANK (232)
            ]),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer ".$myToken
                ),
            ));
            
        $mo_response = curl_exec($curl);
        $mo_generate = json_decode($mo_response);
              
        return $mo_generate;
        
    }


    /**
     * Function to generate / verify Wema Bank Virtual Account Creation using flutterwave endpoint
     * @group string
     */
    public function wemaVirtualAccount($accountName,$customerEmail,$TxtReference,$rave_secret_key){
        
        $curl = curl_init();
        
        $amount = "0";
        
        //GENERATE VIRTUAL ACCOUNT ON FLUTTERWAVE
        $search_restapi = mysqli_query($this->link, "SELECT * FROM restful_apisetup WHERE api_name = 'rave_create_virtual_account'");
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
                'email'=>$customerEmail,
                'is_permanent'=>true,
                'amount'=>$amount,
                'narration'=>$accountName,
                'tx_ref'=>$TxtReference
            ]),
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$rave_secret_key
          ),
        ));
        
        $response = curl_exec($curl);
        $rave_generate = json_decode($response, true);
              
        return $rave_generate;
    
    }
    
    
    /**
     * Function to generate / verify Sterling Bank Virtual Account Creation using Payant endpoint
     * @group string
     */
    public function sterlingPayantVirtualAcct($accountName,$customerEmail,$phoneNumber,$currencyCode,$country,$payantEmail,$payantPwd,$payantOrgId){
        
        $curl = curl_init();
        
        $encodeAUth = base64_encode($payantEmail.":".$payantPwd);
        
        //GENERATE VIRTUAL ACCOUNT ON FLUTTERWAVE
        $search_restapi = mysqli_query($this->link, "SELECT * FROM restful_apisetup WHERE api_name = 'payant_baseUrl'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;

        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url.'/accounts',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode([
                'customer'=>[
                    'name'=>$accountName,
                    'email'=>$customerEmail,
                    'phoneNumber'=>$phoneNumber,
                    'sendNotifications'=>true
                    ],
                'type'=>'RESERVED',
                'accountName'=>$accountName,
                'bankCode'=>'000001',
                'currency'=>$currencyCode,
                'country'=>$country
            ]),
          CURLOPT_HTTPHEADER => array(
            "Authorization: Basic ".$encodeAUth,
            "OrganizationID: ".$payantOrgId,
            "Content-Type: application/json"
          ),
        ));
        
        $response = curl_exec($curl);
        $payant_generate = json_decode($response, true);
              
        return $payant_generate;
    
    }
    
    
    /**
     * Function to generate / verify Access Bank Account Creation using Access Endpoint
     * @group string
     */
    public function accessBankCallAPI($mymethod, $myurl, $mydata, $accessBank_apimSubKey, $accessBank_auditId, $accessBank_appId){
        
       $curl4 = curl_init();
    
       switch ($mymethod){
          case "POST":
             curl_setopt($curl4, CURLOPT_POST, 1);
             if ($mydata)
                curl_setopt($curl4, CURLOPT_POSTFIELDS, $mydata);
             break;
          case "PUT":
             curl_setopt($curl4, CURLOPT_CUSTOMREQUEST, "PUT");
             if ($mydata)
                curl_setopt($curl4, CURLOPT_POSTFIELDS, $mydata);			 					
             break;
          default:
             if ($mydata)
                $myurl = sprintf("%s?%s", $myurl, http_build_query($mydata));
       }
    
       // OPTIONS:
       curl_setopt($curl4, CURLOPT_URL, $myurl);
       curl_setopt($curl4, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Cache-Control: no-cache",
        "Ocp-Apim-Subscription-Key: ".$accessBank_apimSubKey
       ));
       curl_setopt($curl4, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl4, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    
       // EXECUTE:
       $myresult = curl_exec($curl4);
       if(curl_error($curl4)){
    		echo 'error:' . curl_error($curl4);
    	}
       curl_close($curl4);
       
       return $myresult;
       
    }
    
    
    protected function accessBankUploadDoc($accessBank_apimSubKey,$accessBank_auditId,$accessBank_appId,$docName,$fullDocPath){
        
        //Get file content from the location
        $img = file_get_contents($fullDocPath); 
          
        // Encode the image string data into base64 
        $imageData = base64_encode($img); 
        
        //GET ENDPOINT FOR SUNTRUST BANK
        $search_restapi = mysqli_query($this->link, "SELECT * FROM restful_apisetup WHERE api_name = 'accessBank_uploadDoc'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
        
        $dataToPost = array(
            "auditId"=> $accessBank_auditId,
            "appId"=> $accessBank_appId,
            "name"=> $docName,
            "content"=> "data:image/jpg;base64,".$imageData
            );
            
        $processImagecall = $this->sunTrustCallAPI('POST', $api_url, json_encode($dataToPost), $accessBank_apimSubKey, $accessBank_auditId, $accessBank_appId);
                           
        $callImageResult = json_decode($processImagecall, true);
              
        return $callImageResult;
        
    }
    
    public function accessAccountOpening($accessBank_apimSubKey,$accessBank_auditId,$accessBank_appId,$docName,$fullDocPath,$accounttier,$title,$profession,$maritalStatus,$middleName,$religion,$addressLine,$city,$state,$customerEmail,$phoneNumber,$userBvn){
        
        //GET ENDPOINT FOR ACCESS BANK
        $search_restapi = mysqli_query($this->link, "SELECT * FROM restful_apisetup WHERE api_name = 'accessBank_accountOpening'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
        
        $passImageDoc = $this->accessBankUploadDoc($accessBank_apimSubKey,$accessBank_auditId,$accessBank_appId,$docName,$fullDocPath);
        $imageId = $passImageDoc['data']['reference'];
        
        $dataToPost = array(
            "accountTier"=> $accounttier,
            "title"=> $title,
            "profession"=> $profession,
            "maritalStatus"=> $maritalStatus,
            "maidenName"=> $middleName,
            "religion"=> $religion,
            "address"=> [
                "addressLine"=> $addressLine,
                "city"=> $city,
                "state"=> $state
                ],
                "emailNumber"=> $customerEmail,
                "mobileNumber"=> $phoneNumber,
                "bvn"=> $userBvn,
                "documents"=> [
                    "id"=> $imageId,
                    "class"=> "Signature"
                    ],
                    "auditId"=> $accessBank_auditId,
                    "appId"=> $accessBank_appId
            );
            
        $processcall = $this->accessBankCallAPI('POST', $api_url, json_encode($dataToPost), $accessBank_apimSubKey, $accessBank_auditId, $accessBank_appId);
                           
        $callResult = json_decode($processcall, true);
              
        return $callResult;
        
    }
    
    
    
    
    
     /**
     * Function to generate / verify SunTrust Bank Account Creation using SunTrust Endpoint
     * @group string
     */
    public function sunTrustCallAPI($mymethod, $myurl, $mydata, $onePipeApiKey, $onePipeSKey, $ReqReference){
        
       $curl4 = curl_init();
    
       switch ($mymethod){
          case "POST":
             curl_setopt($curl4, CURLOPT_POST, 1);
             if ($mydata)
                curl_setopt($curl4, CURLOPT_POSTFIELDS, $mydata);
             break;
          case "PUT":
             curl_setopt($curl4, CURLOPT_CUSTOMREQUEST, "PUT");
             if ($mydata)
                curl_setopt($curl4, CURLOPT_POSTFIELDS, $mydata);			 					
             break;
          default:
             if ($mydata)
                $myurl = sprintf("%s?%s", $myurl, http_build_query($mydata));
       }
    
       // OPTIONS:
       curl_setopt($curl4, CURLOPT_URL, $myurl);
       curl_setopt($curl4, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: Bearer ".$onePipeApiKey,
        "Signature: ".md5($ReqReference.";".$onePipeSKey)
       ));
       curl_setopt($curl4, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl4, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    
       // EXECUTE:
       $myresult = curl_exec($curl4);
       if(curl_error($curl4)){
    		echo 'error:' . curl_error($curl4);
    	}
       curl_close($curl4);
       
       return $myresult;
       
    }
    
    public function sunTrustAccountOpening($phoneNumber,$onePipeSKey,$onePipeApiKey,$walletafrica_skey,$ReqReference,$TxtReference,$title,$firstName,$surName,$middleName,$dob,$customerEmail,$gender,$addressLine,$city,$state,$country){
        
        //GET ENDPOINT FOR SUNTRUST BANK
        $search_restapi = mysqli_query($this->link, "SELECT * FROM restful_apisetup WHERE api_name = 'suntrust_account_opening'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $myapi_url = $fetch_restapi->api_url;
        
        $postMyData =  array(
        	"request_ref"=> $ReqReference,
            "request_type"=> "open_account",
            "auth"=>[
                 "type"=> null, //"bvn",
                 "secure"=> null, //$this->encryptText($onePipeSKey,$userBvn),
                 "auth_provider"=> "SunTrust",
                 "route_mode"=> null,
                ],
                "transaction"=>[
                    "mock_mode"=> "live",
                    "transaction_ref"=> $TxtReference,
                    "transaction_desc"=> "A random transaction",
                    "transaction_ref_parent"=> null,
                    "amount"=> 0,
                    "customer"=> [
                        "customer_ref"=> $TxtReference,
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
                            "name_on_account"=> $surName.' '.$firstName.' '.$middleName,
                            "middlename"=> $middleName,
                            "dob"=> $dob,
                            "gender"=> $gender,
                            "title"=> $title,
                            "address_line_1"=> $addressLine,
                            "address_line_2"=> "none",
                            "city"=> $city,
                            "state"=> $state,
                            "country"=> $country
                            ],
                    ]
    	        );
    	
    	$make_mycall = $this->sunTrustCallAPI('POST', $myapi_url, json_encode($postMyData), $onePipeApiKey, $onePipeSKey, $ReqReference);
                           
        return $make_mycall;
    
    }
    
    public function sunTrustOTPAOConfirmation($otp,$ReqReference,$TxtReference,$onePipeSKey,$onePipeApiKey){
        
        //GET ENDPOINT FOR SUNTRUST BANK
        $search_restapi = mysqli_query($this->link, "SELECT * FROM restful_apisetup WHERE api_name = 'suntrust_otp_validation'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
        
        $postMyData =  array(
            "request_ref"=> $ReqReference,
            "request_type"=> "open_account",
            "auth"=>[
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
     * Function to generate Rubbies MFB Virtual Bank Account
     * @group string
     */
    public function rubiesVirtualAccount($accountName,$rubbiesSecKey){
        
        $curl = curl_init();
        
        $amount = "1";
        
        $amountcontrol = "VARIABLEAMOUNT";
        
        $daysactive = "0";
        
        $minutesactive = "52560000";
        
        $rubbiesCallbackUrl = "https://esusu.app/config/rubbiesCallback.php";
        
        //GENERATE VIRTUAL ACCOUNT ON MONIFY
        $search_restapi = mysqli_query($this->link, "SELECT * FROM restful_apisetup WHERE api_name = 'rubies_create_virtual_account'");
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
                'virtualaccountname'=>$accountName,
                'amount'=>$amount,
                'amountcontrol'=>$amountcontrol,
                'daysactive'=>$daysactive,
                'minutesactive'=>$minutesactive,
                'callbackurl'=>$rubbiesCallbackUrl,
                'singleuse'=>'N'
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


    /**
     * Function to generate Providus Bank Virtual Account
     * @group string
     */
    public function providusVirtualAccount($accountName,$userBvn,$providusClientId,$providusClientSecret){
        
        $curl = curl_init();

        $encodeAUth = $providusClientId.":".$providusClientSecret;
        $providusSignature = strtoupper(hash('sha512', $encodeAUth));
        
        //GENERATE VIRTUAL ACCOUNT ON MONIFY
        $search_restapi = mysqli_query($this->link, "SELECT * FROM restful_apisetup WHERE api_name = 'providus_baseUrl'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url."PiPCreateReservedAccountNumber";

        //Disable CURLOPT_SSL_VERIFYHOST and CURLOPT_SSL_VERIFYPEER by
        //setting them to false.
        //dirname(__FILE__)."/cacert.pem"
        //CURLOPT_CAINFO => dirname(__FILE__)."/cacert.pem",
        //CURLOPT_CAPATH => dirname(__FILE__)."/cacert.pem",
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode([
                'account_name'=>$accountName,
                'bvn'=>$userBvn
            ]),
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Client-Id: ".$providusClientId,
            "X-Auth-Signature: ".$providusSignature
          ),
        ));
        
        $response = curl_exec($curl);
        $providus_generate = json_decode($response, true);
              
        return $providus_generate;
    
    }


    /**
     * Function to generate Wema Bank Virtual Account
     * @group string
     */
    public function directWemaVirtualAccount($accountName,$wemaVAPrefix){

        $generateVANo = $wemaVAPrefix.rand(100,999).date("ds");

        $searchVA = mysqli_query($this->link, "SELECT * FROM virtual_account WHERE account_number = '$generateVANo'");
        $fetchVANum = mysqli_num_rows($search_restapi);
        $realVANo = ($fetchVANum == 0) ? $generateVANo : $wemaVAPrefix.date("ds").rand(100,999);

        return [
            "responseCode" => "00",
            "account_name" => $accountName,
            "account_number" => $realVANo
        ];

    }
        




    /**
     * Function to generate Kuda Virtual Bank Account
     * @group string
     */
    public function RSAEncrypt($data, $publicKey)
	{
        $rsa = new Crypt_RSA();

        $rsa->loadKey($publicKey);

        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);

        $encryptedData = $rsa->encrypt($data);

        $encodedData = base64_encode($encryptedData);
        return $encodedData;

    }

    public function RSADecrypt($data, $privateKey)
	{
        $rsa = new Crypt_RSA();
        $rsa->loadKey($privateKey);
        
        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        $decodedData = base64_decode($data);
        $decryptedData = $rsa->decrypt($decodedData);
        return $decryptedData;

    }
	
	public function AESEncrypt($data, $password)
	{
		$cipher = new Crypt_Rijndael();
		$cipher->setPassword($password, 'pbkdf2', 'sha1', 'randomsalt', 1000, 256 / 8);
		$encryptedData = $cipher->encrypt($data);
		$encodedData = base64_encode($encryptedData);
		return $encodedData;
	}
	
	public function AESDecrypt($data, $password)
	{
		$cipher = new Crypt_Rijndael();
		$decodedData = base64_decode($data);
		$cipher->setPassword($password, 'pbkdf2', 'sha1', 'randomsalt', 1000, 256 / 8);
		$decryptedData = $cipher->decrypt($decodedData);
		return $decryptedData;
    }
    
    public function kudaVirtualAccount($ReqReference,$customerEmail,$phoneNumber,$surName,$firstName,$trackingReference,$kudaClientID){
        
        $curl = curl_init();

        $publicKey = __DIR__ . 'kudaPublicKey.xml';
        $privateKey = __DIR__ . 'kudaPrivateKey.xml';

        $keyCombine = $kudaClientID . "-" . uniqid();

        $password = $this->RSAEncrypt($keyCombine, $publicKey);

        $data = array(
            "serviceType"=>"ADMIN_CREATE_VIRTUAL_ACCOUNT",
            "requestRef"=>$ReqReference,
            "data"=>[
                "email"=>$customerEmail,
                "phoneNumber"=>$phoneNumber,
                "lastName"=>$surName,
                "firstName"=>$firstName,
                "trackingReference"=>$trackingReference
            ]
        );

        $mydata = $this->AESEncrypt($data, $password);

        //GENERATE VIRTUAL ACCOUNT ON MONIFY
        $search_restapi = mysqli_query($this->link, "SELECT * FROM restful_apisetup WHERE api_name = 'rubies_create_virtual_account'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = "https://sandbox.kudabank.com/v1"; //$fetch_restapi->api_url;

        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $mydata, //json_encode([$request]),
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json"
          ),
        ));
        
        $response = curl_exec($curl);
        $kuda_generate = json_decode($response, true);
              
        return $response;
    
    }
    
    
    public function sterlingVirtualAccount($firstName,$lastName,$phoneNumber,$dob,$gender,$currencyCode,$inputKey,$iv){
        
        $curl = curl_init();
        
        //GET ENDPOINT
        $search_restapi = mysqli_query($this->link, "SELECT * FROM restful_apisetup WHERE api_name = 'sterling_baseUrl'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url."/api/Wallet/CreateWallet";

        $mygender = ($gender == "Male") ? "M" : "F";
        $data_to_send_server = json_encode(['firstname'=>$firstName,'lastname'=>$lastName,'mobile'=>$phoneNumber,'DOB'=>$dob,'Gender'=>$mygender,'CURRENCYCODE'=>$currencyCode,'AccountTier'=>1,'ChannelID'=>56,'ProductID'=>1,'MobileNotNuban'=>true]);
        $blockSize = 128;
        $aes = new AESEncryption($data_to_send_server, $inputKey, $iv, $blockSize);
        $encryptData = base64_decode($aes->encrypt());
        $binToHex = bin2hex($encryptData);
        $dataPasser = json_encode(['Data'=>$binToHex]);
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $dataPasser,
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json"
          ),
        ));
        
        $response = curl_exec($curl);
        $process = json_decode($response, true);
        $hextobin = base64_encode(pack('H*',$process));
        $aes->setData($hextobin);
        $descriptResponse = $aes->decrypt();
        $decodeResponse = json_decode($descriptResponse, true);
        
        return $decodeResponse;
        
    }
    
     
}

$newVA = new GenerateBankAccount($link);

?>