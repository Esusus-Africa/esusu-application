<?php

class App {

    protected $db;

    protected $pdo;

    protected $myitoday_record;
    
    public function __construct(Database $db) {

        $this->db = $db;
        $this->db->pdo = $pdo;

    }

    /**
     * THIS SECTION IS FOR ALL SQL
     * SELECTION STATEMENT WITH DYNAMIC
     * FUNCTIONS
     * 
     * WRITTEN BY: AKINADE AYODEJI T.
     * For: Scalability purpose
     */

    //FETCH SUM OF ALL WALLET BALANCE
    public function allTotalWalletBalance($parameter, $parameter1) {

        $query = "SELECT SUM($parameter) FROM $parameter1";

        return $this->db->fetchByTwo($query, $parameter, $parameter1);

    }
    public function sumOfAirtimeDataPerDay($parameter, $parameter1) {

        $query = "SELECT SUM(debit) FROM wallet_history WHERE date_time LIKE '$parameter%' AND initiator = '$parameter1' AND (paymenttype = 'Airtime - WEB' OR paymenttype = 'Databundle - WEB')";

        return $this->db->fetchByTwo($query, $parameter, $parameter1);

    }
    public function sumOfBankTransferPerDay($parameter, $parameter1) {

        $query = "SELECT SUM(debit) FROM wallet_history WHERE date_time LIKE '$parameter%' AND initiator = '$parameter1' AND paymenttype = 'BANK_TRANSFER'";

        return $this->db->fetchByTwo($query, $parameter, $parameter1);

    }

    //FETCH NUMBER OF PENDING TERMINAL SETTLEMENT BALANCE
    public function fetchPendingTerminalSettlement($parameter) {

        $query = "SELECT * FROM terminal_reg WHERE terminal_status = '$parameter' AND (pending_balance > '0.0' OR pending_balance > 0)";

        return $this->db->fetchByOne($query, $parameter);

    }

    //FETCH PAY SCHEDULE, COUNTER
    public function fetchPaySchedule($parameter, $parameter1) {

        $query = "SELECT COUNT(*), payment, get_id FROM pay_schedule WHERE lid = '$parameter' AND status = '$parameter1' ORDER BY id ASC";

        return $this->db->fetchByTwo($query, $parameter, $parameter1);

    }
    //All
    public function fetchPayScheduleAll($parameter, $parameter1) {

        $query = "SELECT * FROM pay_schedule WHERE lid = '$parameter' AND status = '$parameter1' ORDER BY id ASC";

        return $this->db->fetchByTwoAll($query, $parameter, $parameter1);


    }

    //FETCH RECORDS WITH ONE PARAMETER
    public function fetchWithOneParam($parameter, $parameter1, $parameter2) {

        $query = "SELECT * FROM $parameter WHERE $parameter1 = '$parameter2'";

        return $this->db->fetchByOne($query, $parameter2);

    }
    public function fetchWithOneParam2($parameter, $parameter1, $parameter2, $parameter3) {

        $query = "SELECT * FROM $parameter WHERE ($parameter1 = '$parameter2' OR $parameter1 = '$parameter3')";

        return $this->db->fetchByTwo($query, $parameter2, $parameter3);

    }

