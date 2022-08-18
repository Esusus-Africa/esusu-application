<?php

error_reporting(0); 
include_once 'autoload.php';

use Class\SessionMgr\SessionManager as GlobalSession;
use Class\BranchMgr\BranchManager as Branch;
use Class\Permission\PermissionManager as Permission;
use Class\Customer\CustomerManager as Customer;
use Class\SavingsMgr\SavingsManager as Savings;
use Class\LoanMgr\LoanManager as Loan;

$db = new Database();
$app = new App($db);
$session = new GlobalSession();

$mycookies = $_COOKIE['PHPSESSID'];
$myuserid = $session->get('tid');
$cookiesNum = $app->fetchWithThreeParam('session_tracker', 'userid', $myuserid, 'browserSession', $mycookies, 'loginStatus', 'On');

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$ourCustomUrl = $protocol . $_SERVER['HTTP_HOST'];

if((trim($myuserid) === '') || ($cookiesNum === 0)){
    
    $date_time = date("Y-m-d h:i:s");
    $app->updateWithTwoParam('session_tracker', 'loginStatus', 'Off', 'LastVisitDateTime', $date_time, 'browserSession', $mycookies);

?>
    <meta http-equiv="refresh" content="1;url=<?php echo $ourCustomUrl; ?>/timeout/index">
<?php
}
elseif(!$db->internetConnectionStatus($_SERVER['HTTP_HOST'])){

    echo "You seem to be offline. Please check your internet connection...";

}

$session_id = $myuserid;
$instid = $session->get('instid');
$istaff_id = $session->get('istaff');
$vend_id = $session->get('vendorid');
$vstaff_id = $session->get('vstaff');
$myitoday_record = date("Y-m-d");

//GLOBAL SETTINGS
$fetchsys_config = $db->fetchSystemSet();
$stampduty = $fetchsys_config['stampduty_fee'];

//FETCH STANDARD FEES
$bvn_fee = $fetchsys_config['bvn_fee'];
$selfServiceTransAuth = $fetchsys_config['selfServiceTransAuth'];
$verveCardLinkingFee = $fetchsys_config['verveCardLinkingFee'];
$verveCardPrefundAmt = $fetchsys_config['verveCardPrefundAmt'];

//WALLET.AFRICA CREDENTIALS
$walletafrica_skey = getenv('WALLETAFRICA_SKEY');
$walletafrica_pkey = getenv('WALLETAFRICA_PKEY');
$walletafrica_status = $fetchsys_config['walletafrica_vastatus'];
$walletafrica_airtimestatus = $fetchsys_config['walletafrica_airtimestatus'];

//MONIFY CREDENTIALS
$mo_api_key = getenv('MONNIFY_API_KEY');
$mo_secret_key = getenv('MONNIFY_SECRET_KEY');
$mo_contract_code = getenv('MONNIFY_CONTRACT_KEY');
$mo_status = $fetchsys_config['mo_status'];
$mo_virtualacct_status = $fetchsys_config['mo_virtualacct_status'];

//RAVE CREDENTIALS
$rave_secret_key = getenv('RAVE_SECRET_KEY');
$rave_public_key = getenv('RAVE_PUBLIC_KEY');

//BANCORE CREDENTIALS
$bancore_merchantID = getenv('BANCORE_MERCHANT_ACCTID');
$bancore_mprivate_key = getenv('BANCORE_MERCHANT_PKEY');

//PROVIDUS CREDENTIALS
$providusUName = getenv('PROVIDUS_UNAME');
$providusPass = getenv('PROVIDUS_PASS');
$providusClientId = getenv('PROVIDUS_CLIENTID');
$providusClientSecret = getenv('PROVIDUS_CLIENT_SECRET');

//VERVE CREDENTIALS
$verveAppId = getenv('VERVEAPPID');
$verveAppKey = getenv('VERVEAPPKEY');
$verveSettlementAcctNo = $fetchsys_config['verveSettlementAcctNo'];
$verveSettlementAcctName = $fetchsys_config['verveSettlementAcctName'];
$verveSettlementBankCode = $fetchsys_config['verveSettlementBankCode'];
$transferToCardCharges = $fetchsys_config['transferToCardCharges'];

