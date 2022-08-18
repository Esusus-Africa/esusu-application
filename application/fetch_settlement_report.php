<?php
include('../config/session.php');

function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}

$column = array('id', 'Action', 'TID/TraceID', 'Operator', 'Merchant Name', 'Channel', 'Pending Balance', 'Settled Amount', 'Transfer Balance', 'Status', 'Date/Time');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$transType = $_POST['transType'];

//echo $merchantid;
$query = " ";

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all" && $transType != "Pending" && $transType != "Settled"){
    
    $query .= "SELECT * FROM terminal_reg 
    WHERE dateCreated BETWEEN '$startDate' AND '$endDate' AND terminal_status = 'Assigned' AND (merchant_id = '$transType' OR terminal_id = '$transType' OR tidoperator = '$transType' OR trace_id = '$transType')
     ";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType == "all"){
    
    $query .= "SELECT * FROM terminal_reg
    WHERE dateCreated BETWEEN '$startDate' AND '$endDate' AND terminal_status = 'Assigned'";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType == "Pending"){
    
    $query .= "SELECT * FROM terminal_reg
    WHERE dateCreated BETWEEN '$startDate' AND '$endDate' AND terminal_status = 'Assigned' AND (pending_balance > '0.0' OR pending_balance > 0)";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType == "Settled"){
    
    $query .= "SELECT * FROM terminal_reg
    WHERE dateCreated BETWEEN '$startDate' AND '$endDate' AND terminal_status = 'Assigned' AND (settled_balance > '0.0' OR settled_balance > 0) AND (pending_balance = '0.0' OR pending_balance = '0')";
    
}

/*if($pmtType != ""){
    
    $query .= "
    WHERE t_type LIKE '%$pmtType%'
    ";
    
}*/


if($searchValue != '')
{
 $query .= "SELECT * FROM terminal_reg
 WHERE merchant_id LIKE '%".$column[$_POST['search']['value']]."%' 
 OR terminal_id LIKE '%".$column[$_POST['search']['value']]."%' 
 OR trace_id LIKE '%".$column[$_POST['search']['value']]."%'
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
 $tidoperator = $row['tidoperator'];
 $terminalID = (is_numeric($row['trace_id']) == 1) ? $row['terminal_id'] : $row['trace_id'];
 $pending_balance = $row['pending_balance'];

 $date_time = $row['dateUpdated'];
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');

 $search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE id = '$tidoperator'");
 $fetch_mystaff = mysqli_fetch_array($search_mystaff);
 $staff_name = ($fetch_mystaff['name'] == "") ? "Unknown" : $fetch_mystaff['name'].' '.$fetch_mystaff['lname'].' ('.$fetch_mystaff['virtual_acctno'].')';
 
 $search_terminal = mysqli_query($link, "SELECT * FROM terminal_report WHERE (terminalId = '$terminalID' OR trace_id = '$terminalID')");
 $fetch_terminal = mysqli_fetch_array($search_terminal);

 $terminal = ($row['trace_id'] == "") ? $row['terminal_id'] : $row['terminal_id'].' / '.$row['trace_id'];

 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>Action  
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".((($approve_terminal_settlement == '1' || $decline_terminal_settlement == '1') && $pending_balance > 0) ? '<li><p><a href="settleTerminal.php?id='.$_SESSION['tid'].'&&termId='.$row['id'].'" class="btn btn-default btn-flat" target="_blank"><i class="fa fa-reply">&nbsp;<b>Settle Balance</b></i></a></p></li>' : '---')."
                    ".('<li><p><a href="assignedReport.php?id='.$_SESSION['tid'].'&&merchID='.$row['merchant_id'].'&&termId='.$row['terminal_id'].'" class="btn btn-default btn-flat" target="_blank"><i class="fa fa-eye">&nbsp;<b>View Reports</b></i></a></p></li>')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = $terminal;
 $sub_array[] = $staff_name;
 $sub_array[] = $row['merchant_name'].' ('.$row['merchant_id'].')';
 $sub_array[] = $row['channel'];
 $sub_array[] = $fetch_terminal['currencyCode'].number_format($row['pending_balance'],2,'.',',');
 $sub_array[] = $fetch_terminal['currencyCode'].number_format($row['settled_balance'],2,'.',',');
 $sub_array[] = $fetch_terminal['currencyCode'].number_format($fetch_mystaff['transfer_balance'],2,'.',',');
 $sub_array[] = ($row['pending_balance'] === "0" || $row['pending_balance'] === "0.0") ? "<span class='label bg-blue'>Settled</span>" : "<span class='label bg-orange'>Pending</span>";
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM terminal_reg WHERE terminal_status = 'Assigned'";
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