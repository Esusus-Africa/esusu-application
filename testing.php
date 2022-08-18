<?php
  ini_set('display_errors', true);
  error_reporting(-1);
    /*$curl = curl_init();
   $id = "101";
   $reg_date = "2020-02-15 17:42:37";
   $merchantid = "AGT821067";
   $encoded = base64_encode($id.'|'.$reg_date.'|'.$merchantid);
   $myhash = hash("sha256",$encoded);
   
   $encoded3 = $id.$merchantid.date("s");
   $activationKey = crc32($encoded3);
    
    $api_url = "https://esusu.app/ea_apis/api/authLogin/";
    
    $encoded_userNmPw = base64_encode($username.':'.$password);
    
    //echo $tran_reference;
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS =>"{\n\t\"token\" : \"$myhash\"\n}",
      CURLOPT_HTTPHEADER => array(
          "Content-Type: application/json",
          "Authorization: Basic ".$encoded_userNmPw,
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    
    echo $response;*/
    
    //$string = "2,000";
    //$res = preg_replace("/[^0-9\s]/", "", $string);
    
    //echo $res;
    
    //echo strlen("+2348101750845");
    
    // One month from a specific date
    /*$todays_date = '2020-01-24 18:29:19';
    $duration = 'days';
    $lock_p = 1;
    $date = date('Y-m-d G:i:s', strtotime('+'.$lock_p.' '.$duration, strtotime($todays_date)));
    
    echo $date;*/
    
    /*$now = strtotime(date("Y-m-d h:m:s")); // or your date as well
    $your_date = strtotime("2020-02-05 00:30:51");
    $datediff = $now - $your_date;
    
    $output = round($datediff / (60 * 60 * 24));
    
    if($output <= 0){
        echo "correct: ".$output;
    }else{
        echo "not-correct: ".$output;
    }*/
    
    /*try{
        
        $soapClient = new SoapServer("http://154.113.16.142:8088/PrepaidCardServiceTest/IswPrepaidCardService.asmx?wsdl");
        
        //var_dump($soapClient->__getFunctions());
        
        $param = array('CurrencyCode'=>"566",'appId'=>"test",'appKey'=>"test");
  
        $response = $soapClient->GetAccountBalance($param);
        
        print_r($response);
        
    }catch(Exception $e){
        echo $e->getMessage();
    }*/
    
    //ini_set('log_errors', TRUE);
    
    /*try
      {
        $currency = "566";
        $appid = "test";
        $appkey = "test";

        $client = new SoapClient('http://154.113.16.142:8088/PrepaidCardServiceTest/IswPrepaidCardService.asmx?WSDL');

        $param = array(
          'CurrencyCode'=>$currency,
          'appId'=>$appid,
          'appKey'=>$appkey,
        );

        //print_r($param);
        $response = $client->GetAccountBalance($param);
        
        $process = json_decode(json_encode($response), true);

         print_r($client);
      }
      catch( Exception $e )
      {
        // You should not be here anymore
        echo $e->getMessage();
      }*/
    
    
    
    
    /*try
      {
        $currency = "566";
        $appid = "test";
        $appkey = "test";
            $context = stream_context_create( array(
              'http' => array(
                'protocol_version'=> '1.0' ,
                'header'=> 'Content-Type: text/xml;' ,
              ),
            ) );

            $options = array(
              'stream_context' => $context,
              'trace' => true,
            );

            $client = new SoapClient('http://154.113.16.142:8088/PrepaidCardServiceTest/IswPrepaidCardService.asmx?WSDL', $options);

            $param = array(
              'CurrencyCode'=>$currency,
              'appId'=>$appid,
              'appKey'=>$appkey,
            );

            //print_r($param);
  
            $response = $client->GetAccountBalance($param);
        
            $process = json_decode(json_encode($response), true);

            print_r($options);
      }
      catch( Exception $e )
      {
        // You should not be here anymore
        echo $e->getMessage();
      }*/
    
    
    //echo phpversion();
    
      /*require_once("lib/nusoap.php");

      $currency = "566";
      $appid = "test";
      $appkey = "test";

      $url = "http://154.113.16.142:8088/PrepaidCardServiceTest/IswPrepaidCardService.asmx?WSDL";
      $client = new nusoap_client($url, "wsdl");
      // 避免乱码
      $client->soap_defencoding = 'UTF-8';
      $client->decode_utf8 = false;
      $client->xml_encoding = 'UTF-8';

    //print_r($client);

      $param = array(
        "CurrencyCode"=>$currency,
        "appId"=>$appid,
        "appKey"=>$appkey
      );

      // 然后通过调用对方提供的方法
      $response = $client->call('GetAccountBalance', $param);

      $process = json_decode(json_encode($response), true);

      print_r($client);*/
      
	/*include("config/restful_apicall3.php");
	
	$result = array();
	$appid = "test";
	$appkey = "test";
	$bvn = "22144577606";
	$moi = "National Id";
    $fname = "Ayodeji";
    $lname = "Akinade";
    $phone = "08101750845";
    $addrs = "138, Akano Ifelagba Street,";
    $state = "Ibadan";
    $email = "akinadeayodeji5@hotmail.com";
    
    $api_url = "http://154.113.16.142:8088/AppDevAPI/api/PrepaidCardNewCardRequest";
	 
	$postdata =  array(
		"appId" 	=> $appid,
		"appKey" 	=> $appkey,
		"bvn" 		=> $bvn,
		"MeansOfIdNumber" 	=> $moi,
		"firstName" => $fname,
		"lastName" 	=> $lname,
		"mobileNr" 	=> $phone,
		"streetAddress" 	=> $addrs,
		"streetAddressLine2"=> $state,
		"email" 	=> $email
	);
	
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);
  	
  	print_r($result);*/
  	
  	
  	/*$link = mysqli_connect('localhost','esusulive_esusuapp','BoI9YR^zqs%M','esusulive_esusuapp') or die('Unable to Connect to Database');
  	
  	$select_cust = mysqli_query($link, "SELECT * FROM borrowers WHERE acct_opening_date = '2019-01-30'");
  	while($get_cust = mysqli_fetch_array($select_cust)){
  	    $last_with_date = $get_cust['last_withdraw_date'];
  	    $acct_opening_date = $get_cust['acct_opening_date'];
  	    $id = $get_cust['id'];
  	        
  	    mysqli_query($link, "UPDATE borrowers SET acct_opening_date = '$last_with_date' WHERE acct_opening_date = '2019-01-30' AND id = '$id' AND last_withdraw_date != '0000-00-00'");
  	        
  	}*/


    /*$resp = json_decode(json_encode($response), true);
        
    var_dump($resp);*/
        
    
    
   /* curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS =>"{\n\t\"appId\" : \"$appid\",\n\t\"appKey\" : \"$appkey\",\n\t\"bvn\" : \"$bvn\",\n\t\"MeansOfIdNumber\" : \"$moi\",\n\t\"firstName\" : \"$fname\",\n\t\"lastName\" : \"$lname\",\n\t\"mobileNr\" : \"$phone\",\n\t\"streetAddress\" : \"$addrs\",\n\t\"streetAddressLine2\" : \"$state\",\n\t\"email\" : \"$email\"\n}",
      CURLOPT_HTTPHEADER => array(
          "Content-Type: application/json",
      ),
    ));
    
    $response = curl_exec($curl);
    
    //curl_close($curl);
    
    print_r($response);*/
    
    /*function registerCardHolder($bvn_no, $moi_no, $fn, $ln, $ph, $addr, $st, $em, $debug=false){
        global $appid,$appkey,$api_url;
    		
    	$url = '?appId='.$appid;
    	$url.= '&appKey='.$appkey;
    	$url.= '&bvn='.urlencode($bvn_no);
    	$url.= '&MeansOfIdNumber='.urlencode($moi_no);
    	$url.= '&firstName='.urlencode($fn);
    	$url.= '&lastName='.urlencode($ln);
    	$url.= '&mobileNr='.urlencode($ph);
    	$url.= '&streetAddress='.urlencode($addr);
    	$url.= '&streetAddressLine2='.$st;
    	$url.= '&email='.$em;
    		
    	$urltouse =  $api_url.$url;
    	if ($debug) { echo "Request: <br>$urltouse<br><br>"; }
    		
    	//Open the URL to send the message
    	$response = file_get_contents($urltouse);
    	if ($debug) {
    		echo "Response: <br><pre>".
    	    str_replace(array("<",">"),array("&lt;","&gt;"),$response).
    		"</pre><br>"; 
    		//echo substr($response, 112);
    		//$textMsg = substr($response, 112);
    		//$textCode = substr($response, 7);
    	}
    	return($response);
    }
    		
    $debug = true;
    //echo registerCardHolder($billing_name,$phone,$billing_addrs,$billing_city,$billing_country,$zip_postalcode,$customer,$debug);
    $processCard = registerCardHolder($bvn,$phone,$moi,$fname,$lname,$phone,$addrs,$state,$email,$debug);*/
      
    
    

