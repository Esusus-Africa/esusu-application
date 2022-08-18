<?php

include("../config/connect.php");
include("../config/nipBankTransfer_class.php");

$mysystem_config = mysqli_query($link, "SELECT * FROM systemset");
$fetchsys_config = mysqli_fetch_array($mysystem_config);

//PRIME AIRTIME CREDENTIALS
$accessToken = $fetchsys_config['primeairtime_token'];
$todaysDate = date("Y-m-d");

$result = $new->reAuthPAToken($link,$accessToken);
mysqli_query($link, "UPDATE systemset SET primeairtime_token = '$result', PAToken_lastupdate = '$todaysDate' WHERE PAToken_lastupdate != '$todaysDate'");

?>