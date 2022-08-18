<?php
/*include("../config/connect.php");

$date = date("Y-m-d");
$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE acct_status = 'Closed' AND last_withdraw_date = '$date'");
while($fetch_cust = mysqli_fetch_array($search_customer)){

    $id = $fetch_cust['id'];
    $snum = $fetch_cust['snum'];
    $fname = $fetch_cust['fname'];
    $lname = $fetch_cust['lname'];
    $mname = $fetch_cust['mname'];
    $email = $fetch_cust['email'];
    $phone = $fetch_cust['phone'];
    $gender = $fetch_cust['gender'];
    $dob = $fetch_cust['dob'];
    $occupation = $fetch_cust['occupation'];
    $addrs = $fetch_cust['addrs'];
    $city = $fetch_cust['city'];
    $state = $fetch_cust['state'];
    $zip = $fetch_cust['zip'];
    $country = $fetch_cust['country'];
    $nok = $fetch_cust['nok'];
    $nok_rela = $fetch_cust['nok_rela'];
    $nok_phone = $fetch_cust['nok_phone'];
    $community_role = $fetch_cust['community_role'];
    $account = $fetch_cust['account'];
    $username = $fetch_cust['username'];
    $password = $fetch_cust['password'];
    $balance = $fetch_cust['balance'];
    $investment_bal = $fetch_cust['investment_bal'];
    $image = $fetch_cust['image'];
    $date_time = $fetch_cust['date_time'];
    $last_withdraw_date = $fetch_cust['last_withdraw_date'];
    $status = $fetch_cust['status'];
    $lofficer = $fetch_cust['lofficer'];
    $c_sign = $fetch_cust['c_sign'];
    $branchid = $fetch_cust['branchid'];
    $sbranchid = $fetch_cust['sbranchid'];
    $acct_status = $fetch_cust['acct_status'];
    
    $s_contribution_interval = $fetch_cust['s_contribution_interval'];
    $savings_amount = $fetch_cust['savings_amount'];
    $charge_interval = $fetch_cust['charge_interval'];
    $chargesAmount = $fetch_cust['chargesAmount'];
    $disbursement_interval = $fetch_cust['disbursement_interval'];
    $disbursement_channel = $fetch_cust['disbursement_channel'];
    $auto_disbursement_status = $fetch_cust['auto_disbursement_status'];
    $auto_charge_status = $fetch_cust['auto_charge_status'];
    $next_charges_date = $fetch_cust['next_charges_date'];
    $next_disbursement_date = $fetch_cust['next_disbursement_date'];
    $recipient_id = $fetch_cust['recipient_id'];
    
    $opt_option = $fetch_cust['opt_option'];
    $currency = $fetch_cust['currency'];
    $wallet_balance = $fetch_cust['wallet_balance'];
    $overdraft = $fetch_cust['overdraft'];
    $card_id = $fetch_cust['card_id'];
    $card_reg = $fetch_cust['card_reg'];
    $card_issurer = $fetch_cust['card_issurer'];
    $tpin = $fetch_cust['tpin'];
    $reg_type = $fetch_cust['reg_type'];
    $gname = $fetch_cust['gname'];
    $gposition = $fetch_cust['gposition'];
    $acct_type = $fetch_cust['acct_type'];
    $expected_fixed_balance = $fetch_cust['expected_fixed_balance'];
    $acct_opening_date = $fetch_cust['acct_opening_date'];
    $unumber = $fetch_cust['unumber'];
    $verve_expiry_date = $fetch_cust['verve_expiry_date'];
    $employer = $fetch_cust['employer'];
    $virtual_number = $fetch_cust['virtual_number'];
    $virtual_acctno = $fetch_cust['virtual_acctno'];
    $bankname = $fetch_cust['bankname'];
    $dedicated_ussd_prefix = $fetch_cust['dedicated_ussd_prefix'];
    $evn = $fetch_cust['evn'];

    mysqli_query($link, "INSERT INTO dumped_borrowers VALUES(null,'$snum','$fname','$lname','$mname','$email','$phone','$gender','$dob','$occupation','$addrs','$city','$state','$zip','$country','$nok','$nok_rela','$nok_phone','$community_role','$account','$username','$password','$balance','$investment_bal','$image','$date_time','$last_withdraw_date','$status','$lofficer','$c_sign','$branchid','$sbranchid','$acct_status','$s_contribution_interval','$savings_amount','$charge_interval','$chargesAmount','$disbursement_interval','$disbursement_channel','$auto_disbursement_status','$auto_charge_status','$next_charges_date','$next_disbursement_date','$recipient_id','$opt_option','$currency','$wallet_balance','$overdraft','$card_id','$card_reg','$card_issurer','$tpin','$reg_type','$gname','$gposition','$acct_type','$expected_fixed_balance','$acct_opening_date','$unumber','$verve_expiry_date','$employer','$virtual_number','$virtual_acctno','$bankname','$dedicated_ussd_prefix','$evn')");
    
    mysqli_query($link, "UPDATE virtual_account SET status = 'DEACTIVATED' WHERE userid = '$account'");
    
    mysqli_query($link, "DELETE FROM borrowers WHERE id = '$id'");

}*/
?>