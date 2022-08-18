<?php
//include("../config/connect.php");
$result = array();
// Pass the customer's authorisation code, email and amount
$postdata =  array(
              "From"          => $r->email_from,
              "To"            => $email,  //Customer Email Address
              "TemplateId"    => '19522793',
              "TemplateModel" => [
                "logo_url"          => $r->logo_url,
                "product_url"       => $r->website,
                "product_name"      => $r->name,
                "ussd_code"         => $workableUssdCode, //Complete USSD Code
                "amount"            => $icurrency.number_format($amt,2,'.',','),
                "datetime"          => $DateTime,
                "support_email"     => $r->email,
                "live_chat_url"     => $r->live_chat,
                "company_name"      => $r->name,
                "company_address"   => $r->address,
                "txid"              => $TransactionID
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
    //echo "Error Code: ".$result['ErrorCode'];
  }
}
?>