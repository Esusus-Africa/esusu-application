<?php
$sql = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated'") or die (mysqli_error($link));
$find = mysqli_fetch_array($sql);
$ozeki_user = $find['username'];
$ozeki_password = $find['password'];
$ozeki_url = $find['api'];
$status = $find['status'];

if($status == "Activated")
{
########################################################
# Functions used to send the SMS message
########################################################

function ozekiSend($sender, $phone, $msg, $debug=false){
      global $ozeki_user,$ozeki_password,$ozeki_url;

      $url = 'username='.$ozeki_user;
      $url.= '&password='.$ozeki_password;
      $url.= '&sender='.urlencode($sender);
      $url.= '&recipient='.urlencode($phone);
      $url.= '&message='.urlencode($msg);

      $urltouse =  $ozeki_url.$url;
      //if ($debug) { echo "Request: <br>$urltouse<br><br>"; }

      //Open the URL to send the message
      $response = file_get_contents($urltouse);
      if ($debug) {
           //echo "Response: <br><pre>".
           //str_replace(array("<",">"),array("&lt;","&gt;"),$response).
           //"</pre><br>"; 
           }

      return($response);
}

$sys_abb = $get_sysset['abb'];

if($cto == ""){
      $search_cust = mysqli_query($link, "SELECT * FROM borrowers") or die("Error:" . mysqli_error($link));
      
      while($get_cust = mysqli_fetch_array($search_cust))
      {
        $phone = trim($get_cust['phone']);
        $message = "$msg";
        $debug = true;
        ozekiSend($sys_abb,$phone,$message,$debug);
      }
      echo "<script>alert('SMS Sent Successfully!');</script>";
      echo "<script>window.location='sendsms_customer.php?id=".$_SESSION['tid']."&&mid=NDAz'; </script>";
  }
  elseif($cto != ""){
        $message = "$msg";
        $debug = true;
        ozekiSend($sys_abb,$cto,$message,$debug);
        echo "<script>alert('SMS Sent Successfully2!');</script>";
        echo "<script>window.location='sendsms_customer.php?id=".$_SESSION['tid']."&&mid=NDAz'; </script>";
  }
}
else{
  echo "";
}
?>