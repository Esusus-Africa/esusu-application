<?php
include('../config/session1.php');

$column = array('id', 'Invoice Code', 'Agent Name', 'Vendor Name', 'Customer Name', 'Plan Name', 'Subscription Code', 'Reference No.', 'Amount', 'Date/Time');

$searchValue = $_POST['search']['value'];

## Custom Field value
//$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
//$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$startDate = (isset($_POST['startDate'])) ? $_POST['startDate'].' 00'.':00'.':00' : "";
$endDate = (isset($_POST['endDate'])) ? $_POST['endDate'].' 24'.':00'.':00' : "";
$filterBy = $_POST['transType'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $filterBy != "pending" && $filterBy != "successful"){
    
    $query .= "SELECT * FROM all_savingssub_transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND merchant_id = '$institution_id' AND (vendorid = '$filterBy' OR agentid = '$filterBy' OR acn = '$filterBy')
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM all_savingssub_transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND merchant_id = '$institution_id'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "pending"){
    
    $query .= "SELECT * FROM all_savingssub_transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND merchant_id = '$institution_id' AND status = 'pending'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "successful"){
    
    $query .= "SELECT * FROM all_savingssub_transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND merchant_id = '$institution_id' AND status = 'successful'
    ";
    
}





if($searchValue != '')
{
 $query .= "SELECT * FROM all_savingssub_transaction
 WHERE subscription_code LIKE '%".$_POST['search']['value']."%' 
 OR acn LIKE '%".$_POST['search']['value']."%' 
 OR reference_no LIKE '%".$_POST['search']['value']."%'
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
 $merchantid = $row['merchant_id'];
 $vendorid = $row['vendorid'];
 $agentid = $row['agentid'];
 $date_time = $row['date_time'];
 $acn = $row['acn']; 
 $plan_code = $row['next_pmt_date'];
 
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');

 $search_plan = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$plan_code'");
 $fetch_plan = mysqli_fetch_array($search_plan);
 $plan1 = $fetch_plan['plan_name'];

 $search_plan2 = mysqli_query($link, "SELECT * FROM target_savings WHERE plan_code = '$plan_code'");
 $fetch_plan2 = mysqli_fetch_array($search_plan2);
 $plan2 = $fetch_plan2['plan_name'];

 $search_creator3 = mysqli_query($link, "SELECT * FROM borrowers WHERE (account = '$acn' OR virtual_acctno = '$acn')");
 $fetch_creator3 = mysqli_fetch_array($search_creator3);
 $creator_name3 = $fetch_creator3['lname'].' '.$fetch_creator3['fname'].' '.$fetch_creator3['mname'].' ('.$fetch_creator3['virtual_acctno'].')';

 $searchUser = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
 $fetchUser = mysqli_fetch_array($searchUser);

 $search_agent = mysqli_query($link, "SELECT * FROM user WHERE id = '$agentid'");
 $fetch_agent = mysqli_fetch_array($search_agent);
 $agent_name = $fetch_agent['name'].' '.$fetch_agent['lname'].' ('.$fetch_agent['virtual_acctno'].')';

 $plan_name = ($plan1 == "") ? $plan2 : $plan1;
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox' name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = '<b style="font-size:14px;">'.$row['invoice_code'].'</b>';
 $sub_array[] = ($agentid == "") ? "---" : $agent_name;
 $sub_array[] = $fetchUser['cname'];
 $sub_array[] = $creator_name3;
 $sub_array[] = ($plan_name == "") ? "---" : $plan_name;
 $sub_array[] = $row['subscription_code'];
 $sub_array[] = '<b style="font-size:14px;">'.$row['reference_no'].'</b><p></p> Status: '.
                ($row['status'] == "successful" ? '<span class="label bg-blue">Success</span>' : ($row['status'] == "pending" ? '<span class="label bg-orange">Pending</span>' : '<span class="label bg-red">failed</span>'));
 $sub_array[] = $row['currency'].number_format($row['amount'],2,'.',',');
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM all_savingssub_transaction WHERE merchant_id = '$institution_id'";
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