<?php
include('../config/session1.php');

$column = array('id', 'Action', 'Branch', 'Teller', 'Cashier', 'Virtual Account', 'Commission Type', 'Commision', 'Till Balance', 'Commission Balance', 'Unsettled Balance', 'Status', 'Date/Time');

$searchValue = $_POST['search']['value'];

## Custom Field value
//$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
//$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$startDate = ($_POST['startDate'] != "") ? $_POST['startDate'].' 00'.':00'.':00' : "2016-01-01 00:00:00";
$endDate = ($_POST['endDate'] != "") ? $_POST['endDate'].' 24'.':00'.':00' : date("Y-m-d").' 24'.':00'.':00';
$transType = $_POST['transType'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all" && $transType != "Active" && $transType != "NotActive"){
    
    $query .= "SELECT * FROM till_account 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND cashier = '$transType' AND companyid = '$institution_id'
    ";
    
}

if($startDate != "" && $endDate != "" && $transType != "" && $transType != "Active" && $transType != "NotActive" && $transType === "all"){
    
    $query .= "SELECT * FROM till_account 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id'
     ";
    
}


if($startDate != "" && $endDate != "" && $transType != "" && $transType != "all" && ($transType === "NotActive" || $transType === "Active")){
    
    $query .= "SELECT * FROM till_account 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND status = '$transType'
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
 $query .= "SELECT * FROM till_account
 WHERE status LIKE '%".$_POST['search']['value']."'
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
 $branchid = $row['branch'];
 $cashier_id = $row['cashier'];
 $date_time = date("Y-m-d h:i:s", strtotime($row['date_time']));
 
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');
 
 $search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$branchid'");
 $fetch_branch = mysqli_fetch_array($search_branch);
 $bname = $fetch_branch['bname'];

 $search_cashier = mysqli_query($link, "SELECT * FROM user WHERE id = '$cashier_id'");
 $fetch_cashier = mysqli_fetch_array($search_cashier);
 $cashier = $fetch_cashier['name'].' '.$fetch_cashier['lname'].' '.$fetch_cashier['mname'];

 $verifyTillAcct = mysqli_query($link, "SELECT * FROM till_virtual_account WHERE userid = '$cashier_id'");
 $fetchTillAcct = mysqli_fetch_array($verifyTillAcct);
 $staff_VA1 = ($fetchTillAcct['account_number'] == "") ? "---" : $fetchTillAcct['account_number'].' | '.$fetchTillAcct['bank_name'];
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($view_teller_transaction == '1') ? "<li><p><a href='view_savings.php?id=".$_SESSION['tid']."&&idm=".$cashier_id."&&mid=NTEw' class='btn btn-default btn-flat'><i class='fa fa-eye'></i> View Transaction</a></p></li>" : '<span style="color: red;">--Not Authorized--</span>')."
                    ".(($fund_allocation_history == '1') ? "<li><p><a href='view_fund_history.php?id=".$_SESSION['tid']."&&idm=".$id."&&mid=NTEw&&tab=tab_1' class='btn btn-default btn-flat'><i class='fa fa-money'></i> Till Fund History</a></p></li>" : '<span style="color: red;">--Not Authorized--</span>')."
                    ".(($fund_settlement == '1') ? "<li><p><a href='settlement_history.php?id=".$_SESSION['tid']."&&idm=".$id."&&mid=NTEw' class='btn btn-default btn-flat'><i class='fa fa-calculator'></i> Old Settlement History</a></p></li>" : '<span style="color: red;">--Not Authorized--</span>')."
                    ".(($allocate_fund == '1') ? "<li><p><a href='allocate_fund.php?id=".$_SESSION['tid']."&&idm=".$id."&&mid=NTEw' class='btn btn-default btn-flat'><i class='fa fa-plus'></i> Allocate Fund</a></p></li>" : '<span style="color: red;">--Not Authorized--</span>')."
                    ".(($settle_fund == '1') ? "<li><p><a href='settle_fund.php?id=".$_SESSION['tid']."&&idm=".$id."&&mid=NTEw' class='btn btn-default btn-flat'><i class='fa fa-minus'></i> Settle Fund</a></p></li>" : '<span style="color: red;">--Not Authorized--</span>')."
                    ".(($irole == "agent_manager" || $irole == 'institution_super_admin' || $irole == "merchant_super_admin") ? "<li><p><a href='withdraw_fund.php?id=".$_SESSION['tid']."&&idm=".$id."&&mid=NTEw' class='btn btn-default btn-flat'><i class='fa fa-minus'></i> Withdraw Fund</a></p></li>" : '<span style="color: red;">--Not Authorized--</span>')."
                    ".(($irole == "agent_manager" || $irole == 'institution_super_admin' || $irole == "merchant_super_admin") ? "<li><p><a href='withdraw_comm.php?id=".$_SESSION['tid']."&&idm=".$id."&&mid=NTEw' class='btn btn-default btn-flat'><i class='fa fa-minus'></i> Withdraw Commission</a></p></li>" : '<span style="color: red;">--Not Authorized--</span>')."
                    ".(($irole == "agent_manager" || $irole == 'institution_super_admin' || $irole == "merchant_super_admin") ? "<li><p><a href='edit_till_acct.php?id=".$_SESSION['tid']."&&idm=".$id."&&mid=NTEw' class='btn btn-default btn-flat'><i class='fa fa-edit'></i> Update Till</a></p></li>" : '<span style="color: red;">--Not Authorized--</span>')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = ($bname == "") ? 'Head Office' : $bname;
 $sub_array[] = $row['teller'];
 $sub_array[] = $cashier;
 $sub_array[] = $staff_VA1;
 $sub_array[] = $row['commission_type'];
 $sub_array[] = $row['commission'];
 $sub_array[] = number_format($row['balance'],2,".",",");
 $sub_array[] = number_format($row['commission_balance'],2,".",",");
 $sub_array[] = number_format($row['unsettled_balance'],2,".",",");
 $sub_array[] = ($row['status'] == "Active") ? '<span class="label bg-blue">Active</span>' : '<span class="label bg-orange">Not-Active</span>';
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM till_account WHERE companyid = '$institution_id'";
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