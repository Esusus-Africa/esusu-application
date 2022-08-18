<?php
if(!empty($_POST) && !empty($_POST['phoneNumber'])){
    
date_default_timezone_get('Africa/Lagos');

include("config/connect.php");

include("config/intlrestful_apicalls.php");
//include("config/walletafrica_restfulapis_call.php");

include("config/restful_apicalls.php");

include("config/virtual_account_creation.php");

function myotp($limit)
{
	return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}

// Function to check string starting 
// with given substring 
function startsWith($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}

// Reads the variables sent via POST from our gateway
$sessionId   = $_POST["sessionId"];
$serviceCode = $_POST["serviceCode"];
$phone       = $_POST["phoneNumber"];
$text        = $_POST["text"];
$ussdPrefix  = ($text == "") ? 0 : $text; //date("s")/;
$duration = date("Y-m-d H:i:s");
$community   = "Borrower";
$acct_no     = date("hs").substr((uniqid(rand(),1)),3,6);

/*$result2 = array();
$payvice = mysqli_query($link, "SELECT * FROM payvice_accesspoint");
$row_payvice = mysqli_fetch_object($payvice);
$pv_walletid = $row_payvice->pv_walletid;
$pv_username = $row_payvice->pv_username;
$pv_tpin = $row_payvice->pv_tpin;
$pv_password = $row_payvice->pv_password;
        	
$search_restapi_forlookup = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'payvice_lookup'");
$fetch_restapi_forlookup = mysqli_fetch_object($search_restapi_forlookup);
$api_url = $fetch_restapi_forlookup->api_url;
        	
// Pass the parameter here
$postdata =  array(
    "vice_id"	=>	$pv_walletid,
    "user_name" =>  $pv_username
);
        	    
$make_call = callAPI('POST', $api_url, json_encode($postdata));
$result2 = json_decode($make_call, true);
        		
$mylookup = $result2['token'];*/

$mysystem_config = mysqli_query($link, "SELECT * FROM systemset");
$fetchsys_config = mysqli_fetch_array($mysystem_config);
$walletafrica_skey = $fetchsys_config['walletafrica_skey'];
$rave_secret_key = $fetchsys_config['secret_key'];
$sms_rate = $fetchsys_config['fax'];
$sys_abb = $fetchsys_config['abb'];
$verveCardPrefundAmt = $fetchsys_config['verveCardPrefundAmt'];
$verveAppId = $fetchsys_config['verveAppId'];
$verveAppKey = $fetchsys_config['verveAppKey'];
$verveSettlementAcctNo = $fetchsys_config['verveSettlementAcctNo'];
$verveSettlementAcctName = $fetchsys_config['verveSettlementAcctName'];
$verveSettlementBankCode = $fetchsys_config['verveSettlementBankCode'];
$transferToCardCharges1 = $fetchsys_config['transferToCardCharges'];

$textArray = explode('*',$text);
$userResponse = trim(end($textArray));

$search_session = mysqli_query($link, "SELECT * FROM session_levels WHERE session_id = '$sessionId'");
$fetch_session = mysqli_fetch_array($search_session);
$level = (mysqli_num_rows($search_session) == 1) ? $fetch_session['level'] : 0;
$current_ussdPrefix = ($fetch_session['ussd_prefix'] == "") ? $text : $fetch_session['ussd_prefix'];
$currenct_sessionCharges = $fetch_session['ussd_session_fee'];
$current_seconds = $fetch_session['target_seconds'];
$currenctHops = $fetch_session['hops'];
$start_duration = strtotime($fetch_session['duration']);
$target_duration = strtotime(date("Y-m-d H:i:s"));
// Formulate the Difference between two dates 
$dateDiff = abs($target_duration - $start_duration); 

// To get the year divide the resultant date into 
// total seconds in a year (365*60*60*24) 
$dateYears = floor($dateDiff / (365*60*60*24));

// To get the month, subtract it with years and 
// divide the resultant date into 
// total seconds in a month (30*60*60*24) 
$dateMonths = floor(($dateDiff - $dateYears * 365*60*60*24) / (30*60*60*24));

// To get the day, subtract it with years and  
// months and divide the resultant date into 
// total seconds in a days (60*60*24) 
$dateDays = floor(($dateDiff - $dateYears * 365*60*60*24 - $dateMonths*30*60*60*24)/ (60*60*24)); 

// To get the hour, subtract it with years,  
// months & seconds and divide the resultant 
// date into total seconds in a hours (60*60) 
$dateHours = floor(($dateDiff - $dateYears * 365*60*60*24 - $dateMonths*30*60*60*24 - $dateDays*60*60*24) / (60*60));

// To get the minutes, subtract it with years, 
// months, seconds and hours and divide the  
// resultant date into total seconds i.e. 60 
$dateMinutes = floor(($dateDiff - $dateYears * 365*60*60*24 - $dateMonths*30*60*60*24 - $dateDays*60*60*24 - $dateHours*60*60)/ 60);

// To get the minutes, subtract it with years, 
// months, seconds, hours and minutes  
$dateSeconds = floor(($dateDiff - $dateYears * 365*60*60*24 - $dateMonths*30*60*60*24 - $dateDays*60*60*24 - $dateHours*60*60 - $dateMinutes*60)); //+ $current_seconds;  

function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);
}

