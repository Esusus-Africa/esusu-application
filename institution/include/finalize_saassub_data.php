<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-globe"></i>  Subscription Form</h3>
            </div>

             <div class="box-body">

          <div class="slideshow-container">
          <div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" style="font-size:17px;" align="center">
              <p>
                  <?php
                  //echo $walletafrica_status;
                  if($mo_virtualacct_status == "Active" && ($walletafrica_status == "NotActive" || $walletafrica_status == "Active")){
                      
                      include("../config/monnify_virtualaccount.php");
                      
                  }
                  elseif($mo_virtualacct_status == "NotActive" && $walletafrica_status == "Active"){
                      
                      include("../config/walletafrica_restfulapis_call.php");
                      include("walletafrica_virtulaccount.php");
                      
                  }
                  ?>
              </p>
          </div>

          <a class="myprev" onclick="plusSlides(-1)">&#10094;</a>
          <a class="mynext" onclick="plusSlides(1)">&#10095;</a>
              
          </div>

              
<form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['submit']))
{
  //Basic Parameters
  $sub_token = "Tkn_".random_strings(12);
  $sub_plan = mysqli_real_escape_string($link, $_POST['plan_code']);
	$plan_name = mysqli_real_escape_string($link, $_POST['plan_name']);
	$refid = mysqli_real_escape_string($link, $_POST['refid']);
	$sms_allocated = mysqli_real_escape_string($link, $_POST['sms_allocated']);
	$amount_paid_per_months = $_POST['amount_per_months'];
	$expiration_grace = mysqli_real_escape_string($link, $_POST['expiration_grace']);
  $paymentMethod = mysqli_real_escape_string($link, $_POST['paymentMethod']);

  //Subscription Plan Config
	$staff_limit = mysqli_real_escape_string($link, $_POST['staff_limit']);
	$branch_limit = mysqli_real_escape_string($link, $_POST['branch_limit']);
	$customer_limit = mysqli_real_escape_string($link, $_POST['customer_limit']);
	$no_group = mysqli_real_escape_string($link, $_POST['no_group']);
	$no_lproduct = mysqli_real_escape_string($link, $_POST['no_lproduct']);
	$no_sproduct = mysqli_real_escape_string($link, $_POST['no_sproduct']);
	$no_invproduct = mysqli_real_escape_string($link, $_POST['no_invproduct']);
	
  //Coupon Code
	$ccode = mysqli_real_escape_string($link, $_POST['ccode']);

  //Subscription Duration Date
	$date_from = mysqli_real_escape_string($link, $_POST['dfrom']);
	$number_of_months = mysqli_real_escape_string($link, $_POST['dto']);
	$expiry_date = date('Y-m-d', strtotime("+$number_of_months months", strtotime($date_from)));

	//Calculate Amount to pay per year/month/day
	$total_amountpaid = ($amount_paid_per_months * $number_of_months);
  $mywalletBal = $iwallet_balance - $total_amountpaid;
  
  //Demo Duration Settings
  $demo_type = $fetchsys_config['demo_type'];
	$demo_rate = $fetchsys_config['demo_rate'];
	$currenctdate = date("Y-m-d h:i:s");

  $search_mycompany = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE coopid_instid = '$institution_id' AND status = 'Paid' AND (usage_status = 'Active' OR usage_status = 'Expired') ORDER BY id DESC");

	$search_sub = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE coopid_instid = '$institution_id' AND amount_paid = '0'");
	$sub_num = mysqli_num_rows($search_sub);

  //Coupon Verification
  $verify_coupon = mysqli_query($link, "SELECT * FROM coupon_setup WHERE coupon_code = '$ccode'");
  $fetch_coupon_details = mysqli_fetch_object($verify_coupon);
	$ctype = $fetch_coupon_details->coupon_type;
  $dto = $fetch_coupon_details->start_date;
	$enddate = $fetch_coupon_details->end_date;
	$max_r = $fetch_coupon_details->max_redemption;
	$count = $fetch_coupon_details->redemption_count;
  $amt_type = $fetch_coupon_details->amt_type;

  //process email and aggregator commission
  $aggr_id = $fetch_inst['aggr_id'];
  $name = $fetch_inst['institution_name'];
  $instEmail = ($fetch_inst['official_email'] == "") ? "" : ",".$fetch_inst['official_email'];
  
  $search_aggr = mysqli_query($link, "SELECT * FROM aggregator WHERE aggr_id = '$aggr_id'");
  $checkAggr = mysqli_num_rows($search_aggr);
  $aggrEmail = ($checkAggr['email'] == "") ? "" : ",".$checkAggr['email'];
  $commType = $checkAggr['aggr_co_type'];
  $commRate = $checkAggr['aggr_co_rate'];
  $commValue = ($commType == "Percentage") ? (($commRate / 100) * $total_amountpaid) : $commRate;
  $walletBal = $checkAggr['wallet_balance'] + $commValue;
  $subType = "Saas Subscription";
  
  $mydetect_subplan = mysqli_query($link, "SELECT * FROM saas_subscription_plan WHERE plan_code = '$sub_plan'");
  $myfetch_subplan = mysqli_fetch_array($mydetect_subplan);
  $myothers = $myfetch_subplan['others'];
  $parameter = (explode(',',$myothers));
  $countNum = count($parameter);

  $emailReceiver = $fetchsys_config['email'].$instEmail.$aggrEmail;

  if($amount_paid_per_months == "0" && $sub_num == 1)
	{

    echo "<div class='alert bg-orange'>Sorry.....You only Subscribe to Demo Plan Ones!</div>";

	}
  elseif($paymentMethod == "wallet" && $total_amountpaid > $iwallet_balance){

    echo "<div class='alert bg-orange'>Sorry.....You have insufficient fund in your wallet!!</div>";

  }
  elseif(mysqli_num_rows($verify_coupon) == 0 && $ccode != "")
  {

    echo "<div class='alert bg-orange'>Invalid Coupon Entered!</div>";
  
  }
  elseif(mysqli_num_rows($verify_coupon) == 1 && $max_r == $count && $ccode != "") {

    echo "<div class='alert bg-orange'>Sorry! Coupon has expired!!</div>";
  
  }
  elseif($amount_paid_per_months == "0" && $sub_num == 0)
	{

		$sub_token = "DemoTkn_".random_strings(12);
		$demo_date = strtotime("+$demo_rate $demo_type", time());
		$expiry_date = date('Y-m-d', $demo_date);
        $subType = "Demo Saas Subscription";
        $calc_bonus = 0;
        
        for($i = 0; $i < $countNum; $i++){
    
            mysqli_query($link, "UPDATE member_settings SET $parameter[$i] = 'On' WHERE companyid = '$institution_id'");
    
        }
        
    	$insert = mysqli_query($link, "INSERT INTO saas_subscription_trans VALUES(null,'$institution_id','$refid','$sub_plan','$sms_allocated','$sub_token','$total_amountpaid','$date_from','$expiry_date','$expiration_grace','$staff_limit','$branch_limit','$customer_limit','$no_group','$no_lproduct','$no_sproduct','$no_invproduct','Paid','Active','$currenctdate','')") or die ("Error: " . mysqli_error($link));
    
        $sendSMS->saasSubPmtNotifier($emailReceiver, $refid, $date_from, $expiry_date, $plan_name, $sub_plan, $institution_id, $sub_token, $total_amountpaid, $name, $calc_bonus, $subType, $iemailConfigStatus, $ifetch_emailConfig);
        echo "<div class='alert bg-blue'>Demo Subscription Initiated Successfully...</div>";
        echo '<meta http-equiv="refresh" content="2;url=saassub_history.php?id='.$_SESSION['tid'].'&&mid=NDIw">';

    }
  elseif($ccode == "" && $amount_paid_per_months != "0" && (($paymentMethod == "wallet" && $total_amountpaid < $iwallet_balance) || $paymentMethod == "card"))
	{

    $calc_bonus = 0;
    ($paymentMethod == "wallet" && $total_amountpaid < $iwallet_balance) ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mywalletBal' WHERE institution_id = '$institution_id'") : "";
    ($paymentMethod == "wallet" && $total_amountpaid < $iwallet_balance) ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','Saas Sub. Plan: $sub_plan','','$total_amountpaid','Debit','$icurrency','Saas_Subscription','SMS Content: Payment for saas subscription plan of: $sub_plan with Sub Token: $sub_token','successful','$currenctdate','$iuid','$mywalletBal','')") : "";
	 (mysqli_num_rows($search_mycompany) == 1) ? mysqli_query($link, "UPDATE saas_subscription_trans SET usage_status = 'Deactivated' WHERE coopid_instid = '$institution_id' AND usage_status = 'Active'") or die ("Error: " . mysqli_error($link)) : "";

		(mysqli_num_rows($search_mycompany) == 1) ? $select_is = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE coopid_instid = '$institution_id' AND usage_status = 'Deactivated' ORDER BY id DESC") : "";
		(mysqli_num_rows($search_mycompany) == 1) ? $fetch_is = mysqli_fetch_array($select_is) : "";
		(mysqli_num_rows($search_mycompany) == 1) ? $date_from = $fetch_is['duration_to'] : "";
		(mysqli_num_rows($search_mycompany) == 1) ? $expiry_date = date('Y-m-d', strtotime("+$number_of_months months", strtotime($date_from))) : "";

		($paymentMethod == "card") ? mysqli_query($link, "INSERT INTO saas_subscription_trans VALUES(null,'$institution_id','','$sub_plan','$sms_allocated','$sub_token','$total_amountpaid','$date_from','$expiry_date','$expiration_grace','$staff_limit','$branch_limit','$customer_limit','$no_group','$no_lproduct','$no_sproduct','$no_invproduct','Pending','NotActive','$currenctdate','')") or die ("Error: " . mysqli_error($link)) : "";
    ($paymentMethod == "wallet" && $total_amountpaid < $iwallet_balance) ? mysqli_query($link, "INSERT INTO saas_subscription_trans VALUES(null,'$institution_id','$refid','$sub_plan','$sms_allocated','$sub_token','$total_amountpaid','$date_from','$expiry_date','$expiration_grace','$staff_limit','$branch_limit','$customer_limit','$no_group','$no_lproduct','$no_sproduct','$no_invproduct','Paid','Active','$currenctdate','')") or die ("Error: " . mysqli_error($link)) : "";

    //Remit Aggregator Commission
    ($aggr_id == "" || $commRate == "0") ? "" : mysqli_query($link, "UPDATE aggregator SET wallet_balance = '$walletBal' WHERE aggr_id = '$aggr_id'");
	  ($aggr_id == "" || $commRate == "0") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','Aggregator: $aggr_id','$commValue','','Credit','NGN','COMMISSION','Response: Esusu Africa Platform Commission of NGN$commValue on Subscription as Aggregator','successful','$currenctdate','$aggr_id','$walletBal','')");
    
    if($paymentMethod == "wallet" && $total_amountpaid <= $iwallet_balance){
        
        for($i = 0; $i < $countNum; $i++){
    
            mysqli_query($link, "UPDATE member_settings SET $parameter[$i] = 'On' WHERE companyid = '$institution_id'");
    
        }
        
    }
    
    echo ($paymentMethod == "wallet" && $total_amountpaid < $iwallet_balance) ? $sendSMS->saasSubPmtNotifier($emailReceiver, $refid, $date_from, $expiry_date, $plan_name, $sub_plan, $institution_id, $sub_token, $total_amountpaid, $name, $calc_bonus, $subType, $iemailConfigStatus, $ifetch_emailConfig) : "";
    echo ($paymentMethod == "card") ? "<div class='alert bg-blue'>Subscription Initiated Successfully.....Please wait patiently to finalize your payment!!</div>" : "";
    echo ($paymentMethod == "wallet" && $total_amountpaid < $iwallet_balance) ? "<div class='alert bg-blue'>Subscription Activated Successfully!!</div>" : "";
    echo ($paymentMethod == "card") ? '<meta http-equiv="refresh" content="2;url=finalize_subpay.php?id='.$_SESSION['tid'].'&&token='.$sub_token.'&&mid=NDIw">' : "";
    echo ($paymentMethod == "wallet" && $total_amountpaid < $iwallet_balance) ? '<meta http-equiv="refresh" content="2;url=saassub_history.php?id='.$_SESSION['tid'].'&&mid=NDIw">' : "";

	}
  elseif($ccode != "" && $amount_paid_per_months != "0" && (($paymentMethod == "wallet" && $total_amountpaid < $iwallet_balance) || $paymentMethod == "card"))
  {

    $verify_sub = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE coopid_instid = '$institution_id' AND sub_token = '$ccode'");

		//NEW METHODS 1
		$date = date("Y-m-d h:i:s", strtotime($dto));
		$now = time(); // or your date as well
		$your_date = strtotime($date);
		$datediff = $your_date - $now;

		//NEW METHODS 2
		$date2 = date("Y-m-d h:i:s", strtotime($enddate));
		$now2 = time(); // or your date as well
		$your_date2 = strtotime($date);
		$datediff2 = $your_date2 - $now2;

    if($datediff >= 1)
		{

      echo "<div class='alert bg-orange'>Sorry! It not yet time to use the coupon!!</div>";
		
    }
		elseif($datediff2 <= 0)
		{

      echo "<div class='alert bg-orange'>Sorry! The Coupon you are trying to apply has expired!!</div>";
	  
    }
    elseif(mysqli_num_rows($verify_sub) == 1){

      echo "<div class='alert bg-orange'>Sorry! Coupon has already been used!!</div>";
    
    }
    else{

      $rate = $fetch_coupon_details->rate;
      $calc_bonus = ($rate/100) * $total_amountpaid;
      $actual_price = ($amt_type == "percent_off") ? ($total_amountpaid - $calc_bonus) : ($total_amountpaid - $rate);
      $total_count = $count + 1;
      $mywalletBal = $iwallet_balance - $actual_price;
      $commValue = ($commType == "Percentage") ? (($commRate / 100) * $actual_price) : $commRate;
      $walletBal = $checkAggr['wallet_balance'] + $commValue;
      
      $update = mysqli_query($link, "UPDATE coupon_setup SET redemption_count = '$total_count' WHERE coupon_code = '$ccode'");
			
      ($paymentMethod == "wallet" && $total_amountpaid < $iwallet_balance) ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$mywalletBal' WHERE institution_id = '$institution_id'") : "";
      ($paymentMethod == "wallet" && $total_amountpaid < $iwallet_balance) ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','Saas Sub. Plan: $sub_plan','','$total_amountpaid','Debit','$icurrency','Saas_Subscription','SMS Content: Payment for saas subscription plan of: $sub_plan with Sub Token: $sub_token','successful','$currenctdate','$iuid','$mywalletBal','')") : "";
			(mysqli_num_rows($search_mycompany) == 1) ? mysqli_query($link, "UPDATE saas_subscription_trans SET usage_status = 'Deactivated' WHERE coopid_instid = '$institution_id' AND usage_status = 'Active'") or die ("Error: " . mysqli_error($link)) : "";

      (mysqli_num_rows($search_mycompany) == 1) ? $select_is = mysqli_query($link, "SELECT * FROM saas_subscription_trans WHERE coopid_instid = '$institution_id' AND usage_status = 'Deactivated' ORDER BY id DESC") : "";
      (mysqli_num_rows($search_mycompany) == 1) ? $fetch_is = mysqli_fetch_array($select_is) : "";
      (mysqli_num_rows($search_mycompany) == 1) ? $date_from = $fetch_is['duration_to'] : "";
      (mysqli_num_rows($search_mycompany) == 1) ? $expiry_date = date('Y-m-d', strtotime("+$number_of_months months", strtotime($date_from))) : "";

		  ($paymentMethod == "card") ? mysqli_query($link, "INSERT INTO saas_subscription_trans VALUES(null,'$institution_id','','$sub_plan','$sms_allocated','$ccode','$actual_price','$date_from','$expiry_date','$expiration_grace','$staff_limit','$branch_limit','$customer_limit','$no_group','$no_lproduct','$no_sproduct','$no_invproduct','Pending','NotActive','$currenctdate','$ccode')") or die ("Error: " . mysqli_error($link)) : "";
      ($paymentMethod == "wallet" && $total_amountpaid < $iwallet_balance) ? mysqli_query($link, "INSERT INTO saas_subscription_trans VALUES(null,'$institution_id','$refid','$sub_plan','$sms_allocated','$ccode','$actual_price','$date_from','$expiry_date','$expiration_grace','$staff_limit','$branch_limit','$customer_limit','$no_group','$no_lproduct','$no_sproduct','$no_invproduct','Paid','Active','$currenctdate','$ccode')") or die ("Error: " . mysqli_error($link)) : "";

      //Remit Aggregator Commission
      ($aggr_id == "" || $commRate == "0") ? "" : mysqli_query($link, "UPDATE aggregator SET wallet_balance = '$walletBal' WHERE aggr_id = '$aggr_id'");
      ($aggr_id == "" || $commRate == "0") ? "" : mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','Aggregator: $aggr_id','$commValue','','Credit','NGN','COMMISSION','Response: Esusu Africa Platform Commission of NGN$commValue on Subscription as Aggregator','successful','$currenctdate','$aggr_id','$walletBal','')");
      
        if($paymentMethod == "wallet" && $total_amountpaid <= $iwallet_balance){
        
            for($i = 0; $i < $countNum; $i++){
        
                mysqli_query($link, "UPDATE member_settings SET $parameter[$i] = 'On' WHERE companyid = '$institution_id'");
        
            }
        
        }
      
      echo ($paymentMethod == "wallet" && $total_amountpaid < $iwallet_balance) ? $sendSMS->saasSubPmtNotifier($emailReceiver, $refid, $date_from, $expiry_date, $plan_name, $sub_plan, $institution_id, $sub_token, $total_amountpaid, $name, $calc_bonus, $subType, $iemailConfigStatus, $ifetch_emailConfig) : "";
      echo ($paymentMethod == "card") ? "<div class='alert bg-blue'>Subscription Initiated Successfully.....Please wait patiently to finalize your payment!!</div>" : "";
      echo ($paymentMethod == "wallet" && $total_amountpaid < $iwallet_balance) ? "<div class='alert bg-blue'>Subscription Activated Successfully!!</div>" : "";
      echo ($paymentMethod == "card") ? '<meta http-equiv="refresh" content="2;url=finalize_subpay.php?id='.$_SESSION['tid'].'&&token='.$ccode.'&&subAmt='.base64_encode($total_amountpaid).'&&mid=NDIw">' : "";
      echo ($paymentMethod == "wallet" && $total_amountpaid < $iwallet_balance) ? '<meta http-equiv="refresh" content="2;url=saassub_history.php?id='.$_SESSION['tid'].'&&mid=NDIw">' : "";

    }

  }
  else{

    echo "<div class='alert bg-orange'>Network Error....Please try again later!!</div>";

  }

}
?>


