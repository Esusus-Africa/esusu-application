<?php 
//error_reporting(0); 
include('connect.php');
 session_start(); 
//Check whether the session variable SESS_MEMBER_ID is present or not
$mycookies = $_COOKIE['PHPSESSID'];
$myuserid = $_SESSION['tid'];
$search_loginCookies = mysqli_query($link, "SELECT * FROM session_tracker WHERE userid = '$myuserid' AND browserSession = '$mycookies' AND loginStatus = 'On'") or die ("Error: " . mysqli_error($link));
$cookiesNum = mysqli_num_rows($search_loginCookies);

if (!isset($_SESSION['tid']) || (trim($_SESSION['tid']) == '') || ($cookiesNum == '0')) { 

    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $ourCustomUrl = $protocol . $_SERVER['HTTP_HOST'];

    $date_time = date("Y-m-d h:i:s");

    mysqli_query($link, "UPDATE session_tracker SET loginStatus = 'Off', LastVisitDateTime = '$date_time' WHERE browserSession = '$mycookies'");
    
    ?>
    <meta http-equiv="refresh" content="1;url=<?php echo $ourCustomUrl; ?>/timeout/index">
    <!--<script>
    window.location = "<?php //echo $ourCustomUrl; ?>/timeout/index";
    </script>-->
<?php 
}
$acnt_id=$_SESSION['acctno'];
$coopid=$_SESSION['coopid'];
$vend_id=$_SESSION['vendorid'];
$vstaff_id=$_SESSION['vstaff'];
$session_id=$_SESSION['tid'];
$instid = $_SESSION['instid'];
$istaff_id = $_SESSION['istaff'];

function isMobileDevice(){
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function panNumberMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 6) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -4);
}

function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);
}

function reformatDate($date, $from_format = 'd/m/Y', $to_format = 'Y-m-d') {
    $date_aux = date_create_from_format($from_format, $date);
    return date_format($date_aux,$to_format);
}

function random_strings($length_of_string) 
{ 
    // String of all alphanumeric character 
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
  
    // Shufle the $str_result and returns substring 
    // of specified length 
    return substr(str_shuffle($str_result), 0, $length_of_string); 
}

function convertDateTime($DateTime){
    
    $userTimezone = new DateTimeZone('GMT');
    $gmtTimezone = new DateTimeZone(date_default_timezone_get());
    $myDateTime = new DateTime(date('Y-m-d G:i:s', strtotime($DateTime)), $gmtTimezone);
    $offset = $userTimezone->getOffset($myDateTime);
    $myInterval=DateInterval::createFromDateString((string)$offset . 'seconds');
    $myDateTime->add($myInterval);
    $result = $myDateTime->format('Y-m-d h:i A');
    return $result;
    
    /*$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$DateTime,new DateTimeZone(date_default_timezone_get()));
    $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
    $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
    $correctdate = $acst_date->format('Y-m-d g:i A');
    return $correctdate;*/
}

function is_base64_encoded($data)
{
    if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
       return TRUE;
    } else {
       return FALSE;
    }
}

//GLOBAL SETTINGS
$mysystem_config = mysqli_query($link, "SELECT * FROM systemset");
$fetchsys_config = mysqli_fetch_array($mysystem_config);
$myitoday_record = date("Y-m-d");
$wemaVAPrefix = $fetchsys_config['wbPrefix'];
$sterlinkInputKey = $fetchsys_config['sterlinkInputKey'];
$sterlingIv = $fetchsys_config['sterlingIv'];
//$myitoday_record1 = date("Y-m-d").' 24'.':00'.':00';

//FETCH STANDARD FEES
$bvn_fee = $fetchsys_config['bvn_fee'];
$verveCardLinkingFee = $fetchsys_config['verveCardLinkingFee'];
$verveCardPrefundAmt = $fetchsys_config['verveCardPrefundAmt'];
//$esusuPayCharges = $ifetch_maintenance_model['cgate_charges'];
//$posCharges = $ifetch_maintenance_model['pos_charges'];

//WALLET.AFRICA CREDENTIALS
$walletafrica_skey = $fetchsys_config['walletafrica_skey'];
$walletafrica_pkey = $fetchsys_config['walletafrica_pkey'];
$walletafrica_status = $fetchsys_config['walletafrica_vastatus'];
$walletafrica_airtimestatus = $fetchsys_config['walletafrica_airtimestatus'];

//OnePipe CREDENTIALS
$onePipeSKey = $fetchsys_config['onePipeSKey'];
$onePipeApiKey = $fetchsys_config['onePipeApiKey'];

//ACCESS BANK CREDENTIALS
$accessBank_apimSubKey = $fetchsys_config['accessBank_apimSubKey'];
$accessBank_auditId = $fetchsys_config['accessBank_auditId'];
$accessBank_appId = $fetchsys_config['accessBank_appId'];

//MONIFY CREDENTIALS
$mo_api_key = $fetchsys_config['mo_api_key'];
$mo_secret_key = $fetchsys_config['mo_secret_key'];
$mo_contract_code = $fetchsys_config['mo_contract_code'];
$mo_status = $fetchsys_config['mo_status'];
$mo_virtualacct_status = $fetchsys_config['mo_virtualacct_status'];

//RAVE CREDENTIALS
$rave_secret_key = $fetchsys_config['secret_key'];
$rave_public_key = $fetchsys_config['public_key'];

//BANCORE CREDENTIALS
$bancore_merchantID = $fetchsys_config['bancore_merchant_acctid'];
$bancore_mprivate_key = $fetchsys_config['bancore_merchant_pkey'];

//PROVIDUS CREDENTIALS
$providusUName = $fetchsys_config['providusUName'];
$providusPass = $fetchsys_config['providusPass'];
$providusClientId = $fetchsys_config['providusClientId'];
$providusClientSecret = $fetchsys_config['providusClientSecret'];

//VERVE CREDENTIALS
$verveAppId = $fetchsys_config['verveAppId'];
$verveAppKey = $fetchsys_config['verveAppKey'];
$verveSettlementAcctNo = $fetchsys_config['verveSettlementAcctNo'];
$verveSettlementAcctName = $fetchsys_config['verveSettlementAcctName'];
$verveSettlementBankCode = $fetchsys_config['verveSettlementBankCode'];
$transferToCardCharges1 = $fetchsys_config['transferToCardCharges'];

//CGATE CREDENTIALS
$cgate_username = $fetchsys_config['cgate_username'];
$cgate_password = $fetchsys_config['cgate_password'];
$cgate_mid = $fetchsys_config['cgate_mid'];
$cgate_charges = $fetchsys_config['cgate_charges'];
$gtbcgate_username = $fetchsys_config['gtbcgate_username'];
$gtbcgate_password = $fetchsys_config['gtbcgate_password'];