/*$link = mysqli_connect('localhost','esusulive_esusuapp','BoI9YR^zqs%M','esusulive_esusuapp') or die('Unable to Connect to Database');
    
 $search_sub = mysqli_query($link, "SELECT * FROM savings_subscription WHERE acn = '5048531799' ORDER BY id DESC");
 while($row_sub = mysqli_fetch_array($search_sub))
 {
     $plan_code = $row_sub['plan_code'];
     $scode = $row_sub['subscription_code'];
     $now = strtotime(date("Y-m-d h:m:s")); // or your date as well
     $date_time = strtotime($row_sub['date_time']);
     $mature_date = strtotime($row_sub['mature_date']);
     $sstatus = $row_sub['status'];
     
     $datediff = $now - $date_time;
     $current_stage = round($datediff / (60 * 60 * 24));
     
     $datediff2 = $mature_date - $date_time;
     $furture_stage = round($datediff2 / (60 * 60 * 24));
     
     $percentage_calc = ($current_stage / $furture_stage) * 100;
     
     echo "Current Stage: ".$current_stage.'<br>';
     echo "Future Stage: ".$furture_stage.'<br>';
     echo "Percent: ".$percentage_calc.'%<hr>';
      
}*/

/*
$link = mysqli_connect('localhost','esusulive_esusuapp','BoI9YR^zqs%M','esusulive_esusuapp') or die('Unable to Connect to Database');

include ("config/restful_apicalls.php");

  $systemset = mysqli_query($link, "SELECT * FROM systemset");
  $row1 = mysqli_fetch_object($systemset);
  
 $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'verify_transaction'");
  $fetch_restapi = mysqli_fetch_object($search_restapi);
  $api_url = $fetch_restapi->api_url;

  $data_array = array(
    "txref"   =>  "EA-esusuPlan-kw1lvnh52b",
    "SECKEY"  =>  "FLWSECK-79ca3a02944e95971b67b9999ce51d88-X"
  );
  
  $make_call = callAPI('POST', $api_url, json_encode($data_array));
  $response = json_decode($make_call, true);
  
  //print_r($response) ;
  
  foreach($response['data']['card']['card_tokens'] as $key)
      
      $auth_code = $key['embedtoken'];
      
      //echo "My Token: ".$auth_code;
      
      $search_restapi2 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'charge_authorization'");
      $fetch_restapi2 = mysqli_fetch_object($search_restapi2);
      $api_url2 = $fetch_restapi2->api_url;
      
      $data_array2 = array(
        "SECKEY"  =>  "FLWSECK-79ca3a02944e95971b67b9999ce51d88-X",
        "token" => $auth_code,
        "currency" => "NGN",
        "amount" => "100",
        "email" => "crescenttechnology9@gmail.com",
        "firstname" => "Ayodeji",
        "lastname" => "Akinade",
        "txRef" =>  "EA-esusuPlan-kw1lvnh52b",
        "payment_plan" => "11527"
      );
      
      $make_call2 = callAPI('POST', $api_url2, json_encode($data_array2));
      $response2 = json_decode($make_call2, true);
      
      echo $response2['status']."<br>";
      echo $response2['message'];
      
      */
  
  
  

    /*$pan = "506400110131002069";
	$validatePan = preg_match("/^([506]{3})([0-9]{1,16})$/", $pan, $match);
	
	if($validatePan){
	    echo "Matches";
	}else{
	    echo "Not-Match";
	}*/





    
    /*$now = date("Y-m-d h:m:s");
    
    $datetime1 = new DateTime($now);

    $datetime2 = new DateTime("2020-02-24 11:02:51");
    
    $difference = $datetime1->diff($datetime2);
    
    echo $difference->d.' days';*/
    

