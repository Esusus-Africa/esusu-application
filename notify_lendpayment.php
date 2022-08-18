<?php 
include "config/connect.php";
$id = $_GET['temp_id'];

$update1 = mysqli_query($link, "UPDATE campaign_lendpay_history SET lstatus = 'Paid' WHERE pid = '$id'");
?>