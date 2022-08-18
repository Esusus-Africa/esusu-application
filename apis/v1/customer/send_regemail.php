<?php
//include("../config/connect.php");
$processing = array();
// Pass the customer's authorisation code, email and amount
$postdata =  array(
              "From"          => $email_from,
              "To"            => $email,  //Customer Email Address
              "TemplateId"    => '9545527',
              "TemplateModel" => [
                "product_name"      => $product_name,
                "name"              => $fname,  //Customer First Name
                "product_url"       => $website,
                "logo_url"          => $logo_url,
                "activation_url"    => $shortenedurl, //Customer Activation Link
                "username"          => $username, //Customer Username
                "password"          => $pw, //Customer Password
                "support_email"     => $support_email,
                "live_chat_url"     => $live_chat_url,
                "sender_name"       => $sender_name,
                "deactivation_url"  => $deactivation_url,  //Customer Deactivation Link
                "company_name"      => $product_name,
                "company_address"   => $company_address
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
  'X-Postmark-Server-Token: '.$email_token
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$request = curl_exec($ch);

curl_close ($ch);
if ($request) {
  $processing = json_decode($request, true);
  if($processing['Message'] == "OK")
  {
    //echo "Email Sent Successfully";
  }else{
    //echo "Error Code: ".$processing['ErrorCode'];
  }
}
?>