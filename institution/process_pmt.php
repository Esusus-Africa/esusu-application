<?php include "../config/session.php"; ?>  

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../dist/css/style.css" />
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
		<div class="loader"></div>
<?php
$id = $_GET['id'];
$acte =  mysqli_real_escape_string($link, $_POST['acte']);
$account_no = mysqli_real_escape_string($link, $_POST['account']);
$customer = mysqli_real_escape_string($link, $_POST['customer']);
$loan = mysqli_real_escape_string($link, $_POST['loan']);
$pay_date = mysqli_real_escape_string($link, $_POST['pay_date']);
$amount_to_pay = mysqli_real_escape_string($link, $_POST['amount_to_pay']);
$remarks = mysqli_real_escape_string($link, $_POST['remarks']);

$search = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$acte'") or die (mysqli_error($link));
while($get_search = mysqli_fetch_array($search))
{
$interest_amt = $get_search['amount_topay'];
$total_cal = $interest_amt - $amount_to_pay;
$update = mysqli_query($link, "UPDATE loan_info SET amount_to_pay = '$total_cal' WHERE lid = '$acte'") or die (mysqli_error($link));

$update = mysqli_query($link,"UPDATE payments SET account='$acte',account_no='$account_no',customer='$customer',loan='$loan',bal='$total_cal',pay_date='$pay_date',amount_to_pay='$amount_to_pay',remarks='$remarks' WHERE id ='$id'")or die(mysqli_error()); 
if(!$update)
{
echo '<meta http-equiv="refresh" content="2;url=view_pmt.php?tid='.$id.'">';
echo '<br>';
echo'<span class="itext" style="color: #FF0000">Unable to update payment records!</span>';
}
else{
echo '<meta http-equiv="refresh" content="2;url=view_pmt.php?id='.$id.'">';
echo '<br>';
echo'<span class="itext" style="color: #FF0000">Updating Payment.....Please Wait!</span>';
}
}
?>
</div>
</body>
</html>