<?php 
   if(isset($_POST['my_actno']))
   {
    include("../config/connect.php");
    require_once '../config/nipBankTransfer_class.php';
	 
  	$result = array();
  	$actnumb = $_POST['my_actno'];
  	$bcode = $_POST['my_bcode'];
  	 
	$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'sterling_InterbankNameEnquiry'");
  	$fetch_restapi = mysqli_fetch_object($search_restapi);
  	$client = $fetch_restapi->api_url;
     
    $data_to_send_server = array(
        "Referenceid"=>date("ymi").time(),
        "RequestType"=>161,
        "Translocation"=>"100,100",
        "ToAccount"=>$actnumb,
        "DestinationBankCode"=>$bcode
    );

    $process = $new->sterlingNIPBankTransfer($data_to_send_server,$client);
    
    $processReturn = $process['data'];
    
  	if($processReturn['status'] == "00"){
  		echo "<b style='font-size:18px;'>".$processReturn['AccountName']."</b>";
        echo '<input name="b_name" type="hidden" value="'.$processReturn['AccountName'].'">';
        echo '<input name="NEStatus" type="hidden" value="'.$processReturn['status'].'">';
        echo '<input name="stSessionId " type="hidden" value="'.$processReturn['sessionID'].'">';
        echo '<input name="FromAcct " type="hidden" value="'.$processReturn['AccountNumber'].'">';
  	}
  	else{
  		echo "<b style='font-size:18px;'>No Account Found!</b>";
  	}
}
?>