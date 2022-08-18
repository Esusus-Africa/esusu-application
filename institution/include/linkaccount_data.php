<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title">Link Account</h3>
            </div>

             <div class="box-body">

 <?php
if(isset($_POST['linkAcct']))
{
	$defaultAcct = mysqli_real_escape_string($link, $_POST['defaultAcct']);
	$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$defaultAcct'");
    $fetch_cust = mysqli_fetch_array($search_customer);
    $snum = $fetch_cust['snum'];
    $fname = $fetch_cust['fname'];
    $lname = $fetch_cust['lname'];
    $mname = $fetch_cust['mname'];
    $email = $fetch_cust['email'];
    $phone = $fetch_cust['phone'];
    $gender = $fetch_cust['gender'];
    $dob = $fetch_cust['dob'];
    $occupation = $fetch_cust['occupation'];
    $addrs = $fetch_cust['addrs'];
    $city = $fetch_cust['city'];
    $state = $fetch_cust['state'];
    $zip = $fetch_cust['zip'];
    $country = $fetch_cust['country'];
    $nok = $fetch_cust['nok'];
    $nok_rela = $fetch_cust['nok_rela'];
    $nok_phone = $fetch_cust['nok_phone'];
    $account = mysqli_real_escape_string($link, $_POST['newAcct']);
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $password = substr((uniqid(rand(),1)),4,6);
    $location = $fetch_cust['image'];
    $date_time = date("Y-m-d h:i:s");
    $last_withdraw_date = "0000-00-00";
    $status = $fetch_cust['status'];
    $lofficer = $fetch_cust['lofficer'];
    $c_sign = $fetch_cust['c_sign'];

    $s_contribution_interval = $fetch_cust['s_contribution_interval'];
    $savings_amount = $fetch_cust['savings_amount'];
    $charge_interval = $fetch_cust['charge_interval'];
    $chargesAmount = $fetch_cust['chargesAmount'];
    $disbursement_interval = $fetch_cust['disbursement_interval'];
    $disbursement_channel = $fetch_cust['disbursement_channel'];
    $auto_disbursement_status = $fetch_cust['auto_disbursement_status'];
    $auto_charge_status = $fetch_cust['auto_charge_status'];
    $next_charges_date = $fetch_cust['next_charges_date'];
    $next_disbursement_date = $fetch_cust['next_disbursement_date'];
    $recipient_id = $fetch_cust['recipient_id'];

    $opt_option = $fetch_cust['opt_option'];
    $currency = $fetch_cust['currency'];
    $overdraft = "No";
    $transactionPin = substr((uniqid(rand(),1)),3,4);
    $reg_type = "Individual";
    $gname = "";
    $gposition = "";
    $acct_type = mysqli_real_escape_string($link, $_POST['acct_type']);
    $acct_opening_date = date("Y-m-d");
    $unumber = $fetch_cust['unumber'];
    $verve_expiry_date = "";
    $employer = $fetch_cust['employer'];
    $virtual_number = "";
    $virtual_acctno = "";
    $bankname = "";
    $dedicated_ussd_prefix = $fetch_cust['dedicated_ussd_prefix'];
    $evnNumber = ($_POST['evnNumber'] == "") ? $fetch_cust['evn'] : mysqli_real_escape_string($link, $_POST['evnNumber']);
	
	$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $sms_rate = $r->fax;
    $sys_email = $r->email;
    $seckey = $r->secret_key;

    $refid = "EA-custReg-".time();
    $cust_charges = ($ifetch_maintenance_model['cust_mfee'] == "") ? $sms_rate : $ifetch_maintenance_model['cust_mfee'];
    $myiwallet_balance = $iwallet_balance - $cust_charges;
    $wallet_date_time = date("Y-m-d h:i:s");

    //END CUSTOMER IDENTITY VERIFICATION
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    $sysabb = $fetch_memset['sender_id'];
    $mobileapp_link = ($fetch_memset['mobileapp_link'] == "") ? "Login at https://esusu.app/$sysabb" : "Download mobile app: ".$fetch_memset['mobileapp_link'];
    //$dedicated_ussd_prefix = $fetch_memset['dedicated_ussd_prefix'];

    $transactionPin = substr((uniqid(rand(),1)),3,4);
        
    $sms = "$sysabb>>>Welcome $fname! Your Account ID: $account, Username: $username, Password: $password, Transaction Pin: $transactionPin. $mobileapp_link";
        
    $max_per_page = 153;
    $sms_length = strlen($sms);
    $calc_length = ceil($sms_length / $max_per_page);

    $maintenance_row = mysqli_num_rows($isearch_maintenance_model);
    $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes";       
    $sms_charges = $calc_length * $cust_charges;
    $mywallet_balance = $iassigned_walletbal - $sms_charges;
    $refid = "EA-smsCharges-".time();

    $verify_username = mysqli_query($link, "SELECT * FROM borrowers WHERE username = '$username'");
    $detect_username = mysqli_num_rows($verify_username);

    $verify_Uusername = mysqli_query($link, "SELECT * FROM user WHERE username = '$username'");
    $detect_Uusername = mysqli_num_rows($verify_Uusername);

    $verify_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id'");
    $fetch_cusno = mysqli_num_rows($verify_customer);
	
	if($detect_username == 1 || $detect_Uusername == 1){

        echo "<p style='font-size:24px; color:orange;'>Sorry, Username has already been used.</p>";

    }
    elseif($fetch_cusno == $icustomer_limit && (mysqli_num_rows($isearch_maintenance_model) == 0 || (mysqli_num_rows($isearch_maintenance_model) == 1 && $billing_type != "PAYG")))
    {

        echo "<script>alert('Sorry, You can not add more than the limit assigned to you. Kindly Contact us for higher plan!'); </script>";
    
    }
    elseif($iwallet_balance < $cust_charges && (mysqli_num_rows($isearch_maintenance_model) == 0 || (mysqli_num_rows($isearch_maintenance_model) == 1 && $billing_type != "PAYGException"))){
        
        echo "<script>alert('Sorry, You are unable to add more customers due to insufficient fund in your Wallet!!'); </script>";
    
    }
    elseif($idedicated_ledgerAcctNo_prefix == ""){

        echo "<p style='font-size:24px; color:orange;'>Sorry! The ledger account number prefix is not yet configure...Kindly contact us to so!!</p>";
    
    }
    elseif(($iwallet_balance >= $cust_charges && mysqli_num_rows($isearch_maintenance_model) == 1 && $billing_type != "PAYGException") || (mysqli_num_rows($isearch_maintenance_model) == 1 && $billing_type == "PAYGException") || mysqli_num_rows($isearch_maintenance_model) == 0){
      
	    $insert = mysqli_query($link, "INSERT INTO borrowers VALUES(null,'','$fname','$lname','$mname','$email','$phone','$gender','$dob','$occupation','$addrs','$city','$state','','$country','$nok','$nok_rela','$nok_phone','Borrower','$account','$username','$password','0.0','0.0','0.0','0.0','0.0','$location','$date_time','$last_withdraw_date','$status','$lofficer','$c_sign','$institution_id','$isbranchid','Not-Activated','$s_contribution_interval','$savings_amount','$charge_interval','$chargesAmount','$disbursement_interval','$disbursement_channel','NotActive','NotActive','','','$recipient_id','$otp','$icurrency','0.0','$overdraft','NULL','No','NULL','$transactionPin','$reg_type','$gname','$g_position','$acct_type','0.0','$opening_date','$unumber','$verve_expiry_date','$employer','$virtual_number','$virtual_acctno','$bankname','$idedicated_ussd_prefix','$evnNumber','Yes','','','','','','','','','','','1','1')") or die (mysqli_error($link));
        
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST']."/?acn=".$account;
        $id = rand(1000000,10000000);
        $shorturl = base_convert($id,20,36);
        
        $insert = mysqli_query($link, "INSERT INTO activate_member VALUES(null,'$url','$shorturl','No','$account')") or die ("Error: " . mysqli_error($link));
        
        $shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/?activation_key=' . $shorturl;
        $shortenedurl1 = $protocol . $_SERVER['HTTP_HOST'] . '/?deactivation_key=' . $shorturl;
        
        //SMS NOTIFICATION
        ($billing_type == "PAYGException" ? "" : ($allow_auth == "Yes" && $debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mywallet_balance, $debitWallet) : ($allow_auth == "Yes" && $debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mywallet_balance, $debitWallet) : "")));
        //EMAIL NOTIFICATION
        ($allow_auth == "Yes") ? $sendSMS->customerRegEmailNotifier($email, $fname, $shortenedurl, $username, $password, $account, $myAccountNumber, $shortenedurl1, $iemailConfigStatus, $ifetch_emailConfig) : "";

        echo "<div class='alert alert-success'>New Account Added Successfully!</div>";
            
    }

}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Do you have <?php echo ($evn_label == "") ? "EVN" : $evn_label; ?>?</label>
                <div class="col-sm-6">
                    <select name="evn_option"  class="form-control select2" id="evn_option" required>
                      <option value="" selected>Select Response</option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                    <div style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> <b><?php echo ($evn_label == "") ? "EVN" : $evn_label; ?> - (12 digit number)</b>.</div>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <span id='ShowValueFrank24'></span>
			 
			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Default Account:</label>
                <div class="col-sm-6">
                    <select name="defaultAcct"  class="form-control select2" required>
                        <option value="" selected>Select Default Account</option>
                        <?php
                        ($individual_customer_records != "1" && $branch_customer_records != "1") ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id'") : "";
                        ($individual_customer_records === "1" && $branch_customer_records != "1") ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id'  AND lofficer = '$iuid'") : "";
                        ($individual_customer_records != "1" && $branch_customer_records === "1") ? $search = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid'") : "";
                        while($get_search = mysqli_fetch_array($search))
                        {
                        ?>
                        <option value="<?php echo $get_search['account']; ?>"><?php echo ($evn_label == "") ? "EVN" : $evn_label; ?>: <?php echo ($get_search['evn'] == "") ? "None" : $get_search['account']; ?> - <?php echo $get_search['account']; ?> - <?php echo $get_search['fname']; ?> <?php echo $get_search['lname']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">New Account Type:</label>
                <div class="col-sm-6">
                    <select name="acct_type" class="form-control select2" required>
 						<option value="" selected='selected'>Select Account Type&hellip;</option>
 						<?php
 						$search_accttype = mysqli_query($link, "SELECT * FROM account_type WHERE merchant_id = '$institution_id' AND account_type = 'Regular'");
 						while($fetch_accttype = mysqli_fetch_object($search_accttype)){
 						?>
 						<option value="<?php echo $fetch_accttype->acct_name; ?>"><?php echo $fetch_accttype->acct_name.' | Interest: '.$fetch_accttype->interest_rate.'%'.' | Minimum Opening Balance: ['.$icurrency.number_format($fetch_accttype->opening_balance,2,'.',',').']'; ?></option>
 						<?php } ?>
 					</select>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
		
			<div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number:</label>
                <div class="col-sm-6">
                    <?php
                    $account = $idedicated_ledgerAcctNo_prefix.rand(10000000,99999999);
                    $search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$account'");
                    $real_acct = (mysqli_num_rows($search_customer) == 0) ? $account : $idedicated_ledgerAcctNo_prefix.rand(1000,9999).rand(10000,99999);
                    ?>
                    <input name="newAcct" type="text" class="form-control" value="<?php echo $real_acct; ?>" placeholder="Account Number" required>
                  </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Username:</label>
                <div class="col-sm-6">
                    <input name="username" type="text" class="form-control" id="vbusername" onkeyup="veryBUsername();" placeholder="Username" required>
                    <div id="mybusername"></div>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
				  
			 </div>
			 
			<div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-6">
                	<button name="linkAcct" type="submit" class="btn bg-blue"><i class="fa fa-link">&nbsp;Submit</i></button>
                </div>
                <label for="" class="col-sm-3 control-label"></label>
            </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>