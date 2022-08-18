<?php
include('../config/session.php');

$column = array('id', 'TxID', 'Client Name', 'Client Branch', 'Savings Product', 'AcctNo', 'AcctName', 'Phone', 'Debit', 'Credit', 'Balance', 'DateTime', 'PostedBy');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = date('Y-m-d g:i A', strtotime($_POST['startDate']));
$endDate = date('Y-m-d g:i A', strtotime($_POST['endDate'] . ' +12 hours'));
$pmtType = $_POST['pmtType'];
$filterBy = $_POST['filterBy'];

//echo $startDate;

$query = " ";

if($startDate != "" && $endDate != "" && $pmtType != "" && $filterBy != "" && $pmtType != "All"){
    
    $query .= "SELECT * FROM transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (branchid = '$filterBy' OR sbranchid = '$filterBy' OR posted_by = '$filterBy' OR acctno = '$filterBy') AND (t_type = '$pmtType' OR p_type = '$pmtType')
     ";
    
}

if($startDate != "" && $endDate != "" && $pmtType != "" && $filterBy != "" && $pmtType === "All"){
    
    $query .= "SELECT * FROM transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (branchid = '$filterBy' OR sbranchid = '$filterBy' OR posted_by = '$filterBy' OR acctno = '$filterBy')
     ";
    
}

/*if($pmtType != ""){
    
    $query .= "
    WHERE t_type LIKE '%$pmtType%'
    ";
    
}*/


if($searchValue != '')
{
 $query .= "SELECT * FROM transaction
 WHERE acctno LIKE '%".$_POST['search']["value"]."%'
 ";
}


if(isset($_POST['order']))
{
 $query .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
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
 $companyid = $row['branchid'];
 $select3 = mysqli_query($link, "SELECT name FROM user WHERE id = '$posted_by'") or die (mysqli_error($link));
 $get_select3 = mysqli_fetch_array($select3);

 $confirm_borr = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acctno'");
 $fetch_borr = mysqli_fetch_array($confirm_borr);

 $confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$tbranch'");
 $fetch_tbranch = mysqli_fetch_array($confirm_tbranch);

 $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$companyid'");
 $fetch_inst = mysqli_fetch_array($search_inst);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<a href='view_receipt.php?id=".$row['id']."' target='_blank'>".$row['txid']."</a>";
 $sub_array[] = ($fetch_inst['institution_name'] == "") ? "account-migrated" : $fetch_inst['institution_name'];
 $sub_array[] = "<b>".($tbranch == "") ? "Head Office" : $fetch_tbranch['bname']."</b>";
 $sub_array[] = ($fetch_borr['acct_type'] == "") ? "---" : $fetch_borr['acct_type'];
 $sub_array[] = $row['acctno'];
 $sub_array[] = $row['ln'].' '.$row['fn'];
 $sub_array[] = $row['phone'];
 $sub_array[] = ($row['t_type'] == "Withdraw" || $row['t_type'] == "Withdraw-Charges" || $row['t_type'] == "Transfer") ? $row['currency'].number_format($row['amount'],2,'.',',') : "---";
 $sub_array[] = ($row['t_type'] == "Deposit" || $row['t_type'] == "Transfer-Received") ? $row['currency'].number_format($row['amount'],2,'.',',') : "---";
 $sub_array[] = ($row['balance'] == "") ? "---" : $row['currency'].number_format($row['balance'],2,'.',',');
 $sub_array[] = $row['date_time'];
 $sub_array[] = $get_select3['name'];
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM transaction";
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