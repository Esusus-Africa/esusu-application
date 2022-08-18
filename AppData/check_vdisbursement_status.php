<?php

include("../config/connect.php");

function callAPI($method, $url, $data){
    $curl = curl_init();
 
    switch ($method){
       case "POST":
          curl_setopt($curl, CURLOPT_POST, 1);
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          break;
       case "PUT":
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
          break;
       default:
          if ($data)
             $url = sprintf("%s?%s", $url, http_build_query($data));
    }
 
    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'content-type: application/json'
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
 
    // EXECUTE:
    $result = curl_exec($curl);
    if(curl_error($curl)){
         echo 'error:' . curl_error($curl);
     }
    curl_close($curl);
    return $result;
 }
 
 function myreference($limit)
 {
     return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
 }

// Function to check string starting 
// with given substring 
function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}

 $result = array();

 $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'transfer_status'");
 $fetch_restapi = mysqli_fetch_object($search_restapi);
 $api_url = $fetch_restapi->api_url;

 $search_transfer = mysqli_query($link, "SELECT * FROM disbursement_history WHERE status = 'NEW' OR status = 'PENDING'");
 while($fetch_trasfer = mysqli_fetch_array($search_transfer))
 {
    $userid = $fetch_trasfer['userid'];
    $transfer_id = $fetch_trasfer['transfer_id'];
    $tamount = $fetch_trasfer['amount'];
    $surcharges = $fetch_trasfer['transfers_fee'];
    $created_by = $fetch_trasfer['created_by'];
    $sub_id = $fetch_trasfer['subid'];
    
    $systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    
    $search_vendor = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$userid'");
    $fetch_vendor = mysqli_fetch_object($search_vendor);
    
    $search_memberset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$userid'");
    $fetch_memberset = mysqli_fetch_object($search_memberset);
    
    $seckey = (startsWith($userid,"VEND") ? $fetch_vendor->rave_secret_key : (startsWith($userid,"MER") || startsWith($userid,"INST") ? $fetch_memberset->rave_secret_key : $row1->secret_key));
    
    //$transfer_charges = $row1->transfer_charges;
    
    $api_link = $api_url.'?seckey='.$seckey.'&&id='.$transfer_id;
   
    if(startsWith($userid,"VEND")){

        $get_data = callAPI('GET', $api_link, false);
        $result = json_decode($get_data, true);

        if($result['status'] == 'success'){

            foreach($result['data']['transfers'] as $key){
                    $tstatus = $key['status'];
                    $details = "Gateway Fee: ".$key['currency'].$key['fee'];
                    $narration = "Narration: ".(($key['narration'] == "") ? "NULL" : $key['narration']);
                    $narration .= " Gateway Response: ".$key['complete_message'];
                    
                    $search_merch = mysqli_query($link, "SELECT * FROM merchant_reg WHERE merchantID = '$created_by'");
                    $fetch_merch = mysqli_fetch_object($search_merch);
                    
                    $my_mbal = $fetch_merch->wallet_balance;
                    $my_vbal = $fetch_vendor->wallet_balance;
                    
                    $vtotal_bal = $my_vbal - $surcharges;
                    $mtotal_bal = $my_mbal + $surcharges;
                    
                    $income_id = "ICM".rand(100000,999999);
                    $income_date = date("Y-m-d");
                    
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "UPDATE merchant_reg SET wallet_balance = '$mtotal_bal' WHERE merchantID = '$created_by'") : '';
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "UPDATE vendor_reg SET wallet_balance = '$vtotal_bal' WHERE companyid = '$userid'") : '';
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "UPDATE savings_subscription SET status = 'Disabled' WHERE id = '$sub_id'") : '';
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "UPDATE mcustomer_wrequest SET status = 'Approved' WHERE bank_details = '$sub_id'") : '';
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "INSERT INTO income VALUES(null,'$created_by','$income_id','Surcharges','$surcharges','$income_date','Disbursement Revenue')") : '';
                    mysqli_query($link, "UPDATE disbursement_history SET transfers_fee = '$details', status = '$tstatus', narration = '$narration' WHERE userid = '$userid' AND transfer_id = '$transfer_id'");
            }

        }

    }
    /*if(startsWith($userid,"MER")){

        $get_data = callAPI('GET', $api_link, false);
        $result = json_decode($get_data, true);

        if($result['status'] == 'success'){

            foreach($result['data']['transfers'] as $key){
                    $tstatus = $key['status'];
                    $details = "Gateway Fee: ".$key['currency'].$key['fee'];
                    $narration = "Narration: ".(($key['narration'] == "") ? "NULL" : $key['narration']);
                    $narration .= " Gateway Response: ".$key['complete_message'];
                    
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "UPDATE savings_subscription SET status = 'Disabled' WHERE id = '$sub_id'") : '';
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "UPDATE mcustomer_wrequest SET status = 'Approved' WHERE bank_details = '$sub_id'") : '';
                    mysqli_query($link, "UPDATE disbursement_history SET transfers_fee = '$details', status = '$tstatus', narration = '$narration' WHERE userid = '$userid' AND transfer_id = '$transfer_id'");
            }

        }

    }*/
    if(startsWith($userid,"INST") || startsWith($userid,"MER")){

        $get_data = callAPI('GET', $api_link, false);
        $result = json_decode($get_data, true);

        if($result['status'] == 'success'){

            foreach($result['data']['transfers'] as $key){
                    $tstatus = $key['status'];
                    $transferfee = $key['fee'];
                    $tprofit = $transfer_charges - $transferfee;
                    $tpercent = $transferfee + $tamount;
                    $details = "Gateway Fee: ".$key['currency'].($key['fee'] + $tprofit);
                    $narration = "Narration: ".(($key['narration'] == "") ? "NULL" : $key['narration']);
                    $narration .= " Gateway Response: ".$key['complete_message'];
    
                    $search_ibal = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$userid'");
                    $fetch_ibal = mysqli_fetch_array($search_ibal);
                    $my_ibal = $fetch_ibal['wallet_balance'];
                    
                    $total_bal = $my_ibal - $tpercent - $tprofit;
                    $income_id = "ICM".rand(100000,999999);
                    $income_date = date("Y-m-d");
                    
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$total_bal' WHERE institution_id = '$userid'") : '';
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "INSERT INTO income VALUES(null,'','$income_id','Revenue','$tprofit','$income_date','Transfer Revenue')") : '';
                    mysqli_query($link, "UPDATE transfer_history SET transfers_fee = '$details', status = '$tstatus', narration = '$narration' WHERE userid = '$userid' AND transfer_id = '$transfer_id'");
            }
                
        }

    }

 }

?>