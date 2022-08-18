   <?php 
   if(isset($_POST['my_cid']))
   {
    include("../config/connect.php");
    include ("../config/restful_apicalls.php");
  	 //$link = mysqli_connect('localhost','root','root','loan2','8888') or die('Unable to Connect to Database');
    $result = array();
    $my_cid = $_POST['my_cid'];

    $systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_object($systemset);
    $seckey = $row1->secret_key;

    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'billpayment'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;

    // Pass the parameter here
    $postdata =  array(
      "secret_key"    =>  $seckey,
      "service"       =>  "bills_validate_".$my_cid,
      "service_method"  =>  "get",
      "service_version" =>  "v1",
      "service_channel" =>  "rave"
    );
      
    $make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);

      if($result['Status'] == "success"){

        echo "<br><label class='label bg-blue'>".$result['Message']."</label>";
      }
  		else{
        echo "<br><label class='label bg-orange'>".$result['Message']."</label>";
      }
  } 
  ?>