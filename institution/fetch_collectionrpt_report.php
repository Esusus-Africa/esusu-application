<?php
include('../config/session1.php');

$column = array('id', 'Reference ID', 'Branch', 'Vendor', 'Staff/Sub-Agent', 'Loan ID', 'Loan Product', 'Borrowers Name', 'Amount Paid', 'Loan Balance', 'Pay Date', 'Status');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? $_POST['startDate'].' 00'.':00'.':00' : "";
$endDate = (isset($_POST['endDate'])) ? $_POST['endDate'].' 24'.':00'.':00' : "";
$filterBy = $_POST['filterBy'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all"){
    
    $query .= "SELECT * FROM payments 
    WHERE pay_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND remarks = 'paid' AND (branchid = '$filterBy' OR sbranchid = '$filterBy' OR vendorid = '$filterBy' OR tid = '$filterBy' OR account_no = '$filterBy')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM payments 
    WHERE pay_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND remarks = 'paid'
    ";
    
}





if($searchValue != '')
{
 $query .= "SELECT * FROM payments
 WHERE refid LIKE '%".$_POST['search']['value']."%' 
 OR account_no LIKE '%".$_POST['search']['value']."%'
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
 $tid = $row['tid'];
 $companyid = $row['branchid'];
 $vendorid = $row['vendorid'];
 $tbranch = $row['sbranchid'];

 $search_lid = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'");
 $fetch_lid = mysqli_fetch_array($search_lid);
 $lproductid = $fetch_lid['lproduct'];

 $search_lp = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproductid'");
 $fetch_lp = mysqli_fetch_array($search_lp);
 
 $search_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$tid'");
 $fetch_user = mysqli_fetch_array($search_user);

 $searchUser = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
 $fetchUser = mysqli_fetch_array($searchUser);

 $confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$tbranch'");
 $fetch_tbranch = mysqli_fetch_array($confirm_tbranch);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = $row['refid'];
 $sub_array[] = "<b>".(($tbranch === "") ? '---' : $fetch_tbranch['bname'])."</b>";
 $sub_array[] = ($vendorid === "") ? '---' : $fetchUser['cname'];
 $sub_array[] = $fetch_user['name'].' '.$fetch_user['lname'].' '.$fetch_user['mname'];
 $sub_array[] = $lid;
 $sub_array[] = $fetch_lp['pname'];
 $sub_array[] = $row['customer'].' ('.$row['account_no'].')';
 $sub_array[] = number_format($row['amount_to_pay'],2,'.',',');
 $sub_array[] = number_format($row['loan_bal'],2,'.',',');
 $sub_array[] = $row['pay_date'];
 $sub_array[] = '<div style="color: blue;"><b>'.$row['remarks'].'</b></div>';
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM payments WHERE branchid = '$institution_id'";
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