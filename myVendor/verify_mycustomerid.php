<?php 
   if(isset($_POST['my_custid']))
   {
	 include("../config/connect.php");
	 
     $curl = curl_init();
  	 $my_custid = $_POST['my_custid'];
     $my_serviceid = $_POST['my_serviceid'];
     $my_productcode = $_POST['my_productcode'];
  	 
  	 $systemset = mysqli_query($link, "SELECT * FROM systemset");
     $row1 = mysqli_fetch_object($systemset);
     $accessToken = $row1->primeairtime_token;

	 $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'primeairtime_baseUrl'");
     $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
     $api_url = $fetch_restapi1->api_url.'/api/billpay/'.$my_serviceid.'/'.$my_productcode.'/validate';

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
                'meter'=>$my_custid
            ]),
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$accessToken
        ),
    ));
        
    $response = curl_exec($curl);
    $prime_generate = json_decode($response, true);

    echo "<b style='font-size:18px;'>".$prime_generate['name']."</b>";
  	echo '<input name="cust_name" type="hidden" value="'.$prime_generate['name'].'">';

}
?>