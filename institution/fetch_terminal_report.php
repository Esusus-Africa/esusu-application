<?php
include('../config/session1.php');

$column = array('id', 'RefID', 'RRN', 'Operator', 'Branch', 'Channel', 'TID', 'TraceID/CardPan', 'Amount', 'Charges', 'Amount Settled', 'Pending Balance', 'Transfer Balance', 'Status', 'Date/Time');

$searchValue = $_POST['search']['value'];

## Custom Field value
//$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
//$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$startDate = (isset($_POST['startDate'])) ? $_POST['startDate'].' 00'.':00'.':00' : "";
$endDate = (isset($_POST['endDate'])) ? $_POST['endDate'].' 24'.':00'.':00' : "";
$transType = $_POST['transType'];
$iRole = $_POST['iRole'];

$query = " ";

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all" && $transType != "all1" && ($iRole === "agent_manager" || $iRole === "institution_super_admin" || $iRole === "merchant_super_admin")){
    
    $query .= "SELECT * FROM terminal_report 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$institution_id' AND (terminalId = '$transType' OR status = '$transType' OR channel = '$transType')
     ";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all" && $transType != "all1" && $iRole != "agent_manager" && $iRole != "institution_super_admin" && $iRole != "merchant_super_admin"){
    
    $query .= "SELECT * FROM terminal_report 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$institution_id' AND initiatedBy = '$iuid' AND (terminalId = '$transType' OR status = '$transType' OR channel = '$transType')
     ";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType === "all"){
    
    $query .= "SELECT * FROM terminal_report 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$institution_id'";
    
}


if($startDate != "" && $endDate != "" && $transType != "" && $transType === "all1"){
    
    $query .= "SELECT * FROM terminal_report 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$institution_id' AND initiatedBy = '$iuid'";
    
}

/*if($pmtType != ""){
    
    $query .= "
    WHERE t_type LIKE '%$pmtType%'
    ";
    
}*/

if($searchValue != '')
{
 $query .= 'SELECT * FROM terminal_report
 WHERE userid LIKE "%'.$column[$_POST['search']['value']].'%" 
 OR terminalId LIKE "%'.$column[$_POST['search']['value']].'%"
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
 $tidoperator = $row['initiatedBy'];
 $mybranch = $row['branchid'];

 $search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE id = '$tidoperator'");
 $fetch_mystaff = mysqli_fetch_array($search_mystaff);
 $staff_name = ($fetch_mystaff['name'] == "") ? "Unknown" : $fetch_mystaff['name'].' '.$fetch_mystaff['lname'].' ('.$fetch_mystaff['virtual_acctno'].')';

 $searchBranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$mybranch' AND created_by = '$institution_id'");
 $fetchBranch = mysqli_fetch_array($searchBranch);

 $date_time = $row['date_time'];
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');

 $stampdutyIncluded = ($row['amount'] >= $fetchsys_config['stampduty_bound']) ? ($row['amount'] - $fetchsys_config['stampduty_fee']) : $row['amount'];
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = $row['refid'];
 $sub_array[] = $row['retrievalRef'];
 $sub_array[] = $staff_name;
 $sub_array[] = ($mybranch == "") ? "Head Office" : $fetchBranch['bname'];
 $sub_array[] = $row['channel'];
 $sub_array[] = $row['terminalId'];
 $sub_array[] = $row['trace_id'];
 $sub_array[] = $row['currencyCode'].number_format($row['amount'],2,'.',',');
 $sub_array[] = $row['currencyCode'].number_format($row['charges'],2,'.',',');
 $sub_array[] = ($row['status'] == "Success" || $row['status'] == "Approved") ? $row['currencyCode'].number_format(($stampdutyIncluded - $row['charges']),2,'.',',') : "---";
 $sub_array[] = $row['currencyCode'].number_format($row['pending_balance'],2,'.',',');
 $sub_array[] = $row['currencyCode'].number_format($row['transfer_balance'],2,'.',',');
 $sub_array[] = ($row['status'] == "Success" || $row['status'] == "Approved" ? "<span class='label bg-blue'>".$row['status']."</span>" : ($row['status'] == "Pending" ? "<span class='label bg-orange'>Pending</span>" : "<span class='label bg-red'>".$row['status']."</span>"));
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM terminal_report WHERE userid = '$institution_id'";
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