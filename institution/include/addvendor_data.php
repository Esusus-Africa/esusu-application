<div class="box">
        
		 <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><i class="fa fa-user"></i> New Vendor</h3>
            </div>
             <div class="box-body">
<?php
if(isset($_POST['vendor']))
{
	//include("../config/restful_apicalls.php");
	//Vendor Records
	$vendorID =  mysqli_real_escape_string($link, $_POST['vendorID']);
	$companyname = mysqli_real_escape_string($link, $_POST['cname']);
	$ctype = mysqli_real_escape_string($link, $_POST['ctype']);
	$cdesc = mysqli_real_escape_string($link, $_POST['cdesc']);
	$caddrs =  mysqli_real_escape_string($link, $_POST['caddrs']);
	$official_email =  mysqli_real_escape_string($link, $_POST['cemail']);
	$official_phone = mysqli_real_escape_string($link, $_POST['cphone']);
	$currency = mysqli_real_escape_string($link, $_POST['currency']);
	$username = mysqli_real_escape_string($link, $_POST['cusername']);
	$apiset = mysqli_real_escape_string($link, $_POST['apiset']);
	$id = "MEM".time();
	//$rave_skey = mysqli_real_escape_string($link, $_POST['rave_skey']);
	//$rave_pkey = mysqli_real_escape_string($link, $_POST['rave_pkey']);
	//$rave_status = mysqli_real_escape_string($link, $_POST['rave_status']);
	$fname = mysqli_real_escape_string($link, $_POST['fname']);
	$mname = mysqli_real_escape_string($link, $_POST['mname']);
	$lname = mysqli_real_escape_string($link, $_POST['lname']);
	$gender = mysqli_real_escape_string($link, $_POST['gender']);
	$password = date("dy").rand(100000,999999);
	$encrypt = base64_encode($password);
	$v_subaccount = mysqli_real_escape_string($link, $_POST['v_subaccount']);
	//$auth_subaccount = mysqli_real_escape_string($link, $_POST['auth_subaccount']);
	
	//BILLING
	//$billtype = mysqli_real_escape_string($link, $_POST['billtype']);
	//$sms_mfee = mysqli_real_escape_string($link, $_POST['sms_mfee']);
	//$lbooking = mysqli_real_escape_string($link, $_POST['lbooking']);
	//$tcharges_type = mysqli_real_escape_string($link, $_POST['tcharges_type']);
	//$t_charges = mysqli_real_escape_string($link, $_POST['t_charges']);
	//$capped_amt = mysqli_real_escape_string($link, $_POST['capped_amt']);

	$account_number =  mysqli_real_escape_string($link, $_POST['acct_no']);
	$bank_code =  mysqli_real_escape_string($link, $_POST['bank_code']);

	$search_bankname = mysqli_query($link, "SELECT * FROM bank_list WHERE bankcode = '$bank_code'");
    $fetch_bankname = mysqli_fetch_array($search_bankname);
    $mybank_name = $fetch_bankname['bankname'];
  
  //SYSTEMSET
  $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
	$r = mysqli_fetch_object($query);
	$sys_abb = $msenderid;
	$sys_email = $r->email;
    
  //SMS CHARGES
  $refid = "EA-smsAlert-".time();
  $sms_charges = $r->fax;;

	$verify_email = mysqli_query($link, "SELECT * FROM vendor_reg WHERE cemail = '$official_email'");
	$detect_email = mysqli_num_rows($verify_email);

	$verify_phone = mysqli_query($link, "SELECT * FROM vendor_reg WHERE cphone = '$cphone'");
	$detect_phone = mysqli_num_rows($verify_phone);
	
	$verify_sid = mysqli_query($link, "SELECT * FROM member_settings WHERE sender_id = '$my_senderid'");
	$detect_sid= mysqli_num_rows($verify_sid);

  $maintenance_row = mysqli_num_rows($isearch_maintenance_model);
  $debitWAllet = ($getSMS_ProviderNum == 1 || ($maintenance_row == 1 && $billing_type == "PAYGException")) ? "No" : "Yes";

	if($detect_email == 1){
		echo'<span class="itext" style="color: #FF0000">Sorry, Email Address has already been used.</span>';
	}
	elseif($detect_phone == 1){
		echo'<span class="itext" style="color: #FF0000">Sorry, Phone Number has already been used.</span>';
	}
	elseif($detect_username == 1){
		echo'<span class="itext" style="color: #FF0000">Sorry, Username has already been used.</span>';
	}
  elseif($mwallet_balance < $sms_charges && mysqli_num_rows($msearch_maintenance_model) == 1){
    echo "<script>alert('Sorry, You are unable to add more vendors due to insufficient fund in your Wallet for SMS Notification!!'); </script>";
  }
	else{

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
		$datetime = date("Y-m-d h:i:s");
	
    $insert_records = mysqli_query($link, "INSERT INTO vendor_reg VALUES(null,'$institution_id','$vendorID','$companyname','$location','$ctype','$cdesc','$caddrs','$official_email','$official_phone','$username','$encrypt','0.0','0.0','0000','','','','$currency',NOW(),'$v_subaccount','','$apiset','$account_number','$mybank_name')") or die ("Error: " . mysqli_error($link));
    $insert_records = mysqli_query($link, "INSERT INTO user VALUES(null,'$fname','$lname','$mname','$official_email','$official_phone','$caddrs','','','','','','Approved','$username','$encrypt','$id','$location','vendor_manager','$vendorID','Registered','$institution_id','VEN','0000','0.0','','0.0','','','','$idedicated_ussd_prefix','$gender','','Allow','Allow','Allow','$datetime','','','','','Pending','agent','','NULL','No','NULL')") or die ("Error: " . mysqli_error($link));
		//$insert_records = mysqli_query($link, "INSERT INTO maintenance_history VALUE(null,'$vendorID','$sms_charges','50','flat','10','2000','Activated','PAYG','50','','50','30','30')") or die ("Error: " . mysqli_error($link));
		
		$sms = "$sys_abb>>>Welcome $companyname! Your Vendor ID is: $vendorID, Transaction Pin is: 0000. Logon to your email for your login details. Our URL is: https://esusu.app/$sys_abb .";

    $max_per_page = 153;
    $sms_length = strlen($sms);
    $calc_length = ceil($sms_length / $max_per_page);
    
    //$sms_rate = $r->fax;
    $final_sms_charges = $calc_length * $sms_charges;
	  $mywallet_balance = $iwallet_balance - $final_sms_charges;
		$currecntDateTime = date("Y-m-d h:i:s");
    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $customUrl = $protocol . $_SERVER['HTTP_HOST'] . '/' . $sys_abb;

    //SMS NOTIFICATION
	  ($billing_type == "PAYGException" ? "" : ($debitWAllet == "No" ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sys_abb, $official_phone, $sms, $institution_id, $refid, $final_sms_charges, $iuid, $mywallet_balance, $debitWallet) : ($debitWAllet == "Yes" && $final_sms_charges <= $iwallet_balance ? $sendSMS->instGeneralAlert($ozeki_password, $ozeki_url, $sys_abb, $official_phone, $sms, $institution_id, $refid, $final_sms_charges, $iuid, $mywallet_balance, $debitWallet) : "")));
    $sendSMS->vendorRegtNotifier($official_email, $companyname, $username, $password, $customUrl, $iemailConfigStatus, $ifetch_emailConfig);
		
    echo "<div class='alert alert-success'>Account Created Successfully!...An email / sms notification has been sent to the Vendor</div>";
		
	}
}
?>
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			
<?php
$vendorID = 'VEND-'.time();
?>
      		<input name="vendorID" type="hidden" class="form-control" value="<?php echo $vendorID; ?>">

			
			<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Logo</label>
			<div class="col-sm-10">
  		  			 <input type='file' name="image" onChange="readURL(this);" />
       				 <img id="blah"  src="../avtar/user2.png" alt="Image Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Name</label>
                  <div class="col-sm-10">
                  <input name="cname" type="text" class="form-control" placeholder="Company Name" required>
                  </div>
                  </div>
                  
      <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Type</label>
                  <div class="col-sm-10">
				            <select name="ctype"  class="form-control select2" required>
										  <option value="" selected>Select Company Type</option>
                      <option value="Asset Management">Asset Management</option>
                      <option value="Takaful Insurance">Takaful Insurance</option>
                      <option value="HMO">HMO</option>
                      <option value="Halal Investment">Halal Investment</option>
                      <option value="Pension Finance">Pension Finance</option>
                      <option value="Mortgage">Mortgage</option>
                      <option value="Pension">Pension</option>
                      <option value="Onlending Firm">Onlending Firm</option>
										</select>                 
									 </div>
                 					 </div>
                            
    <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Description</label>
                  	<div class="col-sm-10">
					<textarea name="cdesc"  class="form-control" rows="2" cols="80" required></textarea>
           			 </div>
          </div>
          
    <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Address</label>
                  	<div class="col-sm-10">
					<textarea name="caddrs"  class="form-control" id="autocomplete1" onFocus="geolocate()" rows="2" cols="80" required></textarea>
           			 </div>
          </div>
				  
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Email</label>
                  <div class="col-sm-10">
                  <input type="email" name="cemail" type="text" id="vemail" onkeyup="veryEmail();" class="form-control" placeholder="Company Email Address" required>
                  <div id="myvemail"></div>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Company Phone</label>
                  <div class="col-sm-10">
                  <input name="cphone" type="text" class="form-control" id="vphone" onkeyup="veryPhone();" placeholder="Company Phone" required>
                  <div id="myvphone"></div>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Currency</label>
                  <div class="col-sm-10">
				            <select name="currency"  class="form-control select2">
										  <option value="" selected>Select Currency</option>
                      <option value="NGN">NGN</option>
                      <option value="USD">USD</option>
					  <option value="GHS">GHS</option>
					  <option value="KES">KES</option>
					  <option value="UGX">UGX</option>
					  <option value="TZS">TZS</option>
										</select>                 
									 </div>
                 					 </div>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">API Notification</label>
                  <div class="col-sm-10">
				    <select name="apiset"  class="form-control select2">
						<option value="" selected>None</option>
						<option value="wellahealth_endpoint">Wellahealth API Notification</option>
					</select>                 
				</div>
            </div>

