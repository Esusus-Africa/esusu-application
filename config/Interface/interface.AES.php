<?php
namespace Interface\myAESEncryption;

interface AESInterface {

    public function setData($parameter);

    public function setKey($key);

    public function setMethod($blockSize, $mode);

    public function setInitializationVector($iv);

    public function validateParams();

    public function getIV();

    public function encrypt();

    public function decrypt();

}

?>