<?php
include('../config/session.php');

$column = array('id', 'RefID', 'Recipient', 'Purpose', 'Credit', 'Debit', 'Balance', 'Status', 'DateTime');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = date('Y-m-d', strtotime($_POST['startDate']));
$endDate = date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours'));
$transType = $_POST['transType'];

//echo $acctno;

$query = " ";

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all"){
    
    $query .= "SELECT * FROM wallet_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND paymenttype = '$transType' AND (initiator = '$acctno' OR recipient = '$acctno' OR recipient = '$bvirtual_acctno')
     ";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType == "all"){
    
    $query .= "SELECT * FROM wallet_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (initiator = '$acctno' OR recipient = '$acctno' OR recipient = '$bvirtual_acctno')
     ";
    
}

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
 
 $myrecipient = $row['recipient'];
      
 $payment_type = $row['paymenttype'];
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<a href='../pdf/view/pdf_billsreceipt.php?refid=".$row['refid']."&&instid=".$acctno."' target='_blank'>".(($row['paymenttype'] == "Stamp Duty" || $row['paymenttype'] == "Charges" || $row['paymenttype'] == "BVN_Charges" || $row['paymenttype'] == "Report Charges") ? "<span style='color: red'>".$row['refid']."</span>" : $row['refid'])."</a>";
 $sub_array[] = ($row['recipient'] == $acctno) ? "self" : $row['recipient'];
 $sub_array[] = $row['paymenttype'];
 $sub_array[] = "<span style='color: green;'><b>".(($myrecipient === $acctno || $myrecipient === $bvirtual_acctno) && $row['credit'] == "" ? $row['currency'].number_format($row['debit'],2,'.',',') : (($myrecipient != $acctno || $myrecipient != $bvirtual_acctno) && $row['credit'] == "" ? '---' : $row['currency'].number_format($row['credit'],2,'.',',')))."</b></span>";
 $sub_array[] = "<span style='color: red;'><b>".(($myrecipient === $acctno || $myrecipient === $bvirtual_acctno) && $row['debit'] != "" ? '---' : ($row['debit'] === "" ? '---' : $row['currency'].number_format($row['debit'],2,'.',',')))."</b></span>";
 $sub_array[] = "<b>".($row['balance'] == "" ? "---" : ($myrecipient === $bvirtual_acctno || $myrecipient === $acctno ? $row['currency'].number_format($row['receiver_bal'],2,'.',',') : $row['currency'].number_format($row['balance'],2,'.',',')))."</b>";
 $sub_array[] = ($row['status'] == "successful" ? "<span class='label bg-blue'>success</span>" : ($row['status'] == "Pending" || $row['status'] == "pending" || $row['status'] == "tbPending" ? "<span class='label bg-orange'>Pending</span>" : "<span class='label bg-red'>failed</span>"));
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM wallet_history WHERE (initiator = '$acctno' OR recipient = '$acctno' OR recipient = '$bvirtual_acctno')";
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