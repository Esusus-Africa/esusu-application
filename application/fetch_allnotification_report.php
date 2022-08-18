<?php
include('../config/session.php');

$column = array('id', 'Action', 'RefID', 'Client Name', 'Vendor', 'Customer Name', 'Plan Code', 'Sub. Code', 'Plan Name', 'Amount Paid', 'Bank Details', 'Status', 'Date/Time');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$filterBy = $_POST['filterBy'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $filterBy != "Pending" && $filterBy != "Approved" && $filterBy != "Declined"){
    
    $query .= "SELECT * FROM investment_notification 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND (merchantid = '$filterBy' OR vendorid = '$filterBy' OR customerid = '$filterBy')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM investment_notification 
    WHERE date_time BETWEEN '$startDate' AND '$endDate'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "Pending"){
    
    $query .= "SELECT * FROM investment_notification 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND status = 'Pending'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "Approved"){
    
    $query .= "SELECT * FROM investment_notification 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND status = 'Approved'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "Declined"){
    
    $query .= "SELECT * FROM investment_notification 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND status = 'Declined'
    ";
    
}





if($searchValue != '')
{
 $query .= "SELECT * FROM investment_notification
 WHERE refid LIKE '%".$_POST['search']['value']."%' 
 OR subcode LIKE '%".$_POST['search']['value']."%'
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
 
 $customerid = $row['customerid'];
 $status = $row['status'];
 $vendorid = $row['vendorid'];
 $companyid = $row['merchantid'];

 $search_borro = mysqli_query($link, "SELECT * FROM borrowers WHERE (account = '$customerid' OR virtual_acctno = '$customerid')");
 $fetch_borro = mysqli_fetch_array($search_borro);
 $bVA = $fetch_borro['virtual_acctno'];
 $acct_owner = $fetch_borro['account'];
 
 $searchUser = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
 $fetchUser = mysqli_fetch_array($searchUser);

 $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$companyid'");
 $fetch_inst = mysqli_fetch_array($search_inst);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>View
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    <li><p><a href='walletVerifiedInfo.php?id=".$_SESSION['tid']."&&mid=NDkw&&uid=".$acct_owner."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-search'>&nbsp;View KYC</i></a></p></li>
                    </ul>
                    </div>
                </div>";
 $sub_array[] = $row['refid'];
 $sub_array[] = $fetch_inst['institution_name'];
 $sub_array[] = $fetchUser['cname'];
 $sub_array[] = $row['customer_name'].' ('.$bVA.')';
 $sub_array[] = $row['plancode'];
 $sub_array[] = $row['subcode'];
 $sub_array[] = $row['plan_name'];
 $sub_array[] = $row['plancurrency'].number_format($row['planamount'],2,'.',',');
 $sub_array[] = $row['bank_details'];
 $sub_array[] = ($status == "Pending" ? '<span class="label bg-orange">Pending</span>' : ($status == "Approved" ? '<span class="label bg-blue">Approved</span>' : '<span class="label bg-red">Declined</span>'));
 $sub_array[] = $row['date_time'];
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM investment_notification";
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