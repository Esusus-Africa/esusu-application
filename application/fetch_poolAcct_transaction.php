<?php
include('../config/session.php');

function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}

$column = array('id', 'Institution', 'Sender', 'RefID', 'Recipient', 'Purpose', 'Credit', 'Debit', 'Balance', 'Status', 'DateTime');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = date('Y-m-d h:i:s', strtotime($_POST['startDate']));
$endDate = date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +1 day'));
$transType = $_POST['transType'];
$filterBy = $_POST['filterBy'];

//echo $merchantid;
$query = " ";

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all" && $filterBy != "" && $filterBy != "all"){
    
    $query .= "SELECT * FROM pool_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND paymenttype = '$transType' AND (userid = '$filterBy' OR recipient = '$filterBy' OR initiator = '$filterBy')
     ";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType == "all" && $filterBy != "" && $filterBy != "all"){
    
    $query .= "SELECT * FROM pool_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (userid = '$filterBy' OR recipient = '$filterBy' OR initiator = '$filterBy')
     ";
    
}

//THIS SECTION IS FOR ALL TRANSACTION OCCURED IN THE SYSTEM
if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all" && $filterBy != "" && $filterBy == "all"){
    
    $query .= "SELECT * FROM pool_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND paymenttype = '$transType'
     ";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType == "all" && $filterBy != "" && $filterBy == "all"){
    
    $query .= "SELECT * FROM pool_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate'
     ";
    
}


/*if($pmtType != ""){
    
    $query .= "
    WHERE t_type LIKE '%$pmtType%'
    ";
    
}*/


if($searchValue != '')
{
 $query .= 'SELECT * FROM pool_history
 WHERE refid LIKE "%'.$column[$_POST['search']['value']].'%" 
 OR status LIKE "%'.$column[$_POST['search']['value']].'%"
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
 $userid = $row['userid'];
 $recipient = $row['recipient'];
 $initiator = $row['initiator'];
 $payment_type = $row['paymenttype'];
        
 $search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE (id = '$recipient' OR phone = '$recipient')");
 $num_mystaff = mysqli_num_rows($search_mystaff);
 $fetch_mystaff = mysqli_fetch_array($search_mystaff);
 $staff_name = $fetch_mystaff['name'].' '.$fetch_mystaff['lname'].' '.$fetch_mystaff['mname'].' ('.$fetch_mystaff['virtual_acctno'].')';
 
 $correctRec = $staff_name;
 
 $search_mystaff1 = mysqli_query($link, "SELECT * FROM user WHERE id = '$initiator'");
 $mystaff1Num = mysqli_num_rows($search_mystaff1);
 $fetch_mystaff1 = mysqli_fetch_array($search_mystaff1);
 $staff_name1 = $fetch_mystaff1['name'].' '.$fetch_mystaff1['lname'].' '.$fetch_mystaff1['mname'];

 $search_memset = mysqli_query($link, "SELECT cname FROM member_settings WHERE companyid = '$userid'");
 $get_memset = mysqli_fetch_array($search_memset);
 $cname = $get_memset['cname'];
        
 $search_memset2 = mysqli_query($link, "SELECT cname FROM member_settings WHERE companyid = '$recipient'");
 $get_memset2 = mysqli_fetch_array($search_memset2);
 $cname2 = $get_memset2['cname'];
 
 $searchInit = mysqli_query($link, "SELECT * FROM pool_account WHERE userid = '$initiator'");
 $fetchInit = mysqli_fetch_array($searchInit);

 $staff_VA1 = ($fetchInit['account_number'] == "") ? $fetch_mystaff1['virtual_acctno'] : $fetchInit['account_number'];
        
 $sender = (startsWith($userid,"AGT") ? $cname : (startsWith($userid,"INST") ? $cname : (startsWith($userid,"MER") ? $cname : (startsWith($userid,"COOP") ? $cname : $bo_name))));
        
 $recipient_name = (startsWith($recipient,"AGT") ? $cname2 : (startsWith($recipient,"INST") ? $cname2 : (startsWith($recipient,"MER") ? $cname2 : (startsWith($recipient,"COOP") ? $cname2 : (startsWith($recipient,"From") ? $recipient : $correctRec)))));

 $accountName = ($mystaff1Num == 1) ? $staff_name1.' ('.$staff_VA1.')' : $sender.' ('.$staff_VA1.')';
 
 $date_time = $row['date_time'];
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = ($row['userid'] == "") ? "Esusu Africa Superadmin" : $sender." (".$row['userid'].")";
 $sub_array[] = $accountName;
 $sub_array[] = $row['refid'];
 $sub_array[] = ($row['recipient'] == "" || $row['recipient'] == "self" ? "Esusu Africa" : ($num_mystaff === 1 && $recipient != "" && $recipient != "self" ? $recipient_name." (".$row['recipient'].")" : $recipient));
 $sub_array[] = $row['paymenttype'];
 $sub_array[] = "<span style='color: green;'><b>".(($row['credit'] === "") ? "---" : $row['currency'].number_format($row['credit'],2,'.',','))."</b></span>";
 $sub_array[] = "<span style='color: red;'><b>".(($row['debit'] === "") ? "---" : $row['currency'].number_format($row['debit'],2,'.',','))."</b></span>";
 $sub_array[] = "<b>".(($row['balance'] === "") ? "---" : $row['currency'].number_format($row['balance'],2,'.',','))."</b>";
 $sub_array[] = ($row['status'] == "successful" ? "<span class='label bg-blue'>success</span>" : ($row['status'] == "pendingpool" ? "<span class='label bg-orange'>Pending</span>" : "<span class='label bg-red'>".$row['status']."</span>"));
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

//($payment_type === "p2p-transfer" && $row['userid'] != "" && (startsWith($recipient,"MEM"))) ||
function count_all_data($connect)
{
 $query = "SELECT * FROM pool_history";
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