<div class="box">
	      <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-dollar"></i> New Payment</h3>
            </div>
             <div class="box-body">

			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

<?php
if(isset($_POST['save']))
{
    function myreference($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
    $tid = $_SESSION['tid'];
    $name = mysqli_real_escape_string($link, $_POST['teller']);
    $lid =  $_POST['acte'];
    $refid = uniqid().myreference(10);
    $pay_date = mysqli_real_escape_string($link, $_POST['pay_date']);
    $amount_to_pay = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount_to_pay']));
    $remarks = mysqli_real_escape_string($link, $_POST['remarks']);
    //$ptime = mysqli_real_escape_string($link, $_POST['ptime']);
    $mycurrentTime = date("Y-m-d h:i:s");
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);

    $account_no = mysqli_real_escape_string($link, $_POST['account']);
    $searchin = mysqli_query($link, "SELECT * FROM borrowers WHERE account ='$account_no'");
    $get_searchin = mysqli_fetch_array($searchin);
    $customer = $get_searchin['fname'].' '.$get_searchin['lname'];
    $uname = $get_searchin['username'];
    $phone = $get_searchin['phone'];
    $em = $get_searchin['email'];
    $sms_checker = $get_searchin['sms_checker'];
    $loanBal = $get_searchin['loan_balance'] - $amount_to_pay;
    $branch = $get_searchin['branchid'];

    $search_loaninfo = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid' AND p_status != 'PAID'");
    $get_loaninfo = mysqli_fetch_array($search_loaninfo);
    //$final_amount = $get_loaninfo['amount_topay'];
    $my_balance = number_format($get_loaninfo['balance'],2,'.','');
    $request_id = $get_loaninfo['request_id'];
    $direct_debit_status = $get_loaninfo['direct_debit_status'];

    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    //$sys_abb = $r->abb;
    $sys_email = $r->email;

    $searchRpSch = mysqli_query($link, "SELECT COUNT(*), payment, get_id FROM pay_schedule WHERE lid = '$lid' AND status = 'UNPAID' ORDER BY id ASC");
    $fetchRpSch = mysqli_fetch_array($searchRpSch);
    $psSchNum = $fetchRpSch['COUNT(*)'];
    $expAmt = $fetchRpSch['payment'];
    $loanid = $fetchRpSch['get_id'];
    $myRpId = $fetchRpSch['id'];
    
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    //$sys_abb = $get_sys['abb'];
    $sysabb = $fetch_memset['sender_id'];
    $our_currency = $fetch_memset['currency'];

    $billing_type = $ifetch_maintenance_model['billing_type'];
    $t_perc = $ifetch_maintenance_model['t_charges'];
    $myiwallet_balance = (mysqli_num_rows($isearch_maintenance_model) == 1 && $billing_type != "PAYGException") ? ($iassigned_walletbal - $t_perc) : "";

    $verify_role = mysqli_query($link, "SELECT * FROM till_account WHERE cashier = '$iuid' AND status = 'Active'");
    $numtill = mysqli_num_rows($verify_role);
    $fetch_role = mysqli_fetch_object($verify_role);
    $balance = $fetch_role->balance;
    $commissiontype = $fetch_role->commission_type;
    $commission = ($commissiontype == "Flat") ? $fetch_role->commission : ($fetch_role->commission/100);
    $commission_bal = $fetch_role->commission_balance;
            
    //Calculate Commission Earn By the Staff
    $cal_commission = $commission * $amount_to_pay;
    //Update Default Commission Balance
    $total_commission_bal = ($commission == 0) ? $commission_bal : $cal_commission + $commission_bal;
    //Update Till Balance after posting payment
    $total_tillbal_left = $balance - $amount_to_pay;

    $status = ($allow_auth == "Yes") ? "paid" : "pending";
    $theStatus = "Approved";
    $channel = "Internal";
    $notification = ($allow_auth == "Yes") ? "1" : "0";
    $checksms = ($sms_checker == "No") ? "0" : "1";
    $maintenance_row = mysqli_num_rows($isearch_maintenance_model);
	$sms_rate = $fetchsys_config['fax'];
    $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 

    $final_bal = $my_balance - $amount_to_pay;
    $p_status = ($final_bal <= 0) ? "PAID" : "PART-PAID";

    //Sms notification draft
    $sms = "$sysabb>>>Dear $customer! Your repayment of ".$icurrency.number_format($amount_to_pay,2,'.',',')." with Loan ID: $lid has been initiated successfully. ";
    $sms .= "Your Loan Balance is: ".$icurrency.number_format($final_bal,2,'.',',')." Thanks.";

    $max_per_page = 153;
    $sms_length = strlen($sms);
    $calc_length = ceil($sms_length / $max_per_page);
    $sms_charges = $calc_length * $sms_rate;
    $mybalance = $iwallet_balance - $sms_charges;
    $sms_refid = uniqid("EA-smsCharges-").time();

    if($tpin != $myiepin){

        echo '<span class="alert bg-orange">Opps!...Invalid Transaction Pin, please try again later!!</span>';

    }
    elseif($amount_to_pay > $my_balance){

        echo '<meta http-equiv="refresh" content="2;url=newpayments.php?tid='.$_SESSION['tid'].'&&mid=NDA4">';
        echo '<br>';
        echo '<span class="alert bg-orange">Opps!...Amount to pay is invalid!!</span>';

    }
    elseif($amount_to_pay > $balance && $numtill == 1){

        echo '<span class="alert bg-orange">Opps!...Insufficient fund in till balance!!</span>';

    }
    else{

        //UPDATE pay_schedule
        ($amount_to_pay === "$expAmt") ? mysqli_query($link, "UPDATE pay_schedule SET status = 'PAID', dueStatus = 'Paid' WHERE id = '$myRpId'") : "";
        ($amount_to_pay > $expAmt || $amount_to_pay < $expAmt) ? mysqli_query($link, "INSERT INTO pay_schedule VALUE(null,'$lid','$loanid','$account_no','','$pay_date','$final_bal','$amount_to_pay','PAID','$institution_id','','','$iuid','NotSent','','Paid')") : "";
        
        $mySearchRpSch = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' AND status = 'UNPAID' ORDER BY id ASC");
        if($amount_to_pay > $expAmt || $amount_to_pay < $expAmt){

            while($rowPsSch = mysqli_fetch_array($mySearchRpSch)){

                $RpId = $rowPsSch['id'];
                $RpAmt = $rowPsSch['payment'];
                $calcExpAmt = number_format(($amount_to_pay / $psSchNum),2,'.','');
                $updatedExpAmt = $RpAmt - $calcExpAmt;
                ($updatedExpAmt <= 0) ? mysqli_query($link, "UPDATE pay_schedule SET payment = '$updatedExpAmt', status = 'PAID', dueStatus = 'Paid' WHERE id = '$RpId' AND status = 'UNPAID'") : "";
                ($updatedExpAmt > 0) ? mysqli_query($link, "UPDATE pay_schedule SET payment = '$updatedExpAmt' WHERE id = '$RpId' AND status = 'UNPAID'") : "";

            }

        }
        
        //Charge Hybrid or PayG User
        (mysqli_num_rows($isearch_maintenance_model) == 1 && $billing_type != "PAYGException") ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','self','','$t_perc','Debit','$icurrency','Charges','Description: Service Charge for Repayment of $amount_to_pay','successful','$mycurrentTime','$iuid','$myiwallet_balance','')") : "";
        (mysqli_num_rows($isearch_maintenance_model) == 1 && $billing_type != "PAYGException") ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$institution_id'") : "";
        
        //Deduct customer loan Balance and log payment record
        ($allow_auth == "Yes") ? $update = mysqli_query($link, "UPDATE borrowers SET loan_balance = '$loanBal' WHERE account = '$account_no'") : "";
        ($allow_auth == "Yes") ? $update = mysqli_query($link, "UPDATE loan_info SET balance = '$final_bal', p_status = '$p_status' WHERE lid = '$lid'") or die (mysqli_error($link)) : "";
        $insert = mysqli_query($link, "INSERT INTO payments VALUES(null,'$tid','$lid','$refid','$account_no','$customer','$final_bal','$pay_date','$amount_to_pay','$status','$institution_id','','$isbranchid','$notification','$notification','$checksms')") or die (mysqli_error($link));

        //Balance Till account balance if exist
        ($numtill == 1 && $allow_auth == "Yes") ? $update = mysqli_query($link, "UPDATE till_account SET commission_balance = '$total_commission_bal', balance = '$total_tillbal_left' WHERE cashier = '$iuid'") : "";
        ($numtill == 1 && $allow_auth != "Yes") ? $update = mysqli_query($link, "UPDATE till_account SET balance = '$total_tillbal_left' WHERE cashier = '$iuid'") : "";
        ($numtill == 1) ? $update = mysqli_query($link, "INSERT INTO fund_allocation_history VALUES(null,'$refid','$institution_id','$iuid','$isbranchid','$customer','$iuid','$amount_to_pay','Debit','LOAN_REPAYMENT','$icurrency','$total_tillbal_left','$sms','successful','$date_time')") : "";
        
        ($sms_checker == "No" || $billing_type == "PAYGException" ? "" : ($allow_auth == "Yes" && $debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $sms_refid, $sms_charges, $iuid, $mybalance, $debitWallet) : ($allow_auth == "Yes" && $debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $sms_refid, $sms_charges, $iuid, $mybalance, $debitWallet) : "")));
        ($allow_auth == "Yes") ? $sendSMS->loanRepaymentEmailNotifier($em, $refid, $uname, $mycurrentTime, $theStatus, $channel, $account_no, $customer, $phone, $lid, $icurrency, $amount_to_pay, $final_bal, $iemailConfigStatus, $ifetch_emailConfig) : "";

        echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Customer Loan Balance is: <b style='color: orange;'>".$icurrency.number_format($final_bal,2,'.',',')."</b></p></div>";
        
    }

}
?>

             <div class="box-body">
				
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Loan ID</label>
                  <div class="col-sm-10">
                  <select class="select2" name="acte" style="width: 100%;" required>
        				<option value="" selected="selected">--Select Loan to Pay--</option>
                         <?php
        				$get = mysqli_query($link, "SELECT * FROM loan_info WHERE p_status != 'PAID' AND status != 'Pending' AND branchid = '$institution_id'") or die (mysqli_error($link));
        				while($rows = mysqli_fetch_array($get))
        				{
        				    $baccount = $rows['baccount'];
        				    $get_b = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$baccount' ORDER BY id") or die (mysqli_error($link));
        				    $fetch_getb = mysqli_fetch_array($get_b);
        				    $myb_name = $fetch_getb['lname'].' '.$fetch_getb['fname'];
        				    $ltype = $fetch_getb['loantype'];
        				    echo '<option value="'.$rows['lid'].'">'.$rows['baccount']." - ".$myb_name." [Loan ID: ".$rows['lid'].", | Loan Bal: ".number_format($rows['balance'],2,".",",").']</option>';
        				}
        				?>
                    </select>
                  </div>
                  </div>
				  
				   <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Customer</label>
				 <div class="col-sm-10">
                <select class="customer select2" name="customer" style="width: 100%;" required>
				<option value="" selected="selected">--Select Customer--</option>
                 <?php
				$get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed' order by id") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				echo '<option value="'.$rows['id'].'">'.(($rows['snum'] == "") ? "" : $rows['snum']." => ").$rows['fname'].' '.$rows['lname'].'</option>';
				}
				?>
                </select>
              </div>
			  </div>
				  
		 <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Customer Account#</label>
				 <div class="col-sm-10">
                <select class="account select2" name="account" style="width: 100%;" required>
				<option value="" selected="selected">--Select Customer Account--</option>
                  <?php
				$getin = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed' order by id") or die (mysqli_error($link));
				while($row = mysqli_fetch_array($getin))
				{
				echo '<option value="'.$row['id'].'">'.$row['account'].'</option>';
				}
				?>
                </select>
              </div>
			  </div>
			  
