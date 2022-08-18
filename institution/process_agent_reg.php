<?php 
error_reporting(0); 
include "../config/session.php";
?>  

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
if(isset($_POST['save']))
{
	
	function random_password($limit)
	{
	    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
	}

	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));		
	$r = mysqli_fetch_object($query);
	$sys_abb = $r->abb;
	$sys_email = $r->email;

	//Agent Records
	$agentid =  mysqli_real_escape_string($link, $_POST['AgentID']);
	$fname = mysqli_real_escape_string($link, $_POST['fname']);
	$gender = mysqli_real_escape_string($link, $_POST['gender']);
	$dob = mysqli_real_escape_string($link, $_POST['dob']);
	$bname =  mysqli_real_escape_string($link, $_POST['bname']);
	$rcnumber =  mysqli_real_escape_string($link, $_POST['rcnumber']);
	$occupation = mysqli_real_escape_string($link, $_POST['occupation']);
	$addrs = mysqli_real_escape_string($link, $_POST['addrs']);
	$aemail = mysqli_real_escape_string($link, $_POST['email']);
	$phone = mysqli_real_escape_string($link, $_POST['phone']);
	$username = mysqli_real_escape_string($link, $_POST['username']);

	$upassword = random_password(10);

		$target_dir = "../img/";
		$target_file = $target_dir.basename($_FILES["image"]["name"]);
		$target_file_vimage = $target_dir.basename($_FILES["vimage"]["name"]);
    	$target_file_utimage = $target_dir.basename($_FILES["utimage"]["name"]);
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$imageFileType_vimage = pathinfo($target_file_vimage,PATHINFO_EXTENSION);
    	$imageFileType_utimage = pathinfo($target_file_utimage,PATHINFO_EXTENSION);
		$check = getimagesize($_FILES["image"]["tmp_name"]);
		$check_vimage = getimagesize($_FILES["vimage"]["tmp_name"]);
    	$check_utimage = getimagesize($_FILES["utimage"]["tmp_name"]);

		$sourcepath = $_FILES["image"]["tmp_name"];
		$sourcepath_vimage = $_FILES["vimage"]["tmp_name"];
    	$sourcepath_utimage = $_FILES["utimage"]["tmp_name"];
		$targetpath = "../img/" . $_FILES["image"]["name"];
		$targetpath_vimage = "../img/" . $_FILES["vimage"]["name"];
    	$targetpath_utimage = "../img/" . $_FILES["utimage"]["name"];
		move_uploaded_file($sourcepath,$targetpath);
		move_uploaded_file($sourcepath_vimage,$targetpath_vimage);
    	move_uploaded_file($sourcepath_utimage,$targetpath_utimage);
		
		$location = "img/".$_FILES['image']['name'];
		$loaction_vimage = "img/".$_FILES['vimage']['name'];
    	$utilitybill = "img/".$_FILES['utimage']['name'];
    
	    $result = array();
	    $bvn = mysqli_real_escape_string($link, $_POST['bvn']);

  	 	//The parameter after verify/ is the transaction reference to be verified
		$url = 'https://api.paystack.co/bank/resolve_bvn/'.$bvn;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt(
			$ch, CURLOPT_HTTPHEADER, [
			'Authorization: Bearer '.$r->secret_key]
		);
		$request = curl_exec($ch);
		if(curl_error($ch)){
			echo 'error:' . curl_error($ch);
		}
		curl_close($ch);
				
		if($request){
			$result = json_decode($request, true);
			//print_r($result);
			if($resul['data']['status'] == true)
			{
				//Get the Plan code from Paystack API
				$plan_code = $result['data']['plan_code'];
					
				$insert_agent = mysqli_query($link, "INSERT INTO agent_data VALUES(null,'$agentid','$fname','$gender','$dob','$bname','$rcnumber','$occupation','$addrs','$aemail','$phone','$username','$upassword','$bvn','$location','$utilitybill','$loaction_vimage',NOW())") or die ("Error: " . mysqli_error($link));

				echo $filename=$_FILES["file"]["tmp_name"];
				foreach ($_FILES['documents']['name'] as $key => $name){
					 
				$newFilename = $name;
				$newlocation = '../img/'.$newFilename;
				if(move_uploaded_file($_FILES['documents']['tmp_name'][$key], '../img/'.$newFilename))
				{

					mysqli_query($link, "INSERT INTO agent_legaldocuments VALUES(null,'$agentid','$newlocation')");
			  		   
				}
			}
			$search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = ''");
			$fetch_gateway = mysqli_fetch_object($search_gateway);
			$gateway_uname = $fetch_gateway->username;
			$gateway_pass = $fetch_gateway->password;
			$gateway_api = $fetch_gateway->api;

			$sms = "$r->abb>>>ACCT. Created | Welcome $fname! Your Account has been created successfully. Please logon to your email for your username and password. Thanks.";

			$sendmessage = mysqli_query($link, "INSERT INTO sms_log VALUES(null,'$sys_abb','$phone','$sms','$gateway_uname','$gateway_pass','$gateway_api','Pending',NOW())");

			$sendemail = mysqli_query($link, "INSERT INTO email_log VALUES(null,'$sys_abb','$sys_email','$fname','$aemail','agent','reg','Pending',NOW())");

			if(!($sendemail && $insert_agent && $sendmessage))
			{

				echo '<meta http-equiv="refresh" content="5;url=add_agent.php?tid='.$_SESSION['tid'].'&&mid=NDQw&&tab=tab_1">';
				echo '<br>';
				echo '<span class="itext" style="color: orange">Error...Please Try Again Later!!<</span>';

			}
			else{

				echo '<meta http-equiv="refresh" content="5;url=index.php?">';
				echo '<br>';
				echo'<span class="itext" style="color: blue">Accunt Created Successfully!...An email / sms notification has been sent to the agent</span>';

			}
					  
		}
		else{

			$message = $result['message'];
			echo "<script>alert('$message \\nPlease try another one'); </script>";

		}

	}
}
?>
</div>
</body>
</html>

	echo '<meta http-equiv="refresh" content="5;url=add_agent.php?tid='.$_SESSION['tid'].'&&mid=NDQw&&tab=tab_1">';
						echo '<br>';
						echo'<span class="itext" style="color: red">'.$result['message'].'</span>';