//GTBANK CREDENTIALS
$gtbaccesscode = $fetchsys_config['gtbaccesscode'];
$gtbusername = $fetchsys_config['gtbusername'];
$gtbpassword = $fetchsys_config['gtbpassword'];
$gtbsourceAcctNo = $fetchsys_config['gtbsourceAcctNo'];

//RUBIES CREDENTIALS
$rubbiesSecKey = $fetchsys_config['rubbiesSecKey'];
$rubbiesPubKey = $fetchsys_config['rubbiesPubKey'];

//PAYANT<>STERLING CREDENTIALS
$payantEmail = $fetchsys_config['payantEmail'];
$payantPwd = $fetchsys_config['payantPwd'];
$payantOrgId = $fetchsys_config['payantOrgId'];

//PRIME AIRTIME CREDENTIALS
$accessToken = $fetchsys_config['primeairtime_token'];

//VERIFY.AFRICA CREDENTIALS
$vafrica_userid = $fetchsys_config['vafrica_userid'];
$vafrica_ninfullapi = $fetchsys_config['vafrica_ninfullapi'];
$vafrica_ninphonefullapi = $fetchsys_config['vafrica_ninphonefullapi'];
$vafrica_ninnamefullapi = $fetchsys_config['vafrica_ninnamefullapi'];
$vafrica_bvnfullapi = $fetchsys_config['vafrica_bvnfullapi'];

//LABEL SETTINGS
$search_label = mysqli_query($link, "SELECT * FROM label_settings WHERE companyid='$instid'");
$fetch_label = mysqli_fetch_array($search_label);
$sno_label = $fetch_label['sno'];
$evn_label = $fetch_label['evn'];
$bvn_label = $fetch_label['bvn'];

//INSTITUTION INFORMATION START FROM HERE
//INSTITUTION BASIC INF0RMATION
$search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$instid'");
$fetch_inst = mysqli_fetch_array($search_inst);
$inst_name = $fetch_inst['institution_name'];
$inst_email = $fetch_inst['official_email'];
$inst_phone = $fetch_inst['official_phone'];
$iwallet_balance = $fetch_inst['wallet_balance'];
$ireferral_bonus = $fetch_inst['referral_bonus'];
$insti_subaccount_code = $fetch_inst['subaccount_code'];
$iaccount_type = $fetch_inst['account_type'];

$isettlement_country = $fetch_inst['settlement_country'];
$isettlement_acctno = $fetch_inst['settlement_acctno'];
$isettlement_bankcode = $fetch_inst['settlement_bankcode'];
$isettlement_bankname = $fetch_inst['settlement_bankname'];
$isettlement_acctname = $fetch_inst['settlement_acctname'];

$isearch_pendinginvestment = mysqli_query($link, "SELECT * FROM investment_notification WHERE merchantid = '$instid' AND vendorid = '' AND status = 'Pending'");
$myinum_pendinginvestment = mysqli_num_rows($isearch_pendinginvestment);

$isearch_maintenance_model = mysqli_query($link, "SELECT * FROM maintenance_history WHERE company_id = '$instid' AND status = 'Activated'");
$ifetch_maintenance_model = mysqli_fetch_array($isearch_maintenance_model);
$model = $ifetch_maintenance_model['billing_type'];
$ibvn_fee = ($ifetch_maintenance_model['bvn_fee'] === "") ? $bvn_fee : $ifetch_maintenance_model['bvn_fee'];
$iussd_session_cost = $ifetch_maintenance_model['ussd_session_cost'];
$bank_transferCharges = $ifetch_maintenance_model['bank_transfer_charges'];
$bank_transferCommission = $ifetch_maintenance_model['bank_transfer_commission'];
$transferToCardCharges12 = ($ifetch_maintenance_model['transferToCardCharges'] === "") ? $transferToCardCharges1 : $ifetch_maintenance_model['transferToCardCharges'];
$card_transferCommission = $ifetch_maintenance_model['transferToCardCommission'];
$verveCardLinkingFee2 = $ifetch_maintenance_model['verveCardLinkingFee'];
$billing_type = $model;
$iairtimeDataCommission = ($ifetch_maintenance_model['airtimeData_comm'] == "") ? $fetchsys_config['airtimedata_commission'] : $ifetch_maintenance_model['airtimeData_comm'];
$ibillPaymentCommission = ($ifetch_maintenance_model['billpay_comm'] == "") ? $fetchsys_config['bp_commission'] : $ifetch_maintenance_model['billpay_comm'];
$ininVerificationCharges = $ifetch_maintenance_model['ninVerificationCharges']; //new line just added
$ibvnVerificationCharges = $ifetch_maintenance_model['bvnVerificationCharges']; //new line just added
//$esusuPayCharges2 = $ifetch_maintenance_model['esusupay_charges'];
//$posCharges2 = $ifetch_maintenance_model['pos_charges'];

//INSTITUTION STAFF DATA
$inst_staff = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$instid' AND username = '$istaff_id' AND (bprefix = 'AG' OR bprefix = 'INS' OR bprefix = 'MER' OR bprefix = 'VEN')") or die(mysqli_error());
$istaff_row = mysqli_fetch_array($inst_staff);
$iname = $istaff_row['name'].' '.$istaff_row['lname'].' '.$istaff_row['mname'];
$ifname = $istaff_row['name'];
$ilname = $istaff_row['lname'];
$iuid = $istaff_row['id'];
$iuserid = $istaff_row['userid'];
$iusername = $istaff_row['username'];
$irole = $istaff_row['role'];
$myiemail_addrs = $istaff_row['email'];
$myiphone = $istaff_row['phone'];
$institution_id = $istaff_row['created_by'];
$isbranchid = $istaff_row['branchid'];
$myiepin = $istaff_row['tpin'];
$idept = $istaff_row['dept'];
$isubagent_wbalance = $istaff_row['wallet_balance'];
$itransfer_balance = $istaff_row['transfer_balance'];
$ivirtual_phone_no = $istaff_row['virtual_number'];
$ivirtual_acctno = $istaff_row['virtual_acctno'];
$ibvn = $istaff_row['addr2'];
$igender = $istaff_row['gender'];
$idob = $istaff_row['dob'];
$icity = $istaff_row['city'];
$istate = $istaff_row['state'];
$iissurer = $istaff_row['card_issurer'];
$icard_id = $istaff_row['card_id'];
$iAcctOfficer = $istaff_row['acctOfficer'];
$businessName = $istaff_row['businessName'];
$allow_auth = $istaff_row['allow_auth'];
$active_status = $istaff_row['status'];

