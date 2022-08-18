<?php
include "../config/session.php"; 
if(isset($_GET['export']))
{
    if($_GET['export'] == 'true'){
        $query = mysqli_query($link, "SELECT * FROM saas_subscription_trans");

        $delimiter = ",";
        $filename = "subscriber_".date('Ymd').".csv";
        $f = fopen("php/memory", 'w');

        $fields = array('CompanyID','RefID','TokenID','PlanCode','Amount','UnitsAllocated','ExpiredDate','TransactionDate','Status');
        fputcsv($f,$fields,$delimiter);

        while($row = mysqli_fetch_assoc($query)){
            $lineData = array($row['coopid_instid'],$row['refid'],$row['sub_token'],$row['plan_code'],$row['amount_paid'],$row['sms_allocated'],$row['duration_to'],$row['transaction_date'],$row['status']);
            fputcsv($f,$lineData,$delimiter);
        }

        fseek($f, 0);

        header("Content-Type: text/csv; charset=UTF-16LE");
        header("Content-Disposition: attachment; filename=".$filename.";");
        header("Pragma: no-cache");
        header("Expires: 0");
        fpassthru($f);
    }
}
?>