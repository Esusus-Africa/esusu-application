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

$subtoken = $_GET['subtoken'];
$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));    
$r = mysqli_fetch_object($query);

$search_duration = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE sub_token = '$subtoken'");
$fetch_duration = mysqli_fetch_object($search_duration);
$dfrom = $fetch_duration->duration_from;
$dto = $fetch_duration->duration_to;
$coopid_instid = $fetch_duration->coopid_instid;

$now = time(); // or your date as well
$your_date = strtotime($dto);
    
$datediff = $your_date - $now;
$total_day = round($datediff / (60 * 60 * 24)) + 1;

$durationLeft = (($total_day <= 5 && $total_day > 0) ? "ends in ".$total_day." day(s) time." : (($total_day == 0) ? "Expired Today" : "has Expired"));
$actionWord = (($total_day <= 5 && $total_day > 0) ? "Top-up" : (($total_day == 0) ? "Reactivate" : "Reactivate"));

$search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$coopid_instid'");
$fetch_inst = mysqli_fetch_array($search_inst);
$aggr_id = $fetch_inst['aggr_id'];
$name = $fetch_inst['institution_name'];
$instEmail = $fetch_inst['official_email'];
    
$search_aggr = mysqli_query($link, "SELECT * FROM aggregator WHERE aggr_id = '$aggr_id'");
$checkAggr = mysqli_num_rows($search_aggr);
$aggrEmail = ($checkAggr['email'] == "") ? "" : ",".$checkAggr['email'];

$searchSID = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$coopid_instid'");
$fetchSID = mysqli_fetch_array($searchSID);
$portalURL = "https://esusu.app/".$fetchSID['sender_id'];

($total_day == 0) ? mysqli_query($link, "UPDATE saas_subscription_trans SET usage_status = 'Expired' WHERE sub_token = '$subtoken'") : "";

$emailReceiver = $instEmail.$aggrEmail;

include("../cron/send_reminder_invoice.php");

echo '<meta http-equiv="refresh" content="5;url=paid_sub.php?id='.$_SESSION['tid'].'&&mid=NDIw">';
echo '<br>';
echo '<span class="itext" style="color: blue">Reminder Sent Successfully</span>';

?>
</div>
</body>
</html>