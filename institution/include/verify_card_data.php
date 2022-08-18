<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><a href="updateloans.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA1&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_3"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-money"></i> Direct Debit Mandate Request</h3>
            </div>
             <div class="box-body">

<?php
	$lid = $_GET['lid'];
	$search_myloaninfo = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'");
	$fetch_myloaninfo = mysqli_fetch_array($search_myloaninfo);
	$mandate_status = $fetch_myloaninfo['mandate_status'];
?>
            			
		           <div class="alert alert-default fade in">
   				  <p style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>;"><b><i>Note that you must verify customer Bank details to allow Direct Debit.</i></b></p>
					</div>
		             <hr>
            
        <form class="form-horizontal" method="post" enctype="multipart/form-data">

        <!--action="request_charge.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&lid=<?php echo $_GET['lid']; ?>"-->
<?php

if(isset($_POST['save'])){

    include("../config/restful_apicalls.php");
    
    $result = array();
    $lid = $_GET['lid'];
    $acn = $_GET['acn'];
    $search_customer = mysqli_query($link, "SELECT * FROM borrowers WHERE account = '$acn'");
    $get_customer = mysqli_fetch_object($search_customer);
    $customer_email = $get_customer->email;
    $customer_phone = $get_customer->phone;
    $customer_name = $get_customer->lname.' '.$get_customer->fname;
    $requestid = date("dmY").time();
    
    //DIRECT DEBIT ACTIVATION FEE
    $ddActivationFee = $fetchsys_config['auth_charges'];
    $calc_mywalletBalance = $iassigned_walletbal - $ddActivationFee;

    $search_restapi2 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'remita'");
    $fetch_restapi2 = mysqli_fetch_object($search_restapi2);
    $url = $fetch_restapi2->api_url;

    $api_url = $url."remita/exapp/api/v1/send/api/echannelsvc/echannel/mandate/setup";

    //transferrecipient
    $bank_code =  mysqli_real_escape_string($link, $_POST['bank_code']);
    $account_number =  mysqli_real_escape_string($link, $_POST['account_number']);
    $currency =  mysqli_real_escape_string($link, $_POST['currency']);
    $country =  mysqli_real_escape_string($link, $_POST['country']);

    $search_repayment_asc = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' ORDER BY id ASC");
    $fetch_repayment_asc = mysqli_fetch_array($search_repayment_asc);

    $search_repayment_desc = mysqli_query($link, "SELECT * FROM pay_schedule WHERE lid = '$lid' ORDER BY id DESC");
    $count = mysqli_num_rows($search_repayment_desc);
    $fetch_repayment_desc = mysqli_fetch_array($search_repayment_desc);
    
    $date = new DateTime($fetch_repayment_desc['schedule']);
    $date->sub(new DateInterval('P5D')); //substract 5 days from the original date
    $date_now = $date->format('Y-m-d');
    
    $current_date = date("Y-m-d");
    
    $startDate = ($count == "1") ? date("d/m/Y", strtotime($current_date)) : date("d/m/Y", strtotime($fetch_repayment_asc['schedule']));
    
    $endDate = date("d/m/Y", strtotime($fetch_repayment_desc['schedule']));
    
    $amt = number_format($fetch_repayment_asc['payment'],2,'.','');

    //REMITAL CREDENTIALS
    $remita_merchantid = $fetch_icurrency->remitaMerchantId;
    $remita_apikey = $fetch_icurrency->remitaApiKey;
    $remita_serviceid = $fetch_icurrency->remitaServiceId;
    $api_token = $fetch_icurrency->remitaApiToken;

    $concat_param = $remita_merchantid.$remita_serviceid.$requestid.$amt.$remita_apikey;
    $hash = hash('sha512', $concat_param);

    $postdata = array(
        "merchantId" => $remita_merchantid,
        "serviceTypeId" => $remita_serviceid,
        "hash"  => $hash,
        "payerName" => $customer_name,
        "payerEmail"  => $customer_email,
        "payerPhone"  => $customer_phone,
        "payerBankCode" => ($bank_code === "221") ? "039" : $bank_code,
        "payerAccount"  => $account_number,
        "requestId" => $requestid,
        "amount"    => $amt,
        "startDate" => $startDate,
        "endDate"   => $endDate,
        "mandateType"   => "DD",
        "maxNoOfDebits" => $count
    );

    $make_call = callAPI('POST', $api_url, json_encode($postdata));
    //$response2 = json_decode(json_encode($make_call), true);
    $response = trim(json_decode(json_encode($make_call), true),'jsonp ();');
    
    $result = json_decode($response, true);
    
    //print_r($postdata);
    //print_r($response2);
    //print_r($result);
    
    if($iassigned_walletbal < $ddActivationFee){
        
        echo "<div class='alert bg-".(($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color'])."'>Sorry!....Please Fund your Wallet to Complete this Direct Debit Activation.</div>";
        
    }
    elseif($result['statuscode'] === "040" || $result['status'] === "OTP Not Supported By Bank"){

        $mandateId = $result['mandateId'];
        $requestId = $result['requestId'];
        
        //echo $result['statuscode'];
        //echo $bank_code;

        mysqli_query($link, "UPDATE loan_info SET mandate_id = '$mandateId', request_id = '$requestId', funcing_acct = '$account_number', funding_bankcode = '$bank_code' WHERE lid = '$lid'");

        if($bank_code === "214" || $bank_code === "057" || $bank_code === "302" || $bank_code === "035" || $bank_code === "082" || $bank_code === "101" || $bank_code === "030"){
            
            //Initiate a new cURL session
            $ch = curl_init();
            
            $request_ts = date("Y-m-d")."T".date("h:m:s")."+000000";
            //echo $request_ts;
  
            $concat_param2 = $remita_apikey.$requestId.$api_token;
            $hash2 = hash("sha512", $concat_param2);

            $api_url2 = $url."remita/exapp/api/v1/send/api/echannelsvc/echannel/mandate/requestAuthorization";

            $postdata2 = array(
                'mandateId' => $mandateId,
                'requestId' => $requestId
            );
            
            curl_setopt($ch, CURLOPT_URL, $api_url2);
        
            //Set the CURLOPT_RETURNTRANSFER option ton true
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    
            //set the CURLOPT_POST option to true for POST request
            curl_setopt($ch, CURLOPT_POST, TRUE);
                    
            //Set the request data as Array using json_encoded function
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata2));
                    
            //Set custom headers for Content-Type header
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "MERCHANT_ID: $remita_merchantid",
                "API_KEY: $remita_apikey",
                "REQUEST_ID: $requestId",
                "REQUEST_TS: $request_ts",
                "API_DETAILS_HASH: $hash2"
                ));
                        
            //Execute cURL request with all previous settings
            $response2 = curl_exec($ch);
            $output2 = trim(json_decode(json_encode($response2), true),'jsonp ();');
    
            $result2 = json_decode($output2, true);
            
            //print_r($result2);

            if($result2['statuscode'] === "00"){

                $remitaRRR = $result2['remitaTransRef'];

                mysqli_query($link, "UPDATE loan_info SET remita_rrr = '$remitaRRR', request_ts = '$request_ts' WHERE lid = '$lid'");

                echo "<script>alert('Mandate Request Created Successfully....Click Okay to Proceed'); </script>";
                echo "<script>window.location='confirm_otp.php?id=".$_GET['id']."&&acn=".$_GET['acn']."&&mid=NDA1&&lid=".$_GET['lid']."&&tab=tab_3'; </script>";

            }

        }
        else{

            $concat_param3 = $remita_merchantid.$remita_apikey.$requestId;
            $hash3 = hash("sha512", $concat_param3);
            $currentDateTime = date("Y-m-d h:i:s");

            $api_url3 = $url."remita/ecomm/mandate/form/$remita_merchantid/$hash3/$mandateId/$requestId/rest.reg";
            
            (($irole != "agent_manager" || $irole != "institution_super_admin" || $irole != "merchant_super_admin") && $isubagent_wallet === "Enabled") ? mysqli_query($link, "UPDATE user SET wallet_balance = '$calc_mywalletBalance' WHERE id = '$iuid'") : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$calc_mywalletBalance' WHERE institution_id = '$institution_id'");
            
            mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$requestid','self','','$ddActivationFee','Debit','$icurrency','DD_Activation','Response: $icurrency.$ddActivationFee was charged for Direct Debit Activation Request for Loan ID: $lid with Account ID: $acn','successful','$currentDateTime','$iuid','$calc_mywalletBalance','')");
            
            mysqli_query($link, "UPDATE loan_info SET mandate_status = 'InProcess', request_ts = '$request_ts' WHERE lid = '$lid'");
            
            echo "<script>alert('Mandate Request Created Successfully....Click Okay to Proceed'); </script>";
            echo "<script>window.open('$api_url3', '_blank'); </script>";
            echo "<script>window.location='verify_card.php?id=".$_GET['id']."&&acn=".$_GET['acn']."&&mid=NDA1&&lid=".$_GET['lid']."&&tab=tab_3'; </script>";

        }

    }
    else{
        
        $msg = $result['status'];
        echo "<script>alert('$msg ...Please try again later'); </script>";

    }
    
    
}

