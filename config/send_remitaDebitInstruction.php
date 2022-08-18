<?php
include("connect.php");

$date = new DateTime(date("Y-m-d"));
$date->sub(new DateInterval('P5D')); //substract 5 days from the original date
$date_now = $date->format('Y-m-d');
$search_mloan = mysqli_query($link, "SELECT * FROM pay_schedule WHERE direct_debit_status = 'NotSent' AND status = 'UNPAID' AND schedule <= '$date_now'");
while($get_mloan = mysqli_fetch_array($search_mloan))
{
    //Initiate a new cURL session
    $ch = curl_init();
                                
    //Schedule ID
    $id = $get_mloan['id'];
    //Loan ID
    $lid = $get_mloan['lid'];
    //Institution ID
    $instId = $get_mloan['branchid'];
    //Amount to Debit
    $totalAmount = number_format($get_mloan['payment'],2,'.','');
    
    //REMITAL CREDENTIALS
    $verify_icurrency = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instId'");
    $fetch_icurrency = mysqli_fetch_array($verify_icurrency);
    $remita_merchantid = $fetch_icurrency['remitaMerchantId'];
    $remita_apikey = $fetch_icurrency['remitaApiKey'];
    $remita_serviceid = $fetch_icurrency['remitaServiceId'];
    $api_token = $fetch_icurrency['remitaApiToken'];
    $requestid = date("dmY").time();
    
    $search_ldetails = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'");
    $fetch_ldetails = mysqli_fetch_array($search_ldetails);
    $mandate_id = $fetch_ldetails['mandate_id'];
    $funcing_acct = $fetch_ldetails['funcing_acct'];
    $funding_bankcode = $fetch_ldetails['funding_bankcode'];
    
    $concat_param = $remita_merchantid.$remita_serviceid.$requestid.$totalAmount.$remita_apikey;
    $hash = hash("sha512", $concat_param);
                                
    $search_restapi2 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'remita'");
    $fetch_restapi2 = mysqli_fetch_object($search_restapi2);
    $url = $fetch_restapi2->api_url;
                                
    $api_url = $url."remita/exapp/api/v1/send/api/echannelsvc/echannel/mandate/payment/send";
    
    $postdata = array(
        "merchantId" => $remita_merchantid,
        "serviceTypeId" => $remita_serviceid,
        "requestId" => $requestid,
        "hash"  => $hash,
        "totalAmount"  => $totalAmount,
        "mandateId" => $mandate_id,
        "fundingAccount"  => $funcing_acct,
        "fundingBankCode" => ($funding_bankcode === "221") ? "039" : $funding_bankcode
        );
                                
    curl_setopt($ch, CURLOPT_URL, $api_url);
    
    //Set the CURLOPT_RETURNTRANSFER option ton true
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                                
    //set the CURLOPT_POST option to true for POST request
    curl_setopt($ch, CURLOPT_POST, TRUE);
                                                
    //Set the request data as Array using json_encoded function
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
                                                
    //Set custom headers for Content-Type header
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                                                    
    //Execute cURL request with all previous settings
    $response2 = curl_exec($ch);
    $output2 = trim(json_decode(json_encode($response2), true),'jsonp ();');
                                
    $result = json_decode($output2, true);
    
    if($result['statuscode'] === "01" || $result['statuscode'] === "069"){
        
        $remitaRRR = $result['RRR'];
        $transactionRef = $result['transactionRef'];
        $newrequestid = $result['requestId'];

        mysqli_query($link, "UPDATE loan_info SET remita_rrr = '$remitaRRR', trans_ref = '$transactionRef' WHERE lid = '$lid'");
        mysqli_query($link, "UPDATE pay_schedule SET direct_debit_status = 'Sent', requestid = '$newrequestid' WHERE id = '$id'");
        
    }
    else{
        
        echo "";
        
    }
    
}
?>