<?php
//include("../config/connect.php");
$result = array();

$systemset = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
$r = mysqli_fetch_object($systemset);

// Pass the customer's authorisation code, email and amount
$postdata =  array(
              "From"          => $myiemail_addrs,
              "To"            => $r->email,  //Our Support Email Address
              "TemplateId"    => '16267417',
              "TemplateModel" => [
                  "staff_name"        => $myname,
                  "logo_url"          => $r->logo_url,
                  "product_url"       => $r->website,
                  "product_name"      => $r->name,
                  "sender_com_name"   => $mycname,
                  "trans_date"        => date("Y-m-d g:i A"), //Transaction date
                  "company_phone"     => $mycphone,
                  "staff_email"       => $em, //Vendor / Merchant Name
                  "amount"            => $icurrency.number_format($amount,2,'.',','), //Amount to move to transfer balance
                  "available_bal"     => $icurrency.number_format($iassigned_walletbal,2,'.',','), //Available balance in wallet
                  "remarks"           => $remark, //Remark
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