<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-globe"></i> Subscription Form</h3>
            </div>

             <div class="box-body">
              
<form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_saassub.php">

<?php
if(isset($_POST['submit']))
{
  
  $subscriber = mysqli_real_escape_string($link, $_POST['subscriber']);
	$institution_id = $subscriber;
	$sub_plan = mysqli_real_escape_string($link, $_POST['plan_code']);
	$plan_name = mysqli_real_escape_string($link, $_POST['plan_name']);
	$sms_allocated = mysqli_real_escape_string($link, $_POST['sms_allocated']);
	$sub_token = "Tkn_".random_strings(12);
	$refid = uniqid('SaaSub').time();
	$amount_paid_per_months = $_POST['amount_per_months'];
	$expiration_grace = mysqli_real_escape_string($link, $_POST['expiration_grace']);
	$staff_limit = mysqli_real_escape_string($link, $_POST['staff_limit']);
	$branch_limit = mysqli_real_escape_string($link, $_POST['branch_limit']);
	$customer_limit = mysqli_real_escape_string($link, $_POST['customer_limit']);
  $others = $_POST['others'];
	
	$no_group = mysqli_real_escape_string($link, $_POST['no_group']);
	$no_lproduct = mysqli_real_escape_string($link, $_POST['no_lproduct']);
	$no_sproduct = mysqli_real_escape_string($link, $_POST['no_sproduct']);
	$no_invproduct = mysqli_real_escape_string($link, $_POST['no_invproduct']);
	
	$date_from = mysqli_real_escape_string($link, $_POST['dfrom']);
	$number_of_months = mysqli_real_escape_string($link, $_POST['dto']);
	//Subscription Date Range
	$expiry_date = date('Y-m-d', strtotime("+$number_of_months months", strtotime($date_from)));

	//Calculate Amount to pay per year/month/day
	$total_amountpaid = ($amount_paid_per_months * $number_of_months);

	//process email and aggregator commission
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

  $emailReceiver = $fetchsys_config['email'].$instEmail.$aggrEmail;
  $calc_bonus = 0;
  $subType = "Saas Subscription";

  $search_emailConfig = mysqli_query($link, "SELECT * FROM email_config WHERE companyid = '$institution_id'");
  $fetch_emailConfig = mysqli_fetch_array($search_emailConfig);
  $emailConfigStatus = $fetch_emailConfig['status']; //Activated OR NotActivated
	
	$search_mycompany = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE coopid_instid = '$subscriber' AND status = 'Paid' AND (usage_status = 'Active' OR usage_status = 'Expired') ORDER BY id DESC");
  if(mysqli_num_rows($search_mycompany) == 1)
	{

    $update = mysqli_query($link, "UPDATE saas_subscription_trans SET usage_status = 'Deactivated' WHERE coopid_instid = '$subscriber' AND usage_status = 'Active'") or die ("Error: " . mysqli_error($link));

		$select_is = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE coopid_instid = '$subscriber' AND usage_status = 'Deactivated' ORDER BY id DESC");
		$fetch_is = mysqli_fetch_array($select_is);
		$date_from = $fetch_is['duration_to'];
		$expiry_date = date('Y-m-d', strtotime("+$number_of_months months", strtotime($date_from)));

    $parameter = (explode(',',$others));
    $countNum = count($parameter);

    for($i = 0; $i < $countNum; $i++){

      mysqli_query($link, "UPDATE member_settings SET $parameter[$i] = 'On' WHERE companyid = '$institution_id'");

    }

    $sendSMS->saasSubPmtNotifier($emailReceiver, $refid, $date_from, $expiry_date, $plan_name, $sub_plan, $institution_id, $sub_token, $total_amountpaid, $name, $calc_bonus, $subType, $emailConfigStatus, $fetch_emailConfig);
    
		$insert = mysqli_query($link, "INSERT INTO saas_subscription_trans VALUES(null,'$subscriber','$refid','$sub_plan','$sms_allocated','$sub_token','$total_amountpaid','$date_from','$expiry_date','$expiration_grace','$staff_limit','$branch_limit','$customer_limit','$no_group','$no_lproduct','$no_sproduct','$no_invproduct','Paid','Active','$currenctdate','')") or die ("Error: " . mysqli_error($link));
		($aggr_id == "") ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$walletBal' WHERE id = '$aggr_id'");
		($aggr_id == "") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$refid','Aggregator: $aggr_id','$commValue','','Credit','NGN','COMMISSION','Response: Esusu Africa Platform Commission of NGN$commValue on Subscription as Aggregator','successful','$currenctdate','$aggr_id','$walletBal','')");
	
    if($insert && $update){

    		echo '<meta http-equiv="refresh" content="5;url=paid_sub.php?id='.$_SESSION['tid'].'&&mid=NDIw">';
    		echo '<br>';
    		echo'<span class="itext" style="color: blue">Subscription Initiated Successfully</span>';
    
    }else{
    
    		echo '<meta http-equiv="refresh" content="5;url=add_saassub_pmt.php?id='.$_SESSION['tid'].'&&mid=NDIw">';
    		echo '<br>';
    		echo'<span class="itext" style="color: blue">Error.....Please try again later!</span>';
    
    }

	}
	else{

    $parameter = (explode(',',$others));
    $countNum = count($parameter);

    for($i = 0; $i < $countNum; $i++){

      mysqli_query($link, "UPDATE member_settings SET $parameter[$i] = 'On' WHERE companyid = '$institution_id'");

    }

    $sendSMS->saasSubPmtNotifier($emailReceiver, $refid, $date_from, $expiry_date, $plan_name, $sub_plan, $institution_id, $sub_token, $total_amountpaid, $name, $calc_bonus, $subType, $emailConfigStatus, $fetch_emailConfig);
			
		$insert = mysqli_query($link, "INSERT INTO saas_subscription_trans VALUES(null,'$subscriber','$refid','$sub_plan','$sms_allocated','$sub_token','$total_amountpaid','$date_from','$expiry_date','$expiration_grace','$staff_limit','$branch_limit','$customer_limit','$no_group','$no_lproduct','$no_sproduct','$no_invproduct','Paid','Active','$currenctdate','')") or die ("Error: " . mysqli_error($link));
		($aggr_id == "") ? "" : mysqli_query($link, "UPDATE user SET transfer_balance = '$walletBal' WHERE id = '$aggr_id'");
		($aggr_id == "") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'','$refid','Aggregator: $aggr_id','$commValue','','Credit','NGN','COMMISSION','Response: Esusu Africa Platform Commission of NGN$commValue on Subscription as Aggregator','successful','$currenctdate','$aggr_id','$walletBal','')");

		if($insert){

			echo '<meta http-equiv="refresh" content="5;url=paid_sub.php?id='.$_SESSION['tid'].'&&mid=NDIw">';
			echo '<br>';
			echo'<span class="itext" style="color: blue">Subscription Initiated Successfully1</span>';

		}else{

			echo '<meta http-equiv="refresh" content="5;url=add_saassub_pmt.php?id='.$_SESSION['tid'].'&&mid=NDIw">';
			echo '<br>';
			echo'<span class="itext" style="color: blue">Error.....Please try again later!</span>';

		}

	}

}
?>

