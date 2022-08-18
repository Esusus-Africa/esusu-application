<?php
include('../config/session1.php');

$column = array('id', 'Branch', 'Account Officer', 'Customer', 'Charges', 'Date/Time', 'Action');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$filterBy = $_POST['filterBy'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $filterBy != "all1" && $filterBy != "all2"){
    
    $query .= "SELECT * FROM bvn_log 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$institution_id' AND acctOfficer = '$filterBy'
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM bvn_log 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$institution_id'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all1"){
    
    $query .= "SELECT * FROM bvn_log 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$institution_id' AND acctOfficer = '$iuid'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all2"){
    
    $query .= "SELECT * FROM bvn_log 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND userid = '$institution_id' AND branchid = '$isbranchid'
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
 $query .= 'SELECT * FROM bvn_log
 WHERE accountID LIKE "%'.$_POST['search']['value'].'%"
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
 
 $accountID = $row['accountID'];
 $branchid = $row['branchid'];
 $acctOfficer = $row['acctOfficer'];
 $search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$acctOfficer'");
 $fetch_mystaff = mysqli_fetch_array($search_mystaff);
 $staffName = $fetch_mystaff['name'].' '.$fetch_mystaff['lname'].' ('.$fetch_mystaff['virtual_acctno'].')';
 
 //CUSTOMER
 $search_borro = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND account = '$accountID'");
 $bRowNum = mysqli_num_rows($search_borro);
 $fetch_borro = mysqli_fetch_array($search_borro);
 $borroName = $fetch_borro['fname'].' '.$fetch_borro['lname'].' '.$fetch_borro['mname'].' ('.$fetch_borro['virtual_acctno'].')';
 
 //STAFF
 $search_mys = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$accountID'");
 $sRowNum = mysqli_num_rows($search_mys);
 $fetch_mys = mysqli_fetch_array($search_mys);
 $sName = $fetch_mys['name'].' '.$fetch_mys['lname'].' '.$fetch_mys['mname'].' ('.$fetch_mys['virtual_acctno'].')';
 
 //DETETCT RIGHT USER
 $userName = ($sRowNum == 0 && $bRowNum == 1) ? $borroName : $sName;

 $search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$branchid'");
 $fetch_branch = mysqli_fetch_array($search_branch);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = ($branchid == "") ? '<b>Head Office</b>' : '<b>'.$fetch_branch['bname'].'</b>';
 $sub_array[] = ($acctOfficer == "") ? "---" : $staffName;
 $sub_array[] = ($accountID == "") ? "---" : $userName;
 $sub_array[] = $icurrency.number_format($row['charges'],2,'.',',');
 $sub_array[] = $correctdate;
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($view_all_bvn_verification == 1 || $view_individual_bvn_verification == 1 || $view_branch_bvn_verification == 1) ? "<li><p><a href='bvnInfo.php?id=".$_SESSION['tid']."&&mid=NDA0&&ref=".$row['ref']."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-search'>&nbsp;View Report</i></a></p></li>" : '')."
                    </ul>
                    </div>
                </div>";
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM bvn_log WHERE userid = '$institution_id'";
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