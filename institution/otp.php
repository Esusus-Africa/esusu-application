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

require_once("../Flutterwave-Rave-PHP-SDK/lib/AccountPayment.php");
use Flutterwave\Account;

$payment = new Account();

$ref = $_GET['txRef'];
$flref = $_GET['ref'];
$otp = $_POST['otp'];

$result = $payment->validateTransaction($otp, $flref, $authModel);

$chargeResponseCode = $result['data']['chargeResponseCode'];

if($chargeResponseCode == "00"){
    

    echo "<script>window.location='https://esusu.app/updateloans.php?id=".$_GET['id']."&&acn=".$acn."&&mid=NDA1&&lid=".$_GET['lid']."&&refid=".$ref."&&tab=tab_3'; </script>";
    
}
else{
    
    $responseMsg = $result['data']['acctvalrespmsg'];
    
    echo $responseMsg;
    
}
?>
		
</div>
</body>
</html>