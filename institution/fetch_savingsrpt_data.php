<?php
include('../config/session1.php');

$column = array('id', 'S/No', 'Staff Name', 'Branch', 'Pending Deposit', 'Approved Deposit', 'Pending Withdrawal', 'Approved Withdrawal', 'Approved Charges', 'Till Balance', 'Unsettled Balance', 'Commission Balance');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? $_POST['startDate'].' 00'.':00'.':00' : "";
$endDate = (isset($_POST['endDate'])) ? $_POST['endDate'].' 24'.':00'.':00' : "";
$filterBy = $_POST['filterBy'];

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all"){
    
    $query .= "SELECT DISTINCT(posted_by), sbranchid FROM transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND (sbranchid = '$filterBy' OR posted_by = '$filterBy')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT DISTINCT(posted_by), sbranchid FROM transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id'
    ";
    
}





if($searchValue != '')
{
 $query .= "SELECT DISTINCT t_type FROM transaction
 WHERE t_type LIKE '%'.$searchValue.'%'
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

$i = 0;

foreach($result as $row)
{
 $sub_array = array();
 $i++;
 $acct_officer = $row['posted_by'];
 
 $search_user = mysqli_query($link, "SELECT name, lname, branchid FROM user WHERE created_by = '$institution_id' AND (id = '$acct_officer' OR name = '$acct_officer')");
 $fetch_user = mysqli_fetch_array($search_user);
 $tbranch = $fetch_user['branchid'];

 $confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$tbranch'");
 $fetch_tbranch = mysqli_fetch_array($confirm_tbranch);

 //PENDING DEPOSIT
 $selPDp = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND posted_by = '$acct_officer' AND t_type = 'Deposit' AND status = 'Pending'");
 $rowPDp = mysqli_fetch_array($selPDp);

 //APPROVED DEPOSIT
 $selADp = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND posted_by = '$acct_officer' AND t_type = 'Deposit' AND status = 'Approved'");
 $rowADp = mysqli_fetch_array($selADp);

 //PENDING WITHDRAWAL
 $selPWth = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND posted_by = '$acct_officer' AND t_type = 'Withdraw' AND status = 'Pending'");
 $rowPWth = mysqli_fetch_array($selPWth);

 //APPROVED WITHDRAWAL
 $selAWth = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND posted_by = '$acct_officer' AND t_type = 'Withdraw' AND status = 'Approved'");
 $rowAWth = mysqli_fetch_array($selAWth);
 
 //APPROVED WITHDRAWAL CHARGES
 $selAWithCharg = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND posted_by = '$acct_officer' AND t_type = 'Withdraw-Charges' AND status = 'Approved'");
 $rowAWithCharg = mysqli_fetch_array($selAWithCharg);

 //TILL BALANCE & UNSETTLED BALANCE
 $selTillBal = mysqli_query($link, "SELECT balance, unsettled_balance, commission_balance FROM till_account WHERE companyid = '$institution_id' AND cashier = '$acct_officer' AND status = 'Active'");
 $rowTillBal = mysqli_fetch_array($selTillBal);

 if($acct_officer === ""){

 }else{

    $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox'>";
    $sub_array[] = $i;
    $sub_array[] = $fetch_user['name'].' '.$fetch_user['lname'];
    $sub_array[] = "<b>".(($tbranch === "") ? '---' : $fetch_tbranch['bname'])."</b>";
    $sub_array[] = number_format($rowPDp['SUM(amount)'],2,'.',',');
    $sub_array[] = number_format($rowADp['SUM(amount)'],2,'.',',');
    $sub_array[] = number_format($rowPWth['SUM(amount)'],2,'.',',');
    $sub_array[] = number_format($rowAWth['SUM(amount)'],2,'.',',');
    $sub_array[] = number_format($rowAWithCharg['SUM(amount)'],2,'.',',');
    $sub_array[] = number_format($rowTillBal['balance'],2,'.',',');
    $sub_array[] = number_format($rowTillBal['unsettled_balance'],2,'.',',');
    $sub_array[] = number_format($rowTillBal['commission_balance'],2,'.',',');
    $data[] = $sub_array;

 }
 
}

function count_all_data($connect)
{
 $query = "SELECT DISTINCT(posted_by), sbranchid FROM transaction WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id'";
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