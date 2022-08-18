<?php
include('../config/session.php');

$column = array('id', 'Action', 'Aggregator ID', 'Name', 'Phone', 'Email', 'Username', 'Activities', 'Commission', 'Wallet Balance', 'Reg. Date');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$transType = $_POST['transType'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all"){
    
    $query .= "SELECT * FROM aggregator 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND aggr_id = '$transType'
    ";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType === "all"){
    
    $query .= "SELECT * FROM aggregator 
    WHERE date_time BETWEEN '$startDate' AND '$endDate'
     ";
    
}




if($searchValue != '')
{
 $query .= "SELECT * FROM aggregator
 WHERE aggr_id LIKE '%".$_POST['search']['value']."'
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
 $aggr_id = $row['aggr_id'];
 $reg_date = $row['date_time'];
 
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$reg_date,new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');
 
 $getcust_no = mysqli_query($link, "SELECT * FROM institution_data WHERE aggr_id = '$aggr_id'");
 $num = mysqli_num_rows($getcust_no);

 $gewallet_no = mysqli_query($link, "SELECT * FROM user WHERE acctOfficer = '$aggr_id'");
 $num2 = mysqli_num_rows($gewallet_no);

 $geterminal_no = mysqli_query($link, "SELECT * FROM terminal_reg WHERE initiatedBy = '$aggr_id' AND terminal_status = 'Assigned'");
 $num3 = mysqli_num_rows($geterminal_no);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($update_aggregator_profile == 1) ? "<li><p><a href='update_aggr.php?id=".$_SESSION['tid']."&&idm=".$id."&&mid=NDE5&&tab=tab_1' class='btn btn-default btn-flat'><i class='fa fa-edit'>&nbsp;Update Info</i></a></p></li>" : '<span style="color: red;">--Not Authorized--</span>')."
                    ".(($view_aggregator_kyc_info == 1) ? "<li><p><a href='aggr_KYCInfo.php?id=".$_SESSION['tid']."&&uid=".$aggr_id."&&mid=NDE5&&tab=tab_1' target='_blank' class='btn btn-default btn-flat'><i class='fa fa-eye'>&nbsp;View KYC</i></a></p></li>" : '<span style="color: red;">--Not Authorized--</span>')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = $aggr_id;
 $sub_array[] = $row['lname'].' '.$row['fname'].' '.$row['mname'];
 $sub_array[] = $row['phone'];
 $sub_array[] = $row['email'];
 $sub_array[] = $row['username'];
 $sub_array[] = '<p style="font-size: 12px;" align="center">Total Client:<br><b>'.$num.'</b></p>
                <p style="font-size: 12px;" align="center">Total Wallet:<br><b>'.$num2.'</b></p>
                <p style="font-size: 12px;" align="center">Total Terminal:<br><b>'.$num3.'</b></p>';
 $sub_array[] = ($row['aggr_co_type'] == "Percentage") ? $row['aggr_co_rate'].'%' : $row['currency'].number_format($row['aggr_co_rate'],2,'.',',');
 $sub_array[] = $row['currency'].number_format($row['wallet_balance'],2,'.',',');
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM aggregator";
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