<div class="box-body">
<?php
$pcode = $_GET['pcode'];
$detect_subplan = mysqli_query($link, "SELECT * FROM saas_subscription_plan WHERE plan_code = '$pcode'");
$fetch_subplan = mysqli_fetch_array($detect_subplan);
$amount_per_months = $fetch_subplan['amount_per_months'];
$demo_type = $fetchsys_config['demo_type'];
$demo_rate = $fetchsys_config['demo_rate'];
?>

<input name="plan_code" type="hidden" class="form-control" value="<?php echo $fetch_subplan['plan_code']; ?>" id="HideValueFrank"/>

<input name="plan_name" type="hidden" class="form-control" value="<?php echo $fetch_subplan['plan_name']; ?>" id="HideValueFrank"/>

<input name="amount_per_months" type="hidden" class="form-control" value="<?php echo $fetch_subplan['amount_per_months']; ?>" id="HideValueFrank"/>

<input name="expiration_grace" type="hidden" class="form-control" value="<?php echo $fetch_subplan['expiration_grace']; ?>" id="HideValueFrank"/>

<input name="sms_allocated" type="hidden" class="form-control" value="<?php echo $fetch_subplan['sms_allocated']; ?>" id="HideValueFrank"/>

<input name="staff_limit" type="hidden" class="form-control" value="<?php echo $fetch_subplan['staff_limit']; ?>" id="HideValueFrank"/>