function detectActiveLP($parameter) {
    global $link, $current_ussdPrefix;

    $query = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$parameter' AND dedicated_ussd_prefix = '$current_ussdPrefix' AND ussd_approval_status = 'Activated'");
    $query_num = mysqli_num_rows($query);
    
    if($query_num == 1){
        
        return 1;
        
    }else{
        
        return 0;
        
    }

}

function detectGeneralActiveLP() {
    global $link, $current_ussdPrefix;

    $query = mysqli_query($link, "SELECT * FROM loan_product WHERE dedicated_ussd_prefix = '$current_ussdPrefix' AND ussd_approval_status = 'Activated'");
    $query_num = mysqli_num_rows($query);
    
    if($query_num == 1){
        
        return 1;
        
    }else{
        
        return 0;
        
    }

}

$sql_sms = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated' AND smsuser = ''") or die (mysqli_error($link));
$find = mysqli_fetch_array($sql_sms);
$ozeki_user = $find['username'];
$ozeki_password = $find['password'];
$ozeki_url = $find['api'];

function ozekiSend($sender, $phone, $msg, $debug=false){
      global $ozeki_user,$ozeki_password,$ozeki_url;

      $url = 'username='.$ozeki_user;
      $url.= '&password='.$ozeki_password;
      $url.= '&sender='.urlencode($sender);
      $url.= '&recipient='.urlencode($phone);
      $url.= '&message='.urlencode($msg);

      $urltouse =  $ozeki_url.$url;
      //if ($debug) { echo "Request: <br>$urltouse<br><br>"; }

      //Open the URL to send the message
      $response = file_get_contents($urltouse);
      if ($debug) {
           //echo "Response: <br><pre>".
           //str_replace(array("<",">"),array("&lt;","&gt;"),$response).
           //"</pre><br>"; 
           }

      return($response);
}

//MEMBER SETTINGS
($current_ussdPrefix != "") ? $fet_memsettings = mysqli_query($link, "SELECT * FROM member_settings WHERE dedicated_ussd_prefix = '$current_ussdPrefix' AND ussd_status = 'Active'") : "";
($current_ussdPrefix == "") ? $fet_memsettings = mysqli_query($link, "SELECT * FROM member_settings WHERE dedicated_ussd_prefix = '0' AND ussd_status = 'Active'") : "";
$fetch_all_memsettings = mysqli_fetch_array($fet_memsettings);
$my_inst_id = $fetch_all_memsettings['companyid'];
$my_inst_currency = $fetch_all_memsettings['currency'];
$my_inst_senderid = ($fetch_all_memsettings['sender_id'] == "") ? $sys_abb : $fetch_all_memsettings['sender_id'];
$company_brandname = ($fetch_all_memsettings['cname'] == "") ? "ESUSU.ME" : $fetch_all_memsettings['cname'];
$dedicated_ussd_prefix = ($fetch_all_memsettings['dedicated_ussd_prefix'] == "0") ? 0 : $fetch_all_memsettings['dedicated_ussd_prefix'];
$myrealText = ($userResponse == "0") ? $dedicated_ussd_prefix : $text;

