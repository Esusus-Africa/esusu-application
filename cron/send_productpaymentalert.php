<?php
//include("../config/connect.php");
$result = array();

$systemset = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
$r = mysqli_fetch_object($systemset);

// Pass the customer's authorisation code, email and amount
$postdata =  array(
              "From"          => $r->email_from,
              "To"            => $creatorEmail,  //Receiver Email Address
              "TemplateId"    => '17983915',
              "TemplateModel" => [
                  "trans_date"        => $converted_date,  //Date Time
                  "refid"             => $new_reference,
                  "logo_url"          => $r->logo_url,
                  "product_url"       => $r->website,
                  "product_name"      => $r->name,
                  'customer_id'       => $customer,
                  'customer_name'     => $myfullname,
                  'sub_code'          => $real_subscription_code,
                  'plan_code'         => $plancode,
                  'plan_cat'          => $categories,
                  'plan_name'         => $plan_name,
                  'payer_bank'        => $mybank_name,
                  'payer_acctno'      => $account_number,
                  'payer_acctname'    => $b_name,
                  'amount_paid'       => $plancurrency.number_format($amountpaid,2,'.',','),
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