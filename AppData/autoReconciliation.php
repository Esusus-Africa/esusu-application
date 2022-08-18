<?php
include("../config/connect.php");

//$link = mysqli_connect('localhost','esusulive_esusuapp','BoI9YR^zqs%M','esusulive_esusuapp') or die('Unable to Connect to Database');

$startDate = date("Y-m-d"); //date_time BETWEEN '$startDate' AND '$endDate'

$endDate = date('Y-m-d h:i:s', strtotime($startDate . ' +1 day'));

$todaysDate = date('Y-m-d h:i:s');

//RECONCILIATION REPORTS FOR STAFF AND INSTITUTION
$searchUser = mysqli_query($link, "SELECT * FROM user WHERE comment = 'Approved'"); // AND transfer_balance != '0.0'
while($fetchUser = mysqli_fetch_array($searchUser)){
    
    $id = $fetchUser['id'];
    $createdBy = $fetchUser['created_by'];
    
    //STAFF TRANSFER HISTORY (DEBIT)
    $searchTransfer_forStaff = mysqli_query($link, "SELECT SUM(amount), SUM(transfers_fee), COUNT(amount) FROM transfer_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND initiator = '$id'");
    //$totalDebitCount_onTransferForStaff = mysqli_num_rows($searchTransfer_forStaff);
    $fetchTransferForStaff = mysqli_fetch_array($searchTransfer_forStaff);
    $totalDebitCount_onTransferForStaff = $fetchTransferForStaff['COUNT(amount)'];
    $totalDebitValue_onTransferForStaff = $fetchTransferForStaff['SUM(amount)'];
    $totalChargesValue_onTransferForStaff = $fetchTransferForStaff['SUM(transfers_fee)'];
    
    //STAFF WALLET HISTORY (DEBIT)
    $searchWalletDebit = mysqli_query($link, "SELECT SUM(amount), COUNT(amount) FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND initiator = '$id' AND userid != '' AND (paymenttype = 'VerveCard_Verification' OR paymenttype = 'DD_Activation' OR paymenttype = 'Card_Withdrawal' OR paymenttype = 'Cardless_Withdrawal' OR paymenttype = 'Topup-Prepaid_Card' OR paymenttype = 'p2p-reversal' OR paymenttype = 'p2p-debit' OR paymenttype = 'Airtime - WEB' OR paymenttype = 'Airtime - USSD' OR paymenttype = 'Databundle - WEB' OR paymenttype = 'Databundle - USSD' OR paymenttype = 'tv - WEB' OR paymenttype = 'internet - WEB' OR paymenttype = 'Prepaid - WEB' OR paymenttype = 'Postpaid - WEB' OR paymenttype = 'waec - WEB' OR paymenttype= 'p2p-transfer')");
    //$totalDebitCount_onWallet = mysqli_num_rows($searchWalletDebit);
    $fetchWalletDebit = mysqli_fetch_array($searchWalletDebit);
    $totalDebitCount_onWallet = $fetchWalletDebit['COUNT(amount)'];
    $totalDebitValue_onWallet = $fetchWalletDebit['SUM(amount)'];
    
    //STAFF WALLET HISTORY (CREDIT)
    $searchWalletCredit = mysqli_query($link, "SELECT SUM(amount), COUNT(amount) FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (recipient = '$id' OR (initiator = '$id' AND userid != '')) AND (paymenttype = 'card' OR paymenttype = 'account' OR paymenttype = 'p2p-transfer' OR paymenttype = 'ACCOUNT_TRANSFER' OR paymenttype = 'USSD' OR paymenttype = 'POS')");
    //$totalCreditCount_onWallet = mysqli_num_rows($searchWalletCredit);
    $fetchWalletCredit = mysqli_fetch_array($searchWalletCredit);
    $totalCreditCount_onWallet = $fetchWalletCredit['COUNT(amount)'];
    $totalCreditValue_onWallet = $fetchWalletCredit['SUM(amount)'];
    
    //STAFF WALLET HISTORY (COMMISSION)
    $searchWalletCommission = mysqli_query($link, "SELECT SUM(amount) FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (recipient = '$id' OR (initiator = '$id' AND userid != '')) AND (paymenttype = 'Commission - WEB' OR paymenttype = 'Commission - USSD' OR paymenttype = 'TRANSFER_COMMISSION' OR paymenttype = 'TERMINAL_COMMISSION')");
    //$totalCreditCount_onWallet = mysqli_num_rows($searchWalletCredit);
    $fetchWalletCommission = mysqli_fetch_array($searchWalletCommission);
    $totalCommissionValue_onWallet = $fetchWalletCommission['SUM(amount)'];
    
    //STAFF WALLET HISTORY (CHARGES) 1
    $searchWalletCharges = mysqli_query($link, "SELECT SUM(amount) FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND initiator = '$id' AND (paymenttype = 'Wallet' OR paymenttype = 'system' OR paymenttype = 'Stamp Duty' OR paymenttype = 'BVN_Charges' OR paymenttype = 'Report_Charges' OR paymenttype = 'Charges')");
    $fetchWalletCharges = mysqli_fetch_array($searchWalletCharges);
    $totalChargesValue_onWallet = $fetchWalletCharges['SUM(amount)'];
    
    //STAFF TERMINAL REPORT (CHARGES) 2
    $searchWalletCharges2 = mysqli_query($link, "SELECT SUM(charges) FROM terminal_report WHERE date_time BETWEEN '$startDate' AND '$endDate' AND initiatedBy = '$id' AND status = 'Success'");
    $fetchWalletCharges2 = mysqli_fetch_array($searchWalletCharges2);
    $totalChargesValue_onWallet2 = $fetchWalletCharges2['SUM(charges)'];
    
    //INSTITUTION TRANSFER HISTORY (DEBIT)
    $searchTransfer_forAll = mysqli_query($link, "SELECT SUM(amount), SUM(transfers_fee), COUNT(amount) FROM transfer_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$createdBy'");
    //$totalDebitCount_onTransferForAll = mysqli_num_rows($searchTransfer_forAll);
    $fetchTransferForAll = mysqli_fetch_array($searchTransfer_forAll);
    $totalDebitCount_onTransferForAll = $fetchTransferForAll['COUNT(amount)'];
    $totalDebitValue_onTransferForAll = $fetchTransferForAll['SUM(amount)'];
    $totalChargesValue_onTransferForAll = $fetchTransferForAll['SUM(transfers_fee)'];
    
    //INSTITUTION WALLET HISTORY (DEBIT)
    $searchWalletDebitInst = mysqli_query($link, "SELECT SUM(amount), COUNT(amount) FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$createdBy' AND (paymenttype = 'VerveCard_Verification' OR paymenttype = 'DD_Activation' OR paymenttype = 'Card_Withdrawal' OR paymenttype = 'Cardless_Withdrawal' OR paymenttype = 'Topup-Prepaid_Card' OR paymenttype = 'p2p-reversal' OR paymenttype = 'p2p-debit' OR paymenttype = 'Airtime - WEB' OR paymenttype = 'Airtime - USSD' OR paymenttype = 'Databundle - WEB' OR paymenttype = 'Databundle - USSD' OR paymenttype = 'tv - WEB' OR paymenttype = 'internet - WEB' OR paymenttype = 'Prepaid - WEB' OR paymenttype = 'Postpaid - WEB' OR paymenttype = 'waec - WEB' OR (paymenttype= 'p2p-transfer' AND recipient != '$createdBy'))");
    //$totalDebitCount_onWalletInst = mysqli_num_rows($searchWalletDebitInst);
    $fetchWalletDebitInst = mysqli_fetch_array($searchWalletDebitInst);
    $totalDebitCount_onWalletInst = $fetchWalletDebitInst['COUNT(amount)'];
    $totalDebitValue_onWalletInst = $fetchWalletDebitInst['SUM(amount)'];
    
    //INSTITUTION WALLET HISTORY (CREDIT)
    /*$verifyInst = mysqli_query($link, "SELECT * FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate'AND userid = '' AND paymenttype = 'p2p-transfer'");
    $vFetchInst = mysqli_fetch_array($verifyInst);
    $recipi = $vFetchInst['recipient'];
    
    $searchIns = mysqli_query($link, "SELECT * FROM user WHERE id = '$recipi'");
    $fetchIns = mysqli_fetch_array($searchIns);
    $originator = $fetchIns['created_by'];*/
    
    ($createdBy == "") ? $searchWalletCreditInst = mysqli_query($link, "SELECT SUM(amount), COUNT(amount) FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '' AND recipient = '$createdBy' AND paymenttype = 'p2p-transfer'") : "";
    ($createdBy != "") ? $searchWalletCreditInst = mysqli_query($link, "SELECT SUM(amount), COUNT(amount) FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$createdBy' AND (paymenttype = 'ACCOUNT_TRANSFER' OR paymenttype = 'card' OR paymenttype = 'account' OR paymenttype = 'USSD' OR paymenttype = 'POS')") : "";
    //$totalCreditCount_onWalletInst = mysqli_num_rows($searchWalletCreditInst);
    $fetchWalletCreditInst = mysqli_fetch_array($searchWalletCreditInst);
    $totalCreditCount_onWalletInst = $fetchWalletCreditInst['COUNT(amount)'];
    $totalCreditValue_onWalletInst = $fetchWalletCreditInst['SUM(amount)'];
    
    //INSTITUTION WALLET HISTORY (CHARGES) 1
    $searchWalletChargesInst = mysqli_query($link, "SELECT SUM(amount) FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$createdBy' AND (paymenttype = 'Wallet' OR paymenttype = 'system' OR paymenttype = 'Stamp Duty' OR paymenttype = 'BVN_Charges' OR paymenttype = 'Report_Charges' OR paymenttype = 'Charges')");
    $fetchWalletChargesInst = mysqli_fetch_array($searchWalletChargesInst);
    $totalChargesValue_onWalletInst = $fetchWalletChargesInst['SUM(amount)'];
    
    //INSTITUTION WALLET HISTORY (CHARGES) 2
    $searchWalletChargesInst2 = mysqli_query($link, "SELECT SUM(charges) FROM terminal_report WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$createdBy' AND status = 'Success'");
    $fetchWalletChargesInst2 = mysqli_fetch_array($searchWalletChargesInst2);
    $totalChargesValue_onWalletInst2 = $fetchWalletChargesInst2['SUM(charges)'];
    
    //INSTITUTION TERMINAL REPORT (COMMISSION)
    $searchWalletCommissionInst = mysqli_query($link, "SELECT SUM(amount) FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$createdBy' AND (paymenttype = 'Commission - WEB' OR paymenttype = 'Commission - USSD' OR paymenttype = 'TRANSFER_COMMISSION' OR paymenttype = 'TERMINAL_COMMISSION')");
    $fetchWalletCommissionInst = mysqli_fetch_array($searchWalletCommissionInst);
    $totalCommissionValue_onWalletInst = $fetchWalletCommissionInst['SUM(amount)'];
    
    //FOR STAFF
    $overallStaffCreditValue = ($totalCreditValue_onWallet == "" || $totalCreditValue_onWallet <= "0") ? 0 : ($totalCreditValue_onWallet + $totalCommissionValue_onWallet);
    $overallStaffDebitValue = $totalDebitValue_onTransferForStaff + $totalDebitValue_onWallet;
    $overralStaffCharges = $totalChargesValue_onTransferForStaff + $totalChargesValue_onWallet + $totalChargesValue_onWallet2;
    $overallStaffCreditCount = ($totalCreditValue_onWallet == "" || $totalCreditValue_onWallet <= "0") ? 0 : $totalCreditCount_onWallet;
    $overallStaffDebitCount = ($overallStaffDebitValue <= "0") ? 0 : ($totalDebitCount_onTransferForStaff + $totalDebitCount_onWallet);
    $overralStaffTransactionValue = $overallStaffCreditValue + $overallStaffDebitValue + $overralStaffCharges;
    $overralStaffCommission = $totalCommissionValue_onWallet;
    $overralStaffTransactionCount = $overallStaffCreditCount + $overallStaffDebitCount;
    
    //FOR INSTITUTION
    $overallInstCreditValue = ($totalCreditValue_onWalletInst == "" || $totalCreditValue_onWalletInst <= "0") ? 0 : ($totalCreditValue_onWalletInst + $totalCommissionValue_onWalletInst);
    $overallInstDebitValue = $totalDebitValue_onTransferForAll + $totalDebitValue_onWalletInst;
    $overralInstCharges = $totalChargesValue_onTransferForAll + $totalChargesValue_onWalletInst + $totalChargesValue_onWalletInst2;
    $overallInstCreditCount = ($totalCreditValue_onWalletInst == "" || $totalCreditValue_onWalletInst <= "0") ? 0 : $totalCreditCount_onWalletInst;
    $overallInstDebitCount = ($overallInstDebitValue <= "0") ? 0 : ($totalDebitCount_onTransferForAll + $totalDebitCount_onWalletInst);
    $overralInstTransactionValue = $overallInstCreditValue + $overallInstDebitValue + $overralInstCharges;
    $overralInstCommission = $totalCommissionValue_onWalletInst;
    $overralInstTransactionCount = $overallInstCreditCount + $overallInstDebitCount;
    
    //FOR STAFF ENTRY
    $verifyStaffEntry = mysqli_query($link, "SELECT * FROM reconciliation_report WHERE date_time BETWEEN '$startDate' AND '$endDate' AND initiatorId = '$id' AND merchantId = '$createdBy'");
    $staffEntryNum = mysqli_num_rows($verifyStaffEntry);
    $fetchStaffEntry = mysqli_fetch_array($verifyStaffEntry);
    if($staffEntryNum == 1 && $overralStaffTransactionCount > 0){
        $staffEntryId = $fetchStaffEntry['id'];
        mysqli_query($link, "UPDATE reconciliation_report SET totalCreditValue = '$overallStaffCreditValue', totalDebitValue = '$overallStaffDebitValue', totalCreditCount = '$overallStaffCreditCount', totalDebitCount = '$overallStaffDebitCount', totalTransactionValue = '$overralStaffTransactionValue', totalChargesValue = '$overralStaffCharges', totalCommissionValue = '$overralStaffCommission', totalTransactionCount = '$overralStaffTransactionCount' WHERE id = '$staffEntryId'");
    }
    elseif($staffEntryNum == 0 && $overralStaffTransactionCount > 0){
        mysqli_query($link, "INSERT INTO reconciliation_report VALUES(null,'$createdBy','$id','$overallStaffCreditValue','$overallStaffDebitValue','$overallStaffCreditCount','$overallStaffDebitCount','$overralStaffTransactionValue','$overralStaffCharges','$overralStaffCommission','$overralStaffTransactionCount','$todaysDate')");
    }
    else{
        //Do nothing;
    }
    
    //FOR INSTITUTION ENTRY
    $verifyInstEntry = mysqli_query($link, "SELECT * FROM reconciliation_report WHERE date_time BETWEEN '$startDate' AND '$endDate' AND initiatorId = '' AND merchantId = '$createdBy'");
    $instEntryNum = mysqli_num_rows($verifyInstEntry);
    $fetchInstEntry = mysqli_fetch_array($verifyInstEntry);
    if($instEntryNum == 1 && $overralInstTransactionCount > 0){
        $instEntryId = $fetchInstEntry['id'];
        mysqli_query($link, "UPDATE reconciliation_report SET totalCreditValue = '$overallInstCreditValue', totalDebitValue = '$overallInstDebitValue', totalCreditCount = '$overallInstCreditCount', totalDebitCount = '$overallInstDebitCount', totalTransactionValue = '$overralInstTransactionValue', totalChargesValue = '$overralInstCharges', totalCommissionValue = '$overralInstCommission', totalTransactionCount = '$overralInstTransactionCount' WHERE id = '$instEntryId'");
    }
    elseif($instEntryNum == 0 && $overralInstTransactionCount > 0){
        mysqli_query($link, "INSERT INTO reconciliation_report VALUES(null,'$createdBy','','$overallInstCreditValue','$overallInstDebitValue','$overallInstCreditCount','$overallInstDebitCount','$overralInstTransactionValue','$overralInstCharges','$overralInstCommission','$overralInstTransactionCount','$todaysDate')");
    }
    else{
        //Do nothing;
    }
    
}



