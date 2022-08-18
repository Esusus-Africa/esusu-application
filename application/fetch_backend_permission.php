<?php
include('../config/session.php');

$column = array('id', 'Actions', 'Role Name');

$searchValue = $_POST['search']['value'];

## Custom Field value
$transType = $_POST['transType'];

$query = " ";

if($transType != "" && $transType === "all"){
    
    $query .= "SELECT * FROM my_permission2 WHERE urole != 'super_admin'";
    
}

if($transType != "" && $transType != "all"){
    
    $query .= "SELECT * FROM my_permission2 WHERE urole = '$transType'";
    
}


if($searchValue != '')
{
 $query .= "SELECT * FROM my_permission2
 WHERE urole LIKE '%".$_POST['search']['value']."'
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
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox' name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($view_staff_permission == '1') ? '<li><p><a rel="tooltip" title="View" href="view_perm2.php?id='.$id.'&&mid='.base64_encode("413").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-eye"></i>&nbsp;View</button></a></p></li>' : '<span style="color: orange;">--Not-Authorized--</span>')."
                    ".(($update_staff_permission == '1') ? '<li><p><a rel="tooltip" title="View" href="edit_perm.php?id='.$id.'&&mid='.base64_encode("413").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-edit"></i>&nbsp;Update</button></a></p></li>' : '<span style="color: orange;">--Not-Authorized--</span>')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = $row['urole'];
 $data[] = $sub_array;
}

function count_all_data($connect)
{
$query = "SELECT * FROM my_permission2";
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