<?php
include('../config/session.php');

$column = array('id', 'Client Name', 'Client Branch', 'Staff/Sub-Agent', 'Total Borrowers', 'Total Loan Released', 'Total Loan Interest', 'Total Repayment');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$filterBy = $_POST['filterBy'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all"){
    
    $query .= "SELECT DISTINCT(agent) FROM loan_info 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (branchid = '$filterBy' OR sbranchid = '$filterBy' OR vendorid = '$filterBy' OR agent = '$filterBy' OR baccount = '$filterBy')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT DISTINCT(agent) FROM loan_info 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (status = 'Approved' OR status = 'Disbursed')
    ";
    
}





if($searchValue != '')
{
 $query .= "SELECT * FROM loan_info
 WHERE baccount LIKE '%".$_POST['search']['value']."%'
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
 
 $acct_officer = $row['agent'];
 $sel_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE agent = '$acct_officer' AND (status = 'Approved' OR status = 'Disbursed')") or die (mysqli_error($link));
 $row_loan = mysqli_fetch_array($sel_loan);
 $id = $row_loan['id'];
 $borrower = $row_loan['baccount'];
 $companyid = $row_loan['branchid'];
 $tbranch = $row_loan['sbranchid'];
 
 $search_user = mysqli_query($link, "SELECT * FROM user WHERE (id = '$acct_officer' OR name = '$acct_officer')");
 $fetch_user = mysqli_fetch_array($search_user);

 $search_inst = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$companyid'");
 $fetch_inst = mysqli_fetch_array($search_inst);

 $confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$tbranch'");
 $fetch_tbranch = mysqli_fetch_array($confirm_tbranch);

 //TOTAL BORROWERS REGISTERED BY THE STAFF/SUB-AGENT
 $selectin = mysqli_query($link, "SELECT COUNT(id) FROM borrowers WHERE lofficer = '$acct_officer'") or die (mysqli_error($link));
 $geth = mysqli_fetch_array($selectin);

 //GET TRACK OF TOTAL OTAL LOAN RELEASED BY EACH STAFF/SUB-AGENT & INTEREST RATE
 $sel_intr = mysqli_query($link, "SELECT SUM(amount), SUM(interest_rate) FROM loan_info WHERE agent = '$acct_officer' AND (status = 'Approved' OR status = 'Disbursed')") or die (mysqli_error($link));
 $row_intr = mysqli_fetch_array($sel_intr);
 $get_rate = $row_intr['SUM(interest_rate)'];
 $totalAmt = $row_intr['SUM(amount)'];
 $get_amt = $get_rate * $totalAmt;
 
 //TO GET TOTAL LOAN REPAYMENT COLLECTED BY THE STAFF
 $sel_pay = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments WHERE tid = '$acct_officer'") or die (mysqli_error($link));
 $row_sel_pay = mysqli_fetch_array($sel_pay);

 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = $fetch_inst['cname'];
 $sub_array[] = "<b>".(($tbranch === "") ? '---' : $fetch_tbranch['bname'])."</b>";
 $sub_array[] = $fetch_user['name'].' '.$fetch_user['lname'].' '.$fetch_user['mname'];
 $sub_array[] = number_format($geth['COUNT(id)'],0,'',',');
 $sub_array[] = $fetch_inst['currency'].number_format($totalAmt,2,'.',',');
 $sub_array[] = $fetch_inst['currency'].number_format($get_amt,2,'.',',');
 $sub_array[] = $fetch_inst['currency'].number_format($row_sel_pay['SUM(amount_to_pay)'],2,'.',',');
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT DISTINCT(agent) FROM loan_info";
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