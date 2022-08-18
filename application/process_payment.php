<?php 
error_reporting(0);
include "../config/session.php"; 
?>  

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
    <div class="loader"></div>
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
$refid = myreference(10);
$loan_bal = mysqli_real_escape_string($link, $_POST['loan']);
$pay_date = mysqli_real_escape_string($link, $_POST['pay_date']);
$amount_to_pay = mysqli_real_escape_string($link, $_POST['amount_to_pay']);
$remarks = mysqli_real_escape_string($link, $_POST['remarks']);
//$ptime = mysqli_real_escape_string($link, $_POST['ptime']);

$account_no = mysqli_real_escape_string($link, $_POST['account']);
$searchin = mysqli_query($link, "SELECT * FROM borrowers WHERE account ='$account_no'");
$get_searchin = mysqli_fetch_array($searchin);
$customer = $get_searchin['fname'].' '.$get_searchin['lname'];
$uname = $get_searchin['username'];
$phone = $get_searchin['phone'];
$em = $get_searchin['email'];

$search_loaninfo = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid' AND p_status != 'PAID'");
$get_loaninfo = mysqli_fetch_array($search_loaninfo);
//$final_amount = $get_loaninfo['amount_topay'];
$my_balance = $get_loaninfo['balance'];

$search4 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE id = '$loan_bal' AND status = 'UNPAID' ORDER BY id ASC") or die (mysqli_error($link));
$get_search4 = mysqli_fetch_array($search4);
$get_id = $get_search4['get_id'];
$cust_aact = $get_search4['tid'];
$date_time = date("Y-m-d");
$original_bal = $get_search4['balance'];
$expected_pay = $get_search4['payment'];

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

