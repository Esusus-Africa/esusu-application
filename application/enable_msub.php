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
$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));		
$r = mysqli_fetch_object($query);

$result = array();
$token =  mysqli_real_escape_string($link, $_POST['token']);
$scode =  mysqli_real_escape_string($link, $_POST['scode']);

// Pass the subaccount name, settlement bank account, account number and percentage charges
$postdata =  array("code" => $scode, "token" => $token);

$url = "https://api.paystack.co/subscription/enable";
	
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = [
	'Authorization: Bearer '.$r->secret_key,
	  'Content-Type: application/json',
	];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$request = curl_exec($ch);
if(curl_error($ch)){
 echo 'error:' . curl_error($ch);
}
curl_close($ch);
	
if ($request) {
	$result = json_decode($request, true);

if($result){

	if($result['status'] == true){

		echo '<meta http-equiv="refresh" content="2;url=view_msaving_sub.php?tid='.$_SESSION['tid'].'&&mid=NDkw">';
		echo '<br>';
		echo'<span class="itext" style="color: blue;">'.$result['message'].'</span>';

	}
	else{

		echo '<meta http-equiv="refresh" content="2;url=view_msaving_sub.php?tid='.$_SESSION['tid'].'&&mid=NDkw">';
		echo '<br>';
		echo'<span class="itext" style="color: orange">'.$result['message'].'</span>';

	}
}
}
?>
</div>
</body>
</html>