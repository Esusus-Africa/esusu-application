<?php              
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'bvn'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;
    
    $url = $api_url.$bvn.'?seckey='.$rave_secret_key;
          
    $get_data = callAPI('GET', $url, false);
    $result = json_decode($get_data, true);
    
     //print_r($result);
         
    if($result['status'] == "success"){
        
        $wbalance = $bwallet_balance - $bvn_fee;
        
        $icm_id = "ICM".time();
        $exp_id = "EXP".time();
        $myOtp = substr((uniqid(rand(),1)),3,6);
        $rOrderID = "EA-bvnCharges-".time();
        
        $date_time = date("Y-m-d");
        $wallet_date_time = date("Y-m-d H:i:s");
        //substr()
        $bvn_fname = $result['data']['first_name'];
        $bvn_lname = $result['data']['last_name'];
        $bvn_dob = date("Y-m-d", strtotime($result['data']['date_of_birth']));
        $bvn_phone = "+234".substr($result['data']['phone_number'],-10);
        $correct_bvnPhone = $result['data']['phone_number'];
        
        $search_bvnverify = mysqli_query($link, "SELECT * FROM bvn_confirmation WHERE otp_code = '$acctno'");
        $bvn_nos = mysqli_num_rows($search_bvnverify);
        
        $seach_membersttings = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
        $fetch_memset = mysqli_fetch_array($seach_membersttings);
        
        //include("alert_sender/bvn_otp.php");
        
        $update_wallet = mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$wbalance' WHERE account = '$acctno'");
        
        ($bvn_nos == 1) ? $update_bvn = mysqli_query($link, "UPDATE bvn_confirmation SET fname = '$bvn_fname', lname = '$bvn_lname', dob = '$bvn_dob', phone = '$bvn_phone', bvn = '$bvn', datetime = '$wallet_date_time' WHERE otp_code = '$acctno'") : $insert_bvn = mysqli_query($link, "INSERT INTO bvn_confirmation VALUE(null,'$bvn_fname','$bvn_lname','$bvn_dob','$bvn_phone','$bvn','$acctno','$wallet_date_time')");
        
        $insert_income = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$bbranchid','$rOrderID','$acctno','','$bvn_fee','Debit','$bbcurrency','BVN_Charges','Response: $bbcurrency.$bvn_fee was charged for Customer BVN Verification','successful','$wallet_date_time','$acctno','$wbalance','')");
        
        $insert_income = mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','BVN','$bvn_fee','$date_time','Customer BVN Verification Charges')");
        
        ((($bvn_lname == $lname || $bvn_lname == strtoupper($lname)) && $bvn_dob == $dob && $bvn_phone == $phone) || (($bvn_lname == $lname || $bvn_lname == strtoupper($lname)) && $bvn_phone == $phone) || (($bvn_lname == $lname || $bvn_lname == strtoupper($lname)) && $bvn_dob == $dob) || ($bvn_dob == $dob && $bvn_phone == $phone)) ? mysqli_query($link, "UPDATE borrowers SET unumber = '$bvn' WHERE account = '$acctno'") : "";
        
        /*$message = "First Name: $bvn_fname ".(($bvn_fname == $fname) ? '<p style="color: blue;"><b>Data Matched with First Name in Database</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Not-Matched with First Name in Database</b> <i class="fa fa-times"></i></p>');
        $message .= "Last Name: $bvn_lname ".(($bvn_lname == $lname) ? '<p style="color: blue;"><b>Data Matched with Last Name in Database</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Not-Matched with Last Name in Database</b> <i class="fa fa-times"></i></p>');
        $message .= "Date of Birth: $bvn_dob ".(($bvn_dob == $dob) ? '<p style="color: blue;"><b>Data Matched with Date of Birth in Database</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Not-Matched with Date of Birth in Database</b> <i class="fa fa-times"></i></p>');
        $message .= "Phone Number: $correct_bvnPhone ".(($bvn_phone == $phone) ? '<p style="color: blue;"><b>Data Matched with Phone Number in Database</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Not-Matched with Phone Number in Database</b> <i class="fa fa-times"></i></p>');*/
        
        echo ((($bvn_lname == $lname || $bvn_lname == strtoupper($lname)) && $bvn_dob == $dob && $bvn_phone == $phone) || (($bvn_lname == $lname || $bvn_lname == strtoupper($lname)) && $bvn_phone == $phone) || (($bvn_lname == $lname || $bvn_lname == strtoupper($lname)) && $bvn_dob == $dob) || ($bvn_dob == $dob && $bvn_phone == $phone)) ? '<p style="color: blue;"><b>Data Verified Successfully!</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Verification Not Successful! Please try with correct BVN that Match your Information on our System</b> <i class="fa fa-times"></i></p>';
        
        echo '<meta http-equiv="refresh" content="3;url=docManager.php?tid='.$_SESSION['tid'].'&&acn='.$_SESSION['acctno'].'&&mid='.base64_encode("950").'&&tab=tab_2">';
     }
     else{
           echo "<br><span class='bg-orange'>Oops! Network Error, please try again later </span>";
     }

?>