<?php 
error_reporting(0); 
include "../config/session.php";
?>  

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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

	$result = array();

	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));		
	$r = mysqli_fetch_object($query);
	$sys_abb = $r->abb;
	$sys_email = $r->email;
	$subaccount_charges = $r->subaccount_charges;
    $wprefund_bal = $r->wallet_prefound_amt;

    $refid = "EA-walletPrefund-".rand(1000000,9999999);

	//Institution Records
	$institutionID =  mysqli_real_escape_string($link, $_POST['institutionID']);
	$itype = mysqli_real_escape_string($link, $_POST['itype']);
	$iname = mysqli_real_escape_string($link, $_POST['iname']);
	$license_no = mysqli_real_escape_string($link, $_POST['license_no']);
	$addrs = mysqli_real_escape_string($link, $_POST['addrs']);
	$state =  mysqli_real_escape_string($link, $_POST['state']);
	$country =  mysqli_real_escape_string($link, $_POST['country']);
	$official_email = mysqli_real_escape_string($link, $_POST['official_email']);
	$official_phone = mysqli_real_escape_string($link, $_POST['official_phone']);
	$my_senderid = mysqli_real_escape_string($link, $_POST['senderid']);
	$currency_type = mysqli_real_escape_string($link, $_POST['currency']);

	//SETTLEMENT ACCOUNT DETAILS / DIVIDEND
	//$account_number = mysqli_real_escape_string($link, $_POST['account_number']);
	//$bank_code = mysqli_real_escape_string($link, $_POST['bank_code']);
	//$bank_name = mysqli_real_escape_string($link, $_POST['bank_name']);
	//$perc_charges = mysqli_real_escape_string($link, $_POST['perc_charges']);
	//$settlement_schedule = mysqli_real_escape_string($link, $_POST['settlement_schedule']);

	//Director's Details
	$drID = mysqli_real_escape_string($link, $_POST['drID']);
	$fname = mysqli_real_escape_string($link, $_POST['fname']);
	$gender = mysqli_real_escape_string($link, $_POST['gender']);
	$demails = mysqli_real_escape_string($link, $_POST['demails']);
	$mobile_no = mysqli_real_escape_string($link, $_POST['mobile_no']);
	$moi = mysqli_real_escape_string($link, $_POST['moi']);
	$idnumber = mysqli_real_escape_string($link, $_POST['idnumber']);
	$username = mysqli_real_escape_string($link, $_POST['username']);
	$unumber = mysqli_real_escape_string($link, $_POST['unumber']);

	$upassword = random_password(10);

	$directorateType = mysqli_real_escape_string($link, $_POST['directorateType']);

	//$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'subaccount'");
	//$fetch_restapi = mysqli_fetch_object($search_restapi);
	//$api_url = $fetch_restapi->api_url;
	
	$verify_email = mysqli_query($link, "SELECT * FROM institution_data WHERE official_email = '$official_email'");
	$detect_email = mysqli_num_rows($verify_email);

	$verify_phone = mysqli_query($link, "SELECT * FROM institution_data WHERE official_phone = '$official_phone'");
	$detect_phone = mysqli_num_rows($verify_phone);

	$verify_username = mysqli_query($link, "SELECT * FROM user WHERE username = '$username'");
	$detect_username = mysqli_num_rows($verify_username);
	
	$verify_id = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$my_senderid'");
	$detect_id= mysqli_num_rows($verify_id);
	
	if($detect_email == 1){
		echo '<meta http-equiv="refresh" content="5;url=add_institution.php?tid='.$_SESSION['tid'].'&&mid=NDE5&&tab=tab_1">';
		echo '<br>';
		echo "<p style='font-size:24px; color:orange;'>Sorry, Email Address has already been used.</p>";
	}
	elseif($detect_phone == 1){
		echo '<meta http-equiv="refresh" content="5;url=add_institution.php?tid='.$_SESSION['tid'].'&&mid=NDE5&&tab=tab_1">';
		echo '<br>';
		echo "<p style='font-size:24px; color:orange;'>Sorry, Phone Number has already been used.</p>";
	}
	elseif($detect_username == 1){
		echo '<meta http-equiv="refresh" content="5;url=add_institution.php?tid='.$_SESSION['tid'].'&&mid=NDE5&&tab=tab_1">';
		echo '<br>';
		echo "<p style='font-size:24px; color:orange;'>Sorry, Username has already been used.</p>";
	}
	elseif($detect_id == 1){
		echo '<meta http-equiv="refresh" content="5;url=add_institution.php?tid='.$_SESSION['tid'].'&&mid=NDE5&&tab=tab_1">';
		echo '<br>';
		echo "<p style='font-size:24px; color:orange;'>Sorry, Sender ID has already been used.</p>";
	}
	elseif($directorateType == "Partnership"){

		$target_dir = "../img/";
		$target_file = $target_dir.basename($_FILES["image"]["name"]);
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$check = getimagesize($_FILES["image"]["tmp_name"]);

		$sourcepath = $_FILES["image"]["tmp_name"];
		$targetpath = "../img/" . $_FILES["image"]["name"];
		move_uploaded_file($sourcepath,$targetpath);
		
		$location = "img/".$_FILES['image']['name'];

		$insert_institution = mysqli_query($link, "INSERT INTO institution_data VALUES(null,'$institutionID','NIL','$itype','$location','$iname','$license_no','$addrs','$state','$country','$official_email','$official_phone','','','Approved','Enable','0.0','$wprefund_bal','NULL',NOW())") or die ("Error: " . mysqli_error($link));
        $insert_institution = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institutionID','$refid','self','$wprefund_bal','NGN','system','Remarks: New User Wallet Bonus','successful',NOW())");
        $insert_institution = mysqli_query($link, "INSERT INTO member_settings VALUE(null,'$institutionID','$iname','$location','','$my_senderid','$currency_type','','','','','100000')") or die ("Error: " . mysqli_error($link));
        
		$systemset = mysqli_query($link, "SELECT * FROM systemset");
	 	$row1 = mysqli_fetch_object($systemset);

		echo $filename=$_FILES["file"]["tmp_name"];
    
	    $allowed_filetypes = array('csv');
	    if(!in_array(end(explode(".", $_FILES['file']['name'])), $allowed_filetypes))
	        {
	        echo "<script type=\"text/javascript\">
	            alert(\"The file you attempted to upload is not allowed.\");
	          </script>";
	        }    
	    elseif($_FILES["file"]["size"] > 0)
	     {
	        $file = fopen($filename, "r");
	           while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
	           {
	           	$result = array();
  	 			$bvn = $emapData[7];
 
			  	$drID2 = 'DIR'.mt_rand(10000000,99999999);
				$upassword2 = random_password(10);
				$encrypt = base64_encode($upassword2);
				//It wiil insert a row to our borrowers table from our csv file`
				$sql = "INSERT INTO institution_user(id,directorate_id,d_type,d_image,d_name,gender,email,mobile_no,moi,id_number,username,password,institution_id,i_branchid,urole,reg_date) VALUES(null,'$drID2','$directorateType','','$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]','$emapData[5]','$emapData[6]','$upassword2','$emapData[7]','$institutionID','','institution_super_admin',NOW())";

				$insert_instuser = mysqli_query($link, "INSERT INTO user VALUES(null,'$emapData[0]','$emapData[2]','$emapData[3]','','','','','','','Approved','$emapData[6]','$encrypt','$drID2','','institution_super_admin','','Registered','$institutionID','INS','0000','0.0','','0.0')") or die (mysqli_error($link));
				           //we are using mysql_query function. it returns a resource on true else False on error
				$outcome = mysqli_query($link,$sql);

				include('email_sender/add_bulkinstitutionmsg.php');

				if(!($outcome && $insert_institution))
				{
					echo '<meta http-equiv="refresh" content="5;url=add_institution?tid='.$_SESSION['tid'].'&&mid=NDE5&&tab=tab_1">';
					echo '<br>';
					echo'<span class="itext" style="color: red">Invalid File:Please Upload CSV File...</span>';
			  	}
			  	}
	           fclose($file);
	           	//throws a message if data successfully imported to mysql database from excel file
	           	echo '<meta http-equiv="refresh" content="5;url=listinstitution?tid='.$_SESSION['tid'].'&&mid=NDE5">';
				echo '<br>';
				echo'<span class="itext" style="color: blue">Institution Created Successfully.....Please Wait while it redirect to all Institution Page!</span>';
	     }
	}
	elseif($directorateType == "Sole Proprietorship"){

		$target_dir = "../img/";
		$target_file = $target_dir.basename($_FILES["image"]["name"]);
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$check = getimagesize($_FILES["image"]["tmp_name"]);

		$sourcepath = $_FILES["image"]["tmp_name"];
		$targetpath = "../img/" . $_FILES["image"]["name"];
		move_uploaded_file($sourcepath,$targetpath);
		
		$location = "img/".$_FILES['image']['name'];
		
		echo $filename=$_FILES["file"]["tmp_name"];
		foreach ($_FILES['documents']['name'] as $key => $name){

			$newFilename = $name;
			$newlocation = '../img/'.$newFilename;
			if(move_uploaded_file($_FILES['documents']['tmp_name'][$key], '../img/'.$newFilename))
			{
				mysqli_query($link, "INSERT INTO institution_legaldoc VALUES(null,'$institutionID','$newlocation')");
			}
			
		}

  	 	$bvn = $unumber;

  	 	$encrypt = base64_encode($upassword);

		$insert_institution = mysqli_query($link, "INSERT INTO institution_data VALUES(null,'$institutionID','NIL','$itype','$location','$iname','$license_no','$addrs','$state','$country','$official_email','$official_phone','','','Approved','Enable','0.0','$wprefund_bal','NULL',NOW())") or die ("Error: " . mysqli_error($link));
        
        $insert_institution = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institutionID','$refid','self','$wprefund_bal','NGN','system','Remarks: New User Wallet Bonus','successful',NOW())");
        
		$insert_institution_user = mysqli_query($link, "INSERT INTO institution_user VALUES(null,'$drID','$directorateType','','$fname','$gender','$demails','$mobile_no','$moi','$idnumber','$username','$upassword','$unumber','$institutionID','','institution_super_admin',NOW())") or die ("Error: " . mysqli_error($link));

		$insert_institution_user = mysqli_query($link, "INSERT INTO user VALUES(null,'$fname','$demails','$mobile_no','','','','','','','Approved','$username','$encrypt','$drID','','institution_super_admin','','Registered','$institutionID','INS','0000','0.0','','0.0')") or die (mysqli_error($link));

        $insert = mysqli_query($link, "INSERT INTO member_settings VALUE(null,'$institutionID','$iname','$location','','$my_senderid','$currency_type','','','','','100000')") or die ("Error: " . mysqli_error($link));
        

		if(!($insert_institution && $insert_institution_user)){

			echo '<meta http-equiv="refresh" content="5;url=add_institution?tid='.$_SESSION['tid'].'&&mid=NDE5&&tab=tab_1">';
			echo '<br>';
			echo'<span class="itext" style="color: orange">Unable to Add Institution!...Try again later!</span>';

		}
    	else{
    		echo '<meta http-equiv="refresh" content="5;url=listinstitution?tid='.$_SESSION['tid'].'&&mid=NDE5">';
    		echo '<br>';
    		echo'<span class="itext" style="color: blue">Institution Created Successfully.....Please Wait while it redirect to all Institution Page!</span>';
    	}
	}
}
?>
</div>
</body>
</html>