?>
             
    <?php
    if($mandate_status === "InProcess")
    {
    ?>
        
        <div class="box-body">
            
            <p>Direct Debit Activation is still <b>under processing</b> by the Bank</p>
            <p>Remita Retrieval Reference: <b><?php echo $fetch_myloaninfo['mandate_id']; ?></b></p>
            <p>Request ID: <b><?php echo $fetch_myloaninfo['request_id']; ?></b></p>
            <p><a href="updateloans.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA1&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_3"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Click Here</button></a> to go back</p>
            
        </div>
    
    <?php
    }
    else{
    ?>
    
            <div class="box-body">
            <?php
                  if($inip_route == "Wallet Africa")
                  {
                 ?>

                 <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Country</label>
                    <div class="col-sm-6">
                        <select name="country"  class="form-control select2" id="country" onchange="loadbank();" required>
                          <option value="" selected>Please Select Country</option>
                          <option value="NG">Nigeria</option>
                          <!--<option value="GH">Ghana</option>
                          <option value="KE">Kenya</option>
                          <option value="UG">Uganda </option>
                          <option value="TZ">Tanzania</option>-->
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="account_number" type="text" id="account_number" onkeydown="loadaccount();" class="form-control" placeholder="Bank Account Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
                  
                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Bank Name</label>
                    <div class="col-sm-6">
                        <div id="bank_list">------------</div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <?php
                  }
                  elseif($inip_route == "ProvidusBank" || $inip_route == "AccessBank" || $inip_route == "SterlingBank"){
                  ?>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Account Number</label>
                    <div class="col-sm-6">
                        <input name="account_number" type="text" id="accountNo" onkeydown="fetchbanklist();" class="form-control" placeholder="Bank Account Number" required>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Recipient Bank</label>
                    <div class="col-sm-6">
                        <?php 
                        if($inip_route == "ProvidusBank"){
                        ?>
                          <select name="bank_code"  class="form-control select2" id="bankCode" onchange="fetchbanklist();" required>
                        <?php
                        }
                        elseif($inip_route == "SterlingBank"){
                        ?>
                          <select name="bank_code"  class="form-control select2" id="sterlingBankCode" onchange="fetchsterlingbanklist();" required>
                        <?php
                        }
                        ?>
                          <option value="" selected>Select Bank</option>
                          <?php
                          if($inip_route == "ProvidusBank"){ //ROUTE FOR PROVIDUS BANK
                              
                              try
                              {
                                  $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'providus_endpoint'");
                                  $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
                                  $api_url1 = $fetch_restapi1->api_url;
                                    
                                  $client = new SoapClient($api_url1);
    
                                  $response = $client->GetNIPBanks();
                                
                                  $process = json_decode(json_encode($response), true);
            
                                  $processReturn = $process['return'];
                                    
                                  $process2 = json_decode($processReturn, true);
                                    
                                  $processReturn2 = $process2['banks'];
                                    
                                  $i = 0;
                                    
                                  foreach($processReturn2 as $key){
                                        
                                      echo "<option value=".$processReturn2[$i]['bankCode'].">".$processReturn2[$i]['bankName']." - ".$processReturn2[$i]['bankCode']."</option>";
                                      $i++;
                                        
                                  }
                                
                              }
                              catch( Exception $e )
                              {
                                  // You should not be here anymore
                                  echo $e->getMessage();
                              }
                              
                          }elseif($inip_route == "SterlingBank"){ //ROUTE FOR STERLING BANK
                              
                              require_once '../config/nipBankTransfer_class.php';
                              
                              $search_restapi1 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'sterling_GetBankListReq'");
                              $fetch_restapi1 = mysqli_fetch_object($search_restapi1);
                              $client = $fetch_restapi1->api_url;
                              
                              $data_to_send_server = array(
                                    "Referenceid"=>date("ymi").time(),
                                    "RequestType"=>152,
                                    "Translocation"=>"100,100"
                                );
                                
                                $process = $new->sterlingNIPBankTransfer($data_to_send_server,$client);
                                
                                $processReturn = $process['data']['response'];
                                
                                $process2 = json_decode($processReturn, true);
                                
                                $i = 0;
                                    
                                foreach($process2 as $key){
                                    
                                    echo "<option value=".$process2[$i]['BANKCODE'].">".$process2[$i]['BANKNAME']." - ".$process2[$i]['BANKCODE']."</option>";
                                    $i++;
                                        
                                }
                              
                          }else{
                              //Do Nothing
                          }
                          ?>
                        </select>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>

                  <?php
                  }
                  else{

                    echo "<div align='center' style='font-size:20px;'>Kindly contact the Administrator to Activate this features if needed!!</div>";

                  }
                  ?>

                  <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Beneficiary Name</label>
                    <div class="col-sm-6">
                        <div id="act_numb">------------</div>
                    </div>
                    <label for="" class="col-sm-3 control-label"></label>
                  </div>
            
            <div class="form-group">
                      <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Currency</label>
                      <div class="col-sm-10">
            <select name="currency"  class="form-control select2" required>
              <option value="" selected>Select Currency</option>
              <option value="<?php echo $icurrency; ?>"><?php echo $icurrency; ?></option>
            </select>                 
            </div>
            </div>
			
			 </div>
			 
			  <div align="right">
              <div class="box-footer">
                	<button name="save" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-plus">&nbsp;Proceed</i></button>

              </div>
			  </div>
			  
	<?php
    }
    ?>
			  
		</form> 
		

</div>	
</div>	
</div>
</div>