//CGATE CREDENTIALS
$cgate_username = getenv('CGATE_USERNAME');
$cgate_password = getenv('CGATE_PASSWORD');
$cgate_mid = getenv('CGATE_MID');
$cgate_charges = $fetchsys_config['cgate_charges'];

//GTBANK CREDENTIALS
$gtbaccesscode = getenv('GTB_ACCESSCODE');
$gtbusername = getenv('GTB_USERNAME');
$gtbpassword = getenv('GTB_PASSWORD');
$gtbsourceAcctNo = $fetchsys_config['gtbsourceAcctNo'];

//RUBIES CREDENTIALS
$rubbiesSecKey = getenv('RUBBIES_SECKEY');
$rubbiesPubKey = getenv('RUBBIES_PUBKEY');

//PAYANT<>STERLING CREDENTIALS
$payantEmail = getenv('PAYANT_EMAIL');
$payantPwd = getenv('PAYANT_PASSWORD');
$payantOrgId = getenv('PAYANT_ORGID');

//PRIME AIRTIME CREDENTIALS
$accessToken = $fetchsys_config['primeairtime_token'];

//GET ALL WALLET BALANCE
$get_i = $app->allTotalWalletBalance('wallet_balance', 'institution_data');
$i_wb = $get_i['SUM(wallet_balance)'];

$get_bo = $app->allTotalWalletBalance('wallet_balance', 'borrowers');
$bo_wb = $get_bo['SUM(wallet_balance)'];

//GET NUMBER OF PENDING TERMINAL SETTLEMENT
$myinum_pendingTerminalSettlement = $app->fetchPendingTerminalSettlement('Assigned');
$preq_num = $app->fetchWithOneParam('wallet_history', 'status', 'tbPending');

//GET USER RECORDS
$user_row = $app->fetchWithOneParam('user', 'id', $session_id);
$iname = $user_row['name'].' '.$user_row['lname'].' '.$user_row['mname'];
$iuid = $user_row['id'];
$iuserid = $user_row['userid'];
$iusername = $user_row['username'];
$irole = $user_row['role'];
$myiemail_addrs = $user_row['email'];
$myiphone = $user_row['phone'];
$institution_id = $user_row['created_by'];
$isbranchid = $user_row['branchid'];
$myiepin = $user_row['tpin'];
$idept = $user_row['dept'];
$isubagent_wbalance = $user_row['wallet_balance'];
$itransfer_balance = $user_row['transfer_balance'];
$ivirtual_phone_no = $user_row['virtual_number'];
$ivirtual_acctno = $user_row['virtual_acctno'];
$ibvn = $user_row['addr2'];
$igender = $user_row['gender'];
$idob = $user_row['dob'];
$icity = $user_row['city'];
$istate = $user_row['state'];
$iissurer = $user_row['card_issurer'];
$icard_id = $user_row['card_id'];
$iAcctOfficer = $user_row['acctOfficer'];
$businessName = $user_row['businessName'];
$allow_auth = $user_row['allow_auth'];
$active_status = $user_row['status'];

//GET TILL ACCOUNT INFO
$verify_till = $app->fetchWithTwoParam('till_account', 'cashier', $iuid, 'status', 'Active');

//GET AGGREGATOR RECORDS
$aggr_row = $app->fetchWithOneParam('aggregator', 'aggr_id', $session_id);
$myaggname = $aggr_row['lname'].' '.$aggr_row['fname'].' '.$aggr_row['mname'];
$aggemail = $aggr_row['email'];
$myaggphone = $aggr_row['phone'];
$aggusername = $aggr_row['username'];
$agguid = $aggr_row['id'];
$aggcurrency = $aggr_row['currency'];
$aggmerchant = $aggr_row['merchantid'];

//USER TRANSFER / AIRTIMEDATA LIMIT
$astaff_indivWalletRow = $app->sumOfAirtimeDataPerDay($myitoday_record, $session_id);
$amyDailyAirtimeData = $astaff_indivWalletRow['SUM(debit)'];