<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;CONTACT PERSON</div>
<hr>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">First Name</label>
                  <div class="col-sm-10">
                  <input name="fname" type="text" class="form-control" placeholder="First Name" required>
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Middle Name</label>
                  <div class="col-sm-10">
                  <input name="mname" type="text" class="form-control" placeholder="Middle Name">
                  </div>
                  </div>

                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Last Name</label>
                  <div class="col-sm-10">
                  <input name="lname" type="text" class="form-control" placeholder="Last Name" required>
                  </div>
                  </div>

				  <div class="form-group">
                  		<label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gender</label>
				  		<div class="col-sm-10">
                            <select name="gender" class="form-control" required>
                                <option value="" selected='selected'>Select Gender&hellip;</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
					</div>  

				
                 					 

<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;COMMISSION ACCOUNT</div>
<hr>
                 					 
        <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Subaccount Code</label>
                  <div class="col-sm-10">
                  <input name="v_subaccount" type="text" class="form-control" placeholder="Vendor Subaccount Code" required>
                  </div>
				  </div>
				  
<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;SETTLEMENT BANK</div>
<hr>


		<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
            <div class="col-sm-10">
            <select name="country"  class="form-control select2" id="country" onchange="loadbank();" required>
              <option selected>Please Select Country</option>
              <option value="NG">Nigeria</option>
              <option value="GH">Ghana</option>
              <option value="KE">Kenya</option>
              <option value="UG">Uganda </option>
              <option value="TZ">Tanzania</option>
            </select>                 
            </div>
            </div>
				  
          
		<div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Account Number</label>
            <div class="col-sm-10">
                <input name="acct_no" type="text" id="account_number" onkeydown="loadaccount();" class="form-control" placeholder="Account Number" required>
            </div>
    	</div>
                  
        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Bank Name</label>
            <div class="col-sm-10">
                <div id="bank_list"></div>
        	</div>
        </div>
        
        <div class="form-group">
            <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Holder</label></label>
            <div class="col-sm-10">
                <span id="act_numb"></span>
        	</div>
        </div>