?>

<?php
/**
    $curl1 = curl_init();

    $tran_reference = "MNFY|20191124094501|006643";
    
    curl_setopt_array($curl1, array(
      CURLOPT_URL => "https://api.monnify.com/api/v1/merchant/transactions/query?transactionReference=" . urlencode($tran_reference),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => false,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Authorization:Basic TUtfUFJPRF9HSFpZR1o0U0hIOjJRTjJRVFBUU0hUS0hUVVFHWFVZVk1DNVFaVVNRR0RV"
      ],
    ));
    
    $status_response = curl_exec($curl1);
    $err = curl_error($curl1);

    curl_close($curl1);
    
    $result = json_decode($status_response, true);
    
    var_dump($result);
  **/
  
  //$key = base64_encode('zeezzplanet|123000|AGT68011836');

  //$loginAlgorithm = hash('sha256', $key);
  
 // echo  $loginAlgorithm;
 
 //putenv("SECRET_HASH=ESUSU_OF_AFRICA");
 
 //$local_signature = getenv('SECRET_HASH');
 
 //echo $local_signature;
 
 
 
 //echo date_default_timezone_get();
 
 
 
 ?>
 
 
 <?php
    
        /*$ref = $_GET['txRef'];
        $amount = ""; //Correct Amount from Server
        $currency = ""; //Correct Currency from Server
        //FLWSECK-2bad14bdc88218ffb65fc531ff597db4-X ESUSU AFRICA
        //FLWSECK-79ca3a02944e95971b67b9999ce51d88-X CRITECH
        
        //EA-esusuPlan-gvvz6d31rp - working sample
        //EA-targetSv-jrglqrwb21 - mine but not work
        //EA-targetSv-gzh1nqny8h --today

        $query = array(
            "SECKEY" => "FLWSECK-2bad14bdc88218ffb65fc531ff597db4-X",
            "txref" => "EA-targetSv-gzh1nqny8h" 
        );

        $data_string = json_encode($query);
                
        $ch = curl_init('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify');                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                              
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        curl_close($ch);

        $resp = json_decode($response, true);
        
        var_dump($resp);

      	$paymentStatus = $resp['data']['status'];
        $chargeResponsecode = $resp['data']['chargecode'];
        $chargeAmount = $resp['data']['amount'];
        $chargeCurrency = $resp['data']['currency'];

        if (($chargeResponsecode == "00" || $chargeResponsecode == "0") && ($chargeAmount == $amount)  && ($chargeCurrency == $currency)) {
          // transaction was successful...
  			 // please check other things like whether you already gave value for this ref
          // if the email matches the customer who owns the product etc
          //Give Value and return to Success page
        } else {
            //Dont Give Value and return to Failure page
        }*/
        
