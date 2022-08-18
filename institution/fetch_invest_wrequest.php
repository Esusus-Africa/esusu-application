<?php
include('../config/session1.php');

$column = array('id', 'Token', 'Agent Name', 'Vendor Name', 'Customer Name', 'Categories', 'Plan Name', 'Subscription Code', 'Amount Requested', 'Status', 'Date/Time');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$filterBy = $_POST['transType'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $filterBy != "Pending" && $filterBy != "Approved" && $filterBy != "Disapproved"){
    
    $query .= "SELECT * FROM mcustomer_wrequest 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND merchantid = '$institution_id' AND (vendorid = '$filterBy' OR agentid = '$filterBy' OR account_number = '$filterBy')
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM mcustomer_wrequest 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND merchantid = '$institution_id'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "Pending"){
    
    $query .= "SELECT * FROM mcustomer_wrequest 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND merchantid = '$institution_id' AND status = 'Pending'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "Approved"){
    
    $query .= "SELECT * FROM mcustomer_wrequest 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND merchantid = '$institution_id' AND status = 'Approved'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "Disapproved"){
    
    $query .= "SELECT * FROM mcustomer_wrequest 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND merchantid = '$institution_id' AND status = 'Disapproved'
    ";
    
}





if($searchValue != '')
{
 $query .= "SELECT * FROM mcustomer_wrequest
 WHERE subscription_code LIKE '%".$_POST['search']['value']."%' 
 OR account_number LIKE '%".$_POST['search']['value']."%' 
 OR wtokenid LIKE '%".$_POST['search']['value']."%'
 OR bank_details LIKE '%".$_POST['search']['value']."%'
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
 $wtoken = $row['wtokenid'];
 $merchantid = $row['merchantid'];
 $vendorid = $row['vendorid'];
 $agentid = $row['agentid'];
 $c_actno = $row['account_number'];
 $pcode = $row['plan_code'];
 $status = $row['status'];
 $date_time = $row['date_time'];

 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');

 $search_pcode = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$pcode'");
 $fetch_pcode = mysqli_fetch_array($search_pcode);

 $search_custi = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$c_actno'");
 $fetch_custi = mysqli_fetch_array($search_custi);

 $search_vendi = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
 $fetch_vendi = mysqli_fetch_array($search_vendi);

 $search_agent = mysqli_query($link, "SELECT * FROM user WHERE id = '$agentid'");
 $fetch_agent = mysqli_fetch_array($search_agent);
 $agent_name = $fetch_agent['name'].' '.$fetch_agent['lname'].' ('.$fetch_agent['virtual_acctno'].')';
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = ($approve_withdrawal_request == 1 && ($status == "Approved" || $status == "Disapproved") ? $wtoken : ($approve_withdrawal_request == 1 && $status == "Pending" ? '<a href="process_ifund.php?id='.$_SESSION['tid'].'&&sid='.$row['bank_details'].'&&tokenid='.$wtoken.'&&mid=NDkw&&tab=tab_6">'.$wtoken.'</a>' : ''));
 $sub_array[] = ($agentid == "") ? "---" : $agent_name;
 $sub_array[] = ($vendorid == "") ? "---" : $fetch_vendi['cname'];
 $sub_array[] = $fetch_custi['fname'].' '.$fetch_custi['lname'].' '.$fetch_custi['mname'].' ('.$fetch_custi['virtual_acctno'].')';
 $sub_array[] = $row['savings_type'];
 $sub_array[] = ($fetch_pcode['plan_name'] == "") ? "---" : $fetch_pcode['plan_name'];
 $sub_array[] = $row['subscription_code'];
 $sub_array[] = $row['amount_requested'];
 $sub_array[] = ($row['status'] == "Approved" ? '<span class="label bg-blue">Approve</span>' : ($row['status'] == "Disapproved" ? '<span class="label bg-red">Disapproved</span>' : '<span class="label bg-orange">Pending</span>'));
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM mcustomer_wrequest WHERE merchantid = '$institution_id'";
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