<?php
include("../config/connect.php");

//Get value(s) from database
if ($stmt = $link->prepare('SELECT official_phone FROM merchant_reg')){
    $stmt->execute();
    $stmt->bind_result($phone);

    header('Content-type: text/plain');
    header('Content-Disposition: attachment; filename=merchant_phone.txt');

    //Export values to file download
    while ($stmt->fetch()) {
        echo $phone.",\n";
    }
    $stmt->close();
}
?>