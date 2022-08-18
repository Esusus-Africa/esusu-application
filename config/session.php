<?php 
error_reporting(0); 
include ('connect.php');
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
$session_id=$_SESSION['tid'];
$rsession_id=$_SESSION['tid'];
$aggr_id = $_SESSION['tid'];
$aggrid = $_SESSION['tid'];
$astaff_id=$_SESSION['aun'];
$rstaff_id=$_SESSION['aun'];
$acnt_id=$_SESSION['acctno'];
$coopid=$_SESSION['coopid'];
$instid=$_SESSION['instid'];
$istaff_id=$_SESSION['istaff'];

function isMobileDevice(){
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function panNumberMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 6) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -4);
}

function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);
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

//GLOBAL SETTINGS
$mysystem_config = mysqli_query($link, "SELECT * FROM systemset");
$fetchsys_config = mysqli_fetch_array($mysystem_config);
$stampduty = $fetchsys_config['stampduty_fee'];
$wemaVAPrefix = $fetchsys_config['wbPrefix'];
$sterlinkInputKey = $fetchsys_config['sterlinkInputKey'];
$sterlingIv = $fetchsys_config['sterlingIv'];
$myitoday_record = date("Y-m-d");
//$myitoday_record1 = date("Y-m-d").' 24'.':00'.':00';

//FETCH STANDARD FEES
$bvn_fee = $fetchsys_config['bvn_fee'];
$selfServiceTransAuth = $fetchsys_config['selfServiceTransAuth'];
$verveCardLinkingFee = $fetchsys_config['verveCardLinkingFee'];
$verveCardPrefundAmt = $fetchsys_config['verveCardPrefundAmt'];

//WALLET.AFRICA CREDENTIALS
$walletafrica_skey = $fetchsys_config['walletafrica_skey'];
$walletafrica_pkey = $fetchsys_config['walletafrica_pkey'];
$walletafrica_status = $fetchsys_config['walletafrica_vastatus'];
$walletafrica_airtimestatus = $fetchsys_config['walletafrica_airtimestatus'];

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
$transferToCardCharges = $fetchsys_config['transferToCardCharges'];

//CGATE CREDENTIALS
$cgate_username = $fetchsys_config['cgate_username'];
$cgate_password = $fetchsys_config['cgate_password'];
$cgate_mid = $fetchsys_config['cgate_mid'];
$cgate_charges = $fetchsys_config['cgate_charges'];

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

$search_payvice = mysqli_query($link, "SELECT * FROM payvice_accesspoint");
$payvice_wallet = mysqli_fetch_array($search_payvice);

//GET ALL WALLET BALANCE
$search_iNGN = mysqli_query($link, "SELECT SUM(wallet_balance) FROM institution_data");
$get_i = mysqli_fetch_array($search_iNGN);
$i_wb = $get_i['SUM(wallet_balance)'];

$search_cNGN = mysqli_query($link, "SELECT SUM(wallet_balance) FROM cooperatives");
$get_c = mysqli_fetch_array($search_cNGN);
$c_wb = $get_c['SUM(wallet_balance)'];

$search_boNGN = mysqli_query($link, "SELECT SUM(wallet_balance) FROM borrowers");
$get_bo = mysqli_fetch_array($search_boNGN);
$bo_wb = $get_bo['SUM(wallet_balance)'];

//GET NUMBER OF PENDING TERMINAL SETTLEMENT
$isearch_pendingTerminalSettlement = mysqli_query($link, "SELECT * FROM terminal_reg WHERE terminal_status = 'Assigned' AND (pending_balance > '0.0' OR pending_balance > 0)");
$myinum_pendingTerminalSettlement = mysqli_num_rows($isearch_pendingTerminalSettlement);

$search_pendinfrequest = mysqli_query($link, "SELECT * FROM wallet_history WHERE status = 'tbPending'");
$preq_num = mysqli_num_rows($search_pendinfrequest);

$lend_setup = mysqli_query($link, "SELECT * FROM lend_setup");
$fetch_lend_setup = mysqli_fetch_array($lend_setup);

$user_query = mysqli_query($link, "SELECT * FROM user WHERE id = '$session_id'")or die(mysqli_error());
$user_row = mysqli_fetch_array($user_query);
$name = $user_row['name'].' '.$user_row['lname'].' '.$user_row['mname'];
$email = $user_row['email'];
$phone = $user_row['phone'];
$branchid = $user_row['created_by'];
$csbranchid = $user_row['branchid'];
$urole = $user_row['role'];
$control_pin = $user_row['tpin'];
$uid = $user_row['id'];
$aggwallet_balance = $user_row['transfer_balance'];
$virtual_acctno = $user_row['virtual_acctno'];
$bvn = $user_row['addr2'];
$dob = $user_row['dob'];
$acctOfficer = $user_row['acctOfficer'];
$gactive_status = $user_row['active_status'];
//$lr = '\''.implode('","',$urole).'\'';
//$result_string =  str_replace(",","','",$lr);

