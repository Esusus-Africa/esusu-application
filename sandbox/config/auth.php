<?php
$db = new Database();
$user = new User($db);
$wallet = new Wallet($db);
$http = new HttpResponse();
$wallethttp = new WalletHttpResponse();

if(!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {

    $http->notAuthorized("Authentication is required to have access to our REST API Service");

    exit();

}else {

    $username = $_SERVER['PHP_AUTH_USER'];

    $password = $_SERVER['PHP_AUTH_PW'];

    $encrypt_pass = base64_encode($password);

    //FOR STAFF
    $staff = $db->userAuth($username, $encrypt_pass);
    $companyDetails = $db->fetchMemberSettings($staff['created_by']);
    $verifytill = $user->fetchTillAcct($staff['id']);

    //FOR CUSTOMER/BORROWER
    $customer = $db->borrowerAuth($username, $password);
    $customerCompanyDetails = $db->fetchMemberSettings($customer['branchid']);

    if($staff === 0 && $customer === 0){

        $http->notFound('User not found! Please try again later!!');
        exit();

    }else {

        $registeral = ($staff === 0) ? $customer['branchid'] : $staff['created_by'];
        $reg_branch = ($staff === 0) ? $customer['sbranchid'] : $staff['branchid'];
        $reg_staffid = ($staff === 0) ? $customer['account'] : $staff['id'];
        $reg_staffName = ($staff === 0) ? $customer['lname']." ".$customer['fname']." ".$customer['mname'] : $staff['lname']." ".$staff['name']." ".$staff['mname'];
        $reg_fName = ($staff === 0) ? $customer['fname'] : $staff['name'];
        $reg_lName = ($staff === 0) ? $customer['lname'] : $staff['lname'];
        $reg_mName = ($staff === 0) ? $customer['mname'] : $staff['mname'];
        $reg_mEmail = ($staff === 0) ? $customer['email'] : $staff['email'];
        $allow_auth = ($staff === 0) ? "No" : $staff['allow_auth'];
        $companyName = ($staff === 0) ? $customerCompanyDetails['cname'] : $companyDetails['cname'];
        $mytpin = ($staff === 0) ? $customer['tpin'] : $staff['tpin'];
        $userid = ($staff === 0) ? $customer['username'] : $staff['username'];
        $passkey = ($staff === 0) ? $customer['password'] : base64_decode($staff['password']);
        $virtualAcctNo = ($staff === 0) ? $customer['virtual_acctno'] : $staff['virtual_acctno'];
        $iacctType = ($staff === 0) ? "customer" : "agent";
        $availableWalletBal = ($staff === 0) ? $customer['wallet_balance'] : $staff['transfer_balance'];
        $cust_reg = ($staff === 0) ? "" : $staff['cust_reg'];
        $deposit = ($staff === 0) ? "" : $staff['deposit'];
        $withdrawal = ($staff === 0) ? "" : $staff['withdrawal'];
        $irole = ($staff === 0) ? "" : $staff['role'];
        $tillBalance = ($staff === 0) ? "" : $verifytill['balance'];
        $tillCommission = ($staff === 0) ? "" : $verifytill['commission_balance'];
        $tillCommissionType = ($staff === 0) ? "" : $verifytill['commission_type'];
        $myimage = ($staff === 0) ? $customer['image'] : $staff['image'];

    }

}
?>