<?php
include('../config/session.php');

$column = array('id', 'Actions', 'Status', 'Client Name', 'Phone Number', 'RefID', 'Unique ID', 'Plan Code', 'Plan Name', 'Amount Paid', 'Expired Date', 'Trans. Date');

$searchValue = $_POST['search']['value'];

## Custom Field value
$startDate = (isset($_POST['startDate'])) ? date('Y-m-d', strtotime($_POST['startDate'])) : "";
$endDate = (isset($_POST['endDate'])) ? date('Y-m-d h:i:s', strtotime($_POST['endDate'] . ' +12 hours')) : "";
$filterBy = $_POST['transType'];

//echo $merchantid;

$query = " ";

if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy != "all"){
    
    $query .= "SELECT * FROM saas_subscription_trans 
    WHERE transaction_date BETWEEN '$startDate' AND '$endDate' AND (coopid_instid = '$filterBy' OR plan_code = '$filterBy' OR status = '$filterBy' OR usage_status = '$filterBy')
    ";
    
}


if($startDate != "" && $endDate != "" && $filterBy != "" && $filterBy === "all"){
    
    $query .= "SELECT * FROM saas_subscription_trans 
    WHERE transaction_date BETWEEN '$startDate' AND '$endDate'
    ";
    
}






if($searchValue != '')
{
 $query .= "SELECT * FROM saas_subscription_trans
 WHERE refid LIKE '%".$_POST['search']['value']."%' 
 OR plan_code LIKE '%".$_POST['search']['value']."%'
 OR sub_token LIKE '%".$_POST['search']['value']."%' 
 OR duration_to LIKE '%".$_POST['search']['value']."%'
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
 $compid = $row['coopid_instid'];
 $reg_date = $row['transaction_date'];
 $pcode = $row['plan_code'];
 $dto = $row['duration_to'];

 $utc_date = DateTime::createFromFormat('Y-m-d G:i:s',$reg_date,new DateTimeZone(date_default_timezone_get()));
 $acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
 $acst_date->setTimeZone(new DateTimeZone('GMT+1'));
 $correctdate = $acst_date->format('Y-m-d g:i A');

 $search_insti = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$compid'");
 $fetch_insti = mysqli_fetch_array($search_insti);

 $searchSaasPlan = mysqli_query($link, "SELECT * FROM saas_subscription_plan WHERE plan_code = '$pcode'") or die (mysqli_error($link));
 $fetchSaasPlan = mysqli_fetch_array($searchSaasPlan);

 $system_settings = mysqli_query($link, "SELECT * FROM systemset");
 $fetch_sys_settings = mysqli_fetch_object($system_settings);

 $now = time(); // or your date as well
 $your_date = strtotime($dto);
    
 $datediff = $your_date - $now;
 $total_day = round($datediff / (60 * 60 * 24)) + 1;
 
 $sub_array[] = "<input id='optionsCheckbox' class='checkbox' name='selector[]' type='checkbox' value='".$row['id']."'>";
 $sub_array[] = "<div class='btn-group'>
                    <div class='btn-group'>
                    <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>Action  
                        <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu'>
                    ".(($row['status'] == 'Paid' && $upgrade_saas_subscription == "1") ? '<li><p><a href="UpgradePlan.php?id='.$_SESSION['tid'].'&&subtoken='.$row['sub_token'].'&&mid='.base64_encode("420").'" class="btn btn-default btn-flat"><i class="fa fa-sort-up">&nbsp;<b>Upgrade Sub.</b></i></a></p></li>' : '')."
                    ".(($row['status'] == 'Expired' && $renew_saas_subscription == "1") ? '<li><p><a href="ReNewPlan.php?id='.$_SESSION['tid'].'&&subtoken='.$row['sub_token'].'&&mid='.base64_encode("420").'" class="btn btn-default btn-flat"><i class="fa fa-lightbulb-o">&nbsp;<b>Renew Sub.</b></i></a></p></li>' : '')."
                    ".(($total_day <= 5 && $push_saas_notification == "1") ? '<li><p><a href="saasNotifier.php?id='.$_SESSION['tid'].'&&subtoken='.$row['sub_token'].'&&mid='.base64_encode("420").'" class="btn btn-default btn-flat"><i class="fa fa-flash">&nbsp;<b>Push Notification</b></i></a></p></li>' : '')."
                    </ul>
                    </div>
                </div>";
 $sub_array[] = ($row['status'] == "Paid") ? "<label class='label bg-blue'>Paid</label>" : "<label class='label bg-orange'>Pending</label>";
 $sub_array[] = $fetch_insti['institution_name'];
 $sub_array[] = $fetch_insti['official_phone'];
 $sub_array[] = $row['refid'];
 $sub_array[] = $row['sub_token'];
 $sub_array[] = $pcode;
 $sub_array[] = $fetchSaasPlan['plan_name'];
 $sub_array[] = $fetch_sys_settings->currency.number_format($row['amount_paid'],2,'.',',');
 $sub_array[] = "<b>".$row['duration_to']."</b>";
 $sub_array[] = $correctdate;
 $data[] = $sub_array;
}

function count_all_data($connect)
{
 $query = "SELECT * FROM saas_subscription_trans";
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