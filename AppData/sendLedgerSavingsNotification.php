<?php
/*include("../config/connect.php");
require_once "../config/smsAlertClass.php";

$search_systemset = mysqli_query($link, "SELECT * FROM systemset");
$fetch_systemset = mysqli_fetch_array($search_systemset);
$sms_rate = $fetch_systemset['fax'];
$sys_email = $fetch_systemset['email'];
$date_time = date("Y-m-d h:i:s");

$checkCustomer = mysqli_query($link, "SELECT * FROM borrowers WHERE sendSMS = '0' AND sendEmail = '0'");
while($fetchCustomer = mysqli_fetch_array($checkCustomer)){

    $myid = $fetchCustomer['id'];
    $fname = $fetchCustomer['fname'];
    $account = $fetchCustomer['account'];
    $username = $fetchCustomer['username'];
    $password = $fetchCustomer['password'];
    $email = $fetchCustomer['email'];
    $phone = $fetchCustomer['phone'];
    $instid = $fetchCustomer['branchid'];
    $iuid = $fetchCustomer['lofficer'];
    $myAccountNumber = ($fetchCustomer['virtual_acctno'] == "") ? "---" : $fetchCustomer['virtual_acctno'];

    $isearch_maintenance_model = mysqli_query($link, "SELECT * FROM maintenance_history WHERE company_id = '$instid' AND status = 'Activated'");
    $ifetch_maintenance_model = mysqli_fetch_array($isearch_maintenance_model);
    $maintenance_row = mysqli_num_rows($isearch_maintenance_model);
    $cust_charges = ($ifetch_maintenance_model['cust_mfee'] == "") ? $sms_rate : $ifetch_maintenance_model['cust_mfee'];
    $billing_type = $ifetch_maintenance_model['billing_type'];

    $checkInst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$instid'");
    $fetchInst = mysqli_fetch_array($checkInst);
    $iwallet_balance = $fetchInst['wallet_balance'];
    $refid = "EA-custReg-".time();

    $verifySMS_Provider = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$instid' AND status = 'Activated'") or die (mysqli_error($link));
    $verifySMS_Provider1 = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'") or die (mysqli_error($link));
    $getSMS_ProviderNum = mysqli_num_rows($verifySMS_Provider);
    $fetchSMS_Provider = ($getSMS_ProviderNum == 0) ? mysqli_fetch_array($verifySMS_Provider1) : mysqli_fetch_array($verifySMS_Provider);
    $ozeki_password = $fetchSMS_Provider['password'];
    $ozeki_url = $fetchSMS_Provider['api'];

    $isearch_emailConfig = mysqli_query($link, "SELECT * FROM email_config WHERE companyid = '$instid'");
    $ifetch_emailConfig = mysqli_fetch_array($isearch_emailConfig);
    $iemailConfigStatus = $ifetch_emailConfig['status']; //Activated OR NotActivated

    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instid'");
	$fetch_memset = mysqli_fetch_array($search_memset);
	$sysabb = $fetch_memset['sender_id'];
	$mobileapp_link = ($fetch_memset['mobileapp_link'] == "") ? "Login at https://esusu.app/$sysabb" : "Download mobile app: ".$fetch_memset['mobileapp_link'];

	$transactionPin = substr((uniqid(rand(),1)),3,4);
	$sms = "$sysabb>>>Welcome $fname! Your Account ID: $account, Username: $username, Password: $password, Transaction Pin: $transactionPin. $mobileapp_link";
	
	$max_per_page = 153;
	$sms_length = strlen($sms);
	$calc_length = ceil($sms_length / $max_per_page);
    $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes";
	$sms_charges = $calc_length * $cust_charges;
	$mywallet_balance = $iwallet_balance - $sms_charges;
	$refid = "EA-smsCharges-".time();

	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$url = $protocol . $_SERVER['HTTP_HOST']."/?acn=".$account;
	$id = rand(1000000,10000000);
	$shorturl = base_convert($id,20,36);

	$shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/?activation_key=' . $shorturl;
	$shortenedurl1 = $protocol . $_SERVER['HTTP_HOST'] . '/?deactivation_key=' . $shorturl;
    $sendsms = ($phone != "") ? "1" : "0";
    $sendemail = ($email != "") ? "1" : "0";

    //SMS NOTIFICATION
    (($billing_type == "PAYGException" || $phone == "") ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $instid, $refid, $sms_charges, $iuid, $mywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $instid, $refid, $sms_charges, $iuid, $mywallet_balance, $debitWallet) : "")));
    //EMAIL NOTIFICATION
    ($email == "") ? "" : $sendSMS->customerRegEmailNotifier($email, $fname, $shortenedurl, $username, $password, $account, $myAccountNumber, $shortenedurl1, $iemailConfigStatus, $ifetch_emailConfig);
    
    mysqli_query($link, "INSERT INTO activate_member VALUES(null,'$url','$shorturl','No','$account')") or die ("Error4: " . mysqli_error($link));
    mysqli_query($link,"UPDATE borrowers SET sendSMS = '$sendsms' AND sendEmail = '$sendemail' WHERE id ='$myid'");

}*/
?>