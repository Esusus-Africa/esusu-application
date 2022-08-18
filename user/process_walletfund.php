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
if(isset($_POST['FundWallet']) && ($fetchsys_config['mo_status'] == "NotActive"))
{
	include ("../config/restful_apicalls.php");
    
    $curl = curl_init();
    
    $reference =  "EA-fundWAllet-".myreference(10);
	$amount =  mysqli_real_escape_string($link, $_POST['amount']);
	$secure_amount = base64_encode($amount);
	$redirect_url = 'https://esusu.app/user/mywallet_history.php?tid='.$_SESSION['tid'].'&&o_amt='.$secure_amount.'&&acn='.$_SESSION['acctno'].'&&refid='.$reference.'&&mid=NDA3';
	
	$systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    $mo_status = $row1->mo_status;
    $mo_contract_code = $row1->mo_contract_code;
    
    $publickey = $row1->public_key;
    $localpayment_charges = $row1->localpayment_charges;
    $capped_amount = $row1->capped_amount;
    $intlpayment_charges = $row1->intlpayment_charges;
        
    $ip = $_SERVER['REMOTE_ADDR'];
    $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip), true);
    $country = $dataArray["geoplugin_countryName"];
    $country_currencycode = $dataArray["geoplugin_currencyCode"];
        
    $local_rate = $amount * $localpayment_charges;
    $intl_rate = ($amount * $intlpayment_charges) + $amount;
        
    $max_cap_amount = $amount + $capped_amount;
        
    $cal_charges = ($country != "Nigeria" || $country_currencycode != "NGN" ? $intl_rate : ($country == "Nigeria" && $local_rate >= $capped_amount ? $max_cap_amount : ($local_rate + $amount)));
    
    if($mo_status == "Active")
    {
    
        $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'mo_standard_payment'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://sandbox.monnify.payment.engine.development.teamapt.com/api/v1/merchant/transactions/init-transaction",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => false,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode([
            'amount'            =>  $cal_charges,
            'customerName'      =>  $bname,
            'customerEmail'     =>  $email2,
            'paymentReference'  =>  $reference,
            'paymentDescription'=>  'Funding My Super Wallet',
            'currencyCode'      =>  $bbcurrency,
            'contractCode'      =>  $mo_contract_code
          ]),
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json"
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
          echo "cURL Error #:" . $err;
        }
        
        $transaction = json_decode($response);
        
        var_dump($transaction);
        
        //if($transaction->responseCode == "0"){
          // there was an error from the API
         // print_r ($transaction->responseMessage);
         //echo $response;
       // }
        
        // uncomment out this line if you want to redirect the user to the payment page
        //print_r($transaction->data->message);
        
        
        // redirect to page so User can pay
        // uncomment this line to allow the user redirect to the payment page
        //header('Location: ' . $transaction->data->link);
        
    }
    else{
        
        $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'standard_payment'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode([
            'amount'=>$cal_charges,
            'customer_email'=>$email2,
            'currency'=>$bbcurrency,
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
	
}
else{
    
    include ("../config/restful_apicalls.php");
    
    $curl = curl_init();
    
    $reference =  "EA-fundWAllet-".myreference(10);
	$amount =  mysqli_real_escape_string($link, $_POST['amount']);

	$systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    
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
    
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'mo_standard_payment'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => false,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode([
        'amount'=>$cal_charges,
        'customerName'=>$bname,
        'customerEmail'=>$email2,
        'paymentReference'=>$reference,
        'paymentDescription'=>'Fund Wallet: '.$acctno,
        'currencyCode'=>$bbcurrency,
        'contractCode'=>$row1->mo_contract_code
      ]),
      CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Authorization: Basic TUtfUFJPRF9HSFpZR1o0U0hIOjJRTjJRVFBUU0hUS0hUVVFHWFVZVk1DNVFaVVNRR0RV"
      ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
        $transaction = json_decode($response);
        
        //print_r($transaction);
    
        if(!$transaction->requestSuccessful == true && !$transaction->responseMessage == 'success'){
          // there was an error from the API
          print_r('API returned error: ' . $transaction->responseMessage);
        }
        
        //mysqli_query($link, "UPDATE merchant_reg SET wallet_balance = '$mybalance' WHERE merchantID = '$company_id'");
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$reference','$acctno','$amount','$bbcurrency','MNFY','','pending',NOW(),'$acctno','$bwallet_balance')");
        // uncomment out this line if you want to redirect the user to the payment page
        echo "<script>window.location='".$transaction->responseBody->checkoutUrl."'; </script>";
    }
    
}
?>
</div>
</body>
</html>