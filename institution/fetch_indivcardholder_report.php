<?php
include('../config/session1.php');

$column = array('Id', 'SNo', 'Branch', 'Account Officer', 'Account Number', 'Account Name', 'Bank Name', 'Phone', 'Email', 'Card ID', 'Wallet Balance', 'Status');

$searchValue = $_POST['search']['value'];

## Custom Field value
$filterBy = $_POST['filterBy'];

//echo $merchantid;

$query = " ";

if($filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM borrowers 
    WHERE branchid = '$institution_id' AND virtual_acctno != '' AND card_id != 'NULL' AND card_reg = 'Yes'
    ";

}


if($filterBy != "" && $filterBy != "all"){
    
    $query .= "SELECT * FROM borrowers 
    WHERE account = '$filterBy' AND branchid = '$institution_id'
    ";
    
}



if($searchValue != '')
{
 $query .= "SELECT * FROM borrowers
 WHERE virtual_acctno LIKE '%".$_POST['search']['value']."%'
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
  
 $acct_owner = $row['virtual_acctno'];
 $userAcctOfficer = $row['lofficer'];
 $mysbranch = $row['sbranchid'];
 $search_mystaff = mysqli_query($link, "SELECT * FROM virtual_account WHERE companyid = '$institution_id' AND account_number = '$acct_owner'");
 $fetch_mystaff = mysqli_fetch_array($search_mystaff);
 $accountName = $fetch_mystaff['account_name'];
 
 $searchUser = mysqli_query($link, "SELECT * FROM user WHERE id = '$userAcctOfficer'");
 $fetchUser = mysqli_fetch_array($searchUser);

 $search_branch = mysqli_query($link, "SELECT * FROM branches WHERE branchid = '$mysbranch'");
 $fetch_branch = mysqli_fetch_array($search_branch);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = ($row['snum'] == "") ? "null" : $row['snum'];
 $sub_array[] = ($mysbranch == "") ? '<b>Head Office</b>' : '<b>'.$fetch_branch['bname'].'</b>';
 $sub_array[] = ($userAcctOfficer == "") ? "---" : $fetchUser['name'].' '.$fetchUser['lname'].' ['.$fetchUser['virtual_acctno'].']';
 $sub_array[] = $acct_owner;
 $sub_array[] = $accountName;
 $sub_array[] = $fetch_mystaff['bank_name'];
 $sub_array[] = $row['phone'];
 $sub_array[] = ($row['email'] == "") ? "---" : $row['email'];
 $sub_array[] = panNumberMasking($row['card_id']);
 $sub_array[] = $row['currency'].number_format($row['wallet_balance'],2,'.',',');
 $sub_array[] = ($row['acct_status'] == "Activated") ? "<span class='label bg-blue'>Active</span>" : "<span class='label bg-orange'>Not-Active</span>";
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM borrowers WHERE branchid = '$institution_id'";
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