//AIRTIME LIMIT PER DAY FOR INSTITUTION/AGENT
$inst_indivWallet = mysqli_query($link, "SELECT SUM(debit) FROM wallet_history WHERE date_time LIKE '$myitoday_record%' AND initiator = '$iuid' AND (paymenttype = 'Airtime - WEB' OR paymenttype = 'Databundle - WEB')") or die(mysqli_error());
$istaff_indivWalletRow = mysqli_fetch_array($inst_indivWallet);
$imyDailyAirtimeData = $istaff_indivWalletRow['SUM(debit)'];

$inst_indivWallet2 = mysqli_query($link, "SELECT SUM(debit) FROM wallet_history WHERE date_time LIKE '$myitoday_record%' AND initiator = '$iuid' AND paymenttype = 'BANK_TRANSFER'") or die(mysqli_error());
$istaff_indivWalletRow2 = mysqli_fetch_array($inst_indivWallet2);
$imyDailyTransferLimit = $istaff_indivWalletRow2['SUM(debit)'];

//INSTITUTION STAFF / SUB-AGENT TRANSFER LIMIT PER TRANSACTION
$inst_indivtransfer = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$iuid' AND companyid = '$instid'") or die(mysqli_error());
$istaff_indivRow = mysqli_fetch_array($inst_indivtransfer);
$itransferLimitPerDay = $istaff_indivRow['transferLimitPerDay'];
$itransferLimitPerTrans = $istaff_indivRow['transferLimitPerTrans'];
$iglobalDailyAirtime_DataLimit = $istaff_indivRow['airtime_dataLimitPerDay'];
$iglobal_airtimeLimitPerTrans = $istaff_indivRow['airtime_dataLimitPerTrans'];

$verifySMS_Provider = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$instid' AND status = 'Activated'") or die (mysqli_error($link));
$verifySMS_Provider1 = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'") or die (mysqli_error($link));
$getSMS_ProviderNum = mysqli_num_rows($verifySMS_Provider);
$fetchSMS_Provider = ($getSMS_ProviderNum == 0) ? mysqli_fetch_array($verifySMS_Provider1) : mysqli_fetch_array($verifySMS_Provider);
$ozeki_password = $fetchSMS_Provider['password'];
$ozeki_url = $fetchSMS_Provider['api'];

//SEARCH FOR MEMBER SETTINGS
$verify_icurrency = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instid'");
$fetch_icurrency = mysqli_fetch_object($verify_icurrency);
$icurrency = $fetch_icurrency->currency;
$isenderid = $fetch_icurrency->sender_id;
$isubagent_wallet = $fetch_icurrency->subagent_wallet;
$iremitaMerchantId = $fetch_icurrency->remitaMerchantId;
$iremitaApiKey = $fetch_icurrency->remitaApiKey;
$iremitaServiceId = $fetch_icurrency->remitaServiceId;
$iremitaApiToken = $fetch_icurrency->remitaApiToken;
$idedicated_ussd_prefix = $fetch_icurrency->dedicated_ussd_prefix;
$iussd_prefixStatus = $fetch_icurrency->ussd_status;
//TURN ON AND OFF MODULES
$ibranch_manager = $fetch_icurrency->branch_manager;
$idept_settings = $fetch_icurrency->dept_settings;
$ipermission_manager = $fetch_icurrency->permission_manager;
$isubagent_manager = $fetch_icurrency->subagent_manager;
$istaff_manager = $fetch_icurrency->staff_manager;
$icustomer_manager = $fetch_icurrency->customer_manager;
$iwallet_manager = $fetch_icurrency->wallet_manager;
$ivendor_manager = $fetch_icurrency->vendor_manager;
$ibvn_manager = $fetch_icurrency->bvn_manager;
$icard_issuance_manager = $fetch_icurrency->card_issuance_manager;
$iloan_manager = $fetch_icurrency->loan_manager;
$iinvestment_manager = $fetch_icurrency->investment_manager;
$iteller_manager = $fetch_icurrency->teller_manager;
$icharges_manager = $fetch_icurrency->charges_manager;
$isavings_account = $fetch_icurrency->savings_account;
$ireports_module = $fetch_icurrency->reports_module;
$ipayroll_module = $fetch_icurrency->payroll_module;
$iincome_module = $fetch_icurrency->income_module;
$iexpenses_module = $fetch_icurrency->expenses_module;
$igeneral_settings = $fetch_icurrency->general_settings;
$iproduct_manager = $fetch_icurrency->product_manager;
$iotp_option = $fetch_icurrency->otp_option;
$ieditoption = $fetch_icurrency->editoption;
$igroupcontribution = $fetch_icurrency->groupcontribution;
$ipos_manager = $fetch_icurrency->pos_manager;
$inip_route = $fetch_icurrency->nip_route;
$iinvite_manager = $fetch_icurrency->invite_manager;
$ihalalpay_module = $fetch_icurrency->halalpay_module;
$iwallet_creation = $fetch_icurrency->wallet_creation;
$ibvn_route = $fetch_icurrency->bvn_route;
$iaccount_manager = $fetch_icurrency->account_manager;
$isms_checker = $fetch_icurrency->sms_checker;
$iva_provider = $fetch_icurrency->va_provider;
$idefaultAcct = $fetch_icurrency->defaultAcct;
$ipool_account = $fetch_icurrency->pool_account;
$iairtime_route = $fetch_icurrency->airtime;
$idatabundle_route = $fetch_icurrency->databundle;
$ibillpayment = $fetch_icurrency->billpayment;
$ipending_manager = $fetch_icurrency->pending_manager;
$iva_fortill = $fetch_icurrency->va_fortill;
$icardless_wroute = $fetch_icurrency->cardless_wroute;
$idedicated_sms_gateway = $fetch_icurrency->dedicated_sms_gateway;
$idedicated_ledgerAcctNo_prefix = $fetch_icurrency->dedicated_ledgerAcctNo_prefix;
$icopyright = $fetch_icurrency->copyright;
$ibvn_verification = $fetch_icurrency->enable_bvn;
$iaccount_verification = $fetch_icurrency->enable_acct_verification;
$iallow_login_otp = $fetch_icurrency->allow_login_otp;
$iverification_manager = $fetch_icurrency->verification_manager; //new update added
$iidVType = $fetch_icurrency->idVType; //new update added
$ienrolment_manager = $fetch_icurrency->enrolment_manager; //new update added