<div class="box-body">

<?php
$pcode = $_GET['pcode'];
$detect_subplan = mysqli_query($link, "SELECT * FROM saas_subscription_plan WHERE plan_code = '$pcode'");
$fetch_subplan = mysqli_fetch_array($detect_subplan);
$pcategory = $fetch_subplan['plan_category'];
$search_currency = mysqli_query($link, "SELECT * FROM systemset");
$fetch_currency = mysqli_fetch_object($search_currency);
?>

<input name="plan_code" type="hidden" class="form-control" value="<?php echo $fetch_subplan['plan_code']; ?>" id="HideValueFrank"/>

<input name="plan_name" type="hidden" class="form-control" value="<?php echo $fetch_subplan['plan_name']; ?>" id="HideValueFrank"/>

<input name="amount_per_months" type="hidden" class="form-control" value="<?php echo $fetch_subplan['amount_per_months']; ?>" id="HideValueFrank"/>

<input name="expiration_grace" type="hidden" class="form-control" value="<?php echo $fetch_subplan['expiration_grace']; ?>" id="HideValueFrank"/>

<input name="sms_allocated" type="hidden" class="form-control" value="<?php echo $fetch_subplan['sms_allocated']; ?>" id="HideValueFrank"/>

<input name="staff_limit" type="hidden" class="form-control" value="<?php echo $fetch_subplan['staff_limit']; ?>" id="HideValueFrank"/>

<input name="branch_limit" type="hidden" class="form-control" value="<?php echo $fetch_subplan['branch_limit']; ?>" id="HideValueFrank"/>

<input name="customer_limit" type="hidden" class="form-control" value="<?php echo $fetch_subplan['customers_limit']; ?>" id="HideValueFrank"/>

<input name="others" type="hidden" class="form-control" value="<?php echo $fetch_subplan['others']; ?>" id="HideValueFrank"/>

<input name="no_group" type="hidden" class="form-control" value="<?php echo $fetch_subplan['no_group']; ?>" id="HideValueFrank"/>

