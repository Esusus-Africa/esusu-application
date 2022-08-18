<?php 
error_reporting(0); 
include "../config/session.php";
?>  
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
		<div class="loader"></div>

<?php
if(isset($_POST['save']))
{
	include("../config/restful_apicalls.php");

	function ccMasking($number, $maskingCharacter = '*') {
    return substr($number, 0, 3) . str_repeat($maskingCharacter, strlen($number) - 6) . substr($number, -3);
	}

	$result = array();
	$customer =  mysqli_real_escape_string($link, $_POST['customer']);
	$billing_addrs = mysqli_real_escape_string($link, $_POST['billing_addrs']);
	$billing_city = mysqli_real_escape_string($link, $_POST['billing_city']);
	$billing_state = mysqli_real_escape_string($link, $_POST['billing_state']);
	$postalcode = mysqli_real_escape_string($link, $_POST['postalcode']);
	$billing_country = mysqli_real_escape_string($link, $_POST['billing_country']);
	$currency_type =  mysqli_real_escape_string($link, $_POST['currency_type']);
	$amount =  mysqli_real_escape_string($link, $_POST['amount']);
	$api_name =  mysqli_real_escape_string($link, $_POST['api_name']);
	$secureoption =  mysqli_real_escape_string($link, $_POST['secure_option']);
	$bank =  mysqli_real_escape_string($link, $_POST['bank']);
	$pin =  ($secureoption == "pin") ? mysqli_real_escape_string($link, $_POST['pin']) : 'null';
	$refid = "EA-preFundCard-".mt_rand(10000,99999);

	$search_user = mysqli_query($link, "SELECT * FROM agent_data WHERE agentid = '$customer'");
	$get_user = mysqli_fetch_array($search_user);
	$billing_name = ($get_user['bname'] == "") ? $get_user['fname'] : $get_user['bname'];
	$phone = $get_user['phone'];
	$mywallet = $get_user['wallet_balance'];

	$search_memberset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$customer'");
	$get_memberset = mysqli_fetch_array($search_memberset);
	$mycurrency = $get_memberset['currency'];

	$systemset = mysqli_query($link, "SELECT * FROM systemset");
	$row1 = mysqli_fetch_object($systemset);
	$seckey = $row1->secret_key;
	$sysabb = $row1->abb;

	$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$bank'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
	$issuer_name = $fetch_restapi->issuer_name;

	// Pass the parameter here
	$postdata =  array(
		"secret_key"	=>	$seckey,
		"currency"		=>	$currency_type,
		"amount"		=> 	$amount,
		"billing_name"	=>	$billing_name,
		"billing_address"	=>	$billing_addrs,
		"billing_city"	=>	$billing_city,
		"billing_state"	=>	$billing_state,
		"billing_postal_code"	=>	$postalcode,
		"billing_country"	=> $billing_country,
		"callback_url"	=> "https://app.esusu.africa/cron/sub_signal.php?cu".$customer
	);

	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);
	
	//var_dump($result);

	if($result['status'] == "success")
	{
		$id = $result['data']['id'];
		$AccountId = $result['data']['AccountId'];
		$card_hash = $result['data']['card_hash'];
		$cardpan = $result['data']['cardpan'];
		$maskedpan = $result['data']['maskedpan'];
		$cvv = $result['data']['cvv'];
		$expiration = $result['data']['expiration'];
		$card_type = $result['data']['card_type'];
		$name_on_card = $result['data']['name_on_card'];
		$is_active = $result['data']['is_active'];
		$total_walletbal = $mywallet + $amount;

		$accno = ccMasking($customer);

		$date_time = date("Y-m-d H:i:s");
		$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone('America/New_York'));
        $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
        $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
        $correctdate = $acst_date->format('Y-m-d g:i A');
        $postedby = "<br><b>Posted by:<br>".$name."</b>";

		$insert = mysqli_query($link, "INSERT INTO card_enrollment VALUES(null,'$branchid','$csbranchid','$customer','$currency_type','$amount','$phone','$billing_addrs','$billing_country','$id','$AccountId','$card_hash','$cardpan','$maskedpan','$cvv','$expiration','$card_type','$name_on_card','$issuer_name','$secureoption','$pin','$is_active',NOW())");
		$insert = mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$customer','$refid','$amount','$currency_type','preFundCard','$postedby','successful',NOW())");
		$update = mysqli_query($link, "UPDATE agent_data SET card_id = '$id' WHERE agentid = '$customer'");

		$sms = "$sysabb>>>CR";
		$sms .= " Amt: ".$currency_type.number_format($amount,2,'.',',')."";
		$sms .= " Acc: ".$accno."";
		$sms .= " Desc: Prefund Card ";
		$sms .= " Time: ".$correctdate."";
		$sms .= " Wallet Bal: ".$mycurrency.number_format($total_walletbal,2,'.',',')."";

		include("../cron/send_general_sms.php");
		echo '<meta http-equiv="refresh" content="5;url=create_card1?id='.$_SESSION['tid'].'&&mid='.base64_encode("550").'&&aId='.$AccountId.'&&tab=tab_2">';
		echo '<br>';
		echo'<span class="itext" style="color: blue;">Card Created Successfully!!</span>';

	}
	else{
		echo '<meta http-equiv="refresh" content="5;url=create_card1?id='.$_SESSION['tid'].'&&mid='.base64_encode("550").'&&tab=tab_1">';
		echo '<br>';
		echo'<span class="itext" style="color: orange;">'.$result['Message'].'</span>';
	}

}
?>
</div>
</body>
</html>