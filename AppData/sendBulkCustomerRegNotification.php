<?php

/*include("../config/connect.php");
require_once "../config/smsAlertClass.php";

$search_systemset = mysqli_query($link, "SELECT * FROM systemset");
$fetch_systemset = mysqli_fetch_array($search_systemset);
$sms_rate = $fetch_systemset['fax'];
$date_time = date("Y-m-d h:i:s");

$search_pendingsms = mysqli_query($link, "SELECT * FROM borrowers WHERE sendSMS = '0' AND sendEmail = '0' AND acct_status = 'Not-Activated'");
while($fetch_pendingsms = mysqli_fetch_array($search_pendingsms)){

    $id = $fetch_pendingsms['id'];
    $email = $fetch_pendingsms['email'];
    $phone = $fetch_pendingsms['phone'];
    $fname = $fetch_pendingsms['fname'];
    $username = $fetch_pendingsms['username'];
    $password = $fetch_pendingsms['password'];
    $account = $fetch_pendingsms['account'];
    $myAccountNumber = ($fetch_pendingsms['virtual_acctno'] == "") ? "----" : $fetch_pendingsms['virtual_acctno'];
    $institution_id = $fetch_pendingsms['branchid'];
    $iuid = $fetch_pendingsms['lofficer'];
   
    $bverifySMS_Provider = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$institution_id' AND status = 'Activated'") or die (mysqli_error($link));
    $bverifySMS_Provider1 = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'") or die (mysqli_error($link));
    $bgetSMS_ProviderNum = mysqli_num_rows($bverifySMS_Provider);
    $bfetchSMS_Provider = ($bgetSMS_ProviderNum == 0) ? mysqli_fetch_array($bverifySMS_Provider1) : mysqli_fetch_array($bverifySMS_Provider);
    $bozeki_password = $bfetchSMS_Provider['password'];
    $bozeki_url = $bfetchSMS_Provider['api'];

    $isearch_maintenance_model = mysqli_query($link, "SELECT * FROM maintenance_history WHERE company_id = '$institution_id' AND status = 'Activated'");
    $ifetch_maintenance_model = mysqli_fetch_array($isearch_maintenance_model);
    $maintenance_row = mysqli_num_rows($isearch_maintenance_model);
    $cust_charges = ($ifetch_maintenance_model['cust_mfee'] == "") ? $sms_rate : $ifetch_maintenance_model['cust_mfee'];
    $billing_type = $ifetch_maintenance_model['billing_type'];
    $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes";
    $correctdate = $sendSMS->formatDateTime($date_time);
    
    $isearch_emailConfig = mysqli_query($link, "SELECT * FROM email_config WHERE companyid = '$institution_id'");
    $ifetch_emailConfig = mysqli_fetch_array($isearch_emailConfig);
    $iemailConfigStatus = $ifetch_emailConfig['status']; //Activated OR NotActivated

    //END CUSTOMER IDENTITY VERIFICATION 
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    $sys_abb = $fetch_memset['sender_id'];
    $customDomain = ($iemailConfigStatus == "Activated") ? $ifetch_emailConfig['product_url'] : "https://esusu.app/$sys_abb";
    $mobileapp_link = ($fetch_memset['mobileapp_link'] == "") ? "Login at ".$customDomain : "Download mobile app: ".$fetch_memset['mobileapp_link'];

    $transactionPin = substr((uniqid(rand(),1)),3,4);        
    $sms = "$sys_abb>>>Welcome $fname! Your Account ID: $account, Username: $username, Password: $password, Transaction Pin: $transactionPin. $mobileapp_link";

    $max_per_page = 153;
	$sms_length = strlen($sms);
	$calc_length = ceil($sms_length / $max_per_page);
	$tsms_charges = $cust_charges * $calc_length;
	$refid = uniqid("EA-smsCharges-").time();

    $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$company_id'");
    $fetch_inst = mysqli_fetch_array($search_inst);
    $iwallet_balance = $fetch_inst['wallet_balance'];
    $mybalance = $iwallet_balance - $tsms_charges;
    $sendsms = ($phone == "") ? "0" : "1";
    $sendemail = ($email == "") ? "0" : "1";

    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$url = substr($customDomain, 0, -10)."/?acn=".$account;
	$id = rand(1000000,10000000);
	$shorturl = base_convert($id,20,36);
	
	($allow_auth == "Yes") ? $insert = mysqli_query($link, "INSERT INTO activate_member VALUES(null,'$url','$shorturl','No','$account')") or die ("Error4: " . mysqli_error($link)) : "";
	
	$shortenedurl = substr($customDomain, 0, -10).'/?activation_key=' . $shorturl;
	$shortenedurl1 = substr($customDomain, 0, -10).'/?deactivation_key=' . $shorturl;

	//SMS NOTIFICATION
	(($billing_type == "PAYGException" || $phone == "") ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($bozeki_password, $bozeki_url, $sys_abb, $phone, $sms, $institution_id, $refid, $tsms_charges, $iuid, $mybalance, $debitWallet) : ($debitWAllet == "Yes" && $tsms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sys_abb, $phone, $sms, $institution_id, $refid, $tsms_charges, $iuid, $mybalance, $debitWallet) : "")));
	//EMAIL NOTIFICATION
	($email != "") ? $sendSMS->customerRegEmailNotifier($email, $fname, $shortenedurl, $username, $password, $account, $myAccountNumber, $shortenedurl1, $iemailConfigStatus, $ifetch_emailConfig) : "";
	
    mysqli_query($link, "UPDATE borrowers SET sendSMS = '$sendsms', sendEmail = '$sendemail' WHERE account = '$account' AND acct_status = 'Not-Activated'");

}*/

?>