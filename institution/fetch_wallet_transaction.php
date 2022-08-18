<?php
include('../config/session1.php');

$column = array('id', 'RefID', 'Recipient', 'Purpose', 'Currency', 'Credit', 'Debit', 'Balance', 'Initiated By', 'Status', 'DateTime');

$searchValue = $_POST['search']['value'];

## Custom Field value
//$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
//$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$startDate = (isset($_POST['startDate'])) ? $_POST['startDate'].' 00'.':00'.':00' : "";
$endDate = (isset($_POST['endDate'])) ? $_POST['endDate'].' 24'.':00'.':00' : "";
$transType = $_POST['transType'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all" && $transType != "All1"){
    
    ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $query .= "SELECT * FROM wallet_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$institution_id' AND (initiator = '$transType' OR paymenttype = '$transType')
    " : "";
    
    ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin") ? $query .= "SELECT * FROM wallet_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND paymenttype = '$transType' AND (initiator = '$iuid' OR recipient = '$iuid')
    " : "";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "All1" && $transType === "all"){
    
    ($irole == "agent_manager" || $irole == "institution_super_admin" || $irole == "merchant_super_admin") ? $query .= "SELECT * FROM wallet_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (userid = '$institution_id' OR recipient = '$institution_id' OR initiator = '$iuid' OR recipient = '$iuid')
     " : "";
     
    ($irole != "agent_manager" && $irole != "institution_super_admin" && $irole != "merchant_super_admin") ? $query .= "SELECT * FROM wallet_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (initiator = '$iuid' OR recipient = '$iuid')
     " : "";
    
}


if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all" && $transType === "All1"){
    
    $query .= "SELECT * FROM wallet_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (initiator = '$iuid' OR recipient = '$iuid')
     ";
    
}

/*if($startDate === "" && $endDate === "" && $transType != "" && $transType === "all"){
    
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
 $query .= 'SELECT * FROM wallet_history
 WHERE refid LIKE "%'.$_POST['search']['value'].'%"
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
 
 $origin_sender = $row['userid'];
 $staff_id = $row['recipient'];
 $initiator = $row['initiator'];
 $search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND (id = '$staff_id' OR phone = '$staff_id')");
 $fetch_mystaff = mysqli_fetch_array($search_mystaff);
 $sRowNum = mysqli_num_rows($search_mystaff);
 $staff_name = $fetch_mystaff['name'].' '.$fetch_mystaff['lname'].' '.$fetch_mystaff['mname'];
 $staff_VA = $fetch_mystaff['virtual_acctno'];
 
 $search_mystaff1 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$initiator'");
 $fetch_mystaff1 = mysqli_fetch_array($search_mystaff1);
 $sRowNum1 = mysqli_num_rows($search_mystaff1);
 $staff_name1 = $fetch_mystaff1['name'].' '.$fetch_mystaff1['lname'].' '.$fetch_mystaff1['mname'];
 $staff_VA1 = $fetch_mystaff1['virtual_acctno'];
 
 $search_borro = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND (account = '$staff_id' OR phone = '$staff_id' OR virtual_acctno = '$staff_id')");
 $fetch_borro = mysqli_fetch_array($search_borro);
 $bRowNum = mysqli_num_rows($search_borro);
 $borro_name = $fetch_borro['lname'].' '.$fetch_borro['fname'].' '.$fetch_borro['mname'];
 
 $search_borro1 = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND account = '$initiator'");
 $fetch_borro1 = mysqli_fetch_array($search_borro1);
 $bRowNum1 = mysqli_num_rows($search_borro1);
 $borro_name1 = $fetch_borro1['lname'].' '.$fetch_borro1['fname'].' '.$fetch_borro1['mname'];
 $borro_VA = $fetch_borro1['virtual_acctno'];
 
 $recipient_detector1 = ($sRowNum == 0 && $bRowNum == 1 ? $borro_VA.' ['.$borro_name.']' : ($sRowNum == 1 && $bRowNum == 0 ? $staff_VA.' ['.$staff_name.']' : ($sRowNum == 1 && $bRowNum == 1 ? $staff_VA.' ['.$staff_name.']' : "Unknown")));
 $recipient_detector = ($sRowNum1 == 0 && $bRowNum1 == 1 ? $borro_VA.' ['.$borro_name1.']' : ($sRowNum1 == 1 && $bRowNum1 == 0 ? $staff_VA1.' ['.$staff_name1.']' : ($sRowNum1 == 1 && $bRowNum1 == 1 ? $staff_VA1.' ['.$staff_name1.']' : "ESUSU AFRICA")));
 
 $payment_type = $row['paymenttype'];
 $transType = $row['transaction_type'];
 $acctNo = ($borro_VA == "") ? "" : "|".$borro_VA;

 $credit = ($row['credit'] == "") ? 0.0 : number_format($row['credit'],2,'.',',');
 $debit = ($row['debit'] == "") ? 0.0 : number_format($row['debit'],2,'.',',');
 $receiver_bal = ($row['receiver_bal'] == "") ? 0.0 : number_format($row['receiver_bal'],2,'.',',');
 $balance = ($row['balance'] == "") ? 0.0 : number_format($row['balance'],2,'.',',');
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<a href='../pdf/view/pdf_billsreceipt.php?refid=".$row['refid']."&&instid=".$institution_id."' target='_blank'>".(($row['paymenttype'] == "Stamp Duty" || $row['paymenttype'] == "Charges" || $row['paymenttype'] == "BVN_Charges" || $row['paymenttype'] == "Report Charges") ? "<span style='color: red'>".$row['refid']."</span>" : $row['refid'].$acctNo)."</a>";
 $sub_array[] = ($staff_id == $institution_id || $staff_id == "self" ? "self" : ($staff_id != $institution_id && ($sRowNum == 1 || $bRowNum == 1) ? $recipient_detector1 : $staff_id));
 $sub_array[] = $row['paymenttype'];
 $sub_array[] = $row['currency'];
 $sub_array[] = "<span style='color: green;'><b>".($row['transaction_type'] === "Credit" ? $credit : '---')."</b></span>";
 $sub_array[] = "<span style='color: red;'><b>".($row['transaction_type'] === "Debit" ? $debit : '---')."</b></span>";
 
 $sub_array[] = "<b>".($row['balance'] === "" && $row['receiver_bal'] === "" ? "---" : (($staff_id === $ivirtual_acctno || $staff_id === $iuid || ($origin_sender === "" && $staff_id === $institution_id && ($irole === "agent_manager" || $irole === "institution_super_admin" || $irole === "merchant_super_admin"))) && $row['receiver_bal'] != "" ? $receiver_bal : $balance))."</b>";
 $sub_array[] = $recipient_detector;
 $sub_array[] = ($row['status'] == "successful" ? "<span class='label bg-blue'>success</span>" : ($row['status'] == "pending" || $row['status'] == "Pending" || $row['status'] == "tbPending" ? "<span class='label bg-orange'>Pending</span>" : "<span class='label bg-red'>".$row['status']."</span>"));
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM wallet_history WHERE (userid = '$institution_id' OR recipient = '$institution_id')";
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