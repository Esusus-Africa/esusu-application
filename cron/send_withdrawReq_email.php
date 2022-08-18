<?php
//include("../config/connect.php");
$result = array();
$systemset = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
$r = mysqli_fetch_object($systemset);

// Pass the customer's authorisation code, email and amount
$postdata =  array(
              "From"          => $r->email_from,
              "To"            => $em,  //Company Email Address
              "TemplateId"    => '20316713',
              "TemplateModel" => [
                  "logo_url"          => $r->logo_url,
                  "product_url"       => $r->website,
                  "product_name"      => $r->name,
                  "vendor_name"       => $vendorName,
                  "trans_type"        => $stype,
                  "destination_channel"=>$destinationChannel,
                  "trans_date"        => date("Y-m-d g:i A"), //Transaction date
                  "refid"             => $wtoken,
                  "vendor_contact"    => $vendorContact,
                  "req_amount"        => $vcurrency.number_format($requestAmt,2,'.',','),
                  "req_status"        => $req_status,
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