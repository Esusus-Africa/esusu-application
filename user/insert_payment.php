<?php
// Establish Connection with MYSQL
include("../config/connect.php");
if($_POST['id'])
{
$id=$_POST['id'];
$search = mysqli_query($link, "SELECT * FROM borrowers WHERE account='$id'");
$get_search = mysqli_fetch_array($search);
$accountno = $get_search['account']; 
$sql=mysqli_query($link, "select * from pay_schedule where status = 'UNPAID' AND tid='$accountno'");
while($row=mysqli_fetch_array($sql))
{
$systemset = mysqli_query($link, "SELECT * FROM systemset");
$rowsys = mysqli_fetch_array($systemset);
echo '<option value="'.$row['id'].'">'."Amount to Pay(".$rowsys['currency'].number_format($row['payment'],2,'.',',')."&nbsp;"."-"."&nbsp;"."Balance: ".$rowsys['currency'].number_format($row['balance'],2,'.',',').")".'</option>';
}
}
?>