//AGGREGATOR
$aggr_query = mysqli_query($link, "SELECT * FROM aggregator WHERE aggr_id = '$aggr_id'")or die(mysqli_error());
$aggr_row = mysqli_fetch_array($aggr_query);
$myaggname = $aggr_row['lname'].' '.$aggr_row['fname'].' '.$aggr_row['mname'];
$aggemail = $aggr_row['email'];
$myaggphone = $aggr_row['phone'];
$aggusername = $aggr_row['username'];
$agguid = $aggr_row['id'];
$aggcurrency = $aggr_row['currency'];
$aggmerchant = $aggr_row['merchantid'];

$agg_indivWallet = mysqli_query($link, "SELECT SUM(debit) FROM wallet_history WHERE date_time LIKE '$myitoday_record%' AND initiator = '$aggr_id' AND (paymenttype = 'Airtime - WEB' OR paymenttype = 'Databundle - WEB')") or die(mysqli_error());
$astaff_indivWalletRow = mysqli_fetch_array($agg_indivWallet);
$amyDailyAirtimeData = $astaff_indivWalletRow['SUM(debit)'];

$agg_indivWallet2 = mysqli_query($link, "SELECT SUM(debit) FROM wallet_history WHERE date_time LIKE '$myitoday_record%' AND initiator = '$aggr_id' AND paymenttype = 'BANK_TRANSFER'") or die(mysqli_error());
$astaff_indivWalletRow2 = mysqli_fetch_array($agg_indivWallet2);
$amyDailyTransferLimit = $astaff_indivWalletRow2['SUM(debit)'];

//AGGREGATOR TRANSFER LIMIT PER TRANSACTION
$aggr_indivtransfer = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$aggr_id'") or die(mysqli_error());
$aggruser_indivRow = mysqli_fetch_array($aggr_indivtransfer);
$aggrtransferLimitPerDay = $aggruser_indivRow['transferLimitPerDay'];
$aggrtransferLimitPerTrans = $aggruser_indivRow['transferLimitPerTrans'];
$aglobalDailyAirtime_DataLimit = $aggruser_indivRow['airtime_dataLimitPerDay'];
$aglobal_airtimeLimitPerTrans = $aggruser_indivRow['airtime_dataLimitPerTrans'];

$verify_aggrcurrency = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$aggmerchant'");
$fetch_aggrcurrency = mysqli_fetch_object($verify_aggrcurrency);
$aggrinst_name = $fetch_aggrcurrency->cname;
$aggrva_provider = $fetch_aggrcurrency->va_provider;
$aggrdefaultAcct = $fetch_aggrcurrency->defaultAcct;
$nip_route =  $fetch_aggrcurrency->nip_route;
$bvn_route =  $fetch_aggrcurrency->bvn_route;
$aggrairtime_route = $fetch_aggrcurrency->airtime;
$aggrdatabundle_route = $fetch_aggrcurrency->databundle;
$aggrbillpayment = $fetch_aggrcurrency->billpayment;
$aggrcopyright = $fetch_aggrcurrency->copyright;
$aggrbvn_verification = $fetch_aggrcurrency->enable_bvn;
$aggraccount_verification = $fetch_aggrcurrency->enable_acct_verification;
$aggallow_login_otp = $fetch_aggrcurrency->allow_login_otp;

$aggrsearch_emailConfig = mysqli_query($link, "SELECT * FROM email_config WHERE companyid = '$bbranchid'");
$aggrfetch_emailConfig = mysqli_fetch_array($aggrsearch_emailConfig);
$aggremailConfigStatus = $aggrfetch_emailConfig['status']; //Activated OR NotActivated

