<?php

namespace Class\LoginAuth;

require_once '../Interface/interface.LoginAuth.php';
require_once 'class.Notification.php';

use Interface\LoginAuth\LoginAuthentication as MyLoginAuth;
use Class\Notification\Notifier as Notifier;

class MyLogin extends App implements MyLoginAuth {

    //Function to login using general login page
    public static function generalLogin($parameter, $session){

        $username = $this->db->sanitizeInput($parameter['username']);
        $password = $this->db->sanitizeInput($parameter['password']);

        //Get Information from User IP Address
        $myip = $this->db->getUserIP();
        $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$myip), true);
        $latitude = $dataArray["geoplugin_latitude"];
        $longitude = $dataArray["geoplugin_longitude"];
        $newLocation = $dataArray["geoplugin_city"].", ".$dataArray["geoplugin_countryName"].".";
        $date_time = date("Y-m-d h:i:s");

        //Password encoding / otpCode generator
        $encrypt = base64_encode($password);
	    $otpCode = substr((uniqid(rand(),1)),3,6);

        //Validate User Details
        $validateUser = $this->fetchWithTwoParam('user', 'username', $username, 'password', $encrypt);
        $companyid = $validateUser['created_by'];
        $branchid = $validateUser['branchid'];
        $staffRole = $validateUser['role'];
        $userStatus = $validateUser['comment'];
        $email = $validateUser['email'];

        //Validate Aggregator Details
        $validateAggr = $this->fetchWithTwoParam('aggregator', 'username', $username, 'password', $encrypt);
        $agcompanyid = $validateAggr['merchantid'];
	    $agemail = $validateAggr['email'];

        //Get Information from User Browser
        $ua = $this->db->getBrowser();
        $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
        $browserName = $ua['name'] . " " . $ua['version'];
        $deviceName = $ua['platform'];

        //Check Cookies
        $cookiesNum = $this->fetchWithThreeParam('session_tracker', 'username', $username, 'mybrowser', $yourbrowser, 'loginStatus', 'On');
        $browserid = md5($yourbrowser.$username.$longitude.$latitude.uniqid());

        //Check previous session details
        $sessionNum1 = $this->fetchWithOneParam('session_tracker', 'username', $username);
        $sessionStatus = $sessionNum1['loginStatus'];
        $previousIpAddress = $sessionNum1['ipAddress'];
        $previousLatitide = $sessionNum1['latitude'];
        $previousLongitude = $sessionNum1['longitude'];
        $previousBroswer = $sessionNum1['mybrowser'];
        $pageHeader = "Authorize New Device";

        $activities_tracked = $username . " make attempt to login with ip address: " . $myip . " from: " . $newLocation;

        if(!$this->db->internetConnectionStatus($_SERVER['HTTP_HOST'])){

            return -1; //You seem to be offline. Please check your internet connection..
        
        }elseif($validateUser === 0 && $validateAggr === 0){

            return -2; //Invalid Login Details

        }elseif($validateUser != 0 && $userStatus == "Not-Activated"){

            return -3; //Sorry, Your account has not been activated!

        }elseif($validateUser != 0 && $userStatus == "Deactivated"){

            return -4; //Opps!, Your account has been Deactivated! Contact our support for further inquiry!!

        }elseif($validateUser != 0 && $userStatus == "Pending"){

            return -5; //Opps! Your account still under review. Contact our support for further inquiry!!

        }elseif($validateUser != 0 && $userStatus == "Suspended"){

            return -6; //Sorry, Your account has been Suspended! Contact our support for more details!!

        }elseif($validateUser != 0 && $userStatus == "Approved" && $staffRole == "aggregator"){//AGGREGATOR LOGIN

            $session->set('tid', $validateUser['id']);
            $mydata = $validateUser['id']."|".$username;

            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", "", time()-3600) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", $browserid, time()+30*24*60*60) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? $mycookies = $_COOKIE['PHPSESSID'] : "";

            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->updateWithOneParam('session_tracker', 'loginStatus', 'Off', 'username', $username) : "";
            ($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser" && $allow_login_otp == "Yes") ? $this->updateManytoMany($parameter, ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], $parameter3, $parameter4) : "";
            ($sessionNum1 != 0 && ($sessionStatus == "Off" || $sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && ($allow_login_otp == "No" || $allow_login_otp == "")) ? $this->updateManytoMany($parameter, ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], $parameter3, $parameter4) : "";

            ($sessionNum1 == 0) ? $querySessionTracker = "INSERT INTO session_tracker(companyid, userid, username, ipAddress, latitude, longitude, browserSession, mybrowser, loginStatus, FirstVisitDateTime, LastVisitDateTime) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
		    ($sessionNum1 == 0) ? $this->db->insertSessionTracker($querySessionTracker, $companyid, $userid, $username, $myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time, $date_time) : "";

            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $queryOtp = "INSERT INTO otp_confirmation(userid, otp_code, data, status, datetime) VALUES(?, ?, ?, ?, ?)" : "";
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->db->insertOTPConfirmation($queryOtp, 'aggregator', $otpCode, $mydata, 'Pending', $date_time) : "";

            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $companyid, 'aggregator', $username, $myip, $yourbrowser, $activities_tracked, $branchid, "Success", $date_time);

            return ((($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? "Success" : (($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? "Otp" : "Failed"));

        }elseif($validateUser != 0 && $userStatus == "Approved" && $staffRole != "aggregator" && $companyid == ""){//ESUSUAFRICA LOGIN

            $session->set('tid', $validateUser['id']);
		    $mydata = $validateUser['id']."|".$username;

            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 === 0) ? setcookie("PHPSESSID", "", time()-3600) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 === 0) ? setcookie("PHPSESSID", $browserid, time()+30*24*60*60) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 === 0) ? $mycookies = $_COOKIE['PHPSESSID'] : "";

            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? $this->updateWithOneParam('session_tracker', 'loginStatus', 'Off', 'username', $username) : "";
            ($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser" && $allow_login_otp == "Yes") ? $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username) : "";
            ($sessionNum1 != 0 && ($sessionStatus == "Off" || $sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && ($allow_login_otp == "No" || $allow_login_otp == "")) ? $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username) : "";

            ($sessionNum1 === 0) ? $querySessionTracker = "INSERT INTO session_tracker(companyid, userid, username, ipAddress, latitude, longitude, browserSession, mybrowser, loginStatus, FirstVisitDateTime, LastVisitDateTime) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
		    ($sessionNum1 === 0) ? $this->db->insertSessionTracker($querySessionTracker, $companyid, $userid, $username, $myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time, $date_time) : "";

            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $queryOtp = "INSERT INTO otp_confirmation(userid, otp_code, data, status, datetime) VALUES(?, ?, ?, ?, ?)" : "";
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->db->insertOTPConfirmation($queryOtp, 'esusuafrica', $otpCode, $mydata, 'Pending', $date_time) : "";

            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $companyid, 'esusuafrica', $username, $myip, $yourbrowser, $activities_tracked, $branchid, "Success", $date_time);

            return ((($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No" || $allow_login_otp == "") ? "Success" : (($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? "Otp" : "Failed"));

        }

    }

    //Function to login using personalize login page
    public static function personalizeLogin($parameter, $session){

        $username = $this->db->sanitizeInput($parameter['username']);
        $password = $this->db->sanitizeInput($parameter['password']);
        $senderid = $this->db->sanitizeInput($parameter['senderid']);

        //Get Information from User IP Address
        $myip = $this->db->getUserIP();
        $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$myip), true);
        $latitude = $dataArray["geoplugin_latitude"];
        $longitude = $dataArray["geoplugin_longitude"];
        $newLocation = $dataArray["geoplugin_city"].", ".$dataArray["geoplugin_countryName"].".";
        $date_time = date("Y-m-d h:i:s");

        //Password encoding / otpCode generator
        $encrypt = base64_encode($password);
	    $otpCode = substr((uniqid(rand(),1)),3,6);

        //Fetch SID
        $fetch_sid = $this->fetchWithOneParam('member_settings', 'sender_id', $senderid);
        $companyid = $fetch_sid['companyid'];
	    $allow_login_otp = $fetch_sid['allow_login_otp'];

        //Get Email Config
        $fetch_emailConfig = $this->fetchWithOneParam('email_config', 'companyid', $companyid);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated

        //Validate User Details
        $validateUser = $this->fetchWithThreeParam('user', 'username', $username, 'password', $encrypt, 'created_by', $companyid);
        $userid = $validateUser['id'];
        $staffRole = $validateUser['role'];
        $userStatus = $validateUser['comment'];
        $email = $validateUser['email'];
        $bprefix = $validateUser['bprefix'];
        $iUpgstatus = $validateUser['status'];
        $ibranchid = $validateUser['branchid'];

        //Validate Customer Details
        $validateBorrower = $this->fetchWithThreeParam('borrowers', 'username', $username, 'password', $password, 'branchid', $companyid);
        $buserid = $validateBorrower['id'];
        $acct_status = $validateBorrower['acct_status'];
        $upgradestatus = $validateBorrower['status'];
        $bemail = $validateBorrower['email'];
        $bbranchid = $validateUser['sbranchid'];

        $branchid = ($validateUser != 0 && $validateBorrower === 0) ? $ibranchid : $bbranchid;

        //Get Information from User Browser
        $ua = $this->db->getBrowser();
        $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
        $browserName = $ua['name'] . " " . $ua['version'];
        $deviceName = $ua['platform'];

        //Check Cookies
        $cookiesNum = $this->fetchWithThreeParam('session_tracker', 'username', $username, 'mybrowser', $yourbrowser, 'loginStatus', 'On');
        $browserid = md5($yourbrowser.$username.$longitude.$latitude.uniqid());

        //Check previous session details
        $sessionNum1 = $this->fetchWithOneParam('session_tracker', 'username', $username);
        $sessionStatus = $sessionNum1['loginStatus'];
        $previousIpAddress = $sessionNum1['ipAddress'];
        $previousLatitide = $sessionNum1['latitude'];
        $previousLongitude = $sessionNum1['longitude'];
        $previousBroswer = $sessionNum1['mybrowser'];
        $pageHeader = "Authorize New Device";

        $activities_tracked = $username . " make attempt to login with ip address: " . $myip . " from: " . $newLocation;

        if(!$this->db->internetConnectionStatus($_SERVER['HTTP_HOST'])){

            return -1; //You seem to be offline. Please check your internet connection..
        
        }elseif($validateUser === 0 && $validateBorrower === 0){

            return -2; //Invalid Login Details

        }elseif(($validateUser != 0 && $validateBorrower === 0 && $userStatus == "Not-Activated") || ($validateBorrower != 0 && $validateUser === 0 && $acct_status == "Not-Activated")){

            return -3; //Sorry, Your account has not been activated!

        }elseif(($validateUser != 0 && $validateBorrower === 0 && $userStatus == "Deactivated") || ($validateBorrower != 0 && $validateUser === 0 && $acct_status == "Deactivated")){

            return -4; //Opps!, Your account has been Deactivated! Contact our support for further inquiry!!

        }elseif(($validateUser != 0 && $validateBorrower === 0 && $userStatus == "Pending") || ($validateBorrower != 0 && $validateUser === 0 && $acct_status == "Pending")){

            return -5; //Opps! Your account still under review. Contact our support for further inquiry!!

        }elseif(($validateUser != 0 && $validateBorrower === 0 && $userStatus == "Suspended") || ($validateBorrower != 0 && $validateUser === 0 && $acct_status == "Suspended")){

            return -6; //Sorry, Your account has been Suspended! Contact our support for more details!!

        }elseif(($validateUser != 0 && $validateBorrower === 0 && $iUpgstatus == "Suspended") || ($validateBorrower != 0 && $validateUser === 0 && $upgradestatus == "Suspended")){

            return -6; //Sorry, Your account has been Suspended! Contact our support for more details!!

        }elseif($validateUser != 0 && $validateBorrower === 0 && $userStatus == "Disapproved"){

            return -7; //Sorry, Your account have been disabled! Contact our support for more details!!

        }elseif($validateUser === 0 && $validateBorrower != 0 && $acct_status == "Locked"){

            return -8; //Sorry, Your account has been locked! Contact our support for more details!!

        }elseif($validateUser === 0 && $validateBorrower != 0 && $acct_status == "Activated"){//CUSTOMER LOGIN

            $session->set('tid', $validateBorrower['id']);
            $session->set('acctno', $validateBorrower['account']);
            $session->set('bbranchid', $validateBorrower['branchid']);
            $mydata = $validateBorrower['id']."|".$validateBorrower['account']."|".$validateBorrower['branchid']."|".$username."|".$senderid;

            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? Notifier::loginOtpNotifier($bemail, $pageHeader, $newLocation, $myip, $browserName, $deviceName, $otpCode, $companyid) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", "", time()-3600) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", $browserid, time()+30*24*60*60) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? $mycookies = $_COOKIE['PHPSESSID'] : "";
            
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->updateWithOneParam('session_tracker', 'loginStatus', 'Off', 'username', $username) : "";
            ($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser" && $allow_login_otp == "Yes") ? $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username) : "";
            ($sessionNum1 != 0 && ($sessionStatus == "Off" || $sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && ($allow_login_otp == "No" || $allow_login_otp == "")) ? $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username) : "";

            ($sessionNum1 === 0) ? $querySessionTracker = "INSERT INTO session_tracker(companyid, userid, username, ipAddress, latitude, longitude, browserSession, mybrowser, loginStatus, FirstVisitDateTime, LastVisitDateTime) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
		    ($sessionNum1 === 0) ? $this->db->insertSessionTracker($querySessionTracker, $companyid, $buserid, $username, $myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time, $date_time) : "";

            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $queryOtp = "INSERT INTO otp_confirmation(userid, otp_code, data, status, datetime) VALUES(?, ?, ?, ?, ?)" : "";
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->db->insertOTPConfirmation($queryOtp, 'customer', $otpCode, $mydata, 'Pending', $date_time) : "";
            
            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $companyid, 'customer', $username, $myip, $yourbrowser, $activities_tracked, $branchid, "Success", $date_time);

            return ((($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? "Success" : (($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? "Otp" : "Failed"));

        }elseif($validateUser != 0 && $validateBorrower === 0 && $userStatus == "Approved" && ($bprefix == "AG" || $bprefix == "INS" || $bprefix == "MER")){//INSTITUTION LOGIN

            $session->set('tid', $validateUser['id']);
            $session->set('instid', $validateUser['created_by']);
            $session->set('istaff', $validateUser['username']);
            $mydata = $validateUser['id']."|".$validateUser['created_by']."|".$validateUser['username']."|".$username."|".$senderid;

            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? Notifier::loginOtpNotifier($email, $pageHeader, $newLocation, $myip, $browserName, $deviceName, $otpCode, $companyid) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", "", time()-3600) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", $browserid, time()+30*24*60*60) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? $mycookies = $_COOKIE['PHPSESSID'] : "";
            
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->updateWithOneParam('session_tracker', 'loginStatus', 'Off', 'username', $username) : "";
            ($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser" && $allow_login_otp == "Yes") ? $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username) : "";
            ($sessionNum1 != 0 && ($sessionStatus == "Off" || $sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && ($allow_login_otp == "No" || $allow_login_otp == "")) ? $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username) : "";
            
            ($sessionNum1 === 0) ? $querySessionTracker = "INSERT INTO session_tracker(companyid, userid, username, ipAddress, latitude, longitude, browserSession, mybrowser, loginStatus, FirstVisitDateTime, LastVisitDateTime) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
		    ($sessionNum1 === 0) ? $this->db->insertSessionTracker($querySessionTracker, $companyid, $userid, $username, $myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time, $date_time) : "";
            
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $queryOtp = "INSERT INTO otp_confirmation(userid, otp_code, data, status, datetime) VALUES(?, ?, ?, ?, ?)" : "";
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->db->insertOTPConfirmation($queryOtp, 'institution', $otpCode, $mydata, 'Pending', $date_time) : "";
            
            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $companyid, 'institution', $username, $myip, $yourbrowser, $activities_tracked, $branchid, "Success", $date_time);

            return ((($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? "iSuccess" : (($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? "iOtp" : "iFailed"));

        }elseif($validateUser != 0 && $validateBorrower === 0 && $userStatus == "Approved" && $bprefix == "VEN"){//VENDOR LOGIN

            $session->set('tid', $validateUser['id']);
            $session->set('vendorid', $validateUser['branchid']);
            $session->set('merchantid', $validateUser['created_by']);
            $session->set('vstaff', $validateUser['username']);
            $mydata = $validateUser['id']."|".$validateUser['branchid']."|".$validateUser['created_by']."|".$validateUser['username']."|".$username."|".$senderid;

            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? Notifier::loginOtpNotifier($email, $pageHeader, $newLocation, $myip, $browserName, $deviceName, $otpCode, $companyid) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", "", time()-3600) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", $browserid, time()+30*24*60*60) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? $mycookies = $_COOKIE['PHPSESSID'] : "";
            
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->updateWithOneParam('session_tracker', 'loginStatus', 'Off', 'username', $username) : "";
            ($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser" && $allow_login_otp == "Yes") ? $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username) : "";
            ($sessionNum1 != 0 && ($sessionStatus == "Off" || $sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && ($allow_login_otp == "No" || $allow_login_otp == "")) ? $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username) : "";

            ($sessionNum1 === 0) ? $querySessionTracker = "INSERT INTO session_tracker(companyid, userid, username, ipAddress, latitude, longitude, browserSession, mybrowser, loginStatus, FirstVisitDateTime, LastVisitDateTime) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
		    ($sessionNum1 === 0) ? $this->db->insertSessionTracker($querySessionTracker, $companyid, $userid, $username, $myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time, $date_time) : "";
            
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $queryOtp = "INSERT INTO otp_confirmation(userid, otp_code, data, status, datetime) VALUES(?, ?, ?, ?, ?)" : "";
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->db->insertOTPConfirmation($queryOtp, 'vendor', $otpCode, $mydata, 'Pending', $date_time) : "";

            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $companyid, 'vendor', $username, $myip, $yourbrowser, $activities_tracked, $branchid, "Success", $date_time);

            return ((($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? "vSuccess" : (($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? "vOtp" : "vFailed"));

        }

    }

    //Function to handle universal login incase of system timeout
    public static function universalLogin($parameter, $session){

        $username = $this->db->sanitizeInput($parameter['username']);
        $password = $this->db->sanitizeInput($parameter['password']);
        
        //Get Information from User IP Address
        $myip = $this->db->getUserIP();
        $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$myip), true);
        $latitude = $dataArray["geoplugin_latitude"];
        $longitude = $dataArray["geoplugin_longitude"];
        $newLocation = $dataArray["geoplugin_city"].", ".$dataArray["geoplugin_countryName"].".";
        $date_time = date("Y-m-d h:i:s");

        //Password encoding / otpCode generator
        $encrypt = base64_encode($password);
	    $otpCode = substr((uniqid(rand(),1)),3,6);
        
        //Validate User Details
        $validateUser = $this->fetchWithTwoParam('user', 'username', $username, 'password', $encrypt);
        $userid = $validateUser['id'];
        $staffRole = $validateUser['role'];
        $userStatus = $validateUser['comment'];
        $email = $validateUser['email'];
        $bprefix = $validateUser['bprefix'];
        $iUpgstatus = $validateUser['status'];

        //Validate Customer Details
        $validateBorrower = $this->fetchWithTwoParam('borrowers', 'username', $username, 'password', $password);
        $buserid = $validateBorrower['id'];
        $acct_status = $validateBorrower['acct_status'];
        $upgradestatus = $validateBorrower['status'];
        $bemail = $validateBorrower['email'];
        
        $companyid = (($validateUser != 0 && $validateBorrower === 0) ? $validateUser['created_by'] : (($validateUser === 0 && $validateBorrower != 0) ? $validateBorrower['branchid'] : ""));
        $branchid = (($validateUser != 0 && $validateBorrower === 0) ? $validateUser['branchid'] : (($validateUser === 0 && $validateBorrower != 0) ? $validateBorrower['sbranchid'] : ""));

        //Fetch SID
        $fetch_sid = $this->fetchWithOneParam('member_settings', 'companyid', $companyid);
        $senderid = $fetch_sid['sender_id'];
	    $allow_login_otp = $fetch_sid['allow_login_otp'];

        //Get Email Config
        $fetch_emailConfig = $this->fetchWithOneParam('email_config', 'companyid', $companyid);
        $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated

        //Get Information from User Browser
        $ua = $this->db->getBrowser();
        $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
        $browserName = $ua['name'] . " " . $ua['version'];
        $deviceName = $ua['platform'];

        //Check Cookies
        $cookiesNum = $this->fetchWithThreeParam('session_tracker', 'username', $username, 'mybrowser', $yourbrowser, 'loginStatus', 'On');
        $browserid = md5($yourbrowser.$username.$longitude.$latitude.uniqid());

        //Check previous session details
        $sessionNum1 = $this->fetchWithOneParam('session_tracker', 'username', $username);
        $sessionStatus = $sessionNum1['loginStatus'];
        $previousIpAddress = $sessionNum1['ipAddress'];
        $previousLatitide = $sessionNum1['latitude'];
        $previousLongitude = $sessionNum1['longitude'];
        $previousBroswer = $sessionNum1['mybrowser'];
        $pageHeader = "Authorize New Device";

        $activities_tracked = $username . " make attempt to login with ip address: " . $myip . " from: " . $newLocation;

        if(!$this->db->internetConnectionStatus($_SERVER['HTTP_HOST'])){

            return -1; //You seem to be offline. Please check your internet connection..
        
        }elseif($validateUser === 0 && $validateBorrower === 0){

            return -2; //Invalid Login Details

        }elseif(($validateUser != 0 && $validateBorrower === 0 && $userStatus == "Not-Activated") || ($validateBorrower != 0 && $validateUser === 0 && $acct_status == "Not-Activated")){

            return -3; //Sorry, Your account has not been activated!

        }elseif(($validateUser != 0 && $validateBorrower === 0 && $userStatus == "Deactivated") || ($validateBorrower != 0 && $validateUser === 0 && $acct_status == "Deactivated")){

            return -4; //Opps!, Your account has been Deactivated! Contact our support for further inquiry!!

        }elseif(($validateUser != 0 && $validateBorrower === 0 && $userStatus == "Pending") || ($validateBorrower != 0 && $validateUser === 0 && $acct_status == "Pending")){

            return -5; //Opps! Your account still under review. Contact our support for further inquiry!!

        }elseif(($validateUser != 0 && $validateBorrower === 0 && $userStatus == "Suspended") || ($validateBorrower != 0 && $validateUser === 0 && $acct_status == "Suspended")){

            return -6; //Sorry, Your account has been Suspended! Contact our support for more details!!

        }elseif(($validateUser != 0 && $validateBorrower === 0 && $iUpgstatus == "Suspended") || ($validateBorrower != 0 && $validateUser === 0 && $upgradestatus == "Suspended")){

            return -6; //Sorry, Your account has been Suspended! Contact our support for more details!!

        }elseif($validateUser != 0 && $validateBorrower === 0 && $userStatus == "Disapproved"){

            return -7; //Sorry, Your account have been disabled! Contact our support for more details!!

        }elseif($validateUser === 0 && $validateBorrower != 0 && $acct_status == "Locked"){

            return -8; //Sorry, Your account has been locked! Contact our support for more details!!

        }elseif($validateUser != 0 && $validateBorrower === 0 && $userStatus == "Approved" && $companyid == ""){//ESUSUAFRICA LOGIN

            $session->set('tid', $validateUser['id']);
		    $mydata = $validateUser['id']."|".$username;

            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 === 0) ? setcookie("PHPSESSID", "", time()-3600) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 === 0) ? setcookie("PHPSESSID", $browserid, time()+30*24*60*60) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 === 0) ? $mycookies = $_COOKIE['PHPSESSID'] : "";

            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? $this->updateWithOneParam('session_tracker', 'loginStatus', 'Off', 'username', $username) : "";
            ($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser" && $allow_login_otp == "Yes") ? $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username) : "";
            ($sessionNum1 != 0 && ($sessionStatus == "Off" || $sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && ($allow_login_otp == "No" || $allow_login_otp == "")) ? $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username) : "";

            ($sessionNum1 === 0) ? $querySessionTracker = "INSERT INTO session_tracker(companyid, userid, username, ipAddress, latitude, longitude, browserSession, mybrowser, loginStatus, FirstVisitDateTime, LastVisitDateTime) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
		    ($sessionNum1 === 0) ? $this->db->insertSessionTracker($querySessionTracker, $companyid, $userid, $username, $myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time, $date_time) : "";

            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $queryOtp = "INSERT INTO otp_confirmation(userid, otp_code, data, status, datetime) VALUES(?, ?, ?, ?, ?)" : "";
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->db->insertOTPConfirmation($queryOtp, 'esusuafrica', $otpCode, $mydata, 'Pending', $date_time) : "";

            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $companyid, 'esusuafrica', $username, $myip, $yourbrowser, $activities_tracked, $branchid, "Success", $date_time);

            return ((($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No" || $allow_login_otp == "") ? "Success" : (($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? "Otp" : "Failed"));

        }elseif($validateUser != 0 && $validateBorrower === 0 && $userStatus == "Approved" && $staffRole == "aggregator"){//AGGREGATOR LOGIN

            $session->set('tid', $validateUser['id']);
            $mydata = $validateUser['id']."|".$username;

            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", "", time()-3600) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", $browserid, time()+30*24*60*60) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? $mycookies = $_COOKIE['PHPSESSID'] : "";

            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->updateWithOneParam('session_tracker', 'loginStatus', 'Off', 'username', $username) : "";
            ($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser" && $allow_login_otp == "Yes") ? $this->updateManytoMany($parameter, ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], $parameter3, $parameter4) : "";
            ($sessionNum1 != 0 && ($sessionStatus == "Off" || $sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && ($allow_login_otp == "No" || $allow_login_otp == "")) ? $this->updateManytoMany($parameter, ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], $parameter3, $parameter4) : "";

            ($sessionNum1 == 0) ? $querySessionTracker = "INSERT INTO session_tracker(companyid, userid, username, ipAddress, latitude, longitude, browserSession, mybrowser, loginStatus, FirstVisitDateTime, LastVisitDateTime) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
		    ($sessionNum1 == 0) ? $this->db->insertSessionTracker($querySessionTracker, $companyid, $userid, $username, $myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time, $date_time) : "";

            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $queryOtp = "INSERT INTO otp_confirmation(userid, otp_code, data, status, datetime) VALUES(?, ?, ?, ?, ?)" : "";
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->db->insertOTPConfirmation($queryOtp, 'aggregator', $otpCode, $mydata, 'Pending', $date_time) : "";

            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $companyid, 'aggregator', $username, $myip, $yourbrowser, $activities_tracked, $branchid, "Success", $date_time);

            return ((($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? "aSuccess" : (($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? "aOtp" : "aFailed"));

        }elseif($validateUser === 0 && $validateBorrower != 0 && $acct_status == "Activated"){//CUSTOMER LOGIN

            $session->set('tid', $validateBorrower['id']);
            $session->set('acctno', $validateBorrower['account']);
            $session->set('bbranchid', $validateBorrower['branchid']);
            $mydata = $validateBorrower['id']."|".$validateBorrower['account']."|".$validateBorrower['branchid']."|".$username."|".$senderid;

            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? Notifier::loginOtpNotifier($bemail, $pageHeader, $newLocation, $myip, $browserName, $deviceName, $otpCode, $companyid) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", "", time()-3600) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", $browserid, time()+30*24*60*60) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? $mycookies = $_COOKIE['PHPSESSID'] : "";
            
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->updateWithOneParam('session_tracker', 'loginStatus', 'Off', 'username', $username) : "";
            ($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser" && $allow_login_otp == "Yes") ? $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username) : "";
            ($sessionNum1 != 0 && ($sessionStatus == "Off" || $sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && ($allow_login_otp == "No" || $allow_login_otp == "")) ? $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username) : "";

            ($sessionNum1 === 0) ? $querySessionTracker = "INSERT INTO session_tracker(companyid, userid, username, ipAddress, latitude, longitude, browserSession, mybrowser, loginStatus, FirstVisitDateTime, LastVisitDateTime) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
		    ($sessionNum1 === 0) ? $this->db->insertSessionTracker($querySessionTracker, $companyid, $buserid, $username, $myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time, $date_time) : "";

            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $queryOtp = "INSERT INTO otp_confirmation(userid, otp_code, data, status, datetime) VALUES(?, ?, ?, ?, ?)" : "";
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->db->insertOTPConfirmation($queryOtp, 'customer', $otpCode, $mydata, 'Pending', $date_time) : "";
            
            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $companyid, 'customer', $username, $myip, $yourbrowser, $activities_tracked, $branchid, "Success", $date_time);

            return ((($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? "bSuccess" : (($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? "bOtp" : "bFailed"));

        }elseif($validateUser != 0 && $validateBorrower === 0 && $userStatus == "Approved" && ($bprefix == "AG" || $bprefix == "INS" || $bprefix == "MER")){//INSTITUTION LOGIN

            $session->set('tid', $validateUser['id']);
            $session->set('instid', $validateUser['created_by']);
            $session->set('istaff', $validateUser['username']);
            $mydata = $validateUser['id']."|".$validateUser['created_by']."|".$validateUser['username']."|".$username."|".$senderid;

            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? Notifier::loginOtpNotifier($email, $pageHeader, $newLocation, $myip, $browserName, $deviceName, $otpCode, $companyid) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", "", time()-3600) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", $browserid, time()+30*24*60*60) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? $mycookies = $_COOKIE['PHPSESSID'] : "";
            
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->updateWithOneParam('session_tracker', 'loginStatus', 'Off', 'username', $username) : "";
            ($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser" && $allow_login_otp == "Yes") ? $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username) : "";
            ($sessionNum1 != 0 && ($sessionStatus == "Off" || $sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && ($allow_login_otp == "No" || $allow_login_otp == "")) ? $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username) : "";
            
            ($sessionNum1 === 0) ? $querySessionTracker = "INSERT INTO session_tracker(companyid, userid, username, ipAddress, latitude, longitude, browserSession, mybrowser, loginStatus, FirstVisitDateTime, LastVisitDateTime) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
		    ($sessionNum1 === 0) ? $this->db->insertSessionTracker($querySessionTracker, $companyid, $userid, $username, $myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time, $date_time) : "";
            
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $queryOtp = "INSERT INTO otp_confirmation(userid, otp_code, data, status, datetime) VALUES(?, ?, ?, ?, ?)" : "";
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->db->insertOTPConfirmation($queryOtp, 'institution', $otpCode, $mydata, 'Pending', $date_time) : "";
            
            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $companyid, 'institution', $username, $myip, $yourbrowser, $activities_tracked, $branchid, "Success", $date_time);

            return ((($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? "iSuccess" : (($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? "iOtp" : "iFailed"));

        }elseif($validateUser != 0 && $validateBorrower === 0 && $userStatus == "Approved" && $bprefix == "VEN"){//VENDOR LOGIN

            $session->set('tid', $validateUser['id']);
            $session->set('vendorid', $validateUser['branchid']);
            $session->set('merchantid', $validateUser['created_by']);
            $session->set('vstaff', $validateUser['username']);
            $mydata = $validateUser['id']."|".$validateUser['branchid']."|".$validateUser['created_by']."|".$validateUser['username']."|".$username."|".$senderid;

            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? Notifier::loginOtpNotifier($email, $pageHeader, $newLocation, $myip, $browserName, $deviceName, $otpCode, $companyid) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", "", time()-3600) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? setcookie("PHPSESSID", $browserid, time()+30*24*60*60) : "";
            (($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? $mycookies = $_COOKIE['PHPSESSID'] : "";
            
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->updateWithOneParam('session_tracker', 'loginStatus', 'Off', 'username', $username) : "";
            ($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser" && $allow_login_otp == "Yes") ? $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username) : "";
            ($sessionNum1 != 0 && ($sessionStatus == "Off" || $sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && ($allow_login_otp == "No" || $allow_login_otp == "")) ? $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username) : "";

            ($sessionNum1 === 0) ? $querySessionTracker = "INSERT INTO session_tracker(companyid, userid, username, ipAddress, latitude, longitude, browserSession, mybrowser, loginStatus, FirstVisitDateTime, LastVisitDateTime) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
		    ($sessionNum1 === 0) ? $this->db->insertSessionTracker($querySessionTracker, $companyid, $userid, $username, $myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time, $date_time) : "";
            
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $queryOtp = "INSERT INTO otp_confirmation(userid, otp_code, data, status, datetime) VALUES(?, ?, ?, ?, ?)" : "";
            ($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser") && $allow_login_otp == "Yes") ? $this->db->insertOTPConfirmation($queryOtp, 'vendor', $otpCode, $mydata, 'Pending', $date_time) : "";

            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $companyid, 'vendor', $username, $myip, $yourbrowser, $activities_tracked, $branchid, "Success", $date_time);

            return ((($sessionNum1 != 0 && $sessionStatus == "Off" && $previousLatitide == "$latitude" && $previousLongitude == "$longitude" && $previousBroswer == "$yourbrowser") || $sessionNum1 == 0 || $allow_login_otp == "No") ? "vSuccess" : (($sessionNum1 != 0 && ($sessionStatus == "On" || $previousLatitide != "$latitude" || $previousLongitude != "$longitude" || $previousBroswer != "$yourbrowser")) ? "vOtp" : "vFailed"));

        }

    }

    //Function to confirm login OTP
    public static function loginOtpAuthorizer($parameter, $session){

        $otpCode = $this->db->sanitizeInput($parameter['otpCode']);
        $senderid = $this->db->sanitizeInput($parameter['senderid']);

        //Get Information from User IP Address
        $myip = $this->db->getUserIP();
        $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$myip), true);
        $latitude = $dataArray["geoplugin_latitude"];
        $longitude = $dataArray["geoplugin_longitude"];
        $date_time = date("Y-m-d h:i:s");

        //Confirm Institution Id
        $fetch_sid = $this->fetchWithOneParam('member_settings', 'sender_id', $senderid);
        $companyid = $fetch_sid['companyid'];

        //Validate OTP
        $fetch_data = $this->fetchWithTwoParam('otp_confirmation', 'otp_code', $otpCode, 'status', 'Pending');
        $concat = $fetch_data['data'];
        $datetime = $fetch_data['datetime'];
        $parameter = (explode('|',$concat));
        $userType = $fetch_data['userid'];

        //Get Information from User Browser
        $ua = $this->db->getBrowser();
        $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
        $browserid = md5($yourbrowser.$longitude.$latitude.uniqid());

        if($fetch_data === 0){

            return -1; //Oops!...Invalid OTP!!;
    
        }elseif($fetch_data != 0 && $userType == "customer"){

            $session->set('tid', $parameter[0]);
            $session->set('acctno', $parameter[1]);
            $session->set('bbranchid', $parameter[2]);
            $username = $parameter[3];
            $activities_tracked = $username . " make attempt to login with ip address: " . $myip . " from: " . $newLocation;

            setcookie("PHPSESSID", "", time()-3600);
            setcookie("PHPSESSID", $browserid, time()+30*24*60*60);
            $mycookies = $_COOKIE['PHPSESSID'];

            $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username);
            $this->deleteWithThreeParam('otp_confirmation', 'userid', 'customer', 'otp_code', $otpCode, 'status', 'Pending');

            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $companyid, 'customer', $username, $myip, $yourbrowser, $activities_tracked, '', "Success", $date_time);

            return "bSuccess";

        }elseif($fetch_data != 0 && $userType == "institution"){

            $session->set('tid', $parameter[0]);
            $session->set('instid', $parameter[1]);
            $session->set('istaff', $parameter[2]);
            $username = $parameter[3];
            $activities_tracked = $username . " make attempt to login with ip address: " . $myip . " from: " . $newLocation;

            setcookie("PHPSESSID", "", time()-3600);
            setcookie("PHPSESSID", $browserid, time()+30*24*60*60);
            $mycookies = $_COOKIE['PHPSESSID'];

            $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username);
            $this->deleteWithThreeParam('otp_confirmation', 'userid', 'institution', 'otp_code', $otpCode, 'status', 'Pending');

            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $companyid, 'institution', $username, $myip, $yourbrowser, $activities_tracked, '', "Success", $date_time);

            return "iSuccess";

        }elseif($fetch_data != 0 && $userType == "vendor"){

            $session->set('tid', $parameter[0]);
            $session->set('vendorid', $parameter[1]);
            $session->set('merchantid', $parameter[2]);
            $session->set('vstaff', $parameter[3]);
            $username = $parameter[4];
            $activities_tracked = $username . " make attempt to login with ip address: " . $myip . " from: " . $newLocation;

            setcookie("PHPSESSID", "", time()-3600);
            setcookie("PHPSESSID", $browserid, time()+30*24*60*60);
            $mycookies = $_COOKIE['PHPSESSID'];

            $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username);
            $this->deleteWithThreeParam('otp_confirmation', 'userid', 'vendor', 'otp_code', $otpCode, 'status', 'Pending');

            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $companyid, 'vendor', $username, $myip, $yourbrowser, $activities_tracked, '', "Success", $date_time);

            return "vSuccess";

        }elseif($fetch_data != 0 && $userType == "aggregator"){

            $session->set('tid', $parameter[0]);
            $username = $parameter[1];
            $activities_tracked = $username . " make attempt to login with ip address: " . $myip . " from: " . $newLocation;

            setcookie("PHPSESSID", "", time()-3600);
            setcookie("PHPSESSID", $browserid, time()+30*24*60*60);
            $mycookies = $_COOKIE['PHPSESSID'];

            $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username);
            $this->deleteWithThreeParam('otp_confirmation', 'userid', 'aggregator', 'otp_code', $otpCode, 'status', 'Pending');

            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $companyid, 'aggregator', $username, $myip, $yourbrowser, $activities_tracked, '', "Success", $date_time);

            return "aSuccess";

        }elseif($fetch_data != 0 && $userType == "esusuafrica"){

            $session->set('tid', $parameter[0]);
            $username = $parameter[1];
            $activities_tracked = $username . " make attempt to login with ip address: " . $myip . " from: " . $newLocation;
            
            setcookie("PHPSESSID", "", time()-3600);
            setcookie("PHPSESSID", $browserid, time()+30*24*60*60);
            $mycookies = $_COOKIE['PHPSESSID'];

            $this->updateManytoMany('session_tracker', ['ipAddress', 'latitude', 'longitude', 'browserSession', 'mybrowser', 'loginStatus', 'LastVisitDateTime'], [$myip, $latitude, $longitude, $mycookies, $yourbrowser, 'On', $date_time], 'username', $username);
            $this->deleteWithThreeParam('otp_confirmation', 'userid', 'esusuafrica', 'otp_code', $otpCode, 'status', 'Pending');

            //Audit Trail Log
            $auditTrailQuery = "INSERT INTO audit_trail(companyid, company_cat, username, ip_addrs, browser_details, activities_tracked, branchid, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertAuditTrail($auditTrailQuery, $companyid, 'esusuafrica', $username, $myip, $yourbrowser, $activities_tracked, '', "Success", $date_time);

            return "Success";
    
        }

    }

}

?>