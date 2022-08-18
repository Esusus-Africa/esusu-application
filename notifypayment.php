<?php 
include "config/connect.php";
$id = $_GET['temp_id'];
$prefid = $_GET['prefid'];
$payid = $_GET['payid'];
$acn = $_GET['acn'];
$bal = $_GET['bal'];
$update1 = mysqli_query($link, "UPDATE pay_schedule SET status = 'PAID' WHERE id = '$id'");
$update2 = mysqli_query($link, "UPDATE payments SET refid = '$prefid', remarks = 'paid' WHERE id = '$payid'");
$update3 = mysqli_query($link, "UPDATE loan_info SET balance = '$bal' WHERE baccount = '$acn'");
?>