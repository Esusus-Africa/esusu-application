<?php

/**
 * Sample Class to test the PGP library.
 * 
 * Created by osemeodigie on 18/03/2019
 * Objective: building to scale
 */

require_once '../coralpay/coralpay-pgp-php/CoralPayPGPEncryption.php';
require_once 'restful_apicall3.php';

class EncryptTestCase
{
    private $gpg;
    
    public $postdata;
    
    public $api_url1;

    public function __construct() {
        $this->testingEncrypt($postdata,$api_url1);
        $this->testingDecrypt();
    }

    /**
     * Testing the Encrypt part of the function
     * @group string
     */
    public function testingEncrypt($postdata,$api_url1)
    {
        global $postdata, $api_url1;
        
        $options_array = array();
        $this->gpg = new CoralPayPGPEncryption($options_array);
        
        $data = $postdata;
        
        putenv("PUBLIC_KEY=A4F1A019E080E413D351459F6CB294F47467C995");

        $keyId = getenv('PUBLIC_KEY');// Other guys public key  2544070920@001#2
        
        $api_url = $api_url1; //"https://testdev.coralpay.com/cgateproxy/api/invokereference";

        $encryptedData = $this->gpg->encryptRequest($data, $keyId);
        
        $finalEncryptedData = callAPI('POST', $api_url, $encryptedData);
        
        return $finalEncryptedData;
        
    }

    /**
     * Testing the Decrypt part of the function
     * @group string
     */
    public function testingDecrypt()
    {
        $options_array = array();
        $this->gpg = new CoralPayPGPEncryption($options_array);
        
        $encryptedData = $this->testingEncrypt($postdata,$api_url1);
        
        putenv("PRIVATE_KEY=41940AB762D67A8F1E5EC5942496B3CD1DE2CC52");
        
        putenv("PASSWORD=EsusuCoralPay@2020");
        
        $keyId = getenv('PRIVATE_KEY');// Your private key
        
        $passphrase = getenv('PASSWORD');
        
        $decryptedData = $this->gpg->decryptResponse($encryptedData, $keyId, $passphrase);

        return $decryptedData;

    }
}

$new = new EncryptTestCase;

?>
