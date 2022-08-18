<?php
include('../config/session.php');

$column = array('Client Name', 'Client Branch', 'A/c Officer', 'A/c Number', 'A/c Name', 'Contact Phone', 'Total Deposit', 'Total Withdrawal', 'Total Charges');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$filterBy = $_POST['filterBy'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all"){
    
    $query .= "SELECT DISTINCT acctno, posted_by, t_type, phone, SUM(amount), branchid, sbranchid FROM transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (branchid = '$filterBy' OR sbranchid = '$filterBy' OR vendorid = '$filterBy' OR agent = '$filterBy' OR baccount = '$filterBy')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT DISTINCT acctno, posted_by, t_type, phone, SUM(amount), branchid, sbranchid FROM transaction 
    WHERE date_time BETWEEN '$startDate' AND '$endDate'
    ";
    
}





if($searchValue != '')
{
 $query .= "SELECT acctno, posted_by, t_type, phone, SUM(amount), branchid, sbranchid FROM transaction
 WHERE baccount LIKE '%".$_POST['search']['value']."%'
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
 $account = $row['acctno'];
 $phone = $row['phone'];
 $acct_officer = $row['posted_by'];
 $companyid = $row['branchid'];
 $tbranch = $row['sbranchid'];
 
 
 $search_user = mysqli_query($link, "SELECT * FROM user WHERE (id = '$acct_officer' OR name = '$acct_officer')");
 $fetch_user = mysqli_fetch_array($search_user);
 $tid = $fetch_user['id'];

 $selectin = mysqli_query($link, "SELECT fname, lname, mname FROM borrowers WHERE account = '$account'") or die (mysqli_error($link));
 $geth = mysqli_fetch_array($selectin);

 $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$companyid'");
 $fetch_inst = mysqli_fetch_array($search_inst);

 $confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$tbranch'");
 $fetch_tbranch = mysqli_fetch_array($confirm_tbranch);

 // TO DETECT TOTAL DEPOSIT
 $sel = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE acctno = '$account' AND t_type = 'Deposit'") or die (mysqli_error($link));
 $rowb = mysqli_fetch_array($sel);

 //GET TRACK OF TOTAL WITHDRAWAL
 $sel_intr = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE acctno = '$account' AND t_type = 'Withdraw'") or die (mysqli_error($link));
 $row_intr = mysqli_fetch_array($sel_intr);
 
 //TO GET TOTAL WITHDRAWAL CHARGES
 $sel_pay = mysqli_query($link, "SELECT SUM(amount) FROM transaction WHERE acctno = '$account' AND t_type = 'Withdraw-Charges'") or die (mysqli_error($link));
 $row_sel_pay = mysqli_fetch_array($sel_pay);

 $sub_array[] = $fetch_inst['institution_name'];
 $sub_array[] = "<b>".(($tbranch === "") ? '---' : $fetch_tbranch['bname'])."</b>";
 $sub_array[] = $fetch_user['name'].' '.$fetch_user['lname'].' '.$fetch_user['mname'];
 $sub_array[] = $account;
 $sub_array[] = $geth['fname'].' '.$geth['lname'].' '.$geth['mname'];
 $sub_array[] = $phone;
 $sub_array[] = number_format($rowb['SUM(amount)'],2,'.',',');
 $sub_array[] = number_format($row_intr['SUM(amount)'],2,'.',',');
 $sub_array[] = number_format($row_sel_pay['SUM(amount)'],2,'.',',');
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM transaction";
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