<input name="no_lproduct" type="hidden" class="form-control" value="<?php echo $fetch_subplan['no_lproduct']; ?>" id="HideValueFrank"/>

<input name="no_sproduct" type="hidden" class="form-control" value="<?php echo $fetch_subplan['no_sproduct']; ?>" id="HideValueFrank"/>

<input name="no_invproduct" type="hidden" class="form-control" value="<?php echo $fetch_subplan['no_invproduct']; ?>" id="HideValueFrank"/>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Select Subscriber</label>
                  <div class="col-sm-9">
                  <select name="subscriber" class="form-control select2" id="saas_subplan" required>
                  <option selected>Select Subscriber</option>
<?php
if($pcategory == "Institution")
{
  $search_subplan = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved'");
  while($fetch_saasplan = mysqli_fetch_array($search_subplan)){
?>
    <option value="<?php echo $fetch_saasplan['institution_id']; ?>"><?php echo $fetch_saasplan['institution_name']; ?></option>
<?php
}
}
?>
<?php
if($pcategory == "Agent")
{
   $search_subplan1 = mysqli_query($link, "SELECT * FROM agent_data WHERE status = 'Approved'");
  while($fetch_saasplan1 = mysqli_fetch_array($search_subplan1)){
?>
    <option value="<?php echo $fetch_saasplan1['agentid']; ?>"><?php echo $fetch_saasplan1['fname']; ?></option>
<?php
}
}
?>
<?php
/**
if($pcategory == "Cooperatives") 
{
  $search_subplan2 = mysqli_query($link, "SELECT * FROM cooperatives WHERE status = 'Approved'");
  while($fetch_saasplan2 = mysqli_fetch_array($search_subplan2)){
?>
    <option value="<?php echo $fetch_saasplan2['coopid']; ?>"><?php echo $fetch_saasplan2['coopname']; ?></option>
<?php
}
}
*/
?>
<?php
if($pcategory == "Merchant")
{
  $search_subplan2 = mysqli_query($link, "SELECT * FROM merchant_reg WHERE status = 'Active'");
  while($fetch_saasplan2 = mysqli_fetch_array($search_subplan2)){
?>
    <option value="<?php echo $fetch_saasplan2['merchantID']; ?>"><?php echo $fetch_saasplan2['company_name']; ?></option>
<?php
}
}
?>
                  </select>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Plan Name</label>
                  <div class="col-sm-9">
                  <span style="color: orange; font-size: 14px"><b><?php echo $fetch_subplan['plan_name'].' ('.$fetch_subplan['plan_code'].')'; ?></b></span>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Amount (per month)</label>
                  <div class="col-sm-9">
                  <span style="color: orange; font-size: 14px"><b><?php echo $fetch_currency->currency.number_format($fetch_subplan['amount_per_months'],2,'.',','); ?></b></span>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Customers</label>
                  <div class="col-sm-9">
                 <span style="color: orange; font-size: 14px"><b><?php echo number_format($fetch_subplan['customers_limit']); ?></b></span>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Staffs</label>
                  <div class="col-sm-9">
                 <span style="color: orange; font-size: 14px"><b><?php echo number_format($fetch_subplan['staff_limit']); ?></b></span>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">No. of Branches</label>
                  <div class="col-sm-9">
                 <span style="color: orange; font-size: 14px"><b><?php echo number_format($fetch_subplan['branch_limit']); ?></b></span>
                  </div>
                  </div>

  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Other Features</label>
                  <div class="col-sm-9">
                      <?php
                        if($fetch_subplan['others'] == ""){
                            echo 'No Module Assigned!';
                        }
                        else{
                            $explodeVA = explode(",",$fetch_subplan['others']);
                            $countVA = (count($explodeVA) - 1);
                            for($i = 0; $i <= $countVA; $i++){
                                echo '<p style="color: orange; font-size: 14px"><b>'.$i.') '.ucwords(str_replace("_"," ",$explodeVA[$i])).'</b></p>';
                            }
                        }
                        ?>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Starting From</label>
                  <div class="col-sm-9">
                 <input name="dfrom" type="text" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly/>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Number of Month(s)</label>
                  <div class="col-sm-9">
                 <input name="dto" type="number" class="form-control" placeholder="Enter Number of month(s) you wish to subscribe for, in order to use the Software" required/>
                 <span style="color: orange; font-size: 18px;"><b>NOTE:</b> You are to enter the number of months you want to subscribe for here in order to use the software</span>
                  </div>
                  </div>


</div>

<div align="right">
     <div class="box-footer">
        <button name="submit" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-cloud-upload">&nbsp;Proceed</i></button>
     </div>
</div>
 </form>

</div>	
</div>	
</div>
</div>