<?php
include('../config/session.php');

$column = array('id', 'Actions', 'Company Name', 'Branch Name', 'Staff', 'Pay Date', 'Gross Amount', 'Deduction Amount', 'Paid/Net Amount');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$filterBy = $_POST['transType'];

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $filterBy != "all1"){
    
    $query .= "SELECT * FROM payroll 
    WHERE pay_date BETWEEN '$startDate' AND '$endDate' AND (companyid = '$filterBy' OR branchid = '$filterBy')
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM payroll 
    WHERE pay_date BETWEEN '$startDate' AND '$endDate'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all1"){
    
    $query .= "SELECT * FROM payroll 
    WHERE pay_date BETWEEN '$startDate' AND '$endDate' AND companyid = ''
    ";
    
}






if($searchValue != '')
{
 $query .= "SELECT * FROM payroll
 WHERE bizname LIKE '%".$_POST['search']['value']."%' 
 OR bank_name LIKE '%".$_POST['search']['value']."%' 
 OR payment_method LIKE '%".$_POST['search']['value']."%'
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
 $tbranch = $row['branchid'];
 $companyid = $row['companyid'];
 $staffid = $row['staff_id'];

 $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$companyid'");
 $fetch_insti = mysqli_fetch_array($search_insti);

 $confirm_tbranch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$tbranch'");
 $fetch_tbranch = mysqli_fetch_array($confirm_tbranch);

 $search_user = mysqli_query($link, "SELECT * FROM user WHERE userid = '$staffid'") or die (mysqli_error($link));
 $get_u = mysqli_fetch_array($search_user);
 $name = $get_u['name'].' '.$get_u['lname'].' '.$get_u['mname'];
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox' name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>Action  
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($generate_payslip == '1') ? '<li><p><a href="generate_payslip.php?id='.$id.'" class="btn btn-default btn-flat" target="_blank"><i class="fa fa-search">&nbsp;Generate Payslip</i></a></p></li>' : '---')."
                    ".(($update_payroll == '1') ? '<li><p><a href="edit_payroll.php?id='.$_SESSION['tid'].'&&staff_id='.$staffid.'&&mid='.base64_encode("423").'" class="btn btn-default btn-flat"><i class="fa fa-eye"></i> View/Modify</a></p></li>' : '---')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = ($companyid == "") ? "Esusu Africa" : $fetch_insti['institution_name'];
 $sub_array[] = ($tbranch == "") ? "---" : $fetch_tbranch['bname'];
 $sub_array[] = $name;
 $sub_array[] = $row['pay_date'];
 $sub_array[] = $row['currency'].number_format($row['gross_amount'],2,".",",");
 $sub_array[] = $row['currency'].number_format($row['total_deduction'],2,".",",");
 $sub_array[] = $row['currency'].number_format($row['paid_amount'],2,".",",");
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM payroll";
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