if($original_bal == "0" && $amount_to_pay == $expected_pay)
{
    $final_bal = $my_balance - $amount_to_pay;
    $update = mysqli_query($link, "UPDATE loan_info SET balance = '0', p_status = 'PAID' WHERE lid = '$lid'") or die (mysqli_error($link));
    $insert = mysqli_query($link, "INSERT INTO payments VALUES(null,'$tid','$lid','$refid','$account_no','$customer','$final_bal','$pay_date','$amount_to_pay','paid','')") or die (mysqli_error($link));
    
    $search_recent_payment = mysqli_query($link, "SELECT * FROM payments WHERE lid = '$lid' ORDER BY id DESC");
    $fetch_recent_payment = mysqli_fetch_array($search_recent_payment);
    $pid = $fetch_recent_payment['id'];
    
    $update = mysqli_query($link, "UPDATE pay_schedule SET pid = '$pid', status = 'PAID' WHERE id = '$loan_bal'") or die (mysqli_error($link));
    
    $verify_role = mysqli_query($link, "SELECT * FROM till_account WHERE cashier = '$iuid'");
    if(mysqli_num_rows($verify_role) == 1){
        $fetch_role = mysqli_fetch_object($verify_role);
		$balance = $fetch_role->balance;
		$commission = $fetch_role->commission;
		$commission_bal = $fetch_role->commission_balance;
		
		//Calculate Commission Earn By the Staff
		$cal_commission = ($commission / 100) * $amount;
			
		//Update Default Commission Balance
		$total_commission_bal = ($commission == 0) ? $commission_bal : $cal_commission + $commission_bal;
		
		$update = mysqli_query($link, "UPDATE till_account SET commission_balance = '$total_commission_bal' WHERE cashier = '$iuid'");
		
    }else{
        echo "";
    }
    
    $sms = "$sysabb>>>Dear $customer! Your repayment of ".$icurrency.number_format($amount_to_pay,2,'.',',')." has been initiated successfully. ";
    $sms .= "Your Loan Balance is: ".$icurrency.number_format($final_bal,2,'.',',')." Thanks.";
    
    if(!($update && $insert))
    {
        echo '<meta http-equiv="refresh" content="2;url=newpayments.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
        echo '<br>';
        echo'<span class="itext" style="color: #FF0000">Unable to payment records.....Please try again later!</span>';
    }
    else{
        include('../cron/send_general_sms.php');
		include('../cron/send_repayemail.php');
        echo '<meta http-equiv="refresh" content="2;url=listpayment.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
        echo '<br>';
        echo'<span class="itext" style="color: #FF0000">Saving Payment.....Please Wait!</span>';
    }
}
elseif($original_bal == "0" && $amount_to_pay < $expected_pay)
{
    $new_obal = $expected_pay - $amount_to_pay;
    $final_bal = $my_balance - $amount_to_pay;
    $update = mysqli_query($link, "UPDATE loan_info SET balance = '$final_bal', p_status = 'PART-PAID' WHERE lid = '$lid'") or die (mysqli_error($link));
    $insert = mysqli_query($link, "INSERT INTO payments VALUES(null,'$tid','$lid','$refid','$account_no','$customer','$final_bal','$pay_date','$amount_to_pay','paid','')") or die (mysqli_error($link));
    
    $search_recent_payment = mysqli_query($link, "SELECT * FROM payments WHERE lid = '$lid' ORDER BY id DESC");
    $fetch_recent_payment = mysqli_fetch_array($search_recent_payment);
    $pid = $fetch_recent_payment['id'];
    
    $update = mysqli_query($link, "UPDATE pay_schedule SET payment = '$new_obal' WHERE id = '$loan_bal'") or die (mysqli_error($link));
    
    $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$get_id','$cust_aact','$pid','$date_time','$final_bal','$amount_to_pay','PAID','')") or die (mysqli_error($link));
    
    $verify_role = mysqli_query($link, "SELECT * FROM till_account WHERE cashier = '$iuid'");
    if(mysqli_num_rows($verify_role) == 1){
        $fetch_role = mysqli_fetch_object($verify_role);
		$balance = $fetch_role->balance;
		$commission = $fetch_role->commission;
		$commission_bal = $fetch_role->commission_balance;
		
		//Calculate Commission Earn By the Staff
		$cal_commission = ($commission / 100) * $amount;
			
		//Update Default Commission Balance
		$total_commission_bal = ($commission == 0) ? $commission_bal : $cal_commission + $commission_bal;
		
		$update = mysqli_query($link, "UPDATE till_account SET commission_balance = '$total_commission_bal' WHERE cashier = '$iuid'");
		
    }else{
        echo "";
    }
    
    $sms = "$sysabb>>>Dear $customer! Your repayment of ".$icurrency.number_format($amount_to_pay,2,'.',',')." has been initiated successfully. ";
    $sms .= "Your Loan Balance is: ".$icurrency.number_format($final_bal,2,'.',',')." Thanks.";
    
    if(!($update && $insert))
    {
        echo '<meta http-equiv="refresh" content="2;url=newpayments.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
        echo '<br>';
        echo'<span class="itext" style="color: #FF0000">Unable to payment records.....Please try again later!</span>';
    }
    else{
        include('../cron/send_general_sms.php');
		include('../cron/send_repayemail.php');
        echo '<meta http-equiv="refresh" content="2;url=listpayment.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
        echo '<br>';
        echo'<span class="itext" style="color: #FF0000">Saving Payment.....Please Wait!</span>';
    }
}
elseif($original_bal != "0" && $amount_to_pay == $expected_pay){
    $final_bal = $my_balance - $amount_to_pay;
    $update = mysqli_query($link, "UPDATE loan_info SET balance = '$final_bal', p_status = 'PART-PAID' WHERE lid = '$lid'") or die (mysqli_error($link));
    $insert = mysqli_query($link, "INSERT INTO payments VALUES(null,'$tid','$lid','$refid','$account_no','$customer','$final_bal','$pay_date','$amount_to_pay','paid','')") or die (mysqli_error($link));
    
    $search_recent_payment = mysqli_query($link, "SELECT * FROM payments WHERE lid = '$lid' ORDER BY id DESC");
    $fetch_recent_payment = mysqli_fetch_array($search_recent_payment);
    $pid = $fetch_recent_payment['id'];
    
    $update = mysqli_query($link, "UPDATE pay_schedule SET pid = '$pid', status = 'PAID' WHERE id = '$loan_bal'") or die (mysqli_error($link));
    
    $verify_role = mysqli_query($link, "SELECT * FROM till_account WHERE cashier = '$iuid'");
    if(mysqli_num_rows($verify_role) == 1){
        $fetch_role = mysqli_fetch_object($verify_role);
		$balance = $fetch_role->balance;
		$commission = $fetch_role->commission;
		$commission_bal = $fetch_role->commission_balance;
		
		//Calculate Commission Earn By the Staff
		$cal_commission = ($commission / 100) * $amount;
			
		//Update Default Commission Balance
		$total_commission_bal = ($commission == 0) ? $commission_bal : $cal_commission + $commission_bal;
		
		$update = mysqli_query($link, "UPDATE till_account SET commission_balance = '$total_commission_bal' WHERE cashier = '$iuid'");
		
    }else{
        echo "";
    }
    
    $sms = "$sysabb>>>Dear $customer! Your repayment of ".$icurrency.number_format($amount_to_pay,2,'.',',')." has been initiated successfully. ";
    $sms .= "Your Loan Balance is: ".$icurrency.number_format($final_bal,2,'.',',')." Thanks.";
    
    if(!($update && $insert))
    {
        echo '<meta http-equiv="refresh" content="2;url=newpayments.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
        echo '<br>';
        echo'<span class="itext" style="color: #FF0000">Unable to payment records.....Please try again later!</span>';
    }
    else{
        include('../cron/send_general_sms.php');
		include('../cron/send_repayemail.php');
        echo '<meta http-equiv="refresh" content="2;url=listpayment.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
        echo '<br>';
        echo'<span class="itext" style="color: #FF0000">Saving Payment.....Please Wait!</span>';
    }
}
elseif($original_bal != "0" && $amount_to_pay < $expected_pay){
    //$new_obal = $original_bal - $amount_to_pay;
    
    $find_payschedule = mysqli_query($link, "SELECT * FROM pay_schedule WHERE payment = '$expected_pay' AND status = 'UNPAID' AND branchid = ''");
    $nums = mysqli_num_rows($find_payschedule);
    
    $total_selected_amt = $expected_pay * $nums;
    
    $amt_to_spreadover = $amount_to_pay / $nums;
    
    $new_pay = $expected_pay - $amt_to_spreadover;
    $final_bal = $my_balance - $amount_to_pay;
    $update = mysqli_query($link, "UPDATE loan_info SET balance = '$final_bal', p_status = 'PART-PAID' WHERE lid = '$lid'") or die (mysqli_error($link));
    $insert = mysqli_query($link, "INSERT INTO payments VALUES(null,'$tid','$lid','$refid','$account_no','$customer','$final_bal','$pay_date','$amount_to_pay','paid','')") or die (mysqli_error($link));
    
    $search_recent_payment = mysqli_query($link, "SELECT * FROM payments WHERE lid = '$lid' ORDER BY id DESC");
    $fetch_recent_payment = mysqli_fetch_array($search_recent_payment);
    $pid = $fetch_recent_payment['id'];
    
    $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$get_id','$cust_aact','$pid','$date_time','$final_bal','$amount_to_pay','PAID','')") or die (mysqli_error($link));
    $update = mysqli_query($link, "UPDATE pay_schedule SET payment = '$new_pay' WHERE lid = '$lid' AND status = 'UNPAID' AND branchid = ''") or die (mysqli_error($link));
    
    $verify_role = mysqli_query($link, "SELECT * FROM till_account WHERE cashier = '$iuid'");
    if(mysqli_num_rows($verify_role) == 1){
        $fetch_role = mysqli_fetch_object($verify_role);
		$balance = $fetch_role->balance;
		$commission = $fetch_role->commission;
		$commission_bal = $fetch_role->commission_balance;
		
		//Calculate Commission Earn By the Staff
		$cal_commission = ($commission / 100) * $amount;
			
		//Update Default Commission Balance
		$total_commission_bal = ($commission == 0) ? $commission_bal : $cal_commission + $commission_bal;
		
		$update = mysqli_query($link, "UPDATE till_account SET commission_balance = '$total_commission_bal' WHERE cashier = '$iuid'");
		
    }else{
        echo "";
    }
    
    $sms = "$sysabb>>>Dear $customer! Your repayment of ".$icurrency.number_format($amount_to_pay,2,'.',',')." has been initiated successfully. ";
    $sms .= "Your Loan Balance is: ".$icurrency.number_format($final_bal,2,'.',',')." Thanks.";
    
    if(!($update && $insert))
    {
        echo '<meta http-equiv="refresh" content="2;url=newpayments.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
        echo '<br>';
        echo'<span class="itext" style="color: #FF0000">Unable to payment records.....Please try again later!</span>';
    }
    else{
        include('../cron/send_general_sms.php');
		include('../cron/send_repayemail.php');
        echo '<meta http-equiv="refresh" content="2;url=listpayment.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
        echo '<br>';
        echo'<span class="itext" style="color: #FF0000">Saving Payment.....Please Wait!</span>';
    }
}
elseif($original_bal != "0" && $amount_to_pay > $expected_pay){
    
    $find_payschedule = mysqli_query($link, "SELECT * FROM pay_schedule WHERE payment = '$expected_pay' AND status = 'UNPAID' AND branchid = ''");
    $nums = mysqli_num_rows($find_payschedule);
    
    $total_selected_amt = $expected_pay * $nums;
    
    $amt_to_spreadover = $amount_to_pay / $nums;
    
    $final_bal = $my_balance - $amount_to_pay;
    
    $insert = mysqli_query($link, "INSERT INTO payments VALUES(null,'$tid','$lid','$refid','$account_no','$customer','$final_bal','$pay_date','$amount_to_pay','paid','')") or die (mysqli_error($link));
    
    $search_recent_payment = mysqli_query($link, "SELECT * FROM payments WHERE lid = '$lid' ORDER BY id DESC");
    $fetch_recent_payment = mysqli_fetch_array($search_recent_payment);
    $pid = $fetch_recent_payment['id'];
    
    while($update_amt = mysqli_fetch_array($find_payschedule)){
        
        $new_amt_shared = ($update_amt['payment'] - $amt_to_spreadover);
        
        if($amt_to_spreadover == $expected_pay){
            
            $update = mysqli_query($link, "UPDATE loan_info SET balance = '0', p_status = 'PAID' WHERE lid = '$lid'") or die (mysqli_error($link));
            $update_all = mysqli_query($link, "UPDATE pay_schedule SET pid = '$pid', status = 'PAID' WHERE payment = '$amt_to_spreadover' AND status = 'UNPAID' AND branchid = ''");
            
        }
        else{
            
            $update = mysqli_query($link, "UPDATE loan_info SET balance = '$final_bal', p_status = 'PART-PAID' WHERE lid = '$lid'") or die (mysqli_error($link));
            $update_all = mysqli_query($link, "UPDATE pay_schedule SET payment = '$new_amt_shared' WHERE payment = '$expected_pay' AND status = 'UNPAID' AND branchid = ''");
            
        }
        
    }
    if($total_selected_amt > $amount_to_pay){
        $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$get_id','$cust_aact','$pid','$date_time','$final_bal','$amount_to_pay','PAID','')") or die (mysqli_error($link));
    }
    
    $verify_role = mysqli_query($link, "SELECT * FROM till_account WHERE cashier = '$iuid'");
    if(mysqli_num_rows($verify_role) == 1){
        $fetch_role = mysqli_fetch_object($verify_role);
		$balance = $fetch_role->balance;
		$commission = $fetch_role->commission;
		$commission_bal = $fetch_role->commission_balance;
		
		//Calculate Commission Earn By the Staff
		$cal_commission = ($commission / 100) * $amount;
			
		//Update Default Commission Balance
		$total_commission_bal = ($commission == 0) ? $commission_bal : $cal_commission + $commission_bal;
		
		$update = mysqli_query($link, "UPDATE till_account SET commission_balance = '$total_commission_bal' WHERE cashier = '$iuid'");
		
    }else{
        echo "";
    }
    
    $sms = "$sysabb>>>Dear $customer! Your repayment of ".$icurrency.number_format($amount_to_pay,2,'.',',')." has been initiated successfully. ";
    $sms .= "Your Loan Balance is: ".$icurrency.number_format($final_bal,2,'.',',')." Thanks.";
    
    include('../cron/send_general_sms.php');
	include('../cron/send_repayemail.php');
    echo '<meta http-equiv="refresh" content="2;url=listpayment.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
    echo '<br>';
    echo'<span class="itext" style="color: #FF0000">Saving Payment.....Please Wait!</span>';
}
}
?>