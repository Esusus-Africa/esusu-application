<?php
//include("../config/connect.php");
$result = array();
// Pass the customer's authorisation code, email and amount
$postdata =  array(
              "From"          => $email_from,
              "To"            => $email,  //User Email Address
              "TemplateId"    => '10291027',
              "TemplateModel" => [
                "txid"              => $refid, //Transaction ID
                "product_name"      => $product_name,
                "trans_date"        => $correctdate, //Transaction date
                "platform_name"     => $product_name, //Platform Name
                "acct_name"         => $aname, //User Full Name
                "account_id"        => $recipientId, //User Account ID
                "amount"            => $currency.number_format($amt,2,'.',','), //Amount Transfer
                "wallet_balance"    => $currency.number_format($totalWbal,2,'.',','), //Wallet Balance (p2p-wallet-transfer)
                "product_url"       => $website,
                "logo_url"          => $logo_url,
                "support_email"     => $support_email,
                "live_chat_url"     => $live_chat_url,
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
  $result = json_decode($request, true);
  if($result['Message'] == "OK")
  {
    //echo "Email Sent Successfully";
  }else{
    //echo "Error Code: ".$result['ErrorCode'];
  }
}
?>