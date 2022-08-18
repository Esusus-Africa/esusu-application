<?php

namespace Interface\Customer;

interface CustomerInterface {

    /**
     * @param string @parameter
     */
    public static function addCustomer($parameter, $getSMS_ProviderNum, $ifetch_maintenance_model, $iwallet_balance, $ismscharges, $isenderid, $mobile_applink, $icustomer_limit, $idedicated_ledgerAcctNo_prefix, $allow_auth, $icurrency, $staffname, $isavings_account);

    public static function bulkCustomerRegCSV($parameter, $getSMS_ProviderNum, $ifetch_maintenance_model, $iwallet_balance, $ismscharges, $isenderid, $mobile_applink, $icustomer_limit, $idedicated_ledgerAcctNo_prefix, $allow_auth, $clientId, $staffname);

    public static function linkCustomerAcct($parameter, $getSMS_ProviderNum, $ifetch_maintenance_model, $iwallet_balance, $ismscharges, $isenderid, $mobile_applink, $icustomer_limit, $idedicated_ledgerAcctNo_prefix, $allow_auth, $clientId, $staffname);

    public static function viewAllCustomers($parameter, $iCustRecords, $bCustRecords, $updateCustinfo, $viewAcctInfo, $viewLoanHistory, $iuid, $isbranchid);

    public static function updateCustomersInfo($parameter, $clientId, $isavings_account, $iloan_manager);

    public static function closeCustomerAccount($parameter);

    public static function activateAutoCharge($parameter);

    public static function activateAutoDisbursement($parameter);

    public static function disableAutoCharge($parameter);

    public static function disableAutoDisbursement($parameter);

    public static function viewAllPendingCustomers($parameter, $iCustRecords, $bCustRecords, $updateCustinfo, $iuid, $isbranchid);

    public static function approveCustomer($parameter);

    public static function disapproveCustomer($parameter);



}

?>