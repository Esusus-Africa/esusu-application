<?php
include('../config/session.php');

$column = array('id', 'Institution', 'AccountName', 'totalCreditValue', 'totalDebitValue', 'totalCreditValue', 'totalDebitCount', 'totalChargesValue', 'totalCommissionValue', 'totalTransactionValue', 'totalTransactionCount', 'DateTime');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +1 day')) : "";
//$endDate = date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +1 day'));
$transType = $_POST['transType'];

$query = " ";

if($startDate != "" && $endDate != "" && $transType != "" && $transType === "all"){
    
    $query .= "SELECT * FROM reconciliation_report 
    WHERE date_time BETWEEN '$startDate' AND '$endDate'
     ";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all"){
    
    $query .= "SELECT * FROM reconciliation_report 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND ((merchantId = '$transType' AND initiatorId = '') OR initiatorId = '$transType')
    ";
    
}


if($searchValue != '')
{
 $query .= 'SELECT * FROM reconciliation_report
 WHERE initiatorId LIKE "%'.$_POST['search']['value'].'%"
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
 
 $origin_sender = $row['merchantId'];
 $initiatorId = $row['initiatorId'];
 $rightReportID = ($origin_sender != "" && $initiatorId != "") ? $initiatorId : $origin_sender;
 $search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE id = '$initiatorId'");
 $fetch_mystaff = mysqli_fetch_array($search_mystaff);
 $sRowNum = mysqli_num_rows($search_mystaff);
 $staff_name = $fetch_mystaff['name'].' '.$fetch_mystaff['lname'].' '.$fetch_mystaff['mname'];
  
 $search_borro = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$initiatorId'");
 $fetch_borro = mysqli_fetch_array($search_borro);
 $bRowNum = mysqli_num_rows($search_borro);
 $borro_name = $fetch_borro['lname'].' '.$fetch_borro['fname'].' '.$fetch_borro['mname'];
 
 $searchCompany = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$origin_sender'");
 $fetchCompany = mysqli_fetch_array($searchCompany);
 
 $recipient_detector = ($sRowNum == 0 && $bRowNum == 1 ? $borro_name.' <b>('.$fetch_borro['virtual_acctno'].')</b>' : ($sRowNum == 1 && $bRowNum == 0 ? $staff_name.' <b>('.$fetch_mystaff['virtual_acctno'].')</b>' : ($sRowNum == 1 && $bRowNum == 1 ? $staff_name.' <b>('.$fetch_mystaff['virtual_acctno'].')</b>' : "Company")));
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = ($fetchCompany['institution_name'] == "") ? "Unknown" : $fetchCompany['institution_name'];
 $sub_array[] = "<a href='view_whistory.php?tid=".$_SESSION['tid']."&&mid=NDA0&&aid=".$rightReportID."' target='_blank'><b>".$recipient_detector."</b></a>";
 $sub_array[] = number_format($row['totalCreditValue'],2,'.',',');
 $sub_array[] = number_format($row['totalDebitValue'],2,'.',',');
 $sub_array[] = number_format($row['totalCreditCount'],0,'',',');
 $sub_array[] = number_format($row['totalDebitCount'],0,'',',');
 $sub_array[] = number_format($row['totalChargesValue'],2,'.',',');
 $sub_array[] = number_format($row['totalCommissionValue'],2,'.',',');
 $sub_array[] = number_format($row['totalTransactionValue'],2,'.',',');
 $sub_array[] = number_format($row['totalTransactionCount'],0,'',',');
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
$query = "SELECT * FROM reconciliation_report";
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