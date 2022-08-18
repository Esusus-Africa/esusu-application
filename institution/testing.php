<?php

//$now = time(); // or your date as well
//$your_date = strtotime('06/02/2018');


//$datediff = $your_date - $now;
//$total_day = round($datediff / (60 * 60 * 24));
//echo "Days left: ".$total_day;

//$urltouse = "https://critechglobal.com/mysms/components/com_spc/smsapi.php?username=admin&password=criTech::SMS@1993&balance=true&";
//$response = file_get_contents($urltouse);
//echo $response;
//$time = "2018-05-09";
//echo date("d",strtotime($time));
/**
$uri = $_SERVER['REQUEST_URI'];
echo $uri.'<br>'; // Outputs: URI

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
 
$url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
echo $url; // Outputs: Full URL
**/

$now = time(); // or your date as well
$your_date = strtotime('2018-11-25');


$datediff = $your_date - $now;
$total_day = round($datediff / (60 * 60 * 24));
echo "Days left: ".$total_day;

?>