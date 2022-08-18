   <?php 
   if(isset($_POST['my_actno']))
   {
	 include("../config/connect.php");
	 //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
	 
  	 $result = array();
  	 $actnumb = $_POST['my_actno'];
  	 $bcode = $_POST['my_bcode'];
  	 
  	 $systemset = mysqli_query($link, "SELECT * FROM systemset");
	 $row1 = mysqli_fetch_object($systemset);

	 $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'providus_endpoint'");
  	 $fetch_restapi = mysqli_fetch_object($search_restapi);
  	 $api_url = $fetch_restapi->api_url;
     
    $client = new SoapClient($api_url);

    $param = array(
        'account_number'=>$actnumb,
        'bank_code'=>$bcode,
        'username'=>$row1->providusUName,
        'password'=>$row1->providusPass
      );

    $response = $client->GetNIPAccount($param);
    
    $process = json_decode(json_encode($response), true);

    $processReturn = $process['return'];
        
    $process2 = json_decode($processReturn, true);
    
  	  //print_r($result);
  	if($process2['responseCode'] == "00"){
  		echo "<b style='font-size:18px;'>".$process2['accountName']."</b>";
  		echo '<input name="b_name" type="hidden" value="'.$process2['accountName'].'">';
  	}
  	else{
  		echo "<b style='font-size:18px;'>".$process2['responseMessage']."</b>";
  	}
}
?>