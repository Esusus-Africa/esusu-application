<?php
include('../config/session.php');

$column = array('id', 'Action', 'Reg. Date', 'Client ID', 'Client Name', 'License Number', 'Address', 'Official Contact', 'Total Customer', 'Transaction Count', 'Wallet Balance', 'Expiry Date');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$transType = $_POST['transType'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all" && $transType != "Pending" && $transType != "Approved" && $transType != "Disapproved" && $transType != "Suspend" && $transType != "agent" && $transType != "institution" && $transType != "merchant"){
    
    $query .= "SELECT * FROM institution_data 
    WHERE reg_date BETWEEN '$startDate' AND '$endDate' AND institution_id = '$transType'
    ";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "Pending" && $transType != "Approved" && $transType != "Disapproved" && $transType != "Suspend" && $transType != "agent" && $transType != "institution" && $transType != "merchant" && $transType === "all"){
    
    $query .= "SELECT * FROM institution_data 
    WHERE reg_date BETWEEN '$startDate' AND '$endDate'
     ";
    
}


if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all" && ($transType === "Pending" || $transType === "Approved" || $transType === "Disapproved" || $transType === "Suspend" || $transType === "agent" || $transType === "institution" || $transType === "merchant")){
    
    $query .= "SELECT * FROM institution_data 
    WHERE reg_date BETWEEN '$startDate' AND '$endDate' AND (status = '$transType' OR account_type = '$transType')
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
 $query .= "SELECT * FROM institution_data
 WHERE institution_id LIKE '%".$_POST['search']['value']."'
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
 $instid = $row['institution_id'];
 $reg_date = $row['reg_date'];
 
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$reg_date,new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');
 
 $getcust_no = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$instid'");
 $num = mysqli_num_rows($getcust_no);
 $getrans_no = mysqli_query($link, "SELECT * FROM transaction WHERE branchid = '$instid'");
 $num2 = mysqli_num_rows($getrans_no);

 $my_membersettings = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$instid'");
 $fetch_mysettings = mysqli_fetch_array($my_membersettings);

 $sub_expriry_date = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE coopid_instid = '$instid' ORDER BY id DESC");
 $fetch_sub_expriry_date = mysqli_fetch_array($sub_expriry_date);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($update_client_info == 1) ? "<li><p><a href='update_instinfo?id=".$_SESSION['tid']."&&idm=".$id."&&mid=NDE5&&tab=tab_1' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-edit'>&nbsp;Update Info</i></a></p></li>" : '<span style="color: red;">--Not Authorized--</span>')."
                    ".(($configure_client == 1) ? "<li><p><a href='instprofile_settings?id=".$_SESSION['tid']."&&idm=".$instid."&&mid=NDE5&&tab=tab_1' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-gear'>&nbsp;Confgure Account</i></a></p></li>" : '<span style="color: red;">--Not Authorized--</span>')."
                    ".(($row['status'] == "Pending") ? "<li><p><a href='resend_iemail?id=".$instid."' class='btn btn-default btn-flat'><i class='fa fa-forward'>&nbsp;Resend Email</i></a></p></li>" : '')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = $correctdate;
 $sub_array[] = "<b>".$instid."</b>".($row['status'] == "Approved" ? '<span class="label bg-blue">Approved</span>' : ($row['status'] == "Disapproved" ? '<span class="label bg-orange">Disapproved</span>' : ($row['status'] == "Pending" ? '<span class="label bg-red">Pending</span>' : '<span class="label bg-blue">Updated</span>')));
 $sub_array[] = strtoupper($row['institution_name']);
 $sub_array[] = ($row['license_no'] === "") ? "------" : $row['license_no'];
 
 $sub_array[] = ($row['location'] === "") ? "------" : $row['location'];
 $sub_array[] = "<p><b>Phone:</b>".$row['official_phone']."</p>
                <p><b>Email:</b>".$row['official_email']."</p>";
 
 $sub_array[] = $num;
 $sub_array[] = $num2;
 $sub_array[] = $fetch_mysettings['currency'].number_format($row['wallet_balance'],2,'.',',');
 $sub_array[] = ($fetch_sub_expriry_date['duration_to'] == "") ? '-------' : '<b>'.$fetch_sub_expriry_date['duration_to'].'</b>';
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM institution_data";
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