<?php
include('../config/session.php');

$column = array('id', 'Client Name', 'Total Trans. Count', 'Total Trans. Volume', 'Total Amount Declined', 'Total Amount Approved', 'Total Charges', 'Total Stampduty', 'Total Pending Balance', 'Total Settled Amount', 'Total Transfer Balance');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? $_POST['startDate'].' 00'.':00'.':00' : "";
$endDate = (isset($_POST['endDate'])) ? $_POST['endDate'].' '.(date(h) + 1).':'.date('i').':'.date('s') : "";
$filterBy = $_POST['filterBy'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all"){
    
    $query .= "SELECT DISTINCT(userid), COUNT(id), SUM(amount), SUM(charges), terminalId, initiatedBy FROM terminal_report 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (channel = '$filterBy' OR userid = '$filterBy' OR initiatedBy = '$filterBy' OR terminalId = '$filterBy')
    ";

}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT DISTINCT(userid), COUNT(id), SUM(amount), SUM(charges), terminalId FROM terminal_report 
    WHERE date_time BETWEEN '$startDate' AND '$endDate'
    ";
    
}

/*if($startDate === "" && $endDate === "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM wallet_history 
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
 $query .= "SELECT DISTINCT(userid), COUNT(id), SUM(amount), SUM(charges), terminalId FROM terminal_report
 WHERE channel LIKE '%".$_POST['search']['value']."%' 
 OR terminalId LIKE '%".$_POST['search']['value']."%'
 ";
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
 
 $client = $row['userid'];
 $search_myclient = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$client'");
 $sRowNum = mysqli_num_rows($search_myclient);
 $fetch_myclient = mysqli_fetch_array($search_myclient);
 $clientName = ($filterBy === "all" || $filterBy === "USSD" || $filterBy === "POS") ? "All" : $fetch_myclient['institution_name'];
 
 //Terminal Declined Report
 $search_temrptdcl = mysqli_query($link, "SELECT SUM(amount) FROM terminal_report WHERE date_time BETWEEN '$startDate' AND '$endDate' AND status = 'Declined'");
 $fetch_temrptdcl = mysqli_fetch_array($search_temrptdcl);
 
 //Terminal Approved Report
 ($filterBy === "all") ? $search_temrptapr = mysqli_query($link, "SELECT SUM(amount) FROM terminal_report WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (status = 'Approved' OR status = 'Success')") : "";
 ($filterBy != "all") ? $search_temrptapr = mysqli_query($link, "SELECT SUM(amount) FROM terminal_report WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (status = 'Approved' OR status = 'Success') AND (channel = '$filterBy' OR userid = '$filterBy' OR initiatedBy = '$filterBy' OR terminalId = '$filterBy')") : "";
 $fetch_temrptapr = mysqli_fetch_array($search_temrptapr);
 $apprvdAmt = $fetch_temrptapr['SUM(amount)'];

 //Terminal Approved Report
 ($filterBy === "all") ? $search_temrptstampdty = mysqli_query($link, "SELECT COUNT(id) FROM terminal_report WHERE date_time BETWEEN '$startDate' AND '$endDate'") : "";
 ($filterBy != "all") ? $search_temrptstampdty = mysqli_query($link, "SELECT COUNT(id) FROM terminal_report WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (channel = '$filterBy' OR userid = '$filterBy' OR initiatedBy = '$filterBy' OR terminalId = '$filterBy')") : "";
 $fetch_temrptstampdty = mysqli_fetch_array($search_temrptstampdty);
 $sumStampduty = $fetch_temrptstampdty['COUNT(id)'] * $stampduty;
 
 //Total Pending and Settled Balance
 ($filterBy === "all") ? $search_mypos = mysqli_query($link, "SELECT SUM(pending_balance), SUM(settled_balance) FROM terminal_reg WHERE terminal_status = 'Assigned'") : "";
 ($filterBy != "all") ? $search_mypos = mysqli_query($link, "SELECT SUM(pending_balance), SUM(settled_balance) FROM terminal_reg WHERE terminal_status = 'Assigned' AND (channel = '$filterBy' OR merchant_id = '$filterBy' OR tidoperator = '$filterBy' OR terminal_id = '$filterBy')") : "";
 $fetch_mypos = mysqli_fetch_array($search_mypos);
 $pendingBal = $fetch_mypos['SUM(pending_balance)'];
 $settledBal = $fetch_mypos['SUM(settled_balance)'];
 
 //Total Transfer Balance
 $initiator = $row['initiatedBy'];
 ($filterBy == "all" || $filterBy == "USSD" || $filterBy == "POS") ? $search_mystaff = mysqli_query($link, "SELECT SUM(transfer_balance) FROM user WHERE virtual_acctno != ''") : "";
 ($filterBy != "all" && $filterBy != "USSD" && $filterBy != "POS") ? $search_mystaff = mysqli_query($link, "SELECT SUM(transfer_balance) FROM user WHERE virtual_acctno != '' AND (created_by = '$filterBy' OR id = '$initiator')") : "";
 $fetch_mystaff = mysqli_fetch_array($search_mystaff);
 $operatorBal = $fetch_mystaff['SUM(transfer_balance)'];

 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value=''>";
 $sub_array[] = $clientName;
 $sub_array[] = $row['COUNT(id)'];
 $sub_array[] = number_format($row['SUM(amount)'],2,'.',',');
 $sub_array[] = number_format($fetch_temrptdcl['SUM(amount)'],2,'.',',');
 $sub_array[] = number_format($apprvdAmt,2,'.',',');
 $sub_array[] = number_format($row['SUM(charges)'],2,'.',',');
 $sub_array[] = number_format($sumStampduty,2,'.',',');
 $sub_array[] = number_format($pendingBal,2,'.',',');
 $sub_array[] = number_format($settledBal,2,'.',',');
 $sub_array[] = number_format($operatorBal,2,'.',',');
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM terminal_report";
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