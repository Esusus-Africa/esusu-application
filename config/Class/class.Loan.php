<?php

namespace Class\LoanMgr;

require_once '../Interface/interface.Loan.php';
require_once '../Interface/interface.LoanRepayment.php';
require_once 'class.Notification.php';

use Interface\MyLoan\LoanInterface as MyLoanInterface;
use Interface\MyLoanRepayment\LoanRepaymentInterface as MyLoanRepaymentInterface;
use Class\Notification\Notifier as Notifier;

class LoanManager extends App implements MyLoanInterface, MyLoanRepaymentInterface {

    /**
     * LOAN APPLICATION SECTION
     * @addLoanInformation
     * @addGuarantor
     * @addProduct
     * @
    */

    public static function addLoanInformation($parameter, $iuid, $isbranchid, $isenderid, $icurrency, $clientId, $ifetch_maintenance_model, $ismscharges, $iwallet_balance, $getSMS_ProviderNum){

        $lproduct = $this->db->sanitizeInput($parameter['lproduct']);
        $borrower = $this->db->sanitizeInput($parameter['borrower']);
        $baccount = $this->db->sanitizeInput($parameter['account']);
        $amount = $this->db->sanitizeInput(preg_replace('/[^0-9.]/', '', $parameter['amount']));
        $income_amt = $this->db->sanitizeInput(preg_replace('/[^0-9.]/', '', $parameter['income']));
        $salary_date = $this->db->sanitizeInput($parameter['salary_date']);
        $employer =  $this->db->sanitizeInput($parameter['employer']);
        $agent = $this->db->sanitizeInput($parameter['agent']);
        $status = $this->db->sanitizeInput($parameter['status']);
        $lreasons = $this->db->sanitizeInput($parameter['lreasons']);
        $account_number = $this->db->sanitizeInput($parameter['acct_no']);
	    $bank_code = $this->db->sanitizeInput($parameter['bank_code']);
        $beneficiary_name = $this->db->sanitizeInput($parameter['beneficiary_name']);
        $upstatus = "Pending";
        $lid = uniqid('LID-').date("ymds");
        $refid = "EA-loanBooking-".time();

        //Fetch loan product interest
        $get_interest = $this->fetchWithOneParam('loan_product', 'id', $lproduct);
        $max_duration  = $get_interest['duration'];
        $interest_type = $get_interest['interest_type'];
        $interest = preg_replace('/[^0-9.]/', '', $get_interest['interest'])/100;
        $tenor = $get_interest['tenor'];
        $amount_topay = ($interest == "0" || $interest_type == "Reducing Balance") ? $amount : (($amount * $interest) + $amount);

        //Saas Billing Settings
        $loan_booking = $ifetch_maintenance_model['loan_booking'];
        $myiwallet_balance = $iwallet_balance - $loan_booking;

    }



    /**
     * LOAN REPAYMENT SECTION
     * @addManualRepayment
     * @loanRepaymentHistory
     * @approveRepayment
     * @disapproveRepayment
     * 
     */