$search_maintenance = mysqli_query($link, "SELECT * FROM maintenance_history WHERE company_id = '$my_inst_id'");
$fetch_maintenance = mysqli_fetch_array($search_maintenance);
$ussd_session_cost = $currenct_sessionCharges + ($fetch_maintenance['ussd_session_cost'] * $dateSeconds);
$perSessionCost = ($fetch_maintenance['ussd_session_cost'] * ($dateSeconds - $current_seconds)) - $currenct_sessionCharges;
$calcHops = $currenctHops + 1;
$loanBookingCost = $fetch_maintenance['loan_booking'];
$verveCardLinkingFee = $fetch_maintenance['verveCardLinkingFee'];
$transferToCardCharges12 = $fetch_maintenance['transferToCardCharges'];


//Customer Details
($dedicated_ussd_prefix != "0") ? $search_account = mysqli_query($link, "SELECT * FROM borrowers WHERE phone = '$phone' AND dedicated_ussd_prefix = '$dedicated_ussd_prefix'") : "";
($dedicated_ussd_prefix == "0") ? $search_account = mysqli_query($link, "SELECT * FROM borrowers WHERE phone = '$phone' AND dedicated_ussd_prefix = '0'") : "";
$fetch_account = mysqli_fetch_array($search_account);
$my_borrowerid = $fetch_account['id'];
$ussd_fname = $fetch_account['fname'];
$ussd_lname = $fetch_account['lname'];
$ussd_email = $fetch_account['email'];
$ussd_dob = $fetch_account['dob'];
$my_account_number = $fetch_account['account'];
$currency = $fetch_account['currency'];
$esusu_balance  = $fetch_account['balance'];
$bwallet_balance = $fetch_account['wallet_balance'];
$binvestment_balance = $fetch_account['investment_bal'];
$borrower_registeral = $fetch_account['branchid'];
$borrower_sbranchid = $fetch_account['sbranchid'];
$bvirtual_phone_no = $fetch_account['virtual_number'];
$bvirtual_acctno = $fetch_account['virtual_acctno'];
$myfull_name = $ussd_fname.' '.$ussd_lname;
$bcard_id = $fetch_account['card_id'];
$bcard_issurer = $fetch_account['card_issurer'];
$my_bussdtpin = $fetch_account['tpin'];
$myussdcode = $fetch_account['dedicated_ussd_prefix'];
$bbvn = $fetch_account['unumber'];

$realTransferToCardCharges = ($transferToCardCharges12 == "") ? $transferToCardCharges1 : $transferToCardCharges12;


function detectCardLinking() {
    global $link, $my_account_number, $borrower_registeral;

    $query = mysqli_query($link, "SELECT * FROM borrowers WHERE card_id != 'NULL' AND card_reg = 'Yes' AND card_issurer = 'VerveCard' AND account = '$my_account_number' AND branchid = '$borrower_registeral'");
    $query_num = mysqli_num_rows($query);
    
    if($query_num == 1){
        
        return 1;
        
    }else{
        
        return 0;
        
    }

}

function detectCardRegStatus() {
    global $link, $my_account_number, $borrower_registeral;

    $query = mysqli_query($link, "SELECT * FROM borrowers WHERE card_reg = 'Yes' AND account = '$my_account_number' AND branchid = '$borrower_registeral'");
    $query_num = mysqli_num_rows($query);
    
    if($query_num == 1){
        
        return 1;
        
    }else{
        
        return 0;
        
    }

}

