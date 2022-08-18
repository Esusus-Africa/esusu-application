<?php
//include("../config/connect.php");
$result = array();

$systemset = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
$r = mysqli_fetch_object($systemset);

// Pass the customer's authorisation code, email and amount
$postdata =  array(
              "From"          => $r->email_from,
              "To"            => $em,  //User Email Address
              "TemplateId"    => '20045888',
              "TemplateModel" => [
                "txid"              => $reference, //Transaction ID
                "logo_url"          => $r->logo_url,
                "product_url"       => $r->website,
                "product_name"      => $r->name,
                "acct_name"         => $myname, //User Full Name
                "trans_date"        => $DateTime, //Transaction date
                "platform_name"     => $r->name, //Platform Name
                "account_id"        => $accountNo, //User Account ID
                "merchant_name"     => $merchantName,
                "amount"            => $currency.number_format($amtWithCharges,2,'.',','), //Amount Debit with charges
                "wallet_balance"    => $currency.number_format($updatedBalance,2,'.',','), //Wallet Balance
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