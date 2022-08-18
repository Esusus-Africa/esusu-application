<?php
include('../config/session1.php');

$column = array('id', 'RefID', 'Recipient', 'Purpose', 'Currency', 'Credit', 'Debit', 'Balance', 'Status', 'DateTime');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = date('Y-m-d h:i:s', strtotime($_POST['startDate']));
$endDate = date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +1 day'));
$transType = $_POST['transType'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all"){
    
    $query .= "SELECT * FROM wallet_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND paymenttype = '$transType' AND userid = '$vcreated_by' AND (recipient = '$vuid' OR initiator = '$vuid' OR initiator = '$vendorid')
     ";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType === "all"){
    
    $query .= "SELECT * FROM wallet_history 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$vcreated_by' AND (recipient = '$vuid' OR initiator = '$vuid' OR initiator = '$vendorid')
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
 $search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE userid = '$staff_id'");
 $fetch_mystaff = mysqli_fetch_array($search_mystaff);
 $staff_name = $fetch_mystaff['name'];
  
 $search_borro = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$staff_id' OR phone = '$staff_id'");
 $fetch_borro = mysqli_fetch_array($search_borro);
 $borro_name = $fetch_borro['lname'].' '.$fetch_borro['fname'];
 $payment_type = $row['paymenttype'];

 $credit = ($row['credit'] == "") ? 0.0 : number_format($row['credit'],2,'.',',');
 $debit = ($row['debit'] == "") ? 0.0 : number_format($row['debit'],2,'.',',');
 $receiver_bal = ($row['receiver_bal'] == "") ? 0.0 : number_format($row['receiver_bal'],2,'.',',');
 $balance = ($row['balance'] == "") ? 0.0 : number_format($row['balance'],2,'.',',');
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<a href='../pdf/view/pdf_billsreceipt.php?refid=".$row['refid']."&&instid=".$vcreated_by."' target='_blank'>".(($row['paymenttype'] == "Stamp Duty" || $row['paymenttype'] == "Charges" || $row['paymenttype'] == "BVN_Charges" || $row['paymenttype'] == "Report Charges") ? "<span style='color: red'>".$row['refid']."</span>" : $row['refid'])."</a>";
 $sub_array[] = ($staff_id == $vendorid || $staff_id == "self" ? "self" : ($staff_id != $vendorid && $staff_id != "" ? $staff_id.' ['.$borro_name.']' : "Esusu Africa - [$payment_type]"));
 $sub_array[] = $row['paymenttype'];
 $sub_array[] = $row['currency'];
 $sub_array[] = "<span style='color: green;'><b>".(($staff_id === $vendorid || $staff_id === $vvirtual_acctno || $staff_id === $vuid) && $row['credit'] == "" ? $debit : (($staff_id != $vcreated_by || $staff_id != $vvirtual_acctno || $staff_id != $vuid) && $row['credit'] == "" ? '---' : $credit))."</b></span>";
 $sub_array[] = "<span style='color: red;'><b>".(($staff_id === $vendorid || $staff_id === $vvirtual_acctno || $staff_id === $vuid) && $row['debit'] != "") ? '---' : $debit."</b></span>";
 $sub_array[] = "<b>".($row['balance'] == "" ? "---" : (($staff_id === $vvirtual_acctno || $staff_id === $vuid) && $origin_sender != '' ? $receiver_bal : $balance))."</b>";
 $sub_array[] = ($row['status'] == "successful" ? "<span class='label bg-blue'>success</span>" : ($row['status'] == "pending" || $row['status'] == "Pending" || $row['status'] == "tbPending" ? "<span class='label bg-orange'>Pending</span>" : "<span class='label bg-red'>failed</span>"));
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM wallet_history WHERE userid = '$vcreated_by' AND (recipient = '$vuid' OR initiator = '$vuid')";
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