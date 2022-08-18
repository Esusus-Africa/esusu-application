<?php
include('../config/session.php');

$column = array('id', 'Client Name', 'Client Branch', 'Client Vendor', 'Borrowers Name', 'Num. of Loan', 'Principal Amount', 'Total Repayment', 'Pending Balance', 'Total Interest', 'Grand Total');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$filterBy = $_POST['filterBy'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all"){
    
    $query .= "SELECT * FROM loan_info 
    WHERE date_release BETWEEN '$startDate' AND '$endDate' AND (status = 'Approved' OR status = 'Disbursed') AND (branchid = '$filterBy' OR sbranchid = '$filterBy' OR vendorid = '$filterBy' OR agent = '$filterBy' OR baccount = '$filterBy')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM loan_info 
    WHERE date_release BETWEEN '$startDate' AND '$endDate' AND (status = 'Approved' OR status = 'Disbursed')
    ";
    
}





if($searchValue != '')
{
 $query .= "SELECT * FROM loan_info
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
 $borrower = $row['baccount'];
 $acct_officer = $row['agent'];
 $companyid = $row['branchid'];
 $vendorid = $row['vendorid'];
 $tbranch = $row['sbranchid'];

 $selectin = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$borrower'") or die (mysqli_error($link));
 $geth = mysqli_fetch_array($selectin);
 $name = $geth['lname'].' '.$geth['fname'].' '.$geth['mname'];
 
 $search_user = mysqli_query($link, "SELECT * FROM user WHERE (id = '$acct_officer' OR name = '$acct_officer')");
 $fetch_user = mysqli_fetch_array($search_user);

 $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$companyid'");
 $fetch_inst = mysqli_fetch_array($search_inst);

 $searchUser = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
 $fetchUser = mysqli_fetch_array($searchUser);

 $confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$tbranch'");
 $fetch_tbranch = mysqli_fetch_array($confirm_tbranch);

 // TO DETECT NUMBER OF LOAN RELEASED FOR EACH BORROWERS
 $sel = mysqli_query($link, "SELECT COUNT(id), SUM(amount), SUM(balance), SUM(amount_topay) FROM loan_info WHERE baccount = '$borrower'") or die (mysqli_error($link));
 $rowb = mysqli_fetch_array($sel);
 $amtWithInt = $rowb['SUM(amount_topay)'];
 $amtWithoutInt = $rowb['SUM(amount)'];
 $calcInterest = $amtWithInt - $amtWithoutInt;
 
 //GET TRACK OF TOTAL DUE AMOUNT HERE
 /*$date_now = date("Y-m-d");
 $sel_pend = mysqli_query($link, "SELECT SUM(payment) FROM pay_schedule WHERE tid = '$borrower' AND status = 'UNPAID' AND schedule <= '$date_now'") or die (mysqli_error($link));
 $row_pend = mysqli_fetch_array($sel_pend);*/
 
 //TO GET TOTAL LOAN REPAYMENT FOR EACH CUSTOMER
 $sel_pay = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments WHERE account_no = '$borrower'") or die (mysqli_error($link));
 $row_sel_pay = mysqli_fetch_array($sel_pay);

 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = $fetch_inst['institution_name'];
 $sub_array[] = "<b>".(($tbranch === "") ? '---' : $fetch_tbranch['bname'])."</b>";
 $sub_array[] = ($vendorid === "") ? '---' : $fetchUser['cname'];
 $sub_array[] = $name;
 $sub_array[] = $rowb['COUNT(id)'];
 $sub_array[] = number_format($rowb['SUM(amount)'],2,'.',',');
 $sub_array[] = number_format($row_sel_pay['SUM(amount_to_pay)'],2,'.',',');
 $sub_array[] = number_format($rowb['SUM(balance)'],2,'.',',');
 $sub_array[] = number_format($calcInterest,2,'.',',');
 $sub_array[] = number_format($amtWithInt,2,'.',',');
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