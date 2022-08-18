<div class="row">	
<?php
require_once "../config/virtualBankAccount_class.php";
require_once "../config/bvnVerification_class.php";
?>
	 <section class="content">
		 
            <h3 class="panel-title"> <a href="listWallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>&&tab=tab_1"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a></h3>
        
		   <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

		 <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="createWallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("404"); ?>&&tab=tab_1">Wallet Creation Form</a></li>
              </ul>
             <div class="tab-content">
<?php
if(isset($_GET['tab']) == true)
{
	$tab = $_GET['tab'];
	if($tab == 'tab_1')
	{
	?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">
           
        <form class="form-horizontal" method="post" enctype="multipart/form-data">
        

        <div align="center">
<?php
if(isset($_POST['indiv_register'])){
    
    $preferredBank = mysqli_real_escape_string($link, $_POST['preferredBank']);
    $acct_type =  mysqli_real_escape_string($link, $_POST['accountType']);
    $fname =  mysqli_real_escape_string($link, $_POST['fname']);
    $lname = mysqli_real_escape_string($link, $_POST['lname']);
    $mname = mysqli_real_escape_string($link, $_POST['mname']);
    $phone = mysqli_real_escape_string($link, $_POST['phone']);
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $password = substr((uniqid(rand(),1)),3,8);
    $dob =  mysqli_real_escape_string($link, $_POST['dob']);
    $gender = mysqli_real_escape_string($link, $_POST['gender']);
    $userBvn = mysqli_real_escape_string($link, $_POST['bvn']);
    $addr1 = mysqli_real_escape_string($link, $_POST['addrs']);
    $origin_province = mysqli_real_escape_string($link, $_POST['state']);
    $status = "Pending";
    $wallet_date_time = date("Y-m-d h:i:s");
    $institution_id = $aggmerchant;

    $ip = $_SERVER['REMOTE_ADDR']; // This will contain the ip of the request
    $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip), true);
    $currencyCode = $dataArray["geoplugin_currencyCode"];
    $origin_countryCode = $dataArray["geoplugin_countryCode"];
    $origin_country = $dataArray["geoplugin_countryName"];
    $origin_city = $dataArray["geoplugin_city"];
    
    $phoneNumber = $phone;
    $country = $origin_countryCode;
    
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $sys_email = $r->email;
    $walletafrica_skey = $r->walletafrica_skey;
    $mo_contract_code = $r->mo_contract_code;
    $ibvn_fee = $r->bvn_fee;
    $sterlinkInputKey = $r->sterlinkInputKey;
    $sterlingIv = $r->sterlingIv;


    $search_gateway = mysqli_query($link, "SELECT * FROM sms WHERE smsuser = '' AND status = 'Activated'");
    $fetch_gateway = mysqli_fetch_object($search_gateway);
    $gateway_uname = $fetch_gateway->username;
    $gateway_pass = $fetch_gateway->password;
    $gateway_api = $fetch_gateway->api;

    $search_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
    $detect_memset = mysqli_fetch_array($search_memset);
    $isenderid = $detect_memset['sender_id'];
    $mobileapp_link = ($detect_memset['mobileapp_link'] == "") ? "Login at https://esusu.app/$isenderid" : "Download mobile app: ".$detect_memset['mobileapp_link'];
    $sysabb = $isenderid;

    $verify_phone = mysqli_query($link, "SELECT * FROM borrowers WHERE phone = '$phone'");	
    $detect_phone = mysqli_num_rows($verify_phone);

    $verify_Uphone = mysqli_query($link, "SELECT * FROM user WHERE phone = '$phone'");	
    $detect_Uphone = mysqli_num_rows($verify_Uphone);

	$verify_username = mysqli_query($link, "SELECT * FROM borrowers WHERE username = '$username'");
	$detect_username = mysqli_num_rows($verify_username);
	
	$verify_Uusername = mysqli_query($link, "SELECT * FROM user WHERE username = '$username'");
    $detect_Uusername = mysqli_num_rows($verify_Uusername);
  
    $ainvCod = (isset($_GET['ainv'])) ? $_GET['ainv'] : "";
    $debitWAllet = ($getSMS_ProviderNum == 1) ? "No" : "Yes";

	if($detect_username == 1 || $detect_Uusername == 1){

	  echo "<p style='font-size:24px; color:orange;'>Sorry, Username has already been used.</p>";
  
    }
    elseif($detect_phone == 1 || $detect_Uphone == 1){
        echo "<p style='font-size:24px; color:orange;'>Sorry, Phone Number has already been used.</p>";
    }
    else{
        $wbalance = $aggwallet_balance - $ibvn_fee;
            
        //print_r($processBVN);

        if($aggwallet_balance < $ibvn_fee){
            echo "<p style='font-size:24px; color:orange;'>Sorry! You do not have sufficient fund in your Wallet for this verification</p>";
        }
        /*elseif($ResponseCode != "200"){
            echo "<p style='font-size:24px; color:orange;'>Sorry, We're unable to verify BVN at the moment, please try again later!! </p>".$ResponseCode;
        }*/
        else{

            $processBVN = $newBV->walletAfricaBVNVerifier($walletafrica_skey,$userBvn,$link);
            $ResponseCode = $processBVN['ResponseCode'];

            $businessName = ($acct_type == "Corporate") ? mysqli_real_escape_string($link, $_POST['businessName']) : "";
            $businessType = ($acct_type == "Corporate") ? mysqli_real_escape_string($link, $_POST['businessType']) : "";
            $otherBusinessType = ($acct_type == "Corporate" && $businessType == "Other") ? mysqli_real_escape_string($link, $_POST['otherBusinessType']) : $businessType;
            $businessPhone = ($acct_type == "Corporate") ? mysqli_real_escape_string($link, $_POST['businessPhone']) : "";
            $rcNumber = ($acct_type == "Corporate") ? mysqli_real_escape_string($link, $_POST['rcNumber']) : "";
            $encrypt = base64_encode($password);
            $global_role = "tqwjr_product_marketer";
            $status = "UnderReview";
            $id = "MEM".time();
            $accountName = ($acct_type == "Corporate") ? $businessName : $fname.' '.$lname.' '.$mname;
            $accountUserId = $id;
            $customerName = $accountName;
            $customerEmail = ($acct_type == "Corporate") ? mysqli_real_escape_string($link, $_POST['businessEmail']) : mysqli_real_escape_string($link, $_POST['email']);
            $acctType = ($acct_type == "Corporate") ? "corporate" : "agent";
            $email = $customerEmail;
            $fullname = $accountName;

            //BVN Details
            $bvn_picture = $processBVN['Picture'];
            $dynamicStr = md5(date("Y-m-d h:i"));
            $image_converted = $newBV->base64_to_jpeg($bvn_picture, $dynamicStr.".png");

            //20 array row
            $mybvn_data = $processBVN['BVN']."|".$processBVN['FirstName']."|".$processBVN['LastName']."|".$processBVN['MiddleName']."|".$processBVN['DateOfBirth']."|".$processBVN['PhoneNumber']."|".$processBVN['Email']."|".$processBVN['EnrollmentBank']."|".$processBVN['EnrollmentBranch']."|".$processBVN['Gender']."|".$processBVN['LevelOfAccount']."|".$processBVN['LgaOfOrigin']."|".$processBVN['LgaOfResidence']."|".$processBVN['MaritalStatus']."|".$processBVN['NameOnCard']."|".$processBVN['Nationality']."|".$processBVN['StateOfOrigin']."|".$processBVN['StateOfResidence']."|".$processBVN['Title']."|".$processBVN['WatchListed']."|".$image_converted;

            $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = $protocol.$_SERVER['HTTP_HOST']."/?id=".$id;
            $ide = time();
            //$shorturl = substr((uniqid(rand(),1)),3,6);
            $shortenedurl = $protocol . $_SERVER['HTTP_HOST'] . '/confirmAcct.php?id='.$mysenderID;
            $otpCode = substr((uniqid(rand(),1)),3,6);
            $shortenedurl1 = $protocol . $_SERVER['HTTP_HOST'] . '/?d_key=' . $otpCode;
            
            ($preferredBank == "Monnify") ? $result = $newVA->monnifyVirtualAccount($accountName,$currencyCode,$customerEmail,$customerName,$userBvn,$mo_contract_code) : "";
            ($preferredBank == "Rubies Bank") ? $result1 = $newVA->rubiesVirtualAccount($accountName,$rubbiesSecKey) : "";
            ($preferredBank == "Flutterwave") ? $result1 = $newVA->wemaVirtualAccount($accountName,$customerEmail,$TxtReference,$rave_secret_key) : "";
            ($preferredBank == "Payant") ? $result1 = $newVA->sterlingPayantVirtualAcct($accountName,$customerEmail,$phoneNumber,$currencyCode,$country,$payantEmail,$payantPwd,$payantOrgId) : "";
            ($preferredBank == "Providus Bank") ? $result1 = $newVA->providusVirtualAccount($accountName,$userBvn,$providusClientId,$providusClientSecret) : "";
            ($preferredBank == "Wema Bank") ? $result1 = $newVA->directWemaVirtualAccount($accountName,$wemaVAPrefix) : "";
            ($preferredBank == "Sterling Bank") ? $result1 = $newVA->sterlingVirtualAccount($fname,$lname,$phoneNumber,$dob,$gender,$currencyCode,$sterlinkInputKey,$sterlingIv) : "";

            ($preferredBank == "Monnify" ? $myAccountReference = $result->responseBody->accountReference : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountReference = "EAVA-".time() : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountReference = $TxtReference : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myAccountReference = $result1['data']['_id'] : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : "")))))));
            ($preferredBank == "Monnify" ? $myAccountName = $result->responseBody->accountName : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountName = $result1['virtualaccountname'] : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountName = $accountName : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myAccountName = $result1['data']['accountName'] : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountName = $result1['account_name'] : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountName = $result1['account_name'] : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $myAccountName = $result1['data']['lastname'].' '.$result1['data']['firstname'] : "")))))));
            ($preferredBank == "Monnify" ? $myAccountNumber = $result->responseBody->accountNumber : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountNumber = $result1['virtualaccount'] : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountNumber = $result1['data']['account_number'] : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myAccountNumber = $result1['data']['accountNumber'] : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountNumber = $result1['account_number'] : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountNumber = $wemaVAPrefix.$result1['account_number'] : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $myAccountNumber = $result1['data']['VIRTUALACCT'] : "")))))));
            ($preferredBank == "Monnify" ? $myBankName = $result->responseBody->bankName : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myBankName = $result1['bankname'] : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myBankName = $result1['data']['bank_name'] : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myBankName = "Sterling Bank" : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myBankName = "Providus Bank" : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $myBankName = "Wema Bank" : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $myBankName = "Sterling Bank" : "")))))));
            ($preferredBank == "Monnify" ? $myStatus = $result->responseBody->status : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myStatus = "ACTIVE" : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myStatus = "ACTIVE" : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myStatus = $result1['data']['status'] : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myStatus = "ACTIVE" : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $myStatus = "ACTIVE" : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $myStatus = "ACTIVE" : "")))))));
            ($preferredBank == "Monnify" ? $date_time = $result->responseBody->createdOn : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $date_time = date("Y-m-d h:i:s") : "")))))));
            ($preferredBank == "Monnify" ? $provider = "monify" : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $provider = "rubies" : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $provider = "rave" : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $provider = "payant" : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $provider = "providus" : (($preferredBank == "Wema Bank") && $result1['responseCode'] == "00" ? $provider = "wema" : (($preferredBank == "Sterling Bank") && $result1['response'] == "00" ? $provider = "sterling" : "")))))));

            $transactionPin = substr((uniqid(rand(),1)),3,4);

            $sms = "$sysabb>>>Welcome $fname! Your Wallet Account Number is: $myAccountNumber, Bank Name: $myBankName, Username: $username, Password: $password and Transaction Pin: $transactionPin. Login here: https://esusu.app/$sysabb";
        
            $max_per_page = 153;
            $sms_length = strlen($sms);
            $calc_length = ceil($sms_length / $max_per_page);
            $sms_rate = $r->fax;
            $sms_charges = $calc_length * $sms_rate;
            $imywallet_balance = ($ResponseCode != "200") ? ($aggwallet_balance - $sms_charges) : ($wbalance - $sms_charges);
            $sms_refid = uniqid("EA-smsCharges-").time();
            $inst_type = 'institution';
            $rOrderID = "EA-bvnCharges-".time();
            $mydate_time = date("Y-m-d h:i:s");
            
            $search_userme = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND (role = 'agent_manager' OR role = 'institution_super_admin' OR role = 'merchant_super_admin') ORDER BY id ASC");
            $fetch_userme = mysqli_fetch_array($search_userme);
            $prefix = $fetch_userme['bprefix'];
            $imagepath = $image_converted;
            
            $posPIN = substr((uniqid(rand(),1)),3,6);
        
            $insert = mysqli_query($link, "INSERT INTO user VALUES(null,'$fname','$lname','$mname','$email','$phone','$addr1','$userBvn','$origin_city','$origin_province','','$origin_countryCode','Not-Activated','$username','$encrypt','$id','$imagepath','$global_role','','Registered','$institution_id','$prefix','$transactionPin','0.0','','0.0','$myAccountReference','$myAccountNumber','$preferredBank','$idedicated_ussd_prefix','$gender','$dob','Disallow','Disallow','Disallow','$wallet_date_time','$businessName','$otherBusinessType','$businessPhone','$rcNumber','$status','$acctType','$aggrid','NULL','Yes','VerveCard','Yes','$posPIN','0.0')") or die ("Error1: " . mysqli_error($link));

            ($myAccountName == "" || $myAccountNumber == "") ? "" : $update_fname = mysqli_query($link, "INSERT INTO virtual_account VALUES(null,'$myAccountReference','$id','$myAccountName','$myAccountNumber','$myBankName','$myStatus','$mydate_time','$provider','$institution_id','$acctType','$status','$aggrid','1000000','100000','10000','5000')");

            $insert = mysqli_query($link, "INSERT INTO activate_member2 VALUES(null,'$shortenedurl','$otpCode','No','$id')") or die ("Error2: " . mysqli_error($link));

            ($userBvn == "" ? "" : ($ResponseCode != "200" && $ResponseCode != "" ? "" : $update = mysqli_query($link, "UPDATE user SET transfer_balance = '$wbalance' WHERE id = '$aggrid'")));

            ($userBvn == "" ? "" : ($ResponseCode != "200" && $ResponseCode != "" ? "" : $insert = mysqli_query($link, "INSERT INTO bvn_log VALUE(null,'$institution_id','','$id','$aggrid','$mybvn_data','$ibvn_fee','$wallet_date_time','$rOrderID')")));
                                
            ($userBvn == "" ? "" : ($ResponseCode != "200" && $ResponseCode != "" ? "" : $insert = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$rOrderID','$userBvn','','$ibvn_fee','Debit','$currencyCode','BVN_Charges','Response: $currencyCode.$ibvn_fee was charged for Customer BVN Verification','successful','$wallet_date_time','$aggrid','$wbalance','')")));
                                
            ($userBvn == "" ? "" : ($ResponseCode != "200" && $ResponseCode != "" ? "" : $insert = mysqli_query($link, "INSERT INTO income VALUES(null,'','$rOrderID','BVN','$ibvn_fee','$date_time','Employee BVN Verification Charges')")));

            $searchNewReg = mysqli_query($link, "SELECT * FROM user WHERE id = '$id' AND created_by = '$institution_id'");
            $fetchNewReg = mysqli_fetch_object($searchNewReg);
            $userid = $fetchNewReg->userid;

            foreach($_FILES['uploaded_file']['name'] as $key => $name){
            
                $newFilename = $name;
                    
                if($newFilename == "")
                {
                    echo "";
                }
                else{
                    $newlocation = $newFilename;
                    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], '../img/'.$newFilename))
                    {
                        mysqli_query($link, "INSERT INTO attachment VALUES(null,'$userid','$id','$id','$newlocation',NOW())") or die ("Error: " . mysqli_error($link));
                    }
                }
                    
            }

            $userType = "user";
            ($debitWAllet == "No" ? $sendSMS->userGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $sms_refid, $sms_charges, $aggrid, $imywallet_balance, $debitWallet, $userType) : ($debitWAllet == "Yes" && $sms_charges <= $aggwallet_balance ? $sendSMS->userGeneralAlert($ozeki_password, $ozeki_url, $sysabb, $phone, $sms, $institution_id, $sms_refid, $sms_charges, $aggrid, $imywallet_balance, $debitWallet, $userType) : ""));
            $sendSMS->staffRegEmailNotifier($email, $sysabb, $fullname, $shortenedurl, $username, $password, $shortenedurl1, $aggremailConfigStatus, $aggrfetch_emailConfig);
            
            echo "<p style='font-size:20px; color:blue;'>Wallet Created Successfully!</p>";

        }
    }
}
?>
</div>


            <div class="box-body">

                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Preferred Bank</label>
                      <div class="col-sm-7">
                          <select name="preferredBank" class="form-control select2" required>
                            <option value="" selected="selected">---Select Bank of your choice---</option>
                            <?php
                            $explodeVA = explode(",",$aggrva_provider);
                            $countVA = (count($explodeVA) - 1);
                            for($i = 0; $i <= $countVA; $i++){
                                echo '<option value="'.$explodeVA[$i].'">'.(($explodeVA[$i] == "Payant" || $explodeVA[$i] == "Monnify") ? "Sterling Bank" : ($explodeVA[$i] == "Flutterwave" ? "Wema Bank" : $explodeVA[$i])).'</option>';
                            }
                            ?>
                          </select>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>

                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Account Type</label>
                      <div class="col-sm-7">
                          <select name="accountType" class="form-control select2" id="accountType" required>
                              <option value="" selected='selected'>Select Account Type&hellip;</option>
                              <option value="Agent">Agent</option>
                          </select>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>

      			<span id='ShowValueFrank'></span>
                <span id='ShowValueFrank'></span>

            </div>

            <div class="form-group" align="right">
                <label for="" class="col-sm-3 control-label" style="color:blue;"></label>
                <div class="col-sm-7">
                    <button name="indiv_register" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-cloud-upload">&nbsp;Submit</i></button> 
                </div>
                <label for="" class="col-sm-2 control-label"></label>
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