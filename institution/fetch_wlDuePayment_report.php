<?php
include('../config/session1.php');

$column = array('id', 'Action', 'Loan ID', 'Loan Officer', 'Loan ID', 'Account ID', 'Account Name', 'Due Amount', 'Balance Left', 'Schedule Due Date', 'Date/Time');

$searchValue = $_POST['search']['value'];

## Custom Field value
//$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
//$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$startDate = (isset($_POST['startDate'])) ? $_POST['startDate'].' 00'.':00'.':00' : "";
$endDate = (isset($_POST['endDate'])) ? $_POST['endDate'].' 24'.':00'.':00' : "";
$filterBy = $_POST['filterBy'];

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $filterBy != "all1" && $filterBy != "all2"){
    
    $query .= "SELECT * FROM wallet_due_loan 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND (tid = '$filterBy' OR lofficer = '$filterBy' OR sbranchid = '$filterBy')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM wallet_due_loan 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all1"){
    
    $query .= "SELECT * FROM wallet_due_loan 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND (tid = '$iuid' OR tid = '$ivirtual_acctno' OR lofficer = '$iuid')
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all2"){
    
    $query .= "SELECT * FROM wallet_due_loan 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND sbranchid = '$isbranchid'
    ";
    
}





if($searchValue != '')
{
 $query .= "SELECT * FROM wallet_due_loan
 WHERE lid LIKE '%".$_POST['search']['value']."%' 
 OR tid LIKE '%".$_POST['search']['value']."%'
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
 $borrower = $row['tid'];
 $lofficer = $row['lofficer'];
 $branchid = $row['sbranchid'];

 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',date("Y-m-d h:i:s", strtotime($row['date_time'])),new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');

 $search_VA = mysqli_query($link, "SELECT * FROM virtual_account WHERE account_number = '$borrower'");
 $fetch_VA = mysqli_fetch_array($search_VA);
 
 $search_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$lofficer'");
 $fetch_user = mysqli_fetch_array($search_user);

 $confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$branchid'");
 $fetch_tbranch = mysqli_fetch_array($confirm_tbranch);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                        <li><p><a href='newRepayment.php?id=".$_SESSION['tid']."&&mid=NDA0&&uid=".$borrower."&&tab=tab_1' class='btn btn-default btn-flat'><i class='fa fa-plus'>&nbsp;Make Repayment</i></a></p></li>
                    </ul>
                    </div>
                </div>";
 $sub_array[] = $lid;
 $sub_array[] = "<b>".(($branchid === "") ? '---' : $fetch_tbranch['bname'])."</b>";
 $sub_array[] = $fetch_user['name'].' '.$fetch_user['lname'].' '.$fetch_user['mname'];
 $sub_array[] = $borrower;
 $sub_array[] = $fetch_VA['account_number'];
 $sub_array[] = $icurrency.number_format($row['dueAmount'],2,".",",");
 $sub_array[] = $icurrency.number_format($row['balance'],2,".",",");
 $sub_array[] = $row['schedule_date'];
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM wallet_loan_repayment WHERE branchid = '$institution_id'";
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