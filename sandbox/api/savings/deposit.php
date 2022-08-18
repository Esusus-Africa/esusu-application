<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";


//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "GET") {

    //FETCH DEPOSIT BY LIMIT IF ID EXIST OR ALL IF ID DOESN'T EXIST
    $resultsData = (isset($_GET['id'])) ? $user->fetchDepositByLimit($registeral,$_GET['id']) : $user->fetchAllDeposit($registeral);

    $resultsInfo = $db->executeCall($registeral);


    if($resultsData === 0) {

        $message = "No Transaction was found";
        $http->notFound($message);

    }elseif($resultsInfo === -1) {

        $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $http->accessForbidden($message);

    }else {

        $http->OK($resultsInfo, $resultsData);

    }

} else if($_SERVER['REQUEST_METHOD'] === "POST") {

    $depositReceived = json_decode(file_get_contents("php://input"));

    $results = $user->postDeposit($depositReceived, $registeral, $reg_branch, $reg_staffid, $reg_staffName, $companyName, $irole);

    $resultsInfo = $db->executeCall($registeral);

    //SENDER ID
    $sysabb = ($resultsInfo['sender_id'] == "") ? "esusuafrica" : $resultsInfo['sender_id'];
    $currency = $resultsInfo['currency'];

    //FETCH SMS DELIVERY CREDENTIALS
    $smsCredentials = $db->fetchSmsCredentials();
    $gateway_uname = $smsCredentials['username'];
    $gateway_pass = $smsCredentials['password'];
    $gateway_api = $smsCredentials['api'];


    //FETCH SYSTEMSET FOR EMAIL DELIVERY CREDENTIALS
    $emailCredentials = $db->fetchSystemSet();
    $email_from = $emailCredentials['email_from'];
    $product_name = $emailCredentials['name'];
    $website = $emailCredentials['website'];
    $logo_url = $emailCredentials['logo_url'];
    $support_email = $emailCredentials['email'];
    $live_chat_url = $emailCredentials['live_chat'];
    $sender_name = $emailCredentials['email_sender_name'];
    $company_address = $emailCredentials['address'];
    $email_token = $emailCredentials['email_token'];

    $refid = "EA-mCharges-".rand(100000000000,999999999999);

    //CUSTOMER INFORMATION FOR SMS / EMAIL DELIVERY
    $account = $depositReceived->acctno;
    $currency = $depositReceived->currency;
    $amt = $depositReceived->amount;
    $txid = $depositReceived->txid;
    $datetime = date("Y-m-d h:m:s");
    $aggrid = $depositReceived->aggr_id;
    $t_type = "Deposit";
    $p_type = $depositReceived->p_type;
    $transfer_to = "----";
    $remark = $depositReceived->remark;
    $date_time = date("Y-m-d h:m:s");
    $aggr_id = $depositReceived->aggr_id;

    //REMAINING CUSTOMER DETAILS
    $otherCustDetails = $db->fetchCustomer($account, $registeral);
    $fname = $otherCustDetails['fname'];
    $lname = $otherCustDetails['lname'];
    $phone = $otherCustDetails['phone'];
    $email = $otherCustDetails['email'];
    $ledger_bal = $otherCustDetails['balance'] + $amt;

    $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$datetime,new DateTimeZone(date_default_timezone_get()));
    $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
    $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
    $correctdate = $acst_date->format('Y-m-d g:i A');
    
    $message = "$sysabb>>>CR";
    $message .= " Amt: ".$currency.number_format($amt,2,'.',',')."";
    $message .= " Acc: ".$db->ccMasking($account)."";
    $message .= " Desc: Direct Deposit - | ".$txid."";
    $message .= " Time: ".$correctdate."";
    $message .= " Bal: ".$currency.number_format($ledger_bal,2,'.',',')."";

    $details = 'SMS Content: '.$message;

    if($results === -1){

        $http->badRequest("A valid JSON of some fields is required");

    }else if($results === -2){

        $http->notFound("Account number is not valid");

    }else if($results === -3){

        $http->badRequest("Required field missing");

    }else if($results === -4){
        
        $http->notAuthorized('Oops! You are not Authorized to use this facilities. Kindly contact us for more info.');
        
    }else if($deposit === "Disallow"){
        
        $http->notAuthorized('Oops! Access Denied.');
        
    }else if($resultsInfo === -1){

        $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $http->accessForbidden($message);

    }else{
        //FETCH INSTITUTION WALLET BALANCE
        $getInst = $user->fetchInstitutionById($registeral);
        $inst_wallet = $getInst['wallet_balance'];

        //FETCH TRANSACTION CHARGES
        $trans_charges = $resultsInfo['t_charges'];
        $mywallet_balance = $inst_wallet - $trans_charges;

        //FETCH AGGREGATOR WALLET BALANCE
        $getAggr = $user->fetchAggrById($aggrid);
        $aggr_wallet = $getAggr['wallet_balance'];
        $aggr_commission = ($getAggr['aggr_co_type'] == "Percentage") ? ($getAggr['aggr_co_rate']/100)*$trans_charges : $getAggr['aggr_co_rate'];

        $verifytill = $user->fetchTillAcct($reg_staffid);
        $balance = $verifytill['balance'] - $amt;
        $commission = $verifytill['commission'];
        $commission_bal = $verifytill['commission_balance'];

        //Calculate Commission Earn By the Staff
		$cal_commission = ($commission / 100) * $amt;
			
		//Update Default Commission Balance
        $total_commission_bal = ($commission == 0) ? $commission_bal : $cal_commission + $commission_bal;

        if($results === -5){

            $message = "Opps! You do not have sufficient fund in your till account to perform this transaction";
            $http->insufficientFund($message);        

        }elseif($results === -6){

            $message = "Sorry! You do not have sufficient fund in your central wallet to complete this transaction";
            $http->insufficientFund($message);        

        }elseif($verifytill === 0 && ($irole === "agent_manager" || $irole === "institution_super_admin" || $irole === "merchant_super_admin")){   // && ($irole === "agent_manager" || $irole === "institution_super_admin" || $irole === "merchant_super_admin")
            //Do not include till account
            $debug=true;
            $db->sendSms($sysabb,$phone,$message,$debug);
            include("send_sdeposit_alertemail.php");
            //UPDATE INSTITUTION BALANCE
            $user->updateInstitutionWallet($mywallet_balance, $registeral);
            //INSERT TRANSACTION CHARGES IN WALLET HISTORY FOR THE AGENT FOR REFERENCE PURPOSE
            $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $db->insertWalletHistory($query, $registeral, $refid, $phone, '', $trans_charges, 'Debit', $currency, 'Charges', $details, 'successful', $datetime, $reg_staffid, $mywallet_balance);
            //UPDATE CUSTOMER LEDGER BALANCE WITH THE AMOUNT DEPOSITED
            $user->updateBorrower($ledger_bal, $account, $registeral);
            //UPDATE COMMISSION FOR AGGREGATOR IF AND ONLY IF THE AGENT WAS REGISTERED BY AGGREGATOR
            
            $queryDep = "INSERT INTO transaction (txid, t_type, p_type, acctno, transfer_to, fn, ln, email, phone, amount, posted_by, remark, date_time, branchid, sbranchid, currency, aggr_id, balance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $db->insertSavings($queryDep, $txid, $t_type, $p_type, $account, $transfer_to, $fname, $lname, $email, $phone, $amt, $reg_staffid, $remark, $date_time, $registeral, $reg_branch, $currency, $aggr_id, $ledger_bal);
            
            $query2 = "INSERT INTO wallet_history (userid, refid, recipient, amount, currency, paymenttype, card_bank_details, status, date_time) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            ($getAggr === 0) ? "" : $db->insertWalletHistory($query2, $registeral, $refid, $phone, $aggr_commission, $currency, 'Wallet', 'Description: Aggregator Commission from the Service Charge of $trans_charges', 'successful', $datetime);
            ($getAggr === 0) ? "" : $user->updateAggr($aggwallet_balance, $aggrid);
            //GIVE OKAY RESPONSE
            $http->OK($resultsInfo, $results);

        }elseif($verifytill != 0){
            //Include till account
            $debug=true;
            $db->sendSms($sysabb,$phone,$message,$debug);
            include("send_sdeposit_alertemail.php");
            //UPDATE INSTITUTION BALANCE
            $user->updateInstitutionWallet($mywallet_balance, $registeral);
            //INSERT TRANSACTION CHARGES IN WALLET HISTORY FOR THE AGENT FOR REFERENCE PURPOSE
            $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $db->insertWalletHistory($query, $registeral, $refid, $phone, '', $trans_charges, 'Debit', $currency, 'Charges', $details, 'successful', $datetime, $reg_staffid, $mywallet_balance);
            //UPDATE CUSTOMER LEDGER BALANCE WITH THE AMOUNT DEPOSITED
            $user->updateBorrower($ledger_bal, $account, $registeral);
            //UPDATE TILL ACCOUNT BALANCE FOR THE STAFF POSTING
            $user->updateTillAcct($balance, $total_commission_bal, $reg_staffid, $registeral);
            //UPDATE COMMISSION FOR AGGREGATOR IF AND ONLY IF THE AGENT WAS REGISTERED BY AGGREGATOR
            
            $queryDep = "INSERT INTO transaction (txid, t_type, p_type, acctno, transfer_to, fn, ln, email, phone, amount, posted_by, remark, date_time, branchid, sbranchid, currency, aggr_id, balance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $db->insertSavings($queryDep, $txid, $t_type, $p_type, $account, $transfer_to, $fname, $lname, $email, $phone, $amt, $reg_staffid, $remark, $date_time, $registeral, $reg_branch, $currency, $aggr_id, $ledger_bal);
            
            $query2 = "INSERT INTO wallet_history (userid, refid, recipient, amount, currency, paymenttype, card_bank_details, status, date_time) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            ($getAggr === 0) ? "" : $db->insertWalletHistory($query2, $registeral, $refid, $phone, $aggr_commission, $currency, 'Wallet', 'Description: Aggregator Commission from the Service Charge of $trans_charges', 'successful', $datetime);
            ($getAggr === 0) ? "" : $user->updateAggr($aggwallet_balance, $aggrid);
            //GIVE OKAY RESPONSE
            $http->OK($resultsInfo, $results);

        }else{
            
            //GIVE OKAY RESPONSE
            $http->OK($resultsInfo, $results);
            
        }
    }

}
?>