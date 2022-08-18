<?php
include('../config/session.php');

$column = array('id', 'Account Name', 'Bank', 'Account Number', 'Balance', 'Status', 'DateTime');

$searchValue = $_POST['search']['value'];

## Custom Field value
$transType = $_POST['transType'];

$query = " ";

if($transType != "" && $transType === "all"){
    
    $query .= "SELECT * FROM institution_data 
    WHERE status = 'Approved'
     ";
    
}

if($transType != "" && $transType != "all"){
    
    $query .= "SELECT * FROM institution_data 
    WHERE institution_id = '$transType' AND status = 'Approved'
    ";
    
}


/*if($startDate === "" && $endDate === "" && $transType != "" && $transType === "all"){
    
    $query .= "SELECT * FROM wallet_history 
    WHERE (userid = '$institution_id' OR recipient = '$institution_id')
     ";
    
}*/

/*if($pmtType != ""){
    
    $query .= "
    WHERE t_type LIKE '%$pmtType%'
    ";
    
}*/


if($searchValue != "")
{
 $query .= 'SELECT * FROM institution_data
 WHERE institution_id LIKE "%'.$_POST['search']['value'].'%"
 ';
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
 
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$row['reg_date'],new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');
 
 $userid = $row['institution_id'];
 $virtual_number = $row['virtual_number'];

 $search_i = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$userid'");
 $get_i = mysqli_fetch_array($search_i);

 $virtualWallet = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$userid'");
 $fetchWallet = mysqli_fetch_array($virtualWallet);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = '<a href="view_whistory.php?tid='.$_SESSION['tid'].'&&mid=NDA0&&aid='.$userid.'" target="_blank"><b>'.$fetchWallet['account_name'].'</b></a>';
 $sub_array[] = $fetchWallet['bank_name'];
 $sub_array[] = $fetchWallet['account_number'];
 $sub_array[] = $get_i['currency'].number_format($row['wallet_balance'],2,'.',',');
 $sub_array[] = '<span class="label bg-'.(($fetchWallet['status'] == "ACTIVE") ? "blue" : "orange").'">'.$fetchWallet['status'].'</span>';
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
$query = "SELECT * FROM institution_data WHERE status = 'Approved'";
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