?>

<!--
    <form action="/action_page.php">
      Chose color:
      <input type="color" name="chosecolor" value="#336600">
      <input type="submit">
    </form>
    -->

  <?php
    /**
    //$link = mysqli_connect('esusuapp-backend.mysql.database.azure.com','esusuafrica@esusuapp-backend','MyEABackend2019','EAbackendDb') or die('Unable to Connect to Database');

              $api_name =  "display_card_bal";
              //$issurer = $get_search['card_issurer'];
              $card_id = 4857038;
          		$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = 'Bancore'");
          		$fetch_restapi = mysqli_fetch_object($search_restapi);
          		$api_url = "https://kegow.bancore.com/getit/api/merchant/balance.do";
              
              $systemset = mysqli_query($link, "SELECT * FROM systemset");
            	$row1 = mysqli_fetch_object($systemset);
            	$bancore_merchantID = $row1->bancore_merchant_acctid;
            	$bancore_mprivate_key = $row1->bancore_merchant_pkey;
              
              $passcode = $card_id.$bancore_merchantID.$bancore_mprivate_key;
            	$encKey = hash('sha256',$passcode);
              
              function cardLoader($card_id, $debug=false){
        		      global $bancore_merchantID,$encKey,$link,$api_url;
        		
        			    $url = '?cardID='.$card_id;
        		      $url.= '&merchantID='.$bancore_merchantID;
        			    $url.= '&encKey='.$encKey;
                      		  
        		      $urltouse =  $api_url.$url;
        			  
        		      if ($debug) { echo "Request: <br>$urltouse<br><br>"; }
        		
        		      //Open the URL to send the message
        		      $response = file_get_contents($urltouse);
                      
        		      if ($debug) {
        		           echo "Response: <br><pre>".
        		           str_replace(array("<",">"),array("&lt;","&gt;"),$response).
        		           "</pre><br>"; 
        				  	//echo substr($response, 112);
                    //echo substr($response,17,-28);
                    $iparr = split ("\&", $response);
        				    echo "$iparr[1] <br />";
        		      }
        		      return($response);
        		}
        		
        		$debug = true;
        		$processBal = cardLoader($card_id,$debug);  
            $iparr = split ("\&", $processBal);
            echo substr("$iparr[1]",7);
            **/
           
            
            //$originalDate = "2020-01-10";
            //$newDate = date("d/m/Y", strtotime($originalDate));
            //echo $newDate;
            
    //include("config/walletafrica_restfulapis_call.php");
    
    //$result = array();
    $result1 = array();
    //$result2 = array();
    //$result3 = array();
    
    //Creating Wallet Africa Account
    /*$api_url = "https://api.wallets.africa/wallet/create";
	
	$postdata =  array(
		"firstName" => "Ayodeji",
		"lastName" 	=> "Akinade",
		"email"     => "crescenttechnology9@gmail.com",
		"phoneNumber"=> "08183131005",
		"password"  => "Timo12345@",
		"secretKey" => "puqdxlpk94ie",
		"dateOfBirth" => "1993-10-31"
	);
					
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
	$result = json_decode($make_call, true);     
	
	print_r($result);
	echo "<br><br>";*/
	
	//Verifying Wallet Afrrica Account
	/*$api_url1 = "https://api.wallets.africa/wallet/verify";
	
	$postdata1 =  array(
		"phoneNumber"=> "08183131005",
		"otp"  => "809965", //Please fill it with correct OTP
		"secretKey" => "puqdxlpk94ie",
	);
	
	$make_call1 = callAPI('POST', $api_url1, json_encode($postdata1));
	$result1 = json_decode($make_call1, true);
	
	print_r($result1);
	echo "<br><br>";*/
	
	/*$api_url1 = "https://api.wallets.africa/wallet/nuban";
	
	$postdata1 =  array(
		"phoneNumber"=> "2348183131005",
		"secretKey" => "puqdxlpk94ie",
	);
	
	$make_call1 = callAPI('POST', $api_url1, json_encode($postdata1));
	$result1 = json_decode($make_call1, true);
	
	print_r($result1);
	echo "<br><br>"*/
	
	//Generate Account NGN
	/*$api_url2 = "https://api.wallets.africa/wallet/generate";

	$postdata2 =  array(
		"firstName" => "Ayodeji",
		"lastName"  => "Akinade",
		"email"     => "crescenttechnology9@gmail.com",
		"secretKey" => "puqdxlpk94ie",
		"dateOfBirth" => "1993-10-31",
		"currency"  => "NGN"
	);
	    
	$make_call2 = callAPI('POST', $api_url2, json_encode($postdata2));
	$result2 = json_decode($make_call2, true);
	
	print_r($result2);
	echo "<br><br>";*/
	    
	/*//Generate Account Number now
	$api_url3 = "https://sandbox.wallets.africa/wallet/generateaccountnumber";
	    
	$postdata3 =  array(
    	"phoneNumber"=> "08101750845",
    	"secretKey" => "puqdxlpk94ie"
	);
	    
	$make_call3 = callAPI('POST', $api_url3, json_encode($postdata3));
    $result3 = json_decode($make_call3, true);
    	
    print_r($result3);*/
	   
	
	/*$date = new DateTime(date("Y-m-d"));
	$date->sub(new DateInterval('P18Y'));
	
	echo $date->format('Y-m-d');*/
	
	//MINIMUM DATE
    /*$min_date = new DateTime(date("Y-m-d"));
    $min_date->sub(new DateInterval('P18Y'));
    $mymin_date = $min_date->format('Y-m-d');
    echo $mymin_date."<br>";*/
    
    //MAXIMUM DATE
    /*$max_date = new DateTime(date("Y-m-d"));
    $max_date->sub(new DateInterval('P60Y'));
    $mymax_date = $max_date->format('Y-m-d');
    echo $mymax_date;*/
    
    /*$password = substr((uniqid(rand(),1)),3,6);
    
    echo $password;*/
    
    //$res = preg_replace("/[^0-9]/", "", "NGN6,000");
    //echo $res;
    
    //$acct_no     = date("dmyis");
    //echo $acct_no;
    
    /*$text = "100*1";
    $textArray = explode('*',$text);
    $userResponse = trim(end($textArray));
    
    echo $userResponse;*/
    
    /*$timezone = new DateTimeZone('UTC');
    $dateTime = DateTime::createFromFormat('dmY', 14061991, $timezone);
    echo $dateTime->format('Y-m-d'); */
    
    /*
    $myemail = "abdulazeez�gmail.com";
    
    $explode_email = explode("�",$myemail);
    
    $confirm_correctEmail = ($explode_email[1] == "") ? $myemail : "$explode_email[0]@$explode_email[1]";
    
    echo $confirm_correctEmail;*/
    
    
    /*try
      {
          
          $api_url1 = 'http://gtweb6.gtbank.com/Gaps_FileUploader/FileUploader.asmx';
          
          $client = new SoapClient($api_url1);
          
          $response = $client->GetNIPBank();
                                    
          $process = json_decode(json_encode($response), true);
        
          print_r($process);
          
      }
      catch( Exception $e )
      {
        // You should not be here anymore
        echo $e->getMessage();
      }
      
      
      $data = 'transdetails'.'accesscode '.'username '.'password'; 
      $hashed = hash('sha512', $data); 
      echo $hashed;*/
      
     
    /*$refid = date("ymd").time();
    
    $date = date("Y/m/d");
    
    $gtbaccesscode = "205140019";
    
    $gtbusername = "adewotol";
    
    $gtbpassword = "abcd1234*";
    
    $client = "http://gtweb6.gtbank.com/Gaps_FileUploader/FileUploader.asmx?wsdl";
    
    $hashconvert = "<transactions> 
                            <transaction>  
                            <amount>2000</amount> 
                            <paymentdate>$date</paymentdate> 
                            <reference>$refid</reference> 
                            <remarks>march 2012 salary</remarks> 
                            <vendorcode>25437</vendorcode> 
                            <vendorname>mtn nigeria</vendorname> 
                            <vendoracctnumber>0002365417</vendoracctnumber> 
                            <vendorbankcode>011011279</vendorbankcode> 
                            </transaction> 
                         </transactions>";
    
    $convert = htmlentities($hashconvert);
    
    $gtbtransdetails = htmlentities($convert);
    
    require_once 'config/nipBankTransfer_class.php';
    
    $result = $new->GTBNIPBankTransfer($hashconvert,$gtbtransdetails,$gtbaccesscode,$gtbusername,$gtbpassword,$client);
    
    print_r($result);*/
    
    
    /*$link = mysqli_connect('localhost','esusulive_esusuapp','BoI9YR^zqs%M','esusulive_esusuapp') or die('Unable to Connect to Database');
    
    require_once "config/virtualBankAccount_class.php";
    
    $accountName = "Ayo Tester";
    
    $rubbiesSecKey = "SK-0001036572478810433-PROD-7270074D9D784C69B12C5975B9679E0A784EA481B9484EB4A981572A8DF198F7";
    
    $result = $newVA->rubiesVirtualAccount($accountName,$rubbiesSecKey);
    
    print_r($result);
    
    echo "<br><br>";
    
    echo $result['virtualaccount'].'<br>';
    
    echo $result['virtualaccountname'];*/
    
    
    /*$link = mysqli_connect('localhost','esusulive_esusuapp','BoI9YR^zqs%M','esusulive_esusuapp') or die('Unable to Connect to Database');
    
    require_once "config/nipBankTransfer_class.php";
    
    $rubbiesSecKey = "SK-0001036572478810433-PROD-7270074D9D784C69B12C5975B9679E0A784EA481B9484EB4A981572A8DF198F7";
    
    $recipientAcctNo = "0056164488";
    
    $recipientBankCode = "000014";
    
    $process = $new->rubiesInterBankNameEnquiry($link,$rubbiesSecKey,$recipientBankCode,$recipientAcctNo);
    
    echo $process['accountname'];
    
    print_r($process);*/


    
    //require_once "config/Class/class.aesEncryption.php";
    require_once "config/connect.php";
    require_once "config/virtualBankAccount_class.php";

    $firstName = "Ayodeji";
    $lastName = "Akinade";
    $phoneNumber = "09012505432";
    $dob = "1993-10-31";
    $gender = "Male";
    $mygender = ($gender == "Male") ? "M" : "F";
    $currencyCode = "NGN";
    $inputKey = "zAL7X5AVRm8l4Ifs";
    $iv = "BE/s3V0HtpPsE+1x";
    /*$blockSize = 256;
    
    $data_to_send_server = json_encode(['firstname'=>$firstName,'lastname'=>$lastName,'mobile'=>$phoneNumber,'DOB'=>$dob,'Gender'=>$mygender,'CURRENCYCODE'=>$currencyCode,'AccountTier'=>1,'ChannelID'=>'','ProductID'=>'','MobileNotNuban'=>true]);
    $aes = new AESEncryption($data_to_send_server, $inputKey, $iv, $blockSize);
    $encryptData = $aes->encrypt();*/

    $result = $newVA->sterlingVirtualAccount($firstName,$lastName,$phoneNumber,$dob,$gender,$currencyCode,$inputKey,$iv);

    print_r($result);


    
    /*$ip = $_SERVER['REMOTE_ADDR']; // This will contain the ip of the request
    $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip), true);
    print_r($dataArray);*/
    
    
    
   //echo date('Y-m-d').' '.(date(h) + 1).':'.date('i a');
   /*$ip = $_SERVER['REMOTE_ADDR']; // This will contain the ip of the request
   $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip), true);
   var_dump($dataArray);
   
   echo $dataArray["geoplugin_currencyCode"];
   echo $dataArray["geoplugin_currencyConverter"];*/
   
   /*
   function enarmor($data, $marker = 'MESSAGE', array $headers = array()) {
        $text = headers($marker) . "\n";
        foreach ($headers as $key => $value) {
            $text .= $key . ': ' . (string)$value . "\n";
        }
        $text .= "\n" . wordwrap(base64_encode($data), 76, "\n", true);
        $text .= "\n".'=' . base64_encode(substr(pack('N', crc24($data)), 1)) . "\n";
        $text .= footer($marker) . "\n";
        return $text;
    }
    
    function headers($marker) {
        return '-----BEGIN ' . strtoupper((string)$marker) . '-----';
    }

    function footer($marker) {
        return '-----END ' . strtoupper((string)$marker) . '-----';
    }

    function crc24($data) {
        $crc = 0x00b704ce;
        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= (ord($data[$i]) & 255) << 16;
            for ($j = 0; $j < 8; $j++) {
                $crc <<= 1;
                if ($crc & 0x01000000) {
                    $crc ^= 0x01864cfb;
                }
            }
        }
        return $crc & 0x00ffffff;
    }
    
    function strip_armor($data, $marker = 'MESSAGE') {
        // remove the noise from the encrypted data
        $data = str_replace(headers($marker) . "", '' , $data);
        $data = str_replace(footer($marker) . "", '' , $data);
        $data = trim($data, "");
        return $data;
    }
   
   include("config/restful_apicall3.php");
   
   $CONFIG['gnupg_home'] = '/home/esusulive/.gnupg';
   $CONFIG['gnupg_fingerprint'] = '41940AB762D67A8F1E5EC5942496B3CD1DE2CC52';
   $CONFIG['gnupg_encryptedkey'] = '116D8CE5FDE79164D164127742B0293CB9C0069C';
    
   $data = '{"RequestHeader": {"userName":"esusu","password": "2544070920@001#2"},' . 
                '"RequestDetails": {"terminalId":"1057ESU1","Channel":"USSD","Amount":1000,' . 
                '"MerchantId": "1057ESU10000001","TransactionType":"0","SubMerchantName":"esusu.me",' .
                '"TraceID": "20325685768"}}';
    
    $gpg = new gnupg();
    putenv("GNUPGHOME={$CONFIG['gnupg_home']}");
    $gpg->seterrormode(gnupg::ERROR_EXCEPTION);
    $gpg->addencryptkey($CONFIG['gnupg_encryptedkey']);
    $gpg->setarmor(0);
    $encrypted = $gpg->encrypt($data);
    
    $postdata = bin2hex($encrypted);
    
    echo "EsusuAfrica Encrypted text: \n<pre>$postdata</pre>\n";
    
    $api_url = "https://testdev.coralpay.com/cgateproxy/api/invokereference";
    
    $encryptedData = callAPI('POST', $api_url, $postdata);
    
    echo "CoralPay Encrypted text: \n<pre>$encryptedData</pre>\n";
    
    $convertBin = hex2bin($encryptedData);
	
	$armoredBinMessage = enarmor($convertBin, 'PGP MESSAGE');
	
	$stripAmor = strip_armor($armoredBinMessage, 'PGP MESSAGE');
    
    // Then use something like this to decrypt the data.
    $passphrase = 'EsusuCoralPay@2020';
    $gpg->adddecryptkey($CONFIG['gnupg_fingerprint'], $passphrase);
    $decrypted = $gpg->decrypt($stripAmor);
    
    echo "Final Decrypted text: \n<pre>$decrypted</pre>\n";
    */
   
   
    /*function generate_uuid() {
        return sprintf( '%04x%04x%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0C2f ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B )
        );
    
    }*/   
    
    //echo bin2hex(openssl_random_pseudo_bytes(5));
    
    //echo generate_uuid();
    
    
    /** CODE TO MIGRATE TRANSFER HISTORY TO WALLET HISTORY
     
    $link = mysqli_connect('localhost','esusulive_esusuapp','BoI9YR^zqs%M','esusulive_esusuapp') or die('Unable to Connect to Database');

     */


    
    
    
    
    //$date = date("Y-m-d", strtotime("04-01-2000"));
    
    
    /*$link = mysqli_connect('localhost','esusulive_esusuapp','BoI9YR^zqs%M','esusulive_esusuapp') or die('Unable to Connect to Database');
    
    //include("config/walletafrica_restfulapis_call.php");
    
    $receiver_txid = "EA-ref".date("mY").time();
    $amountDebited = 5;
    $receiver_virtual_number = "2341050547070";*/
    
   /* $systemset = mysqli_query($link, "SELECT * FROM systemset");
    $row1 = mysqli_fetch_array($systemset);*/
    //$sysabb = $row1->abb;
    //$walletafrica_skey = $row1['walletafrica_skey'];
    
    /*$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_debit'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $api_url = $fetch_restapi->api_url;*/
    
    /*$search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_credit'");
    $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
    $api_url1 = $fetch_restapi1->api_url;
    $receiver_txid = "EA-ref".date("mY").time();*/
            
    /*$postdata1 =  array(
        "transactionReference" => $receiver_txid,
        "amount" => $amountDebited,
        "phoneNumber" => $receiver_virtual_number,
        "secretKey" => $walletafrica_skey
    );
                               
    $make_call = callAPI('POST', $api_url1, json_encode($postdata1));
         $t1 = json_decode($make_call1, true);
            
    print_r($result1);*/
		
		/*$curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url1,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>"{\r\n  \"transactionReference\": \"$receiver_txid\",\r\n  \"amount\": $amountDebited,\r\n  \"phoneNumber\": \"$receiver_virtual_number\",\r\n  \"secretKey\": \"$walletafrica_skey\"\r\n}",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer 45sn3zhmqy0d"
          ),
        ));
        
        $response = curl_exec($curl);
        
        $transaction = json_decode($response, true);
        
        print_r($transaction)*/
    
    /*$api_url = "https://api.wallets.africa/account/resolvebvn";
                           
    $postdata =  array(
    	"bvn" => "22239765167",
    	"secretKey" => "puqdxlpk94ie"
    	);
                           
    $make_call = callAPI('POST', $api_url, json_encode($postdata));
    $result = json_decode($make_call, true);
    
    print_r($result);*/
    
    
    
    
    
    /*$search_data = mysqli_query($link, "SELECT * FROM otp_confirmation WHERE otp_code = '334371'");
    $fetch_data = mysqli_fetch_array($search_data);
    
    $concat = $fetch_data['data'];
    
    $datetime = $fetch_data['datetime'];
    
    $parameter = (explode('|',$concat));
    
    //print_r($parameter);
    
    echo $parameter[1];
    echo "<br>";
    echo $parameter[3];
    echo "<br>";
    echo ($parameter[7] == "") ? "Non" : $parameter[7];
    echo "<br>";
    echo $parameter[5];
    echo "<br>";*/
    
    /*$currenctdate = date("Y-m-d H:i:s");
    
    //echo $currenctdate;
    
    $start_date = new DateTime($datetime);
    $since_start = $start_date->diff(new DateTime($currenctdate));
    
    echo $since_start->i.' minutes<br>';*/
    
	
	//include ("config/restful_apicalls.php");
	
	/*$result = array();
    //REMITAL CREDENTIALS
    $remita_merchantid = "27768931";
    $remita_apikey = "Q1dHREVNTzEyMzR8Q1dHREVNTw==";
    $remita_serviceid = "35126630";
    $api_token = "SGlQekNzMEdMbjhlRUZsUzJCWk5saDB6SU14Zk15djR4WmkxaUpDTll6bGIxRCs4UkVvaGhnPT0=";
    $amt = 2000;
    $requestid = date("dY").time();
    
    $concat_param = $remita_merchantid.$remita_serviceid.$requestid.$amt.$remita_apikey;
    $hash = hash('sha512', $concat_param);

    $api_url = "https://remitademo.net/remita/exapp/api/v1/send/api/echannelsvc/echannel/mandate/setup";
    
    $postdata = array(
        "merchantId" => $remita_merchantid,
        "serviceTypeId" => $remita_serviceid,
        "hash"  => $hash,
        "payerName" => "Akinade Ayodeji",
        "payerEmail"  => "ayodeji@esusu.africa",
        "payerPhone"  => "+2348101750845",
        "payerBankCode" => "044",
        "payerAccount"  => "0056164488",
        "requestId" => $requestid,
        "amount"    => $amt,
        "startDate" => "03/04/2020",
        "endDate"   => "17/04/2020",
        "mandateType"   => "DD",
        "maxNoOfDebits" => "3"
    );
  
	$make_call = callAPI('POST', $api_url, json_encode($postdata));
    $result = trim(json_decode(json_encode($make_call), true),'jsonp ();');
    //print_r($result);
    
    $response = json_decode($result, true);
    
    print_r($response);
    echo $response['mandateId'];*/
    
    
    //PARAMETERS TO GENERATE COOPORATE API KEYS 
    //$hash = hash('sha256', '28|INST-41911|NG|institution');
    //echo "SECKEY_".$hash;
    
    
    
   
    
    /*$search_agent = mysqli_query($link, "SELECT * FROM agent_data");
    while($fetch_agent = mysqli_fetch_array($search_agent)){
        
        $agentid = $fetch_agent['agentid'];
        $subaccountcode = "";
        $agenttype = $fetch_agent['agenttype'];
        $institution_logo = "";
        $bname = $fetch_agent['bname'];
        $rcnumber = $fetch_agent['rcnumber'];
        $addrs = $fetch_agent['addrs'];
        $state = "";
        $country = "NG";
        $email = $fetch_agent['email'];
        $phone = $fetch_agent['phone'];
        $bvn = $fetch_agent['bvn'];
        $acct_type = "agent";
        $status = $fetch_agent['status'];
        $frontend_reg = "Enable";
        $referral_bonus = $fetch_agent['referral_bonus'];
        $wallet_balance = $fetch_agent['wallet_balance'];
        $card_id = "NULL";
        $date_time = $fetch_agent['date_time'];
        $aggr_id = $fetch_agent['aggr_id'];
        $api_key = "";
        
        mysqli_query($link, "INSERT INTO institution_data VALUES(null,'$agentid','$subaccountcode','$agenttype','$institution_logo','$bname','$rcnumber','$addrs','$state','$country','$email','$phone','$bvn','$acct_type','$status','$frontend_reg','$referral_bonus','$wallet_balance','$card_id','$date_time','$aggr_id','$api_key')");
        
    }*/
    
    
    /*$search_merchant = mysqli_query($link, "SELECT * FROM merchant_reg");
    while($fetch_merchant = mysqli_fetch_array($search_merchant)){
        
        $merchantid = $fetch_merchant['merchantID'];
        $subaccountcode = $fetch_merchant['subaccount_code'];;
        $msector = $fetch_merchant['msector'];
        $institution_logo = $fetch_merchant['mlogo'];
        $bname = $fetch_merchant['company_name'];
        $rcnumber = $fetch_merchant['mlicense_no'];
        $addrs = "";
        $state = $fetch_merchant['state'];
        $country = "NG";
        $email = $fetch_merchant['official_email'];
        $phone = $fetch_merchant['official_phone'];
        $bvn = "";
        $acct_type = "merchant";
        $status = "Approved";
        $frontend_reg = "Enable";
        $referral_bonus = $fetch_merchant['referral_bonus'];
        $wallet_balance = $fetch_merchant['wallet_balance'];
        $card_id = "NULL";
        $date_time = $fetch_merchant['date_time'];
        $aggr_id = "";
        $api_key = "";
        
        mysqli_query($link, "INSERT INTO institution_data VALUES(null,'$merchantid','$subaccountcode','$msector','$institution_logo','$bname','$rcnumber','$addrs','$state','$country','$email','$phone','$bvn','$acct_type','$status','$frontend_reg','$referral_bonus','$wallet_balance','$card_id','$date_time','$aggr_id','$api_key')");
        
    }*/
    
    
    
    /*$call20 = mysqli_query($link, "SELECT amount_to_pay, pay_date FROM payments WHERE branchid = 'INST-87507' AND remarks = 'paid'");
 
     $dataPoints = array();
     
     $timezone = date_default_timezone_get();
     
     //echo $timezone;
     
     while($fetch20 = mysqli_fetch_array($call20))
     {
         $sub_array = array();
         $date = new DateTime($fetch20['pay_date'], new DateTimeZone($timezone));
         $sub_array["x"] = date("Y", $date->format('U'));
         $sub_array["y"] = $fetch20['amount_to_pay'];
         $dataPoints[] = $sub_array;
     }    
    
     print_r($dataPoints);
     //echo json_encode($dataPoints);*/
     
     
     //echo ucwords("institution");

	/*$search_agentdoc = mysqli_query($link, "SELECT * FROM agent_legaldocuments");
    while($fetch_agentdoc = mysqli_fetch_array($search_agentdoc)){
        
        $agentid = $fetch_agentdoc['agentid'];
        $documents = $fetch_agentdoc['document'];
        
        mysqli_query($link, "INSERT INTO institution_legaldoc VALUES(null,'$agentid','$documents')");
        
    }*/
	

/*function base64_to_jpeg( $base64_string, $output_file ) {
	$ifp = fopen( 'img/'.$output_file, "wb" );
	fwrite( $ifp, base64_decode( $base64_string) );
	fclose( $ifp );
	return( $output_file );
}
$dynamicStr    = md5(date("Y-m-d h:i"));
$image_converted = base64_to_jpeg($image, $dynamicStr.".png");*/

//echo $image_converted;


//define('UPLOAD_DIR', 'cgi-bin/');

//$output_image = base64ToImage($image, UPLOAD_DIR);   
//echo $output_image;

//$outp2 = file_put_contents('/', file_get_contents($image));

?>