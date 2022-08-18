<?php

include("../config/connect.php");

$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));    
$r = mysqli_fetch_object($query);
$sys_abb = $r->abb;
$sys_email = $r->email;

$search_duration = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE usage_status = 'Active'");
while($fetch_duration = mysqli_fetch_object($search_duration))
{
  $dfrom = $fetch_duration->duration_from;
  $dto = $fetch_duration->duration_to;
  $expired_grace = $fetch_duration->expiration_grace;
  $coopid_instid = $fetch_duration->coopid_instid;
  $sub_token = $fetch_duration->sub_token;

  $now = time(); // or your date as well
  $your_date = strtotime($dto);
    
  $datediff = $your_date - $now;
  $total_day = round($datediff / (60 * 60 * 24)) + 1;

  $durationLeft = (($total_day <= 5 && $total_day > 0) ? "ends in ".$total_day." day(s) time." : (($total_day == 0) ? "Expired Today" : "has Expired"));
  $actionWord = (($total_day <= 5 && $total_day > 0) ? "Top-up" : (($total_day == 0) ? "Reactivate" : "Reactivate"));

  if($total_day <= 5 && $total_day >= 0)
  {
    $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$coopid_instid'");
    $fetch_inst = mysqli_fetch_array($search_inst);
    $aggr_id = $fetch_inst['aggr_id'];
    $name = $fetch_inst['institution_name'];
    $instEmail = $fetch_inst['official_email'];
    
    $search_aggr = mysqli_query($link, "SELECT * FROM aggregator WHERE aggr_id = '$aggr_id'");
    $checkAggr = mysqli_num_rows($search_aggr);
    $aggrEmail = ($checkAggr['email'] == "") ? "" : ",".$checkAggr['email'];

    $searchSID = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$coopid_instid'");
    $fetchSID = mysqli_fetch_array($searchSID);
    $portalURL = "https://esusu.app/".$fetchSID['sender_id'];

    ($total_day == 0) ? mysqli_query($link, "UPDATE saas_subscription_trans SET usage_status = 'Expired' WHERE sub_token = '$sub_token'") : "";

    $emailReceiver = $instEmail.$aggrEmail;

    //include("../config/connect.php");
    $result = array();
    // Pass the customer's authorisation code, email and amount
    $postdata =  array(
          "From"          => $r->email_from,
          "To"            => $emailReceiver,  //Receiver EMail
          "TemplateId"    => '9576041',
          "TemplateModel" => [
          "product_name"      => $r->name,
          "duration_left"     => $durationLeft, //Duration left
          "logo_url"          => $r->logo_url,
          "product_url"       => $r->website,
          "action_word"       => $actionWord,
          "brand_name"        => $name,
          "portal_url"        => $portalURL,
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
  }
}
?>