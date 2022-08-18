<?php
include("../config/session1.php");

$api_name =  "display_card_bal";
$search_restapi = mysqli_query($link, "SELECT * FROM atmcard_gateway_apis WHERE api_name = '$api_name' AND issuer_name = '$iissurer'");
$fetch_restapi = mysqli_fetch_object($search_restapi);
$api_url = $fetch_restapi->api_url;
  
$systemset = mysqli_query($link, "SELECT * FROM systemset");
$row1 = mysqli_fetch_object($systemset);
$bancore_merchantID = $row1->bancore_merchant_acctid;
$bancore_mprivate_key = $row1->bancore_merchant_pkey;
      
$passcode = $icard_id.$bancore_merchantID.$bancore_mprivate_key;
$encKey = hash('sha256',$passcode);

function cardLoader($icard_id, $debug=false){
      global $bancore_merchantID,$encKey,$link,$api_url;
        		
      $url = '?cardID='.$icard_id;
      $url.= '&merchantID='.$bancore_merchantID;
      $url.= '&encKey='.$encKey;
                      		  
      $urltouse =  $api_url.$url;
        			  
      //if ($debug) { echo "Request: <br>$urltouse<br><br>"; }
        		
      //Open the URL to send the message
      $response = file_get_contents($urltouse);
                      
      if ($debug) {
           //echo "Response: <br><pre>".
          //str_replace(array("<",">"),array("&lt;","&gt;"),$response).
          //"</pre><br>"; 
      	//echo substr($response, 112);        				   
      }
      return($response);
	}

$debug = true;
$processBal = cardLoader($icard_id,$debug);  
$iparr = split ("\&", $processBal);
$cardBal = substr("$iparr[1]",7);
$cardCur = substr("$iparr[2]",9);

echo "Card Balance: ".$cardCur.number_format($cardBal,2,'.',',');
?>