    //FETCH RECORDS WITH TWO PARAMETERS
    public function fetchWithTwoParam($parameter, $parameter1, $parameter2, $parameter3, $parameter4) {

        $query = "SELECT * FROM $parameter WHERE $parameter1 = '$parameter2' AND $parameter3 = '$parameter4'";

        return $this->db->fetchByTwo($query, $parameter2, $parameter4);

    }
    //with NOT only
    public function fetchWithTwoParamNot($parameter, $parameter1, $parameter2, $parameter3, $parameter4) {

        $query = "SELECT * FROM $parameter WHERE $parameter1 = '$parameter2' AND $parameter3 != '$parameter4'";

        return $this->db->fetchByTwo($query, $parameter2, $parameter4);

    }
    //All
    public function fetchWithTwoParamAll($parameter, $parameter1, $parameter2, $parameter3, $parameter4) {

        $query = "SELECT * FROM $parameter WHERE $parameter1 = '$parameter2' AND $parameter3 = '$parameter4'";

        return $this->db->fetchByTwoAll($query, $parameter2, $parameter4);

    }
    //All with NOT sign on the second parameter
    public function fetchWithTwoParamNotAll($parameter, $parameter1, $parameter2, $parameter3, $parameter4) {

        $query = "SELECT * FROM $parameter WHERE $parameter1 = '$parameter2' AND $parameter3 != '$parameter4'";

        return $this->db->fetchByTwoAll($query, $parameter2, $parameter4);

    }
    //WITH ORDER BY
    public function fetchWithTwoParam2($parameter, $parameter1, $parameter2, $parameter3, $parameter4, $parameter5) {

        $query = "SELECT * FROM $parameter WHERE $parameter1 = '$parameter2' AND $parameter3 = '$parameter4' ORDER BY $parameter5 DESC";

        return $this->db->fetchByTwo($query, $parameter2, $parameter4);

    }
    

    //FETCH RECORDS WITH THREE PARAMETERS
    public function fetchWithThreeParam($parameter, $parameter1, $parameter2, $parameter3, $parameter4, $parameter5, $parameter6) {

        $query = "SELECT * FROM $parameter WHERE $parameter1 = '$parameter2' AND $parameter3 = '$parameter4' AND $parameter5 = '$parameter6'";

        return $this->db->fetchByThree($query, $parameter2, $parameter4, $parameter6);

    }
    //with NOT only
    public function fetchWithThreeParamNot($parameter, $parameter1, $parameter2, $parameter3, $parameter4, $parameter5, $parameter6) {

        $query = "SELECT * FROM $parameter WHERE $parameter1 = '$parameter2' AND $parameter3 = '$parameter4' AND $parameter5 != '$parameter6'";

        return $this->db->fetchByThree($query, $parameter2, $parameter4, $parameter6);

    }
    //All with NOT sign on the third parameter
    public function fetchWithThreeParamNotAll($parameter, $parameter1, $parameter2, $parameter3, $parameter4, $parameter5, $parameter6) {

        $query = "SELECT * FROM $parameter WHERE $parameter1 = '$parameter2' AND $parameter3 = '$parameter4' AND $parameter5 != '$parameter6'";

        return $this->db->fetchByThreeAll($query, $parameter2, $parameter4, $parameter6);

    }
    public function fetchWithThreeParam2($parameter, $parameter1, $parameter2, $parameter3, $parameter4, $parameter5, $parameter6, $parameter7) {

        $query = "SELECT * FROM $parameter WHERE $parameter1 = '$parameter2' AND $parameter3 = '$parameter4' AND ($parameter5 = '$parameter6' OR $parameter5 = '$parameter7')";

        return $this->db->fetchByFour($query, $parameter2, $parameter4, $parameter6, $parameter7);

    }
    //WITH ORDER BY
    public function fetchWithThreePara2($parameter, $parameter1, $parameter2, $parameter3, $parameter4, $parameter5, $parameter6, $parameter7) {

        $query = "SELECT * FROM $parameter WHERE $parameter1 = '$parameter2' AND $parameter3 = '$parameter4' AND $parameter5 = '$parameter6' ORDER BY $parameter7 DESC";

        return $this->db->fetchByThree($query, $parameter2, $parameter4, $parameter6);

    }


    /**
     * THIS SECTION IS FOR ALL SQL
     * UPDATE STATEMENT WITH DYNAMIC
     * FUNCTIONS
     * 
     * Written By: AKINADE AYODEJI T.
     * For: Scalability purpose
     */

    //UPDATE ONE PARAMETER
    public function updateWithOneParam($parameter, $parameter1, $parameter2, $parameter5, $parameter6) {

        $query = "UPDATE $parameter SET $parameter1 = '$parameter2' WHERE $parameter5 = '$parameter6'";

        return $this->db->updateOneParam($query, $parameter2);

    }
    public function updateWithOneParam2($parameter, $parameter1, $parameter2, $parameter5, $parameter6, $parameter7, $parameter8) {

        $query = "UPDATE $parameter SET $parameter1 = '$parameter2' WHERE $parameter5 = '$parameter6' AND $parameter7 = '$parameter8'";

        return $this->db->updateOneParam($query, $parameter2);

    }

