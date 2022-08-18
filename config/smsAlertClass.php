<?php

/**
 * Class for all SMS/Email Notification
 * 
 * Created by AKINADE AYODEJI TIMOTHEW on 1/3/2021
 * Objective: building to scale
 */
 
error_reporting(E_ERROR | E_PARSE);
 
class smsALert{

    public $link, $ozeki_password, $ozeki_url;

    public $sender, $phone, $msg, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet, $userType, $vendorid;
    
    public $creatorEmail, $converted_date, $new_reference, $customer, $myfullname, $real_subscription_code, $plancode, $categories; 
    
    public $plan_name, $mybank_name, $account_number, $b_name, $plancurrency, $amountpaid;

    public $emailReceiver, $date_from, $expiry_date, $sub_plan, $sub_token, $total_amountpaid, $name, $calc_bonus, $subType;

    public $official_email, $iname, $iusername, $ipassword, $sender_id;

    public $email, $fname, $companyid, $shortenedurl, $username, $password, $url, $ptype, $account;

    public $currency, $amount, $bal, $remark, $final_date_time, $myname, $totalwallet_balance, $senderName, $senderAccount;

    public $em, $transactionType, $txid, $uname, $inst_name, $ln, $fn, $icurrency, $total, $senderEmail, $myRemainingBalance;

    public $myAccountNumber, $shortenedurl1, $icname, $portalLink, $sysabb, $fullname, $tReference, $transactionDateTime;

    public $tType, $RRN, $lname, $PAN_Masked, $DateTime, $CurrCode, $TrxnAmount, $availbal, $accountName, $recipientAcctNo;

    public $amountWithNoCharges, $senderBalance, $mycurrentTime, $theStatus, $channel, $account_no, $lid, $amount_to_pay, $final_bal;

    public $company_email, $hod_email, $dept_name, $uaccount, $bfull_name, $LID, $review_date, $charges_name;

    public $emailConfigStatus, $fetch_emailConfig, $acctno, $merchantName;

    public $TransactionID, $responsemessage, $paymentMethod, $bank, $settlmentType, $oprName, $SubMerchantName, $currencyCode;
    
    public $pendingBal, $detectStampDutyforAuto, $charges, $mywallet_balance, $subject, $terminalId, $receiverEmail;

    public $status, $trace_id, $merchant_name, $merchant_email, $merchant_phone_no, $activationFee;

    public $pbal, $scharge, $sstatus, $amt_to_settle, $settledAMount;

    public $myiemail_addrs, $cashier, $formated_settled_fund, $formated_balance, $companyname, $customUrl;

    public $docType, $custName, $scode, $planName, $plancat, $planamount, $custVAActNo, $mdate, $type, $msg_content;

    public $pageHeader, $newLocation, $ipAddress, $browserName, $deviceName, $activationCode, $enrollment_type;

    public $uploaded_file, $uploaded_fileTMP, $accountid, $file_title, $sendData, $isbranchid;

    public function __construct($link){
        
        $this->link = $link;
        $this->ozeki_password = $ozeki_password;
        $this->ozeki_url = $ozeki_url;
        $this->sender = $sender;
        $this->phone = $phone;
        $this->msg = $msg;
        $this->debitWallet = $debitWallet; //Should be Yes or No
        $this->institution_id = $institution_id;
        $this->refid = $refid;
        $this->sms_rate = $sms_rate;
        $this->iuid = $iuid;
        $this->mybalance = $mybalance;
        $this->userType = $userType;
        $this->vendorid = $vendorid;
        $this->creatorEmail = $creatorEmail;
        $this->converted_date = $converted_date;
        $this->new_reference = $new_reference;
        $this->customer = $customer;
        $this->myfullname = $myfullname;
        $this->real_subscription_code = $real_subscription_code;
        $this->plancode = $plancode;
        $this->categories = $categories;
        $this->plan_name = $plan_name;
        $this->mybank_name = $mybank_name;
        $this->account_number = $account_number;
        $this->b_name = $b_name;
        $this->plancurrency = $plancurrency;
        $this->amountpaid = $amountpaid;
        $this->emailReceiver = $emailReceiver;
        $this->date_from = $date_from;
        $this->expiry_date = $expiry_date;
        $this->sub_plan = $sub_plan;
        $this->sub_token = $sub_token;
        $this->total_amountpaid = $total_amountpaid;
        $this->name = $name;
        $this->calc_bonus = $calc_bonus;
        $this->subType = $subType;
        $this->official_email = $official_email;
        $this->iname = $iname;
        $this->iusername = $iusername;
        $this->ipassword = $ipassword;
        $this->sender_id = $sender_id;
        $this->email = $email;
        $this->fname = $fname;
        $this->companyid = $companyid;
        $this->shortenedurl = $shortenedurl;
        $this->username = $username;
        $this->password = $password;
        $this->url = $url;
        $this->ptype = $ptype;
        $this->account = $account;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->bal = $bal;
        $this->remark = $remark;
        $this->em = $em;
        $this->transactionType = $transactionType;
        $this->txid = $txid;
        $this->uname = $uname;
        $this->inst_name = $inst_name;
        $this->ln = $ln;
        $this->fn = $fn;
        $this->icurrency = $icurrency;
        $this->total = $total;
        $this->final_date_time = $final_date_time;
        $this->myname = $myname;
        $this->totalwallet_balance = $totalwallet_balance;
        $this->senderEmail = $senderEmail;
        $this->$senderName = $senderName;
        $this->senderAccount = $senderAccount;
        $this->merchantName = $merchantName;
        $this->myRemainingBalance = $myRemainingBalance;
        $this->myAccountNumber = $myAccountNumber;
        $this->shortenedurl1 = $shortenedurl1;
        $this->icname = $icname;
        $this->portalLink = $portalLink;
        $this->tType = $tType;
        $this->RRN = $RRN;
        $this->lname = $lname;
        $this->PAN_Masked = $PAN_Masked;
        $this->DateTime = $DateTime;
        $this->CurrCode = $CurrCode;
        $this->TrxnAmount = $TrxnAmount;
        $this->availbal = $availbal;
        $this->sysabb = $sysabb;
        $this->fullname = $fullname;
        $this->tReference = $tReference;
        $this->transactionDateTime = $transactionDateTime;
        $this->accountName = $accountName;
        $this->recipientAcctNo = $recipientAcctNo;
        $this->amountWithNoCharges = $amountWithNoCharges;
        $this->senderBalance = $senderBalance;
        $this->mycurrentTime = $mycurrentTime;
        $this->theStatus = $theStatus;
        $this->channel = $channel;
        $this->account_no = $account_no;
        $this->lid = $lid;
        $this->amount_to_pay = $amount_to_pay;
        $this->final_bal = $final_bal;
        $this->company_email = $company_email;
        $this->hod_email = $hod_email;
        $this->dept_name = $dept_name;
        $this->uaccount = $uaccount;
        $this->bfull_name = $bfull_name;
        $this->LID = $LID;
        $this->review_date = $review_date;
        $this->charges_name = $charges_name;
        $this->emailConfigStatus = $emailConfigStatus;
        $this->fetch_emailConfig = $fetch_emailConfig;
        $this->acctno = $acctno;
        $this->TransactionID = $TransactionID;
        $this->responsemessage = $responsemessage;
        $this->paymentMethod = $paymentMethod;
        $this->bank = $bank;
        $this->settlmentType = $settlmentType;
        $this->oprName = $oprName;
        $this->SubMerchantName = $SubMerchantName;
        $this->currencyCode = $currencyCode;
        $this->pendingBal = $pendingBal;
        $this->detectStampDutyforAuto = $detectStampDutyforAuto;
        $this->charges = $charges;
        $this->mywallet_balance = $mywallet_balance;
        $this->subject = $subject;
        $this->terminalId = $terminalId;
        $this->receiverEmail = $receiverEmail;
        $this->status = $status;
        $this->trace_id = $trace_id;
        $this->merchant_name = $merchant_name;
        $this->merchant_email = $merchant_email;
        $this->merchant_phone_no = $merchant_phone_no;
        $this->activationFee = $activationFee;
        $this->pbal = $pbal;
        $this->scharge = $scharge;
        $this->sstatus = $sstatus;
        $this->amt_to_settle = $amt_to_settle;
        $this->settledAMount = $settledAMount;
        $this->myiemail_addrs = $myiemail_addrs;
        $this->cashier = $cashier;
        $this->formated_settled_fund = $formated_settled_fund;
        $this->formated_balance = $formated_balance;
        $this->companyname = $companyname;
        $this->customUrl = $customUrl;
        $this->docType = $docType;
        $this->custName = $custName;
        $this->scode = $scode;
        $this->planName = $planName;
        $this->plancat = $plancat;
        $this->planamount = $planamount;
        $this->custVAActNo = $custVAActNo;
        $this->mdate = $mdate;
        $this->pageHeader = $pageHeader;
        $this->newLocation = $newLocation;
        $this->ipAddress = $ipAddress;
        $this->browserName = $browserName;
        $this->deviceName = $deviceName;
        $this->activationCode = $activationCode;
        $this->type = $type;
        $this->enrollment_type = $enrollment_type;
        $this->msg_content = $msg_content;
        $this->uploaded_file = $uploaded_file;
        $this->uploaded_fileTMP = $uploaded_fileTMP;
        $this->accountid = $accountid;
        $this->file_title = $file_title;
        $this->sendData = $sendData;
        $this->isbranchid = $isbranchid;

    }

