<div class="row">	
		
	 <section class="content">
		         
		   <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

		 <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="addcustomer.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("403"); ?>&&tab=tab_1">New Customer Registration Form</a></li>
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="addcustomer.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("403"); ?>&&tab=tab_2">Import Customer's Information in Excel</a></li>
              </ul>
             <div class="tab-content">
<?php
function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}

if(isset($_GET['tab']) == true)
{
	$tab = $_GET['tab'];
	if($tab == 'tab_1')
	{
	?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">
			 
 <?php
if(isset($_POST['save']))
{
$acct_type = ((startsWith($institution_id,"INST") || startsWith($institution_id,"MER")) && $isavings_account === "On" && $igeneral_settings === "On") ? mysqli_real_escape_string($link, $_POST['acct_type']) : "";
$reg_type = ((startsWith($institution_id,"INST") || startsWith($institution_id,"MER")) && $isavings_account === "On") ? mysqli_real_escape_string($link, $_POST['reg_type']) : "Individual";
$gname =  ((startsWith($institution_id,"INST") || startsWith($institution_id,"MER")) && $isavings_account === "On" && $igeneral_settings === "On") ? mysqli_real_escape_string($link, $_POST['gname']) : "";
$g_position =  ((startsWith($institution_id,"INST") || startsWith($institution_id,"MER")) && $isavings_account === "On" && $igeneral_settings === "On") ? mysqli_real_escape_string($link, $_POST['g_position']) : "";

$snum = mysqli_real_escape_string($link, $_POST['snum']);
$fname = mysqli_real_escape_string($link, $_POST['fname']);
$lname = mysqli_real_escape_string($link, $_POST['lname']);
$mname = mysqli_real_escape_string($link, $_POST['mname']);
$email = mysqli_real_escape_string($link, $_POST['email']);
$phone = mysqli_real_escape_string($link, $_POST['phone']);

$gender =  mysqli_real_escape_string($link, $_POST['gender']);
$dob =  mysqli_real_escape_string($link, $_POST['bdate']);
$smsChecker = mysqli_real_escape_string($link, $_POST['smsChecker']);

$occupation = mysqli_real_escape_string($link, $_POST['occupation']);
$addrs = mysqli_real_escape_string($link, $_POST['addrs']);
$city = mysqli_real_escape_string($link, $_POST['city']);
$state = mysqli_real_escape_string($link, $_POST['state']);
//$zip = mysqli_real_escape_string($link, $_POST['zip']);
$country = mysqli_real_escape_string($link, $_POST['country']);
$nok = mysqli_real_escape_string($link, $_POST['nok']);
$nok_rela = mysqli_real_escape_string($link, $_POST['nok_rela']);
$nok_phone = mysqli_real_escape_string($link, $_POST['nok_phone']);

$account = mysqli_real_escape_string($link, $_POST['account']);
$status = ($allow_auth == "Yes") ? "Not-Activated" : "queue";
$acct_status = ($allow_auth == "Yes") ? "Not-Activated" : "queue";
$lofficer = mysqli_real_escape_string($link, $_POST['lofficer']);
$sbranchid = mysqli_real_escape_string($link, $_POST['branch']);
$otp = mysqli_real_escape_string($link, $_POST['otp']);
$overdraft = mysqli_real_escape_string($link, $_POST['overdraft']);

$username = mysqli_real_escape_string($link, $_POST['username']);
$password = substr((uniqid(rand(),1)),3,6);

$s_interval = ($isavings_account === "On") ? mysqli_real_escape_string($link, $_POST['s_interval']) : "";
$s_amount = ($isavings_account === "On") ? mysqli_real_escape_string($link, $_POST['s_amount']) : "";
$c_interval = ($isavings_account === "On") ? mysqli_real_escape_string($link, $_POST['c_interval']) : "";
$chargesAmount = ($isavings_account === "On") ? mysqli_real_escape_string($link, $_POST['chargesAmount']) : "";
$d_interval = ($isavings_account === "On") ? mysqli_real_escape_string($link, $_POST['d_interval']) : "";
$d_channel = ($isavings_account === "On") ? mysqli_real_escape_string($link, $_POST['d_channel']) : "";
$account_number = ($d_channel == "Bank" && $isavings_account == "On") ? mysqli_real_escape_string($link, $_POST['acct_no']) : "";
$bank_code = ($d_channel == "Bank" && $isavings_account == "On") ? mysqli_real_escape_string($link, $_POST['bank_code']) : "";
//$d_interval = mysqli_real_escape_string($link, $_POST['d_interval']);

$verify_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND acct_status != 'Closed'");
$fetch_cusno = mysqli_num_rows($verify_customer);

//START CUSTOMER IDENTITY VERIFICATION
$verify_email = mysqli_query($link, "SELECT * FROM borrowers WHERE email = '$email' AND acct_status != 'Closed'");
$detect_email = mysqli_num_rows($verify_email);

$verify_phone = mysqli_query($link, "SELECT * FROM borrowers WHERE phone = '$phone' AND acct_status != 'Closed'");	
$detect_phone = mysqli_num_rows($verify_phone);

$verify_username = mysqli_query($link, "SELECT * FROM borrowers WHERE username = '$username' AND acct_status != 'Closed'");
$detect_username = mysqli_num_rows($verify_username);

$verify_Uemail = mysqli_query($link, "SELECT * FROM user WHERE email = '$email'");
$detect_Uemail = mysqli_num_rows($verify_Uemail);

$verify_Uphone = mysqli_query($link, "SELECT * FROM user WHERE phone = '$phone'");	
$detect_Uphone = mysqli_num_rows($verify_Uphone);

$verify_Uusername = mysqli_query($link, "SELECT * FROM user WHERE username = '$username'");
$detect_Uusername = mysqli_num_rows($verify_Uusername);

$query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$r = mysqli_fetch_object($query);
$sms_rate = $r->fax;
$sys_email = $r->email;
$seckey = $r->secret_key;

$refid = uniqid("EA-custReg-").time();
$cust_charges = ($ifetch_maintenance_model['cust_mfee'] == "") ? $sms_rate : $ifetch_maintenance_model['cust_mfee'];
$myiwallet_balance = $iwallet_balance - $cust_charges;
$wallet_date_time = date("Y-m-d h:i:s");

//END CUSTOMER IDENTITY VERIFICATION 
$search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$fetch_memset = mysqli_fetch_array($search_memset);
$customDomain = ($iemailConfigStatus == "Activated") ? $ifetch_emailConfig['product_url'] : "https://esusu.app/$isenderid";
$mobileapp_link = ($fetch_memset['mobileapp_link'] == "") ? "Login at ".$customDomain : "Download mobile app: ".$fetch_memset['mobileapp_link'];

$transactionPin = substr((uniqid(rand(),1)),3,4);
$myAccountNumber = "----";
	
$sms = "$isenderid>>>Welcome $fname! Your Account ID: $account, Username: $username, Password: $password, Transaction Pin: $transactionPin. $mobileapp_link";

$max_per_page = 153;
$sms_length = strlen($sms);
$calc_length = ceil($sms_length / $max_per_page);
 
$maintenance_row = mysqli_num_rows($isearch_maintenance_model);
$debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 
$sms_charges = $calc_length * $cust_charges;
$mybalance = $iwallet_balance - $sms_charges;

//loan group setup
$verify_groupmember_limit = mysqli_query($link, "SELECT * FROM lgroup_setup WHERE id = '$gname'");
$fetch_vg = mysqli_fetch_array($verify_groupmember_limit);
$max_member = $fetch_vg['max_member'];

$verify_memgroup = mysqli_query($link, "SELECT * FROM borrowers WHERE reg_type = '$reg_type' AND gname = '$gname'");
$v_memgroup = mysqli_num_rows($verify_memgroup);

$verify_serialno = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND snum = '$snum'");
if(mysqli_num_rows($verify_serialno) == 1){
    echo "<div class='alert alert-info'>Oops! Serial Number Already Used.</div>";
}elseif($detect_email == 1 || $detect_Uemail == 1){
	echo "<p style='font-size:24px; color:orange;'>Sorry, Email Address has already been used.</p>";
}
elseif($detect_phone == 1 || $detect_Uphone == 1){
	echo "<p style='font-size:24px; color:orange;'>Sorry, Phone Number has already been used.</p>";
}
elseif($detect_username == 1 || $detect_Uusername == 1){
	echo "<p style='font-size:24px; color:orange;'>Sorry, Username has already been used.</p>";
}
elseif($reg_type == "Group" && $max_member == $v_memgroup){
	echo "<p style='font-size:24px; color:orange;'>The Group has already reach the maximum limit of member's.</p>";
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
    
    $search_user = mysqli_query($link, "SELECT * FROM user WHERE userid = '$lofficer'");
    $get_user = mysqli_fetch_array($search_user);
    $branchid = $get_user['branchid'];

    $target_dir = "../img/";
    $target_file = $target_dir.basename($_FILES["image"]["name"]);
    //$target_file_c_sign = $target_dir.basename($_FILES["c_sign"]["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    //$imageFileType_c_sign = pathinfo($target_file_c_sign,PATHINFO_EXTENSION);
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    //$check_c_sign = getimagesize($_FILES["c_sign"]["tmp_name"]);
	
	$sourcepath = $_FILES["image"]["tmp_name"];
	//$sourcepath_c_sign = $_FILES["c_sign"]["tmp_name"];
	$targetpath = "../img/" . $_FILES["image"]["name"];
	//$targetpath_c_sign = "../img/" . $_FILES["c_sign"]["name"];
	move_uploaded_file($sourcepath,$targetpath);
	//move_uploaded_file($sourcepath_c_sign,$targetpath_c_sign);

	$location = $_FILES['image']['name'];
	//$loaction_c_sign = "img/".$_FILES['c_sign']['name'];

	$last_charge_date = date("Y-m-d h:m:s");
	$opening_date = date("Y-m-d");

	$send_sms = ($billing_type == "PAYGException" ? "0" : ($allow_auth == "Yes" && $debitWAllet == "No" ? "1" : ($allow_auth == "Yes" && $debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? "1" : "0")));
	$send_email = ($email == "" || $allow_auth == "No") ? "0" : "1";
	//Bank Details
	/*$search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'transferrecipient'");
	$fetch_restapi = mysqli_fetch_object($search_restapi);
	$api_url = $fetch_restapi->api_url;
			
	// Pass the plan's name, interval and amount
	$postdata =  array(
	'account_number'  => $account_number,
	'account_bank'    => $bank_code,
	'seckey'          => $seckey
	);
	
	($d_channel == "Bank" && $isavings_account == "On") ? $make_call = callAPI('POST', $api_url, json_encode($postdata)) : "";
	($d_channel == "Bank" && $isavings_account == "On") ? $result = json_decode($make_call, true) : "";

	//Get the Recipient Id from Rav API
	($d_channel == "Bank" && $isavings_account == "On") ? $recipient_id = $result['data']['id'] : "";
	//Get the Bank Name from Rav API
	($d_channel == "Bank" && $isavings_account == "On") ? $bank_name = $result['data']['bank_name'] : "";
	//Get the Recipient Full Name From Rav API
	($d_channel == "Bank" && $isavings_account == "On") ? $fullname = $result['data']['fullname'] : "";
	
	($d_channel == "Bank" && $isavings_account == "On") ? $insert = mysqli_query($link, "INSERT INTO transfer_recipient VALUES(null,'$institution_id','$recipient_id','$fullname','$account_number','$bank_code','$bank_name',NOW(),'$iuid','$isbranchid')") or die ("Error6: " . mysqli_error($link)) : "";
*/
	foreach ($_FILES['uploaded_file']['name'] as $key => $name){
        
		$newFilename = $name;
		
		if($newFilename == "")
		{
			echo "";
		}
		else{
			$newlocation = $newFilename;
			if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], '../img/'.$newFilename))
			{
				mysqli_query($link, "INSERT INTO attachment VALUES(null,'','$account','$account','$newlocation',NOW())") or die ("Error5:" . mysqli_error($link));
			}
		}
		
	}

	$insert = mysqli_query($link, "INSERT INTO borrowers VALUES(null,'$snum','$fname','$lname','$mname','$email','$phone','$gender','$dob','$occupation','$addrs','$city','$state','','$country','$nok','$nok_rela','$nok_phone','Borrower','$account','$username','$password','0.0','0.0','0.0','0.0','0.0','$location',NOW(),'0000-00-00','$status','$lofficer','','$institution_id','$sbranchid','$acct_status','$s_interval','$s_amount','$c_interval','$chargesAmount','$d_interval','$d_channel','NotActive','NotActive','','','$recipient_id','$otp','$icurrency','0.0','$overdraft','NULL','No','NULL','$transactionPin','$reg_type','$gname','$g_position','$acct_type','0.0','$opening_date','','','','','','','$idedicated_ussd_prefix','','$smsChecker','','','','','','','','','','','$send_sms','$send_email')") or die ("Error: " .mysqli_error($link));

	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/confirmAcct.php?id='.$mysenderID;
    $otpCode = substr((uniqid(rand(),1)),3,6);
    $shortenedurl1 = $protocol . $_SERVER['HTTP_HOST'] . '/?deactivation_key=' . $otpCode;
	
	($allow_auth == "Yes") ? $insert = mysqli_query($link, "INSERT INTO activate_member2 VALUES(null,'$shortenedurl','$otpCode','No','$account')") or die ("Error4: " . mysqli_error($link)) : "";

	//SMS NOTIFICATION
	($billing_type == "PAYGException" ? "" : ($allow_auth == "Yes" && $debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mybalance, $debitWallet) : ($allow_auth == "Yes" && $debitWAllet == "Yes" && $sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $isenderid, $phone, $sms, $institution_id, $refid, $sms_charges, $iuid, $mybalance, $debitWallet) : "")));
	//EMAIL NOTIFICATION
	($allow_auth == "Yes") ? $sendSMS->customerRegEmailNotifier($email, $fname, $shortenedurl, $username, $password, $account, $myAccountNumber, $otpCode, $shortenedurl1, $iemailConfigStatus, $ifetch_emailConfig) : "";
		
	echo "<div class='alert alert-success'>New Customer Added Successfully!</div>";
    
}
else{
    
    echo "<div class='alert alert-danger'>Oops!....Unable to Register Customer at the moment. Please try again later!!</div>";

}
}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">
<?php
if((startsWith($institution_id,"INST") || startsWith($institution_id,"MER")) && $isavings_account === "On" && $igeneral_settings === "On")
{
?>
            <div class="form-group">
 		                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Savings Product</label>
 		                  <div class="col-sm-10">
 						<select name="acct_type" class="form-control select2" required>
 												<option value="" selected='selected'>Select Savings Product&hellip;</option>
												 <option value="Union Purse">Union Purse</option>
 												<?php
 												$search_accttype = mysqli_query($link, "SELECT * FROM account_type WHERE merchant_id = '$institution_id' AND account_type = 'Regular'");
 												while($fetch_accttype = mysqli_fetch_object($search_accttype)){
 												    $systemset = mysqli_query($link, "SELECT * FROM systemset");
                                                    $fetch_sys = mysqli_fetch_array($systemset);
 												?>
 												<option value="<?php echo $fetch_accttype->acct_name; ?>"><?php echo $fetch_accttype->acct_name.' | Interest: '.$fetch_accttype->interest_rate.'%'.' | Minimum Opening Balance: ['.$icurrency.number_format($fetch_accttype->opening_balance,2,'.',',').']'; ?></option>
 												<?php } ?>
 						</select>
 				</div>
 				</div>
                 
                <div class="form-group">
 		                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Registration Type</label>
 		                  <div class="col-sm-10">
 						<select name="reg_type" class="form-control select2" id="reg_type" required>
 												<option value="" selected='selected'>Select Registration Type&hellip;</option>
 												<option value="Group">Group Registration</option>
 												<option value="Individual">Individual Registration</option>
 						</select>
 				</div>
 				</div>
 				
      			<span id='ShowValueFrank'></span>
      			<span id='ShowValueFrank'></span>
      			
<?php
}
else{
    echo "";
}
?>
				
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Your Image</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);">
       				 <img id="blah"  src="<?php echo $fetchsys_config['file_baseurl']; ?>user2.png" alt="Image Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;"><?php echo ($sno_label == "") ? "Serial No:" : $sno_label; ?></label>
                  <div class="col-sm-10">
                  <input name="snum" type="text" class="form-control" placeholder="Enter <?php echo ($sno_label == "") ? "Serial Number" : $sno_label; ?>" required>
                  </div>
                  </div>
			
			 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
                  <div class="col-sm-10">
