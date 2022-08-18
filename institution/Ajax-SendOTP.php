<?php
include ("../config/session1.php");
require_once "../config/smsAlertClass.php";
$VAcctNo = $_GET['VAcctNo'];
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$myaltrow = mysqli_fetch_array($myaltcall);
$sysabb = $myaltrow['sender_id'];

$searchCustomerPhone = mysqli_query($link, "SELECT phone FROM borrowers WHERE (account='$VAcctNo' OR virtual_acctno='$VAcctNo')") or die("Error: " . mysqli_error($link));
$get_cphone = mysqli_fetch_array($searchCustomerPhone);
$phone = $get_cphone['phone'];

$otpcode = substr((uniqid(rand(),1)),3,6);

$sms = "$sysabb>>>Dear Customer! Your One Time Password is $otpcode";

if($PostType == "0" && $VAcctNo != ""){

    $sms_rate = $fetchsys_config['fax'];
    $imywallet_balance = $itransfer_balance - $sms_rate;
    $sms_refid = "EA-smsCharges-".rand(1000000,9999999);
    $currenctdate = date("Y-m-d h:i:s");

    $debitWallet = "Yes";
    $userType = "user";

    ($itransfer_balance < $sms_rate) ? "" : $sendSMS->userGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $sms_refid, $sms_rate, $iuid, $imywallet_balance, $debitWallet, $userType);
    mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$iuid','$otpcode','none','Pending','$currenctdate')");
?>
            
    <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">OTP Code</label>
        <div class="col-sm-6">
            <input name="otpCode" type="password" class="form-control" placeholder="Enter OTP Code Received by Customer" required/>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

<?php
}elseif($PostType == "0" && $VAcctNo == ""){

?>

    <div class="form-group">
        <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
        <div class="col-sm-6" style="font-size:17px;">
            <b>Error!...Please Enter Customer A/C Number to proceed!!</b>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

<?php
}
else{
    //Do nothing
}
?>