<?php 
error_reporting(0); 
include "../config/session1.php";
?>  

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
		<div class="loader"></div>
<?php
include("../config/restful_apicalls.php");

$result = array();
$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));		
$r = mysqli_fetch_object($query);
$rave_key = ($fetch_icurrency->rave_status === "Enabled") ? $fetch_icurrency->rave_secret_key : $r->secret_key;

$txid =  mysqli_real_escape_string($link, $_GET['txid']);
$scode = mysqli_real_escape_string($link, $_GET['scode']);

$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'subscription_action'");
$fetch_restapi = mysqli_fetch_object($search_restapi);
$api_url = $fetch_restapi->api_url;

// Pass the subaccount name, settlement bank account, account number and percentage charges
$postdata =  array(
	"seckey" => $rave_key
);

$url = $api_url.$txid."/activate?fetch_by_tx=1";
	
$make_call = callAPI('POST', $url, json_encode($postdata));
$result = json_decode($make_call, true);

//var_dump($result);

if($result['status'] == "success"){

	$update_records = mysqli_query($link, "UPDATE savings_subscription SET status = 'Active' WHERE subscription_code = '$scode'");
	echo '<meta http-equiv="refresh" content="2;url=view_msaving_sub.php?tid='.$_SESSION['tid'].'&&mid=NDkw">';
	echo '<br>';
	echo'<span class="itext" style="color: blue;">'.$result['message'].'</span>';
}
else{

	echo '<meta http-equiv="refresh" content="2;url=view_msaving_sub.php?tid='.$_SESSION['tid'].'&&mid=NDkw">';
	echo '<br>';
	echo'<span class="itext" style="color: orange">'.$result['message'].'</span>';

}
?>
</div>
</body>
</html>