$isearch_maintenance_model = mysqli_query($link, "SELECT * FROM maintenance_history WHERE company_id = '$aggmerchant' AND status = 'Activated'");
$ifetch_maintenance_model = mysqli_fetch_array($isearch_maintenance_model);
$model = $ifetch_maintenance_model['billing_type'];
$billing_type = $model;
$ibvn_fee = ($ifetch_maintenance_model['bvn_fee'] === "") ? $bvn_fee : $ifetch_maintenance_model['bvn_fee'];
$iussd_session_cost = $ifetch_maintenance_model['ussd_session_cost'];
$bank_transferCharges = $ifetch_maintenance_model['bank_transfer_charges'];
$bank_transferCommission = $ifetch_maintenance_model['bank_transfer_commission'];
$transferToCardCharges12 = ($ifetch_maintenance_model['transferToCardCharges'] === "") ? $transferToCardCharges1 : $ifetch_maintenance_model['transferToCardCharges'];
$card_transferCommission = $ifetch_maintenance_model['transferToCardCommission'];
$verveCardLinkingFee2 = $ifetch_maintenance_model['verveCardLinkingFee'];
$airtimeDataCommission = ($ifetch_maintenance_model['airtimeData_comm'] == "") ? $fetchsys_config['airtimedata_commission'] : $ifetch_maintenance_model['airtimeData_comm'];
$billPaymentCommission = ($ifetch_maintenance_model['billpay_comm'] == "") ? $fetchsys_config['bp_commission'] : $ifetch_maintenance_model['billpay_comm'];

$verifySMS_Provider = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$aggmerchant' AND status = 'Activated'") or die (mysqli_error($link));
$verifySMS_Provider1 = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'") or die (mysqli_error($link));
$getSMS_ProviderNum = mysqli_num_rows($verifySMS_Provider);
$fetchSMS_Provider = ($getSMS_ProviderNum == 0) ? mysqli_fetch_array($verifySMS_Provider1) : mysqli_fetch_array($verifySMS_Provider);
$ozeki_password = $fetchSMS_Provider['password'];
$ozeki_url = $fetchSMS_Provider['api'];

//REVENUE
$revenue_query = mysqli_query($link, "SELECT * FROM revenue_data WHERE ogsaa_id = '$rsession_id'") or die(mysqli_error($link));
$revenue_row = mysqli_fetch_array($revenue_query);
$rwallet_balance = $revenue_row['wallet_balance'];
$myrname = $revenue_row['fname'];
$rbname = $revenue_row['bname'];
$remail = $revenue_row['email'];
$myrphone = $revenue_row['phone'];

$rsearch_maintenance_model = mysqli_query($link, "SELECT * FROM maintenance_history WHERE company_id = '$rsession_id' AND status = 'Activated'");
$rfetch_maintenance_model = mysqli_fetch_array($rsearch_maintenance_model);

$revenue_staff = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$rsession_id' AND username = '$rstaff_id' AND bprefix = 'REV'") or die(mysqli_error());
$rstaff_row = mysqli_fetch_array($revenue_staff);

$rname = $rstaff_row['name'];
$remail = $rstaff_row['email'];
$rrole = $rstaff_row['role'];
$revenue_id = $rstaff_row['created_by'];
$rsbranchid = $rstaff_row['branchid'];
$rphone = $rstaff_row['phone'];
$rusername = $rstaff_row['username'];
$ruid = $rstaff_row['id'];
$ruide = $rstaff_row['userid'];
$myrepin = $rstaff_row['tpin'];
//$bname = $agent_row['bname'];
$verify_rcurrency = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$revenue_id'");
$fetch_rcurrency = mysqli_fetch_object($verify_rcurrency);
$rcurrency = $fetch_rcurrency->currency;

//COOPERATIVE INFORMATION START FROM HERE
$search_coope = mysqli_query($link, "SELECT * FROM cooperatives WHERE coopid = '$coopid'");
$fetch_coope = mysqli_fetch_array($search_coope);
$coop_name = $fetch_coope['coopname'];
$coop_phone = $fetch_coope['official_phone'];
$coop_email = $fetch_coope['official_email'];
$cwallet_balance = $fetch_coope['wallet_balance'];
$creferral_bonus = $fetch_coope['referral_bonus'];
$mycepin = $fetch_coope['tpin'];

$verify_ccurrency = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$coopid'");
$fetch_ccurrency = mysqli_fetch_object($verify_ccurrency);
$ccurrency = $fetch_ccurrency->currency;
//COOPERATIVE INFORMATION ENDS HERE


$coopmem_query = mysqli_query($link, "SELECT * FROM coop_members WHERE id = '$session_id'")or die(mysqli_error());
$coopmem_row = mysqli_fetch_array($coopmem_query);
$memberid = $coopmem_row['memberid'];
$coopmem_name = $coopmem_row['fullname'];
$coopmem_email = $coopmem_row['email'];
$coopmem_phone = $coopmem_row['phone'];

