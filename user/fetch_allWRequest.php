<?php
include('../config/session.php');

$column = array('id', 'TxID', 'Source', 'Amount Requested', 'Current Balance', 'Status', 'Remarks', 'PostedBy', 'DateTime');

$searchValue = $_POST['search']['value'];

## Custom Field value
//$startDate = date('Y-m-d g:i A', strtotime($_POST['startDate']));
//$endDate = date('Y-m-d g:i A', strtotime($_POST['endDate'] . ' +1 day'));
$startDate = ($_POST['startDate'] != "") ? $_POST['startDate'].' 00'.':00'.':00' : "2016-01-01 00:00:00";
$endDate = ($_POST['endDate'] != "") ? $_POST['endDate'].' 24'.':00'.':00' : date("Y-m-d").' 24'.':00'.':00';
$pmtType = $_POST['pmtType'];
$filterBy = $_POST['filterBy'];

$query = " ";

if($startDate != "" && $endDate != "" && $pmtType != "" && $filterBy != "" && $pmtType != "All"){
    
    $query .= "SELECT * FROM ledger_withdrawal_request 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND acn = '$filterBy' AND companyid = '$bbranchid' AND ptype = '$pmtType'";
    
}

if($filterBy != "" && $pmtType != "" && $pmtType == "All"){
    
    $query .= "SELECT * FROM ledger_withdrawal_request 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND acn = '$filterBy' AND companyid = '$bbranchid'";

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
 $query .= 'SELECT * FROM ledger_withdrawal_request
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
 $acct_officer = $row['acct_officer'];
 $acn = $row['acn'];
 $tbranch = $row['sbranchid'];
 $status = $row['status'];
 $select3 = mysqli_query($link, "SELECT * FROM user WHERE id = '$acct_officer'") or die (mysqli_error($link));
 $get_select3 = mysqli_fetch_array($select3);

 $confirm_borr = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
 $fetch_borr = mysqli_fetch_array($confirm_borr);

 $confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$tbranch'");
 $fetch_tbranch = mysqli_fetch_array($confirm_tbranch);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = $row['txid'];
 $sub_array[] = $row['balance_toimpact'].' account';
 $sub_array[] = $row['currency'].number_format($row['amt_requested'],2,'.',',');
 $sub_array[] = $row['currency'].(($row['balance_toimpact'] == "ledger") ? number_format($fetch_borr['balance'],2,'.',',') : (($row['balance_toimpact'] == "target") ? number_format($fetch_borr['target_savings_bal'],2,'.',',') : number_format($fetch_borr['investment_bal'],2,'.',',')));
 $sub_array[] = ($status == "Approved" ? "<span class='label bg-blue'>".$status."</span>" : ($status == "Pending" ? "<span class='label bg-orange'>".$status."</span>" : "<span class='label bg-red'>".$status."</span>"));
 $sub_array[] = $row['remarks'];
 $sub_array[] = ($acct_officer == "") ? "---" : $get_select3['name'].' '.$get_select3['lname'].' '.$get_select3['mname'];
 $sub_array[] = $row['date_time'];
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM ledger_withdrawal_request WHERE companyid = '$bbranchid' AND acn = '$filterBy'";
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