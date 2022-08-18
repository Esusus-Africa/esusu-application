<?php
include('../config/session1.php');

$column = array('id', 'Status', 'Institution', 'Branch', 'Operator', 'Transaction Type', 'Enrollee Name', 'Gender', 'MOI', 'TrackingId', 'NIN Number', 'BVN Number', 'Phone Contact', 'Email Address', 'Payment Type', 'Amount Paid', 'Balance Left', 'dateCreated', 'lastUpdated');

$searchValue = $_POST['search']['value'];

## Custom Field value
//$startDate = date('Y-m-d h:i:s', strtotime($_POST['startDate']));
$startDate = ($_POST['startDate'] != "") ? $_POST['startDate'].' 00'.':00'.':00' : "2016-01-01 00:00:00";
$endDate = ($_POST['endDate'] != "") ? $_POST['endDate'].' 24'.':00'.':00' : date("Y-m-d").' 24'.':00'.':00';
//$endDate = date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +1 day'));
$byCustomer = $_POST['byCustomer'];
$filterBy = $_POST['filterBy'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $byCustomer == "" && $filterBy != "" && $filterBy != "All"){
    
    $query .= "SELECT * FROM enrollees 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND (companyid = '$filterBy' OR branchid = '$filterBy' OR staffid = '$filterBy' OR nimcPartner = '$filterBy' OR transactionType = '$filterBy' OR ninSlipStatus = '$filterBy' OR idCardStatus = '$filterBy')
     ";
    
}

if($startDate == "" && $endDate == "" && $filterBy == "" && $byCustomer != ""){
    
    $query .= "SELECT * FROM enrollees 
    WHERE trackingId = '$byCustomer'
     ";
    
}


if($startDate != "" && $endDate != "" && $filterBy == "All" && $byCustomer == ""){
    
    $query .= "SELECT * FROM enrollees 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate'
     ";
    
}


if($startDate != "" && $endDate != "" && $filterBy == "" && $byCustomer == ""){
    
    $query .= "SELECT * FROM enrollees 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate'
     ";
    
}


if($searchValue != '')
{
 $query .= "SELECT * FROM enrollees
 WHERE trackingId LIKE '%'.$searchValue.'%' 
 OR ninNo LIKE '%'.$searchValue.'%' 
 OR bvn LIKE '%'.$searchValue.'%'
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
 $mybranch = $row['branchid'];
 $staffid = $row['staffid'];
 $companyid = $row['companyid'];
 
 $search_inst= mysqli_query($link, "SELECT institution_name FROM institution_data WHERE institution_id = '$companyid' AND status = 'Approved'");
 $fetch_inst = mysqli_fetch_array($search_inst);
 $instName = $fetch_inst['institution_name'];

 $search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$mysbranch'");
 $fetch_branch = mysqli_fetch_array($search_branch);
        
 $search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$staffid'");
 $fetch_staff = mysqli_fetch_array($search_staff);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='$id'>";
 $sub_array[] = "<p>NINSlip: <span class='label bg-".(($row['ninSlipStatus'] == 'Pending') ? 'orange' : (($row['ninSlipStatus'] == 'Completed') ? 'success' : 'blue'))."'>".$row['ninSlipStatus']."</span></p>
                IDCard: <span class='label bg-".(($row['idCardStatus'] == 'Pending') ? 'orange' : (($row['idCardStatus'] == 'Completed') ? 'success' : 'blue'))."'>".$row['idCardStatus']."</span>";
 $sub_array[] = $instName;
 $sub_array[] = ($mybranch == "") ? '<b>Head Office</b>' : '<b>'.$fetch_branch['bname'].'</b>';
 $sub_array[] = $fetch_staff['name'].' '.$fetch_staff['lname'];
 $sub_array[] = $row['transactionType'];
 $sub_array[] = $row['userLname'].' '.$row['userFname'].(($row['userMname'] != "") ? ' '.$row['userMname'] : '');
 $sub_array[] = $row['gender'];
 $sub_array[] = $row['moi'];
 $sub_array[] = $row['trackingId'];
 $sub_array[] = $row['ninNo'];
 $sub_array[] = $row['bvn'];
 $sub_array[] = $row['phoneNo'];
 $sub_array[] = $row['emailAddress'];
 $sub_array[] = $row['paymentType'];
 $sub_array[] = number_format(($row['amount'] - $row['balance']),2,'.',',');
 $sub_array[] = number_format($row['balance'],2,'.',',');
 $sub_array[] = convertDateTime($row['createdAt']);
 $sub_array[] = convertDateTime($row['updatedAt']);
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 ($all_enrollee_list === "1" && $individual_enrollee_list != "1" && $branch_enrollee_list != "1") ? $query = "SELECT * FROM enrollees WHERE companyid = '$institution_id'" : "";
 ($all_enrollee_list != "1" && $individual_enrollee_list === "1" && $branch_enrollee_list != "1") ? $query = "SELECT * FROM enrollees WHERE companyid = '$institution_id' AND staffid = '$iuid'" : "";
 ($all_enrollee_list != "1" && $individual_enrollee_list != "1" && $branch_enrollee_list === "1") ? $query = "SELECT * FROM enrollees WHERE companyid = '$institution_id' AND branchid = '$isbranchid'" : "";
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