<input name="branch_limit" type="hidden" class="form-control" value="<?php echo $fetch_subplan['branch_limit']; ?>" id="HideValueFrank"/>

<input name="customer_limit" type="hidden" class="form-control" value="<?php echo $fetch_subplan['customers_limit']; ?>" id="HideValueFrank"/>

<input name="no_group" type="hidden" class="form-control" value="<?php echo $fetch_subplan['no_group']; ?>" id="HideValueFrank"/>

<input name="no_lproduct" type="hidden" class="form-control" value="<?php echo $fetch_subplan['no_lproduct']; ?>" id="HideValueFrank"/>

<input name="no_sproduct" type="hidden" class="form-control" value="<?php echo $fetch_subplan['no_sproduct']; ?>" id="HideValueFrank"/>

<input name="no_invproduct" type="hidden" class="form-control" value="<?php echo $fetch_subplan['no_invproduct']; ?>" id="HideValueFrank"/>

<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount (per month)</label>
                  <div class="col-sm-9">
                  <span style="color: orange; font-size: 21px"><b><?php echo $icurrency.number_format($fetch_subplan['amount_per_months'],2,'.',','); ?></b></span>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">SMS Allocated</label>
                  <div class="col-sm-9">
                 <span style="color: orange; font-size: 21px"><b><?php echo number_format($fetch_subplan['sms_allocated']); ?></b> <i>Bonus</i></span>
                  </div>
                  </div>

  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Other Features</label>
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
<?php
if($amount_per_months == "0")
{
?>

<input name="ccode" type="hidden" class="form-control" placeholder="Enter Coupon Code" />
  
 <?php
}
else{
?>
  <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Coupon Code</label>
                  <div class="col-sm-9">
                 <input name="ccode" type="text" class="form-control" placeholder="Enter Coupon Code" />
                 <span style="color: orange; font-size: 15px;">You can enter <b>coupon code</b> here if available</span>
                  </div>
                  </div>
<?php } ?>

 <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Starting From</label>
                  <div class="col-sm-9">
                 <input name="dfrom" type="text" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly/>
                  </div>
                  </div>
