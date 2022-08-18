<?php 
   if(isset($_POST['my_custid']))
   {
	 include("../config/connect.php");

     $curl = curl_init();
  	 $my_custid = $_POST['my_custid'];
  	 $bcode = $_POST['my_bcode'];
  	 
  	 $systemset = mysqli_query($link, "SELECT * FROM systemset");
     $row1 = mysqli_fetch_object($systemset);
     $rubbiesSecKey = $row1->rubbiesSecKey;

	 $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'rubies_billerverification'");
     $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
     $api_url = $fetch_restapi1->api_url;

     curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
                'billercode'=>$bcode,
                'billercustomerid'=>$my_custid
            ]),
        CURLOPT_HTTPHEADER => array(
            "Authorization: ".$rubbiesSecKey,
            "Content-Type: application/json"
        ),
    ));
        
    $response = curl_exec($curl);
    $rubbies_generate = json_decode($response, true);

  	if($rubbies_generate['responsecode'] == "00"){
  		echo "<b style='font-size:18px;'>".$rubbies_generate['customername']."</b>";
  		echo '<input name="cust_name" type="hidden" value="'.$rubbies_generate['customername'].'">';
  	}
  	else{
  		echo "<b style='font-size:18px;'>".$rubbies_generate['responsemessage']."</b>";
  	}
}
?>