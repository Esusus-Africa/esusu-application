<?php
include ("../config/session.php");
$VAcctNo = $_GET['VAcctNo'];
$PostType = $_GET['PostType'];

$sysabb = $fetchsys_config['abb'];

$searchCustomerPhone = mysqli_query($link, "SELECT phone FROM user WHERE virtual_acctno = '$VAcctNo'") or die("Error: " . mysqli_error($link));
$get_cphone = mysqli_fetch_array($searchCustomerPhone);
$phone = $get_cphone['phone'];

$otpcode = substr((uniqid(rand(),1)),3,6);

$sms = "$sysabb>>>Dear Customer! Your One Time Password is $otpcode";

if($PostType == "0" && $VAcctNo != ""){

    $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;

    $sms_rate = $fetchsys_config['fax'];
    $imywallet_balance = $aggwallet_balance - $sms_rate;
    $sms_refid = "EA-smsCharges-".rand(1000000,9999999);
    $currenctdate = date("Y-m-d h:i:s");

    mysqli_query($link,"INSERT INTO otp_confirmation VALUES(null,'$aggr_id','$otpcode','none','Pending','$currenctdate')")or die(mysqli_error());
	mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$sms_refid','$phone','','$sms_rate','Debit','NGN','Charges','SMS Content: $sms','successful','$currenctdate','$aggr_id','$imywallet_balance','')");
    mysqli_query($link, "UPDATE user SET transfer_balance = '$imywallet_balance' WHERE id = '$aggr_id'");

    include("../cron/send_general_sms.php");
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
            <b>Error!...Please Select Operator Account to proceed!!</b>
        </div>
        <label for="" class="col-sm-3 control-label"></label>
    </div>

<?php
}
else{
    //Do nothing
}
?>