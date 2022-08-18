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
if(isset($_POST['inter_save']))
{
	function myreference($limit)
	{
	    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
	}

	$result = array();
	$amount = mysqli_real_escape_string($link, $_POST['amt_totransfer']);
	$narration =  mysqli_real_escape_string($link, $_POST['reasons']);
	$currency =  mysqli_real_escape_string($link, $_POST['currency']);
	$reference =  "EA-itransfer-".myreference(10);
	$b_name = mysqli_real_escape_string($link, $_POST['b_name']);
	$account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
	$routing_number = mysqli_real_escape_string($link, $_POST['routing_number']);
	$swift_code = mysqli_real_escape_string($link, $_POST['swift_code']);
	$bank_name = mysqli_real_escape_string($link, $_POST['bank_name']);
	$b_addrs = mysqli_real_escape_string($link, $_POST['b_addrs']);
	$b_country = mysqli_real_escape_string($link, $_POST['b_country']);
	$debit_currency = mysqli_real_escape_string($link, $_POST['debit_currency']);

	$systemset = mysqli_query($link, "SELECT * FROM systemset");
	$row1 = mysqli_fetch_object($systemset);
	$seckey = $row1->secret_key;

	$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'transfer'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
	
	// Pass the parameter here
	$postdata =  array(
		"amount"			=>	$amount,
		"seckey"			=>	$seckey,
		"narration"			=>	$narration,
		"currency"			=>	$currency,
		"reference"			=>	$reference,
		"beneficiary_name"	=>	$b_name,
		"meta"			=>	[
			"AccountNumber"		=>	$account_number,
			"RoutingNumber"		=>	$routing_number,
			"SwiftCode"			=>	$swift_code,
			"BankName"			=>	$bank_name,
			"BeneficiaryName"	=>	$b_name,
			"BeneficiaryAddress"=>	$b_addrs,
			"BeneficiaryCountry"=>	$b_country	
		],
		"callback_url"		=>	"https://app.esusu.africa/application/mywallet.php/?id=".$_SESSION['tid']."&&ref=".$reference."&&mid=NDA0",
		"debit_currency"	=>	$debit_currency
	);
	  
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$api_url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
    curl_setopt($ch, CURLOPT_TIMEOUT, 200);

	$headers = array('Content-Type: application/json');
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$request = curl_exec ($ch);

	curl_close ($ch);

	if ($request) {
	$result = json_decode($request, true);

		if($result['status'] == "success"){

			$transfer_id = $result['data']['id'];
		    $transfers_fee = $result['data']['fee'];
		    $status = $result['data']['status'];

		    $insert = mysqli_query($link, "INSERT INTO transfer_history VALUES(null,'$transfer_id','$reference','$account_number','$b_name','$routing_number','$bank_name','$currency','$amount','$transfers_fee','$status','$narration',NOW())");

			echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_2">';
			echo '<br>';
			echo'<span class="itext" style="color: blue;">Transfer Initiated Successfully!!</span>';
		
		}
		else{
			echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_2">';
			echo '<br>';
			echo'<span class="itext" style="color: blue;">'.$result['data']['message'].'</span>';
		}
	}
}
?>
</div>
</body>
</html>