    // Function to check string starting 
    // with given substring 
    public function startsWith($string, $startString) 
    {

      $len = strlen($startString); 
      return (substr($string, 0, $len) === $startString);

    }

    public function formatDateTime($date_time){

      $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
      $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
      $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
      $correctdate = $acst_date->format('Y-m-d g:i A');
      return $correctdate;

    }

    /**
    * Function to convert base64 to jpeg
    * @group string 
    */
    public function base64Tojpeg($bvn_picture, $dynamicStr) {
    
      $ifp = fopen( '../img/'.$dynamicStr, "wb" );
      fwrite( $ifp, base64_decode( $bvn_picture) );
      fclose( $ifp );
      return( $dynamicStr );
      
    }

    //Upload Attachment
    public function uploadAttachement($uploaded_file, $uploaded_fileTMP, $accountid, $account, $file_title){

      $date_time = date("Y-m-d h:i:s");

      foreach($uploaded_file as $key => $name){

          $newFilename = $name;
          
          if($newFilename == "")
          {
              //do nothing
          }
          else{
              $newlocation = $newFilename;
              if(move_uploaded_file($uploaded_fileTMP[$key], '../img/'.$newFilename))
              {

                  mysqli_query($this->link, "INSERT INTO attachment VALUES(null, '', '$accountid', '$account', '$newlocation', '$file_title', 'Pending', '$date_time')");

              }
          }

      }

  }


