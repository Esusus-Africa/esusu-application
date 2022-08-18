<?php 
include "config/connect.php";
$acn = $_GET['acn'];
$pid = $_GET['pid'];
$c_id = $_GET['c_id'];
$bname = $_GET['bname'];
$em = $_GET['em'];
$amt = $_GET['amt'];
$dtype = $_GET['dtype'];
$ddate = $_GET['ddate'];
$status = $_GET['status'];
$bbranchid = $_GET['bbranchid'];

if($dtype == "Donate"){
	$insert = mysqli_query($link, "INSERT INTO campaign_pay_history VALUES('','$pid','$c_id','$bname','$em','$amt','$dtype',NOW(),'','$status','not-released','$bbranchid')");
}elseif($dtype == "Lend"){
	$insert = mysqli_query($link, "INSERT INTO campaign_pay_history VALUES('','$pid','$c_id','$bname','$em','$amt','$dtype',NOW(),'$ddate','$status','not-released','$bbranchid')");
}

$update = mysqli_query($link, "SELECT * FROM causes WHERE id = '$c_id'");
$get_update = mysqli_fetch_array($update);
$total_contributer = $get_update['total_contributer'] + 1;
$current_fund = $get_update['current_fund'] + $amt;
$msg = $get_update['msg_to_donor'];
$update1 = mysqli_query($link, "UPDATE causes SET current_fund = '$current_fund', total_contributer = '$total_contributer' WHERE id = '$c_id'");

//SEND EMAIL TO DONOR HERE
$query = mysqli_query($link, "SELECT email FROM systemset") or die (mysqli_error($link));
$r = mysqli_fetch_object($query);

$subject = "THANKS FOR YOUR CONTRIBUTION";
$body = "\n$msg";
$additionalheaders = "From:$r->email\r\n";
$additionalheaders .= "Reply-To:noreply@imon.com \r\n";
$additionalheaders .= "MIME-Version: 1.0";
$additionalheaders .= "Content-Type: text/html\r\n";
mail($em,$subject,$body,$additionalheaders);
?>