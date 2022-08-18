<?php
include('../config/session1.php');

$column = array('id', 'action', 'sNo', 'branch', 'staffName', 'accountId', 'name', 'acctType', 'regType', 'phone', 'Last Update', 'Opening Date', 'ledgerBalance', 'targetSavingsBal', 'investmentBalance', 'loanBalance', 'assetAcquisitionBal', 'walletBalance', 'status');

$searchValue = $_POST['search']['value'];

## Custom Field value
//$startDate = date('Y-m-d h:i:s', strtotime($_POST['startDate']));
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
//$endDate = date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +1 day'));
$byCustomer = $_POST['byCustomer'];
$filterBy = $_POST['filterBy'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $byCustomer == "" && $filterBy != ""){
    
    ($individual_customer_records != "1" && $branch_customer_records != "1") ? $query .= "SELECT * FROM borrowers 
    WHERE (acct_opening_date BETWEEN '$startDate' AND '$endDate'  AND branchid = '$institution_id' AND acct_status != 'Closed' AND (sbranchid = '$filterBy' OR lofficer = '$filterBy' OR status = '$filterBy'))
     " : "";
     
    ($individual_customer_records === "1" && $branch_customer_records != "1") ? $query .= "SELECT * FROM borrowers 
    WHERE (acct_opening_date BETWEEN '$startDate' AND '$endDate'  AND branchid = '$institution_id' AND acct_status != 'Closed' AND lofficer = '$iuid')
     " : "";
     
    ($individual_customer_records === "1" && $branch_customer_records != "1") ? $query .= "SELECT * FROM borrowers 
    WHERE (acct_opening_date BETWEEN '$startDate' AND '$endDate'  AND branchid = '$institution_id' AND acct_status != 'Closed' AND sbranchid = '$isbranchid')
     " : "";
    
}

if($startDate == "" && $endDate == "" && $filterBy == "" && $byCustomer != ""){
    
    ($individual_customer_records != "1" && $branch_customer_records != "1") ? $query .= "SELECT * FROM borrowers 
    WHERE account =  '$byCustomer' AND branchid = '$institution_id' AND acct_status != 'Closed'
     " : "";
     
    ($individual_customer_records === "1" && $branch_customer_records != "1") ? $query .= "SELECT * FROM borrowers 
    WHERE account =  '$byCustomer' AND branchid = '$institution_id' AND lofficer = '$iuid' AND acct_status != 'Closed'
     " : "";
     
    ($individual_customer_records != "1" && $branch_customer_records === "1") ? $query .= "SELECT * FROM borrowers 
    WHERE account =  '$byCustomer' AND branchid = '$institution_id' AND sbranchid = '$isbranchid' AND acct_status != 'Closed'
     " : "";
    
}


if($startDate == "" && $endDate == "" && $filterBy != "All" && $filterBy != "" && $byCustomer == ""){
    
    ($individual_customer_records != "1" && $branch_customer_records != "1") ? $query .= "SELECT * FROM borrowers 
    WHERE branchid = '$institution_id' AND acct_status != 'Closed' AND (sbranchid = '$filterBy' OR lofficer = '$filterBy' OR status = '$filterBy')
     " : "";
     
    ($individual_customer_records === "1" && $branch_customer_records != "1") ? $query .= "SELECT * FROM borrowers 
    WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND acct_status != 'Closed'
     " : "";
     
    ($individual_customer_records != "1" && $branch_customer_records === "1") ? $query .= "SELECT * FROM borrowers 
    WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND acct_status != 'Closed'
     " : "";
    
}


if($startDate == "" && $endDate == "" && $filterBy == "All" && $byCustomer == ""){
    
    ($individual_customer_records != "1" && $branch_customer_records != "1") ? $query .= "SELECT * FROM borrowers 
    WHERE branchid = '$institution_id' AND acct_status != 'Closed'
     " : "";
     
    ($individual_customer_records === "1" && $branch_customer_records != "1") ? $query .= "SELECT * FROM borrowers 
    WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND acct_status != 'Closed'
     " : "";
     
    ($individual_customer_records != "1" && $branch_customer_records === "1") ? $query .= "SELECT * FROM borrowers 
    WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND acct_status != 'Closed'
     " : "";
    
}


if($startDate != "" && $endDate != "" && $filterBy == "" && $byCustomer == ""){
    
    ($individual_customer_records != "1" && $branch_customer_records != "1") ? $query .= "SELECT * FROM borrowers 
    WHERE acct_opening_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND acct_status != 'Closed'
     " : "";
     
    ($individual_customer_records === "1" && $branch_customer_records != "1") ? $query .= "SELECT * FROM borrowers 
    WHERE acct_opening_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND lofficer = '$iuid' AND acct_status != 'Closed'
     " : "";
     
    ($individual_customer_records === "1" && $branch_customer_records != "1") ? $query .= "SELECT * FROM borrowers 
    WHERE acct_opening_date BETWEEN '$startDate' AND '$endDate' AND branchid = '$institution_id' AND sbranchid = '$isbranchid' AND acct_status != 'Closed'
     " : "";
    
}

/*if($pmtType != ""){
    
    $query .= "
    WHERE t_type LIKE '%$pmtType%'
    ";
    
}*/


