<?php
include('../config/session1.php');

$column = array('id', 'Actions', 'Acct. Type', 'Acct. Officer', 'Acct. Number', 'Acct. Name', 'Bank Name', 'Phone Number', 'Email Address', 'Wallet Balance', 'Opening Date', 'Status');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$filterBy = $_POST['filterBy'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $filterBy != "all1" && $filterBy != "all2" && $filterBy != "all3"){
    
    $query .= "SELECT * FROM virtual_account 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND (userid = '$filterBy' OR acctOfficer = '$filterBy')
    ";
    
}

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM virtual_account 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND reg_type = 'Individual'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all1"){
    
    $query .= "SELECT * FROM virtual_account 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND reg_type = 'agent'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all2"){
    
    $query .= "SELECT * FROM virtual_account 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND reg_type = 'corporate'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all3"){
    
    $query .= "SELECT * FROM virtual_account 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$institution_id' AND acctOfficer = '$ivirtual_acctno' AND reg_type = 'Individual'
    ";
    
}

/*if($startDate === "" && $endDate === "" && $filterBy != "" && $filterBy === "all"){
    
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
 $query .= "SELECT * FROM virtual_account
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
 
 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',date("Y-m-d h:i:s", strtotime($row['date_time'])),new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');
 
 $acct_owner = $row['userid'];
 $search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$acct_owner'");
 $sRowNum = mysqli_num_rows($search_mystaff);
 $fetch_mystaff = mysqli_fetch_array($search_mystaff);
 $sphone = $fetch_mystaff['phone'];
 $semail = $fetch_mystaff['email'];
 $sAcctType = $fetch_mystaff['reg_type'];
 $sAcctOfficer = $fetch_mystaff['acctOfficer'];
 $sWalletBal = $fetch_mystaff['transfer_balance'];
 $sAcctStatus = $fetch_mystaff['comment'];
 $scardID = $fetch_mystaff['card_id'];
 
 $search_borro = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND account = '$acct_owner'");
 $bRowNum = mysqli_num_rows($search_borro);
 $fetch_borro = mysqli_fetch_array($search_borro);
 $bphone = $fetch_borro['phone'];
 $bemail = $fetch_borro['email'];
 $bAcctType = $fetch_borro['reg_type'];
 $bAcctOfficer = $fetch_borro['lofficer'];
 $bWalletBal = $fetch_borro['wallet_balance'];
 $bAcctStatus = $fetch_borro['acct_status'];
 $bcardID = $fetch_borro['card_id'];
 
 $userPhone = ($sRowNum == 0 && $bRowNum == 1) ? $bphone : $sphone;
 $userEmail = ($sRowNum == 0 && $bRowNum == 1) ? $bemail : $semail;
 $userAcctType = ($sRowNum == 0 && $bRowNum == 1) ? $bAcctType : $sAcctType;
 $userAcctOfficer = ($sRowNum == 0 && $bRowNum == 1) ? $bAcctOfficer : $sAcctOfficer;
 $userWalletBal = ($sRowNum == 0 && $bRowNum == 1) ? $bWalletBal : $sWalletBal;
 $userAcctStatus = ($sRowNum == 0 && $bRowNum == 1) ? $bAcctStatus : $sAcctStatus;
 $userCardID = ($sRowNum == 0 && $bRowNum == 1) ? $bcardID : $scardID;
 $userVirtualAcctNo = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['virtual_acctno'] : $fetch_mystaff['virtual_acctno'];
 $userAccountStatus = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['status'] : $fetch_mystaff['status'];
 $userActivationStatus = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['acct_status'] : $fetch_mystaff['comment'];

 $searchUser = mysqli_query($link, "SELECT * FROM user WHERE id = '$userAcctOfficer'");
 $fetchUser = mysqli_fetch_array($searchUser);
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = ($userAcctStatus == "Closed") ? "---" : "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($fund_wallet == '1') ? "<li><p><a href='wallet-towallet.php?id=".$id."&&mid=NDA0&&uid=".$userVirtualAcctNo."&&tab=tab_2' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-plus'>&nbsp;Fund Wallet</i></a></p></li>" : '')."
                    ".(($withdraw_from_wallet == '1') ? "<li><p><a href='withdraw_fromWallet.php?id=".$_SESSION['tid']."&&mid=NDA0&&uid=".$row['account_number']."&&tab=tab_1' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-minus'>&nbsp;Make Withdrawal</i></a></p></li>" : '')."
                    ".(($view_wallet_statement == '1') ? "<li><p><a href='walletStatement.php?id=".$_SESSION['tid']."&&mid=NDA0&&uid=".$acct_owner."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-eye'>&nbsp;View Statement</i></a></p></li>" : '')."
                    ".(($link_verve_card == '1' && $userCardID == "NULL") ? "<li><p><a href='link_verveCard.php?id=".$_SESSION['tid']."&&mid=NDA0&&uid=".$acct_owner."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-credit-card'>&nbsp;Link Vervecard</i></a></p></li>" : '')."
                    ".(($userAcctType == 'Individual') ? "<li><p><a href='updateWalletKYC.php?id=".$_SESSION['tid']."&&uid=".$acct_owner."&&tab=tab_1' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-edit'>&nbsp;Update KYC</i></a></p></li>" : '')."
                    ".(($view_wallet_verification == '1') ? "<li><p><a href='walletVerifiedInfo.php?id=".$_SESSION['tid']."&&mid=NDA0&&uid=".$acct_owner."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-search'>&nbsp;Verified Info</i></a></p></li>" : '')."
                    ".(($fund_card == '1' && $userCardID != "NULL") ? "<li><p><a href='create_card.php?id=".$_SESSION['tid']."&&mid=NDA0&&uid=".$acct_owner."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-money'>&nbsp;Fund Vervecard</i></a></p></li>" : '')."
                    ".(($upgrade_wallet == '1' && $userAccountStatus != "Verified") ? "<li><p><a href='upgradeWallet.php?id=".$_SESSION['tid']."&&mid=NDA0&&uid=".$acct_owner."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-arrow-up'>&nbsp;Upgrade Wallet</i></a></p></li>" : '')."
                    ".(($activate_wallet == '1' && ($userActivationStatus == 'Approved' || $userActivationStatus == 'Activated')) ? '' : "<li><p><a href='activateWallet.php?id=".$_SESSION['tid']."&&mid=NDA0&&uid=".$acct_owner."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-check'>&nbsp;Activate Wallet</i></a></p></li>")."
                    ".(($wallet_loan_history == '1' && $userVirtualAcctNo != $ivirtual_acctno) ? "<li><p><a href='loanHistory.php?id=".$_SESSION['tid']."&&uid=".$row['userid']."&&act=".$row['account_number']."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-book'>&nbsp;Loan Account</i></a></p></li>" : '')."
                    ".(($close_wallet == '1') ? "<li><p><a href='closeWallet.php?id=".$_SESSION['tid']."&&mid=NDA0&&uid=".$acct_owner."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-times'>&nbsp;Close Wallet</i></a></p></li>" : '')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = $userAcctType;
 $sub_array[] = ($userAcctOfficer == "") ? "---" : $fetchUser['name'].' '.$fetchUser['lname'].' ('.$fetchUser['virtual_acctno'].')';
 $sub_array[] = $row['account_number'];
 $sub_array[] = $row['account_name'];
 $sub_array[] = $row['bank_name'];
 $sub_array[] = ($userPhone == "" || $userPhone == "+234") ? "---" : $userPhone;
 $sub_array[] = ($userEmail == "noreply@esusu.africa" || $userEmail == "") ? "---" : $userEmail;
 $sub_array[] = number_format($userWalletBal,2,'.',',');
 $sub_array[] = $correctdate;
 $sub_array[] = ($userAcctStatus == "Activated" || $userAcctStatus == "Approved") ? "<span class='label bg-blue'>ACTIVE</span>" : "<span class='label bg-orange'>Pending</span>";
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM virtual_account WHERE companyid = '$institution_id'";
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