<?php

include("connect.php");

$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
$fetch_gateway = mysqli_fetch_object($search_gateway);
$gateway_uname = $fetch_gateway->username;
$gateway_pass = $fetch_gateway->password;
$gateway_api = $fetch_gateway->api;

function sendSms($sender, $phone, $msg, $debug=false)
{
    global $gateway_uname,$gateway_pass,$gateway_api;

    $url = 'username='.$gateway_uname;
    $url.= '&password='.$gateway_pass;
    $url.= '&sender='.urlencode($sender);
    $url.= '&recipient='.urlencode($phone);
    $url.= '&message='.urlencode($msg);

    $urltouse =  $gateway_api.$url;
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

$search_systemset = mysqli_query($link, "SELECT * FROM systemset");
$fetch_systemset = mysqli_fetch_array($search_systemset);
//$sms_rate = $fetch_systemset['fax'];
$date_time = date("Y-m-d H:i:s");

$search_pendingsms = mysqli_query($link, "SELECT * FROM sms_logs1 WHERE sms_status = 'Pending' AND recipient != '' ORDER BY id DESC");
while($fetch_pendingsms = mysqli_fetch_array($search_pendingsms)){

    $id = $fetch_pendingsms['id'];
    $company_id = $fetch_pendingsms['company_id'];
    $c_type = $fetch_pendingsms['c_type'];
    $phone = $fetch_pendingsms['recipient'];
    $sms = $fetch_pendingsms['sms_content'];
    $sys_abb = $fetch_pendingsms['sender_id'];
    $sms_price = $fetch_pendingsms['price'];
    
    $max_per_page = 153;
	$sms_length = strlen($sms);
	$calc_length = ceil($sms_length / $max_per_page);
	
	$tsms_charges = $sms_price * $calc_length;
	
	$refid = "EA-smsCharges-".time();

    $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$company_id'");
    $fetch_inst = mysqli_fetch_array($search_inst);
    $iwallet_balance = $fetch_inst['wallet_balance'];

    $search_coop = mysqli_query($link, "SELECT * FROM cooperatives WHERE coopid = '$company_id'");
    $fetch_coop = mysqli_fetch_array($search_coop);
    $cwallet_balance = $fetch_coop['wallet_balance'];
    
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

    if($c_type == "" && $fetch_duplicate == 1){

        ($fetch_duplicate == 1) ? exit() : $debug = true;
        ($fetch_duplicate == 1) ? exit() : sendSms($sys_abb,$phone,$sms,$debug);
        ($fetch_duplicate == 1) ? exit() : mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");

    }elseif($c_type == "institution" && $iwallet_balance >= $sms_rate && $fetch_duplicate == 1){
        
        ($fetch_duplicate == 1) ? exit() : $debug = true;
        ($fetch_duplicate == 1) ? exit() : sendSms($sys_abb,$phone,$sms,$debug);
        ($fetch_duplicate == 1) ? exit() : $mybalance = $iwallet_balance - $tsms_charges;
        ($fetch_duplicate == 1) ? exit() : mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");
        ($fetch_duplicate == 1) ? exit() : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mybalance' WHERE institution_id = '$company_id'");
        ($fetch_duplicate == 1) ? exit() : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$company_id','$refid','$phone','','$tsms_charges','Debit','NGN','Charges','SMS Content: $sms','successful','$date_time','','$mybalance','')");

    }elseif($c_type == "cooperative" && $cwallet_balance >= $sms_rate){

        ($fetch_duplicate == 1) ? exit() : $debug = true;
        ($fetch_duplicate == 1) ? exit() : sendSms($sys_abb,$phone,$sms,$debug);
        ($fetch_duplicate == 1) ? exit() : $mybalance = $cwallet_balance - $tsms_charges;
        ($fetch_duplicate == 1) ? exit() : mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");
        ($fetch_duplicate == 1) ? exit() : mysqli_query($link, "UPDATE cooperatives SET wallet_balance = '$mybalance' WHERE coopid = '$company_id'");
        ($fetch_duplicate == 1) ? exit() : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$company_id','$refid','$phone','','$tsms_charges','Debit','NGN','Charges','SMS Content: $sms','successful','$date_time','','$mybalance','')");

    }elseif($c_type == "aggregator" && $aggwallet_balance >= $sms_rate){

        ($fetch_duplicate == 1) ? exit() : $debug = true;
        ($fetch_duplicate == 1) ? exit() : sendSms($sys_abb,$phone,$sms,$debug);
        ($fetch_duplicate == 1) ? exit() : $mybalance = $aggwallet_balance - $tsms_charges;
        ($fetch_duplicate == 1) ? exit() : mysqli_query($link, "UPDATE aggregator SET wallet_balance = '$mybalance' WHERE aggr_id = '$company_id'");
        ($fetch_duplicate == 1) ? exit() : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$company_id','$refid','$phone','','$tsms_charges','Debit','NGN','Charges','SMS Content: $sms','successful','$date_time','','$mybalance','')");
        ($fetch_duplicate == 1) ? exit() : mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");

    }elseif($c_type == "revenue_agency" && $rwallet_balance >= $sms_rate){

        ($fetch_duplicate == 1) ? exit() : $debug = true;
        ($fetch_duplicate == 1) ? exit() : sendSms($sys_abb,$phone,$sms,$debug);
        ($fetch_duplicate == 1) ? exit() : $mybalance = $rwallet_balance - $tsms_charges;
        ($fetch_duplicate == 1) ? exit() : mysqli_query($link, "UPDATE revenue_data SET wallet_balance = '$mybalance' WHERE ogsaa_id = '$company_id'");
        ($fetch_duplicate == 1) ? exit() : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$company_id','$refid','$phone','','$tsms_charges','','NGN','Charges','SMS Content: $sms','successful','$date_time','','$mybalance','')");
        ($fetch_duplicate == 1) ? exit() : mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");

    }elseif($c_type == "user" && $bwallet_balance >= $sms_rate && $fetch_duplicate == 1){

        ($fetch_duplicate == 1) ? exit() : $debug = true;
        ($fetch_duplicate == 1) ? exit() : sendSms($sys_abb,$phone,$sms,$debug);
        ($fetch_duplicate == 1) ? exit() : $mybalance = $bwallet_balance - $tsms_charges;
        ($fetch_duplicate == 1) ? exit() : mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$mybalance' WHERE account = '$company_id'");
        ($fetch_duplicate == 1) ? exit() : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$refid','$phone','','$tsms_charges','','NGN','Charges','SMS Content: $sms','successful','$date_time','$company_id','$mybalance','')");
        ($fetch_duplicate == 1) ? exit() : mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");

    }

}

?>