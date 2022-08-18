<?php

include("../config/connect.php");

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

$search_invite = mysqli_query($link, "SELECT * FROM invite WHERE status = 'Pending'");
while($fetch_invite = mysqli_fetch_array($search_invite)){
    
    $id = $fetch_invite['id'];
    
    $institution_id = $fetch_invite['companyid'];
    
    $shortenedurl = $fetch_invite['invite_link'];
    
    $userid = $fetch_invite['userid'];
    
    $concat = $fetch_invite['mydata'];
    
    $datetime = date("Y-m-d");
    
    $parameter = (explode('|',$concat));
    
    $fname = $parameter[0];
    $email = $parameter[1];
    $phone = $parameter[2];
    $sysabb = $parameter[3];
    $itype = $parameter[4];
    $translate_itype = ($itype == "1") ? "an agent" : "a member";
    
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    $icname = $fetch_memset['cname'];
    $portalLink = ($fetch_memset['mobileapp_link'] == "") ? "https://esusu.app/$sysabb" : $fetch_memset['mobileapp_link'];
    $isubagent_wallet = $fetch_memset['subagent_wallet'];
    
    $searchUserId = mysqli_query($link, "SELECT * FROM user WHERE id = '$userid'");
    $fetchUserId = mysqli_fetch_array($searchUserId);
    $iname = $fetchUserId['name']." ".$fetchUserId['lname']." ".$fetchUserId['lname'];
    $irole = $fetchUserId['role'];
    $iwallet_balance2 = $fetchUserId['wallet_balance'];
    
    $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$institution_id'");
    $fetch_inst = mysqli_fetch_array($search_inst);
    $iwallet_balance = $fetch_inst['wallet_balance'];
    
    $iassigned_walletbal = (($irole === "agent_manager" || $irole === "institution_super_admin" || $irole === "merchant_super_admin") && ($isubagent_wallet == "Enabled" || $isubagent_wallet == "No" || $isubagent_wallet == "Disabled") ? $iwallet_balance : (($irole != "agent_manager" || $irole != "institution_super_admin" || $irole != "merchant_super_admin") && ($isubagent_wallet == "No" || $isubagent_wallet == "Disabled") ? $iwallet_balance : $iwallet_balance2));
    
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $sms_rate = $r->fax;
    $sys_email = $r->email;
    
    $sms = "$sysabb>>>Dear $fname! This is to notify you of an attempt by: $iname to register you as $translate_itype. Please click $shortenedurl to join $icname for potential financial product";
    
    $max_per_page = 153;
    $sms_length = strlen($sms);
    $calc_length = ceil($sms_length / $max_per_page);
    
    $sms_charges = $calc_length * $sms_rate;
    $mywallet_balance = $iassigned_walletbal - $sms_charges;
    $refid = "EA-smsCharges-".time();
    
    $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;
    
    mysqli_query($link, "UPDATE invite SET status = 'Sent' WHERE id = '$id' AND status = 'Pending'");
    
    if($iassigned_walletbal >= $sms_charges && $phone != "")
	{
		$debug = true;
        sendSms($sysabb,$phone,$sms,$debug);
		include('../cron/send_invite.php');
		mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','$phone','','$sms_charges','','NGN','Charges','SMS Content: $sms','successful','$datetime','$userid','$mywallet_balance','')");
     	mysqli_query($link, "INSERT INTO sms_logs1 VALUES(null,'$institution_id','institution','$sysabb','$phone','$sms','Sent',NOW())");
        (($irole != "agent_manager" || $irole != "institution_super_admin" || $irole != "merchant_super_admin") && $isubagent_wallet === "Enabled") ? mysqli_query($link, "UPDATE user SET wallet_balance = '$mywallet_balance' WHERE id = '$userid'") : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mywallet_balance' WHERE institution_id = '$institution_id'");
	}
	else{
		include('../cron/send_invite.php');
	}
    
}

?>