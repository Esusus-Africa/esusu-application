<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<?php
if(isset($_GET['refid']) == true)
{
	$lid = $_GET['lid'];
	$verify_balance_end = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' AND status = 'UNPAID'") or die ("Error:" . mysqli_error($link));
	$fetch_row_bal = mysqli_num_rows($verify_balance_end);
	if($fetch_row_bal == '1')
	{
		$refid = $_GET['refid'];
		$olp_id = $_GET['olp_id'];
		$pid = $_GET['pid'];
		$o_amt = base64_decode($_GET['o_amt']);
		$amount = base64_decode($_GET['n_amt']);
		
		$icm_id = "ICM".rand(100000,999999);
        $revenue = $amount - $o_amt;
        $icm_date = date("Y/m/d");
        $insert_records = mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','Revenue','$revenue','$icm_date','Make Loan Repayment Revenue')");
		$updatepay = mysqli_query($link, "UPDATE payments SET refid = '$refid', remarks = 'paid' WHERE id = '$pid'");
		$updatepaysch = mysqli_query($link, "UPDATE pay_schedule SET status = 'PAID' WHERE id = '$olp_id'");
		$search_bal = mysqli_query($link, "SELECT * FROM payments WHERE id = '$pid'");
		$track_bal = mysqli_fetch_object($search_bal);
		$get_bal = $track_bal->loan_bal;
		$updatepayloan_info = mysqli_query($link, "UPDATE loan_info SET balance = '$get_bal', p_status = 'PAID' WHERE baccount = '$acnt_id'");
	}
	else{
		$refid = $_GET['refid'];
		$olp_id = $_GET['olp_id'];
		$pid = $_GET['pid'];
		$o_amt = base64_decode($_GET['o_amt']);
		$amount = base64_decode($_GET['n_amt']);
		
		$icm_id = "ICM".rand(100000,999999);
        $revenue = $amount - $o_amt;
        $icm_date = date("Y/m/d");
        $insert_records = mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','Revenue','$revenue','$icm_date','Make Loan Repayment Revenue')");
		$updatepay = mysqli_query($link, "UPDATE payments SET refid = '$refid', remarks = 'paid' WHERE id = '$pid'");
		$updatepaysch = mysqli_query($link, "UPDATE pay_schedule SET status = 'PAID' WHERE id = '$olp_id'");
		$search_bal = mysqli_query($link, "SELECT * FROM payments WHERE id = '$pid'");
		$track_bal = mysqli_fetch_object($search_bal);
		$get_bal = $track_bal->loan_bal;
		$updatepayloan_info = mysqli_query($link, "UPDATE loan_info SET balance = '$get_bal' WHERE baccount = '$acnt_id'");
	}
}
?>
    
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Loan Repayment History
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">List</li>
      </ol>
    </section>
    <section class="content">
		<?php include("include/list_payloans_data.php"); ?>
	</section>
</div>	

<?php include("include/footer.php"); ?>