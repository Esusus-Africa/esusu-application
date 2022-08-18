<?php
include('../config/session.php');

$column = array('id', 'Institution', 'Account Name', 'Bank', 'Account Number', 'Balance', 'Status', 'DateTime');

$searchValue = $_POST['search']['value'];

## Custom Field value
$transType = $_POST['transType'];

$query = " ";

if($transType != "" && $transType === "all"){
    
    $query .= "SELECT * FROM user WHERE comment = 'Approved' AND virtual_acctno != ''";
    
}

if($transType != "" && $transType != "all"){
    
    $query .= "SELECT * FROM user 
    WHERE comment = 'Approved' AND virtual_acctno != '' AND (virtual_acctno = '$transType' OR created_by = '$transType')
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


if($searchValue != '')
{
 $query .= 'SELECT * FROM user
 WHERE virtual_acctno LIKE "%'.$_POST['search']['value'].'%" 
 ';
}


if(isset($_POST['order']))
{
 $query .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' DESC ';
}
else
{
 $query .= 'ORDER BY userid DESC ';
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
 
 $userid = $row['created_by'];
 $id = $row['id'];

 $search_i = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$userid'");
 $get_i = mysqli_fetch_array($search_i);
 $i_name = ($get_i['cname'] === "") ? "Unknown" : $get_i['cname'];

 $virtualWallet = mysqli_query($link, "SELECT * FROM virtual_account WHERE userid = '$id' ORDER BY id DESC");
 $fetchWallet = mysqli_fetch_array($virtualWallet);
 $date_time = $fetchWallet['date_time'];
 
 /*$utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');*/
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['userid']."'>";
 $sub_array[] = "<a href='view_whistory.php?tid=".$_SESSION['tid']."&&mid=NDA0&&aid=".$row['id']."' target='_blank'><b>".$i_name."</b></a>";
 $sub_array[] = $fetchWallet['account_name'];
 $sub_array[] = $fetchWallet['bank_name'];
 $sub_array[] = $fetchWallet['account_number'];
 $sub_array[] = $get_i['currency'].number_format($row['transfer_balance'],2,'.',',');
 $sub_array[] = "<span class='label bg-".(($fetchWallet['status'] == 'ACTIVE') ? 'blue' : 'orange')."'>".$fetchWallet['status']."</span>";
 $sub_array[] = date("Y-m-d g:i A", strtotime($date_time));
 $data[] = $sub_array;
}

function count_all_data($connect)
{
$query = "SELECT * FROM user WHERE comment = 'Approved' AND virtual_acctno != ''";
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