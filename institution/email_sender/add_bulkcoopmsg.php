<?php
$search_sys = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
$get_sys = mysqli_fetch_array($search_sys);
$sender = $get_sys['abb'];
$sysname = $get_sys['name'];
$email = $get_sys['email'];

$subject = "Welcome Notification as New Cooperative - $sender";
$body = "\n Hi $emapData[2],";
$body .= "\n We are really glad for your interest in registering with us as a cooperative.";
$body .= " And we are glad to inform you that your Coopperative Registration has been initiated"; 
$body .= " successfully on our platform.";
$body .= "\n";
$body .= "\n Kindly wait patiently as we complete the process.";
$body .= "\n";
$body .= "\n Best Regards.";
$body .= "\n $sysname";
$additionalheaders = "From:$email\r\n";
$additionalheaders .= "Reply-To:noreply@alert.com \r\n";
$additionalheaders .= "MIME-Version: 1.0";
$additionalheaders .= "Content-Type: text/html\r\n";
  
mail($coopemail,$subject,$body,$additionalheaders); 
?>