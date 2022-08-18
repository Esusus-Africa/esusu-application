<?php
include('../config/session.php');

$column = array('id', 'Actions', 'Institution', 'Branch ID', 'Branch Name', 'Phone', 'Transaction Count', 'Currency', 'Created', 'Status');

$searchValue = $_POST['search']['value'];

## Custom Field value
$transType = $_POST['transType'];

$query = " ";

if($transType != "" && $transType === "all"){
    
    $query .= "SELECT * FROM branches WHERE created_by != ''";
    
}

if($transType != "" && $transType != "all"){
    
    $query .= "SELECT * FROM branches WHERE created_by = '$transType'";
    
}


if($searchValue != '')
{
 $query .= "SELECT * FROM branches
 WHERE branchid LIKE '%".$_POST['search']['value']."' 
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
 $createdby = $row['created_by'];
 $branchid = $row['branchid'];
 
 $searchInst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$createdby'");
 $fetchInst = mysqli_fetch_object($searchInst);

 $select2 = mysqli_query($link, "SELECT * FROM transaction WHERE sbranchid = '$branchid' ORDER BY id DESC") or die (mysqli_error($link));
 $get_num = mysqli_num_rows($select2);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox' name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($update_client_branches == '1') ? "<li><p><a href='edit_branches?id=".$_SESSION['tid']."&&idm=".$id."&&mid=NDE5' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-edit'>&nbsp;Update Branch</i></a></p></li>" : '<span style="color: red;">--Not Authorized--</span>')."
                    ".(($view_client_branch_transaction == '1') ? "<li><p><a href='view_transaction?id=".$_SESSION['tid']."&&idm=".$branchid."&&mid=NDE5' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-search'>&nbsp;View Transaction</i></a></p></li>" : '<span style="color: red;">--Not Authorized--</span>')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = $fetchInst->institution_name;
 $sub_array[] = $branchid;
 $sub_array[] = $row['bname'];
 $sub_array[] = $row['branch_mobile'];
 $sub_array[] = '<div class="alert bg-orange" align="center"><b>'.$get_num.'</b></div>';
 $sub_array[] = $row['currency'];
 $sub_array[] = $row['bopendate'];
 $sub_array[] = $row['bstatus'];
 $data[] = $sub_array;
}

function count_all_data($connect)
{
$query = "SELECT * FROM branches WHERE created_by != ''";
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