<?php
$account = $idedicated_ledgerAcctNo_prefix.rand(10000000,99999999);
$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$account'");
$real_acct = (mysqli_num_rows($search_customer) == 0) ? $account : $idedicated_ledgerAcctNo_prefix.substr((uniqid(rand(),1)),4,8);
?>
                  <input name="account" type="text" class="form-control" value="<?php echo $real_acct; ?>" placeholder="Account Number" required>
                  </div>
                  </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">First Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" placeholder="First Name" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Last Name</label>
                  <div class="col-sm-10">
                  <input name="lname" type="text" class="form-control" placeholder="Last Name" required>
                  </div>
                  </div>

				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Middle Name</label>
                  <div class="col-sm-10">
                  <input name="mname" type="text" class="form-control" placeholder="Middle Name">
                  </div>
                  </div>

        <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mobile</label>
                      <div class="col-sm-4">
                      <input name="phone" type="tel" class="form-control" id="phone" onkeyup="veryBPhone();" required>
                      <div id="myvbphone"></div>
                      </div><?php echo (isMobileDevice()) ? "<br>" : ""; ?>
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gender</label>
                      <div class="col-sm-4">
                            <select name="gender" class="form-control" required>
                                        <option value="" selected='selected'>Select Gender&hellip;</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                            </select>
                        </div>  
					</div>


		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Email(Optional)</label>
                  <div class="col-sm-10">
                  <input type="text" name="email" type="text" id="vbemail" onkeyup="veryBEmail();" class="form-control" placeholder="Email">
                  <div id="myvbemail"></div>
                  </div>
				  </div>
				  
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Date of Birth</label>
                  <div class="col-sm-10">
                  <input name="bdate" type="date" class="form-control">
                  </div>
                  </div>
                  
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Occupation</label>
                  <div class="col-sm-10">
                  <input name="occupation" type="text" class="form-control" placeholder="Occupation">
                  </div>
                  </div>
				  
		 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Address</label>
                  	<div class="col-sm-10"><textarea name="addrs"  class="form-control" rows="2" cols="80" required></textarea></div>
          </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">City</label>
                  <div class="col-sm-10">
                  <input name="city" type="text" class="form-control" placeholder="City">
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">State</label>
                  <div class="col-sm-10">
                  <input name="state" type="text" class="form-control" placeholder="State">
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
                  <div class="col-sm-10">
				<select name="country"  class="form-control" required>
										<option value="" selected='selected'>Select Country</option>
										<option value="AX">&#197;land Islands</option>
										<option value="AF">Afghanistan</option>
										<option value="AL">Albania</option>
										<option value="DZ">Algeria</option>
										<option value="AD">Andorra</option>
										<option value="AO">Angola</option>
										<option value="AI">Anguilla</option>
										<option value="AQ">Antarctica</option>
										<option value="AG">Antigua and Barbuda</option>
										<option value="AR">Argentina</option>
										<option value="AM">Armenia</option>
										<option value="AW">Aruba</option>
										<option value="AU">Australia</option>
										<option value="AT">Austria</option>
										<option value="AZ">Azerbaijan</option>
										<option value="BS">Bahamas</option>
										<option value="BH">Bahrain</option>
										<option value="BD">Bangladesh</option>
										<option value="BB">Barbados</option>
										<option value="BY">Belarus</option>
										<option value="PW">Belau</option>
										<option value="BE">Belgium</option>
										<option value="BZ">Belize</option>
										<option value="BJ">Benin</option>
										<option value="BM">Bermuda</option>
										<option value="BT">Bhutan</option>
										<option value="BO">Bolivia</option>
										<option value="BQ">Bonaire, Saint Eustatius and Saba</option>
										<option value="BA">Bosnia and Herzegovina</option>
										<option value="BW">Botswana</option>
										<option value="BV">Bouvet Island</option>
										<option value="BR">Brazil</option>
										<option value="IO">British Indian Ocean Territory</option>
										<option value="VG">British Virgin Islands</option>
										<option value="BN">Brunei</option>
										<option value="BG">Bulgaria</option>
										<option value="BF">Burkina Faso</option>
										<option value="BI">Burundi</option>
										<option value="KH">Cambodia</option>
										<option value="CM">Cameroon</option>
										<option value="CA">Canada</option>
										<option value="CV">Cape Verde</option>
										<option value="KY">Cayman Islands</option>
										<option value="CF">Central African Republic</option>
										<option value="TD">Chad</option>
										<option value="CL">Chile</option>
										<option value="CN">China</option>
										<option value="CX">Christmas Island</option>
										<option value="CC">Cocos (Keeling) Islands</option>
										<option value="CO">Colombia</option>
										<option value="KM">Comoros</option>
										<option value="CG">Congo (Brazzaville)</option>
										<option value="CD">Congo (Kinshasa)</option>
										<option value="CK">Cook Islands</option>
										<option value="CR">Costa Rica</option>
										<option value="HR">Croatia</option>
										<option value="CU">Cuba</option>
										<option value="CW">Cura&Ccedil;ao</option>
										<option value="CY">Cyprus</option>
										<option value="CZ">Czech Republic</option>
										<option value="DK">Denmark</option>
										<option value="DJ">Djibouti</option>
										<option value="DM">Dominica</option>
										<option value="DO">Dominican Republic</option>
										<option value="EC">Ecuador</option>
										<option value="EG">Egypt</option>
										<option value="SV">El Salvador</option>
										<option value="GQ">Equatorial Guinea</option>
										<option value="ER">Eritrea</option>
										<option value="EE">Estonia</option>
										<option value="ET">Ethiopia</option>
										<option value="FK">Falkland Islands</option>
										<option value="FO">Faroe Islands</option>
										<option value="FJ">Fiji</option>
										<option value="FI">Finland</option>
										<option value="FR">France</option>
										<option value="GF">French Guiana</option>
										<option value="PF">French Polynesia</option>
										<option value="TF">French Southern Territories</option>
										<option value="GA">Gabon</option>
										<option value="GM">Gambia</option>
										<option value="GE">Georgia</option>
										<option value="DE">Germany</option>
										<option value="GH">Ghana</option>
										<option value="GI">Gibraltar</option>
										<option value="GR">Greece</option>
										<option value="GL">Greenland</option>
										<option value="GD">Grenada</option>
										<option value="GP">Guadeloupe</option>
										<option value="GT">Guatemala</option>
										<option value="GG">Guernsey</option>
										<option value="GN">Guinea</option>
										<option value="GW">Guinea-Bissau</option>
										<option value="GY">Guyana</option>
										<option value="HT">Haiti</option>
										<option value="HM">Heard Island and McDonald Islands</option>
										<option value="HN">Honduras</option>
										<option value="HK">Hong Kong</option>
										<option value="HU">Hungary</option>
										<option value="IS">Iceland</option>
										<option value="IN">India</option>
										<option value="ID">Indonesia</option>
										<option value="IR">Iran</option>
										<option value="IQ">Iraq</option>
										<option value="IM">Isle of Man</option>
										<option value="IL">Israel</option>
										<option value="IT">Italy</option>
										<option value="CI">Ivory Coast</option>
										<option value="JM">Jamaica</option>
										<option value="JP">Japan</option>
										<option value="JE">Jersey</option>
										<option value="JO">Jordan</option>
										<option value="KZ">Kazakhstan</option>
										<option value="KE">Kenya</option>
										<option value="KI">Kiribati</option>
										<option value="KW">Kuwait</option>
										<option value="KG">Kyrgyzstan</option>
										<option value="LA">Laos</option>
										<option value="LV">Latvia</option>
										<option value="LB">Lebanon</option>
										<option value="LS">Lesotho</option>
										<option value="LR">Liberia</option>
										<option value="LY">Libya</option>
										<option value="LI">Liechtenstein</option>
										<option value="LT">Lithuania</option>
										<option value="LU">Luxembourg</option>
										<option value="MO">Macao S.A.R., China</option>
										<option value="MK">Macedonia</option>
										<option value="MG">Madagascar</option>
										<option value="MW">Malawi</option>
										<option value="MY">Malaysia</option>
										<option value="MV">Maldives</option>
										<option value="ML">Mali</option>
										<option value="MT">Malta</option>
										<option value="MH">Marshall Islands</option>
										<option value="MQ">Martinique</option>
										<option value="MR">Mauritania</option>
										<option value="MU">Mauritius</option>
										<option value="YT">Mayotte</option>
										<option value="MX">Mexico</option>
										<option value="FM">Micronesia</option>
										<option value="MD">Moldova</option>
										<option value="MC">Monaco</option>
										<option value="MN">Mongolia</option>
										<option value="ME">Montenegro</option>
										<option value="MS">Montserrat</option>
										<option value="MA">Morocco</option>
										<option value="MZ">Mozambique</option>
										<option value="MM">Myanmar</option>
										<option value="NA">Namibia</option>
										<option value="NR">Nauru</option>
										<option value="NP">Nepal</option>
										<option value="NL">Netherlands</option>
										<option value="AN">Netherlands Antilles</option>
										<option value="NC">New Caledonia</option>
										<option value="NZ">New Zealand</option>
										<option value="NI">Nicaragua</option>
										<option value="NE">Niger</option>
										<option value="NG">Nigeria</option>
										<option value="NU">Niue</option>
										<option value="NF">Norfolk Island</option>
										<option value="KP">North Korea</option>
										<option value="NO">Norway</option>
										<option value="OM">Oman</option>
										<option value="PK">Pakistan</option>
										<option value="PS">Palestinian Territory</option>
										<option value="PA">Panama</option>
										<option value="PG">Papua New Guinea</option>
										<option value="PY">Paraguay</option>
										<option value="PE">Peru</option>
										<option value="PH">Philippines</option>
										<option value="PN">Pitcairn</option>
										<option value="PL">Poland</option>
										<option value="PT">Portugal</option>
										<option value="QA">Qatar</option>
										<option value="IE">Republic of Ireland</option>
										<option value="RE">Reunion</option>
										<option value="RO">Romania</option>
										<option value="RU">Russia</option>
										<option value="RW">Rwanda</option>
										<option value="ST">S&atilde;o Tom&eacute; and Pr&iacute;ncipe</option>
										<option value="BL">Saint Barth&eacute;lemy</option>
										<option value="SH">Saint Helena</option>
										<option value="KN">Saint Kitts and Nevis</option>
										<option value="LC">Saint Lucia</option>
										<option value="SX">Saint Martin (Dutch part)</option>
										<option value="MF">Saint Martin (French part)</option>
										<option value="PM">Saint Pierre and Miquelon</option>
										<option value="VC">Saint Vincent and the Grenadines</option>
										<option value="SM">San Marino</option>
										<option value="SA">Saudi Arabia</option>
										<option value="SN">Senegal</option>
										<option value="RS">Serbia</option>
										<option value="SC">Seychelles</option>
										<option value="SL">Sierra Leone</option>
										<option value="SG">Singapore</option>
										<option value="SK">Slovakia</option>
										<option value="SI">Slovenia</option>
										<option value="SB">Solomon Islands</option>
										<option value="SO">Somalia</option>
										<option value="ZA">South Africa</option>
										<option value="GS">South Georgia/Sandwich Islands</option>
										<option value="KR">South Korea</option>
										<option value="SS">South Sudan</option>
										<option value="ES">Spain</option>
										<option value="LK">Sri Lanka</option>
										<option value="SD">Sudan</option>
										<option value="SR">Suriname</option>
										<option value="SJ">Svalbard and Jan Mayen</option>
										<option value="SZ">Swaziland</option>
										<option value="SE">Sweden</option>
										<option value="CH">Switzerland</option>
										<option value="SY">Syria</option>
										<option value="TW">Taiwan</option>
										<option value="TJ">Tajikistan</option>
										<option value="TZ">Tanzania</option>
										<option value="TH">Thailand</option>
										<option value="TL">Timor-Leste</option>
										<option value="TG">Togo</option>
										<option value="TK">Tokelau</option>
										<option value="TO">Tonga</option>
										<option value="TT">Trinidad and Tobago</option>
										<option value="TN">Tunisia</option>
										<option value="TR">Turkey</option>
										<option value="TM">Turkmenistan</option>
										<option value="TC">Turks and Caicos Islands</option>
										<option value="TV">Tuvalu</option>
										<option value="UG">Uganda</option>
										<option value="UA">Ukraine</option>
										<option value="AE">United Arab Emirates</option>
										<option value="GB">United Kingdom (UK)</option>
										<option value="US">United States (US)</option>
										<option value="UY">Uruguay</option>
										<option value="UZ">Uzbekistan</option>
										<option value="VU">Vanuatu</option>
										<option value="VA">Vatican</option>
										<option value="VE">Venezuela</option>
										<option value="VN">Vietnam</option>
										<option value="WF">Wallis and Futuna</option>
										<option value="EH">Western Sahara</option>
										<option value="WS">Western Samoa</option>
										<option value="YE">Yemen</option>
										<option value="ZM">Zambia</option>
										<option value="ZW">Zimbabwe</option>
									</select>                 
									 </div>
                 					 </div>
