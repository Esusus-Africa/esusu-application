<?php

namespace Interface\LoginAuth;

interface LoginAuthentication {

    /**
     * @param string $username
     * @param string $password
     * @param mixed $senderid
     * @param mixed $session
     */
    public static function generalLogin($parameter, $session);

    public static function personalizeLogin($parameter, $session);

    public static function universalLogin($parameter, $session);

    /**
     * @param integer $otpCode
     */
    public static function loginOtpAuthorizer($parameter, $session);

}

?>