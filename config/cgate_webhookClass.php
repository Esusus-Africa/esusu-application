<?php

/**
 * Sample Class to test the PGP library.
 * 
 * Created by osemeodigie on 18/03/2019
 * Objective: building to scale
 */

require_once '../coralpay/coralpay-pgp-php/CoralPayPGPEncryption.php';

class EncryptTestCase
{
    private $gpg;
    
    public $postdata;

    public function __construct() {
        $this->testingEncrypt($postdata);
        $this->testingDecrypt();
    }

    /**
     * Testing the Encrypt part of the function
     * @group string
     */
    public function testingEncrypt($postdata)
    {
        global $postdata;
        
        $data = $postdata;
        
        return $data;
        
    }

    /**
     * Testing the Decrypt part of the function
     * @group string
     */
    public function testingDecrypt()
    {
        $options_array = array();
        $this->gpg = new CoralPayPGPEncryption($options_array);
        
        $encryptedData = $this->testingEncrypt($postdata);
        
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