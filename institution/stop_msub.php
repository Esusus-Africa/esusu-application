<?php 
error_reporting(0); 
include "../config/session1.php";
?>  

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
        <div class="loader"></div>
        
<?php
$scode = $_GET['scode'];

mysqli_query($link, "UPDATE savings_subscription SET status = 'Stop' WHERE subscription_code = '$scode' AND status = 'Approved'");

echo '<meta http-equiv="refresh" content="2;url=my_savings_plan.php?tid='.$_SESSION['tid'].'&&mid='.((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Savings'])) ? 'NTAw' : 'NDA3')).''.((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ''))).'">';
echo '<br>';
echo '<span class="itext" style="color: orange">Subscription Stopped Successfully!!</span>';

?>

</div>
</body>
</html>