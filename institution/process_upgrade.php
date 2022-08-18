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
if(isset($_POST['UpgradePlan']))
{
  include ("../config/restful_apicalls.php");
  $curl = curl_init();
    
  $reference =  "EA-UpgradePlan-".myreference(10);
  $plan_code =  mysqli_real_escape_string($link, $_POST['plan_code']);
  $amount = mysqli_real_escape_string($link, $_POST['amount']);
  $secure_amount = base64_encode($amount);
  $redirect_url = 'https://esusu.app/institution/saassub_history.php?tid='.$_SESSION['tid'].'&&u_amt='.$secure_amount.'&&plcode='.$plan_code.'&&u_refid='.$reference.'&&mid=NDIw';
  
  $systemset = mysqli_query($link, "SELECT * FROM systemset");
  $row1 = mysqli_fetch_object($systemset);
  $publickey = $row1->public_key;
  
  $localpayment_charges = $row1->localpayment_charges;
  $capped_amount = $row1->capped_amount;
  $intlpayment_charges = $row1->intlpayment_charges;
    
  $ip = $_SERVER['REMOTE_ADDR'];
  $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
  $country = $dataArray->geoplugin_countryName;
  $country_currencycode = $dataArray->geoplugin_currencyCode;
    
  $local_rate = $amount * $localpayment_charges;
  $intl_rate = ($amount * $intlpayment_charges) + $amount;
    
  $max_cap_amount = $amount + $capped_amount;
    
  $cal_charges = ($country != "Nigeria" || $country_currencycode != "NGN" ? $intl_rate : ($country == "Nigeria" && $local_rate >= $capped_amount ? $max_cap_amount : ($local_rate + $amount)));

  $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'standard_payment'");
  $fetch_restapi = mysqli_fetch_object($search_restapi);
  $api_url = $fetch_restapi->api_url;
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode([
        'amount'=>$cal_charges,
        'customer_email'=>$inst_email,
        'currency'=>$icurrency,
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
</div>
</body>
</html>