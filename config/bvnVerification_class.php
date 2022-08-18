<?php

/**
 * Sample Class for all BVN Verification
 * 
 * Created by AKINADE AYODEJI TIMOTHEW on 28/8/2020
 * Objective: building to scale
 */
 
class verifyBVN {
    
    public $walletafrica_skey, $userBvn, $link, $bvn_picture, $dynamicStr;
    
    
    /**
     * Function to verify BVN using wallet.africa endpoint
     * @group string 
     */
    public function base64_to_jpeg($bvn_picture, $dynamicStr) {
        
        global $bvn_picture, $dynamicStr;
        
        $ifp = fopen( '../img/'.$dynamicStr, "wb" );
        fwrite( $ifp, base64_decode( $bvn_picture) );
        fclose( $ifp );
        return( $dynamicStr );
        
    }
    
    protected function encryptText($encryption_key,$data)
    {
        $source = mb_convert_encoding($encryption_key, 'UTF-16LE', 'UTF-8');
    
        $key = md5($source, true);
    
        $key .= substr($key, 0, 8);
    
         // a 128 bit (16 byte) key
         // append the first 8 bytes onto the end
    
    
        //Pad for PKCS7
        $block = mcrypt_get_block_size('tripledes', 'cbc');
        $len = strlen($data);
        $padding = $block - ($len % $block);
        $data .= str_repeat(chr($padding),$padding);
    
        $iv =  "\0\0\0\0\0\0\0\0";
    
        $encData = mcrypt_encrypt('tripledes', $key, $data, 'cbc',$iv);
    
        return base64_encode($encData);
    }
    
    public function waCallAPI($method, $url, $data){
        
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
        "Authorization: Bearer btx9cyh7332r" //Sandbox Public Key: uvjqzm5xl6bw  Live Credential: 45sn3zhmqy0d
       ));
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    
       // EXECUTE:
       $result = curl_exec($curl);
       if(curl_error($curl)){
    		echo 'error:' . curl_error($curl);
        }
        
       curl_close($curl);
       return $result;
       
    }
                                
    public function walletAfricaBVNVerifier($walletafrica_skey,$userBvn,$link){
        
        global $link, $walletafrica_skey, $userBvn;
        
        //$result = array();
        
        $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'walletafrica_bvn'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url;
                           
        $postdata =  array(
        	"bvn" => $userBvn,
        	"secretKey" => $walletafrica_skey
    	);
    	
    	$make_call = $this->waCallAPI('POST', $api_url, json_encode($postdata));
                           
        $result = json_decode($make_call, true);
        
        return $result;
        
    }


    /**
     * Function to verify BVN using Providus Bank endpoint
     * @group string
     */
    public function providusBankBVNVerifier(){
        
        
        
    }
    
    
    
    /**
     * Function to verify BVN using Sterling Bank endpoint
     * @group string
     */
    public function sterlingBankBVNVerifier(){
        
        
        
    }
    
    
}

$newBV = new verifyBVN;

?>