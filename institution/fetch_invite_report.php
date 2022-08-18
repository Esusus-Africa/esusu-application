<?php
include('../config/session1.php');

$column = array('id', 'InviteCode', 'Referrer', 'firstName', 'Email', 'Phone', 'DateTime');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$transType = $_POST['transType'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all" && $transType != "Pending" && $transType != "Sent" && $transType != "Clicked" && $transType != "Registered"){
    
    $query .= "SELECT * FROM invite 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND (userid = '$transType' OR customerid = '$transType')
    ";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType === "Pending"){
    
    ($view_all_customers === "1" && $individual_customer_records != "1" && $individual_wallet != "1" && $branch_customer_records != '1' && $branch_wallet != "1") ? $query .= "SELECT * FROM invite 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND status = 'Pending'
    " : "";
    
    ($view_all_customers != "1" && ($individual_customer_records === "1" || $individual_wallet === "1") && $branch_customer_records != '1' && $branch_wallet != "1") ? $query .= "SELECT * FROM invite 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND status = 'Pending' AND userid = '$iuid'
    " : "";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType === "Sent"){
    
    ($view_all_customers === "1" && $individual_customer_records != "1" && $individual_wallet != "1" && $branch_customer_records != '1' && $branch_wallet != "1") ? $query .= "SELECT * FROM invite 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND status != 'Pending'
    " : "";
    
    ($view_all_customers != "1" && ($individual_customer_records === "1" || $individual_wallet === "1") && $branch_customer_records != '1' && $branch_wallet != "1") ? $query .= "SELECT * FROM invite 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND status != 'Pending' AND userid = '$iuid'
    " : "";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType === "Clicked"){
    
    ($view_all_customers === "1" && $individual_customer_records != "1" && $individual_wallet != "1" && $branch_customer_records != '1' && $branch_wallet != "1") ? $query .= "SELECT * FROM invite 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND status = 'Clicked'
    " : "";
    
    ($view_all_customers != "1" && ($individual_customer_records === "1" || $individual_wallet === "1") && $branch_customer_records != '1' && $branch_wallet != "1") ? $query .= "SELECT * FROM invite 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND status = 'Clicked' AND userid = '$iuid'
    " : "";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType === "Registered"){
    
    ($view_all_customers === "1" && $individual_customer_records != "1" && $individual_wallet != "1" && $branch_customer_records != '1' && $branch_wallet != "1") ? $query .= "SELECT * FROM invite 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND status = 'Registered'
    " : "";
    
    ($view_all_customers != "1" && ($individual_customer_records === "1" || $individual_wallet === "1") && $branch_customer_records != '1' && $branch_wallet != "1") ? $query .= "SELECT * FROM invite 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND status = 'Registered' AND userid = '$iuid'
    " : "";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType === "all"){
    
    ($view_all_customers === "1" && $individual_customer_records != "1" && $individual_wallet != "1" && $branch_customer_records != '1' && $branch_wallet != "1") ? $query .= "SELECT * FROM invite 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id'
     " : "";
     
    ($view_all_customers != "1" && ($individual_customer_records === "1" || $individual_wallet === "1") && $branch_customer_records != '1' && $branch_wallet != "1") ? $query .= "SELECT * FROM invite 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND userid = '$iuid'
     " : "";
    
}



if($searchValue != '')
{
 $query .= 'SELECT * FROM invite
 WHERE invite_code LIKE "%'.$_POST['search']['value'].'%"
 ';
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
 
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$row['date_time'],new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');
 
 $staff_id = $row['userid'];
 $search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$staff_id'");
 $sRowNum = mysqli_num_rows($search_mystaff);
 $fetch_mystaff = mysqli_fetch_array($search_mystaff);
 $staff_name = $fetch_mystaff['name'].' '.$fetch_mystaff['lname'].' '.$fetch_mystaff['mname'];
  
 $search_borro = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND account = '$staff_id'");
 $bRowNum = mysqli_num_rows($search_borro);
 $fetch_borro = mysqli_fetch_array($search_borro);
 $borro_name = $fetch_borro['lname'].' '.$fetch_borro['fname'].' '.$fetch_borro['mname'];
 
 $recipient_detector1 = ($sRowNum == 0 && $bRowNum == 1) ? $borro_name.' (Customer)' : $staff_name.' (Staff)';
 
 $status = $row['status'];
 $concat = $row['mydata'];
 $parameter = (explode('|',$concat));
 $fname = $parameter[0];
 $email = $parameter[1];
 $phone = $parameter[2];
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = ($status == "Registered") ? "<a href='invoice-print.php?id=".$_SESSION['tid']."&&mid=NDAz&&uid=".$row['customerid']."' target='_blank'>".$row['invite_code']."</a>" : $row['invite_code'];
 $sub_array[] = $recipient_detector1;
 $sub_array[] = ($status == "Registered") ? "<a href='invoice-print.php?id=".$_SESSION['tid']."&&mid=NDAz&&uid=".$row['customerid']."' target='_blank'>".$fname."</a>" : $fname;
 $sub_array[] = $email;
 $sub_array[] = ($phone == "") ? "---" : $phone;
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 ($view_all_customers === "1" && $individual_customer_records != "1") ? $query = "SELECT * FROM invite WHERE companyid = '$institution_id'" : "";
 ($view_all_customers != "1" && $individual_customer_records === "1") ? $query = "SELECT * FROM invite WHERE companyid = '$institution_id' AND userid = '$iuid'" : "";
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