    //UPDATE AS MANY AS POSSIBLE PARAMETERS
    public function updateManytoMany($parameter, array $parameter1, array $parameter2, $parameter3, $parameter4) {

        $getKey = $parameter1;
        $getValue = $parameter2;
        
        $countKey = count($getKey);
        $countValue = count($getValue);

        if($countKey != $countValue){
            
            return "array|value mismatched";
            
        }else{

            for($i = 0; $i <= ($countKey - 1); $i++){
                
                $myKey = $getKey[$i];
                $myValue = $getValue[$i];
                
                $query = "UPDATE $parameter SET $myKey = '$myValue' WHERE $parameter3 = '$parameter4'";
                
            }

        }

        return $this->db->updateOneParam($query, $myValue);

    }

    public function updateManytoMany1($parameter, array $parameter1, array $parameter2, $parameter3, $parameter4, $parameter5, $parameter6) {

        $getKey = $parameter1;
        $getValue = $parameter2;
        
        $countKey = count($getKey);
        $countValue = count($getValue);

        if($countKey != $countValue){
            
            return "array|value mismatched";
            
        }else{

            for($i = 0; $i <= ($countKey - 1); $i++){
                
                $myKey = $getKey[$i];
                $myValue = $getValue[$i];
                
                $query = "UPDATE $parameter SET $myKey = '$myValue' WHERE $parameter3 = '$parameter4' AND $parameter5 = '$parameter6'";
                
            }

        }

        return $this->db->updateOneParam($query, $myValue);

    }



    /**
     * THIS SECTION IS FOR ALL SQL
     * DELETE STATEMENT WITH DYNAMIC
     * FUNCTIONS
     * 
     * Written By: AKINADE AYODEJI T.
     * For: Scalability purpose
     */

    //DELETE RECORDS WITH ONE PARAMETER
    public function deleteWithOneParam($parameter, $parameter1, $parameter2) {

        $query = "DELETE FROM $parameter WHERE $parameter1 = '$parameter2'";

        return $this->db->fetchByOne($query, $parameter2);

    }

    //DELETE RECORDS WITH TWO PARAMETERS
    public function deleteWithTwoParam($parameter, $parameter1, $parameter2, $parameter3, $parameter4) {

        $query = "DELETE FROM $parameter WHERE $parameter1 = '$parameter2' AND $parameter3 = '$parameter4'";

        return $this->db->fetchByTwo($query, $parameter2, $parameter4);

    }

    //FETCH RECORDS WITH THREE PARAMETERS
    public function deleteWithThreeParam($parameter, $parameter1, $parameter2, $parameter3, $parameter4, $parameter5, $parameter6) {

        $query = "DELETE FROM $parameter WHERE $parameter1 = '$parameter2' AND $parameter3 = '$parameter4' AND $parameter5 = '$parameter6'";

        return $this->db->fetchByThree($query, $parameter2, $parameter4, $parameter6);

    }



    /**
     * INSTITUTION AUDIT TRAIL
     */
    public static function viewAuditTrail($parameter){

        $column = array('id', 'Branch', 'Username', 'IP Address', 'Browser Details', 'Activities', 'DateTime');

        //Data table Parameter
        $searchValue = $paramter->searchValue;
        $draw = $paramter->draw;

        //Filltering Parameter
        $clientId = $paramter->clientId;
        $startDate = ($paramter->startDate != "") ? $paramter->startDate.' 00'.':00'.':00' : "2016-01-01 00:00:00";
        $endDate = ($paramter->endDate != "") ? $paramter->endDate.' 24'.':00'.':00' : date("Y-m-d").' 24'.':00'.':00';
        $filterBy = $paramter->filterBy;

        $query = " ";

        if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
            $query .= "SELECT * FROM audit_trail 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$clientId'";
            
        }
        
        if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all"){
            
