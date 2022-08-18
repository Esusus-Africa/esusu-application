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

 $systemset = mysqli_query($link, "SELECT * FROM systemset");
 $row1 = mysqli_fetch_object($systemset);
 $seckey = $row1->secret_key;
 $transfer_charges = $row1->transfer_charges;

 $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'transfer_status'");
 $fetch_restapi = mysqli_fetch_object($search_restapi);
 $api_url = $fetch_restapi->api_url;

 $search_transfer = mysqli_query($link, "SELECT * FROM transfer_history WHERE status = 'NEW' OR status = 'PENDING'");
 while($fetch_trasfer = mysqli_fetch_array($search_transfer))
 {
    $userid = $fetch_trasfer['userid'];
    $transfer_id = $fetch_trasfer['transfer_id'];
    $tamount = $fetch_trasfer['amount'];
    $api_link = $api_url.'?seckey='.$seckey.'&&id='.$transfer_id;
   
    if($userid == ""){
   
       $get_data = callAPI('GET', $api_link, false);
       $result = json_decode($get_data, true);

       //print_r($result);
   
       if($result['status'] == 'success'){
   
           foreach($result['data']['transfers'] as $key){
               $tstatus = $key['status'];
               $transferfee = $key['fee'];
               $tpercent = $transferfee + $amount;
               $details = "Gateway Fee: ".$key['currency'].$transferfee;
               $narration = "Narration: ".(($key['narration'] == "") ? "NULL" : $key['narration']);
               $narration .= " Gateway Response: ".$key['complete_message'];
       
               mysqli_query($link, "UPDATE transfer_history SET transfers_fee = '$details', status = '$tstatus', narration = '$narration' WHERE userid = '' AND transfer_id = '$transfer_id'");
           }
              
       }
    
    }
    if(startsWith($userid,"INST") || startsWith($userid,"AGT") || startsWith($userid,"MER")){

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
    if(startsWith($userid,"COOP")){

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
    
                    $search_cbal = mysqli_query($link, "SELECT * FROM cooperatives WHERE coopid = '$userid'");
                    $fetch_cbal = mysqli_fetch_array($search_cbal);
                    $my_cbal = $fetch_cbal['wallet_balance'];
                    
                    $total_bal = $my_cbal - $tamount - $tprofit;
                    $income_id = "ICM".rand(100000,999999);
                    $income_date = date("Y-m-d");
                    
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "UPDATE cooperatives SET wallet_balance = '$total_bal' WHERE coopid = '$userid'") : '';
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "INSERT INTO income VALUES(null,'','$income_id','Revenue','$tprofit','$income_date','Transfer Revenue')") : '';
                    mysqli_query($link, "UPDATE transfer_history SET transfers_fee = '$details', status = '$tstatus', narration = '$narration' WHERE userid = '$userid' AND transfer_id = '$transfer_id'");                
            }

        }

    }
    if(startsWith($userid,"AGGR")){

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
    
                    $search_aggbal = mysqli_query($link, "SELECT * FROM aggregator WHERE aggr_id = '$userid'");
                    $fetch_aggbal = mysqli_fetch_array($search_aggbal);
                    $my_aggbal = $fetch_aggbal['wallet_balance'];
                    
                    $total_bal = $my_aggbal - $tamount - $tprofit;
                    $income_id = "ICM".rand(100000,999999);
                    $income_date = date("Y-m-d");
                    
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "UPDATE aggregator SET wallet_balance = '$total_bal' WHERE aggr_id = '$userid'") : '';
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "INSERT INTO income VALUES(null,'','$income_id','Revenue','$tprofit','$income_date','Transfer Revenue')") : '';
                    mysqli_query($link, "UPDATE transfer_history SET transfers_fee = '$details', status = '$tstatus', narration = '$narration' WHERE userid = '$userid' AND transfer_id = '$transfer_id'");    
            }

        }

    }
    if(startsWith($userid,"REV")){

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
    
                    $search_rbal = mysqli_query($link, "SELECT * FROM revenue_data WHERE ogsaa_id = '$userid'");
                    $fetch_rbal = mysqli_fetch_array($search_rbal);
                    $my_rbal = $fetch_rbal['wallet_balance'];
                    
                    $total_bal = $my_rbal - $tamount - $tprofit;
                    $income_id = "ICM".rand(100000,999999);
                    $income_date = date("Y-m-d");
                    
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "UPDATE revenue_data SET wallet_balance = '$total_bal' WHERE ogsaa_id = '$userid'") : '';
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "INSERT INTO income VALUES(null,'','$income_id','Revenue','$tprofit','$income_date','Transfer Revenue')") : '';
                    mysqli_query($link, "UPDATE transfer_history SET transfers_fee = '$details', status = '$tstatus', narration = '$narration' WHERE userid = '$userid' AND transfer_id = '$transfer_id'");             
            }

        }

    }
    if(!(startsWith($userid,"AGT") && startsWith($userid,"INST") && startsWith($userid,"MER") && startsWith($userid,"COOP")) && ($userid != "")){

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
    
                    $search_bbal = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$userid'");
                    $fetch_bbal = mysqli_fetch_array($search_bbal);
                    $my_bbal = $fetch_bbal['wallet_balance'];
                    
                    $total_bal = $my_bbal - $tamount - $tprofit;
                    $income_id = "ICM".rand(100000,999999);
                    $income_date = date("Y-m-d");
                    
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$total_bal' WHERE account = '$userid'") : '';
                    ($tstatus != 'FAILED' && $tstatus != 'PENDING') ? mysqli_query($link, "INSERT INTO income VALUES(null,'','$income_id','Revenue','$tprofit','$income_date','Transfer Revenue')") : '';
                    mysqli_query($link, "UPDATE transfer_history SET transfers_fee = '$details', status = '$tstatus', narration = '$narration' WHERE userid = '$userid' AND transfer_id = '$transfer_id'");
            }

        }

    }

 }

?>