<?php
if($amount_per_months == "0")
{
  $refid = uniqid('Demo').time();
?>
<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Number of <?php echo ($demo_type == 'day') ? 'Day(s)' : 'Month(s)'; ?></label>
                  <div class="col-sm-9">
                   <input name="dto" type="hidden" class="form-control" value="<?php echo $demo_rate; ?>"/>
                   <input name="refid" type="hidden" class="form-control" value="<?php echo $refid; ?>"/>
                   <span style="color: orange; font-size: 18px;"><b>NOTE:</b> that this subscription plan last for just <span style="color: blue;"><?php echo $demo_rate; ?> <?php echo ($demo_type == 'day') ? 'Day(s)' : 'Month(s)'; ?></span> with limited features as described above.</span>
                 </div>
               </div>
 <?php
}
else{
  $refid = uniqid('SaaSub').time();
?>
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Number of Month(s)</label>
                  <div class="col-sm-9">
                 <input name="dto" type="number" class="form-control" placeholder="Enter Number of month(s) you wish to subscribe for, in order to use the Software" required/>
                 <input name="refid" type="hidden" class="form-control" value="<?php echo $refid; ?>"/>
                 <span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>; font-size: 18px;"><b>NOTE:</b> You are to enter the number of months you want to subscribe for here in order to use the software</span>
                  </div>
                  </div>
<?php
}
?>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Payment Method</label>
                  <div class="col-sm-9">
                  <select name="paymentMethod" class="form-control select2" required>
                    <option value="" selected>Select Payment Method</option>
                    <option value="wallet">wallet</option>
                    <option value="card">card</option>
                  </select>
                  </div>
                  </div>



</div>

<div align="right">
     <div class="box-footer">
        <button name="submit" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-cloud-upload">&nbsp;Proceed</i></button>

     </div>
</div>
 </form>

</div>	
</div>	
</div>
</div>