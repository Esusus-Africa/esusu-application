<?php

function callAPI($method, $url, $data){
   $curl = curl_init();

   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
         break;
      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }

   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "MERCHANT_ID: $remita_merchantid",
    "API_KEY: $remita_apikey",
    "REQUEST_ID: $requestId",
    "REQUEST_TS: $request_ts",
    "API_DETAILS_HASH: $hash2"
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

   // EXECUTE:
   $result5 = curl_exec($curl);
   if(curl_error($curl)){
		echo 'error:' . curl_error($curl);
	}
   curl_close($curl);
   return $result5;
}
?>