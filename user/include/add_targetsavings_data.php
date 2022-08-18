<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="my_savings_plan.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo (isset($_GET['Takaful']) ? 'NTA0' : (isset($_GET['Savings']) ? 'NTAw' : 'NDA3')); ?><?php echo ((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Savings'])) ? '&&Savings' : '')); ?>"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-plus"></i> Create Target Savings</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	include ("../config/restful_apicalls.php");
	
	$acn = $_SESSION['acctno'];
	$sname = mysqli_real_escape_string($link, $_POST['sname']);
	$savingsPurpose = mysqli_real_escape_string($link, $_POST['purpose']);
	$otherPurpose = mysqli_real_escape_string($link, $_POST['other_purpose']);
	$purpose = ($savingsPurpose == "Other") ? $otherPurpose : $savingsPurpose;
	$amount = mysqli_real_escape_string($link, $_POST['amount']);
	$interestRate = mysqli_real_escape_string($link, $_POST['interestRate']);
	$interestType = mysqli_real_escape_string($link, $_POST['interestType']);
	$sinterval = mysqli_real_escape_string($link, $_POST['sinterval']);
	$duration = mysqli_real_escape_string($link, $_POST['duration']);
	$smethod = mysqli_real_escape_string($link, $_POST['smethod']);
	$epin = mysqli_real_escape_string($link, $_POST['epin']);
	$new_reference = "EA".date("dy").time();
	$date_time = date("Y-m-d h:i:s");
	$myfullname = $myfn.' '.$myln;
	
	//Calculate Maturity Period
	$maturity_period = ($sinterval == "daily" ? 'day' : ($sinterval == "weekly" ? 'week' : ($sinterval == "monthly" ? 'month' : 'year')));
  	$mature_date = date('Y-m-d h:i:s', strtotime('+'.$duration.' '.$maturity_period, strtotime($date_time)));

	//Calculate Next Payment Date
	$next_pmt_date = date('Y-m-d h:i:s', strtotime('+1 '.$maturity_period, strtotime($date_time)));

	//Calculate Interest
	$total_output = $amount * $duration;
    $calc_interest = ($interestType == "Flat" ? ($interestRate + $total_output) : ($interestType == "Percentage" ? ((($interestRate / 100) * $total_output) + $total_output) : "Undefine"));
    
    $search_myinst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$bbranchid'");
    $fetch_myinst = mysqli_fetch_array($search_myinst);
    $iofficial_email = $fetch_myinst['official_email'];
    $creatorEmail = $iofficial_email.','.$email2;
    $converted_date = date('m/d/Y').' '.(date('h') + 1).':'.date('i a');
    
    if($myuepin != $epin){
	    
	    echo "<script>alert('Invalid Transaction Pin!...Please try again later!!'); </script>";
	    
	}
	elseif($smethod == "card"){

		$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'paymentplans'");
		$fetch_restapi = mysqli_fetch_object($search_restapi);
		$api_url = $fetch_restapi->api_url;

		// Pass the plan's name, interval and amount
		$postdata =  array(
			'amount'=> $amount,
			'name'	=> $sname,
			'interval'	=> $sinterval,
			'duration'	=> $duration,
			'seckey'	=> $brave_secret_key
		);
		
		$make_call = callAPI('POST', $api_url, json_encode($postdata));
		$response = json_decode($make_call, true);

		if($response['status'] == "success"){
		
			//Get the Plan code from Flutterwave API
			$plan_id = $response['data']['id'];
			$plan_code = $response['data']['plan_token'];
			$date_time = date("Y-m-d h:i:s");
			
			//LOG THE SAVINGS PLAN AND IT SUBSCRIPTION RECORD WITH PENDING STATUS
			mysqli_query($link, "INSERT INTO target_savings VALUES(null,'$acn','$plan_id','$plan_code','$sname','$purpose','$amount','$bbcurrency','$sinterval','$duration','$interestType','$interestRate','','Pending','$date_time','','$bsbranchid','$bbranchid','$bAcctOfficer','$iupfront_payment')") or die ("Error: " . mysqli_error($link));
			mysqli_query($link, "INSERT INTO savings_subscription VALUES(null,'$bbranchid','$purpose','$plan_code','$plan_code','$plan_id','$plan_id','$amount','$bbcurrency','$sinterval','$duration','','$acctno','Pending','$date_time','$bsbranchid','','','','','Auto','Savings','$amount','0','0','$bAcctOfficer','Yes','No','Target')");

			echo "<script>alert('Savings Created Successfully! Click OK to proceed with your first payment!!'); </script>";
			echo "<script>window.location='tokenize_card.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=".((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Health'])) ? 'MTAwMA==' : ((isset($_GET['Savings'])) ? 'NTAw' : (isset($_GET['Donation']) ? 'NjA0' : 'NDA3'))))."".((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : ''))))."&&pcode=".$plan_code."'; </script>";

		}else{
		
			$message = $response['message'];
			echo "<script>alert('$message \\nPlease try another one'); </script>";

		}

	}elseif($smethod == "wallet" && $bwallet_balance >= $amount){
	    
		$real_subscription_code = "uSub".date("dy").time();
		$real_invoice_code = date("dYi").time();
		$plan_id = rand(10000000,99999999);
		$plan_code = uniqid("rpp_").random_strings(7);
		$paymenttype = "Wallet Transfer";
		$real_status = "successful";
		$senderBalance = $bwallet_balance - $amount;
		$myTargetSavingsBal = $btargetsavings_bal + $amount;
		$customer = $bvirtual_acctno;
		$mybank_name = "Wallet Transfer";
		$account_number = ccMasking($bvirtual_acctno);
		$b_name = $bname;

		$search_merchant = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$bbranchid' AND (role = 'agent_manager' OR role = 'institution_super_admin' OR role = 'merchant_super_admin')");
        $fetch_merchant = mysqli_fetch_array($search_merchant);
        $merchantIuid = $fetch_merchant['id'];
        $merchantBal = $fetch_merchant['transfer_balance'] + $amount;
		
		//Email Notification Service
        $sendSMS->productPaymentNotifier($creatorEmail, $converted_date, $new_reference, $customer, $myfullname, $real_subscription_code, $plan_code, $purpose, $sname, $mybank_name, $account_number, $b_name, $bbcurrency, $amount, $emailConfigStatus, $fetch_emailConfig);

		//UPDATE CUSTOMER BALANCE
		mysqli_query($link, "UPDATE borrowers SET wallet_balance = '$senderBalance', target_savings_bal = '$myTargetSavingsBal' WHERE account = '$acctno'");
		mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$bbranchid','$new_reference','Target Savings Subscription','','$amount','Debit','$bbcurrency','TARGET_SAVINGS','Payment for the subscription of Product Name: $sname','successful','$date_time','$acctno','$senderBalance','')") or die ("Error4: " . mysqli_error($link));
        
		//UPDATE MERCHANT BALANCE
		mysqli_query($link, "UPDATE user SET transfer_balance = '$merchantBal' WHERE id = '$merchantIuid'");
		mysqli_query($link, "INSERT INTO wallet_history VALUES(null,'$bbranchid','$new_reference','Target Savings Sub from: $acctno','$amount','','Credit','$bbcurrency','TARGET_SAVINGS','Payment for the subscription of Product Name: $sname','successful','$date_time','$bAcctOfficer','$merchantBal','')") or die ("Error4: " . mysqli_error($link));

		//LOG THE SAVINGS PLAN AND THE TRANSACTION RECORD
		mysqli_query($link, "INSERT INTO target_savings VALUES(null,'$acctno','$plan_id','$plan_code','$sname','$purpose','$amount','$bbcurrency','$sinterval','$duration','$interestType','$interestRate','$real_invoice_code','Approved','$date_time','$mature_date','$bsbranchid','$bbranchid','$bAcctOfficer','$iupfront_payment')") or die ("Error: " . mysqli_error($link));
		mysqli_query($link, "INSERT INTO savings_subscription VALUES(null,'$bbranchid','$purpose','$plan_code','$plan_code','$plan_id','$plan_id','$amount','$bbcurrency','$sinterval','$duration','$real_subscription_code','$acctno','Approved','$date_time','$bsbranchid','$mature_date','$calc_interest','$new_reference','$next_pmt_date','Manual','Savings','$amount','0','0','$bAcctOfficer','Yes','No')");
		mysqli_query($link, "INSERT INTO all_savingssub_transaction VALUES(null,'$bbranchid','$acctno','$real_invoice_code','$real_subscription_code','$new_reference','$bbcurrency','$amount','$real_status','$date_time','NONE','$myfn','$myln','NONE','NONE','NONE','NONE','$paymenttype','NG','$plan_code','$bsbranchid','$bAcctOfficer')");
        
		echo "<script>alert('Savings Created Successfully!'); </script>";
		echo "<script>window.location='my_savings_plan.php?tid=".$_SESSION['tid']."&&acn=".$_SESSION['acctno']."&&mid=".((isset($_GET['Takaful'])) ? 'NTA0' : ((isset($_GET['Health'])) ? 'MTAwMA==' : ((isset($_GET['Savings'])) ? 'NTAw' : (isset($_GET['Donation']) ? 'NjA0' : 'NDA3'))))."".((isset($_GET['Takaful'])) ? '&&Takaful' : ((isset($_GET['Health'])) ? '&&Health' : ((isset($_GET['Savings'])) ? '&&Savings' : ((isset($_GET['Donation'])) ? '&&Donation' : ''))))."'; </script>";

	}else{

		echo "<script>alert('Sorry! You have insufficient fund in your wallet to proceed!!'); </script>";

	}
	
}
?>        

			<form class="form-horizontal" method="post" enctype="multipart/form-data">

            <div class="box-body">
			
			<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Savings Name</label>
                  <div class="col-sm-6">
                  <input name="sname" type="text" class="form-control" placeholder="Savings Name" /required>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Purpose</label>
                <div class="col-sm-6">
        		<select name="purpose" class="form-control select2" id="plan_category" required>
                    <option value="" selected='selected'>Select Purpose&hellip;</option>
                    <option value="Acquire Property">Acquire Property</option>
	                <option value="Appliances/Electronic Gadgets">Appliances/Electronic Gadgets</option>
	                <option value="Build Property">Build Property</option>
	                <option value="Car Purchase/Repairs">Car Purchase/Repairs</option>
	                <option value="Debt Consolidation">Debt Consolidation</option>
	                <option value="Expand Business">Expand Business</option>
	                <option value="Fashion Goods">Fashion Goods</option>
	                <option value="Funeral Expenses">Funeral Expenses</option>
	                <option value="Home Improvements">Home Improvements</option>
	                <option value="Medical Expenses">Medical Expenses</option>
	                <option value="Personal Emergency">Personal Emergency</option>
	                <option value="Portable Goods">Portable Goods</option>
	                <option value="Rent">Rent</option>
	                <option value="School Fees/Educational Expenses">School Fees/Educational Expenses</option>
	                <option value="Start a Business">Start a Business</option>
	                <option value="Travel/Holiday">Travel/Holiday</option>
	                <option value="Pilgrimage">Pilgrimage</option>
	                <option value="Wedding/Event">Wedding/Event</option>
	                <option value="Birthday">Birthday</option>
                    <option value="Other">Other</option>
        		</select>
		    	</div>
		    	<label for="" class="col-sm-3 control-label"></label>
		    </div>

		    <span id='ShowValueFrank'></span>

		    <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount</label>
                  <div class="col-sm-6">
                  <input name="amount" type="number" class="form-control" placeholder="Enter Amount to be Saving" /required>
                  </div>
				  <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Interest</label>
				<div class="col-sm-1">
					<input name="interestType" type="hidden" class="form-control" value="<?php echo $ts_roi_type; ?>"/>
					<input name="interestRate" type="number" class="form-control" value="<?php echo ($ts_roi == "Ratio") ? "Undefine" : $ts_roi; ?>" placeholder="Interest Rate" readonly/>
				</div>
				<label class="control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><?php echo ($ts_roi_type == "Percentage" ? "Percent" : ($ts_roi_type == "Flat" ? "Flat" : "TBD")); ?><label>
            </div>
			
			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Savings Interval</label>
                <div class="col-sm-6">
                  <select name="sinterval" class="select2" style="width: 100%;" id="savings_interval" /required>
					<option value="" selected="selected">---Choose Interval for Savings---</option>
					<option value="daily">Daily</option>
					<option value="weekly">Weekly</option>
					<option value="monthly">Monthly</option>
					<option value="yearly">Yearly</option>
                  </select>
				</div>
				<label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Duration</label>
				<div class="col-sm-1">
					<input name="duration" type="number" class="form-control" value="<?php echo $default_duration; ?>" placeholder="Enter Duration" required/>
				</div>
				<label class="control-label" id="mirror_interval" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">-frequency-<label>
            </div>

            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Savings Method</label>
                  <div class="col-sm-6">
				  	<select name="smethod" class="form-control select2" style="width: 100%;" /required>
						<option value="" selected="selected">---Choose Savings Method---</option>
						<option value="card">card</option>
						<option value="wallet">wallet</option>
					</select>
                  	<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">NOTE: Card savings is authomated based on the frequency selected for recurring payment.</span>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                </div>
                
            <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin</label>
                  <div class="col-sm-6">
                  <input name="epin" type="password" class="form-control" placeholder="Transaction Pin" maxlength="4" required>
                  </div>
                  <label for="" class="col-sm-3 control-label"></label>
                  </div>
			
			 </div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"></label>
                <div class="col-sm-6">
                    <button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-forward">&nbsp;Proceed</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>