$coopmem_sub = mysqli_query($link, "SELECT * from saas_subscription_trans WHERE coopid_instid = '$coopid' AND usage_status = 'Active'") or die(mysqli_error());
$coopmem_subrow = mysqli_fetch_array($coopmem_sub);
$sub_token = $coopmem_subrow['sub_token'];
$cstaff_limit = $coopmem_subrow['staff_limit'];
$csub_plan_code = $coopmem_subrow['plan_code'];


$branch_query = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$branchid'")or die(mysqli_error($link));
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

$user_query2 = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$session_id'") or die(mysqli_error());
$user_row2 = mysqli_fetch_array($user_query2);
$bname = $user_row2['fname'].' '.$user_row2['lname'].' '.$user_row2['mname'];
$email2 = $user_row2['email'];
$bbranchid = $user_row2['branchid'];
$bsbranchid = $user_row2['sbranchid'];
$acctno = $user_row2['account'];
$balance = $user_row2['balance'];
$bwallet_balance = $user_row2['wallet_balance'];
$btargetsavings_bal = $user_row2['target_savings_bal'];
$binvest_bal = $user_row2['investment_bal'];
$phone2 = $user_row2['phone'];
$bvirtual_phone_no = $user_row2['virtual_number'];
$bvirtual_acctno = $user_row2['virtual_acctno'];
$bbcurrency = $user_row2['currency'];
$myfn = $user_row2['fname'];
$myln = $user_row2['lname'];
$mymn = $user_row2['mname'];
$myuepin = $user_row2['tpin'];
$issurer = $user_row2['card_issurer'];
$card_id = $user_row2['card_id'];
$bbvn = $user_row2['unumber']; 
$password = $user_row2['password']; 
$dateofbirth = $user_row2['dob'];
$baddrs = $user_row2['addrs'];
$bcity = $user_row2['city'];
$bstate = $user_row2['state'];
$bzip = $user_row2['zip'];
$bcountry = $user_row2['country'];
$bgender = $user_row2['gender'];
$bAcctOfficer = $user_row2['lofficer'];
$bactive_status = $user_row2['status'];
$bnok = $user_row2['nok'];
$bnok_rela = $user_row2['nok_rela'];
$boccupation = $user_row2['occupation'];
$bemployer = $user_row2['employer'];
$nok_addrs = $user_row2['nok_addrs'];
$lga = $user_row2['lga'];
$moi = $user_row2['moi'];
$mmaidenName = $user_row2['mmaidenName'];
$otherInfo = $user_row2['otherInfo'];

//Customer KYC Verification Doc.
$search_ValidID = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$acctno' AND file_title = 'ValidID'");
$fetch_ValidID = mysqli_num_rows($search_ValidID);

$search_Utility = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$acctno' AND file_title = 'UtilityBills'");
$fetch_Utility = mysqli_num_rows($search_Utility);

$search_Signature = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$acctno' AND file_title = 'Signature'");
$fetch_Signature = mysqli_num_rows($search_Signature);
//End of Customer KYC Verification Doc.

$bor_indivWallet = mysqli_query($link, "SELECT SUM(debit) FROM wallet_history WHERE date_time LIKE '$myitoday_record%' AND initiator = '$acctno' AND (paymenttype = 'Airtime - WEB' OR paymenttype = 'Databundle - WEB')") or die(mysqli_error());
$bstaff_indivWalletRow = mysqli_fetch_array($bor_indivWallet);
$bmyDailyAirtimeData = $bstaff_indivWalletRow['SUM(debit)'];

$bor_indivWallet2 = mysqli_query($link, "SELECT SUM(debit) FROM wallet_history WHERE date_time LIKE '$myitoday_record%' AND initiator = '$acctno' AND paymenttype = 'BANK_TRANSFER'") or die(mysqli_error());
$bstaff_indivWalletRow2 = mysqli_fetch_array($bor_indivWallet2);
$bmyDailyTransferLimit = $bstaff_indivWalletRow2['SUM(debit)'];

//VENDOR USER TRANSFER LIMIT PER TRANSACTION
$borrower_indivtransfer = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$acctno' AND companyid = '$bbranchid'") or die(mysqli_error());
$buser_indivRow = mysqli_fetch_array($borrower_indivtransfer);
$btransferLimitPerDay = $buser_indivRow['transferLimitPerDay'];
$btransferLimitPerTrans = $buser_indivRow['transferLimitPerTrans'];
$bglobalDailyAirtime_DataLimit = $buser_indivRow['airtime_dataLimitPerDay'];
$bglobal_airtimeLimitPerTrans = $buser_indivRow['airtime_dataLimitPerTrans'];

