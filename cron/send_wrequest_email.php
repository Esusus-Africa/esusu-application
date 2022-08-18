<?php
//include("../config/connect.php");
$result = array();
$systemset = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
$r = mysqli_fetch_object($systemset);

// Pass the customer's authorisation code, email and amount
$postdata =  array(
              "From"          => $r->email_from,
              "To"            => $em,  //Company Email Address
              "TemplateId"    => '11007568',
              "TemplateModel" => [
                  "wtoken"            => $wtoken,  //Token ID
                  "trans_type"        => $stype,
                  "logo_url"          => $r->logo_url,
                  "product_url"       => $r->website,
                  "product_name"      => $r->name,
                  "trans_date"        => date("Y-m-d g:i A"), //Transaction date
                  "name"              => $cname, //Vendor / Merchant Name
                  "acct_name"         => $bname, //Customer Full Name
                  "accountid"         => $bvirtual_acctno, //Customer Account Number
                  "plan_code"         => $plan_code,
                  "sub_code"          => $sub_code, //Subscription Code
                  "plan_name"         => $plan_name,
                  "amount"            => $bbcurrency.number_format($sum_amount,2,'.',','), //Amount Withdrawn
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