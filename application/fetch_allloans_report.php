<?php
include('../config/session.php');

$column = array('id', 'Actions', 'Loan ID', 'Client Name', 'Branch Name', 'Client Vendor', 'RRR Number', 'Account ID', 'Contact Number', 'Principal Amount', 'Amount + Interest', 'Booked By', 'Last Reviewed By', 'Date', 'Status');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$filterBy = $_POST['filterBy'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $filterBy != "pend" && $filterBy != "act" && $filterBy != "paid" && $filterBy != "appr" && $filterBy != "disappr" && $filterBy != "disburs" && $filterBy != "intrev"){
    
    $query .= "SELECT * FROM loan_info 
    WHERE date_release BETWEEN '$startDate' AND '$endDate' AND (branchid = '$filterBy' OR sbranchid = '$filterBy' OR vendorid = '$filterBy' OR agent = '$filterBy' OR baccount = '$filterBy')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM loan_info 
    WHERE date_release BETWEEN '$startDate' AND '$endDate'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "pend"){
    
    $query .= "SELECT * FROM loan_info 
    WHERE date_release BETWEEN '$startDate' AND '$endDate' AND status = 'Pending'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "intrev"){
    
    $query .= "SELECT * FROM loan_info 
    WHERE date_release BETWEEN '$startDate' AND '$endDate' AND status = 'Internal-Review'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "act"){
    
    $query .= "SELECT * FROM loan_info 
    WHERE date_release BETWEEN '$startDate' AND '$endDate' AND p_status != 'PAID' AND (status = 'Disbursed' OR status = 'Approved')
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "paid"){
    
    $query .= "SELECT * FROM loan_info 
    WHERE date_release BETWEEN '$startDate' AND '$endDate' AND p_status = 'PAID'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "appr"){
    
    $query .= "SELECT * FROM loan_info 
    WHERE date_release BETWEEN '$startDate' AND '$endDate' AND status = 'Approved'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "disappr"){
    
    $query .= "SELECT * FROM loan_info 
    WHERE date_release BETWEEN '$startDate' AND '$endDate' AND status = 'Disapproved'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "disburs"){
    
    $query .= "SELECT * FROM loan_info 
    WHERE date_release BETWEEN '$startDate' AND '$endDate' AND status = 'Disbursed'
    ";
    
}





if($searchValue != '')
{
 $query .= "SELECT * FROM loan_info
 WHERE lid LIKE '%".$_POST['search']['value']."%' 
 OR mandate_id LIKE '%".$_POST['search']['value']."%' 
 OR loantype LIKE '%".$_POST['search']['value']."%' 
 OR employer LIKE '%".$_POST['search']['value']."%' 
 OR unumber LIKE '%".$_POST['search']['value']."%' 
 OR teller LIKE '%".$_POST['search']['value']."%' 
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
 $lid = $row['lid'];
 $borrower = $row['borrower'];
 $acct_officer = $row['agent'];
 $status = $row['status'];
 $companyid = $row['branchid'];
 $tbranch = $row['sbranchid'];
 $vendorid = $row['vendorid'];
 /**$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',date("Y-m-d h:i:s", strtotime($row['date_time'])),new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');*/

 $selectin = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
 $geth = mysqli_fetch_array($selectin);
 $name = $geth['lname'].' '.$geth['fname'].' '.$geth['fname'];
 $myphone = $geth['phone'];
 
 $search_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$acct_officer' OR name = '$acct_officer'");
 $fetch_user = mysqli_fetch_array($search_user);

 $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$companyid'");
 $fetch_inst = mysqli_fetch_array($search_inst);

 $searchUser = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
 $fetchUser = mysqli_fetch_array($searchUser);

 $confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$tbranch'");
 $fetch_tbranch = mysqli_fetch_array($confirm_tbranch);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                    <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($update_loan_records == '1') ? "<li><p><a href='updateloans.php?id=".$id."&&acn=".$row['baccount']."&&mid=".base64_encode("405")."&&lid=".$row['lid']."&&tab=tab_0' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-eyes'>&nbsp;View Info</i></a></p></li>" : '')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = "<b>".$row['lid']."</b>";
 $sub_array[] = $fetch_inst['institution_name'];
 $sub_array[] = "<b>".(($tbranch === "") ? '---' : $fetch_tbranch['bname'])."</b>";
 $sub_array[] = ($vendorid === "") ? '---' : $fetchUser['cname'];
 $sub_array[] = "<b>".(($row['mandate_status'] === "Pending") ? '----' : $row['mandate_id'])."</b>";
 $sub_array[] = $name.'<br>('.$row['baccount'].')';
 $sub_array[] = $myphone;
 $sub_array[] = $icurrency.number_format($row['amount'],2,'.',',');
 $sub_array[] = $icurrency.number_format($row['amount_topay'],2,'.',',');
 $sub_array[] = ($row['agent'] == "") ? 'Customer' : $fetch_user['name'].' '.$fetch_user['lname'];
 $sub_array[] = ($row['teller'] == "") ? '<span class="label bg-orange">Waiting for Review</span>' : $row['teller'];
 $sub_array[] = $row['date_release'];
 $sub_array[] = '<span class="label bg-'.(($status == "Approved" ? "blue" : ($status == "Disapproved" ? "red" : ($status == "Disbursed" ? "green" : "orange")))).'">'.$status.'</span>';
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM loan_info";
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