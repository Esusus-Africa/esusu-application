<?php

$db = new Database();
$user = new User($db);
$providusVPS = new providusVPS($db);
$newhttp = new pBHttpResponse();

/**
 * get authorization header
 * */
function getAuthorizationHeader(){
    $headers = null;
    if (isset($_SERVER['HTTP_X_AUTH_SIGNATURE'])) {
        $headers = trim($_SERVER["HTTP_X_AUTH_SIGNATURE"]);
    }
    return $headers;
}


if(getAuthorizationHeader() === ""){

    $newhttp->notAuthorized("Unauthorized Access");
    exit();

}else{

    $signature = getAuthorizationHeader();

    $systemDetails = $db->fetchSystemSet();
    $providusClientId = $systemDetails['providusClientId'];
    $providusClientSecret = $systemDetails['providusClientSecret'];
    
    $encodeAUth = $providusClientId.":".$providusClientSecret;
    //$systemDetails['providusSignature'];
    
    $correctSignature = hash('sha512', $encodeAUth);
    
    if($signature != $correctSignature){

        $newhttp->notAuthorized("Unauthorized Access");
        exit();
   

    }else{

        $signature = $correctSignature;

    }

}

?>