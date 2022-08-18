<?php 
   if(isset($_POST['my_actno']))
   {
	 include("../config/connect.php");
	 include("../config/restful_apicalls.php");
	 //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
	 
  	 $result = array();
  	 $actnumb = $_POST['my_actno'];
  	 $bcode = $_POST['my_bcode'];
  	 
  	 $systemset = mysqli_query($link, "SELECT * FROM systemset");
	 $row1 = mysqli_fetch_object($systemset);

	 $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'resolveaccount'");
  	 $fetch_restapi = mysqli_fetch_object($search_restapi);
  	 $api_url = $fetch_restapi->api_url;
	 
	$postdata =  array(
		"recipientaccount" 	=> $actnumb,
		"destbankcode" 		=> $bcode,
		"PBFPubKey" 		=> $row1->public_key
	);
					
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);
  	  //print_r($result);
  	if($result['data']['data']['responsecode'] == "00"){
  		echo "<b style='font-size:18px;'>".$result['data']['data']['accountname']."</b>";
  		echo '<input name="b_name" type="hidden" value="'.$result['data']['data']['accountname'].'">';
  	}
  	else{
  		echo "<b style='font-size:18px;'>".$result['data']['data']['responsemessage']."</b>";
  	}
}
?>