$astaff_indivWalletRow2 = $app->sumOfBankTransferPerDay($myitoday_record, $session_id);
$amyDailyTransferLimit = $astaff_indivWalletRow2['SUM(debit)'];

//GET INSTITTUTION MEMBER SETTINGS
$fetch_instmemset = $app->fetchWithOneParam('member_settings', 'companyid', $instid);
$icurrency = $fetch_instmemset['currency'];
$isenderid = $fetch_instmemset['sender_id'];
$isubagent_wallet = $fetch_instmemset['subagent_wallet'];
$iremitaMerchantId = $fetch_instmemset['remitaMerchantId'];
$iremitaApiKey = $fetch_instmemset['remitaApiKey'];
$iremitaServiceId = $fetch_instmemset['remitaServiceId'];
$iremitaApiToken = $fetch_instmemset['remitaApiToken'];
$idedicated_ussd_prefix = $fetch_instmemset['dedicated_ussd_prefix'];
$iussd_prefixStatus = $fetch_instmemset['ussd_status'];
//TURN ON AND OFF MODULES
$ibranch_manager = $fetch_instmemset['branch_manager'];
$idept_settings = $fetch_instmemset['dept_settings'];
$ipermission_manager = $fetch_instmemset['permission_manager'];
$isubagent_manager = $fetch_instmemset['subagent_manager'];
$istaff_manager = $fetch_instmemset['staff_manager'];
$icustomer_manager = $fetch_instmemset['customer_manager'];
$iwallet_manager = $fetch_instmemset['wallet_manager'];
$ivendor_manager = $fetch_instmemset['vendor_manager'];
$ibvn_manager = $fetch_instmemset['bvn_manager'];
$icard_issuance_manager = $fetch_instmemset['card_issuance_manager'];
$iloan_manager = $fetch_instmemset['loan_manager'];
$iinvestment_manager = $fetch_instmemset['investment_manager'];
$iteller_manager = $fetch_instmemset['teller_manager'];
$icharges_manager = $fetch_instmemset['charges_manager'];
$isavings_account = $fetch_instmemset['savings_account'];
$ireports_module = $fetch_instmemset['reports_module'];
$ipayroll_module = $fetch_instmemset['payroll_module'];
$iincome_module = $fetch_instmemset['income_module'];
$iexpenses_module = $fetch_instmemset['expenses_module'];
$igeneral_settings = $fetch_instmemset['general_settings'];
$iproduct_manager = $fetch_instmemset['product_manager'];
$iotp_option = $fetch_instmemset['otp_option'];
$ieditoption = $fetch_instmemset['editoption'];
$igroupcontribution = $fetch_instmemset['groupcontribution'];
$ipos_manager = $fetch_instmemset['pos_manager'];
$inip_route = $fetch_instmemset['nip_route'];
$iinvite_manager = $fetch_instmemset['invite_manager'];
$ihalalpay_module = $fetch_instmemset['halalpay_module'];
$iwallet_creation = $fetch_instmemset['wallet_creation'];
$ibvn_route = $fetch_instmemset['bvn_route'];
$iaccount_manager = $fetch_instmemset['account_manager'];
$isms_checker = $fetch_instmemset['sms_checker'];
$iva_provider = $fetch_instmemset['va_provider'];
$idefaultAcct = $fetch_instmemset['defaultAcct'];
$ipool_account = $fetch_instmemset['pool_account'];
$iairtime_route = $fetch_instmemset['airtime'];
$idatabundle_route = $fetch_instmemset['databundle'];
$ibillpayment = $fetch_instmemset['billpayment'];
$ipending_manager = $fetch_instmemset['pending_manager'];
$iva_fortill = $fetch_instmemset['va_fortill'];
$icardless_wroute = $fetch_instmemset['cardless_wroute'];
$idedicated_sms_gateway = $fetch_instmemset['dedicated_sms_gateway'];
$idedicated_ledgerAcctNo_prefix = $fetch_instmemset['dedicated_ledgerAcctNo_prefix'];
$icopyright = $fetch_instmemset['copyright'];
$ibvn_verification = $fetch_instmemset['enable_bvn'];
$iaccount_verification = $fetch_instmemset['enable_acct_verification'];
$iallow_login_otp = $fetch_instmemset['allow_login_otp'];

