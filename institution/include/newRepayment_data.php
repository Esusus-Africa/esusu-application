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
    $refid = uniqid().myreference(10);
    $lid = mysqli_real_escape_string($link, $_POST['acte']);
    $account_no = mysqli_real_escape_string($link, $_POST['account']);
    $pay_date = mysqli_real_escape_string($link, $_POST['pay_date']);
    $amount_to_pay = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['amount_to_pay']));
    $tpin = mysqli_real_escape_string($link, $_POST['tpin']);
    $mycurrentTime = date("Y-m-d h:i:s");

    $searchVAWN = mysqli_query($link, "SELECT * FROM virtual_account WHERE account_number = '$account_no'");
    $fetchVAWN = mysqli_fetch_array($searchVAWN);
    $userid = $fetchVAWN['userid'];
    $customer = $fetchVAWN['account_name'];

    $search_mystaff = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND id = '$userid'");
    $sRowNum = mysqli_num_rows($search_mystaff);
    $fetch_mystaff = mysqli_fetch_array($search_mystaff);

    $search_borro = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND account = '$userid'");
    $bRowNum = mysqli_num_rows($search_borro);
    $fetch_borro = mysqli_fetch_array($search_borro);

    $userType = ($sRowNum == 0 && $bRowNum == 1) ? "Customer" : "User";
    $uname = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['username'] : $fetch_mystaff['username'];
    $phone = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['phone'] : $fetch_mystaff['phone'];
    $em = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['email'] : $fetch_mystaff['email'];
    $sms_checker = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['sms_checker'] : "Yes";
    $loanBal = ($sRowNum == 0 && $bRowNum == 1) ? ($fetch_borro['loan_balance'] - $amount_to_pay) : ($fetch_mystaff['loan_balance'] - $amount_to_pay);
    $branch = ($sRowNum == 0 && $bRowNum == 1) ? $fetch_borro['branchid'] : $fetch_mystaff['created_by'];

    $search_loaninfo = mysqli_query($link, "SELECT * FROM wallet_loan_history WHERE lid = '$lid'");
    $get_loaninfo = mysqli_fetch_array($search_loaninfo);
    //$final_amount = $get_loaninfo['amount_topay'];
    $my_balance = number_format($get_loaninfo['balance'],2,'.','');

    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    //$sys_abb = $r->abb;
    $sys_email = $r->email;
    
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    //$sys_abb = $get_sys['abb'];
    $sysabb = $fetch_memset['sender_id'];
    $our_currency = $fetch_memset['currency'];

    $billing_type = $ifetch_maintenance_model['billing_type'];
    $t_perc = $ifetch_maintenance_model['t_charges'];
    $myiwallet_balance = (mysqli_num_rows($isearch_maintenance_model) == 1 || $billing_type == "Hybrid") ? ($iassigned_walletbal - $t_perc) : "";

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

    $status = "paid";
    $theStatus = "Approved";
    $channel = "Internal";
    $notification = "1";
    $checksms = "1";
    $maintenance_row = mysqli_num_rows($isearch_maintenance_model);
    $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 

    if($tpin != $myiepin){

        echo '<span class="alert bg-orange">Opps!...Invalid Transaction Pin, please try again later!!</span>';

    }
    elseif($amount_to_pay > $my_balance){

        echo '<span class="alert bg-orange"Opps!...Amount to pay is invalid!!</span>';

    }
    elseif($amount_to_pay > $balance && $numtill == 1){

        echo '<span class="alert bg-orange">Opps!...Insufficient fund in till balance!!</span>';

    }
    else{
        $final_bal = $my_balance - $amount_to_pay;
        //Charge Hybrid or PayG User
        (mysqli_num_rows($isearch_maintenance_model) == 1 || $billing_type == "Hybrid") ? mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','self','','$t_perc','Debit','$icurrency','Charges','Description: Service Charge for Repayment of $amount_to_pay','successful','$mycurrentTime','$iuid','$myiwallet_balance','')") : "";
        (mysqli_num_rows($isearch_maintenance_model) == 1 || $billing_type == "Hybrid") ? mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$institution_id'") : "";
        
        //Deduct customer loan Balance and log payment record
        ($userType == "Customer") ? $update = mysqli_query($link, "UPDATE borrowers SET loan_balance = '$loanBal' WHERE account = '$userid'") : "";
        ($userType == "User") ? $update = mysqli_query($link, "UPDATE user SET loan_balance = '$loanBal' WHERE id = '$userid'") : "";
        $update = mysqli_query($link, "UPDATE wallet_loan_history SET balance = '$final_bal' WHERE lid = '$lid'") or die (mysqli_error($link));
        $insert = mysqli_query($link, "INSERT INTO wallet_loan_repayment VALUES(null,'$iuid','$lid','$refid','$account_no','$customer','$amount_to_pay','$final_bal','$pay_date','$status','$institution_id','$isbranchid','$notification','$notification','$checksms')") or die (mysqli_error($link));
        
        //Sms notification draft
        $sms = "$sysabb>>>Dear $customer! Your repayment of ".$icurrency.number_format($amount_to_pay,2,'.',',')." with Loan ID: $lid has been initiated successfully. ";
        $sms .= "Your Loan Balance is: ".$icurrency.number_format($final_bal,2,'.',',')." Thanks.";

        $max_per_page = 153;
		$sms_length = strlen($sms);
		$calc_length = ceil($sms_length / $max_per_page);
		$sms_charges = $calc_length * $sms_rate;
		$mybalance = $iwallet_balance - $sms_charges;

        //Balance Till account balance if exist
        ($numtill == 1 && $allow_auth == "Yes") ? $update = mysqli_query($link, "UPDATE till_account SET commission_balance = '$total_commission_bal', balance = '$total_tillbal_left' WHERE cashier = '$iuid'") : "";
        ($numtill == 1 && $allow_auth != "Yes") ? $update = mysqli_query($link, "UPDATE till_account SET balance = '$total_tillbal_left' WHERE cashier = '$iuid'") : "";
        ($numtill == 1) ? $update = mysqli_query($link, "INSERT INTO fund_allocation_history VALUES(null,'$refid','$institution_id','$iuid','$isbranchid','$customer','$iuid','$amount_to_pay','Debit','LOAN_REPAYMENT','$icurrency','$total_tillbal_left','$sms','successful','$date_time')") : "";
        
        if(!($update && $insert))
        {
            echo'<span class="alert bg-orange">Unable to payment records.....Please try again later!</span>';
        }
        else{
            ($sms_checker == "No" || $billing_type == "PAYGException" ? "" : ($allow_auth == "Yes" && $debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mybalance, $debitWallet) : ($allow_auth == "Yes" && $debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mybalance, $debitWallet) : "")));
            $sendSMS->loanRepaymentEmailNotifier($em, $refid, $uname, $mycurrentTime, $theStatus, $channel, $account_no, $customer, $phone, $lid, $icurrency, $amount_to_pay, $final_bal, $iemailConfigStatus, $ifetch_emailConfig);
            echo "<div align='center'><img src='../image/checkmark.gif'><p style='color: #38A1F3;'>Transaction Successful!</p><p style='color: #38A1F3;'>Customer Loan Balance is: <b style='color: orange;'>".$icurrency.number_format($final_bal,2,'.',',')."</b></p></div>";
        }
    }
}
?>

             <div class="box-body">