$search_kycRequirement = mysqli_query($link, "SELECT * FROM required_kyc WHERE companyid = '$instid'");
$fetch_kycRequirement = mysqli_fetch_array($search_kycRequirement);
$bget_kycRequirementNum = mysqli_num_rows($search_kycRequirement);

$isearch_lvWidget = mysqli_query($link, "SELECT * FROM dedicated_livechat_widget WHERE companyid = '$instid'");
$ifetch_lvWidget = mysqli_fetch_array($isearch_lvWidget);
$ilvWidgetStatus = $ifetch_lvWidget['status']; //Activated OR NotActivated 

$isearch_emailConfig = mysqli_query($link, "SELECT * FROM email_config WHERE companyid = '$instid'");
$ifetch_emailConfig = mysqli_fetch_array($isearch_emailConfig);
$iemailConfigStatus = $ifetch_emailConfig['status']; //Activated OR NotActivated

//GET EXACT ASSIGNED WALLET BALANCE
$iassigned_walletbal = (($irole === "agent_manager" || $irole === "institution_super_admin" || $irole === "merchant_super_admin") && ($isubagent_wallet == "Enabled" || $isubagent_wallet == "No" || $isubagent_wallet == "Disabled") ? $iwallet_balance : (($irole != "agent_manager" || $irole != "institution_super_admin" || $irole != "merchant_super_admin") && ($isubagent_wallet == "No" || $isubagent_wallet == "Disabled") ? $iwallet_balance : $isubagent_wbalance));

//SEARCH INTERNAL REVIEW NO.
$inst_IR = mysqli_query($link, "SELECT * FROM loan_info WHERE branchid = '$instid' AND sbranchid = '$isbranchid' AND dept = '$idept' AND status = 'Internal-Review'");
$i_IR_num = mysqli_num_rows($inst_IR);

//SEARCH ALL DIRECT DEBIT ACTIVATED
$inst_AllDD = mysqli_query($link, "SELECT * FROM loan_info WHERE branchid = '$instid' AND mandate_status = 'Activated'");
$i_AllDD_num = mysqli_num_rows($inst_AllDD);

//SEARCH BRANCH DIRECT DEBIT ACTIVATED
$inst_INDIV = mysqli_query($link, "SELECT * FROM loan_info WHERE branchid = '$instid' AND sbranchid = '$isbranchid' AND mandate_status = 'Activated'");
$i_INDIV_num = mysqli_num_rows($inst_INDIV);

//SEARCH INDIVIDUAL DIRECT DEBIT ACTIVATED
$inst_BRDD = mysqli_query($link, "SELECT * FROM loan_info WHERE branchid = '$instid' AND mandate_status = 'Activated' AND (agent = '$iuid' OR agent = '$iname')");
$i_BRDD_num = mysqli_num_rows($inst_BRDD);

$detectDD = ($isbranchid === "") ? $i_INDIV_num : $i_BRDD_num;

$iactivatedDD = ($irole != "agent_manager" || $irole != "institution_super_admin" || $irole != "merchant_super_admin") ? $i_AllDD_num : $detectDD;


//SEARCH INSTITUTION ACTIVE SUBSCRIPTION RECORDS
$instmem_sub = mysqli_query($link, "SELECT * from saas_subscription_trans WHERE coopid_instid = '$instid' AND usage_status = 'Active'") or die(mysqli_error());
$inst_subrow = mysqli_fetch_array($instmem_sub);
$isub_token = $inst_subrow['sub_token'];
$isub_plan_code = $inst_subrow['plan_code'];
$icustomer_limit = $inst_subrow['customer_limit'];
$istaff_limit = $inst_subrow['staff_limit'];
$ibranch_limit = $inst_subrow['branch_limit'];
$iamount_paid = $inst_subrow['amount_paid'];

//SEARCH BRANCH INFORMATION
$branch_query = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$isbranchid'")or die(mysqli_error($link));
$branch_row = mysqli_fetch_array($branch_query);
$bcurrency = $branch_row['currency'];
$c_rate = $branch_row['c_rate'];
$bname = $branch_row['bname'];
$branch_addrs = $branch_row['branch_addrs'];
$branch_country = $branch_row['bcountry'];
$branch_province = $branch_row['branch_province'];
$min_loanamout = $branch_row['minloan_amount'];
$max_loanamount = $branch_row['maxloan_amount'];
$min_interest_rate = $branch_row['min_interest_rate'];
$max_interest_rate = $branch_row['max_interest_rate'];

//
//INSTITUTION INFORMATION ENDS HERE
//

//VENDOR INFORMATION START FROM HERE
$vnst_staff = mysqli_query($link, "SELECT * FROM user WHERE branchid = '$vend_id' AND username = '$vstaff_id' AND bprefix = 'VEN'") or die(mysqli_error());
$vstaff_row = mysqli_fetch_array($vnst_staff);
$vname = $vstaff_row['name'].' '.$vstaff_row['lname'].' '.$vstaff_row['mname'];
$vfname = $vstaff_row['name'];
$vlname = $vstaff_row['lname'];
$vuid = $vstaff_row['id'];
$vrole = $vstaff_row['role'];
$vgender = $vstaff_row['gender'];
$vdob = $vstaff_row['dob'];
$vsbranchid = $vstaff_row['branchid'];
$myvepin = $vstaff_row['tpin'];
$vcreated_by = $vstaff_row['created_by'];
$vtransfer_balance = $vstaff_row['transfer_balance'];
$vvirtual_phone_no = $vstaff_row['virtual_number'];
$vvirtual_acctno = $vstaff_row['virtual_acctno'];
$vactive_status = $vstaff_row['active_status'];

$ven_indivWallet = mysqli_query($link, "SELECT SUM(debit) FROM wallet_history WHERE date_time LIKE '$myitoday_record%' AND initiator = '$vuid' AND (paymenttype = 'Airtime - WEB' OR paymenttype = 'Databundle - WEB')") or die(mysqli_error());
$vstaff_indivWalletRow = mysqli_fetch_array($ven_indivWallet);
$vmyDailyAirtimeData = $vstaff_indivWalletRow['SUM(debit)'];

$ven_indivWallet2 = mysqli_query($link, "SELECT SUM(debit) FROM wallet_history WHERE date_time LIKE '$myitoday_record%' AND initiator = '$vuid' AND paymenttype = 'BANK_TRANSFER'") or die(mysqli_error());
$vstaff_indivWalletRow2 = mysqli_fetch_array($ven_indivWallet2);
$vmyDailyTransferLimit = $vstaff_indivWalletRow2['SUM(debit)'];

