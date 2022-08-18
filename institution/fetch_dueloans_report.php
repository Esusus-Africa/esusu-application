<?php
include('../config/session1.php');

$column = array('id', 'Actions', 'Loan ID', 'Branch', 'RRR Number', 'Account ID', 'Account Name', 'Contact Number', 'Principal Amount', 'Amount to Pay', 'Loan Balance', 'DD Status', 'Approve By', 'Repayment Date');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$filterBy = $_POST['filterBy'];

$testdate = date("Y-m-d");
$date = new DateTime(date("Y-m-d"));
$date->sub(new DateInterval('P5D')); //substract 5 days from the original date
$date_now = $date->format('Y-m-d');

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $filterBy != "all1" && $filterBy != "all2"){
    
    $query .= "SELECT * FROM pay_schedule 
    WHERE schedule BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND status = 'UNPAID' AND (lofficer = '$filterBy' OR tid = '$filterBy' OR sbranchid = '$filterBy') AND (schedule <= '$testdate' OR schedule <= '$date_now')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM pay_schedule 
    WHERE schedule BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND status = 'UNPAID' AND (schedule <= '$testdate' OR schedule <= '$date_now')
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all1"){
    
    $query .= "SELECT * FROM pay_schedule 
    WHERE schedule BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND lofficer = '$iuid' AND status = 'UNPAID' AND (schedule <= '$testdate' OR schedule <= '$date_now')
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all2"){
    
    $query .= "SELECT * FROM pay_schedule 
    WHERE schedule BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND sbranchid = '$isbranchid' AND status = 'UNPAID' AND (schedule <= '$testdate' OR schedule <= '$date_now') 
    ";
    
}






if($searchValue != '')
{
 $query .= "SELECT * FROM pay_schedule
 WHERE lid LIKE '%".$_POST['search']['value']."%' 
 OR status LIKE '%".$_POST['search']['value']."%' 
 OR direct_debit_status LIKE '%".$_POST['search']['value']."%' 
 OR tid LIKE '%".$_POST['search']['value']."%'
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
 $tbranch = $row['sbranchid'];
 /**$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',date("Y-m-d h:i:s", strtotime($row['date_time'])),new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');*/

 $select_ps = mysqli_query($link, "SELECT * FROM loan_info WHERE branchid = '$institution_id' AND lid = '$lid' AND status = 'Approved'");
 $fetch_ps = mysqli_fetch_array($select_ps);
 $id = $fetch_ps['id'];
 $borrower = $fetch_ps['borrower'];
 $status = $fetch_ps['status'];
 $upstatus = $fetch_ps['upstatus'];
 $ddinstruction_status = $row['direct_debit_status'];
    
 $selectin = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
 $geth = mysqli_fetch_array($selectin);
 $name = $geth['lname'].' '.$geth['fname'].' '.$geth['mname'];
 $myphone = $geth['phone'];
 
 $confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$tbranch'");
 $fetch_tbranch = mysqli_fetch_array($confirm_tbranch);

 $search_payment = mysqli_query($link, "SELECT * FROM authorized_card WHERE lid = '$lid'") or die ("Error:" . mysqli_error($link));
 $reg_pay_query = mysqli_fetch_object($search_payment);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = ($claim_payment == 1 && $reg_pay_query->authorized_code == '') ? "---" : "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>Request Payment
                    <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($claim_payment == '1') ? "<li><p><a href='request_pay.php?auth=".$reg_pay_query->authorized_code."&&id=".$id."&&perc=100' class='btn btn-default btn-flat'><i class='fa fa-mastercard'>&nbsp;Collect <b>Payment</b></i></a></p></li>" : '')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = $row['lid'];
 $sub_array[] = "<b>".(($tbranch === "") ? '---' : $fetch_tbranch['bname'])."</b>";
 $sub_array[] = ($fetch_ps['mandate_status'] === "Activated") ? $fetch_ps['mandate_id'] : '----';
 $sub_array[] = $fetch_ps['baccount'];
 $sub_array[] = $name;
 $sub_array[] = $myphone;
 $sub_array[] = $icurrency.number_format($fetch_ps['amount'],2,'.',',');
 $sub_array[] = $icurrency.number_format($row['payment'],2,'.',',');
 $sub_array[] = ($row['balance'] <= 0) ? $icurrency.'0.0' : $icurrency.number_format($row['balance'],2,'.',',');
 $sub_array[] = ($ddinstruction_status == "Debited" ? '<span class="label bg-blue">Debited</span>' : ($ddinstruction_status == "Sent" ? '<span class="label bg-green">Sent</span>' : ($ddinstruction_status == "NotSent" ? '<span class="label bg-orange">Pending</span>' : '<span class="label bg-red">Stop</span>')));
 $sub_array[] = ($fetch_ps['teller'] == "") ? '<span class="label bg-orange">Waiting for Review</span>' : $fetch_ps['teller'];
 $sub_array[] = $row['schedule'];
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM pay_schedule WHERE branchid = '$institution_id'";
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