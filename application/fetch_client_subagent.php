<?php
include('../config/session.php');

$column = array('id', 'Actions', 'UserType', 'Institution', 'Branch', 'Name', 'Username', 'Email', 'Phone Number', 'Wallet Balance', 'Transfer Balance', 'Reg. Date');

$searchValue = $_POST['search']['value'];

## Custom Field value
$transType = $_POST['transType'];

$query = " ";

if($transType != "" && $transType === "all"){
    
    $query .= "SELECT * FROM user WHERE created_by != ''";
    
}

if($transType != "" && $transType != "all"){
    
    $query .= "SELECT * FROM user WHERE (created_by = '$transType' OR id = '$transType' OR branchid = '$transType')";
    
}


if($searchValue != '')
{
 $query .= "SELECT * FROM user
 WHERE username LIKE '%".$_POST['search']['value']."'
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
 $branch = $row['branchid'];
 $utype = $row['utype'];
 
 $searchInst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$createdby'");
 $fetchInst = mysqli_fetch_object($searchInst);

 $search_branc = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$branch'");
 $fetch_branc = mysqli_fetch_array($search_branc);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox' name='selector[]' type='checkbox' value='".$row['userid']."'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>  
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".($utype == 'Registered' && $update_client_subagent == '1' ? '<a href="view_emp.php?id='.$id.'&&mid='.base64_encode("419").'"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-eye"></i>&nbsp;Update</button></a>' : ($utype == 'Unregistered' && $update_client_subagent == '1' ? '<a href="edit_unreg_user.php?id='.$_SESSION['tid'].'&&idm='.$id.'&&mid=NDIz"><button type="button" class="btn btn-flat bg-blue"><i class="fa fa-eye"></i>&nbsp;Update</button></a>' : '<span style="color: orange;">-------</span>'))."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = ($utype == 'Registered') ? '<span class="label bg-blue">Registered</span>' : '<span class="label bg-orange">Unregistered</span>';
 $sub_array[] = $fetchInst->institution_name;
 $sub_array[] = ($branch == '') ? '<b>Head Office</b>' : '<b>'.$fetch_branc['bname'].'</b>';
 $sub_array[] = $row['name'].' '.$row['lname'].' '.$row['mname'];
 $sub_array[] = $row['username'];
 $sub_array[] = $row['email'];
 $sub_array[] = $row['phone'];
 $sub_array[] = number_format($row['wallet_balance'],2,'.',',');
 $sub_array[] = number_format($row['transfer_balance'],2,'.',',');
 $sub_array[] = $row['date_time'];
 $data[] = $sub_array;
}

function count_all_data($connect)
{
$query = "SELECT * FROM user WHERE created_by != ''";
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