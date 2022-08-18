<?php include "../config/session.php"; ?>  

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
		<div class="loader"></div>
<?php							
if (isset($_POST['send']))
{
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
	
	$cto = mysqli_real_escape_string($link, $_POST['cto']);
	$msg = mysqli_real_escape_string($link, $_POST['message']);
	
	switch ($cto) {
		case ($cto == "Borrower" || $cto == "Contributor" || $cto == "Author"):
			$search_cust = mysqli_query($link, "SELECT * FROM borrowers WHERE community_role = '$cto'") or die("Error:" . mysqli_error($link));
			while($get_cust = mysqli_fetch_array($search_cust))
			{
				$system_set = mysqli_query($link, "SELECT * FROM systemset");
				$get_sysset = mysqli_fetch_array($system_set);
				$fname = $get_cust['fname'];
				$phone = $get_cust['phone'];
				$sender = $get_sysset['abb'];

				$message .= "Dear ".$fname."! ";
				$message .= "".$msg."";
				$debug = true;
				ozekiSend($sender,$phone,$message,$debug);
				echo '<meta http-equiv="refresh" content="2;url=sendsms_customer.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("403").'">';
				echo '<br>';
				echo'<span class="itext" style="color: #FF0000">Sending Message...Done!</span>';
			}
			break;
		case ($cto != "Borrower" && $cto != "Contributor" && $cto != "Author"):
			$search_cust = mysqli_query($link, "SELECT * FROM borrowers WHERE phone = '$cto'") or die("Error:" . mysqli_error($link));
			while($get_cust = mysqli_fetch_array($search_cust))
			{
				$system_set = mysqli_query($link, "SELECT * FROM systemset");
				$get_sysset = mysqli_fetch_array($system_set);
				$fname = $get_cust['fname'];
				$sender = $get_sysset['abb'];

				$message .= "Dear ".$fname."! ";
				$message .= "".$msg."";
				$debug = true;
				ozekiSend($sender,$cto,$message,$debug);
				echo '<meta http-equiv="refresh" content="2;url=sendsms_customer.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("403").'">';
				echo '<br>';
				echo'<span class="itext" style="color: #FF0000">Sending Message...Done!</span>';
			}
			break;
		default:
			echo '<meta http-equiv="refresh" content="2;url=sendsms_customer.php?tid='.$_SESSION['tid'].'&&mid='.base64_encode("403").'">';
			echo '<br>';
			echo'<span class="itext" style="color: #FF0000">Unable to deliver message.....Please try again later!</span>';
	}
	}
}
?>	
</div>
</body>
</html>