  public function identityVerifier2($sendData, $institution_id, $isbranchid, $iuid){

    $curl = curl_init();

    $search_restapi = mysqli_query($this->link, "SELECT * FROM restful_apisetup WHERE api_name = 'idverify_identityApiUrl1'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;

    $verification_type = $sendData['verification_type'];
    $transactionReference = $sendData['transactionReference'];
    $searchParameter = $sendData['searchParameter'];
    $firstName = $sendData['firstName'];
    $lastName = $sendData['lastName'];
    $gender = $sendData['gender'];
    $dob = $sendData['dob'];
    $userid = $sendData['userid'];
    $apiKey = $sendData['apiKey'];

    if($verification_type == "NIN-SEARCH" || $verification_type == "NIN-PHONE-SEARCH" || $verification_type == "BVN-FULL-DETAILS"){

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
          'searchParameter' => $searchParameter,
          'transactionReference' => $transactionReference,
          'verificationType' => $verification_type
        ]),
        CURLOPT_HTTPHEADER => array(
          "userid: ".$userid,
          "apiKey: ".$apiKey,
          "Content-Type: application/json"
        ),
      ));

      $response = curl_exec($curl);
      $decodResponse = json_decode($response, true);
          
      return $decodResponse;

    }elseif($verification_type == "NIN-DEMOGRAPHIC-SEARCH"){

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
          'firstName' => $firstName,
          'lastName' => $lastName,
          'gender' => $gender,
          'dob' => $dob,
          'transactionReference' => $transactionReference,
          'verificationType' => $verification_type
        ]),
        CURLOPT_HTTPHEADER => array(
          "userid: ".$userid,
          "apiKey: ".$apiKey,
          "Content-Type: application/json"
        ),
      ));

      $response = curl_exec($curl);
      $decodResponse = json_decode($response, true);
          
      return $decodResponse;

    }else{

      //DO Nothing

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

  





    /**
     * GENERAL ALERT FOR:
     * 
     * DEPOSIT
     * WITHDRAWAL
     * WITHDRAWAL CHARGES
     * OTP
     * CUSTOMER REGISTRATION
     * WALLET CREATION
     * LOAN BOOKING
     * LOAN INTERNAL REVIEW NOTIFICATION TO HOD
     * LOAN APPROVAL
     * LOAN DISBURSE
     * LOAN DISAPPROVAL
     * LOAN REPAYMENT
     * P-to-P TRANSFER
     */
     
    public function file_get_contents_curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        $err = curl_error($ch);
            
        if($err){
            return "cURL Error #:" . $err;
        }else{
            return $data;
        }
    }

    
    public function instGeneralAlert($ozeki_password, $ozeki_url, $sender, $phone, $msg, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet){
        
        //ini_set('display_errors', true);
        //error_reporting(-1);

        $instType = ($this->startsWith($institution_id,"MER") ? "merchant" : ($this->startsWith($institution_id,"INST") ? "institution" : "agent"));

        $url = 'action=send-sms';
        $url.= '&api_key='.$ozeki_password;
        $url.= '&to='.urlencode($phone);
        $url.= '&from='.urlencode($sender);
        $url.= '&sms='.urlencode($msg);
      
        //Capture complete processing URL
        $urltouse =  $ozeki_url.$url;
      
        //Open the URL to send the message
        $response = $this->file_get_contents_curl($urltouse);
        
        //Confirm the response from Gateway
        $getResponse = json_decode($response, true);
        
        $okStatus = $getResponse['code'];

        if($okStatus == "ok"){

            $date_time = date("Y-m-d h:i:s");
            //Capture API Response
            mysqli_query($this->link, "INSERT INTO api_txtwaitinglist VALUE(null,'$iuid','$refid','$urltouse','$response','$date_time')");

            ($debitWallet == "Yes") ? mysqli_query($this->link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$phone','','$sms_rate','Debit','NGN','Charges','SMS Content: $msg','successful','$date_time','$iuid','$mybalance','')") : "";
            ($debitWallet == "Yes") ? mysqli_query($this->link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','$instType','$sender','$phone','$msg','Sent',NOW())") : "";
            ($debitWallet == "Yes") ? mysqli_query($this->link, "UPDATE institution_data SET wallet_balance = '$mybalance' WHERE institution_id = '$institution_id'") : "";

        }

    }


    public function userGeneralAlert($ozeki_password, $ozeki_url, $sender, $phone, $msg, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet, $userType){
        
        $instType = ($this->startsWith($institution_id,"MER") ? "merchant" : ($this->startsWith($institution_id,"INST") ? "institution" : ($this->startsWith($institution_id,"AGGR") ? "aggregator" : "agent")));

        $url = 'action=send-sms';
        $url.= '&api_key='.$ozeki_password;
        $url.= '&to='.urlencode($phone);
        $url.= '&from='.urlencode($sender);
        $url.= '&sms='.urlencode($msg);
      
        //Capture complete processing URL
        $urltouse =  $ozeki_url.$url;
      
        //Open the URL to send the message
        $response = $this->file_get_contents_curl($urltouse);

        //Confirm the response from Gateway
        $getResponse = json_decode($response, true);
        
        $okStatus = $getResponse['code'];

        if($okStatus == "ok"){

            $date_time = date("Y-m-d h:i:s");
            //Capture API Response
            mysqli_query($this->link, "INSERT INTO api_txtwaitinglist VALUE(null,'$iuid','$refid','$urltouse','$response','$date_time')");

            ($debitWallet == "Yes") ? mysqli_query($this->link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$phone','','$sms_rate','Debit','NGN','Charges','SMS Content: $msg','successful','$date_time','$iuid','$mybalance','')") : "";
            ($debitWallet == "Yes") ? mysqli_query($this->link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','$instType','$sender','$phone','$msg','Sent',NOW())") : "";
            ($debitWallet == "Yes" && $userType == "user") ? mysqli_query($this->link, "UPDATE user SET transfer_balance = '$mybalance' WHERE id = '$iuid'") : "";
            ($debitWallet == "Yes" && $userType == "customer") ? mysqli_query($this->link, "UPDATE borrowers SET wallet_balance = '$mybalance' WHERE account = '$iuid'") : "";

        }

    }
    
    
    public function backendGeneralAlert($sender, $phone, $msg, $refid, $sms_rate, $iuid){
        
        $search_gateway = mysqli_query($this->link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
        $fetch_gateway = mysqli_fetch_array($search_gateway);
        $ozeki_password = $fetch_gateway['password'];
        $ozeki_url = $fetch_gateway['api'];

        $url = 'action=send-sms';
        $url.= '&api_key='.$ozeki_password;
        $url.= '&to='.urlencode($phone);
        $url.= '&from='.urlencode($sender);
        $url.= '&sms='.urlencode($msg);
      
        //Capture complete processing URL
        $urltouse =  $ozeki_url.$url;
      
        //Open the URL to send the message
        $response = $this->file_get_contents_curl($urltouse);

        //Confirm the response from Gateway
        $getResponse = json_decode($response, true);
        
        $okStatus = $getResponse['code'];

        if($okStatus == "ok"){

            $date_time = date("Y-m-d h:i:s");
            $query = mysqli_query($this->link, "SELECT * FROM systemset") or die (mysqli_error($link));
            $r = mysqli_fetch_array($query);
            $sysabb = $r['abb'];
            
            //Capture API Response
            mysqli_query($this->link, "INSERT INTO api_txtwaitinglist VALUE(null,'$iuid','$refid','$urltouse','$response','$date_time')");

            mysqli_query($this->link, "INSERT INTO wallet_history VALUE(null,'','$refid','$phone','','$sms_rate','Debit','NGN','Charges','SMS Content: $msg','successful','$date_time','$iuid','','')");
            mysqli_query($this->link, "INSERT INTO sms_logs1 VALUES(null,'','esusubackend','$sysabb','$phone','$msg','Sent',NOW())");

        }

    }


    public function vendorGeneralAlert($sender, $phone, $msg, $institution_id, $refid, $sms_rate, $iuid, $mybalance, $debitWallet, $vendorid){
        
        $search_gateway = mysqli_query($this->link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
        $fetch_gateway = mysqli_fetch_array($search_gateway);
        $ozeki_password = $fetch_gateway['password'];
        $ozeki_url = $fetch_gateway['api'];

        $url = 'action=send-sms';
        $url.= '&api_key='.$ozeki_password;
        $url.= '&to='.urlencode($phone);
        $url.= '&from='.urlencode($sender);
        $url.= '&sms='.urlencode($msg);
      
        //Capture complete processing URL
        $urltouse =  $ozeki_url.$url;
      
        //Open the URL to send the message
        $response = $this->file_get_contents_curl($urltouse);

        //Confirm the response from Gateway
        $getResponse = json_decode($response, true);
        
        $okStatus = $getResponse['code'];

        if($okStatus == "ok"){

            $date_time = date("Y-m-d h:i:s");
            $query = mysqli_query($this->link, "SELECT * FROM systemset") or die (mysqli_error($link));
            $r = mysqli_fetch_array($query);
            $sysabb = $r['abb'];
            
            //Capture API Response
            mysqli_query($this->link, "INSERT INTO api_txtwaitinglist VALUE(null,'$iuid','$refid','$urltouse','$response','$date_time')");

            mysqli_query($this->link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$phone','','$sms_rate','Debit','NGN','Charges','SMS Content: $msg','successful','$date_time','$iuid','$mybalance','')");
            mysqli_query($this->link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','vendor','$sysabb','$phone','$msg','Sent',NOW())");
            mysqli_query($this->link, "UPDATE vendor_reg SET wallet_balance = '$mybalance' WHERE companyid = '$vendorid'");

        }

    }

    public function smsWithNoCharges($sender, $phone, $msg, $refid, $iuid){
        
        $search_gateway = mysqli_query($this->link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
        $fetch_gateway = mysqli_fetch_array($search_gateway);
        $ozeki_password = $fetch_gateway['password'];
        $ozeki_url = $fetch_gateway['api'];

        $url = 'action=send-sms';
        $url.= '&api_key='.$ozeki_password;
        $url.= '&to='.urlencode($phone);
        $url.= '&from='.urlencode($sender);
        $url.= '&sms='.urlencode($msg);
      
        //Capture complete processing URL
        $urltouse =  $ozeki_url.$url;
      
        //Open the URL to send the message
        $response = $this->file_get_contents_curl($urltouse);

        //Confirm the response from Gateway
        $getResponse = json_decode($response, true);
        
        $okStatus = $getResponse['code'];

        if($okStatus == "ok"){

            $date_time = date("Y-m-d h:i:s");
            
            //Capture API Response
            mysqli_query($this->link, "INSERT INTO api_txtwaitinglist VALUE(null,'$iuid','$refid','$urltouse','$response','$date_time')");

        }

    }


    public function batchSmsWithNoCharges($bozeki_password, $bozeki_url, $sender, $phone, $msg, $refid, $iuid, $debitWallet){
        
      $url = 'action=send-sms';
      $url.= '&api_key='.$bozeki_password;
      $url.= '&to='.urlencode($phone);
      $url.= '&from='.urlencode($sender);
      $url.= '&sms='.urlencode($msg);
    
      //Capture complete processing URL
      $urltouse =  $bozeki_url.$url;
    
      //Open the URL to send the message
      $response = $this->file_get_contents_curl($urltouse);

      //Confirm the response from Gateway
      $getResponse = json_decode($response, true);
      
      $okStatus = $getResponse['code'];

      if($okStatus == "ok"){

          $date_time = date("Y-m-d h:i:s");
          
          //Capture API Response
          mysqli_query($this->link, "INSERT INTO api_txtwaitinglist VALUE(null,'$iuid','$refid','$urltouse','$response','$date_time')");

      }

  }
    
    
    
    /**
     * GENERAL EMAIL NOTIFICATION FOR:
     * 
     * DEPOSIT
     * WITHDRAWAL
     * WITHDRAWAL CHARGES
     * OTP
     * CUSTOMER REGISTRATION
     * WALLET CREATION
     * LOAN BOOKING
     * LOAN INTERNAL REVIEW NOTIFICATION TO HOD
     * LOAN APPROVAL
     * LOAN DISBURSE
     * LOAN DISAPPROVAL
     * LOAN REPAYMENT
     * P-to-P TRANSFER
     * PRODUCT PAYMENT
     */
    
    
     //New Payment for Product Subscription
     public function productPaymentNotifier($creatorEmail, $converted_date, $new_reference, $customer, $myfullname, $real_subscription_code, $plancode, $categories, $plan_name, $mybank_name, $account_number, $b_name, $plancurrency, $amountpaid, $emailConfigStatus, $fetch_emailConfig){
         
        $result = array();
        $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
        $r = mysqli_fetch_array($systemset);
        
        // Pass the customer's authorisation code, email and amount
        $postdata = array(
            "From"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
            "To"        => $creatorEmail,  //Receiver Email Address
            "TemplateId"    => '17983915',
            "TemplateModel" => [
                    "trans_date"        => $converted_date,  //Date Time
                    "refid"             => $new_reference,
                    "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
                    "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
                    "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
                    "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
                    "customer_id"       => $customer,
                    "customer_name"     => $myfullname,
                    "sub_code"          => $real_subscription_code,
                    "plan_code"         => $plancode,
                    "plan_cat"          => $categories,
                    "plan_name"         => $plan_name,
                    "payer_bank"        => $mybank_name,
                    "payer_acctno"      => $account_number,
                    "payer_acctname"    => $b_name,
                    "amount_paid"       => $plancurrency.number_format($amountpaid,2,'.',','),
                    "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
                    "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
                    "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
                    "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
                ]
            );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $headers = [
          'Accept: application/json',
          'Content-Type: application/json',
          'X-Postmark-Server-Token: '.$r['email_token']
        ];
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $request = curl_exec($ch);
        
        curl_close ($ch);
        if ($request) {
          $result = json_decode($request, true);
          if($result['Message'] == "OK")
          {
            //echo "Email Sent Successfully";
          }else{
            //echo "Error Code: ".$result['ErrorCode'];
          }
        }
         
     }

    
    //New Subscription Alarm for Bank - Wallet 
    public function productPaymentViaBankTransferNotifier($creatorEmail, $converted_date, $new_reference, $acctno, $myfullname, $real_subscription_code, $plancode, $categories, $plan_name, $mybank_name, $account_number, $b_name, $plancurrency, $amountpaid, $emailConfigStatus, $fetch_emailConfig){
         
      $result = array();
      $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
      $r = mysqli_fetch_array($systemset);
      
      // Pass the customer's authorisation code, email and amount
      $postdata =  array(
        "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
        "To"            => $creatorEmail,  //Receiver Email Address
        "TemplateId"    => '17414648',
        "TemplateModel" => [
            "trans_date"        => $converted_date,  //Date Time
            "refid"             => $new_reference,
            "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
            "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
            "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
            "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
            'customer_id'       => $acctno,
            'customer_name'     => $myfullname,
            'sub_code'          => $real_subscription_code,
            'plan_code'         => $plancode,
            'plan_cat'          => $categories,
            'plan_name'         => $plan_name,
            'payer_bank'        => $mybank_name,
            'payer_acctno'      => $account_number,
            'payer_acctname'    => $b_name,
            'amount_paid'       => $plancurrency.number_format($amountpaid,2,'.',','),
            "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
            "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
            "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
            "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
          ]
        );
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      
      $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
        'X-Postmark-Server-Token: '.$r['email_token']
      ];
      
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      
      $request = curl_exec($ch);
      
      curl_close ($ch);
      if ($request) {
        $result = json_decode($request, true);
        if($result['Message'] == "OK")
        {
          //echo "Email Sent Successfully";
        }else{
          //echo "Error Code: ".$result['ErrorCode'];
        }
      }
       
    }

    //Saas Subscription Receipt
    public function saasSubPmtNotifier($emailReceiver, $refid, $date_from, $expiry_date, $plan_name, $sub_plan, $institution_id, $sub_token, $total_amountpaid, $name, $calc_bonus, $subType, $emailConfigStatus, $fetch_emailConfig){
         
        $result = array();
        $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
        $r = mysqli_fetch_array($systemset);
        
        // Pass the customer's authorisation code, email and amount
        $postdata =  array(
            "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
            "To"            => $emailReceiver,  //Receiver Email Address
            "TemplateId"    => '22974197',
            "TemplateModel" => [
              "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
              "sub_type"          => $subType,
              "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
              "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
              "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
              "receipt_id"        => $refid, //Receipt ID
              "merchant_name"     => $name, //Merchant OR Institution Name
              "date_from"         => $date_from, //Start Date
              "date_to"           => $expiry_date, //Expiration Date
              "plan_name"         => $plan_name, //Plan Name
              "plan_code"         => $sub_plan, //Plan Code
              "inst_id"           => $institution_id,
              "sub_token"         => $sub_token,
              "amount"            => number_format($total_amountpaid,2,'.',','),
              "discount"          => number_format($calc_bonus,2,'.',','),
              "amount_paid"       => number_format(($total_amountpaid - $calc_bonus),2,'.',','),
              "support_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
              "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
              "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address']),
              "name"              => $name
            ]
          );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $headers = [
          'Accept: application/json',
          'Content-Type: application/json',
          'X-Postmark-Server-Token: '.$r['email_token']
        ];
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $request = curl_exec($ch);
        
        curl_close ($ch);
        if ($request) {
          $result = json_decode($request, true);
          if($result['Message'] == "OK")
          {
            //echo "Email Sent Successfully";
          }else{
            //echo "Error Code: ".$result['ErrorCode'];
          }
        }
         
    }


    //Welcome Notification for Inhouse-Merchant Registration
    public function vendorRegtNotifier($official_email, $companyname, $username, $password, $customUrl, $emailConfigStatus, $fetch_emailConfig){
      
      $result = array();
      $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
      $r = mysqli_fetch_array($systemset);
      
      // Pass the customer's authorisation code, email and amount
      $postdata =  array(
        "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
        "To"            => $official_email,  //Merchant Email Address
        "TemplateId"    => '9575497',
        "TemplateModel" => [
          "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
          "name"              => $companyname,  //Merchant Full Name
          "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
          "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
          "username"          => $username, //Merchant Username
          "password"          => $password, //Merchant Password
          "custom_url"        => $customUrl,
          "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
          "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
          "sender_name"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_sender_name'] : $fetch_emailConfig['email_sender_name']),
          "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
          "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
        ]
      );
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      
      $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
        'X-Postmark-Server-Token: '.$r['email_token']
      ];
      
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      
      $request = curl_exec($ch);
      
      curl_close ($ch);
      if ($request) {
        $result = json_decode($request, true);
        if($result['Message'] == "OK")
        {
          //echo "Email Sent Successfully";
        }else{
          //echo "Error Code: ".$result['ErrorCode'];
        }
      }
       
    }


    //Certificate / Policy Document
    public function vendorCertEmailNotifier($emailReceiver, $docType, $custName, $scode, $planName, $plancat, $planamount, $custVAActNo, $phone, $mdate, $emailConfigStatus, $fetch_emailConfig){
      
      $result = array();
      $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
      $r = mysqli_fetch_array($systemset);
      
      // Pass the customer's authorisation code, email and amount
      $postdata =  array(
        "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
        "To"            => $emailReceiver,  //Customer Email Address
        "TemplateId"    => '20288401',
        "TemplateModel" => [
          "doc_type"          => $docType,
          "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
          "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
          "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
          "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
          "cust_name"         => $custName,
          "subcode"           => $scode,
          "plan_name"         => $planName,
          "plan_cat"          => $plancat,
          "plan_amount"       => $planamount,
          "wallet_acctno"     => ($custVAActNo == "") ? "---" : $custVAActNo,
          "phone"             => $phone,
          "mdate"             => $mdate,
          "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
          "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
          "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
          "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
        ]
      );
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      
      $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
        'X-Postmark-Server-Token: '.$r['email_token']
      ];
      
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      
      $request = curl_exec($ch);
      
      curl_close ($ch);
      if ($request) {
        $result = json_decode($request, true);
        if($result['Message'] == "OK")
        {
          //echo "Email Sent Successfully";
        }else{
          //echo "Error Code: ".$result['ErrorCode'];
        }
      }
       
    }


    public function clientRegNotifier($official_email, $iname, $iusername, $ipassword, $sender_id){
         
      $result = array();
      $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
      $r = mysqli_fetch_array($systemset);
      
      // Pass the customer's authorisation code, email and amount
      $postdata =  array(
        "From"          => $r['email_from'],
        "To"            => $official_email,  //Institution Email Address
        "TemplateId"    => '9583873',
        "TemplateModel" => [
          "product_name"      => $r['name'],
          "name"              => $iname,  //Institution Full Name
          "product_url"       => $r['website'],
          "username"          => $iusername, //Director's Username
          "password"          => $ipassword, //Director's Password
          "custom_url"        => "https://esusu.app/".$sender_id,
          "logo_url"          => $r['logo_url'],
          "support_email"     => $r['email'],
          "live_chat_url"     => $r['live_chat'],
          "company_name"      => $r['name'],
          "company_address"   => $r['address']
        ]
        );
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      
      $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
        'X-Postmark-Server-Token: '.$r['email_token']
      ];
      
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      
      $request = curl_exec($ch);
      
      curl_close ($ch);
      if ($request) {
        $result = json_decode($request, true);
        if($result['Message'] == "OK")
        {
          //echo "Email Sent Successfully";
        }else{
          //echo "Error Code: ".$result['ErrorCode'];
        }
      }
       
  }

  public function frontendClientRegNotifier($email, $fname, $companyid, $shortenedurl, $username, $password){
         
    $result = array();
    $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
    $r = mysqli_fetch_array($systemset);
    
    // Pass the customer's authorisation code, email and amount
    $postdata =  array(
      "From"          => $r['email_from'],
      "To"            => $email,  //Official Email Address
      "TemplateId"    => '9561823',
      "TemplateModel" => [
        "product_name"      => $r['name'],
        "name"              => $fname,  //Contact Person's Full Name
        "agentid"           => $companyid, //Unique ID
        "product_url"       => $r['website'],
        "logo_url"          => $r['logo_url'],
        "complete_areg_url" => $shortenedurl, //Reg Proceed Link
        "username"          => $username, //Username
        "password"          => $password, //Password
        "support_email"     => $r['email'],
        "live_chat_url"     => $r['live_chat'],
        "sender_name"       => $r['email_sender_name'],
        "company_name"      => $r['name'],
        "company_address"   => $r['address'],
        "license_form"      => "https://esusu.app/img/ELECTRONIC ESUSU LICENSE SUBSCRIPTION FORM.pdf"
      ]
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $headers = [
      'Accept: application/json',
      'Content-Type: application/json',
      'X-Postmark-Server-Token: '.$r['email_token']
    ];
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $request = curl_exec($ch);
    
    curl_close ($ch);
    if ($request) {
      $result = json_decode($request, true);
      if($result['Message'] == "OK")
      {
        //echo "Email Sent Successfully";
      }else{
        //echo "Error Code: ".$result['ErrorCode'];
      }
    }
     
}

//Ledger Savings Alert
public function ledgerSavingsEmailNotifier($em, $transactionType, $txid, $uname, $correctdate, $ptype, $inst_name, $account, $ln, $fn, $icurrency, $amount, $total, $emailConfigStatus, $fetch_emailConfig){
  
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $em,  //Borrower Email Address
    "TemplateId"    => '9688434',
    "TemplateModel" => [
      "ttype"             => $transactionType,
      "txid"              => $txid, //Transaction ID
      "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
      "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
      "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
      "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "name"              => $uname,  //Borrower username
      "trans_date"        => $correctdate, //Transaction date
      "ptype"             => $ptype,
      "platform_name"     => $inst_name, //Platform Name
      "account_number"    => $account, //Borrower Account Number
      "acct_name"         => $ln.' '.$fn, //Borrower Full Name
      "amount"            => $icurrency.$amount, //Amount Withdrawn
      "legal_balance"     => $icurrency.$total, //Ledger Balance
      "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
      "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
      "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}

//Withdrawal Request Notification
public function withdrawalRequestNotifier($email, $fullname, $iname, $inst_name, $correctdate, $ptype, $account, $currency, $amount, $bal, $remark, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $email,  //Borrower Email Address
    "TemplateId"    => '16171090',
    "TemplateModel" => [
        "customer_name"   => $fullname,
        "staff_name"      => $iname,
        "brand_color"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
        "logo_url"        => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
        "product_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
        "product_name"    => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
        "name"            => $inst_name, //Platform Name
        "trans_date"      => $correctdate, //Transaction date
        "trans_type"      => $ptype,
        "accountid"       => $account,
        "amount"          => $currency.$amount, //Amount Withdrawn
        "available_bal"   => $currency.$bal, //Legal Balance (For Deposit & Savings)
        "remarks"         => $remark,
        "support_email"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
        "live_chat_url"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
        "company_name"    => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
        "company_address" => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}

//P2p_BwalletFunding - Credit
public function walletCreditEmailNotifier($em, $txid, $final_date_time, $inst_name, $myname, $account, $icurrency, $amount, $totalwallet_balance, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $em,  //User Email Address
    "TemplateId"    => '10291027',
    "TemplateModel" => [
      "txid"              => $txid, //Transaction ID
      "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
      "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
      "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
      "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "acct_name"         => $myname, //User Full Name
      "trans_date"        => $final_date_time, //Transaction date
      "platform_name"     => $inst_name, //Platform Name
      "account_id"        => $account, //User Account ID
      "amount"            => $icurrency.number_format($amount,2,'.',','), //Amount Transfer
      "wallet_balance"    => $icurrency.number_format($totalwallet_balance,2,'.',','), //Wallet Balance (p2p-wallet-transfer)
      "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
      "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
      "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}

//P2p_BwalletFunding - Debit
public function walletDebitEmailNotifier($senderEmail, $txid, $senderName, $final_date_time, $inst_name, $senderAccount, $merchantName, $icurrency, $amount, $myRemainingBalance, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $senderEmail,  //User Email Address
    "TemplateId"    => '20045888',
    "TemplateModel" => [
      "txid"              => $txid, //Transaction ID
      "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
      "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
      "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
      "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "acct_name"         => $senderName, //User Full Name
      "trans_date"        => $final_date_time, //Transaction date
      "platform_name"     => $inst_name, //Platform Name
      "account_id"        => $senderAccount, //User Account ID
      "merchant_name"     => $merchantName,
      "amount"            => $icurrency.number_format($amount,2,'.',','), //Amount Transfer
      "wallet_balance"    => $icurrency.number_format($myRemainingBalance,2,'.',','), //Wallet Balance (p2p-wallet-transfer)
      "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
      "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
      "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}

//Customer Welcome Email
public function customerRegEmailNotifier($email, $fname, $shortenedurl, $username, $password, $account, $myAccountNumber, $otpCode, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $email,  //Customer Email Address
    "TemplateId"    => '9545527',
    "TemplateModel" => [
      "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "name"              => $fname,  //Customer First Name
      "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
      "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
      "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
      "activation_url"    => $shortenedurl, //Customer Activation Code
      "username"          => $username, //Customer Username
      "password"          => $password, //Customer Password
      "ledger_acno"       => $account,
      "wallet_acno"       => ($myAccountNumber == "") ? '---' : $myAccountNumber,
      "activation_code"   => $otpCode,
      "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
      "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
      "sender_name"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_sender_name'] : $fetch_emailConfig['email_sender_name']),
      "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}

//Customer Invite Email
public function inviteEmailNotifier($email, $fname, $iname, $icname, $shortenedurl, $portalLink, $emailConfigStatus, $fetch_emailConfig){
  
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $email,  //Customer Email Address
    "TemplateId"    => '19806422',
    "TemplateModel" => [
      "fname"             => $fname,
      "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
      "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
      "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
      "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "staffName"         => $iname,
      "cname"             => $icname,
      "activation_url"    => $shortenedurl, //Customer Invite Link
      "inst_portal_link"  => $portalLink, //Institution Portal Link
      "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}

//Card Transaction Notification
public function cardEmailNotifier($email, $tType, $RRN, $fname, $lname, $PAN_Masked, $DateTime, $account, $CurrCode, $TrxnAmount, $availbal, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $email,  //Customer Email Address
    "TemplateId"    => '18060229',
    "TemplateModel" => [
      "tType"             => $tType,
      "rrn"               => $RRN,
      "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
      "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
      "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
      "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "acct_name"         => $fname.' '.$lname,
      "card_mask"         => $PAN_Masked,
      "trans_date"        => $DateTime,
      "account_id"        => $account,
      "amount"            => $CurrCode.number_format($TrxnAmount,2,'.',','),
      "card_balance"      => $CurrCode.number_format($availbal,2,'.',','),
      "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
      "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
      "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}

//Product Agent Welcome Email
public function staffRegEmailNotifier($email, $sysabb, $fullname, $shortenedurl, $username, $password, $shortenedurl1, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $email,  //Customer Email Address
    "TemplateId"    => '17601278',
    "TemplateModel" => [
      "cname"             => $sysabb,
      "name"              => $fullname,  //Agent Full Name
      "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
      "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
      "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
      "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "activation_url"    => $shortenedurl,
      "username"          => $username, //Agent Username
      "password"          => $password, //Agent Password
      "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
      "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
      "deactivation_url"  => $shortenedurl1,
      "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}

//Bank Transfer Alert
public function bankTransferEmailNotifier($senderEmail, $tReference, $senderName, $transactionDateTime, $accountName, $recipientAcctNo, $mybank_name, $merchantName, $icurrency, $amountWithNoCharges, $senderBalance, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => "admin@esusu.africa,".$senderEmail,  //User Email Address
    "TemplateId"    => '21373949',
    "TemplateModel" => [
      "txid"              => $tReference, //Transaction ID
      "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
      "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
      "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
      "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "acct_name"         => $senderName, //User Full Name
      "trans_date"        => $transactionDateTime, //Transaction date
      "platform_name"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']), //Platform Name
      "recipient_name"    => $accountName, //Recipient Name
      "recipient_acctno"  => $recipientAcctNo, //Recipient Account Number
      "recipient_bankname"=> $mybank_name, //Recipient Bank Name
      "merchant_name"     => $merchantName,
      "amount"            => $icurrency.number_format($amountWithNoCharges,2,'.',','), //Amount Transfer
      "wallet_balance"    => $icurrency.number_format($senderBalance,2,'.',','), //Remaining Balance Left
      "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
      "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
      "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}

//Loan Repayment Alert
public function loanRepaymentEmailNotifier($em, $refid, $uname, $mycurrentTime, $theStatus, $channel, $account_no, $customer, $phone, $lid, $icurrency, $amount_to_pay, $final_bal, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $em,  //Borrower Email Address
    "TemplateId"    => '13352617',
    "TemplateModel" => [
      "refid"             => $refid, //Transaction ID
      "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
      "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
      "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
      "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "name"              => $uname,  //Borrower username
      "trans_date"        => $mycurrentTime, //Transaction date
      "status"            => $theStatus,
      "channel"           => $channel,
      "account_number"    => $account_no, //Borrower Account Number
      "acct_name"         => $customer, //Borrower Full Name
      "phone"             => $phone,
      "platform_name"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']), //Platform Name
      "loan_id"			      => $lid,
      "amount"            => $icurrency.number_format($amount_to_pay,2,'.',','), //Amount Paid
      "loan_balance"     	=> $icurrency.number_format($final_bal,2,'.',','), //Loan Balance
      "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
      "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
      "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}

//Receipt (Loan Review Notification)
public function loanInternalReviewEmailNotifier($company_email, $hod_email, $dept_name, $uaccount, $bfull_name, $LID, $review_date, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => $company_email,
    "To"            => $hod_email,  //Customer Email Address
    "TemplateId"    => '16182409',
    "TemplateModel" => [
        "dpt_name"        => $dept_name,
        "brand_color"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
        "logo_url"        => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
        "product_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
        "product_name"    => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
        "accountid"       => $uaccount,  //Customer Account ID
        "acct_name"       => $bfull_name,
        "loanid"          => $LID,
        "review_date"     => $review_date,
        "support_email"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
        "live_chat_url"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
        "company_name"    => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
        "company_address" => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}

//Loan Offer Letter
public function loanOfferLetterEmailNotifier($em, $customer, $action_url, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $em,  //Borrower Email Address
    "TemplateId"    => '27652966',
    "TemplateModel" => [
      "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "name"              => $customer, //Borrower Full Name
      "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
      "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
      "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
      "action_url"        => $action_url,
      "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
      "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
      "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}


//Direct Debit Alert
public function chargesDirectDebitEmailNotifier($em, $txid, $final_date_time, $charges_name, $uname, $ln, $fn, $account, $icurrency, $amount, $total, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $em,  //Borrower Email Address
    "TemplateId"    => '10204486',
    "TemplateModel" => [
      "txid"              => $txid, //Transaction ID
      "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
      "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
      "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
      "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "name"              => $uname, //Borrower username
      "trans_date"        => $final_date_time, //Transaction date
      "ttype"             => $charges_name, //Transaction Type
      "platform_name"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']), //Platform Name
      "acct_name"         => $ln.' '.$fn, //Borrower Full Name
      "account_number"    => $account, //Borrower Account Number
      "amount"            => $icurrency.$amount, //Amount Charge
      "legal_balance"     => $icurrency.$total, //Legal Balance (For Deposit & Savings)
      "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
      "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
      "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}


//Terminal Transaction Receipt
public function posEmailNotifier($emailReceiver, $TransactionID, $DateTime, $responsemessage, $paymentMethod, $bank, $settlmentType, $oprName, $SubMerchantName, $currencyCode, $pendingBal, $amount, $detectStampDutyforAuto, $charges, $mywallet_balance, $subject, $terminalId, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $emailReceiver,  //Customer Email Address
    "TemplateId"    => '19608062',
    "TemplateModel" => [
      "txid"              => $TransactionID,
      "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
      "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
      "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
      "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "trans_date"        => $DateTime,
      "status"            => $responsemessage,
      "type"              => $paymentMethod,
      "acct_name"         => $bank,
      "customer_mobile"   => "---",
      "settlement_type"   => ucwords($settlmentType),
      "operator_name"     => $oprName,
      "merchant_name"     => $SubMerchantName,
      "pending_balance"   => $currencyCode.number_format($pendingBal,2,'.',','),
      "amount"            => $currencyCode.number_format($amount,2,'.',','),
      "amount_settled"    => $currencyCode.number_format(($detectStampDutyforAuto - $charges),2,'.',','),
      "transfer_balance"  => $currencyCode.number_format($mywallet_balance,2,'.',','),
      "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
      "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
      "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address']),
      "subject"           => $subject,
      "tid"               => $terminalId
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}


//Terminal Request Notification
public function terminalRequestEmailNotifier($receiverEmail, $status, $terminalId, $trace_id, $merchant_name, $merchant_email, $merchant_phone_no, $activationFee, $DateTime, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $receiverEmail,  //SupCustomer Email Address
    "TemplateId"    => '19885509',
    "TemplateModel" => [
      "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
      "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
      "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
      "status"            => $status,
      "terminalId"        => $terminalId,
      "traceid"           => $trace_id,
      "merchant_name"     => $merchant_name,
      "merchant_email"    => $merchant_email,
      "merchant_phone"    => $merchant_phone_no,
      "activation_fee"    => number_format($activationFee,2,'.',','),
      "date_time"         => $DateTime,
      "support_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
      "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}


//Terminal Settlement Confirmation
public function terminalSettlementEmailNotifier($emailReceiver, $subject, $status, $TransactionID, $DateTime, $paymentMethod, $bank, $SubMerchantName, $pbal, $scharge, $sstatus, $amt_to_settle, $settledAMount, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $emailReceiver,  //Customer Email Address
    "TemplateId"    => '20639353',
    "TemplateModel" => [
      "subject"           => $subject,
      "status"            => $status,
      "txid"              => $TransactionID,
      "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
      "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
      "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
      "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "trans_date"        => $DateTime,
      "type"              => $paymentMethod,
      "acct_name"         => $bank,
      "customer_mobile"   => "---",
      "merchant_name"     => $SubMerchantName,
      "pending_balance"   => number_format($pbal,2,'.',','),
      "charges"           => number_format($scharge,2,'.',','),
      "amount_settled"    => ($sstatus == "successful") ? number_format($amt_to_settle,2,'.',',') : $settledAMount,
      "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
      "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
      "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}


//Till Funding / Withdrawal Alert
public function tillFundingEmailNotifier($myiemail_addrs, $txid, $iusername, $ptype, $cashier, $icurrency, $formated_settled_fund, $formated_balance, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  $date_time = date("Y-m-d h:i:s");
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $myiemail_addrs,  //Sub-agent Email Address
    "TemplateId"    => '18359731',
    "TemplateModel" => [
      "txid"          => $txid, //Transaction ID
      "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
      "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
      "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
      "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "name"          => $iusername,  //Sub-agent username
      "trans_date"    => $this->formatDateTime($date_time), //Transaction date
      "tilltype"      => $ptype,
      "acct_name"     => $cashier, //Sub-agent Full Name
      "amount"        => $icurrency.$formated_settled_fund, //Amount Withdrawn
      "balance"       => $icurrency.$formated_balance, //Till Balance
      "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
      "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
      "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
      "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
    ]
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}


//Login OTP Notifier
public function loginOtpNotifier($creatorEmail, $pageHeader, $newLocation, $ipAddress, $browserName, $deviceName, $activationCode, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  $date_time = date("Y-m-d h:i:s");
  
  // Pass otp code
  $postdata = array(
      "From"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
      "To"        => $creatorEmail,  //Receiver Email Address
      "TemplateId"    => '23799812',
      "TemplateModel" => [
              "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
              "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
              "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
              "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
              "page_header"       => $pageHeader,
              "new_location"      => $newLocation,
              "ip_address"        => $ipAddress,
              "browser_name"      => $browserName,
              "device_name"       => $deviceName,
              "activation_code"   => $activationCode,
              "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
              "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
              "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address']),
              "date_time"         => $this->formatDateTime($date_time)  //Date Time              
          ]
      );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}



//Password Reset
public function passwordReset($email, $name, $username, $password, $ipAddress, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  $date_time = date("Y-m-d h:i:s");
  
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $email,  //Customer Email Address
    "TemplateId"    => '16144716',
    "TemplateModel" => [
        "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
        "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
        "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
        "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
        "name"              => $name,  //Customer First Name
        "username"          => $username, //Customer Username
        "password"          => $password, //Customer Password
        "ip_address"        => $ipAddress,
        "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
        "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
        "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
        "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address'])
      ]
      );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}


//Enrollment Notification
public function enrolleeNotifier($email, $lname, $type, $enrollment_type, $msg_content, $emailConfigStatus, $fetch_emailConfig){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_array($systemset);
  $date_time = date("Y-m-d h:i:s");
  
  $postdata =  array(
    "From"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email_from'] : $fetch_emailConfig['email_from']),
    "To"            => $email,  //Customer Email Address
    "TemplateId"    => '25408837',
    "TemplateModel" => [
        "type"              => $type,
        "brand_color"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['brand_color'] : $fetch_emailConfig['brand_color']),
        "logo_url"          => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['logo_url'] : $fetch_emailConfig['logo_url']),
        "product_url"       => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['website'] : $fetch_emailConfig['product_url']),
        "product_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
        "lname"             => $lname,  //Customer Last Name
        "msg_content"       => $msg_content, //Message Content
        "support_email"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['email'] : $fetch_emailConfig['support_email']),
        "live_chat_url"     => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['live_chat'] : $fetch_emailConfig['live_chat']),
        "company_name"      => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['name'] : $fetch_emailConfig['product_name']),
        "company_address"   => (($emailConfigStatus == "" || $emailConfigStatus == "NotActivated") ? $r['address'] : $fetch_emailConfig['company_address']),
        "enrollment_type"   => $enrollment_type
      ]
      );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r['email_token']
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}


//Send Aggregator Welcome Email
public function aggregatorWelcomeEmail($email, $fname, $shortenedurl, $username, $password){
         
  $result = array();
  $systemset = mysqli_query($this->link, "SELECT * FROM systemset");
  $r = mysqli_fetch_object($systemset);
  
  // Pass the customer's authorisation code, email and amount
  $postdata =  array(
    "From"          => $r->email_from,
    "To"            => $email,  //Customer Email Address
    "TemplateId"    => '14651404',
    "TemplateModel" => [
      "product_name"      => $r->name,
      "name"              => $fname,  //Aggregator First Name
      "logo_url"          => $r->logo_url,
      "product_url"       => $r->website,
      "activation_url"    => $shortenedurl,
      "username"          => $username, //Customer Username
      "password"          => $password, //Customer Password
      "support_email"     => $r->email,
      "live_chat_url"     => $r->live_chat,
      "company_name"      => $r->name,
      "company_address"   => $r->address
    ]
  );
   
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Postmark-Server-Token: '.$r->email_token
  ];
  
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
  $request = curl_exec($ch);
  
  curl_close ($ch);
  if ($request) {
    $result = json_decode($request, true);
    if($result['Message'] == "OK")
    {
      //echo "Email Sent Successfully";
    }else{
      //echo "Error Code: ".$result['ErrorCode'];
    }
  }
   
}


}

$sendSMS = new smsALert($link);

?>