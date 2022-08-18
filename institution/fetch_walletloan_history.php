<?php
include('../config/session1.php');

$column = array('id', 'Actions', 'lid', 'Account Name', 'Amount', 'Interest Rate', 'Interest Amount', 'Duration', 'Amount+Interest', 'Loan Balance', 'Booked By', 'Reviewer', 'Status', 'Date/Time');

$searchValue = $_POST['search']['value'];

## Custom Field value
//$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
//$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$startDate = (isset($_POST['startDate'])) ? $_POST['startDate'].' 00'.':00'.':00' : "";
$endDate = (isset($_POST['endDate'])) ? $_POST['endDate'].' 24'.':00'.':00' : "";
$transType = $_POST['transType'];
$filterBy = $_POST['filterBy'];
$sfilterBy = $_POST['sfilterBy'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != ""  && $transType != "" && $transType != "all" && $transType != "pend" && $transType != "apprv"){
    
    $query .= "SELECT * FROM wallet_loan_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND userid = '$filterBy' AND (bookedBy = '$transType' OR sbranchid = '$transType')
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != ""  && $transType != "" && $transType === "all"){
    
    $query .= "SELECT * FROM wallet_loan_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND userid = '$filterBy'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != ""  && $transType != "" && $transType === "pend"){
    
    $query .= "SELECT * FROM wallet_loan_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND lstatus = 'Pending' AND userid = '$filterBy'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != ""  && $transType != "" && $transType === "apprv"){
    
    $query .= "SELECT * FROM wallet_loan_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND lstatus = 'Pending' AND userid = '$filterBy'
    ";
    
}


if($searchValue != '')
{
 $query .= "SELECT * FROM wallet_loan_history
 WHERE userid LIKE '%".$_POST['search']['value']."%'
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
 
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',date("Y-m-d h:i:s", strtotime($row['date_time'])),new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');
 
 $acct_owner = $row['borrowerid'];
 $search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE userid = '$acct_owner'");
 $sRowNum = mysqli_num_rows($search_mystaff);
 $fetch_mystaff = mysqli_fetch_array($search_mystaff);
 $borrower_iuname = $fetch_mystaff['lname'].' '.$fetch_mystaff['name'].' '.$fetch_mystaff['mname'];
 
 $search_borro = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$acct_owner'");
 $bRowNum = mysqli_num_rows($search_borro);
 $fetch_borro = mysqli_fetch_array($search_borro);
 $borrower_uname = $fetch_borro['lname'].' '.$fetch_borro['fname'].' '.$fetch_borro['mname'];
 
 $userName = ($sRowNum == 0 && $bRowNum == 1) ? $borrower_iuname : $borrower_uname;

 $bookerid = $row['bookedBy'];
 $searchBooker = mysqli_query($link, "SELECT * FROM user WHERE id = '$bookerid'");
 $fetchBooker = mysqli_fetch_array($searchBooker);

 $reviewerid = $row['reviewer'];
 $searchReviewer = mysqli_query($link, "SELECT * FROM user WHERE id = '$reviewerid'");
 $fetchReviewer = mysqli_fetch_array($searchReviewer);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<a href='loanDetails.php?id=".$_SESSION['tid']."&&lide=".$row['lid']."&&act=".$sfilterBy."&&tab=tab_1' class='btn btn-default btn-flat'><i class='fa fa-eyes'>&nbsp;View Details</i></a>";
 $sub_array[] = $row['lid'];
 $sub_array[] = $userName.' ('.$row['userid'].')';
 $sub_array[] = $row['currency'].number_format($row['loanAmount'],2,'.',',');
 $sub_array[] = $row['interest_rate'].'%';
 $sub_array[] = $row['currency'].number_format($row['interestAmount'],2,'.',',');
 $sub_array[] = $row['duration'] . ' ' . (($row['tenor'] == "Weekly") ? 'Week(s)' : 'Month(s)');
 $sub_array[] = $row['currency'].number_format($row['amount_topay'],2,'.',',');
 $sub_array[] = $row['currency'].number_format($row['balance'],2,'.',',');
 $sub_array[] = $fetchBooker['lname'].' '.$fetchBooker['name'];
 $sub_array[] = ($reviewerid == "") ? "---" : $fetchReviewer['lname'].' '.$fetchReviewer['name'];
 $sub_array[] = ($row['lstatus'] == "Approved" ? "<span class='label bg-blue'>Approved</span>" : ($row['lstatus'] == "Pending" ? "<span class='label bg-orange'>Pending</span>" : "<span class='label bg-red'>".$row['lstatus']."</span>"));
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM wallet_loan_history WHERE branchid = '$institution_id' AND userid = '$filterBy'";
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