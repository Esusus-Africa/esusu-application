<?php

include("../config/connect.php");

function myreference($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}

$searchQuery = mysqli_query($link, "SELECT * FROM wallet_due_loan");
while($fetchQuery = mysqli_fetch_array($searchQuery)){

    $refid = uniqid().myreference(10);
    $lid = $fetchQuery['lid'];
    $account_no = $fetchQuery['tid'];
    $pay_date = date("Y-m-d");
    $amount_to_pay = preg_replace('/[^0-9.]/', '', $fetchQuery['dueAmount']);
    $mycurrentTime = date("Y-m-d h:i:s");

    $searchVAWN = mysqli_query($link, "SELECT * FROM virtual_account WHERE account_number = '$account_no'");
    $fetchVAWN = mysqli_fetch_array($searchVAWN);
    $userid = $fetchVAWN['userid'];
    $customer = $fetchVAWN['account_name'];

    $search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$userid'");
    $sRowNum = mysqli_num_rows($search_mystaff);
    $fetch_mystaff = mysqli_fetch_array($search_mystaff);

    $search_borro = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND account = '$userid'");
    $bRowNum = mysqli_num_rows($search_borro);
    $fetch_borro = mysqli_fetch_array($search_borro);

    $userType = ($sRowNum == 0 && $bRowNum == 1) ? "Customer" : "User";
    $uname = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['username'] : $fetch_mystaff['username'];
    $phone = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['phone'] : $fetch_mystaff['phone'];
    $em = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['email'] : $fetch_mystaff['email'];
    $sms_checker = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['sms_checker'] : "Yes";
    $walletBalance = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['wallet_balance'] : $fetch_mystaff['transfer_balance'];
    $alternateWBal = ($amount_to_pay > $walletBalance) ? $walletBalance : $amount_to_pay;
    $defaultLoanBal = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['loan_balance'] : $fetch_mystaff['loan_balance'];
    $loanBal = ($sRowNum == 0 && $bRowNum == 1) ? ($fetch_borro['loan_balance'] - $alternateWBal) : ($fetch_mystaff['loan_balance'] - $alternateWBal);
    $branch = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['branchid'] : $fetch_mystaff['created_by'];
    $sbranch = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['sbranchid'] : $fetch_mystaff['branchid'];

    $search_loaninfo = mysqli_query($link, "SELECT * FROM wallet_loan_history WHERE lid = '$lid'");
    $get_loaninfo = mysqli_fetch_array($search_loaninfo);
    $my_balance = number_format($get_loaninfo['balance'],2,'.','');
    $lofficer = $get_loaninfo['bookedBy'];

    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $sys_email = $r->email;
    
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    $sysabb = $fetch_memset['sender_id'];
    $our_currency = $fetch_memset['currency'];

    $status = "paid";
    $theStatus = "Approved";
    $channel = "Internal";
    $notification = "1";
    $checksms = "1";

    $searchDue = mysqli_query($link, "SELECT * FROM wallet_due_loan WHERE lid = '$lid'");
    $fetchDue = mysqli_fetch_array($searchDue);    

    if($amount_to_pay > $walletBalance){

        $newWalletBal = $walletBalance - $walletBalance;
        $final_bal = $my_balance - $walletBalance;
        $newdueAmount = $fetchDue['dueAmount'] - $walletBalance;

        //Deduct customer loan Balance and log payment record
        ($userType == "Customer") ? $update = mysqli_query($link, "UPDATE borrowers SET loan_balance = '$loanBal', wallet_balance = '$newWalletBal' WHERE account = '$userid'") : "";
        ($userType == "User") ? $update = mysqli_query($link, "UPDATE user SET loan_balance = '$loanBal', transfer_balance = '$newWalletBal' WHERE id = '$userid'") : "";
        $update = mysqli_query($link, "UPDATE wallet_loan_history SET balance = '$final_bal' WHERE lid = '$lid'") or die (mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO wallet_loan_repayment VALUES(null,'$lofficer','$lid','$refid','$account_no','$customer','$walletBalance','$final_bal','$pay_date','$status','$branch','$sbranch','$notification','$notification','$checksms')") or die (mysqli_error($link));
        mysqli_query($link, "UPDATE wallet_due_loan SET dueAmount = '$newdueAmount', balance = '$final_bal' WHERE lid = '$lid'");

        include('../cron/send_repayemail.php');
        
    }
    else{

        $final_bal = $my_balance - $amount_to_pay;
        $newWalletBal = $walletBalance - $amount_to_pay;

        //Deduct customer loan Balance and log payment record
        ($userType == "Customer") ? $update = mysqli_query($link, "UPDATE borrowers SET loan_balance = '$loanBal', wallet_balance = '$newWalletBal' WHERE account = '$userid'") : "";
        ($userType == "User") ? $update = mysqli_query($link, "UPDATE user SET loan_balance = '$loanBal', transfer_balance = '$newWalletBal' WHERE id = '$userid'") : "";
        $update = mysqli_query($link, "UPDATE wallet_loan_history SET balance = '$final_bal' WHERE lid = '$lid'") or die (mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO wallet_loan_repayment VALUES(null,'$lofficer','$lid','$refid','$account_no','$customer','$amount_to_pay','$final_bal','$pay_date','$status','$branch','$sbranch','$notification','$notification','$checksms')") or die (mysqli_error($link));
    
        include('../cron/send_repayemail.php');

        mysqli_query($link, "DELETE FROM wallet_due_loan WHERE lid = '$lid'");

    }

}

?>