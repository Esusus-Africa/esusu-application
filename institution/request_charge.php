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

$refid = "EA-token-".time();
$systemset = mysqli_query($link, "SELECT * FROM systemset");
$row1 = mysqli_fetch_object($systemset);
$skey = $row1->secret_key;
$pkey = $row1->public_key;
$auth_charges = $row1->auth_charges;
$mysubacct_id = $row1->auth_subaccount;
    
$acn = $_GET['acn'];
$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
$get_customer = mysqli_fetch_object($search_customer);
$customer_email = $get_customer->email;
$customer_dob = $get_customer->dob;
$customer_phone = $get_customer->phone;
$customer_fname = $get_customer->fname;
$customer_lname = $get_customer->lname;
$customer_bvn = $get_customer->unumber;
    
//transferrecipient
$bank_code =  mysqli_real_escape_string($link, $_POST['bank_code']);
$account_number =  mysqli_real_escape_string($link, $_POST['acct_no']);
$currency =  mysqli_real_escape_string($link, $_POST['currency']);
$country =  mysqli_real_escape_string($link, $_POST['country']);

$array = array(
    "PBFPubKey" => $pkey,
    "accountbank"=> $bank_code,// get the bank code from the bank list endpoint.
    "accountnumber" => $account_number,
    "currency" => $currency,
    "payment_type" => "account",
    "country" => $country,
    "amount" => $auth_charges,
    "email" => $customer_email,
    "passcode" => date("dmY", strtotime($customer_dob)), //customer Date of birth this is required for Zenith bank account payment.
    "bvn" => $customer_bvn,
    "phonenumber" => $customer_phone,
    "firstname" => $customer_fname,
    "lastname" => $customer_lname,
    'subaccounts' => [
            [
                'id' => $mysubacct_id,
                'transaction_split_ratio' => '9'
            ],
            [
                'id' => $insti_subaccount_code,
                'transaction_split_ratio' => '1'
            ]
        ],
    "IP" => $_SERVER['REMOTE_ADDR'],
    "txRef" => $refid, // merchant unique reference
    'redirect_url' => 'https://esusu.app/updateloans.php?id='.$_GET['id'].'&&acn='.$acn.'&&mid=NDA1&&lid='.$_GET['lid'].'&&refid='.$refid.'&&tab=tab_3'
    );
    
$txRef = $array['txRef'];

if($payment->accountCharge($array)){
    
    $result = $payment->accountCharge($array);
    
    print_r($result);
    
    $authUrl = $result['data']['authurl'];
    $flwRef = $result['data']['flwRef'];
            
    if($authUrl != "NO-URL"){
                
        echo "<script>window.location='$authUrl'; </script>";
                
    }else{
        
        echo "<script>window.location='confirm_otp.php?txRef=".$txRef."&&ref=".$flwRef."&&id=".$_GET['id']."&&acn=".$_GET['acn']."&&mid=NDA1&&lid=".$_GET['lid']."&&tab=tab_3'; </script>";
                
    }
    
}
?>
</div>
</body>
</html>
