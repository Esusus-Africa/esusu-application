<?php 
if(isset($_POST['my_bvn']))
{
	include("../config/connect.php");
	include ("../config/restful_apicalls.php");
	 //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
	$result = array();
	$systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    $seckey = $row1->secret_key;

    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'bvn'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;
	 
    $bvn = $_POST['my_bvn'];
	 
	$get_data = callAPI('GET', $api_url.$bvn.'?seckey='.$seckey, false);
	$result = json_decode($get_data, true);
	
    if($result['status'] == "success"){

  	echo "<br><label class='label label-success'>".$result['data']['first_name'].' '.$result['data']['middle_name'].' '.$result['data']['last_name']."</label><br>";
  	echo "<br><label class='label label-success'>Date of Birth: ".$result['data']['date_of_birth'].' Phone No.:'.$result['data']['phone_number']."</label><br>";
    }
    else{
  		echo "<br><label class='label label-danger'>".$result['message']."</label>";
    }
}
?>