<?php
$acct_owner = $_GET['uid'];
$searchVAWN = mysqli_query($link, "SELECT * FROM virtual_account WHERE account_number = '$acct_owner'");
$fetchVAWN = mysqli_fetch_array($searchVAWN);
$userid = $fetchVAWN['userid'];
$myb_name = $fetchVAWN['account_number'];
?>
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Loan ID</label>
                  <div class="col-sm-10">
                  <select class="select2" name="acte" style="width: 100%;" required>
        				<option value="" selected="selected">--Select Due Payment--</option>
                         <?php
                        $acct_owner = $_GET['uid'];
        				$get = mysqli_query($link, "SELECT * FROM wallet_due_loan WHERE branchid = '$institution_id' AND tid = '$acct_owner'") or die (mysqli_error($link));
        				while($rows = mysqli_fetch_array($get))
        				{
        				    $baccount = $rows['tid'];
        				    echo '<option value="'.$rows['lid'].'">'.$baccount." - ".$myb_name." [Loan ID: ".$rows['lid']." | Due Amount: ".number_format($rows['dueAmount'],2,".",",")." | Loan Bal: ".number_format($rows['balance'],2,".",",").']</option>';
        				}
        				?>
                    </select>
                  </div>
                  </div>

                  <input name="account" type="hidden" class="form-control" value="<?php echo $acct_owner; ?>" required>
			  
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

<input type="hidden" class="form-control pull-right" name="pay_date" value="<?php echo date('Y-m-d h:i:s'); ?>" /required>

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