$search_subacct = mysqli_query($link, "SELECT * FROM loan_product WHERE merchantid = '$bbranchid'") or die ("Error" . mysqli_error($link));
$fetch_subacct = mysqli_fetch_object($search_subacct);
$subaccount_code = $fetch_subacct->subaccount_code;

//SEARCH FOR MEMBER SETTINGS
$verify_icurrency = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
$fetch_icurrency = mysqli_fetch_object($verify_icurrency);
$binst_name = $fetch_icurrency->cname;
$bsender_id = $fetch_icurrency->sender_id;
$iwallet_manager = $fetch_icurrency->wallet_manager;
$iloan_manager = $fetch_icurrency->loan_manager;
$iinvestment_manager = $fetch_icurrency->investment_manager;
$iproduct_manager = $fetch_icurrency->product_manager;
$isavings_account = $fetch_icurrency->savings_account;
$otp_option = $fetch_icurrency->otp_option;
$idedicated_ussd_prefix = $fetch_icurrency->dedicated_ussd_prefix;
$mobileapp_link = $fetch_icurrency->mobileapp_link;
$tsavings_subacct = $fetch_icurrency->tsavings_subacct;
$ts_roi_type = $fetch_icurrency->ts_roi_type;
$ts_roi = $fetch_icurrency->ts_roi;
$brave_secret_key = $fetch_icurrency->rave_secret_key;
$brave_public_key = $fetch_icurrency->rave_public_key;
$brave_status = $fetch_icurrency->rave_status;
$itakafulmenu = $fetch_icurrency->takafulmenu;
$ihealthmenu = $fetch_icurrency->healthmenu;
$bgroupcontribution = $fetch_icurrency->groupcontribution;
$inip_route = $fetch_icurrency->nip_route;
$ibvn_route = $fetch_icurrency->bvn_route;
$bdefaultAcct = $fetch_icurrency->defaultAcct;
$iairtime_route = $fetch_icurrency->airtime;
$idatabundle_route = $fetch_icurrency->databundle;
$ibillpayment = $fetch_icurrency->billpayment;
$idonation_manager = $fetch_icurrency->donation_manager;
$icopyright = $fetch_icurrency->copyright; 
$iupfront_payment = $fetch_icurrency->upfront_payment;
$ibvn_verification = $fetch_icurrency->enable_bvn;
$iaccount_verification = $fetch_icurrency->enable_acct_verification;
$iallow_login_otp = $fetch_icurrency->allow_login_otp;

$search_kycRequirement = mysqli_query($link, "SELECT * FROM required_kyc WHERE companyid = '$bbranchid'");
$fetch_kycRequirement = mysqli_fetch_array($search_kycRequirement);
$bget_kycRequirementNum = mysqli_num_rows($search_kycRequirement);

$search_lvWidget = mysqli_query($link, "SELECT * FROM dedicated_livechat_widget WHERE companyid = '$bbranchid'");
$fetch_lvWidget = mysqli_fetch_array($search_lvWidget);
$lvWidgetStatus = $fetch_lvWidget['status']; //Activated OR NotActivated

$search_emailConfig = mysqli_query($link, "SELECT * FROM email_config WHERE companyid = '$bbranchid'");
$fetch_emailConfig = mysqli_fetch_array($search_emailConfig);
$emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated

$bsearch_maintenance_model = mysqli_query($link, "SELECT * FROM maintenance_history WHERE company_id = '$bbranchid' AND status = 'Activated'");
$bfetch_maintenance_model = mysqli_fetch_array($bsearch_maintenance_model);
$bmodel = $ifetch_maintenance_model['billing_type'];
$bbilling_type = $bmodel;
$bairtimeDataCommission = ($bfetch_maintenance_model['airtimeData_comm'] == "") ? $fetchsys_config['airtimedata_commission'] : $bfetch_maintenance_model['airtimeData_comm'];
$bbillPaymentCommission = ($bfetch_maintenance_model['billpay_comm'] == "") ? $fetchsys_config['bp_commission'] : $bfetch_maintenance_model['billpay_comm'];

$institution_query = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'")or die(mysqli_error());
$institution_row = mysqli_fetch_array($institution_query);
$biwallet_balance = $institution_row['wallet_balance'];

$bverifySMS_Provider = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$bbranchid' AND status = 'Activated'") or die (mysqli_error($link));
$bverifySMS_Provider1 = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'") or die (mysqli_error($link));
$bgetSMS_ProviderNum = mysqli_num_rows($bverifySMS_Provider);
$bfetchSMS_Provider = ($bgetSMS_ProviderNum == 0) ? mysqli_fetch_array($bverifySMS_Provider1) : mysqli_fetch_array($bverifySMS_Provider);
$bozeki_password = $bfetchSMS_Provider['password'];
$bozeki_url = $bfetchSMS_Provider['api'];

