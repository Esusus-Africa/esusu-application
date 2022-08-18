<?php
include('../config/session.php');

$column = array('id', 'action', 'sNo', 'Client Name', 'Client Branch', 'staffName', 'accountId', 'name', 'phone', 'DateTime', 'ledgerBalance', 'walletBalance', 'status');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$byCustomer = $_POST['byCustomer'];
$filterBy = $_POST['filterBy'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $byCustomer == "" && $filterBy != ""){
    
    $query .= "SELECT * FROM borrowers 
    WHERE (acct_opening_date BETWEEN '$startDate' AND '$endDate' AND (branchid = '$filterBy' OR sbranchid = '$filterBy' OR lofficer = '$filterBy' OR status = '$filterBy'))
     ";
    
}

if($startDate == "" && $endDate == "" && $filterBy == "" && $byCustomer != ""){
    
    $query .= "SELECT * FROM borrowers 
    WHERE account = '$byCustomer'
     ";
    
}

if($startDate == "" && $endDate == "" && $filterBy != "" && $byCustomer == ""){
    
    $query .= "SELECT * FROM borrowers 
    WHERE (branchid = '$filterBy' OR sbranchid = '$filterBy' OR lofficer = '$filterBy' OR status = '$filterBy')
     ";
    
}

if($startDate != "" && $endDate != "" && $filterBy == "" && $byCustomer == ""){
    
    $query .= "SELECT * FROM borrowers 
    WHERE acct_opening_date BETWEEN '$startDate' AND '$endDate'
     ";
    
}

/*if($pmtType != ""){
    
    $query .= "
    WHERE t_type LIKE '%$pmtType%'
    ";
    
}*/


if($searchValue != '')
{
 $query .= "SELECT * FROM borrowers
 WHERE account LIKE '%".$_POST['search']['value']."'
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
 $mname = $row['mname'];
 $email = $row['email'];
 $phone = $row['phone'];
 $date_time = $row['date_time'];
 $referral = $row['referral'];
 $posts = $row['posts'];
 $acct_status = $row['acct_status'];
 $bal = $row['balance'];
 //$image = $row['image'];
 $mybranch = $row['branchid'];
 $mysbranch = $row['sbranchid'];
 $myofficer = $row['lofficer'];
 $card_id = $row['card_id'];
 $card_reg = $row['card_reg'];
 $card_issuer = $row['card_issurer'];
 $bvn = $row['unumber'];
       
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

 $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$mybranch'");
 $fetch_inst = mysqli_fetch_array($search_inst);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$id."'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($update_client_customers_info == '1') ? "<li><p><a href='add_to_borrower_list.php?id=".$id."&&mid=".base64_encode('403')."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-save'>&nbsp;Update Information</i></a></p></li>" : '')."
                    ".(($transfer_customers == '1') ? "<li><p><a href='transfer_customer.php?id=".$id."&&mid=".base64_encode('403')."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-forward'>&nbsp;Transfer Customer</i></a></p></li>" : '')."
                    ".(($view_client_customers_info == '1') ? "<li><p><a href='invoice-print.php?id=".$_SESSION['tid']."&&mid=".base64_encode('403')."&&uid=".$acctno."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-search'>&nbsp;View Account Info.</i></a></p></li>" : '')."
                    ".(($view_client_customer_loan_history == '1') ? "<li><p><a href='loan-history.php?id=".$_SESSION['tid']."&&mid=".base64_encode('403')."&&uid=".$acctno."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-book'>&nbsp;View Loan History.</i></a></p></li>" : '')."
                    ".(($initiate_cardholder_registration == '1' && $card_reg == 'No' && $c_address != '' && $dob != '' && $c_city != '' && $c_state != '' && $c_zip != '') ? "<li><p><a href='cardholder_reg.php?id=".$id."&&mid=".base64_encode('403')."' class='btn btn-default btn-flat'><i class='fa fa-cc-mastercard'>&nbsp;Enrol for Mastercard</i></a></p></li>" : '')."
                    ".(($initiate_cardholder_registration == '1' && $card_reg == 'No' && $c_address != '' && $dob != '' && $c_city != '' && $c_state != '' && $bvn != "") ? "<li><p><a href='cardholder_reg1.php?id=".$id."&&mid=".base64_encode('403')."' class='btn btn-default btn-flat'><i class='fa fa-credit-card'>&nbsp;Enrol for VerveCard</i></a></p></li>" : '')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = ($snum == "") ? "null" : $snum;
 $sub_array[] = strtoupper($fetch_inst['institution_name']);
 $sub_array[] = ($mybranch != "" && $mysbranch == "") ? '<b>Head Office-'.$mybranch.'</b>' : $fetch_branch['bname'];
 $sub_array[] = ($myofficer == "") ? 'NIL' : $fetch_staff['name'].' '.$fetch_staff['lname'].' '.$fetch_staff['mname'];
 $sub_array[] = $acctno;
 $sub_array[] = $fname.' '.$lname.' '.$mname;
 $sub_array[] = $phone;
 $sub_array[] = $correctdate;
 $sub_array[] = $row['currency'].number_format($bal,2,'.',',');
 $sub_array[] = $row['currency'].number_format($row['wallet_balance'],2,'.',',');
 $sub_array[] = ($acct_status == "Activated" ? "<span class='label bg-blue'>Active</span>" : ($acct_status == "Closed" ? "<span class='label bg-red'>Closed</span>" : "<span class='label bg-orange'>Not-Active</span>"));
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM borrowers";
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