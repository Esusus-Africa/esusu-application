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
if(isset($_POST['PayBill1']))
{

	$billp_settings = mysqli_query($link, "SELECT * FROM billpayment WHERE companyid = '$agentid' AND status = 'Active'");
    $row_bp = mysqli_fetch_object($billp_settings);

    $url = 'https://estoresms.com/bill_payment_processing/v/1/'; //API Url (Do not change)
    $token = $row_bp->token;
    $act_email = $row_bp->email;
    $username = $row_bp->username;
    $ref = "txid-".rand(10000000,99999999);
    $category = mysqli_real_escape_string($link, $_POST['category']);
    $product_id = mysqli_real_escape_string($link, $_POST['product_id']);
    $plan_list = mysqli_real_escape_string($link, $_POST['plan_list']);
    $smartcard = mysqli_real_escape_string($link, $_POST['smartcard']);

	//Initiate cURL.
	$ch = curl_init($url);

	$data=array();
	$data['username']=$username;

	//Generate Hash
	$data['hash']=hash('sha512',$token.$act_email.$username.$ref);

	//Reference
	$data['ref']=$ref;

	//Category
	$data['category']=$category;

	//Product
	$data['product']=$product_id;

	//Product Plan
	$data['plan']=$plan_list;

	//Smartcard Number
	$data['number']=$smartcard;


	//Send as a POST request.
	curl_setopt($ch, CURLOPT_POST, 1);

	//Attach encoded JSON string to the POST fields.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	//Allow parsing response to string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	//Execute the request
	$response=curl_exec($ch);

	curl_close($ch);
	
	if ($response) {
	  $data = json_decode($response, true);

	  if($data['response'] == "OK"){
			
			$message = $data['message'];
	  		echo '<meta http-equiv="refresh" content="10;url=dashboard.php?id='.$_SESSION['tid'].'&&mid=NDAx">';
			echo '<br>';
			echo '<span class="itext" style="color: blue">'.$message.' (Transaction ID: '.$data['transaction_id'].')</span>';
			echo '<span class="itext" style="color: blue">'.$data['pin_message'].'</span>';

		}
		else{

			$message = $data['message'];
			echo '<meta http-equiv="refresh" content="5;url=pay_bills.php?id='.$_SESSION['tid'].'&&mid=NDA0">';
			echo '<br>';
			echo '<span class="itext" style="color: orange">'.$message.'</span>';

		}
	}
}
?>
</div>
</body>
</html>