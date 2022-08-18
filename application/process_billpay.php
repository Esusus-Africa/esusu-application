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
if(isset($_POST['PayBill1']))
{
	include ("../config/restful_apicalls.php");
    
    $response = array();
	$country =  mysqli_real_escape_string($link, $_POST['country']);
	$customerid = mysqli_real_escape_string($link, $_POST['customerid']);
	$reference =  "EA-paybills-".myreference(10);
	$amount =  mysqli_real_escape_string($link, $_POST['amount']);
	$rec_type =  mysqli_real_escape_string($link, $_POST['rec_type']);
	$biller_name =  mysqli_real_escape_string($link, $_POST['biller_name']);
	
	$b_name = mysqli_real_escape_string($link, $_POST['b_name']);

	$systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    $seckey = $row1->secret_key;

    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'billpayment'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;

    $data_array = array(
    	"secret_key"		=> $seckey,
    	"service"			=> "fly_buy",
    	"service_method"	=> "post",
    	"service_version"	=> "v1",
    	"service_channel"	=> "rave",
    	"service_payload"	=> [
    		"Country"		=> $country,
            "CustomerId"	=> $customerid,
            "Reference"		=> $reference,
            "Amount"		=> $amount,
            "RecurringType"	=> $rec_type,
            "IsAirtime"		=> ($biller_name == "AIRTIME") ? true : false,
            "BillerName"	=> $biller_name
    	]
    );

	$make_call = callAPI('POST', $api_url, json_encode($data_array));
	$response = json_decode($make_call, true);

	if($response['data']['Status'] == "success"){

		$message = $response['data']['Message'];
	  	echo '<meta http-equiv="refresh" content="10;url=mywallet.php?id='.$_SESSION['tid'].'&&mid=NDA0">';
		echo '<br>';
		echo '<span class="itext" style="color: blue">'.$message.'</span>';
	}
	else{
		$message2 = $response['data']['Message'];
		echo '<meta http-equiv="refresh" content="5;url=pay_bills.php?id='.$_SESSION['tid'].'&&mid=NDA0">';
		echo '<br>';
		echo '<span class="itext" style="color: blue">'.$message2.'</span>';

	}
}
?>
</div>
</body>
</html>