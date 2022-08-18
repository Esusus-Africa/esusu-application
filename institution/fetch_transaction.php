<?php
include('../config/session1.php');

$column = array('id', 'TxID', 'Savings Product', 'Branch', 'AcctNo', 'AcctName', 'Phone', 'Debit', 'Credit','Balance', 'Status', 'DateTime', 'PostedBy');

$searchValue = $_POST['search']['value'];

## Custom Field value
//$startDate = date('Y-m-d g:i A', strtotime($_POST['startDate']));
//$endDate = date('Y-m-d g:i A', strtotime($_POST['endDate'] . ' +1 day'));
$startDate = ($_POST['startDate'] != "") ? $_POST['startDate'].' 00'.':00'.':00' : "2016-01-01 00:00:00";
$endDate = ($_POST['endDate'] != "") ? $_POST['endDate'].' 24'.':00'.':00' : date("Y-m-d").' 24'.':00'.':00';
$pmtType = $_POST['pmtType'];
$filterBy = $_POST['filterBy'];

//echo $institution_id;

$query = " ";

if($startDate != "" && $endDate != "" && $pmtType != "" && $filterBy != "" && $pmtType != "All"){
    
    ($individual_transaction_records != "1" && $branch_transaction_records != "1") ? $query .= "SELECT * FROM transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (sbranchid = '$filterBy' OR posted_by = '$filterBy' OR acctno = '$filterBy') AND branchid = '$institution_id' AND (t_type = '$pmtType' OR p_type = '$pmtType')" : "";
     
    ($individual_transaction_records === "1" && $branch_transaction_records != "1") ? $query .= "SELECT * FROM transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND posted_by = '$iuid' AND acctno = '$filterBy' AND (t_type = '$pmtType' OR p_type = '$pmtType')" : "";
     
    ($individual_transaction_records != "1" && $branch_transaction_records === "1") ? $query .= "SELECT * FROM transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND sbranchid = '$isbranchid' AND acctno = '$filterBy' AND (t_type = '$pmtType' OR p_type = '$pmtType')" : "";
    
}

if($filterBy != "" && $pmtType != "" && $pmtType == "All"){
    
    ($individual_transaction_records != "1" && $branch_transaction_records != "1") ? $query .= "SELECT * FROM transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND (sbranchid = '$filterBy' OR posted_by = '$filterBy' OR acctno = '$filterBy')" : "";
     
    ($individual_transaction_records === "1" && $branch_transaction_records != "1") ? $query .= "SELECT * FROM transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND (posted_by = '$iuid' OR acctno = '$filterBy')" : "";
     
    ($individual_transaction_records != "1" && $branch_transaction_records === "1") ? $query .= "SELECT * FROM transaction 
     OR date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND (sbranchid = '$isbranchid' OR acctno = '$filterBy')" : "";
    
}

if($filterBy == "" && $pmtType != "" && $pmtType != "All"){
    
    ($individual_transaction_records != "1" && $branch_transaction_records != "1") ? $query .= "SELECT * FROM transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND (t_type = '$pmtType' OR p_type = '$pmtType')" : "";
     
    ($individual_transaction_records === "1" && $branch_transaction_records != "1") ? $query .= "SELECT * FROM transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND posted_by = '$iuid' AND (t_type = '$pmtType' OR p_type = '$pmtType')" : "";
     
    ($individual_transaction_records != "1" && $branch_transaction_records === "1") ? $query .= "SELECT * FROM transaction 
     OR date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND sbranchid = '$isbranchid' AND (t_type = '$pmtType' OR p_type = '$pmtType')" : "";
    
}

if($filterBy == "" && $pmtType != "" && $pmtType == "All"){
    
    ($individual_transaction_records != "1" && $branch_transaction_records != "1") ? $query .= "SELECT * FROM transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id'" : "";
     
    ($individual_transaction_records === "1" && $branch_transaction_records != "1") ? $query .= "SELECT * FROM transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND posted_by = '$iuid'" : "";
     
    ($individual_transaction_records != "1" && $branch_transaction_records === "1") ? $query .= "SELECT * FROM transaction 
     OR date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND sbranchid = '$isbranchid'" : "";
    
}

/*
if($pmtType != ""){
    
    $query .= "
    WHERE t_type LIKE '%$pmtType%'
    ";
    
}
*/


if($searchValue != '')
{
 $query .= 'SELECT * FROM transaction
 WHERE txid LIKE "%'.$_POST['search']['value'].'%" 
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
 $posted_by = $row['posted_by'];
 $acctno = $row['acctno'];
 $tbranch = $row['sbranchid'];
 $status = $row['status'];
 $select3 = mysqli_query($link, "SELECT * FROM user WHERE id = '$posted_by'") or die (mysqli_error($link));
 $get_select3 = mysqli_fetch_array($select3);

 $confirm_borr = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acctno'");
 $fetch_borr = mysqli_fetch_array($confirm_borr);

 $confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$tbranch'");
 $fetch_tbranch = mysqli_fetch_array($confirm_tbranch);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<a href='view_receipt.php?id=".$row['id']."' target='_blank'>".$row['txid']."</a>";
 $sub_array[] = ($fetch_borr['acct_type'] == "") ? "---" : $fetch_borr['acct_type'];
 $sub_array[] = "<b>".($tbranch == "") ? "Head Office" : $fetch_tbranch['bname']."</b>";
 $sub_array[] = $row['acctno'];
 $sub_array[] = $row['ln'].' '.$row['fn'];
 $sub_array[] = $row['phone'];
 $sub_array[] = ($row['t_type'] == "Withdraw" || $row['t_type'] == "Withdraw-Charges" || $row['t_type'] == "Transfer") ? $row['currency'].number_format($row['amount'],2,'.',',') : "---";
 $sub_array[] = ($row['t_type'] == "Deposit" || $row['t_type'] == "Transfer-Received") ? $row['currency'].number_format($row['amount'],2,'.',',') : "---";
 $sub_array[] = ($row['balance'] == "") ? "---" : $row['currency'].number_format($row['balance'],2,'.',',');
 $sub_array[] = ($status == "Approved" ? "<span class='label bg-blue'>".$status."</span>" : ($status == "Pending" ? "<span class='label bg-orange'>".$status."</span>" : "<span class='label bg-red'>".$status."</span>"));
 $sub_array[] = $row['date_time'];
 $sub_array[] = $get_select3['name'].' '.$get_select3['lname'].' '.$get_select3['mname'];
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 ($individual_transaction_records != "1" && $branch_transaction_records != "1") ? $query = "SELECT * FROM transaction WHERE branchid = '$institution_id'" : "";
 ($individual_transaction_records === "1" && $branch_transaction_records != "1") ? $query = "SELECT * FROM transaction WHERE branchid = '$institution_id' AND posted_by = '$iuid'" : "";
 ($individual_transaction_records != "1" && $branch_transaction_records === "1") ? $query = "SELECT * FROM transaction WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid'" : "";
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