    //Function to add manual loan repayment
    public static function addManualRepayment($parameter, $iuid, $isbranchid, $isenderid, $icurrency, $clientId, $tpin, $ifetch_maintenance_model, $ismscharges, $iwallet_balance, $getSMS_ProviderNum, $allow_auth){

        $refid = date("Ymdis").myreference(10);
        $tid = $this->db->sanitizeInput($parameter['tid']);
        $name = $this->db->sanitizeInput($parameter['teller']);
        $lid = $this->db->sanitizeInput($parameter['acte']);
        $pay_date = $this->db->sanitizeInput($parameter['pay_date']);
        $amount_to_pay = $this->db->sanitizeInput(preg_replace('/[^0-9.]/', '', $parameter['amount_to_pay']));
        $remarks = $this->db->sanitizeInput($parameter['remarks']);
        $mycurrentTime = date("Y-m-d h:i:s");
        $confirmTPin = $this->db->sanitizeInput(preg_replace('/[^0-9]/', '', $parameter['confirmTPin']));
        $account_no = $this->db->sanitizeInput($parameter['account']);

        //Get loan info
        $get_loaninfo = $this->fetchWithTwoParamNot('loan_info', 'lid', $lid, 'p_status', 'PAID');
        $my_balance = number_format($get_loaninfo['balance'],2,'.','');
        $request_id = $get_loaninfo['request_id'];
        $direct_debit_status = $get_loaninfo['direct_debit_status'];
        $balanceColumn = ($get_loaninfo['loantype'] == "Purchase") ? 'asset_acquisition_bal' : 'loan_balance';

        //Get borrowers details
        $get_searchin = $this->fetchWithOneParam('borrowers', 'account', $account_no);
        $customer = $get_searchin['fname'].' '.$get_searchin['lname'];
        $uname = $get_searchin['username'];
        $phone = $get_searchin['phone'];
        $em = $get_searchin['email'];
        $sms_checker = $get_searchin['sms_checker'];
        $loanBal = ($balanceColumn == "loan_balance") ? ($get_searchin['loan_balance'] - $amount_to_pay) : ($get_searchin['asset_acquisition_bal'] - $amount_to_pay) ;

        //Get Payment Schedule Details
        $fetchRpSch = $this->fetchPaySchedule($lid, 'UNPAID');
        $psSchNum = $fetchRpSch['COUNT(*)'];
        $expAmt = $fetchRpSch['payment'];
        $loanid = $fetchRpSch['get_id'];
        $myRpId = $fetchRpSch['id'];

        //Saas Billing Settings
        $t_perc = $ifetch_maintenance_model['t_charges'];
        $mycharges = ($t_perc == "0" || $t_perc == "") ? $ismscharges : $t_perc;
        $myiwallet_balance = $iwallet_balance - $mycharges;

        //Get till account info
        $fetch_role = $this->fetchWithTwoParam('till_account', 'cashier', $iuid, 'status', 'Active');
        $balance = $fetch_role['balance'];
        $commissiontype = $fetch_role['commission_type'];
        $commission = ($commissiontype == "Flat") ? $fetch_role['commission'] : ($fetch_role['commission']/100);
        $commission_bal = $fetch_role['commission_balance'];
        $unsettled_balance = $fetch_role['unsettled_balance'];
        //Calculate Commission Earn By the Staff
        $cal_commission = $commission * $amount_to_pay;
        //Update Default Commission Balance
        $total_commission_bal = ($commission == 0) ? $commission_bal : ($cal_commission + $commission_bal);
        //Update Till Balance after posting payment
        $total_tillbal_left = $balance - $amount_to_pay;
        //Update Till Unsettled Balance
        $total_unsettled_bal = $unsettled_balance + $amount_to_pay;

        //SMS / Email / Authorization Settings
        $status = ($allow_auth == "Yes") ? "paid" : "pending";
        $theStatus = "Approved";
        $channel = "Internal";
        $notification = ($allow_auth == "Yes") ? "1" : "0";
        $checksms = ($sms_checker == "No") ? "0" : "1";
        $debitWAllet = ($getSMS_ProviderNum != 0 || ($ifetch_maintenance_model != 0 && $ismscharges == "0")) ? "No" : "Yes";
        $final_bal = $my_balance - $amount_to_pay;
        $p_status = ($final_bal <= 0) ? "PAID" : "PART-PAID";

        //Formatted Loan Repayment SMS Message
        $message = Notifier::loanRepayAlertMsg($isenderid, $customer, $icurrency, $amount_to_pay, $lid, $final_bal);
        $sms_charges = ceil(strlen($message) / 153) * $ismscharges;
        $mybalance = $iwallet_balance - $sms_charges;
        $sms_refid = uniqid("EA-smsCharges-").time();

        if($confirmTPin != $tpin){

            return -1; //Invalid Transaction Pin
    
        }elseif($amount_to_pay > $my_balance){

            return -2; //Amount to pay is invalid

        }elseif($amount_to_pay > $balance && $fetch_role != 0){

            return -3; //Insufficient fund in till balance
    
        }else{

            //UPDATE pay_schedule
            ($amount_to_pay === "$expAmt") ? $this->updateManytoMany('pay_schedule', ['status', 'dueStatus'], ['PAID', 'Paid'], 'id', $myRpId) : "";
            ($amount_to_pay > $expAmt || $amount_to_pay < $expAmt) ? $payScheduleQuery = "INSERT INTO pay_schedule(lid, get_id, tid, pid, schedule, balance, payment, status, branchid, vendorid, sbranchid, lofficer, direct_debit_status, requestid, dueStatus) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
            ($amount_to_pay > $expAmt || $amount_to_pay < $expAmt) ? $this->db->insertPaySchedule($payScheduleQuery, $lid, $loanid, $account_no, '', $pay_date, $final_bal, $amount_to_pay, 'PAID', $clientId, '', $isbranchid, $iuid, 'NotSent', '', 'Paid') : "";

            $mySearchRpSch = $this->fetchPayScheduleAll($lid, 'UNPAID');
            if($amount_to_pay > $expAmt || $amount_to_pay < $expAmt){

                foreach($mySearchRpSch as $row){

                    $RpId = $row['id'];
                    $RpAmt = $row['payment'];
                    $calcExpAmt = number_format(($amount_to_pay / $psSchNum),2,'.','');
                    $updatedExpAmt = $RpAmt - $calcExpAmt;
                    ($updatedExpAmt <= 0) ? $this->updateManytoMany1('pay_schedule', ['payment', 'status', 'dueStatus'], [$updatedExpAmt, 'PAID', 'Paid'], 'id', $RpId, 'status', 'UNPAID') : "";
                    ($updatedExpAmt > 0) ? $this->updateWithOneParam2('pay_schedule', 'payment', $updatedExpAmt, 'id', $RpId, 'status', 'UNPAID') : "";

                }

            }

            //Charge Hybrid or PayG User
            ($t_perc == "0" || $t_perc == "") ? "" : $query = "INSERT INTO wallet_history (userid, refid, recipient, credit, debit, transaction_type, currency, paymenttype, remark, status, date_time, initiator, balance) VALUE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            ($t_perc == "0" || $t_perc == "") ? "" : $this->db->insertWalletHistory($query, $clientId, $refid, 'self', '', $mycharges, 'Debit', $icurrency, 'Charges', 'Description: Service Charge for Repayment of '.$amount_to_pay, 'successful', $mycurrentTime, $iuid, $myiwallet_balance);
            //UPDATE INSTITUTION WALLET BALANCE
            ($t_perc == "0" || $t_perc == "") ? "" : $this->updateWithOneParam('institution_data', 'wallet_balance', $myiwallet_balance, 'institution_id', $clientId);

            //Deduct customer loan Balance and log payment record
            ($allow_auth == "Yes") ? $this->updateWithOneParam('borrowers', $balanceColumn, $loanBal, 'account', $account_no) : "";
            ($allow_auth == "Yes") ? $this->updateManytoMany('loan_info', ['balance', 'p_status'], [$final_bal, $p_status], 'lid', $lid) : "";
            $pmtQuery = "INSERT INTO payments(tid, lid, refid, account_no, customer, loan_bal, pay_date, amount_to_pay, remarks, branchid, vendorid, sbranchid, sendSms, sendEmail, smsChecker)";
            $this->insertLoanRepayment($pmtQuery, $iuid, $lid, $refid, $account_no, $customer, $final_bal, $pay_date, $amount_to_pay, $status, $clientId, '', $isbranchid, $notification, $notification, $checksms);

            //Balance Till account balance if exist
            ($fetch_role != 0 && $allow_auth == "Yes") ? $this->updateManytoMany('till_account', ['commission_balance', 'balance', 'unsettled_balance'], [$total_commission_bal, $total_tillbal_left, $total_unsettled_bal], 'cashier', $iuid) : "";
            ($fetch_role != 0 && $allow_auth != "Yes") ? $this->updateManytoMany('till_account', ['balance', 'unsettled_balance'], [$total_tillbal_left, $total_unsettled_bal], 'cashier', $iuid) : "";
            ($fetch_role != 0) ? $queryTillFund = "INSERT INTO fund_allocation_history (txid, companyid, manager_id, branch, teller, cashier, amount_fund, ttype, paymenttype, currency, balance, note_comment, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
            ($fetch_role != 0) ? $this->db->insertTillFundingHistory($queryTillFund, $refid, $clientId, $iuid, $isbranchid, $customer, $iuid, $amount_to_pay, 'Debit', 'LOAN_REPAYMENT', $icurrency, $total_tillbal_left, $message, "successful", $mycurrentTime) : "";

            //SMS/Email Notification
			(($sms_checker == "No" || $mycharges == "0" || $phone == "") ? "" : ($allow_auth == "Yes" && $debitWAllet == "No" ? Notifier::sendSMS($isenderid, $phone, $message, $clientId, $sms_refid, $iuid) : ($allow_auth == "Yes" && $debitWAllet == "Yes" && $mycharges <= $iwallet_balance ? Notifier::sendSMS($isenderid, $phone, $message, $clientId, $sms_refid, $iuid) : "")));
            ($allow_auth == "Yes" && $em != "") ? Notifier::loanRepaymentEmailNotifier($em, $refid, $uname, $mycurrentTime, $theStatus, $channel, $account_no, $customer, $phone, $lid, $icurrency, $amount_to_pay, $final_bal) : "";

            return "Success"; //Transaction Successful

        }

    }

