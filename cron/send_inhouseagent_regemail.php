<?php
include("../config/connect.php");

$search_availableemail = mysqli_query($link, "SELECT * FROM email_log WHERE shortenedurl = 'agent' AND shortenedurl1 = 'reg'");
while($fetch_availableemail = mysqli_fetch_array($search_availableemail))
{
  //INFORMATION NEEDED TO SEND EMAIL
  $id = $fetch_availableemail['id'];
  $sys_abb = $fetch_availableemail['sys_abb'];
  $sys_email = $fetch_availableemail['sys_email'];
  $lname = $fetch_availableemail['lname'];
  $customer_email = $fetch_availableemail['customer_email'];
  //$activation_link = $fetch_availableemail['shortenedurl'];
  //$deactivation_link = $fetch_availableemail['shortenedurl1'];
  $status = $fetch_availableemail['status'];

  $search_agent = mysqli_query($link, "SELECT * FROM agent_data WHERE email = '$customer_email'");
  $fetch_agent = mysqli_fetch_object($search_agent);
  
    if($status == "Pending")
    {
    $to = "$customer_email";
    $subject = "Welcome! Account Created Successfully - $sys_abb";
    $body = "\nHi $lname,";
    $body .= "\nCongratulation! Your Application as an Agent has been Initiated Successfully. You can now logon to the system with the username and password stated below:";
    $body .= "\n";
    $body .= "\nYour Username is: $fetch_agent->username";
    $body .= "\nYour Password is: $fetch_agent->upassword";
    $body .= "\n";
    $body .= "\nNOTE:";
    $body .= "\nPlease we urge you to keep your password safe and make sure you did not disclose it with anyone Or whatsoever ask anyone to logon on your behalf.";
    $body .= "\nAlso, you are advise to change your password once you are able to login for security purpose.";
    $body .= "\n";
    $body .= "\n";
    $body .= "\nRegards";
    $body .= "\nEsusu Africa";
    $additionalheaders = "From:$sys_email\r\n";
    $additionalheaders .= "Reply-To:noreply@imon.com \r\n";
    $additionalheaders .= "MIME-Version: 1.0";
    $additionalheaders .= "Content-Type: text/html\r\n";
      
    if(mail($customer_email,$subject,$body,$additionalheaders))
    {
      mysqli_query($link, "UPDATE email_log SET status = 'Sent' WHERE id = '$id'");
    }
    else{
      echo "";
    }
  }
  else{
    echo "";
  }
}
?>