//SEARCH INSTITUTION ACTIVE SUBSCRIPTION RECORDS
$binstmem_sub = mysqli_query($link, "SELECT * from saas_subscription_trans WHERE coopid_instid = '$bbranchid' AND usage_status = 'Active'") or die(mysqli_error());
$binst_subrow = mysqli_fetch_array($binstmem_sub);
$bisub_token = $binst_subrow['sub_token'];
$bisub_plan_code = $binst_subrow['plan_code'];
$bicustomer_limit = $binst_subrow['customer_limit'];
$bistaff_limit = $binst_subrow['staff_limit'];
$bibranch_limit = $binst_subrow['branch_limit'];
$biamount_paid = $binst_subrow['amount_paid'];

//END OF SEARCH MEMBER SETTINGS

$check_module = mysqli_query($link, "SELECT * FROM my_permission2 WHERE (urole = '$urole' OR urole = '$rrole')") or die ("Error" . mysqli_error($link));
$get_module = mysqli_fetch_array($check_module);

//GENERAL SETTING
$access_settings_tab = $get_module['access_settings_tab'];
$add_system_module = $get_module['add_system_module'];
$setup_backend_module_properties = $get_module['setup_backend_module_properties'];
$setup_client_module_properties = $get_module['setup_client_module_properties'];
$company_setup = $get_module['company_setup'];
$setup_coupon = $get_module['setup_coupon'];
$delete_coupon = $get_module['delete_coupon'];
$sms_gateway_settings = $get_module['sms_gateway_settings'];
$airtime_other_apis = $get_module['airtime_other_apis'];
$restful_api_settings = $get_module['restful_api_settings'];
$backup_database = $get_module['backup_database'];

//Branch Manager
$access_backend_branch_tab = $get_module['access_backend_branch_tab'];
$add_backend_branches = $get_module['add_backend_branches'];
$list_backend_branches = $get_module['list_backend_branches'];
$delete_backend_branches = $get_module['delete_backend_branches'];
$update_backend_branches = $get_module['update_backend_branches'];
$view_branch_operations = $get_module['view_branch_operations'];

//Client Manager
$access_client_tab = $get_module['access_client_tab'];
$add_client = $get_module['add_client'];
$add_aggregator = $get_module['add_aggregator'];
$list_client = $get_module['list_client'];
$delete_client = $get_module['delete_client'];
$update_client_info = $get_module['update_client_info'];
$configure_client = $get_module['configure_client'];
$view_client_branch = $get_module['view_client_branch'];
$delete_client_branches = $get_module['delete_client_branches'];
$update_client_branches = $get_module['update_client_branches'];
$view_client_branch_transaction = $get_module['view_client_branch_transaction'];
$view_all_customers = $get_module['view_all_customers'];
$delete_client_customer = $get_module['delete_client_customer'];
$update_client_customers_info = $get_module['update_client_customers_info'];
$transfer_customers = $get_module['transfer_customers'];
$view_client_customers_info = $get_module['view_client_customers_info'];
$view_client_customer_loan_history = $get_module['view_client_customer_loan_history'];
$list_teller = $get_module['list_teller'];
$delete_till_account = $get_module['delete_till_account'];
$view_teller_transaction = $get_module['view_teller_transaction'];
$teller_fund_allocation_history = $get_module['teller_fund_allocation_history'];
$teller_settlement_history = $get_module['teller_settlement_history'];
$update_till_account = $get_module['update_till_account'];
$list_client_subagent = $get_module['list_client_subagent'];
$update_client_subagent = $get_module['update_client_subagent'];
$delete_client_subagent = $get_module['delete_client_subagent'];
$list_aggregators = $get_module['list_aggregators'];
$view_aggregator_kyc_info = $get_module['view_aggregator_kyc_info'];
$delete_aggregator = $get_module['delete_aggregator'];
$update_aggregator_profile = $get_module['update_aggregator_profile'];
$initiate_cardholder_registration = $get_module['initiate_cardholder_registration'];
$link_vervecard = $get_module['link_vervecard'];
$issue_or_load_card = $get_module['issue_or_load_card'];
$list_cardholder = $get_module['list_cardholder'];
$view_all_transaction = $get_module['view_all_transaction'];
$delete_ledger_transaction = $get_module['delete_ledger_transaction'];
$view_all_charges = $get_module['view_all_charges'];
$delete_client_charges = $get_module['delete_client_charges'];
$edit_client_charges = $get_module['edit_client_charges'];
$view_all_roles = $get_module['view_all_roles'];
$view_client_staff_permission = $get_module['view_client_staff_permission'];
$delete_client_permission = $get_module['delete_client_permission'];
$update_client_staff_permission = $get_module['update_client_staff_permission'];
$delete_client_enrollee = $get_module['delete_client_enrollee'];

