<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, x-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/user.php";
require_once "../../models/HttpResponse.php";
require_once "../../config/auth.php";


//CHECK INCOMING GATE REQUESTS
if($_SERVER['REQUEST_METHOD'] === "GET") {

    //FETCH ONE CUSTOMER IF ID OR ACCOUNT NUMBER EXIST OR ALL IF ID DOESN'T EXIST
    $resultsData = (isset($_GET['id'])) ? $user->fetchCustomerById($_GET['id'],$registeral,$companyName) : $user->fetchAllCustomer($registeral,$companyName);

    $resultsInfo = $db->executeCall($registeral);


    if($resultsData === 0) {

        $message = "No customer was found";
        $http->notFound($message);

    }elseif($resultsInfo === -1) {

        $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $http->accessForbidden($message);

    }else {
        
        $http->OK($resultsInfo, $resultsData);

    }

} else if($_SERVER['REQUEST_METHOD'] === "POST") {

    $customerReceived = json_decode(file_get_contents("php://input"));

    $results = $user->insertPcCustomer($customerReceived, $registeral, $reg_branch, $reg_staffName, $companyName);

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
	$id = rand(1000000,10000000);
    $shorturl = base_convert($id,20,36);
    $shortenedurl = 'https://esusu.app/?activation_key=' . $shorturl;
    $deactivation_url = 'https://esusu.app/?deactivation_key=' . $shorturl;
    $support_email = $emailCredentials['email'];
    $live_chat_url = $emailCredentials['live_chat'];
    $sender_name = $emailCredentials['email_sender_name'];
    $company_address = $emailCredentials['address'];
    $email_token = $emailCredentials['email_token'];

    $refid = "EA-smsCharges-".rand(1000000,9999999);

    // Function to check string starting 
    // with given substring 
    function startsWith ($string, $startString) 
    { 
        $len = strlen($startString); 
        return (substr($string, 0, $len) === $startString); 
    }

    if($results === -1){

        $http->badRequest("A valid JSON of some fields is required");

    }else if($results === -2){

        $http->duplicateEntry("Opps!..Username / Phone Already Exist");

    }else if($results === -3){
        
        $http->notAuthorized('Oops! You are not Authorized to use this facilities. Kindly contact us for more info.');
        
    }else if($cust_reg === "Disallow"){
        
        $http->notAuthorized('Oops! Access Denied.');
        
    }else if($resultsInfo === -1){

        $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
        $message .= " to have access to our REST API";
        $http->accessForbidden($message);

    }else{  //if(startsWith($registeral,"INST") || startsWith($registeral,"AGT") || startsWith($registeral,"MER"))

        //CUSTOMER INFORMATION FOR SMS / EMAIL DELIVERY
        //$convert = 
        $snum = $customerReceived->snum;
        $fname = $customerReceived->fname;
        $lname = $customerReceived->lname;
        $account = $customerReceived->account;
        $username = $customerReceived->username;
        $pw = $customerReceived->password;
        $phone = $customerReceived->phone;
        $email = $customerReceived->email;
        $gender = $customerReceived->gender;
        $dob = $customerReceived->dob;
        $addrs = $customerReceived->addrs;
        $city = $customerReceived->city;
        $state = $customerReceived->state;
        $country = $customerReceived->country;
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
        $url = "https://esusu.app/?acn=".$account;

        $sms = "$sysabb>>>Welcome $fname! Your Account ID: $account, Username: $username, Password: $pw, Transaction Pin: 0000. Login at https://esusu.app/$sysabb";

        $details = 'SMS Content: '.$sms;
        
        //FETCH INSTITUTION WALLET BALANCE
        $getInst = $user->fetchInstitutionById($registeral);
        $inst_wallet = $getInst['wallet_balance'];

        //SMS CONTENT PRICE CALCULATION PER PAGE
        $max_per_page = 153;
        $sms_length = strlen($sms);
        $calc_length = ceil($sms_length / $max_per_page);
        $cust_charges = $resultsInfo['cust_mfee'];
        $sms_charges = $calc_length * $cust_charges;
        $mywallet_balance = $inst_wallet - $sms_charges;
        $date_time = date("Y-m-d h:m:s");
        
        if($inst_wallet < $sms_charges){
            
            $myQuery = "INSERT INTO borrowers (snum, fname, lname, email, phone, gender, dob, addrs, city, state, country, username, password, community_role, account, balance, investment_bal, last_withdraw_date, status, opt_option, currency, wallet_balance, overdraft, card_id, card_reg, tpin, reg_type, branchid, sbranchid, acct_status, date_time, acct_opening_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $db->insertCustomerPc($myQuery, $snum, $fname, $lname, $email, $phone, $gender, $dob, $addrs, $city, $state, $country, $username, $pw, $community_role, $account, $ledger_bal, $invst_bal, $last_with_date, $status, $otp_option, $currency, $wallet_bal, $over_draft, $card_id, $card_reg, $tpin, $gname, $registeral, $reg_branch, $acct_status, $date_time, $acct_opening_date);
            
            include_once("../../cron/send_regemail.php");
            
            $http->OKCust($resultsInfo, $results);

        }else{

            $myQuery = "INSERT INTO borrowers (snum, fname, lname, email, phone, gender, dob, addrs, city, state, country, username, password, community_role, account, balance, investment_bal, last_withdraw_date, status, opt_option, currency, wallet_balance, overdraft, card_id, card_reg, tpin, reg_type, branchid, sbranchid, acct_status, date_time, acct_opening_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $db->insertCustomerPc($myQuery, $snum, $fname, $lname, $email, $phone, $gender, $dob, $addrs, $city, $state, $country, $username, $pw, $community_role, $account, $ledger_bal, $invst_bal, $last_with_date, $status, $otp_option, $currency, $wallet_bal, $over_draft, $card_id, $card_reg, $tpin, $gname, $registeral, $reg_branch, $acct_status, $date_time, $acct_opening_date);

            $debug=true;
            $db->sendSms($sysabb,$phone,$sms,$debug);
            include("send_regemail.php");
            
            $user->updateInstitutionWallet($mywallet_balance, $registeral);
            $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $db->insertWalletHistory($query, $registeral, $refid, $phone, '', $sms_charges, 'Debit', $currency, 'Charges', $details, 'successful', $date_time, $reg_staffid, $mywallet_balance);
            $query2 = "INSERT INTO activate_member (url, shorturl, attempt, acn) VALUES(?, ?, ?, ?)";
            $db->insertActivationCode($query2, $url, $shorturl, 'No', $account);
            $http->OKCust($resultsInfo, $results);

        }
    }
    /*else{
        //CUSTOMER INFORMATION FOR SMS / EMAIL DELIVERY
        //$convert = 
        $fname = $customerReceived->fname;
        $account = $customerReceived->account;
        $username = $customerReceived->username;
        $pw = $customerReceived->password;
        $phone = $customerReceived->phone;
        $email = $customerReceived->email;
        $url = "https://esusu.app/?acn=".$account;

        $sms = "$sysabb>>>Welcome $fname! Your Account ID: $account, Username: $username, Password: $pw, Transaction Pin: 0000. Download App at bit.ly/esusuafrica_app";

        $details = 'SMS Content: '.$sms;
        
        //FETCH INSTITUTION WALLET BALANCE
        $getMerchant = $user->fetchMerchantById($registeral);
        $merchant_wallet = $getMerchant['wallet_balance'];

        //SMS CONTENT PRICE CALCULATION PER PAGE
        $max_per_page = 159;
        $sms_length = strlen($sms);
        $calc_length = ceil($sms_length / $max_per_page);
        $cust_charges = $resultsInfo['cust_mfee'];
        $sms_charges = $calc_length * $cust_charges;
        $mywallet_balance = $merchant_wallet - $sms_charges;
        $date_time = date("Y-m-d h:m:s");
        
        if($merchant_wallet < $sms_charges){

            include_once("../../cron/send_regemail.php");
            $http->OKCust($resultsInfo, $results);

        }else{

            $debug=true;
            $db->sendSms($sysabb,$phone,$sms,$debug);
            include("send_regemail.php");
            $user->updateMerchantWallet($mywallet_balance, $registeral);
            $query = "INSERT INTO wallet_history (userid, refid, recipient, amount, currency, paymenttype, card_bank_details, status, date_time) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $db->insertWalletHistory($query, $registeral, $refid, $phone, $sms_charges, $currency, 'system', $details, 'successful', $date_time);
            $query2 = "INSERT INTO activate_member (url, shorturl, attempt, acn) VALUES(?, ?, ?, ?)";
            $db->insertActivationCode($query2, $url, $shorturl, 'No', $account);
            $http->OKCust($resultsInfo, $results);

        }
    }*/
} else if($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $customerReceived = json_decode(file_get_contents("php://input"));

    if(!isset($customerReceived->id)) {

        //ID NOT PROVIDED BAD REQUEST
        $http->badRequest("Please an Id is required to make a PUT request");
        exit();

    }

    $query = "SELECT * FROM borrowers WHERE id = ? AND branchid = '$registeral'";
    $results = $db->fetchById($query, $customerReceived->id);

    if($results === 0) {

        //ACCOUNT NOT FOUND
        $http->notFound("Customer Information Not Found");

    }else if($results['branchid'] !== $registeral && $results['sbranchid'] !== $reg_branch) {

        //NOT AUTHORIZED
        $http->notAuthorized("You are not authorized to update this customer");

    }else {

        //USERS CAN UPDATE THE FOLLOWING PARAMETERS
        $parameters = [
            'id' => $customerReceived->id,
            'fname' => isset($customerReceived->fname) ? $customerReceived->fname : $results['fname'],
            'lname' => isset($customerReceived->lname) ? $customerReceived->lname : $results['lname'],
            'email' => isset($customerReceived->email) ? $customerReceived->email : $results['email'],
            'phone' => isset($customerReceived->phone) ? $customerReceived->phone : $results['phone'],
            'dob' => isset($customerReceived->dob) ? $customerReceived->dob : $results['dob'],
            'addrs' => isset($customerReceived->addrs) ? $customerReceived->addrs : $results['addrs'],
            'city' => isset($customerReceived->city) ? $customerReceived->city : $results['city'],
            'state' => isset($customerReceived->state) ? $customerReceived->state : $results['state'],
            'country' => isset($customerReceived->country) ? $customerReceived->country : $results['country'],
            'username' => isset($customerReceived->username) ? $customerReceived->username : $results['username'],
            'password' => isset($customerReceived->password) ? $customerReceived->password : $results['password'],
        ];

        $resultsData = $user->updatePcCustomer($parameters);

        $resultsInfo = $db->executeCall($registeral);

        if($resultsInfo === -1) {

            $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
            $message .= " to have access to our REST API";
            $http->accessForbidden($message);
    
        }else {
    
            $http->OK($resultsInfo, $resultsData);
    
        }

    }

} else if($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $idRequest = json_decode(file_get_contents("php://input"));

    if(!isset($idRequest->id)) {

        //ID NOT PROVIDED BAD REQUEST
        $http->badRequest("No Id was Provided");
        exit();

    }

    $query = "SELECT * FROM borrowers WHERE id = ? AND branchid = '$registeral'";
    $results = $db->fetchById($query, $idRequest->id);

    if($results === 0) {

        //ACCOUNT NOT FOUND
        $http->notFound("Customer information with Id $idRequest->id was not found");
        exit();

    }
    if($results['branchid'] !== $registeral && $results['sbranchid'] !== $reg_branch) {

        //NOT AUTHORIZED
        $http->notAuthorized("You are not authorized to delete this customer");

    }else{

        //USER CAN NOW DELETE
        $resultsData = $user->deletePcCustomer($idRequest->id);

        $resultsInfo = $db->executeCall($registeral);

        if($resultsInfo === -1) {

            $message = "Opps! Sorry, you needs to be configured on Pay-as-you-use model";
            $message .= " to have access to our REST API";
            $http->accessForbidden($message);
    
        }else {
    
            $http->OK($resultsInfo, $resultsData);
    
        }

    }

}
?>