    //Function to filter loan repayment history
    public static function loanRepaymentHistory($parameter, $iTranRecords, $bTransRecords, $iuid, $isbranchid){

        $column = array('id', 'Reference ID', 'Branch', 'Loan Officer', 'Loan ID', 'Account ID', 'Account Name', 'Amount Payed', 'Loan Balance', 'Status', 'DateTime');

        //Data table Parameter
        $searchValue = $parameter['searchValue']; //$_POST['search']['value']
        $draw = $parameter['draw']; //$_POST['draw']
        $order = $parameter['order']; //$_POST['order']
        $orderColumn = $parameter['orderColumn']; //$_POST['order']['0']['column']
        $start = $parameter['start']; //$_POST['start']
        $length = $parameter['length']; //$_POST['length']

        $startDate = ($parameter['startDate'] != "") ? $parameter['startDate'].' 00'.':00'.':00' : "2016-01-01 00:00:00";
        $endDate = ($parameter['endDate'] != "") ? $parameter['endDate'].' 24'.':00'.':00' : date("Y-m-d").' 24'.':00'.':00';
        $filterBy = $parameter['filterBy'];
        $clientId = $parameter['clientId'];
        $myStatus = $parameter['status']; //pending OR paid

        $query = " ";

        if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $filterBy != "all1" && $filterBy != "all2"){
    
            $query .= "SELECT * FROM payments 
            WHERE pay_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND remarks = '$myStatus' AND (tid = '$filterBy' OR account_no = '$filterBy' OR sbranchid = '$filterBy')
            ";
            
        }
        
