<?php
$search_sys = mysqli_query($link, "SELECT * FROM systemset") or die("Error:" . mysqli_error($link));
$get_sys = mysqli_fetch_array($search_sys);
$sender = $get_sys['abb'];
$sys_name = $get_sys['name'];
$email = $get_sys['email'];

$subject = "Welcome Notification - $sender";
$body = "\n Dear $emapData[15],";
$body .= "\n This is to inform you that we are through with your Institution account setup.";
$body .= " Kindly login using the details below:"; 
$body .= "\n";
$body .= "\n";
$body .= "\n Username: $emapData[21]";
$body .= "\n Passowrd: $upassword";
$body .= "\n";
$body .= "\n";
$body .= "\n Please you are advise to change your password once you are able to login for security purpose.";
$body .= "\n";
$body .= "\n Best Regards.";
$body .= "\n $sys_name";
$additionalheaders = "From:$email\r\n";
$additionalheaders .= "Reply-To:noreply@alert.com \r\n";
$additionalheaders .= "MIME-Version: 1.0";
$additionalheaders .= "Content-Type: text/html\r\n";

mail($emapData[17],$subject,$body,$additionalheaders); 
?>