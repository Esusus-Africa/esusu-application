<?php

namespace Interface\MySavings;

interface SavingsInterface {

    /**
     * @param string $account
     * @param string $ptype
     * @param mixed $amount
     * @param string $remark
     * @param integer $ifetch_maintenance_model
     * @param string $balanceToImpact
     * @param integer $verify_till
     * @param mixed $iwallet_balance
     * @param mixed $iuid
     * @param string $allow_auth
     * @param string $getSMS_ProviderNum
     * @param string $ismscharges
     * @param string $isenderid
     * @param string $icurrency
     * @param integer $tPin
     * @param integer $confirmTPin
     * @param mixed $clientId
     * @param mixed $isbranchid
     * @param string $aggrId
     * @param string $inst_name
     * @param string $staffname
     * @param string $adminEmail
     * @return mixed
     */
    public static function deposit($parameter, $ifetch_maintenance_model, $verify_till, $iwallet_balance, $iuid, $allow_auth, $getSMS_ProviderNum, $ismscharges, $isenderid, $icurrency, $tPin, $clientId, $isbranchid, $aggrId, $inst_name, $staffname);

    /**
     * @param integer $charges
     * @return mixed
     */
    public static function withdraw($parameter, $ifetch_maintenance_model, $verify_till, $iwallet_balance, $iuid, $allow_auth, $getSMS_ProviderNum, $ismscharges, $isenderid, $icurrency, $tPin, $clientId, $isbranchid, $aggrId, $inst_name, $staffname);

    public static function withdrawalRequest($parameter, $ifetch_maintenance_model, $verify_till, $iwallet_balance, $iuid, $allow_auth, $getSMS_ProviderNum, $ismscharges, $isenderid, $icurrency, $tPin, $clientId, $isbranchid, $aggrId, $inst_name, $staffname, $adminEmail);

    /**
     * @param mixed $myCSVFile
     * @return mixed
     */
    public static function bulkDepositCSV($myCSVFile, $ifetch_maintenance_model, $verify_till, $iwallet_balance, $iuid, $allow_auth, $getSMS_ProviderNum, $ismscharges, $isenderid, $icurrency, $clientId, $isbranchid, $aggrId, $inst_name, $staffname);

    public static function bulkWithdrawalCSV($myCSVFile, $ifetch_maintenance_model, $verify_till, $iwallet_balance, $iuid, $charges, $allow_auth, $getSMS_ProviderNum, $ismscharges, $isenderid, $icurrency, $clientId, $isbranchid, $aggrId, $inst_name, $staffname);

    /**
     * Filter Savings History
     * @param string $parameter
     * @param string $iTranRecords
     * @param string $bTransRecords
     * @param string $iuid
     * @param string $isbranchid
     * @return mixed
     */
    public static function miniSavingsStmt($parameter);

    public static function savingsHistory($parameter, $iTranRecords, $bTransRecords, $iuid, $isbranchid);

    public static function withdrawalRequestHistory($parameter, $iTranRecords, $bTransRecords, $iuid, $isbranchid);

    public static function approveSavings($parameter);

    public static function disapproveSavings($parameter, $iuid, $isbranchid);

}

?>