//Employee Manager
$access_backend_employee_tab = $get_module['access_backend_employee_tab'];
$add_backend_employee = $get_module['add_backend_employee'];
$list_backend_employee = $get_module['list_backend_employee'];
$update_backend_employee_records = $get_module['update_backend_employee_records'];
$delete_employee = $get_module['delete_employee'];

//Wallet Manager
$backend_access_wallet_tab = $get_module['backend_access_wallet_tab'];
$backend_add_transfer_recipient = $get_module['backend_add_transfer_recipient'];
$backend_view_all_recipient = $get_module['backend_view_all_recipient'];
$backend_view_transfer_balance = $get_module['backend_view_transfer_balance'];
$backend_add_fund = $get_module['backend_add_fund'];
$backend_sms_marketing = $get_module['backend_sms_marketing'];
$backend_sms_report = $get_module['backend_sms_report'];
$backend_transfer_fund = $get_module['backend_transfer_fund'];
$backend_recharge_airtime_or_data = $get_module['backend_recharge_airtime_or_data'];
$backend_list_wallet = $get_module['backend_list_wallet'];
$backend_individual_wallet = $get_module['backend_individual_wallet'];
$backend_branch_wallet = $get_module['backend_branch_wallet'];
$backend_agent_wallet = $get_module['backend_agent_wallet'];
$backend_corporate_wallet = $get_module['backend_corporate_wallet'];
$backend_withdraw_from_wallet = $get_module['backend_withdraw_from_wallet'];
$backend_view_wallet_statement = $get_module['backend_view_wallet_statement'];
$backend_view_wallet_verification = $get_module['backend_view_wallet_verification'];
$backend_upgrade_wallet = $get_module['backend_upgrade_wallet'];
$backend_activate_wallet = $get_module['backend_activate_wallet'];
$backend_close_wallet = $get_module['backend_close_wallet'];
$backend_wallet_history = $get_module['backend_wallet_history'];
$backend_ussd_session = $get_module['backend_ussd_session'];
$wallet_loan_history = $get_module['wallet_loan_history'];

//Investment Manager
$access_investment_tab = $get_module['access_investment_tab'];
$view_investment_subscription = $get_module['view_investment_subscription'];
$view_investment_transaction = $get_module['view_investment_transaction'];
$investment_withdrawal_request = $get_module['investment_withdrawal_request'];
$investment_settlement_to_vendor = $get_module['investment_settlement_to_vendor'];
$disapprove_withdrawal_request = $get_module['disapprove_withdrawal_request'];
$approve_withdrawal_request = $get_module['approve_withdrawal_request'];

//Account Opening Manager
$backend_account_manager_tab = $get_module['backend_account_manager_tab'];
$backend_add_bank_gateway = $get_module['backend_add_bank_gateway'];
$backend_list_bank_gateway = $get_module['backend_list_bank_gateway'];
$backend_edit_bank_gateway = $get_module['backend_edit_bank_gateway'];
$backend_manage_bank_account = $get_module['backend_manage_bank_account'];

//POS Manager
$backend_pos_tab = $get_module['backend_pos_tab'];
$backend_add_ussd_bank = $get_module['backend_add_ussd_bank'];
$backend_add_terminal = $get_module['backend_add_terminal'];
$backend_assign_terminal = $get_module['backend_assign_terminal'];
$backend_all_terminal = $get_module['backend_all_terminal'];
$backend_all_ussd_bank = $get_module['backend_all_ussd_bank'];
$backend_all_terminal_request = $get_module['backend_all_terminal_request'];
$backend_individual_terminal_report = $get_module['backend_individual_terminal_report'];
$backend_all_terminal_report = $get_module['backend_all_terminal_report'];
$backend_terminal_withdrawn_request = $get_module['backend_terminal_withdrawn_request'];
$backend_approve_terminal_request = $get_module['backend_approve_terminal_request'];
$backend_reject_terminal_request = $get_module['backend_reject_terminal_request'];
$backend_delete_terminal = $get_module['backend_delete_terminal'];
$backend_withdraw_terminal = $get_module['backend_withdraw_terminal'];
$backend_pending_terminal_settlement = $get_module['backend_pending_terminal_settlement'];
$approve_terminal_settlement = $get_module['approve_terminal_settlement'];
$decline_terminal_settlement = $get_module['decline_terminal_settlement'];
$pos_wallet_settings = $get_module['pos_wallet_settings'];

