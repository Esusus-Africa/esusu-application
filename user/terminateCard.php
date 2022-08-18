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

    $result = array();
    $cid = $_GET['cid'];
	$search_id = mysqli_query($link, "SELECT * FROM card_enrollment WHERE id = '$cid'");
    $get_id = mysqli_fetch_array($search_id);
    $card_id = $get_id['card_id'];

	$systemset = mysqli_query($link, "SELECT * FROM systemset");
	$row1 = mysqli_fetch_object($systemset);
    $seckey = $row1->secret_key;
    
    $api_name =  "terminate-virtualcards";
	$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = 'Flutterwave'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;

	// Pass the parameter here
	$postdata =  array(
		"id" =>	$card_id,
		"secret_key" =>	$seckey
	);

	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);
	
	if($result['status'] == "success")
	{
        
        mysqli_query($link, "UPDATE card_enrollment SET is_active = '0' WHERE id = '$cid'");

		echo '<meta http-equiv="refresh" content="5;url=allcard?id='.$_SESSION['tid'].'&&mid='.base64_encode("900").'">';
		echo '<br>';
		echo'<span class="itext" style="color: blue;">Card Terminated Successfully!!</span>';

	}
	else{
		echo '<meta http-equiv="refresh" content="5;url=allcard?id='.$_SESSION['tid'].'&&mid='.base64_encode("900").'">';
		echo '<br>';
		echo'<span class="itext" style="color: orange;">Sorry!...Unable to terminate card, please try again later!!</span>';
	}

}
?>
</div>
</body>
</html>