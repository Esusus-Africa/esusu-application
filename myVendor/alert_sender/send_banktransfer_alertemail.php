<?php
//include("../config/connect.php");
$result = array();

$systemset = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
$r = mysqli_fetch_object($systemset);

// Pass the customer's authorisation code, email and amount
$postdata =  array(
              "From"          => $r->email_from,
              "To"            => $senderEmail,  //User Email Address
              "TemplateId"    => '21373949',
              "TemplateModel" => [
                "txid"              => $tReference, //Transaction ID
                "logo_url"          => $r->logo_url,
                "product_url"       => $r->website,
                "product_name"      => $r->name,
                "acct_name"         => $senderName, //User Full Name
                "trans_date"        => $transactionDateTime, //Transaction date
                "platform_name"     => $r->name, //Platform Name
                "recipient_name"    => $accountName, //Recipient Name
                "recipient_acctno"  => $recipientAcctNo, //Recipient Account Number
                "recipient_bankname"=> $mybank_name, //Recipient Bank Name
                "merchant_name"     => $merchantName,
                "amount"            => $icurrency.number_format($amountWithNoCharges,2,'.',','), //Amount Transfer
                "wallet_balance"    => $icurrency.number_format($senderBalance,2,'.',','), //Remaining Balance Left
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