<?php
if($assign_customer_to_staff == 1)
{
?>
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Officer (Optional)</label>
                  <div class="col-sm-10">
				<select name="lofficer"  class="form-control select2">
										<option value="" selected='selected'>Assign Staff&hellip;</option>
										<?php
$search = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id'");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['id']; ?>"><?php echo $get_search['name'].' ('.$get_search['country'].')'; ?></option>
<?php } ?>
				</select>
				<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"> You can assign customers to the above accoun officers. This will allow you to download the collection sheet for each staff and the staff will know which customers to work with. </span><br>
		</div>
		</div>
<?php
}
else{
    ?>
  
    <input name="lofficer" type="hidden" class="form-control" value="<?php echo $iuid; ?>"/>    
    
<?php
}
?>


<?php
if($assign_customer_to_branch == 1)
{
?>
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Branch</label>
                  <div class="col-sm-10">
				<select name="branch"  class="form-control select2">
							<option value="">Head Office</option>
										<?php
$search = mysqli_query($link, "SELECT * FROM branches WHERE created_by = '$institution_id'");
while($get_search = mysqli_fetch_array($search))
{
?>
					<option value="<?php echo $get_search['branchid']; ?>"><?php echo $get_search['bname']; ?></option>
<?php } ?>
				</select>
		</div>
		</div>
		
<?php
}
else{
    ?>
  
    <input name="branch" type="hidden" class="form-control" value="<?php echo $isbranchid; ?>"/>    
    
<?php
}
?>



