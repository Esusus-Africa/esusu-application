<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"> <a href="paid_sub.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDIw"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-spine"></i> Renew Subscription</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['renewSub']))
{
    function random_password($limit)
	{
	    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
    
    $oldsub_token = mysqli_real_escape_string($link, $_POST['sub_token']);
    $sub_token = "Tkn_".random_password(11);
    $refid = mt_rand(10000000,99999999);
    $institution_id = mysqli_real_escape_string($link, $_POST['subscriber']);
    $sub_plan =  mysqli_real_escape_string($link, $_POST['plan_code']);
    $amount_paid =  mysqli_real_escape_string($link, $_POST['amount_paid']);
    $staff_limit =  mysqli_real_escape_string($link, $_POST['staff_limit']);
    $branch_limit =  mysqli_real_escape_string($link, $_POST['branch_limit']);
    $customer_limit = mysqli_real_escape_string($link, $_POST['customer_limit']);
    $no_group =  mysqli_real_escape_string($link, $_POST['no_group']);
    $no_lproduct =  mysqli_real_escape_string($link, $_POST['no_lproduct']);
    $no_sproduct =  mysqli_real_escape_string($link, $_POST['no_sproduct']);
    $no_invproduct =  mysqli_real_escape_string($link, $_POST['no_invproduct']);

    $date_from = mysqli_real_escape_string($link, $_POST['starting_from']);
    $number_of_months = mysqli_real_escape_string($link, $_POST['dto']);
    //Subscription Date Range
    $expiry_date = date('Y-m-d', strtotime("+$number_of_months months", strtotime($date_from)));
    
    //Calculate Amount to pay per year/month/day
    $total_amountpaid = ($amount_paid * $number_of_months);

    $search_inst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$institution_id'");
    $fetch_inst = mysqli_fetch_array($search_inst);
    $aggr_id = $fetch_inst['aggr_id'];
    $name = $fetch_inst['institution_name'];
    $instEmail = ($fetch_inst['official_email'] == "") ? "" : ",".$fetch_inst['official_email'];
    
    $search_aggr = mysqli_query($link, "SELECT * FROM aggregator WHERE aggr_id = '$aggr_id'");
    $checkAggr = mysqli_num_rows($search_aggr);
    $aggrEmail = ($checkAggr['email'] == "") ? "" : ",".$checkAggr['email'];
    $commType = $checkAggr['aggr_co_type'];
    $aggr_co_rate = $checkAggr['aggr_co_rate'];
    $commValue = ($commType == "Percentage") ? (($aggr_co_rate / 100) * $total_amountpaid) : $aggr_co_rate;
    
    $search_aggrUser = mysqli_query($link, "SELECT * FROM user WHERE id = '$aggr_id'");
    $checkAggrUser = mysqli_num_rows($search_aggrUser);
    $walletBal = $checkAggrUser['transfer_balance'] + $commValue;
    $currenctdate = date("Y-m-d h:i:s");

    $search_sysstemset = mysqli_query($link, "SELECT * FROM systemset");
    $r = mysqli_fetch_object($search_sysstemset);

    $emailReceiver = $r->email.$instEmail.$aggrEmail;

    $update = mysqli_query($link, "UPDATE saas_subscription_trans SET usage_status = 'Deactivated' WHERE sub_token = '$oldsub_token'") or die ("Error: " . mysqli_error($link));
    $insert = mysqli_query($link, "INSERT INTO saas_subscription_trans VALUES(null,'$institution_id','$refid','$sub_plan','0','$sub_token','$total_amountpaid','$date_from','$expiry_date','5','$staff_limit','$branch_limit','$customer_limit','$no_group','$no_lproduct','$no_sproduct','$no_invproduct','Paid','Active','$currenctdate')") or die ("Error: " . mysqli_error($link));
    ($aggr_id == "") ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$walletBal' WHERE id = '$aggr_id'");
    ($aggr_id == "") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$refid','Aggregator: $aggr_id','$commValue','','Credit','NGN','COMMISSION','Response: Esusu Africa Platform Commission of NGN$commValue on Subscription as Aggregator','successful','$currenctdate','$aggr_id','$walletBal','')");

    if(!($update && $insert))
    {
            echo "<div class='alert bg-orange'>Error.....Please try again later</div>";
    }
    else{
        include('../cron/subscription_receipt.php');
        echo "<div class='alert bg-blue'>Subscription Upgraded Successfully!</div>";
    }
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
<?php
$subtoken = $_GET['subtoken'];
$search_saasplan = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE sub_token = '$subtoken'");
$fetch_saasplan = mysqli_fetch_object($search_saasplan);
$pcode = $fetch_saasplan->plan_code;

$searchSaasPlan = mysqli_query($link, "SELECT * FROM saas_subscription_plan WHERE plan_code = '$pcode'") or die (mysqli_error($link));
$fetchSaasPlan = mysqli_fetch_array($searchSaasPlan);
?>


             <div class="box-body">

             <input name="sub_token" type="hidden" class="form-control" value="<?php echo $subtoken; ?>">

            <input name="subscriber" type="hidden" class="form-control" value="<?php echo $fetch_saasplan->coopid_instid; ?>">

			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Code</label>
                  <div class="col-sm-9">
                  <input name="plan_code" type="text" class="form-control" value="<?php echo $pcode; ?>" readonly>
                  </div>
                  </div>

			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Name</label>
                  <div class="col-sm-9">
                  <input name="plan_name" type="text" class="form-control" value="<?php echo $fetchSaasPlan['plan_name']; ?>" placeholder="Plan Name" readonly>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Amount</label>
                  <div class="col-sm-9">
                  <input name="amount_paid" type="text" class="form-control" value="<?php echo $fetch_saasplan->amount_paid; ?>" placeholder="Amount Paid" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Staff/Sub-Agent Limit</label>
                  <div class="col-sm-9">
                  <input name="staff_limit" type="text" class="form-control" value="<?php echo $fetch_saasplan->staff_limit; ?>" placeholder="Number of Staff Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Branch Limit</label>
                  <div class="col-sm-9">
                  <input name="branch_limit" type="text" class="form-control" value="<?php echo $fetch_saasplan->branch_limit; ?>" placeholder="Number of Branches Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Customers Limit</label>
                  <div class="col-sm-9">
                  <input name="customer_limit" type="text" class="form-control" value="<?php echo $fetch_saasplan->customer_limit; ?>" placeholder="Number of Customers Regstration Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Groups</label>
                  <div class="col-sm-9">
                  <input name="no_group" type="text" class="form-control" value="<?php echo $fetch_saasplan->no_group; ?>" placeholder="Number of Groups Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Loan Product</label>
                  <div class="col-sm-9">
                  <input name="no_lproduct" type="text" class="form-control" value="<?php echo $fetch_saasplan->no_lproduct; ?>" placeholder="Number of Loan Product Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Savings Product</label>
                  <div class="col-sm-9">
                  <input name="no_sproduct" type="text" class="form-control" value="<?php echo $fetch_saasplan->no_sproduct; ?>" placeholder="Number of Savings Product Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Investment Product</label>
                  <div class="col-sm-9">
                  <input name="no_invproduct" type="text" class="form-control" value="<?php echo $fetch_saasplan->no_invproduct; ?>" placeholder="Number of Investment Product Allowed" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Starting From</label>
                  <div class="col-sm-9">
                  <input name="starting_from" type="text" class="form-control" value="<?php echo $fetch_saasplan->duration_to; ?>" <?php echo ($urole == "super_admin") ? "required" : "readonly"; ?>>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Number of Month(s)</label>
                  <div class="col-sm-9">
                 <input name="dto" type="number" class="form-control" placeholder="Enter Number of month(s) the client want to subscribe for, in order to use the software" required/>
                 <span style="color: orange; font-size: 18px;"><b>NOTE:</b> You are to enter the number of months the client want to subscribe for here in order to use the software</span>
                  </div>
                  </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="renewSub" type="submit" class="btn bg-blue"><i class="fa fa-sort-up">&nbsp;Renew Sub</i></button>
              </div>
			  </div>

			 </form> 

</div>	
</div>	
</div>
</div>