<!--								  
<hr>	
<div class="bg-<?php //echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;MAINTENANCE SETTINGS</div>
<hr>

			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Billing Type</label>
                  <div class="col-sm-10">
                  <select name="billtype" class="form-control select2" required style="width:100%">
           <option value="" selected>Select Billing Type</option>
           <option value="PAYG">PAYG</option>
           <option value="Hybrid">Hybrid</option>
          </select>
                  </div>
                  </div>
                  
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Loan Booking</label>
                  <div class="col-sm-10">
                  <input name="lbooking" type="text" class="form-control" placeholder="Maintenance Fee per Loan Booking" required>
                  </div>
                  </div>
                  
<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">ROI Commision Type</label>
                  <div class="col-sm-10">
                  <select name="tcharges_type" class="form-control select2" required style="width:100%">
           <option value="" selected>Select ROI Commission Type</option>
           <option value="flat">flat</option>
           <option value="percentage">percentage</option>
          </select>
                  </div>
                  </div>

 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Commission Charges</label>
                  <div class="col-sm-10">
                  <input name="t_charges" type="text" class="form-control" placeholder="Commission Charges" required>
                  </div>
                  </div>

<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Capped Amount</label>
                  <div class="col-sm-10">
                  <input name="capped_amt" type="text" class="form-control" placeholder="Capped Amount for Commission Charges" required>
                  </div>
                  </div>	
-->									  
			
<hr>	
<div class="bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">&nbsp;LOGIN INFORMATION</div>
<hr>	
					
					 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Username</label>
                  <div class="col-sm-10">
                  <input name="cusername" type="text" class="form-control" id="vusername" onkeyup="veryUsername();" placeholder="Username" required>
                  <div id="myusername"></div>
                  </div>
                  </div>
				 
				 <!-- 
				   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Rave Secret Key</label>
                  <div class="col-sm-10">
                  <input name="rave_skey" type="text" class="form-control" placeholder="Rave Secret Key">
                  </div>
                  </div>
				  
				    <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Rave Public Key</label>
                  <div class="col-sm-10">
                  <input name="rave_pkey" type="text" class="form-control" placeholder="Rave Public Key">
                  </div>
                  </div>
                  
                  
           <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Rave Status</label>
                  <div class="col-sm-10">
				            <select name="rave_status"  class="form-control select2">
										  <option value="" selected>Select Rave Status</option>
                      <option value="Enabled">Enable</option>
                      <option value="Disabled">Disable</option>
										</select>                 
									 </div>
                 					 </div>
                 -->
			  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="vendor" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Save</i></button>

              </div>
			  </div>
			  
			 </form> 

</div>
</div>
</div>
</div>