            $query .= "SELECT * FROM audit_trail 
            WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$clientId' AND (branchid = '$filterBy' OR username = '$filterBy')";
            
        }

        if($searchValue != ''){

            $query .= 'SELECT * FROM audit_trail
            WHERE branchid LIKE "%'.$searchValue.'%" 
            ';

        }

        if(isset($_POST['order'])){

            $query .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' DESC ';

        }else{

            $query .= 'ORDER BY id DESC ';

        }

        $query1 = '';

        if($_POST['length'] != -1){

            $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];

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
            $branchid = $row['branchid'];

            $selectBranch = $this->fetchWithOneParam('branches', 'branchid', $branchid);

            $sub_array[] = "<input id='optionsCheckbox' class='checkbox' name='selector[]' type='checkbox' value='".$row['id']."'>";
            $sub_array[] = "<b>".($branchid == "") ? "Head Office" : $selectBranch['bname']."</b>";
            $sub_array[] = $row['username'];
            $sub_array[] = $row['ip_addrs'];
            $sub_array[] = $row['browser_details'];
            $sub_array[] = $row['activities_tracked'];
            $sub_array[] = $this->db->convertDateTime($row['date_time']);
            $data[] = $sub_array;

        }

        $query2 = "SELECT * FROM audit_trail WHERE companyid = '$clientId'";
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

    //Upload Image/Profile Picture
    public function uploadImage($sourcepath, $targetpath){

        //$location = $_FILES['image']['name'];
        //$sourcepath = $_FILES["image"]["tmp_name"];
		//$targetpath = "../img/" . $_FILES["image"]["name"];
		move_uploaded_file($sourcepath,$targetpath);

    }

    //Upload Attachment
    public function uploadAttachement($uploaded_file, $uploaded_fileTMP, $accountid, $account, $file_title){

        $date_time = date("Y-m-d h:i:s");

        foreach($uploaded_file as $key => $name){

            $newFilename = $name;
            
            if($newFilename == "")
            {
                //do nothing
            }
            else{
                $newlocation = $newFilename;
                if(move_uploaded_file($uploaded_fileTMP[$key], '../img/'.$newFilename))
                {

                    $queryAttchmt = "INSERT INTO attachment(get_id, borrowerid, tid, attached_file, file_title, fstatus, date_time) VALUES(?, ?, ?, ?, ?, ?, ?)";
                    $this->db->insertAttachment($queryAttchmt, $accountid, $account, $account, $newlocation, $file_title, 'Pending', $date_time);

                }
            }

        }

    }

    //Generate Bank Beneficiaries
    public function generateBankRecipient($acctNumber, $bankCode, $benefName, $companyid, $staffid, $sbranchid){

        $fetch_restapi = $this->fetchWithOneParam('restful_apisetup', 'api_name', 'transferrecipient');
        $api_url = $fetch_restapi['api_url'];
        $date_time = date("Y-m-d h:i:s");

        $searchSysset = $this->db->fetchSystemSet();  
        $brave_secret_key = $searchSysset['secret_key'];

        $searchBeneficiary = $this->fetchWithOneParam('transfer_recipient', 'acct_no', $acctNumber);

        $header = [
            "Content-Type: application/json",
            "Authorization: Bearer ".$brave_secret_key
        ];

        if($searchBeneficiary === 0){

            $sendData = array('account_number'=> $acctNumber, 'account_bank'=> $bankCode, 'beneficiary_name'=> $benefName);

            $response = $this->db->callAPI("POST", $api_url, json_encode($sendData), $header, 1);
            $rave_generate = json_decode($response, true);

            if($rave_generate['status'] == "success"){

                $recipient_id = $rave_generate['data']['id'];
                $bank_name = $rave_generate['data']['bank_name'];
                $fullname = $rave_generate['data']['fullname'];

                $queryBeneficiary = "INSERT INTO transfer_recipient(companyid, recipient_id, full_name, acct_no, bank_code, bank_name, date_time, staffid, sbranchid) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->db->insertBankBeneficiary($queryBeneficiary, $companyid, $recipient_id, $fullname, $acctNumber, $bankCode, $bank_name, $date_time, $staffid, $sbranchid);

            }else{
                //Do nothing
            }

        }else{
            //do nothing
        }

    }


}

?>