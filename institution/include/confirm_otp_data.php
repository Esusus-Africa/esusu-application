<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><a href="verify_card.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA1&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_3"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button></a> <i class="fa fa-money"></i> OTP Confirmation</h3>
            </div>
             <div class="box-body">
                 
<?php
	$lid = $_GET['lid'];
	$search_myloaninfo = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'");
	$fetch_myloaninfo = mysqli_fetch_array($search_myloaninfo);
	$mandate_status = $fetch_myloaninfo['mandate_status'];
?>

        <form class="form-horizontal" method="post" enctype="multipart/form-data">
            <!--action="otp.php?id=<?php echo $_GET['id']; ?>&&acn=<?php echo $_GET['acn']; ?>&&lid=<?php echo $_GET['lid']; ?>&&txRef=<?php echo $_GET['txRef']; ?>&&ref=<?php echo $_GET['ref']; ?>"-->

<?php
if(isset($_POST['confirm_otp'])){
    
    //Initiate a new cURL session
    $ch = curl_init();
    
    //REMITAL CREDENTIALS
    $remita_merchantid = $fetch_icurrency->remitaMerchantId;
    $remita_apikey = $fetch_icurrency->remitaApiKey;
    $api_token = $fetch_icurrency->remitaApiToken;

    $lid = $_GET['lid'];
    $search_loan = mysqli_query($link, "SELECT * FROM loan_info WHERE lid = '$lid'");
    $fetch_loan = mysqli_fetch_array($search_loan);
    $requestid = $fetch_loan['request_id'];
    $mandate_id = $fetch_loan['mandate_id'];
    $remitaTransRef = $fetch_loan['remita_rrr'];
    $acn = $fetch_loan['baccount'];
    $otp = mysqli_real_escape_string($link, $_POST['otp']);
    $card = mysqli_real_escape_string($link, $_POST['card']);
    $request_ts = $fetch_loan['request_ts'];
    //$request_ts = date("Y-m-d")."T".date("h:m:s")."+000000";
    
    //DIRECT DEBIT ACTIVATION FEE
    $ddActivationFee = $fetchsys_config['auth_charges'];
    $calc_mywalletBalance = $iassigned_walletbal - $ddActivationFee;

    $concat_param = $remita_apikey.$requestid.$api_token;
    $hash = hash("sha512", $concat_param);

    $search_restapi2 = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'remita'");
    $fetch_restapi2 = mysqli_fetch_object($search_restapi2);
    $url = $fetch_restapi2->api_url;

    $api_url = $url."remita/exapp/api/v1/send/api/echannelsvc/echannel/mandate/validateAuthorization";

    $postdata = array(
        "remitaTransRef" => $remitaTransRef,
        "authParams" => [
            "param1" => "OTP",
            "value" => $otp
            ],
            [
            "param2" => "CARD",
            "value" => $card
            ]
        );

    curl_setopt($ch, CURLOPT_URL, $api_url);
        
    //Set the CURLOPT_RETURNTRANSFER option ton true
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    
    //set the CURLOPT_POST option to true for POST request
    curl_setopt($ch, CURLOPT_POST, TRUE);
                    
    //Set the request data as Array using json_encoded function
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
                    
    //Set custom headers for Content-Type header
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "MERCHANT_ID: $remita_merchantid",
        "API_KEY: $remita_apikey",
        "REQUEST_ID: $requestid",
        "REQUEST_TS: $request_ts",
        "API_DETAILS_HASH: $hash"
        ));
                        
    //Execute cURL request with all previous settings
    $response2 = curl_exec($ch);
    $output2 = json_decode(json_encode($response2), true);
    //$output2 = trim(json_decode(json_encode($response2), true),'jsonp ();');
    
    $result = json_decode($output2, true);
    $currentDateTime = date("Y-m-d h:i:s");
    //print_r($output2);
    //echo "<br><br>";
    //print_r($postdata);
    //echo $api_url;
    
    if($result['statuscode'] === "00"){
        
        $newMandateId = $result['mandateId'];
        
        (($irole != "agent_manager" || $irole != "institution_super_admin" || $irole != "merchant_super_admin") && $isubagent_wallet === "Enabled") ? mysqli_query($link, "UPDATE user SET wallet_balance = '$calc_mywalletBalance' WHERE id = '$iuid'") : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$calc_mywalletBalance' WHERE institution_id = '$institution_id'");
            
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$requestid','self','','$ddActivationFee','Debit','$icurrency','DD_Activation','Response: $icurrency.$ddActivationFee was charged for Direct Debit Activation Request for Loan ID: $lid with Account ID: $acn','successful','$currentDateTime','$iuid','$calc_mywalletBalance','')");

        mysqli_query($link, "UPDATE loan_info SET mandate_id = '$newMandateId' WHERE lid = '$lid'");

        echo "<script>alert('Mandate Activated Successfully'); </script>";
        echo "<script>window.location='https://esusu.app/updateloans.php?id=".$_GET['id']."&&acn=".$acn."&&mid=NDA1&&lid=".$_GET['lid']."&&tab=tab_3'; </script>";
        
    }
    else{
        
        $concat_param3 = $remita_merchantid.$remita_apikey.$requestid;
        $hash3 = hash("sha512", $concat_param3);

        $api_url3 = $url."remita/ecomm/mandate/form/$remita_merchantid/$hash3/$mandate_id/$requestid/rest.reg";
        
        (($irole != "agent_manager" || $irole != "institution_super_admin" || $irole != "merchant_super_admin") && $isubagent_wallet === "Enabled") ? mysqli_query($link, "UPDATE user SET wallet_balance = '$calc_mywalletBalance' WHERE id = '$iuid'") : mysqli_query($link, "UPDATE institution_data SET wallet_balance = '$calc_mywalletBalance' WHERE institution_id = '$institution_id'");
            
        mysqli_query($link, "INSERT INTO wallet_history VALUE(null,'$institution_id','$requestid','self','','$ddActivationFee','Debit','$icurrency','DD_Activation','Response: $icurrency.$ddActivationFee was charged for Direct Debit Activation Request for Loan ID: $lid with Account ID: $acn','successful','$currentDateTime','$iuid','$calc_mywalletBalance','')");
        
        mysqli_query($link, "UPDATE loan_info SET mandate_status = 'InProcess' WHERE lid = '$lid'");
        
        echo "<script>alert('Mandate Request Created Successfully....Click Okay to Proceed'); </script>";
        echo "<script>window.open('$api_url3', '_blank'); </script>";
        echo "<script>window.location='confirm_otp.php?id=".$_GET['id']."&&acn=".$acn."&&mid=NDA1&&lid=".$_GET['lid']."&&tab=tab_3'; </script>";
        
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
            <p><a href="updateloans.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_GET['acn']; ?>&&mid=NDA1&&lid=<?php echo $_GET['lid']; ?>&&tab=tab_3"><button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><i class="fa fa-mail-reply-all"></i>&nbsp;Click Here</button></a> to go back</p>
            
        </div>
    
    <?php
    }
    else{
    ?>

            <div class="box-body">
                    
                <div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'blue' : $myaltrow['alternate_color']; ?>">Please validate with the OTP sent to your mobile phone with the last 4 digit of your ATM card.</div>
                    
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">OTP Code</label>
                  <div class="col-sm-10">
                  <input name="otp" type="text" class="form-control" placeholder="Enter OTP Code">
                  
                  </div>
                  </div>
                  
                  <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">Card Last 4 Digit</label>
                  <div class="col-sm-10">
                  <input name="card" type="text" class="form-control" placeholder="Enter your ATM card last 4 digit" maxlength="4">
                  
                  </div>
                  </div>
                    
            </div>
                
            <div align="right">
              <div class="box-footer">
                	<button name="confirm_otp" type="submit" class="btn bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?> btn-flat"><i class="fa fa-plus">&nbsp;Confirm</i></button>

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