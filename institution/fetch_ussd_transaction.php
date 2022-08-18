<?php
include('../config/session1.php');

$column = array('id', 'Date', 'Service Code', 'Phone Number', 'Hops', 'Duration', 'Cost', 'Status');

$searchValue = $_POST['search']['value'];

## Custom Field value
//$startDate = date('Y-m-d H:i:s', strtotime($_POST['myStartDate']));
//$endDate = date('Y-m-d H:i:s', strtotime($_POST['myEndDate'] . ' +1 day'));
$startDate = (isset($_POST['myStartDate'])) ? $_POST['myStartDate'].' 00'.':00'.':00' : "";
$endDate = (isset($_POST['myEndDate'])) ? $_POST['myEndDate'].' 24'.':00'.':00' : "";

$query = " ";

if($startDate == "" && $endDate == ""){
    
    $query .= "SELECT * FROM session_levels WHERE companyid = '$institution_id'";
    
}

if($startDate != "" && $endDate != ""){
    
    $query .= "SELECT * FROM session_levels WHERE duration BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id'";
    
}

if($searchValue != '')
{
 $query .= 'SELECT * FROM session_levels
 WHERE phone_number LIKE "%'.$_POST['search']['value'].'%"
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
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = date('M, d, Y g:i A', strtotime($row['duration']));
 $sub_array[] = ($idedicated_ussd_prefix == "0") ? "<b>*347*62#</b>" : "<b>*347*62*".$idedicated_ussd_prefix."#</b>";
 $sub_array[] = $row['phone_number'];
 $sub_array[] = $row['hops'];
 $sub_array[] = ($row['target_seconds'] == "0" || $row['target_seconds'] == "") ? "0s" : $row['target_seconds']."s";
 $sub_array[] = "<b>".$icurrency.$row['session_cost']."</b>";
 $sub_array[] = ($row['level'] == 0) ? "<span class='label bg-blue'>Success</span>" : "<span class='label bg-orange'>Incomplete</span>";
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM session_levels WHERE companyid = '$institution_id'";
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