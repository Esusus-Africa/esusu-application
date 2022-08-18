<?php
//include("../config/connect.php");
$result = array();
// Pass the customer's authorisation code, email and amount
$postdata =  array(
              "From"          => $r->email_from,
              "To"            => $emailReceiver,  //Customer Email Address
              "TemplateId"    => '20288401',
              "TemplateModel" => [
                "doc_type"          => $docType,
                "logo_url"          => $r->logo_url,
                "product_url"       => $r->website,
                "product_name"      => $r->name,
                "cust_name"         => $custName,
                "subcode"           => $scode,
                "plan_name"         => $planName,
                "plan_cat"          => $plancat,
                "plan_amount"       => $planamount,
                "wallet_acctno"     => ($custVAActNo == "") ? "---" : $custVAActNo,
                "phone"             => $phone,
                "mdate"             => $mdate,
                "support_email"     => $r->email,
                "live_chat_url"     => $r->live_chat,
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
    //echo "Error Code: ".$result['ErrorCode'];
  }
}
?>