<?php
if($enable_otp == 1)
{
?>
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Enable OTP</label>
                  <div class="col-sm-10">
				<select name="otp"  class="form-control select2">
				            <option value="No">No</option>
							<option value="Yes">Yes</option>
				</select>
				<span style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;">Enable OTP for Withdrawal Option</span>
		</div>
		</div>
<?php
}
else{
?>

		<input name="otp" type="hidden" class="form-control" value="No"/> 

<?php
}
?>


<?php
if($enable_overdraft == 1)
{
?>
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Allow Overdraft</label>
                  <div class="col-sm-10">
				<select name="overdraft"  class="form-control select2">
				            <option value="No">No</option>
							<option value="Yes">Yes</option>
				</select>
		</div>
		</div>
		<?php
}
else{
?>

		<input name="overdraft" type="hidden" class="form-control" value="No"/> 

<?php
}
?>


<?php
if($isms_checker == "On")
{
?>
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Allow SMS Notification</label>
                  <div class="col-sm-10">
				<select name="smsChecker"  class="form-control select2" required>
							<option value="Yes">Yes</option>
							<option value="No">No</option>
				</select>
		</div>
		</div>
		<?php
}
else{
?>

		<input name="smsChecker" type="hidden" class="form-control" value="Yes"/> 

<?php
}
?>

		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Username</label>
                  <div class="col-sm-10">
                  <input name="username" type="text" class="form-control" id="vbusername" onkeyup="veryBUsername();" placeholder="Username" required>
                  <div id="mybusername"></div>
                  </div>
                  </div>
                  