$search_agent = mysqli_query($link, "SELECT * FROM user WHERE phone = '$phone' AND (bprefix = 'AG' OR bprefix = 'INS' OR bprefix = 'MER') AND comment = 'Approved' AND dedicated_ussd_prefix = '$dedicated_ussd_prefix'");
$fetch_agent = mysqli_fetch_array($search_agent);
$unique_merchant_id = $fetch_agent['created_by'];
$unique_role = $fetch_agent['role'];

//INSTITUTION DIRECTORIES
$fet_institution = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$unique_merchant_id' OR institution_id = '$borrower_registeral' OR institution_id = '$my_inst_id'");
$fetch_all_i = mysqli_fetch_array($fet_institution);
$directory_officialEmail = $fetch_all_i['official_email'];
$directory_all_wal = $fetch_all_i['wallet_balance'];
$myUSSDChargesPerSession = $directory_all_wal - $perSessionCost;

$search_permission = mysqli_query($link, "SELECT * FROM my_permission WHERE companyid = '$unique_merchant_id' AND urole = '$unique_role'");
$fetch_permission = mysqli_fetch_array($search_permission);
$enable_withdrawal_charges = $fetch_permission['enable_charges'];

//PERSONALIZE LAST NAME FOR EACH USERS
$my_lname = (mysqli_num_rows($search_account) == 1) ? $fetch_account['fname'] : $fetch_agent['name'];

//LEDGER TRANSACTION RECORDS
$search_trans = mysqli_query($link, "SELECT * FROM transaction WHERE phone = '$phone' ORDER BY id DESC");
$fetch_trans = mysqli_fetch_array($search_trans);

    
    if(mysqli_num_rows($search_account) == 1 && mysqli_num_rows($search_agent) == 0 && $borrower_registeral != "" && $myussdcode != "0")
    {
        
        /**
         * TO BE ACCESSED BY THE INSTITUTIONAL REGISTERED 
         * CUSTOMER ONLY USING DEDICATED USSD CODE MAPPED TO 
         * THE INSTITUTION ACCOUNT E.G: *347*62*INSTITUTION CODE#
         * 
        * */
    
        include("ussd/ussdCode_for_institutionRegisteredCustomer.php");
        
    }
    else if(mysqli_num_rows($search_account) == 1 && mysqli_num_rows($search_agent) == 0 && $borrower_registeral != "" && $myussdcode == "0")
    {
        
        /**
         * TO BE ACCESSED BY THE SELF SERVICE REGISTERED 
         * CUSTOMER ONLY USING *347*62#.
         * 
        * */
        
        include("ussd/ussdCode_for_selfServiceRegisteredCustomer.php");
        
    }
    else if(mysqli_num_rows($search_account) == 0 && mysqli_num_rows($search_agent) == 1)
    {
        
        /**
         * TO BE ACCESSED BY THE INSTITUTION / AGENT / MERCHANT 
         * STAFF ONLY USING THERE DEDICATED USSD CODE MAPPED TO THERE 
         * INSTITUTION ACCOUNT E.G: *347*62*INSTITUTION CODE#
         * 
        * */
         $response = "END Opps! Staff module not available yet.. Please check back later!! \n";
        //include("ussd/ussdCode_for_institutionStaff.php");
        
    }
    else if(mysqli_num_rows($search_account) == 0 && mysqli_num_rows($search_agent) == 0){
        
        /**
         * TO BE ACCESSED BY NEW CUSTOMER WILLING TO REGISTER UNDER INSTITUTION
         * USING DEDICATED CODE SUCH AS *347*62*INSTITUTION CODE#
         * 
        * */
        
        include("ussd/ussdCode_for_newCustomerRegistration.php");
        
    }
    
    
// Echo the response back to the API
header('Content-type: text/plain');
echo $response;
}
?>