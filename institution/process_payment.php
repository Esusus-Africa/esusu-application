<?php 
error_reporting(0);
include "../config/session1.php"; 
?>  

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">

<?php
if(isset($_POST['save']))
{
    function myreference($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
    $tid = $_SESSION['tid'];
    $name = mysqli_real_escape_string($link, $_POST['teller']);
    $lid =  $_POST['acte'];
    $refid = uniqid().myreference(10);
    $pay_date = mysqli_real_escape_string($link, $_POST['pay_date']);
    $amount_to_pay = mysqli_real_escape_string($link, $_POST['amount_to_pay']);
    $remarks = mysqli_real_escape_string($link, $_POST['remarks']);
    //$ptime = mysqli_real_escape_string($link, $_POST['ptime']);
    $mycurrentTime = date("Y-m-d h:i:s");
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);

    $account_no = mysqli_real_escape_string($link, $_POST['account']);
    $searchin = mysqli_query($link, "SELECT * FROM borrowers WHERE account ='$account_no'");
    $get_searchin = mysqli_fetch_array($searchin);
    $customer = $get_searchin['fname'].' '.$get_searchin['lname'];
    $uname = $get_searchin['username'];
    $phone = $get_searchin['phone'];
    $em = $get_searchin['email'];
    $sms_checker = $get_searchin['sms_checker'];

    $search_loaninfo = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid' AND p_status != 'PAID'");
    $get_loaninfo = mysqli_fetch_array($search_loaninfo);
    //$final_amount = $get_loaninfo['amount_topay'];
    $my_balance = $get_loaninfo['balance'];
    $request_id = $get_loaninfo['request_id'];
    $direct_debit_status = $get_loaninfo['direct_debit_status'];

    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    //$sys_abb = $r->abb;
    $sys_email = $r->email;
    
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    //$sys_abb = $get_sys['abb'];
    $sysabb = $fetch_memset['sender_id'];
    $our_currency = $fetch_memset['currency'];

    $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '$institution_id' AND status = 'Activated'");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;

    $billing_type = $ifetch_maintenance_model['billing_type'];
    $t_perc = $ifetch_maintenance_model['t_charges'];
    $myiwallet_balance = (mysqli_num_rows($isearch_maintenance_model) == 1 || $billing_type == "Hybrid") ? $iassigned_walletbal - $t_perc : "";

    $verify_role = mysqli_query($link, "SELECT * FROM till_account WHERE cashier = '$iuid' AND status = 'Active'");
    $numtill = mysqli_num_rows($verify_role);
    $fetch_role = mysqli_fetch_object($verify_role);
    $balance = $fetch_role->balance;
    $commissiontype = $fetch_role->commission_type;
    $commission = ($commissiontype == "Flat") ? $fetch_role->commission : ($fetch_role->commission/100);
    $commission_bal = $fetch_role->commission_balance;
            
    //Calculate Commission Earn By the Staff
    $cal_commission = $commission * $amount_to_pay;
    //Update Default Commission Balance
    $total_commission_bal = ($commission == 0) ? $commission_bal : $cal_commission + $commission_bal;
    //Update Till Balance after posting payment
    $total_tillbal_left = $balance - $amount_to_pay;

    $status = ($allow_auth == "Yes") ? "paid" : "pending";
    $theStatus = "Approved";
    $channel = "Internal";
    $notification = ($allow_auth == "Yes") ? "1" : "0";
    $checksms = ($sms_checker == "No") ? "0" : "1";

    if($tpin != $myiepin){

        echo '<meta http-equiv="refresh" content="2;url=newpayments.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
        echo '<br>';
        echo '<span class="itext" style="color: #FF0000">Opps!...Invalid Transaction Pin, please try again later!!</span>';

    }
    elseif($amount_to_pay > $my_balance){

        echo '<meta http-equiv="refresh" content="2;url=newpayments.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
        echo '<br>';
        echo '<span class="itext" style="color: #FF0000">Opps!...Amount to pay is invalid!!</span>';

    }
    elseif($amount_to_pay > $balance && $numtill == 1){

        echo '<meta http-equiv="refresh" content="2;url=newpayments.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
        echo '<br>';
        echo '<span class="itext" style="color: #FF0000">Opps!...Insufficient fund in till balance!!</span>';

    }
    else{
        $final_bal = $my_balance - $amount_to_pay;
        $p_status = ($final_bal <= 0) ? "PAID" : "PART-PAID";
        //Charge Hybrid or PayG User
        (mysqli_num_rows($isearch_maintenance_model) == 1 || $billing_type == "Hybrid") ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$txid','self','','$t_perc','Debit','$icurrency','Charges','Description: Service Charge for Repayment of $amount_to_pay','successful','$mycurrentTime','$iuid','$myiwallet_balance','')") : "";
        (mysqli_num_rows($isearch_maintenance_model) == 1 || $billing_type == "Hybrid") ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$institution_id'") : "";
        
        //Deduct customer loan Balance and log payment record
        ($allow_auth == "Yes") ? $update = mysqli_query($link, "UPDATE loan_info SET balance = '$final_bal', p_status = '$p_status' WHERE lid = '$lid'") or die (mysqli_error($link)) : "";
        $insert = mysqli_query($link, "INSERT INTO payments VALUES(null,'$tid','$lid','$refid','$account_no','$customer','$final_bal','$pay_date','$amount_to_pay','$status','$institution_id','','$isbranchid','$notification','$notification','$checksms')") or die (mysqli_error($link));
        
        //Balance Till account balance if exist
        ($numtill == 1 && $allow_auth == "Yes") ? $update = mysqli_query($link, "UPDATE till_account SET commission_balance = '$total_commission_bal', balance = '$total_tillbal_left' WHERE cashier = '$iuid'") : "";
        ($numtill == 1 && $allow_auth != "Yes") ? $update = mysqli_query($link, "UPDATE till_account SET balance = '$total_tillbal_left' WHERE cashier = '$iuid'") : "";

        //Sms notification draft
        $sms = "$sysabb>>>Dear $customer! Your repayment of ".$icurrency.number_format($amount_to_pay,2,'.',',')." with Loan ID: $lid has been initiated successfully. ";
        $sms .= "Your Loan Balance is: ".$icurrency.number_format($final_bal,2,'.',',')." Thanks.";
        
        if(!($update && $insert))
        {
            echo '<meta http-equiv="refresh" content="2;url=newpayments.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
            echo '<br>';
            echo'<span class="itext" style="color: #FF0000">Unable to payment records.....Please try again later!</span>';
        }
        else{
            ($sms_checker == "No" ? "" : ($allow_auth == "Yes" ? include('../cron/send_general_sms.php') : ""));
            ($sms_checker == "No" ? "" : ($allow_auth == "Yes" ? include('../cron/send_repayemail.php') : ""));
            echo '<meta http-equiv="refresh" content="2;url=listpayment.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
            echo '<br>';
            echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Customer Loan Balance is: <b style='color: orange;'>".$icurrency.number_format($final_bal,2,'.',',')."</b></p></div>";
        }
    }
}
?>