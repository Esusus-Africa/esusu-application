<?php

namespace Interface\MyLoanRepayment;

interface LoanRepaymentInterface {

    /**
     * add loan repayment (manually) for cash-base collection
     * @param mixed $iwallet_balance
     * @param mixed $iuid
     * @param string $allow_auth
     * @param string $getSMS_ProviderNum
     * @param string $ismscharges
     * @param string $isenderid
     * @param string $icurrency
     * @param integer $tPin
     * @param mixed $clientId
     * @param mixed $isbranchid
     * @param string ifetch_maintenance_model
     */
    public static function addManualRepayment($parameter, $iuid, $isbranchid, $isenderid, $icurrency, $clientId, $tpin, $ifetch_maintenance_model, $ismscharges, $iwallet_balance, $getSMS_ProviderNum, $allow_auth);

    /**
     * Filter Loan Repayment History
     * @param string $parameter
     * @param string $iTranRecords
     * @param string $bTransRecords
     * @param string $iuid
     * @param string $isbranchid
     * @return mixed
     */
    public static function loanRepaymentHistory($parameter, $iTranRecords, $bTransRecords, $iuid, $isbranchid);

    public static function approveRepayment($parameter, $iuid, $isbranchid);

    public static function disapproveRepayment($parameter, $iuid, $isbranchid);

}

?>