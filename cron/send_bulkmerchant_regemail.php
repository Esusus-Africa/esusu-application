<?php
//include("../config/connect.php");
$result = array();
// Pass the customer's authorisation code, email and amount
$postdata =  array(
              "From"          => $r->email_from,
              "To"            => $emapData[6],  //Merchant Email Address
              "TemplateId"    => '9575497',
              "TemplateModel" => [
                "product_name"      => $r->name,
                "name"              => $emapData[1],  //Merchant Full Name
                "product_url"       => $r->website,
                "logo_url"          => $r->logo_url,
                "username"          => $emapData[16], //Merchant Username
                "password"          => $upassword, //Merchant Password
                "support_email"     => $r->email,
                "live_chat_url"     => $r->live_chat,
                "sender_name"       => $r->email_sender_name,
                "company_name"      => $r->name,
                "company_address"   => $r->address
              ]
            );

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/withTemplate");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = [
  'Accept: application/json',
  'Content-Type: application/json',
  'X-Postmark-Server-Token: '.$r->email_token
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$request = curl_exec($ch);

curl_close ($ch);
if ($request) {
  $result = json_decode($request, true);
  if($result['Message'] == "OK")
  {
    //echo "Email Sent Successfully";
  }else{
    echo "Error Code: ".$result['ErrorCode'];
  }
}
?>