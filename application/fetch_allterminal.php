<?php
include('../config/session.php');

$column = array('id', 'Actions', 'Client Name', 'Branch Name', 'TID/TraceID', 'Channel', 'Requested By', 'Operator', 'Model Code', 'Issued By', 'MID', 'Created By', 'Status', 'Date Created');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$filterBy = $_POST['transType'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $filterBy != "Available" && $filterBy != "Booked" && $filterBy != "Assigned" && $filterBy != "POS" && $filterBy != "USSD"){
    
    $query .= "SELECT * FROM terminal_reg 
    WHERE dateCreated BETWEEN '$startDate' AND '$endDate' AND (merchant_id = '$filterBy' OR tidoperator = '$filterBy' OR branchid = '$filterBy')
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM terminal_reg 
    WHERE dateCreated BETWEEN '$startDate' AND '$endDate'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "Available"){
    
    $query .= "SELECT * FROM terminal_reg 
    WHERE dateCreated BETWEEN '$startDate' AND '$endDate' AND terminal_status = 'Available'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "Booked"){
    
    $query .= "SELECT * FROM terminal_reg 
    WHERE dateCreated BETWEEN '$startDate' AND '$endDate' AND terminal_status = 'Booked'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "Assigned"){
    
    $query .= "SELECT * FROM terminal_reg 
    WHERE dateCreated BETWEEN '$startDate' AND '$endDate' AND terminal_status = 'Assigned'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "POS"){
    
    $query .= "SELECT * FROM terminal_reg 
    WHERE dateCreated BETWEEN '$startDate' AND '$endDate' AND channel = 'POS'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "USSD"){
    
    $query .= "SELECT * FROM terminal_reg 
    WHERE dateCreated BETWEEN '$startDate' AND '$endDate' AND channel = 'USSD'
    ";
    
}






if($searchValue != '')
{
 $query .= "SELECT * FROM terminal_reg
 WHERE terminal_status LIKE '%".$_POST['search']['value']."%' 
 OR terminal_id LIKE '%".$_POST['search']['value']."%' 
 OR terminal_owner_code LIKE '%".$_POST['search']['value']."%'
 OR channel LIKE '%".$_POST['search']['value']."%'
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
 $reg_date = $row['dateCreated'];
 $createdBy = $row['createdBy'];
 $merchantID = $row['merchant_id'];
 $tidoperator = $row['tidoperator'];
 $initiatedBy = $row['initiatedBy'];
 $mysbranch = $row['branchid'];

 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$reg_date,new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');

 $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$merchantID'");
 $fetch_insti = mysqli_fetch_array($search_insti);

 $userSearch = mysqli_query($link, "SELECT * FROM user WHERE id = '$createdBy'");
 $fetchSearch = mysqli_fetch_array($userSearch);
 $staffName = $fetchSearch['name'].' '.$fetchSearch['lname'].' '.$fetchSearch['mname'];

 $userOP = mysqli_query($link, "SELECT * FROM user WHERE id = '$tidoperator'");
 $fetchOP = mysqli_fetch_array($userOP);
 $operatorName = $fetchOP['name'].' '.$fetchOP['lname'].' '.$fetchOP['mname'].' ('.$fetchOP['virtual_acctno'].')';

 $userIni = mysqli_query($link, "SELECT * FROM user WHERE id = '$initiatedBy'");
 $fetchIni = mysqli_fetch_array($userIni);
 $initiatorName = $fetchIni['name'].' '.$fetchIni['lname'].' '.$fetchIni['mname'].' ('.$fetchIni['virtual_acctno'].')';

 $search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$mysbranch'");
 $fetch_branch = mysqli_fetch_array($search_branch);

 $terminal = ($row['trace_id'] == "") ? $row['terminal_id'] : $row['trace_id'];
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox' name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>Action  
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($row['terminal_status'] == 'Assigned' && $backend_withdraw_terminal == "1") ? '<li><p><a href="withTerminal.php?id='.$_SESSION['tid'].'&&termId='.$row['terminal_id'].'&&mid='.base64_encode("700").'&&tab=tab_1" class="btn btn-default btn-flat" target="_blank"><i class="fa fa-times">&nbsp;<b>Withdraw Terminal</b></i></a></p></li>' : '---')."
                    ".(($row['terminal_status'] == 'Assigned' && $backend_individual_terminal_report == "1") ? '<li><p><a href="assignedReport.php?id='.$_SESSION['tid'].'&&merchID='.$merchantID.'&&termId='.$terminal.'&&mid='.base64_encode("700").'&&tab=tab_1" class="btn btn-default btn-flat" target="_blank"><i class="fa fa-eye">&nbsp;<b>View Report</b></i></a></p></li>' : '---')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = ($fetch_insti['institution_name'] == "") ? "---" : $fetch_insti['institution_name'];
 $sub_array[] = ($mysbranch == "") ? "---" : $fetch_branch['bname'];
 $sub_array[] = $row['terminal_id'].(($row['trace_id'] == "") ? "" : "/".$row['trace_id']);
 $sub_array[] = $row['channel'];
 $sub_array[] = ($initiatedBy == "") ? "---" : $initiatorName;
 $sub_array[] = ($tidoperator === "") ? '<a href="updateTerminal.php?tmid='.$terminal.'" target="_blank">Update<i class="fa fa-external-link"></i></a>' : '<a href="updateTerminal.php?tmid='.$terminal.'" target="_blank">'.$operatorName.'<i class="fa fa-external-link"></i></a>';
 $sub_array[] = $row['terminal_model_code'];
 $sub_array[] = $row['terminal_issurer'];
 $sub_array[] = $row['terminal_owner_code'];
 $sub_array[] = $staffName;
 $sub_array[] = ($row['terminal_status'] == "Assigned") ? "<span class='label bg-blue'>Assigned <i class='fa fa-check'></i></span>" : "<span class='label bg-orange'>".$row['terminal_status']." <i class='fa fa-exclamation-circle'></i></span>";
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM terminal_reg";
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