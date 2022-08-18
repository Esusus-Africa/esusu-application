<?php
//include("../config/connect.php");
$result = array();

$systemset = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
$r = mysqli_fetch_object($systemset);

// Pass the customer's authorisation code, email and amount
$postdata =  array(
              "From"          => $r->email_from,
              "To"            => $em,  //Borrower Email Address
              "TemplateId"    => '13352617',
              "TemplateModel" => [
                "refid"              => $refid, //Transaction ID
                "product_name"      => $r->name,
                "trans_date"        => $date_time, //Transaction date
                "loan_id"			=> $lid,
                "name"              => $uname,  //Borrower username
                "platform_name"     => $r->name, //Platform Name
                "acct_name"         => $customer, //Borrower Full Name
                "account_number"    => $account_no, //Borrower Account Number
                "amount"            => $our_currency.number_format($amount_to_pay,2,'.',','), //Amount Paid
                "loan_balance"     	=> $our_currency.number_format($final_bal,2,'.',','), //Loan Balance
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