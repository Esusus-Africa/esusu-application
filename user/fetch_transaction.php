<?php
include('../config/session.php');

$column = array('id', 'Date', 'TxID', 'Description', 'Debit', 'Credit');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = date('Y-m-d h:i:s', strtotime($_POST['startDate']));
$endDate = date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +1 day'));
$pmtType = $_POST['pmtType'];

//echo $acctno;

$query = " ";

if($startDate != "" && $endDate != "" && $pmtType != "" && $pmtType != "all"){
    
    $query .= "SELECT * FROM transaction 
    WHERE (date_time BETWEEN '$startDate' AND '$endDate' AND (t_type = '$pmtType' OR p_type = '$pmtType') AND acctno = '$acctno') 
     OR (date_time BETWEEN '$startDate' AND '$endDate' AND (t_type = '$pmtType' OR p_type = '$pmtType') AND acctno = '$acctno')
     OR (date_time BETWEEN '$startDate' AND '$endDate' AND (t_type = '$pmtType' OR p_type = '$pmtType') AND acctno = '$acctno')
     ";
    
}

if($startDate != "" && $endDate != "" && $pmtType != "" && $pmtType === "all"){
    
    $query .= "SELECT * FROM transaction 
    WHERE (date_time BETWEEN '$startDate' AND '$endDate' AND acctno = '$acctno') 
     OR (date_time BETWEEN '$startDate' AND '$endDate' AND acctno = '$acctno') 
     OR (date_time BETWEEN '$startDate' AND '$endDate' AND acctno = '$acctno')
     OR (date_time BETWEEN '$startDate' AND '$endDate' AND acctno = '$acctno')
     ";
    
}

/*if($pmtType != ""){
    
    $query .= "
    WHERE t_type LIKE '%$pmtType%'
    ";
    
}*/


if($searchValue != '')
{
 $query .= 'SELECT * FROM transaction
 WHERE id LIKE "%'.$_POST['search']['value'].'%" 
 OR txid LIKE "%'.$_POST['search']['value'].'%" 
 OR date_time LIKE "%'.$_POST['search']['value'].'%"
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
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = date("d/m/Y g:i A", strtotime($row['date_time']));
 $sub_array[] = "<a href='view_receipt.php?id=".$row['id']."' target='_blank'>".$row['txid']."</a>";
 $sub_array[] = ($row['remark'] == "") ? "NILL" : $row['remark'];
 $sub_array[] = ($row['t_type'] == "Withdraw" || $row['t_type'] == "Withdraw-Charges" || $row['t_type'] == "Transfer") ? $row['currency'].number_format($row['amount'],2,'.',',') : "---";
 $sub_array[] = ($row['t_type'] == "Deposit" || $row['t_type'] == "Transfer-Received") ? $row['currency'].number_format($row['amount'],2,'.',',') : "---";
 $sub_array[] = ($row['balance'] != "") ? $row['currency'].number_format($row['balance'],2,'.',',') : "---";
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM transaction WHERE acctno = '$acctno'";
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