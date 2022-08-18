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
try {
        $id = $_GET['id'];
        $search_user = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id'");
        $get_user = mysqli_fetch_array($search_user);
    	$bvn = $get_user['unumber'];
    	$moi = "National Id";
        $fname = $get_user['fname'];
        $lname = $get_user['lname'];
        $phone = $get_user['phone'];
        $addrs = $get_user['addrs'];
        $state = $get_user['state'];
        $email = $get_user['email'];
        
        $systemset = mysqli_query($link, "SELECT * FROM systemset");
        $row1 = mysqli_fetch_object($systemset);
        $verveAppId = $row1->verveAppId;
        $verveAppKey = $row1->verveAppKey;
        
        $api_name =  "cardholder_registration";
        $search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = 'VerveCard'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
        
        $client = new SoapClient($api_url);
        
        $param = array(
          'appId'=>$verveAppId,
          'appKey'=>$verveAppKey,
          'bvn'=>$bvn,
          'MeansOfIdNumber'=>$moi,
          'firstName'=>$fname,
          'lastName'=>$lname,
          'mobileNr'=>$phone,
          'streetAddress'=>$addrs,
          'streetAddressLine2'=>$state,
          'email'=>$email
        );
        
        $response = $client->PostIswNewPrepaidCard($param);
        
        $process = json_decode(json_encode($response), true);
        
        //print_r($process);
        
        $statusCode = $process['PostIswNewPrepaidCardResult']['StatusCode'];
        
        $verveMsg = $process['PostIswNewPrepaidCardResult']['JsonData'];
        
        if($statusCode == "00"){
            
            $update = mysqli_query($link, "UPDATE borrowers SET card_reg = 'Yes', card_issurer = 'VerveCard' WHERE id = '$id'");
	
        	echo '<meta http-equiv="refresh" content="5;url=customer?id='.$_SESSION['tid'].'&&mid=NDAz">';
        	echo '<br>';
        	echo '<span class="itext" style="color: orange;">'.$verveMsg.'</span>';
            
        }else{
            
            echo '<meta http-equiv="refresh" content="5;url=customer?id='.$_SESSION['tid'].'&&mid=NDAz">';
        	echo '<br>';
        	echo '<span class="itext" style="color: orange;">Oops!...Network Error, Please try again leter!!</span>';
            
        }
        
        
  	} catch( Exception $e ){
  	    
  	    // You should not be here anymore
        echo $e->getMessage();
  	    
  	}
?>
</div>
</body>
</html>