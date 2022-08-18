<?php
include('../config/session1.php');

$column = array('id', 'Action', 'Status', 'Branch', 'Operator', 'Transaction Type', 'Enrollee Name', 'MOI', 'TrackingId', 'NIN Number', 'BVN Number', 'Phone Contact', 'Amount Paid', 'Balance Left', 'Expected Amount', 'dateCreated', 'lastUpdated');

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
    
    ($all_enrollee_list === "1" && $individual_enrollee_list != "1" && $branch_enrollee_list != "1") ? $query .= "SELECT * FROM enrollees 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND (branchid = '$filterBy' OR staffid = '$filterBy' OR transactionType = '$filterBy' OR ninSlipStatus = '$filterBy' OR idCardStatus = '$filterBy')
     " : "";
     
    ($all_enrollee_list != "1" && $individual_enrollee_list === "1" && $branch_enrollee_list != "1") ? $query .= "SELECT * FROM enrollees 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND staffid = '$iuid'
     " : "";
     
    ($all_enrollee_list != "1" && $individual_enrollee_list != "1" && $branch_enrollee_list === "1") ? $query .= "SELECT * FROM enrollees 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND branchid = '$isbranchid'
     " : "";
    
}

if($startDate == "" && $endDate == "" && $filterBy == "" && $byCustomer != ""){
    
    ($all_enrollee_list === "1" && $individual_enrollee_list != "1" && $branch_enrollee_list != "1") ? $query .= "SELECT * FROM enrollees 
    WHERE trackingId = '$byCustomer' AND companyid = '$institution_id'
     " : "";
     
    ($all_enrollee_list != "1" && $individual_enrollee_list === "1" && $branch_enrollee_list != "1") ? $query .= "SELECT * FROM enrollees 
    WHERE trackingId = '$byCustomer' AND companyid = '$institution_id' AND staffid = '$iuid'
     " : "";
     
    ($all_enrollee_list != "1" && $individual_enrollee_list != "1" && $branch_enrollee_list === "1") ? $query .= "SELECT * FROM enrollees 
    WHERE trackingId = '$byCustomer' AND companyid = '$institution_id' AND branchid = '$isbranchid'
     " : "";
    
}


if($startDate != "" && $endDate != "" && $filterBy == "All" && $byCustomer == ""){
    
    ($all_enrollee_list === "1" && $individual_enrollee_list != "1" && $branch_enrollee_list != "1") ? $query .= "SELECT * FROM enrollees 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id'
     " : "";
     
    ($all_enrollee_list != "1" && $individual_enrollee_list === "1" && $branch_enrollee_list != "1") ? $query .= "SELECT * FROM enrollees 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND staffid = '$iuid'
     " : "";
     
    ($all_enrollee_list != "1" && $individual_enrollee_list != "1" && $branch_enrollee_list === "1") ? $query .= "SELECT * FROM enrollees 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND branchid = '$isbranchid'
     " : "";
    
}


if($startDate != "" && $endDate != "" && $filterBy == "" && $byCustomer == ""){
    
    ($all_enrollee_list === "1" && $individual_enrollee_list != "1" && $branch_enrollee_list != "1") ? $query .= "SELECT * FROM enrollees 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id'
     " : "";
     
    ($all_enrollee_list != "1" && $individual_enrollee_list === "1" && $branch_enrollee_list != "1") ? $query .= "SELECT * FROM enrollees 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND staffid = '$iuid'
     " : "";
     
    ($all_enrollee_list != "1" && $individual_enrollee_list != "1" && $branch_enrollee_list === "1") ? $query .= "SELECT * FROM enrollees 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND branchid = '$isbranchid'
     " : "";
    
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

 $search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$mysbranch'");
 $fetch_branch = mysqli_fetch_array($search_branch);
        
 $search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$staffid'");
 $fetch_staff = mysqli_fetch_array($search_staff);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='$id'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($update_enrollee == '1') ? "<li><p><a href='enrolleeDetails.php?id=".$id."&&mid=".base64_encode('711')."&&edit' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-edit'> Update</i></a></p></li>" : '')."
                    ".(($view_enrollee_details == '1') ? "<li><p><a href='enrolleeDetails.php?id=".$id."&&mid=".base64_encode('711')."&&view' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-eye'> View</i></a></p></li>" : '')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = "<p>NINSlip: <span class='label bg-".(($row['ninSlipStatus'] == 'Pending') ? 'orange' : (($row['ninSlipStatus'] == 'Completed') ? 'success' : 'blue'))."'>".$row['ninSlipStatus']."</span></p>
                IDCard: <span class='label bg-".(($row['idCardStatus'] == 'Pending') ? 'orange' : (($row['idCardStatus'] == 'Completed') ? 'success' : 'blue'))."'>".$row['idCardStatus']."</span>";
 $sub_array[] = ($mybranch == "") ? '<b>Head Office</b>' : '<b>'.$fetch_branch['bname'].'</b>';
 $sub_array[] = $fetch_staff['name'].' '.$fetch_staff['lname'];
 $sub_array[] = $row['transactionType'];
 $sub_array[] = $row['userLname'].' '.$row['userFname'].(($row['userMname'] != "") ? ' '.$row['userMname'] : '');
 $sub_array[] = $row['moi'];
 $sub_array[] = $row['trackingId'];
 $sub_array[] = ($row['ninNo'] == "") ? '---' : $row['ninNo'];
 $sub_array[] = ($row['bvn'] == "") ? '---' : $row['bvn'];
 $sub_array[] = $row['phoneNo'];
 $sub_array[] = number_format(($row['amount'] - $row['balance']),2,'.',',');
 $sub_array[] = number_format($row['balance'],2,'.',',');
 $sub_array[] = number_format($row['amount'],2,'.',',');
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