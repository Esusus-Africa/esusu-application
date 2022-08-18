<?php
include "../config/session1.php"; 
if(isset($_GET['export']))
{
    // Filter the excel data 
    function filterData(&$str){ 
        $str = preg_replace("/\t/", "\\t", $str); 
        $str = preg_replace("/\r?\n/", "\\n", $str); 
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
    } 
    
    // Excel file name for download 
    $filename = "customerInfo_".date('Ymd').".xls";

    $fields = array('Surname','First Name','Middle Name','Gender','Address','City','State','LGA','Country','Date of Birth','Email Address','Contact Phone','BVN','Occupation','Mother Maiden Name','Mode of Identification','Beneficiary','Beneficiary Phone','Beneficiary Address','Name of Trustee','Date of Proposal','Origin Plan Code','Subscription Plan Code','Plan Name','Plan Amount','Contributable Amount','Effective Date','Maturity Date','Frequency','Tenor','Other Info');
    
    // Display column names as first row 
    $excelData = implode("\t", array_values($fields)) . "\n"; 

    // Get records from the database 
    $acct = $_GET['acct'];
    $scode = $_GET['scode'];
    
    $query = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acct' OR virtual_acctno = '$acct'");
    $row = mysqli_fetch_assoc($query);
    
    $query2 = mysqli_query($link, "SELECT * FROM savings_subscription WHERE subscription_code = '$scode' AND status = 'Approved'");
    $row2 = mysqli_fetch_assoc($query2);
    $pcode = $row2['plan_code'];
    $newpcode = $row2['new_plancode'];
    
    $query3 = mysqli_query($link, "SELECT * FROM savings_plan WHERE plan_code = '$pcode' AND status = 'Active'");
    $row3 = mysqli_fetch_assoc($query3);

    $contributableAmt = $row2['amount'] * $row2['duration'];
    $beneficiary = ($row['nok_rela'] == "") ? $row['nok'] : $row['nok'].'('.$row['nok_rela'].')';
    $tenor = ($row2['savings_interval'] == "daily" ? $row2['duration'].' Day(s)' : ($row2['savings_interval'] == "weekly" ? $row2['duration'].' Week(s)' : ($row2['savings_interval'] == "monthly" ? $row2['duration'].' Month(s)' : $row2['duration'].' Year(s)')));
    $lineData = array($row['lname'],$row['fname'],$row['mname'],$row['gender'],$row['addrs'],$row['city'],$row['state'],$row['lga'],$row['country'],$row['dob'],$row['email'],$row['phone'],$row['unumber'],$row['occupation'],$row['mmaidenName'],$row['moi'],$beneficiary,$row['nok_phone'],$row['nok_addrs'],$row['name_of_trustee'],$row2['date_time'],$pcode,$newpcode,$row3['plan_name'],$row2['amount'],$contributableAmt,$row2['date_time'],$row2['mature_date'],ucwords($row2['savings_interval']),$tenor,$row['otherInfo']);
    
    array_walk($lineData, 'filterData'); 
    $excelData .= implode("\t", array_values($lineData)) . "\n";

    // Headers for download 
    header("Content-Disposition: attachment; filename=\"$filename\";"); 
    header("Content-Type: application/vnd.ms-excel"); 
    header("Pragma: no-cache");
    header("Expires: 0");
     
    // Render excel data 
    echo $excelData; 
}
?>