//VENDOR USER TRANSFER LIMIT PER TRANSACTION
$vendor_indivtransfer = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$vuid' AND companyid = '$vcreated_by'") or die(mysqli_error());
$vstaff_indivRow = mysqli_fetch_array($vendor_indivtransfer);
$vtransferLimitPerDay = $vstaff_indivRow['transferLimitPerDay'];
$vtransferLimitPerTrans = $vstaff_indivRow['transferLimitPerTrans'];
$vglobalDailyAirtime_DataLimit = $vstaff_indivRow['airtime_dataLimitPerDay'];
$vglobal_airtimeLimitPerTrans = $vstaff_indivRow['airtime_dataLimitPerTrans'];

$vverifySMS_Provider = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$vcreated_by' AND status = 'Activated'") or die (mysqli_error($link));
$vverifySMS_Provider1 = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'") or die (mysqli_error($link));
$vgetSMS_ProviderNum = mysqli_num_rows($vverifySMS_Provider);
$vfetchSMS_Provider = ($vgetSMS_ProviderNum == 0) ? mysqli_fetch_array($vverifySMS_Provider1) : mysqli_fetch_array($vverifySMS_Provider);
$vozeki_password = $vfetchSMS_Provider['password'];
$vozeki_url = $vfetchSMS_Provider['api'];

$vendor_query = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vend_id'")or die(mysqli_error());
$vendor_row = mysqli_fetch_array($vendor_query);
$vc_name = $vendor_row['cname'];
$vo_email = $vendor_row['cemail'];
$vo_phone = $vendor_row['cphone'];
$vuname = $vendor_row['cusername'];
$v_ctype = $vendor_row['ctype'];
$vendorid = $vendor_row['companyid'];
$vwallet_balance = $vendor_row['wallet_balance'];
$vinvestment_balance = $vendor_row['investment_balance'];
$vcurrency = $vendor_row['currency'];

$vsearch_pendinginvestment = mysqli_query($link, "SELECT * FROM investment_notification WHERE vendorid = '$vend_id' AND status = 'Pending'");
$myvnum_pendinginvestment = mysqli_num_rows($vsearch_pendinginvestment);

$vsearch_maintenance_model = mysqli_query($link, "SELECT * FROM maintenance_history WHERE company_id = '$vend_id' AND status = 'Activated' AND billing_type = 'PAYG'");
$vfetch_maintenance_model = mysqli_fetch_array($vsearch_maintenance_model);
$vbilling_type = $vfetch_maintenance_model['billing_type'];
$vairtimeDataCommission = ($vfetch_maintenance_model['airtimeData_comm'] == "") ? $fetchsys_config['airtimedata_commission'] : $vfetch_maintenance_model['airtimeData_comm'];
$vbillPaymentCommission = ($vfetch_maintenance_model['billpay_comm'] == "") ? $fetchsys_config['bp_commission'] : $vfetch_maintenance_model['billpay_comm'];

$verify_vcurrency = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$vcreated_by'");
$fetch_vcurrency = mysqli_fetch_object($verify_vcurrency);
$mvcname = $fetch_vcurrency->cname;
$mvlogo = $fetch_vcurrency->logo;
$mvsenderid = $fetch_vcurrency->sender_id;
$votp_option = $fetch_vcurrency->otp_option;
$vnip_route = $fetch_vcurrency->nip_route;
$vdefaultAcct = $fetch_vcurrency->defaultAcct;
$vairtime_route = $fetch_vcurrency->airtime;
$vdatabundle_route = $fetch_vcurrency->databundle;
$vbillpayment = $fetch_vcurrency->billpayment;
$vcopyright = $fetch_vcurrency->copyright;
$vbvn_verification = $fetch_vcurrency->enable_bvn;
$vaccount_verification = $fetch_vcurrency->enable_acct_verification;
$vallow_login_otp = $fetch_vcurrency->allow_login_otp;

$vsearch_lvWidget = mysqli_query($link, "SELECT * FROM dedicated_livechat_widget WHERE companyid = '$vcreated_by'");
$vfetch_lvWidget = mysqli_fetch_array($vsearch_lvWidget);
$vlvWidgetStatus = $vfetch_lvWidget['status']; //Activated OR NotActivated

$vsearch_emailConfig = mysqli_query($link, "SELECT * FROM email_config WHERE companyid = '$vcreated_by'");
$vfetch_emailConfig = mysqli_fetch_array($vsearch_emailConfig);
$vemailConfigStatus = $vfetch_emailConfig['status']; //Activated OR NotActivated
//VENDOR INFORMATION ENDS HERE

//COOPERATIVE INFORMATION START FROM HERE
$search_coope = mysqli_query($link, "SELECT * FROM cooperatives WHERE coopid = '$coopid'");
$fetch_coope = mysqli_fetch_array($search_coope);
$coop_name = $fetch_coope['coopname'];
$coop_phone = $fetch_coope['official_phone'];
$coop_email = $fetch_coope['official_email'];
$cwallet_balance = $fetch_coope['wallet_balance'];
$creferral_bonus = $fetch_coope['referral_bonus'];

$verify_ccurrency = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$coopid'");
$fetch_ccurrency = mysqli_fetch_object($verify_ccurrency);
$ccurrency = $fetch_ccurrency->currency;
//COOPERATIVE INFORMATION ENDS HERE


//SEARCH COOPERATIVE RECORDS
$coopmem_query = mysqli_query($link, "SELECT * FROM coop_members WHERE id = '$session_id'")or die(mysqli_error());
$coopmem_row = mysqli_fetch_array($coopmem_query);
$memberid = $coopmem_row['memberid'];
$coopmem_name = $coopmem_row['fullname'];
$coopmem_email = $coopmem_row['email'];
$coopmem_phone = $coopmem_row['phone'];

$coopmem_sub = mysqli_query($link, "SELECT * from saas_subscription_trans WHERE coopid_instid = '$coopid' AND usage_status = 'Active'") or die(mysqli_error());
$coopmem_subrow = mysqli_fetch_array($coopmem_sub);
$sub_token = $coopmem_subrow['sub_token'];
$csub_plan_code = $coopmem_subrow['plan_code'];
$cstaff_limit = $coopmem_subrow['staff_limit'];