<hr>
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">NEXT OF KIN DETAILS</div>
<hr>
                  
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Next of Kin</label>
                  <div class="col-sm-10">
                  <input name="nok" type="text" class="form-control" placeholder="Next of Kin">
                  </div>
                  </div>   
        
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Relationship</label>
                  <div class="col-sm-10">
                  <input name="nok_rela" type="text" class="form-control" placeholder="Next of Kin">
                  </div>
                  </div> 
                 
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Phone</label>
                  <div class="col-sm-10">
                  <input name="nok_phone" type="text" class="form-control" placeholder="Next of Kin Phone Number">
                  </div>
                  </div>

<?php
if($isavings_account == "On")
{
?>

<hr>
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">SET SAVINGS STRUCTURE</div>
<hr>
        
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Savings Interval</label>
                  <div class="col-sm-10">
				<select name="s_interval" class="form-control select2">
					<option value="" selected='selected'>Select Interval&hellip;</option>	
					<option value="daily">Daily</option>
					<option value="weekly">Weekly</option>
					<option value="monthly">Monthly</option>
				</select>
		</div>
		</div>
		
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Savings Amount</label>
                  <div class="col-sm-10">
                  <input name="s_amount" type="text" class="form-control" placeholder="Enter Savings Amount">
                  </div>
                  </div>
                  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Charge Interval</label>
                  <div class="col-sm-10">
				<select name="c_interval" class="form-control select2">
					<option value="" selected='selected'>Select Interval&hellip;</option>	
					<option value="weekly">Weekly</option>
					<option value="monthly">Monthly</option>
					<option value="yearly">Yearly</option>
				</select>
		</div>
		</div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Charge Amount</label>
                  <div class="col-sm-10">
					<select name="chargesAmount"  class="form-control select2">
						<option value="" selected>Select Charges</option>
						<?php
						$search_mycharges = mysqli_query($link, "SELECT * FROM charge_management WHERE companyid = '$institution_id'");
						while($fetch_mycharges = mysqli_fetch_object($search_mycharges))
						{
						?>
						<option value="<?php echo $fetch_mycharges->id; ?>"><?php echo $fetch_mycharges->charges_name.'('.$fetch_mycharges->charges_value.' - ['.$fetch_mycharges->charges_type.'])'; ?></option>
						<?php } ?>
					</select>
				</div>
            </div>

		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Disbursement Interval</label>
                  <div class="col-sm-10">
				<select name="d_interval" class="form-control select2">
					<option value="" selected='selected'>Select Interval&hellip;</option>	
					<option value="weekly">Weekly</option>
					<option value="monthly">Monthly</option>
					<option value="yearly">Yearly</option>
				</select>
		</div>
		</div>

		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Disbursement Channel</label>
                  <div class="col-sm-10">
				<select name="d_channel" class="form-control select2" id="d_channel">
					<option value="" selected='selected'>Select Channel&hellip;</option>	
					<option value="Prepaid_card">Prepaid Card</option>
					<option value="Bank">Bank</option>
					<option value="Wallet">Wallet</option>
				</select>
		</div>
		</div>

		<span id='ShowValueFrank50'></span>
  		<span id='ShowValueFrank50'></span>

		
<?php
}
else{
    echo "";
}
?>

