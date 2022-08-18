<?php
include('../config/session.php');

$column = array('id', 'ID', 'Action', 'Client Name', 'Client Vendor', 'Category', 'Product Name', 'Interest Type', 'Interest on Duration', 'Duration', 'USSD Prefix', 'USSD Visibility');

$searchValue = $_POST['search']['value'];

## Custom Field value
$transType = $_POST['transType'];

$query = " ";

if($transType != "" && $transType != "all" && $transType != "Individual" && $transType != "Group" && $transType != "Purchase"){
    
    $query .= "SELECT * FROM loan_product WHERE (merchantid = '$transType' OR vendorid = '$transType')";
    
}

if($transType != "" && $transType === "all"){
    
    $query .= "SELECT * FROM loan_product WHERE merchantid != ''";
    
}

if($transType != "" && $transType === "Individual"){
    
    $query .= "SELECT * FROM loan_product WHERE category = 'Individual'";
    
}

if($transType != "" && $transType === "Group"){
    
    $query .= "SELECT * FROM loan_product WHERE category = 'Group'";
    
}

if($transType != "" && $transType === "Purchase"){
    
    $query .= "SELECT * FROM loan_product WHERE category = 'Purchase'";
    
}






if($searchValue != '')
{
 $query .= "SELECT * FROM loan_product
 WHERE category LIKE '%".$_POST['search']['value']."' 
 OR tenor LIKE '%".$_POST['search']['value']."' 
 OR interest_type LIKE '%".$_POST['search']['value']."'
 OR ussd_approval_status LIKE '%".$_POST['search']['value']."'
 OR visibility LIKE '%".$_POST['search']['value']."' 
 OR pname LIKE '%".$_POST['search']['value']."'
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
 $companyid = $row['merchantid'];
 $vendorid = $row['vendorid'];

 $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$companyid'");
 $fetch_inst = mysqli_fetch_array($search_inst);

 $searchUser = mysqli_query($link, "SELECT * FROM vendor_reg WHERE companyid = '$vendorid'");
 $fetchUser = mysqli_fetch_array($searchUser);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox' name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = $row['id'];
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>Action  
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($edit_loan_product == 1) ? '<li><p><a href="edit_loanprd.php?id='.$_SESSION['tid'].'&&idm='.$id.'&&mid='.base64_encode("405").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-edit"></i> Edit</button></a></p></li>' : '<span style="color: orange;">--Not-Authorized--</span>')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = $fetch_inst['institution_name'];
 $sub_array[] = ($vendorid === "") ? '---' : $fetchUser['cname'];
 $sub_array[] = $row['category'];
 $sub_array[] = $row['pname'];
 $sub_array[] = $row['interest_type'];
 $sub_array[] = $row['interest']."%";
 $sub_array[] = ($row['tenor'] == "Monthly") ? $row['duration']." Month(s)" : $row['duration']." Week(s)";
 $sub_array[] = ($row['dedicated_ussd_prefix'] == "") ? "None" : $row['dedicated_ussd_prefix'];
 $sub_array[] = ($row['ussd_approval_status'] == "Activated" ? "<span class='label bg-blue'>Activated</span>" : ($row['ussd_approval_status'] == "Pending" ? "<span class='label bg-orange'>Pending</span>" : "<span class='label bg-red'>Deactivated</span>"));
 $sub_array[] = ($edit_loan_product == 1) ? '<a href="edit_loanprd.php?id='.$_SESSION['tid'].'&&idm='.$id.'&&mid=NDEx"> <button type="button" class="btn bg-blue btn-flat"><i class="fa fa-edit"></i> Edit</button></a></td>' : '---';
 $data[] = $sub_array;
}

function count_all_data($connect)
{
$query = "SELECT * FROM loan_product";
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