if($searchValue != '')
{
 $query .= "SELECT * FROM borrowers
 WHERE id LIKE '%".$_POST['search']['value']."%' 
 OR account LIKE '%".$_POST['search']['value']."%' 
 OR snum LIKE '%".$_POST['search']['value']."%' 
 OR status LIKE '%".$_POST['search']['value']."%' 
 OR phone LIKE '%".$_POST['search']['value']."%' 
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
 $snum = $row['snum'];
 $acctno = $row['account'];
 $lname = $row['lname'];
 $fname = $row['fname'];
 $email = $row['email'];
 $phone = $row['phone'];
 $date_time = $row['date_time'];
 $referral = $row['referral'];
 $posts = $row['posts'];
 $acct_status = $row['acct_status'];
 $bal = $row['balance'];
 $wbal = $row['wallet_balance'];
 $mybranch = $row['branchid'];
 $mysbranch = $row['sbranchid'];
 $myofficer = $row['lofficer'];
 $acct_type = $row['acct_type'];
 $reg_type = $row['reg_type'];
 $card_id = $row['card_id'];
 $card_reg = $row['card_reg'];
 $card_issuer = $row['card_issurer'];
 $openingdate = $row['acct_opening_date'];
       
 //Card registration requirement
 $dob = $row['dob'];
 $c_address = $row['addrs'];
 $c_city = $row['city'];
 $c_state = $row['state'];
 $c_zip = $row['zip'];
 //$image = $row['image'];
        
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$date_time,new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');
       
 $search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$mysbranch'");
 $fetch_branch = mysqli_fetch_array($search_branch);
        
 $search_staff = mysqli_query($link, "SELECT * FROM user WHERE id = '$myofficer'");
 $fetch_staff = mysqli_fetch_array($search_staff);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$id."' ".(($bal >= 1 || $wbal >= 1) ? 'disabled' : '').">";
 $sub_array[] = ($acct_status == "Closed") ? "---" : "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($update_customers_info == '1') ? "<li><p><a href='add_to_borrower_list.php?id=".$id."&&mid=".base64_encode('403')."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-save'>&nbsp;Update Information</i></a></p></li>" : '')."
                    ".(($view_account_info == '1') ? "<li><p><a href='invoice-print.php?id=".$_SESSION['tid']."&&mid=".base64_encode('403')."&&uid=".$acctno."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-search'>&nbsp;View Account Info.</i></a></p></li>" : '')."
                    ".(($view_loan_history == '1') ? "<li><p><a href='loan-history.php?id=".$_SESSION['tid']."&&mid=".base64_encode('403')."&&uid=".$acctno."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-book'>&nbsp;View Loan History.</i></a></p></li>" : '')."
                    ".(($initiate_cardholder_registration == '1' && $card_reg == 'No' && $c_address != '' && $dob != '' && $c_city != '' && $c_state != '') ? "<li><p><a href='cardholder_reg1.php?id=".$id."&&mid=".base64_encode('403')."' class='btn btn-default btn-flat'><i class='fa fa-credit-card'>&nbsp;Enrol for VerveCard</i></a></p></li>" : '')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = ($snum == "") ? "null" : $snum;
 $sub_array[] = ($mybranch != "" && $mysbranch == "") ? '<b>Head Office</b>' : '<b>'.$fetch_branch['bname'].'</b>';
 $sub_array[] = ($myofficer == "") ? 'NIL' : $fetch_staff['name'];
 $sub_array[] = $acctno;
 $sub_array[] = $fname.' '.$lname;
 $sub_array[] = ($acct_type == "") ? "NIL" : $acct_type;
 $sub_array[] = $reg_type;
 $sub_array[] = $phone;
 $sub_array[] = $correctdate;
 $sub_array[] = $openingdate;
 $sub_array[] = $row['currency'].number_format($bal,2,'.',',');
 $sub_array[] = $row['currency'].number_format($row['target_savings_bal'],2,'.',',');
 $sub_array[] = $row['currency'].number_format($row['investment_bal'],2,'.',',');
 $sub_array[] = $row['currency'].number_format($row['loan_balance'],2,'.',',');
 $sub_array[] = $row['currency'].number_format($row['asset_acquisition_bal'],2,'.',',');
 $sub_array[] = $row['currency'].number_format($wbal,2,'.',',');
 $sub_array[] = ($acct_status == "Activated" ? "<span class='label bg-blue'>Active</span>" : ($acct_status == "Closed" ? "<span class='label bg-red'>Closed</span>" : "<span class='label bg-orange'>Not-Active</span>"));
 $data[] = $sub_array;
}

//".(($initiate_cardholder_registration == '1' && $card_reg == 'No' && $c_address != '' && $dob != '' && $c_city != '' && $c_state != '') ? "<li><p><a href='cardholder_reg.php?id=".$id."&&mid=".base64_encode('403')."' class='btn btn-default btn-flat'><i class='fa fa-cc-mastercard'>&nbsp;Enrol for Mastercard</i></a></p></li>" : '')."

function count_all_data($connect)
{
 ($individual_transaction_records != "1" && $branch_transaction_records != "1") ? $query = "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed'" : "";
 ($individual_transaction_records === "1" && $branch_transaction_records != "1") ? $query = "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND acct_status != 'Closed'" : "";
 ($individual_transaction_records != "1" && $branch_transaction_records === "1") ? $query = "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND acct_status != 'Closed'" : "";
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