//Subscription Manager
$access_saas_subscription_tab = $get_module['access_saas_subscription_tab'];
$setup_saas_sub_plan = $get_module['setup_saas_sub_plan'];
$add_saas_sub_payment = $get_module['add_saas_sub_payment'];
$view_saas_sub_plan = $get_module['view_saas_sub_plan'];
$saas_subscription_history = $get_module['saas_subscription_history'];
$update_saas_sub_plan = $get_module['update_saas_sub_plan'];
$upgrade_saas_subscription = $get_module['upgrade_saas_subscription'];
$renew_saas_subscription = $get_module['renew_saas_subscription'];
$push_saas_notification = $get_module['push_saas_notification'];

//Loan Manager
$access_backend_loan_tab = $get_module['access_backend_loan_tab'];
$view_all_loans = $get_module['view_all_loans'];
$update_loan_records = $get_module['update_loan_records'];
$delete_loan_record = $get_module['delete_loan_record'];
$view_due_loans = $get_module['view_due_loans'];
$send_debit_instruction = $get_module['send_debit_instruction'];
$cancel_debit_instruction = $get_module['cancel_debit_instruction'];
$claim_payment = $get_module['claim_payment'];
$edit_loan_product = $get_module['edit_loan_product'];
$view_all_loan_product = $get_module['view_all_loan_product'];
$delete_loan_product = $get_module['delete_loan_product'];
$list_all_repayment = $get_module['list_all_repayment'];

//Permission Manager
$access_backend_permision_tab = $get_module['access_backend_permision_tab'];
$create_backend_role = $get_module['create_backend_role'];
$set_permission_level = $get_module['set_permission_level'];
$view_all_backend_roles = $get_module['view_all_backend_roles'];
$edit_backend_roles = $get_module['edit_backend_roles'];
$delete_backend_roles = $get_module['delete_backend_roles'];
$delete_backend_permission = $get_module['delete_backend_permission'];
$view_staff_permission = $get_module['view_staff_permission'];
$update_staff_permission = $get_module['update_staff_permission'];
$add_nimc_partners = $get_module['add_nimc_partners'];
$add_bulk_nimc_partners = $get_module['add_bulk_nimc_partners'];
$edit_nimc_partner = $get_module['edit_nimc_partner'];
$delete_nimc_partner = $get_module['delete_nimc_partner'];

//Helpdesk Manager
$access_helpdesk_tab = $get_module['access_helpdesk_tab'];
$view_all_tickets = $get_module['view_all_tickets'];
$close_tickets = $get_module['close_tickets'];
$delete_tickets = $get_module['delete_tickets'];
$close_tickets = $get_module['close_tickets'];
$create_tickets = $get_module['create_tickets'];

//Income & Expenses Manager
$income_and_expenses_tab = $get_module['income_and_expenses_tab'];
$add_income = $get_module['add_income'];
$add_expenses = $get_module['add_expenses'];
$view_income = $get_module['view_income'];
$view_expenses = $get_module['view_expenses'];
$delete_expense = $get_module['delete_expense'];
$edit_expense = $get_module['edit_expense'];
$delete_income = $get_module['delete_income'];
$edit_income = $get_module['edit_income'];
$add_or_view_incometype = $get_module['add_or_view_incometype'];
$delete_incometype = $get_module['delete_incometype'];
$edit_incometype = $get_module['edit_incometype'];
$view_expense_type = $get_module['view_expense_type'];
$add_expense_type = $get_module['add_expense_type'];
$delete_expense_type = $get_module['delete_expense_type'];
$update_expense_type = $get_module['update_expense_type'];

//Payroll Manager
$access_payroll_tab = $get_module['access_payroll_tab'];
$add_payroll = $get_module['add_payroll'];
$view_payroll = $get_module['view_payroll'];
$delete_payroll = $get_module['delete_payroll'];
$update_payroll = $get_module['update_payroll'];
$generate_payslip = $get_module['generate_payslip'];

//Report Manager
$access_report_tab = $get_module['access_report_tab'];
$loan_borrowers_reports = $get_module['loan_borrowers_reports'];
$loan_collection_reports = $get_module['loan_collection_reports'];
$loan_collector_reports = $get_module['loan_collector_reports'];
$ledger_savings_reports = $get_module['ledger_savings_reports'];
$subscription_reports = $get_module['subscription_reports'];
$terminal_aggregate_reports = $get_module['terminal_aggregate_reports'];
$financial_reports = $get_module['financial_reports'];
?>