<?php

/*include("../config/connect.php");
require_once "../config/smsAlertClass.php";

$date_time = date("Y-m-d H:i:s");

$search_pendingsms = mysqli_query($link, "SELECT * FROM sms_logs1 WHERE sms_status = 'Pending' AND recipient != ''");
while($fetch_pendingsms = mysqli_fetch_array($search_pendingsms)){

    $id = $fetch_pendingsms['id'];
    $company_id = $fetch_pendingsms['company_id'];
    $c_type = $fetch_pendingsms['c_type'];
    $phone = $fetch_pendingsms['recipient'];
    $sms = $fetch_pendingsms['sms_content'];
    $sys_abb = $fetch_pendingsms['sender_id'];
    $sms_price = $fetch_pendingsms['price'];
    $iuid = "";

    $bverifySMS_Provider = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$company_id' AND status = 'Activated'") or die (mysqli_error($link));
    $bverifySMS_Provider1 = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'") or die (mysqli_error($link));
    $bgetSMS_ProviderNum = mysqli_num_rows($bverifySMS_Provider);
    $bfetchSMS_Provider = ($bgetSMS_ProviderNum == 0) ? mysqli_fetch_array($bverifySMS_Provider1) : mysqli_fetch_array($bverifySMS_Provider);
    $bozeki_password = $bfetchSMS_Provider['password'];
    $bozeki_url = $bfetchSMS_Provider['api'];

    $isearch_maintenance_model = mysqli_query($link, "SELECT * FROM maintenance_history WHERE company_id = '$company_id' AND status = 'Activated'");
    $ifetch_maintenance_model = mysqli_fetch_array($isearch_maintenance_model);
    $maintenance_row = mysqli_num_rows($isearch_maintenance_model);
    $billing_type = $ifetch_maintenance_model['billing_type'];
    $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes";
    
    $max_per_page = 153;
	$sms_length = strlen($sms);
	$calc_length = ceil($sms_length / $max_per_page);
	
	$tsms_charges = $sms_price * $calc_length;
	
	$refid = uniqid("EA-smsCharges-").time();

    $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$company_id'");
    $fetch_inst = mysqli_fetch_array($search_inst);
    $iwallet_balance = $fetch_inst['wallet_balance'];
    
    $search_aggr = mysqli_query($link, "SELECT * FROM aggregator WHERE aggr_id = '$company_id'");
    $fetch_aggr = mysqli_fetch_array($search_aggr);
    $aggwallet_balance = $fetch_aggr['wallet_balance'];
    
    $search_rev = mysqli_query($link, "SELECT * FROM revenue_data WHERE ogsaa_id = '$company_id'");
    $fetch_rev = mysqli_fetch_array($search_rev);
    $rwallet_balance = $fetch_rev['wallet_balance'];

    $search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$company_id'");
    $fetch_user = mysqli_fetch_array($search_user);
    $bwallet_balance = $fetch_user['wallet_balance'];
    $bbranchid = $fetch_user['branchid'];
    
    $protect_duplicate = mysqli_query($link, "SELECT * FROM sms_logs1 WHERE id = '$id' AND sms_status = 'Sent'");
    $fetch_duplicate = mysqli_num_rows($protect_duplicate);

    if($c_type == "" && $fetch_duplicate == 0){

        $sendSMS->smsWithNoCharges($sys_abb, $phone, $sms, $refid, $iuid);
        mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");

    }
    if($c_type == "institution" && $iwallet_balance >= $tsms_charges && $fetch_duplicate == 0){

        $mybalance = $iwallet_balance - $tsms_charges;
        mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mybalance' WHERE institution_id = '$company_id'");
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$company_id','$refid','$phone','','$tsms_charges','Debit','NGN','Charges','SMS Content: $sms','successful','$date_time','','$mybalance','')");
        $sendSMS->batchSmsWithNoCharges($bozeki_password, $bozeki_url, $sys_abb, $phone, $sms, $refid, $iuid, $debitWallet);
        mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");

    }
    if($c_type == "aggregator" && $aggwallet_balance >= $tsms_charges && $fetch_duplicate == 0){

        $mybalance = $aggwallet_balance - $tsms_charges;
        mysqli_query($link, "UPDATE aggregator SET wallet_balance = '$mybalance' WHERE aggr_id = '$company_id'");
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$refid','$phone','','$tsms_charges','Debit','NGN','Charges','SMS Content: $sms','successful','$date_time','$company_id','$mybalance','')");
        $sendSMS->smsWithNoCharges($sys_abb, $phone, $sms, $refid, $iuid);
        mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");

    }
    if($c_type == "revenue_agency" && $rwallet_balance >= $tsms_charges && $fetch_duplicate == 0){

        $debug = true;
        sendSms($sys_abb,$phone,$sms,$debug);
        $mybalance = $rwallet_balance - $tsms_charges;
        mysqli_query($link, "UPDATE revenue_data SET wallet_balance = '$mybalance' WHERE ogsaa_id = '$company_id'");
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$company_id','$refid','$phone','','$tsms_charges','Debit','NGN','Charges','SMS Content: $sms','successful','$date_time','','$rwallet_balance','')");
        mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");

    }
    if($c_type == "user" && $bwallet_balance >= $tsms_charges && $fetch_duplicate == 0){

        $mybalance = $bwallet_balance - $tsms_charges;
        mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$mybalance' WHERE account = '$company_id'");
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$refid','$phone','','$tsms_charges','Debit','NGN','Charges','SMS Content: $sms','successful','$date_time','$company_id','$mybalance','')");
        $sendSMS->smsWithNoCharges($sys_abb, $phone, $sms, $refid, $iuid);
        mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");

    }

}*/

?>