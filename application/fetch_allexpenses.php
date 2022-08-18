<?php
include('../config/session.php');

$column = array('id', 'Actions', 'Client Name', 'ExpID', 'Type', 'Amount', 'Description', 'File');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$filterBy = $_POST['transType'];

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $filterBy != "all1"){
    
    $query .= "SELECT * FROM expenses 
    WHERE edate BETWEEN '$startDate' AND '$endDate' AND branchid = '$filterBy'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM expenses 
    WHERE edate BETWEEN '$startDate' AND '$endDate'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all1"){
    
    $query .= "SELECT * FROM expenses 
    WHERE edate BETWEEN '$startDate' AND '$endDate' AND branchid = ''
    ";
    
}






if($searchValue != '')
{
 $query .= "SELECT * FROM expenses
 WHERE expid LIKE '%".$_POST['search']['value']."%' 
 OR exptype LIKE '%".$_POST['search']['value']."%'
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
 $expid = $row['expid'];
 $companyid = $row['branchid'];

 $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$companyid'");
 $fetch_insti = mysqli_fetch_array($search_insti);

 $select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
 $row1 = mysqli_fetch_array($select1);
 $currency = $row1['currency']; 
    
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox' name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>Action  
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($edit_expense == '1') ? '<li><p><a href="edit_expenses.php?id='.$_SESSION['tid'].'&&idm='.$id.'&&mid='.base64_encode("500").'" class="btn btn-default btn-flat" target="_blank"><i class="fa fa-edit">&nbsp;<b>Edit</b></i></a></p></li>' : '---')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = ($companyid == "") ? "Esusu Africa" : $fetch_insti['institution_name'];
 $sub_array[] = $row['expid'];
 $sub_array[] = $row['exptype'];
 $sub_array[] = $currency.number_format($row['eamt'],2,".",",");
 $sub_array[] = $row['edesc'];
 $i = 0;
 $select_doc = mysqli_query($link, "SELECT expid, newfilepath FROM expense_document WHERE expid = '$expid'") or die (mysqli_error($link));
 if(mysqli_num_rows($select_doc)){
    $sub_array[] = 'No Receipt Attached';
 }
 while($row_doc = mysqli_fetch_array($select_doc)){
    $i++;
    $sub_array[] = '<a href="'.$fetchsys_config['file_baseurl'].$row_doc['newfilepath'].'" target="blank"> File'.$i.'</a>';
 }
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM expenses";
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