$vendorsub = mysqli_query($link, "SELECT * from saas_subscription_trans WHERE coopid_instid = '$vend_id' AND usage_status = 'Active'") or die(mysqli_error());
$vendor_subrow = mysqli_fetch_array($vendorsub);
$vsub_token = $vendor_subrow['sub_token'];
$vsub_plan_code = $vendor_subrow['plan_code'];
$vcustomer_limit = $vendor_subrow['customer_limit'];
$vstaff_limit = $vendor_subrow['staff_limit'];
$vbranch_limit = $vendor_subrow['branch_limit'];
$vamount_paid = $vendor_subrow['amount_paid'];

//SEARCH CUSTOMER INFORMATION
$user_query2 = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$session_id'")or die(mysqli_error());
$user_row2 = mysqli_fetch_array($user_query2);
$bname = $user_row2['fname'].' '.$user_row2['lname'];
$email2 = $user_row2['email'];
$bbranchid = $user_row2['branchid'];
$bsbranchid = $user_row2['sbranchid'];
$acctno = $user_row2['account'];
$balance = $user_row2['balance'];
$bwallet_balance = $user_row2['wallet_balance'];
$binvest_bal = $user_row2['investment_bal'];
$phone2 = $user_row2['phone'];
$bvirtual_phone_no = $user_row2['virtual_number'];
$bvirtual_acctno = $user_row2['virtual_acctno'];
$bbcurrency = $user_row2['currency'];
$myfn = $user_row2['fname'];
$myln = $user_row2['lname'];
$myuepin = $user_row2['tpin'];
$issurer = $user_row2['card_issurer'];
$card_id = $user_row2['card_id'];
$bbvn = $user_row2['unumber']; 
$password = $user_row2['password']; 
$dateofbirth = $user_row2['dob'];

$check_module = mysqli_query($link, "SELECT * FROM my_permission WHERE (urole = '$irole' OR urole = '$vrole')") or die ("Error" . mysqli_error($link));
$get_module = mysqli_fetch_array($check_module);

//BRANCHES RECORDS
$list_branches = $get_module['list_branches'];
$delete_branches = $get_module['delete_branches'];
$update_branches = $get_module['update_branches'];

//COOPERATIVES RECORDS
$update_cooperatives = $get_module['update_cooperatives'];
$view_coop_members = $get_module['view_coop_members'];
$update_coop_members = $get_module['update_coop_members'];
$delete_coop_members = $get_module['delete_coop_members'];
$print_coop_members = $get_module['print_coop_members'];
$delete_cooperatives = $get_module['delete_cooperatives'];
$send_sms_coop = $get_module['send_sms_coop'];
$send_email_coop = $get_module['send_email_coop'];

//INSTITUTION RECORDS
$view_institution_members = $get_module['view_institution_members'];
$update_members_info = $get_module['update_members_info'];
$update_institution_info = $get_module['update_institution_info'];
$delete_members = $get_module['delete_members'];
$delete_institution = $get_module['delete_institution'];
$send_sms = $get_module['send_sms'];
$send_email = $get_module['send_email'];

//AGENT RECORDS
$update_agent = $get_module['update_agent'];
$delete_agent = $get_module['delete_agent'];
$send_sms_agent = $get_module['send_sms_agent'];
$send_email_agent = $get_module['send_email_agent'];

//MERCHANT RECORDS
$add_mloan_product = $get_module['add_mloan_product'];
$disbursed_mloan = $get_module['disbursed_mloan'];
$merchant_loan_repayment = $get_module['merchant_loan_repayment'];
$update_mloan_records = $get_module['update_mloan_records'];
$aprove_disapprove_mloan = $get_module['aprove_disapprove_mloan'];
$update_msavings_plan = $get_module['update_msavings_plan'];
$delete_msavings_plan = $get_module['delete_msavings_plan'];
$delete_mloan_records = $get_module['delete_mloan_records'];
$disable_msavings_subscription = $get_module['disable_msavings_subscription'];
$enable_msavings_subscription = $get_module['enable_msavings_subscription'];
$update_merchant_info = $get_module['update_merchant_info'];
$delete_merchant = $get_module['delete_merchant'];
$send_sms_merchant = $get_module['send_sms_merchant'];
$send_email_merchant = $get_module['send_email_merchant'];
$add_group = $get_module['add_group'];
$delete_group = $get_module['delete_group'];
$update_group = $get_module['update_group'];
$add_account_type = $get_module['add_account_type'];
$delete_account_type = $get_module['delete_account_type'];
$update_account_type = $get_module['update_account_type'];

//INVESTMENT RECORDS
$disapprove_withdrawal_request = $get_module['disapprove_withdrawal_request'];
$approve_withdrawal_request = $get_module['approve_withdrawal_request'];
$enable_savings_subscription = $get_module['enable_savings_subscription'];
$disable_savings_subscription = $get_module['disable_savings_subscription'];

//LOAN RECORDS
$add_loan = $get_module['add_individual_loan'];
$add_group_loan = $get_module['add_group_loan'];
$add_purchase_loan = $get_module['add_purchase_loan'];
$upload_bulk_loan = $get_module['upload_bulk_loan'];
$view_due_loans = $get_module['view_due_loans'];
$approve_disapprove_loans = $get_module['approve_disapprove_loans'];
$update_loan_records = $get_module['update_loan_records'];
$delete_loans = $get_module['delete_loans'];
$print_loan_records = $get_module['print_loan_records'];
$export_loan_records = $get_module['export_loan_records'];
$approved_loan = $get_module['approved_loan'];
$disapproved_loan = $get_module['disapproved_loan'];
$internal_review = $get_module['internal_review'];
$disbursed_loan = $get_module['disbursed_loan'];
$view_all_loans = $get_module['view_all_loans'];
$individual_loan_records = $get_module['individual_loan_records'];
$branch_loan_records = $get_module['branch_loan_records'];
$cancel_debit_instruction = $get_module['cancel_debit_instruction'];
$send_debit_instruction = $get_module['send_debit_instruction'];
$individual_due_loans = $get_module['individual_due_loans'];
$branch_due_loans = $get_module['branch_due_loans'];
$paid_loan = $get_module['paid_loan'];
$active_loan = $get_module['active_loan'];
$delete_loan_product_info = $get_module['delete_loan_product_info'];
$accept_loan_guarantor = $get_module['accept_loan_guarantor'];
$reject_loan_guarantor = $get_module['reject_loan_guarantor'];
$approve_loan_document = $get_module['approve_loan_document'];
$decline_loan_document = $get_module['decline_loan_document'];


//SUBSCRIBER RECORDS
$update_saas_sub_plan = $get_module['update_saas_sub_plan'];
$delete_saas_sub_plan = $get_module['delete_saas_sub_plan'];
$setup_saas_sub_plan = $get_module['setup_saas_sub_plan'];
$delete_saas_sub_transaction = $get_module['delete_saas_sub_transaction'];