//INSTITUTION BASIC INF0RMATION
$fetch_inst = $app->fetchWithOneParam('institution_data', 'institution_id', $instid);
$inst_name = $fetch_inst['institution_name'];
$inst_email = $fetch_inst['official_email'];
$inst_phone = $fetch_inst['official_phone'];
$iwallet_balance = $fetch_inst['wallet_balance'];
$iaccount_type = $fetch_inst['account_type'];

//SEARCH INSTITUTION ACTIVE SUBSCRIPTION RECORDS
$inst_subrow = $app->fetchWithTwoParam('saas_subscription_trans', 'coopid_instid', $instid, 'usage_status', 'Active');
$isub_token = $inst_subrow['sub_token'];
$isub_plan_code = $inst_subrow['plan_code'];
$icustomer_limit = $inst_subrow['customer_limit'];
$istaff_limit = $inst_subrow['staff_limit'];
$ibranch_limit = $inst_subrow['branch_limit'];
$iamount_paid = $inst_subrow['amount_paid'];
$aggrId = $inst_subrow['aggr_id'];

//CUSTOM EMAIL CONFIGURATION SETTINGS
$ifetch_emailConfig = $app->fetchWithOneParam('email_config', 'companyid', $instid);
$iemailConfigStatus = $ifetch_emailConfig['status']; //Activated OR NotActivated

//GET NUMBER OF INSTITUTION INVESTMENT NOTIFICATION STATUS ON PENDING
$myinum_pendinginvestment = $app->fetchWithThreeParam('investment_notification', 'merchantid', $instid, 'vendorid', $vend_id, 'status', 'Pending');

//GET MAINTENANCE HISTORY SETTINGS FOR INSTITUTION
$ifetch_maintenance_model = $app->fetchWithTwoParam('maintenance_history', 'company_id', $instid, 'status', 'Activated');
$model = $ifetch_maintenance_model['billing_type'];
$ibvn_fee = ($ifetch_maintenance_model['bvn_fee'] === "") ? $bvn_fee : $ifetch_maintenance_model['bvn_fee'];
$iussd_session_cost = $ifetch_maintenance_model['ussd_session_cost'];
$bank_transferCharges = $ifetch_maintenance_model['bank_transfer_charges'];
$bank_transferCommission = $ifetch_maintenance_model['bank_transfer_commission'];
$transferToCardCharges12 = ($ifetch_maintenance_model['transferToCardCharges'] === "") ? $transferToCardCharges : $ifetch_maintenance_model['transferToCardCharges'];
$card_transferCommission = $ifetch_maintenance_model['transferToCardCommission'];
$verveCardLinkingFee2 = $ifetch_maintenance_model['verveCardLinkingFee'];
$billing_type = $model;
$ismscharges = $ifetch_maintenance_model['ismscharges'];
$airtimeDataCommission = ($ifetch_maintenance_model['airtimeData_comm'] == "") ? $fetchsys_config['airtimedata_commission'] : $ifetch_maintenance_model['airtimeData_comm'];
$billPaymentCommission = ($ifetch_maintenance_model['billpay_comm'] == "") ? $fetchsys_config['bp_commission'] : $ifetch_maintenance_model['billpay_comm'];

//GET SMS SETTINGS
$getSMS_ProviderNum = $app->fetchWithTwoParam('sms', 'smsuser', $instid, 'status', 'Activated');
$getSMS_ProviderNum1 = $app->fetchWithTwoParam('sms', 'smsuser', '', 'status', 'Activated');
$ozeki_password = ($getSMS_ProviderNum === 0) ? $getSMS_ProviderNum1['password'] : $getSMS_ProviderNum['password'];
$ozeki_url = ($getSMS_ProviderNum === 0) ? $getSMS_ProviderNum1['api'] : $getSMS_ProviderNum['api'];

//SEARCH INTERNAL REVIEW NO.
$i_IR_num = $app->fetchWithThreeParam('loan_info', 'branchid', $instid, 'dept', $idept, 'status', 'Internal-Review');

