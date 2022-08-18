<!DOCTYPE html>
<html lang="en">
<head>
    <title>Testing</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <form method="post" enctype="multipart/form-data">
    <input name="amount" type="text" placeholder="Enter Amount to be Fund" required>
    <button name="FundWallet" type="submit" >Fund Wallet</button>
<?php
if(isset($_POST['FundWallet']))
{
    include "config/connect.php";
    
	function myreference($limit)
    {
    	return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
    
    $curl = curl_init();
    
    $reference =  "EA-fundWAllet-".myreference(10);
	$amount =  mysqli_real_escape_string($link, $_POST['amount']);
	$secure_amount = base64_encode($amount);
	$redirect_url = 'https://esusu.app/institution/mywallet.php?id=DIR30632441&&o_amt='.$secure_amount.'&&refid='.$reference.'&&mid=NDA3';

	$systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    $publickey = $row1->public_key;
    
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'standard_payment'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode([
        'amount'=>$amount,
        'customer_email'=>"ayodeji@esusu.africa",
        'currency'=>"NGN",
        'txref'=>$reference,
        'PBFPubKey'=>$publickey,
        'redirect_url'=>$redirect_url
      ]),
      CURLOPT_HTTPHEADER => [
        "content-type: application/json",
        "cache-control: no-cache"
      ],
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    if($err){
      // there was an error contacting the rave API
      die('Curl returned error: ' . $err);
    }
    
    $transaction = json_decode($response);
    
    if(!$transaction->data && !$transaction->data->link){
      // there was an error from the API
      print_r('API returned error: ' . $transaction->message);
    }
    
    // uncomment out this line if you want to redirect the user to the payment page
    print_r($transaction->data->message);
    
    
    // redirect to page so User can pay
    // uncomment this line to allow the user redirect to the payment page
    //header('Location: ' . $transaction->data->link);
    echo "<script>window.location='".$transaction->data->link."'; </script>";
}
?>
</form>
</body>
</html>