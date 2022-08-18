<?php
function cardLoader($card_id, $debug=false){
      global $bancore_merchantID,$encKey,$link,$api_url;
        		
      $url = '?cardID='.$card_id;
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
?>