//SEARCH ALL DIRECT DEBIT ACTIVATED
$i_AllDD_num = $app->fetchWithTwoParam('loan_info', 'branchid', $instid, 'mandate_status', 'Activated');

//SEARCH BRANCH DIRECT DEBIT ACTIVATED
$i_INDIV_num = $app->fetchWithThreeParam('loan_info', 'branchid', $instid, 'sbranchid', $isbranchid, 'mandate_status', 'Activated');

//SEARCH INDIVIDUAL DIRECT DEBIT ACTIVATED
$i_BRDD_num = $app->fetchWithThreeParam2('loan_info', 'branchid', $instid, 'mandate_status', 'Activated', 'agent', $iuid, $iname);
$detectDD = ($isbranchid === "") ? $i_INDIV_num : $i_BRDD_num;
$iactivatedDD = ($irole != "agent_manager" || $irole != "institution_super_admin" || $irole != "merchant_super_admin") ? $i_AllDD_num : $detectDD;

//GET CUSTOMER INFORMATION
$user_row2 = $app->fetchWithOneParam('borrowers', 'id', $session_id);
$bname = $user_row2['fname'].' '.$user_row2['lname'].' '.$user_row2['mname'];
$email2 = $user_row2['email'];
$bbranchid = $user_row2['branchid'];
$bsbranchid = $user_row2['sbranchid'];
$acctno = $user_row2['account'];
$balance = $user_row2['balance'];
$bwallet_balance = $user_row2['wallet_balance'];
$bloan_balance = $user_row2['loan_balance'];
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

//CUSTOMER KYC VERIFICATION DOC.
$fetch_ValidID = $app->fetchWithTwoParam('attachment', 'borrowerid', $acctno, 'file_title', 'ValidID');
$fetch_Utility = $app->fetchWithTwoParam('attachment', 'borrowerid', $acctno, 'file_title', 'UtilityBills');
$fetch_Signature = $app->fetchWithTwoParam('attachment', 'borrowerid', $acctno, 'file_title', 'Signature');

//GET USER VIRTUAL ACCOUNT TRANSACTION LIMIT
$vaUser_indivRow = $app->fetchWithOneParam2('virtual_account', 'userid', $session_id, $acctno);
$vatransferLimitPerDay = $vaUser_indivRow['transferLimitPerDay'];
$vatransferLimitPerTrans = $vaUser_indivRow['transferLimitPerTrans'];
$valobalDailyAirtime_DataLimit = $vaUser_indivRow['airtime_dataLimitPerDay'];
$valobal_airtimeLimitPerTrans = $vaUser_indivRow['airtime_dataLimitPerTrans'];

//GET REQUIRED KYC SETTING
$fetch_kycRequirement = $app->fetchWithOneParam('required_kyc', 'companyid', $instid);
$bget_kycRequirementNum = $fetch_kycRequirement;

//GET IF DEDICATED LIVE CHAT IS CONFIGURED FOR INSTITUTION
$fetch_lvWidget = $app->fetchWithOneParam('dedicated_livechat_widget', 'companyid', $instid);
$lvWidgetStatus = $fetch_lvWidget['status']; //Activated OR NotActivated

//GET VENDOR INFORMATION
$vendor_row = $app->fetchWithOneParam('vendor_reg', 'companyid', $vend_id);
$vc_name = $vendor_row['cname'];
$vo_email = $vendor_row['cemail'];
$vo_phone = $vendor_row['cphone'];
$vuname = $vendor_row['cusername'];
$v_ctype = $vendor_row['ctype'];
$vendorid = $vendor_row['companyid'];
$vwallet_balance = $vendor_row['wallet_balance'];
$vinvestment_balance = $vendor_row['investment_balance'];
$vcurrency = $vendor_row['currency'];

//GET ESUSU SUPERADMIN PERMISSION SETTING
$get_module = $app->fetchWithOneParam('my_permission2', 'urole', $irole);

//GET INSTITUTION PERMISSION SETTINGS
$get_module2 = $app->fetchWithOneParam('my_permission', 'urole', $irole);

?>