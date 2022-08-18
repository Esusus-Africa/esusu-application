<?php
//include("../config/connect.php");
$result = array();
// Pass the customer's authorisation code, email and amount
$postdata =  array(
              "From"          => $r->email_from,
              "To"            => $emailReceiver,  //Receiver Email Address
              "TemplateId"    => '10833478',
              "TemplateModel" => [
                "product_name"      => $r->name,
                "receipt_id"        => $refid, //Receipt ID
                "product_url"       => $r->website,
                "logo_url"          => $r->logo_url,
                "date_from"         => $date_from, //Start Date
                "date_to"           => $expiry_date, //Expiration Date
                "plan_name"         => $plan_name, //Plan Name
                "plan_code"         => $sub_plan,
                "inst_id"           => $institution_id,
                "sub_token"         => $sub_token,
                "amount"            => $total_amountpaid,
                "merchant_name"     => $name,
                "support_url"       => $r->live_chat,
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