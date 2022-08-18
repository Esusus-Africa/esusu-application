<?php
include('../config/session.php');

function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}

$column = array('id', 'Institution', 'Account Name', 'RefID', 'Recipient', 'Purpose', 'Credit', 'Balance', 'Debit', 'Status', 'DateTime');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = date('Y-m-d h:i:s', strtotime($_POST['startDate']));
$endDate = date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +1 day'));
$transType = $_POST['transType'];
$filterBy = $_POST['filterBy'];

//echo $merchantid;
$query = " ";

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all"){
    
    $query .= "SELECT * FROM wallet_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND paymenttype = '$transType' AND (userid = '$filterBy' OR recipient = '$filterBy' OR initiator = '$filterBy')
     ";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType == "all"){
    
    $query .= "SELECT * FROM wallet_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (userid = '$filterBy' OR recipient = '$filterBy' OR initiator = '$filterBy')
     ";
    
}







if($searchValue != '')
{
 $query .= 'SELECT * FROM wallet_history
 WHERE refid LIKE "%'.$column[$_POST['search']['value']].'%"
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
    
 $search_bo = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$userid' OR phone = '$userid'");
 $get_bo = mysqli_fetch_array($search_bo);
 $bo_name = $get_bo['lname'].' '.$get_bo['fname'];
        
 $search_bo1 = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$recipient' OR phone = '$recipient'");
 $get_bo1 = mysqli_fetch_array($search_bo1);
 $bo_name1 = $get_bo1['lname'].' '.$get_bo1['fname'];
 
 $search_bo2 = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$initiator'");
 $boNum = mysqli_num_rows($search_bo2);
 $get_bo2 = mysqli_fetch_array($search_bo2);
 $bo_name2 = $get_bo2['lname'].' '.$get_bo2['fname'];
        
 $search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE id = '$recipient' OR phone = '$recipient'");
 $fetch_mystaff = mysqli_fetch_array($search_mystaff);
 $staff_name = $fetch_mystaff['name'];
 
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
 
 $searchInit = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$initiator'");
 $fetchInit = mysqli_fetch_array($searchInit);
        
 $sender = (startsWith($userid,"AGT") ? $cname : (startsWith($userid,"INST") ? $cname : (startsWith($userid,"MER") ? $cname : (startsWith($userid,"COOP") ? $cname : $bo_name))));
        
 $recipient_name = (startsWith($recipient,"AGT") ? $cname2 : (startsWith($recipient,"INST") ? $cname2 : (startsWith($recipient,"MER") ? $cname2 : (startsWith($recipient,"COOP") ? $cname2 : (startsWith($recipient,"VEND") ? $staff_name : $bo_name1)))));
        
 $accountName = ($boNum == 1 && $mystaff1Num == 0 ? $bo_name2.' ('.$fetchInit['account_number'].')' : ($boNum == 0 && $mystaff1Num == 1 ? $staff_name1.' ('.$fetchInit['account_number'].')' : $sender.' ('.$fetchInit['account_number'].')'));
 
 $date_time = $row['date_time'];
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here $filterBy
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = ($row['userid'] == "") ? "Esusu Africa Superadmin" : $sender." (".$row['userid'].")";
 $sub_array[] = $accountName;
 $sub_array[] = "<a href='../pdf/view/pdf_billsreceipt.php?refid=".$row['refid']."&&instid=".(($row['userid'] == "") ? "" : $row['userid'])."' target='_blank'>".$row['refid']."</a>";
 $sub_array[] = ($row['recipient'] == "" || $row['recipient'] == "self" ? "Esusu Africa" : ($staff_name == "" && ($row['recipient'] != "" || $row['recipient'] != "self") ? $recipient_name." (".$row['recipient'].")" : $staff_name));
 $sub_array[] = $row['paymenttype'];
 $sub_array[] = "<span style='color: green;'><b>".($recipient === $filterBy && $row['credit'] == "" ? $row['currency'].number_format($row['debit'],2,'.',',') : ($row['credit'] === "" ? '---' :  ($recipient === $filterBy && $row['credit'] == "" ? '---' : $row['currency'].number_format($row['credit'],2,'.',','))))."</b></span>";
 $sub_array[] = "<span style='color: red;'><b>".($recipient === $filterBy && $row['debit'] != "" ? '---' : ($row['debit'] === "" ? '---' : $row['currency'].number_format($row['debit'],2,'.',',')))."</b></span>";
 $sub_array[] = "<b>".($row['balance'] == "" ? "---" : ($recipient === $filterBy && $row['receiver_bal'] != "" ? $row['currency'].number_format($row['receiver_bal'],2,'.',',') : ($recipient === $filterBy && $row['receiver_bal'] === "" ? $row['currency'].number_format($row['balance'],2,'.',',') : $row['currency'].number_format($row['balance'],2,'.',','))))."</b>";
 $sub_array[] = ($row['status'] == "successful" ? "<span class='label bg-blue'>success</span>" : ($row['status'] == "Pending" || $row['status'] == "tbPending" ? "<span class='label bg-orange'>Pending</span>" : "<span class='label bg-red'>failed</span>"));
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM wallet_history WHERE (userid = '$filterBy' OR recipient = '$filterBy' OR initiator = '$filterBy')";
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