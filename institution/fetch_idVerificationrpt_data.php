<?php
include('../config/session1.php');

$column = array('id', 'S/No', 'Operator-in-charge', 'Total Verified', 'Total UnVerified', 'Total NIN VCharges', 'Total BVN VCharges', 'Grand Total');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? $_POST['startDate'].' 00'.':00'.':00' : "";
$endDate = (isset($_POST['endDate'])) ? $_POST['endDate'].' 24'.':00'.':00' : "";
$filterBy = $_POST['filterBy'];

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all"){
    
    $query .= "SELECT DISTINCT(staffid), branchid FROM verification_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND (branchid = '$filterBy' OR staffid = '$filterBy')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT DISTINCT(staffid), branchid FROM verification_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id'
    ";
    
}





if($searchValue != '')
{
 $query .= "SELECT DISTINCT(staffid), branchid, verification_type FROM verification_history
 WHERE verification_type LIKE '%'.$searchValue.'%'
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
 $acct_officer = $row['staffid'];
 
 $search_user = mysqli_query($link, "SELECT name, lname, branchid FROM user WHERE created_by = '$institution_id' AND id = '$acct_officer'");
 $fetch_user = mysqli_fetch_array($search_user);
 $tbranch = $fetch_user['branchid'];

 $confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$tbranch'");
 $fetch_tbranch = mysqli_fetch_array($confirm_tbranch);

 //VERIFIED KYC
 $selV = mysqli_query($link, "SELECT COUNT(verificationStatus) FROM verification_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND staffid = '$acct_officer' AND verificationStatus = 'VERIFIED'");
 $rowV = mysqli_fetch_array($selV);
 //UNVERIFIED KYC
 $selUV = mysqli_query($link, "SELECT COUNT(verificationStatus) FROM verification_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND staffid = '$acct_officer' AND verificationStatus != 'VERIFIED'");
 $rowUV = mysqli_fetch_array($selUV);
 $uVCount = $rowUV['COUNT(verificationStatus)'];

 //NIN VERIFICATION
 $selNV = mysqli_query($link, "SELECT COUNT(verificationStatus) FROM verification_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND staffid = '$acct_officer' AND verification_type != 'BVN-FULL-DETAILS'");
 $rowNV = mysqli_fetch_array($selNV);
 $nVCharges = $rowNV['COUNT(verificationStatus)'] * $ininVerificationCharges;
 //BVN VERIFICATION
 $selBV = mysqli_query($link, "SELECT COUNT(verificationStatus) FROM verification_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND staffid = '$acct_officer' AND verification_type = 'BVN-FULL-DETAILS'");
 $rowBV = mysqli_fetch_array($selBV);
 $bVCharges = $rowBV['COUNT(verificationStatus)'] * $ibvnVerificationCharges;

 if($acct_officer === ""){

 }else{

    $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox'>";
    $sub_array[] = $i;
    $sub_array[] = $fetch_user['name'].' '.$fetch_user['lname'];
    $sub_array[] = "<b>".(($tbranch === "") ? '---' : $fetch_tbranch['bname'])."</b>";
    $sub_array[] = number_format($rowV['COUNT(verificationStatus)'],0,'',',');
    $sub_array[] = number_format($rowUV['COUNT(verificationStatus)'],0,'',',');
    $sub_array[] = number_format($nVCharges,2,'.',',');
    $sub_array[] = number_format($bVCharges,2,'.',',');
    $sub_array[] = number_format(($nVCharges + $bVCharges),2,'.',',');
    $data[] = $sub_array;

 }
 
}

function count_all_data($connect)
{
 $query = "SELECT DISTINCT(staffid), branchid FROM verification_history WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id'";
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