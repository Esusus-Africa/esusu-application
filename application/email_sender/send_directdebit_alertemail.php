<?php
//include("../config/connect.php");
$result = array();

$systemset = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
$r = mysqli_fetch_object($systemset);

// Pass the customer's authorisation code, email and amount
$postdata =  array(
              "From"          => $r->email_from,
              "To"            => $em,  //Borrower Email Address
              "TemplateId"    => '10204486',
              "TemplateModel" => [
                "txid"              => $txid, //Transaction ID
                "product_name"      => $r->name,
                "trans_date"        => $final_date_time, //Transaction date
                "ttype"             => $charges_name //Transaction Type
                "name"              => $uname,  //Borrower username
                "platform_name"     => $r->name, //Platform Name
                "acct_name"         => $ln.' '.$fn, //Borrower Full Name
                "account_number"    => $account, //Borrower Account Number
                "amount"            => $r->currency.number_format($amount,2,'.',','), //Amount Charge
                "legal_balance"     => $r->currency.number_format($total,2,'.',','), //Legal Balance (For Deposit & Savings)
                "product_url"       => $r->website,
                "logo_url"          => $r->logo_url,
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
    echo "Error Code: ".$result['ErrorCode'];
  }
}
?>