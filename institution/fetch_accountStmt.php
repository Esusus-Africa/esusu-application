<?php
include('../config/session1.php');

$column = array('id', 'Reference', 'Branch', 'A/c Officer', 'Source A/c', 'Credit', 'Debit', 'Balance', 'Remark', 'Status', 'Date/Time');

$searchValue = $_POST['search']['value'];

## Custom Field value
//$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
//$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$startDate = ($_POST['startDate'] != "") ? $_POST['startDate'].' 00'.':00'.':00' : "2016-01-01 00:00:00";
$endDate = ($_POST['endDate'] != "") ? $_POST['endDate'].' 24'.':00'.':00' : date("Y-m-d").' 24'.':00'.':00';
$transType = $_POST['transType'];

//echo $startDate;

$query = " ";

if($startDate != "" && $endDate != "" && $transType != "" && $transType === "all" && $transType != "all1" && $transType != "all2"){
    
    $query .= "SELECT * FROM bank_account_stmt 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND merchantid = '$institution_id'
     ";
    
}


if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all" && $transType === "all1" && $transType != "all2"){
    
    $query .= "SELECT * FROM bank_account_stmt 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND merchantid = '$institution_id' AND staffid = '$iuid'
     ";
    
}


if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all" && $transType != "all1" && $transType === "all2"){
    
    $query .= "SELECT * FROM bank_account 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND merchantid = '$institution_id' AND branchid = '$isbranchid'
     ";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all" && $transType != "all1" && $transType != "all2"){
    
    $query .= "SELECT * FROM bank_account_stmt 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND merchantid = '$institution_id' AND (staffid = '$transType' OR account_number = '$transType')
    ";
    
}



if($searchValue != '')
{
 $query .= "SELECT * FROM bank_account_stmt
 WHERE status LIKE '%".$_POST['search']['value']."'
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
 $id = $row['id'];
 $branchid = $row['branchid'];
 $staffid = $row['staffid'];
 $date_time = date("Y-m-d h:i:s", strtotime($row['date_time']));
 
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');
 
 $search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$branchid'");
 $fetch_branch = mysqli_fetch_array($search_branch);
 $bname = $fetch_branch['bname'];

 $search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$staffid'");
 $fetch_staff = mysqli_fetch_array($search_staff);
 $staffName = $fetch_staff['name'].' '.$fetch_staff['lname'].' '.$fetch_staff['mname'];

 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = $row['transaction_ref'];
 $sub_array[] = ($bname == "") ? 'Head Office' : $bname;
 $sub_array[] = $staffName;
 $sub_array[] = $row['account_number']." | ".$row['bank_name'];
 $sub_array[] = ($row['transaction_type'] == "credit") ? $row['curCode'].number_format($row['amount'],2,".",",") : "---";
 $sub_array[] = ($row['transaction_type'] == "debit") ? $row['curCode'].number_format($row['amount'],2,".",",") : "---";
 $sub_array[] = $row['curCode'].number_format($row['balance'],2,".",",");
 $sub_array[] = $row['remark'];
 $sub_array[] = ($row['status'] == "Active") ? '<span class="label bg-blue">Active</span>' : '<span class="label bg-orange">Not-Active</span>';
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM bank_account_stmt WHERE merchantid = '$institution_id'";
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