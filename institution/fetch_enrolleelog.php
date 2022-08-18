<?php
include('../config/session1.php');

$column = array('id', 'Branch', 'Operator', 'NIMC Partner', 'IP Address', 'Browser Details', 'Activities Tracked', 'dateCreated');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = ($_POST['startDate'] != "") ? $_POST['startDate'].' 00'.':00'.':00' : "2016-01-01 00:00:00";
$endDate = ($_POST['endDate'] != "") ? $_POST['endDate'].' 24'.':00'.':00' : date("Y-m-d").' 24'.':00'.':00';
$filterBy = $_POST['filterBy'];

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "All"){
    
    ($view_all_enrolment_log === "1" && $view_individual_enrolment_log != "1" && $view_branch_enrolment_log != "1") ? $query .= "SELECT * FROM enrolleeLog 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND (branchid = '$filterBy' OR staffid = '$filterBy' OR nimcPartner = '$filterBy')
     " : "";
     
    ($view_all_enrolment_log != "1" && $view_individual_enrolment_log === "1" && $view_branch_enrolment_log != "1") ? $query .= "SELECT * FROM enrolleeLog 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND staffid = '$iuid' OR (companyid = '$institution_id' AND staffid = '$iuid' AND nimcPartner = '$filterBy')
     " : "";
     
    ($view_all_enrolment_log != "1" && $view_individual_enrolment_log != "1" && $view_branch_enrolment_log === "1") ? $query .= "SELECT * FROM enrolleeLog 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND branchid = '$isbranchid' OR (companyid = '$institution_id' AND branchid = '$isbranchid' AND nimcPartner = '$filterBy')
     " : "";
    
}

if($startDate != "" && $endDate != "" && $filterBy == "All"){
    
    ($view_all_enrolment_log === "1" && $view_individual_enrolment_log != "1" && $view_branch_enrolment_log != "1") ? $query .= "SELECT * FROM enrolleeLog 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id'
     " : "";
     
    ($view_all_enrolment_log != "1" && $view_individual_enrolment_log === "1" && $view_branch_enrolment_log != "1") ? $query .= "SELECT * FROM enrolleeLog 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND staffid = '$iuid'
     " : "";
     
    ($view_all_enrolment_log != "1" && $view_individual_enrolment_log != "1" && $view_branch_enrolment_log === "1") ? $query .= "SELECT * FROM enrolleeLog 
    WHERE createdAt BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND branchid = '$isbranchid'
     " : "";
    
}


if($searchValue != '')
{
 $query .= "SELECT * FROM enrolleeLog
 WHERE ip_addrs LIKE '%'.$searchValue.'%' 
 OR activities_tracked LIKE '%'.$searchValue.'%'
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
 $partnerid = $row['nimcPartner'];

 $search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$mysbranch'");
 $fetch_branch = mysqli_fetch_array($search_branch);
        
 $search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$staffid'");
 $fetch_staff = mysqli_fetch_array($search_staff);

 $search_partner = mysqli_query($link, "SELECT * FROM nimcPartner WHERE id = '$partnerid'");
 $fetch_partner = mysqli_fetch_array($search_partner);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='$id'>";
 $sub_array[] = ($mybranch == "") ? '<b>Head Office</b>' : '<b>'.$fetch_branch['bname'].'</b>';
 $sub_array[] = $fetch_staff['name'].' '.$fetch_staff['lname'];
 $sub_array[] = $fetch_partner['partnerName'];
 $sub_array[] = $row['ip_addrs'];
 $sub_array[] = $row['browser_details'];
 $sub_array[] = $row['activities_tracked'];
 $sub_array[] = convertDateTime($row['createdAt']);
 $data[] = $sub_array;
}

function count_all_data($connect)
{
    ($view_all_enrolment_log === "1" && $view_individual_enrolment_log != "1" && $view_branch_enrolment_log != "1") ? $query = "SELECT * FROM enrolleeLog WHERE companyid = '$institution_id'" : "";
    ($view_all_enrolment_log != "1" && $view_individual_enrolment_log === "1" && $view_branch_enrolment_log != "1") ? $query = "SELECT * FROM enrolleeLog WHERE companyid = '$institution_id' AND staffid = '$iuid'" : "";
    ($view_all_enrolment_log != "1" && $view_individual_enrolment_log != "1" && $view_branch_enrolment_log === "1") ? $query = "SELECT * FROM enrolleeLog WHERE companyid = '$institution_id' AND branchid = '$isbranchid'" : "";
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