<?php
include('../config/session1.php');

$column = array('id', 'Action', 'Agent Name', 'Plan Name', 'Customer Name', 'Subscription Code', 'Amount', 'Duration', 'Status', 'Activation Date', 'Maturity Date','Expected Amount');

$searchValue = $_POST['search']['value'];

## Custom Field value
//$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
//$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$startDate = (isset($_POST['startDate'])) ? $_POST['startDate'].' 00'.':00'.':00' : "";
$endDate = (isset($_POST['endDate'])) ? $_POST['endDate'].' 24'.':00'.':00' : "";
$filterBy = $_POST['transType'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all" && $filterBy != "Pending" && $filterBy != "Approved" && $filterBy != "Stop"){
    
    $query .= "SELECT * FROM savings_subscription 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$vcreated_by' AND vendorid = '$vendorid' AND (agentid = '$filterBy' OR acn = '$filterBy')
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM savings_subscription 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$vcreated_by' AND vendorid = '$vendorid'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "Pending"){
    
    $query .= "SELECT * FROM savings_subscription 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$vcreated_by' AND vendorid = '$vendorid' AND status = 'Pending'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "Approved"){
    
    $query .= "SELECT * FROM savings_subscription 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$vcreated_by' AND vendorid = '$vendorid' AND status = 'Approved'
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "Stop"){
    
    $query .= "SELECT * FROM savings_subscription 
    WHERE date_time BETWEEN '$startDate' AND '$endDate' AND companyid = '$vcreated_by' AND vendorid = '$vendorid' AND status = 'Stop'
    ";
    
}





if($searchValue != '')
{
 $query .= "SELECT * FROM savings_subscription
 WHERE subscription_code LIKE '%".$_POST['search']['value']."%' 
 OR acn LIKE '%".$_POST['search']['value']."%'
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
 
 $plan_code = $row['plan_code'];
 $scode = $row['subscription_code'];
 $now = strtotime(date("Y-m-d h:m:s")); // or your date as well
 $date_time = strtotime($row['date_time']);
 $mature_date = strtotime($row['mature_date']);
 $sstatus = $row['status'];
 $acn = $row['acn'];
 $sub_type = $row['sub_type'];
 $agentid = $row['agentid'];
 $vendorid = $row['vendorid'];

 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$row['date_time'],new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');

 $search_cust = mysqli_query($link, "SELECT * FROM borrowers WHERE (account = '$acn' OR virtual_acctno = '$acn')");
 $fetch_cust = mysqli_fetch_array($search_cust);

 $search_agent = mysqli_query($link, "SELECT * FROM user WHERE id = '$agentid'");
 $fetch_agent = mysqli_fetch_array($search_agent);
 $agent_name = $fetch_agent['name'].' '.$fetch_agent['lname'].' ('.$fetch_agent['virtual_acctno'].')';
    
 $search_subdetails = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$plan_code'");
 $fetch_subdetails = mysqli_fetch_array($search_subdetails);
     
 $search_trans = mysqli_query($link, "SELECT SUM(amount), invoice_code FROM all_savingssub_transaction WHERE subscription_code = '$scode'");
 $fetch_trans = mysqli_fetch_array($search_trans);
 $txid = $fetch_trans['invoice_code'];
 $total_invested = $fetch_trans['SUM(amount)'];
     
 $datediff = $now - $date_time;
 $current_stage = round($datediff / (60 * 60 * 24));
     
 $datediff2 = $mature_date - $date_time;
 $furture_stage = round($datediff2 / (60 * 60 * 24));

 $percentage_calc = ($current_stage / $furture_stage) * 100;
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox'  name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])." btn-flat dropdown-toggle' data-toggle='dropdown'>View
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($sstatus == "Approved" && $sub_type == "Auto") ? "<li><p><a href='disable_msub.php?tid=".$_SESSION['tid']."&&txid=".$txid."&&pcode=".$plan_code."&&scode=".$scode."&&mid=".base64_encode("490")."' class='btn btn-default btn-flat'><i class='fa fa-times'>&nbsp;Disable Sub</i></a></p></li>" : '')."
                    <li><p><a href='walletVerifiedInfo.php?id=".$_SESSION['tid']."&&mid=".base64_encode("1000")."&&uid=".$acn."&&scode=".$scode."' class='btn btn-default btn-flat' target='_blank'><i class='fa fa-search'>&nbsp;View KYC / Document</i></a></p></li>
                    <li><p><a href='uploadCert.php?tid=". $_SESSION['tid']."&&scode=".$scode."&&mid=".base64_encode("490")."' target='_blank' class='btn btn-default btn-flat'><i class='fa fa-file'>&nbsp;Upload File</i></a></p></li>
                    </ul>
                    </div>
                </div>";
 $sub_array[] = ($agentid == "") ? "---" : $agent_name;
 $sub_array[] = ($fetch_subdetails['plan_name'] == "") ? "---" : $fetch_subdetails['plan_name'];
 $sub_array[] = $fetch_cust['fname'].' '.$fetch_cust['lname'].' '.$fetch_cust['mname'].' ('.$fetch_cust['virtual_acctno'].')';
 $sub_array[] = $row['subscription_code'].'
                <div class="progress-group">
                <p>
                    <b>Amount Invested:</b><br>
                    '.$fetch_subdetails['currency'].number_format($total_invested,2,'.',',').'
                </p>
                <span class="float-right">'.(($current_stage >= $furture_stage) ? "0" : ($furture_stage - $current_stage)).' <b>day(s) left</b></span>
                <div class="progress progress-sm">
                <div class="progress-bar bg-'.(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']).'" style="width: '.(($percentage_calc >= 100) ? 100 : $percentage_calc).'%"></div>
                </div>
                </div>';
 $sub_array[] = $row['currency'].number_format($row['amount'],2,'.',',');
 $sub_array[] = $row['duration'].' '.(($row['savings_interval'] == "daily" ? "day(s)" : ($row['savings_interval'] == "weekly" ? "week(s)" : ($row['savings_interval'] == "monthly" ? "month(s)" : ($row['savings_interval'] == "yearly" ? "year(s)" : ($row['savings_interval'] == "quarterly" ? "quarter(s)" : ($row['savings_interval'] == "ONE-OFF" ? "ONE-OFF" : "bi-anual")))))));
 $sub_array[] = $row['status'];
 $sub_array[] = $correctdate;
 $sub_array[] = date("Y-m-d g:i A", strtotime($row['mature_date']));
 $sub_array[] = ($row['amt_plusInterest'] == "Undefine" || $row['amt_plusInterest'] == "") ? "TBD" : $fetch_subdetails['currency'].number_format($row['amt_plusInterest'],2,'.',',');
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM savings_subscription WHERE companyid = '$vcreated_by' AND vendorid = '$vendorid'";
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