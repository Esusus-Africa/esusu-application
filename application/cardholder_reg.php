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
$id = $_GET['id'];
$search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id'");
$get_user = mysqli_fetch_array($search_user);
$customer = $get_user['account'];
$billing_name = $get_user['lname'].' '.$get_user['fname'];
$billing_addrs = $get_user['addrs'];
$billing_city = $get_user['city'];
$billing_state = $get_user['state'];
$zip_postalcode = $get_user['zip'];
$billing_country = $get_user['country'];
$phone = $get_user['phone'];

$systemset = mysqli_query($link, "SELECT * FROM systemset");
$row1 = mysqli_fetch_object($systemset);
$seckey = $row1->secret_key;
$sysabb = $row1->abb;
$bancore_merchantID = $row1->bancore_merchant_acctid;
$bancore_mprivate_key = $row1->bancore_merchant_pkey;
$passcode = $bancore_merchantID.substr($phone, -13).$bancore_mprivate_key;
$encKey = hash('sha256',$passcode);
	
$api_name =  "cardholder_registration";
$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = 'Mastercard'");
$fetch_restapi = mysqli_fetch_object($search_restapi);
$api_url = $fetch_restapi->api_url;
		
function registerCardHolder($name, $ph, $address, $city, $country, $zip, $customerID, $debug=false){
    global $bancore_merchantID,$encKey,$api_url;
		
	$url = '?merchantID='.$bancore_merchantID;
	$url.= '&phone='.urlencode(substr($ph, -13));
	$url.= '&name='.urlencode($name);
	$url.= '&address='.urlencode($address);
	$url.= '&city='.urlencode($city);
	$url.= '&country='.urlencode($country);
	$url.= '&zip='.urlencode($zip);
	$url.= '&customerID='.urlencode($customerID);
	$url.= '&encKey='.$encKey;
		
	$urltouse =  $api_url.$url;
	//if ($debug) { echo "Request: <br>$urltouse<br><br>"; }
		
	//Open the URL to send the message
	$response = file_get_contents($urltouse);
	if ($debug) {
		//echo "Response: <br><pre>".
	    //str_replace(array("<",">"),array("&lt;","&gt;"),$response).
		//"</pre><br>"; 
		//echo substr($response, 112);
		//$textMsg = substr($response, 112);
		//$textCode = substr($response, 7);
	}
	return($response);
}
		
$debug = true;
//echo registerCardHolder($billing_name,$phone,$billing_addrs,$billing_city,$billing_country,$zip_postalcode,$customer,$debug);
$processCard = registerCardHolder($billing_name,$phone,$billing_addrs,$billing_city,$billing_country,$zip_postalcode,$customer,$debug);
$iparr = split ("\&", $processCard);
$regStatus = substr("$iparr[0]",7);

if($regStatus == 30){
	
	$update = mysqli_query($link, "UPDATE borrowers SET card_reg = 'Yes', card_issurer = 'Mastercard' WHERE id = '$id'");
	
	echo '<meta http-equiv="refresh" content="5;url=customer?id='.$_SESSION['tid'].'&&mid=NDAz">';
	echo '<br>';
	echo '<span class="itext" style="color: orange;">Cardholder Registered Successfully</span>';
	
}elseif($regStatus == 60){
	
	echo '<meta http-equiv="refresh" content="5;url=customer?id='.$_SESSION['tid'].'&&mid=NDAz">';
	echo '<br>';
	echo '<span class="itext" style="color: orange;">Oops! Specified phone already registered.</span>';
	
}
?>
</div>
</body>
</html>