//MY WALLET ACTION MODULES / RECORDS FOR INSTITUTION
$transfer_fund = $get_module['transfer_fund'];
$add_transfer_recipient = $get_module['add_transfer_recipient'];
$view_all_recipient = $get_module['view_all_recipient'];
$view_transfer_balance = $get_module['view_transfer_balance'];
$recharge_airtime_or_data = $get_module['recharge_airtime_or_data'];
$add_fund = $get_module['add_fund'];
//new wallet features
$create_wallet = $get_module['create_wallet'];
$create_individual_wallet_only = $get_module['create_individual_wallet_only'];
$create_agent_wallet_only = $get_module['create_agent_wallet_only'];
$create_corporate_wallet_only = $get_module['create_corporate_wallet_only'];
$list_wallet = $get_module['list_wallet'];
$close_wallet = $get_module['close_wallet'];
$withdraw_from_wallet = $get_module['withdraw_from_wallet'];
$fund_wallet = $get_module['fund_wallet']; 
$individual_wallet = $get_module['individual_wallet'];
$branch_wallet = $get_module['branch_wallet'];
$view_wallet_statement = $get_module['view_wallet_statement'];
$view_wallet_verification = $get_module['view_wallet_verification'];
$upgrade_wallet = $get_module['upgrade_wallet'];
$activate_wallet = $get_module['activate_wallet'];
$wallet_loan_history = $get_module['wallet_loan_history'];
$book_wallet_loan = $get_module['book_wallet_loan'];
$wallet_loan_repayment = $get_module['wallet_loan_repayment'];
$individual_wallet_loan_repayment = $get_module['individual_wallet_loan_repayment'];
$branch_wallet_loan_repayment = $get_module['branch_wallet_loan_repayment'];
$wallet_due_payment = $get_module['wallet_due_payment'];
$individual_wallet_due_payment = $get_module['individual_wallet_due_payment'];
$branch_wallet_due_payment = $get_module['branch_wallet_due_payment'];
$review_wallet_loan = $get_module['review_wallet_loan'];
$reject_wallet_loan_guarantor = $get_module['reject_wallet_loan_guarantor'];
$accept_wallet_loan_guarantor = $get_module['accept_wallet_loan_guarantor'];
$review_kyc = $get_module['review_kyc'];


//CUSTOMER RECORDS
$add_customer = $get_module['add_customer'];
$link_account = $get_module['link_account'];
$update_customers_info = $get_module['update_customers_info'];
$view_account_info = $get_module['view_account_info'];
$view_loan_history = $get_module['view_loan_history'];
$delete_customer = $get_module['delete_customer'];
$print_customer_records = $get_module['print_customer_records'];
$export_customer_records = $get_module['export_customer_records'];
$send_sms_customer = $get_module['send_sms_customer'];
$send_email_customer = $get_module['send_email_customer'];
$initiate_cardholder_registration = $get_module['initiate_cardholder_registration'];
$view_all_customers = $get_module['view_all_customers'];
$individual_customer_records = $get_module['individual_customer_records'];
$branch_customer_records = $get_module['branch_customer_records'];
$close_customer_account = $get_module['close_customer_account'];
$assign_customer_to_branch = $get_module['assign_customer_to_branch'];
$assign_customer_to_staff = $get_module['assign_customer_to_staff'];
$enable_overdraft = $get_module['enable_overdraft'];
$enable_otp = $get_module['enable_otp'];
$activate_auto_charges = $get_module['activate_auto_charges'];
$activate_auto_disburse = $get_module['activate_auto_disburse'];
$disable_auto_charges = $get_module['disable_auto_charges'];
$disable_auto_disburse = $get_module['disable_auto_disburse'];


//REFERRAL RECORDS
$set_referral_plan = $get_module['set_referral_plan'];
$update_compensation_plan = $get_module['update_compensation_plan'];
$delete_compensation_plan = $get_module['delete_compensation_plan'];

//EMPLOYEE RECORDS
$add_employee = $get_module['add_employee'];
$update_employee_records = $get_module['update_employee_records'];
$list_employee = $get_module['list_employee'];
$print_employee_records = $get_module['print_employee_records'];
$export_employee_records = $get_module['export_employee_records'];
$send_sms_employee = $get_module['send_sms_employee'];
$list_branch_employee = $get_module['list_branch_employee'];
$block_employee = $get_module['block_employee'];
$unblock_employee = $get_module['unblock_employee'];
//PERMISSION LEVEL


//NOTICE BOARD 
$add_notice = $get_module['add_notice'];
$view_notice_info = $get_module['view_notice_info'];
$delete_notice = $get_module['delete_notice'];

//HELPDESK DETAILS
$view_all_tickets = $get_module['view_all_tickets'];
$close_tickets = $get_module['close_tickets'];
$create_tickets = $get_module['create_tickets'];
$delete_tickets = $get_module['delete_tickets'];

//MISSED PAYMENT RECORDS
$claim_payment = $get_module['claim_payment'];

//REPAYMENT RECORDS
$remit_cash_payment = $get_module['remit_cash_payment'];
$remit_bulk_cash_payment = $get_module['remit_bulk_cash_payment'];
$list_all_repayment = $get_module['list_all_repayment'];
$list_individual_loan_repayment = $get_module['list_individual_loan_repayment'];
$list_branch_loan_repayment = $get_module['list_branch_loan_repayment'];
$delete_loan_repayment_records = $get_module['delete_loan_repayment_records'];
$print_loan_repayment_records = $get_module['print_loan_repayment_records'];
$export_loan_repayment_to_excel = $get_module['export_loan_repayment_to_excel'];

//EXPENSES RECORDS
$add_expenses = $get_module['add_expenses'];
$view_expense_type = $get_module['view_expense_type'];
$update_expense_type = $get_module['update_expense_type'];
$add_expense_type = $get_module['add_expense_type'];
$delete_expense_type = $get_module['delete_expense_type'];
$update_expenses = $get_module['update_expenses'];
$view_attachment = $get['view_attachment'];
$delete_expenses_records = $get['delete_expenses_records'];
$export_expenses_to_excel = $get['export_expenses_to_excel'];

//PAYROLL DATA
$add_payroll = $get_module['add_payroll'];
$generate_payslip = $get_module['generate_payslip'];
$update_payroll = $get_module['update_payroll'];
$delete_payroll = $get_module['delete_payroll'];

