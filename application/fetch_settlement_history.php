<?php
include('../config/session.php');

$column = array('id', 'Status', 'Reference', 'Client Name', 'Vendor Name', 'Vendor Contact', 'Request Amount', 'Destination Channel', 'A/c Details', 'DateTime');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$filterBy = $_POST['transType'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $filterBy != "Pending" && $filterBy != "Settled" && $filterBy != "Declined"){
    
    $query .= "SELECT * FROM manual_investsettlement 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (companyid = '$filterBy' OR vendorid = '$filterBy')
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM manual_investsettlement 
    WHERE date_time BETWEEN '$startDate' AND '$endDate'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "Pending"){
    
    $query .= "SELECT * FROM manual_investsettlement 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND status = 'Pending'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "Declined"){
    
    $query .= "SELECT * FROM manual_investsettlement 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND status = 'Declined'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "Settled"){
    
    $query .= "SELECT * FROM manual_investsettlement 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND status = 'Settled'
    ";
    
}






if($searchValue != '')
{
 $query .= "SELECT * FROM manual_investsettlement
 WHERE vendorid LIKE '%".$_POST['search']['value']."%' 
 OR companyid LIKE '%".$_POST['search']['value']."%' 
 OR vendorContact LIKE '%".$_POST['search']['value']."%'
 OR refid LIKE '%".$_POST['search']['value']."%'
 OR status LIKE '%".$_POST['search']['value']."%'
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
 $date_time = $row['date_time'];

 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');

 $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$merchantid'");
 $fetch_insti = mysqli_fetch_array($search_insti);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = ($row['status'] == "Settled" ? '<span class="label bg-blue">Settled</span>' : ($row['status'] == "Declined" ? '<span class="label bg-red">Declined</span>' : '<span class="label bg-orange">Pending</span>'));
 $sub_array[] = $row['refid'];
 $sub_array[] = $fetch_insti['institution_name'];
 $sub_array[] = $row['vendorName'];
 $sub_array[] = $row['vendorContact'];
 $sub_array[] = $row['currency'].number_format($row['amount'],2,'.',',');
 $sub_array[] = $row['destinationChannel'];
 $sub_array[] = $row['details'];
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM manual_investsettlement";
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