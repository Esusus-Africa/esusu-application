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
		<div class="loader"></div>
<?php
if(isset($_POST['save_loan']))
{
include("../config/restful_apicalls.php");

$lproduct =  mysqli_real_escape_string($link, $_POST['lproduct']);
$borrower =  mysqli_real_escape_string($link, $_POST['borrower']);
$baccount = mysqli_real_escape_string($link, $_POST['account']);
$amount = mysqli_real_escape_string($link, $_POST['amount']);
$income_amt = mysqli_real_escape_string($link, $_POST['income']);
$salary_date = mysqli_real_escape_string($link, $_POST['salary_date']);
$employer =  mysqli_real_escape_string($link, $_POST['employer']);
//$date_release = mysqli_real_escape_string($link, $_POST['date_release']);
$agent = mysqli_real_escape_string($link, $_POST['agent']);
//$unumber = mysqli_real_escape_string($link, $_POST['unumber']);
$gname = mysqli_real_escape_string($link, $_POST['g_name']);
$gphone = mysqli_real_escape_string($link, $_POST['g_phone']);
//$g_unumber = mysqli_real_escape_string($link, $_POST['g_unumber']);
//$g_dob = mysqli_real_escape_string($link, $_POST['g_dob']);
//$g_bname = mysqli_real_escape_string($link, $_POST['g_bname']);

$g_rela = mysqli_real_escape_string($link, $_POST['grela']);
$g_address = mysqli_real_escape_string($link, $_POST['gaddress']);
$status = mysqli_real_escape_string($link, $_POST['status']);
//$remarks = mysqli_real_escape_string($link, $_POST['remarks']);
//$pay_date = mysqli_real_escape_string($link, $_POST['pay_date']);

//$calc_int = ($interest / 100) * $amount;
//$amount_topay = $amount + $calc_int;
$upstatus = "Pending";
//$teller = mysqli_real_escape_string($link, $_POST['teller']);
$lreasons = mysqli_real_escape_string($link, $_POST['lreasons']);

$search_interest = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
$get_interest = mysqli_fetch_object($search_interest);
$max_duration  = $get_interest->duration;
$interest_type = $get_interest->interest_type;
$interest = $get_interest->interest/100;
$tenor = $get_interest->tenor;

$amount_topay = ($interest == "0" || $interest_type == "Reducing Balance") ? $amount : ($amount * $interest) + $amount;

$target_dir = "../img/";
$target_file = $target_dir.basename($_FILES["image"]["name"]);
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$check = getimagesize($_FILES["image"]["tmp_name"]);

$id = $_SESSION['tid'];
$lid = 'LID-'.date("dmY").time();

$verify_outstandingloan = mysqli_query($link, "SELECT * FROM loan_info WHERE borrower = '$borrower'"); 
$fetch_loan = mysqli_fetch_array($verify_outstandingloan);
$p_status = $fetch_loan['p_status'];


$refid = "EA-loanBooking-".time();
$billing_type = $ifetch_maintenance_model['billing_type'];
$loan_booking = $ifetch_maintenance_model['loan_booking'];
$myiwallet_balance = $iassigned_walletbal - $loan_booking;

//PROCESS CUSTOMER BANK ACCOUNT FOR DISBURSEMENT
$account_number =  mysqli_real_escape_string($link, $_POST['acct_no']);
$bank_code =  mysqli_real_escape_string($link, $_POST['bank_code']);

$systemset = mysqli_query($link, "SELECT * FROM systemset");
$row1 = mysqli_fetch_object($systemset);
$seckey = $row1->secret_key;

$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'transferrecipient'");
$fetch_restapi = mysqli_fetch_object($search_restapi);
$api_url = $fetch_restapi->api_url;
		
// Pass the plan's name, interval and amount
$postdata =  array(
  'account_number'  => $account_number,
  'account_bank'    => $bank_code,
  'seckey'          => $seckey
);
  
$make_call = callAPI('POST', $api_url, json_encode($postdata));
$result = json_decode($make_call, true);

/*if($_FILES["image"]["size"] > 50000000)
{
	echo '<meta http-equiv="refresh" content="2;url=newloans.php?tid='.$_SESSION['tid'].'&&mid=NDA1">';
	echo '<br>';
	echo'<span class="itext" style="color: orange">Image must not more than 500KB!</span>';
}*/
/*elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
{
	echo '<meta http-equiv="refresh" content="2;url=newloans.php?tid='.$_SESSION['tid'].'&&mid=NDA1">';
	echo '<br>';
	echo'<span class="itext" style="color: orange">Sorry, only JPG, JPEG, PNG & GIF Files are allowed.</span>';
}*/
if($p_status == "UNPAID" || $p_status == "PART-PAID")
{
	echo '<meta http-equiv="refresh" content="2;url=newloans.php?tid='.$_SESSION['tid'].'&&mid=NDA1">';
	echo '<br>';
	echo'<span class="itext" style="color: orange">Sorry, The Customer Still Have Few Outstanding Loan to be Balanced.</span>';
}
elseif($iassigned_walletbal < $loan_booking && (mysqli_num_rows($isearch_maintenance_model) == 1 || $billing_type == "Hybrid")){
    echo '<meta http-equiv="refresh" content="2;url=newloans.php?tid='.$_SESSION['tid'].'&&mid=NDA1">';
	echo '<br>';
	echo'<span class="itext" style="color: orange">Sorry, You are unable to book for loan due to insufficient fund in your Wallet!!</span>';
}
/*elseif(($iassigned_walletbal >= $loan_booking) && (mysqli_num_rows($isearch_maintenance_model) == 1 || $billing_type == "Hybrid")){
    echo '<meta http-equiv="refresh" content="2;url=newloans.php?tid='.$_SESSION['tid'].'&&mid=NDA1">';
	echo '<br>';
	echo'<span class="itext" style="color: orange">Network Error.....Please try again later!</span>';
}*/
else{
    
    $sourcepath = $_FILES["image"]["tmp_name"];
	$targetpath = "../img/" . $_FILES["image"]["name"];
	move_uploaded_file($sourcepath,$targetpath);
	
	$location = "img/".$_FILES['image']['name'];
	$p_status = "UNPAID";
	
	$pschedule = mysqli_real_escape_string($link, $_POST['pschedule']);
	//$partial_pschedule = [$pschedule];
	
	$fetch_memset = $fetchsys_config['abb'];
	
	//Get the Recipient Id from Rav API
    $recipient_id = $result['data']['id'];
    //Get the Bank Name from Rav API
    $bank_name = $result['data']['bank_name'];
    //Get the Recipient Full Name From Rav API
    $fullname = $result['data']['fullname'];
	
	$bacinfo = "Bank Name: ".strtoupper($bank_name).", ";
	$bacinfo .= "Account Name: ".strtoupper($fullname).", ";
	$bacinfo .= "Account Number: ".$account_number;
	$wallet_date_time = date("Y-m-d H:i:s");

    $insert = mysqli_query($link, "INSERT INTO transfer_recipient VALUES(null,'$institution_id','$recipient_id','$fullname','$account_number','$bank_code','$bank_name',NOW(),'$uid','$branchid')") or die ("Error: " . mysqli_error($link));
	$insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','self','$loan_booking','NGN','Wallet','Description: Maintenance fee for Loan Booking','successful','$wallet_date_time','$iuid','$iassigned_walletbal')");
	$insert = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$institution_id'");
	$insert = mysqli_query($link, "INSERT INTO loan_info VALUES(null,'$lid','$lproduct','Individual','$borrower','$baccount','$income_amt','$salary_date','$employer','$bacinfo','$amount','','','$agent','','$gname','$gphone','$g_address','','','$g_rela','$location','Pending','','','$interest','$amount_topay','$amount_topay','','$lreasons','$upstatus','$p_status','$institution_id','','$idept','$isbranchid','','','','','','','Pending','NotSent','')") or die ("Error: " . mysqli_error($link));
	$insert = mysqli_query($link, "INSERT INTO payment_schedule VALUES(null,'$lid','$baccount','$pschedule','$tenor','$institution_id','','$lproduct')") or die ("Error: " . mysqli_error($link));
	
	if(!$insert)
	{
		echo '<meta http-equiv="refresh" content="2;url=newloans.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'">';
		echo '<br>';
		echo'<span class="itext" style="color: orange">Unable to Save Loan Information.....Please try again later!</span>';
	}
	else{
		include("alert_sender/loan_process1_alert.php");
		echo '<meta http-equiv="refresh" content="2;url=listloans.php?id='.$_SESSION['tid'].'&&mid='.base64_encode("404").'&&tab=tab_1">'; 
		echo '<br>';
		echo'<span class="itext" style="color: blue">Saving Loan Information.....4 more steps to complete the request.</span>';
	}
}
}
?>
</div>
</body>
</html>
