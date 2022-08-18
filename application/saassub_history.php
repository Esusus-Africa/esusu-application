<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar1.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

<?php
if(isset($_GET['refid']) == true){
  $refid = $_GET['refid'];
  $sub_token = $_GET['token'];

  $search_refferal = mysqli_query($link, "SELECT * FROM referral_records WHERE downline_id = '$session_id' AND pstatus = 'NotPaid'");
  if(mysqli_num_rows($search_refferal) == 1)
  {
    $fetch_referral = mysqli_fetch_array($search_refferal);
    $upline_id = $fetch_referral['upline_id'];
    $accttype = $fetch_referral['accttype'];

    $search_perc = mysqli_query($link, "SELECT * FROM compensation_plan ORDER BY id");
    $fetch_perc = mysqli_fetch_array($search_perc);
    $perc = ($fetch_perc['percentage'])/100;

    $amount_topay = $total_amountpaid * $perc;

    $search_agt = mysqli_query($link, "SELECT * FROM agent_data WHERE status = 'Approved' AND agentid = '$upline_id'");
    $fetch_agt = mysqli_fetch_array($search_agt);
    $aname = $fetch_agt['fname'];

    $insert = mysqli_query($link, "INSERT INTO referral_incomehistory VALUES(null,'$upline_id','$aname','$amount_topay','Pending',NOW())");

    $activate_sub = mysqli_query($link, "UPDATE saas_subscription_trans SET refid = '$refid', status = 'Paid', usage_status = 'Active' WHERE sub_token = '$sub_token'");
    echo "<script>alert('Subscription Activated Successfully'); </script>";
    echo "<script>window.location='dashboard.php?id=".$_SESSION['tid']."&&mid=NDAx'; </script>";
  }
  else{
    $activate_sub = mysqli_query($link, "UPDATE saas_subscription_trans SET refid = '$refid', status = 'Paid', usage_status = 'Active' WHERE sub_token = '$sub_token'");
    echo "<script>alert('Subscription Activated Successfully'); </script>";
    echo "<script>window.location='dashboard.php?id=".$_SESSION['tid']."&&mid=NDAx'; </script>";
  }

}
?>
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        My Subscription History
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
		<li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">History</li>
      </ol>
    </section>
    <section class="content">
		<?php include("include/saassub_history_data.php"); ?>
	</section>
</div>	

<?php include("include/footer.php"); ?>