<?php
include('../config/session.php');

$column = array('id', 'Acct. Type', 'Institution', 'Acct. Officer', 'Acct. Number', 'Acct. Name', 'Bank Name', 'Available Balance', 'Opening Date');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$filterBy = $_POST['filterBy'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $filterBy != "agent" && $filterBy != "corporate"){
    
    $query .= "SELECT * FROM pool_account 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid != '' AND (userid = '$filterBy' OR companyid = '$filterBy')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM pool_account 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid != ''
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && ($filterBy === "agent" || $filterBy === "corporate")){
    
    $query .= "SELECT * FROM pool_account 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND reg_type = '$filterBy'
    ";
    
}

/*if($startDate === "" && $endDate === "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM wallet_history 
    WHERE (userid = '$institution_id' OR recipient = '$institution_id')
     ";
    
}*/

/*if($pmtType != ""){
    
    $query .= "
    WHERE t_type LIKE '%$pmtType%'
    ";
    
}*/


if($searchValue != '')
{
 $query .= 'SELECT * FROM pool_account
 WHERE account_number LIKE "%'.$_POST['search']['value'].'%"
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
 
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',date("Y-m-d h:i:s", strtotime($row['date_time'])),new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');
 
 $institution_id = $row['companyid'];
 $userAcctOfficer = $row['acctOfficer'];
 $userAcctType = $row['reg_type'];

 $searchUser = mysqli_query($link, "SELECT * FROM user WHERE id = '$userAcctOfficer'");
 $fetchUser = mysqli_fetch_array($searchUser);

 $search_memset = mysqli_query($link, "SELECT cname FROM member_settings WHERE companyid = '$institution_id'");
 $get_memset = mysqli_fetch_array($search_memset);
 $cname = $get_memset['cname'];
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = ucwords($userAcctType);
 $sub_array[] = '<b>'.$cname.'</b>';
 $sub_array[] = $fetchUser['name'].' '.$fetchUser['lname'];
 $sub_array[] = "<a href='poolAcct_history.php?id=".$_SESSION['tid']."&&mid=NDE5&&tab=tab_1'>".$row['account_number']."</a>";
 $sub_array[] = '<b>'.$row['account_name'].'</b>';
 $sub_array[] = $row['bank_name'];
 $sub_array[] = '<b>'.$row['currency'].number_format($row['availableBal'],2,'.',',').'</b>';
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM pool_account";
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