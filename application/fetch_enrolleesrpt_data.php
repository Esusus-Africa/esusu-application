<?php
include('../config/session1.php');

$column = array('id', 'S/No', 'Institution', 'Operator-in-charge', 'Branch', 'Total Service Amount', 'Total Balance', 'Total Paid', 'NINSlip Status', 'IDCard Status');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? $_POST['startDate'].' 00'.':00'.':00' : "";
$endDate = (isset($_POST['endDate'])) ? $_POST['endDate'].' 24'.':00'.':00' : "";
$filterBy = $_POST['filterBy'];

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all"){
    
    $query .= "SELECT DISTINCT(staffid), branchid, companyid FROM enrollees 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND (companyid = '$filterBy' OR branchid = '$filterBy' OR staffid = '$filterBy' OR nimcPartner = '$filterBy')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT DISTINCT(staffid), branchid, companyid FROM enrollees 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate'
    ";
    
}





if($searchValue != '')
{
 $query .= "SELECT DISTINCT(staffid), branchid, userLname, userFname, phoneNo FROM transaction
 WHERE userLname LIKE '%'.$searchValue.'%' 
 OR userFname LIKE '%'.$searchValue.'%' 
 OR phoneNo LIKE '%'.$searchValue.'%' 
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
 $companyid = $row['companyid'];
 $acct_officer = $row['staffid'];

 $search_inst= mysqli_query($link, "SELECT institution_name FROM institution_data WHERE institution_id = '$companyid' AND status = 'Approved'");
 $fetch_inst = mysqli_fetch_array($search_inst);
 $instName = $fetch_inst['institution_name'];
 
 $search_user = mysqli_query($link, "SELECT name, lname, branchid FROM user WHERE created_by = '$companyid' AND id = '$acct_officer'");
 $fetch_user = mysqli_fetch_array($search_user);
 $tbranch = $fetch_user['branchid'];

 $confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$tbranch'");
 $fetch_tbranch = mysqli_fetch_array($confirm_tbranch);

 //TOTAL SERVICE AMOUNT
 $selSA = mysqli_query($link, "SELECT SUM(amount) FROM enrollees WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$companyid' AND staffid = '$acct_officer'");
 $rowSA = mysqli_fetch_array($selSA);

 //TOTAL BALANCE LEFT
 $selBL = mysqli_query($link, "SELECT SUM(balance) FROM enrollees WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$companyid' AND staffid = '$acct_officer'");
 $rowBL = mysqli_fetch_array($selBL);

 //PENDING SLIP STATUS
 $selPSS = mysqli_query($link, "SELECT COUNT(ninSlipStatus) FROM enrollees WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$companyid' AND staffid = '$acct_officer' AND ninSlipStatus = 'Pending'");
 $rowPSS = mysqli_fetch_array($selPSS);
 //COMPLETED SLIP STATUS
 $selCSS = mysqli_query($link, "SELECT COUNT(ninSlipStatus) FROM enrollees WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$companyid' AND staffid = '$acct_officer' AND ninSlipStatus = 'Completed'");
 $rowCSS = mysqli_fetch_array($selCSS);
 //COLLECTED SLIP STATUS
 $selCOLSS = mysqli_query($link, "SELECT COUNT(ninSlipStatus) FROM enrollees WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$companyid' AND staffid = '$acct_officer' AND ninSlipStatus = 'Collected'");
 $rowCOLSS = mysqli_fetch_array($selCOLSS);

 //PENDING IDCARD STATUS
 $selPIDS = mysqli_query($link, "SELECT COUNT(idCardStatus) FROM enrollees WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$companyid' AND staffid = '$acct_officer' AND idCardStatus = 'Pending'");
 $rowPIDS = mysqli_fetch_array($selPIDS);
 //COMPLETED IDCARD STATUS
 $selCIDS = mysqli_query($link, "SELECT COUNT(idCardStatus) FROM enrollees WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$companyid' AND staffid = '$acct_officer' AND idCardStatus = 'Completed'");
 $rowCIDS = mysqli_fetch_array($selCIDS);
 //COLLECTED IDCARD STATUS
 $selCOLIDS = mysqli_query($link, "SELECT COUNT(idCardStatus) FROM enrollees WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$companyid' AND staffid = '$acct_officer' AND idCardStatus = 'Collected'");
 $rowCOLIDS = mysqli_fetch_array($selCOLIDS);

 if($acct_officer === ""){

 }else{

    $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox'>";
    $sub_array[] = $i;
    $sub_array[] = $instName;
    $sub_array[] = $fetch_user['name'].' '.$fetch_user['lname'];
    $sub_array[] = "<b>".(($tbranch === "") ? '---' : $fetch_tbranch['bname'])."</b>";
    $sub_array[] = number_format($rowSA['SUM(amount)'],2,'.',',');
    $sub_array[] = number_format($rowBL['SUM(balance)'],2,'.',',');
    $sub_array[] = number_format(($rowSA['SUM(amount)'] - $rowBL['SUM(balance)']),2,'.',',');
    $sub_array[] = "Pending = " . $rowPSS['COUNT(ninSlipStatus)'] . ", Completed = " . $rowCSS['COUNT(ninSlipStatus)'] . ", Collected = " . $rowCOLSS['COUNT(ninSlipStatus)'];
    $sub_array[] = "Pending = " . $rowPIDS['COUNT(idCardStatus)'] . ", Completed = " . $rowCIDS['COUNT(idCardStatus)'] . ", Collected = " . $rowCOLIDS['COUNT(idCardStatus)'];
    $data[] = $sub_array;

 }
 
}

function count_all_data($connect)
{
 $query = "SELECT DISTINCT(staffid), branchid, companyid FROM enrollees WHERE date_time BETWEEN '$startDate' AND '$endDate'";
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