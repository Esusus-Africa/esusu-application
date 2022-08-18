<?php
include('../config/session.php');

function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}

$column = array('RefID','RRN', 'Terminal ID', 'Operator', 'Merchant ID', 'Merchant Name', 'Channel', 'Amount', 'Charges', 'Amount to Settle', 'Pending Balance', 'Transfer Balance', 'Status', 'Date/Time');

$searchValue = $_POST['search']['value'];

## Custom Field value
//$startDate = date('Y-m-d h:i:s', strtotime($_POST['startDate']));
//$endDate = date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +1 day'));
$startDate = (isset($_POST['startDate'])) ? $_POST['startDate'].' 00'.':00'.':00' : "";
$endDate = (isset($_POST['endDate'])) ? $_POST['endDate'].' 24'.':00'.':00' : "";
$transType = $_POST['transType'];

//echo $merchantid;
$query = " ";

if($startDate != "" && $endDate != "" && $transType != ""){
    
    $query .= "SELECT * FROM terminal_report 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (terminalId = '$transType' OR trace_id = '$transType')
     ";
    
}

/*if($pmtType != ""){
    
    $query .= "
    WHERE t_type LIKE '%$pmtType%'
    ";
    
}*/


if($searchValue != '')
{
 $query .= "SELECT * FROM terminal_report
 WHERE terminalId LIKE '%".$_POST['search']['value']."%' 
 OR trace_id LIKE '%".$_POST['search']['value']."%'
 ";
}


if(isset($_POST['order']))
{
 $query .= 'ORDER BY id DESC ';
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
 $initiatedBy = $row['initiatedBy'];

 $search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE id = '$initiatedBy'");
 $fetch_mystaff = mysqli_fetch_array($search_mystaff);
 $staff_name = ($fetch_mystaff['name'] == "") ? "Unknown" : $fetch_mystaff['name'].' '.$fetch_mystaff['lname'].' ('.$fetch_mystaff['virtual_acctno'].')';

 $date_time = $row['date_time'];
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');

 $stampdutyIncluded = ($row['amount'] >= $fetchsys_config['stampduty_bound']) ? ($row['amount'] - $fetchsys_config['stampduty_fee']) : $row['amount'];
 
 $sub_array[] = $row['refid'];
 $sub_array[] = $row['retrievalRef'];
 $sub_array[] = $row['terminalId'];
 $sub_array[] = $staff_name;
 $sub_array[] = $row['userid'];
 $sub_array[] = $row['subMerchantName'];
 $sub_array[] = $row['channel'];
 $sub_array[] = $row['currencyCode'].number_format($row['amount'],2,'.',',');
 $sub_array[] = $row['currencyCode'].number_format($row['charges'],2,'.',',');
 $sub_array[] = $row['currencyCode'].number_format(($stampdutyIncluded - $row['charges']),2,'.',',');
 $sub_array[] = $row['currencyCode'].number_format($row['pending_balance'],2,'.',',');
 $sub_array[] = $row['currencyCode'].number_format($row['transfer_balance'],2,'.',',');
 $sub_array[] = ($row['status'] == "Success" || $row['status'] == 'Approved' ? "<span class='label bg-blue'>success</span>" : ($row['status'] == "Pending" ? "<span class='label bg-orange'>pending</span>" : "<span class='label bg-red'>failed</span>"));;
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM terminal_report WHERE terminal_id = '$transType'";
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