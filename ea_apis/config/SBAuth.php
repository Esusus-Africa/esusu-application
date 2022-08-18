<?php

$db = new Database();
$user = new User($db);
$sterlingBank = new sterlingBankVA($db);
$newhttp = new sBHttpResponse();

$systemDetails = $db->fetchSystemSet();
$sterlinkInputKey = $systemDetails['sterlinkInputKey'];
$sterlingIv = $systemDetails['sterlingIv'];

if($sterlinkInputKey == "" || $sterlingIv == ""){

    $newhttp->notAuthorized("Invalid Authorization");
    exit();

}else{

    $inputKey = $sterlinkInputKey;
    $iv = $sterlingIv;

}

?>