<?php
include('../config/session1.php');

$column = array('id', 'RefID', 'Initiated By', 'Branch', 'Cashier', 'Purpose', 'Credit', 'Debit', 'Balance', 'Status', 'DateTime');

$searchValue = $_POST['search']['value'];

## Custom Field value
//$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
//$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$startDate = (isset($_POST['startDate'])) ? $_POST['startDate'].' 00'.':00'.':00' : "";
$endDate = (isset($_POST['endDate'])) ? $_POST['endDate'].' 24'.':00'.':00' : "";
$transType = $_POST['transType'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all"){
    
    ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $query .= "SELECT * FROM fund_allocation_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND (paymenttype = '$transType' OR manager_id = '$transType' OR cashier = '$transType')
    " : "";

    ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin") ? $query .= "SELECT * FROM fund_allocation_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND (paymenttype = '$transType' OR manager_id = '$transType' OR cashier = '$transType' OR cashier = '$iuid' OR manager_id = '$iuid')
    " : "";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType === "all"){
    
    ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $query .= "SELECT * FROM fund_allocation_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id'
    " : "";

    ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin") ? $query .= "SELECT * FROM fund_allocation_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND (cashier = '$iuid' OR manager_id = '$iuid')
    " : "";
    
}

/*if($startDate === "" && $endDate === "" && $transType != "" && $transType === "all"){
    
    $query .= "SELECT * FROM pool_history 
    WHERE (userid = '$institution_id' OR recipient = '$institution_id')
     ";
    
}*/

/*if($pmtType != ""){
    
    $query .= "
    WHERE t_type LIKE '%$pmtType%'
    ";
    
}*/


if($searchValue != '')
{
 $query .= 'SELECT * FROM institution_super_admin
 WHERE refid LIKE "%'.$_POST['search']['value'].'%"
 ';
}


if(isset($_POST['order']))
{
 $query .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' DESC ';
}
else
{
 $query .= 'ORDER BY id DESC ';
}


$query1 = '';

if($_POST['length'] != -1)
{
 $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}


$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = array();

foreach($result as $row)
{
 $sub_array = array();
 
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$row['date_time'],new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');

 //Cashier Details
 $staff_id = $row['cashier'];
 $search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$staff_id'");
 $fetch_mystaff = mysqli_fetch_array($search_mystaff);
 $sRowNum = mysqli_num_rows($search_mystaff);
 $staff_name = $fetch_mystaff['name'].' '.$fetch_mystaff['lname'].' '.$fetch_mystaff['mname'];
 $staff_VA = $fetch_mystaff['virtual_acctno'];
 
 //Initiator Details
 $initiator = $row['manager_id'];
 $search_mystaff1 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$initiator'");
 $fetch_mystaff1 = mysqli_fetch_array($search_mystaff1);
 $sRowNum1 = mysqli_num_rows($search_mystaff1);
 $staff_name1 = $fetch_mystaff1['name'].' '.$fetch_mystaff1['lname'].' '.$fetch_mystaff1['mname'];

 $verifyTillAcct = mysqli_query($link, "SELECT * FROM till_virtual_account WHERE userid = '$initiator'");
 $fetchTillAcct = mysqli_fetch_array($verifyTillAcct);
 $staff_VA1 = ($fetchTillAcct['account_number'] == "") ? $fetch_mystaff1['virtual_acctno'] : $fetchTillAcct['account_number'];
 
 $recipient_detector1 = ($sRowNum == 1) ? $staff_VA.' ['.$staff_name.']' : "Unknown";
 $recipient_detector = ($sRowNum1 == 1) ? $staff_VA1.' ['.$staff_name1.']' : "ESUSU AFRICA";

 //Branch Details
 $mbranchid = $row['branch'];
 $search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$mbranchid'");
 $fetch_branch = mysqli_fetch_array($search_branch);
 $bname = $fetch_branch['bname'];
 
 $payment_type = $row['paymenttype'];
 $transType = $row['transaction_type'];
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = (($row['ttype'] == "Debit") ? "<span style='color: red'>".$row['txid']."</span>" : $row['txid']);
 $sub_array[] = $recipient_detector;
 $sub_array[] = ($mbranchid == "") ? "---" : $bname;
 $sub_array[] = $recipient_detector1;
 $sub_array[] = $payment_type;
 
 $sub_array[] = "<span style='color: green;'><b>".($row['ttype'] === "Credit" ? $row['currency'].number_format($row['amount_fund'],2,'.',',') : '---')."</b></span>";
 $sub_array[] = "<span style='color: red;'><b>".($row['ttype'] === "Debit" ? $row['currency'].number_format($row['amount_fund'],2,'.',',') : '---')."</b></span>";
 
 $sub_array[] = "<b>".(($row['balance'] === "") ? "---" : $row['currency'].number_format($row['balance'],2,'.',','))."</b>";
 $sub_array[] = ($row['status'] == "successful" ? "<span class='label bg-blue'>success</span>" : ($row['status'] == "pendingtill" || $row['status'] == "Pending" || $row['status'] == "tbPending" ? "<span class='label bg-orange'>Pending</span>" : "<span class='label bg-red'>".$row['status']."</span>"));
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $query = "SELECT * FROM fund_allocation_history WHERE companyid = '$institution_id'" : "";
 ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin") ? $query = "SELECT * FROM fund_allocation_history WHERE companyid = '$institution_id' AND (cashier = '$iuid' OR manager_id = '$iuid')" : "";
 $statement = $connect->prepare($query);
 $statement->execute();
 return $statement->rowCount();
}

$output = array(
 'draw'    => intval($_POST['draw']),
 'recordsTotal'  => count_all_data($connect),
 'recordsFiltered' => $number_filter_row,
 'data'    => $data
);

echo json_encode($output);

?>