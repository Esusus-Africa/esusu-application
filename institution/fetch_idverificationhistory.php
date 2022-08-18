<?php
include('../config/session1.php');

$column = array('id', 'Reference', 'Type', 'Branch', 'Operator', 'AccountID', 'Verification Status', 'Transaction Status', 'DateTime');

$searchValue = $_POST['search']['value'];

## Custom Field value
//$startDate = date('Y-m-d h:i:s', strtotime($_POST['startDate']));
$startDate = ($_POST['startDate'] != "") ? $_POST['startDate'].' 00'.':00'.':00' : "2016-01-01 00:00:00";
$endDate = ($_POST['endDate'] != "") ? $_POST['endDate'].' 24'.':00'.':00' : date("Y-m-d").' 24'.':00'.':00';
$filterBy = $_POST['filterBy'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "All"){
    
    ($all_verification_history === "1" && $individual_verification_history != "1" && $branch_verification_history != "1") ? $query .= "SELECT * FROM verification_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND (branchid = '$filterBy' OR staffid = '$filterBy' OR acn = '$filterBy')
     " : "";

    ($all_verification_history != "1" && $individual_verification_history === "1" && $branch_verification_history != "1") ? $query .= "SELECT * FROM verification_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND staffid = '$iuid' AND (branchid = '$filterBy' OR acn = '$filterBy')
    " : "";

    ($all_verification_history != "1" && $individual_verification_history != "1" && $branch_verification_history === "1") ? $query .= "SELECT * FROM verification_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND branchid = '$isbranchid' AND (staffid = '$filterBy' OR acn = '$filterBy')
    " : "";
    
}


if($startDate != "" && $endDate != "" && $filterBy == "All"){
    
    ($all_verification_history === "1" && $individual_verification_history != "1" && $branch_verification_history != "1") ? $query .= "SELECT * FROM verification_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id'
     " : "";

    ($all_verification_history != "1" && $individual_verification_history === "1" && $branch_verification_history != "1") ? $query .= "SELECT * FROM verification_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND staffid = '$iuid'
    " : "";

    ($all_verification_history != "1" && $individual_verification_history != "1" && $branch_verification_history === "1") ? $query .= "SELECT * FROM verification_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND branchid = '$isbranchid'
    " : "";
    
}



if($searchValue != '')
{
    $query .= "SELECT * FROM verification_history
    WHERE id LIKE '%'.$searchValue.'%' 
    OR transactionReference LIKE '%'.$searchValue.'%' 
    OR verification_type LIKE '%'.$searchValue.'%' 
    OR acn LIKE '%'.$searchValue.'%'
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
 $acctno = $row['acn'];
 $date_time = $row['date_time'];
 $mybranch = $row['branchid'];
 $myofficer = $row['staffid'];
       
 $search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$mybranch'");
 $fetch_branch = mysqli_fetch_array($search_branch);
        
 $search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$myofficer'");
 $fetch_staff = mysqli_fetch_array($search_staff);

 $search_customer= mysqli_query($link, "SELECT * FROM borrower WHERE account = '$acctno'");
 $fetch_customer = mysqli_fetch_array($search_customer);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$id."'>";
 $sub_array[] = "<a href='viewIdentity.php?refid=".$row['transactionReference']."' target='_blank'><b>".$row['transactionReference']."</b></a>";
 $sub_array[] = $row['verification_type'];
 $sub_array[] = ($mybranch == "") ? 'NIL' : $fetch_branch['bname'];
 $sub_array[] = ($myofficer == "") ? 'NIL' : $fetch_staff['name'].' '.$fetch_staff['lname'];
 $sub_array[] = ($acctno == "") ? 'NIL' : $acctno.' ('.$fetch_customer['lname'].' '.$fetch_customer['fname'].')';
 $sub_array[] = $row['verificationStatus'];
 $sub_array[] = $row['transactionStatus'];
 $sub_array[] = convertDateTime($date_time);
 $data[] = $sub_array;
}

function count_all_data($connect)
{
    ($all_verification_history === "1" && $individual_verification_history != "1" && $branch_verification_history != "1") ? $query = "SELECT * FROM verification_history WHERE companyid = '$institution_id'" : "";
    ($all_verification_history != "1" && $individual_verification_history === "1" && $branch_verification_history != "1") ? $query = "SELECT * FROM verification_history WHERE companyid = '$institution_id' AND staffid = '$iuid'" : "";
    ($all_verification_history != "1" && $individual_verification_history != "1" && $branch_verification_history === "1") ? $query = "SELECT * FROM verification_history WHERE companyid = '$institution_id' AND branchid = '$isbranchid'" : "";
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