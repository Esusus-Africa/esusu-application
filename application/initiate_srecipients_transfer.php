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
if(isset($_POST['ugandan_save']))
{
	function myreference($limit)
	{
	    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
	}

	$result = array();
	$recipient_id= $_POST['recipient_id'];
	$title =  mysqli_real_escape_string($link, $_POST['t_title']);

	$systemset = mysqli_query($link, "SELECT * FROM systemset");
	$row1 = mysqli_fetch_object($systemset);
	$seckey = $row1->secret_key;

	$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'bulktransfer'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;

	if(is_array($recipient_id)){

		while (list ($key, $val) = each ($recipient_id)){

			$retrieve_saved_recipient = mysqli_query($link, "SELECT * FROM transfer_recipient WHERE recipient_id = '$val'");
			$fetch_saved_recipient = mysqli_fetch_object($retrieve_saved_recipient);

			$bank_code = $fetch_saved_recipient->bank_code;
			$account_number = $fetch_saved_recipient->acct_no;
			$amount =  mysqli_real_escape_string($link, $_POST['amount']);
			$currency = mysqli_real_escape_string($link, $_POST['currency']);
			$narration =  mysqli_real_escape_string($link, $_POST['reasons']);
			$reference =  "EA-bulktransfer-".myreference(10);
			$bank_name = mysqli_real_escape_string($link, $_POST['bank_name']);
			
			// Pass the parameter here
			$postdata =  array(
				"seckey"	=>	$seckey,
				"title"		=>	$title,
				"bulk_data"	=>	[
					"Bank"			=>	$bank_code,
					"Account Number"=>	$account_number,
					"Amount"		=>	$amount,
					"Currency"		=>	$currency,
					"Narration"		=>	$narration,
					"reference"		=>	$reference
				],
				"callback_url"		=>	"https://app.esusu.africa/application/mywallet.php/?id=".$_SESSION['tid']."&&ref=".$reference."&&mid=NDA0"
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$api_url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
		    curl_setopt($ch, CURLOPT_TIMEOUT, 200);

			$headers = array('Content-Type: application/json');
		    
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$request = curl_exec ($ch);

			curl_close ($ch);

			if ($request) {
			$result = json_decode($request, true);

				if($result['status'] == "success"){

					$transfer_id = $result['data']['id'];
				    $transfers_fee = $result['data']['fee'];
				    $status = $result['data']['status'];

				    $insert = mysqli_query($link, "INSERT INTO transfer_history VALUES(null,'$transfer_id','$reference','$account_number','$b_name','$account_bank','$bank_name','$currency','$amount','$transfers_fee','$status','$narration',NOW())");

					echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_5">';
					echo '<br>';
					echo'<span class="itext" style="color: blue;">Transfer Initiated Successfully!!</span>';
				
				}
				else{
					echo '<meta http-equiv="refresh" content="2;url=transfer_fund.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_5">';
					echo '<br>';
					echo'<span class="itext" style="color: blue;">'.$result['data']['message'].'</span>';
				}
			}	
		}
	}
}
?>
</div>
</body>
</html>