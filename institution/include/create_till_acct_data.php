<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"> <a href="view_teller.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDEw"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a> <i class="fa fa-desktop"></i>  Add Till</h3>
            </div>

             <div class="box-body">
 <?php
if(isset($_POST['save']))
{
	$till_name =  mysqli_real_escape_string($link, $_POST['till_name']);
	$cashier = mysqli_real_escape_string($link, $_POST['cashier']);
	$office = ($_POST['office'] == "head") ? "" : mysqli_real_escape_string($link, $_POST['office']);
	$till_desc =  mysqli_real_escape_string($link, $_POST['till_desc']);
	$status =  mysqli_real_escape_string($link, $_POST['status']);
	$commtype = mysqli_real_escape_string($link, $_POST['commtype']);
	$percentage = preg_replace('/[^0-9.]/', '', mysqli_real_escape_string($link, $_POST['percentage']));
	$currencyCode =  mysqli_real_escape_string($link, $_POST['currency']);
	
	$search_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$cashier'");
	$fetch_user = mysqli_fetch_array($search_user);
	$customerEmail = $fetch_user['email'];
	$accountUserId = $cashier;
	$accountName =  $fetch_user['lname'].' '.$fetch_user['name'].' '. $fetch_user['mname'];
	$customerName = $accountName;
	$userBvn = $fetch_user['addr2'];
	$phoneNumber = $fetch_user['phone'];
	$country = $fetch_user['country'];
	$mydate_time = date("Y-m-d h:i:s");

	//Check Duplicate Account
    $search_tillinfo = mysqli_query($link, "SELECT * FROM till_account WHERE cashier = '$cashier' AND currency = '$currencyCode'");
    $fetch_tillinfo = mysqli_num_rows($search_tillinfo);

	if($fetch_tillinfo == 0){

		($iva_fortill == "None") ? "" : require_once "config/virtualBankAccount_class.php";

		($iva_fortill == "None" ? "" : ($iva_fortill == "Monnify" ? $result = $newVA->monnifyVirtualAccount($accountName,$currencyCode,$customerEmail,$customerName,$userBvn,$mo_contract_code) : ""));
		($iva_fortill == "None" ? "" : ($iva_fortill == "Rubies Bank" ? $result1 = $newVA->rubiesVirtualAccount($accountName,$rubbiesSecKey) : ""));
		($iva_fortill == "None" ? "" : ($iva_fortill == "Flutterwave" ? $result1 = $newVA->wemaVirtualAccount($accountName,$customerEmail,$TxtReference,$rave_secret_key) : ""));
		($iva_fortill == "None" ? "" : ($iva_fortill == "Payant" ? $result1 = $newVA->sterlingPayantVirtualAcct($accountName,$customerEmail,$phoneNumber,$currencyCode,$country,$payantEmail,$payantPwd,$payantOrgId) : ""));
		($iva_fortill == "None" ? "" : ($iva_fortill == "Providus Bank" ? $result1 = $newVA->providusVirtualAccount($accountName,$userBvn,$providusClientId,$providusClientSecret) : ""));
		($iva_fortill == "None" ? "" : ($iva_fortill == "Wema Bank" ? $result1 = $newVA->directWemaVirtualAccount($accountName,$wemaVAPrefix) : ""));

		($iva_fortill == "None" ? "" : ($iva_fortill == "Monnify" ? $myAccountReference = $result->responseBody->accountReference : (($iva_fortill == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountReference = "EAVA-".time() : (($iva_fortill == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountReference = $TxtReference : (($iva_fortill == "Payant") && $result1['statusCode'] == "200" ? $myAccountReference = $result1['data']['_id'] : (($iva_fortill == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : (($iva_fortill == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountReference = uniqid("EAVA-").time() : "")))))));
		($iva_fortill == "None" ? "" : ($iva_fortill == "Monnify" ? $myAccountName = $result->responseBody->accountName : (($iva_fortill == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountName = $result1['virtualaccountname'] : (($iva_fortill == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountName = $accountName : (($iva_fortill == "Payant") && $result1['statusCode'] == "200" ? $myAccountName = $result1['data']['accountName'] : (($iva_fortill == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountName = $result1['account_name'] : (($iva_fortill == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountName = $result1['account_name'] : "")))))));
		($iva_fortill == "None" ? "" : ($iva_fortill == "Monnify" ? $myAccountNumber = $result->responseBody->accountNumber : (($iva_fortill == "Rubies Bank") && $result1['responsecode'] == "00" ? $myAccountNumber = $result1['virtualaccount'] : (($iva_fortill == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myAccountNumber = $result1['data']['account_number'] : (($iva_fortill == "Payant") && $result1['statusCode'] == "200" ? $myAccountNumber = $result1['data']['accountNumber'] : (($iva_fortill == "Providus Bank") && $result1['responseCode'] == "00" ? $myAccountNumber = $result1['account_number'] : (($iva_fortill == "Wema Bank") && $result1['responseCode'] == "00" ? $myAccountNumber = $wemaVAPrefix.$result1['account_number'] : "")))))));
		($iva_fortill == "None" ? "" : ($iva_fortill == "Monnify" ? $myBankName = $result->responseBody->bankName : (($iva_fortill == "Rubies Bank") && $result1['responsecode'] == "00" ? $myBankName = $result1['bankname'] : (($iva_fortill == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myBankName = $result1['data']['bank_name'] : (($iva_fortill == "Payant") && $result1['statusCode'] == "200" ? $myBankName = "Sterling Bank" : (($iva_fortill == "Providus Bank") && $result1['responseCode'] == "00" ? $myBankName = "Providus Bank" : (($iva_fortill == "Wema Bank") && $result1['responseCode'] == "00" ? $myBankName = "Wema Bank" : "")))))));
		($iva_fortill == "None" ? "" : ($iva_fortill == "Monnify" ? $myStatus = $result->responseBody->status : (($iva_fortill == "Rubies Bank") && $result1['responsecode'] == "00" ? $myStatus = "ACTIVE" : (($iva_fortill == "Flutterwave") && $result1['data']['response_code'] == "02" ? $myStatus = "ACTIVE" : (($iva_fortill == "Payant") && $result1['statusCode'] == "200" ? $myStatus = $result1['data']['status'] : (($iva_fortill == "Providus Bank") && $result1['responseCode'] == "00" ? $myStatus = "ACTIVE" : (($iva_fortill == "Wema Bank") && $result1['responseCode'] == "00" ? $myStatus = "ACTIVE" : "")))))));
		($iva_fortill == "None" ? "" : ($iva_fortill == "Monnify" ? $date_time = $result->responseBody->createdOn : (($iva_fortill == "Rubies Bank") && $result1['responsecode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($iva_fortill == "Flutterwave") && $result1['data']['response_code'] == "02" ? $date_time = date("Y-m-d h:i:s") : (($iva_fortill == "Payant") && $result1['statusCode'] == "200" ? $date_time = date("Y-m-d h:i:s") : (($iva_fortill == "Providus Bank") && $result1['responseCode'] == "00" ? $date_time = date("Y-m-d h:i:s") : (($iva_fortill == "Wema Bank") && $result1['responseCode'] == "00" ? $date_time = date("Y-m-d h:i:s") : "")))))));
		($iva_fortill == "None" ? "" : ($iva_fortill == "Monnify" ? $provider = "monify" : (($iva_fortill == "Rubies Bank") && $result1['responsecode'] == "00" ? $provider = "rubies" : (($iva_fortill == "Flutterwave") && $result1['data']['response_code'] == "02" ? $provider = "wema" : (($iva_fortill == "Payant") && $result1['statusCode'] == "200" ? $provider = "sterling" : (($iva_fortill == "Providus Bank") && $result1['responseCode'] == "00" ? $provider = "providus" : (($iva_fortill == "Wema Bank") && $result1['responseCode'] == "00" ? $provider = "wema" : "")))))));

		($myAccountName == "" || $myAccountNumber == "" || $iva_fortill == "None") ? "" : mysqli_query($link, "INSERT INTO till_virtual_account VALUES(null,'$myAccountReference','$accountUserId','$myAccountName','$myAccountNumber','$myBankName','$myStatus','$mydate_time','$provider','$institution_id','agent','$status','$iuid','0.0','$currencyCode')");
		mysqli_query($link, "INSERT INTO till_account VALUES(null,'$institution_id','$office','$till_name','$cashier','$commtype','$percentage','0.0','$till_desc','0','0','$status',NOW(),'$currencyCode')") or die ("Error: " . mysqli_error($link));
		
		echo "<div class='alert alert-success'>Till Account Added Successfully!</div>";

	}else{

		echo "<div class='alert alert-danger'>Duplicate account is not allowed!</div>";

	}

	

}
?>           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data">

             <div class="box-body">
			 
                  <input name="branchid" type="hidden" class="form-control" value="">
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Till Name</label>
                  <div class="col-sm-10">
                  <input name="till_name" type="text" class="form-control" placeholder="Till Name" required>
                  </div>
                  </div>

			<div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Cashier Name</label>
                <div class="col-sm-10">
					<select name="cashier" class="form-control select2" required>
						<option value='' selected='selected'>Select Cashier&hellip;</option>
						<?php
						$get = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND (role != 'agent_manager' || role != 'institution_super_admin' || role != 'merchant_super_admin') ORDER BY userid") or die (mysqli_error($link));
						while($rows = mysqli_fetch_array($get))
						{
						?>
						<option value="<?php echo $rows['id']; ?>"><?php echo $rows['name'].' '.$rows['lname'].' '.$rows['mname']; ?></option>
						<?php } ?>
					</select>
				</div>
            </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Office</label>
                  <div class="col-sm-10">
					<select name="office" class="form-control select2" required>
					<option value='' selected='selected'>Select Office Branch&hellip;</option>
						<option value='head'>Head Office</option>
						<?php
						$get_office = mysqli_query($link, "SELECT * FROM branches WHERE created_by = '$institution_id' ORDER BY id") or die (mysqli_error($link));
						while($office_rows = mysqli_fetch_array($get_office))
						{
						?>
						<option value="<?php echo $office_rows['branchid']; ?>"><?php echo $office_rows['bname']; ?></option>
						<?php } ?>
					</select>
					</div>
                </div>

				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Currency</label>
                  <div class="col-sm-10">
					<select name="currency" class="form-control select2" required>
						<option value="" selected='selected'>Select Currency</option>
						<option value='NGN'>NGN</option>
						<option value='USD'>USD</option>
					</select>
					</div>
                </div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Commission Type</label>
                  <div class="col-sm-10">
					<select name="commtype" class="form-control select2" required>
						<option value="" selected='selected'>Select Commission Type</option>
						<option value='Percentage'>Percentage</option>
						<option value='Flat'>Flat</option>
					</select>
					</div>
                </div>
                 					 
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Commision (%)</label>
                  <div class="col-sm-10">
                  	<input name="percentage" type="text" class="form-control" placeholder="Deposit Commission like 2.2, 5, 10.... without putting % sign" required>
                  </div>
                  </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Description</label>
                  <div class="col-sm-10">
				  <textarea name="till_desc"  class="form-control" rows="4" cols="80" placeholder="Description"> </textarea>
                </div>
                </div>

            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Status</label>
                  <div class="col-sm-10">
				<select name="status" class="form-control select2" required>
										<option value='' selected='selected'>Select Status&hellip;</option>
										<option value='Active'>Active</option>
										<option value='NotActive'>NotActive</option>
									</select>
									 </div>
                 					 </div>				  
				  
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                				<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>"><i class="fa fa-save">&nbsp;Save</i></button>
              </div>
			  </div>
			  
			 </form> 

</div>	
</div>	
</div>
</div>