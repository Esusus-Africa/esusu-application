<?php

/**
 * Class for all Identity Verification
 * @BVN
 * @International Passport
 * @NIN Search
 * @Phone Search
 * 
 * Created by AKINADE AYODEJI TIMOTHEW on 3/7/2021
 * Objective: building to scale
 */
 
error_reporting(E_ERROR | E_PARSE);
 
class identityVerification{

    public $bvn, $phone, $docNo, $ninNo, $verificationAccessKey;

    public function __construct($link){
        
        $this->link = $link;
        $this->bvn = $bvn;
        $this->phone = $phone;
        $this->docNo = $docNo;
        $this->ninNo = $ninNo;
        $this->verificationAccessKey = $verificationAccessKey;

    }

    //NIN Verification
    public function ninVerification($ninNo, $verificationAccessKey) {

        $curl = curl_init();

        $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'idverify_identityApiUrl'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url.'/verifyNIN';

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('nin' => $ninNo),
            CURLOPT_HTTPHEADER => array(
              "Content-Type: application/json",
              "Authorization: Bearer ".$verificationAccessKey
            ),
          ));
          
        $response = curl_exec($curl);
          
        return $response;

    }

    //Phone Number Verification
    public function phoneVerification($phone, $verificationAccessKey) {

        $curl = curl_init();

        $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'idverify_identityApiUrl'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url.'/verifyPhone';

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('phone' => $phone),
            CURLOPT_HTTPHEADER => array(
              "Content-Type: application/json",
              "Authorization: Bearer ".$verificationAccessKey
            ),
          ));
          
        $response = curl_exec($curl);
          
        return $response;

    }

    //Document Verification (BVN OR International Passport)
    public function docVerification($docNo, $verificationAccessKey) {

        $curl = curl_init();

        $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'idverify_identityApiUrl'");
        $fetch_restapi = mysqli_fetch_object($search_restapi);
        $api_url = $fetch_restapi->api_url.'/verifyDocNo';

        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('docNo' => $docNo),
            CURLOPT_HTTPHEADER => array(
              "Content-Type: application/json",
              "Authorization: Bearer ".$verificationAccessKey
            ),
          ));
          
        $response = curl_exec($curl);
          
        return $response;

    }

}

$verifyIdentity = new identityVerification($link);

?>