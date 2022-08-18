<div class="row">	
		
	 <section class="content">
     
	        <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive"> 
             <div class="box-body">
<?php
$id = $_GET['id'];
$select = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'") or die (mysqli_error($link));
$rowLoan = mysqli_fetch_array($select);
//$borrower = $row['borrower'];  
$baccount = $rowLoan['baccount']; 
$institution_id = $rowLoan['branchid'];
$acct_officer = $rowLoan['agent'];
$isbranchid = $rowLoan['sbranchid'];

$searchInst = mysqli_query($link, "SELECT * FROM institution_data WHERE institution_id = '$institution_id'");
$fetchInst = mysqli_fetch_array($searchInst);
$iwallet_balance = $fetchInst['wallet_balance'];

//REMITAL CREDENTIALS
$verify_inst = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$fetch_inst = mysqli_fetch_object($verify_inst);
$iremitaMerchantId = $fetch_inst->remitaMerchantId;
$iremitaApiKey = $fetch_inst->remitaApiKey;
$iremitaServiceId = $fetch_inst->remitaServiceId;
$iremitaApiToken = $fetch_inst->remitaApiToken;
$ibvn_route = $fetch_inst->bvn_route;
$icurrency = $fetch_inst->currency;
	
$search_user = mysqli_query($link, "SELECT * FROM user WHERE id = '$acct_officer' OR name = '$acct_officer'");
$fetch_user = mysqli_fetch_array($search_user);
?>
			 <div class="col-md-14">
             <div class="nav-tabs-custom">
             <ul class="nav nav-tabs">
              <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="#tab_1" data-toggle="tab">Loan Information</a></li>
              <li <?php echo ($_GET['tab'] == 'tab_0') ? "class='active'" : ''; ?>><a href="updateloans.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo base64_encode("405"); ?>&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_0">KYC Verification</a></li>
              <li <?php echo ($_GET['tab'] == 'tab_5') ? "class='active'" : ''; ?>><a href="updateloans.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo base64_encode("405"); ?>&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_5">Repayment Schedule</a></li>
              <li <?php echo ($_GET['tab'] == "tab_3") ? "class='active'" : ''; ?>><a href="updateloans.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=<?php echo base64_encode('405'); ?>&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_3">Direct Debit Activation <i><b>(for Autocharge)</b></i></a></li>
              <li <?php echo ($_GET['tab'] == "tab_4") ? "class='active'" : ''; ?>><a href="#">Loan Bal: <b><?php echo ($rowLoan['balance'] == "") ? $icurrency."0.0" : $icurrency.number_format($rowLoan['balance'],2,'.',','); ?></b></a></li>
              </ul>
             <div class="tab-content">
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">
           
			 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_loan.php?id=<?php echo $id; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA1&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_1">
	
             <div class="box-body">
			
			 <div class="form-group">
                <label for="" class="col-sm-2 control-label" style="color:blue">Borrower</label>
				 <div class="col-sm-10">
				<?php
				$get = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$baccount'") or die (mysqli_error($link));
				while($rows = mysqli_fetch_array($get))
				{
				?>
					<input name="borrower" type="text" class="form-control" value=<?php echo $rows['fname'].'&nbsp;'.$rows['lname']; ?> readonly>
				<?php } ?>
              </div>
			  </div>
			  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Account</label>
                  <div class="col-sm-10">
                  <input name="account" type="text" class="form-control" value="<?php echo $row['baccount']; ?>" readonly>
                  </div>
                  </div>
				 
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Amount</label>
                  <div class="col-sm-10">
                  <input name="amount" type="text" class="form-control" value="<?php echo $row['amount']; ?>" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Salary Income</label>
                  <div class="col-sm-10">
                  <input name="income" type="text" class="form-control" value="<?php echo $row['income']; ?>" required>
                  </div>
                  </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Salary Date</label>
                  <div class="col-sm-10">
	               <div class="input-group date">
	                  <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </div>
                  <input name="salary_date" type="text" class="form-control" value="<?php echo $row['salary_date']; ?>" required>
			  </div>
             </div>
           </div>
				  
		<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Employer Name</label>
                  <div class="col-sm-10">
                  <input name="employer" type="text" class="form-control" value="<?php echo $row['employer']; ?>" required>
                  </div>
                  </div>
			  
			 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue">Your Bank Account Info.</label>
                  	<div class="col-sm-10">
					<textarea name="bacinfo"  class="form-control" rows="4" cols="80" readonly><?php echo $row['descs']; ?></textarea>
           			</div>
          	 </div>
			 
			 <div class="form-group">
		                 	<label for="" class="col-sm-2 control-label" style="color:blue">Reasons for Loan.</label>
		                 	<div class="col-sm-10">
						<textarea name="lreasons"  class="form-control" rows="2" cols="80" required><?php echo $row['remark']; ?></textarea>
		          			 </div>
						 </div>
			  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Account Officer</label>
                  <div class="col-sm-10">
                  <input name="agent" type="text" class="form-control" value="<?php echo $fetch_user['name']; ?>" readonly>
                  </div>
                  </div>
				  
				 
				  
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Status</label>
                  <div class="col-sm-10">
                  <input name="status" type="text" class="form-control" value="<?php echo $row['status']; ?>" readonly="readonly">
                  </div>
                  </div>


<hr>	
<div class="bg-orange">&nbsp;<?php echo ($row['loantype'] != 'Purchase') ? "GUARANTOR INFORMATION" : "PRODUCT INFORMATION"; ?></div>
<hr>
				  
			<div class="form-group">
				<label for="" class="col-sm-2 control-label" style="color:blue"><?php echo ($row['loantype'] != 'Purchase') ? "Gurantor's Passport" : "Product Invoice"; ?></label>
				<div class="col-sm-10">
  		  		
       			 <img id="blah"  src="../<?php echo $row ['g_image'] ;?>" alt="Image Here" height="100" width="100"/>
			</div>
			</div>
			
			<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue"><?php echo ($row['loantype'] != 'Purchase') ? "Relationship" : "Product Name"; ?></label>
                  <div class="col-sm-10">
                  <input name="grela" type="text" class="form-control" value="<?php echo $row['rela']; ?>" required>
                  </div>
                  </div>
			
			 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue"><?php echo ($row['loantype'] != 'Purchase') ? "Guarantor's Name" : "Model Number"; ?></label>
                  <div class="col-sm-10">
                  <input name="g_name" type="text" class="form-control" value="<?php echo $row['g_name']; ?>" required>
                  </div>
                  </div>
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue"><?php echo ($row['loantype'] != 'Purchase') ? "Guarantor's Phone Number" : "Serial Number"; ?></label>
                  <div class="col-sm-10">
                  <input name="g_phone" type="text" class="form-control" value="<?php echo $row['g_phone']; ?>" required>
                  </div>
                  </div>
                  
                  <?php
                  if($row['loantype'] != 'Purchase'){
                      echo "";
                  }
                  else{
                    ?>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Quantity</label>
                  <div class="col-sm-10">
                  <input name="qty" type="number" class="form-control" value="<?php echo $row['amount_topay'] / $row['amount']; ?>" readonly>
                  </div>
                  </div>  
                    
                  <?php
                  }
                  ?>
				  
				 
				 <div class="form-group">
                  	<label for="" class="col-sm-2 control-label" style="color:blue"><?php echo ($row['loantype'] != 'Purchase') ? "Guarantor's Address" : "Other Description"; ?></label>
                  	<div class="col-sm-10">
					<textarea name="gaddress"  class="form-control" rows="4" cols="80" required><?php echo $row['g_address']; ?></textarea>
           			 </div>
          	    </div> 
			

