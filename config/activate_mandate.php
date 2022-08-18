<?php

include("connect.php");

include("restful_apicalls.php");

$search_mloan = mysqli_query($link, "SELECT * FROM loan_info WHERE mandate_status = 'InProcess'");
//$search_mloan = mysqli_query($link, "SELECT * FROM loan_info WHERE mandate_id = '260388468483'");
while($get_mloan = mysqli_fetch_array($search_mloan))
{
    $lid = $get_mloan['lid'];
    $mandate_status = $get_mloan['mandate_status'];
    $direct_debit_status = $get_mloan['direct_debit_status'];
    $requestId = $get_mloan['request_id'];
    $mandateId = $get_mloan['mandate_id'];
    $instid = $get_mloan['branchid'];
    
    //REMITAL CREDENTIALS
    $verify_icurrency = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instid'");
    $fetch_icurrency = mysqli_fetch_array($verify_icurrency);
    $remita_merchantid = $fetch_icurrency['remitaMerchantId'];
    $remita_apikey = $fetch_icurrency['remitaApiKey'];
    $remita_serviceid = $fetch_icurrency['remitaServiceId'];
    $api_token = $fetch_icurrency['remitaApiToken'];
    
    //echo $remita_merchantid;
    
    $concat_param = $mandateId.$remita_merchantid.$requestId.$remita_apikey;
    $hash = hash("sha512", $concat_param);
        
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'remita'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $url = $fetch_restapi->api_url;
        
    $api_url = $url."remita/exapp/api/v1/send/api/echannelsvc/echannel/mandate/status";
        
    $postdata = array(
        'merchantId' => $remita_merchantid,
        'mandateId' => $mandateId,
        'hash'  => $hash,
        'requestId' => $requestId
        );
        
    $make_call = callAPI('POST', $api_url, json_encode($postdata));
    $output2 = trim(json_decode(json_encode($make_call), true),'jsonp ();');
    $result = json_decode($output2, true);

    //echo ($result['isActive'] === true) ? "Correct" : "Not-Correct";
    //print_r($output2);

    if($result['statuscode'] === "00" && $result['isActive'] === true){
            
        mysqli_query($link, "UPDATE loan_info SET mandate_status = 'Activated' WHERE lid = '$lid' AND mandate_status = 'InProcess' AND mandate_id = '$mandateId'");
        
    }
    else{
            
       echo "";//Forget this event ever happen
            
    }
}
?>