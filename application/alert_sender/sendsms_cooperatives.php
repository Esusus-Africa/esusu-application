<?php
include("../config/connect.php");
session_start();

ini_set('max_execution_time', 0); // to get unlimited php script execution time

if(empty($_SESSION['i'])){
    $_SESSION['i'] = 0;
}

$total = 100;
for($i=$_SESSION['i'];$i<$total;$i++)
{
    $_SESSION['i'] = $i;
    $percent = intval($i/$total * 100)."%";   
  
    //sleep(1); // Here call your time taking function like sending bulk sms etc.
    function httpGet($url)
    {
      $ch = curl_init();  
         
      curl_setopt($ch,CURLOPT_URL,$url);
      curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
           
      $output=curl_exec($ch);
           
      curl_close($ch);
      return $output;
    }

    $sql = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated' AND smsuser = ''") or die (mysqli_error($link));
    $find = mysqli_fetch_array($sql);
    $ozeki_user = $find['username'];
    $ozeki_password = $find['password'];
    $ozeki_url = $find['api'];
    $status = $find['status'];

    $cto = mysqli_real_escape_string($link, $_POST['cto']);
    $message = mysqli_real_escape_string($link, $_POST['message']);
    
    $system_set = mysqli_query($link, "SELECT * FROM systemset");
    $get_sysset = mysqli_fetch_array($system_set);

    $sys_abb = $get_sysset['abb'];

    if($cto == ""){

      $search_cust = mysqli_query($link, "SELECT * FROM cooperatives") or die("Error:" . mysqli_error($link));
      $get_cust = mysqli_fetch_array($search_cust);
          
      $mobileNumber = [while($get_cust){
        echo $get_cust['official_phone'];
      }];
      $smstxt= $message;

      foreach ($mobileNumber as $mobile)
      {
        $data = array('username' => $ozeki_user,
                '&password' => $ozeki_password,
                '&sender' => urlencode($sys_abb),
                '&recipient' => urlencode($mobile),
                '&message' => urlencode($smstxt)
              );
          
        $url= $ozeki_url.http_build_query($data);

        httpGet($url);
      }

      echo '<script>
      parent.document.getElementById("progressbar").innerHTML="<div style=\"width:'.$percent.';background:linear-gradient(to bottom, rgba(125,126,125,1) 0%,rgba(14,14,14,1) 100%); ;height:35px;\">&nbsp;</div>";
      parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\">'.$percent.' is processed.</div>";</script>';

      ob_flush(); 
      flush(); 
  }
  elseif($cto != ""){
          
      $mobileNumber = $cto;
      $smstxt= $message;

      foreach ($mobileNumber as $mobile)
      {
        $data = array('username' => $ozeki_user,
                '&password' => $ozeki_password,
                '&sender' => urlencode($sys_abb),
                '&recipient' => urlencode($mobile),
                '&message' => urlencode($smstxt)
              );
          
        $url= $ozeki_url.http_build_query($data);

        httpGet($url);
      }

      echo '<script>
      parent.document.getElementById("progressbar").innerHTML="<div style=\"width:'.$percent.';background:linear-gradient(to bottom, rgba(125,126,125,1) 0%,rgba(14,14,14,1) 100%); ;height:35px;\">&nbsp;</div>";
      parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\">'.$percent.' is processed.</div>";</script>';

      ob_flush(); 
      flush(); 

  }
}
echo '<script>parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\">Process completed</div>"</script>';

session_destroy(); 

?>