<hr>	
<div class="bg-orange">&nbsp;OTHER INFO</div>
<hr>	
				  
				  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Last Reviewed By</label>
                  <div class="col-sm-10">
                  <input name="teller" type="text" class="form-control" value="<?php echo $row['teller']; ?>" readonly>
                  </div>
                  </div>
                  
                  <div class="form-group">
		              <label for="" class="col-sm-2 control-label" style="color:blue">Loan Product</label>
		  			 <div class="col-sm-10">
		              <select name="lproduct2" class="select2" id="loan_products2" style="width: 100%;" readonly>
		  	              <?php
		  	  			$id = $_GET['id'];
		  	  			$getin = mysqli_query($link, "SELECT * FROM loan_info WHERE id='$id'") or die (mysqli_error($link));
		  	  			$row = mysqli_fetch_array($getin);
						$idp = $row['lproduct'];
							
			  	  			$search_lp = mysqli_query($link, "SELECT * FROM loan_product WHERE id='$idp'") or die (mysqli_error($link));
			  	  			$row_lp = mysqli_fetch_array($search_lp);
		  				echo '<option value="'.$row_lp['id'].'" selected="selected">'.$row_lp['pname'].' - '.'(Interest Rate: '. $row_lp['interest'].'% per month)'.'</option>';
		  	  				?>
		                  <?php
		  				$getin = mysqli_query($link, "SELECT * FROM loan_product ORDER BY id") or die (mysqli_error($link));
		  				while($row = mysqli_fetch_array($getin))
		  				{
		  				echo '<option value="'.$row['id'].'">'.$row['pname'].' - '.'(Interest Rate: '. $row['interest'].'% per month)'.'</option>';
		  				}
		  				?>
		              </select>
		            </div>
		  		  </div>
				  
	    			<span id='ShowValueFrank'></span>
	    			<span id='ShowValueFrank'></span>
				  
	  			 <input name="lid" type="hidden" class="form-control" value="<?php echo $_GET['lid']; ?>" readonly>
				  
			 </div>	
			 
			 <div align="right">
              <div class="box-footer">
                	<button name="loansave" type="submit" class="btn bg-blue"><i class="fa fa-save">&nbsp;Update</i></button>
              </div>
			  </div>
			 
			  </form>

              </div>
              <!-- /.tab-pane -->
	<?php
	if(isset($_GET['tab']) == true)
	{
		$tab = $_GET['tab'];
		if($tab == 'tab_3')
		{
			$acn = $_GET['acn'];
			$lid = $_GET['lid'];
			$search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
            $get_customer = mysqli_fetch_object($search_customer);
            
            $search_mloan = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'");
			$get_mloan = mysqli_fetch_object($search_mloan);
            $mandate_status = $get_mloan->mandate_status;

			$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
            $row1 = mysqli_fetch_object($select1);
		?>
		
<?php
if($iremitaMerchantId === "" || $iremitaApiKey === "" || $iremitaServiceId === "" || $iremitaApiToken === "")
{
    echo "";
}
else{
?>
		
	              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">
	                  
	       <form class="form-horizontal" method="post" enctype="multipart/form-data">
	           
			<?php
			if($mandate_status === "InProcess" || $mandate_status === "Activated" || $mandate_status === "Stop")
			{	
			    include ("../config/restful_apicalls.php");
			    
			    $systemset = mysqli_query($link, "SELECT * FROM systemset");
				$row1 = mysqli_fetch_object($systemset);
  
				$result = array();
                $requestId = $get_mloan->request_id;
                $mandateId = $get_mloan->mandate_id;

                $concat_param = $mandateId.$iremitaMerchantId.$requestId.$iremitaApiKey;
                $hash = hash("sha512", $concat_param);

                $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'remita'");
                $fetch_restapi = mysqli_fetch_object($search_restapi);
                $url = $fetch_restapi->api_url;

                $api_url = $url."remita/exapp/api/v1/send/api/echannelsvc/echannel/mandate/status";

                $postdata = array(
                    'merchantId' => $iremitaMerchantId,
                    'mandateId' => $mandateId,
                    'hash'  => $hash,
                    'requestId' => $requestId
                );

                $make_call = callAPI('POST', $api_url, json_encode($postdata));
                
                $output2 = trim(json_decode(json_encode($make_call), true),'jsonp ();');
                $result = json_decode($output2, true);

				if($result['isActive'] == true){
				    
					mysqli_query($link, "UPDATE loan_info SET mandate_status = 'Activated' WHERE lid = '$lid'");
					
					echo "<p>Remita Retrieval Reference: <b>".$result['mandateId']."</b></p>";
                    echo "<p>Request ID: <b>".$result['requestId']."</b></p>";
                    echo "<p>Start Date: <b>".$result['startDate']."</b></p>";
                    echo "<p>End Date: <b>".$result['endDate']."</b></p>";
                    echo "<p style='color: ".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'><i class='fa fa-check'></i><b> Direct Debit Mandate Activated Successfully!</b></p>";
                    echo ($mandate_status === "Activated") ? '<button type="submit" class="btn bg-'.(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']).'" name="stop_mandate"><i class="fa fa-times"></i>&nbsp;Stop Mandate</button>' : '';
				    
				}
				else{
					    
				    $concat_param3 = $iremitaMerchantId.$iremitaApiKey.$requestId;
                    $hash3 = hash("sha512", $concat_param3);
            
                    $api_url3 = $url."remita/ecomm/mandate/form/$iremitaMerchantId/$hash3/$mandateId/$requestId/rest.reg";
					    
				    // the transaction was not successful, do not deliver value'
				    // print_r($result);  //uncomment this line to inspect the result, to check why it failed.
                    echo "<p>Direct Debit Activation is still <b>under processing</b> by the Bank</p>";
                    echo "<p>Remita Retrieval Reference: <b>".$result['mandateId']."</b></p>";
                    echo "<p>Request ID: <b>".$result['requestId']."</b></p>";
                    echo "<p>Mandate Status: <b>$get_mloan->mandate_status</b></p>";
                    echo "<a href='$api_url3' target='_blank'><button type='button' class='btn bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'><i class='fa fa-forward'></i>&nbsp;Click Here</button></a> to Re-Print <b>RRR Mandate Form</b></p>";

			    }
			?>
			
<?php
if(isset($_POST['stop_mandate'])){
    
    include ("../config/restful_apicalls.php");
    
    $requestId = $get_mloan->request_id;
    $mandateId = $get_mloan->mandate_id;
				
	//REMITAL CREDENTIALS
    $iremitaMerchantId = $fetch_icurrency->remitaMerchantId;
    $iremitaApiKey = $fetch_icurrency->remitaApiKey;

    $concat_param = $mandateId.$iremitaMerchantId.$requestId.$iremitaApiKey;
    $hash = hash("sha512", $concat_param);
    
    $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'remita'");
    $fetch_restapi = mysqli_fetch_object($search_restapi);
    $url = $fetch_restapi->api_url;

    $api_url = $url."remita/exapp/api/v1/send/api/echannelsvc/echannel/mandate/stop";
    
    $postdata = array(
        'merchantId' => $iremitaMerchantId,
        'mandateId' => $mandateId,
        'hash'  => $hash,
        'requestId' => $requestId
        );

    $make_call = callAPI('POST', $api_url, json_encode($postdata));
                
    $output2 = trim(json_decode(json_encode($make_call), true),'jsonp ();');
    $result = json_decode($output2, true);
    
    if($result['statuscode'] === "00"){
        
        mysqli_query($link, "UPDATE loan_info SET mandate_status = 'Stop' WHERE lid = '$lid'");
        echo "<script>alert('Mandate Deactivated Successfully!!!'); </script>";
        echo "<script>window.location='updateloans.php?id=".$_GET['id']."&&acn=".$_GET['acn']."&&mid=NDA1&&lid=".$_GET['lid']."&&tab=tab_3'; </script>";
        
    }
    else{
        
        echo "<script>alert('Unable to Deactivated Mandate!'); </script>";
        echo "<script>window.location='updateloans.php?id=".$_GET['id']."&&acn=".$_GET['acn']."&&mid=NDA1&&lid=".$_GET['lid']."&&tab=tab_3'; </script>";
        
    }
    
}
?>
			
		<?php
		}
		else{
			?>
				  <br>
				  <p style="color: orange;"><i class="fa fa-times"></i><b> Direct Debit Not Yet Activated</b></p>
                  <!--
                  <form >
				    <a href="verify_card.php?id=<?php //echo $_GET['id']; ?>&&acn=<?php //echo $acn; ?>&&mid=NDA1&&lid=<?php //echo $lid; ?>&&tab=tab_3"><button type="button" class="btn bg-blue"><i class="fa fa-refresh"></i> Proceed to Activate Direct Debit! </button></a>
				  </form>
                  -->
	    <?php
	    }
		?>
		
              </div>
              
    </form>
              
<?php
}
?>
              
	<?php
	}
	elseif($tab == 'tab_0')
	{
	    $my_id = $_GET['id'];
	    $myget_acn = $_GET['acn'];
	    $search_myfile = mysqli_query($link, "SELECT borrowers.unumber, loan_info.g_bvn, loan_info.g_phone FROM borrowers LEFT JOIN loan_info ON borrowers.account = loan_info.baccount WHERE borrowers.account = '$myget_acn' AND loan_info.id = '$my_id'") or die ("Error: " . mysqli_error($link));
	    $fetch_myfile = mysqli_fetch_array($search_myfile);
	    $mybvn = $fetch_myfile['unumber'];
	    $g_bvn = $fetch_myfile['g_bvn'];
	    $old_gphone = $fetch_myfile['g_phone'];
	    
	    $search_otpconfirm = mysqli_query($link, "SELECT * FROM bvn_log WHERE otp_code = '$myget_acn'");
	    $fetch_otpconfirm = mysqli_num_rows($search_otpconfirm);
        
        
	?>
              <!-- /.tab-pane -->
              <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_0') ? 'active' : ''; ?>" id="tab_0">
                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                    
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">Upload Documents</label>
                  <div class="col-sm-9">
                  <input name="uploaded_file[]" type="file" class="btn bg-orange" multiple>
                  <span style="color: orange;">UPLOAD <b>GOVERNMENT ISSUED ID CARD, BANK ACCOUNT STATEMENT AND UTILITY BILL</b></span>
                  <hr>
                    <?php
                    $i = 0;
                    $search_file = mysqli_query($link, "SELECT * FROM attachment WHERE borrowerid = '$myget_acn'") or die ("Error: " . mysqli_error($link));
                    if(mysqli_num_rows($search_file) == 0){
                    	echo "<span style='color: ".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>No file attached!!</span>";
                    }else{
                    	while($get_file = mysqli_fetch_array($search_file)){
                    		$i++;
                    ?>
                    <a href="../application/<?php echo $get_file['attached_file']; ?>" target="_blank"><img src="../img/file_attached.png" width="64" height="64"> Attachment<?php echo $i; ?></a>
                    <?php
                    	}
                    }
                    ?>
                
                  </div>
                  </div>
                  <hr>
                  
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">Guarantor BVN <?php echo ($g_bvn != "" && strlen($g_bvn) == "11") ? '<span style="color: '.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'">[<b>Verified</b> <i class="fa fa-check"></i>]</span>' : '<span style="color: '.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'">[<b>Not-Verified</b> <i class="fa fa-times"></i>]</span>'; ?></label>
                  <label class="col-sm-7">
                  <input name="g_bvn" type="text" class="form-control" placeholder="BVN Number Here" value="<?php echo $g_bvn; ?>" maxlength="11">
                  <span style="color: orange;"> BVN Verification cost <b style="color: blue;"><?php echo $fetchsys_config['currency'].number_format($fetchsys_config['bvn_fee'],2,'.',','); ?></b> routed through your Super Wallet (Nigeria Account Only). </span>
                  
                  <div class="scrollable">
                  <?php
                  if(isset($_POST['verifyG_BVN'])){
                      
                       $id = $_GET['id'];
                       $userBvn = mysqli_real_escape_string($link, $_POST['g_bvn']);
                       
                       $search_myloan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'");
                       $fetch_myloan = mysqli_fetch_array($search_myloan);
                       $g_fullname = $fetch_myloan['g_name'];
                       $g_phone = $fetch_myloan['g_phone'];
                       $AcctOfficer = $fetch_myloan['agent'];
                       
                       if($iwallet_balance < $bvn_fee){
           
                           echo "<br><span class='bg-orange'>Sorry! You do not have sufficient fund in your Wallet for this verification</span>";
                           
                       }
                       elseif(strlen($userBvn) != 11){
                           
                           echo "<br><span>BVN Number not Valid....</span>";
                           
                       }
                       elseif($ibvn_route == "Wallet Africa"){
                           
                            require_once "../config/bvnVerification_class.php";
    
                            $processBVN = $newBV->walletAfricaBVNVerifier($walletafrica_skey,$userBvn,$link);
                            $ResponseCode = $processBVN['ResponseCode'];
                           
                            if($ResponseCode == "200"){
                                
                                $icm_id = "ICM".time();
                                $exp_id = "EXP".time();
                                $myOtp = substr((uniqid(rand(),1)),3,6);
                                $rOrderID = "EA-bvnCharges-".time();
                               
                                $date_time = date("Y-m-d");
                                $wallet_date_time = date("Y-m-d H:i:s");

                                $wbalance = $iwallet_balance - $bvn_fee;
                                
                                //BVN Details
                                $g_bvn_fullname = $processBVN['LastName'].' '.$result['FirstName'].' '.$processBVN['MiddleName'];
                                $g_bvn_phone = $processBVN['PhoneNumber'];
                                $bvn_picture = $processBVN['Picture'];
                                $dynamicStr = md5(date("Y-m-d h:i"));
                                $image_converted = $newBV->base64_to_jpeg($bvn_picture, $dynamicStr.".png");

                                //20 array row
                                $mygbvn_data = $processBVN['BVN']."|".$processBVN['FirstName']."|".$processBVN['LastName']."|".$processBVN['MiddleName']."|".$processBVN['DateOfBirth']."|".$processBVN['PhoneNumber']."|".$processBVN['Email']."|".$processBVN['EnrollmentBank']."|".$processBVN['EnrollmentBranch']."|".$processBVN['Gender']."|".$processBVN['LevelOfAccount']."|".$processBVN['LgaOfOrigin']."|".$processBVN['LgaOfResidence']."|".$processBVN['MaritalStatus']."|".$processBVN['NameOnCard']."|".$processBVN['Nationality']."|".$processBVN['StateOfOrigin']."|".$processBVN['StateOfResidence']."|".$processBVN['Title']."|".$processBVN['WatchListed']."|".$image_converted;
                               
                                $search_bvnverify = mysqli_query($link, "SELECT * FROM bvn_log WHERE (accountID = '$g_phone' OR accountID = '$g_bvn_phone')");
                                $fetch_bvnverify = mysqli_fetch_array($search_bvnverify);
                                $bvn_nos = mysqli_num_rows($search_bvnverify);
                                $concat = $fetch_bvnverify['mydata'];
                                $parameter = (explode('|',$concat));
                                $old_picture = $parameter[20];
                               
                               $update_wallet = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$wbalance' WHERE institution_id = '$institution_id'");
                               
                               ($bvn_nos == 1 && $old_picture != "") ? unlink("../img/".$old_picture) : "";
                               ($bvn_nos == 1) ? $update_bvn = mysqli_query($link, "UPDATE mydata = '$mygbvn_data' WHERE accountID = '$g_phone'") : $insert_bvn = mysqli_query($link, "INSERT INTO bvn_log VALUE(null,'$institution_id','$isbranchid','$g_phone','$AcctOfficer','$mygbvn_data','$bvn_fee','$wallet_date_time','$rOrderID')");
                               
                               $insert_income = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$rOrderID','$userBvn','','$bvn_fee','Debit','$icurrency','BVN_Charges','Response: $icurrency.$bvn_fee was charged for Guarantor BVN Verification','successful','$wallet_date_time','$uid','$wbalance','')");

                               $insert_income = mysqli_query($link, "INSERT INTO expenses VALUES(null,'$institution_id','$exp_id','BVN','$bvn_fee','$date_time','Guarantor BVN Verification Charges')");
                               
                               $insert_income = mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','BVN','$bvn_fee','$date_time','Guarantor BVN Verification Charges')");
                               
                               (($g_bvn_phone == $g_phone) || ($g_bvn_fullname == $g_fullname)) ? mysqli_query($link, "UPDATE loan_info SET g_bvn = '$userBvn' WHERE id = '$id'") : "";
                               
                               /*$message = "Full Name: $g_bvn_fullname ".(($g_bvn_fullname == $g_fullname) ? '<p style="color: blue;"><b>Data Matched with First Name in Database</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Not-Matched with First Name in Database</b> <i class="fa fa-times"></i></p>');
                               $message .= "Phone Number: $g_bvn_phone ".(($g_bvn_phone == $g_phone) ? '<p style="color: blue;"><b>Data Matched with Phone Number in Database</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Not-Matched with Phone Number in Database</b> <i class="fa fa-times"></i></p>');*/
                               
                               echo (($g_bvn_phone == $g_phone) || ($g_bvn_fullname == $g_fullname)) ? '<p style="color: blue;"><b>Data Verified Successfully!</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Verification Not Successful! Please try with correct BVN that Match your Information on our System</b> <i class="fa fa-times"></i></p>';
                               
                               echo '<meta http-equiv="refresh" content="10;url=updateloans.php?id='.$_GET['id'].'&&acn='.$_GET['acn'].'&&mid='.base64_encode("405").'&&lid='.$_GET['lid'].'&&tab=tab_3">';
                            }
                            else{
                          		echo "<br><span class='bg-orange'>Oops! Network Error, please try again later </span>";
                            }
                               
                        }
                        else{
                           //empty
                           echo "Sorry! Service not available at the moment, please try again later!!";
                        }
                      
                  }
                  ?>
                  </div>
                  
                  </label>
                  <label class="col-sm-2"><button type="submit" class="btn bg-blue btn-flat" name="verifyG_BVN"><i class="fa fa-eye">&nbsp;Verify</i></button></label>
                </div>

                <?php
                $lookup_bvn = mysqli_query($link, "SELECT * FROM bvn_log WHERE accountID = '$old_gphone'");
                $fetch_lookupbvn = mysqli_fetch_array($lookup_bvn);
                $vry_bvn_num = mysqli_num_rows($lookup_bvn);
                if($vry_bvn_num == 1)
                {
                    $concat = $fetch_lookupbvn['mydata'];
                    $parameter = (explode('|',$concat));
                    $myold_newpicture = $parameter['20']; //bvn_logo.png  mybvn.jpg
                ?>
                <hr>
                <div align="center">
                    <img src="../img/bvn_logo.png" height="75" width="200"/>
                    <p><b>Last BVN Verification for Guarantor on <?php echo date("Y-m-d", strtotime($fetch_lookupbvn['date_time'])); ?></b></p>
                </div>
                <hr>
                <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color: blue;">Picture</label>
    			<div class="col-sm-7" align="center">
           			<div class="frame"><img src="../img/<?php echo ($myold_newpicture == "") ? 'image-placeholder.jpg' : $myold_newpicture; ?>" alt="<?php echo $fetch_lookupbvn['fname']; ?> Picture" height="100%" width="40%"/></div>
    			</div>
    			</div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">First Name</label>
                  <div class="col-sm-7">
                  <input name="bvn_fn" type="text" class="form-control" value="<?php echo $parameter['1']; ?>" placeholder="BVN First Name" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">Last Name</label>
                  <div class="col-sm-7">
                  <input name="bvn_ln" type="text" class="form-control" value="<?php echo $parameter['2']; ?>" placeholder="BVN Last Name" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">Middle Name</label>
                  <div class="col-sm-7">
                  <input name="bvn_mn" type="text" class="form-control" value="<?php echo $parameter['3']; ?>" placeholder="BVN Middle Name" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">Date of Birth</label>
                  <div class="col-sm-7">
                  <input name="bvn_mydob" type="text" class="form-control" value="<?php echo $parameter['4']; ?>" placeholder="Date of Birth" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">Phone Number</label>
                  <div class="col-sm-7">
                  <input name="bvn_ph" type="text" class="form-control" value="<?php echo $parameter['5']; ?>" placeholder="BVN Phone" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">Email Address</label>
                  <div class="col-sm-7">
                  <input name="bvn_email" type="text" class="form-control" value="<?php echo $parameter['6']; ?>" placeholder="Email Address" readonly>
                  </div>
                </div>
                <!--<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php //echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Confirm OTP</label>
                  <div class="col-sm-7">
                  <input name="bvn_otp" type="text" class="form-control" placeholder="Enter OTP Received after Verifying the BVN">
                  </div>
                </div>-->
                <?php
                }
                else{
                    echo "";
                }
                ?>

                  
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">BVN <?php echo ($mybvn != "" && strlen($mybvn) == "11" && $fetch_otpconfirm == 1) ? '<span style="color: '.(($myaltrow['theme_color'] == "") ? "blue" : $myaltrow['theme_color']).'">[<b>Verified</b> <i class="fa fa-check"></i>]</span>' : '<span style="color: '.(($myaltrow['alternate_color'] == "") ? "orange" : $myaltrow['alternate_color']).'">[<b>Not-Verified</b> <i class="fa fa-times"></i>]</span>'; ?></label>
                  <label class="col-sm-7">
                  
                  <input name="cust_bvn" type="text" class="form-control" value="<?php echo $mybvn; ?>" placeholder="BVN Number Here" maxlength="11">
                  <span style="color: orange;"> BVN Verification cost <b style="color: blue;"><?php echo $fetchsys_config['currency'].number_format($fetchsys_config['bvn_fee'],2,'.',','); ?></b> routed through your Super Wallet (Nigeria Account Only). </span>
                  <input name="cust_acctno" type="hidden" class="form-control" value="<?php echo $myget_acn; ?>">
                  <div id="bvn2"></div><br>
                  <div class="scrollable">
                  <?php
                  if(isset($_POST['verifyBVN'])){
                      
                       $id = $_GET['id'];
                       $userBvn = mysqli_real_escape_string($link, $_POST['cust_bvn']);
                       $actno = mysqli_real_escape_string($link, $_POST['cust_acctno']);
                       
                       $search_mycust = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$actno'");
                       $fetch_mycust = mysqli_fetch_array($search_mycust);
                       $fname = $fetch_mycust['fname'];
                       $lname = $fetch_mycust['lname'];
                       $dob = $fetch_mycust['dob'];
                       $phone = $fetch_mycust['phone'];
                       $AcctOfficer = $fetch_mycust['lofficer'];
                       
                       if($iwallet_balance < $bvn_fee){
           
                           echo "<br><span class='bg-orange'>Sorry! No sufficient fund in Client Wallet to perform this verification</span>";
                           
                       }
                       elseif(strlen($userBvn) != 11){
                           
                           echo "<br><span>BVN Number not Valid....</span>";
                           
                       }
                       elseif($ibvn_route == "Wallet Africa"){
                           
                            require_once "../config/bvnVerification_class.php";

                            $processBVN = $newBV->walletAfricaBVNVerifier($walletafrica_skey,$userBvn,$link);
                            $ResponseCode = $processBVN['ResponseCode'];
                       
                            if($ResponseCode == "200"){
                                                               
                                $wbalance = $iwallet_balance - $bvn_fee;
                               
                                $icm_id = "ICM".time();
                                $exp_id = "EXP".time();
                                $myOtp = substr((uniqid(rand(),1)),3,6);
                                $rOrderID = "EA-bvnCharges-".time();
                               
                                $date_time = date("Y-m-d");
                                $wallet_date_time = date("Y-m-d H:i:s");
                               
                                //substr()
                                $bvn_fname = $processBVN['FirstName'];
                                $bvn_lname = $processBVN['LastName'];
                                $bvn_dob = $processBVN['DateOfBirth'];
                                $bvn_phone = "+234".substr($processBVN['PhoneNumber'],-10);
                                $correct_bvnPhone = $processBVN['PhoneNumber'];
                                $bvn_email = $processBVN['Email'];
                                $bvn_picture = $processBVN['Picture'];
                                $dynamicStr    = md5(date("Y-m-d h:i"));
                                $image_converted = base64_to_jpeg($bvn_picture, $dynamicStr.".png");

                                //20 array row
                                $mybvn_data = $processBVN['BVN']."|".$processBVN['FirstName']."|".$processBVN['LastName']."|".$processBVN['MiddleName']."|".$processBVN['DateOfBirth']."|".$processBVN['PhoneNumber']."|".$processBVN['Email']."|".$processBVN['EnrollmentBank']."|".$processBVN['EnrollmentBranch']."|".$processBVN['Gender']."|".$processBVN['LevelOfAccount']."|".$processBVN['LgaOfOrigin']."|".$processBVN['LgaOfResidence']."|".$processBVN['MaritalStatus']."|".$processBVN['NameOnCard']."|".$processBVN['Nationality']."|".$processBVN['StateOfOrigin']."|".$processBVN['StateOfResidence']."|".$processBVN['Title']."|".$processBVN['WatchListed']."|".$image_converted;
                               
                                $search_bvnverify = mysqli_query($link, "SELECT * FROM bvn_log WHERE accountID = '$actno'");
                                $fetch_bvnverify = mysqli_fetch_array($search_bvnverify);
                                $bvn_nos = mysqli_num_rows($search_bvnverify);
                                $concat = $fetch_bvnverify['mydata'];
                                $parameter = (explode('|',$concat));
                                $old_picture = $parameter[20];
                               
                                $update_wallet = mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$wbalance' WHERE institution_id = '$institution_id'");
                               
                                ($bvn_nos == 1 && $old_picture != "") ? unlink("../img/".$old_picture) : "";
                                ($bvn_nos == 1) ? $update_bvn = mysqli_query($link, "UPDATE bvn_log SET mydata = '$mybvn_data' WHERE accountID = '$actno'") : $insert_bvn = mysqli_query($link, "INSERT INTO bvn_log VALUE(null,'$institution_id','$isbranchid','$actno','$AcctOfficer','$mybvn_data','$bvn_fee','$wallet_date_time','$rOrderID')");
                                
                                $insert_income = mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$rOrderID','$userBvn','','$bvn_fee','Debit','$icurrency','BVN_Charges','Response: $icurrency.$bvn_fee was charged for Guarantor BVN Verification','successful','$wallet_date_time','$uid','$wbalance','')");

                                $insert_income = mysqli_query($link, "INSERT INTO expenses VALUES(null,'$institution_id','$exp_id','BVN','$bvn_fee','$date_time','Customer BVN Verification Charges')");
                                
                                $insert_income = mysqli_query($link, "INSERT INTO income VALUES(null,'','$icm_id','BVN','$bvn_fee','$date_time','Customer BVN Verification Charges')");
                                
                                ($bvn_lname == $lname || $bvn_phone == $phone) ? mysqli_query($link, "UPDATE borrowers SET unumber = '$userBvn' WHERE account = '$actno'") : "";
                                
                                ($bvn_lname == $lname || $bvn_phone == $phone) ? mysqli_query($link, "UPDATE loan_info SET unumber = '$userBvn' WHERE id = '$id'") : "";
                                
                                echo ($bvn_lname == $lname || $bvn_phone == $phone) ? '<p style="color: blue;"><b>Data Verified Successfully!</b> <i class="fa fa-check"></i></p>' : '<p style="color: orange;"><b>Data Verification Not Successful! Please try with correct BVN that Match your Information on our System</b> <i class="fa fa-times"></i></p>';
                               
                                echo '<meta http-equiv="refresh" content="3;url=updateloans.php?id='.$_GET['id'].'&&acn='.$_GET['acn'].'&&mid='.base64_encode("405").'&&lid='.$_GET['lid'].'&&tab=tab_3">';                            
                               
                            }else{
                          		echo "<br><span class='bg-orange'>Oops! Network Error, please try again later </span>";
                            }
                               
                        }
                        else{
                           //empty
                           echo "Sorry! Service not available at the moment, please try again later!!";                           
                        }
                      
                    }
                    ?>
                  </div>
                  </label>
                  <label class="col-sm-2"><button type="submit" class="btn bg-blue btn-flat" name="verifyBVN"><i class="fa fa-eye">&nbsp;Verify</i></button></label>
                </div>
                
                <?php
                $acctnum = $_GET['acn'];
                $lookup_bvn = mysqli_query($link, "SELECT * FROM bvn_log WHERE accountID = '$acctnum'");
                $fetch_lookupbvn = mysqli_fetch_array($lookup_bvn);
                $vry_bvn_num = mysqli_num_rows($lookup_bvn);
                if($vry_bvn_num == 1)
                {
                    $concat = $fetch_lookupbvn['mydata'];
                    $parameter = (explode('|',$concat));
                    $myold_newpicture = $parameter['20']; //bvn_logo.png  mybvn.jpg
                ?>
                <hr>
                <div align="center">
                    <img src="../img/bvn_logo.png" height="75" width="200"/>
                    <p><b>Last BVN Verification for Customer on <?php echo date("Y-m-d", strtotime($fetch_lookupbvn['date_time'])); ?></b></p>
                </div>
                <hr>
                <div class="form-group">
                <label for="" class="col-sm-3 control-label" style="color: blue;">Picture</label>
    			<div class="col-sm-7" align="center">
           			<div class="frame"><img src="../img/<?php echo ($myold_newpicture == "") ? 'image-placeholder.jpg' : $myold_newpicture; ?>" alt="<?php echo $fetch_lookupbvn['fname']; ?> Picture" height="100%" width="40%"/></div>
    			</div>
    			</div>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">First Name</label>
                  <div class="col-sm-7">
                  <input name="bvn_fn" type="text" class="form-control" value="<?php echo $parameter['1']; ?>" placeholder="BVN First Name" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">Last Name</label>
                  <div class="col-sm-7">
                  <input name="bvn_ln" type="text" class="form-control" value="<?php echo $parameter['2']; ?>" placeholder="BVN Last Name" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">Middle Name</label>
                  <div class="col-sm-7">
                  <input name="bvn_mn" type="text" class="form-control" value="<?php echo $parameter['3']; ?>" placeholder="BVN Middle Name" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">Date of Birth</label>
                  <div class="col-sm-7">
                  <input name="bvn_mydob" type="text" class="form-control" value="<?php echo $parameter['4']; ?>" placeholder="Date of Birth" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">Phone Number</label>
                  <div class="col-sm-7">
                  <input name="bvn_ph" type="text" class="form-control" value="<?php echo $parameter['5']; ?>" placeholder="BVN Phone" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">Email Address</label>
                  <div class="col-sm-7">
                  <input name="bvn_email" type="text" class="form-control" value="<?php echo $parameter['6']; ?>" placeholder="Email Address" readonly>
                  </div>
                </div>
                <!--<div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue">Confirm OTP</label>
                  <div class="col-sm-7">
                  <input name="bvn_otp" type="text" class="form-control" placeholder="Enter OTP Received after Verifying the BVN">
                  </div>
                </div>-->
                <?php
                }
                else{
                    echo "";
                }
                ?>
                

			 <div align="center">
              <div class="box-footer">
                	<button type="submit" class="btn bg-blue btn-flat" name="upload"><i class="fa fa-save">&nbsp;Update Profile</i></button>
              </div>
			  </div>
<?php
if(isset($_POST['upload']))
{
    $id = $_GET['id'];
    $tid = $_SESSION['tid'];
    $mybvn = mysqli_real_escape_string($link, $_POST['cust_bvn']);
    $myg_bvn = mysqli_real_escape_string($link, $_POST['g_bvn']);
    
    $search_lcust = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'");
    $fetch_lcust = mysqli_fetch_array($search_lcust);
    $mylborrower = $fetch_lcust['baccount'];
    
    foreach ($_FILES['uploaded_file']['name'] as $key => $name){
      
        $newFilename = $name;
            
        if($newFilename == "")
        {
            echo "";
        }
        else{
            $newlocation = 'document/'.$newFilename;
      		if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'][$key], '../application/document/'.$newFilename))
       		{
       		    mysqli_query($link, "INSERT INTO attachment VALUES(null,'$id','$mylborrower','$tid','$newlocation',NOW())") or die (mysqli_error($link));
       		}
        }
        	
    }
	
	$update_record = mysqli_query($link, "UPDATE borrowers SET unumber = '$mybvn' WHERE account = '$mylborrower'");
	$update_record = mysqli_query($link, "UPDATE loan_info SET unumber = '$mybvn', g_bvn = '$myg_bvn' WHERE id = '$id'");
	    
	if(!$update_record)
    {
        echo "<script>alert('Error.....Please try again later!'); </script>";
    }
    else{
        echo "<script>alert('Profile Updated Successfully!!'); </script>";
    }

}
?>

			 </form> 
              </div>
			  
	<?php
	}
	elseif($tab == 'tab_5')
	{
	?>
			  
			  <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_5') ? 'active' : ''; ?>" id="tab_5">
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
			    <div align="center"><h3><b>Loan Repayment Schedule</b></h3></div>
			<div class="box-body">

<?php
$lid = $_GET['lid'];
$searchin = mysqli_query($link, "SELECT * FROM payment_schedule WHERE lid = '$lid'") or die (mysqli_error($link));
$haveit = mysqli_fetch_array($searchin);
$idmet= $haveit['id'];
$lproduct = $haveit['lproduct'];

$search_mylp = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
$fetch_mylp = mysqli_fetch_array($search_mylp);
$iintr_type = $fetch_mylp['interest_type'];
?>

				<div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Duration Type:</label>
                  <div class="col-sm-10">
				  <select name="d1" class="form-control" readonly>
				 <option value="<?php echo $haveit['term']; ?>"><?php echo $haveit['schedule']; ?></option>
				  </select>
                  </div>
                  </div>
                  
                 <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Interest Type:</label>
                  <div class="col-sm-10">
				  <select name="intr_type" class="form-control" readonly>
				 <option value="<?php echo ($lproduct == "") ? 'Flat Rate' : $iintr_type; ?>"><?php echo ($lproduct == "") ? 'Flat Rate' : $iintr_type; ?></option>
				  </select>
                  </div>
                  </div>
				  
				  <input name="schedule" type="hidden" value="<?php echo $haveit['schedule']; ?>" class="form-control">
				  
				   <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue">Schedules:</label>
                  <div class="col-sm-10">
				<table>
                <tbody> 
<?php
$get_id = $_GET['id'];
$searchin = mysqli_query($link, "SELECT * FROM pay_schedule WHERE get_id = '$get_id'") or die (mysqli_error($link));
while($haveit = mysqli_fetch_array($searchin))
{
$idmet= $haveit['id'];
?>			
				<tr>
        			<td><input id="optionsCheckbox" class="uniform_on" name="selector[]" type="hidden" value="<?php echo $idmet; ?>" checked></td>
                    <td width="400"><input name="schedulek[]" type="date" class="form-control pull-right" placeholder="Schedule" value="<?php echo $haveit['schedule']; ?>"></td>
                    <td width="300"><input name="balance[]" type="text" class="form-control" placeholder="Balance" value="<?php echo $haveit['balance']; ?>"></td>
        			<td width="130"><input name="payment[]" type="text" class="form-control" placeholder="Payment" value="<?php echo number_format($haveit['payment'],2,'.',''); ?>"></td>
        			<!--<td width="100"><input name="lstatus[]" type="text" class="form-control" placeholder="Status" value="<?php echo $haveit['status']; ?>" disabled></td>-->
			    </tr>
<?php } ?>

<?php
$get_lid = $_GET['lid'];
$searchliin = mysqli_query($link, "SELECT SUM(payment) FROM pay_schedule WHERE lid = '$get_lid'") or die (mysqli_error($link));
$fetchliin = mysqli_fetch_array($searchliin);
$overall_payment = $fetchliin['SUM(payment)'];
?>
                <tr>
        			<td></td>
                    <td width="400"></td>
                    <td width="300"></td>
        			<td width="130"><b><?php echo number_format($overall_payment,2,'.',','); ?></b></td>
        			<td width="100"></td>
			    </tr>
				</tbody>
                </table>
<hr>
<?php
$id = $_GET['id'];
$searchin2 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE get_id = '$id' AND (balance = '0' OR balance <= '0')") or die (mysqli_error($link));
$numit = mysqli_num_rows($searchin2);
?>
<?php echo ($numit == 1) ? "<span class='label bg-".(($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color'])."'>Completed</span>" : "<span class='label bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>Progressing...(Schedule not completed yet).</span>"; ?>
<hr>
<div align="left">
              <div class="box-footer">

                				<button type="submit" class="btn bg-blue" name="add_sch_rows" <?php echo ($numit == 1) ? 'disabled' : ''; ?>><i class="fa fa-plus">&nbsp;Generate Schedule</i></button>
                				<button name="delrow2" type="submit" class="btn bg-orange"><i class="fa fa-trash">&nbsp;Delete Schedule</i></button> 
                                <?php
                                $id = $_GET['id'];
                                $comfirm_select1 = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'") or die (mysqli_error($link));
                                $comfirm_row1 = mysqli_fetch_array($comfirm_select1);
                                if($comfirm_row1['status'] == "Approved"){
                                    echo "";
                                }
                                else{
                                ?>
                                    <button type="submit" class="btn bg-blue" name="add_pay_schedule"><i class="fa fa-save">&nbsp;Save</i></button>
                                <?php
                                }
                                ?>
                                <?php
                                $id = $_GET['id'];
                                $comfirm_select = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$id'") or die (mysqli_error($link));
                                $comfirm_row = mysqli_fetch_array($comfirm_select);
                                if($comfirm_row['upstatus'] == "Completed"){
                                ?>
                                    <button type="submit" class="btn bg-orange" name="generate_pdf"><i class="fa fa-print">&nbsp;Print Schedule!</i></button>
                                <?php
                                }else{
                                    echo "";
                                }
                                ?>
              </div>
			  </div>
   <?php
						if(isset($_POST['delrow2'])){
						$idm = $_GET['id'];
							$lid = $_GET['lid'];
							$id=$_POST['selector'];
							$N = count($id);
						if($id == ''){
						echo "<script>alert('Row Not Selected!!!'); </script>";	
						echo "<script>window.location='updateloans.php?id=".$idm."&&acn=".$_GET['acn']."&&mid=".base64_encode("405")."&&lid=".$lid."&&tab=tab_5'; </script>";
						}
						else{
							for($i=0; $i < $N; $i++)
							{
							    $update_interest_calc = mysqli_query($link, "DELETE FROM interest_calculator WHERE lid = '$lid'");
								$result = mysqli_query($link,"DELETE FROM pay_schedule WHERE id ='$id[$i]'");
								echo "<script>window.location='updateloans.php?id=".$idm."&&acn=".$_GET['acn']."&&mid=".base64_encode("405")."&&lid=".$lid."&&tab=tab_5'; </script>";
							}
						}
						}
?>

<?php
if(isset($_POST['add_sch_rows']))
{
$id = $_GET['id'];
$lid = $_GET['lid'];
$tid = $_GET['acn'];

$day = mysqli_real_escape_string($link, $_POST['d1']);
$intr_type = mysqli_real_escape_string($link, $_POST['intr_type']);
$schedule_of_paymt = mysqli_real_escape_string($link, $_POST['schedule']);

$N = $day;

//$count = ($N <= 2)

$process_data2 = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'") or die (mysqli_error($link));
$fetch = mysqli_fetch_array($process_data2);
$totalamount_topay = $fetch['amount_topay'];
$baccount = $fetch['baccount'];
$amount_borrowed = $fetch['amount'];
$lproduct = $fetch['lproduct'];

$search_product = mysqli_query($link, "SELECT * FROM loan_product WHERE id = '$lproduct'");
$get_product = mysqli_fetch_object($search_product);
$duration = $get_product->duration;
$interest = $get_product->interest / 100;

$amt_topay_per_duration = $amount_borrowed / $duration;

$new_interest = ($interest * $amount_borrowed) + $amt_topay_per_duration;

$calc_myint = ($amount_borrowed / $duration) + (($amount_borrowed / $duration) * $interest);

//$int_rate = ($intr_type == "Flat Rate") ? ($totalamount_topay / $duration) : $calc_myint;
$int_rate = ($intr_type == "Flat Rate") ? ($totalamount_topay / $duration) : $new_interest;

$first_balance = ($intr_type == "Flat Rate") ? number_format(($totalamount_topay - $int_rate),0,'.','') : number_format(($amount_borrowed - $amt_topay_per_duration),0,'.','');

$verify_data = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid'") or die (mysqli_error($link));
$verify_data2 = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
if((mysqli_num_rows($verify_data) == 0) && (mysqli_num_rows($verify_data2) == 0) && ($schedule_of_paymt == "Monthly") && ($intr_type == "Flat Rate"))
{
    $insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$first_balance','$int_rate','$duration')") or die (mysqli_error($link));
    
    $cur_date = strtotime($fetch['salary_date']);
    $lastDayThisMonth = date("Y-m-d", strtotime("+1 month", $cur_date));
	
    
    $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth','$first_balance','$int_rate','UNPAID','$institution_id','','$isbranchid','$uid','NotSent','','Pending')") or die (mysqli_error($link));
    //$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$first_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    	    
    for($i = 1; $i < $N; $i++) {
    	        
    	//CONFIRMATION OF INTEREST RATE
        $select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
        $get_calculator = mysqli_fetch_array($select_int_calculator);
        $balance = $get_calculator['amt_to_pay'];
        $lrate = $get_calculator['int_rate'];
        $new_balance = number_format(($balance - $lrate),0,'.','');
    	        
    	$verify_data1 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
        $fetch_data1 = mysqli_fetch_array($verify_data1);
        $my_schedule = strtotime($fetch_data1['schedule']);
        $lastDayThisMonth2 = date("Y-m-d", strtotime("+1 month", $my_schedule));
        	    
        $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth2','$new_balance','$int_rate','UNPAID','$institution_id','','$isbranchid','$uid','NotSent','','Pending')") or die (mysqli_error($link));
        $update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    	        
    }
    echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$tid."&&mid=NDA1&&lid=".$lid."&&tab=tab_5'; </script>";
	
}
elseif((mysqli_num_rows($verify_data) == 0) && (mysqli_num_rows($verify_data2) == 0) && ($schedule_of_paymt == "Monthly") && ($intr_type != "Flat Rate"))
{
    $insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$first_balance','$amt_topay_per_duration','$duration')") or die (mysqli_error($link));
    
    $cur_date = strtotime($fetch['salary_date']);
    $lastDayThisMonth = date("Y-m-d", strtotime("+1 month", $cur_date));
    //$amt_topayby_borrower = $amt_topay_per_duration + ($first_balance * $interest);
    
    $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth','$first_balance','$int_rate','UNPAID','$institution_id','','$isbranchid','$uid','NotSent','','Pending')") or die (mysqli_error($link));
    //$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$first_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    
    for($i = 1; $i < $N; ++$i) {
    	        
    	//CONFIRMATION OF INTEREST RATE
        $select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
        $get_calculator = mysqli_fetch_array($select_int_calculator);
        $balance = $get_calculator['amt_to_pay'];
        $myduration = $get_calculator['duration'] - 1;
        $divided_amt = $get_calculator['int_rate'];
        //$next_amt_topay = ($balance / $myduration) + (($balance / $myduration) * $interest);
        //$next_amt_topay = (($balance - $amt_topay_per_duration) * $interest) + $amt_topay_per_duration;
        $next_amt_topay = ($interest * $balance) + $divided_amt;
        $new_balance = ($myduration <= 1) ? '0' : number_format(($balance - $divided_amt),0,'.','');
    	        
    	$verify_data1 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
        $fetch_data1 = mysqli_fetch_array($verify_data1);
        $my_schedule = strtotime($fetch_data1['schedule']);
        $lastDayThisMonth2 = date("Y-m-d", strtotime("+1 month", $my_schedule));
        	    
        $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth2','$new_balance','$next_amt_topay','UNPAID','$institution_id','','$isbranchid','$uid','NotSent','','Pending')") or die (mysqli_error($link));
        $update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance', duration = '$myduration' WHERE lid = '$lid'") or die (mysqli_error($link));
    	
    }
    $verify_repaysum = mysqli_query($link, "SELECT SUM(payment) FROM pay_schedule WHERE lid = '$lid' AND status = 'UNPAID'");
    $fetch_repaysum = mysqli_fetch_array($verify_repaysum);
    $total_repaysum = number_format($fetch_repaysum['SUM(payment)'],2,'.','');
    mysqli_query($link, "UPDATE loan_info SET amount_topay = '$total_repaysum', balance = '$total_repaysum' WHERE lid = '$lid'");
    echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$tid."&&mid=NDA1&&lid=".$lid."&&tab=tab_5'; </script>";
	
}
elseif((mysqli_num_rows($verify_data) == 0) && (mysqli_num_rows($verify_data2) == 0) && ($schedule_of_paymt == "Weekly") && ($intr_type == "Flat Rate")) {
    
    $insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$first_balance','$int_rate','$duration')") or die (mysqli_error($link));
    
    $cur_date = strtotime($fetch['salary_date']);
    $lastDayThisMonth = date("Y-m-d", strtotime("+1 week", $cur_date));
	
    
    $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth','$first_balance','$int_rate','UNPAID','$institution_id','','$isbranchid','$uid','NotSent','','Pending')") or die (mysqli_error($link));
    //$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$first_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    	    
    for($i = 1; $i < $N; $i++) {
    	        
    	//CONFIRMATION OF INTEREST RATE
        $select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
        $get_calculator = mysqli_fetch_array($select_int_calculator);
        $balance = $get_calculator['amt_to_pay'];
        $lrate = $get_calculator['int_rate'];
        $new_balance = number_format(($balance - $lrate),0,'.','');
    	        
    	$verify_data1 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
        $fetch_data1 = mysqli_fetch_array($verify_data1);
        $my_schedule = strtotime($fetch_data1['schedule']);
        $lastDayThisMonth2 = date("Y-m-d", strtotime("+1 week", $my_schedule));
        	    
        $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth2','$new_balance','$int_rate','UNPAID','$institution_id','','$isbranchid','$uid','NotSent','','Pending')") or die (mysqli_error($link));
        $update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    	        
    }
    echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$tid."&&mid=NDA1&&lid=".$lid."&&tab=tab_5'; </script>";
}
elseif((mysqli_num_rows($verify_data) == 0) && (mysqli_num_rows($verify_data2) == 0) && ($schedule_of_paymt == "Weekly") && ($intr_type != "Flat Rate")) {
    
    $insert = mysqli_query($link, "INSERT INTO interest_calculator VALUES(null,'$lid','$first_balance','$amt_topay_per_duration','$duration')") or die (mysqli_error($link));
    
    $cur_date = strtotime($fetch['salary_date']);
    $lastDayThisMonth = date("Y-m-d", strtotime("+1 week", $cur_date));
	//$amt_topayby_borrower = $amt_topay_per_duration + ($first_balance * $interest);
    
    $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth','$first_balance','$int_rate','UNPAID','$institution_id','','$isbranchid','$uid','NotSent','','Pending')") or die (mysqli_error($link));
    //$update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$first_balance' WHERE lid = '$lid'") or die (mysqli_error($link));
    	    
    for($i = 1; $i < $N; ++$i) {
    	        
    	//CONFIRMATION OF INTEREST RATE
        $select_int_calculator = mysqli_query($link, "SELECT * FROM interest_calculator WHERE lid = '$lid'") or die (mysqli_error($link));
        $get_calculator = mysqli_fetch_array($select_int_calculator);
        $balance = $get_calculator['amt_to_pay'];
        $myduration = $get_calculator['duration'] - 1;
        $divided_amt = $get_calculator['int_rate'];
        //$next_amt_topay = ($balance / $myduration) + (($balance / $myduration) * $interest);
        //$next_amt_topay = (($balance - $amt_topay_per_duration) * $interest) + $amt_topay_per_duration;
        $next_amt_topay = ($interest * $balance) + $divided_amt;
        $new_balance = ($myduration == 1) ? '0' : number_format(($balance - $divided_amt),0,'.','');
    	        
    	$verify_data1 = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' ORDER BY id DESC") or die (mysqli_error($link));
        $fetch_data1 = mysqli_fetch_array($verify_data1);
        $my_schedule = strtotime($fetch_data1['schedule']);
        $lastDayThisMonth2 = date("Y-m-d", strtotime("+1 week", $my_schedule));
        	    
        $insert = mysqli_query($link, "INSERT INTO pay_schedule VALUES(null,'$lid','$id','$baccount','','$lastDayThisMonth2','$new_balance','$next_amt_topay','UNPAID','$institution_id','','$isbranchid','$uid','NotSent','','Pending')") or die (mysqli_error($link));
        $update_calculator = mysqli_query($link, "UPDATE interest_calculator SET amt_to_pay = '$new_balance', duration = '$myduration' WHERE lid = '$lid'") or die (mysqli_error($link));
    	        
    }
    $verify_repaysum = mysqli_query($link, "SELECT SUM(payment) FROM pay_schedule WHERE lid = '$lid' AND status = 'UNPAID'");
    $fetch_repaysum = mysqli_fetch_array($verify_repaysum);
    $total_repaysum = number_format($fetch_repaysum['SUM(payment)'],2,'.','');
    mysqli_query($link, "UPDATE loan_info SET amount_topay = '$total_repaysum', balance = '$total_repaysum' WHERE lid = '$lid'");
    echo "<script>window.location='updateloans.php?id=".$id."&&acn=".$tid."&&mid=NDA1&&lid=".$lid."&&tab=tab_5'; </script>";
}
}
?>
                  </div>
                  </div>
				  
              </div>

<?php
if(isset($_POST['add_pay_schedule']))
{
$idm = $_GET['id'];
$lid = $_GET['lid'];
$id=$_POST['selector'];
//$N = count($id);
	
$i = 0;
$tid = $_SESSION['tid'];
$acn = $_GET['acn'];
foreach($_POST['selector'] as $s)
{
	$schedule = mysqli_real_escape_string($link, $_POST['schedulek'][$i]);
	$update = mysqli_query($link, "UPDATE pay_schedule SET schedule = '$schedule' WHERE id = '$s'") or die (mysqli_error($link));
	$i++;
	$insert = mysqli_query($link, "UPDATE loan_info SET upstatus = 'Completed' WHERE id = '$idm'") or die (mysqli_error($link));
	if(!($update && $insert))
	{
	echo "<script>alert('Record not inserted.....Please try again later!'); </script>";
	}
	else{
	echo "<script>alert('Payment Scheduled Successfully!!'); </script>";
	echo "<script>window.location='updateloans.php?id=".$idm."&&acn=".$acn."&&mid=NDA1&&lid=".$lid."&&tab=tab_5'; </script>";
	}
}
}
?>

<?php
if(isset($_POST['generate_pdf']))
{
    $id = $_GET['id'];
    $lid = $_GET['lid'];
    $tid = $_GET['acn'];
	echo "<script>window.open('../pdf/view/pdf_payschedule.php?id=".$id."&&acn=".$tid."&&mid=NDA1&&lid=".$lid."&&instid=".$institution_id."', '_blank'); </script>";
}
?>
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
</div>