<hr>
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">UPLOAD GOVERNMENT ISSUED ID, UTILITY BILL ETC.</div>
<hr>

			<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Upload Documents</label>
                <div class="col-sm-10">
                  <input name="uploaded_file[]" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" multiple/>
                </div>
            </div>
<!--
		
<hr>
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">Upload Scanned Copy of the Registration Form Filled by the Customer</div>
<hr>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Scanned Copy</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="c_sign" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" onChange="readURL(this);"/>
		</div>
		</div>
-->

			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button type="reset" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-times">&nbsp;Reset</i></button>
                				<button name="save" type="submit" class="btn bg-blue"><i class="fa fa-plus">&nbsp;Submit</i></button>

              </div>
			  </div>
			  
			 </form> 
			 
              </div>
              <!-- /.tab-pane -->
	<?php
	}
	elseif($tab == 'tab_2')
	{
	?>
              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">
				  
				   <form class="form-horizontal" method="post" enctype="multipart/form-data">
					   
<div class="box-body">

<?php
if((startsWith($institution_id,"INST") || startsWith($institution_id,"MER")) && $isavings_account === "On")
{
?>	
	            

      			
<?php
}
else{
    echo "";
}
?>

<?php
if(isset($_POST["Import"])){
    
    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $fetch_memset = mysqli_fetch_array($search_memset);
    //$sys_abb = $get_sys['abb'];
    $mobileapp_link = ($fetch_memset['mobileapp_link'] == "") ? "Login at https://esusu.app/$isenderid" : "Download mobile app: ".$fetch_memset['mobileapp_link'];
	
	$refid = "EA-custReg-".time().uniqid();
    $cust_charges = ($ifetch_maintenance_model['cust_mfee'] == "" || $ifetch_maintenance_model['cust_mfee'] == "0") ? 0 : $ifetch_maintenance_model['cust_mfee'];
    $smsfee = $fetchsys_config['fax'];
	$myiwallet_balance = $iwallet_balance - $cust_charges;
	$maintenance_row = mysqli_num_rows($isearch_maintenance_model);
	$debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes"; 
	$transactionPin = substr((uniqid(rand(),1)),3,4);
	
	if($idedicated_ledgerAcctNo_prefix == ""){
		echo "<p style='font-size:24px; color:orange;'>Sorry! The ledger account number prefix is not yet configure...Kindly contact us to so!!</p>";
	}
    elseif($_FILES['file']['name']){
        
        $filename = explode('.', $_FILES['file']['name']);
        
        if($filename[1] == 'csv'){
            
            $handle = fopen($_FILES['file']['tmp_name'], "r");
            fgetcsv($handle,1000,","); // pop the headers
            while($data = fgetcsv($handle,1000,",")){
                
                $empty_filesop = array_filter(array_map('trim', $data));
                
                if(!empty($empty_filesop)){

                    $snum = mysqli_real_escape_string($link, $data[0]);
                    $fname = ucwords(mysqli_real_escape_string($link, $data[1]));
                    $lname = ucwords(mysqli_real_escape_string($link, $data[2]));
                    $mname = ucwords(mysqli_real_escape_string($link, $data[3]));
                    $email = str_replace(' ', '', mysqli_real_escape_string($link, $data[4]));
                    $phone = (strpos(mysqli_real_escape_string($link, $data[5]), '+234') === 0 ? str_replace(' ', '', mysqli_real_escape_string($link, $data[5])) : (strpos(mysqli_real_escape_string($link, $data[5]), '234') === 0 ? "+".str_replace(' ', '', mysqli_real_escape_string($link, $data[5])) : (strpos(mysqli_real_escape_string($link, $data[5]), '0') === 0 ?  "+234".str_replace('0', '', mysqli_real_escape_string($link, $data[5])) : ($data[5] == "" ? "" : "+234".str_replace(' ', '', mysqli_real_escape_string($link, $data[5])))))); //NEW UPDATE DONE HERE
                    $gender = (($data[6] == "F") ? "Female" : (($data[6] == "M") ? "Male" : ucwords(mysqli_real_escape_string($link, $data[6])))); //NEW UPDATE DONE HERE
                    $dob = ($data[7] == "") ? "" : reformatDate(mysqli_real_escape_string($link, $data[7]));
                    $occupation = mysqli_real_escape_string($link, $data[8]);
                    $address = mysqli_real_escape_string($link, $data[9]);
                    $city = ucwords(mysqli_real_escape_string($link, $data[10]));
                    $state = ucwords(mysqli_real_escape_string($link, $data[11]));
                    $country = ($data[12] == "NIGERIA" || $data[12] == "Nigeria" || $data[12] == "nigeria") ? "NG" : strtoupper(mysqli_real_escape_string($link, $data[12])); //NEW UPDATE DONE HERE
                    $next_of_kin = mysqli_real_escape_string($link, $data[13]);
                    $next_of_kin_rela = mysqli_real_escape_string($link, $data[14]);
                    $next_of_kin_phone = mysqli_real_escape_string($link, $data[15]);
                    $myusername = mysqli_real_escape_string($link, $data[16]);
					$username = ($myusername == "") ? $fname : $myusername;
                    $password = substr((uniqid(rand(),1)),3,6);
                    $currency = str_replace(' ', '', strtoupper(mysqli_real_escape_string($link, $data[17])));
					$account = $idedicated_ledgerAcctNo_prefix.substr((uniqid(rand(),1)),4,8);

                    $s_contribution_interval = ucwords(mysqli_real_escape_string($link, $data[18]));
                    $savings_amount = mysqli_real_escape_string($link, $data[19]);
                    $charge_interval = ucwords(mysqli_real_escape_string($link, $data[20]));
                    $chargesAmount = mysqli_real_escape_string($link, $data[21]);
                    $disbursement_interval = ucwords(mysqli_real_escape_string($link, $data[22]));
                    $disbursement_channel = mysqli_real_escape_string($link, $data[23]);
					$savingsProductCode = mysqli_real_escape_string($link, $data[24]);
					$groupCode = mysqli_real_escape_string($link, $data[25]);
					$savingsBalance = mysqli_real_escape_string($link, $data[26]);
					$targetSavingsBal = mysqli_real_escape_string($link, $data[27]);
					$investementBal = mysqli_real_escape_string($link, $data[28]);
					$loanBal = mysqli_real_escape_string($link, $data[29]);
					$assetAcquisitionBal = mysqli_real_escape_string($link, $data[30]);
					$branchCode = mysqli_real_escape_string($link, $data[31]);
					$status = ($allow_auth == "Yes") ? "Not-Activated" : "queue";
					$myAccountNumber = "----";
					
					$searchAccountType = mysqli_query($link, "SELECT * FROM lgroup_setup WHERE id = '$groupCode'");
					$fetchAccountType = mysqli_fetch_array($searchAccountType);
                    $gname = ($fetchAccountType['gname'] == "") ? "" : $fetchAccountType['gname'];
                    $g_position = ($fetchAccountType['gname'] == "") ? "" : "member";
                    
                    $searchAccountType = mysqli_query($link, "SELECT * FROM account_type WHERE id = '$savingsProductCode'");
					$fetchAccountType2 = mysqli_fetch_array($searchAccountType);
					$acct_type = ($fetchAccountType2['acct_name'] == "") ? "Regular Savings" : $fetchAccountType2['acct_name'];
					$reg_type = ($fetchAccountType2['gname'] == "") ? "Individual" : "Group";
                                      
                    $opening_date = date("Y-m-d");
                    $wallet_date_time = date("Y-m-d H:i:s");
                	
                    if($iwallet_balance < $cust_charges && mysqli_num_rows($isearch_maintenance_model) == 1){
                        echo "<script>alert('Sorry, You are unable to add more customers due to insufficient fund in your Wallet!!'); </script>";
                    }
                    elseif(($iwallet_balance >= $cust_charges && mysqli_num_rows($isearch_maintenance_model) == 1) || mysqli_num_rows($isearch_maintenance_model) == 0){
                    
                        $sql = "INSERT INTO borrowers(id,snum,fname,lname,mname,email,phone,gender,dob,occupation,addrs,city,state,zip,country,nok,nok_rela,nok_phone,community_role,account,username,password,balance,target_savings_bal,investment_bal,loan_balance,asset_acquisition_bal,image,date_time,last_withdraw_date,status,lofficer,c_sign,branchid,sbranchid,acct_status,s_contribution_interval,savings_amount,charge_interval,chargesAmount,disbursement_interval,disbursement_channel,auto_disbursement_status,auto_charge_status,next_charge_date,next_disbursement_date,recipient_id,opt_option,currency,wallet_balance,overdraft,card_id,card_reg,card_issurer,tpin,reg_type,gname,gposition,acct_type,expected_fixed_balance,acct_opening_date,unumber,verve_expiry_date,employer,virtual_number,virtual_acctno,bankname,dedicated_ussd_prefix,evn,sms_checker,ws_interval,ave_savings_amt,ws_duration,ws_frequency,mmaidenName,moi,lga,otherInfo,nok_addrs,name_of_trustee,sendSMS,sendEmail) VALUES(null,'$snum','$fname','$lname','$mname','$email','$phone','$gender','$dob','$occupation','$address','$city','$state','','$country','$next_of_kin','$next_of_kin_rela','$next_of_kin_phone','Borrower','$account','$username','$password','$savingsBalance','$targetSavingsBal','$investementBal','$loanBal','$assetAcquisitionBal','',NOW(),'0000-00-00','Completed','','','$institution_id','$branchCode','$status','$s_contribution_interval','$savings_amount','$charge_interval','$chargesAmount','$disbursement_interval','$disbursement_channel','NotActive','NotActive','','','','No','$currency','0.0','No','NULL','No','NULL','$transactionPin','$reg_type','$gname','$g_position','$acct_type','0.0','$opening_date','','','','','','','$idedicated_ussd_prefix','','Yes','','','','','','','','','','','0','0')";
						$result = mysqli_query($link,$sql);

                        if(!$result)
            			{
            				echo "<script type=\"text/javascript\">
            					alert(\"Invalid File:Please Upload CSV File.\");
            				    </script>".mysqli_error($link);
            			}
            			
                    }
                }
            }
            fclose($handle);
            echo "<script type=\"text/javascript\">
						alert(\"Customers Information has been successfully Imported.\");
					</script>";
            
        }
        
    }
    
}
?>
      			
	<div class="form-group">
           <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Import Customer:</label>
	<div class="col-sm-10">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="file" accept=".csv" required>
	</div>
	</div>
	
	<div class="form-group">
           <label for="" class="col-sm-2 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">NOTE:</label>
	<div class="col-sm-10">
        <span style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">(1)</span> <i>Kindly download the <a href="../sample/borrowers_sample.csv"> <b class="btn bg-blue"><i class="fa fa-download"></i> sample file</b></a> to upload the details once.</i>
	</div>
	</div>
	
    <div align="right">
       <div class="box-footer">
	     <button class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> ks-btn-file" type="submit" name="Import"><span class="fa fa-cloud-upload"></span> Import Customer Details</button> 
       </div>
    </div>  

</div>	

<?php
/*if(isset($_POST["Import"])){

		echo $filename=$_FILES["file"]["tmp_name"];
		$acct_type = ((startsWith($institution_id,"INST") || startsWith($institution_id,"MER")) && $isavings_account === "On") ? mysqli_real_escape_string($link, $_POST['acct_type']) : "";
        $reg_type = ((startsWith($institution_id,"INST") || startsWith($institution_id,"MER")) && $isavings_account === "On") ? mysqli_real_escape_string($link, $_POST['reg_type']) : "";
        $gname =  ((startsWith($institution_id,"INST") || startsWith($institution_id,"MER")) && $isavings_account === "On") ? mysqli_real_escape_string($link, $_POST['gname']) : "";
        $g_position =  ((startsWith($institution_id,"INST") || startsWith($institution_id,"MER")) && $isavings_account === "On") ? mysqli_real_escape_string($link, $_POST['g_position']) : "";
        
		$allowed_filetypes = array('csv');
		if(!in_array(end(explode(".", $_FILES['file']['name'])), $allowed_filetypes))
		    {
				echo "<script type=\"text/javascript\">
						alert(\"The file you attempted to upload is not allowed.\");
					</script>";
		    }    
		elseif($_FILES["file"]["size"] > 0)
		 {
		  	$file = fopen($filename, "r");
	         while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
	         {
	          $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
              $fetch_memset = mysqli_fetch_array($search_memset);
              //$sys_abb = $get_sys['abb'];
              $sysabb = $fetch_memset['sender_id'];
              
              $account = '10'.rand(10000000,99999999);
              $phone = "+".$emapData[4];
              $dob = date("Y-m-d", strtotime($emapData[6]));
              
              $refid = "EA-custReg-".time();
              $cust_charges = ($ifetch_maintenance_model['cust_mfee'] == "") ? $fetchsys_config['fax'] : $ifetch_maintenance_model['cust_mfee'];
              $myiwallet_balance = $iwallet_balance - $cust_charges;
            
              $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
              $fetch_gateway = mysqli_fetch_object($search_gateway);
              $gateway_uname = $fetch_gateway->username;
              $gateway_pass = $fetch_gateway->password;
              $gateway_api = $fetch_gateway->api;
              
              $opening_date = date("Y-m-d");
              $wallet_date_time = date("Y-m-d H:i:s");
            	
              $sms = "$sysabb>>>Welcome $fname! Your Account ID: $account, Username: $username, Password: $password, Transaction Pin: 0000. Download App at bit.ly/esusuafrica_app";
              
              if($iwallet_balance < $cust_charges && mysqli_num_rows($isearch_maintenance_model) == 1){
                    echo "<script>alert('Sorry, You are unable to add more customers due to insufficient fund in your Wallet!!'); </script>";
              }
              elseif(($iwallet_balance >= $cust_charges && mysqli_num_rows($isearch_maintenance_model) == 1) || mysqli_num_rows($isearch_maintenance_model) == 0){
                  //It wiil insert a row to our borrowers table from our csv file`
    	          $sql = "INSERT INTO borrowers(id,snum,fname,lname,email,phone,gender,dob,occupation,addrs,city,state,zip,country,nok,nok_rela,nok_phone,community_role,account,username,password,balance,investment_bal,image,date_time,last_withdraw_date,status,lofficer,c_sign,branchid,sbranchid,acct_status,s_contribution_interval,savings_amount,auto_debit_option,charge_interval,last_charge_date,s_disbursement_interval,opt_option,currency,wallet_balance,overdraft,card_id,card_reg,card_issurer,tpin,reg_type,gname,gposition,acct_type,expected_fixed_balance,acct_opening_date,unumber,verve_expiry_date,employer,virtual_number,virtual_acctno,dedicated_ussd_prefix) VALUES(null,'$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]','$phone','$emapData[5]','$dob','$emapData[7]','$emapData[8]','$emapData[9]','$emapData[10]','','$emapData[11]','$emapData[12]','$emapData[13]','$emapData[14]','Borrower','$account','$emapData[15]','$emapData[16]','0.0','0.0','',NOW(),'0000-00-00','Pending','','','$institution_id','$isbranchid','Activated','','0','No','','','','No','$emapData[17]','0.0','No','NULL',No,'NULL','0000','$reg_type','$gname','$g_position','','0.0','$opening_date','','','','','','$idedicated_ussd_prefix')";
    	         //$insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$refid','self','$cust_charges','NGN','Wallet','Description: Maintenance fee for Customer Registration','successful','$wallet_date_time'");
                  //$insert = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$myiwallet_balance' WHERE institution_id = '$institution_id'");
    	         //we are using mysql_query function. it returns a resource on true else False on error
    	          $result = mysqli_query($link,$sql);
    	          
    	          //include('../cron/send_general_sms.php');
    			  if(!$result)
    			  {
    				echo "<script type=\"text/javascript\">
    					alert(\"Invalid File:Please Upload CSV File.\");
    				    </script>";
    			  }
    			  //include('../cron/send_general_sms.php');
              }
	         }
	         fclose($file);
	         //throws a message if data successfully imported to mysql database from excel file
	         echo "<script type=\"text/javascript\">
						alert(\"Customers Information has been successfully Imported.\");
					</script>";
		 }
	}*/	 
?>    
				   </form>
<!--
<hr>
<div class="bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">&nbsp;<b> IMAGE SECTION </b></div>
<hr>
           <form class="form-horizontal" method="post" enctype="multipart/form-data">
             
<div class="box-body">
    
  <div class="form-group">
           <label for="" class="col-sm-4 control-label" style="color: <?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Import Bulk Logos Here:</label>
  <div class="col-sm-8">
        <span class="fa fa-cloud-upload"></span>
           <span class="ks-text">Choose file</span>
           <input type="file" name="uploaded_file[]" multiple required>
  </div>
  </div>

   <hr>
  <p style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><b style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">NOTICE:</b><br>
  <hr>
  
    <div align="right">
       <div class="box-footer">
       <button class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> ks-btn-file" type="submit" name="Import_logo"><span class="fa fa-cloud-upload"></span> Import Picture</button> 
       </div>
    </div>  

</div>  

<div class="scrollable">
<?php
if(isset($_POST["Import_logo"])){

    echo $filename=$_FILES["file"]["tmp_name"];
    foreach ($_FILES['uploaded_file']['name'] as $key => $name){
 
    $newFilename = $name;
    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], '../img/'.$newFilename))
    {
      echo "<p><span style='color: blue'><i>[".$newFilename."]</i></span> <span style='color: red;'>uploaded successfully...</span></p>";
    }
    else{
      echo "<script type=\"text/javascript\">
              alert(\"Error....Please try again later\");
            </script>";
    }
    }  
   
  }  
?> 
</div> 
-->
           </form>
      </div>
	<?php
	}
}
?>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
			
          </div>					
			</div>
		
              </div>
	
</div>	
</div>
</div>
</section>	
</div>