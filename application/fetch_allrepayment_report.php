<?php
include('../config/session.php');

$column = array('id', 'Reference ID', 'Client Name', 'Client Branch', 'Client Vendor', 'Loan Officer', 'Loan ID', 'Account ID', 'Account Name', 'Amount Payed', 'Loan Balance', 'Date', 'Status');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$filterBy = $_POST['filterBy'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all"){
    
    $query .= "SELECT * FROM payments 
    WHERE pay_date BETWEEN '$startDate' AND '$endDate' AND (branchid = '$filterBy' OR sbranchid = '$filterBy' OR vendorid = '$filterBy' OR tid = '$filterBy' OR account_no = '$filterBy')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM payments 
    WHERE pay_date BETWEEN '$startDate' AND '$endDate'
    ";
    
}






if($searchValue != '')
{
 $query .= "SELECT * FROM payments
 WHERE refid LIKE '%".$_POST['search']['value']."%' 
 OR customer LIKE '%".$_POST['search']['value']."%'
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
 $lid = $row['lid'];
 $borrower = $row['account_no'];
 $lofficer = $row['tid'];
 $companyid = $row['branchid'];
 $vendorid = $row['vendorid'];
 $branchid = $row['sbranchid'];
 $status = $row['status'];
 $remarks = $row['remarks'];
 /**$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',date("Y-m-d h:i:s", strtotime($row['date_time'])),new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');*/

 $selectin = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$borrower'") or die (mysqli_error($link));
 $geth = mysqli_fetch_array($selectin);
 $name = $geth['lname'].' '.$geth['fname'].' '.$geth['fname'];
 $myphone = $geth['phone'];
 
 $search_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$lofficer'");
 $fetch_user = mysqli_fetch_array($search_user);

 $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$companyid'");
 $fetch_inst = mysqli_fetch_array($search_inst);

 $searchUser = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
 $fetchUser = mysqli_fetch_array($searchUser);

 $confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$branchid'");
 $fetch_tbranch = mysqli_fetch_array($confirm_tbranch);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = $row['refid'];
 $sub_array[] = $fetch_inst['institution_name'];
 $sub_array[] = "<b>".(($branchid === "") ? '---' : $fetch_tbranch['bname'])."</b>";
 $sub_array[] = ($vendorid === "") ? '---' : $fetchUser['cname'];
 $sub_array[] = $fetch_user['name'].' '.$fetch_user['lname'].' '.$fetch_user['mname'];
 $sub_array[] = $row['lid'];
 $sub_array[] = $borrower;
 $sub_array[] = $row['customer'];
 $sub_array[] = $icurrency.number_format($row['amount_to_pay'],2,".",",");
 $sub_array[] = $icurrency.number_format($row['loan_bal'],2,'.',',');
 $sub_array[] = $row['pay_date'];
 $sub_array[] = ($remarks == 'paid') ? '<span class="label bg-blue">Paid</span>' : '<span class="label bg-orange">Pending...</span>';
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM payments";
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