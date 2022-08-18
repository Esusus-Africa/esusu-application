<?php

$db = new Database();
$user = new User($db);
$newhttp = new eHttpResponse();

/**
 * get authorization header
 * */
function getAuthorizationHeader(){
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    }
    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //print_r($requestHeaders);
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}

/**
 * get access token from header
 * */
function getBearerToken() {
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}

if(getAuthorizationHeader() == "" || getBearerToken() == ""){

    $newhttp->notAuthorized("Authorization should not be empty");
    exit();

}else {

    $bearerToken = getBearerToken();

    $systemDetails = $db->checkAuthKey($bearerToken);

    $systemDetails2 = $db->fetchSystemSet();

    if($systemDetails === 0){

        $newhttp->notAuthorized("Invalid Authorization");
        exit();

    }else{

        $bearerToken = $systemDetails['api_key'];
        $clientId = $systemDetails['institution_id'];
        $companyName = $systemDetails['institution_name'];
        $companyType = $systemDetails['itype'];
        $iwallet_balance = $systemDetails['wallet_balance'];
        $aggrId = $systemDetails['aggr_id'];
        $fileBaseUrl = $systemDetails2['file_baseurl'];
        $trans_charges = $systemDetails2['fax'];
        $companyEmail = $systemDetails['official_email'];
        $companyPhone = $systemDetails['official_phone'];

    }

}

?>