//RECONCILIATION REPORTS FOR CUSTOMER
$searchCustomer = mysqli_query($link, "SELECT * FROM borrowers WHERE acct_status = 'Activated' AND virtual_acctno != ''"); // AND transfer_balance != '0.0'
while($fetchCustomer = mysqli_fetch_array($searchCustomer)){
    
    $id = $fetchCustomer['account'];
    $createdBy = $fetchCustomer['branchid'];
    
    //CUSTOMER TRANSFER HISTORY (DEBIT)
    $searchTransfer_forCustomer = mysqli_query($link, "SELECT SUM(amount), SUM(transfers_fee), COUNT(amount) FROM transfer_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND initiator = '$id'");
    //$totalDebitCount_onTransferForCustomer = mysqli_num_rows($searchTransfer_forCustomer);
    $fetchTransferForCustomer = mysqli_fetch_array($searchTransfer_forCustomer);
    $totalDebitCount_onTransferForCustomer = $fetchTransferForCustomer['COUNT(amount)'];
    $totalDebitValue_onTransferForCustomer = $fetchTransferForCustomer['SUM(amount)'];
    $totalChargesValue_onTransferForCustomer = $fetchTransferForCustomer['SUM(transfers_fee)'];
    
    //CUSTOMER WALLET HISTORY (DEBIT)
    $searchWalletDebitCustomer = mysqli_query($link, "SELECT SUM(amount), COUNT(amount) FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND initiator = '$id' AND (paymenttype = 'VerveCard_Verification' OR paymenttype = 'DD_Activation' OR paymenttype = 'Card_Withdrawal' OR paymenttype = 'Cardless_Withdrawal' OR paymenttype = 'Topup-Prepaid_Card' OR paymenttype = 'p2p-reversal' OR paymenttype = 'p2p-debit' OR paymenttype = 'Airtime - WEB' OR paymenttype = 'Airtime - USSD' OR paymenttype = 'Databundle - WEB' OR paymenttype = 'Databundle - USSD' OR paymenttype = 'tv - WEB' OR paymenttype = 'internet - WEB' OR paymenttype = 'Prepaid - WEB' OR paymenttype = 'Postpaid - WEB' OR paymenttype = 'waec - WEB' OR paymenttype= 'p2p-transfer')");
    //$totalDebitCount_onWalletCustomer = mysqli_num_rows($searchWalletDebitCustomer);
    $fetchWalletDebitCustomer = mysqli_fetch_array($searchWalletDebitCustomer);
    $totalDebitCount_onWalletCustomer = $fetchWalletDebitCustomer['COUNT(amount)'];
    $totalDebitValue_onWalletCustomer = $fetchWalletDebitCustomer['SUM(amount)'];
    
    //CUSTOMER WALLET HISTORY (CREDIT)
    $searchWalletCreditCustomer = mysqli_query($link, "SELECT SUM(amount), COUNT(amount) FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND ((recipient = '$id' AND (paymenttype = 'card' OR paymenttype = 'account' OR paymenttype = 'Commission - WEB' OR paymenttype = 'Commission - USSD' OR paymenttype = 'p2p-transfer')) OR (initiator = '$id' AND paymenttype = 'ACCOUNT_TRANSFER'))");
    //$totalCreditCount_onWalletCustomer = mysqli_num_rows($searchWalletCreditCustomer);
    $fetchWalletCreditCustomer = mysqli_fetch_array($searchWalletCreditCustomer);
    $totalCreditCount_onWalletCustomer = $fetchWalletCreditCustomer['COUNT(amount)'];
    $totalCreditValue_onWalletCustomer = $fetchWalletCreditCustomer['SUM(amount)'];
    
    //CUSTOMER WALLET HISTORY (CHARGES)
    $searchWalletChargesCustomer = mysqli_query($link, "SELECT SUM(amount) FROM wallet_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND initiator = '$id' AND (paymenttype = 'Wallet' OR paymenttype = 'system' OR paymenttype = 'Stamp Duty' OR paymenttype = 'BVN_Charges' OR paymenttype = 'Report_Charges' OR paymenttype = 'Charges')");
    $fetchWalletChargesCustomer = mysqli_fetch_array($searchWalletChargesCustomer);
    $totalChargesValue_onWalletCustomer = $fetchWalletChargesCustomer['SUM(amount)'];
    
    //CALCULATION
    $overallCustomerCreditValue = ($totalCreditValue_onWalletCustomer == "" || $totalCreditValue_onWalletCustomer <= "0") ? 0 : $totalCreditValue_onWalletCustomer;
    $overallCustomerDebitValue = $totalDebitValue_onTransferForCustomer + $totalDebitValue_onWalletCustomer;
    $overralCustomerCharges = $totalChargesValue_onTransferForCustomer + $totalChargesValue_onWalletCustomer;
    $overallCustomerCreditCount = ($totalCreditValue_onWalletCustomer == "" || $totalCreditValue_onWalletCustomer <= "0") ? 0 : $totalCreditCount_onWalletCustomer;
    $overallCustomerDebitCount = ($overallCustomerDebitValue <= "0") ? 0 : ($totalDebitCount_onTransferForCustomer + $totalDebitCount_onWalletCustomer);
    $overralCustomerTransactionValue = $overallCustomerCreditValue + $overallCustomerDebitValue + $overralCustomerCharges;
    $overralCustomerCommission = 0.0;
    $overralCustomerTransactionCount = $overallCustomerCreditCount + $overallCustomerDebitCount;
    
    //FOR CUSTOMER ENTRY
    $verifyCustomerEntry = mysqli_query($link, "SELECT * FROM reconciliation_report WHERE date_time BETWEEN '$startDate' AND '$endDate' AND initiatorId = '$id' AND merchantId = '$createdBy'");
    $customerEntryNum = mysqli_num_rows($verifyCustomerEntry);
    $fetchCustomerEntry = mysqli_fetch_array($verifyCustomerEntry);
    if($customerEntryNum == 1 && $overralCustomerTransactionCount > 0){
        $customerEntryId = $fetchCustomerEntry['id'];
        mysqli_query($link, "UPDATE reconciliation_report SET totalCreditValue = '$overallCustomerCreditValue', totalDebitValue = '$overallCustomerDebitValue', totalCreditCount = '$overallCustomerCreditCount', totalDebitCount = '$overallCustomerDebitCount', totalTransactionValue = '$overralCustomerTransactionValue', totalChargesValue = '$overralCustomerCharges', totalCommissionValue = '$overralCustomerCommission', totalTransactionCount = '$overralCustomerTransactionCount' WHERE id = '$customerEntryId'");
    }
    elseif($customerEntryNum == 0 && $overralCustomerTransactionCount > 0){
        mysqli_query($link, "INSERT INTO reconciliation_report VALUES(null,'$createdBy','$id','$overallCustomerCreditValue','$overallCustomerDebitValue','$overallCustomerCreditCount','$overallCustomerDebitCount','$overralCustomerTransactionValue','$overralCustomerCharges','$overralCustomerCommission','$overralCustomerTransactionCount','$todaysDate')");
    }
    else{
        //Do nothing;
    }
    
}
?>