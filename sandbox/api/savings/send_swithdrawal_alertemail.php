<?php
//include("../config/connect.php");
$result = array();
// Pass the customer's authorisation code, email and amount
$postdata =  array(
              "From"          => $email_from,
              "To"            => $email,  //Borrower Email Address
              "TemplateId"    => '9687992',
              "TemplateModel" => [
                "txid"              => $txid, //Transaction ID
                "product_name"      => $product_name,
                "trans_date"        => $correctdate, //Transaction date
                "name"              => $fname,  //Borrower username
                "platform_name"     => $product_name, //Platform Name
                "acct_name"         => $lname.' '.$fname, //Borrower Full Name
                "account_number"    => $account, //Borrower Account Number
                "amount"            => $currency.number_format(($amt + $wcharges),2,'.',','), //Amount Withdrawn
                "legal_balance"     => $currency.number_format($ledger_bal,2,'.',','), //Legal Balance (For Deposit & Savings)
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