<?php

include('config/connect.php');

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
$sms_rate = $fetch_systemset['fax'];

$search_pendingsms = mysqli_query($link, "SELECT * FROM sms_logs1 WHERE sms_status = 'Pending'");
while($fetch_pendingsms = mysqli_fetch_array($search_pendingsms)){

    $id = $fetch_pendingsms['id'];
    $company_id = $fetch_pendingsms['company_id'];
    $c_type = $fetch_pendingsms['c_type'];
    $phone = $fetch_pendingsms['recipient'];
    $sms = $fetch_pendingsms['sms_content'];
    $sys_abb = $fetch_pendingsms['sender_id'];
    
    $max_per_page = 159;
	$sms_length = strlen($sms);
	$calc_length = ceil($sms_length / $max_per_page);
	
	$tsms_charges = $sms_rate * $calc_length;
	
	$refid = "EA-smsCharges-".rand(1000000,9999999);

    $search_agt = mysqli_query($link, "SELECT * FROM agent_data WHERE agentid = '$company_id'");
    $fetch_agt = mysqli_fetch_array($search_agt);
    $awallet_balance = $fetch_agt['wallet_balance'];

    $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$company_id'");
    $fetch_inst = mysqli_fetch_array($search_inst);
    $iwallet_balance = $fetch_inst['wallet_balance'];

    $search_mtch = mysqli_query($link, "SELECT * FROM merchant_reg WHERE merchantID = '$company_id'");
    $fetch_mtch = mysqli_fetch_array($search_mtch);
    $mwallet_balance = $fetch_mtch['wallet_balance'];

    $search_coop = mysqli_query($link, "SELECT * FROM cooperatives WHERE coopid = '$company_id'");
    $fetch_coop = mysqli_fetch_array($search_coop);
    $cwallet_balance = $fetch_coop['wallet_balance'];

    $search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$company_id'");
    $fetch_user = mysqli_fetch_array($search_user);
    $bwallet_balance = $fetch_user['wallet_balance'];

    if($c_type == ""){

        $debug = true;
        sendSms($sys_abb,$phone,$sms,$debug);
        mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");

    }elseif($c_type == "agent" && $awallet_balance >= $sms_rate){

        $debug = true;
        sendSms($sys_abb,$phone,$sms,$debug);
        $mybalance = $awallet_balance - $tsms_charges;
        mysqli_query($link, "UPDATE agent_data SET wallet_balance = '$mybalance' WHERE agentid = '$company_id'");
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$company_id','$refid','$phone','$tsms_charges','NGN','system','SMS Content: $sms','successful',NOW())");
        mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");

    }elseif($c_type == "institution" && $iwallet_balance >= $sms_rate){
        
        $debug = true;
        sendSms($sys_abb,$phone,$sms,$debug);
        $mybalance = $iwallet_balance - $tsms_charges;
        mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mybalance' WHERE institution_id = '$company_id'");
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$company_id','$refid','$phone','$tsms_charges','NGN','system','SMS Content: $sms','successful',NOW())");
        mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");

    }elseif($c_type == "merchant" && $mwallet_balance >= $sms_rate){

        $debug = true;
        sendSms($sys_abb,$phone,$sms,$debug);
        $mybalance = $mwallet_balance - $tsms_charges;
        mysqli_query($link, "UPDATE merchant_reg SET wallet_balance = '$mybalance' WHERE merchantID = '$company_id'");
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$company_id','$refid','$phone','$tsms_charges','NGN','system','SMS Content: $sms','successful',NOW())");
        mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");

    }elseif($c_type == "cooperative" && $cwallet_balance >= $sms_rate){

        $debug = true;
        sendSms($sys_abb,$phone,$sms,$debug);
        $mybalance = $cwallet_balance - $tsms_charges;
        mysqli_query($link, "UPDATE cooperatives SET wallet_balance = '$mybalance' WHERE coopid = '$company_id'");
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$company_id','$refid','$phone','$tsms_charges','NGN','system','SMS Content: $sms','successful',NOW())");
        mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");

    }elseif($c_type == "user" && $bwallet_balance >= $sms_rate){

        $debug = true;
        sendSms($sys_abb,$phone,$sms,$debug);
        $mybalance = $bwallet_balance - $tsms_charges;
        mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$mybalance' WHERE account = '$company_id'");
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$company_id','$refid','$phone','$tsms_charges','NGN','system','SMS Content: $sms','successful',NOW())");
        mysqli_query($link, "UPDATE sms_logs1 SET sms_status = 'Sent' WHERE id = '$id'");

    }

}

?>