//SAVINGS RECORDS
$deposit_money = $get_module['deposit_money'];
$withdraw_money = $get_module['withdraw_money'];
$bulk_savings_upload = $get_module['bulk_savings_upload'];
$delete_transaction = $get_module['delete_transaction'];
$print_transaction = $get_module['print_transaction'];
$export_transaction = $get_module['export_transaction'];
$view_all_transaction = $get_module['view_all_transaction'];
$individual_transaction_records = $get_module['individual_transaction_records'];
$branch_transaction_records = $get_module['branch_transaction_records'];


//INCOME
$add_income = $get_module['add_income'];
$view_income = $get_module['view_income'];
$edit_income = $get_module['edit_income'];
$delete_income = $get_module['delete_income'];
$add_view_incometype = $get_module['add_view_incometype'];
$edit_incometype = $get_module['edit_incometype'];
$delete_incometype = $get_module['delete_incometype'];


//TILL ACCOUNT
$settle_fund = $get_module['settle_fund'];
$allocate_fund = $get_module['allocate_fund'];
$delete_till_account = $get_module['delete_till_account'];
$view_teller_transaction = $get_module['view_teller_transaction'];
$fund_allocation_history = $get_module['fund_allocation_history'];
$till_virtual_account_details = $get_module['till_virtual_account_details'];
$fund_settlement = $get_module['fund_settlement'];


//CHARGES MANAGEMENT
$charges_tab = $get_module['charges_tab'];
$add_charges = $get_module['add_charges'];
$view_all_charges = $get_module['view_all_charges'];
$edit_charges = $get_module['edit_charges'];
$delete_charges = $get_module['delete_charges'];
$enable_charges = $get_module['enable_charges'];
$direct_charges = $get_module['direct_charges'];


//Card Manager Settings
$terminate_card = $get_module['terminate_card'];
$fund_card = $get_module['fund_card'];
$fetch_card_transaction = $get_module['fetch_card_transaction'];
$withdraw_fromcard = $get_module['withdraw_fromcard'];
$reset_cardpassword = $get_module['reset_cardpassword'];
$link_verve_card = $get_module['link_verve_card'];


//Campaign Manager Settings
$add_category = $get_module['add_category'];
$delete_category = $get_module['delete_category'];
$edit_category = $get_module['edit_category'];
$delete_region = $get_module['delete_region'];
$refund_lender = $get_module['refund_lender'];
$add_region = $get_module['add_region'];
$add_team = $get_module['add_team'];
$list_teams = $get_module['list_teams'];

$active_campaign = $get_module['active_campaign'];
$campaign_approved = $get_module['campaign_approved'];
$campaign_disapproved = $get_module['campaign_disapproved'];
$pending_campaign = $get_module['pending_campaign'];


//SMS MARKETING
$sms_marketing = $get_module['sms_marketing'];
$sms_report = $get_module['sms_report'];


//VENDOR MANAGER
$edit_vendor = $get_module['edit_vendor'];
$add_vendor = $get_module['add_vendor'];
$delete_vendor = $get_module['delete_vendor'];


//DEPARTMENT MANAGEMENT
$edit_department = $get_module['edit_department'];
$delete_department = $get_module['delete_department'];


//INVESTMENT MANAGER
$access_investment_tab = $get_check['access_investment_tab'];
$all_investment_subscription = $get_check['all_investment_subscription'];
$individual_investment_subscription = $get_check['individual_investment_subscription'];
$all_investment_transaction = $get_check['all_investment_transaction'];
$individual_investment_transaction = $get_check['individual_investment_transaction'];


//PRODUCT MANAGER
$product_manager_tab = $get_module['product_manager_tab'];
$list_all_product = $get_module['list_all_product'];
$all_product_subscription = $get_module['all_product_subscription'];
$individual_product_subscription = $get_module['individual_product_subscription'];
$all_product_transaction = $get_module['all_product_transaction'];
$individual_product_transaction = $get_module['individual_product_transaction'];
$product_withdrawal_request = $get_module['product_withdrawal_request'];


//VENDOR MANAGER
$access_vendor_tab = $get_check['access_vendor_tab'];
$add_vendor = $get_check['add_vendor'];
$list_vendor  = $get_check['list_vendor'];


//POS Manager
$pos_tab = $get_module['pos_tab'];
$terminal_report = $get_module['terminal_report'];
$esusuPAY_cardless_withdrawal = $get_module['esusuPAY_cardless_withdrawal'];
$pending_terminal_settlement = $get_module['pending_terminal_settlement'];
$approve_terminal_settlement = $get_module['approve_terminal_settlement'];
$decline_terminal_settlement = $get_module['decline_terminal_settlement'];


//Bank Account Manager
$account_manager_tab = $get_module['account_manager_tab'];
$open_bank_account = $get_module['open_bank_account'];
$manage_bank_account = $get_module['manage_bank_account'];
$manage_individual_bank_account = $get_module['manage_individual_bank_account'];
$manage_branch_bank_account = $get_module['manage_branch_bank_account'];
$view_bank_internal_account_statement = $get_module['view_bank_internal_account_statement'];
$transfer_fund_from_bank_account = $get_module['transfer_fund_from_bank_account'];
$view_bank_account_info = $get_module['view_bank_account_info'];


//BVN Verification Manager
$bvn_tab = $get_module['bvn_tab'];
$verify_bvn = $get_module['verify_bvn'];
$view_all_bvn_verification = $get_module['view_all_bvn_verification'];
$view_individual_bvn_verification = $get_module['view_individual_bvn_verification'];
$view_branch_bvn_verification = $get_module['view_branch_bvn_verification'];

//Verification Manager
$all_verification_history = $get_module['all_verification_history'];
$individual_verification_history = $get_module['individual_verification_history'];
$branch_verification_history = $get_module['branch_verification_history'];

//Enrolment Manager
$add_enrollee = $get_module['add_enrollee'];
$add_bulk_enrollees = $get_module['add_bulk_enrollees'];
$all_enrollee_list = $get_module['all_enrollee_list'];
$individual_enrollee_list = $get_module['individual_enrollee_list'];
$branch_enrollee_list = $get_module['branch_enrollee_list'];
$view_enrollee_details = $get_module['view_enrollee_details'];
$update_enrollee = $get_module['update_enrollee'];
$delete_enrollee = $get_module['delete_enrollee'];
$view_all_enrolment_log = $get_module['view_all_enrolment_log'];
$view_individual_enrolment_log = $get_module['view_individual_enrolment_log'];
$view_branch_enrolment_log = $get_module['view_branch_enrolment_log'];

//Report
$id_verification_report = $get_module['id_verification_report'];
$enrollees_report = $get_module['enrollees_report'];
?>