<?php
if($irole === "institution_super_admin")
{
?>
			  
			  <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Payment Date</label>
			 <div class="col-sm-10">
              <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" class="form-control pull-right" name="pay_date" /required>
                </div>
              </div>
			  </div>
			  
<?php
}
else{
?>

<input type="hidden" class="form-control pull-right" name="pay_date" value="<?php echo date('Y-m-d'); ?>" /required>

<?php
}
?>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Amount to Pay</label>
                  <div class="col-sm-10">
                  <input name="amount_to_pay" type="number" class="form-control" placeholder="Amount to Pay" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Posted By</label>
                  <div class="col-sm-10">
                 <?php
$tid = $_SESSION['tid'];
$sele = mysqli_query($link, "SELECT * from user WHERE id = '$tid'") or die (mysqli_error($link));
$row = mysqli_fetch_array($sele)
?>
                  <input name="teller" type="text" class="form-control" value="<?php echo ($row['name'] != true) ? $iname : $row['name'].' '.$row['lname']; ?>" readonly>
                  </div>
                  </div>

				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Transaction Pin</label>
                  <div class="col-sm-10">
                  <input name="tpin" type="password" inputmode="numeric" pattern="[0-9]*" class="form-control" placeholder="Enter your transaction pin" required>
                  </div>
                  </div>

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Make Payment</i></button>

              </div>
			  </div>
			  </form>
			  

           
</div>	
</div>
</div>	
</div>