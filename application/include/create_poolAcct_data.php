<div class="row">	
<?php
require_once "../config/virtualBankAccount_class.php";
?>
	 <section class="content">
		 
            <h3 class="panel-title"></h3>
        
		   <div class="box box-success">
           <div class="box-body">
             <div class="table-responsive"> 
            <div class="box-body">

		 <div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="create_poolAcct.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("419"); ?>&&tab=tab_1">Pool Account Creation Form</a></li>
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
           
        <form class="form-horizontal" method="post" enctype="multipart/form-data">
        

        <div align="center">
<?php
if(isset($_POST['indiv_register'])){
    
    $institution_id = mysqli_real_escape_string($link, $_POST['institution']);
    $preferredBank = mysqli_real_escape_string($link, $_POST['preferredBank']);
    $acct_type =  mysqli_real_escape_string($link, $_POST['accountType']);
    $fname =  mysqli_real_escape_string($link, $_POST['fname']);
    $lname = mysqli_real_escape_string($link, $_POST['lname']);
    $phone = mysqli_real_escape_string($link, $_POST['phone']);
    $status = "Approved";
    $wallet_date_time = date("Y-m-d h:i:s");

    $ip = $_SERVER['REMOTE_ADDR']; // This will contain the ip of the request
    $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip), true);
    $currencyCode = $dataArray["geoplugin_currencyCode"];
    $origin_countryCode = $dataArray["geoplugin_countryCode"];

    $phoneNumber = $phone;
    $country = $origin_countryCode;
    
    $query = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
    $r = mysqli_fetch_object($query);
    $sysabb = $r->abb;
    $sys_email = $r->email;
    $walletafrica_skey = $r->walletafrica_skey;
    $mo_contract_code = $r->mo_contract_code;
    $rubbiesSecKey = $r->rubbiesSecKey;


    $verify_staff = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND (role = 'agent_manager' OR role = 'institution_super_admin' OR role = 'merchant_super_admin')");
    $fetch_staff = mysqli_fetch_array($verify_staff);
    $userBvn = $fetch_staff['addr2'];

    $verifyDuplicate = mysqli_query($link, "SELECT * FROM pool_account WHERE companyid = '$institution_id'");
    $vnum = mysqli_num_rows($verifyDuplicate);

    if($vnum == 1){

        echo "<p style='font-size:20px; color:orange;'>Opps!...Unable to create multiple pool account for the same institution!!</p>";

    }
    elseif($acct_type == "Agent" || $acct_type == "Corporate"){
        
        $businessName = ($acct_type == "Corporate") ? mysqli_real_escape_string($link, $_POST['businessName']) : "";
        $id = $fetch_staff['id'];
        $accountName = ($acct_type == "Corporate") ? $businessName : $fname.' '.$lname.' '.$mname;
        $accountUserId = $id;
        $customerName = $accountName;
        $customerEmail = ($acct_type == "Corporate") ? mysqli_real_escape_string($link, $_POST['businessEmail']) : mysqli_real_escape_string($link, $_POST['email']);
        $acctType = ($acct_type == "Corporate") ? "corporate" : "agent";
        $email = $customerEmail;
        $fullname = $accountName;
        $mydate_time = date("Y-m-d h:i:s");
        
        ($preferredBank == "Monnify") ? $result = $newVA->monnifyVirtualAccount($accountName,$currencyCode,$customerEmail,$customerName,$userBvn,$mo_contract_code) : "";
        ($preferredBank == "Rubies Bank") ? $result1 = $newVA->rubiesVirtualAccount($accountName,$rubbiesSecKey) : "";
        ($preferredBank == "Flutterwave") ? $result1 = $newVA->wemaVirtualAccount($accountName,$customerEmail,$TxtReference,$rave_secret_key) : "";
        ($preferredBank == "Payant") ? $result1 = $newVA->sterlingPayantVirtualAcct($accountName,$customerEmail,$phoneNumber,$currencyCode,$country,$payantEmail,$payantPwd,$payantOrgId) : "";
        ($preferredBank == "Providus Bank") ? $result1 = $newVA->providusVirtualAccount($accountName,$userBvn,$providusClientId,$providusClientSecret) : "";

        ($preferredBank == "Monnify" ? $myAccountReference = $result->responseBody->accountReference : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountReference = "EAVA-".time() : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountReference = $TxtReference : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myAccountReference = $result1['data']['_id'] : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : "")))));
        ($preferredBank == "Monnify" ? $myAccountName = $result->responseBody->accountName : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountName = $result1['virtualaccountname'] : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountName = $accountName : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myAccountName = $result1['data']['accountName'] : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountName = $result1['account_name'] : "")))));
        ($preferredBank == "Monnify" ? $myAccountNumber = $result->responseBody->accountNumber : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountNumber = $result1['virtualaccount'] : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountNumber = $result1['data']['account_number'] : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myAccountNumber = $result1['data']['accountNumber'] : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountNumber = $result1['account_number'] : "")))));
        ($preferredBank == "Monnify" ? $myBankName = $result->responseBody->bankName : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myBankName = $result1['bankname'] : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myBankName = $result1['data']['bank_name'] : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myBankName = "Sterling Bank" : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myBankName = "Providus Bank" : "")))));
        ($preferredBank == "Monnify" ? $myStatus = $result->responseBody->status : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $myStatus = "ACTIVE" : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myStatus = "ACTIVE" : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $myStatus = $result1['data']['status'] : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $myStatus = "ACTIVE" : "")))));
        ($preferredBank == "Monnify" ? $date_time = $result->responseBody->createdOn : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $date_time = date("Y-m-d h:i:s") : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $date_time = date("Y-m-d h:i:s") : "")))));
        ($preferredBank == "Monnify" ? $provider = "monify" : (($preferredBank == "Rubies Bank") && $result1['responsecode'] == "00" ? $provider = "rubies" : (($preferredBank == "Flutterwave") && $result1['data']['response_code'] == "02" ? $provider = "wema" : (($preferredBank == "Payant") && $result1['statusCode'] == "200" ? $provider = "sterling" : (($preferredBank == "Providus Bank") && $result1['responseCode'] == "00" ? $provider = "providus" : "")))));

        $sms = "$sysabb>>>Congratulation! Your pool account has been created successfully with ACCOUNT NUMBER: $myAccountNumber, ACCOUNT NAME: $accountName, Bank Name: $myBankName. Thanks";
        
        $max_per_page = 153;
        $sms_length = strlen($sms);
        $calc_length = ceil($sms_length / $max_per_page);
        $sms_rate = $r->fax;
        $sms_charges = $calc_length * $sms_rate;
        $sms_refid = uniqid("EA-smsCharges-").time();

        ($myAccountName == "" || $myAccountNumber == "") ? "" : $update_fname = mysqli_query($link, "INSERT INTO pool_account VALUES(null,'$myAccountReference','$id','$myAccountName','$myAccountNumber','$myBankName','$myStatus','$mydate_time','$provider','$institution_id','$acctType','$status','$uid','0.0','$currencyCode')");

        $sendSMS->backendGeneralAlert($sysabb, $phone, $sms, $sms_refid, $sms_charges, $uid);
        
        echo "<p style='font-size:20px; color:blue;'>Pool Account Created Successfully!</p>";

    }
    else{
      
      echo "<p style='font-size:20px; color:orange;'>Opps!...Invalid Request!!</p>";

    }
}
?>
</div>


            <div class="box-body">

                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Institution</label>
                      <div class="col-sm-7">
                        <select name="institution" class="form-control select2" /required>
                            <option value="" selected="selected">---Select Institution---</option>
                            <?php
                            $get1 = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved' ORDER BY id") or die (mysqli_error($link));
                            while($rows1 = mysqli_fetch_array($get1))
                            {
                            ?>
                            <option value="<?php echo $rows1['institution_id']; ?>"><?php echo $rows1['institution_name']; ?></option>
                            <?php } ?>
                        </select>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>

                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Preferred Bank</label>
                      <div class="col-sm-7">
                        <select name="preferredBank" class="form-control select2" /required>
                            <option value="" selected="selected">---Select Bank of your choice---</option>
                            <option value="Monnify">Monnify</option>
                            <option value="Rubies Bank">Rubies Bank</option>
                            <option value="Flutterwave">Flutterwave</option>
                            <option value="Payant">Payant</option>
                            <option value="Providus Bank">Providus Bank</option>
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
                              <option value="Corporate">Corporate</option>
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