        if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
            
            $query .= "SELECT * FROM payments 
            WHERE pay_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND remarks = '$myStatus'
            ";
            
        }
        
        if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all1"){
            
            $query .= "SELECT * FROM payments 
            WHERE pay_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND tid = '$iuid' AND remarks = '$myStatus'
            ";
            
        }
        
        if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all2"){
            
            $query .= "SELECT * FROM payments 
            WHERE pay_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND sbranchid = '$isbranchid' AND remarks = '$myStatus'
            ";
            
        }

        if($searchValue != ''){

            $query .= "SELECT * FROM payments
            WHERE refid LIKE '%'.$searchValue.'%' 
            OR account_no LIKE '%'.$searchValue.'%' 
            OR customer LIKE '%'.$searchValue.'%' 
            ";

        }

        if(isset($order)){

            $query .= 'ORDER BY '.$column[$orderColumn].' DESC ';

        }else{

            $query .= 'ORDER BY id DESC ';

        }

        $query1 = '';

        if($length != -1){

            $query1 = 'LIMIT ' . $start . ', ' . $length;

        }

        //Query Builder for the filtering
        $statement = $this->db->pdo->prepare($query);
        $statement->execute();
        $number_filter_row = $statement->rowCount();

        $statement = $this->db->pdo->prepare($query . $query1);
        $statement->execute();
        $result = $statement->fetchAll();

        $data = array();

        foreach($result as $row){

            $sub_array = array();
            $borrower = $row['account_no'];
            $lofficer = $row['tid'];
            $branchid = $row['sbranchid'];

            $selectBorrower = $this->fetchWithOneParam('borrowers', 'account', $borrower);
            $selectUser = $this->fetchWithOneParam('user', 'id', $lofficer);
            $selectBranch = $this->fetchWithOneParam('branches', 'branchid', $branchid);

            $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
            $sub_array[] = $row['refid'];
            $sub_array[] = "<b>".(($branchid === "") ? '---' : $selectBranch['bname'])."</b>";
            $sub_array[] = $selectUser['name'].' '.$selectUser['lname'];
            $sub_array[] = $row['lid'];
            $sub_array[] = $borrower;
            $sub_array[] = $row['customer'];
            $sub_array[] = number_format($row['amount_to_pay'],2,".",",");
            $sub_array[] = number_format($row['loan_bal'],2,'.',',');
            $sub_array[] = ($remarks == 'paid' ? '<span class="label bg-blue">paid</span>' : ($remarks == 'pending' ? '<span class="label bg-orange">pending</span>' : '<span class="label bg-red">'.$remarks.'</span>'));
            $sub_array[] = $row['pay_date'];
            $data[] = $sub_array;

        }

        $query2 = "SELECT * FROM payments WHERE branchid = '$clientId' AND remarks = '$myStatus'";
        $statement2 = $this->db->pdo->prepare($query2);
        $statement2->execute();

        $output = array(
            'draw' => intval($draw),
            'recordsTotal' => $statement2->rowCount(),
            'recordsFiltered' => $number_filter_row,
            'data' => $data
        );

        return json_encode($output);

    }


    //Function to approve pending repayment
    public static function approveRepayment($parameter, $iuid, $isbranchid){

        $clientId = $parameter['clientId'];
        $selector = $parameter['selector'];
        $number = count($selector);

        if($selector == ''){

            return -1; //No record was selected;

        }elseif($number >= 1){

            for($i=0; $i<$number; $i++){

                //Get Loan Repayment Transaction details
                $fetch_search = $this->fetchWithOneParam('payments', 'id', $selector[$i]);
                $lid = $fetch_search['lid'];
				$amount_to_pay = $fetch_search['amount_to_pay'];
				$account_no = $fetch_search['account_no'];
				$customer = $fetch_search['customer'];
				$refid = $fetch_search['refid'];
				$mycurrentTime = date("Y-m-d h:i:s");
				$staffid = $fetch_search['tid'];

                //Get loan info
                $fetch_lns = $this->fetchWithOneParam('loan_info', 'lid', $lid);
                $my_balance = $fetch_lns['balance'];
				$final_bal = $my_balance - $amount_to_pay;
				$p_status = ($final_bal <= 0) ? "PAID" : "PART-PAID";
                $balanceColumn = ($fetch_lns['loantype'] == "Purchase") ? 'asset_acquisition_bal' : 'loan_balance';

                 //Get borrowers details
                 $get_searchin = $this->fetchWithOneParam('borrowers', 'account', $account_no);
                 $loanBal = ($balanceColumn == "asset_acquisition_bal") ? ($get_searchin['asset_acquisition_bal'] - $amount_to_pay) : ($get_searchin['loan_balance'] - $amount_to_pay);

                //Get till role
                $fetch_role = $this->fetchWithTwoParam('till_account', 'cashier', $staffid, 'status', 'Active');
                $commissiontype = $fetch_role['commission_type'];
    			$commission = ($commissiontype == "Flat") ? $fetch_role['commission'] : ($fetch_role['commission']/100);
				$commission_bal = $fetch_role['commission_balance'];

				//Calculate Commission Earn By the Staff
				$cal_commission = $commission * $amount_to_pay;
				//Update Default Commission Balance
				$total_commission_bal = ($commission == 0) ? $commission_bal : ($cal_commission + $commission_bal);

                $this->updateWithOneParam('borrowers', $balanceColumn, $loanBal, 'account', $account_no);
                ($fetch_role != 0) ? $this->updateWithOneParam('till_account', 'commission_balance', $total_commission_bal, 'cashier', $staffid) : "";
                $this->updateManytoMany('loan_info', ['balance', 'p_status'], [$final_bal, $p_status], 'lid', $lid);
                $this->updateWithOneParam('payments', 'remarks', 'paid', 'id', $selector[$i]);

            }
            return "Success"; //Loan Repayment Approved Successfully

        }else{

            return -1; //No record was selected;

        }

    }

     //Function to disapprove pending repayment
     public static function disapproveRepayment($parameter, $iuid, $isbranchid){

        $clientId = $parameter['clientId'];
        $selector = $parameter['selector'];
        $number = count($selector);

        if($selector == ''){

            return -1; //No record was selected;

        }elseif($number >= 1){

            for($i=0; $i<$number; $i++){

                //Get Loan Repayment Transaction details
                $fetch_search = $this->fetchWithOneParam('payments', 'id', $selector[$i]);
                $lid = $fetch_search['lid'];
				$amount_to_pay = $fetch_search['amount_to_pay'];
				$account_no = $fetch_search['account_no'];
				$customer = $fetch_search['customer'];
				$refid = $refid = uniqid().time();
				$mycurrentTime = date("Y-m-d h:i:s");
				$staffid = $fetch_search['tid'];

                //Get loan info
                $fetch_lns = $this->fetchWithOneParam('loan_info', 'lid', $lid);
                $balanceColumn = ($fetch_lns['loantype'] == "Purchase") ? 'asset_acquisition_bal' : 'loan_balance';

                //Get borrowers details
                $get_searchin = $this->fetchWithOneParam('borrowers', 'account', $account_no);
                $loanBal = ($balanceColumn == "asset_acquisition_bal") ? ($get_searchin['asset_acquisition_bal'] + $amount_to_pay) : ($get_searchin['loan_balance'] + $amount_to_pay);

                //Get till details for exist
                $fetch_role = $this->fetchWithTwoParam('till_account', 'cashier', $staffid, 'status', 'Active');

                ($fetch_role != 0) ? $this->updateWithOneParam('till_account', 'balance', $loanBal, 'cashier', $staffid) : "";
                ($fetch_role != 0) ? $queryTillFund = "INSERT INTO fund_allocation_history (txid, companyid, manager_id, branch, teller, cashier, amount_fund, ttype, paymenttype, currency, balance, note_comment, status, date_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" : "";
                ($fetch_role != 0) ? $this->db->insertTillFundingHistory($queryTillFund, $refid, $clientId, $iuid, $isbranchid, $customer, $iuid, $amount, $tType, $myLabelType, $icurrency, $remain_balance, 'Amount to repay Loan with Loan ID'.$lid.' was Declined', "successful", $mycurrentTime) : "";
                $this->updateWithOneParam('payments', 'remarks', 'declined', 'id', $selector[$i]);

            }
            return "Success"; //Loan Repayment Disapproved Successfully

        }else{

            return -1; //No record was selected;

        }

    }


}

?>