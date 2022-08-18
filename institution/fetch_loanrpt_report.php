<?php
include('../config/session1.php');

$column = array('id', 'SNo', 'Staff Name', 'Branch', 'Loan Product', 'Loan Type', 'Principal Amount', 'Interest Rate', 'Rate Method', 'Amount to Pay', 'Loan Balance', 'Overdue', 'Total Repaid');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? $_POST['startDate'].' 00'.':00'.':00' : "";
$endDate = (isset($_POST['endDate'])) ? $_POST['endDate'].' 24'.':00'.':00' : "";
$filterBy = $_POST['filterBy'];
$loanProduct = $_POST['loanProduct'];

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $loanProduct != "" && $loanProduct != "all"){
    
    $query .= "SELECT DISTINCT(agent), sbranchid FROM loan_info 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND lproduct = '$loanProduct' AND p_status != 'PAID' AND (status = 'Approved' OR status = 'Disbursed') AND (sbranchid = '$filterBy' OR agent = '$filterBy')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $loanProduct === "all"){
    
    $query .= "SELECT DISTINCT(agent), sbranchid FROM loan_info 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND p_status != 'PAID' AND (status = 'Approved' OR status = 'Disbursed') AND (sbranchid = '$filterBy' OR agent = '$filterBy')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all" && $loanProduct != "" && $loanProduct != "all"){
    
    $query .= "SELECT DISTINCT(agent), sbranchid FROM loan_info 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND lproduct = '$loanProduct' AND p_status != 'PAID' AND (status = 'Approved' OR status = 'Disbursed')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all" && $loanProduct === "all"){
    
    $query .= "SELECT DISTINCT(agent), sbranchid FROM loan_info 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND p_status != 'PAID' AND (status = 'Approved' OR status = 'Disbursed')
    ";
    
}



if($searchValue != '')
{
 $query .= "SELECT DISTINCT(*) FROM loan_info
 WHERE baccount LIKE '%'.$searchValue.'%'
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
 $agent = $row['agent'];
 $lproduct = $loanProduct;
 $date_now = date("Y-m-d");

 ($loanProduct === "all") ? "" : $searchG = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$loanProduct'");
 ($lproduct === "all") ? "" : $fetchG = mysqli_fetch_array($searchG);

 $search_user = mysqli_query($link, "SELECT name, lname, branchid FROM user WHERE (id = '$agent' OR name = '$agent')");
 $fetch_user = mysqli_fetch_array($search_user);
 $tbranch = $fetch_user['branchid'];

 $confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$tbranch'");
 $fetch_tbranch = mysqli_fetch_array($confirm_tbranch);

 ($loanProduct == "all") ? $myExpAmt = mysqli_query($link, "SELECT SUM(balance), SUM(interest_rate), SUM(amount), SUM(amount_topay), loantype FROM loan_info WHERE date_time BETWEEN '$startDate' AND '$endDate' AND agent = '$agent' AND p_status != 'PAID' AND (status = 'Approved' OR status = 'Disbursed')") : "";
 ($loanProduct != "all" && $loanProduct != "") ? $myExpAmt = mysqli_query($link, "SELECT SUM(balance), SUM(interest_rate), SUM(amount), SUM(amount_topay), loantype FROM loan_info WHERE date_time BETWEEN '$startDate' AND '$endDate' AND agent = '$agent' AND lproduct = '$loanProduct' AND p_status != 'PAID' AND (status = 'Approved' OR status = 'Disbursed')") : "";
 $getExpAmt = mysqli_fetch_array($myExpAmt);
        
 $selectDuePay = mysqli_query($link, "SELECT SUM(payment) FROM pay_schedule WHERE lofficer = '$agent' AND schedule <= '$date_now' AND status = 'UNPAID'");
 $fetchDuePay = mysqli_fetch_array($selectDuePay);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = $i;
 $sub_array[] = $fetch_user['name'].' '.$fetch_user['lname'];
 $sub_array[] = "<b>".(($tbranch === "") ? '---' : $fetch_tbranch['bname'])."</b>";
 $sub_array[] = ($loanProduct === "all") ? "All Product(s)" : $fetchG['pname'];
 $sub_array[] = ($loanProduct === "all") ? "All Types(s)" : $getExpAmt['loantype'];
 $sub_array[] = number_format($getExpAmt['SUM(amount)'],2,'.',',');
 $sub_array[] = number_format($getExpAmt['SUM(interest_rate)'],2,'.',',');
 $sub_array[] = ($loanProduct === "all") ? "All Types(s)" : $fetchG['interest_type'];
 $sub_array[] = number_format($getExpAmt['SUM(amount_topay)'],2,'.',',');
 $sub_array[] = number_format($getExpAmt['SUM(balance)'],2,'.',',');
 $sub_array[] = number_format($fetchDuePay['SUM(payment)'],2,'.',',');
 $sub_array[] = number_format(($getExpAmt['SUM(amount_topay)'] - $getExpAmt['SUM(balance)']),2,'.',',');
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT DISTINCT(agent), sbranchid FROM loan_info WHERE date_time BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND p_status != 'PAID' AND (status = 'Approved' OR status = 'Disbursed')";
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